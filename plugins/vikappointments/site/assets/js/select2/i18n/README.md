## Internationalization (i18n)

Select2 supports multiple languages by simply including the right language JS
file (`select2_locale_it.js`, `select2_locale_nl.js`, etc.) after `select2.js`.

Looking for a specific language? Just check if it is already available from here:
https://github.com/ericevenchick/trainstats/tree/master/web/select2-3.5.4

In case it exists, download it and copy it in this **i18n** folder.
The file name must be built as `select2_locale_[LANG].js`.

Missing a language? Just copy `select2_locale_en.js.template` from the parent folder,
translate it and push it in this folder with the right name.
