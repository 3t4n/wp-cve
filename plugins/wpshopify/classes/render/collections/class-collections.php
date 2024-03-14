<?php

namespace ShopWP\Render;

if (!defined('ABSPATH')) {
    exit();
}

class Collections
{
    public $Templates;
    public $Defaults_Collections;

    public function __construct($Templates, $Defaults_Collections)
    {
        $this->Templates = $Templates;
        $this->Defaults_Collections = $Defaults_Collections;
    }

    public function collections($data = [])
    {
        return $this->Templates->load([
            'skeleton' => 'components/skeletons/collections-all',
            'type' => 'collections',
            'defaults' => 'collections',
            'data' => $this->Templates->sanitize_user_data(
                $data,
                'collections',
                $this->Defaults_Collections
            ),
        ]);
    }
}
