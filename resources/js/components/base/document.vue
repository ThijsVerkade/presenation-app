<template>
    <div :class="blockClass">
        <div :class="elementClass('content')">
            <div :class="elementClass('icon')">
                <i class="fal" :class="icon ? icon : 'fa-file'"></i>
            </div>
            <div :class="elementClass('info')">
                <a :href="data.url" download target="blank"
                    ><span :class="elementClass('title')" v-tooltip="data.title">{{ data.title }}</span></a
                >
                <div :class="elementClass('meta')">
                    <div :class="elementClass('meta-text')" v-if="data.size">
                        {{ formatBytes(data.size) }}
                    </div>
                </div>
            </div>
            <div :class="elementClass('actions')">
                <slot name="actions"></slot>
                <Button
                    icon="fal fa-download"
                    size="sm"
                    variant="default"
                    v-if="allowInteractions"
                    :href="data.url"
                    download
                />
                <Button icon="fal fa-times" size="sm" variant="default" v-if="removable" @click="$emit('remove')" />
            </div>
        </div>
        <div :class="elementClass('loading')" v-if="data.progress && data.progress < 100">
            <div class="c-document__loading-bar">
                <div class="c-document__loading-bar-progress" :style="{ width: `${data.progress}%` }"></div>
            </div>
        </div>
    </div>
</template>
<script lang="ts" setup>
import { computed } from 'vue';
import { useBEM } from '@helpers/bem';
import Button from '@components/base/button.vue';
import { getMimeTypeIcon } from '@helpers/fileIcon';

// Define props interface
interface DocumentData {
    title?: string;
    url?: string;
    download_url?: string;
    size?: number;
    mime_type?: string;
    progress?: number;
    [key: string]: any;
}

interface Props {
    data: DocumentData;
    allowInteractions?: boolean;
    removable?: boolean;
}

// Define props with withDefaults
const props = withDefaults(defineProps<Props>(), {
    data: () => ({}),
    allowInteractions: true,
    removable: false
});

// Define emits
defineEmits<{
    (e: 'remove'): void;
}>();

// Convert computed property
const icon = computed(() => {
    return getMimeTypeIcon(props.data.mime_type || '');
});

// Convert method to function
const formatBytes = (bytes: number, decimals: number = 2): string => {
    if (bytes === 0) return '0 Bytes';

    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

    const i = Math.floor(Math.log(bytes) / Math.log(k));

    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
};

const blockName = 'c-document';
const { blockClass, elementClass } = useBEM(blockName);
</script>
