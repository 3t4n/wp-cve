import LocalizedStrings from "../libs/localized-strings/LocalizedStrings.js";

import { english } from './english.js';
import { russian } from './russian.js';

let translate = new LocalizedStrings({
    en: english,
    ru: russian
}, {
    customLanguageInterface: () => {
        return jQuery('html').attr('lang');
    }
});

export { translate };