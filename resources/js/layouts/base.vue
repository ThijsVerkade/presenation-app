<template>
    <div class="u-flex u-min-h-screen">
        <Sidebar :slides="slides" :slide="slide" :displays="displays" v-if="slides"/>
        <div class="u-flex-1 u-h-screen u-overflow-y-auto u-bg-neutral-50 md:u-pt-8 md:u-pl-8 md:u-pr-8 u-border-l u-border-neutral-200">
            <slot />
        </div>
    </div>
    <DialogAlert />
    <FlashMessages />
</template>

<script setup lang="ts">
import DialogAlert from "@components/base/dialog-alert.vue";
import FlashMessages from "@components/base/flash-messages.vue";
import Sidebar from "@components/Sidebar.vue";

defineProps< {
    slide: {
        id: number;
        is_active: boolean;
    };
    slides: Array<{
        id: number;
        is_active: boolean;
    }>;
    displays: {
        id: number;
        name: string;
        slug: string;
        width: number;
        height: number;
        order: number;
        media?: string;
    }[];
}>();
</script>
