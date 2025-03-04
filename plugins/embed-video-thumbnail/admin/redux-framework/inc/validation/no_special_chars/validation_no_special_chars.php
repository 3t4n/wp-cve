<?php

    if (!class_exists('Redux_Validation_no_special_chars')) {
        class Redux_Validation_no_special_chars
        {
            /**
             * Field Constructor.
             * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function.
             *
             * @since ReduxFramework 1.0.0
             *
             * @param mixed $parent
             * @param mixed $field
             * @param mixed $value
             * @param mixed $current
             */
            public function __construct($parent, $field, $value, $current)
            {
                $this->parent = $parent;
                $this->field = $field;
                $this->field['msg'] = (isset($this->field['msg'])) ? $this->field['msg'] : __('You must not enter any special characters in this field, all special characters have been removed.', 'redux-framework');
                $this->value = $value;
                $this->current = $current;

                $this->validate();
            }

            //function

            /**
             * Field Render Function.
             * Takes the vars and validates them.
             *
             * @since ReduxFramework 1.0.0
             */
            public function validate()
            {
                if (0 == !preg_match('/[^a-zA-Z0-9_ -]/s', $this->value)) {
                    $this->warning = $this->field;
                }

                $this->value = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $this->value);
            }

            //function
        } //class
    }
