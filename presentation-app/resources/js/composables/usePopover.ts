import { ref, onMounted, onUpdated, onBeforeUnmount, watchEffect } from 'vue';
import { createPopper, type Placement, type Instance } from '@popperjs/core';

interface PopoverProps {
    placement?: Placement;
    offset?: number[];
    target?: HTMLElement | null;
    popover?: HTMLElement | null;
}

export function usePopover({ placement = 'auto', offset = [0, 6], target = null, popover = null }: PopoverProps = {}) {
    const targetElement = ref<HTMLElement | null>(target);
    const popoverTarget = ref<HTMLElement | null>(popover);
    const offsetValue = ref(offset);
    const visible = ref(false);
    const popperInstance = ref<Instance | null>(null);

    const setTarget = ({
        target,
        popover,
        offset
    }: {
        target: HTMLElement | null;
        popover: HTMLElement | null;
        offset?: number[];
    }) => {
        targetElement.value = target;
        popoverTarget.value = popover;
        if (offset) {
            offsetValue.value = offset;
        }
        positionPopover();
    };

    const positionPopover = () => {
        if (popperInstance.value) {
            popperInstance.value.destroy();
        }

        if (targetElement.value && popoverTarget.value) {
            createPopper(targetElement.value, popoverTarget.value, {
                placement,
                modifiers: [
                    {
                        name: 'offset',
                        options: {
                            offset: offsetValue.value
                        }
                    }
                ]
            });
        }
    };

    watchEffect(() => {
        if (visible.value) {
            positionPopover();
        }
    });

    const closeOnClickOutside = (event: MouseEvent) => {
        if (
            popoverTarget.value &&
            !popoverTarget.value.contains(event.target as Node) &&
            !(targetElement.value instanceof Element && targetElement.value.contains(event.target as Node))
        ) {
            visible.value = false;
        }
    };

    onMounted(() => {
        positionPopover();
        document.addEventListener('click', closeOnClickOutside);
    });

    onBeforeUnmount(() => {
        if (popperInstance.value) {
            popperInstance.value.destroy();
        }
        document.removeEventListener('click', closeOnClickOutside);
    });

    const toggle = () => {
        visible.value = !visible.value;
    };

    const open = () => {
        visible.value = true;
    };

    const close = () => {
        visible.value = false;
    };

    return {
        visible,
        toggle,
        open,
        close,
        setTarget
    };
}
