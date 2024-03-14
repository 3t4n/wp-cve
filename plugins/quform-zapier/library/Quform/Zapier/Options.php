<?php

/**
 * @copyright Copyright (c) 2009-2020 ThemeCatcher (https://www.themecatcher.net)
 */

class Quform_Zapier_Options
{
    /**
     * The key within the wp_options table
     *
     * @var string
     */
    protected $key;

    /**
     * The options
     *
     * @var array
     */
    protected $options = array();

    /**
     * @param string $key
     */
    public function __construct($key)
    {
        $this->key = $key;
        $this->options = get_option($this->key, $this->getDefaults());
    }

    /**
     * Get the default options
     *
     * @return array
     */
    protected function getDefaults()
    {
        return array(
            'enabled' => true
        );
    }

    /**
     * Save the options
     */
    protected function update()
    {
        update_option($this->key, $this->options);
    }

    /**
     * Get the value opf the option with the given key
     *
     * If it does not exist the given default will be returned
     * If the given default is null it will get the default value for the option
     *
     * @param   string      $key      The option key
     * @param   mixed|null  $default  The default to return if the key does not exist
     * @return  mixed
     */
    public function get($key, $default = null)
    {
        $value = Quform::get($this->options, $key, $default);

        if ($value === null) {
            $value = Quform::get($this->getDefaults(), $key, $default);
        }

        return $value;
    }

    /**
     * Set the value of the option with the given key and save the options
     *
     * @param  string|array  $key
     * @param  null|mixed    $value
     */
    public function set($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->options[$k] = $v;
            }
        } else {
            $this->options[$key] = $value;
        }

        $this->update();
    }

    /**
     * Called when the plugin is uninstalled, delete all options
     */
    public function uninstall()
    {
        // Delete options
        delete_option($this->key);
    }
}
