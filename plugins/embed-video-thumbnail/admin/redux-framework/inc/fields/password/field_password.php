<?php

    /**
     * Class ReduxFramework_password.
     */

// Exit if accessed directly
    if (!\defined('ABSPATH')) {
        exit;
    }

    if (!class_exists('ReduxFramework_password')) {
        class ReduxFramework_password
        {
            /**
             * Field Constructor.
             * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function.
             *
             * @since ReduxFramework 1.0.1
             *
             * @param mixed $field
             * @param mixed $value
             * @param mixed $parent
             */
            public function __construct($field = [], $value = '', $parent)
            {
                $this->parent = $parent;
                $this->field = $field;
                $this->value = $value;
            }

            /**
             * Field Render Function.
             * Takes the vars and outputs the HTML for the field in the settings.
             *
             * @since ReduxFramework 1.0.1
             */
            public function render()
            {
                if (!empty($this->field['username']) && true === $this->field['username']) {
                    $this->_render_combined_field();
                } else {
                    $this->_render_single_field();
                }
            }

            /**
             * This will render a combined User/Password field.
             *
             * @since ReduxFramework 3.0.9
             *
             * @example
             *        <code>
             *        array(
             *        'id'          => 'smtp_account',
             *        'type'        => 'password',
             *        'username'    => true,
             *        'title'       => 'SMTP Account',
             *        'placeholder' => array('username' => 'Username')
             *        )
             *        </code>
             */
            private function _render_combined_field()
            {
                $defaults = [
                    'username' => '',
                    'password' => '',
                    'placeholder' => [
                        'password' => __('Password', 'redux-framework'),
                        'username' => __('Username', 'redux-framework'),
                    ],
                ];

                $this->value = wp_parse_args($this->value, $defaults);

                if (!empty($this->field['placeholder'])) {
                    if (\is_array($this->field['placeholder'])) {
                        if (!empty($this->field['placeholder']['password'])) {
                            $this->value['placeholder']['password'] = $this->field['placeholder']['password'];
                        }
                        if (!empty($this->field['placeholder']['username'])) {
                            $this->value['placeholder']['username'] = $this->field['placeholder']['username'];
                        }
                    } else {
                        $this->value['placeholder']['password'] = $this->field['placeholder'];
                    }
                }

                // Username field
                echo '<input type="text" autocomplete="off" placeholder="' . $this->value['placeholder']['username'] . '" id="' . $this->field['id'] . '[username]" name="' . $this->field['name'] . $this->field['name_suffix'] . '[username]' . '" value="' . esc_attr($this->value['username']) . '" class="' . $this->field['class'] . '" />&nbsp;';

                // Password field
                echo '<input type="password" autocomplete="off" placeholder="' . $this->value['placeholder']['password'] . '" id="' . $this->field['id'] . '[password]" name="' . $this->field['name'] . $this->field['name_suffix'] . '[password]' . '" value="' . esc_attr($this->value['password']) . '" class="' . $this->field['class'] . '" />';
            }

            /**
             * This will render a single Password field.
             *
             * @since ReduxFramework 3.0.9
             *
             * @example
             *        <code>
             *        array(
             *        'id'    => 'smtp_password',
             *        'type'  => 'password',
             *        'title' => 'SMTP Password'
             *        )
             *        </code>
             */
            private function _render_single_field()
            {
                echo '<input type="password" id="' . $this->field['id'] . '" name="' . $this->field['name'] . $this->field['name_suffix'] . '" value="' . esc_attr($this->value) . '" class="' . $this->field['class'] . '" />';
            }
        }
    }
