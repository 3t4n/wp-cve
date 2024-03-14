<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Traits for simple products
 *
 * @author		Maxim Glazunov
 * @link			https://icopydoc.ru/
 * @since		1.0.0
 *
 * @return 		$result_xml (string)
 *
 * @depends		class:	XFGMC_Get_Paired_Tag
 *				methods: add_skip_reason
 *				functions: 
 */


trait XFGMC_Trait_Simple_Get_Id {
	public function get_id( $tag_name = 'g:id', $result_xml = '' ) {
		$result_xml_id = $this->get_product()->get_id();
		$xfgmc_instead_of_id = xfgmc_optionGET( 'xfgmc_instead_of_id', $this->get_feed_id(), 'set_arr' );
		if ( $xfgmc_instead_of_id === 'sku' ) {
			$sku_xml = $this->get_product()->get_sku();
			if ( ! empty( $sku_xml ) ) {
				$result_xml_id = htmlspecialchars( $sku_xml );
			}
		}

		$result_xml_id = apply_filters(
			'xfgmc_simple_result_xml_id',
			$result_xml_id,
			$this->get_product(),
			$xfgmc_instead_of_id,
			$this->get_feed_id()
		);

		$result_xml = new XFGMC_Get_Paired_Tag( $tag_name, $result_xml_id );
		return $result_xml;
	}
}

trait XFGMC_Trait_Simple_Get_Name {
	public function get_name( $tag_name = 'g:title', $result_xml = '' ) {
		$result_xml_name = htmlspecialchars( $this->get_product()->get_title(), ENT_NOQUOTES ); // название товара
		$result_xml_name = apply_filters(
			'xfgmc_change_name',
			$result_xml_name,
			$this->get_product()->get_id(),
			$this->get_product(),
			$this->get_feed_id()
		);

		$result_xml = new XFGMC_Get_Paired_Tag( $tag_name, $result_xml_name );
		return $result_xml;
	}
}

trait XFGMC_Trait_Simple_Get_Description {
	public function get_description( $tag_name = 'g:description', $result_xml = '' ) {
		// описание
		$xfgmc_desc = xfgmc_optionGET( 'xfgmc_desc', $this->get_feed_id(), 'set_arr' );
		$xfgmc_the_content = xfgmc_optionGET( 'xfgmc_the_content', $this->get_feed_id(), 'set_arr' );

		switch ( $xfgmc_desc ) {
			case "full":
				$tag_value = $this->get_product()->get_description();
				break;
			case "excerpt":
				$tag_value = $this->get_product()->get_short_description();
				break;
			case "fullexcerpt":
				$tag_value = $this->get_product()->get_description();
				if ( empty( $tag_value ) ) {
					$tag_value = $this->get_product()->get_short_description();
				}
				break;
			case "excerptfull":
				$tag_value = $this->get_product()->get_short_description();
				if ( empty( $tag_value ) ) {
					$tag_value = $this->get_product()->get_description();
				}
				break;
			case "fullplusexcerpt":
				$tag_value = sprintf( '%1$s<br/>%2$s',
					$this->get_product()->get_description(),
					$this->get_product()->get_short_description()
				);
				break;
			case "excerptplusfull":
				$tag_value = sprintf( '%1$s<br/>%2$s',
					$this->get_product()->get_short_description(),
					$this->get_product()->get_description()
				);
				break;
			default:
				$tag_value = $this->get_product()->get_description();
		}
		$xfgmc_adapt_facebook = xfgmc_optionGET( 'xfgmc_adapt_facebook', $this->get_feed_id(), 'set_arr' );

		$tag_value = apply_filters( 'xfgmc_description_xml_filter', $tag_value, $this->get_product()->get_id(), $this->get_product(), $this->get_feed_id() ); /* с версии 2.2.1 */
		if ( ! empty( $tag_value ) ) {
			$enable_tags = '<p>,<h2>,<h3>,<em>,<ul>,<li>,<ol>,<br/>,<br>,<strong>,<sub>,<sup>,<div>,<span>,<dl>,<dt>,<dd>';
			if ( $xfgmc_the_content === 'enabled' ) {
				$tag_value = html_entity_decode( apply_filters( 'the_content', $tag_value ) ); /* с версии 2.2.4 */
			}
			$enable_tags = apply_filters( 'xfgmc_enable_tags_filter', $enable_tags, $this->get_feed_id() ); /* с версии 2.0.7 */
			if ( $xfgmc_adapt_facebook === 'yes' ) {
				$enable_tags = '';
			} /* с версии 2.3.3 */
			$tag_value = strip_tags( $tag_value, $enable_tags );
			$tag_value = strip_shortcodes( $tag_value );
			$tag_value = xfgmc_max_lim_text( $tag_value, 5000 );
			$tag_value = apply_filters( 'xfgmc_description_filter', $tag_value, $this->get_product()->get_id(), $this->get_product(), $this->get_feed_id() );
			$tag_value = trim( $tag_value );
			if ( $tag_value !== '' ) {
				$tag_value = str_replace( ']]', '', $tag_value ); // Заменяем ]] чтобы не валилалась CDATA
				$result_xml = '<g:description><![CDATA[' . $tag_value . ']]></g:description>' . PHP_EOL;
			}
		}

		return $result_xml;
	}
}

trait XFGMC_Trait_Simple_Get_Google_Product_Category {
	public function get_google_product_category() {
		if ( get_post_meta( $this->get_product()->get_id(), 'xfgmc_google_product_category', true ) == '' ) {
			if ( get_term_meta( $this->get_cat_id(), 'xfgmc_google_product_category', true ) !== '' ) {
				$xfgmc_google_product_category = get_term_meta( $this->get_cat_id(), 'xfgmc_google_product_category', true );
				$xfgmc_google_product_category = htmlspecialchars( $xfgmc_google_product_category );
				$result_xml_category = new XFGMC_Get_Paired_Tag( 'g:google_product_category', $xfgmc_google_product_category );
			} else {
				$result_xml_category = '';
			}
		} else {
			$xfgmc_google_product_category = get_post_meta( $this->get_product()->get_id(), 'xfgmc_google_product_category', true );
			$xfgmc_google_product_category = htmlspecialchars( $xfgmc_google_product_category );
			$result_xml_category = new XFGMC_Get_Paired_Tag( 'g:google_product_category', $xfgmc_google_product_category );
		}

		$result_xml_category = apply_filters( 'xfgmc_xml_google_cat_simple_filter', $result_xml_category, $this->get_cat_id(), $this->get_input_data_arr() );

		return $result_xml_category;
	}
}

trait XFGMC_Trait_Simple_Get_Fb_Product_Category {
	public function get_fb_product_category() {
		if ( get_post_meta( $this->get_product()->get_id(), '_xfgmc_fb_product_category', true ) == '' ) {
			if ( get_term_meta( $this->get_cat_id(), 'xfgmc_fb_product_category', true ) !== '' ) {
				$xfgmc_fb_product_category = get_term_meta( $this->get_cat_id(), 'xfgmc_fb_product_category', true );
				$xfgmc_fb_product_category = htmlspecialchars( $xfgmc_fb_product_category );
				$result_xml_fb_product_category = new XFGMC_Get_Paired_Tag( 'g:fb_product_category', $xfgmc_fb_product_category );
			} else {
				$result_xml_fb_product_category = '';
			}
		} else {
			$xfgmc_fb_product_category = get_post_meta( $this->get_product()->get_id(), '_xfgmc_fb_product_category', true );
			$xfgmc_fb_product_category = htmlspecialchars( $xfgmc_fb_product_category );
			$result_xml_fb_product_category = new XFGMC_Get_Paired_Tag( 'g:fb_product_category', $xfgmc_fb_product_category );
		}

		$result_xml_fb_product_category = apply_filters( 'xfgmc_xml_facebook_cat_simple_filter', $result_xml_fb_product_category, $this->get_cat_id(), $this->get_input_data_arr() );

		return $result_xml_fb_product_category;
	}
}

trait XFGMC_Trait_Simple_Get_Tax_Category {
	public function get_tax_category() {
		if ( get_post_meta( $this->get_product()->get_id(), '_xfgmc_tax_category', true ) == '' ) {
			if ( get_term_meta( $this->get_cat_id(), 'xfgmc_tax_category', true ) !== '' ) {
				$xfgmc_tax_category = get_term_meta( $this->get_cat_id(), 'xfgmc_tax_category', true );
				$xfgmc_tax_category = htmlspecialchars( $xfgmc_tax_category );
				$result_xml_xfgmc_tax_category = new XFGMC_Get_Paired_Tag( 'g:tax_category', $xfgmc_tax_category );
			} else {
				$result_xml_xfgmc_tax_category = '';
			}
		} else {
			$xfgmc_tax_category = get_post_meta( $this->get_product()->get_id(), '_xfgmc_tax_category', true );
			$xfgmc_tax_category = htmlspecialchars( $xfgmc_tax_category );
			$result_xml_xfgmc_tax_category = new XFGMC_Get_Paired_Tag( 'g:tax_category', $xfgmc_tax_category );
		}

		$result_xml_xfgmc_tax_category = apply_filters( 'xfgmc_xml_google_cat_simple_filter', $result_xml_xfgmc_tax_category, $this->get_cat_id(), $this->get_input_data_arr() );

		return $result_xml_xfgmc_tax_category;
	}
}

trait XFGMC_Trait_Simple_Get_Product_Type {
	public function get_product_type() {
		$result_xml_product_type = '';

		$xfgmc_product_type = xfgmc_optionGET( 'xfgmc_product_type', $this->get_feed_id(), 'set_arr' );
		if ( $xfgmc_product_type === 'enabled' ) {
			$product_type_res = xfgmc_product_type( $this->get_cat_id(), $this->get_feed_id() );
			// $product_type_res = apply_filters('xfgmc_product_type_res_simple_filter', $product_type_res, $this->get_cat_id(), $this->get_input_data_arr());	

			$product_type_res = apply_filters( 'xfgmc_product_type_res_simple_filter', $product_type_res, $this->get_cat_id(), $this->get_product(), $this->get_feed_id() );

			if ( $product_type_res === '' ) {
			} else {
				$result_xml_product_type = new XFGMC_Get_Paired_Tag( 'g:product_type', htmlspecialchars( $product_type_res, ENT_NOQUOTES ) );
			}
		}

		return $result_xml_product_type;
	}
}
trait XFGMC_Trait_Simple_Get_Link {
	public function get_link() {
		$result_url = htmlspecialchars( get_permalink( $this->get_product()->get_id() ) ); // урл товара
		$result_url = apply_filters( 'xfgmc_url_filter_simple', $result_url, $this->get_product(), $this->get_cat_id(), $this->get_feed_id() ); /* с версии 2.0.5 */
		$result_xml_url = new XFGMC_Get_Paired_Tag( 'g:link', $result_url );

		return $result_xml_url;
	}
}
trait XFGMC_Trait_Simple_Get_Image_Link {
	public function get_image_link() {
		// убираем default.png из фида
		$no_default_png_products = xfgmc_optionGET( 'xfgmc_no_default_png_products', $this->get_feed_id(), 'set_arr' );
		if ( ( $no_default_png_products === 'on' ) && ( ! has_post_thumbnail( $this->get_product()->get_id() ) ) ) {
			$picture_xml = '';
		} else {
			$thumb_id = get_post_thumbnail_id( $this->get_product()->get_id() );
			$thumb_url = wp_get_attachment_image_src( $thumb_id, 'full', true );
			$thumb_xml = $thumb_url[0]; /* урл оригинал миниатюры товара */
			$picture_xml = new XFGMC_Get_Paired_Tag( 'g:image_link', xfgmc_deleteGET( $thumb_xml ) );
		}
		$picture_xml = apply_filters( 'xfgmc_pic_simple_offer_filter', $picture_xml, $this->get_product(), $this->get_feed_id() );

		return $picture_xml;
	}
}

trait XFGMC_Trait_Simple_Get_Quantity {
	public function get_quantity() {
		$result_xml_quantity = '';
		$xfgmc_g_stock = xfgmc_optionGET( 'xfgmc_g_stock', $this->get_feed_id(), 'set_arr' );
		if ( $xfgmc_g_stock === 'enabled' ) {
			if ( $this->get_product()->get_manage_stock() == true ) { // включено управление запасом  
				$stock_quantity = $this->get_product()->get_stock_quantity();

				$result_xml_quantity = new XFGMC_Get_Paired_Tag( 'g:quantity', $stock_quantity );
			}
		}

		return $result_xml_quantity;
	}
}

trait XFGMC_Trait_Simple_Get_Availability {
	public function get_availability() {
		$xfgmc_adapt_facebook = xfgmc_optionGET( 'xfgmc_adapt_facebook', $this->get_feed_id(), 'set_arr' );

		if ( $xfgmc_adapt_facebook === 'yes' ) {
			$in_stock = 'in stock';
			$out_of_stock = 'out of stock';
			$onbackorder = 'available for order';
		} else {
			$in_stock = 'in_stock';
			$out_of_stock = 'out_of_stock';
			$onbackorder = 'preorder';
		}

		if ( $this->get_product()->get_manage_stock() == true ) { // включено управление запасом
			if ( $this->get_product()->get_stock_quantity() > 0 ) {
				$available = $in_stock;
			} else {
				if ( $this->get_product()->get_backorders() === 'no' ) { // предзаказ запрещен
					$available = $out_of_stock;
				} else {
					$xfgmc_behavior_onbackorder = xfgmc_optionGET( 'xfgmc_behavior_onbackorder', $this->get_feed_id(), 'set_arr' );
					switch ( $xfgmc_behavior_onbackorder ) {
						case "out_of_stock":
							$available = $out_of_stock;
							break;
						case "in_stock":
							$available = $in_stock;
							break;
						case "onbackorder":
							$available = $onbackorder;
							break;
						default:
							$available = $onbackorder;
					}
				}
			}
		} else { // отключено управление запасом
			if ( $this->get_product()->get_stock_status() === 'instock' ) {
				$available = $in_stock;
			} else if ( $this->get_product()->get_stock_status() === 'outofstock' ) {
				$available = $out_of_stock;
			} else {
				$xfgmc_behavior_onbackorder = xfgmc_optionGET( 'xfgmc_behavior_onbackorder', $this->get_feed_id(), 'set_arr' );
				switch ( $xfgmc_behavior_onbackorder ) {
					case "out_of_stock":
						$available = $out_of_stock;
						break;
					case "in_stock":
						$available = $in_stock;
						break;
					case "onbackorder":
						$available = $onbackorder;
						break;
					default:
						$available = $onbackorder;
				}
			}
		}

		$result_xml_available = new XFGMC_Get_Paired_Tag( 'g:availability', $available );

		if (($xfgmc_adapt_facebook !== 'yes') && ($available == 'preorder')) {
			$result_xml_available .= $this->get_availability_date();
		}

		return $result_xml_available;
	}
}

trait XFGMC_Trait_Simple_Get_Adult {
	public function get_adult() {
		if ( ( get_post_meta( $this->get_product()->get_id(), 'xfgmc_adult', true ) !== '' ) && ( get_post_meta( $this->get_product()->get_id(), 'xfgmc_adult', true ) !== 'off' ) ) {
			$xfgmc_adult = get_post_meta( $this->get_product()->get_id(), 'xfgmc_adult', true );
			$result_xml_adult = new XFGMC_Get_Paired_Tag( 'g:adult', $xfgmc_adult );
		} else {
			$result_xml_adult = '';
		}

		return $result_xml_adult;
	}
}

trait XFGMC_Trait_Simple_Get_Is_Bundle {
	public function get_is_bundle() {
		if ( ( get_post_meta( $this->get_product()->get_id(), '_xfgmc_is_bundle', true ) === 'yes' ) || ( get_post_meta( $this->get_product()->get_id(), '_xfgmc_is_bundle', true ) === 'no' ) ) {
			$xfgmc_is_bundle = get_post_meta( $this->get_product()->get_id(), '_xfgmc_is_bundle', true );
			$result_xml_is_bundle = new XFGMC_Get_Paired_Tag( 'g:is_bundle', $xfgmc_is_bundle );
		} else {
			$result_xml_is_bundle = '';
		}

		return $result_xml_is_bundle;
	}
}

trait XFGMC_Trait_Simple_Get_Multipack {
	public function get_multipack() {
		if ( get_post_meta( $this->get_product()->get_id(), '_xfgmc_multipack', true ) !== '' ) {
			$xfgmc_multipack = get_post_meta( $this->get_product()->get_id(), '_xfgmc_multipack', true );
			$result_xml_multipack = new XFGMC_Get_Paired_Tag( 'g:multipack', $xfgmc_multipack );
		} else {
			$result_xml_multipack = '';
		}

		return $result_xml_multipack;
	}
}

trait XFGMC_Trait_Simple_Get_Condition {
	public function get_condition() {
		$xfgmc_condition = get_post_meta( $this->get_product()->get_id(), 'xfgmc_condition', true );
		switch ( $xfgmc_condition ) {
			case '':
				$xfgmc_default_condition = xfgmc_optionGET( 'xfgmc_default_condition', $this->get_feed_id(), 'set_arr' );
				$result_xml_condition = new XFGMC_Get_Paired_Tag( 'g:condition', $xfgmc_default_condition );
				break;
			case 'off':
				$result_xml_condition = '';
				break;
			case 'default':
				$xfgmc_default_condition = xfgmc_optionGET( 'xfgmc_default_condition', $this->get_feed_id(), 'set_arr' );
				$result_xml_condition = new XFGMC_Get_Paired_Tag( 'g:condition', $xfgmc_default_condition );
				break;
			default:
				$result_xml_condition = new XFGMC_Get_Paired_Tag( 'g:condition', $xfgmc_condition );
		}

		return $result_xml_condition;
	}
}

trait XFGMC_Trait_Simple_Get_Custom_Label {
	public function get_custom_label() {
		$result_custom_label = '';
		if ( get_post_meta( $this->get_product()->get_id(), 'xfgmc_custom_label_0', true ) !== '' ) {
			$xfgmc_custom_label_0 = get_post_meta( $this->get_product()->get_id(), 'xfgmc_custom_label_0', true );
			$result_custom_label .= '<g:custom_label_0>' . $xfgmc_custom_label_0 . '</g:custom_label_0>' . PHP_EOL;
		}
		if ( get_post_meta( $this->get_product()->get_id(), 'xfgmc_custom_label_1', true ) !== '' ) {
			$xfgmc_custom_label_1 = get_post_meta( $this->get_product()->get_id(), 'xfgmc_custom_label_1', true );
			$result_custom_label .= '<g:custom_label_1>' . $xfgmc_custom_label_1 . '</g:custom_label_1>' . PHP_EOL;
		}
		if ( get_post_meta( $this->get_product()->get_id(), 'xfgmc_custom_label_2', true ) !== '' ) {
			$xfgmc_custom_label_2 = get_post_meta( $this->get_product()->get_id(), 'xfgmc_custom_label_2', true );
			$result_custom_label .= '<g:custom_label_2>' . $xfgmc_custom_label_2 . '</g:custom_label_2>' . PHP_EOL;
		}
		if ( get_post_meta( $this->get_product()->get_id(), 'xfgmc_custom_label_3', true ) !== '' ) {
			$xfgmc_custom_label_3 = get_post_meta( $this->get_product()->get_id(), 'xfgmc_custom_label_3', true );
			$result_custom_label .= '<g:custom_label_3>' . $xfgmc_custom_label_3 . '</g:custom_label_3>' . PHP_EOL;
		}
		if ( get_post_meta( $this->get_product()->get_id(), 'xfgmc_custom_label_4', true ) !== '' ) {
			$xfgmc_custom_label_4 = get_post_meta( $this->get_product()->get_id(), 'xfgmc_custom_label_4', true );
			$result_custom_label .= '<g:custom_label_4>' . $xfgmc_custom_label_4 . '</g:custom_label_4>' . PHP_EOL;
		}

		return $result_custom_label;
	}
}

trait XFGMC_Trait_Simple_Get_Price {
	public function get_price() {
		$result_xml = '';

		$price_xml = $this->get_product()->get_price();

		if ( $price_xml == 0 || empty( $price_xml ) ) {
			$this->add_skip_reason( array( 'reason' => __( 'Price not specified', 'xml-for-google-merchant-center' ), 'post_id' => $this->get_product()->get_id(), 'file' => 'traits-xfgmc-simple.php', 'line' => __LINE__ ) );
			return '';
		}

		if ( class_exists( 'XmlforGoogleMerchantCenterPro' ) ) {
			if ( ( xfgmc_optionGET( 'xfgmcp_compare_value', $this->get_feed_id(), 'set_arr' ) !== false ) && ( xfgmc_optionGET( 'xfgmcp_compare_value', $this->get_feed_id(), 'set_arr' ) !== '' ) ) {
				$xfgmcp_compare_value = xfgmc_optionGET( 'xfgmcp_compare_value', $this->get_feed_id(), 'set_arr' );
				$xfgmcp_compare = xfgmc_optionGET( 'xfgmcp_compare', $this->get_feed_id(), 'set_arr' );
				if ( $xfgmcp_compare == '>=' ) {
					if ( $price_xml < $xfgmcp_compare_value ) {
						$this->add_skip_reason( array( 'reason' => __( 'The product price', 'xml-for-google-merchant-center' ) . ' ' . $this->get_product()->get_price() . ': < ' . $xfgmcp_compare_value, 'post_id' => $this->get_product()->get_id(), 'file' => 'traits-xfgmc-simple.php', 'line' => __LINE__ ) );
						return '';
					}
				} else {
					if ( $price_xml >= $xfgmcp_compare_value ) {
						$this->add_skip_reason( array( 'reason' => __( 'The product price', 'xml-for-google-merchant-center' ) . ' ' . $this->get_product()->get_price() . ': >= ' . $xfgmcp_compare_value, 'post_id' => $this->get_product()->get_id(), 'file' => 'traits-xfgmc-simple.php', 'line' => __LINE__ ) );
						return '';
					}
				}
			}
		}

		$xfgmc_default_currency = xfgmc_optionGET( 'xfgmc_default_currency', $this->get_feed_id(), 'set_arr' );
		if ( $xfgmc_default_currency == '' ) {
			$currencyId_xml = get_woocommerce_currency();
		} else {
			$currencyId_xml = $xfgmc_default_currency;
		}
		$currencyId_xml = apply_filters( 'xfgmc_change_price_currency', $currencyId_xml, $this->get_product(), $this->get_feed_id() ); /* с версии 2.2.10 */

		$price_xml = apply_filters( 'xfgmc_simple_price_xml_filter', $price_xml, $this->get_product(), $this->get_feed_id() ); /* с версии 2.0.6 */
		$xfgmc_sale_price = xfgmc_optionGET( 'xfgmc_sale_price', $this->get_feed_id(), 'set_arr' );
		if ( $xfgmc_sale_price === 'yes' ) {
			$sale_price = (float) $this->get_product()->get_sale_price();
			$sale_price = apply_filters( 'xfgmc_simple_sale_price_xml_filter', $sale_price, $this->get_product(), $this->get_feed_id() ); /* с версии 2.3.1 */
			$price_xml = (float) $price_xml;
			if ( ( $sale_price > 0 ) && ( $price_xml === $sale_price ) ) {
				$sale_price_xml = $this->get_product()->get_regular_price();
				$result_xml .= '<g:price>' . $sale_price_xml . ' ' . $currencyId_xml . '</g:price>' . PHP_EOL;
				$result_xml .= '<g:sale_price>' . $price_xml . ' ' . $currencyId_xml . '</g:sale_price>' . PHP_EOL;

				$sales_price_from = $this->get_product()->get_date_on_sale_from();
				$sales_price_to = $this->get_product()->get_date_on_sale_to();
				if ( ! empty( $sales_price_from ) && ! empty( $sales_price_to ) ) {
					$sales_price_from = date( DATE_ISO8601, strtotime( $sales_price_from ) );
					$sales_price_to = date( DATE_ISO8601, strtotime( $sales_price_to ) );
					$result_xml .= '<g:sale_price_effective_date>' . $sales_price_from . '/' . $sales_price_to . '</g:sale_price_effective_date>' . PHP_EOL;
				}

				$result_xml = apply_filters( 'xfgmc_simple_sale_price_filter', $result_xml, $this->get_product(), $this->get_feed_id() ); /* с версии 2.0.6 */
			} else {
				$result_xml .= '<g:price>' . $price_xml . ' ' . $currencyId_xml . '</g:price>' . PHP_EOL;
			}
		} else {
			$result_xml .= '<g:price>' . $price_xml . ' ' . $currencyId_xml . '</g:price>' . PHP_EOL;
		}


		$this->feed_price = $price_xml;
		return $result_xml;
	}
}

trait XFGMC_Trait_Simple_Get_Pricing_Measure {
	public function get_unit_pricing_measure() {
		$result_xml_unit_pricing_measure = '';

		if ( get_post_meta( $this->get_product()->get_id(), '_xfgmc_unit_pricing_measure', true ) !== '' ) {
			$unit_pricing_measure_xml = get_post_meta( $this->get_product()->get_id(), '_xfgmc_unit_pricing_measure', true );
			$result_xml_unit_pricing_measure = new XFGMC_Get_Paired_Tag( 'g:unit_pricing_measure', $unit_pricing_measure_xml );
		}
		if ( class_exists( 'WooCommerce_Germanized' ) ) {
			if ( ! empty( wc_gzd_get_gzd_product( $this->get_product()->get_id() )->get_unit() ) ) {
				$unit_germanized = ' ' . wc_gzd_get_gzd_product( $this->get_product()->get_id() )->get_unit();
			}
			;

			if ( ! empty( wc_gzd_get_gzd_product( $this->get_product()->get_id() )->get_unit_product() ) ) {
				$result_xml_unit_pricing_measure = "<g:unit_pricing_measure>" . wc_gzd_get_gzd_product( $this->get_product()->get_id() )->get_unit_product() . $unit_germanized . "</g:unit_pricing_measure>" . PHP_EOL;
			}
			;
		}

		return $result_xml_unit_pricing_measure;
	}
}

trait XFGMC_Trait_Simple_Get_Base_Measure {
	public function get_unit_pricing_base_measure() {
		$result_xml_pricing_base_measure = '';

		if ( get_post_meta( $this->get_product()->get_id(), '_xfgmc_unit_pricing_base_measure', true ) !== '' ) {
			$unit_pricing_base_measure_xml = get_post_meta( $this->get_product()->get_id(), '_xfgmc_unit_pricing_base_measure', true );
			$result_xml_pricing_base_measure = new XFGMC_Get_Paired_Tag( 'g:unit_pricing_base_measure', $unit_pricing_base_measure_xml );
		}
		if ( class_exists( 'WooCommerce_Germanized' ) ) {
			if ( ! empty( wc_gzd_get_gzd_product( $this->get_product()->get_id() )->get_unit() ) ) {
				$unit_germanized = ' ' . wc_gzd_get_gzd_product( $this->get_product()->get_id() )->get_unit();
			}
			;

			if ( ! empty( wc_gzd_get_gzd_product( $this->get_product()->get_id() )->get_unit_base() ) ) {
				$result_xml_pricing_base_measure = "<g:unit_pricing_base_measure>" . wc_gzd_get_gzd_product( $this->get_product()->get_id() )->get_unit_base() . $unit_germanized . "</g:unit_pricing_base_measure>" . PHP_EOL;
			}
			;
		}

		return $result_xml_pricing_base_measure;
	}
}

trait XFGMC_Trait_Simple_Get_Return_Rule_Label {
	public function get_return_rule_label() {
		$result_return_rule_label = '';

		if ( get_post_meta( $this->get_product()->get_id(), '_xfgmc_return_rule_label', true ) !== '' ) {
			$xfgmc_return_rule_label = get_post_meta( $this->get_product()->get_id(), '_xfgmc_return_rule_label', true );
			$result_return_rule_label = '<g:return_rule_label>' . $xfgmc_return_rule_label . '</g:return_rule_label>' . PHP_EOL;
		} else {
			$xfgmc_s_return_rule_label = xfgmc_optionGET( 'xfgmc_s_return_rule_label', $this->get_feed_id(), 'set_arr' );
			switch ( $xfgmc_s_return_rule_label ) { /* disabled, default_value, post_meta */
				case "default_value":
					$xfgmc_return_rule_label = xfgmc_optionGET( 'xfgmc_def_return_rule_label', $this->get_feed_id(), 'set_arr' );
					if ( $xfgmc_return_rule_label === '' ) {
					} else {
						$result_return_rule_label = '<g:return_rule_label>' . $xfgmc_return_rule_label . '</g:return_rule_label>' . PHP_EOL;
					}
					break;
				case "post_meta":
					$xfgmc_return_rule_label = xfgmc_optionGET( 'xfgmc_def_return_rule_label', $this->get_feed_id(), 'set_arr' );
					$xfgmc_return_rule_label_id = trim( $xfgmc_return_rule_label );
					if ( get_post_meta( $this->get_product()->get_id(), $xfgmc_return_rule_label_id, true ) !== '' ) {
						$result_return_rule_label_xml = get_post_meta( $this->get_product()->get_id(), $xfgmc_return_rule_label_id, true );
						$result_return_rule_label = '<g:return_rule_label>' . $result_return_rule_label_xml . '</g:return_rule_label>' . PHP_EOL;
					}
					break;
			}
		}

		return $result_return_rule_label;
	}
}

trait XFGMC_Trait_Simple_Get_Store_Code {
	public function get_store_code() {
		$result_xml_store_code = '';

		if ( get_post_meta( $this->get_product()->get_id(), '_xfgmc_store_code', true ) !== '' ) {
			$xfgmc_store_code = get_post_meta( $this->get_product()->get_id(), '_xfgmc_store_code', true );
			$result_xml_store_code = new XFGMC_Get_Paired_Tag( 'g:store_code', $xfgmc_store_code );
		} else {
			$xfgmc_store_code = xfgmc_optionGET( 'xfgmc_def_store_code', $this->get_feed_id(), 'set_arr' );
			if ( $xfgmc_store_code === '' ) {
			} else {
				$result_xml_store_code = new XFGMC_Get_Paired_Tag( 'g:store_code', $xfgmc_store_code );
			}
		}

		return $result_xml_store_code;
	}
}

trait XFGMC_Trait_Simple_Get_Identifier_Exists {
	/**
	 * Summary of get_identifier_exists
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string
	 */
	public function get_identifier_exists( $tag_name = 'g:identifier_exists', $result_xml = '' ) {
		$intermediate_result_xml = '';
		if ( ( ! empty( get_post_meta( $this->get_product()->get_id(), 'xfgmc_identifier_exists', true ) ) )
			&& ( get_post_meta( $this->get_product()->get_id(), 'xfgmc_identifier_exists', true ) !== 'off' )
			&& ( get_post_meta( $this->get_product()->get_id(), 'xfgmc_identifier_exists', true ) !== 'disabled' )
		) {
			$identifier_exists_val = get_post_meta( $this->get_product()->get_id(), 'xfgmc_identifier_exists', true );

			if ( $identifier_exists_val === 'no' ) {
				$intermediate_result_xml .= new XFGMC_Get_Paired_Tag( $tag_name, $identifier_exists_val );
				$intermediate_result_xml .= "<g:gtin></g:gtin>" . PHP_EOL;
				$intermediate_result_xml .= "<g:mpn></g:mpn>" . PHP_EOL;
			}

			if ( $identifier_exists_val === 'default' ) {
				$gtin = $this->get_gtin();
				$mpn = $this->get_mpn();
				if ( empty( $gtin ) && empty( $mpn ) ) {
					$intermediate_result_xml .= new XFGMC_Get_Paired_Tag( $tag_name, 'no' );
				} else {
					$intermediate_result_xml .= new XFGMC_Get_Paired_Tag( $tag_name, 'yes' );
					$intermediate_result_xml .= $gtin;
					$intermediate_result_xml .= $mpn;
				}
			} else {
				$intermediate_result_xml .= new XFGMC_Get_Paired_Tag( $tag_name, $identifier_exists_val );
			}
		} else if ( empty( get_post_meta( $this->get_product()->get_id(), 'xfgmc_identifier_exists', true ) ) ) {
			// условие сработает в тогда, когда тупо нет метаполя xfgmc_identifier_exists
			$gtin = $this->get_gtin();
			$mpn = $this->get_mpn();
			if ( empty( $gtin ) && empty( $mpn ) ) {
				$intermediate_result_xml .= new XFGMC_Get_Paired_Tag( $tag_name, 'no' );
			} else {
				$intermediate_result_xml .= new XFGMC_Get_Paired_Tag( $tag_name, 'yes' );
				$intermediate_result_xml .= $gtin;
				$intermediate_result_xml .= $mpn;
			}
		}

		$result_xml = apply_filters(
			'x4gmc_f_simple_tag_identifier_exists',
			$intermediate_result_xml,
			[ 
				'product' => $this->get_product(),
				'result_xml' => $result_xml
			],
			$this->get_feed_id()
		);
		return $result_xml;
	}
}

trait XFGMC_Trait_Simple_Get_Gtin {
	public function get_gtin( $tag_name = 'g:gtin', $result_xml = '' ) {
		$xfgmc_identifier_exists = get_post_meta( $this->get_product()->get_id(), 'xfgmc_identifier_exists', true );

		$gtin = xfgmc_optionGET( 'xfgmc_gtin', $this->get_feed_id(), 'set_arr' );
		switch ( $gtin ) { /* disabled, sku, post_meta или id */
			case "disabled":
				// выгружать штрихкод нет нужды
				break;
			case "no":
				if ( $xfgmc_identifier_exists !== 'no' ) {
					$result_xml .= "<g:gtin></g:gtin>" . PHP_EOL;
				}
				break;
			case "sku":
				// выгружать из артикула
				$sku_xml = $this->get_product()->get_sku();
				if ( ! empty( $sku_xml ) ) {
					$result_xml .= "<g:gtin>" . $sku_xml . "</g:gtin>" . PHP_EOL;
				}
				break;
			case "post_meta":
				$gtin_post_meta_id = xfgmc_optionGET( 'xfgmc_gtin_post_meta', $this->get_feed_id(), 'set_arr' );
				$gtin_post_meta_id = trim( $gtin_post_meta_id );
				if ( get_post_meta( $this->get_product()->get_id(), $gtin_post_meta_id, true ) !== '' ) {
					$gtin_xml = get_post_meta( $this->get_product()->get_id(), $gtin_post_meta_id, true );
					$result_xml .= "<g:gtin>" . $gtin_xml . "</g:gtin>" . PHP_EOL;
				}
				break;
			case "germanized":
				if ( class_exists( 'WooCommerce_Germanized' ) ) {
					if ( get_post_meta( $this->get_product()->get_id(), '_ts_gtin', true ) !== '' ) {
						$gtin_xml = get_post_meta( $this->get_product()->get_id(), '_ts_gtin', true );
						$result_xml .= "<g:gtin>" . $gtin_xml . "</g:gtin>" . PHP_EOL;
					}
				}
				break;
			default:
				$gtin = (int) $gtin;
				$xfgmc_gtin_xml = $this->get_product()->get_attribute( wc_attribute_taxonomy_name_by_id( $gtin ) );
				if ( ! empty( $xfgmc_gtin_xml ) ) {
					$result_xml .= '<g:gtin>' . xfgmc_replace_decode( $xfgmc_gtin_xml ) . '</g:gtin>' . PHP_EOL;
				}
		}

		return $result_xml;
	}
}

trait XFGMC_Trait_Simple_Get_Mpn {
	public function get_mpn( $tag_name = 'g:mpn', $result_xml = '' ) {
		$xfgmc_identifier_exists = get_post_meta( $this->get_product()->get_id(), 'xfgmc_identifier_exists', true );

		$mpn = xfgmc_optionGET( 'xfgmc_mpn', $this->get_feed_id(), 'set_arr' );
		switch ( $mpn ) { /* disabled, sku, post_meta или id */
			case "disabled":
				// выгружать штрихкод нет нужды
				break;
			case "no":
				if ( $xfgmc_identifier_exists !== 'no' ) {
					$result_xml .= "<g:mpn></g:mpn>" . PHP_EOL;
				}
				break;
			case "sku":
				// выгружать из артикула
				$sku_xml = $this->get_product()->get_sku();
				if ( ! empty( $sku_xml ) ) {
					$result_xml .= "<g:mpn>" . htmlspecialchars( $sku_xml ) . "</g:mpn>" . PHP_EOL;
				}
				break;
			case "post_meta":
				$mpn_post_meta_id = xfgmc_optionGET( 'xfgmc_mpn_post_meta', $this->get_feed_id(), 'set_arr' );
				$mpn_post_meta_id = trim( $mpn_post_meta_id );
				if ( get_post_meta( $this->get_product()->get_id(), $mpn_post_meta_id, true ) !== '' ) {
					$mpn_xml = get_post_meta( $this->get_product()->get_id(), $mpn_post_meta_id, true );
					$result_xml .= "<g:mpn>" . htmlspecialchars( $mpn_xml ) . "</g:mpn>" . PHP_EOL;
				}
				break;
			case "germanized":
				if ( class_exists( 'WooCommerce_Germanized' ) ) {
					if ( get_post_meta( $this->get_product()->get_id(), '_ts_mpn', true ) !== '' ) {
						$mpn_xml = get_post_meta( $this->get_product()->get_id(), '_ts_mpn', true );
						$result_xml .= "<g:mpn>" . htmlspecialchars( $mpn_xml ) . "</g:mpn>" . PHP_EOL;
					}
				}
				break;
			default:
				$mpn = (int) $mpn;
				$xfgmc_mpn_xml = $this->get_product()->get_attribute( wc_attribute_taxonomy_name_by_id( $mpn ) );
				if ( ! empty( $xfgmc_mpn_xml ) ) {
					$result_xml .= '<g:mpn>' . xfgmc_replace_decode( $xfgmc_mpn_xml ) . '</g:mpn>' . PHP_EOL;
				}
		}

		return $result_xml;
	}
}

trait XFGMC_Trait_Simple_Get_Age_Group {
	public function get_age_group() {

		$result_xml = '';

		$age = xfgmc_optionGET( 'xfgmc_age', $this->get_feed_id(), 'set_arr' );
		switch ( $age ) { /* disabled, sku, post_meta или id */
			case "disabled":
				// выгружать штрихкод нет нужды
				break;
			case "default_value":
				$xfgmc_age_group_post_meta = xfgmc_optionGET( 'xfgmc_age_group_post_meta', $this->get_feed_id(), 'set_arr' );
				$result_xml .= "<g:age_group>" . $xfgmc_age_group_post_meta . "</g:age_group>" . PHP_EOL;
				break;
			case "post_meta":
				$age_post_meta_id = xfgmc_optionGET( 'xfgmc_age_group_post_meta', $this->get_feed_id(), 'set_arr' );
				$age_post_meta_id = trim( $age_post_meta_id );
				if ( get_post_meta( $this->get_product()->get_id(), $age_post_meta_id, true ) !== '' ) {
					$age_xml = get_post_meta( $this->get_product()->get_id(), $age_post_meta_id, true );
					$result_xml .= "<g:age_group>" . $age_xml . "</g:age_group>" . PHP_EOL;
				}
				break;
			default:
				$age = (int) $age;
				$age_xml = $this->get_product()->get_attribute( wc_attribute_taxonomy_name_by_id( $age ) );
				if ( ! empty( $age_xml ) ) {
					$result_xml .= "<g:age_group>" . ucfirst( xfgmc_replace_decode( $age_xml ) ) . "</g:age_group>" . PHP_EOL;
				}
		}

		return $result_xml;
	}
}

trait XFGMC_Trait_Simple_Get_Brand {
	public function get_brand() {
		$result_xml = '';

		$brand = xfgmc_optionGET( 'xfgmc_brand', $this->get_feed_id(), 'set_arr' );
		if ( ! empty( $brand ) && $brand !== 'off' ) {
			if ( ( is_plugin_active( 'perfect-woocommerce-brands/perfect-woocommerce-brands.php' ) || is_plugin_active( 'perfect-woocommerce-brands/main.php' ) || class_exists( 'Perfect_Woocommerce_Brands' ) ) && $brand === 'sfpwb' ) {
				$barnd_terms = get_the_terms( $this->get_product()->get_id(), 'pwb-brand' );
				if ( $barnd_terms !== false ) {
					foreach ( $barnd_terms as $barnd_term ) {
						$result_xml .= '<g:brand>' . $barnd_term->name . '</g:brand>' . PHP_EOL;
						break;
					}
				}
			} else if ( ( is_plugin_active( 'premmerce-woocommerce-brands/premmerce-brands.php' ) ) && ( $brand === 'premmercebrandsplugin' ) ) {
				$barnd_terms = get_the_terms( $this->get_product()->get_id(), 'product_brand' );
				if ( $barnd_terms !== false ) {
					foreach ( $barnd_terms as $barnd_term ) {
						$result_xml .= '<g:brand>' . $barnd_term->name . '</g:brand>' . PHP_EOL;
						break;
					}
				}
			} else if ( ( is_plugin_active( 'woocommerce-brands/woocommerce-brands.php' ) ) && ( $brand === 'woocommerce_brands' ) ) {
				$barnd_terms = get_the_terms( $this->get_product()->get_id(), 'product_brand' );
				if ( $barnd_terms !== false ) {
					foreach ( $barnd_terms as $barnd_term ) {
						$result_xml .= '<g:brand>' . $barnd_term->name . '</g:brand>' . PHP_EOL;
						break;
					}
				}
			} else if ( class_exists( 'woo_brands' ) && $brand === 'woo_brands' ) {
				$barnd_terms = get_the_terms( $this->get_product()->get_id(), 'product_brand' );
				if ( $barnd_terms !== false ) {
					foreach ( $barnd_terms as $barnd_term ) {
						$result_xml .= '<g:brand>' . $barnd_term->name . '</g:brand>' . PHP_EOL;
						break;
					}
				}
			} else if ( $brand === 'post_meta' ) {
				$brand_post_meta_id = xfgmc_optionGET( 'xfgmc_brand_post_meta', $this->get_feed_id(), 'set_arr' );
				$brand_post_meta_id = trim( $brand_post_meta_id );
				if ( get_post_meta( $this->get_product()->get_id(), $brand_post_meta_id, true ) !== '' ) {
					$brand_xml = get_post_meta( $this->get_product()->get_id(), $brand_post_meta_id, true );
					$result_xml .= "<g:brand>" . $brand_xml . "</g:brand>" . PHP_EOL;
				}
			} else if ( $brand === 'default_value' ) {
				$xfgmc_brand_post_meta = xfgmc_optionGET( 'xfgmc_brand_post_meta', $this->get_feed_id(), 'set_arr' );
				$result_xml .= "<g:brand>" . $xfgmc_brand_post_meta . "</g:brand>" . PHP_EOL;
			} else {
				$brand = (int) $brand;
				$brand_xml = $this->get_product()->get_attribute( wc_attribute_taxonomy_name_by_id( $brand ) );
				if ( ! empty( $brand_xml ) ) {
					$result_xml .= "<g:brand>" . ucfirst( xfgmc_replace_decode( $brand_xml ) ) . "</g:brand>" . PHP_EOL;
				}
			}
		}

		return $result_xml;
	}
}

trait XFGMC_Trait_Simple_Get_Color {
	public function get_color() {
		$result_xml = '';

		$color = xfgmc_optionGET( 'xfgmc_color', $this->get_feed_id(), 'set_arr' );
		if ( ! empty( $color ) && $color !== 'off' ) {
			$color = (int) $color;
			$color_xml = $this->get_product()->get_attribute( wc_attribute_taxonomy_name_by_id( $color ) );
			if ( ! empty( $color_xml ) ) {
				$result_xml .= "<g:color>" . ucfirst( xfgmc_replace_decode( $color_xml ) ) . "</g:color>" . PHP_EOL;
			}
		}

		return $result_xml;
	}
}

trait XFGMC_Trait_Simple_Get_Material {
	public function get_material() {
		$result_xml = '';

		// материал
		$material = xfgmc_optionGET( 'xfgmc_material', $this->get_feed_id(), 'set_arr' );
		if ( ! empty( $material ) && $material !== 'off' ) {
			$material = (int) $material;
			$material_xml = $this->get_product()->get_attribute( wc_attribute_taxonomy_name_by_id( $material ) );
			if ( ! empty( $color_xml ) ) {
				$result_xml .= "<g:material>" . ucfirst( xfgmc_replace_decode( $material_xml ) ) . "</g:material>" . PHP_EOL;
			}
		}

		return $result_xml;
	}
}


trait XFGMC_Trait_Simple_Get_Pattern {
	public function get_pattern() {
		$result_xml = '';

		$pattern = xfgmc_optionGET( 'xfgmc_pattern', $this->get_feed_id(), 'set_arr' );
		if ( ! empty( $pattern ) && $pattern !== 'off' ) {
			$pattern = (int) $pattern;
			$pattern_xml = $this->get_product()->get_attribute( wc_attribute_taxonomy_name_by_id( $pattern ) );
			if ( ! empty( $pattern_xml ) ) {
				$result_xml .= "<g:pattern>" . ucfirst( xfgmc_replace_decode( $pattern_xml ) ) . "</g:pattern>" . PHP_EOL;
			}
		}

		return $result_xml;
	}
}

trait XFGMC_Trait_Simple_Get_Gender {
	public function get_gender() {
		$result_xml = '';

		$gender = xfgmc_optionGET( 'xfgmc_gender', $this->get_feed_id(), 'set_arr' );
		if ( ! empty( $gender ) && $gender !== 'disabled' ) {
			$gender = (int) $gender;
			$gender_xml = $this->get_product()->get_attribute( wc_attribute_taxonomy_name_by_id( $gender ) );
			if ( ! empty( $gender_xml ) ) {
				$result_xml .= "<g:gender>" . ucfirst( xfgmc_replace_decode( $gender_xml ) ) . "</g:gender>" . PHP_EOL;
			} else {
				$gender_alt = xfgmc_optionGET( 'xfgmc_gender_alt', $this->get_feed_id(), 'set_arr' );
				if ( $gender_alt !== 'disabled' ) {
					$result_xml .= "<g:gender>" . $gender_alt . "</g:gender>" . PHP_EOL;
				}
			}
		}

		return $result_xml;
	}
}

trait XFGMC_Trait_Simple_Get_Size {
	public function get_size() {
		$result_xml = '';

		// размер
		if ( get_term_meta( $this->get_cat_id(), 'xfgmc_size', true ) == '' || get_term_meta( $this->get_cat_id(), 'xfgmc_size', true ) === 'default' ) {
			$size = xfgmc_optionGET( 'xfgmc_size', $this->get_feed_id(), 'set_arr' );
			if ( ! empty( $size ) && $size !== 'disabled' ) {
				$size = (int) $size;
				$size_xml = $this->get_product()->get_attribute( wc_attribute_taxonomy_name_by_id( $size ) );
				if ( ! empty( $size_xml ) ) {
					$result_xml .= "<g:size>" . ucfirst( xfgmc_replace_decode( $size_xml ) ) . "</g:size>" . PHP_EOL;
				}
			}
		} else {
			$size = (int) get_term_meta( $this->get_cat_id(), 'xfgmc_size', true );
			$size_xml = $this->get_product()->get_attribute( wc_attribute_taxonomy_name_by_id( $size ) );
			if ( ! empty( $size_xml ) ) {
				$result_xml .= "<g:size>" . ucfirst( xfgmc_replace_decode( $size_xml ) ) . "</g:size>" . PHP_EOL;
			}
		}

		return $result_xml;
	}
}

trait XFGMC_Trait_Simple_Get_Size_Type {
	public function get_size_type() {
		$result_xml = '';

		// тип размера
		if ( empty( get_term_meta( $this->get_cat_id(), 'xfgmc_size_type', true ) )
			|| get_term_meta( $this->get_cat_id(), 'xfgmc_size_type', true ) === 'default' ) {
			$size_type = xfgmc_optionGET( 'xfgmc_size_type', $this->get_feed_id(), 'set_arr' );
			if ( ! empty( $size_type ) && $size_type !== 'disabled' ) {
				$size_type = (int) $size_type;
				$size_type_xml = $this->get_product()->get_attribute( wc_attribute_taxonomy_name_by_id( $size_type ) );
				if ( ! empty( $size_type_xml ) ) {
					$result_xml .= new XFGMC_Get_Paired_Tag( 'g:size_type', ucfirst( xfgmc_replace_decode( $size_type_xml ) ) );
				} else {
					$size_type_alt = xfgmc_optionGET( 'xfgmc_size_type_alt', $this->get_feed_id(), 'set_arr' );
					if ( $size_type_alt !== 'disabled' ) {
						$result_xml .= new XFGMC_Get_Paired_Tag( 'g:size_type', $size_type_alt );
					}
				}
			}
		} else {
			$size_type = (int) get_term_meta( $this->get_cat_id(), 'xfgmc_size_type', true );
			$size_type_xml = $this->get_product()->get_attribute( wc_attribute_taxonomy_name_by_id( $size_type ) );
			if ( ! empty( $size_type_xml ) ) {
				$result_xml .= new XFGMC_Get_Paired_Tag( 'g:size_type', ucfirst( xfgmc_replace_decode( $size_type_xml ) ) );
			} else {
				$size_type_alt = get_term_meta( $this->get_cat_id(), 'xfgmc_size_type_alt', true );
				if ( $size_type_alt !== 'default' ) {
					$result_xml .= new XFGMC_Get_Paired_Tag( 'g:size_type', $size_type_alt );
				}
			}
		}

		return $result_xml;
	}
}

trait XFGMC_Trait_Simple_Get_Size_System {
	public function get_size_system() {
		$result_xml = '';

		// система размеров
		$size_system = xfgmc_optionGET( 'xfgmc_size_system', $this->get_feed_id(), 'set_arr' );
		if ( ! empty( $size_system ) && $size_system !== 'disabled' ) {
			$size_system = (int) $size_system;
			$size_system_xml = $this->get_product()->get_attribute( wc_attribute_taxonomy_name_by_id( $size_system ) );
			if ( empty( $size_system_xml ) ) {
				$size_system_alt = xfgmc_optionGET( 'xfgmc_size_system_alt', $this->get_feed_id(), 'set_arr' );
				if ( $size_system_alt !== 'disabled' ) {
					$result_xml .= new XFGMC_Get_Paired_Tag( 'g:size_system', $size_system_alt );
				}
			} else {
				$result_xml .= new XFGMC_Get_Paired_Tag(
					'g:size_system',
					ucfirst( xfgmc_replace_decode( $size_system_xml ) )
				);
			}
		}

		return $result_xml;
	}
}

trait XFGMC_Trait_Simple_Get_Shipping_Xml {
	public function get_shipping_xml() {
		$result_xml = '';

		$xfgmc_default_currency = xfgmc_optionGET( 'xfgmc_default_currency', $this->get_feed_id(), 'set_arr' );
		if ( $xfgmc_default_currency == '' ) {
			$currencyId_xml = get_woocommerce_currency();
		} else {
			$currencyId_xml = $xfgmc_default_currency;
		}
		$currencyId_xml = apply_filters( 'xfgmc_change_price_currency', $currencyId_xml, $this->get_product(), $this->get_feed_id() ); /* с версии 2.2.10 */

		$xfgmc_shipping_xml = '';
		$xfgmc_def_shipping_country = xfgmc_optionGET( 'xfgmc_def_shipping_country', $this->get_feed_id(), 'set_arr' );
		$xfgmc_def_delivery_area_type = xfgmc_optionGET( 'xfgmc_def_delivery_area_type', $this->get_feed_id(), 'set_arr' );
		$xfgmc_def_delivery_area_value = xfgmc_optionGET( 'xfgmc_def_delivery_area_value', $this->get_feed_id(), 'set_arr' );
		if ( $xfgmc_def_shipping_country !== '' && $xfgmc_def_delivery_area_type !== '' && $xfgmc_def_delivery_area_value !== '' ) {
			$xfgmc_def_shipping_service = xfgmc_optionGET( 'xfgmc_def_shipping_service', $this->get_feed_id(), 'set_arr' );
			$xfgmc_def_shipping_price = xfgmc_optionGET( 'xfgmc_def_shipping_price', $this->get_feed_id(), 'set_arr' );

			$xfgmc_shipping_xml = '<g:shipping>' . PHP_EOL;
			$xfgmc_shipping_xml .= '<g:country>' . $xfgmc_def_shipping_country . '</g:country>' . PHP_EOL;
			$xfgmc_shipping_xml .= '<g:' . $xfgmc_def_delivery_area_type . '>' . $xfgmc_def_delivery_area_value . '</g:' . $xfgmc_def_delivery_area_type . '>' . PHP_EOL;
			if ( $xfgmc_def_shipping_service !== '' ) {
				$xfgmc_shipping_xml .= '<g:service>' . $xfgmc_def_shipping_service . '</g:service>' . PHP_EOL;
			}
			if ( $xfgmc_def_shipping_price !== '' ) {
				$xfgmc_shipping_xml .= '<g:price>' . $xfgmc_def_shipping_price . ' ' . $currencyId_xml . '</g:price>' . PHP_EOL;
			}
			$xfgmc_shipping_xml .= '</g:shipping>' . PHP_EOL;
		}

		$result_min_handling_time = '';
		if ( get_post_meta( $this->get_product()->get_id(), '_xfgmc_min_handling_time', true ) !== '' ) {
			$xfgmc_min_handling_time = get_post_meta( $this->get_product()->get_id(), '_xfgmc_min_handling_time', true );
			$result_min_handling_time = '<g:min_handling_time>' . $xfgmc_min_handling_time . '</g:min_handling_time>' . PHP_EOL;
		} else {
			$xfgmc_min_handling_time = xfgmc_optionGET( 'xfgmc_def_min_handling_time', $this->get_feed_id(), 'set_arr' );
			if ( $xfgmc_min_handling_time === '' ) {
			} else {
				$result_min_handling_time = '<g:min_handling_time>' . $xfgmc_min_handling_time . '</g:min_handling_time>' . PHP_EOL;
			}
		}

		$result_max_handling_time = '';
		if ( get_post_meta( $this->get_product()->get_id(), '_xfgmc_max_handling_time', true ) !== '' ) {
			$xfgmc_max_handling_time = get_post_meta( $this->get_product()->get_id(), '_xfgmc_max_handling_time', true );
			$result_max_handling_time = '<g:max_handling_time>' . $xfgmc_max_handling_time . '</g:max_handling_time>' . PHP_EOL;
		} else {
			$xfgmc_max_handling_time = xfgmc_optionGET( 'xfgmc_def_max_handling_time', $this->get_feed_id(), 'set_arr' );
			if ( $xfgmc_max_handling_time === '' ) {
			} else {
				$result_max_handling_time = '<g:max_handling_time>' . $xfgmc_max_handling_time . '</g:max_handling_time>' . PHP_EOL;
			}
		}

		$result_shipping_label = '';
		if ( get_post_meta( $this->get_product()->get_id(), '_xfgmc_shipping_label', true ) !== '' ) {
			$xfgmc_shipping_label = get_post_meta( $this->get_product()->get_id(), '_xfgmc_shipping_label', true );
			$result_shipping_label = '<g:shipping_label>' . $xfgmc_shipping_label . '</g:shipping_label>' . PHP_EOL;
		} else {
			$xfgmc_shipping_label = xfgmc_optionGET( 'xfgmc_def_shipping_label', $this->get_feed_id(), 'set_arr' );
			if ( $xfgmc_shipping_label === '' ) {
			} else {
				$result_shipping_label = '<g:shipping_label>' . $xfgmc_shipping_label . '</g:shipping_label>' . PHP_EOL;
			}
		}

		$result_xml .= $result_shipping_label;
		$result_xml .= $result_min_handling_time;
		$result_xml .= $result_max_handling_time;
		$result_xml .= $xfgmc_shipping_xml;

		// вес
		$weight_xml = $this->get_product()->get_weight();
		if ( ! empty( $weight_xml ) ) {
			$xfgmc_def_shipping_weight_unit = xfgmc_optionGET( 'xfgmc_def_shipping_weight_unit', $this->get_feed_id(), 'set_arr' );
			if ( $xfgmc_def_shipping_weight_unit == '' ) {
				$xfgmc_def_shipping_weight_unit = 'kg';
			}
			$xfgmc_def_shipping_weight_unit = apply_filters( 'xfgmc_simple_def_shipping_weight_unit_filter', $xfgmc_def_shipping_weight_unit, $this->get_product(), $this->get_feed_id() ); /* с версии 2.4.0 */
			$weight_xml = round( wc_get_weight( $weight_xml, $xfgmc_def_shipping_weight_unit ), 3 );
			$result_xml .= "<g:shipping_weight>" . $weight_xml . " " . $xfgmc_def_shipping_weight_unit . "</g:shipping_weight>" . PHP_EOL;
		}

		/*$dimensions = $this->get_product()->get_dimensions();
								if (!empty($dimensions)) {*/
		$dimensions = wc_format_dimensions( $this->get_product()->get_dimensions( false ) );
		if ( $this->get_product()->has_dimensions() ) {
			$length_xml = $this->get_product()->get_length();
			if ( ! empty( $length_xml ) ) {
				$length_xml = round( wc_get_dimension( $length_xml, 'cm' ), 3 );
				$result_xml .= "<g:shipping_length>" . $length_xml . " cm</g:shipping_length>" . PHP_EOL;
			}

			$width_xml = $this->get_product()->get_width();
			if ( ! empty( $width_xml ) ) {
				$width_xml = round( wc_get_dimension( $width_xml, 'cm' ), 3 );
				$result_xml .= "<g:shipping_width>" . $width_xml . " cm</g:shipping_width>" . PHP_EOL;
			}

			$height_xml = $this->get_product()->get_height();
			if ( ! empty( $length_xml ) ) {
				$height_xml = round( wc_get_dimension( $height_xml, 'cm' ), 3 );
				$result_xml .= "<g:shipping_height>" . $height_xml . " cm</g:shipping_height>" . PHP_EOL;
			}
		}

		return $result_xml;
	}
}

trait XFGMC_Trait_Get_Cat_Id {
	public function get_cat_id() {
		// "Категории 
		$catid = null;
		if ( class_exists( 'WPSEO_Primary_Term' ) ) {
			$catWPSEO = new WPSEO_Primary_Term( 'product_cat', $this->get_product()->get_id() );
			$catidWPSEO = $catWPSEO->get_primary_term();
			if ( $catidWPSEO !== false ) {
				$catid = $catidWPSEO;
			} else {
				$termini = get_the_terms( $this->get_product()->get_id(), 'product_cat' );
				if ( $termini !== false ) {
					foreach ( $termini as $termin ) {
						$catid = $termin->term_id;
						break; // т.к. у товара может быть лишь 1 категория - выходим досрочно.
					}
				} else { // если база битая. фиксим id категорий
					new XFGMC_Error_Log( 'FEED № ' . $this->get_feed_id() . '; WARNING: Для товара $this->get_product()->get_id() = ' . $this->get_product()->get_id() . ' get_the_terms = false. Возможно база битая. Пробуем задействовать wp_get_post_terms; Файл: offer.php; Строка: ' . __LINE__ );
					$product_cats = wp_get_post_terms( $this->get_product()->get_id(), 'product_cat', array( "fields" => "ids" ) );
					// Раскомментировать строку ниже для автопочинки категорий в БД (место 1 из 2)
					// wp_set_object_terms($this->get_product()->get_id(), $product_cats, 'product_cat');
					if ( is_array( $product_cats ) && count( $product_cats ) ) {
						$catid = $product_cats[0];
						new XFGMC_Error_Log( 'FEED № ' . $this->get_feed_id() . '; WARNING: Для товара $this->get_product()->get_id() = ' . $this->get_product()->get_id() . ' база наверняка битая. wp_get_post_terms вернула массив. $catid = ' . $catid . '; Файл: offer.php; Строка: ' . __LINE__ );
					}
				}
			}
		} else if ( class_exists( 'RankMath' ) ) {
			$primary_cat_id = get_post_meta( $this->get_product()->get_id(), 'rank_math_primary_category', true );
			if ( $primary_cat_id ) {
				$product_cat = get_term( $primary_cat_id, 'product_cat' );
				$catid = $product_cat->term_id;
			} else {
				$termini = get_the_terms( $this->get_product()->get_id(), 'product_cat' );
				if ( $termini !== false ) {
					foreach ( $termini as $termin ) {
						$catid = $termin->term_id;
						break; // т.к. у товара может быть лишь 1 категория - выходим досрочно.
					}
				} else { // если база битая. фиксим id категорий
					new XFGMC_Error_Log( 'FEED № ' . $this->get_feed_id() . '; WARNING: Для товара $this->get_product()->get_id() = ' . $this->get_product()->get_id() . ' get_the_terms = false. Возможно база битая. Пробуем задействовать wp_get_post_terms; Файл: offer.php; Строка: ' . __LINE__ );
					$product_cats = wp_get_post_terms( $this->get_product()->get_id(), 'product_cat', array( "fields" => "ids" ) );
					// Раскомментировать строку ниже для автопочинки категорий в БД (место 1 из 2)
					// wp_set_object_terms($this->get_product()->get_id(), $product_cats, 'product_cat');
					if ( is_array( $product_cats ) && count( $product_cats ) ) {
						$catid = $product_cats[0];
						new XFGMC_Error_Log( 'FEED № ' . $this->get_feed_id() . '; WARNING: Для товара $this->get_product()->get_id() = ' . $this->get_product()->get_id() . ' база наверняка битая. wp_get_post_terms вернула массив. $catid = ' . $catid . '; Файл: offer.php; Строка: ' . __LINE__ );
					}
				}
			}
		} else {
			$termini = get_the_terms( $this->get_product()->get_id(), 'product_cat' );
			if ( $termini !== false ) {
				foreach ( $termini as $termin ) {
					$catid = $termin->term_id;
					break; // т.к. у товара может быть лишь 1 категория - выходим досрочно.
				}
			} else { // если база битая. фиксим id категорий
				new XFGMC_Error_Log( 'FEED № ' . $this->get_feed_id() . '; WARNING: Для товара $this->get_product()->get_id() = ' . $this->get_product()->get_id() . ' get_the_terms = false. Возможно база битая. Пробуем задействовать wp_get_post_terms; Файл: offer.php; Строка: ' . __LINE__ );
				$product_cats = wp_get_post_terms( $this->get_product()->get_id(), 'product_cat', array( "fields" => "ids" ) );
				// Раскомментировать строку ниже для автопочинки категорий в БД (место 1 из 2)
				// wp_set_object_terms($this->get_product()->get_id(), $product_cats, 'product_cat');
				if ( is_array( $product_cats ) && count( $product_cats ) ) {
					$catid = $product_cats[0];
					new XFGMC_Error_Log( 'FEED № ' . $this->get_feed_id() . '; WARNING: Для товара $this->get_product()->get_id() = ' . $this->get_product()->get_id() . ' база наверняка битая. wp_get_post_terms вернула массив. $catid = ' . $catid . '; Файл: offer.php; Строка: ' . __LINE__ );
				}
			}
		}

		$this->feed_category_id = $catid;
		return $catid;
	}
}

trait XFGMC_Trait_Skips {
	public function get_skips() {
		$skip_flag = false;

		if ( null == $this->get_product() ) {
			$this->add_skip_reason( [ 
				'reason' => __( 'There is no product with this ID', 'xml-for-google-merchant-center' ),
				'post_id' => $this->get_product()->get_id(),
				'file' => 'traits-xfgmc-simple.php',
				'line' => __LINE__
			] );
			return '';
		}

		if ( $this->get_product()->is_type( 'grouped' ) ) {
			$this->add_skip_reason( array( 'reason' => __( 'Product is grouped', 'xml-for-google-merchant-center' ), 'post_id' => $this->get_product()->get_id(), 'file' => 'traits-xfgmc-simple.php', 'line' => __LINE__ ) );
			return '';
		}

		// что выгружать
		$xfgmc_whot_export = xfgmc_optionGET( 'xfgmc_whot_export', $this->get_feed_id(), 'set_arr' );
		if ( $this->get_product()->is_type( 'variable' ) ) {
			if ( $xfgmc_whot_export === 'simple' ) {
				$this->add_skip_reason( array( 'reason' => __( 'Product is simple', 'xml-for-google-merchant-center' ), 'post_id' => $this->get_product()->get_id(), 'file' => 'traits-xfgmc-simple.php', 'line' => __LINE__ ) );
				return '';
			}
		}
		if ( $this->get_product()->is_type( 'simple' ) ) {
			if ( $xfgmc_whot_export === 'variable' ) {
				$this->add_skip_reason( array( 'reason' => __( 'Product is variable', 'xml-for-google-merchant-center' ), 'post_id' => $this->get_product()->get_id(), 'file' => 'traits-xfgmc-simple.php', 'line' => __LINE__ ) );
				return '';
			}
		}

		$special_data_for_flag = '';
		$special_data_for_flag = apply_filters( 'xfgmc_special_data_for_flag_filter', $special_data_for_flag, $this->get_product(), $this->get_feed_id() ); /* с версии 2.2.7 */

		$skip_flag = apply_filters( 'xfgmc_skip_flag', $skip_flag, $this->get_product()->get_id(), $this->get_product(), $special_data_for_flag, $this->get_feed_id() ); /* c версии 2.2.0, с версии 2.2.7 добавелн $special_data_for_flag */
		if ( $skip_flag === true ) {
			$this->add_skip_reason( array( 'reason' => __( 'Flag', 'xml-for-google-merchant-center' ), 'post_id' => $this->get_product()->get_id(), 'file' => 'traits-xfgmc-simple.php', 'line' => __LINE__ ) );
			return '';
		}

		// пропуск товаров, которых нет в наличии
		$xfgmc_skip_missing_products = xfgmc_optionGET( 'xfgmc_skip_missing_products', $this->get_feed_id(), 'set_arr' );
		if ( $xfgmc_skip_missing_products == 'on' ) {
			if ( $this->get_product()->is_in_stock() == false ) {
				$this->add_skip_reason( array( 'reason' => __( 'Skip missing products', 'xml-for-google-merchant-center' ), 'post_id' => $this->get_product()->get_id(), 'file' => 'traits-xfgmc-simple.php', 'line' => __LINE__ ) );
				return '';
			}
		}

		// пропускаем товары на предзаказ
		$skip_backorders_products = xfgmc_optionGET( 'xfgmc_skip_backorders_products', $this->get_feed_id(), 'set_arr' );
		if ( $skip_backorders_products == 'on' ) {
			if ( $this->get_product()->get_manage_stock() == true ) { // включено управление запасом  
				if ( ( $this->get_product()->get_stock_quantity() < 1 ) && ( $this->get_product()->get_backorders() !== 'no' ) ) {
					$this->add_skip_reason( array( 'reason' => __( 'Skip backorders products', 'xml-for-google-merchant-center' ), 'post_id' => $this->get_product()->get_id(), 'file' => 'traits-xfgmc-simple.php', 'line' => __LINE__ ) );
					return '';
				}
			} else {
				if ( $this->get_product()->get_stock_status() !== 'instock' ) {
					$this->add_skip_reason( array( 'reason' => __( 'Skip backorders products', 'xml-for-google-merchant-center' ), 'post_id' => $this->get_product()->get_id(), 'file' => 'traits-xfgmc-simple.php', 'line' => __LINE__ ) );
					return '';
				}
			}
		}

		if ( $this->get_product()->is_type( 'variable' ) ) {
			// пропуск вариаций, которых нет в наличии
			$xfgmc_skip_missing_products = xfgmc_optionGET( 'xfgmc_skip_missing_products', $this->get_feed_id(), 'set_arr' );
			if ( $xfgmc_skip_missing_products == 'on' ) {
				if ( $this->get_offer()->is_in_stock() == false ) {
					$this->add_skip_reason( array( 'offer_id' => $this->get_offer()->get_id(), 'reason' => __( 'Skip missing products', 'xml-for-google-merchant-center' ), 'post_id' => $this->get_product()->get_id(), 'file' => 'traits-xfgmc-variable.php', 'line' => __LINE__ ) );
					return '';
				}
			}

			// пропускаем вариации на предзаказ
			$skip_backorders_products = xfgmc_optionGET( 'xfgmc_skip_backorders_products', $this->get_feed_id(), 'set_arr' );
			if ( $skip_backorders_products == 'on' ) {
				if ( $this->get_offer()->get_manage_stock() == true ) { // включено управление запасом			  
					if ( ( $this->get_offer()->get_stock_quantity() < 1 ) && ( $this->get_offer()->get_backorders() !== 'no' ) ) {
						$this->add_skip_reason( array( 'offer_id' => $this->get_offer()->get_id(), 'reason' => __( 'Skip backorders products', 'xml-for-google-merchant-center' ), 'post_id' => $this->get_product()->get_id(), 'file' => 'traits-xfgmc-variable.php', 'line' => __LINE__ ) );
						return '';
					}
				}
			}

			$skip_flag = apply_filters( 'xfgmc_skip_flag_variable', $skip_flag, $this->get_product()->get_id(), $this->get_product(), $this->get_offer(), $special_data_for_flag, $this->get_feed_id() ); /* c версии 2.2.0, с версии 2.2.7 добавелн $special_data_for_flag */
			if ( $skip_flag === true ) {
				$this->add_skip_reason( array( 'offer_id' => $this->get_offer()->get_id(), 'reason' => __( 'Flag', 'xml-for-google-merchant-center' ), 'post_id' => $this->get_product()->get_id(), 'file' => 'traits-xfgmc-variable.php', 'line' => __LINE__ ) );
				return '';
			}
		}
	}

}

trait XFGMC_Trait_Get_USA_Tax_Info {
	public function get_usa_tax_info() {
		$result_xml = '';
		$xfgmc_whot_export = xfgmc_optionGET( 'xfgmc_whot_export', $this->get_feed_id(), 'set_arr' );
		$xfgmc_usa_tax_info = xfgmc_optionGET( 'xfgmc_usa_tax_info', $this->get_feed_id(), 'set_arr' );
		if ( $xfgmc_usa_tax_info === 'enabled' ) {
			$result_xml .= '<g:tax>' . PHP_EOL;
			$result_xml .= '<g:country>US</g:country>' . PHP_EOL;

			$xfgmc_tax_region = xfgmc_optionGET( 'xfgmc_tax_region', $this->get_feed_id(), 'set_arr' );
			$xfgmc_tax_rate = xfgmc_optionGET( 'xfgmc_tax_rate', $this->get_feed_id(), 'set_arr' );
			$xfgmc_sipping_tax = xfgmc_optionGET( 'xfgmc_sipping_tax', $this->get_feed_id(), 'set_arr' );

			if ( ! empty( $xfgmc_tax_region ) ) {
				$result_xml .= '<g:region>' . $xfgmc_tax_region . '</g:region>' . PHP_EOL;
			}
			if ( ! empty( $xfgmc_tax_rate ) ) {
				$result_xml .= '<g:rate>' . $xfgmc_tax_rate . '</g:rate>' . PHP_EOL;
			}
			if ( ! empty( $xfgmc_sipping_tax ) ) {
				$result_xml .= '<g:tax_ship>' . $xfgmc_sipping_tax . '</g:tax_ship>' . PHP_EOL;
			}
			$result_xml .= '</g:tax>' . PHP_EOL;
		}
		return $result_xml;
	}
}