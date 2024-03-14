<?php
if (class_exists('WPBakeryShortCodesContainer')) {
    class WPBakeryShortCode_wdo_ult_pricing_parent extends WPBakeryShortCodesContainer {
    	protected function content( $atts, $content = null ) {

    		extract(shortcode_atts(array( 
                'wdo_columns' => 'vc_col-sm-4',
                'pricing_color_scheme' => '',
            ), $atts)); 
            ob_start(); 

            ?>
            <div class="pricing-table-container wdo-ult-container">
                <div class="col-val <?php echo $pricing_color_scheme; ?>" data-cols="<?php echo $wdo_columns; ?>">
                    <div class="pricing-animation-yes pricing-style2 row">
                            <?php do_shortcode( $content ); ?>
                    </div>
                </div>
            </div>

            <?php

            $output = ob_get_clean();

            return $output; 
		}
    }
} 

if (class_exists('WPBakeryShortCode')) {
    class WPBakeryShortCode_wdo_ult_pricing_child extends WPBakeryShortCode {
    	protected function content( $atts, $content = null ) {
    		 extract(shortcode_atts(array(
            'table_title' => 'Basic Plan', 
            'table_price' => '0',
            'table_currency' => '$',
            'table_price_period' => 'month',
            'table_show_button' => 'yes',
            'table_button_text' => 'Purchase',
            'table_link' => '',
            'table_target' => '_self',
            'featured' => '',

        ), $atts)); 
            wp_enqueue_style( 'wdo-ult-pricing-bootstrap',  ULT_URL.'assets/css/bootstrap-min.css');
            wp_enqueue_style( 'wdo-ult-pricing',  ULT_URL.'assets/css/ult-pricing.css');
            wp_enqueue_script( 'wdo-ult-pricing-js',  ULT_URL.'assets/js/pure-table.js',array('jquery'));

            ?>
                <div class="wdo-cols">
                    <div class="tc-animation-hover">
                        <div class="pricing-plan <?php echo ( $featured == 'yes' ) ? 'featured' : '' ; ?> tc-animation-fade">
                            <div class="pricing-head">
                                <div class="price">
                                    <sup> <?php echo $table_currency; ?> </sup>
                                    <span class="value"><?php echo $table_price; ?></span>
                                    <span class="duration"> / <?php echo $table_price_period; ?></span>
                                </div>
                                <div class="name"> 
                                    <?php echo $table_title; ?>
                                </div>
                                <div class="p-button">
                                    <a class="dt-sc-button medium" href="<?php echo $table_link; ?>" target="<?php echo $table_target; ?>">
                                        <?php echo $table_button_text; ?>
                                    </a>
                                </div>
                            </div>
                            <div class="pricing-body">
                                <?php echo $content; ?>
                            </div>
                        </div>
                    </div>
                </div>

            <?php
    	}

    }
}

if (function_exists("vc_map")) {
    vc_map(array(
        "name" => __("Pricing Tables", "wdo-ultimate-addons"),
        "description" => __("Display pricing.", 'wdo-ultimate-addons'),
        "base" => "wdo_ult_pricing_parent",
        "as_parent" => array('only' => 'wdo_ult_pricing_child'),
        "content_element" => true,
        "show_settings_on_create" => true,
        "category" => 'All in One Addons',
        "is_container" => true,
        'description' => __('Insert Pricing Tables', 'wdo-ultimate-addons'),
        "js_view" => 'VcColumnView',
        "icon"      => ULT_URL.'icons/pricing-table-icon.png',
        "params" => array(
                array(
                    "type" => "dropdown",
                    "heading" => "Columns",
                    "param_name" => "wdo_columns",
                    "value" => array(
                        "Two"       => "col-md-6 col-sm-6",
                        "Three"     => "col-md-4 col-sm-6",
                        "Four"      => "col-md-3 col-sm-6",
                    ),
                    'save_always' => true,
                    "description" => "Select number of pricing tables you want to show in a row."
                ),

                array(
                    "type" => "dropdown",
                    "heading" => "Color Scheme",
                    "group" => 'Color Schemes',
                    "param_name" => "pricing_color_scheme",
                    "value" => array(
                        "Blue" => "blue",
                        "Green" => "green", 
                        "Orange" => "orange",
                        "Red"   => "red",
                        "Violet"    => "violet",
                    ),
                    "description" => ""
                ),

                array(
                    "type" => "html",
                    "group" => "Demo",
                    "heading" => "<h3 style='padding: 10px;background: #2b4b80;text-align:center;'><a style='color: #fff;text-decoration:none;' target='_blank' href='https://demo.webdevocean.com/pricing-tables-vc/' >Click to See Demo</a>",
                    "param_name" => "demo",
                ),
        ),
    ));

}

if (function_exists("vc_map")) {
    $animationEffects = array(
                'Fade'          =>  'tc-animation-fade',
                'Slide Top'     =>  'tc-animation-slide-top',
                'Slide Bottom'  =>  'tc-animation-slide-bottom',
                'Slide Left'    =>  'tc-animation-slide-left',
                'Slide Right'   =>  'tc-animation-slide-right',
                'Scale Up'      =>  'tc-animation-scale-up',
                'Scale Down'    =>  'tc-animation-scale-down',
                'Shake'         =>  'tc-animation-shake',
                'Rotate'        =>  'tc-animation-rotate',
                'Scale'         =>  'tc-animation-scale',
                'Scale'         =>  'tc-animation-scale',
    );

    //Register "container" content element. It will hold all your inner (child) content elements
    vc_map(array(
        "name" => __("Pricing Table", "wdo-ultimate-addons"),
        "base" => "wdo_ult_pricing_child",
        "content_element" => true,
        "as_child" => array('only' => 'wdo_ult_pricing_parent'),
        "icon" => 'icon-wpb-pricing_column',
        "params" => array(
                        
                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => "Title",
                            "param_name" => "table_title",
                            "value" => "Basic Plan",
                            "group"         => "Typography",
                        ),
                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => "Price",
                            "param_name" => "table_price",
                            "description" => "",
                            "group"         => "Typography",
                        ),
                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => "Currency",
                            "param_name" => "table_currency",
                            "description" => "",
                            "group"         => "Typography",
                        ),
                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => "Price Period",
                            "param_name" => "table_price_period",
                            "description" => "",
                            "group"         => "Typography",
                        ),
                        array(
                            "type" => "textarea_html",
                            "class" => "",
                            "heading" => "Content",
                            "param_name" => "content",
                            "value" => '<li class="whyt">Your Content Here</li>
                                        <li>Your Content Here</li>
                                        <li class="whyt">Your Content Here</li>
                                        <li>Your Content Here</li>
                                        <li class="whyt">Your Content Here</li>',
                            "description" => "",
                            "group"         => "Typography",
                        ),
                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => "Show Button",
                            "param_name" => "table_show_button",
                            "value" => array(
                                "Yes" => "yes",
                                "No" => "no"
                            ),
                            'save_always' => true,
                            "group"         => "Button",
                        ),
                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => "Button Text",
                            "param_name" => "table_button_text",
                            "description" => "Default label is Purchase",
                            "dependency" => array('element' => 'table_show_button', 'value' => 'yes'),
                            "group"         => "Button",
                        ),
                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => "Button Link",
                            "param_name" => "table_link",
                            "dependency" => array('element' => 'table_show_button', 'value' => 'yes'),
                            "group"         => "Button",
                        ),
                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => "Button Target",
                            "param_name" => "table_target",
                            "value" => array(
                                "" => "",
                                "Self" => "_self",
                                "Blank" => "_blank",    
                                "Parent" => "_parent"
                            ),
                            "dependency" => array('element' => 'table_show_button', 'value' => 'yes'),
                            "group"         => "Button",
                        ),
                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => "Featured",
                            "param_name" => "featured",
                            "value" => array(
                                "No" => "no",
                                "Yes" => "yes"  
                            ),
                            'save_always' => true,
                            "description" => "This would be shown as different as compared to other tables.",
                            "group"         => "Featured",
                        ),
                        
                        array(
                            "type" => "html",
                            "group" => "Demo",
                            "heading" => "<h3 style='padding: 10px;background: #2b4b80;text-align:center;'><a style='color: #fff;text-decoration:none;' target='_blank' href='https://demo.webdevocean.com/pricing-tables-vc/' >Click to See Demo</a>",
                            "param_name" => "demo",
                        ),
                        
            )
    ));


}
?>