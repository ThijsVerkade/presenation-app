<template>
    <div class="u-w-full u-h-full u-overflow-hidden">
        <div v-if="mediaUrl"  class="u-w-full u-h-full u-overflow-hidden">
            <img
                v-if="mediaType === 'image'"
                :src="mediaUrl"
                alt="Slide image"
            />
            <video
                v-else-if="mediaType === 'video'"
                :src="mediaUrl"
                controls
                autoplay
                loop
            ></video>
        </div>
        <div v-else class="no-slide">
            <h2>Waiting for active slide...</h2>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';

// ✅ TEMPORARY INLINED SETUP
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

const echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: false,
    enabledTransports: ['ws', 'wss'],
});

const props = defineProps<{
    display: {
        data: {
            id: number;
            name: string;
            slug: string;
            width: number;
            height: number;
            order: number;
            media: string;
        };
    };
    slides: {
        id: number;
        is_active: boolean;
    };
}>();

const currentSlide = ref(null);
const mediaUrl = ref(null);
const mediaType = ref(null);

const getMediaType = (url: string | null) => {
    const extension = url?.split('.').pop()?.toLowerCase();
    if (!extension) return null;

    const imageTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    const videoTypes = ['mp4', 'webm', 'ogg'];

    if (imageTypes.includes(extension)) return 'image';
    if (videoTypes.includes(extension)) return 'video';
    return null;
};

onMounted(() => {
    echo.channel(`display.${props.display.data.slug}`)
        .listen('SlideActivated', (event: any) => {
            currentSlide.value = event.slide;

            const url = event.media_paths?.['main'] || null;
            console.log(url)
            mediaUrl.value = url;
            mediaType.value = getMediaType(url);
        });

    if (props.display.data.media) {
        mediaUrl.value = props.display.data.media;
        mediaType.value = getMediaType(props.display.data.media);
    }
});

onBeforeUnmount(() => {
    echo.leave(`display.${props.display.data.slug}`);
});
</script>
