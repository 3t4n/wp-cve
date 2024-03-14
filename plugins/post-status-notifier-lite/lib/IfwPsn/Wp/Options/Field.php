<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 *
 * Options field
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: Field.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 */
abstract class IfwPsn_Wp_Options_Field
{
    /**
     * @var string
     */
    protected $_id;

    /**
     * @var string
     */
    protected $_label;

    /**
     * @var string
     */
    protected $_description;

    /**
     * @var array
     */
    protected $_params;

    /**
     * @var null|string
     */
    protected $_pageId;

    /**
     * @var string
     */
    protected $_labelIcon;


    protected static $_instance = [];


    /**
     * @return mixed|static
     */
    public static function getInstance()
    {
        if (!empty(static::ID) && !isset(self::$_instance[static::ID])) {
            self::$_instance[static::ID] = new static();
        }
        return self::$_instance[static::ID];
    }


    /**
     * @param $id
     * @param $label
     * @param null $description
     * @param array $params
     */
    public function __construct($id = null, $label = null, $description = null, $params = array())
    {
        if (!empty($id)) {
            $this->_id = $id;
        }
        if (!empty($label)) {
            $this->_label = $label;
        }
        if (!empty($description)) {
            $this->_description = $description;
        }
        if (!empty($params)) {
            $this->_params = $params;
        }

        if (method_exists($this, 'init')) {
            $this->init();
        }
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->_id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param string $label
     * @return $this
     */
    public function setLabel($label)
    {
        $this->_label = $label;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->_label;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->_description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * @param null|string $pageId
     */
    public function setPageId($pageId)
    {
        $this->_pageId = $pageId;
    }

    /**
     * @return null|string
     */
    public function getPageId()
    {
        return $this->_pageId;
    }

    /**
     * @return null|string
     */
    public function hasPageId()
    {
        return !empty($this->_pageId);
    }

    /**
     * @return bool
     */
    public function hasSanitizer()
    {
        return isset($this->_params['sanitizer']);
    }

    /**
     * @return mixed
     */
    public function getSanitizer()
    {
        return isset($this->_params['sanitizer']) ? $this->_params['sanitizer'] : null;
    }

    /**
     * @param $id
     * @return string
     */
    protected function _getOutputStart($id, $class = '')
    {
        if (!empty($this->_params['indented']) && $this->_params['indented'] === true) {
            $class .= ' indented';
        }

        return sprintf(
            '<div id="%s" class="%s">',
            $id . '_box',
            $class
        );
    }

    /**
     * @param $id
     * @return string
     */
    protected function _getOutputEnd($id = null)
    {
        return '</div>';
    }

    /**
     * @return string
     */
    public function hasLabelIcon()
    {
        return !empty($this->_labelIcon);
    }

    /**
     * @return string
     */
    public function getLabelIcon()
    {
        return $this->_labelIcon;
    }

    /**
     * @param string $labelIcon
     * @return $this
     */
    public function setLabelIcon($labelIcon)
    {
        $this->_labelIcon = $labelIcon;
        return $this;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function setParams(array $params)
    {
        $this->_params = $params;
        return $this;
    }

    /**
     * @return string|void
     */
    public function getUrl()
    {
        return admin_url('admin.php?page=asa2_options&highlight=' . $this->getId());
    }

    /**
     * @param array $params
     * @return mixed
     */
    abstract public function render(array $params);
}
