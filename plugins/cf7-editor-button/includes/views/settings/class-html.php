<?php
namespace Ari_Cf7_Button\Views\Settings;

use Ari_Cf7_Button\Views\Base as Base;

class Html extends Base {
    public $form;

    public function display( $tmpl = null ) {
        $this->set_title( sprintf( __( 'Contact Form 7 Editor Button - Settings v. %s', 'contact-form-7-editor-button' ), ARICF7BUTTON_VERSION ) );

        $data = $this->get_data();
        $form = $data['form'];

        $this->form = $form;

        add_action( 'admin_head', function() {
echo 'd';exit();
        });

        parent::display( $tmpl );
    }
}
