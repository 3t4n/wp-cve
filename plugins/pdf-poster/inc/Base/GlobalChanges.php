<?php
namespace PDFPro\Base;
use PDFPro\Helper\Pipe;
class GlobalChanges{
    protected static $_instance = null;

    /**
     * construct function
     */
    public function register(){
        add_filter( 'admin_footer_text', [$this, 'pdfp_admin_footer']);	
    }

    /**
     * Create instance function
     */
    public static function instance(){
        if(self::$_instance === null){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function pdfp_admin_footer( $text ) {
        if ( 'pdfposter' == get_post_type() ) {
            $url = 'https://wordpress.org/support/plugin/pdf-poster/reviews/?filter=5#new-post';
            $text = sprintf( __( 'If you like <strong>Pdf Poster</strong> please leave us a <a href="%s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. Your Review is very important to us as it helps us to grow more. ', 'pdfp' ), $url );
        }
        return $text;
    }

    
}

GlobalChanges::instance();