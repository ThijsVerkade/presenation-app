<template>
    <div :class="blockClass">
        <flat-pickr :model-value="modelValue" :config="flatpickrConfig" :disabled="disabled" :placeholder="placeholder" />
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import FlatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import { useBEM } from '@helpers/bem';
import type { DatePickerProps } from '@interfaces/date-picker';
import Default from 'flatpickr/dist/l10n/default';
import Dutch from 'flatpickr/dist/l10n/nl';
import { i18n } from '@base/i18n';

const mappedLocale: Record<string, any> = {
    en_GB: Default,
    nl_NL: Dutch.nl
};

const { t, locale } = i18n.global;
const props = defineProps<DatePickerProps>();
const emit = defineEmits<{
    (event: 'onChange', value: Date | Date[]): void;
}>();

const {
    modelValue = null,
    type = 'single',
    minDate = null,
    maxDate = null,
    disabled = false,
    placeholder = t('components.date-picker.placeholder'),
    dateFormat = 'Y-m-d',
    timeFormat = 'H:i'
} = props;

const blockName = 'c-date-picker';
const { blockClass } = useBEM(blockName, disabled ? ['disabled'] : []);

const onUpdate = (selectedDates: Date[]) => {
    emit('onChange', selectedDates.length === 1 ? selectedDates[0] : selectedDates);
};

const flatpickrConfig = computed(() => ({
    minDate,
    maxDate,
    mode: type === 'range' ? 'range' : 'single',
    dateFormat: type === 'time' ? timeFormat : dateFormat,
    enableTime: type === 'time',
    noCalendar: type === 'time',
    time_24hr: true,
    altFormat: 'F j, Y H:i',
    onChange: onUpdate,
    locale: mappedLocale[locale] || 'en'
}));
</script>
