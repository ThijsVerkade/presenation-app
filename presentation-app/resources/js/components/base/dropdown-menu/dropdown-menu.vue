<template>
    <div class="c-dropdown-menu">
        <div v-if="slots.button" @click="toggleDropdown" @keydown.enter="toggleDropdown" ref="dropdownButton">
            <slot name="button" :isOpen="isOpen" />
        </div>
        <Button v-else @click="toggleDropdown" @keydown.enter="toggleDropdown" ref="dropdownButton" :label="label ?? 'Dropdown Menu'" />
        <component
            v-model="isOpen"
            :is="componentType"
            :target="dropdownButton?.$el ?? dropdownButton"
            :placement="drawer ? 'bottom' : 'bottom-start'"
            :offset="[0, 5]"
        >
            <div :class="drawer ? 'c-dropdown-menu__drawer' : 'c-dropdown-menu__list'">
                <template v-if="!slots.dropdownContent">
                    <DropdownItem
                        v-for="(option, index) in options"
                        :key="index"
                        :id="index"
                        :variant="variantClass"
                        ref="dropdownItem"
                        :option="option"
                        :drawer="drawer"
                        @click="handleDropdownItemClick(option)"
                        @closeDropdown="isOpen = false"
                    />
                </template>
                <div class="c-dropdown-menu__content" v-else>
                    <slot name="dropdownContent" />
                </div>
            </div>
        </component>
    </div>
</template>

<script setup lang="ts">
import { ref, type Ref, useSlots, watch } from 'vue';
import DropdownItem from '@components/base/dropdown-menu/dropdown-item.vue';
import type { DropdownMenuProps, DropdownMenuItem } from '@interfaces/dropdown-menu';
import Button from '@components/base/button.vue';
import Dropdown from '@components/base/dropdown.vue';
import Drawer from '@components/base/drawer.vue';

const { options, variant, open, drawer = false, label } = defineProps<DropdownMenuProps>();

const slots = useSlots();
const isOpen: Ref<boolean> = ref(open ?? false);
const componentType = drawer ? Drawer : Dropdown;
const variantClass = ref(variant ?? 'large');

const emit = defineEmits<{
    (e: 'handleDropdown', option: DropdownMenuItem): void;
}>();

const dropdownItem: Ref = ref([]);
const dropdownButton = ref<HTMLElement | null>(null);

watch(
    () => open,
    newValue => {
        isOpen.value = newValue;
    }
);

const toggleDropdown = (): void => {
    isOpen.value = !isOpen.value;
};

const handleDropdownItemClick = (option: DropdownMenuItem) => {
    if (option.type === 'option') {
        isOpen.value = false;
        if (option.onClick) {
            option.onClick();
        }
    }
};
</script>
