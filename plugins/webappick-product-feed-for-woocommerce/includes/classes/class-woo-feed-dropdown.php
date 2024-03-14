<?php
/**
 * The file that defines the merchants attributes dropdown
 *
 * A class definition that includes attributes dropdown and functions used across the admin area.
 *
 * @link       https://webappick.com/
 * @since      1.0.0
 *
 * @package    Woo_Feed
 * @subpackage Woo_Feed/includes
 * @author     Ohidul Islam <wahid@webappick.com>
 */

class Woo_Feed_Dropdown {
	
	public $cats = array();
	public $output_types = array();
	
	public function __construct() {
	    $this->output_types = $this->get_output_types();
	}

    /**
     * Get Output Types
     *
     * @return array
     */
    function get_output_types() {
        $output_types = array(
            '1'  => 'Default',
            '2'  => 'Strip Tags',
            '3'  => 'UTF-8 Encode',
            '4'  => 'htmlentities',
            '5'  => 'Integer',
            '6'  => 'Price',
            '7'  => 'Rounded Price',
            '8'  => 'Remove Space',
            '9'  => 'CDATA',
            '10' => 'Remove Special Character',
            '11' => 'Remove ShortCodes',
            '12' => 'ucwords',
            '13' => 'ucfirst',
            '14' => 'strtoupper',
            '15' => 'strtolower',
            '16' => 'urlToSecure',
            '17' => 'urlToUnsecure',
            '18' => 'only_parent',
            '19' => 'parent',
            '20' => 'parent_if_empty',
            '21' => '',
            '22' => '',
        );

        return apply_filters('woo_feed_output_types', $output_types);
    }

	/**
	 * Get Cached Dropdown Entries
	 *
	 * @param string $key      cache key
	 * @param string $selected selected option
	 *
	 * @return string|false
	 */
	protected function get_cached_dropdown( $key, $selected = '' ) {
		$options = woo_feed_get_cached_data( $key );
		if ( strlen( $selected ) ) {
			$selected = esc_attr( $selected );
			$options = str_replace( "value=\"{$selected}\"", "value=\"{$selected}\" selected", $options );
		}
		return empty( $options ) ? false : $options;
	}

	/**
	 * create dropdown options and cache for next use
	 *
	 * @param string $cache_key cache key
	 * @param array  $items     dropdown items
	 * @param string $selected  selected option
	 * @param string $default   default option
	 *
	 * @return string
	 */
	protected function cache_dropdown( $cache_key, $items, $selected = '', $default = '' ) {

		if ( empty( $items ) || ! is_array( $items ) ) {
			return '';
		}

		if ( ! empty( $default ) ) {
			$options = '<option value="" class="disabled" selected>' . esc_html( $default ) . '</option>';
		} else {
			$options = '<option></option>';
		}

		foreach ( $items as $key => $value ) {
			if ( substr( $key, 0, 2 ) == '--' ) {
				$options .= "<optgroup label=\"{$value}\">";
			} elseif ( substr( $key, 0, 2 ) == '---' ) {
				$options .= '</optgroup>';
			} else {
				$options .= sprintf( '<option value="%s">%s</option>', $key, $value );
			}
		}

		woo_feed_set_cache_data( $cache_key, $options );

		if ( strlen( $selected ) ) {
			$selected = esc_attr( $selected );
			$options = str_replace( "value=\"{$selected}\"", "value=\"{$selected}\" selected", $options );
		}

		return $options;
	}
	
	/**
	 * Dropdown of Merchant List
	 *
	 * @param string $selected
	 *
	 * @return string
	 */
	public function merchantsDropdown( $selected = '' ) {
		$options = $this->get_cached_dropdown( 'merchantsDropdown', $selected );
		if ( false === $options ) {
			$attributes = new Woo_Feed_Merchant();
			$options = $this->cache_dropdown( 'merchantsDropdown', $attributes->merchants(), $selected );
		}
		return $options;
	}

    /**
     * Dropdown of Country List
     *
     * @param string $selected
     *
     * @return string
     */
    public function countriesDropdown( $selected = '' ) {
        $options = $this->get_cached_dropdown( 'countriesDropdown', $selected );
        if ( false === $options ) {
            $options    = $this->cache_dropdown( 'countriesDropdown', woo_feed_countries(), $selected );
        }
        return $options;
    }
	
	/**
	 * @param int|int[] $selected
	 *
	 * @return string
	 */
	public function outputTypes( $selected = 1 ) {
		$output_types = '';
		if ( ! is_array( $selected ) ) {
			$selected = (array) explode( ',', $selected );
		}
		foreach ( $this->output_types as $key => $value ) {
			$output_types .= "<option value=\"{$key}\"" . selected( in_array( $key, $selected ), true, false ) . ">{$value}</option>";
		}
		// @TODO remove update_option( 'woo_feed_output_type_options', $output_types, false );
		
		return $output_types;
	}
	
	/**
	 * Read txt file which contains google taxonomy list
	 *
	 * @param string $selected
	 *
	 * @return string
	 */
	public function googleTaxonomy( $selected = '' ) {
		// Get All Google Taxonomies
		$fileName           = WOO_FEED_FREE_ADMIN_PATH . '/partials/templates/taxonomies/google_taxonomy.txt';
		$customTaxonomyFile = fopen( $fileName, 'r' ); // phpcs:ignore
		$str                = '';
		if ( ! empty( $selected ) ) {
			$selected = trim( $selected );
			if ( ! is_numeric( $selected ) ) {
				$selected = html_entity_decode( $selected );
			} else {
				$selected = (int) $selected;
			}
		}
		if ( $customTaxonomyFile ) {
			// First line contains metadata, ignore it
			fgets( $customTaxonomyFile ); // phpcs:ignore
			while ( $line = fgets( $customTaxonomyFile ) ) { // phpcs:ignore
				list( $catId, $cat ) = explode( '-', $line );
				$catId = (int) trim( $catId );
				$cat   = trim( $cat );
                $is_selected = selected( $selected, is_numeric( $selected ) ? $catId : $cat, false );
                $str .= "<option value='{$catId}' {$is_selected} >{$cat}</option>";
			}
		}
		if ( ! empty( $str ) ) {
			$str = '<option></option>' . $str;
		}

		return $str;
	}

	/**
	 * Read txt file which contains google taxonomy list
	 *
	 * @return array
	 */
	public function googleTaxonomyArray() {
		// Get All Google Taxonomies
		$fileName           = WOO_FEED_FREE_ADMIN_PATH . '/partials/templates/taxonomies/google_taxonomy.txt';
		$customTaxonomyFile = fopen( $fileName, 'r' );  // phpcs:ignore
		$taxonomy           = array();
		if ( $customTaxonomyFile ) {
			// First line contains metadata, ignore it
			fgets( $customTaxonomyFile );  // phpcs:ignore
			while ( $line = fgets( $customTaxonomyFile ) ) {  // phpcs:ignore
				list( $catId, $cat ) = explode( '-', $line );
				$taxonomy[] = array(
					'value' => absint( trim( $catId ) ),
					'text'  => trim( $cat ),
				);
			}
		}
		$taxonomy = array_filter( $taxonomy );

		return $taxonomy;
	}

    /**
     * Read txt file which contains facebook taxonomy list
     *
     * @param string $selected
     *
     * @return string
     */
    public function facebookTaxonomy( $selected = '' ) {
        // Get All Facebook Taxonomies
        $fileName           = WOO_FEED_FREE_ADMIN_PATH . '/partials/templates/taxonomies/fb_taxonomy.txt';
        $customTaxonomyFile = fopen( $fileName, 'r' ); // phpcs:ignore
        $str                = '';
        if ( ! empty( $selected ) ) {
            $selected = trim( $selected );
            if ( ! is_numeric( $selected ) ) {
                $selected = html_entity_decode( $selected );
            } else {
                $selected = (int) $selected;
            }
        }
        if ( $customTaxonomyFile ) {
            // First line contains metadata, ignore it
            fgets( $customTaxonomyFile ); // phpcs:ignore
            while ( $line = fgets( $customTaxonomyFile ) ) { // phpcs:ignore
                list( $catId, $cat ) = explode( ',', $line );
                $catId = (int) trim( $catId );
                $cat   = trim( $cat );
                $is_selected = selected( $selected, is_numeric( $selected ) ? $catId : $cat, false );
                $str .= "<option value='{$catId}' {$is_selected} >{$cat}</option>";
            }
        }
        if ( ! empty( $str ) ) {
            $str = '<option></option>' . $str;
        }

        return $str;
    }

	/**
	 * Read txt file which contains facebook taxonomy list
	 *
	 * @return array
	 */
	public function facebookTaxonomyArray() {
		// Get All Facebook Taxonomies
		$fileName           = WOO_FEED_FREE_ADMIN_PATH . '/partials/templates/taxonomies/fb_taxonomy.txt';
		$customTaxonomyFile = fopen( $fileName, 'r' );  // phpcs:ignore
		$taxonomy           = array();
		if ( $customTaxonomyFile ) {
			// First line contains metadata, ignore it
			fgets( $customTaxonomyFile );  // phpcs:ignore
			while ( $line = fgets( $customTaxonomyFile ) ) {  // phpcs:ignore
				list( $catId, $cat ) = explode( ',', $line );
				$taxonomy[] = array(
					'value' => absint( trim( $catId ) ),
					'text'  => trim( $cat ),
				);
			}
		}
		$taxonomy = array_filter( $taxonomy );

		return $taxonomy;
	}

	
	// Merchant Attribute DropDown.
	
	/**
	 * Dropdown of Google Attribute List
	 *
	 * @param string $selected
     * @param array $merchants
	 *
	 * @return string
	 */
	public function googleAttributesDropdown( $selected = '', $merchants = [] ) {
		$options = $this->get_cached_dropdown( 'googleAttributesDropdown', $selected );
		
		if ( false === $options ) {
            $attributes_obj = new Woo_Feed_Default_Attributes();
            $attributes = apply_filters( 'woo_feed_filter_dropdown_attributes', $attributes_obj->googleAttributes(), $merchants );
			return $this->cache_dropdown( 'googleAttributesDropdown', $attributes, $selected );
		}
		return $options;
	}

    /**
     * Dropdown of Facebook Template
     *
     * @param string $selected
     *
     * @return string
     */
    public function facebookAttributesDropdown( $selected = '' ) {
        $options = $this->get_cached_dropdown( 'facebookAttributesDropdown', $selected );

        if ( false === $options ) {
            $attributes = new Woo_Feed_Default_Attributes();
            return $this->cache_dropdown( 'facebookAttributesDropdown', $attributes->facebookAttributes(), $selected );
        }
        return $options;
    }

    /**
     * Dropdown of Google Local Inventory Ads Template
     *
     * @param string $selected
     *
     * @return string
     */
    public function google_localAttributesDropdown( $selected = '' ) {
        $options = $this->get_cached_dropdown( 'google_localAttributesDropdown', $selected );

        if ( false === $options ) {
            $attributes = new Woo_Feed_Default_Attributes();
            return $this->cache_dropdown( 'google_localAttributesDropdown', $attributes->googleAttributes(), $selected );
        }
        return $options;
    }

    /**
     * Dropdown of Google Local Product Inventory Template
     *
     * @param string $selected
     *
     * @return string
     */
    public function google_local_inventoryAttributesDropdown( $selected = '' ) {
        $options = $this->get_cached_dropdown( 'google_local_inventoryAttributesDropdown', $selected );

        if ( false === $options ) {
            $attributes = new Woo_Feed_Default_Attributes();
            return $this->cache_dropdown( 'google_local_inventoryAttributesDropdown', $attributes->googleAttributes(), $selected );
        }
        return $options;
    }

    /**
     * Dropdown of Google Promotions Template
     *
     * @param string $selected
     *
     * @return string
     */
    public function google_promotionsAttributesDropdown( $selected = '' ) {
        $options = $this->get_cached_dropdown( 'google_promotionsAttributesDropdown', $selected );

        if ( false === $options ) {
            $attributes = new Woo_Feed_Default_Attributes();
            return $this->cache_dropdown( 'google_promotionsAttributesDropdown', $attributes->googleAttributes(), $selected );
        }
        return $options;
    }

    /**
     * Dropdown of Bing Attribute List
     *
     * @param string $selected
     *
     * @return string
     */
    public function bingAttributesDropdown( $selected = '' ) {
        $options = $this->get_cached_dropdown( 'bingAttributesDropdown', $selected );

        if ( false === $options ) {
            $attributes = new Woo_Feed_Default_Attributes();
            return $this->cache_dropdown( 'bingAttributesDropdown', $attributes->bingAttributes(), $selected );
        }
        return $options;
    }

    /**
     * Dropdown of Bing Local Inventory Attribute List
     *
     * @param string $selected
     *
     * @return string
     */
    public function bing_local_inventoryAttributesDropdown( $selected = '' ) {
        $options = $this->get_cached_dropdown( 'bing_local_inventoryAttributesDropdown', $selected );

        if ( false === $options ) {
            $attributes = new Woo_Feed_Default_Attributes();
            return $this->cache_dropdown( 'bing_local_inventoryAttributesDropdown', $attributes->bingAttributes(), $selected );
        }
        return $options;
    }

    /**
     * Dropdown of Snapchat Attribute List
     *
     * @param string $selected
     *
     * @return string
     */
    public function snapchatAttributesDropdown( $selected = '' ) {
        $options = $this->get_cached_dropdown( 'snapchatAttributesDropdown', $selected );
        if ( false === $options ) {
            $attributes_obj = new Woo_Feed_Default_Attributes();
            $attributes = apply_filters( 'woo_feed_filter_dropdown_attributes', $attributes_obj->googleAttributes(), [ 'snapchat' ] );
            return $this->cache_dropdown( 'snapchatAttributesDropdown', $attributes, $selected );
        }
        return $options;
    }

    /**
     * Dropdown of Google Review Attribute List
     *
     * @param string $selected
     *
     * @return string
     */
    public function googlereviewAttributesDropdown( $selected = '' ) {
        $options = $this->get_cached_dropdown( 'googlereviewAttributesDropdown', $selected );

        if ( false === $options ) {
            $attributes = new Woo_Feed_Default_Attributes();
            return $this->cache_dropdown( 'googlereviewAttributesDropdown', $attributes->googlereviewAttributes(), $selected );
        }
        return $options;
    }

    /**
     * Dropdown of Wine Searcher Attribute List
     *
     * @param string $selected
     *
     * @return string
     */
    public function wine_searcherAttributesDropdown( $selected = '' ) {
        $options = $this->get_cached_dropdown( 'wine_searcherAttributesDropdown', $selected );
        if ( false === $options ) {
            $attributes_obj = new Woo_Feed_Default_Attributes();
            $attributes = apply_filters( 'woo_feed_filter_dropdown_attributes', $attributes_obj->winesearcherAttributes(), [ 'winesearcher' ] );
            return $this->cache_dropdown( 'wine_searcherAttributesDropdown', $attributes, $selected );
        }
        return $options;
    }

    /**
     * Dropdown of TikTok Attribute List
     *
     * @param string $selected
     *
     * @return string
     */
    public function tiktokAttributesDropdown( $selected = '' ) {
        $options = $this->get_cached_dropdown( 'tiktokAttributesDropdown', $selected );
        if ( false === $options ) {
            $attributes_obj = new Woo_Feed_Default_Attributes();
            $attributes = apply_filters( 'woo_feed_filter_dropdown_attributes', $attributes_obj->tiktokAttributes(), [ 'tiktok' ] );
            return $this->cache_dropdown( 'tiktokAttributesDropdown', $attributes, $selected );
        }
        return $options;
    }

    /**
     * Dropdown of Modalova Attribute List
     *
     * @param string $selected
     *
     * @return string
     */
    public function modalovaAttributesDropdown( $selected = '' ) {
        $options = $this->get_cached_dropdown( 'modalovaAttributesDropdown', $selected );
        if ( false === $options ) {
            $attributes_obj = new Woo_Feed_Default_Attributes();
            $attributes = apply_filters( 'woo_feed_filter_dropdown_attributes', $attributes_obj->modalovaAttributes(), [ 'modalova' ] );
            return $this->cache_dropdown( 'modalovaAttributesDropdown', $attributes, $selected );
        }
        return $options;
    }

    /**
     * Dropdown of Catch.com.au Attribute List
     *
     * @param string $selected
     *
     * @return string
     */
    public function catchdotcomAttributesDropdown( $selected = '' ) {
        $options = $this->get_cached_dropdown( 'catchDotComAttributesDropdown', $selected );

        if ( false === $options ) {
            $attributes = new Woo_Feed_Default_Attributes();
            return $this->cache_dropdown( 'catchDotComAttributesDropdown', $attributes->catchdotcomAttributes(), $selected );
        }
        return $options;
    }

    /**
     * Dropdown of Fashionchick.nl attribute List
     *
     * @param string $selected
     *
     * @return string
     */
    public function fashionchickAttributesDropdown( $selected = '' ) {
        $options = $this->get_cached_dropdown( 'fashionchickAttributesDropdown', $selected );

        if ( false === $options ) {
            $attributes = new Woo_Feed_Default_Attributes();
            return $this->cache_dropdown( 'fashionchickAttributesDropdown', $attributes->fashionchickAttributes(), $selected );
        }
        return $options;
    }

    /**
     * Dropdown of GoedGeplaatst.nl attribute List
     *
     * @param string $selected
     *
     * @return string
     */
    public function goedgeplaatstAttributesDropdown( $selected = '' ) {
        $options = $this->get_cached_dropdown( 'goedgeplaatstAttributesDropdown', $selected );

        if ( false === $options ) {
            $attributes = new Woo_Feed_Default_Attributes();
            return $this->cache_dropdown( 'goedgeplaatstAttributesDropdown', $attributes->goedgeplaatstAttributes(), $selected );
        }
        return $options;
    }

    /**
	 * Google Shopping Action Attribute list
	 * Alias of google attribute dropdown for facebook
	 *
	 * @param string $selected
	 *
	 * @return string
	 */
	public function google_shopping_actionAttributesDropdown( $selected = '' ) {
        return $this->googleAttributesDropdown( $selected, [ 'google_shopping_action' ] );
	}


    /**
     * Google Dynamic Ads Attribute list
     *
     * @param string $selected
     *
     * @return string
     */
    public function google_dynamic_adsAttributesDropdown( $selected = '' ) {
        $options = $this->get_cached_dropdown( 'google_dynamic_adsAttributesDropdown', $selected );

        if ( false === $options ) {
            $attributes = new Woo_Feed_Default_Attributes();
            return $this->cache_dropdown( 'google_dynamic_adsAttributesDropdown', $attributes->google_dynamic_adsAttributes(), $selected );
        }
        return $options;
    }
	
	/**
	 * Pinterest Attribute list
	 * Alias of google attribute dropdown for pinterest
	 *
	 * @param string $selected
	 *
	 * @return string
	 */
	public function pinterestAttributesDropdown( $selected = '' ) {
        return $this->googleAttributesDropdown( $selected, [ 'pinterest' ] );
	}

    /**
     * Pinterest Catelog Attribute list
     * Alias of google attribute dropdown for pinterest catelog
     *
     * @param string $selected
     *
     * @return string
     */
    public function pinterest_rssAttributesDropdown( $selected = '' ) {
        $options = $this->get_cached_dropdown( 'pinterest_rssAttributesDropdown', $selected );

        if ( false === $options ) {
            $attributes = new Woo_Feed_Default_Attributes();
            return $this->cache_dropdown( 'pinterest_rssAttributesDropdown', $attributes->pinterest_rssAttributes(), $selected );
        }
        return $options;
    }
	
	/**
	 * AdRoll Attribute list
	 * Alias of google attribute dropdown for AdRoll
	 *
	 * @param string $selected
	 *
	 * @return string
	 */
	public function adrollAttributesDropdown( $selected = '' ) {
        return $this->googleAttributesDropdown( $selected, [ 'adroll' ] );
	}
	
	/**
	 * Skroutz Attribute list
	 *
	 * @param string $selected
	 *
	 * @return string
	 */
	public function skroutzAttributesDropdown( $selected = '' ) {
		$options = $this->get_cached_dropdown( 'skroutzAttributesDropdown', $selected );
		if ( false === $options ) {
			$attributes = new Woo_Feed_Default_Attributes();
			$options = $this->cache_dropdown( 'skroutzAttributesDropdown', $attributes->skroutzAttributes(), $selected );
		}
		return $options;
	}

    /**
     * Best Price Attribute list
     *
     * @param string $selected
     *
     * @return string
     */
    public function bestpriceAttributesDropdown( $selected = '' ) {
        $options = $this->get_cached_dropdown( 'bestpriceAttributesDropdown', $selected );
        if ( false === $options ) {
            $attributes = new Woo_Feed_Default_Attributes();
            $options = $this->cache_dropdown( 'bestpriceAttributesDropdown', $attributes->bestpriceAttributes(), $selected );
        }
        return $options;
    }

	/**
	 * Daisycon Advertiser (General) Attribute list
	 *
	 * @param string $selected
	 *
	 * @return string
	 */
	public function daisyconAttributesDropdown( $selected = '' ) {
		$options = $this->get_cached_dropdown( 'daisycon_AttributesDropdown', $selected );
		if ( false === $options ) {
			$attributes = new Woo_Feed_Default_Attributes();
			$options = $this->cache_dropdown( 'daisycon_AttributesDropdown', $attributes->daisyconAttributes(), $selected );
		}
		return $options;
	}
	
	/**
	 * Daisycon Advertiser (Automotive) Attribute list
	 *
	 * @param string $selected
	 *
	 * @return string
	 */
	public function daisycon_automotiveAttributesDropdown( $selected = '' ) {
		$options = $this->get_cached_dropdown( 'daisycon_automotiveAttributesDropdown', $selected );
		if ( false === $options ) {
			$attributes = new Woo_Feed_Default_Attributes();
			$options = $this->cache_dropdown( 'daisycon_automotiveAttributesDropdown', $attributes->daisycon_automotiveAttributes(), $selected );
		}
		return $options;
	}
	
	/**
	 * Daisycon Advertiser (Books) Attribute list
	 *
	 * @param string $selected
	 *
	 * @return string
	 */
	public function daisycon_booksAttributesDropdown( $selected = '' ) {
		$options = $this->get_cached_dropdown( 'daisycon_booksAttributesDropdown', $selected );
		if ( false === $options ) {
			$attributes = new Woo_Feed_Default_Attributes();
			$options = $this->cache_dropdown( 'daisycon_booksAttributesDropdown', $attributes->daisycon_booksAttributes(), $selected );
		}
		return $options;
	}
	
	/**
	 * Daisycon Advertiser (Cosmetics) Attribute list
	 *
	 * @param string $selected
	 *
	 * @return string
	 */
	public function daisycon_cosmeticsAttributesDropdown( $selected = '' ) {
		$options = $this->get_cached_dropdown( 'daisycon_cosmeticsAttributesDropdown', $selected );
		if ( false === $options ) {
			$attributes = new Woo_Feed_Default_Attributes();
			$options = $this->cache_dropdown( 'daisycon_cosmeticsAttributesDropdown', $attributes->daisycon_cosmeticsAttributes(), $selected );
		}
		return $options;
	}
	
	/**
	 * Daisycon Advertiser (Daily Offers) Attribute list
	 *
	 * @param string $selected
	 *
	 * @return string
	 */
	public function daisycon_daily_offersAttributesDropdown( $selected = '' ) {
		$options = $this->get_cached_dropdown( 'daisycon_daily_offersAttributesDropdown', $selected );
		if ( false === $options ) {
			$attributes = new Woo_Feed_Default_Attributes();
			$options = $this->cache_dropdown( 'daisycon_daily_offersAttributesDropdown', $attributes->daisycon_daily_offersAttributes(), $selected );
		}
		return $options;
	}
	
	/**
	 * Daisycon Advertiser (Electronics) Attribute list
	 *
	 * @param string $selected
	 *
	 * @return string
	 */
	public function daisycon_electronicsAttributesDropdown( $selected = '' ) {
		$options = $this->get_cached_dropdown( 'daisycon_electronicsAttributesDropdown', $selected );
		if ( false === $options ) {
			$attributes = new Woo_Feed_Default_Attributes();
			$options = $this->cache_dropdown( 'daisycon_electronicsAttributesDropdown', $attributes->daisycon_electronicsAttributes(), $selected );
		}
		return $options;
	}
	
	/**
	 * Daisycon Advertiser (Food & Drinks) Attribute list
	 *
	 * @param string $selected
	 *
	 * @return string
	 */
	public function daisycon_food_drinksAttributesDropdown( $selected = '' ) {
		$options = $this->get_cached_dropdown( 'daisycon_food_drinksAttributesDropdown', $selected );
		if ( false === $options ) {
			$attributes = new Woo_Feed_Default_Attributes();
			$options = $this->cache_dropdown( 'daisycon_food_drinksAttributesDropdown', $attributes->daisycon_food_drinksAttributes(), $selected );
		}
		return $options;
	}
	
	/**
	 * Daisycon Advertiser (Home & Garden) Attribute list
	 *
	 * @param string $selected
	 *
	 * @return string
	 */
	public function daisycon_home_gardenAttributesDropdown( $selected = '' ) {
		$options = $this->get_cached_dropdown( 'daisycon_home_gardenAttributesDropdown', $selected );
		if ( false === $options ) {
			$attributes = new Woo_Feed_Default_Attributes();
			$options = $this->cache_dropdown( 'daisycon_home_gardenAttributesDropdown', $attributes->daisycon_home_gardenAttributes(), $selected );
		}
		return $options;
	}
	
	/**
	 * Daisycon Advertiser (Housing) Attribute list
	 *
	 * @param string $selected
	 *
	 * @return string
	 */
	public function daisycon_housingAttributesDropdown( $selected = '' ) {
		$options = $this->get_cached_dropdown( 'daisycon_housingAttributesDropdown', $selected );
		if ( false === $options ) {
			$attributes = new Woo_Feed_Default_Attributes();
			$options = $this->cache_dropdown( 'daisycon_housingAttributesDropdown', $attributes->daisycon_housingAttributes(), $selected );
		}
		return $options;
	}
	
	/**
	 * Daisycon Advertiser (Fashion) Attribute list
	 *
	 * @param string $selected
	 *
	 * @return string
	 */
	public function daisycon_fashionAttributesDropdown( $selected = '' ) {
		$options = $this->get_cached_dropdown( 'daisycon_fashionAttributesDropdown', $selected );
		if ( false === $options ) {
			$attributes = new Woo_Feed_Default_Attributes();
			$options = $this->cache_dropdown( 'daisycon_fashionAttributesDropdown', $attributes->daisycon_fashionAttributes(), $selected );
		}
		return $options;
	}
	
	/**
	 * Daisycon Advertiser (Studies & Trainings) Attribute list
	 *
	 * @param string $selected
	 *
	 * @return string
	 */
	public function daisycon_studies_trainingsAttributesDropdown( $selected = '' ) {
		$options = $this->get_cached_dropdown( 'daisycon_studies_trainingsAttributesDropdown', $selected );
		if ( false === $options ) {
			$attributes = new Woo_Feed_Default_Attributes();
			$options = $this->cache_dropdown( 'daisycon_studies_trainingsAttributesDropdown', $attributes->daisycon_studies_trainingsAttributes(), $selected );
		}
		return $options;
	}
	
	/**
	 * Daisycon Advertiser (Telecom: Accessories) Attribute list
	 *
	 * @param string $selected
	 *
	 * @return string
	 */
	public function daisycon_telecom_accessoriesAttributesDropdown( $selected = '' ) {
		$options = $this->get_cached_dropdown( 'daisycon_telecom_accessoriesAttributesDropdown', $selected );
		if ( false === $options ) {
			$attributes = new Woo_Feed_Default_Attributes();
			$options = $this->cache_dropdown( 'daisycon_telecom_accessoriesAttributesDropdown', $attributes->daisycon_telecom_accessoriesAttributes(), $selected );
		}
		return $options;
	}
	
	/**
	 * Daisycon Advertiser (Telecom: All-in-one) Attribute list
	 *
	 * @param string $selected
	 *
	 * @return string
	 */
	public function daisycon_telecom_all_in_oneAttributesDropdown( $selected = '' ) {
		$options = $this->get_cached_dropdown( 'daisycon_telecom_all_in_oneAttributesDropdown', $selected );
		if ( false === $options ) {
			$attributes = new Woo_Feed_Default_Attributes();
			$options = $this->cache_dropdown( 'daisycon_telecom_all_in_oneAttributesDropdown', $attributes->daisycon_telecom_all_in_oneAttributes(), $selected );
		}
		return $options;
	}
	
	/**
	 * Daisycon Advertiser (Telecom: GSM + Subscription) Attribute list
	 *
	 * @param string $selected
	 *
	 * @return string
	 */
	public function daisycon_telecom_gsm_subscriptionAttributesDropdown( $selected = '' ) {
		$options = $this->get_cached_dropdown( 'daisycon_telecom_gsm_subscriptionAttributesDropdown', $selected );
		if ( false === $options ) {
			$attributes = new Woo_Feed_Default_Attributes();
			$options = $this->cache_dropdown( 'daisycon_telecom_gsm_subscriptionAttributesDropdown', $attributes->daisycon_telecom_gsm_subscriptionAttributes(), $selected );
		}
		return $options;
	}
	
	/**
	 * Daisycon Advertiser (Telecom: GSM only) Attribute list
	 *
	 * @param string $selected
	 *
	 * @return string
	 */
	public function daisycon_telecom_gsmAttributesDropdown( $selected = '' ) {
		$options = $this->get_cached_dropdown( 'daisycon_telecom_gsmAttributesDropdown', $selected );
		if ( false === $options ) {
			$attributes = new Woo_Feed_Default_Attributes();
			$options = $this->cache_dropdown( 'daisycon_telecom_gsmAttributesDropdown', $attributes->daisycon_telecom_gsmAttributes(), $selected );
		}
		return $options;
	}
	
	/**
	 * Daisycon Advertiser (Telecom: Sim only) Attribute list
	 *
	 * @param string $selected
	 *
	 * @return string
	 */
	public function daisycon_telecom_simAttributesDropdown( $selected = '' ) {
		$options = $this->get_cached_dropdown( 'daisycon_telecom_simAttributesDropdown', $selected );
		if ( false === $options ) {
			$attributes = new Woo_Feed_Default_Attributes();
			$options = $this->cache_dropdown( 'daisycon_telecom_simAttributesDropdown', $attributes->daisycon_telecom_simAttributes(), $selected );
		}
		return $options;
	}
	
	/**
	 * Daisycon Advertiser (Magazines) Attribute list
	 *
	 * @param string $selected
	 *
	 * @return string
	 */
	public function daisycon_magazinesAttributesDropdown( $selected = '' ) {
		$options = $this->get_cached_dropdown( 'daisycon_magazinesAttributesDropdown', $selected );
		if ( false === $options ) {
			$attributes = new Woo_Feed_Default_Attributes();
			$options = $this->cache_dropdown( 'daisycon_magazinesAttributesDropdown', $attributes->daisycon_magazinesAttributes(), $selected );
		}
		return $options;
	}
	
	/**
	 * Daisycon Advertiser (Holidays: Accommodations) Attribute list
	 *
	 * @param string $selected
	 *
	 * @return string
	 */
	public function daisycon_holidays_accommodationsAttributesDropdown( $selected = '' ) {
		$options = $this->get_cached_dropdown( 'daisycon_holidays_accommodationsAttributesDropdown', $selected );
		if ( false === $options ) {
			$attributes = new Woo_Feed_Default_Attributes();
			$options = $this->cache_dropdown( 'daisycon_holidays_accommodationsAttributesDropdown', $attributes->daisycon_holidays_accommodationsAttributes(), $selected );
		}
		return $options;
	}
	
	/**
	 * Daisycon Advertiser (Holidays: Accommodations and transport) Attribute list
	 *
	 * @param string $selected
	 *
	 * @return string
	 */
	public function daisycon_holidays_accommodations_and_transportAttributesDropdown( $selected = '' ) {
		$options = $this->get_cached_dropdown( 'daisycon_holidays_accommodations_and_transportAttributesDropdown', $selected );
		if ( false === $options ) {
			$attributes = new Woo_Feed_Default_Attributes();
			$options = $this->cache_dropdown( 'daisycon_holidays_accommodations_and_transportAttributesDropdown', $attributes->daisycon_holidays_accommodations_and_transportAttributes(), $selected );
		}
		return $options;
	}
	
	/**
	 * Daisycon Advertiser (Holidays: Trips) Attribute list
	 *
	 * @param string $selected
	 *
	 * @return string
	 */
	public function daisycon_holidays_tripsAttributesDropdown( $selected = '' ) {
		$options = $this->get_cached_dropdown( 'daisycon_holidays_tripsAttributesDropdown', $selected );
		if ( false === $options ) {
			$attributes = new Woo_Feed_Default_Attributes();
			$options = $this->cache_dropdown( 'daisycon_holidays_tripsAttributesDropdown', $attributes->daisycon_holidays_tripsAttributes(), $selected );
		}
		return $options;
	}
	
	/**
	 * Daisycon Advertiser (Work & Jobs) Attribute list
	 *
	 * @param string $selected
	 *
	 * @return string
	 */
	public function daisycon_work_jobsAttributesDropdown( $selected = '' ) {
		$options = $this->get_cached_dropdown( 'daisycon_work_jobsAttributesDropdown', $selected );
		if ( false === $options ) {
			$attributes = new Woo_Feed_Default_Attributes();
			$options = $this->cache_dropdown( 'daisycon_work_jobsAttributesDropdown', $attributes->daisycon_work_jobsAttributes(), $selected );
		}
		return $options;
	}
}
