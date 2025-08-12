<template>
    <Base>
        <div class="u-flex u-justify-between">
        <h1 class="u-text-xl u-font-normal u-text-neutral-800 u-mb-5 ">Open display</h1>
            <div class="u-flex u-justify-between u-gap-4">
                <div class="u-bg-white u-m-auto u-p-1 " style="box-shadow: 0 0.125rem 0.25rem rgba(41, 37, 36, 0.1); border-radius: 0.5rem;">
                    <Button
                        class="u-bg-white"
                        icon="fal fa-arrow-left"
                        size="sm"
                        @click="() => goToPreviousSlide()"
                        variant="default"
                    />
                    <Button
                        class="u-bg-white"
                        icon="fal fa-arrow-right"
                        size="sm"
                        @click="() => goToNextSlide()"
                        variant="default"
                    />
                </div>
            <Button
            icon="fal fa-display"
            variant="secondary"
            size="md"
            label="Manage presentation"
            :href="route('admin.slides', {slide: slide.id})" />
            </div>
        </div>
        <div
            class="u-w-full u-flex u-gap-2 u-mb-4">
            <Link  v-for="element in displays.data" :href="`/d/${element.slug}`" target="_blank">
                <ScreenCard
                    :key="element.id"
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
import { ref } from "vue";
import Button from "@components/base/button.vue";
import {Link, router} from '@inertiajs/vue3';
import {route} from "ziggy-js";
import {createApiCall} from "@helpers/apiHelper";

const props = defineProps<{
    slide: {
        id: number;
        is_active: boolean;
    },
    displays: {
        data: {
            id: number;
            name: string;
            width: number;
            height: number;
            order: number;
            asset_id? : number;
            media? : string;
        }[]
    };
}>();


const apiCall = createApiCall();

const goToPreviousSlide = async () => {
    apiCall(
        'post',
        route('admin.slides.previous'),
        {},
        'Slide loaded successfully',
        'failed to load slide',
    );
};
const goToNextSlide = async () => {
    apiCall(
        'post',
        route('admin.slides.next'),
        {},
        'Slide loaded successfully',
        'failed to load slide',
    );
};
</script>

<style scoped>
</style>
