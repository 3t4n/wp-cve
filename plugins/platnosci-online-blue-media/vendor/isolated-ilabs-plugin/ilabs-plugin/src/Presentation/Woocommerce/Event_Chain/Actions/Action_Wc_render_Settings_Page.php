<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Woocommerce\Event_Chain\Actions;

use Exception;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Abstracts\Abstract_Action;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Form\Form;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Woocommerce\Form_Chain\Renderer_Wc_General_Settings;
class Action_Wc_render_Settings_Page extends Abstract_Action
{
    /**
     * @var Form
     */
    private $form;
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $label;
    /**
     * @param Form $form
     * @param string $id
     * @param string $label
     */
    public function __construct(Form $form, string $id, string $label)
    {
        $this->form = $form;
        $this->id = $id;
        $this->label = $label;
    }
    /**
     * @throws Exception
     */
    public function run()
    {
        $renderer = new Renderer_Wc_General_Settings($this->form, $this->id, $this->label);
        $renderer->render();
    }
}
