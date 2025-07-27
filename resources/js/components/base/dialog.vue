<template>
    <div>
        <Teleport to="body">
            <Transition name="dialog-transition" :duration="300">
                <div
                    :role="type === 'alert' ? 'alertdialog' : 'dialog'"
                    :class="[blockClass, 'c-dialog--' + type]"
                    v-if="active"
                >
                    <Overlay v-model="active" @close="closeModalViaBackdrop" />
                    <div :class="elementClass('dialog', modifierList)" ref="dialog">
                        <div :class="elementClass('close')" v-if="!hideCloseButton">
                            <a @click="close">
                                <i class="fal fa-times"></i>
                            </a>
                        </div>
                        <div :class="elementClass('content')">
                            <div :class="elementClass('body')">
                                <slot></slot>
                            </div>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>
<script setup lang="ts">
import { useBEM } from '@helpers/bem';
import { onBeforeUnmount, onMounted, ref, watch, computed } from 'vue';
import Overlay from '@components/base/overlay.vue';
import type { DialogProps } from '@interfaces/dialog';
import { clearAllBodyScrollLocks } from 'body-scroll-lock';

const {
    allowBackdropClose = true,
    allowEscapeKeyClose = true,
    hideCloseButton = false,
    modelValue = false,
    type = 'default',
    variant = 'light',
    size = 'default'
} = defineProps<DialogProps>();
const emit = defineEmits<{
    (event: 'update:modelValue', value: boolean): void;
    (event: 'close'): void;
}>();

const blockName = 'c-dialog';
const active = ref(modelValue);
const modifierList = computed(() => [variant, size]);

const { blockClass, elementClass } = useBEM(blockName);

watch(
    () => modelValue,
    value => {
        active.value = value;
    }
);

const closeModalViaBackdrop = () => {
    if (allowBackdropClose) {
        close();
    }
};

const close = () => {
    emit('update:modelValue', false);
    emit('close');
    active.value = false;
    clearAllBodyScrollLocks();
};

onMounted(() => {
    document.addEventListener('keydown', onKeyDown);
});

onBeforeUnmount(() => {
    document.removeEventListener('keydown', onKeyDown);
});

const onKeyDown = (event: KeyboardEvent) => {
    if (allowEscapeKeyClose && event.key === 'Escape') {
        close();
    }
};
</script>
