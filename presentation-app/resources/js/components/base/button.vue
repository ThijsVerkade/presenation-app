<template>
    <component :is="tag" :class="blockClass" :href="href" @click="handleClick" v-bind="$attrs" ref="button">
        <i v-if="icon && iconPosition === 'left'" :class="icon"></i>
        <span v-if="label" :class="elementClass('label')">
            {{ label }}
        </span>
        <i v-if="icon && iconPosition === 'right'" :class="icon"></i>

        <span :class="elementClass('loading')"><i :class="['fal fa-spin fa-spinner', elementClass('loader')]"></i></span>
    </component>
</template>

<script setup lang="ts">
import { computed, onMounted, ref, watch } from 'vue';
import { useBEM } from '@helpers/bem';
import type { ButtonProps } from '@interfaces/button';

const emit = defineEmits<{
    (e: 'onClick', event: MouseEvent): void;
}>();

const blockName = 'c-btn';

const button = ref<HTMLButtonElement | null>(null);

const {
    variant = 'default',
    label,
    icon,
    iconPosition = 'right',
    href,
    size = 'default',
    disabled,
    loading = false
} = defineProps<ButtonProps>();

const isRound = computed(() => icon && !label);

watch(
    () => disabled,
    value => {
        disableButton(value);
    }
);

onMounted(() => {
    disableButton(disabled);
});

const disableButton = (status: boolean) => {
    if (status) {
        button.value?.setAttribute('disabled', 'disabled');
    } else {
        button.value?.removeAttribute('disabled');
    }
};

const modifierList = computed(() => {
    const modifiers: string[] = [variant, size];
    if (disabled) {
        modifiers.push('disabled');
    }
    if (isRound.value) {
        modifiers.push('round');
    }
    if (loading) {
        modifiers.push('loading');
    }
    return modifiers;
});

const { blockClass, setModifiers, elementClass } = useBEM(blockName, modifierList.value);

// This is done for Storybook to make sure the modifiers are updated
watch(
    modifierList,
    modifiers => {
        setModifiers(modifiers);
    },
    { deep: true }
);

const tag = computed(() => (href ? 'a' : 'button'));

const handleClick = (event: MouseEvent) => {
    if (!disabled) {
        emit('onClick', event);
    }
};
</script>
