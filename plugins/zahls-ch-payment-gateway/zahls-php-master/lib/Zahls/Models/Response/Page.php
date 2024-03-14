<?php
/**
 * The Page response model 
 * @since     v1.0
 */
namespace Zahls\Models\Response;

/**
 * Class Page
 * @package Zahls\Models\Response
 */
class Page extends \Zahls\Models\Request\Page
{
    protected $createdAt = 0;

    /**
     * @return int
     */
    public function getCreatedDate()
    {
        return $this->createdAt;
    }

    /**
     * @param int $createdAt
     */
    public function setCreatedDate($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @param array $fields
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
    }
}
