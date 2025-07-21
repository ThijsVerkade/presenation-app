<template>
    <div :class="[blockClass, active ? 'is-active' : '', selectable ? 'is-selectable' : '']">
        <img :class="elementClass('image')" :src="avatar" alt="" />
        <span :class="elementClass('name')">{{ text }}</span>
    </div>
</template>

<script setup lang="ts">
import { useBEM } from '@helpers/bem';
import type { ProfileBoxProps } from '@interfaces/profile-box';
import { computed } from 'vue';

const blockName = 'c-profile-box';

const { text = '', image = '', active = false, selectable = false } = defineProps<ProfileBoxProps>();

const avatar = computed(() => {
    return image || '/images/avatar-user.svg';
});

const { blockClass, elementClass } = useBEM(blockName);
</script>
