<template>
    <div :class="blockClass">
        <Avatar
            v-for="(item, index) in avatarList"
            :key="index"
            :avatar="item.avatar"
            :link="item.link"
            :name="item.name"
            v-tooltip.300="item.name"
        />
    </div>
</template>

<script setup lang="ts">
import { useBEM } from '@helpers/bem';
import type { AvatarGroupProps } from '@interfaces/avatar-group';
import Avatar from '@components/base/avatar.vue';

const blockName = 'c-avatar-group';
const { avatarList = [] } = defineProps<AvatarGroupProps>();

const { blockClass } = useBEM(blockName);
</script>
