<?php

namespace AOP\App\Admin;

class Text
{
    public static function data()
    {
        return [
            'general' => [
                'sidebarTitle' => __('Publish'),
                'modal' => [
                    'heading' => __('Are you sure?'),
                    'cancel' => __('Cancel'),
                    'delete' => __('Delete')
                ],
                'role' => [
                    'administrator' => _x('Administrator', 'User role'),
                    'editor' => _x('Editor', 'User role')
                ]
            ],
            'page' => [
                'create' => [
                    'buttonSave' => __('Save'),
                    'h1Title' => __('New Pages')
                ],
                'update' => [
                    'buttonUpdate' => __('Update'),
                    'linkDelete' => __('Delete'),
                    'h1Title' => __('Edit Pages'),
                    'deleteDropdown' => [
                        'cancel' => __('Cancel'),
                        'delete' => __('Delete')
                    ]
                ]
            ]
        ];
    }
}
