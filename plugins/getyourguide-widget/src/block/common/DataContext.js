import { createContext } from 'react';

export default createContext({
  currency: {
    USD: {
      value: 'USD',
      label: 'US$ US Dollar',
    },
    EUR: {
      value: 'EUR',
      label: '€ Euro',
    },
    AUD: {
      value: 'AUD',
      label: 'AU$ Australian Dollar',
    },
    GBP: {
      value: 'GBP',
      label: '￡ British Pound',
    },
    CAD: {
      value: 'CAD',
      label: 'C$ Canadian Dollar',
    },
    CSK: {
      value: 'CSK',
      label: 'Kč Czech koruna',
    },
    DKK: {
      value: 'DKK',
      label: 'DKK Danish Krone',
    },
    HKD: {
      value: 'HKD',
      label: 'HK$ Hong Kong Dollar',
    },
    JPY: {
      value: 'JPY',
      label: '¥ Japanese Yen',
    },
    NZD: {
      value: 'NZD',
      label: 'NZ$ New Zealand Dollar',
    },
    NOK: {
      value: 'NOK',
      label: 'NOK Norwegian krone',
    },
    PLN: {
      value: 'PLN',
      label: 'zł Polish Złoty',
    },
    SGD: {
      value: 'SGD',
      label: 'S$ Singapore Dollar',
    },
    SEK: {
      value: 'SEK',
      label: 'SEK Swedish Krona',
    },
    CHF: {
      value: 'CHF',
      label: 'CHF Swiss Franc',
    },
    AED: {
      value: 'AED',
      label: 'د.إ UAE Dirham',
    },
  },
  locale_code: {
    'da-DK': { value: 'da-DK', label: 'Dansk' },
    'de-DE': { value: 'de-DE', label: 'Deutsch' },
    'de-AT': { value: 'de-AT', label: 'Deutsch (Österreich)' },
    'de-CH': { value: 'de-CH', label: 'Deutsch (Schweiz)' },
    'en-US': { value: 'en-US', label: 'English' },
    'en-GB': { value: 'en-GB', label: 'English (United Kingdom)' },
    'es-ES': { value: 'es-ES', label: 'Español' },
    'es-MX': { value: 'es-MX', label: 'Español (México)' },
    'fr-FR': { value: 'fr-FR', label: 'Français' },
    'it-IT': { value: 'it-IT', label: 'Italiano' },
    'nl-NL': { value: 'nl-NL', label: 'Nederlands' },
    'no-NO': { value: 'no-NO', label: 'Norsk' },
    'pl-PL': { value: 'pl-PL', label: 'Polski' },
    'pt-PT': { value: 'pt-PT', label: 'Português' },
    'pt-BR': { value: 'pt-BR', label: 'Português (Brasil)' },
    'fi-FI': { value: 'fi-FI', label: 'Suomen kieli' },
    'sv-SE': { value: 'sv-SE', label: 'Svenska' },
    'tr-TR': { value: 'tr-TR', label: 'Türkçe' },
    'ru-RU': { value: 'ru-RU', label: 'Русский' },
    'ja-JP': { value: 'ja-JP', label: '日本語' },
    'zh-CN': { value: 'zh-CN', label: '简体中文' },
    'zh-TW': { value: 'zh-TW', label: '繁體中文' },
  },
});
