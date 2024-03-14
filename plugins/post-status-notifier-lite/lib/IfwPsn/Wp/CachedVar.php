<?php
/**
 * AmazonSimpleAffiliate (ASA2)
 * For more information see https://getasa2.com/
 *
 *
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 */
class IfwPsn_Wp_CachedVar
{
    /**
     * name used for transient
     * @var string
     */
    protected $name;

    /**
     * time in seconds used for set_transient expiration param
     * @var int
     */
    protected $lifetime;


    /**
     * IfwPsn_Wp_CachedVar constructor.
     * @param $name
     * @param $lifetime
     */
    public function __construct($name, $lifetime)
    {
        $this->name = $name;
        $this->lifetime = $lifetime;
    }

    /**
     * @return bool
     */
    public function exists()
    {
        return $this->get() !== false;
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return get_transient($this->name);
    }

    /**
     * @return mixed|void
     */
    public function getSaveTime()
    {
        return $this->getTimeout() - $this->lifetime;
    }

    /**
     * @return string|null
     */
    public function getSaveTimeBlogFormat()
    {
        $saveTime = $this->getSaveTime();

        if (!empty($saveTime)) {
            return IfwPsn_Wp_Date::format($this->getSaveTime());
        }

        return null;
    }

    /**
     * @return mixed|void
     */
    public function getTimeout()
    {
        return get_option('_transient_timeout_' . $this->name);
    }

    /**
     * @return string
     */
    public function getTimeoutBlogFormat()
    {
        return IfwPsn_Wp_Date::format($this->getTimeout());
    }

    /**
     * @param $value
     * @return bool
     */
    public function set($value)
    {
        return set_transient($this->name, $value, $this->lifetime);
    }

    public function reset()
    {
        delete_transient($this->name);
    }
}
