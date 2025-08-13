<template>
    <Base :slides="slides.data" :slide="slide" :displays="displays.data">
        <div class="u-gap-2 u-ml-auto u-flex u-justify-end">
            <InputBase
                v-model="slide.duration_seconds"
                :addon="{
                    label: 'Duration (seconds)',
                    position: 'start',
                }"
                size="lg"
                type="number"
                placeholder="Enter auto play time"
                @change="() => updateSlide(slide.is_active, slide.duration_seconds)"
            />
            <Select
                :autocomplete="false"
                mode="single"
                v-model="slide.is_active"
                :options="[
          { label: 'Active', value: 1 },
          { label: 'Draft', value: 0 }
        ]"
                size="md"
                @change="(option) => updateSlide(option, slide.duration_seconds)"
            />
            <Button icon="fal fa-trash-alt" @click="deleteSlide()" />
        </div>

        <h1 v-if="firstDisplay" class="u-text-sm u-font-normal u-text-neutral-800 u-mb-5 u-mt-0">{{ firstDisplay.name }}</h1>
        <div v-if="firstDisplay" class="u-h-[500px]">
            <div
                v-if="firstDisplay.media"
                class="u-relative u-bg-neutral-50 u-border u-border-neutral-200 u-rounded-[8px] u-h-full u-overflow-hidden"
            >
                <Button
                    icon="fal fa-edit"
                    variant="default"
                    size="sm"
                    class="u-absolute u--top-2 u--right-2 u-z-10"
                    @click="deleteSlideAsset(firstDisplay.asset_id)"
                />

                <template v-if="mediaType === 'image'">
                    <img
                        :src="firstDisplay.media"
                        class="u-absolute u-inset-0 u-w-full u-h-full u-object-cover u-object-center"
                        alt=""
                    />
                </template>

                <template v-else-if="mediaType === 'video'">
                    <video
                        controls
                        :src="firstDisplay.media"
                        class="u-absolute u-inset-0 u-w-full u-h-full u-object-cover u-object-center"
                        playsinline
                    />
                </template>
            </div>

            <InputDropUpload
                v-else
                icon="fal fa-upload"
                label_upload="Upload an image or video"
                size="big"
                class="u-h-full"
                type="image_video"
                :preparing="isPreparing(firstDisplay.id)"
                @uploaded="(file) => handleUploaded(firstDisplay.id, file)"
            />
        </div>

        <h1 class="u-text-sm u-font-normal u-text-neutral-800 u-mt-5 u-mb-5">Displays</h1>
        <div class="u-w-full u-flex u-flex-wrap u-gap-2 u-mb-4">
            <div v-for="display in otherDisplays" :key="display.id" class="u-w-[calc(25%-0.5rem)]">
                <div
                    v-if="display.media"
                    class="u-relative u-bg-neutral-50 u-border u-border-neutral-200 u-rounded-[8px] u-h-[300px] u-overflow-hidden"
                >
                    <Button
                        icon="fal fa-edit"
                        variant="default"
                        size="sm"
                        class="u-absolute u--top-2 u--right-2 u-z-10"
                        @click="deleteSlideAsset(display.asset_id)"
                    />

                    <template v-if="getMediaType(display.media) === 'image'">
                        <img
                            :src="display.media"
                            class="u-absolute u-inset-0 u-w-full u-h-full u-object-cover u-object-center"
                            alt=""
                        />
                    </template>

                    <template v-else-if="getMediaType(display.media) === 'video'">
                        <video
                            controls
                            :src="display.media"
                            class="u-absolute u-inset-0 u-w-full u-h-full u-object-cover u-object-center"
                            playsinline
                        />
                    </template>
                </div>

                <InputDropUpload
                    v-else
                    label_upload="Upload an image or video"
                    size="small"
                    type="image_video"
                    :preparing="isPreparing(display.id)"
                    @uploaded="(file) => handleUploaded(display.id, file)"
                />
                <h1 class="u-text-sm u-font-normal u-text-neutral-800 u-mt-5">{{ display.name }}</h1>
            </div>
        </div>
    </Base>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import Base from '@layouts/base.vue';
import InputDropUpload from '@components/base/input-drop-upload.vue';
import Select from '@components/base/select.vue';
import Button from '@components/base/button.vue';
import { useAlert } from '@composables/useAlert';
import type { UploadFileProps } from '@/types/media';
import { createApiCall } from '@helpers/apiHelper';
import { router } from '@inertiajs/vue3';
import { prepareForm } from '@helpers/prepareForm';
import InputBase from "@components/base/input-base.vue";

const { alertDialog } = useAlert();
const apiCall = createApiCall();

const props = defineProps<{
    slide: {
        id: number;
        is_active: boolean;
        autoplay_time?: number | string;
    };
    displays: {
        data: {
            id: number;
            name: string;
            width: number;
            height: number;
            order: number;
            asset_id?: number;
            media?: string;
        }[];
    };
    slides: {
        data: {
            id: number;
            is_active: boolean;
            duration_seconds: boolean;
            first_media?: string;
        }[];
    };
}>();

const firstDisplay = computed(() => props.displays.data[0]);
const otherDisplays = computed(() => props.displays.data.slice(1));

const mediaType = ref<'image' | 'video' | null>(null);

const preparing = ref<boolean>(false); // keep if you use it elsewhere
const preparingByDisplay = ref<Record<number, boolean>>({});

const setPreparing = (id: number, val: boolean) => {
    preparing.value = val;
    preparingByDisplay.value = { ...preparingByDisplay.value, [id]: val };
};

const isPreparing = (id: number) => !!preparingByDisplay.value[id];

const getMediaType = (url: string | null) => {
    const extension = url?.split('.').pop()?.toLowerCase();
    if (!extension) return null;

    const imageTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    const videoTypes = ['mp4', 'webm', 'ogg'];

    if (imageTypes.includes(extension)) return 'image';
    if (videoTypes.includes(extension)) return 'video';
    return null;
};

watch(
    () => firstDisplay.value?.media,
    (media) => {
        mediaType.value = getMediaType(media || null);
    },
    { immediate: true }
);

const handleUploaded = async (displayId: number, file: UploadFileProps) => {
    setPreparing(displayId, true);
    try {
        const formData = prepareForm({
            temporary: true,
            media: file,
            display_id: displayId,
            slide_id: props.slide.id
        });

        router.post(route('admin.slide-assets.store'), formData)

    } finally {
        setPreparing(displayId, false);
    }
};

const updateSlide = async (is_active: number, duration_seconds: number) => {
    await apiCall(
        'patch',
        route('admin.slides.edit', { id: props.slide.id }),
        {
            is_active,
            duration_seconds
        },
        'Display updated successfully',
        'Failed to update display'
    );

    router.visit(window.location.href, {
        replace: true,
        preserveScroll: false,
        preserveState: false,
    });
};

const deleteSlide = async () => {
    const result = await alertDialog({
        title: 'Delete Slide',
        message: 'Are you sure you want to delete this slide?',
        showCancelButton: true
    });

    if (result) {
        apiCall(
            'delete',
            route('admin.slides.destroy', { id: props.slide.id }),
            {},
            'Slide deleted successfully',
            'Failed to delete slide'
        );
    }

    router.visit(route('admin.displays'));
};

const deleteSlideAsset = async (assetId: number) => {
    const result = await alertDialog({
        title: 'Delete Slide',
        message: 'Are you sure you want to delete this asset?',
        showCancelButton: true
    });

    if (!result) return;

    router.post(route('admin.slide-assets.destroy', { id: assetId }));
};
</script>
