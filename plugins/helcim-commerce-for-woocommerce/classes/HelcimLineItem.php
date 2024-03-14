<?php

class HelcimLineItem
{

    private $sku;
    private $description;
    private $quantity;
    private $price;
    private $total;

    public function __construct()
    {
        $this->sku = '';
        $this->description = '';
        $this->quantity = 0;
        $this->price = 0.00;
        $this->total = 0.00;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function setSku(string $sku): HelcimLineItem
    {
        $this->sku = $sku;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): HelcimLineItem
    {
        $this->description = $description;
        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): HelcimLineItem
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): HelcimLineItem
    {
        $this->price = $price;
        return $this;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function setTotal(float $total): HelcimLineItem
    {
        $this->total = $total;
        return $this;
    }
}