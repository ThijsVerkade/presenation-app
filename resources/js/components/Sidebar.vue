<script setup lang="ts">
import Button from "@components/base/button.vue";
import ImageCardWithStatus from "@components/base/ImageCardWithStatus.vue";
import Draggable from 'vuedraggable';
import {Link, router} from '@inertiajs/vue3';
import {route} from "ziggy-js";
import {createApiCall} from "@helpers/apiHelper";
import {ref} from "vue";

const props = defineProps<{
    slides: Array<{
        id: number;
        is_active: boolean;
    }>;
    slide?: {
        id: number;
        is_active: boolean;
    };
}>();

const slides = ref([...props.slides]);

const apiCall = createApiCall();
const createSlide = () => {
    apiCall(
        'post',
        route('admin.slides.store'),
        {},
        'Display updated successfully',
        'Failed to update display',
    );
    router.visit(window.location.href, {
        replace: true,
        preserveScroll: false,
        preserveState: false,
    })
};

const onDragEnd = (event) => {
    const orderedIds =  slides.value.map(item => item.id);
    apiCall(
        'post',
        route('admin.slides.reorder'),
        {items: orderedIds},
        'Display order updated successfully',
        'Failed to update display order',
    );
};

</script>

<template>
    <div class="u-fixed u-bg-white u-z-20 u-transition-all md:u-relative u-top-0 u-left-0 u-bottom-0 u-right-0 u-w-full md:u-max-w-[300px] md:u-h-screen md:u-overflow-y-auto md:u-translate-x-0">
        <div class="u-flex u-items-center u-p-6">
            <img src="/images/logo.svg" alt="Logo" class="h-10" />
            <h1 class="u-text-sm u-font-semibold u-mt-0 u-max-w-[600px] u-ml-2 u-word-break-all">Presentation</h1>
        </div>
        <div class="u-flex u-justify-between u-px-6">
            <Button
                icon="fal fa-display"
                variant="secondary"
                size="md"
                label="Display"
                @click=""
                :href="route('admin.displays')"/>
            <Button
                v-if="slide"
                icon="fal fa-play"
                variant="primary"
                size="md"
                label="Play"
                :href="route('admin.slides.activate', { slide: slide.id })"
                />
        </div>
        <div class="u-mt-4 u-border-t u-border-neutral-200 u-pt-4 u-px-6">
            <h2 class="u-text-neutral-400 u-text-sm u-font-medium u-pb-4" >Slides</h2>
            <Draggable
                v-model="slides"
                handle=".drag-handle"
                item-key="id"
                class="u-w-full"
                :animation="200"
                @end="onDragEnd"
            >
                <template #item="{ element }">
                    <div class="u-flex u-pb-4">
                        <i class="fas fa-grip-vertical u-text-neutral-300 u-w-3 u-h-4 u-mr-2 drag-handle u-my-auto u-cursor-pointer" />
                        <Link :href="route('admin.slides', {slide: element.id})" class="u-flex-grow" :preserve-state="false" :preserve-scroll="true">
                            <ImageCardWithStatus :statusLabel="element.is_active ? 'active' : 'draft'"
                                                 class="u-cursor-pointer"
                                                 :class="slide?.id === element.id ? 'u-border-neutral-700' : ''"

                            />
                        </Link>
                    </div>
                </template>
            </Draggable>
        </div>
        <div class="u-flex u-items-center u-mb-4 u-px-4 u-gap-4">
            <div class="u-border-t u-border-neutral-200 u-flex-1"></div>
            <div class="u-flex-1">
                <Button
                    icon="fal fa-plus"
                    label="Add slide"
                    variant="default"
                    size="sm"
                    class="u-w-full u-flex-1"
                    @click="() => createSlide()"
                />
            </div>
            <div class="u-border-t u-border-neutral-200 u-flex-1"></div>
        </div>
    </div>
</template>

<style scoped>

</style>
