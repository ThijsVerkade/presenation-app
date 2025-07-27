<template>
    <div :class="blockClass" @click="handleClick">
        <div
            :class="[
                elementClass('content', sizeModifiers),
                { [elementClass('default')]: !avatar, [elementClass('border')]: isBorder }
            ]"
        >
            <div :class="elementClass('gradient')"></div>
            <img :src="avatarImage" :alt="name" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { useBEM } from '@helpers/bem';
import type { AvatarProps } from '@interfaces/avatar';

const blockName = 'c-avatar';
const { avatar = null, link = '', name = '', isBorder = true, size } = defineProps<AvatarProps>();

const avatarImage = computed(() => {
    return !!avatar ? avatar : '/images/avatar-user.svg';
});

const handleClick = () => {
    if (link) {
        window.location.href = link;
    }
};

const modifiers = computed(() => {
    return link ? ['link'] : [];
});

const sizeModifiers = computed(() => {
    let modifiers = [];
    if (size === 'sm') {
        modifiers.push('small');
    }
    return modifiers;
});

const { blockClass, elementClass } = useBEM(blockName, modifiers);
</script>
