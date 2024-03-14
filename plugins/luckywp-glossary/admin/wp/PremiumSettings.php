<?php

namespace luckywp\glossary\admin\wp;

use luckywp\glossary\core\base\BaseObject;
use luckywp\glossary\core\helpers\ArrayHelper;
use luckywp\glossary\core\helpers\Html;

class PremiumSettings extends BaseObject
{

    /**
     * @param $field
     */
    public static function checkbox($field)
    {

        // Параметры
        $params = $field['params'];
        $checkboxOptions = isset($params['checkboxOptions']) ? $params['checkboxOptions'] : [];
        $checkboxOptions['disabled'] = true;

        $label = ArrayHelper::getValue($checkboxOptions, 'label', '');
        $label = '<span class="lwpglsColorMuted">' . $label . '</span>';
        $checkboxOptions['label'] = $label;

        // Вывод
        echo Html::checkbox('', false, $checkboxOptions);
        if ($field['desc'] != '') {
            echo '<p class="description lwpglsColorMuted">' . $field['desc'] . '</p>';
        }
    }
}
