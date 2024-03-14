<?php
/**
 * Walker class
 */
class LaStudio_Kit_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * @var string
	 */
	protected $item_type   = 'simple';

	/**
	 * @var null
	 */
	private $item_settings = null;

	/**
	 * @var int
	 */
	public $lv_1_index = 0;

	private $current_root_id = 0;
	/**
	 * Starts the list before the elements are added.
	 *
	 * @since 3.0.0
	 *
	 * @see Walker::start_lvl()
	 *
	 * @param string   $output Passed by reference. Used to append additional content.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}

		$indent = str_repeat( $t, $depth );

		// Default class.
		$classes     = array( 'lakit-nav__sub' );
		$classes[]   = 'lakit-nav-depth-' . $depth;
		$class_names = join( ' ', $classes );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= "{$n}{$indent}<div $class_names>{$n}";
	}

	/**
	 * Ends the list of after the elements are added.
	 *
	 * @since 3.0.0
	 *
	 * @see Walker::end_lvl()
	 *
	 * @param string   $output Passed by reference. Used to append additional content.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}

		$indent  = str_repeat( $t, $depth );
		$output .= "$indent</div>{$n}";
	}

	/**
	 * Starts the element output.
	 *
	 * @since 3.0.0
	 * @since 4.4.0 The {@see 'nav_menu_item_args'} filter was added.
	 *
	 * @see Walker::start_el()
	 *
	 * @param string   $output Passed by reference. Used to append additional content.
	 * @param WP_Post  $item   Menu item data object.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 * @param int      $id     Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		$this->item_settings = null;

		$settings = $this->get_settings( $item->ID );

		$show_desc = isset( $args->widget_settings['show_items_desc'] ) ? filter_var( $args->widget_settings['show_items_desc'], FILTER_VALIDATE_BOOLEAN ) : false;
		$show_megamenu = isset( $args->widget_settings['show_megamenu'] ) ? filter_var( $args->widget_settings['show_megamenu'], FILTER_VALIDATE_BOOLEAN ) : false;
		$enable_ajax_megamenu = isset( $args->widget_settings['enable_ajax_megamenu'] ) ? filter_var( $args->widget_settings['enable_ajax_megamenu'], FILTER_VALIDATE_BOOLEAN ) : false;

        if( $depth == 0 ) {
            $logo_position = !empty($args->widget_settings['logo_position']) ? absint($args->widget_settings['logo_position']) : 0;
            $logo_html = !empty($args->widget_settings['logo_html']) ? $args->widget_settings['logo_html'] : false;
            if(!empty($logo_html) && $this->lv_1_index === $logo_position){
                $output .= $logo_html;
            }
        }

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;

		if(!empty($args->widget_settings['widget_id'])){
			$classes[] = 'lakit-nav-id-' . $args->widget_settings['widget_id'];
		}

		if ( $show_megamenu && 0 === $depth && $this->is_mega_enabled( $item->ID ) ) {
			$classes[] = 'menu-item-has-children';
			$classes[] = 'lakit-nav__item--mega';
			if ( ! empty( $settings['custom_mega_menu_position'] ) && 'default' !== $settings['custom_mega_menu_position'] ) {
				$classes[] = 'lakit-nav__item--' . esc_attr( $settings['custom_mega_menu_position'] );
			}
		}
		else{
			$classes[] = 'lakit-nav__item--default';
		}

		// Add an active class for ancestor items
		if ( in_array( 'current-menu-ancestor', $classes ) || in_array( 'current-page-ancestor', $classes ) ) {
			$classes[] = 'lakit-nav__item--current';
			$classes[] = 'current-menu-item';
		}


		$classes[] = 'lakit-nav__item-' . $item->ID;
		$classes[] = 'lakit-nav__item';
		if ( 0 < $depth ) {
			$classes[] = 'lakit-nav-item-sub';
		}

		/**
		 * Filters the arguments for a single nav menu item.
		 *
		 * @since 4.4.0
		 *
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param WP_Post  $item  Menu item data object.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		$class_names = join( ' ', array_filter( $classes ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
		$li_style = '';

		$output .= $indent . '<div' . $class_names . $li_style .'>';

		$atts           = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';
		$atts['class']  = 'menu-item-link menu-item-link-depth-' . $depth;

		if ( 0 === $depth ) {
			$atts['class'] .= ' menu-item-link-top';
		} else {
			$atts['class'] .= ' menu-item-link-sub';
		}


		$hide_item_text = isset( $settings['hide_item_text'] ) && filter_var( $settings['hide_item_text'], FILTER_VALIDATE_BOOLEAN );

		if ( $hide_item_text ) {
			$atts['class'] .= ' label-hidden';
		}

		/**
		 * Filters the HTML attributes applied to a menu item's anchor element.
		 *
		 * @since 3.6.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array $atts {
		 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
		 *
		 *     @type string $title  Title attribute.
		 *     @type string $target Target attribute.
		 *     @type string $rel    The rel attribute.
		 *     @type string $href   The href attribute.
		 * }
		 * @param WP_Post  $item  The current menu item.
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';

		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		/**
		 * Filters a menu item's title.
		 *
		 * @since 4.4.0
		 *
		 * @param string   $title The menu item's title.
		 * @param WP_Post  $item  The current menu item.
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$icon  = '';
		$desc  = '';
		$badge = '';
		$dropdown_icon = '';


		$icon_type = isset( $settings['menu_icon_type'] ) ? $settings['menu_icon_type'] : 'icon';

		switch ( $icon_type ) {
			case 'icon':
				$icon = ! empty( $settings['menu_icon'] ) ? sprintf('<span class="lakit-nav-item-icon"><i class="%1$s"></i></span>', $settings['menu_icon']) : '';
				break;

			case 'svg':
				$icon = ! empty( $settings['menu_svg'] ) ? $this->get_svg_icon_html( $settings['menu_svg'] ) : '';
				break;
		}

		if ( ! empty( $item->description ) && $show_desc ) {
			$desc = sprintf(
				'<span class="lakit-nav-item-desc %2$s">%1$s</span>',
				$item->description,
				( 0 === $depth ) ? 'top-level-desc'  : 'sub-level-desc'
			);
		}

		$title = sprintf('<span class="menu-text">%1$s</span>', $title);

		if ( $hide_item_text ) {
			$title = '';
		}

		if ( in_array( 'menu-item-has-children', $classes ) ) {
			$arrow_icon = isset( $args->widget_settings['dropdown_icon'] ) ? $args->widget_settings['dropdown_icon'] : 'lastudioicon-down-arrow';
			if ( $arrow_icon ) {
				$dropdown_icon = $this->get_dropdown_arrow_html( $arrow_icon );
			}
		}

		if ( ! empty( $settings['menu_badge'] ) ) {
			$badge = $this->get_badge_html( $settings['menu_badge'], $depth );
		}

		$inline_styles = [];

		if(!empty($settings['icon_color'])){
			$inline_styles[] = '--mm-icon-color:' . $settings['icon_color'];
		}
		if(!empty($settings['icon_size'])){
			$inline_styles[] = '--mm-icon-size:' . $settings['icon_size'] . 'px';
		}
		if(!empty($settings['badge_color'])){
			$inline_styles[] = '--mm-badge-color:' . $settings['badge_color'];
		}
		if(!empty($settings['badge_bg_color'])){
			$inline_styles[] = '--mm-badge-bg:'. $settings['badge_bg_color'];
		}

		$inline_styles_html = join(';', $inline_styles);
		$title_wrapper_html = sprintf( '<span class="lakit-nav-link-inner" style="%6$s"><span class="lakit-nav-link-text">%5$s%1$s</span>%3$s%4$s</span>%2$s', $title, $dropdown_icon, $badge, $desc, $icon, $inline_styles_html );

		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before . $title_wrapper_html . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		if(0 === $depth && $show_megamenu && $this->is_mega_enabled( $item->ID )){

			$is_elementor_preview = ( isset( $_GET['elementor-preview'] ) ) ? true : false;

			$template_content = apply_filters('lastudio-kit/module/menu/megamenu_content', '', $item->ID);

            if(empty($template_content)){
                if($enable_ajax_megamenu && !$is_elementor_preview){
                    $template_content = '<div data-lakit_ajax_loadtemplate="true" data-template-id="'.esc_attr($item->ID).'"><span class="lakit-css-loader"></span></div>';
                }
                else{
                    if ( class_exists( 'Elementor\Plugin' ) ) {
                        $template_content = Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $item->ID );
                    }
                }
            }
			if(!empty($template_content)){
				$position = ( !empty( $settings['custom_mega_menu_position'] ) && 'default' !== $settings['custom_mega_menu_position'] ) ? 'relative' : 'default';
				$sub_width = !empty( $settings['custom_mega_menu_width'] ) ? absint($settings['custom_mega_menu_width']) . 'px' : 'auto';
				$item_output .= sprintf('<div class="lakit-nav__sub lakit-nav__sub-mega lakit-nav-depth-%3$s" data-position="%2$s" data-max-width="%4$s" style="--mm-sub-width:%4$s">%1$s</div>', $template_content, $position, $depth, $sub_width);
			}
		}

		/**
		 * Filters a menu item's starting output.
		 *
		 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
		 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
		 * no filter for modifying the opening and closing `<li>` for a menu item.
		 *
		 * @since 3.0.0
		 *
		 * @param string   $item_output The menu item's starting HTML output.
		 * @param WP_Post  $item        Menu item data object.
		 * @param int      $depth       Depth of menu item. Used for padding.
		 * @param stdClass $args        An object of wp_nav_menu() arguments.
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	/**
	 * Render Icon HTML
	 *
	 * @param  string $icon Icon slug to render.
	 * @return string
	 */
	public function get_dropdown_arrow_html( $icon = '' ) {

		$format = apply_filters(
			'lastudio-kit/nav-menu/walker/dropdown-arrow-format',
			'<i class="lakit-nav-arrow %s"></i>',
			$icon
		);

		return sprintf( $format, esc_attr( $icon ) );
	}

	/**
	 * Ends the element output, if needed.
	 *
	 * @since 3.0.0
	 *
	 * @see Walker::end_el()
	 *
	 * @param string   $output Passed by reference. Used to append additional content.
	 * @param WP_Post  $item   Page data object. Not used.
	 * @param int      $depth  Depth of page. Not Used.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$output .= "</div>{$n}";
		if( $depth == 0 ){
		    $this->lv_1_index++;
        }
	}

	/**
	 * Traverse elements to create list from elements.
	 *
	 * Display one element if the element doesn't have any children otherwise,
	 * display the element and its children. Will only traverse up to the max
	 * depth and no ignore elements under that depth. It is possible to set the
	 * max depth to include all depths, see walk() method.
	 *
	 * This method should not be called directly, use the walk() method instead.
	 *
	 * @since 2.5.0
	 *
	 * @param object $element           Data object.
	 * @param array  $children_elements List of elements to continue traversing (passed by reference).
	 * @param int    $max_depth         Max depth to traverse.
	 * @param int    $depth             Depth of current element.
	 * @param array  $args              An array of arguments.
	 * @param string $output            Used to append additional content (passed by reference).
	 */
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
		if ( ! $element ) {
			return;
		}

		$id_field = $this->db_fields['id'];
		$id       = $element->$id_field;

		if($depth == 0){
			$this->current_root_id = $id;
		}

		// Display this element.
		$this->has_children = ! empty( $children_elements[ $id ] );
		if ( isset( $args[0] ) && is_array( $args[0] ) ) {
			$args[0]['has_children'] = $this->has_children; // Back-compat.
		}

		$this->start_el( $output, $element, $depth, ...array_values( $args ) );

		// @added by Jun
		$ignore_child = false;

		if(isset($args[0]->widget_settings) && isset($args[0]->widget_settings['show_megamenu']) && filter_var($args[0]->widget_settings['show_megamenu'], FILTER_VALIDATE_BOOLEAN) && $this->is_mega_enabled($id) ) {
			$ignore_child = true;
		}


		// Descend only when the depth is right and there are children for this element.
		if ( ( 0 == $max_depth || $max_depth > $depth + 1 ) && isset( $children_elements[ $id ] ) ) {

			if(!$ignore_child){
				foreach ( $children_elements[ $id ] as $child ) {

					if ( ! isset( $newlevel ) ) {
						$newlevel = true;
						// Start the child delimiter.
						$this->start_lvl( $output, $depth, ...array_values( $args ) );
					}
					$this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
				}
			}
			else{
				foreach ( $children_elements[ $id ] as $child ) {
					$this->remove_display_element( $child, $children_elements );
				}
			}
			unset( $children_elements[ $id ] );
		}

		if ( isset( $newlevel ) && $newlevel ) {
			// End the child delimiter.


			$this->end_lvl( $output, $depth, ...array_values( $args ) );
		}

		// End this element.
		$this->end_el( $output, $element, $depth, ...array_values( $args ) );
	}

	public function remove_display_element( $element, &$children_elements ){
		if ( ! $element ) {
			return;
		}

		$id_field = $this->db_fields['id'];
		$id       = $element->$id_field;

		if(isset($children_elements[$id])){
			unset($children_elements[$id]);
		}
	}

	/**
	 * Check if mega menu enabled for passed item
	 *
	 * @param  int  $item_id Item ID
	 * @return boolean
	 */
	public function is_mega_enabled( $item_id = 0 ) {

		$item_settings = $this->get_settings( $item_id );

		return ( lastudio_kit()->get_theme_support('elementor::mega-menu') && isset( $item_settings['enabled'] ) && 'true' == $item_settings['enabled'] );
	}
	/**
	 * Get item settings
	 *
	 * @param  integer $item_id Item ID
	 * @return array
	 */
	public function get_settings( $item_id = 0 ) {

		if ( null === $this->item_settings ) {
			$settings = get_post_meta( $item_id, 'lakit_menu_settings', true );
			$this->item_settings = ! empty( $settings ) ? $settings : array();
		}

		return $this->item_settings;
	}

	/**
	 * [get_svg_html description]
	 * @param  string $svg_id [description]
	 * @return [type]         [description]
	 */
	public function get_svg_icon_html( $svg_id = '', $wrapper = true ) {

		if ( empty( $svg_id ) ) {
			return '';
		}

		$url = wp_get_attachment_url( $svg_id );

		if ( ! $url ) {
			return '';
		}

		return lastudio_kit_helper()->get_image_by_url( $url, array( 'class' => 'lakit-nav-item-icon' ), $wrapper );
	}

	/**
	 * @param string $badge
	 * @param int $depth
	 *
	 * @return string
	 */
	public function get_badge_html( $badge = '', $depth = 0 ) {
		$format = apply_filters(
			'lastudio-kit/nav-menu/walker/badge-format',
			'<span class="lakit-nav-item-badge"><span class="lakit-nav-item-badge-inner">%1$s</span></span>',
			$badge,
			$depth
		);

		return sprintf( $format, esc_attr( $badge ) );
	}
}
