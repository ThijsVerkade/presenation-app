<template>
    <div :class="elementClass('container')" @click="handleButtonClick">
        <span v-if="addon && addon.position === 'start'" :class="getAddonClass(addon)" @click="handleAddonButtonClick">
            <i v-if="addon.icon" :class="addon.icon"></i>
            <span class="u-w-max">{{ addon.label }}</span>
        </span>
        <div :class="getInputContainerClass">
            <i v-if="startIcon" :class="getIconClass(startIcon)" @click="handleIconClick"></i>
            <div v-if="type === 'button'" class="u-flex u-cursor-pointer u-w-full">{{ placeholder }}</div>
            <input
                v-else
                :value="modelValue"
                @input="onInput"
                :name="name"
                ref="inputRef"
                :type="type"
                :disabled="disabled"
                :placeholder="placeholder"
                :class="blockClass"
                @blur="onBlur"
                v-bind="$attrs"
                :readonly="readonly"
            />
            <i v-if="endIcon" :class="getIconClass(endIcon)" @click="handleIconClick"></i>
            <i v-else-if="textAppend" :class="elementClass('text-append')">{{ textAppend }}</i>
            <slot name="endIcon"></slot>
        </div>
        <span v-if="addon && addon.position === 'end'" :class="getAddonClass(addon)" @click="handleAddonButtonClick">
            <i v-if="addon.icon" :class="addon.icon"></i>
            <span class="u-w-max">{{ addon.label }}</span>
        </span>
    </div>
    <ValidationErrors :errors="errors" />
</template>

<script setup lang="ts">
import { computed, ref, watch, onMounted } from 'vue';
import { useBEM } from '@helpers/bem';
import type { Addon, InputProps } from '@interfaces/input';
import ValidationErrors from '@components/base/validation-errors.vue';

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
    (e: 'onBlur'): void;
    (e: 'onIconClick'): void;
    (e: 'onAddonButtonClick'): void;
    (e: 'onButtonClick'): void;
}>();
const blockName = 'c-input';

const {
    errors = [],
    modelValue,
    type = 'text',
    name = '',
    placeholder = '',
    size = 'lg',
    disabled = false,
    startIcon,
    endIcon,
    isIconClickable = false,
    addon = null,
    textAppend = null,
    autofocus = false,
    focus = false,
    readonly = false
} = defineProps<InputProps>();

const inputRef = ref<HTMLInputElement | null>(null);

const modifierList = computed(() => [size]);

watch(
    () => focus,
    value => {
        if (value) {
            inputRef.value?.focus();
        } else {
            inputRef.value?.blur();
        }
    }
);

const { blockClass, elementClass, setModifiers } = useBEM(blockName, modifierList.value);

// This is done for Storybook to make sure the modifiers are updated
watch(
    modifierList,
    modifiers => {
        setModifiers(modifiers);
    },
    { deep: true }
);

watch(
    () => focus,
    newFocus => {
        if (newFocus) {
            handleFocus();
        }
    }
);

const handleFocus = (): void => {
    if (inputRef.value) {
        inputRef.value.focus();
    }
};

const handleIconClick = (): void => {
    if (!isIconClickable) {
        return;
    }
    emit('onIconClick');
};

onMounted(() => {
    if (inputRef.value && (autofocus || focus)) {
        inputRef.value.focus();
    }
});
const getIconClass = (icon: string): string[] => {
    let iconModifiers = [];
    if (isIconClickable) {
        iconModifiers.push('is-clickable');
    }
    if (errors.length > 0) {
        iconModifiers.push('has-error');
    }
    return [icon, elementClass('icon', iconModifiers)];
};

const getAddonClass = (addon: Addon): string[] => {
    let addonModifiers = [];
    if (addon.type === 'button') {
        addonModifiers.push('button');
    }
    if (addon.position === 'start') {
        addonModifiers.push('addon-start');
    }
    if (addon.position === 'end') {
        addonModifiers.push('addon-end');
    }
    if (errors.length > 0) {
        addonModifiers.push('has-error');
    }
    return [elementClass('addon', addonModifiers)];
};

const getInputContainerClass = computed(() => {
    let containerModifiers = [];
    if (addon) {
        if (addon.position === 'start') {
            containerModifiers.push('no-radius-left');
        }
        if (addon.position === 'end') {
            containerModifiers.push('no-radius-right');
        }
    }
    if (type === 'button') {
        containerModifiers.push('is-button');
    }
    if (isIconClickable) {
        containerModifiers.push('has-clickable-icon');
    }
    if (errors.length > 0) {
        containerModifiers.push('is-error');
    }
    if (disabled) {
        containerModifiers.push('is-disabled');
    }
    return [elementClass('input-container', containerModifiers), readonly ? 'is-readonly' : ''];
});

const onInput = (event: Event) => {
    emit('update:modelValue', (event.target as HTMLInputElement).value);
};

const onBlur = (): void => {
    emit('onBlur');
};

const handleAddonButtonClick = (): void => {
    if (!addon || addon.type !== 'button') {
        return;
    }
    emit('onAddonButtonClick');
};

const handleButtonClick = (): void => {
    if (type === 'button') {
        emit('onButtonClick');
    }
};
</script>
