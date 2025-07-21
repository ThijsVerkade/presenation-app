export interface DatePickerProps {
    modelValue?: string | Date | [Date, Date] | null;
    type?: 'single' | 'range' | 'time';
    minDate?: string | Date | null;
    maxDate?: string | Date | null;
    disabled?: boolean;
    placeholder?: string;
    dateFormat?: string;
    timeFormat?: string;
}
