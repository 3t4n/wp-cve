<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 * 
 * 
 *
 * @author    Timo Reith <timo@ifeelweb.de>
 * @version   $Id: CustomCss.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 * @package   
 */ 
class IfwPsn_Wp_CustomCss
{
    /**
     * @var string
     */
    protected $_id;

    /**
     * @var array
     */
    protected static $_instance = array();

    
    
    /**
     * @param IfwPsn_Wp_Plugin_Manager
     */
    protected function __construct($id)
    {
        $this->setId($id);
    }

    /**
     * @param string $id
     * @return IfwPsn_Wp_CustomCss
     */
    public static function getInstance($id)
    {
        if (!isset(self::$_instance[$id]) || self::$_instance[$id] === null) {
            self::$_instance[$id] = new self($id);
        }
        return self::$_instance[$id];
    }

    public function loadAdmin()
    {
        if ($this->hasCss()) {
            add_action('admin_head', array($this, 'loadCss'));
        }
    }

    public function loadFrontend()
    {
        if ($this->hasCss()) {
            add_action('wp_footer', array($this, 'loadCss'), 1000000);
        }
    }

    /**
     * @param $css
     * @return bool
     */
    public function save($css)
    {
        return update_option($this->getId(), $css);
    }

    /**
     * @return bool
     */
    public function hasCss()
    {
        $css = get_option($this->getId());
        return !empty($css);
    }

    /**
     * @return mixed|void
     */
    public function get()
    {
        $css = get_option($this->getId());
        return $css;
    }

    /**
     * @return bool
     */
    public function delete()
    {
        return delete_option($this->getId());
    }

    public function loadCss()
    {
        $css = $this->get();
        $css = IfwPsn_Util_Parser_Css::sanitize($css);
        $css = IfwPsn_Util_Parser_Css::compress($css);
        if (!empty($css)) {
            echo sprintf('<style>%s</style>', $css);
        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->_id = trim($id);
    }
}
