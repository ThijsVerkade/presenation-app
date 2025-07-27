<template>
    <Base :slides="slides">
        <h1 class="u-text-sm u-font-normal u-text-neutral-800 u-mb-5 ">Displays</h1>

        <Draggable
            v-model="displays"
            handle=".drag-handle"
            item-key="id"
            class="u-w-full u-flex u-flex-wrap u-gap-2 u-mb-4"
            :animation="200"
            @end="onDragEnd"
        >
            <template #item="{ element }">
                <div class="u-w-1/4">
                    <ScreenCard
                        :key="element.id"
                        :name="element.name"
                        :width="element.width"
                        :height="element.height"
                        :main-item="element.order == 1"
                        @click="() => editDisplay(element)"
                    >
                        <template #footer>
                            <div class="u-mt-4 u-flex u-justify-center u-items-center">
                                <i class="fas fa-grip-dots u-text-neutral-300 u-w-3 u-h-4 drag-handle u-my-auto u-cursor-pointer" />
                            </div>
                        </template>
                    </ScreenCard>
                </div>
            </template>
        </Draggable>

        <Button
            icon="fal fa-display"
            variant="primary"
            size="md"
            label="Add Display"
            @click="() => openDialog()" />

        <Dialog v-model="isDialogVisible"
                :title="isEditing ? 'Edit Display' : 'Add Display'">
            <div class="u-p-4 u-w-[400px]">
                <h2 class="u-font-bold u-text-xl u-mb-4">{{ isEditing ? 'Edit Display' : 'Add Display' }}</h2>
                <div class="u-mb-4">
                    <div class="u-text-sm u-font-medium u-text-neutral-800 u-pb-1">Name</div>
                    <InputBase
                        v-model="newName"
                        size="lg"
                        type="text"
                        placeholder="PHUD"
                    />
                </div>
                <div class="u-text-sm u-font-medium u-text-neutral-800 u-pb-1">Resolution</div>
                <div class="u-flex u-gap-2 u-pb-5">
                <InputBase
                    v-model="newWidth"
                    size="lg"
                    type="text"
                    placeholder="3840"
                    :addon="{type: 'text', label: 'px', position: 'end'}"
                    addon.position="end"
                />
                <InputBase
                    v-model="newHeight"
                    size="lg"
                    type="text"
                    :addon="{type: 'text', label: 'px', position: 'end'}"
                    placeholder="1080"
                />
                </div>

                <Button variant="primary" icon="fal fa-check" :label="isEditing ? 'Update' : 'Save'" class="u-w-full" @click="saveDisplay" />
            </div>
        </Dialog>
    </Base>
</template>

<script setup lang="ts">
import {ref} from "vue";
import Base from "@layouts/base.vue";
import Button from "@components/base/button.vue";
import ScreenCard from "@components/base/ScreenCard.vue";
import Draggable from "vuedraggable";
import InputBase from "@components/base/input-base.vue";
import Dialog from "@components/base/dialog.vue";
import {createApiCall} from "@helpers/apiHelper";
import { router } from '@inertiajs/vue3';
import {route} from "ziggy-js";
const apiCall = createApiCall();


const props = defineProps<{
    displays: {
        id: number;
        name: string;
        width: number;
        height: number;
        order: number;
    }[];
    slides: Array<{
        id: number;
        is_active: boolean;
    }>;
}>();

const isDialogVisible = ref(false)
const newName = ref('')
const newWidth = ref('')
const newHeight = ref('')
const currentDisplayId = ref(null)
const isEditing = ref(false)
const displays = ref([...props.displays]);

const openDialog = () => {
    newName.value = ''
    newWidth.value = ''
    newHeight.value = ''
    currentDisplayId.value = null
    isEditing.value = false
    isDialogVisible.value = true;
}

const editDisplay = (display) => {
    newName.value = display.name
    newWidth.value = display.width.toString()
    newHeight.value = display.height.toString()
    currentDisplayId.value = display.id
    isEditing.value = true
    isDialogVisible.value = true;
}

const onDragEnd = (event) => {
    const orderedIds = displays.value.map(item => item.id);
    apiCall(
        'post',
        route('admin.displays.reorder'),
        {items: orderedIds},
        'Display order updated successfully',
        'Failed to update display order',
    );
}

const saveDisplay = () => {
    if (isEditing.value) {
        apiCall(
            'post',
            route('admin.displays.update', { id: currentDisplayId.value }),
            {
                name: newName.value,
                width: newWidth.value,
                height: newHeight.value
            },
            'Display updated successfully',
            'Failed to update display',
        )
    } else {
        apiCall(
            'post',
            route('admin.displays.store'),
            {
                name: newName.value,
                width: newWidth.value,
                height: newHeight.value
            },
            'Display created successfully',
            'Failed to create display',
        )
    }
    isDialogVisible.value = false;
    router.visit(window.location.href, {
        replace: true,
        preserveScroll: false,
        preserveState: false,
    })
}
</script>

<style scoped>
</style>
