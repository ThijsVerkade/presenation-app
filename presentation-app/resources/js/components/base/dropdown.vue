<template>
    <teleport to="body">
        <div class="c-dropdown" v-if="active" ref="dropdown">
            <slot />
        </div>
    </teleport>
</template>
<script setup lang="ts">
import { usePopover } from '@base/composables/usePopover';
import { ref, watch, nextTick, onMounted, onBeforeUnmount, computed } from 'vue';

const props = defineProps<{
    target: HTMLElement | null;
    placement?:
        | 'top'
        | 'bottom'
        | 'left'
        | 'right'
        | 'top-start'
        | 'top-end'
        | 'bottom-start'
        | 'bottom-end'
        | 'left-start'
        | 'left-end'
        | 'right-start'
        | 'right-end';
    offset?: [number, number];
    modelValue?: boolean;
}>();

const active = computed(() => (props.modelValue === null ? true : props.modelValue));

const dropdown = ref<HTMLElement | null>(null);

const { setTarget, close: closeSubMenu } = usePopover({
    target: props.target,
    popover: dropdown.value,
    placement: props.placement || 'top',
    offset: props.offset || [0, -6]
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: boolean): void;
}>();

// Add event listener for clicks outside the dropdown
const handleClickOutside = (event: MouseEvent) => {
    const clickTarget = event.target as Node;
    if (dropdown.value && !dropdown.value.contains(clickTarget) && props.target && !props.target.contains(clickTarget)) {
        closeWrapper();
    }
};

watch(
    () => props.modelValue,
    newValue => {
        if (newValue) {
            nextTick(() => {
                setTarget({ target: props.target, popover: dropdown.value });
            });
        }
    }
);

onMounted(() => {
    nextTick(() => {
        setTarget({ target: props.target, popover: dropdown.value });
    });

    document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
});

const closeWrapper = () => {
    emit('update:modelValue', false);
};
</script>
