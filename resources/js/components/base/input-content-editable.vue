<template>
    <component
        :is="type"
        :contenteditable="active"
        type="text"
        :placeholder="placeholder"
        @input="onLabelInput"
        :autofocus="autofocus"
        ref="input"
    ></component>
</template>
<script setup lang="ts">
import { onMounted, ref, watch, defineExpose } from 'vue';
import type { InputContentEditableProps } from '@interfaces/input-content-editable';

const {
    placeholder,
    modelValue,
    active = true,
    type = 'div',
    preventBreaks = false,
} = defineProps<InputContentEditableProps>();

const input = ref();

defineExpose({ input });

const emit = defineEmits<{
    (event: 'update:modelValue', value: string): void;
}>();

const onLabelInput = (e: Event) => {
    emit('update:modelValue', (e.currentTarget as HTMLElement).innerText);
};

const updateContent = (newContent: string) => {
    input.value.innerHTML = newContent;
};

onMounted(() => {
    updateContent(modelValue ?? '');
    if (preventBreaks) {
        input.value.addEventListener('keypress', (evt: KeyboardEvent) => {
            if (evt.key === 'Enter') {
                evt.preventDefault();
            }
        });
    }

    input.value.addEventListener('paste', function (e: ClipboardEvent) {
        e.preventDefault();
        const text = (e.clipboardData || window.clipboardData).getData('text');
        const selection: Selection | null = window.getSelection();
        if (selection && selection.rangeCount) {
            selection.deleteFromDocument();
            selection.getRangeAt(0).insertNode(document.createTextNode(text));
        }

        emit('update:modelValue', input.value.innerHTML);
    });
});

watch(
    () => modelValue,
    (newVal, oldVal) => {
        if (newVal != input.value.innerText) {
            updateContent(newVal ?? '');
        }
    }
);
</script>
