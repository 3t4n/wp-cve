<?php
/**
 * Core helper functions.
 *
 * @since x.x.x
 * @package Magazine Blocks
 */

/**
 * Get the direct filesystem object.
 *
 * @return \WP_Filesystem_Direct|null
 */
function magazine_blocks_get_filesystem() {
	/**
	 * WP_FIlesystem_Direct instance.
	 *
	 *  @var \WP_Filesystem_Direct $wp_filesystem WP_FIlesystem_Direct instance.
	 */
	global $wp_filesystem;

	if ( ! $wp_filesystem || 'direct' !== $wp_filesystem->method ) {
		require_once ABSPATH . '/wp-admin/includes/file.php';
		$credentials = request_filesystem_credentials( '', 'direct' );
		WP_Filesystem( $credentials );
	}

	return $wp_filesystem;
}

/**
 * Append content to file.
 *
 * If file does not exists, it will create new file.
 *
 * @param string           $filepath Filepath to append.
 * @param Closure|callable $append_callback Appender.
 *
 * @return bool True on success and false on failure.
 */
function magazine_blocks_filesystem_append_to_file( $filepath, $append_callback ) {
	$filesystem = magazine_blocks_get_filesystem();

	if ( ! $filesystem ) {
		return false;
	}

	if ( ! $filesystem->exists( $filepath ) ) {
		$filesystem->touch( $filepath, FS_CHMOD_FILE );
	}

	$current     = $filesystem->get_contents( $filepath );
	$new_content = call_user_func_array( $append_callback, array( $current, $filesystem, $filepath ) );

	if ( ! isset( $new_content ) || ! is_string( $new_content ) ) {
		_doing_it_wrong( __FUNCTION__, 'Invalid new content.', '1.0.0' );
		return false;
	}

	return $filesystem->put_contents( $filepath, $new_content );
}

/**
 * Is magazine blocks block.
 *
 * @since x.x.x
 * @param array $block Block data.
 * @return bool
 */
function magazine_blocks_is_magazine_blocks_block( array $block ): bool {
	if ( empty( $block ) || empty( $block['blockName'] ) ) {
		return false;
	}

	$namespace = $block['blockName'];

	if ( 0 === strpos( $namespace, 'magazine-blocks/' ) ) {
		return true;
	}

	return false;
}

/**
 * Get public post types.
 *
 * @return array
 */
function magazine_blocks_get_post_types(): array {
	return array_filter(
		get_post_types(
			array(
				'public'       => true,
				'show_in_rest' => true,
			),
			'objects'
		),
		function( $post_type ) {
			return ! in_array( $post_type->name, array( 'revision', 'nav_menu_item', 'attachment' ), true );
		}
	);
}

/**
 * Get icon.
 *
 * @param string $name Icon name.
 * @param bool   $echo Echo or return.
 * @param array  $args Icon args.
 *
 * @return string
 */
function magazine_blocks_get_icon( $name, $echo = false, $args = array() ) {
	$icon = \MagazineBlocks\Icon::init()->get( $name, $args );
	if ( $echo ) {
		echo wp_kses( $icon, magazine_blocks_get_allowed_svg_elements() );
	}
	return $icon;
}

/**
 * Get the ID.
 *
 * @return false|int|string
 */
function magazine_blocks_get_the_id() {
	$id = false;

	if ( ! magazine_blocks_is_block_theme() ) {
		if ( is_singular() ) {
			$id = get_the_ID();
		}
		return $id;
	}

	if ( is_front_page() && is_home() ) {
		$id = 'home';
	} elseif ( is_front_page() && ! is_home() ) {
		$id = 'front_page';
	} elseif ( is_home() && ! is_front_page() ) {
		$id = 'blog';
	} elseif ( is_archive() ) {
		if ( is_category() ) {
			$id = 'category';
		} elseif ( is_tag() ) {
			$id = 'tag';
		} elseif ( is_author() ) {
			$id = 'author';
		} elseif ( is_date() ) {
			$id = 'date';
		} elseif ( is_post_type_archive() ) {
			$id = 'post_type_archive';
		} elseif ( is_tax() ) {
			$id = 'taxonomy';
		}
	} elseif ( is_search() ) {
		$id = 'search';
	} elseif ( is_404() ) {
		$id = '404';
	} elseif ( is_singular() ) {
		$id = get_the_ID();
	}

	return $id;
}

/**
 * Is preview.
 *
 * @return bool
 */
function magazine_blocks_is_preview(): bool {
	return isset( $_GET['preview'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
}

/**
 * Is block theme.
 *
 * @return bool
 */
function magazine_blocks_is_block_theme(): bool {
	return function_exists( 'wp_is_block_theme' ) && wp_is_block_theme();
}

/**
 * Process blocks.
 *
 * Get only magazine blocks, flatten inner blocks and process reusable blocks.
 *
 * @param array $blocks Array of blocks.
 *
 * @return array
 */
function magazine_blocks_process_blocks( &$blocks ): array {
	$processed_blocks = array();
	$refs             = array();

	foreach ( $blocks as &$block ) {
		if ( magazine_blocks_is_magazine_blocks_block( $block ) ) {
			$processed_blocks[] = &$block;
		}
		if ( 'core/block' === $block['blockName'] ) {
			$ref = magazine_blocks_array_get( $block, 'attrs.ref' );
			if ( $ref && ! isset( $refs[ $ref ] ) ) {
				$refs[ $ref ]  = true;
				$reusable_post = get_post( $ref );

				if ( $reusable_post && $reusable_post instanceof WP_Post ) {
					$ref_blocks       = parse_blocks( $reusable_post->post_content );
					$ref_blocks       = magazine_blocks_process_blocks( $ref_blocks );
					$processed_blocks = array_merge( $processed_blocks, $ref_blocks );
				}
			}
		}
		if ( ! empty( $block['innerBlocks'] ) ) {
			$inner_blocks     = magazine_blocks_process_blocks( $block['innerBlocks'] );
			$processed_blocks = array_merge( $processed_blocks, $inner_blocks );

			if ( 'magazine-blocks/button' !== $block['blockName'] ) {
				magazine_blocks_array_forget( $block, 'innerBlocks' );
			}
		}
		magazine_blocks_array_forget( $block, array( 'innerHTML', 'innerContent' ) );
	}

	return $processed_blocks;
}


/**
 * Generate block styles.
 *
 * @param array           $blocks Block data.
 * @param null|int|string $id Current page or template id.
 * @param bool            $force_generate Force generate styles.
 *
 * @return \MagazineBlocks\BlockStyles
 */
function magazine_blocks_generate_blocks_styles( array &$blocks, $id = null, bool $force_generate = false ) {
	return new \MagazineBlocks\BlockStyles( $blocks, $id, $force_generate );
}


/**
 * The function retrieves widget blocks and parses them into an array.
 *
 * @return array Array of blocks.
 */
function magazine_blocks_get_widget_blocks() {
	$callback = function( $acc, $curr ) {
		if ( ! empty( $curr['content'] ) ) {
			$acc .= $curr['content'];
		}
		return $acc;
	};
	return parse_blocks(
		array_reduce(
			get_option( 'widget_block', array() ),
			$callback,
			''
		)
	);
}

/**
 * Get allowed svg elements.
 *
 * @return array
 */
function magazine_blocks_get_allowed_svg_elements() {
	return [
		'svg'     => [
			'class'           => true,
			'xmlns'           => true,
			'width'           => true,
			'height'          => true,
			'viewbox'         => true,
			'aria-hidden'     => true,
			'role'            => true,
			'focusable'       => true,
			'fill'            => true,
			'stroke'          => true,
			'stroke-width'    => true,
			'stroke-linecap'  => true,
			'stroke-linejoin' => true,
		],
		'g'       => [ 'fill' => true ],
		'title'   => [ 'title' => true ],
		'path'    => [
			'fill'      => true,
			'fill-rule' => true,
			'd'         => true,
			'transform' => true,
		],
		'circle'  => [
			'cx' => true,
			'cy' => true,
			'r'  => true,
		],
		'polygon' => [
			'fill'      => true,
			'fill-rule' => true,
			'points'    => true,
			'transform' => true,
			'focusable' => true,
		],
		'line'    => [
			'x1' => true,
			'y1' => true,
			'x2' => true,
			'y2' => true,
		],
	];
}

/**
 * String to kebab case.
 *
 * @param string $string
 * @return string
 */
function magazine_blocks_string_to_kebab( $string ) {
	$string = str_replace( ' ', '-', strtolower( $string ) );
	$string = preg_replace( '/[^a-z0-9-]/', '', $string );
	$string = preg_replace( '/-+/', '-', $string );
	$string = trim( $string, '-' );

	return $string;
}

/**
 * Build html attributes from array.
 *
 * @param array $attributes
 * @param boolean $echo
 * @return void
 */
function magazine_blocks_build_html_attrs( $attributes = array(), $echo_attributes = false ) {
	$index  = 0;
	$attrs  = '';
	$length = count( $attributes );

	foreach ( $attributes as $key => $value ) {
		if ( isset( $value ) ) {
			if ( is_array( $value ) ) {
				$value = implode( ' ', array_filter( $value ) );
			}

			if ( $echo_attributes ) {
				echo ' ' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"' . ( $length === $index + 1 ? ' ' : '' );
			} else {
				$attrs .= ' ' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"' . ( $length === $index + 1 ? ' ' : '' );
			}
		}
		++$index;
	}
	return ! $echo_attributes ? true : $attrs;
}

/**
 * Is rest request.
 *
 * @return boolean
 */
function magazine_blocks_is_rest_request() {
	return defined( 'REST_REQUEST' ) && REST_REQUEST;
}

/**
 * Is rest request.
 *
 * @return boolean
 */
function magazine_blocks_is_development() {
	return defined( 'MAGAZINE_BLOCKS_DEVELOPMENT' ) && MAGAZINE_BLOCKS_DEVELOPMENT;
}


/**
 * Get setting object.
 *
 * @param string $key
 * @param mixed $default_value
 */
function magazine_blocks_get_setting( $key = '', $default_value = null ) {
	if ( ! $key ) {
		return \MagazineBlocks\Setting::all();
	}
	return \MagazineBlocks\Setting::get( $key, $default_value );
}

/**
 * Strung to bool.
 *
 * @param string $string
 * @return bool
 */
function magazine_blocks_string_to_bool( $string ) {
	if ( is_bool( $string ) ) {
		return $string;
	}

	$string = strtolower( $string );

	return ( 'yes' === $string || 1 === $string || 'true' === $string || '1' === $string );
}

/**
 * Bool to string.
 *
 * @param bool $bool
 * @return string
 */
function magazine_blocks_bool_to_string( $bool ) {
	if ( ! is_bool( $bool ) ) {
		$bool = magazine_blocks_string_to_bool( $bool );
	}
	return true === $bool ? 'yes' : 'no';
}

/**
 * Get webfont url.
 *
 * @param string $url Remote webfont url.
 * @param string $format Font format.
 * @param boolean $preload Preload font.
 * @return void
 */
function magazine_blocks_get_webfont_url( $url, $format = 'woff2', $preload = false ) {
	$font = new \MagazineBlocks\WebFontLoader( $url, $preload );
	$font->set_font_format( $format );
	return $font->get_url();
}

/**
 * Get global styles.
 *
 * @return array
 */
function magazine_blocks_get_global_styles() {
	$global_styles = magazine_blocks_get_setting( 'global-styles' );

	try {
		$global_styles = json_decode( $global_styles, true );
	} catch ( Exception $e ) {
		$global_styles = array();
	}

	return $global_styles;
}

/**
 * Generate global styles.
 *
 * @return \BlockArt\GlobalStyles
 */
function magazine_blocks_generate_global_styles() {
	return new \MagazineBlocks\GlobalStyles();
}
