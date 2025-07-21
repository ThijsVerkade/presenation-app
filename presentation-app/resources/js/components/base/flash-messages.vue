<template>
    <teleport to="body">
        <div class="u-fixed u-right-4 u-bottom-4 u-left-4 md:u-left-auto u-z-[10000]">
            <transition name="fade-in-up">
                <Toast :autoclose="true" :message="message" :type="type" v-if="show"></Toast>
            </transition>
        </div>
    </teleport>
</template>
<script setup lang="ts">
import Toast from '@components/base/toast.vue';
import { useToast } from '@composables/useToast';
import { usePage } from '@inertiajs/vue3';
import { watch } from 'vue';

const { show, message, type, toastSuccess, toastError } = useToast();

const page = usePage();

watch(
    () => page.props.flash?.success,
    value => {
        if (value) {
            toastSuccess(value);
            page.props.flash.success = null;
        }
    }
);

watch(
    () => page.props.flash?.error,
    value => {
        if (value) {
            toastError(value);
            page.props.flash.error = null;
        }
    }
);
</script>
