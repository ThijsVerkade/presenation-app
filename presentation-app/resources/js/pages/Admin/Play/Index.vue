<template>
    <Base>
        <div class="u-flex u-justify-between">
        <h1 class="u-text-xl u-font-normal u-text-neutral-800 u-mb-5 ">Open display</h1>
        <Button
            icon="fal fa-display"
            variant="secondary"
            size="md"
            label="Manage presentation"
            :href="route('admin.slides', {slide: slide.id})" />
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
console.log(props)
</script>

<style scoped>
</style>
