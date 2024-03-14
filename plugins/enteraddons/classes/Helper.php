<?php
namespace Enteraddons\Classes;

/**
 * Enteraddons helper class
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

class Helper {
	
	public static function ratingStar( $rating, $echo = true ) {

		$starRating = '';

		$j = 0;

	    for( $i = 0; $i <= 4; $i++ ) {
	      $j++;

	      if( $rating  >= $j   || $rating  == '5'   ) {
	        $starRating .= '<i class="fas fa-star"></i>';
	      }elseif( $rating < $j && $rating  > $i ) {
	        $starRating .= '<i class="fas fa-star-half-alt"></i>';
	      } else {
	        $starRating .= '<i class="far fa-star"></i>';
	      }

	    }
	    //
	    if( $echo == true ) {
	      echo wp_kses( $starRating, [ 'i' => ['class' => [] ] ] );
	    } else {
	      return $starRating;
	    }

	}

	public static function includeCurrentPathFile( $path, $fileName ) {

		if( !is_array( $fileName ) ) {
			require_once( plugin_dir_path( $path ).$fileName );
		} else {
			foreach( $fileName as $name ) {
				require_once( plugin_dir_path( $path ).$name );
			}
		}
	}

	public static function getElementorIcon( $optionValue ) {
		if( empty( $optionValue ) ) {
			return;
		}
		//
		if(  'svg' != $optionValue['library']  ) {
			if( !empty( $optionValue['value'] ) ) {
				return '<i class="'.esc_attr( $optionValue['value'] ).'" /></i>';
			}				
		} else {
			return '<img src="'.esc_url( $optionValue['value']['url'] ).'" />';
		}

	}
	
	public static function getElementorLinkHandler( $url, $innerData, $class= 'anchor--link' ) {
		//
		if( !empty( $url['url'] ) ) {
	        $target = '_self';
	        if( !empty( $url['is_external'] ) && $url['is_external'] == 'on' ) {
	            $target = '_blank';
	        }

			return '<a class="'.esc_attr($class).'" href="'.esc_url( $url['url'] ).'" target="'.esc_attr( $target ).'">'.self::allowFormattingTagHtml( $innerData ).'</a>';
		}
	}
	/**
	 * allowFormattingTagHtml
	 * 
	 * @param  string $text
	 * @return void
	 */
	public static function allowFormattingTagHtml( $text = '' ) {

		if( empty( $text ) ) {
			return;
		}

		$allowHtml = [
			'span' => ['class' => [],'style' => []],
			'b' => [],
			'strong' => [],
			'br' => [],
			'i' => ['class' => []],
			'svg' => [],
			'small' => [],
			'img' => ['src'=>[],'class' => []]
		];

		return wp_kses( $text, $allowHtml );
	}
	/**
	 * currency list
	 * @return array
	 */
	public static function getCurrencyList() {
		return [
            ''             => esc_html__( 'None', 'enteraddons' ),
            'baht'         => '&#3647; ' . esc_html__( 'Baht', 'enteraddons' ),
            'bdt'          => '&#2547; ' . esc_html__( 'BD Taka', 'enteraddons' ),
            'dollar'       => '&#36; ' . esc_html__( 'Dollar', 'enteraddons' ),
            'euro'         => '&#128; ' . esc_html__( 'Euro', 'enteraddons' ),
            'franc'        => '&#8355; ' . esc_html__( 'Franc', 'enteraddons' ),
            'guilder'      => '&fnof; ' . esc_html__( 'Guilder', 'enteraddons' ),
            'indian_rupee' => '&#8377; ' . esc_html__( 'Rupee (Indian)', 'enteraddons' ),
            'krona'        => 'kr ' . esc_html__( 'Krona', 'enteraddons' ),
            'lira'         => '&#8356; ' . esc_html__( 'Lira', 'enteraddons' ),
            'peseta'       => '&#8359 ' . esc_html__( 'Peseta', 'enteraddons' ),
            'peso'         => '&#8369; ' . esc_html__( 'Peso', 'enteraddons' ),
            'pound'        => '&#163; ' . esc_html__( 'Pound Sterling', 'enteraddons' ),
            'real'         => 'R$ ' . esc_html__( 'Real', 'enteraddons' ),
            'ruble'        => '&#8381; ' . esc_html__( 'Ruble', 'enteraddons' ),
            'rupee'        => '&#8360; ' . esc_html__( 'Rupee', 'enteraddons' ),
            'shekel'       => '&#8362; ' . esc_html__( 'Shekel', 'enteraddons' ),
            'yen'          => '&#165; ' . esc_html__( 'Yen/Yuan', 'enteraddons' ),
            'won'          => '&#8361; ' . esc_html__( 'Won', 'enteraddons' ),
            'custom'       => esc_html__( 'Custom', 'enteraddons' ),
        ];
	}

	public static function getCurrencySymbol( $symbol_code ) {

		$symbolList = [
            'baht'         => '&#3647;',
            'bdt'          => '&#2547;',
            'dollar'       => '&#36;',
            'euro'         => '&#128;',
            'franc'        => '&#8355;',
            'guilder'      => '&fnof;',
            'indian_rupee' => '&#8377;',
            'krona'        => 'kr',
            'lira'         => '&#8356;',
            'peseta'       => '&#8359',
            'peso'         => '&#8369;',
            'pound'        => '&#163;',
            'real'         => 'R$',
            'ruble'        => '&#8381;',
            'rupee'        => '&#8360;',
            'shekel'       => '&#8362;',
            'yen'          => '&#165;',
            'won'          => '&#8361;',
        ];

        return !empty( $symbolList[$symbol_code] ) ? $symbolList[$symbol_code] : '';

	}
	/**
	 * enteraddons_elementor
	 * @return void
	 */
	public static function enteraddons_elementor() {
		return \Elementor\Plugin::instance();
	}
	/**
	 * is_elementor_edit_mode
	 * @return boolean
	 */
	public static function is_elementor_edit_mode() {
		$elementor = self::enteraddons_elementor();
		return $elementor->editor->is_edit_mode() || $elementor->preview->is_preview_mode() || is_preview();
	}

	/**
	 *
	 * Check WooCommerce plugin activities
	 *
	 * @return string
	 *
	 */
	public static function is_woo_activated() {
		$isWoo = false;
		if( class_exists( 'woocommerce' ) ) {
			$isWoo = true;
		}
		return $isWoo;
	}
	/**
	 * Change editor js folder permissions mode to work properly
	 * @return void
	 * 
	 */
	public static function change_permissions_mode() {

		$dir = ENTERADDONS_DIR_CORE.'libs/editor/js';
		if( !chmod($dir,0777) ) {
			chmod($dir,0777);
		}
	}
	
	/**
	 * [elementor_content_display description]
	 * @param  [type] $contentId [description]
	 * @return [type]            [description]
	 */
	public static function elementor_content_display( $contentId = '' ) {

		if( !self::is_elementor_edit_mode() ) {
			global $ea_cache_dir_url, $ea_cache_file_prefix;
			//
			$handle = $ea_cache_file_prefix.$contentId;
			$url = $ea_cache_dir_url.$handle.'.css';
			$version = ENTERADDONS_VERSION.'.'.get_post_modified_time( 'U', false, $contentId, false );

			// Enqueue Header Widget Css for Front-End
			wp_enqueue_style( $handle, $url, array('elementor-frontend'), $version, false );
		}
		//wp_enqueue_style( 'enteraddons-'.$contentId );
		return \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $contentId );
	}

	/**
	 * [menu_list description]
	 * @return [type] [description]
	 */
	public static function menu_list(){
	    $siteMenu = wp_get_nav_menus();
	    $getMenu  = [];
		$getMenu[''] = esc_html__( 'Select A Menu','enteraddons' );
	    foreach( $siteMenu as $menu ) {
	        $getMenu[ $menu->slug ] = $menu->name;
	    }
	    return $getMenu;
	}

	public static function is_pro_active() {
		return apply_filters( 'enteraddons_is_pro', false );
	}

	public static function getPages() {
		return get_pages();
	}
		
	public static function getPagesIdTitle() {
		$pages = self::getPages();
		$getList = [];
		if( !empty( $pages ) ) {
			foreach( $pages as $page ) {
				$getList[$page->ID] = $page->post_title;
			}
		}
		return $getList;
	}

	public static function checkPhpV81() {
		$b = version_compare( ENTERADDONS_CURRENT_PHPVERSION, '8.0.99', '>' );
		$l = version_compare( ENTERADDONS_CURRENT_PHPVERSION, '8.2', '<' );

		if( $b && $l  ) {
			return true;
		} else {
			return false;
		}
	}

	public static function getElementorTemplates() {

		$args = array(
            'post_type' 	=> 'elementor_library',
            'numberposts' 	=> '-1',
            'post_status' 	=> 'publish'
        );

        $elementor_templates = get_posts($args);
        $templates = [];

		foreach ( $elementor_templates as $template ) {
		   $templates[$template->ID] = $template->post_title;
		}

		return $templates;
	}

	public static function versionType() {
		return self::is_pro_active() ? 'PRO' : 'LITE';
	}
	
	public static function ea_brand_icon_html() {
		return '<i style="float: right;font-size: 16px;color: #E82A5C;" class="entera entera-ea-fev"></i>';
	}

} // Class End