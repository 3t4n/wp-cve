<?php

namespace Avecdo\SDK\POPO;

class Category
{
    /**
     * @var string
     */
    protected $categoryId = null;

    /**
     * @var string
     */
    protected $name = null;

    /**
     * @var string
     */
    protected $fullName = null;

    /**
     * @var string
     */
    protected $parent = 0;

    /**
     * @var int
     */
    protected $depth = 1;

    /**
     * @var string
     */
    protected $url = null;

    /**
     * @var string
     */
    protected $image = null;

    /**
     * @var string
     */
    protected $description = null;

    /**
     * @return array
     */
    public function getAll()
    {
        return get_object_vars($this);
    }

    /**
     * @param $id
     * @return $this
     */
    public function setCategoryId($id)
    {
        $this->categoryId = $id;

        return $this;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param $fullName
     * @return $this
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * @param $parent
     * @return $this
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @param $depth
     * @return $this
     */
    public function setDepth($depth)
    {
        $this->depth = $depth;

        return $this;
    }

    /**
     * @param $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @param $image
     * @return $this
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @param $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }
}
