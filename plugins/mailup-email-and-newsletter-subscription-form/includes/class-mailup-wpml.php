<?php

declare(strict_types=1);

class Mailup_WPML
{
    public static function getTranslationFields($form, $typeFields)
    {
        if (!class_exists('SitePress')) {
            return $form;
        }

        $form->terms = array_map(
            static function ($term) {
                $term->text = self::getTranslationString($term->text, 'privacy-'.$term->id);

                return $term;
            },
            $form->terms
        );
        $form->title = self::getTranslationString($form->title, 'form-title');
        $form->description = self::getTranslationString($form->description, 'form-description');
        $form->submit_text = self::getTranslationString($form->submit_text, 'form-submit-text');
        $fieldsTranslated = self::prepareFormFieldsTranslations($form->fields, $typeFields);

        $form->fields = array_map(
            static function ($field) use ($fieldsTranslated) {
                $label = array_search($field->name, $fieldsTranslated, true);
                $field->name = self::getTranslationString($field->name, $label);

                return $field;
            },
            $form->fields
        );

        return $form;
    }

    public static function getTranslationMessages($messages)
    {
        if (!class_exists('SitePress')) {
            return $messages;
        }

        foreach ($messages as $key => $value) {
            $messages[$key] = self::getTranslationString($value, $key);
        }

        return $messages;
    }

    public static function registerTerms($terms): void
    {
        if (!class_exists('SitePress')) {
            return;
        }

        $termsToRegister = self::prepareTermsTranslation($terms);

        self::registerWpml($termsToRegister);
    }

    public static function registerForm($form, $type_fields): void
    {
        if (!class_exists('SitePress')) {
            return;
        }

        $formToRegister = self::prepareFormsTranslation($form, $type_fields);

        self::registerWpml($formToRegister);
    }

    public static function registerMessages($messages): void
    {
        if (!class_exists('SitePress')) {
            return;
        }

        self::registerWpml($messages);
    }

    protected static function prepareTermsTranslation($terms)
    {
        $fieldsToTranslate = [];

        foreach ($terms as $term) {
            $fieldsToTranslate['privacy-'.$term->id] = $term->text;
        }

        return $fieldsToTranslate;
    }

    protected static function prepareFormsTranslation($form, $type_fields)
    {
        return array_merge(
            [
                'form-title' => $form->title,
                'form-description' => $form->description,
                'form-submit-text' => $form->submit_text,
            ],
            self::prepareFormFieldsTranslations($form->fields, $type_fields)
        );
    }

    protected static function prepareFormFieldsTranslations($fields, $type_fields)
    {
        $fieldsToTranslate = [];

        foreach ($fields as $field) {
            $fieldId = $field->id;
            $label_obj = array_filter(
                $type_fields,
                static function ($e) use (&$fieldId) {
                    return $e->id === $fieldId;
                }
            );

            $label = reset($label_obj)->name;
            $fieldsToTranslate['form-field-'.$label] = $field->name;
        }

        return $fieldsToTranslate;
    }

    private static function registerWpml($stringsToTranslate): void
    {
        foreach ($stringsToTranslate as $key => $value) {
            do_action('wpml_register_single_string', 'mailup-form', $key, $value, false, ICL_LANGUAGE_CODE);
        }
    }

    private static function getTranslationString($value, $key)
    {
        return apply_filters('wpml_translate_single_string', $value, 'mailup-form', $key);
    }
}
