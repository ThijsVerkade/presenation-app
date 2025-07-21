<template>
    <component :is="wrapper" :class="blockClass">
        <div>
            <div v-if="label" :class="[elementClass('label'), getColor]">{{ label }}<i v-if="required">*</i></div>
            <div v-if="description" :class="[elementClass('description'), getColor]">{{ description }}</div>
            <div v-if="$slots.description" class="u-mt-2">
                <slot name="description" />
            </div>
        </div>
        <div :class="elementClass('input')">
            <slot />
        </div>
    </component>
</template>
<script setup lang="ts">
import { computed } from 'vue';
import { useBEM } from '@helpers/bem';
import type { InputGroupProps } from '@interfaces/input-group';
import { isTablet } from '@helpers/screens';

const blockName = 'c-input-group';

const { label = '', description, direction = 'vertical', mode, required = false } = defineProps<InputGroupProps>();

const currentDirection = computed(() => {
    return isTablet ? 'vertical' : direction;
});

const modifierList = computed(() => [currentDirection.value]);

const wrapper = computed(() => (direction === 'horizontal' ? 'div' : 'label'));
const getColor = computed(() => {
    let classes = '';
    switch (mode) {
        case 'default':
            break;

        case 'danger':
            classes = 'u-text-danger';
            break;

        case 'warning':
            classes = 'u-text-warning';
            break;
    }
    return classes;
});

const { blockClass, elementClass, setModifiers } = useBEM(blockName, modifierList.value);
</script>
