<?php

namespace AOP\App\Admin;

use AOP\App\Validation;

class Notice extends Validation
{
    private static $delayClass         = ' has-delay';
    private static $noticeMessageClass = 'aop-notice__message';

    public static function succes($message = '', $delay = false)
    {
        if (get_current_screen()->id === 'toplevel_page_admin_options_pages_master') {
            printf(
                '<div class="notice notice-success settings-error is-dismissible"><p>%s</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>',
                htmlspecialchars($message)
            );
        } else {
            printf(
                '<div class="aop-snackbar-wrapper"><div id="%s" class="%s%s" tabindex="0" role="button"><p class="%s">%s</p></div></div>',
                'aop-snackbar',
                'aop-snackbar aop-snackbar--succes',
                $delay === true ? self::$delayClass : '',
                self::$noticeMessageClass,
                htmlspecialchars($message)
            );

            wp_add_inline_script(
                'aop-app-js',
                'var aopSnackbar=document.getElementById("aop-snackbar");setTimeout(function(){aopSnackbar.classList.add("aop-snackbar--remove")},3000),setTimeout(function(){aopSnackbar.outerHTML=""},5000);',
                'after'
            );
        }
    }
}
