export interface DialogProps {
    modelValue?: boolean;
    allowBackdropClose?: boolean;
    allowEscapeKeyClose?: boolean;
    hideCloseButton?: boolean;
    variant?: 'dark' | 'light';
    type?: string;
    size?: 'sm' | 'default' | 'md';
}

export interface OverlayProps {
    modelValue?: boolean;
}
