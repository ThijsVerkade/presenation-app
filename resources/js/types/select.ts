export interface Option {
    label: string;
    value: string | number;
    disabled?: boolean;
}

export interface SelectProps {
    options: Option[];
    modelValue?: string[];
    searchable?: boolean;
    label?: string;
    placeholder?: string;
    size?: 'sm' | 'md' | 'lg';
    autocomplete?: string;
    mode?: 'single' | 'multiple' | 'tags';
    minChars?: number;
    errors?: string[];
}
