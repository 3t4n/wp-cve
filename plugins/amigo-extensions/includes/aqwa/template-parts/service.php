<?php 
/**
 * displaying theme about section
 * 
 */

defined( 'ABSPATH' ) || exit;

class Aqwa_Theme_Services{

	public static $default = '';

	public static function init(){	
		
		self::$default = aqwa_service_section_default();

        add_action( 'wp_enqueue_scripts', array( __CLASS__,'inline_css_this_section' ) );

		// home page service section
        add_action( 'Aqwa_Homepage_Sections', array( __CLASS__, 'add_service_section_homepage'), 12 );
    }

    /**
    * Service section inline CSS
    *
    */
    public static function inline_css_this_section(){

        $element_img = AMIGO_PLUGIN_DIR_URL . 'includes/aqwa/assets/images/elements-01.png';
        $custom_css = "
        .our-services .container:after{
            background-image: url(".$element_img.");
        }";

        wp_add_inline_style( 'aqwa-style', $custom_css );
    }

    /**
    * displaying service section 
    *   
    */
    public static function add_service_section_homepage(){	
        if( ! get_theme_mod( 'aqwa_display_service_section' ,self::$default['aqwa_display_service_section'] ) ){
            return;
        }

        $title = get_theme_mod( 'aqwa_service_section_title' ,self::$default['aqwa_service_section_title'] );
        $sub_title = get_theme_mod( 'aqwa_service_section_subtitle' ,self::$default['aqwa_service_section_subtitle'] );
        $text = get_theme_mod( 'aqwa_service_section_text' ,self::$default['aqwa_service_section_text'] );	
        $load_button_text = get_theme_mod( 'aqwa_service_load_button_text' ,self::$default['aqwa_service_load_button_text'] );    
        ?>

        <section class="our-services bg-gray">
            <div class="container">
                <div class="section-title text-center">
                 <?php if( ! empty( $title ) ){ ?>
                    <h5> <span> <?php echo esc_html( $title ) ?>  </span> </h5>
                <?php } ?>

                <?php if( ! empty( $sub_title ) ){ ?>
                    <h2> <?php echo esc_html( $sub_title ) ?> </h2>
                <?php } ?>

                <?php if( ! empty( $text ) ){ ?>
                    <p> <?php echo esc_html( $text ) ?></p>
                <?php } ?>
            </div>

            <?php self::service_section_items(); ?>
            
        </div>
    </section>

    <?php
}
/**
* displaying service section 
* 
*
* @package Aqwa WordPress Theme
*/
public static function service_section_items(){

    $service_items = get_theme_mod( 'aqwa_service_items', aqwa_default_service_items() );
    $service_column = get_theme_mod( 'aqwa_service_section_column', self::$default['aqwa_service_section_column'] );

    if ( ! empty( $service_items ) ) {

        echo '<div class="row service-items">';

        $service_items = json_decode( $service_items );

        foreach ( $service_items as $item ) { ?>

           <div class="<?php echo esc_attr( $service_column ) ?> col-md-6">
            <div class="card services-col wow fadeInUp animated" data-wow-delay="0.3s">
                
                 <?php if( !empty( $item->image_url )){ ?>
                <div class="services-img">
                    <img src="<?php echo esc_url( $item->image_url ) ?>" class="img-fluid" alt="img">
                </div>
                 <?php } ?>

                <div class="card-body">

                     <?php if( !empty( $item->icon_value )){ ?>
                    <span class="service-icon"><i class="<?php echo esc_html( $item->icon_value ) ?> icon">  </i> </span>
                    <?php } ?>

                    <?php if( !empty( $item->title )){ ?>
                        <h4 class="card-title"> <?php echo esc_html( $item->title ) ?> </h4>
                    <?php } ?>

                    <?php if( !empty( $item->text )){ ?>
                        <p class="card-text"><?php echo esc_html( $item->text ) ?></p>
                    <?php } ?>

                    <?php if( !empty( $item->text2 )){ ?>
                        <a href="<?php echo esc_url( $item->link2 ) ?>" class="btn btn-theme mt-4"><?php echo esc_html( $item->text2 ) ?> <i class="fas fa-long-arrow-alt-right"></i> </a>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php
    }

    echo '</div>'; 

}

}
}

Aqwa_Theme_Services::init(); 

?>