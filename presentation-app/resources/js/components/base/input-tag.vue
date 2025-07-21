<template>
    <div :class="blockClass">
        <vue-tags-input
            :class="elementClass('content')"
            :tags="modelValue"
            :placeholder="placeholder"
            @on-tags-changed="handleChangeTag"
        />
    </div>
</template>

<script setup lang="ts">
import VueTagsInput from "vue3-tags-input";
import { useBEM } from '@helpers/bem';
import type { InputTagProps } from '@interfaces/input-tag';

const blockName = 'c-input-tag';
const { blockClass, elementClass } = useBEM(blockName);

const emit = defineEmits<{
    (e: 'update:modelValue', value: string[]): void;
}>();

const { modelValue = [], placeholder = '' } = defineProps<InputTagProps>();

const handleChangeTag = (tags: string[]) => {
    emit('update:modelValue', tags);
};
</script>
