<?php

namespace ShopWP\Render;

if (!defined('ABSPATH')) {
    exit();
}

class Reviews
{
    public $Templates;
    public $Defaults_Reviews;

    public function __construct($Templates, $Defaults_Reviews)
    {
        $this->Templates = $Templates;
        $this->Defaults_Reviews = $Defaults_Reviews;
    }

    public function reviews($data = [])
    {
        return $this->Templates->load([
            'skeleton' => 'components/skeletons/reviews',
            'type' => 'reviews',
            'defaults' => 'reviews',
            'data' => $this->Templates->sanitize_user_data(
                $data,
                'reviews',
                $this->Defaults_Reviews
            )
        ]);
    }
}
