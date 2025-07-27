export interface ButtonProps {
    label?: string;
    variant?: 'default' | 'primary' | 'light' | 'dark' | 'danger';
    size?: 'sm' | 'default' | 'fluid';
    disabled?: boolean;
    icon?: string | null;
    iconPosition?: 'left' | 'right';
    href?: string;
    loading?: boolean;
}
