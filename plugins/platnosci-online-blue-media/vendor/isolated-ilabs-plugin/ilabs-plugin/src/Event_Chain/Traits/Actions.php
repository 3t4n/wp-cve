<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Traits;

use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Action\Action;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Action\Copy;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Action\Output_Template;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event_Chain;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Form\Form;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Woocommerce\Event_Chain\Actions\Action_Wc_render_Metabox;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Woocommerce\Event_Chain\Actions\Action_Wc_render_Settings_Page;
trait Actions
{
    public function action_copy(callable $callable_arguments) : Event_Chain
    {
        $this->get_event_chain()->add_action(new Copy($callable_arguments));
        return $this->get_event_chain();
    }
    protected abstract function get_event_chain() : Event_Chain;
    public function action(callable $callable_arguments) : Event_Chain
    {
        $this->get_event_chain()->add_action(new Action($callable_arguments));
        return $this->get_event_chain();
    }
    public function action_output_template(string $template) : Event_Chain
    {
        $this->get_event_chain()->add_action(new Output_Template($template));
        return $this->get_event_chain();
    }
    public function action_render_wc_general_settings(Form $form, string $id, string $label) : Event_Chain
    {
        $this->get_event_chain()->add_action(new Action_Wc_render_Settings_Page($form, $id, $label));
        return $this->get_event_chain();
    }
    public function action_render_wc_post_meta(Form $form, string $header, string $metabox_type = Action_Wc_render_Metabox::METABOX_TYPE_SIDE) : Event_Chain
    {
        $this->get_event_chain()->add_action(new Action_Wc_render_Metabox($form, $header, $metabox_type));
        return $this->get_event_chain();
    }
}
