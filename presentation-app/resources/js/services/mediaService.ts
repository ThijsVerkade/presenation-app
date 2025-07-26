import { createApiCall } from '@helpers/apiHelper';
import type {UploadFileProps} from "@/types/media";
import { prepareForm } from '@helpers/prepareForm';

const apiCall = createApiCall();

export const mediaService = {
    async upload(file: File, type: string): Promise<UploadFileProps> {
        let formData;

        if (type === 'document') {
            formData = prepareForm({
                temporary: true,
                document: file
            });
        } else {
            formData = prepareForm({
                temporary: true,
                image: file
            });
        }

        const response = await apiCall<{file: UploadFileProps}>(
            'post',
            `/api/upload`,
            formData,
            undefined,
            'Failed to upload file'
        );

        return response.file;
    },
};
