<template>
    <transition name="overlay-toggle">
        <div :class="blockClass" @click="$emit('close')" v-if="modelValue"></div>
    </transition>
</template>
<script setup lang="ts">
import type { OverlayProps } from '@interfaces/dialog';
import { useBEM } from '@helpers/bem';

const blockName = 'c-overlay';
const { blockClass } = useBEM(blockName);
const { modelValue = false } = defineProps<OverlayProps>();
</script>
