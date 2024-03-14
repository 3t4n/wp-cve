<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Woocommerce\Form_Chain;

use Exception;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Form\Abstract_Renderer;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Form\Form;
class Renderer_Wc_General_Settings extends Abstract_Renderer
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $label;
    /**
     * @var Form
     */
    private $form;
    /**
     * @param string $id
     * @param string $label
     * @param Form $form
     */
    public function __construct(Form $form, string $id, string $label)
    {
        $this->id = $id;
        $this->label = $label;
        $this->form = $form;
    }
    /**
     * @throws Exception
     */
    public function render()
    {
        add_filter('woocommerce_get_settings_pages', function ($woocommerce_settings) {
            $wc_settings = new Wc_General_Settings_Child($this->map_fields($this->form), $this->id, $this->label);
            return $woocommerce_settings;
        });
    }
    /**
     * @throws Exception
     */
    private function map_fields(Form $form) : array
    {
        $walker = new Walker_Wc_General_Settings($form->get_items());
        $walker->walk();
        return $walker->get_result();
    }
}
