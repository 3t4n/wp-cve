<?php

namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Woocommerce\Event_Chain\Actions;

use Exception;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Abstracts\Abstract_Action;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Form\Form;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Woocommerce\Form_Chain\Renderer_Wc_Post_Metabox;
class Action_Wc_render_Metabox extends Abstract_Action
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
    public function __construct(Form $form, string $header, string $metabox_type = self::METABOX_TYPE_SIDE)
    {
        $this->form = $form;
        $this->header = $header;
        $this->metabox_type = $metabox_type;
    }
    /**
     * @throws Exception
     */
    public function run()
    {
        $renderer = new Renderer_Wc_Post_Metabox($this->form, $this->header, $this->metabox_type);
        $renderer->render();
    }
}
