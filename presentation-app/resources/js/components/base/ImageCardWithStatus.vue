<script setup lang="ts">
import Badge from "@components/base/badge.vue";
import {computed, ref, watch} from 'vue'

const props = defineProps({
    imageUrl: {
        type: String,
        default: ''
    },
    statusLabel: {
        type: String,
        default: 'draft'
    }
});

const statusColor = computed(() => {
    switch (props.statusLabel.toLowerCase()) {
        case 'draft':
            return '#FDAB1B';
        case 'active':
            return '#0DAD2B';
        default:
            return '#FDAB1B';
    }
})

const statusLabel = computed(() => {
    return props.statusLabel.charAt(0).toUpperCase() + props.statusLabel.slice(1).toLowerCase();
})

const imageLoaded = ref(true)

function handleImageError() {
    imageLoaded.value = false
}
</script>

<template>
    <div class="u-relative u-w-full u-h-[130px] u-bg-neutral-50 u-rounded-lg u-border u-border-neutral-200 u-flex u-items-center u-justify-center">
        <img
            v-if="imageLoaded && imageUrl"
            :src="imageUrl"
            alt="Card image"
            class="u-object-cover u-w-full u-h-full"
            @error="handleImageError"
        />

        <div v-else class="u-text-neutral-400 u-text-2xl">
            <i class="fas fa-image"></i>
        </div>

        <div class="u-absolute u-bottom-2 u-right-2">
            <Badge :label="statusLabel" :color="statusColor" />
        </div>
    </div>
</template>
