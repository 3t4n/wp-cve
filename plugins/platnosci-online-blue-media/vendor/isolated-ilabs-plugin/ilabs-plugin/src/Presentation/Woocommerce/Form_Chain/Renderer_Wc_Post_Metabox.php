<?php

namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Woocommerce\Form_Chain;

use Exception;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Abstract_Ilabs_Plugin;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Form\Abstract_Renderer;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Form\Form;
class Renderer_Wc_Post_Metabox extends Abstract_Renderer
{
    const METABOX_TYPE_NORMAL = 'normal';
    const METABOX_TYPE_SIDE = 'side';
    const SCREEN_SHOP_ORDER = 'shop_order';
    /**
     * @var Form
     */
    private $form;
    /**
     * @var string
     */
    private $header;
    /**
     * @var string
     */
    private $metabox_type;
    /**
     * @param Form $form
     * @param string $header
     * @param string $metabox_type
     */
    public function __construct(Form $form, string $header, string $metabox_type)
    {
        $this->form = $form;
        $this->header = $header;
        $this->metabox_type = $metabox_type;
    }
    public static function get_templates_directory() : string
    {
        return Abstract_Ilabs_Plugin::$initial_instance->get_framework_templates_dir() . \DIRECTORY_SEPARATOR . 'Woocommerce' . \DIRECTORY_SEPARATOR;
    }
    /**
     * @throws Exception
     */
    public function render()
    {
        add_action('add_meta_boxes', function () {
            add_meta_box(sanitize_title($this->header), $this->header, function () {
                $metabox_header = $this->header;
                include static::get_templates_directory() . 'Metabox_header.php';
                $walker = new Walker_Wc_Post_Metabox($this->form->get_items());
                $walker->walk();
                include static::get_templates_directory() . 'Metabox_footer.php';
            }, self::SCREEN_SHOP_ORDER, $this->metabox_type, 'default', ['label' => $this->header]);
        }, 10, 2);
    }
}
