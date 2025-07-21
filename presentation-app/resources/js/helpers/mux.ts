import axios from 'axios'
import * as UpChunk from '@mux/upchunk';
import type { MuxAssetResponse } from '@interfaces/input-drop-upload';

interface MuxOptions {
    signedStorageUrl?: string;
    mp4_support?: boolean;
    progress?: (progress: number) => void;
}

interface UploadResponse {
    url: string;
    id: string;
}

class Mux {
    async store(file: File, options: MuxOptions = {}): Promise<MuxAssetResponse> {
        const response = await axios.post<{data: UploadResponse}>(
            options.signedStorageUrl ?? '/api/mux/signed-storage-url',
            {
                mp4_support: options.mp4_support
            }
        );

        if (typeof options.progress === 'undefined') {
            options.progress = () => {};
        }

        const upload = UpChunk.createUpload({
            endpoint: response.data.data.url,
            file: file,
            chunkSize: 5120,
        });

        upload.on('error', (err: { detail: any }) => {
            console.error('💥 🙀', err.detail);
        });

        upload.on('progress', (progress: { detail: number }) => {
            options.progress?.(progress.detail);
            console.log('Uploaded', progress.detail, 'percent of this file.');
        });

        upload.on('success', () => {
            console.log("Wrap it up, we're done here. 👋");
        });

        const data = await this.getUploadStatus(response.data.data.id);

        return data;
    }

    private async getUploadStatus(id: string): Promise<MuxAssetResponse> {
        while (true) {
            const response = await axios.post<{data: MuxAssetResponse}>('/api/mux/signed-storage-url', {
                'upload_id': id
            });

            const data = response.data.data;

            if (data.status === 'asset_created') {
                return data;
            }

            await new Promise(resolve => setTimeout(resolve, 1000));
        }
    }
}

export default new Mux();