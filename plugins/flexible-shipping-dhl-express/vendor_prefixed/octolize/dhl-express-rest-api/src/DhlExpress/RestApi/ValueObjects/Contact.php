<?php

declare (strict_types=1);
namespace DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects;

use DhlVendor\Octolize\DhlExpress\RestApi\Exceptions\InvalidAddressException;
class Contact
{
    protected string $phone;
    protected string $companyName;
    protected string $fullName;
    protected string $email = '';
    protected string $mobilePhone = '';
    public function __construct(string $phone, string $companyName, string $fullName, string $email = '', string $mobilePhone = '')
    {
        $this->mobilePhone = $mobilePhone;
        $this->email = $email;
        $this->fullName = $fullName;
        $this->companyName = $companyName;
        $this->phone = $phone;
    }
    /**
     * @return string
     */
    public function getPhone() : string
    {
        return $this->phone;
    }
    /**
     * @return string
     */
    public function getCompanyName() : string
    {
        return $this->companyName;
    }
    /**
     * @return string
     */
    public function getFullName() : string
    {
        return $this->fullName;
    }
    /**
     * @return string
     */
    public function getMobilePhone() : string
    {
        return $this->mobilePhone;
    }
    public function getEmail() : string
    {
        return $this->email;
    }
    public function getAsArray() : array
    {
        $result = ['phone' => $this->phone, 'companyName' => $this->companyName, 'fullName' => $this->fullName];
        if ($this->email !== '') {
            $result['email'] = $this->email;
        }
        if ($this->mobilePhone !== '') {
            $result['mobilePhone'] = $this->mobilePhone;
        }
        return $result;
    }
}
