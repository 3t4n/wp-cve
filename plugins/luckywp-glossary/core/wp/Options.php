<?php

namespace luckywp\glossary\core\wp;

use luckywp\glossary\core\base\BaseObject;
use luckywp\glossary\core\Core;

class Options extends BaseObject
{

    /**
     * @param string $option
     * @param mixed $default
     * @return mixed
     */
    public function get($option, $default = false)
    {
        return get_option(Core::$plugin->prefix . $option, $default);
    }

    /**
     * @param string $option
     * @param mixed $value
     * @param bool|null $autoload
     * @return bool
     */
    public function set($option, $value, $autoload = null)
    {
        return update_option(Core::$plugin->prefix . $option, $value, $autoload);
    }

    /**
     * @param string $option
     * @return bool
     */
    public function delete($option)
    {
        return delete_option(Core::$plugin->prefix . $option);
    }

    /**
     * @param string $option
     * @param mixed $default
     * @return mixed
     */
    public function getForUser($option, $default = false)
    {
        $option = $this->getOptionForUser($option);
        return $option ? $this->get($option, $default) : false;
    }

    /**
     * @param string $option
     * @param mixed $value
     * @return bool
     */
    public function setForUser($option, $value)
    {
        $option = $this->getOptionForUser($option);
        return $option ? $this->set($option, $value) : false;
    }

    /**
     * @param string $option
     * @return bool
     */
    public function deleteForUser($option)
    {
        $option = $this->getOptionForUser($option);
        return $option ? $this->delete($option) : false;
    }

    /**
     * @param string $option
     * @return string|false
     */
    protected function getOptionForUser($option)
    {
        $userId = get_current_user_id();
        if ($userId) {
            return $option . '_' . $userId;
        }
        return false;
    }
}
