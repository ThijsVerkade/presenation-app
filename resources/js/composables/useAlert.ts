import { ref } from 'vue';

const isVisible = ref<boolean>(false);
const dialogTitle = ref<string>('');
const dialogMessage = ref<string>('');
const confirmButtonText = ref<string | null>(null);
const cancelButtonText = ref<string | null>(null);
const showCancelButton = ref<boolean>(false);
const onConfirm = ref<(() => void) | undefined>(undefined);
let resolvePromise: (result: boolean) => void;

export function useAlert() {
    const alertDialog = (options: {
        title: string;
        message: string;
        confirmButtonText?: string;
        cancelButtonText?: string;
        showCancelButton?: boolean;
        onConfirm?: () => void;
    }): Promise<boolean> => {
        dialogTitle.value = options.title;
        dialogMessage.value = options.message;
        isVisible.value = true;
        confirmButtonText.value = options.confirmButtonText || null;
        cancelButtonText.value = options.cancelButtonText || null;
        showCancelButton.value = options.showCancelButton || false;
        onConfirm.value = options.onConfirm;

        return new Promise(resolve => {
            resolvePromise = resolve;
        });
    };

    const confirm = () => {
        isVisible.value = false;
        resolvePromise(true);
        if (onConfirm.value) {
            onConfirm.value();
        }
    };

    const close = () => {
        isVisible.value = false;
        resolvePromise(false);
    };

    return {
        alertDialog,
        dialogTitle,
        dialogMessage,
        isVisible,
        confirmButtonText,
        cancelButtonText,
        showCancelButton,
        confirm,
        close
    };
}
