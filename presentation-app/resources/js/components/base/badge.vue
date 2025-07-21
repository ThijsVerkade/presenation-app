<template>
    <component :is="tag" :class="blockClass" :href="link" :style="{ backgroundColor: color }">
        <span :class="elementClass('label')">{{ label }}</span>
    </component>
</template>

<script setup lang="ts">
import { computed, watch } from 'vue';
import { useBEM } from '@helpers/bem';
import type { BadgeProps } from '@interfaces/badge';

const blockName = 'c-badge';

const { label, variant = 'default', isActive = false, link = '' } = defineProps<BadgeProps>();

const modifiers = computed(() => {
    return isActive ? ['active'] : [];
});

const tag = computed(() => {
    switch (variant) {
        case 'default':
            return 'span';
        case 'button':
            return 'button';
        case 'link':
            return 'a';
        default:
            return 'span';
    }
});

// // This is done for Storybook to make sure the modifiers are updated
watch(
    modifiers,
    value => {
        setModifiers(value);
    },
    { deep: true }
);

const { blockClass, elementClass, setModifiers } = useBEM(blockName, modifiers.value);
</script>
