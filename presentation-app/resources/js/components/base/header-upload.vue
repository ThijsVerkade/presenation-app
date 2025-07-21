<template>
<div v-if="headerImage?.url" class="u-relative">
    <img :src="headerImage?.url" alt="header image" class="u-w-full u-object-cover u-rounded-md">
    <Button variant="default" size="sm" icon="fal fa-trash-alt" class="u-absolute u--top-2 u--right-2" @click="handleHeaderImageRemove" />
</div>

<div v-else-if="videoData?.playback_id && videoData?.status === 'ready'" class="u-w-full u-rounded-md u-overflow-hidden u-aspect-video u-relative">
    <mux-player
        class="u-w-full u-h-full"
        controls="none"
        stream-type="on-demand"
        accent-color="var(--neutral-500)"
        :playback-id="videoData.playback_id"
    />
    <Button variant="default" size="sm" icon="fal fa-trash-alt" class="u-absolute u--top-2 u--right-2" @click="handleVideoRemove" />
</div>

<div v-else-if="videoData?.playback_id && videoData?.status !== 'ready'" class="u-bg-[black] u-text-white u-rounded-md u-p-2 u-aspect-video u-relative u-flex u-flex-col u-items-center u-justify-center">
    <div class="u-w-1/2 u-text-center">
        <i class="fal fa-spinner-third fa-spin"></i>
        <h3 class="u-text-lg u-font-bold">{{ $t('video.video_is_processing') }}</h3>
        <p class="u-mt-4">
            {{ $t('video.video_is_processing_description') }}
        </p>
    </div>
    <Button variant="default" size="sm" icon="fal fa-trash-alt" class="u-absolute u--top-2 u--right-2" @click="handleVideoRemove" />
</div>

<InputDropUpload
    v-else
    label_upload="Upload image or video"
    size="small"
    type="image_video"
    @uploadedImage="handleHeaderImageUpdate"
    @uploadedVideo="handleVideoUpload"
/>
</template>
<script setup lang="ts">
import axios from 'axios';
import InputDropUpload from "@components/base/input-drop-upload.vue";
import Button from "@components/base/button.vue";
import { ref, onMounted, nextTick } from "vue";

interface Props {
    headerImage: any;
    videoData: any;
}

const props = defineProps<Props>();
const emit = defineEmits(['headerImageUpdate', 'videoUpload', 'headerImageRemove', 'videoRemove']);

onMounted(() => {
    checkVideoStatus();
});

const checkVideoStatus = async () => {
    if (props.videoData?.playback_id && props.videoData?.status !== 'ready') {
       const videoStatus = await getVideoStatus(props.videoData.asset_id);

       if (videoStatus.status === 'ready') {
            props.videoData.status = 'ready';
       }
    }
};

const getVideoStatus = async (asset_id) => {
    while (true) {
        const response = await axios.get(`/api/mux/asset/${asset_id}`);

        if (response.data.status === 'ready') {
            return response.data;
        }

        await new Promise(resolve => setTimeout(resolve, 15000));
    }
}

const handleHeaderImageUpdate = (file: UploadFileProps) => {
    emit('headerImageUpdate', file);
};

const handleVideoUpload = (video: MuxVideoResponse) => {
    emit('videoUpload', video);

    nextTick(() => {
        checkVideoStatus();
    });
};

const handleHeaderImageRemove = () => {
    emit('headerImageRemove');
};

const handleVideoRemove = () => {
    emit('videoRemove');
};


</script>