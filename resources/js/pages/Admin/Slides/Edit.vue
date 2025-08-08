<template>
    <Base :slides="slides.data" :slide="slide" :displays="displays.data">
        <div class="u-gap-2 u-ml-auto u-flex u-justify-end">
            <Select
                :autocomplete="false"
                mode="single"
                :options="[
          { label: 'Active', value: 'active' },
          { label: 'Inactive', value: 'inactive' },
          { label: 'Archived', value: 'archived' }
        ]"
                size="md"
                @change="() => console.log('todo')"
            />
            <Button icon="fal fa-trash-alt" @click="deleteSlide()" />
        </div>

        <h1 v-if="firstDisplay" class="u-text-sm u-font-normal u-text-neutral-800 u-mb-5 u-mt-0">{{ firstDisplay.name }}</h1>
        <div v-if="firstDisplay" class="u-h-[500px]">
            <div
                v-if="firstDisplay.media"
                class="u-relative u-bg-neutral-50 u-border u-border-neutral-200 u-rounded-[8px]"
            >
                <Button
                    icon="fal fa-edit"
                    variant="default"
                    size="sm"
                    class="u-absolute u--top-2 u--right-2"
                    @click="deleteSlideAsset(firstDisplay.asset_id)"
                />

                <template v-if="mediaType === 'image'">
                    <img
                        :src="firstDisplay.media"
                        class="u-object-fill u-w-100 u-h-full u-max-w-full u-max-h-[500px] u-rounded-[8px]"
                    />
                </template>
                <template v-else-if="mediaType === 'video'">
                    <video
                        controls
                        :src="firstDisplay.media"
                        class="u-object-fill u-w-100 u-h-full u-max-w-full u-max-h-[500px] u-rounded-[8px]"
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
                @uploaded="(file) => handleUploaded(firstDisplay.id, file)"
            />
        </div>

        <h1 class="u-text-sm u-font-normal u-text-neutral-800 u-mt-5 u-mb-5">Displays</h1>
        <div class="u-w-full u-flex u-flex-wrap u-gap-2 u-mb-4">
            <div v-for="display in otherDisplays" :key="display.id" class="u-w-[calc(25%-0.5rem)]">
                <div v-if="display.media" class="u-relative u-bg-neutral-50 u-border u-border-neutral-200 u-rounded-[8px]">
                    <Button
                        icon="fal fa-edit"
                        variant="default"
                        size="sm"
                        class="u-absolute u--top-2 u--right-2"
                        @click="deleteSlideAsset(display.asset_id)"
                    />

                    <template v-if="getMediaType(display.media) === 'image'">
                        <img
                            :src="display.media"
                            class="u-object-cover u-max-w-full u-max-h-[300px] u-rounded-[8px]"
                        />
                    </template>
                    <template v-else-if="getMediaType(display.media) === 'video'">
                        <video
                            controls
                            :src="display.media"
                            class="u-object-cover u-max-w-full u-max-h-[300px] u-rounded-[8px]"
                        />
                    </template>
                </div>

                <InputDropUpload
                    v-else
                    label_upload="Upload an image or video"
                    size="small"
                    type="image_video"
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
import { route } from 'ziggy-js';
import { router } from '@inertiajs/vue3';
import { prepareForm } from '@helpers/prepareForm';

const { alertDialog } = useAlert();
const apiCall = createApiCall();

const props = defineProps<{
    slide: {
        id: number;
        is_active: boolean;
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
            first_media?: string;
        }[];
    };
}>();

const firstDisplay = computed(() => props.displays.data[0]);
const otherDisplays = computed(() => props.displays.data.slice(1));

const mediaType = ref<'image' | 'video' | null>(null);

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

const handleUploaded = (displayId: number, file: UploadFileProps) => {
    const formData = prepareForm({
        temporary: true,
        media: file,
        display_id: displayId,
        slide_id: props.slide.id
    });

    apiCall(
        'post',
        route('admin.slide-assets.store'),
        formData,
        'Display updated successfully',
        'Failed to update display'
    );

    router.visit(window.location.href, {
        replace: true,
        preserveScroll: false,
        preserveState: false,
    })
};

const deleteSlide = async () => {
    const result = await alertDialog({
        title: 'Delete Slide',
        message: 'Are you sure you want to delete this slide?',
        showCancelButton: true
    });

    if (result) {
        // TODO: actual delete logic
    }

    router.visit(window.location.href, {
        replace: true,
        preserveScroll: false,
        preserveState: false,
    })
};

const deleteSlideAsset = async (assetId: number) => {
    const result = await alertDialog({
        title: 'Delete Slide',
        message: 'Are you sure you want to delete this asset?',
        showCancelButton: true
    });

    if (!result) return;

    apiCall(
        'delete',
        route('admin.slide-assets.destroy', { id: assetId }),
        {},
        'Display updated successfully',
        'Failed to update display'
    );

    router.visit(window.location.href, {
        replace: true,
        preserveScroll: false,
        preserveState: false,
    })
};
</script>
