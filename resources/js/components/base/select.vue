<template>
    <div :class="blockClass">
        <multiselect
            :model-value="modelValue"
            @update:model-value="updateSelectedItems"
            :mode="mode"
            :ref="selectElement"
            can-clear
            :append-to="selectElement"
            :clear-on-search="true"
            track-by="label"
            clear-on-select
            :min-chars="minChars"
            :options="filteredOptions"
            :autocomplete="autocomplete"
            :placeholder="placeholder"
            no-options-text="No options found"
            no-results-text="No results found"
            caret
            @close="clearSearch"
            v-bind="$attrs"
        >
            <template #singlelabel="{ value }">
                <div class="multiselect-single-label">
                    <span class="multiselect-selected-label--item">{{ value.label }}</span>
                </div>
            </template>

            <template #multiplelabel="{ values }">
                <div class="multiselect-multiple-label">{{ values.length }} options selected</div>
            </template>

            <template #tag="{ option, handleTagRemove }">
                <span class="multiselect-tag">
                    {{ option.label }}
                    <button @click="handleTagRemove(option, $event)" class="multiselect-tag-remove-icon">x</button>
                </span>
            </template>

            <template #caret>
                <i class="fas fa-sort" aria-hidden="true"></i>
            </template>

            <template #option="{ option }">
                <div v-if="isSearchOption(option)" class="custom-search-input-wrapper" @click.stop @keydown.stop>
                    <input v-model="customSearch" type="text" placeholder="Search items" class="custom-search-input" />
                    <span class="custom-search-input__icon">
                        <i class="fas fa-search" aria-hidden="true"></i>
                    </span>
                </div>
                <div v-else>
                    <span>{{ option.label }}</span>
                </div>
            </template>
        </multiselect>
    </div>
    <ValidationErrors :errors="errors" />
</template>

<script lang="ts" setup>
import { ref, computed, watch } from 'vue';
import type { SelectProps, Option } from '@interfaces/select';
import Multiselect from '@vueform/multiselect';
import '@vueform/multiselect/themes/default.css';
import { useBEM } from '@helpers/bem';
import ValidationErrors from '@components/base/validation-errors.vue';

const {
    errors = [],
    options = [],
    size = 'md',
    modelValue = [],
    searchable = false,
    placeholder = 'Select',
    autocomplete = '',
    mode = 'single',
    minChars = 0
} = defineProps<SelectProps>();

const emit = defineEmits<{
    (e: 'update:modelValue', newSelectedItem: string): void;
}>();

const customSearch = ref<string>('');
const selectElement = ref<HTMLElement | null>(null);

const { blockClass, setModifiers, elementClass } = useBEM('c-select', [size]);

const modifierList = computed(() => {
    let modifiers = [size];
    if (errors.length > 0) {
        modifiers.push('is-error');
    }

    return modifiers;
});

// This is done for Storybook to make sure the modifiers are updated
watch(
    modifierList,
    modifiers => {
        setModifiers(modifiers);
    },
    { deep: true }
);

const updateSelectedItems = (newSelectedItem: string) => {
    emit('update:modelValue', newSelectedItem);
};

const isSearchOption = (option: Option) => option.value === 'search';

const clearSearch = () => {
    customSearch.value = '';
};

const filteredOptions = computed(() => {
    const searchOption = {
        label: 'Search items',
        value: 'search'
    };

    const filtered = options.filter((option: Option) =>
        option.label.toLowerCase().includes(customSearch.value.toLowerCase())
    );

    return searchable ? [searchOption, ...filtered] : filtered;
});
</script>
