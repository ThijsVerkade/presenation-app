<template>
    <Dialog
        v-model="isVisible"
        type="alert"
        hideCloseButton
        :allowBackdropClose="false"
        :allowEscapeKeyClose="showCancelButton"
        @close="handleDialogClose"
    >
        <div class="u-p-4">
            <h2 class="u-font-bold u-text-base">{{ dialogTitle }}</h2>
            <p class="u-text-sm u-text-neutral-500 u-text-balance">{{ dialogMessage }}</p>

            <div class="u-flex u-justify-end u-mt-4 u-gap-2">
                <Button
                    v-if="showCancelButton"
                    variant="default"
                    ref="cancelButton"
                    @click="onClose"
                    :label="cancelButtonText || $t('components.dialog.cancel')"
                />
                <Button variant="primary" @click="onConfirm" :label="confirmButtonText || $t('components.dialog.confirm')" />
            </div>
        </div>
    </Dialog>
</template>

<script setup lang="ts">
import Dialog from '@components/base/dialog.vue';
import Button from '@components/base/button.vue';
import { useAlert } from '@composables/useAlert';

const { dialogTitle, dialogMessage, isVisible, confirm, close, cancelButtonText, confirmButtonText, showCancelButton } =
    useAlert();

const emit = defineEmits<{
    (event: 'confirm'): void;
    (event: 'close'): void;
    (event: 'update:modelValue', value: boolean): void;
}>();

const onConfirm = () => {
    confirm();
    emit('update:modelValue', false);
};

const onClose = () => {
    close();
    emit('update:modelValue', false);
};

const handleDialogClose = () => {
    if (showCancelButton.value) {
        onClose();
    }
};
</script>
