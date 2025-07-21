<template>
    <div
        class="c-dropdown-item"
        tabindex="0"
        ref="dropdownItem"
        :class="[
            { 'is-active': active },
            variantClass ? `c-dropdown-item--${variantClass}` : '',
            drawer ? 'c-dropdown-item--drawer' : ''
        ]"
    >
        <template v-if="option.type === 'header'">
            <div class="c-dropdown-item__header">{{ option.label }}</div>
        </template>
        <template v-if="option.type === 'link'">
            <a
                class="c-dropdown-item__label"
                :class="{ 'c-dropdown-item__label__disabled': option.disabled }"
                :href="option.link"
                :download="option.download || false"
                :target="option.target || '_self'"
            >
                <img
                    v-if="option.image"
                    :src="option.image"
                    :class="option.imageType === 'square' ? 'avatar-square' : 'avatar'"
                />
                <i v-else class="c-dropdown-item__icon" :class="option.icon" :style="'color: ' + option.iconColor"></i>
                <span>{{ option.label }}</span>
                <span v-if="option.subLabel" class="c-dropdown-item__sublabel">{{ option.subLabel }}</span>
            </a>
        </template>
        <template v-if="option.type === 'action'">
            <button
                class="c-dropdown-item__label"
                :class="{ 'c-dropdown-item__label__disabled': option.disabled }"
                @click="handleActionClick(option)"
            >
                <img
                    v-if="option.image"
                    :src="option.image"
                    :class="option.imageType === 'square' ? 'avatar-square' : 'avatar'"
                />
                <i
                    v-else-if="option.icon"
                    class="c-dropdown-item__icon"
                    :class="option.icon"
                    :style="'color: ' + option.iconColor"
                ></i>
                <span>{{ option.label }}</span>
                <span v-if="option.subLabel" class="c-dropdown-item__sublabel">{{ option.subLabel }}</span>
            </button>
        </template>
        <template v-if="option.type === 'upload'">
            <label class="c-dropdown-item__label" :class="{ 'c-dropdown-item__label__disabled': option.disabled }">
                <input
                    id="uploadImages"
                    multiple
                    :disabled="option.disabled"
                    :accept="option.accept ?? 'image/gif,image/jpeg,image/jpg,image/png,image/bmp'"
                    type="file"
                    @change="option.onSelect"
                    class="u-hidden"
                />
                <i class="c-dropdown-item__icon" :class="option.icon" :style="'color: ' + option.iconColor"></i>
                <span>{{ option.label }}</span>
                <span v-if="option.subLabel" class="c-dropdown-item__sublabel">{{ option.subLabel }}</span>
            </label>
        </template>
        <template v-if="option.type === 'profile-box'">
            <button class="c-dropdown-item__profile-box" @click="navigate(option.link)">
                <ProfileBox :image="option.image" :text="option.label" :active="active" :selectable="true" />
            </button>
        </template>
        <template v-if="option.type === 'divider'">
            <div class="c-dropdown-item__divider"></div>
        </template>
        <template v-if="option.type === 'dropdown'">
            <div
                class="c-dropdown-item__label"
                :class="{ 'c-dropdown-item__label__disabled': !hasSubmenu || option.disabled }"
                @click="toggleSubmenu"
                ref="dropdownButton"
            >
                <img
                    v-if="option.image"
                    :src="option.image"
                    :class="option.imageType === 'square' ? 'avatar-square' : 'avatar'"
                />
                <i v-else :class="option.icon" :style="'color: ' + option.iconColor"></i>
                <span>{{ option.label }}</span>
                <span v-if="option.subLabel" class="c-dropdown-item__sublabel">{{ option.subLabel }}</span>
                <div class="c-dropdown-item__arrow-right">
                    <i class="fa fa-solid fa-caret-right" v-if="hasSubmenu"></i>
                </div>
            </div>
            <div
                v-if="isSubmenuOpen"
                class="c-dropdown-item__submenu"
                ref="dropdownMenu"
                :class="{ 'c-dropdown-item__left': isLeftAligned }"
            >
                <slot v-if="$slots.submenu" name="submenu"></slot>
                <DropdownItem
                    v-else
                    v-for="(subOption, subIndex) in option.submenu"
                    :key="subIndex"
                    :id="subIndex"
                    ref="dropdownItem"
                    :option="subOption"
                />
            </div>
        </template>
        <template v-if="option.type === 'option'">
            <div class="c-dropdown-item__label">
                <i :class="option.icon" :style="'color: ' + option.iconColor"></i>
                <span>{{ option.label }}</span>
            </div>
        </template>
    </div>
</template>

<script setup lang="ts">
import { ref, type Ref, nextTick, onBeforeUnmount, watch, onMounted } from 'vue';
import DropdownItem from '@components/base/dropdown-menu/dropdown-item.vue';
import ProfileBox from '@components/base/profile-box.vue';
import type { DropdownItemProps, DropdownMenuItem } from '@interfaces/dropdown-menu';
import { createPopper } from '@popperjs/core';

const { option, active, variant, drawer, open } = defineProps<DropdownItemProps>();

const dropdownButton: Ref<HTMLElement | null> = ref(null);
const dropdownMenu: Ref<HTMLElement | null> = ref(null);
const isSubmenuOpen: Ref<boolean> = ref(false);
const isLeftAligned: Ref<boolean> = ref(false);
const dropdownItem: Ref = ref([]);
const popperInstance: Ref<ReturnType<typeof createPopper> | null> = ref(null);
const variantClass = ref(variant ?? 'large');

watch(
    () => open,
    newValue => {
        isSubmenuOpen.value = newValue;
    }
);

const handleActionClick = (option: DropdownMenuItem) => {
    if (option.disabled) {
        return;
    }
    option.onClick();
    emit('closeDropdown');
};

const emit = defineEmits(['closeDropdown']);

const toggleSubmenu = (): void => {
    if (!hasSubmenu.value || option.disabled) return;
    isSubmenuOpen.value = !isSubmenuOpen.value;

    if (isSubmenuOpen.value) {
        setupPopper();
    } else {
        destroyPopper();
    }
};

onBeforeUnmount(() => {
    destroyPopper();
});

onMounted(() => {
    // add event listener to close dropdown on click outside
    document.addEventListener('click', event => {
        if (dropdownButton.value && dropdownMenu.value) {
            if (!dropdownButton.value.contains(event.target as Node) && !dropdownMenu.value.contains(event.target as Node)) {
                toggleSubmenu();
            }
        }
    });
});

function setupPopper() {
    nextTick(() => {
        if (dropdownButton.value && dropdownMenu.value) {
            popperInstance.value = createPopper(dropdownButton.value, dropdownMenu.value, {
                placement: option.placement ?? 'auto',
                modifiers: [
                    {
                        name: 'offset',
                        options: {
                            offset: [dropdownMenu.value?.offsetHeight / 2 - dropdownButton.value?.offsetHeight / 2, 6] // Distance between button and dropdown
                        }
                    }
                ]
            });
        }
    });
}

function destroyPopper() {
    if (popperInstance.value) {
        popperInstance.value.destroy();
        popperInstance.value = null;
    }
}
const hasSubmenu: Ref<boolean> = ref(!!option.submenu && option.submenu.length > 0);
const navigate = (link: string | null | undefined): void => {
    if (option.disabled) {
        return;
    }
    if (!link) return;
    window.location.href = link;
};
</script>
