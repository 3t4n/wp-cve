<?php
/**
 * Email Customizer for WooCommerce Builder Functions
 *
 * @author    ThemeHiGH
 * @category  Admin
 */

if(!defined('ABSPATH')){ exit; }

if(!class_exists('WECMF_Builder_Settings')):
class WECMF_Builder_Settings {
	protected static $_instance = null;

	public function __construct() {
	}

	public static function instance() {
		if(is_null(self::$_instance)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}	

	/**
     * Render the builder main page
     *
     */
	public function render_template_builder(){
		?>
		<div id="thwecmf_wrapper" class="thwecmf-tbuilder-wrapper">
            <div id="thwecmf_ajax_load_modal"></div>
            <?php $this->render_builder(); ?>
        </div>
        <?php
	}

	/**
	* Render the email builder
	*
	*/
    private function render_builder(){
        $this->render_template_builder_css_section('thwecmf_template_css');
        ?>
        <div id="render_builder"></div>
        <?php
    }

	 /**
	 * Render builder element styles
	 *
	 * @param array $wrapper_id stylesheet id
	 */
	private function render_template_builder_css_section($wrapper_id) {
		?>

		<style id="<?php echo esc_attr( $wrapper_id ); ?>_layouts" type="text/css">
			.thwecmf-block-one-column > tbody > tr > td{
				width: 100%;				
			}
			.thwecmf-block-two-column > tbody > tr > td{
				width: 50%;				
			}

			.thwecmf-block-three-column >tbody > tr > td{
                width: 33%;             
            }

            .thwecmf-block-four-column >tbody > tr > td{
                width: 25%;             
            }
		</style>

		<style id="<?php echo esc_attr( $wrapper_id ); ?>" type="text/css">
			.thwecmf-main-builder{
				max-width:600px;
				width:600px;
				margin: auto; 
			}

			.thwecmf-block a{
                color: <?php echo esc_html( WECMF_Utils::get_template_global_css('link-color') ); ?>;
                text-decoration: <?php echo esc_html( WECMF_Utils::get_template_global_css('link-decoration') ); ?>;
            }

            <?php if( apply_filters( 'thwec_enable_global_link_color', true ) ){ ?>
                #tb_t_builder.thwecmf-main-builder  a.thwecmf-link,
                #tb_t_builder.thwecmf-main-builder  .thwecmf-block-text a,
                #tb_t_builder.thwecmf-main-builder .thwecmf-block-billing a,
                #tb_t_builder.thwecmf-main-builder .thwecmf-block-shipping a,
                #tb_t_builder.thwecmf-main-builder .thwecmf-block-customer a{
                    color: <?php echo esc_html( WECMF_Utils::get_template_global_css('link-color') ); ?>;
                    text-decoration: <?php echo esc_html( WECMF_Utils::get_template_global_css('link-decoration') ); ?>;
                }
            <?php } ?>

            #tb_t_builder .thwecmf-block-text b{
				font-weight: revert;
			}

			.thwecmf-main-builder .thwecmf-builder-column{
				background-color:#ffffff;
				vertical-align: top;
				border-top-width: 0px;
				border-right-width: 0px;
				border-bottom-width: 0px;
				border-left-width: 0px;
				border-style: none;
				border-color: transparent;
				border-radius: 2px;
				background-size: cover;
				background-repeat: no-repeat;
				background-position: top;
			}
			.thwecmf_wrapper{
				background-color: #f7f7f7;
				margin: 0; 
				width: 100%;
			}
			.thwecmf-row{
				border-spacing: 0px;
			}

			.thwecmf-row,
			.thwecmf-block{
				width:100%;
				table-layout: fixed;
			}
			.thwecmf-block td{
				padding: 0;
			}
			.thwecmf-layout-block{
				overflow: hidden;
			}
			.thwecmf-row td{
				vertical-align: top;
				box-sizing: border-box;
			}
			.thwecmf-block-one-column,
			.thwecmf-block-two-column,
			.thwecmf-block-three-column,
			.thwecmf-block-four-column{
				max-width: 100%;
                margin: 0 auto;
                margin-top: 0px;
                margin-right: auto;
                margin-bottom: 0px;
                margin-left: auto;
                background-size: 100%;
                background-repeat: no-repeat;
                background-position: top;
                border-top-width: 1px;
                border-right-width: 1px;
                border-bottom-width: 1px;
                border-left-width: 1px;
                border-style: dotted;
                border-color: #dddddd;
                padding-top: 12px;
                padding-right: 10px;
                padding-bottom: 12px;
                padding-left: 10px;
			}

			.thwecmf-row .thwecmf-columns{
				border-top-width: 1px;
				border-right-width: 1px;
				border-bottom-width: 1px;
				border-left-width: 1px;
				border-style: dotted;
				border-color: #dddddd;
				word-break: break-word;
				padding: 10px 10px;
				text-align: center;
				background-size: 100%;
				background-position: top;
			}

			.thwecmf-block-divider{
				margin: 0;
			}

			.thwecmf-block-divider td{
				padding: 20px 0px;
				text-align: center;
			}

			.thwecmf-block-divider hr{
				display: inline-block;
				border:none;
				border-top: 2px solid transparent;
				border-color: gray;
				width:70%;
				height: 2px;
				margin: 0 auto;
			}

			.thwecmf-block-text{
				width: 100%;
				color: #636363;
				font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;
				font-size: 13px;
				line-height: 22px;
				text-align:center;
				margin: 0 auto;
				box-sizing: border-box;
			}

			.thwecmf-block-text .thwecmf-block-text-holder{
				color: #636363;
				font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;
				font-size: 13px;
				line-height: 150%;
				text-align: center;
				padding: 15px 15px;
				border-top-width: 0px;
				border-right-width: 0px;
				border-bottom-width: 0px;
				border-left-width: 0px;
				border-color: transparent;
				border-style: none;
				background-size: cover;
				background-repeat: no-repeat;
				background-color: transparent;
			}

			.thwecmf-block-image{
				width: auto;
				height: auto;
				max-width: 600px;
				box-sizing: border-box;
				width: 100%;
			}

			.thwecmf-block-image td.thwecmf-image-column{
				text-align: center;
				vertical-align: middle;
			}

			.thwecmf-block-image p{
				padding: 10px 0px;
                margin: 0;
                width: 50%;
                display: inline-block;
                border-top-width: 0px;
                border-right-width: 0px;
                border-bottom-width: 0px;
                border-left-width: 0px;
                border-style: none;
                border-color: transparent;
			}

			.thwecmf-block-image img{
				/*width:100%;
				height:auto;*/
				max-width: 100%;
				max-height: 100%;
				display:block;
				/* Following styles are added now */
				width: 100%;
				height: 100%;
			}

			 .thwecmf-block-header{
                overflow: hidden;
                text-align: center;
                box-sizing: border-box;
                position: relative;
                width:100%;
                height: auto;
                margin:0 auto;
                max-width: 100%;
                background-size: 100%;
                background-repeat: no-repeat;
                background-position: center;
                background-color:#0099ff;
                border-top-width: 0px;
                border-right-width: 0px;
                border-bottom-width: 0px;
                border-left-width: 0px;
                border-style: none;
                border-color: transparent;
            }
            
            .thwecmf-block-header .thwecmf-header-logo{
                text-align: center;
                font-size: 0;
                line-height: 1;
                padding: 15px 5px 15px 5px;
            }
            
            .thwecmf-block-header .thwecmf-header-logo-ph{
                width:155px;
                height: 103px;
                margin:0 auto;
                border-top-width: 0px;
                border-right-width: 0px;
                border-bottom-width: 0px;
                border-left-width: 0px;
                border-style: none;
                border-color: transparent;
                display: inline-block;
            }

            .thwecmf-block-header .thwecmf-header-logo-ph img{
                width:100%;
                height:100%;
                display: block;
                max-width: 100%;
                max-height: 100%;
            }

            .thwecmf-block-header .thwecmf-header-text{
                padding: 30px 0px 30px 0px; 
                font-size: 0;
            }

            .thwecmf-block-header .thwecmf-header-text h1{
                margin:0 auto;
                width: 100%;
                max-width: 100%;
                color:#ffffff;
                font-size:40px;
                font-weight:300;
                mso-line-height-rule: exactly;
                line-height:100%;
                vertical-align: middle;
                text-align:center;
                font-family: Georgia, serif;
                border:1px solid transparent;
                box-sizing: border-box; 
            }

            .thwecmf-block-header .thwecmf-header-text h3{
                padding:0px;
                margin:0;
                color:#ffffff;
                font-size:22px;
                font-weight:300;
                text-align:center;
                font-family: 'Times New Roman', Times, serif;
                line-height:150%;    
            }

            .thwecmf-block-header .thwecmf-header-text p{
                margin:0 auto;
                width: 100%;
                max-width: 100%;
                color:#ffffff;
                font-size:40px;
                font-weight:300;
                mso-line-height-rule: exactly;
                line-height:150%;
                text-align:center;
                font-family: Georgia, serif;
                border:1px solid transparent;
                box-sizing: border-box; 
            }

			.thwecmf-block-shipping .thwecmf-shipping-padding,
			.thwecmf-block-billing .thwecmf-billing-padding,
			.thwecmf-block-customer .thwecmf-customer-padding{
				padding-top: 5px;
				padding-right: 0px;
				padding-bottom: 2px;
				padding-left: 0px;
			}

			.thwecmf-block-billing,
			.thwecmf-block-shipping,
			.thwecmf-block-customer,
			.thwecmf-block-shipping .thwecmf-address-alignment,
			.thwecmf-block-billing .thwecmf-address-alignment,
			.thwecmf-block-customer .thwecmf-address-customer{
				margin: 0;
				padding:0;
				border: 0px none transparent;
				border-collapse: collapse;
				box-sizing: border-box;
			}

			.thwecmf-block-billing .thwecmf-address-wrapper-table,
			.thwecmf-block-shipping .thwecmf-address-wrapper-table,
			.thwecmf-block-customer .thwecmf-address-wrapper-table{
				width:100%;
				height: 115px;
				border-top-width: 0px;
				border-right-width: 0px;
				border-bottom-width: 0px;
				border-left-width: 0px;
				border-style: none;
				border-color: transparent;
                background-repeat: no-repeat;
                background-size: cover;
                background-color: transparent;
                background-position: top;
			}

			.thwecmf-block-billing .thwecmf-billing-header,
			.thwecmf-block-shipping .thwecmf-shipping-header,
			.thwecmf-block-customer .thwecmf-customer-header {
				color:#0099ff;
				display:block;
				font-family:"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;
				font-size:18px;
				font-weight:bold;
				line-height:150%;
				text-align:center;
				margin: 0px;
			}

			.thwecmf-block-billing .thwecmf-billing-body,
			.thwecmf-block-shipping .thwecmf-shipping-body,
			.thwecmf-block-customer .thwecmf-customer-body {
				text-align:center;
				line-height:150%;
				border:0px !important;
				font-family: 'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;
				font-size: 13px;
				padding: 0px 0px 0px 0px;
				margin: 13px 0px;
				color: #444444;
			}

			.thwecmf-block-gap{
				height:48px;
				margin: 0;
				box-sizing: border-box;
				border-top-width: 0px;
				border-right-width: 0px;
				border-bottom-width: 0px;
				border-left-width: 0px;
				border-style: none;
				border-color: transparent;
				background-size: cover;
                background-color: transparent;
                background-repeat: no-repeat;
                background-position: center;
			}

			 .thwecmf-block-social{
                text-align: center;
                width:100%;
                box-sizing: border-box;
                background-size: cover;
                background-repeat: no-repeat;
                background-color: transparent;
                margin: 0 auto;
                border-top-width: 0px;
                border-right-width: 0px;
                border-bottom-width: 0px;
                border-left-width: 0px;
                border-style: none;
                border-color: transparent;
            }

            .thwecmf-block-social .thwecmf-social-outer-td{
                padding-top: 0px;
                padding-right: 0px;
                padding-bottom: 0px;
                padding-left: 0px;
            }

            .thwecmf-block-social .thwecmf-social-td{
                padding: 15px 3px 15px 3px;
                font-size: 0;
                line-height: 1px;
            }

            .thwecmf-block-social .thwecmf-social-icon{
                width: 40px;
                height: 40px;
                margin: 0px;
                text-decoration:none;
                box-shadow:none;
            }
    
            .thwecmf-block-social .thwecmf-social-icon img {
                width: 100%;
                height: 100%;
                display:block;
                max-width: 100%;
                max-height: 100%;
            }

            .thwecmf-button-wrapper-table{
                width: 80px;
                margin: 0 auto;
                padding-top: 10px;
                padding-right: 0px;
                padding-bottom: 10px;
                padding-left: 0px;
            }

            .thwecmf-button-wrapper-table td{
                border-radius: 2px;
                background-color: #4169e1;
                text-align: center;
                padding: 10px 0px;
                border-top-width: 1px;
                border-right-width: 1px;
                border-bottom-width: 1px;
                border-left-width: 1px;
                border-style: solid;
                border-color: #4169e1;
                text-decoration: none;
                color: #fff;
                font-size: 13px;
            }

            .thwecmf-button-wrapper-table td a.thwecmf-button-link{
                color: #fff;
                line-height: 150%;
                font-size: 13px;
                text-decoration: none;
                font-family: 'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;
            }

            .thwecmf-block-gif{
                margin: 0;
                width: 100%;
                height: auto;
                max-width: 600px;
                box-sizing: border-box;
            }

            .thwecmf-block-gif td.thwecmf-gif-column{
                text-align: center;
            }

            .thwecmf-block-gif td.thwecmf-gif-column p{
                margin: 0;
                width: 50%;
                padding: 10px 10px;
                display: inline-block;
                vertical-align: top;
                border-top-width: 0px;
                border-right-width: 0px;
                border-bottom-width: 0px;
                border-left-width: 0px;
                border-style: none;
                border-color: transparent;
            }

            .thwecmf-block-gif td.thwecmf-gif-column img {
                width:100%;
                height:auto;
                display:block;
            }

             .thwecmf-block-order{
                background-color: white;
                margin: 0 auto;
                position: relative;
                background-size: cover;
                background-repeat: no-repeat;
                background-position: top;
                border-top-width: 0px;
                border-right-width: 0px;
                border-bottom-width: 0px;
                border-left-width: 0px;
                border-color: transparent;
                border-style: none;
            }

            .thwecmf-block-order td{
                word-break: unset;
            }

            .thwecmf-block-order .thwecmf-order-padding {
                padding:20px 48px;
            }

            .thwecmf-block-order .thwecmf-order-heading {
                font-size:18px;
                text-align:left;
                line-height:100%;
                color: #4286f4;
                font-family: 'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;
            }

            .thwecmf-block-order .thwecmf-order-table {
                table-layout: fixed;
                width:100%;
                font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;
                color: #636363;
                border: 1px solid #e5e5e5;
                border-collapse:collapse;
            }
            .thwecmf-block-order .thwecmf-td {
                color: #636363;
                border: 1px solid #e5e5e5;
                padding:12px;
                text-align: left;
                font-size: 14px;
                line-height: 150%;
            }

            <?php if( apply_filters( 'thwec_order_table_column_auto_width', true ) ) : ?>
                .thwecmf-order-table td,
                .thwecmf-order-table th{
                    word-break: keep-all;
                }

                .thwecmf-block-order .thwecmf-order-table {
                    table-layout: auto;
                }
            <?php endif; ?>

            .thwecmf-block-order .thwecmf-order-item-img{
                margin-bottom: 5px;
            }
            .thwecmf-block-order .thwecmf-order-item-img img{
                width: 32px;
                height: 32px;
                display: inline;
                height: auto;
                outline: none;
                line-height: 100%;
                vertical-align: middle;
                margin-right: 10px;
                text-decoration: none;
                text-transform: capitalize;
            }

            .thwecmf_downloadable_table td,
            .thwecmf_downloadable_table th{
            	font-size: 14px;
            }

            .thwecmf-block-one-column .thwecmf-block-image.thwecmf-default-placeholder p,
            .thwecmf-block-one-column .thwecmf-block-gif.thwecmf-default-placeholder p{
                width: 10% !important;
            }

            .thwecmf-block-two-column .thwecmf-block-image.thwecmf-default-placeholder p,
            .thwecmf-block-two-column .thwecmf-block-gif.thwecmf-default-placeholder p{
                width: 21% !important;
            }

            .thwecmf-block-three-column .thwecmf-block-image.thwecmf-default-placeholder p,
            .thwecmf-block-three-column .thwecmf-block-gif.thwecmf-default-placeholder p{
                width: 32% !important;
            }

            .thwecmf-block-four-column .thwecmf-block-image.thwecmf-default-placeholder p,
            .thwecmf-block-four-column .thwecmf-block-gif.thwecmf-default-placeholder p{
                width: 45% !important;
            }

            .thwec-short-description{
                margin-top: 10px;
                font-size: 12px;
            }
		</style>
		<?php
	}
}
endif;