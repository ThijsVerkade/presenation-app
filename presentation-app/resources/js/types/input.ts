export interface InputProps {
    modelValue: string;
    type?: 'text' | 'password' | 'email' | 'button';
    placeholder?: string;
    name?: string;
    errors?: string[];
    disabled?: boolean;
    size?: 'md' | 'lg';
    startIcon?: string | null;
    endIcon?: string | null;
    textAppend?: string;
    isIconClickable?: boolean;
    addon?: Addon;
    autofocus?: boolean;
    image?: string;
    focus?: boolean;
    readonly?: boolean;
}

export interface Addon {
    type: 'button' | 'text';
    label: string;
    position: 'start' | 'end';
    icon?: string;
}
