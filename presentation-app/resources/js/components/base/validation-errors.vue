<template>
    <div v-if="errors.length > 0" :class="blockClass">
        <p v-for="error in errors">{{ error }}</p>
    </div>
</template>

<script setup lang="ts">
import { useBEM } from '@helpers/bem';

const blockName = 'c-validation-errors';
const { blockClass } = useBEM(blockName);

const { errors } = defineProps<{
    errors: string[];
}>();
</script>
