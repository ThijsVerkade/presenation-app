import type { Placement } from '@popperjs/core';

export interface DropdownMenuItem {
    type: 'header' | 'divider' | 'link' | 'action' | 'upload' | 'dropdown' | 'profile-box' | 'option';
    label: string;
    subLabel?: string;
    link?: string;
    download?: boolean;
    target?: string;
    icon?: string;
    iconColor?: string;
    accept?: string;
    image?: string;
    disabled?: boolean;
    imageType?: 'circle' | 'square';
    onSelect?: (event: Event) => void;
    onClick?: () => void;
    submenu?: DropdownMenuItem[];
    placement?: Placement;
}

export interface DropdownItemProps {
    option: DropdownMenuItem;
    active?: boolean;
    open?: boolean;
    variant?: string;
    drawer?: boolean;
}
export interface DropdownMenuProps {
    options: DropdownMenuItem[];
    label?: string;
    variant?: string;
    open?: boolean;
    drawer?: boolean;
}
