<template>
    <label :class="blockClass">
        <input
            type="checkbox"
            v-bind="$attrs"
            class="u-sr-only"
            @input="handleInput"
            :aria-checked="isChecked"
            role="switch"
        />
        {{ label }}
    </label>
</template>

<script setup lang="ts">
import { computed, watch, type Ref, ref } from 'vue';
import { useBEM } from '@helpers/bem';
import type { PillProps } from '@interfaces/pill';

const blockName = 'c-pill';

const props = defineProps<PillProps>();

// Compute whether the pill is checked based on modelValue type
const isChecked = computed(() => {
    if (Array.isArray(props.modelValue)) {
        return props.modelValue.includes(props.value);
    }
    return !!props.modelValue;
});

const modifierList = computed(() => {
    let modifiers: string[] = [];
    if (isChecked.value) {
        modifiers.push('active');
    }
    return modifiers;
});

const { blockClass, setModifiers } = useBEM(blockName, modifierList.value);

// This is done for Storybook to make sure the modifiers are updated
watch(
    modifierList,
    modifiers => {
        setModifiers(modifiers);
    },
    { deep: true }
);

const emit = defineEmits<{
    (e: 'update:modelValue', value: boolean | any[]): void;
}>();

const handleInput = () => {
    if (Array.isArray(props.modelValue)) {
        const newValue = [...props.modelValue];
        const index = newValue.indexOf(props.value);

        if (index === -1) {
            newValue.push(props.value);
        } else {
            newValue.splice(index, 1);
        }

        emit('update:modelValue', newValue);
    } else {
        emit('update:modelValue', !props.modelValue);
    }
};
</script>
