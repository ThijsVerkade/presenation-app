<template>
    <div :class="blockClass">
        <input
            type="file"
            :accept="accept"
            @change="handleFileChange"
            :class="elementClass('input')"
            ref="fileInput"
        >
        <Button
            :label="label"
            :variant="variant"
            :size="size"
            :icon="icon"
            :disabled="disabled || loading"
            :loading="loading"
            @onClick="triggerFileInput"
        />
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { useBEM } from '@helpers/bem';
import Button from '@components/base/button.vue';

const emit = defineEmits<{
    (e: 'fileSelected', file: File): void;
}>();

const props = withDefaults(defineProps<{
    label?: string;
    variant?: 'default' | 'primary' | 'light' | 'track';
    size?: 'sm' | 'default' | 'fluid';
    icon?: string;
    disabled?: boolean;
    loading?: boolean;
    accept?: string;
}>(), {
    label: 'Upload File',
    variant: 'default',
    size: 'default',
    icon: 'fal fa-upload',
    disabled: false,
    loading: false,
    accept: 'image/*'
});

const blockName = 'c-file-upload';
const { blockClass, elementClass } = useBEM(blockName);

const fileInput = ref<HTMLInputElement | null>(null);

const triggerFileInput = () => {
    fileInput.value?.click();
};

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        emit('fileSelected', target.files[0]);
    }
};
</script>