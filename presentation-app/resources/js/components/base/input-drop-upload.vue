<template>
    <label class="c-input-drop-upload" :class="{'is-active': dragging, 'is-small': props.size === 'small'}" v-if="!file">
        <div class="c-input-drop-upload__dropzone"
            @dragover="dragover"
            @dragleave="dragleave"
            @drop="drop"
        >

            <div v-if="loading">
                <i class="fas fa-spinner fa-spin c-input-drop-upload__icon"></i>
                <small v-if="uploadProgress < 100"><span v-if="uploadProgress > 0">{{ uploadProgress }}%</span> uploading...</small>
                <small v-else-if="preparing">Preparing...</small>
            </div>
            <div v-else>
                <i :class="[props.icon, 'c-input-drop-upload__icon']"></i>
                <small>{{ props.label_upload }}</small>
            </div>

        </div>
        <input type="file" ref="fileRef" :accept="accepts[props.type]"
         @change="onChange" class="c-input-drop-upload__input">
    </label>
</template>

<script setup lang="ts">
import axios from 'axios';
import { ref } from 'vue';
import { mediaService } from '@services/mediaService';
import type { UploadFileProps } from '@/types/media';
import Mux from '@helpers/mux';
import type { MuxAssetResponse, MuxVideoResponse } from '@interfaces/input-drop-upload';

interface Props {
  icon?: string;
  label_upload?: string;
  accept?: string;
  type?: 'image' | 'video'  | 'image_video' | 'document';
  size?: string;
}

const props = withDefaults(defineProps<Props>(), {
  icon: 'fal fa-folder-open',
  label_upload: '',
  accept: 'image/jpeg,image/jpg,image/png,video/mp4',
  type: 'document',
  size: 'default'
});

const accepts = {
    'image': 'image/jpeg,image/jpg,image/png',
    'video': 'video/*,video/mp4,video/avi,video/webm,video/x-ms-wmv,video/x-flv,video/mpeg,video/quicktime,video/x-m4v',
    'image_video': 'image/jpeg,image/jpg,image/png,video/*,video/mp4,video/avi,video/webm,video/x-ms-wmv,video/x-flv,video/mpeg,video/quicktime,video/x-m4v',
    'document': 'application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation'
}

const emit = defineEmits<{
  (e: 'uploadedImage', file: UploadFileProps): void
  (e: 'uploadedVideo', videoUpload: MuxVideoResponse): void
}>();

const loading = ref<boolean>(false);
const preparing = ref<boolean>(false);
const dragging = ref<boolean>(false);
const uploadProgress = ref<number>(0);
const fileRef = ref<HTMLInputElement | null>(null);

const onChange = async () => {
  if (!fileRef.value?.files?.[0]) return;

  loading.value = true;
  try {
    const file = fileRef.value.files[0];
    let fileType = 'document';

    // Determine file type based on MIME type
    if (file.type.startsWith('image/')) {
      fileType = 'image';
    } else if (file.type.startsWith('video/')) {
        preparing.value = true;
        let videoUpload: MuxAssetResponse = await Mux.store(file, {
            progress: progress => {
                uploadProgress.value = parseInt(progress.toFixed(0));
            },
        });

        const asset = await getVideoStatus(videoUpload.asset_id);

        preparing.value = false;

        emit('uploadedVideo', asset);
        return asset;
    }

    const uploadedFile = await mediaService.upload(file, fileType);

    emit('uploadedImage', uploadedFile);

    if (fileRef.value) fileRef.value.value = '';
  } finally {
    loading.value = false;
  }
};

const getVideoStatus = async (asset_id) => {
    while (true) {
        const response = await axios.get(`/api/mux/asset/${asset_id}`);

        return response.data;
    }
}

const dragover = (event: DragEvent) => {
  event.preventDefault();
  const target = event.currentTarget as HTMLElement;
  if (!target.classList.contains('is-active')) {
    target.classList.remove('is-active');
    target.classList.add('is-active');
  }
};

const dragleave = (event: DragEvent) => {
  const target = event.currentTarget as HTMLElement;
  target.classList.remove('is-active');
};

const drop = (event: DragEvent) => {
  event.preventDefault();
  if (!event.dataTransfer || !fileRef.value) return;

  fileRef.value.files = event.dataTransfer.files;
  onChange();

  const target = event.currentTarget as HTMLElement;
  target.classList.add('bg-gray-100');
  target.classList.remove('bg-green-300');
};
</script>
