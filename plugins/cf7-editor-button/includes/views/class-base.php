<?php
namespace Ari_Cf7_Button\Views;

use Ari\Views\View as View;
use Ari\Utils\Request as Request;

class Base extends View {
    protected $title = '';

    public function display( $tmpl = null ) {
        wp_enqueue_style( 'ari-cf7button-app' );

        echo '<div id="ari_cf7button_plugin" class="wrap ari-theme">';

        $this->render_message();
        $this->render_title();

        parent::display( $tmpl );

        echo '</div>';
    }

    public function set_title( $title ) {
        $this->title = $title;
    }

    protected function render_title() {
        if ( $this->title )
            printf(
                '<h1 class="wp-heading-inline">%s</h1>',
                $this->title
            );
    }

    protected function render_message() {
        if ( ! Request::exists( 'msg' ) )
            return ;

        $message_type = Request::get_var( 'msg_type', 'notice', 'alpha' );
        $message = Request::get_var( 'msg' );

        printf(
            '<div class="notice notice-%2$s is-dismissible"><p>%1$s</p></div>',
            $message,
            $message_type
        );
    }
}
