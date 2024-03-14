<?php

if(class_exists('RabbitLoader_21_CanonicalUrl')){
    #it seems we have a conflict
    return;
}
/**
 * WordPress adds canonical meta tag for single post, but not for homepage, paginated pages etc.
 */
class RabbitLoader_21_CanonicalUrl {

	public static function init() {
		add_action( 'wp_head', 'RabbitLoader_21_CanonicalUrl::addCanonical', 5);
	}

	public static function addCanonical() {
        if (is_singular()) {
            //we don't need to add it, WP already adds it
			return '';
		}
        try{
            $can_url = '';
            if ( is_home()) {
                $can_url = self::get_home_canonical_url();
            } elseif ( is_post_type_archive()) {
                $can_url = self::get_post_type_archive_canonical_url();
            } elseif ( is_author()) {
                $can_url = self::get_author_url();
            }elseif ( is_tax() || is_category() || is_tag()) {
                $can_url = self::get_term_canonical_url();
            }
    
            if (!empty($can_url)) {
				if(is_wp_error($can_url)){
					RabbitLoader_21_Core::on_exception($can_url);
				}else{
					echo '<meta name="rl:url" content="', esc_url( $can_url ), '" />',"\n";
				}
            }
        }catch(Throwable $e){
            RabbitLoader_21_Core::on_exception($e);
        }
	}

	private static function get_home_canonical_url() {
		$can_url= is_front_page() ? home_url( '/' ) : get_permalink( get_queried_object());
		return self::get_paginated_url($can_url);
	}

	public static function get_term_canonical_url() {
		$can_url= get_term_link( get_queried_object());
		return self::get_paginated_url($can_url);
	}

	private static function get_post_type_archive_canonical_url() {
		$can_url= get_post_type_archive_link(get_post_type());
		return self::get_paginated_url($can_url);
	}

	private static function get_author_url() {
		$can_url= get_author_posts_url( get_queried_object_id());
		return self::get_paginated_url($can_url);
	}

	private static function get_paginated_url( $can_url) {
		$paged = get_query_var('paged');
		if ( $paged < 2 ) {
			return $can_url;
		}
		if (get_option('permalink_structure') =='') {
			$can_url= add_query_arg( 'paged', $paged, $can_url);
		} else {
			$can_url= trailingslashit($can_url) . 'page/' . user_trailingslashit($paged, 'paged');
		}
		return $can_url;
	}
}