<?php

namespace ShopWP\Render;

if (!defined('ABSPATH')) {
    exit();
}

class Search
{
    public $Templates;
    public $Defaults_Search;

    public function __construct($Templates, $Defaults_Search)
    {
        $this->Templates = $Templates;
        $this->Defaults_Search = $Defaults_Search;
    }

    public function search($data = [])
    {
        return $this->Templates->load([
            'type' => 'search',
            'defaults' => 'search',
            'data' => $this->Templates->sanitize_user_data(
                $data,
                'search',
                $this->Defaults_Search
            )
        ]);
    }
}
