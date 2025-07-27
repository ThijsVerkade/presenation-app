import { ref } from 'vue';
const message = ref<string>('');
const type = ref<'success' | 'error'>('success');
const show = ref<boolean>(false);

export function useToast() {
    const addToast = (toastMessage: string, toastType: 'success' | 'error', duration = 3000) => {
        message.value = toastMessage;
        type.value = toastType;
        show.value = true;

        if (type.value !== 'error') {
            setTimeout(() => {
                show.value = false;
            }, duration);
        }
    };

    const toastSuccess = (message: string, duration = 3000) => {
        addToast(message, 'success', duration);
    };

    const toastError = (message: string, duration = 3000) => {
        addToast(message, 'error', duration);
    };

    return {
        message,
        type,
        show,
        toastSuccess,
        toastError
    };
}
