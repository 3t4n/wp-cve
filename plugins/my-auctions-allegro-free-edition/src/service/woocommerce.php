<?php
/**
 * My Auctions Allegro
 * @Author Luke Grochal (Grojan Team)
 * @Author URI https://grojanteam.pl
 */

defined('ABSPATH') or die;

class GJMAA_Service_Woocommerce {

	protected $_isEnabled;

	protected $_wooCommerceMeta = [
		'_visibility'         => 'visible',
		'_stock_status'       => 'instock',
		'total_sales'         => '0',
		'_downloadable'       => 'no',
		'_virtual'            => 'no',
		'_featured'           => 'no',
		'_sku'                => 'sku',
		'_price'              => 'price',
		'_regular_price'      => 'price',
		'_manage_stock'       => 'yes',
		'_backorders'         => 'no',
		'_stock'              => 'quantity',
		'_allegro'            => 'yes',
		'_allegro_auction_id' => 'itId'
	];

	protected $thumbnailsToRemove = [
		'100x100',
		'150x150',
		'300x225',
		'324x324',
		'416x312',
		'768x576',
		'1024x768'
	];

	protected $settingId;

	protected $settings;

	protected $profile;

	protected $oosDecision = [];

	protected $_update = [
		'_stock',
		'_stock_status',
		'_allegro_auction_id'
	];

	public function isEnabled() {
		if ( null === $this->_isEnabled ) {
			if ( function_exists('is_plugin_active') ) {
				$this->_isEnabled = is_plugin_active('woocommerce/woocommerce.php');
			} else {
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

				$this->_isEnabled = is_plugin_active('woocommerce/woocommerce.php');
			}
		}

		return $this->_isEnabled;
	}

	public function setSettingId( $settingId ) {
		$this->settingId = $settingId;
	}

	public function setSettings( $settings ) {
		$this->settings = $settings;

		return $this;
	}

	public function getSettingId() {
		return $this->settingId;
	}

	public function getSettings() {
		return $this->settings;
	}

	public function setProfile( $profile ) {
		$this->profile = $profile;

		return $this;
	}

	public function getProfile() {
		return $this->profile;
	}

	public function saveProducts( $auctionDetails, $newMethod = false ) {
		$productIds = [];

		ini_set('max_execution_time', 240);

		foreach ( $auctionDetails as $auction ) {
			if ( empty($auction) ) {
				continue;
			}

			$auctionId = $newMethod ? $auction[ 'id' ] : $auction->itemInfo->itId;
			$productId = $this->addProduct($auction, $newMethod);

			$this->saveFlagForAddedAuction($auctionId, $productId);
			if ( $productId !== 0 ) {
				$productIds[ $auctionId ] = $productId;
			}
		}

		if ( ! empty($productIds) ) {
			if ( function_exists('wc_update_product_lookup_tables_is_running') ) {
				if ( ! wc_update_product_lookup_tables_is_running() ) {
					wc_update_product_lookup_tables();
				}
			}
		}

		return $productIds;
	}

	public function addProduct( $allegroProduct, $newMethod = false ) {
		if ( $newMethod ) {
			$lastCategoryId = $allegroProduct[ 'categories' ];
			/** @var GJMAA_Service_Categories $categoryService */
			$categoryService = GJMAA::getService('categories');
			$categoryService->setSettings($this->getSettings());
			$categories = $categoryService->getFullTreeForCategory($lastCategoryId);

			$categories = array_reverse($categories, true);

			$attributes = $allegroProduct[ 'attributes' ];
			$post       = $this->getProductIdOrCreate($allegroProduct, $newMethod);
			$post_id    = $post[ 'postId' ];
			if ( $post_id === 0 ) {
				return 0;
			}
			$media = [];

			if ( isset($allegroProduct[ 'images' ]) ) {
				$media = $this->getProductImage($allegroProduct[ 'images' ], true);
			}

			foreach ( $this->_wooCommerceMeta as $meta_key => $meta_value ) {
				$originalValue = get_post_meta($post_id, $meta_key, true);
				switch ( $meta_key ) :
					case '_sku':
						$meta_value = $allegroProduct[ 'external_id' ] ?? $allegroProduct[ 'id' ];
						break;
					case '_price':
					case '_regular_price':
						if ( $this->getProfile()->getData('profile_sync_price') ) {
							$meta_value = $allegroProduct[ 'price' ];
							$meta_value = str_replace(',', '.', $meta_value);
						} else {
							$meta_value = $originalValue;
						}
						break;
					case '_stock':
						$meta_value = $this->getProfile()->getData('profile_sync_stock') ? $allegroProduct[ 'stock' ] : $originalValue;
						break;
					case '_stock_status':
						$meta_value = $this->getProfile()->getData('profile_sync_stock') ? ( $allegroProduct[ 'stock' ] > 0 ? 'instock' : 'outofstock' ) : $originalValue;
						break;
					case '_allegro_auction_id':
						if ( ! empty($originalValue) ) {
							$auctionIds = explode(',', $originalValue);
							if ( ! in_array($allegroProduct[ 'id' ], $auctionIds) ) {
								$auctionIds[] = $allegroProduct[ 'id' ];
							}

							$meta_value = implode(',', $auctionIds);
						} else {
							$meta_value = $allegroProduct[ 'id' ];
						}
						break;
				endswitch;

				if ( $post[ 'new' ] || in_array($meta_key, $this->_update) ) {
					update_post_meta($post_id, $meta_key, $meta_value);
				}
			}

			if ( ! $post[ 'new' ] ) {
				$wooCommerceFields = $this->getProfile()->getData('profile_sync_woocommerce_fields');
				if ( is_string($wooCommerceFields) ) {
					$wooCommerceFields = explode(',', $wooCommerceFields);
				}

				if ( is_array($wooCommerceFields) ) {
					if ( in_array('post_title', $wooCommerceFields) ) {
						$this->updatePostTitle($post_id, $allegroProduct[ 'name' ]);
					}

					if ( in_array('post_content', $wooCommerceFields) ) {
						$this->updatePostContent($post_id, $allegroProduct[ 'description' ], $allegroProduct[ 'id' ]);
					}

					if ( in_array('post_attributes', $wooCommerceFields) ) {
						$this->assignAttributes($allegroProduct, $attributes, $post_id, $lastCategoryId, $newMethod);
					}

					if ( in_array('post_categories', $wooCommerceFields) ) {
						$this->assignCategories($categories, $post_id, $newMethod);
					}

					if ( in_array('post_thumbnail', $wooCommerceFields) ) {
						$this->updateProductThumbnail($allegroProduct[ 'id' ], $media, $post_id, $newMethod);
					}

					if ( in_array('post_media', $wooCommerceFields) ) {
						$this->updateProductGallery($allegroProduct[ 'id' ], $media, $post_id, $newMethod);
					}
				}
			} else {
				$this->assignAttributes($allegroProduct, $attributes, $post_id, $lastCategoryId, $newMethod);
				$this->assignCategories($categories, $post_id, $newMethod);
				$this->assignProductThumbnail($allegroProduct[ 'id' ], $media, $post_id, $newMethod);
				$this->assignProductGallery($allegroProduct[ 'id' ], $media, $post_id, $newMethod);
			}
		} else {
			$categories = $allegroProduct->itemCats->item;
			$attributes = $allegroProduct->itemAttribs->item;
			$product    = $allegroProduct->itemInfo;
			$auctionId  = $product->itId;
			$post       = $this->getProductIdOrCreate($allegroProduct);
			$post_id    = $post[ 'postId' ];
			$media      = [];

			if ( isset($allegroProduct->itemImages->item) ) {
				$media = $this->getProductImage($allegroProduct->itemImages->item);
			}

			foreach ( $this->_wooCommerceMeta as $meta_key => $meta_value ) {
				$originalValue = get_post_meta($post_id, $meta_key, true);
				switch ( $meta_key ) :
					case '_sku':
						$meta_value = ( isset($allegroProduct->itEan) ? $allegroProduct->itEan : $auctionId );
						break;
					case '_price':
					case '_regular_price':
						$meta_value = ( $product->itBuyNowActive ? $product->itBuyNowPrice : $product->itPrice );
						$meta_value = str_replace(',', '.', $meta_value);
						break;
					case '_stock':
						$meta_value = $product->itQuantity;
						break;
					case '_stock_status':
						$meta_value = $product->itQuantity > 0 ? 'instock' : 'outofstock';
						break;
					case '_allegro_auction_id':
						if ( ! empty($originalValue) ) {
							$auctionIds = explode(',', $originalValue);
							if ( ! in_array($auctionId, $auctionIds) ) {
								$auctionIds[] = $auctionId;
							}

							$meta_value = implode(',', $auctionIds);
						} else {
							$meta_value = $auctionId;
						}
						break;
				endswitch;

				if ( $post[ 'new' ] || in_array($meta_key, $this->_update) ) {
					update_post_meta($post_id, $meta_key, $meta_value);
				}
			}

			if ( $post[ 'new' ] ) {
				$this->assignCategories($categories, $post_id);
				$this->assignProductThumbnail($auctionId, $media, $post_id);
				$this->assignProductGallery($auctionId, $media, $post_id);
				$this->assignAttributes($product, $attributes, $post_id, $categories);
			}
		}

		$this->assignAdditionalData($post_id, $this->getSettingId(), $allegroProduct);

		return $post_id;
	}

	public function getPriceData( $auction ) {
		$cPrice = null;
		$prices = isset($auction->priceInfo) ? $auction->priceInfo->item : $auction->itemPrice;
		foreach ( $prices as $price ) {
			if ( $price->priceType == 'buyNow' || $price->priceType == 1 ) {
				$cPrice = (float) $price->priceValue;
				break;
			} else {
				$cPrice = (float) $price->priceValue;
			}
		}

		return $cPrice;
	}

    public function getProductImage($images, $newMethod = false)
    {
        $images = is_array($images) ? $images : [$images];
        $media = [];

        if ($newMethod) {
           $media = $images;
        } else {
            foreach ($images as $image) {
                if (!isset($media[$image->imageType])) {
                    $media[$image->imageType] = [];
                }
                $media[$image->imageType][] = $image->imageUrl;
            }
        }

        return $media;
    }

	public function get_attach_id_by_filealt_and_type( $filealt, $type ) {
		global $wpdb;

		$filename = str_replace(' ', '_', $filealt);
		$guid     = sprintf('%s/wp-content/uploads/allegro/%d/%d/%s_%s.jpg', get_option('siteurl'), date('Y'), date('m'), $filename, $type);

		$query = sprintf("SELECT ID FROM %s WHERE guid='%s'", $wpdb->posts, $guid);

		return $wpdb->get_var($query);
	}

	public function attach_image( $fileurl, $filealt, $type, $post_id = 0, $download = true ) {
		$filename = str_replace(' ', '_', $filealt); // Get the filename including extension from the $fileurl e.g. myimage.jpg
		if ( $download ) {
			$destination = sprintf('%s/uploads/allegro/%d/%d', WP_CONTENT_DIR, date('Y'), date('m')); // Specify where we wish to upload the file, generally in the wp uploads directory
			if ( ! is_dir($destination) ) {
				mkdir($destination, 0777, true);
			}

			$destinationPath = $destination . '/' . $filename . '_' . $type . '.jpg';
			copy($fileurl, $destinationPath);
			$filetype  = wp_check_filetype($destinationPath); // Get the mime type of the file
			$mime_type = $filetype[ 'type' ];
			$guid      = sprintf('%s/wp-content/uploads/allegro/%d/%d/%s_%s.jpg', get_option('siteurl'), date('Y'), date('m'), $filename, $type);
		} else {
			$guid            = $fileurl;
			$finfo           = new finfo(FILEINFO_MIME_TYPE);
			$mime_type       = $finfo->buffer(file_get_contents($fileurl));
			$filetype        = wp_check_filetype($fileurl);
			$destinationPath = $fileurl;
		}

		$attachment = array( // Set up our images post data
			'guid'           => $guid,
			'post_mime_type' => $mime_type,
			'post_title'     => $filename . '_' . $type . '.jpg',
			'post_author'    => 1,
			'post_content'   => ''
		);

		$attach_id = wp_insert_attachment($attachment, $destinationPath, $post_id); // Attach/upload image to the specified post id, think of this as adding a new post.
		add_post_meta($attach_id, '_wp_attachment_image_alt', $filealt); // Add the alt text to our new image post
		add_post_meta($attach_id, '_allegro_url', $fileurl);

		$this->addAttachmentToRegenerate($attach_id, $destinationPath);

		return $attach_id; // Return the images id to use in the below functions
	}

	public function getProductIdOrCreate( $product, $newMethod = false ) {
		if ( $newMethod ) {
			$auctionId         = $product[ 'id' ];
			$auctionExternalId = $product[ 'external_id' ];
		} else {
			$auctionId         = $product->itemInfo->itId;
			$auctionExternalId = $product->itemInfo->itEan;
		}

		if ( ! is_null($auctionExternalId) ) {
			$auctionExternalId = (string) $auctionExternalId;
		}

		$postId    = $this->getProductIdByAuctionId($auctionId, $auctionExternalId);
		$new       = false;
		$canAddNew = $this->getProfile()->getData('profile_import_new_flag');

		if ( 0 === $postId && $canAddNew ) {
			if ( $newMethod ) {
				$slugNew  = sanitize_title($product[ 'name' ] . '-' . $product[ 'id' ]);
				$postData = array(
					'post_title'   => $product[ 'name' ],
					'post_content' => $this->prepareDescription($product[ 'description' ], $product[ 'id' ]),
					'post_status'  => $product[ 'status' ] == 'ACTIVE' ? 'publish' : 'draft',
					'post_type'    => "product",
					'post_name'    => $slugNew
				);
			} else {
				$slugNew  = sanitize_title($product->itemInfo->itName) . '-' . $product->itemInfo->itId;
				$postData = array(
					'post_title'   => $product->itemInfo->itName,
					'post_content' => $this->stripTags($product->itemInfo->itDescription),
					'post_status'  => 'publish',
					'post_type'    => "product",
					'post_name'    => $slugNew
				);
			}

			$postId = wp_insert_post($postData);
			$new    = true;
		}

		return [
			'postId' => $postId,
			'new'    => $new
		];
	}

	public function assignCategories( $categories, $product_id, $newMethod = false ) {
		$categoriesWooIds = [];
		$categoriesWooIds = apply_filters('gjmaa_service_woocommerce_before_create_categories_filters', $categories, $newMethod);
		if ( $categories === $categoriesWooIds || empty($categoriesWooIds) ) {
			$categoriesWooIds = $this->addNewCategories($categories, $newMethod);
		}

		do_action('gjmaa_service_woocommerce_after_create_categories', $categoriesWooIds[ 'map_woocommerce_category_ids' ]);

		wp_set_object_terms($product_id, $categoriesWooIds[ 'woocommerce_category_ids' ], 'product_cat');
	}

	public function addNewCategories( $categories, $newMethod = false ) {
		$categoriesId = [];

		$args = array(
			'taxonomy' => 'product_cat'
		);

		$index                    = 0;
		$wooCommerceCategoryLevel = $this->getProfile()->getData('profile_save_woocommerce_category_level') ?? 0;
		$map                      = [];

		foreach ( $categories as $category ) {
			if ( $wooCommerceCategoryLevel > 0 ) {
				$wooCommerceCategoryLevel--;
				continue;
			}

			if ( ! $newMethod ) {
				$allegroCategoryId = $category->catId;
				$parent            = $category->catLevel > 0 ? $categoriesId[ $category->catLevel - 1 ] : 0;
				$slug              = sanitize_title($category->catName);
				$name              = $category->catName;
				$level             = $category->catLevel;
			} else {
				$allegroCategoryId = $category[ 'category_id' ];
				$parent            = $index > 0 ? $categoriesId[ $index - 1 ] : 0;
				$slug              = sanitize_title($category[ 'name' ]);
				$name              = $category[ 'name' ];
				$level             = $index;
			}

			$args[ 'parent' ]      = $parent;
			$args[ 'description' ] = $name;

			$term = $this->getWooCommerceProductCategory($args);

			if ( empty($term) ) {
				$term = wp_insert_term($name, 'product_cat', [
					'description' => $name,
					'slug'        => $slug,
					'parent'      => $parent
				]);

				if ( is_wp_error($term) ) {
					$term_id = $term->error_data[ 'term_exists' ] ?? null;
				} else {
					$term_id = $term[ 'term_id' ];
				}
			} else {
				$term_id = $term[ 'term_id' ];
			}

			if ( null !== $term_id ) {
				$categoriesId[ $level ] = (int) $term_id;
				$map[ $term_id ]        = $allegroCategoryId;
			}
			$index++;
		}

		return [
			'woocommerce_category_ids'     => $categoriesId,
			'map_woocommerce_category_ids' => $map
		];
	}

	private function getWooCommerceProductCategory( array $args ) {
		global $wpdb;

		$termTaxonomyTable = sprintf('%s%s', $wpdb->prefix, 'term_taxonomy');

		$query = "SELECT term_id FROM %s WHERE taxonomy='%s' AND parent=%d AND description='%s'";

		return $wpdb->get_row(sprintf($query, $termTaxonomyTable, $args[ 'taxonomy' ], $args[ 'parent' ], $args[ 'description' ]), ARRAY_A);
	}

	public function assignProductThumbnail( $auctionId, $media, $product_id, bool $newMethod = false ): void {
		$attachment_ids = null;
		foreach ( $media as $type => $image ) {
			if ( ! $newMethod && $type === 3 ) {
				$attachment_ids = $this->attach_image($image[ 0 ], $auctionId, 0, $product_id);
				break;
			} else {
				$attachment_ids = $this->attach_image($image, $auctionId, 0, $product_id);
				break;
			}
		}

		if ( $attachment_ids ) {
			set_post_thumbnail($product_id, $attachment_ids);
		}
	}

	private function updateProductThumbnail( $auctionId, $media, $product_id, bool $newMethod = false ): void {
		$thumbnailId = get_post_thumbnail_id($product_id);
		if ( $thumbnailId ) {
			$this->deleteProductThumbnail($thumbnailId, $product_id);
		}

		$this->assignProductThumbnail($auctionId, $media, $product_id, $newMethod);
	}

	private function deleteProductThumbnail( $thumbnailId, $productId ): void {
		delete_post_thumbnail($productId);

		if ( $this->thumbnailIdIsUsedInOtherProducts($thumbnailId, $productId) ) {
			return;
		}

		wp_delete_attachment($thumbnailId);
	}

	private function thumbnailIdIsUsedInOtherProducts( $thumbnailId, $productId ): bool {
		global $wpdb;

		$pattern = "SELECT COUNT(meta_id) FROM wp_postmeta WHERE meta_key = '_thumbnail_id' AND meta_value = %d AND post_id NOT IN (%d)";

		$query = sprintf($pattern, $thumbnailId, $productId);

		$count = (int) $wpdb->get_var($query);

		return $count > 0;
	}

	public function assignProductGallery( $auctionId, $media, $product_id, $newMethod = false ) {
		$product_images = [];
		if ( ! $newMethod ) {
			foreach ( $media[ 3 ] as $type => $image ) {
				if ( $type > 0 ) {
					$product_images[] = $this->attach_image($image, $auctionId, $type, $product_id);
				}
			}
		} else {
			foreach ( $media as $index => $image ) {
				if ( $index == 0 ) {
					continue;
				}

				$product_images[] = $this->attach_image($image, $auctionId, $index, $product_id);
			}
		}

		if ( $product_images ) {
			update_post_meta($product_id, '_product_image_gallery', implode(',', $product_images));
		}
	}

	public function updateProductGallery( $auctionId, $media, $product_id, $newMethod = false ) {
		$product = wc_get_product($product_id);
		if ( $product instanceof WC_Product ) {
			$galleryIds = $product->get_gallery_image_ids();
			if ( ! empty($galleryIds) ) {
				$this->deleteProductGallery($galleryIds, $product);
			}
		}

		$this->assignProductGallery($auctionId, $media, $product_id, $newMethod);
	}

	private function deleteProductGallery( array $galleryIds, WC_Product $product ) {
		$product->set_gallery_image_ids([]);
		$product->save();

		array_walk($galleryIds, function ( $attachmentId ) use ( $product ) {
			if ( ! $this->attachmentIdIsUsedInOtherProducts($attachmentId, $product->get_id()) ) {
				wp_delete_attachment($attachmentId);
			}
		});
	}

	private function attachmentIdIsUsedInOtherProducts( $attachId, $productId ): bool {
		global $wpdb;

		$pattern = "SELECT COUNT(meta_id) FROM wp_postmeta WHERE meta_key = '_product_image_gallery' AND meta_value LIKE '%s' AND post_id NOT IN (%d)";

		$query = sprintf($pattern, '%' . $attachId . '%', $productId);

		$count = (int) $wpdb->get_var($query);

		return $count > 0;
	}

	public function assignAttributes( $product, $attributes, $product_id, $category, $newMethod = false ) {
		if ( $newMethod ) {
			/** @var GJMAA_Source_Allegro_Attribute $allegroAttributeService */
			$allegroAttributeService = GJMAA::getSource('allegro_attribute');
			$allegroAttributeDetails = $allegroAttributeService->setSettings($this->getSettings())->getOptions([ 'category_id' => $category ]);
		}

		$wooCommerceAttributes = [];

		$attributes = is_array($attributes) ? $attributes : [
			$attributes
		];

		foreach ( $attributes as $attribute ) {
			if ( $newMethod ) {
				$attributeId      = (int) $attribute[ 'id' ];
				$allegroAttribute = $allegroAttributeDetails[ $attributeId ] ?? false;
				if ( ! $allegroAttribute ) {
					continue;
				}
				$attributeName           = $allegroAttribute[ 'attribute_name' ];
				$attributeValue          = null;
				$index                   = sanitize_title($attributeName);
				$allegroAttributeOptions = null;
				$attributeRestrictions   = json_decode($allegroAttribute[ 'attribute_restrictions' ], true);
				$multipleChoices         = $attributeRestrictions[ 'multipleChoices' ] ?? false;
				switch ( $allegroAttribute[ 'attribute_type' ] ) {
					case 'dictionary':
						$isDictionary            = true;
						$currentValue            = $multipleChoices ? $attribute[ 'valuesIds' ] : $attribute[ 'valuesIds' ][ 0 ];
						$attributeOptions        = json_decode($allegroAttribute[ 'attribute_dictionary' ], true);
						$attributeValue          = [];
						$allegroAttributeOptions = [];
						foreach ( $attributeOptions as $attribute_option ) {
							if ( ! is_array($currentValue) ) {
								if ( $attribute_option[ 'id' ] == $currentValue ) {
									$attributeValue                                          = $attribute_option[ 'value' ];
									$allegroAttributeOptions[ $attribute_option[ 'value' ] ] = $currentValue;
									break;
								}
							} else {
								foreach ( $currentValue as $currentAllegroValue ) {
									if ( $attribute_option[ 'id' ] == $currentAllegroValue ) {
										$attributeValue[]                                        = $attribute_option[ 'value' ];
										$allegroAttributeOptions[ $attribute_option[ 'value' ] ] = $currentAllegroValue;
										break;
									}
								}
							}
						}
						break;
					default:
						$isDictionary   = false;
						$attributeValue = $attribute[ 'values' ];
						break;
				}
			} else {
				$attributeId             = null;
				$allegroAttributeOptions = [];
				$attributeName           = $attribute->attribName;
				$values                  = $attribute->attribValues->item;
				$attributeValue          = is_array($values) ? implode(',', $values) : $values;
				$index                   = sanitize_title($attributeName);
				$isDictionary            = false;
			}


			$wooCommerceAttributes[ $index ] = [
				'name'                         => $attributeName,
				'value'                        => is_array($attributeValue) ? implode(',', $attributeValue) : $attributeValue,
				'allegro_attribute_id'         => $attributeId,
				'allegro_attribute_option_ids' => $allegroAttributeOptions,
				'is_dictionary'                => $isDictionary,
				'is_visible'                   => 1,
				'is_taxonomy'                  => 0
			];
		}

		if ( $newMethod ) {
			$wooCommerceAttributes = apply_filters('gjmaa_service_woocommerce_before_create_attributes_filters', $wooCommerceAttributes, $category, $product_id, $allegroAttributeDetails);
		} else {
			$wooCommerceAttributes = apply_filters('gjmaa_service_woocommerce_before_create_attributes_filters', $wooCommerceAttributes, $category, $product_id);
		}
		update_post_meta($product_id, '_product_attributes', $wooCommerceAttributes);
	}

	public function assignAdditionalData( $productId, $settingId, $allegroProduct ) {
		do_action('gjmaa_service_woocommerce_assign_additional_data', $productId, $settingId, $allegroProduct);
	}

	public function stripTags( $description ) {
		$description = preg_replace(array(
			// Remove invisible content
			'@<head[^>]*?>.*?</head>@siu',
			'@<style[^>]*?>.*?</style>@siu',
			'@<script[^>]*?.*?</script>@siu',
			'@<object[^>]*?.*?</object>@siu',
			'@<embed[^>]*?.*?</embed>@siu',
			'@<applet[^>]*?.*?</applet>@siu',
			'@<noframes[^>]*?.*?</noframes>@siu',
			'@<noscript[^>]*?.*?</noscript>@siu',
			'@<noembed[^>]*?.*?</noembed>@siu'
			// Add line breaks before and after blocks
		), array(
			' ',
			' ',
			' ',
			' ',
			' ',
			' ',
			' ',
			' ',
			' '
		), $description);

		return strip_tags($description, '<p><img><a><h1><h2><h3><ul><li><ol><table><tbody><thead><tr><th><td>');
	}

	public function getCategories( $parentId = 0 ) {
		$args = array(
			'hierarchical'     => 1,
			'show_option_none' => '',
			'hide_empty'       => 0,
			'parent'           => $parentId,
			'taxonomy'         => 'product_cat'
		);

		$select          = [];
		$currentCategory = false;
		if ( $parentId ) {
			$currentCategory = get_term_by('id', $parentId, 'product_cat');
		}

		$categories = get_categories($args);

		if ( $currentCategory ) {
			$select[ $currentCategory->parent ]  = ' <= ' . __('Back', 'my-auctions-allegro-free-edition');
			$select[ $currentCategory->term_id ] = $currentCategory->name;
		}

		foreach ( $categories as $category ) {
			$select[ $category->term_id ] = $currentCategory ? '-- ' . $category->name : $category->name;
		}

		return $select;
	}

	public function getProductIdByAuctionId( $auctionId, ?string $auctionExternalId = null ) {
		$postId = apply_filters('gjmaa_get_product_id_by_auction_id', $auctionId, $auctionExternalId);
		if ( $postId != $auctionId && $postId !== 0 ) {
			return $postId;
		}

		return $this->get_product_by_sku($auctionId, $auctionExternalId);
	}

	public function get_product_by_sku( $auctionId, ?string $auctionExternalId = null ) {
		global $wpdb;

		$linkId = $auctionId;
		if($this->getProfile()->getData('profile_link_by_signature')) {
			$linkId = ( ! is_null($auctionExternalId) && ! empty(trim($auctionExternalId)) ? trim($auctionExternalId) : $auctionId );
		}

		$product_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $linkId));

		return $product_id ?: 0;
	}

	public function outofstock() {
		return (int) get_option('woocommerce_notify_no_stock_amount', 0);
	}

	public function updatePrices( $priceUpdateData ) {
		foreach ( $priceUpdateData as $auctionId => $auctionData ) {
			$productId = $auctionData[ 'product_id' ];

			$price = $auctionData[ 'price' ];
			$price = str_replace(',', '.', $price);

			error_log(sprintf('[%s] Updating product price (%s) from auction (%s) with ID (%d)', 'WOOCOMMERCE PRICE', $price, $auctionId, $productId));

			update_post_meta($productId, '_price', $price);
			update_post_meta($productId, '_regular_price', $price);
		}
	}

	public function updateStock( $stockData ) {
		/** @var GJMAA_Source_Oosdecision $oosDecision */
		$oosDecision = GJMAA::getSource('oosdecision');

		foreach ( array_chunk($stockData, 50, true) as $stockChunked ) {
			$productsToRemove = [];
			foreach ( $stockChunked as $auctionId => $auctionData ) {
				$productId = $auctionData[ 'product_id' ];
				if ( ! $productId ) {
					continue;
				}

				if ( $auctionData[ 'is_in_stock' ] ) {
					update_post_meta($productId, '_stock', $auctionData[ 'quantity' ]);
					update_post_meta($productId, '_stock_status', 'instock');
				} else {
					if ( ! isset($this->oosDecision[ $auctionData[ 'profile_id' ] ]) ) {
						$this->oosDecision[ $auctionData[ 'profile_id' ] ] = GJMAA::getModel('settings')->getProductDecisionByProfileId($auctionData[ 'profile_id' ]);
					}

					$canUpdate = $this->oosDecision[ $auctionData[ 'profile_id' ] ];
					if ( ! $canUpdate ) {
						continue;
					}

					if ( $canUpdate == $oosDecision::UPDATE_TO_OUT_OF_STOCK ) {
						update_post_meta($productId, '_stock', $auctionData[ 'quantity' ]);
						update_post_meta($productId, '_stock_status', 'outofstock');

						do_action('gjmaa_change_stock_to_out_of_stock', $productId);
					} elseif ( $canUpdate == $oosDecision::REMOVE_PRODUCT_FROM_WOOCOMMERCE ) {
						$productsToRemove[] = $productId;
					}
				}
			}

			if ( ! empty($productsToRemove) ) {
				global $wpdb;

				$attachmentsPattern = "SELECT * FROM %s WHERE post_parent IN (%s) AND post_type = '%s'";
				$postsTable         = $wpdb->prefix . 'posts';

				$attachmentsQuery = sprintf($attachmentsPattern, $postsTable, implode(',', $productsToRemove), 'attachment');
				$attachments      = $wpdb->get_results($attachmentsQuery, ARRAY_A);
				if ( ! empty($attachments) ) {
					array_walk($attachments, function ( $attachment ) {
						$attachId = $attachment[ 'ID' ];
						wp_delete_attachment($attachId, true);
					});
				}

				array_walk($productsToRemove, function ( $productId ) {
					do_action('gjmaa_delete_product_on_closed_auction', $productId);
					wp_delete_post($productId, true);
				});
			}
		}
	}

	public function productsToRemove() {
		global $wpdb;

		$allExistingPosts = "SELECT ID FROM {$wpdb->prefix}posts WHERE post_type = 'product'";
		$patternSelect    = "SELECT * FROM %s WHERE post_type = 'attachment' AND post_parent IN (%s)";
		$table            = "{$wpdb->prefix}posts";
		$select           = sprintf($patternSelect, $table, $allExistingPosts);
		$attachments      = $wpdb->get_results($select, ARRAY_A);

		$productsToRemove = [];
		foreach ( $attachments as $attachment ) {
			$attachId  = $attachment[ 'ID' ];
			$productId = $attachment[ 'post_parent' ];
			$path      = get_post_meta($attachId, '_wp_attached_file', true);
			if ( strpos($path, 'allegro') !== false ) {
				$uploadDir    = wp_get_upload_dir();
				$basedir      = $uploadDir[ 'basedir' ];
				$pathToRemove = $basedir . '/' . $path;

				if ( ! file_exists($pathToRemove) ) {
					$productsToRemove[] = $productId;
				}
			}
		}

		$productsToRemove = array_unique($productsToRemove);
		if ( empty($productsToRemove) ) {
			return;
		}

		$queryPattern     = "DELETE FROM %s WHERE ID IN (%s) AND post_type = '%s'";
		$queryPatternMeta = "DELETE FROM %s WHERE post_id IN (%s)";

		$postsTable       = $wpdb->prefix . 'posts';
		$postsMetaTable   = $wpdb->prefix . 'postmeta';
		$productQuery     = sprintf($queryPattern, $postsTable, implode(',', $productsToRemove), 'product');
		$productMetaQuery = sprintf($queryPatternMeta, $postsMetaTable, implode(',', $productsToRemove));
		$wpdb->query($productQuery);
		$wpdb->query($productMetaQuery);
	}

	public function removeAllNotAssignedMedia() {
		global $wpdb;

		$select = "SELECT * FROM {$wpdb->prefix}posts WHERE post_type = 'attachment' AND post_parent = 0";

		$attachments = $wpdb->get_results($select, ARRAY_A);

		$attachmentsToRemove = [];

		foreach ( $attachments as $attachment ) {

			$attachId = $attachment[ 'ID' ];

			$path = get_post_meta($attachId, '_wp_attached_file', true);

			if ( strpos($path, 'allegro') !== false ) {

				$uploadDir = wp_get_upload_dir();

				$basedir = $uploadDir[ 'basedir' ];

				$pathToRemove = $basedir . '/' . $path;

				if ( file_exists($pathToRemove) ) {

					unlink($pathToRemove);

					$filename = pathinfo($pathToRemove, PATHINFO_FILENAME);

					$extension = pathinfo($pathToRemove, PATHINFO_EXTENSION);

					$dirname = pathinfo($pathToRemove, PATHINFO_DIRNAME);

					foreach ( $this->thumbnailsToRemove as $thumbnailSize ) {

						$file = $dirname . '/' . $filename . '-' . $thumbnailSize . '.' . $extension;

						if ( file_exists($file) ) {

							unlink($file);
						}
					}
				}

				$attachmentsToRemove[] = $attachId;
			}
		}

		if ( empty($attachmentsToRemove) ) {

			return;
		}

		$queryPattern = "DELETE FROM %s WHERE ID IN (%s) AND post_type = '%s'";

		$queryPatternMeta = "DELETE FROM %s WHERE post_id IN (%s)";

		$postsTable = $wpdb->prefix . 'posts';

		$postsMetaTable = $wpdb->prefix . 'postmeta';

		$attachQuery = sprintf($queryPattern, $postsTable, implode(',', $attachmentsToRemove), 'attachment');

		$attachMetaQuery = sprintf($queryPatternMeta, $postsMetaTable, implode(',', $attachmentsToRemove));

		$wpdb->query($attachQuery);

		$wpdb->query($attachMetaQuery);
	}

	public function removeAllMediaThatProductNotExist() {
		global $wpdb;

		$allExistingPosts = "SELECT ID FROM {$wpdb->prefix}posts WHERE post_type != 'attachment'";

		$patternSelect = "SELECT * FROM %s WHERE post_type = 'attachment' AND post_parent NOT IN (%s)";

		$table = "{$wpdb->prefix}posts";

		$select = sprintf($patternSelect, $table, $allExistingPosts);

		$attachments = $wpdb->get_results($select, ARRAY_A);

		$attachmentsToRemove = [];

		foreach ( $attachments as $attachment ) {

			$attachId = $attachment[ 'ID' ];

			$path = get_post_meta($attachId, '_wp_attached_file', true);

			if ( strpos($path, 'allegro') !== false ) {

				$uploadDir = wp_get_upload_dir();

				$basedir = $uploadDir[ 'basedir' ];

				$pathToRemove = $basedir . '/' . $path;

				if ( file_exists($pathToRemove) ) {

					unlink($pathToRemove);

					$filename = pathinfo($pathToRemove, PATHINFO_FILENAME);

					$extension = pathinfo($pathToRemove, PATHINFO_EXTENSION);

					$dirname = pathinfo($pathToRemove, PATHINFO_DIRNAME);

					foreach ( $this->thumbnailsToRemove as $thumbnailSize ) {

						$file = $dirname . '/' . $filename . '-' . $thumbnailSize . '.' . $extension;

						unlink($file);
					}
				}

				$attachmentsToRemove[] = $attachId;
			}
		}

		if ( empty($attachmentsToRemove) ) {

			return;
		}

		$queryPattern = "DELETE FROM %s WHERE ID IN (%s) AND post_type = '%s'";

		$queryPatternMeta = "DELETE FROM %s WHERE post_id IN (%s)";

		$postsTable = $wpdb->prefix . 'posts';

		$postsMetaTable = $wpdb->prefix . 'postmeta';

		$attachQuery = sprintf($queryPattern, $postsTable, implode(',', $attachmentsToRemove), 'attachment');

		$attachMetaQuery = sprintf($queryPatternMeta, $postsMetaTable, implode(',', $attachmentsToRemove));

		$wpdb->query($attachQuery);

		$wpdb->query($attachMetaQuery);
	}

	public function getFasterSQLForGettingProductId( $auctionId ) {
		global $wpdb;

		$select = "SELECT post_id FROM {$wpdb->prefix}postmeta AS meta WHERE meta.meta_key = '_allegro_auction_id' AND meta.meta_value LIKE '%{$auctionId}%'";

		return $wpdb->get_var($select);
	}

	public function removeDuplicateCategories() {
		global $wpdb;

		$query = "DELETE a FROM {$wpdb->prefix}term_taxonomy a JOIN ( SELECT term_taxonomy_id FROM {$wpdb->prefix}term_taxonomy WHERE taxonomy = 'product_cat' GROUP BY description HAVING COUNT(term_taxonomy_id) > 1 ) b ON a.term_taxonomy_id = b.term_taxonomy_id";

		do {
			$rowsAffected = $wpdb->query($query);
		} while ( $rowsAffected > 0 );
	}

	public function updatePostTitle( $productId, $newTitle ) {
		$product = get_post($productId);

		if ( $product->post_title === $newTitle ) {
			return;
		}

		$postUpdate = array(
			'ID'         => $product->ID,
			'post_title' => $newTitle
		);

		wp_update_post($postUpdate);
	}

	public function updatePostContent( $productId, $newDescription, $auctionId = null ) {
		$newDescription = $this->prepareDescription($newDescription, $auctionId);

		$product = get_post($productId);

		if ( $product->post_content === $newDescription ) {
			return;
		}

		$postUpdate = array(
			'ID'           => $product->ID,
			'post_content' => $newDescription
		);

		wp_update_post($postUpdate);
	}

	private function getFilePathByAttachId( $attachId ): string {
		$uploadDir = wp_upload_dir();

		$filePath = get_post_meta($attachId, '_wp_attached_file', true);

		$uploadPath = $uploadDir[ 'basedir' ];

		return $uploadPath . '/' . $filePath;
	}

	public function checkIfPhysicalFileExists( $attachId ): bool {
		return file_exists($this->getFilePathByAttachId($attachId));
	}

	public function saveAllegroImageForThisAttach( $allegroImageUrl, $attachId ): void {
		$destination = $this->getFilePathByAttachId($attachId);

		copy($allegroImageUrl, $destination);

		$this->addAttachmentToRegenerate($attachId, $destination);
	}

	private function saveFlagForAddedAuction( $auctionId, $productId ) {
		/** @var GJMAA_Model_Auctions $auctionModel */
		$auctionModel = GJMAA::getModel('auctions');
		$auctionModel->load([
			$auctionId,
			$this->getProfile()->getId()
		], [
			'auction_id',
			'auction_profile_id'
		]);

		$auId = $auctionModel->getId();

		$auctionInWooCommerce = 1;
		if ( $productId === 0 ) {
			$auctionInWooCommerce = 2;
		}

		if ( $auId ) {
			$auctionModel->updateData($auctionModel->getId(), [
				'auction_in_woocommerce' => $auctionInWooCommerce,
				'auction_woocommerce_id' => $productId
			]);
		}
	}

	private function addAttachmentToRegenerate( int $attachId, string $destinationPath ) {
		/** @var GJMAA_Model_Attachments $attachments */
		$attachments = GJMAA::getModel('attachments');
		$attachments->setData('attach_id', $attachId);
		$attachments->setData('destination_path', $destinationPath);
		$attachments->save();
	}

	public function prepareDescription( $description, $auctionId = null ) {
		$html = '<div class="container">';

		foreach ( $description[ 'sections' ] as $items ) {
			$imageHtml    = $textHtml = '';
			$isFirstItem  = true;
			$isFirstImage = false;
			foreach ( $items[ 'items' ] as $item ) {
				$isImage = strtolower($item[ 'type' ]) == 'image';
				$isText  = strtolower($item[ 'type' ]) == 'text';

				if ( $isImage ) {
					$parts_url = explode('/', $item[ 'url' ]);
					$lastHash  = end($parts_url);
					$attachId  = $this->get_attach_id_by_filealt_and_type($auctionId, $lastHash);
					if ( ! $attachId ) {
						$attachId = $this->attach_image($item[ 'url' ], $auctionId, $lastHash);
					} else {
						$this->checkIfPhysicalFileExists($attachId);
						$this->saveAllegroImageForThisAttach($item[ 'url' ], $attachId);
					}

					$imageHtml .= '<div class="col-sm">' . $this->parseAttachIdToHtml($attachId, $auctionId) . '</div>';

					if ( $isFirstItem ) {
						$isFirstImage = true;
					}
				}

				if ( $isText ) {
					$textHtml = '<div class="col-sm">' . $item[ 'content' ] . '</div>';
				}

				$isFirstItem = false;
			}

			$html .= '<div class="row" style="margin-bottom: 1em;">' . ( $isFirstImage ? $imageHtml . $textHtml : $textHtml . $imageHtml ) . '</div>';
		}

		$html .= '</div>';

		return $html;
	}

	private function parseAttachIdToHtml( $attachId, $alt, $isMediaText = false ) {
		$image = wp_get_attachment_image_url($attachId, 'large');

		$type = 'media-text__media';

		return '<figure class="wp-block-' . $type . '"><img src="' . $image . '" alt="' . $alt . '" /></figure>';
	}

	private function parseMediaTextToHtml( $attachId, $mediaPosition, $auctionId ) {
		return '<div class="wp-block-media-text alignwide ' . ( $mediaPosition === 'right' ? 'has-media-on-the-right ' : '' ) . 'is-stacked-on-mobile">' . $this->parseAttachIdToHtml($attachId, $auctionId, true) . '<div class="wp-block-media-text__content">__SPLIT__</div></div>';
	}
}
