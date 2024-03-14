<?php
/** @noinspection PhpIllegalPsrClassPathInspection */

class MM_WPFS_Shortcode {
    const SHORTCODE_PATTERN = '[fullstripe_form name="%s" type="%s"]';

    /**
     * @param $form
     *
     * @return bool|string
     */
    public static function createShortCodeByForm($form) {
        if (MM_WPFS::FORM_TYPE_PAYMENT === $form->type &&
            MM_WPFS::FORM_LAYOUT_INLINE === $form->layout) {
            return sprintf(self::SHORTCODE_PATTERN, $form->name, MM_WPFS::FORM_TYPE_INLINE_PAYMENT);
        } elseif (MM_WPFS::FORM_TYPE_SAVE_CARD === $form->type &&
            MM_WPFS::FORM_LAYOUT_INLINE === $form->layout) {
            return sprintf(self::SHORTCODE_PATTERN, $form->name, MM_WPFS::FORM_TYPE_INLINE_SAVE_CARD);
        } elseif (MM_WPFS::FORM_TYPE_SUBSCRIPTION === $form->type &&
            MM_WPFS::FORM_LAYOUT_INLINE === $form->layout) {
            return sprintf(self::SHORTCODE_PATTERN, $form->name, MM_WPFS::FORM_TYPE_INLINE_SUBSCRIPTION);
        } elseif (MM_WPFS::FORM_TYPE_DONATION === $form->type &&
            MM_WPFS::FORM_LAYOUT_INLINE === $form->layout) {
            return sprintf(self::SHORTCODE_PATTERN, $form->name, MM_WPFS::FORM_TYPE_INLINE_DONATION);
        } elseif (MM_WPFS::FORM_TYPE_PAYMENT === $form->type &&
            MM_WPFS::FORM_LAYOUT_CHECKOUT === $form->layout) {
            return sprintf(self::SHORTCODE_PATTERN, $form->name, MM_WPFS::FORM_TYPE_CHECKOUT_PAYMENT);
        } elseif (MM_WPFS::FORM_TYPE_SAVE_CARD === $form->type &&
            MM_WPFS::FORM_LAYOUT_CHECKOUT === $form->layout) {
            return sprintf(self::SHORTCODE_PATTERN, $form->name, MM_WPFS::FORM_TYPE_CHECKOUT_SAVE_CARD);
        } elseif (MM_WPFS::FORM_TYPE_SUBSCRIPTION === $form->type &&
            MM_WPFS::FORM_LAYOUT_CHECKOUT === $form->layout) {
            return sprintf(self::SHORTCODE_PATTERN, $form->name, MM_WPFS::FORM_TYPE_CHECKOUT_SUBSCRIPTION);
        } elseif (MM_WPFS::FORM_TYPE_DONATION === $form->type &&
            MM_WPFS::FORM_LAYOUT_CHECKOUT == $form->layout) {
            return sprintf(self::SHORTCODE_PATTERN, $form->name, MM_WPFS::FORM_TYPE_CHECKOUT_DONATION);
        }

        return false;
    }

    /**
     * @param $type string
     * @return string
     */
    public static function normalizeShortCodeFormType( $type ) {
        $result = $type;

        switch ($type) {
            case MM_WPFS::FORM_TYPE_PAYMENT:
                $result = MM_WPFS::FORM_TYPE_INLINE_PAYMENT;
                break;

            case MM_WPFS::FORM_TYPE_SUBSCRIPTION:
                $result = MM_WPFS::FORM_TYPE_INLINE_SUBSCRIPTION;
                break;

            case MM_WPFS::FORM_TYPE_POPUP_PAYMENT:
                $result = MM_WPFS::FORM_TYPE_CHECKOUT_PAYMENT;
                break;

            case MM_WPFS::FORM_TYPE_POPUP_SUBSCRIPTION:
                $result = MM_WPFS::FORM_TYPE_CHECKOUT_SUBSCRIPTION;
                break;

            case MM_WPFS::FORM_TYPE_POPUP_SAVE_CARD:
                $result = MM_WPFS::FORM_TYPE_CHECKOUT_SAVE_CARD;
                break;

            case MM_WPFS::FORM_TYPE_POPUP_DONATION:
                $result = MM_WPFS::FORM_TYPE_CHECKOUT_DONATION;
                break;
        }

        return $result;
    }
}