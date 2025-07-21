<template>
    <div
        class="u-relative u-w-[248px] u-bg-white u-rounded-xl u-border u-border-neutral-200 u-p-4 u-flex u-flex-col u-items-center u-shadow-sm u-cursor-pointer hover:u-shadow-md"
    >
        <Badge label="isMain" v-if="isMain"/>

        <div class="u-h-[54px] u-flex u-items-center u-pt-4">
            <div class="u-bg-neutral-50 u-border-neutral-200 u-border u-rounded-lg u-p-4  u-flex u-items-center u-justify-center"
                 :style="{ width: screenBoxWidth, height: screenBoxHeight }"
            >
                <i class="fa fa-display u-text-md u-text-neutral-400"></i>
            </div>
        </div>

        <!-- Screen Name -->
        <div class="u-text-[14px] u-font-semibold u-text-neutral-800 u-pt-4">
            {{ name }}
        </div>

        <div class="u-text-[12px] u-text-neutral-400">
            {{ width }} x {{ height }}
        </div>


        <slot name="footer"></slot>
    </div>
</template>

<script setup>
import {computed} from "vue";
import Badge from "@components/base/badge.vue";

const props = defineProps({
    name: {
        type: String,
        default: 'Main'
    },
    width: {
        type: String,
    },
    height: {
        type: String,
    },
    isMain: {
        type: Boolean,
        default: false
    }
});

const scaleFactor = 0.05
const maxWidthPx = 200
const maxHeightPx = 50

const rawWidth = computed(() => parseInt(props.width) * scaleFactor)
const rawHeight = computed(() => parseInt(props.height) * scaleFactor)

const screenBoxWidth = computed(() => {
    const width = rawWidth.value
    const height = rawHeight.value

    if (!width || !height) return '100px'

    const ratio = width > maxWidthPx ? maxWidthPx / width : 1

    return `${Math.round(width * ratio)}px`
})

const screenBoxHeight = computed(() => {
    const width = rawWidth.value
    const height = rawHeight.value

    if (!width || !height) return '60px'

    const ratio = height > maxHeightPx ? maxHeightPx / height : 1;

    return `${Math.round(height * ratio)}px`
})
</script>
