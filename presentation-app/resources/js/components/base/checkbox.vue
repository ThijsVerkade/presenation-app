<template>
    <label :class="blockClass">
        <input
            :disabled="disabled"
            :name="name"
            class="u-hidden"
            :type="isRadio ? 'radio' : 'checkbox'"
            @input="handleInput"
        />
        <div :class="elementClass('container')">
            <div :class="boxClasses">
                <div v-if="isChecked">
                    <i v-if="!isRadio" class="fa fa-check" :class="elementClass('icon')"></i>
                    <i v-else :class="elementClass('icon')"></i>
                </div>
            </div>
            <div v-if="label">
                <p :class="elementClass('label')">{{ label }}</p>
                <p v-if="description" :class="elementClass('description')">{{ description }}</p>
            </div>
        </div>
    </label>
</template>
<script setup lang="ts">
import { computed, type ComputedRef, type Ref, ref, watch } from 'vue';
import type { CheckboxProps } from '@interfaces/checkbox';
import { useBEM } from '@helpers/bem';

const blockName = 'c-checkbox';

const {
    name = '',
    label = '',
    isRadio = false,
    description = null,
    modelValue = false,
    disabled = false
} = defineProps<CheckboxProps>();

const emit = defineEmits<{
    (event: 'update:modelValue', value: boolean): void;
}>();

watch(
    () => modelValue,
    value => {
        isChecked.value = value;
    }
);

const isChecked: Ref<boolean> = ref(modelValue);

const boxClasses: ComputedRef<string> = computed(() => {
    const boxModifiers = [];
    if (isChecked.value) {
        boxModifiers.push('checked');
    }
    if (isRadio) {
        boxModifiers.push('radio');
    }
    return elementClass('box', boxModifiers);
});

const { blockClass, elementClass, setModifiers } = useBEM(blockName);

const handleInput = () => {
    if (disabled) {
        return;
    }
    isChecked.value = !isChecked.value;
    emit('update:modelValue', isChecked.value);
};
</script>
