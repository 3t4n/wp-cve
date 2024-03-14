<?php
// hooks your functions into the correct filters
function wcatcbll_add_mce()
{

    // check user permissions
    if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
        return;
    }
    // check if WYSIWYG is enabled
    if ('true' == get_user_option('rich_editing')) {
        add_filter('mce_external_plugins', 'wcatcbll_add_tinymce_plugin');
        add_filter('mce_buttons', 'wcatcbll_register_mce_button');
    }
}
add_action('admin_head', 'wcatcbll_add_mce');

// register shortcode button in the editor
function wcatcbll_register_mce_button($buttons)
{
    array_push($buttons, "wccb_shrtcd");
    return $buttons;
}

// declare a script for the Shortcode button
// the script will insert the shortcode on the click event
function wcatcbll_add_tinymce_plugin($plugin_array)
{
    $plugin_array['Wccbinsertshortcode'] = WCATCBLL_CART_JS . 'wcatcbll_shortcode.js';
    return $plugin_array;
}

if (!function_exists('wcatcbll_shortcode')) {
    function wcatcbll_shortcode($atts = array())
    {
        $astra_active_or_not = get_option('template');
        // latest product showing when no product id pass
        if (!empty($atts["pid"]) && isset($atts["pid"])) {
            $pid = $atts['pid']; // get parameter value in the shortcode
            $pids = explode(",", $pid);
            $pid_count = count($pids);
        } else {
            $pid_count = 0;
        }

        /* 
        ** Button styling using shortcode perameters
        ** @General setting values isset($ccbtn_setting)
        */
        $catcbll_settings = get_option('_woo_catcbll_all_settings');
        extract($catcbll_settings);
        $shortcode_attr = array('background', 'font_size', 'font_color', 'font_awesome', 'border_color', 'border_size', 'icon_position', 'image');
        $option_key_vals = array();


        foreach ($shortcode_attr as $key) {
            if (isset($atts[$key])) {
                $option_key_vals[$key] = $atts[$key];
                if (empty($option_key_vals[$key])) {
                    switch ($key) {
                        case 'background':
                            $option_key_vals[$key] = $catcbll_btn_bg;
                            break;
                        case 'font_size':
                            $option_key_vals[$key] = $catcbll_btn_fsize;
                            break;
                        case 'font_color':
                            $option_key_vals[$key] = $catcbll_btn_fclr;
                            break;
                        case 'font_awesome':
                            $option_key_vals[$key] = $catcbll_btn_icon_cls;
                            break;
                        case 'border_color':
                            $option_key_vals[$key] = $catcbll_btn_border_clr;
                            break;
                        case 'border_size':
                            $option_key_vals[$key] = $catcbll_border_size;
                            break;
                        case 'icon_position':
                            $option_key_vals[$key] = $catcbll_btn_icon_psn;
                            break;
                        default:
                            $option_key_vals[$key] = '';
                    }
                }
            } else {
                $background = $catcbll_btn_bg;
                $font_size = $catcbll_btn_fsize;
                $font_color = $catcbll_btn_fclr;
                $font_awesome = $catcbll_btn_icon_cls;
                $border_color = $catcbll_btn_border_clr;
                $border_size = $catcbll_border_size;
                $icon_position = $catcbll_btn_icon_psn;
                $image = '';
            }
        }
        extract($option_key_vals);

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
        // shortcode setting end 

?>
        <style>
            <?php
            if ($catcbll_custom_btn_position == 'left' || $catcbll_custom_btn_position == 'right') {
                $display = 'display:inline-flex';
            } else {
                $display = 'display:block';
            }

            if (isset($catcbll_hide_btn_bghvr) && !empty($catcbll_hide_btn_bghvr) || isset($catcbll_btn_hvrclr) && !empty($catcbll_btn_hvrclr)) {
                $btn_class = 'btn';
                $imp = '';
            } else {
                $btn_class = 'button';
                $imp = '!important';
            }
            if (isset($astra_active_or_not) && $astra_active_or_not == 'Avada') {
                $avada_style = 'display: inline-block;float: none !important;';
            } else {
                $avada_style = '';
            }

            echo 'form.cart{display:inline-block}';
            echo '.catcbll_preview_button{text-align:' . $catcbll_custom_btn_alignment . ';margin:' . $btn_margin . ';display:' . $display . '}';
            echo '.catcbll_preview_button .fa{font-family:FontAwesome ' . $imp . '}';
            echo '.' . $catcbll_hide_btn_bghvr . ':before{border-radius:' . $catcbll_btn_radius . 'px ' . $imp . ';background:' . $catcbll_btn_hvrclr . ' ' . $imp . ';color:#fff ' . $imp . ';}';
            echo '.catcbll_preview_button .catcbll{' . $avada_style . 'color:' . $catcbll_btn_fclr . ' ' . $imp . ';font-size:' . $catcbll_btn_fsize . 'px ' . $imp . ';padding:' . $catcbll_padding_top_bottom . 'px ' . $catcbll_padding_left_right . 'px ' . $imp . ';border:' . $catcbll_border_size . 'px solid ' . $catcbll_btn_border_clr . ' ' . $imp . ';border-radius:' . $catcbll_btn_radius . 'px ' . $imp . ';background-color:' . $catcbll_btn_bg . ' ' . $imp . ';}';
            echo '.catcbll_preview_button a{text-decoration: none ' . $imp . ';}';
            if (empty($catcbll_hide_btn_bghvr)) {
                echo '.catcbll:hover{border-radius:' . $catcbll_btn_radius . ' ' . $imp . ';background-color:' . $catcbll_btn_hvrclr . ' ' . $imp . ';color:#fff ' . $imp . ';}';
            }
            ?>.quantity,
            .buttons_added {
                display: inline-block;
            }

            .stock {
                display: none
            }
        </style>
<?php
        if ($pid_count > 0) {
            for ($x = 0; $x < $pid_count; $x++) {
                // get featured image url in the database
                if (get_post_type($pids[$x]) == 'product') {
                    $pimg_id = get_post_meta($pids[$x], '_thumbnail_id', false);
                    $pimg_url = get_post($pimg_id[0]);
                    $pimg_url = $pimg_url->guid;

                    // Get button label, URL and open-new-tab-checkbox value in the database
                    $prd_lbl = get_post_meta($pids[$x], '_catcbll_btn_label', true);
                    $prd_url = get_post_meta($pids[$x], '_catcbll_btn_link',  true);

                    if ($btn_opnt_new_tab == "1") {
                        $trgtblnk = "target='_blank'";
                    } else {
                        $trgtblnk = "";
                    }
                    //count button values               
                    if (is_array($prd_lbl)) {
                        $atxtcnt = count($prd_lbl);
                    } else {
                        $atxtcnt = '';
                    }

                    if (($custom == "custom") || ($add2cart == "add2cart")) {
                        if (!empty($prd_lbl[0]) && ($custom == "custom")) {
                            if ($image == 'false' || $image == '') {
                                $html = '';
                            } else {
                                $html = '<img src="' . $pimg_url . '" class="prd_img_shrtcd">';
                            }
                            echo "<div class='shortcode_" . $x . "'>" . $html;
                            if ($catcbll_custom_btn_position == 'down' || $catcbll_custom_btn_position == 'right') {
                                $product = new WC_Product($pids[$x]);
                                $add_to_cart = do_shortcode('[add_to_cart_url id="' . $pids[$x] . '"]');
                                if ($both == "both" && $add2cart == "add2cart") {
                                    if ($product->is_type('variable')) {
                                        woocommerce_template_loop_add_to_cart($pids[$x], $product);
                                    } else {
                                        woocommerce_template_single_add_to_cart($pids[$x], $product);
                                    }
                                }
                            }
                            //Show multiple button using loop
                            for ($y = 0; $y < $atxtcnt; $y++) {
                                $prd_btn = '';
                                if ($icon_position == 'right') {
                                    if (!empty($prd_lbl[$y])) {
                                        $prd_btn = '<div class="catcbll_preview_button"><a href="' . $prd_url[$y] . '" class="' . $btn_class . ' btn-lg catcbll ' . $catcbll_hide_btn_bghvr . ' ' . $catcbll_hide_2d_trans . '" ' . $trgtblnk . '>' . $prd_lbl[$y] . ' <i class="fa ' . $font_awesome . '"></i></a></div>';
                                    }
                                } else {
                                    //Checking label field .It is empty or not
                                    if (!empty($prd_lbl[$y])) {
                                        $prd_btn = '<div class="catcbll_preview_button"><a href="' . $prd_url[$y] . '" class="' . $btn_class . ' btn-lg catcbll ' . $catcbll_hide_btn_bghvr . ' ' . $catcbll_hide_2d_trans . ' " ' . $trgtblnk . '><i class="fa ' . $font_awesome . '"></i> ' . $prd_lbl[$y] . ' </a></div>';
                                    }
                                }
                                echo $prd_btn;
                            } // end for loop
                            if ($catcbll_custom_btn_position == 'up' || $catcbll_custom_btn_position == 'left') {
                                $product = new WC_Product($pids[$x]);
                                $add_to_cart = do_shortcode('[add_to_cart_url id="' . $pids[$x] . '"]');
                                if ($both == "both" && $add2cart == "add2cart") {
                                    if ($product->is_type('variable')) {
                                        woocommerce_template_loop_add_to_cart($pids[$x], $product);
                                    } else {
                                        woocommerce_template_single_add_to_cart($pids[$x], $product);
                                    }
                                }
                            }
                            echo "</div>";
                        } else {
                            $product = new WC_Product($pids[$x]);
                            $add_to_cart = do_shortcode('[add_to_cart_url id="' . $pids[$x] . '"]');
                            if ($both == "both" || $add2cart == "add2cart") {
                                if ($image == 'false' || $image == '') {
                                    $html = '';
                                } else {
                                    $html = '<img src="' . $pimg_url . '" class="prd_img_shrtcd">';
                                }
                                echo "<div class='shortcode_" . $x . "'>" . $html;

                                if ($product->is_type('variable')) {
                                    woocommerce_template_loop_add_to_cart($pids[$x], $product);
                                } else {
                                    woocommerce_template_single_add_to_cart($pids[$x], $product);
                                }
                                echo "</div>";
                            }
                        }
                    } else {
                        $html = '';
                        $html .= '<img src="' . $pimg_url . '" class="prd_img_shrtcd">'; // Product featured image
                        echo $html;
                        $args = array(
                            'post_type' => array('product'),
                            'post_status' => 'publish',
                            'posts_per_page' => '-1',
                            'meta_key' => array(
                                '_catcbll_btn_label'
                            ),
                        );
                        $loop = new WP_Query($args);
                        while ($loop->have_posts()) :
                            $loop->the_post();
                            $prd_id = $loop->post->ID;
                            if ($pid[$x] == $prd_id) {
                                // woocommerce_template_single_add_to_cart( $prd_id, $product );
                                if ($product->is_type('variable')) {
                                    woocommerce_template_loop_add_to_cart($prd_id, $product);
                                } else {
                                    woocommerce_template_single_add_to_cart($prd_id, $product);
                                }
                            }
                        endwhile;
                        echo '<br><br>';
                    } // end else                    
                } // end if(post_type='product')
                else {
                    echo "Please use correct product Id";
                }
            } // end main for loop

        } // end if pid count > 0
        else {
            echo __('Please Write Us PID Perameter In Shortcode', '') . " ([catcbll pid='Please change it to your product ID'])";
        }
    } // close function

}
add_shortcode('catcbll', 'wcatcbll_shortcode');
?>