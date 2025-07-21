<script setup lang="ts">
import { useBEM } from '@helpers/bem';
import { computed, ref, watch } from 'vue';
import type { ToastProps } from '@interfaces/toast';
import { useToast } from '@composables/useToast';

const blockName = 'c-toast';
const { message, type = 'success', description } = defineProps<ToastProps>();
const emit = defineEmits<{
    (event: 'onClose'): void;
}>();
const { show: isVisible } = useToast();

const modifiersList = computed(() => {
    return [type];
});

watch(
    modifiersList,
    modifiers => {
        setModifiers(modifiers);
    },
    { deep: true }
);

const { blockClass, elementClass, setModifiers } = useBEM(blockName, modifiersList.value);

const iconClass = computed(() => {
    let icon = 'fa-sharp fa-light fa-circle-check';
    if (type === 'error') {
        icon = 'fa-light fa-bug';
    }
    if (type === 'warning') {
        icon = 'fa-light fa-triangle-exclamation';
    }

    return [elementClass('icon'), icon];
});

const closeToast = () => {
    emit('onClose');
    isVisible.value = false;
};
</script>

<template>
    <div :class="blockClass" v-if="isVisible">
        <div :class="elementClass('message-container')">
            <i :class="iconClass"></i>
            <div :class="elementClass('content')">
                <div :class="elementClass('message')">{{ message }}</div>
                <div v-if="description" :class="elementClass('description')">{{ description }}</div>
            </div>
        </div>
        <div :class="elementClass('close')" @click="closeToast">
            <i class="fa-sharp fa-light fa-xmark"></i>
        </div>
    </div>
</template>
