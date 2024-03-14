<?php

namespace AOP\App;

use AOP\Lib\Illuminate\Support\Collection;

class Validation
{
    public function validateNonce($nonceName = '', $nonceAction = -1)
    {
        if (!isset($_POST[$nonceName]) || !wp_verify_nonce($_REQUEST[$nonceName], $nonceAction)) {
            return false;
        }

        return true;
    }

    public function pages()
    {
        if (!$this->pagesFound()) {
            return false;
        }

        if ($this->noPageTitleFound()) {
            return false;
        }

        if ($this->notCorrectFieldNames()) {
            return false;
        }

        if ($this->duplicatedFieldNames()) {
            return false;
        }

        if ($this->duplicatedOptionNames()) {
            return false;
        }

        return true;
    }

    public function customMenu()
    {
        if ($this->notCorrectMenuPosition()) {
            return false;
        }

        if ($this->notCorrectMenuTitle()) {
            return false;
        }

        if (!$this->menuIcon()) {
            return false;
        }

        return true;
    }

    public function existingMenu()
    {
        if ($this->notCorrectExistingMenu()) {
            return false;
        }

        return true;
    }

    public function existingMenuMenuSlug()
    {
        if (is_null($slug = $_REQUEST['existing_menu']['menu_slug'])) {
            return '';
        }

        $defaultSlugs = Collection::make(Data::defaultWordpressMenuPageSlugs());

        if (!$defaultSlugs->contains($slug)) {
            return $this->wpDieMessage('Unknown slug!');
        }

        return $slug;
    }

    /**
     * Validate the name and the slug for an existing WordPress menu.
     *
     * @return void
     */
    public function notCorrectExistingMenu()
    {
        if (is_null($slug = $_REQUEST['existing_menu']['menu_slug'])) {
            return;
        }

        $defaultSlugs = Collection::make(Data::defaultWordpressMenuPageSlugs());

        if (!$defaultSlugs->contains($slug)) {
            return $this->wpDieMessage('Unknown slug!');
        }

        return false;
    }

    /**
     * Validate if there are pages found.
     *
     * @return boolean
     */
    public function pagesFound()
    {
        if (!isset($_REQUEST['pages'])) {
            return false;
        }

        return true;
    }

    public function noPageTitleFound()
    {
        if (!isset($_REQUEST['pages'])) {
            return [];
        }

        $titles = Data::requestGetSubpages()->pluck('menu_title');

        $checkTitles = $titles->filter(function ($title) {
            if (!$title) {
                return;
            }

            return $title;
        })->count();

        if ($checkTitles !== $titles->count()) {
            return ($titles->count() - $checkTitles);
        }

        return false;
    }

    public function notCorrectFieldNames()
    {
        if (!isset($_REQUEST['pages'])) {
            return [];
        }

        // vardumper($_REQUEST['pages']);

        $list = Data::getFieldsWithFieldNames();

        // vardumper(Data::getFieldsWithFieldNames());

        $checkList = $list->filter(function ($name) {
            if (!$name) {
                return;
            }

            preg_match('/[_]*[A-Za-z0-9]\w+/', $name, $matches);

            if (!$matches) {
                return $this->wpDieMessage('No valid field name: ' . $name);
            }

            if (strlen($name) > 191) {
                return $this->wpDieMessage('The field name may not be greater than 191 characters: ' . $name);
            }

            return trim($name) === $matches[0];
        });

        return $list->diff($checkList)->all();
    }

    public function duplicatedFieldNames()
    {
        if (!isset($_REQUEST['pages'])) {
            return [];
        }

        $coutedFields = Collection::make(array_count_values(Data::getFieldsWithFieldNames()->toArray()));

        return $coutedFields->filter(function ($field) {
            return $field > 1;
        })->all();
    }

    public function duplicatedOptionNames()
    {
        if (!isset($_REQUEST['pages'])) {
            return [];
        }

        $acceptedFieldNames = Data::getFieldsWithFieldNames()
            ->diff(Data::getAllOptionNamesExcludeCurrentPageFieldNames())
            ->all();

        return Data::getFieldsWithFieldNames()
            ->diff($acceptedFieldNames)
            ->all();
    }

    public function notCorrectMenuPosition()
    {
        if (!isset($_REQUEST['menu']['menu_position'])) {
            return 'no request';
        }

        $menuPosition = $_REQUEST['menu']['menu_position'];

        if (!$menuPosition) {
            return 'empty';
        }

        preg_match('/^([0-9]{1,3}|150){1}(\.[0-9]{1,4})?$/', $menuPosition, $matches);

        if (!$matches) {
            return $menuPosition;
        }

        if (!$matches[0]) {
            return $menuPosition;
        }

        return false;
    }

    public function notCorrectMenuTitle()
    {
        if (!isset($_REQUEST['menu']['menu_title'])) {
            return 'no request';
        }

        $menuTitle = $_REQUEST['menu']['menu_title'];

        if (!$menuTitle) {
            return 'empty';
        }

        $sanitizedMenuTitle = strip_tags(sanitize_text_field($_REQUEST['menu']['menu_title']));

        if ($menuTitle !== $sanitizedMenuTitle) {
            $notValidCharacters = preg_replace("/[(\\\)$sanitizedMenuTitle]/", '', $menuTitle);

            return htmlspecialchars($notValidCharacters);
        }

        return false;
    }

    /**
     * Validate the menu icon.
     *
     * @return void|true
     */
    public function menuIcon()
    {
        if (!isset($_REQUEST['menu']['menu_icon'])) {
            return 'no request';
        }

        $menuIcon = $_REQUEST['menu']['menu_icon'];

        $menuIconName = preg_replace('/dashicons-/', '', $menuIcon);

        $correctIcon = Dashicons::allIconsCollection()->whereIn([0], $menuIconName)->all();

        if (!$correctIcon) {
            return $this->wpDieMessage(htmlspecialchars($menuIcon) . '? What are you doing?');
        }

        return true;
    }

    /**
     * Validate a class attribite.
     *
     * @param string $class
     * @return void
     */
    public function classAttribute($class = '')
    {
        if (empty($class)) {
            return $class;
        }

        $classNames = explode(' ', $class);

        if (!$this->valuesAreAlphanumericDashesAll($classNames)) {
            return $this->wpDieMessage('Wrong class names.');
        }

        return $class;
    }

    /**
     * Determine if the given $values are allowed.
     * Checked for alphanumeric and underscores.
     *
     * @param array $values
     * @return boolean
     */
    public function valuesAreAlphanumericUnderscore(array $values)
    {
        $values = Collection::make($values);

        $checkedValues = $values->filter(function ($value) {
            preg_match('/[A-Za-z0-9_]+/', $value, $matches);

            return trim($value) === $matches[0];
        })->all();

        return $values->diff($checkedValues)->all() ? false : true;
    }

    /**
     * Determine if the given $values are allowed.
     * Checked for alphanumeric, dashes and underscores.
     *
     * @param array $values
     * @return boolean
     */
    public function valuesAreAlphanumericDashesAll(array $values)
    {
        $values = Collection::make($values);

        $checkedValues = $values->filter(function ($value) {
            preg_match('/[A-Za-z0-9_-]+/', $value, $matches);

            if (!$matches) {
                return false;
            }

            return trim($value) === $matches[0];
        })->all();

        return $values->diff($checkedValues)->all() ? false : true;
    }

    /**
     * Determine if the given $values are allowed.
     * Checked for alphanumeric.
     *
     * @param array $values
     * @return boolean
     */
    public function valuesAreAlphanumeric(array $values)
    {
        $values = Collection::make($values);

        $checkedValues = $values->filter(function ($value) {
            preg_match('/[A-Za-z0-9]+/', $value, $matches);

            return trim($value) === $matches[0];
        })->all();

        return $values->diff($checkedValues)->all() ? false : true;
    }

    /**
     * Determine if the given $values are all numbers.
     *
     * @param array $values
     * @param int|null $length
     * @return boolean
     */
    public function valuesAreNumbers(array $values, $length = null)
    {
        $values = Collection::make($values);

        $checkedValuesIfNumeric = $values->map(function ($value) {
            return (intval($value) >= 0 && is_numeric($value)) ? $value : null;
        })->all();

        if ($values->diff($checkedValuesIfNumeric)->all()) {
            return false;
        }

        if (is_null($length)) {
            return true;
        }

        if (!absint($length)) {
            return false;
        }

        $checkedValuesForCorrectLength = $values->map(function ($value) use ($length) {
            if (strlen($value) !== $length) {
                return null;
            }

            return $value;
        })->all();

        return $values->diff($checkedValuesForCorrectLength)->all() ? false : true;
    }

    /**
     * Validate a number.
     *
     * @param int $number
     * @return int
     */
    public function number($number)
    {
        if (!is_numeric($number)) {
            $this->wpDieMessage();
        }

        if (!$this->valuesAreNumbers([$number])) {
            $this->wpDieMessage();
        }

        return $number;
    }

    public function integerOrEmpty($number)
    {
        if ($number === '') {
            return null;
        }

        $int = (int) $number;

        if (!is_int($int)) {
            $this->wpDieMessage('Not an integer.');
        }

        return $int;
    }

    /**
    * Determine if the given $values are dupicate
    *
    * @param array $values
    * @return boolean
    */
    public function valuesAreDuplicated(array $values)
    {
        return count($values) !== count(array_unique($values));
    }

    /**
     * Validate fieldId.
     *
     * @param string $id
     * @return int
     */
    public function fieldId($id)
    {
        $id = preg_replace('/[^0-9]/', '', $id);

        if (strlen($id) !== 13) {
            $this->wpDieMessage();
        }

        return (int) $id;
    }

    /**
     * Validate tableList.
     *
     * @param array $list
     * @return array
     */
    public function tableList(array $list)
    {
        $list = collection::make($list);

        $values = $list->pluck('value')->all();
        $ids    = $list->pluck('id')->all();

        if ($this->valuesAreDuplicated($values)) {
            $this->wpDieMessage();
        }

        if (!$this->valuesAreAlphanumericUnderscore($values)) {
            $this->wpDieMessage();
        }

        if (!$this->valuesAreNumbers($ids, 13)) {
            $this->wpDieMessage();
        }

        return $list->map(function ($item) {
            sanitize_text_field($item['label']);

            return $item;
        })->all();
    }

    /**
     * Validate text field.
     *
     * @param string $value
     * @return string
     */
    public function textField($value)
    {
        return sanitize_text_field($value);
    }

    /**
     * Validate string if alphanumeric, dashes and underscores.
     *
     * @param string $value
     * @return string
     */
    public function alphanumericDashesAll($value)
    {
        if (!$this->valuesAreAlphanumericDashesAll([$value])) {
            $this->wpDieMessage();
        }

        return $value;
    }

    /**
     * Validate string if alphanumeric.
     *
     * @param string $value
     * @return string
     */
    public function alphanumeric($value)
    {
        if (!$this->valuesAreAlphanumeric([$value])) {
            $this->wpDieMessage();
        }

        return $value;
    }

    /**
     * Validate field types.
     *
     * @param object $types
     * @param array $correctTypes
     * @return void
     */
    public function fieldTypes($types, array $correctTypes)
    {
        $hasWrongTypes = $types->whereNotIn('type', $correctTypes)->all();

        if ($hasWrongTypes) {
            $this->wpDieMessage();
        }
    }

    /**
     * Validate toggle if true or false.
     *
     * @param string $toggle
     * @return void|string
     */
    public function toggle($toggle)
    {
        if (!($toggle === 'true' || $toggle === 'false')) {
            $this->wpDieMessage();
        }

        return $toggle;
    }

    /**
     * Validate toggle if true or false.
     *
     * @param string $toggle
     * @return boolean
     */
    public function toggleBoolean($toggle)
    {
        if (!($toggle === 'true' || $toggle === 'false')) {
            $this->wpDieMessage();
        }

        return $toggle === 'true';
    }

    public function textFieldStyle($class)
    {
        $classList = Collection::make([
            'regular_text',
            'all_options',
            'small_text',
            'large_text'
        ]);

        if (!$classList->contains($class)) {
            $this->wpDieMessage();
        }

        return $class;
    }

    public function contains($valueToContains, $fieldItem)
    {
        $classList = Collection::make($valueToContains);

        if (!$classList->contains($fieldItem)) {
            $this->wpDieMessage();
        }

        return $fieldItem;
    }

    public function textareaStyle($class)
    {
        $classList = Collection::make([
            'standard',
            'all_options',
            'large_text'
        ]);

        if (!$classList->contains($class)) {
            $this->wpDieMessage();
        }

        return $class;
    }

    public function toggleTextFormat($field)
    {
        if (!isset($field['toggle_text_format'])) {
            return;
        }

        if ($field['toggle_text_format'] !== '1') {
            $this->wpDieMessage();
        }

        return $field['toggle_text_format'];
    }

    public function toggleAutoload($field)
    {
        if (!isset($field['toggle_autoload'])) {
            return 'yes';
        }

        if ($field['toggle_autoload'] !== 'no') {
            $this->wpDieMessage();
        }

        return $field['toggle_autoload'];
    }

    public function toggleOnOff($fieldItem)
    {
        if (!isset($fieldItem)) {
            return;
        }

        if ($fieldItem !== 'on' && $fieldItem !== 'off') {
            $this->wpDieMessage();
        }

        return $fieldItem;
    }

    public function imageExtensions($extensions)
    {
        if ($extensions === []) {
            return ['types' => [
                'jpeg' => 'on',
                'png' => 'on',
                'gif' => 'on',
                'svg' => 'on',
                'ico' => 'on'
            ]];
        }

        $list = [
            'jpeg',
            'png',
            'gif',
            'svg',
            'ico'
        ];

        Collection::make($extensions['types'])->map(function ($state, $type) use ($list) {
            if (!in_array($type, $list)) {
                $this->wpDieMessage();
            }
        });

        return $extensions;
    }

    /**
     * Validate a text area.
     *
     * @param string $text
     * @return string
     */
    public function textArea($text)
    {
        return wp_kses_post($text);
    }

    /**
     * Validate if value is correct hexadecimal color.
     *
     * @param string $value
     * @return boolean
     */
    public function isColorHex($value)
    {
        if (preg_match('/^#[a-f0-9]{6}$/i', $value) || preg_match('/^#[a-f0-9]{3}$/i', $value)) {
            return true;
        }

        return false;
    }

    /**
     * Validate a hexadecimal color input.
     *
     * @param string $value
     * @return boolean
     */
    public function colorHex($value)
    {
        if (!$value) {
            return '#ffffff';
        }

        if (!preg_match('/^#[a-f0-9]{6}$/i', $value)) {
            $this->wpDieMessage();
        }

        return $value;
    }

    public function capabilityPage($value)
    {
        if ($value === 'editor') {
            return 'edit_others_posts';
        }

        return 'manage_options';
    }

    public function capabilityMenu()
    {
        if (Collection::make($_POST['pages'])->contains('role', 'editor')) {
            return 'edit_others_posts';
        }

        return 'manage_options';
    }

    /**
     * Value already validated.
     *
     * @param mixed $value
     * @param string $method Gives the name of the method where the validation has been done.
     * @return mixed
     */
    public function already($value, $method = '')
    {
        return $value;
    }

    /**
     * WP die message.
     *
     * @param string $message
     * @return void
     */
    public function wpDieMessage($message = '')
    {
        $message = $message ?: 'Are you trying to break something?';

        \wp_die($message);
    }
}
