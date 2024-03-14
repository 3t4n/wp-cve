<?php

namespace ShopWP\Render;

defined('ABSPATH') ?: exit();

/*

Render: Storefront

*/
class Storefront
{
    public $Templates;
    public $Defaults_Storefront;

    public function __construct($Templates, $Defaults_Storefront)
    {
        $this->Templates = $Templates;
        $this->Defaults_Storefront = $Defaults_Storefront;
    }

    /*

	Storefront: Storefront

	*/
    public function storefront($data = [])
    {
        return $this->Templates->load([
            'skeleton' => 'components/skeletons/storefront',
            'after' => 'components/storefront/storefront',
            'type' => 'storefront',
            'defaults' => 'storefront',
            'data' => $this->Templates->sanitize_user_data(
                $data,
                'storefront',
                $this->Defaults_Storefront
            )
        ]);
    }
}
