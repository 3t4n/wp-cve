<?php 

use Adminz\Admin\Adminz as Adminz;
add_action('ux_builder_setup', 'adminz_facebookpage');
add_shortcode('adminz_facebookpage', 'adminz_facebookpage_function');
function adminz_facebookpage(){
	add_ux_builder_shortcode('adminz_facebookpage', array(
        'name'      => __('Facebook Page Embedded','administrator-z'),
        'category'  => Adminz::get_adminz_menu_title(),
        'thumbnail' =>  get_template_directory_uri() . '/inc/builder/shortcodes/thumbnails/' . 'ux_image' . '.svg',
        'options' => array(
            'href' => array(
                'type'       => 'textfield',
                'heading'    => 'Page url',
                'default' => 'https://www.facebook.com/facebook',
            ),
            'width' => array(
                'type'       => 'slider',
                'unit' => 'px',
                'min'=> 180,
                'max'=> 1000,
                'heading'    => 'Width',
                'default' => '340',
            ),
            'height' => array(
                'type'       => 'slider',
                'unit' => 'px',
                'min'=> 70,
                'max'=> 1000,
                'heading'    => 'Height',
                'default' => '70',
            ),
            'tabs' => array(
                'type'       => 'select',
                'heading'    => 'Tabs',
                'default' => '',
                'config'  => array(
                    'placeholder' => __( 'Select...', 'administrator-z' ),
                    'multiple'    => true,
                    'options'=> [
                        'timeline'=>'timeline',
                        'events'=>'events',
                        'messages'=>'messages',
                    ]
                )
                
            ),
            'hide_cover' => array(
                'type'       => 'checkbox',
                'heading'    => 'Hide cover',
                'default' => 'false',
            ),
            'show_facepile' => array(
                'type'       => 'checkbox',
                'heading'    => 'Show facepile',
                'default' => 'true',
            ),
            'hide_cta' => array(
                'type'       => 'checkbox',
                'heading'    => 'Hide CTA',
                'default' => 'false',
            ),
            'small_header' => array(
                'type'       => 'checkbox',
                'heading'    => 'Small header',
                'default' => 'false',
            ),
            'adapt_container_width' => array(
                'type'       => 'checkbox',
                'heading'    => 'Adapt container width',
                'default' => 'true',
            ),
            'lazy' => array(
                'type'       => 'checkbox',
                'heading'    => 'Lazy load',
                'default' => 'false',
            ),
            'lang'=> array(
                'type'       => 'textfield',
                'heading'    => 'Language',
                'default' => 'en_US',
                'placeholder'=> 'vi_VN'
            ),
        ),
    ));
}
function adminz_facebookpage_function($atts){	
	extract(shortcode_atts(array(
        'href'=> 'https://www.facebook.com/facebook',
        'width'=> '340',
        'height'=> '70',
        'tabs'=> '',
        'hide_cover'=> 'false',
        'show_facepile'=> 'true',
        'hide_cta'=> 'false',
        'small_header'=> 'false',
        'adapt_container_width'=> 'true',
        'lazy'=> 'false',
        'lang'=> "en_US"
    ), $atts));
    ob_start(); 
    if(isset( $_POST['ux_builder_action'] )){
        ?>
        <div style="
        background: #71cedf;
        width: <?php echo esc_attr($width); ?>px;
        height: <?php echo esc_attr($height); ?>px;
        border: 2px dashed #000;
        display: flex;
        padding: 20px;
        color: white;
        font-size: 1.5em;
        justify-content: center;
        align-items: center;
        ">
            <?php echo __('Facebook Page Embedded','administrator-z');?>
        </div>
        <?php
    }else{
        ?>
        <div id="fb-root"></div>
        <script async defer crossorigin="anonymous" src="https://connect.facebook.net/<?php echo esc_attr($lang); ?>/sdk.js#xfbml=1&version=v10.0" nonce="IQPCOR6q"></script>
        <div 
            class="fb-page" 
            data-href="<?php echo esc_attr($href); ?>"
            data-width="<?php echo esc_attr($width); ?>"
            data-height="<?php echo esc_attr($height); ?>"
            data-tabs="<?php echo esc_attr($tabs); ?>"
            data-hide-cover="<?php echo esc_attr($hide_cover); ?>"
            data-show-facepile="<?php echo esc_attr($show_facepile); ?>"
            data-hide-cta="<?php echo esc_attr($hide_cta); ?>"
            data-small-header="<?php echo esc_attr($small_header); ?>"
            data-adapt-container-width="<?php echo esc_attr($adapt_container_width); ?>"
            data-lazy="<?php echo esc_attr($lazy); ?>"
            >    
        </div>    
        <?php  
    }
      
    return ob_get_clean();
}