<template>
    <Teleport to="body">
        <transition name="drawer-transition">
            <div v-if="active" :class="blockClass">
                <div v-if="active && allowCloseWithBackdrop" :class="elementClass('backdrop')" @click="closeWrapper"></div>
                <div :class="elementClass('drawer-container')" ref="drawerRef">
                    <div :class="elementClass('drawer')">
                        <div :class="elementClass('content')">
                            <slot />
                        </div>
                        <div :class="elementClass('close')" v-if="!hideCloseButton">
                            <a @click="closeWrapper">
                                <i class="fal fa-times"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </Teleport>
</template>

<script setup lang="ts">
import { computed, ref, watch, onMounted, onUnmounted } from 'vue';
import { useBEM } from '@helpers/bem';
import type { DrawerProps } from '@interfaces/drawer';


const {
    modelValue = false,
    allowCloseWithBackdrop = true,
    placement = 'left',
    hideCloseButton = false
} = defineProps<DrawerProps>();

const active = computed(() => modelValue);
const drawerRef = ref<HTMLElement | null>(null);

const getBoundingInfo = () => {
    return drawerRef.value?.getBoundingClientRect();
};

defineExpose({ getBoundingInfo });

const emit = defineEmits<{
    (e: 'update:modelValue', value: boolean): void;
}>();

const blockName = 'c-drawer';

const modifierList = computed(() => {
    const modifiers: string[] = [placement];
    if (active.value) {
        modifiers.push('open');
    }
    return modifiers;
});

const { blockClass, setModifiers, elementClass } = useBEM(blockName, modifierList.value);

watch(
    modifierList,
    (modifiers: string[]) => {
        setModifiers(modifiers);
    },
    { deep: true }
);

const closeWrapper = () => {
    emit('update:modelValue', false);
};

const handleEscape = (event: KeyboardEvent) => {
    if (event.key === 'Escape' && active.value) {
        closeWrapper();
    }
};

onMounted(() => {
    document.addEventListener('keydown', handleEscape);
});

onUnmounted(() => {
    document.removeEventListener('keydown', handleEscape);
});
</script>
