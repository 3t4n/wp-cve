<?php

namespace FedExVendor\FedEx\TrackService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * Used in authentication of the sender's identity.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Package Movement Information Service
 *
 * @property WebAuthenticationCredential $ParentCredential
 * @property WebAuthenticationCredential $UserCredential
 */
class WebAuthenticationDetail extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'WebAuthenticationDetail';
    /**
     * This was renamed from cspCredential.
     *
     * @param WebAuthenticationCredential $parentCredential
     * @return $this
     */
    public function setParentCredential(\FedExVendor\FedEx\TrackService\ComplexType\WebAuthenticationCredential $parentCredential)
    {
        $this->values['ParentCredential'] = $parentCredential;
        return $this;
    }
    /**
     * Credential used to authenticate a specific software application. This value is provided by FedEx after registration.
     *
     * @param WebAuthenticationCredential $userCredential
     * @return $this
     */
    public function setUserCredential(\FedExVendor\FedEx\TrackService\ComplexType\WebAuthenticationCredential $userCredential)
    {
        $this->values['UserCredential'] = $userCredential;
        return $this;
    }
}
