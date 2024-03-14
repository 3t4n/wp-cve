<?php

namespace TotalContestVendors\TotalCore\Form;

use TotalContestVendors\TotalCore\Application;
use TotalContestVendors\TotalCore\Contracts\Form\Page as PageContract;
use TotalContestVendors\TotalCore\Helpers\Arrays;
use TotalContestVendors\TotalCore\Helpers\Html;
use TotalContestVendors\TotalCore\Http\MimeTypes;

/**
 * Class Field
 *
 * @package TotalContestVendors\TotalCore\Form
 */
abstract class Field implements \TotalContestVendors\TotalCore\Contracts\Form\Field
{

    protected $default = null;
    protected $name = null;
    protected $value = null;
    protected $options = [];
    protected $errors = [];
    protected $template = '<div class="{{slug}}-form-field {{slug}}-form-field-type-{{type}} {{slug}}-column-full"><div class="{{slug}}-form-field-wrapper">{{label}}{{field}}<div class="{{slug}}-form-field-errors">{{errors}}</div></div></div>';
    protected $errorTemplate = '<div class="{{slug}}-form-field-errors-item">{{error}}</div>';
    protected $htmlElements = [];
    protected $cache = null;

    /**
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function setOption($key, $value)
    {
        $this->options = Arrays::setDotNotation($this->options, $key, $value);

        return $this;
    }

    /**
     * @return null
     */
    public function getValue()
    {
        return $this->value === null ? $this->default : Arrays::apply($this->value, 'wp_strip_all_tags');
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @param  array  $rules
     *
     * @return array|bool
     */
    public function validate($rules = [])
    {
        $rules = empty($rules) ? $this->getValidationsRules() : $rules;

        if (empty($rules)):
            return true;
        endif;

        // Validations
        foreach ((array)$rules as $rule => $ruleArgs):

            if (empty($ruleArgs['enabled'])):
                continue;
            endif;

            if (!is_array($ruleArgs)):
                $rule = current($rules);
                $ruleArgs = [];
            endif;

            $callback = $rule;

            $args = array_merge([$this], [$ruleArgs]);
            if (!empty($ruleArgs['callback'])):
                $callback = $ruleArgs['callback'];
            elseif (is_string($rule) && method_exists('\TotalContestVendors\TotalCore\Form\Validator', "is{$rule}")):
                $callback = ['\TotalContestVendors\TotalCore\Form\Validator', "is{$rule}"];
            endif;

            if (is_callable($callback)):
                $returnedValue = call_user_func_array($callback, $args);
                if ($returnedValue !== true):
                    $returnedValue = is_callable($returnedValue) ? $returnedValue($this, $args) : $returnedValue;
                    $this->errors[$rule] = str_replace(
                        ['{{label}}'],
                        [
                            $this->getOption('label', __('This field', Application::getInstance()->env('slug')))
                        ],
                        $returnedValue
                    );

                    break;
                endif;
            endif;

        endforeach;

        return empty($this->errors) ? true : $this->errors;
    }

    /**
     * @return array
     */
    public function getValidationsRules()
    {
        $rules = (array)$this->getOption('validations', []);

        return array_filter($rules, function ($rule) {return !empty($rule['enabled']);});
    }

    /**
     * @param      $key
     * @param  null  $default
     *
     * @return mixed|null
     */
    public function getOption($key, $default = null)
    {
        return Arrays::getDotNotation($this->options, $key, $default);
    }

    /**
     * @return mixed|null
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * @param  bool  $purgeCache
     *
     * @return mixed|null
     */
    public function render($purgeCache = false)
    {
        if ($this->cache === null || $purgeCache === true):
            if (empty($this->htmlElements['label'])):
                $this->htmlElements['label'] = $this->getLabelHtmlElement();
            endif;

            if (empty($this->htmlElements['field'])):
                $this->htmlElements['field'] = $this->getInputHtmlElement();
            endif;

            if (!empty($this->options['attributes']['class'])):
                $this->htmlElements['field']->appendToAttribute('class', $this->options['attributes']['class']);
            endif;

            if (!empty($this->errors)):
                $errors = array_map(
                    function ($item) {
                        return str_replace(
                            [
                                '{{error}}',
                                '{{slug}}',
                            ],
                            [
                                $item,
                                Application::getInstance()
                                                      ->env('slug'),
                            ],
                            $this->errorTemplate
                        );
                    },
                    $this->errors
                );
            endif;

            $placeholders = [
                '{{type}}'   => sanitize_title_with_dashes($this->htmlElements['field']->getAttribute('type', $this->getOption('type', 'unknown'))),
                '{{slug}}'   => Application::getInstance()
                                                      ->env('slug'),
                '{{label}}'  => $this->htmlElements['label'],
                '{{field}}'  => $this->htmlElements['field'],
                '{{errors}}' => empty($this->errors) ? '' : implode('', $errors),
            ];

            $this->cache = str_replace(array_keys($placeholders), $placeholders, $this->template);
        endif;

        return $this->cache;
    }

    /**
     * @return mixed
     */
    public function getLabelHtmlElement()
    {
        $label = $this->getOption('label', ['content' => '']);
        $defaults = [
            'content' => is_array($label) ? $label['content'] : (string)$label,
            'for'     => $this->getOption('id'),
            'class'   => Application::getInstance()
                                               ->env('slug').'-form-field-label',
        ];

        if ($label):
            $attributes = Arrays::parse($label, $defaults);
            $content = $attributes['content'];
            unset($attributes['content']);
            $label = new Html('label', $attributes, $content);
        endif;

        return $label;
    }

    /**
     * @return mixed
     */
    abstract public function getInputHtmlElement();

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param $options
     *
     * @return mixed
     */
    public function setOptions($options)
    {
        if (!empty($options['options']) && is_string($options['options'])):

            $options['options'] = (array)preg_split("/(\r\n|\n|\r)/", $options['options']);
            $optionsArray = [];
            foreach ($options['options'] as $index => $option):
                unset($options['options'][$index]);

                $option = explode(':', $option, 2);
                $option[1] = empty($option[1]) ? $option[0] : $option[1];
                $key = trim($option[0]);
                $value = trim($option[1]);

                if ($value === ''):
                    continue;
                endif;

                $optionsArray[$key] = $value;
            endforeach;

            $options['options'] = $optionsArray;

        endif;

        if (!empty($options['default'])):
            $this->default = $options['default'];
            if (preg_match('/[\n\r]/', $this->default)):
                $this->default = preg_split('/[\n\r]/', $this->default);
            endif;
        endif;

        if (!empty($options['template'])):
            $this->template = $options['template'];
        endif;

        $options['validations'] = empty($options['validations']) ? [] : (array)$options['validations'];

        $this->options = Arrays::parse($options, $this->options);

        return $this;
    }

    /**
     * @param  PageContract  $page
     */
    public function onAttach(PageContract $page)
    {
        // TODO: Implement onAttach() method.
    }

    /**
     * @param  PageContract  $page
     */
    public function onDetach(PageContract $page)
    {
        // TODO: Implement onDetach() method.
    }

    /**
     * @return array|mixed
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id'          => $this->getOption('id'),
            'type'        => substr(strrchr(get_class($this), '\\'), 1),
            'name'        => $this->getName(),
            'field'       => $this->getOption('name'),
            'label'       => $this->getOption('label'),
            'placeholder' => $this->getOption('placeholder'),
            'default'     => $this->getDefault(),
            'options'     => $this->getOption('options'),
            'attributes'  => array_diff_key($this->getAttributes(), ['name' => true, 'id' => true, 'placeholder' => true]),
            'validations' => $this->getValidationsRules(),
        ];
    }

    /**
     * @return mixed|null
     */
    public function getName()
    {
        return empty($this->name) ? $this->getOption('name', $this->getOption('id', uniqid())) : $this->name;
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return null
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param $default
     *
     * @return $this
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        $attributes = array_merge(
            array_diff_key(
                $this->options,
                array_flip(['options', 'label', 'default', 'validations', 'attributes', 'template'])
            ),
            $this->getHtmlValidationAttributes(),
            $this->getOption('attributes', [])
        );

        return $attributes;
    }

    /**
     * @return array
     */
    public function getHtmlValidationAttributes()
    {
        $attributes = [];

        $validations = $this->getOption('validations', []);

        if (!empty($validations['filled']['enabled'])):
            $attributes['required'] = '';
        endif;

        if (!empty($validations['email']['enabled'])):
            $attributes['type'] = 'email';
        endif;

        if (!empty($validations['regex']['enabled'])):
            $attributes['pattern'] = esc_attr($validations['regex']['pattern']);
        endif;

        if (!empty($validations['size']['enabled'])):
            if (!empty($validations['size']['min'])):
                $attributes['minlength'] = absint($validations['size']['min']);
            endif;
            if (!empty($validations['size']['max'])):
                $attributes['maxlength'] = absint($validations['size']['max']);
            endif;
        endif;

        // Numeric min value
        if (!empty($validations['min']['enabled'])):
            if (!empty($validations['min']['value'])):
                $attributes['min'] = absint($validations['min']['value']);
            endif;
        endif;

        // Numeric max value
        if (!empty($validations['max']['enabled'])):
            if (!empty($validations['max']['value'])):
                $attributes['max'] = absint($validations['max']['value']);
            endif;
        endif;

        if (!empty($validations['formats']['enabled'])):
            $accept = [];
            $allowedExtensions = [];

            if (!is_array($validations['formats']['extensions'])):
                $validations['formats']['extensions'] = array_flip(preg_split('/(\s*(\||\,|\-)\s*)/m', trim($validations['formats']['extensions'])));
            endif;

            foreach ($validations['formats']['extensions'] as $allowedExtension => $enabled):
                $allowedExtensions[] = $allowedExtension;
                foreach (MimeTypes::$list as $mimetype => $extension):
                    if ($extension === $allowedExtension):
                        $accept[] = $mimetype;
                    endif;
                endforeach;
            endforeach;
            $attributes['accept'] = implode(',', $accept);
            $attributes['formats'] = implode(', ', $allowedExtensions);
        endif;

        return $attributes;
    }

    public function getType() {
        return 'text';
    }
}
