<?php

namespace HQRentalsPlugin\HQRentalsModels;

abstract class HQRentalsBaseModel
{
    abstract protected function create();
    abstract protected function all();
    public function getUpdatedAt(): string
    {
        try {
            $date = $this->updated_at;
            return empty($date) ? 'N/A' : $date;
        } catch (\Throwable $e) {
            return 'N/A';
        }
    }
    public function setUpdatedAt($updatedAtFromDB)
    {
        $this->updated_at = $updatedAtFromDB;
    }
}
