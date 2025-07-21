import { format, isToday, isYesterday, formatDistanceToNow } from 'date-fns';
import { useI18n } from 'vue-i18n';

export const formatDate = (date: Date, formatStr: string = 'dd MMM, HH:mm', withYesterday: boolean = false): string => {
    const { t } = useI18n();

    if (isToday(date)) {
        const relativeTime = formatDistanceToNow(date, { addSuffix: false });
        const [num, unit] = relativeTime.split(' ');
        if (unit.includes('minute')) return `${num}m`;
        if (unit.includes('hour')) return `${num}h`;
        return relativeTime;
    } else if (isYesterday(date) && withYesterday) {
        return t('components.inbox.yesterday');
    } else {
        return format(date, formatStr);
    }
};

export const formatFullDate = (date: Date): string => {
    return format(date, 'dd-MM-yyyy');
};
