<?php
// Register and load the widget
function wcatcbll_widget()
{
    register_widget('wccb_widget');
}
add_action('widgets_init', 'wcatcbll_widget');

// Creating the widget
class wccb_widget extends WP_Widget
{
    function __construct()
    {
        parent::__construct(
            // Base ID of your widget
            'wccb_widget',
            // Widget name will appear in UI
            __('Custom Cart Button', 'catcbll'),

            // Widget description
            array(
                'description' => __('Wcatcbll Product show', 'catcbll'),
            )
        );
    }

    // Creating widget front-end
    public function widget($args, $instance)    {
        global $product;
        $astra_active_or_not = get_option('template');
        if (isset($instance['title'])) {
            $title = apply_filters('widget_title', $instance['title']);
        } else {
            $title = '';
        }

        if (isset($instance['nprd'])) {
            $nbr_prd = apply_filters('widget_title', $instance['nprd']);
        } else {
            $nbr_prd = '';
        }

        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if (!empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        } else {
            echo $args['before_title'] . "Products" . $args['after_title'];
        }
        $arg = array(
            'post_type' => array('product'),
            'post_status' => 'publish',
            'posts_per_page' => '-1',
            'meta_key' => array(
                '_catcbll_btn_label'
            ),
        );
        $dbResult = new WP_Query($arg);
        $posts = $dbResult->posts;
        if ($dbResult->post_count >= $nbr_prd) {
            $count = $nbr_prd;
        } else {
            $count = $dbResult->post_count;
        }
        /*button styling settings */
        $catcbll_settings = get_option('_woo_catcbll_all_settings');
        extract($catcbll_settings);

        //button display setting
        if (isset($catcbll_both_btn)) {
            $both  = $catcbll_both_btn;
        } else {
            $both = '';
        }
        if (isset($catcbll_add2_cart)) {
            $add2cart = $catcbll_add2_cart;
        } else {
            $add2cart = '';
        }
        if (isset($catcbll_custom)) {
            $custom = $catcbll_custom;
        } else {
            $custom  = '';
        }
        // open new tab
        if (isset($catcbll_btn_open_new_tab)) {
            $btn_opnt_new_tab  = $catcbll_btn_open_new_tab;
        } else {
            $btn_opnt_new_tab = '';
        }

        /*Button Margin*/
        $btn_margin = $catcbll_margin_top . 'px ' . $catcbll_margin_right . 'px ' . $catcbll_margin_bottom . 'px ' . $catcbll_margin_left . 'px';

?>
        <style>
            <?php
            
            if(isset($catcbll_hide_btn_bghvr) && !empty($catcbll_hide_btn_bghvr) || isset($catcbll_btn_hvrclr) && !empty($catcbll_btn_hvrclr)){
                $btn_class = 'btn';
                $imp = '';
            }else{
                $btn_class = 'button';
                $imp = '!important';
            }

            if (isset($astra_active_or_not) && $astra_active_or_not == 'Avada') {
                $avada_style = 'display: inline-block;float: none !important;';
            }else{
                $avada_style = '';
            }
            echo '.widget_wccb_widget .catcbll_preview_button{text-align:'.$catcbll_custom_btn_alignment.';margin:'.$btn_margin.';}';
            echo '.widget_wccb_widget .catcbll_preview_button .fa{font-family:FontAwesome '. $imp.'}';
            echo '.widget_wccb_widget .' . $catcbll_hide_btn_bghvr.':before{border-radius:'.$catcbll_btn_radius.'px '. $imp.';background:'.$catcbll_btn_hvrclr.' '. $imp.';color:#fff '. $imp.';}'; 
            echo '.widget_wccb_widget .catcbll_preview_button .catcbll{'.$avada_style.'color:'.$catcbll_btn_fclr.' '. $imp.';font-size:'.$catcbll_btn_fsize.'px '. $imp.';padding:'.$catcbll_padding_top_bottom.'px '.$catcbll_padding_left_right.'px '. $imp.';border:'.$catcbll_border_size.'px solid '.$catcbll_btn_border_clr.' '. $imp.';border-radius:'.$catcbll_btn_radius.'px '. $imp.';background-color:'.$catcbll_btn_bg.' '. $imp.';}';
            echo '.widget_wccb_widget .catcbll_preview_button a{text-decoration: none '. $imp.';}';
            if(empty($catcbll_hide_btn_bghvr)){
                echo '.widget_wccb_widget .catcbll:hover{border-radius:'.$catcbll_btn_radius.' '. $imp.';background-color:'.$catcbll_btn_hvrclr.' '. $imp.';color:#fff '. $imp.';}';
            }
            ?>.widget_wccb_widget .quantity,
            .widget_wccb_widget .buttons_added {
                display: inline-block;
            }

            .widget_wccb_widget .stock {
                display: none
            }
        </style>
        <?php

        for ($x = 0; $x < $count; $x++) {

            // get featured image url in the database
            $pimg_id = get_post_meta($posts[$x]->ID, '_thumbnail_id', true);
            $pimg_url = get_post($pimg_id);
            $pimg_urls = $pimg_url->guid;

            // Get button label, URL and open-new-tab-checkbox value in the database
            $prd_lbl = get_post_meta($posts[$x]->ID, '_catcbll_btn_label', true); // Post meta
            $prd_url = get_post_meta($posts[$x]->ID, '_catcbll_btn_link', true); // Post meta


            //count button values               
            if (is_array($prd_lbl)) {
                $atxtcnt = count($prd_lbl);
            } else {
                $atxtcnt = '';
            }

            if ($btn_opnt_new_tab == "1") {
                $trgtblnk = "target='_blank'";
            } else {
                $trgtblnk = "";
            }

            if (($custom == "custom") || ($add2cart == "add2cart")) {
                if (!empty($prd_lbl[0]) && ($custom == "custom")) {
                    $html = '<img src="' . $pimg_urls . '" class="prd_img_shrtcd">'; // Product featured image
                    echo $html;

                    if ($catcbll_custom_btn_position == 'down' || $catcbll_custom_btn_position == 'right') {
                        if ($both == "both" && $add2cart == "add2cart") {
                            if ($product->is_type('variable')) {
                                woocommerce_single_variation_add_to_cart_button($posts[$x]->ID, $product); //Default
                            } else {
                                woocommerce_template_single_add_to_cart($posts[$x]->ID, $product); //Default
                            }
                        }
                    }
                    for ($y = 0; $y < $atxtcnt; $y++) {
                        $prd_btn = '';
                        if ($catcbll_btn_icon_psn == 'right') {
                            if (!empty($prd_lbl[$y])) {
                                $prd_btn = '<div class="catcbll_preview_button"><a href="' . $prd_url[$y] . '" class="'.$btn_class.' btn-lg catcbll ' . $catcbll_hide_btn_bghvr . ' ' . $catcbll_hide_2d_trans . '" ' . $trgtblnk . '>' . $prd_lbl[$y] . ' <i class="fa ' . $catcbll_btn_icon_cls . '"></i></a></div>';
                            }
                        } else {
                            //Checking label field .It is empty or not
                            if (!empty($prd_lbl[$y])) {
                                $prd_btn = '<div class="catcbll_preview_button"><a href="' . $prd_url[$y] . '" class="'.$btn_class.' btn-lg catcbll ' . $catcbll_hide_btn_bghvr . ' ' . $catcbll_hide_2d_trans . ' " ' . $trgtblnk . '><i class="fa ' . $catcbll_btn_icon_cls . '"></i> ' . $prd_lbl[$y] . ' </a></div>';
                            }
                        }
                        echo $prd_btn;
                    } //end for
                    
                    if ($catcbll_custom_btn_position == 'up' || $catcbll_custom_btn_position == 'left') {
                        if ($both == "both" && $add2cart == "add2cart") {
                            if ($product->is_type('variable')) {
                                woocommerce_single_variation_add_to_cart_button($posts[$x]->ID, $product); //Default
                            } else {
                                woocommerce_template_single_add_to_cart($posts[$x]->ID, $product); //Default
                            }
                        }
                    }
                }
            } else {
                $html = '';
                $html .= '<img src="' . $pimg_urls . '" class="prd_img_shrtcd">'; // Product featured image
                if (preg_match("/\.(gif|png|jpg)$/", $pimg_urls)) {
                    echo $html;
                    $arg = array(
                        'post_type' => array(
                            'product'
                        ),
                        'post_status' => 'publish',
                        'posts_per_page' => '-1',
                        'meta_key' => array(
                            '_catcbll_btn_label'
                        ),
                    );
                    $loop = new WP_Query($arg);
                    while ($loop->have_posts()) :
                        $loop->the_post();
                        $prd_id = $loop->post->ID;
                        if ($posts[$x]->ID == $prd_id) {
                            if ($product->is_type('variable')) {
                                woocommerce_single_variation_add_to_cart_button($prd_id, $product); //Default
                            } else {
                                woocommerce_template_single_add_to_cart($prd_id, $product); //Default
                            }
                        }
                    endwhile;
                }
            }
        } // end for each
        echo $args['after_widget'];
    }

    // Widget Backend
    public function form($instance)
    {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('New title', 'catcbll');
        }
        if (isset($instance['nprd'])) {
            $nbr_prd = $instance['nprd'];
        } else {
            $nbr_prd = '';
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p id="wcatbtn_nprd">
            <label for="<?php echo $this->get_field_id('nprd'); ?>"><?php _e(__('Number of products to show', 'catcbll')); ?></label>
            <input class="widefat wcatbtn_nprd" id="<?php echo $this->get_field_id('nprd'); ?>" name="<?php echo $this->get_field_name('nprd'); ?>" type="text" value="<?php echo esc_attr($nbr_prd); ?>" />
        </p>
<?php
    }

    // Updating widget replacing old instances with new
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['nprd'] = (!empty($new_instance['nprd'])) ? strip_tags($new_instance['nprd']) : '';
        return $instance;
    }
} // Class wpb_widget ends here

?>