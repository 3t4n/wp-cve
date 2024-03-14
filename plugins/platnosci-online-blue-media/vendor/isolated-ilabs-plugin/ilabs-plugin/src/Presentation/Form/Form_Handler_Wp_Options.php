<?php

namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Form;

class Form_Handler_Wp_Options
{
    /**
     * @var Form
     */
    private $form;
    public function __construct(Form $form)
    {
        $this->form = $form;
    }
    public function fill() : Form
    {
    }
}
