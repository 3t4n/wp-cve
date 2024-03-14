<?php

namespace luckywp\glossary\admin\widgets;

use luckywp\glossary\core\base\Widget;
use luckywp\glossary\core\Core;
use luckywp\glossary\core\helpers\Html;

class AccessRoles extends Widget
{

    /**
     * @var array
     */
    public $field;

    /**
     * @var bool
     */
    public $fake = false;

    public function run()
    {
        $value = Core::$plugin->settings->getValue($this->field['group'], $this->field['id'], [], false);
        if (!is_array($value)) {
            $value = [];
        }

        // Роли
        $roles = [];
        foreach (wp_roles()->roles as $role => $details) {
            $roles[$role] = translate_user_role($details['name']);
        }

        // HTML
        $html = Html::hiddenInput($this->field['name']);
        foreach ($roles as $roleId => $roleName) {
            $options = [
                'label' => $roleName,
                'value' => $roleId,
            ];
            $name = $this->field['name'] . '[]';
            $checked = in_array($roleId, $value);
            if ($roleId == 'administrator') {
                $checked = true;
                $options['disabled'] = true;
            }
            if ($this->fake) {
                $name = '';
                $options['disabled'] = true;
                $options['label'] = '<span class="lwpglsColorMuted">' . $options['label'] . '</span>';
            }
            $html .= '<p>' . Html::checkbox($name, $checked, $options) . '</p>';
        }
        return $html;
    }
}
