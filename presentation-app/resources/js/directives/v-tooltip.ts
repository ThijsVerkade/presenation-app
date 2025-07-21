// Add interfaces for better type safety
interface TooltipState {
    tooltip: HTMLElement | null;
    content: string;
    showTimeout: number | null;
    cleanup?: () => void;
}

interface TooltipBinding {
    value: string;
    modifiers: Record<string, boolean>;
    arg?: string;
}

const tooltipMap = new WeakMap<HTMLElement, TooltipState>();

const tooltipDirective = {
    mounted(el: HTMLElement, binding: TooltipBinding) {
        // Create a single tooltip element that will be reused
        const state: TooltipState = {
            tooltip: null,
            content: binding.value,
            showTimeout: null
        };
        tooltipMap.set(el, state);

        const getDelay = (): number => {
            const delayModifier = Object.keys(binding.modifiers).find(mod => !isNaN(Number(mod)));
            return delayModifier ? Number(delayModifier) : 0;
        };

        const createTooltip = () => {
            if (state.tooltip) return state.tooltip;

            const tooltip = document.createElement('div');
            tooltip.classList.add('c-tooltip');

            // Support HTML content if arg is 'html'
            if (binding.arg === 'html') {
                tooltip.innerHTML = state.content;
            } else {
                tooltip.textContent = state.content;
            }

            tooltip.style.position = 'absolute';
            tooltip.style.visibility = 'hidden';
            tooltip.style.opacity = '0';
            tooltip.style.zIndex = '9999';
            tooltip.style.transition = 'visibility 0s, opacity 0.2s ease';

            document.body.appendChild(tooltip);
            state.tooltip = tooltip;
            return tooltip;
        };

        const positionTooltip = () => {
            const tooltip = tooltipMap.get(el)?.tooltip;

            if (!tooltip) return;

            const rect = el.getBoundingClientRect();
            const tooltipWidth = tooltip.offsetWidth;
            const tooltipHeight = tooltip.offsetHeight;
            let position = 'top';

            if (binding.modifiers.top) {
                position = 'top';
            } else if (binding.modifiers.bottom) {
                position = 'bottom';
            } else if (binding.modifiers.left) {
                position = 'left';
            } else if (binding.modifiers.right) {
                position = 'right';
            }

            switch (position) {
                case 'top':
                    tooltip.style.top = `${rect.top + window.scrollY - tooltipHeight - 4}px`;
                    tooltip.style.left = `${rect.left + (rect.width - tooltipWidth) / 2}px`;
                    break;
                case 'bottom':
                    tooltip.style.top = `${rect.bottom + window.scrollY + 4}px`;
                    tooltip.style.left = `${rect.left + (rect.width - tooltipWidth) / 2}px`;
                    break;
                case 'left':
                    tooltip.style.top = `${rect.top + window.scrollY + (rect.height - tooltipHeight) / 2}px`;
                    tooltip.style.left = `${rect.left - tooltipWidth - 4}px`;
                    break;
                case 'right':
                    tooltip.style.top = `${rect.top + window.scrollY + (rect.height - tooltipHeight) / 2}px`;
                    tooltip.style.left = `${rect.right + 4}px`;
                    break;
            }
        };

        const showTooltip = () => {
            const delay = getDelay();

            if (state.showTimeout) {
                clearTimeout(state.showTimeout);
            }

            if (delay > 0) {
                state.showTimeout = window.setTimeout(() => {
                    const tooltip = createTooltip();
                    positionTooltip();
                    requestAnimationFrame(() => {
                        tooltip.style.visibility = 'visible';
                        tooltip.style.opacity = '1';
                    });
                }, delay);
            } else {
                const tooltip = createTooltip();
                positionTooltip();
                requestAnimationFrame(() => {
                    tooltip.style.visibility = 'visible';
                    tooltip.style.opacity = '1';
                });
            }
        };

        const hideTooltip = () => {
            if (state.showTimeout) {
                clearTimeout(state.showTimeout);
                state.showTimeout = null;
            }

            if (state.tooltip) {
                state.tooltip.style.visibility = 'hidden';
                state.tooltip.style.opacity = '0';
                state.tooltip.remove();
                state.tooltip = null;
            }
        };

        // Store cleanup function
        state.cleanup = () => {
            el.removeEventListener('mouseenter', showTooltip);
            el.removeEventListener('mouseleave', hideTooltip);
            window.removeEventListener('resize', positionTooltip);
            window.removeEventListener('scroll', positionTooltip);

            if (state.tooltip) {
                state.tooltip.remove();
                state.tooltip = null;
            }
        };

        // Add event listeners
        el.addEventListener('mouseenter', showTooltip);
        el.addEventListener('mouseleave', hideTooltip);
        window.addEventListener('resize', positionTooltip);
        window.addEventListener('scroll', positionTooltip);
    },

    updated(el: HTMLElement, binding: TooltipBinding) {
        const state = tooltipMap.get(el);
        if (state) {
            state.content = binding.value;
            if (state.tooltip) {
                if (binding.arg === 'html') {
                    state.tooltip.innerHTML = binding.value;
                } else {
                    state.tooltip.textContent = binding.value;
                }
            }
        }
    },

    unmounted(el: HTMLElement) {
        const state = tooltipMap.get(el);
        if (state?.cleanup) {
            state.cleanup();
        }
        tooltipMap.delete(el);
    }
};

export default tooltipDirective;
