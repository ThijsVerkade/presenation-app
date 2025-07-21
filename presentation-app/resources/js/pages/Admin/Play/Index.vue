<template>
    <Base>
        <div class="u-flex u-justify-between">
        <h1 class="u-text-xl u-font-normal u-text-neutral-800 u-mb-5 ">Open display</h1>
        <Button
            icon="fal fa-display"
            variant="secondary"
            size="md"
            label="Manage presentation"
            @click="() => router.visit(route('admin.presentations.index'))" />
        </div>
        <div
            class="u-w-full u-flex u-gap-2 u-mb-4">
            <ScreenCard
                v-for="element in displays"
                :key="element.id"
                :name="element.name"
                :width="element.width"
                :height="element.height"
                :main-item="element.order === 1"
            >
                <template #footer>
                    <div class="u-text-[12px] u-text-neutral-400">
                        {{ `https://carux.local/display/${element.name}` }}

                    </div>
                </template>
            </ScreenCard>
        </div>
    </Base>
</template>

<script setup>
import Base from "@layouts/base.vue";
import ScreenCard from "@components/base/ScreenCard.vue";
import { ref } from "vue";
import Button from "@components/base/button.vue";
const displays = ref([
    { id: 1, name: 'Main Display', width: '1920', height: '1080', order: 1 },
    { id: 2, name: 'Secondary Display', width: '1280', height: '720', order: 2 },
]);
import { router } from '@inertiajs/vue3';

// const props = defineProps<{
//     slides: Array<{
//         id: number,
//         is_active: boolean,
//     }>
// }>();
</script>

<style scoped>
</style>
