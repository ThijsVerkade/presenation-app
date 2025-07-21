<script setup lang="ts">
import { useBEM } from '@helpers/bem';
import { computed, type ComputedRef, type Ref, ref, watch } from 'vue';
import type { SwitchProps } from '@interfaces/switch';

const blockName = 'c-switch';

const { modelValue, label = '', description = '', withBorder = false, borderBottom = false } = defineProps<SwitchProps>();

const emit = defineEmits<{
    (event: 'update:modelValue', value: boolean): void;
}>();

const modifiersList = computed(() => {
    let modifiers = [];
    if (withBorder) {
        modifiers.push('with-border');
    }
    return modifiers;
});
const { blockClass, elementClass } = useBEM(blockName, modifiersList.value);

const isChecked: Ref<boolean> = ref(modelValue);

watch(
    () => modelValue,
    value => {
        isChecked.value = value;
    }
);

const handleClick = () => {
    isChecked.value = !isChecked.value;
    emit('update:modelValue', isChecked.value);
};

const switchModifiers: ComputedRef<string[]> = computed(() => {
    let modifiers = [];
    if (isChecked.value) {
        modifiers.push('on');
    }

    return modifiers;
});

const containerModifiers: ComputedRef<string[]> = computed(() => {
    let modifiers = [];
    if (borderBottom) {
        modifiers.push('border-bottom');
    }
    if (description) {
        modifiers.push('with-description');
    }

    return modifiers;
});
</script>

<template>
    <div :class="blockClass">
        <div :class="elementClass('container', containerModifiers)">
            <div :class="elementClass('switch', switchModifiers)" @click="handleClick">
                <div :class="elementClass('switch-toggle')"></div>
            </div>
            <input class="u-hidden" type="checkbox" id="switch" :checked="isChecked" />
            <label v-if="label" for="switch" :class="elementClass('label')">
                <span :class="elementClass('text')">{{ label }}</span>
                <span v-if="description" :class="elementClass('description')">{{ description }}</span>
            </label>
        </div>
    </div>
</template>
