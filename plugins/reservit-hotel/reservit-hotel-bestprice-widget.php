<?php
/*
 *      Reservit Hotel Best Price Widget
 *      Version: 1.9
 *      By Reservit
 *
 *      Contact: http://www.reservit.com/hebergement
 *      Created: 2017
 *      Modified: 15/05/2019
 *
 *      Copyright (c) 2017, Reservit. All rights reserved.
 *
 *      Licensed under the GPLv2 license - https://www.gnu.org/licenses/gpl-2.0.html
 *
 */
include_once plugin_dir_path(__FILE__) . '/reservit-hotel-language.php';

class Reservit_Hotel_Bestprice_Widget extends WP_Widget {

    /**
     * New instance Reservit_Hotel-Bestprice widget
     *
     * @access public
     */
    public function __construct() {

        $widget_ops = array(
            'description' => esc_html__('A room best price widget for your hotel by Reservit', 'reservit-hotel'),
        );


        parent::__construct('reservit_hotel_bestprice', 'Reservit Hotel', $widget_ops);

        //Js for Reservit hotel bestprice widget
        wp_enqueue_script('rsvit_hotel_script', plugins_url('reservit-hotel.js', __FILE__), array('jquery'), '', true);
        //localise the plugin directory for later use in js
        wp_localize_script('rsvit_hotel_script', 'rsvitHotelScript', array('reservitClickUrl' => plugins_url('', __FILE__),));

        add_action('wp_enqueue_scripts', array($this, 'add_rsvit_hotel_widget_css'));
    }

    //Load Widget CSS
    public function add_rsvit_hotel_widget_css() {
        //Load fontawsome css.min cdn
        wp_enqueue_style('rsvit_front_fontawsome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');

        //Reservit hotel bestprice widget static CSS
        wp_enqueue_style('rsvit_hotel_style', plugins_url('reservit-hotel-bestprice-widget.css', __FILE__));
        //Inline CSS generated from the options
        //display icon
        $rsvit_btn_ico = sanitize_text_field(get_option('rsvit_btn_ico'));
        //button style
        $rsvit_btn_bgcolor = sanitize_text_field(get_option('rsvit_btn_bgcolor'));
        $rsvit_btn_color = sanitize_text_field(get_option('rsvit_btn_color'));
        $rsvit_btn_fontsize = sanitize_text_field(get_option('rsvit_btn_fontsize') . get_option('rsvit_btn_fontunit'));
        $rsvit_btn_fontweight = sanitize_text_field(get_option('rsvit_btn_fontweight'));
        $rsvit_btn_radius = sanitize_text_field(get_option('rsvit_btn_radius') . get_option('rsvit_btn_radiusunit'));
        $rsvit_btn_bordercolor = sanitize_text_field(get_option('rsvit_btn_bordercolor'));
        $rsvit_btn_borderwidth = sanitize_text_field(get_option('rsvit_btn_borderwidth') . get_option('rsvit_btn_borderunit'));
        $rsvit_btn_borderwidth_get_option = get_option('rsvit_btn_borderwidth');
        if (!empty($rsvit_btn_borderwidth_get_option)) {
            $rsvit_btn_border_style = 'solid';
        } else {
            $rsvit_btn_border_style = 'initial';
        }
        //mobile button style
        $rsvit_btn_mobilebgcolor = sanitize_text_field(get_option('rsvit_btn_mobilebgcolor'));
        $rsvit_btn_mobilecolor = sanitize_text_field(get_option('rsvit_btn_mobilecolor'));
        $rsvit_btn_mobilebordercolor = sanitize_text_field(get_option('rsvit_btn_mobilebordercolor'));
        //button hover
        $rsvit_btn_hoverbgcolor = sanitize_text_field(get_option('rsvit_btn_hoverbgcolor'));
        $rsvit_btn_hovercolor = sanitize_text_field(get_option('rsvit_btn_hovercolor'));
        $rsvit_btn_hoverbordercolor = sanitize_text_field(get_option('rsvit_btn_hoverbordercolor'));
        //mobile button hover
        $rsvit_btn_mobilehoverbgcolor = sanitize_text_field(get_option('rsvit_btn_mobilehoverbgcolor'));
        $rsvit_btn_mobilehovercolor = sanitize_text_field(get_option('rsvit_btn_mobilehovercolor'));
        $rsvit_btn_mobilehoverbordercolor = sanitize_text_field(get_option('rsvit_btn_mobilehoverbordercolor'));
        //box style
        $rsvit_box_btn_color = sanitize_text_field(get_option('rsvit_box_btn_color'));
        $rsvit_box_btn_textcolor = sanitize_text_field(get_option('rsvit_box_btn_textcolor'));

        //generate css
        $hotel_from_options_css = "
        	#box_btn {
        		background-color: {$rsvit_box_btn_color};	
        	}
        	
        	#box_btn_close {
        		color: {$rsvit_box_btn_textcolor};
        	}
        	
        	#btn_bed_ico {
        	display: {$rsvit_btn_ico};
        	}
        	
        	#rsvit_btn {
        		background-color: {$rsvit_btn_bgcolor};
        		color: {$rsvit_btn_color};
        		font-size: {$rsvit_btn_fontsize};
        		font-weight: {$rsvit_btn_fontweight};
        		border-top-left-radius: {$rsvit_btn_radius};
        	    border-top-right-radius: 0;
        	    border-bottom-left-radius: {$rsvit_btn_radius};
        	    border-bottom-right-radius: 0;
        	    border-color: {$rsvit_btn_bordercolor};
        	    border-width: {$rsvit_btn_borderwidth};
        	    border-style: {$rsvit_btn_border_style};
        	}
        	
        	#rsvit_btn:hover {
        		background-color: {$rsvit_btn_hoverbgcolor};
        		color: {$rsvit_btn_hovercolor};
        		border-color: {$rsvit_btn_hoverbordercolor};
        	}
        	
        	@media (max-width: 768px) {
			    #rsvit_btn {
			        background-color: {$rsvit_btn_mobilebgcolor};
	        		color: {$rsvit_btn_mobilecolor};
	        		border-top-left-radius: {$rsvit_btn_radius};
	        	    border-top-right-radius: {$rsvit_btn_radius};
	        	    border-bottom-left-radius: 0;
	        	    border-bottom-right-radius: 0;
	        	    border-color: {$rsvit_btn_mobilebordercolor};
			    }
			    #rsvit_btn:hover {
	        		background-color: {$rsvit_btn_mobilehoverbgcolor};
	        		color: {$rsvit_btn_mobilehovercolor};
	        		border-color: {$rsvit_btn_mobilehoverbordercolor};
	        	}
        	}
        							";
        wp_add_inline_style('rsvit_hotel_style', $hotel_from_options_css);
        //Inline Custom CSS generated from the option Custom CSS
        $hotel_custom_css = esc_textarea(get_option('rsvit_hotel_custom_css'));
        wp_add_inline_style('rsvit_hotel_style', $hotel_custom_css);
    }

    //widget
    public function widget($args, $instance) {
        echo $args['before_widget'];
        echo $args['before_title'];
        echo $args['after_title'];

        if (get_option('rsvit_hotel_bestprice_display') !== 'true') {
            $showprice = 'false';
        } else {
            $showprice = get_option('rsvit_hotel_bestprice_display');
        }
        
        if (get_option('rsvit_hotel_distributorpriceblock_display') !== 'true') {
            $distributorpriceblock_display = 'false';
        } else {
            $distributorpriceblock_display = get_option('rsvit_hotel_distributorpriceblock_display');
        }
        
        if (get_option('rsvit_hotel_distributorprice_display') !== 'true') {
            $distributorprice_display = 'false';
        } else {
            $distributorprice_display = get_option('rsvit_hotel_distributorprice_display');
        }

        $langue_widget = setRsvitLanguageDefault();
        $dyn_text_btn = 'rsvit_btn_txt_' . $langue_widget;
        $rsvit_box_borderwidth_get_option = get_option('rsvit_box_borderwidth');
        if (!empty($rsvit_box_borderwidth_get_option)) {
            $borderw = $rsvit_box_borderwidth_get_option;
        } else {
            $borderw = 4;
        }
        ?>

        <script type="text/javascript">

            window.onload = function () {
                var rsvitCookieName = "rsvit_box_closed";
                var rsvitCookieVal;

                var reservitDivSize;
                var formOrientation;

                if (window.innerHeight >= "450") {
                    reservitDivSize = "250px";
                    formOrientation = "vertical";
                } else {
                    reservitDivSize = "450px";
                    formOrientation = "horizontal";
                }

                // Widget Configuration
                var paramsWidget = {
                    'fromdate': '',
                    'nbAdultMax': '<?= get_option('rsvit_hotel_max_adlut'); ?>', // Nombre maximum d'adultes selectionnable par l'utilisateur
                    'nbChildMax': '<?= get_option('rsvit_hotel_max_child'); ?>', // Nombre maximum d'enfants selectionnable par l'utilisateur
                    'bDisplayBestPrice': '<?= $showprice; ?>', // Determine l'affichage ou non du bloc présentant le meilleur tarif
                    'langcode': '<?= $langue_widget; ?>', // Langue du widget
                    'divContainerWidth': reservitDivSize, // Largeur (en px) du div contenant le widget, dans le cas d'une intégration en iframe (400px conseillé au minimum en largeur de l'iframe)
                    'displayMode': formOrientation, // Affichage du Widget en mode horizontal ou vertical (valeurs à mettre : horizontal ou vertical)
                    'partid': '<?= get_option('rsvit_hotel_partner_id'); ?>', // Id du partenaire s'affichant a la place du tarif "site web hotel" (partid), ce parametre est optionnel, vous pouvez donc ne pas le remplir}}
                    'bDisplayDistrib': '<?= $distributorpriceblock_display; ?>', // Determine l'affichage ou non du bloc présentant les distributeurs
                    'partidDistrib': '<?= get_option('rsvit_hotel_distributorpartner1_id'); ?>', // Id du partenaire avec lequel comparer vos tarifs (partidDistrib), ce parametre est optionnel, vous pouvez donc ne pas le remplir}}
                    'partidDistrib01': '<?= get_option('rsvit_hotel_distributorpartner2_id'); ?>', // Id du partenaire avec lequel comparer vos tarifs (partidDistrib), ce parametre est optionnel, vous pouvez donc ne pas le remplir}}
                    'partidDistrib02': '<?= get_option('rsvit_hotel_distributorpartner3_id'); ?>', // Id du partenaire avec lequel comparer vos tarifs (partidDistrib), ce parametre est optionnel, vous pouvez donc ne pas le remplir}}
                    'showDistribEqual': '<?= $distributorprice_display; ?>',
                    'version': '<?= get_option('rsvit_hotel_design_version'); ?>' // Version du design
                };

                console.log('Content fully loaded!');
                fill_the_box('<?= get_option('rsvit_hotel_id'); ?>', '<?= get_option('rsvit_chaine_id'); ?>', paramsWidget);

                if (document.cookie.indexOf(rsvitCookieName + '=') != -1) {
                } else {
                    creerCookie(rsvitCookieName, "no", 365);
                    console.log('Cookie initialized');
                }
                ;
                rsvitCookieVal = getCookie(rsvitCookieName);
                show_the_btn(rsvitCookieVal);

                //Window size change
                jQuery(window).on('resize', function (event) {

                    if (window.innerHeight >= "450") {
                        paramsWidget.divContainerWidth = "250px";
                        paramsWidget.displayMode = "vertical";
                    } else {
                        paramsWidget.divContainerWidth = "450px";
                        paramsWidget.displayMode = "horizontal";
                    }

                    fill_the_box('<?= get_option('rsvit_hotel_id'); ?>', '<?= get_option('rsvit_chaine_id'); ?>', paramsWidget);
                });

            }
        </script>

        <button id="rsvit_btn"><i id="btn_bed_ico" class="fa fa-bed" aria-hidden="true"></i><?php echo get_option($dyn_text_btn); ?></button>
        <div id="ReservitBestPriceWidgetbox1">
            <div id="box_btn">
                <i id="box_btn_close" class="fa fa-times" aria-hidden="true"></i>
            </div>
            <div id="ReservitBestPriceWidgetbox">
                <iframe id="ReservitBestPriceWidget"></iframe>
            </div>
        </div>


        <?php
        echo $args['after_widget'];
    }

}
