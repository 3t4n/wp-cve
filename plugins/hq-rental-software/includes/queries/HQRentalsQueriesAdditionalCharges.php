<?php

namespace HQRentalsPlugin\HQRentalsQueries;

use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsAdditionalCharge;

class HQRentalsQueriesAdditionalCharges
{
    public function __construct()
    {
        $this->model = new HQRentalsModelsAdditionalCharge();
    }

    public function allCharges()
    {
        $charges = $this->model->all();
        return array_map(function ($post) {
            return new HQRentalsModelsAdditionalCharge($post);
        }, $charges);
    }
}
