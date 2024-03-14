<?php
namespace InspireLabs\WoocommerceInpost\shipx\models\courier_pickup;

class ShipX_Dispatch_Order_Point_Model
{
    /**
     * @var int
     */
    const STATUS_CREATED = 1;

    /**
     * @var int
     */
    const STATUS_ACTIVATED = 2;

    /**
     * @var int
     */
    const STATUS_SUSPENDED = 3;

    /**
     * @var string
     */
    private $href;

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $office_hours;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $comments;

    /**
     * @var ShipX_Dispatch_Order_Point_Address_Model
     */
    private $address;

    /**
     * @var string
     */
    private $status;

    /**
     * @return string
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getOfficeHours()
    {
        return $this->office_hours;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @return ShipX_Dispatch_Order_Point_Address_Model
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $href
     */
    public function setHref($href)
    {
        $this->href = $href;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param string $office_hours
     */
    public function setOfficeHours($office_hours)
    {
        $this->office_hours = $office_hours;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param string $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    /**
     * @param ShipX_Dispatch_Order_Point_Address_Model $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
}
