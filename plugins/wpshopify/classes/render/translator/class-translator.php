<?php

namespace ShopWP\Render;

if (!defined('ABSPATH')) {
    exit();
}

class Translator
{
    public $Templates;
    public $Defaults_Translator;

    public function __construct($Templates, $Defaults_Translator)
    {
        $this->Templates = $Templates;
        $this->Defaults_Translator = $Defaults_Translator;
    }

    public function translator($data = [])
    {
        return $this->Templates->load([
            'skeleton' => 'components/skeletons/translator',
            'type' => 'translator',
            'defaults' => 'translator',
            'data' => $this->Templates->sanitize_user_data(
                $data,
                'translator',
                $this->Defaults_Translator
            )
        ]);
    }
}
