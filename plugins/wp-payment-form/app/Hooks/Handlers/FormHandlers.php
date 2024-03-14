<?php

namespace WPPayForm\App\Hooks\Handlers;

use WPPayForm\App\Models\Form;

class FormHandlers
{
    public static function insertTemplate($formId, $data, $template)
    {
        return Form::insertTemplateForm($formId, $data, $template);
    }
}
