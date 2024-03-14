<?php

class WP3CXW_WebinarForm {

	const post_type = 'wp3cxw_webinar_form';

	private static $found_items = 0;
	private static $current = null;

	private $id;
	private $name;
	private $title;
	private $locale;
	private $properties = array();
	private $unit_tag;
	private $shortcode_atts = array();

	public static function count() {
		return self::$found_items;
	}

	public static function get_current() {
		return self::$current;
	}

	public static function register_post_type() {
		register_post_type( self::post_type, array(
			'labels' => array(
				'name' => __( 'Webinar Forms', '3cx-webinar' ),
				'singular_name' => __( 'Webinar Form', '3cx-webinar' ),
			),
			'rewrite' => false,
			'query_var' => false,
		) );
	}

	public static function find( $args = '' ) {
		$defaults = array(
			'post_status' => 'any',
			'posts_per_page' => -1,
			'offset' => 0,
			'orderby' => 'ID',
			'order' => 'ASC',
		);

		$args = wp_parse_args( $args, $defaults );

		$args['post_type'] = self::post_type;

		$q = new WP_Query();
		$posts = $q->query( $args );

		self::$found_items = $q->found_posts;

		$objs = array();

		foreach ( (array) $posts as $post ) {
			$objs[] = new self( $post );
		}

		return $objs;
	}

	public static function get_template( $args = '' ) {
		global $l10n;

		$defaults = array( 'locale' => null, 'title' => '' );
		$args = wp_parse_args( $args, $defaults );

		$locale = $args['locale'];
		$title = $args['title'];

		if ( $locale ) {
			$mo_orig = $l10n['3cx-webinar'];
			wp3cxw_load_textdomain( $locale );
		}

		self::$current = $webinar_form = new self;
		$webinar_form->title =
			( $title ? $title : __( 'Untitled', '3cx-webinar' ) );
		$webinar_form->locale = ( $locale ? $locale : get_user_locale() );

		$properties = $webinar_form->get_properties();

		$webinar_form->properties = $properties;

		$webinar_form = apply_filters( 'wp3cxw_webinar_form_default_pack',
			$webinar_form, $args );

		if ( isset( $mo_orig ) ) {
			$l10n['3cx-webinar'] = $mo_orig;
		}

		return $webinar_form;
	}

	public static function get_instance( $post ) {
		$post = get_post( $post );

		if ( ! $post || self::post_type != get_post_type( $post ) ) {
			return false;
		}

		return self::$current = new self( $post );
	}

	private static function get_unit_tag( $id = 0 ) {
		static $global_count = 0;

		$global_count += 1;

		if ( in_the_loop() ) {
			$unit_tag = sprintf( 'wp3cxw-f%1$d-p%2$d-o%3$d',
				absint( $id ), get_the_ID(), $global_count );
		} else {
			$unit_tag = sprintf( 'wp3cxw-f%1$d-o%2$d',
				absint( $id ), $global_count );
		}

		return $unit_tag;
	}

	private function __construct( $post = null ) {
		$post = get_post( $post );

		if ( $post && self::post_type == get_post_type( $post ) ) {
			$this->id = $post->ID;
			$this->name = $post->post_name;
			$this->title = $post->post_title;
			$this->locale = get_post_meta( $post->ID, '_locale', true );

			$properties = $this->get_properties();

			foreach ( $properties as $key => $value ) {
				if ( metadata_exists( 'post', $post->ID, '_' . $key ) ) {
					$properties[$key] = get_post_meta( $post->ID, '_' . $key, true );
				} elseif ( metadata_exists( 'post', $post->ID, $key ) ) {
					$properties[$key] = get_post_meta( $post->ID, $key, true );
				}
			}

			$this->properties = $properties;
			$this->upgrade();
		}

		do_action( 'wp3cxw_webinar_form', $this );
	}

	public function initial() {
		return empty( $this->id );
	}

	public function prop( $name ) {
		$props = $this->get_properties();
		return isset( $props[$name] ) ? $props[$name] : null;
	}

	public function get_properties() {
		$properties = (array) $this->properties;

		$properties = wp_parse_args( $properties, array(
			'config' => array()
		) );

		$properties = (array) apply_filters( 'wp3cxw_webinar_form_properties',
			$properties, $this );

		return $properties;
	}

	public function set_properties( $properties ) {
		$defaults = $this->get_properties();

		$properties = wp_parse_args( $properties, $defaults );
		$properties = array_intersect_key( $properties, $defaults );

		$this->properties = $properties;
	}

	public function id() {
		return $this->id;
	}

	public function name() {
		return $this->name;
	}

	public function title() {
		return $this->title;
	}

	public function set_title( $title ) {
		$title = strip_tags( $title );
		$title = trim( $title );

		if ( '' === $title ) {
			$title = __( 'Untitled', '3cx-webinar' );
		}

		$this->title = $title;
	}

	public function locale() {
		if ( wp3cxw_is_valid_locale( $this->locale ) ) {
			return $this->locale;
		} else {
			return '';
		}
	}

	public function set_locale( $locale ) {
		$locale = trim( $locale );

		if ( wp3cxw_is_valid_locale( $locale ) ) {
			$this->locale = $locale;
		} else {
			$this->locale = 'en_US';
		}
	}

	public function shortcode_attr( $name ) {
		if ( isset( $this->shortcode_atts[$name] ) ) {
			return (string) $this->shortcode_atts[$name];
		}
	}

	// Return true if this form is the same one as currently POSTed.
	public function is_posted() {

		if ( empty( $_POST['_wp3cxw_unit_tag'] ) ) {
			return false;
		}

		return $this->unit_tag == sanitize_key($_POST['_wp3cxw_unit_tag']);
	}

	/* Generating Form HTML */

	public function form_html( $args = '' ) {

		$args = wp_parse_args( $args, array(
			'html_id' => '',
			'html_name' => '',
			'html_class' => '',
			'output' => 'form',
		) );

		$this->shortcode_atts = $args;
		$this->unit_tag = self::get_unit_tag( $this->id );
    $config = $this->properties['config'];
    if (!$config['active']){
      return '';
    }

		$lang_tag = str_replace( '_', '-', $this->locale );

		if ( preg_match( '/^([a-z]+-[a-z]+)-/i', $lang_tag, $matches ) ) {
			$lang_tag = $matches[1];
		}

    $reply = wp3cxw_GetWebinarTransient($this->id);
		if ($reply && $reply['result']){
      $div_id = 'webmeeting_webinar_'.$this->id;
      $html='<div class="tcxwebinar" id="'.$div_id.'" tcxtarget="'.get_rest_url(null, '/3cx-webinar/subscribe').'">'.sprintf('<ul tcxwebinarformid="%s" >', $this->id);
      if (isset($reply['meetings']) && is_array($reply['meetings'])) {
        foreach($reply['meetings'] as $m) {
          $html.='<li>';
          $html.=sprintf('<div class="tcxwebinartitle"><a tcxdatetime="%s" tcxmeetingid="%s" href="#">%s</a></div>', $m['datetime'].':00Z', md5($this->id.$m['meetingid']), esc_html($m['subject']));
          $html.='<div class="tcxwebinardate">';
          $html.='<span class="tcxwebinardatelocale"></span>';
          $html.=sprintf('<span class="tcxwebinarduration">, '.esc_html(__('duration %s minutes','3cx-webinar')).'</span>', $m['duration']);
          if ($config['country']){
            $html.=sprintf('<img class="tcxwebinarflag flags %s" src="'.wp3cxw_plugin_url('').'/images/blank.png" width="18px" height="12px" alt="%s" />', $config['country'], $config['country']);
          }
          $html.='</div>';
          if (!empty($m['description'])) {
            $html.=sprintf('<div class="tcxwebinardescr">%s</div>', esc_html($m['description']));
          }
          $html.='</li>';
        }
      }
      $html.='</ul></div>';

			$html.='<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery("#'.$div_id.'").tcxWebinar();
		});	
	</script>';
		}
		else {
			$html = sprintf('<p id="webmeeting_webinar_'.$this->id.'">Webinar API error: %s</p>',esc_html($reply['error']));
		}
		
		return $html;
	}

	/* Upgrade */

	private function upgrade() {
		$config = $this->prop( 'config' );
		$this->properties['config'] = $config;
	}

	/* Save */

	public function save() {
		$props = $this->get_properties();

		$post_content = implode( "\n", wp3cxw_array_flatten( $props ) );

		if ( $this->initial() ) {
			$post_id = wp_insert_post( array(
				'post_type' => self::post_type,
				'post_status' => 'publish',
				'post_title' => $this->title,
				'post_content' => trim( $post_content ),
			) );
		} else {
			$post_id = wp_update_post( array(
				'ID' => (int) $this->id,
				'post_status' => 'publish',
				'post_title' => $this->title,
				'post_content' => trim( $post_content ),
			) );
		}

		if ( $post_id ) {
			foreach ( $props as $prop => $value ) {
				update_post_meta( $post_id, '_' . $prop,
					wp3cxw_normalize_newline_deep( $value ) );
			}

			if ( wp3cxw_is_valid_locale( $this->locale ) ) {
				update_post_meta( $post_id, '_locale', $this->locale );
			}

			if ( $this->initial() ) {
				$this->id = $post_id;
				do_action( 'wp3cxw_after_create', $this );
			} else {
				do_action( 'wp3cxw_after_update', $this );
			}

			do_action( 'wp3cxw_after_save', $this );
		}

		return $post_id;
	}

	public function copy() {
		$new = new self;
		$new->title = $this->title . '_copy';
		$new->locale = $this->locale;
		$new->properties = $this->properties;

		return apply_filters( 'wp3cxw_copy', $new, $this );
	}

	public function delete() {
		if ( $this->initial() ) {
			return;
		}

		if ( wp_delete_post( $this->id, true ) ) {
			$this->id = 0;
			return true;
		}

		return false;
	}

	public function shortcode( $args = '' ) {
		$args = wp_parse_args( $args, array() );
		$title = str_replace( array( '"', '[', ']' ), '', $this->title );
    $shortcode = sprintf( '[3cx-webinar id="%1$d" title="%2$s"]', $this->id, $title );
		return apply_filters( 'wp3cxw_webinar_form_shortcode', $shortcode, $args, $this );
	}
}
