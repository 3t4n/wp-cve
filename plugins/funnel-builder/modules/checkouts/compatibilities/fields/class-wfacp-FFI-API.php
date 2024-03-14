<?php

/**
 * FFl API By Optimum7
 * Plugin URI: https://wordpress.org/plugins/ffl-api/
 */
#[AllowDynamicProperties]

  class WFACP_Compatibility_FFI_API {

	public $is_field = false;

	public function __construct() {

		/* Register Add field */
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_field' ], 20 );
		add_filter( 'wfacp_html_fields_wfacp_ffl_api', '__return_false' );
		add_action( 'process_wfacp_html', [ $this, 'display_ffl_field' ], 999, 3 );
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );

	}

	public function is_enabled() {
		return class_exists( 'Ffl_Api_Public' );
	}

	public function add_field( $fields ) {
		if ( false === $this->is_enabled() ) {
			return $fields;
		}


		$fields['wfacp_ffl_api'] = [
			'type'       => 'wfacp_html',
			'class'      => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'wfacp_ffl_api' ],
			'id'         => 'wfacp_ffl_api',
			'field_type' => 'wfacp_ffl_api',
			'label'      => __( 'FFL API', 'woofunnels-aero-checkout' ),

		];

		return $fields;
	}

	public function display_ffl_field( $field, $key, $args ) {
		if ( ! empty( $key ) && 'wfacp_ffl_api' === $key && $this->is_enabled() ) {
			$location = get_option( 'ffl_init_map_location', 'woocommerce_checkout_order_review' );
			$object   = WFACP_Common::remove_actions( $location, 'Ffl_Api_Public', 'ffl_init_map' );
			if ( $object instanceof Ffl_Api_Public ) {
				$this->is_field = true;
				echo '<div id="ffl_container"></div>';
			}
		}
	}

	public function internal_css() {

		if ( false === $this->is_enabled() ) {
			return;
		}


		$aKey = esc_attr( get_option( 'ffl_api_key_option' ) );
		$gKey = esc_attr( get_option( 'ffl_api_gmaps_option' ) );
		$wMes = get_option( 'ffl_api_warning_message' ) != '' ? get_option( 'ffl_api_warning_message' ) : 'You have chosen your address as a shipping address for a firearm product. Unfortunately, we are not able to ship the firearms directly to your address. Please select the closest FFL from the map using your zip code.';
		$hok  = get_option( 'ffl_init_map_location' );

		echo '
<script type="text/javascript">
    
  var aKey = "' . $aKey . '"
    var gKey = "' . $gKey . '"
    var wMes = "' . $wMes . '"
    var hok = "' . $hok . '"

</script>';


		?>

        <script>

            window.addEventListener('load', function () {
                (function ($) {

                    $(document.body).on('updated_checkout', function () {

                        add_hide_animate();
                    });


                    function add_hide_animate() {

                        var addresses = ['billing', 'shipping'];
                        for (var i in addresses) {
                            var key = addresses[i];


                            $(".wfacp_divider_" + key + " .form-row").each(function () {
                                let field_id = $(this).attr("id");
                                if (field_id != '') {
                                    let field_val_id1 = field_id.replace('_field', '');
                                    let field_val = $('#' + field_val_id1).val();
                                    if (field_val != '' && field_val != null && !$(this).hasClass('wfacp-anim-wrap')) {

                                        $(this).addClass("wfacp-anim-wrap");
                                    }
                                }
                            });
                        }


                    }

                    localStorage.removeItem("selectedFFL");

                    if (!document.getElementById("ffl-zip-code")) {
                        initFFLJs(aKey, gKey, wMes, hok);
                    }
                    jQuery("#shipping_country")?.val('US').trigger('change');

                })(jQuery);
            });
        </script>

        <style>
            #ffl_container .columns {
                display: block;
                margin-top: 15px;
            }

            #ffl_container .columns .column {
                display: inline-block;
                float: left;
                width: 31.33%;
                margin: 0;
                margin-right: 2%;
            }

            #ffl_container .columns .column:nth-child(2n) {
            }

            #ffl_container .columns .column:last-child {
                margin: 0;
                position: relative;
                margin-top: 32px;
            }

            #ffl_container .columns .column #ffl-search {
                border: 1px solid #d9d9d9;
            }

            #ffl_container .columns .column select {
                padding-top: 14px;
                padding-bottom: 14px;
                margin-bottom: 0;
                -webkit-appearance: menulist;
                -moz-appearance: menulist;
            }

            #ffl_container .columns:after, #ffl_container .columns:before {
                content: '';
                display: block;
            }

            #ffl_container .columns:after {
                clear: both;
            }
        </style>
		<?php
	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_FFI_API(), 'wfacp-ffi-api' );
