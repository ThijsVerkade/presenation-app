import { createI18n } from 'vue-i18n';
import { i18nStrings } from '@i18n/i18n';

export const i18n = createI18n({
    locale: window.locale || 'en_GB', // set locale
    fallbackLocale: 'en_GB',
    legacy: false,
    messages: i18nStrings
});
