<template>
    <Base>
        <div class="u-flex u-justify-between u-items-center">
            <div>
                <h1 class="u-text-xl u-font-normal u-text-neutral-800 u-mb-2">Open display</h1>

                <!-- Status pill -->
                <div
                    v-if="currentSlideId !== null"
                    class="u-inline-flex u-items-center u-gap-2 u-text-sm u-px-2 u-py-1 u-rounded u-bg-neutral-100 u-text-neutral-700"
                >
                    <span class="u-inline-block u-w-2 u-h-2 u-rounded-full u-bg-green-500"></span>
                    <span>Live slide ID: {{ currentSlideId }}</span>
                </div>
                <div
                    v-else
                    class="u-inline-flex u-items-center u-gap-2 u-text-sm u-px-2 u-py-1 u-rounded u-bg-amber-100 u-text-amber-700"
                >
                    <span class="u-inline-block u-w-2 u-h-2 u-rounded-full u-bg-amber-500"></span>
                    <span>No presentation running</span>
                </div>
            </div>

            <div class="u-flex u-justify-between u-gap-4 u-items-center">
                <div
                    class="u-bg-white u-m-auto u-p-1"
                    style="box-shadow: 0 0.125rem 0.25rem rgba(41, 37, 36, 0.1); border-radius: 0.5rem;"
                >
                    <Button class="u-bg-white" icon="fal fa-arrow-left"  size="sm" variant="default" @click="goToPreviousSlide" />
                    <Button v-if="currentSlideId === null" class="u-bg-white" icon="fal fa-play" size="sm" variant="default" @click="startFirstSlide" />
                    <Button v-else class="u-bg-white" icon="fal fa-stop" size="sm" variant="default" @click="stopPresentation" />
                    <Button class="u-bg-white" icon="fal fa-arrow-right" size="sm" variant="default" @click="goToNextSlide" />
                </div>

                <Button
                    icon="fal fa-display"
                    variant="secondary"
                    size="md"
                    label="Manage presentation"
                    :href="slide ? route('admin.slides', { slide: slide.id }) : undefined"
                    :disabled="!slide"
                />
            </div>
        </div>

        <div class="u-w-full u-flex u-gap-2 u-mt-4">
            <Link
                v-for="element in displays.data"
                :key="element.id"
                :href="`/d/${element.slug}`"
                target="_blank"
            >
                <ScreenCard
                    :name="element.name"
                    :width="element.width"
                    :height="element.height"
                    :main-item="element.order === 1"
                >
                    <template #footer>
                        <div class="u-text-[12px] u-text-neutral-400">
                            {{ `https://carux.local/d/${element.slug}` }}
                        </div>
                    </template>
                </ScreenCard>
            </Link>
        </div>
    </Base>
</template>

<script setup lang="ts">
import Base from "@layouts/base.vue";
import ScreenCard from "@components/base/ScreenCard.vue";
import Button from "@components/base/button.vue";
import { Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { createApiCall } from "@helpers/apiHelper";
import { onMounted, onBeforeUnmount, reactive, ref } from "vue";

import Echo from "laravel-echo";
import Pusher from "pusher-js";
window.Pusher = Pusher;

const echo = new Echo({
    broadcaster: "reverb",
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: false,
    enabledTransports: ["ws", "wss"],
});

type Display = {
    id: number,
    slug: string,
    name: string,
    width: number,
    height: number,
    order: number,
    asset_id?: number,
    media?: string,
};

type DisplaysResource = { data: Display[] };
type SlideDTO = { id: number; is_active: boolean };

const props = defineProps<{
    slide: SlideDTO | null,
    displays: DisplaysResource,
}>();

const { slide, displays } = props;

const apiCall = createApiCall();

const currentSlideId = ref<number | null>(slide ? slide.id : null);
const mediaByDisplay = reactive<Record<string, string>>({});

onMounted(() => {
    displays.data.forEach(d => {
        const channel = `display.${d.slug}`;
        echo.channel(channel).listen("SlideActivated", (event: any) => {
            if (event?.slide_id) {
                currentSlideId.value = event.slide_id;
            }
            if (event?.media_paths && event.media_paths[d.slug] !== undefined) {
                mediaByDisplay[d.slug] = event.media_paths[d.slug] || "";
            }
        });

        if (d.media) mediaByDisplay[d.slug] = d.media;
    });
});

onBeforeUnmount(() => {
    displays.data.forEach(d => echo.leave(`display.${d.slug}`));
});

const startFirstSlide = async () => {
    await apiCall("post", route("admin.play.start"), {}, "Presentation started", "Failed to start presentation");
};

const goToPreviousSlide = async () => {
    await apiCall("post", route("admin.play.previous"), {}, "Slide loaded successfully", "Failed to load slide");
};

const goToNextSlide = async () => {
    await apiCall("post", route("admin.play.next"), {}, "Slide loaded successfully", "Failed to load slide");
};

const stopPresentation = async () => {
    await apiCall("post", route("admin.play.stop"), {}, "Presentation stopped", "Failed to stop presentation");
    currentSlideId.value = null;
}
</script>

<style scoped>
</style>
