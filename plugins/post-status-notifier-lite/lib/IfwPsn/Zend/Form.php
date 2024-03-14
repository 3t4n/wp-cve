<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 * 
 * 
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: Form.php 1850578 2018-03-31 19:27:09Z worschtebrot $
 */ 
class IfwPsn_Zend_Form extends IfwPsn_Vendor_Zend_Form
{
    const NONCE_KEY = 'nonce';

    /**
     * @var string
     */
    protected $_nonceAction;

    /**
     * @var bool
     */
    protected $_validNonce = true;

    /**
     * @var string
     */
    protected $_saveMode = 'create';


    /**
     * @param IfwPsn_Vendor_Zend_Form_Element|string $element
     * @param null $name
     * @param null $options
     * @return IfwPsn_Vendor_Zend_Form
     * @throws IfwPsn_Vendor_Zend_Form_Exception
     */
    public function addElement($element, $name = null, $options = null)
    {
        if ($element instanceof IfwPsn_Vendor_Zend_Form_Element) {
            $name = $element->getName();
        }

        do_action($this->getName() . '_before_' . $name, $this);

        $result = parent::addElement($element, $name, $options);

        $decoratorHtml = $result->getElement($name)->getDecorator('HtmlTag');
        if ($decoratorHtml) {
            $decoratorHtml->setOption('id', 'form_element_' . $name);
        }

        do_action($this->getName() . '_after_' . $name, $this);

        return $result;
    }

    /**
     * @return array
     */
    public function removeNonceAndGetValues()
    {
        if ($this->hasNonce()) {
            $this->removeElement(self::NONCE_KEY);
        }
        $values = parent::getValues();

        // sanitize values
        foreach ($values as $k => $v) {
            $v = IfwPsn_Util_Parser_Html::sanitize($v);
            $values[$k] = $v;
        }

        return $values;
    }

    /**
     * @param array $data
     * @return bool
     * @throws IfwPsn_Vendor_Zend_Form_Exception
     */
    public function isValid($data)
    {
        if ($this->hasNonce() && !$this->verifyNonce()) {
            $this->_validNonce = false;
            return false;
        }

        return parent::isValid($data);
    }

    /**
     * @return bool
     */
    public function isValidNonce()
    {
        return $this->_validNonce === true;
    }

    /**
     * @param $action
     */
    public function setNonce($action)
    {
        $field = new IfwPsn_Vendor_Zend_Form_Element_Hidden(self::NONCE_KEY);
        $field->setValue($this->createNonce($action));
        $field->setDecorators(array('ViewHelper'));

        $this->addElement($field);
    }

    /**
     * @param $action
     * @param null $id
     * @return string
     */
    public function createNonce($action, $id = null)
    {
        if (is_numeric($id)) {
            $action .= '-' . $id;
        } elseif (!empty($_REQUEST['id'])) {
            $action .= '-' . $_REQUEST['id'];
        }

        $this->_nonceAction = $action;

        return wp_create_nonce($this->_nonceAction);
    }

    /**
     * @return bool
     */
    public function verifyNonce()
    {
        $result = false;

        if (isset($_REQUEST[self::NONCE_KEY])) {
            $result = wp_verify_nonce($_REQUEST[self::NONCE_KEY], $this->getNonceAction());
        }

        return $result;
    }

    /**
     * @return bool
     */
    public function hasNonce()
    {
        return $this->_nonceAction !== null;
    }

    /**
     * @return string
     */
    public function getNonceAction()
    {
        return $this->_nonceAction;
    }

    /**
     * @return string
     */
    public function hasValidationError()
    {
        return $this->_validationError !== null;
    }

    /**
     * @return string
     */
    public function getValidationError()
    {
        return $this->_validationError;
    }

    public function setSaveModeCreate()
    {
        $this->_saveMode = 'create';
    }

    public function isSaveModeCreate()
    {
        return $this->_saveMode == 'create';
    }

    public function setSaveModeUpdate()
    {
        $this->_saveMode = 'update';
    }

    public function isSaveModeUpdate()
    {
        return $this->_saveMode == 'update';
    }

}
