<?php
//Custom ATC button on archive page.
if (!function_exists('catcbll_woo_template_loop_custom_button')) {
    function catcbll_woo_template_loop_custom_button()
    {
        $astra_active_or_not = get_option('template');
        include(WCATCBLL_CART_PUBLIC . 'wcatcbll_all_settings.php');

        /*Both button or not*/
        if (!empty($prd_lbl[0]) && $custom == "custom") { ?>
            <style>
                :root {
                    --text-align: <?php echo $catcbll_custom_btn_alignment; ?>;
                    --margin: <?php echo $btn_margin; ?>;
                    --display: <?php echo $display; ?>;
                    --border-radius: <?php echo $catcbll_btn_radius . 'px ' . $imp; ?>;
                    --color: <?php echo $catcbll_btn_fclr . ' ' . $imp; ?>;
                    --font-size: <?php echo $catcbll_btn_fsize . 'px ' . $imp; ?>;
                    --padding: <?php echo $catcbll_padding_top_bottom . 'px ' . $catcbll_padding_left_right . 'px ' . $imp; ?>;
                    --border: <?php echo $catcbll_border_size . 'px solid '; ?>;
                    --background-color: <?php echo $catcbll_btn_bg . ' ' . $imp; ?>;
                    --border-color: <?php echo $catcbll_btn_border_clr . ' ' . $imp; ?>
                }

                <?php
                $crtubtn_rds = '';
                echo '.catcbnl_mtxt{width: 100%; display: inline-block;}';
                if (isset($catcbll_ready_to_use) && !empty($catcbll_ready_to_use)) {

                    $crtubtn = explode(" ", $catcbll_ready_to_use);
                    if (!empty($catcbll_btn_fclr)) {
                        echo "." . $crtubtn[1] . " {--color1: var(--color);}";
                    }
                    if (!empty($catcbll_border_size)) {
                        echo "." . $crtubtn[1] . " {--border1: var(--border);}";
                    }
                    if (!empty($catcbll_btn_border_clr)) {
                        echo "." . $crtubtn[1] . " {--border-color1:var(--border-color);}";
                    }
                    if (!empty($catcbll_padding_top_bottom) && !empty($catcbll_padding_left_right)) {
                        echo "." . $crtubtn[1] . " { --padding1: var(--padding);}";
                    }
                    if (!empty($catcbll_btn_fsize)) {
                        echo "." . $crtubtn[1] . " {--font-size1: var(--font-size);}";
                    }
                    if (!empty($catcbll_btn_bg)) {
                        echo "." . $crtubtn[1] . " {--background1: var(--background-color);}";
                    }
                    if (!empty($catcbll_btn_radius) && $catcbll_btn_radius > 6) {
                        echo "." . $crtubtn[1] . " {--border-radius1: var(--border-radius);}";
                    } else {
                        $crtubtn_rds = 'var(--border-radius1);';
                    }

                    echo '.' . $crtubtn[1] . '{--text-align1: center;-text-decoration1: none;--display1: inline-block;}';
                }

                if (isset($crtubtn_rds) && !empty($crtubtn_rds)) {
                    $before_radius = $crtubtn_rds;
                } else {
                    $before_radius = $catcbll_btn_radius - 4 . 'px';
                }

                echo '.' . $catcbll_hide_btn_bghvr . ':before{border-radius:' . $before_radius . ' ' . $imp . ';background:' . $catcbll_btn_hvrclr . ' ' . $imp . ';color:#fff ' . $imp . ';' . $avada_hover . '}';
                if (empty($catcbll_hide_btn_bghvr)) {
                    echo '.catcbll:hover{border-radius:' . $catcbll_btn_radius . 'px ' . $imp . ';background-color:' . $catcbll_btn_hvrclr . ' ' . $imp . ';color:#fff ' . $imp . ';}';
                }
                ?>
            </style>
<?php
            if ($catcbll_custom_btn_position == 'down' || $catcbll_custom_btn_position == 'right') {
                if (($both == "both") && ($add2cart == "add2cart")) {
                    woocommerce_template_loop_add_to_cart();
                }
            }

            //Show multiple button using loop
            for ($y = 0; $y < $atxtcnt; $y++) {
                if (!empty($prd_url[$y])) {
                    $aurl = $prd_url[$y];
                } else {
                    $aurl = site_url() . '/?add-to-cart=' . $pid;
                }
                $prd_btn = '';
                if ($catcbll_btn_icon_psn == 'right') {
                    if (!empty($prd_lbl[$y])) {
                        $prd_btn = '<div class="catcbll_preview_button"><a href="' . $aurl . '" class="' . $btn_class . '  ' . $catcbll_hide_btn_bghvr . ' ' . $catcbll_hide_2d_trans . '" ' . $trgtblnk . '>' . $prd_lbl[$y] . ' <i class="fa ' . $catcbll_btn_icon_cls . '"></i></a>
                        </div>';
                    }
                } else {
                    //Checking label field .It is empty or not
                    if (!empty($prd_lbl[$y])) {
                        $prd_btn = '<div class="catcbll_preview_button"><a href="' . $aurl . '" class="' . $btn_class . '  ' . $catcbll_hide_btn_bghvr . ' ' . $catcbll_hide_2d_trans . ' " ' . $trgtblnk . '><i class="fa ' . $catcbll_btn_icon_cls . '"></i> ' . $prd_lbl[$y] . ' </a>     
                        </div>';
                    }
                }
                echo $prd_btn;
            } //end for each

            if ($catcbll_custom_btn_position == 'up' || $catcbll_custom_btn_position == 'left') {
                if (($both == "both") && ($add2cart == "add2cart")) {
                    woocommerce_template_loop_add_to_cart();
                }
            }
        } else {
            woocommerce_template_loop_add_to_cart();
        }
        echo  '<div class="catcbnl_mtxt">'.$content.'</div>';
    }
}
$astra_active_or_not = get_option('template');
if (isset($astra_active_or_not) && $astra_active_or_not == 'Avada') {
    /* Remove Avada default add to cart button */
    add_action('after_setup_theme', 'remove_woo_commerce_hooks');
    function remove_woo_commerce_hooks()
    {
        global $avada_woocommerce;
        remove_action('woocommerce_after_shop_loop_item', array($avada_woocommerce, 'template_loop_add_to_cart'), 10);
        remove_action('woocommerce_after_shop_loop_item', array($avada_woocommerce, 'show_details_button'), 15);
        add_action('woocommerce_after_shop_loop_item', 'catcbll_woo_template_loop_custom_button', 10);
    }
} elseif (isset($astra_active_or_not) && $astra_active_or_not == 'oceanwp') {
    add_action('ocean_after_archive_product_inner', 'catcbll_woo_template_loop_custom_button', 10);
    add_filter('ocean_woo_product_elements_positioning', 'catcbll_woo_archive_temp_remove_default_button', 10, 2);
    function catcbll_woo_archive_temp_remove_default_button($sections)
    {
        unset($sections[6]);
        return $sections;
    }
} else {
    add_action('woocommerce_after_shop_loop_item', 'catcbll_woo_template_loop_custom_button', 10);
}

?>