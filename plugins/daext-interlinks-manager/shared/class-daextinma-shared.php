<?php

/*
 * this class should be used to stores properties and methods shared by the
 * admin and public side of wordpress
 */

class Daextinma_Shared {

	//regex
	public $regex_number_ten_digits = '/^\s*\d{1,10}\s*$/';

	protected static $instance = null;

	private $data = array();

	private function __construct() {

		$this->data['slug'] = 'daextinma';
		$this->data['ver']  = '1.07';
		$this->data['dir']  = substr( plugin_dir_path( __FILE__ ), 0, - 7 );
		$this->data['url']  = substr( plugin_dir_url( __FILE__ ), 0, - 7 );

		//Here are stored the plugin option with the related default values
		$this->data['options'] = [

			//database version -----------------------------------------------------
			$this->get( 'slug' ) . "_database_version"                   => "0",

			//optimization ---------------------------------------------------------
			$this->get( 'slug' ) . '_optimization_num_of_characters'     => 1000,
			$this->get( 'slug' ) . '_optimization_delta'                 => 2,

			//juice ----------------------------------------------------------------
			$this->get( 'slug' ) . '_default_seo_power'                  => 1000,
			$this->get( 'slug' ) . '_penality_per_position_percentage'   => "1",
			$this->get( 'slug' ) . '_remove_link_to_anchor'              => "1",
			$this->get( 'slug' ) . '_remove_url_parameters'              => "0",

			//analysis ----------------------------------------------------------
			$this->get( 'slug' ) . '_set_max_execution_time'             => "1",
			$this->get( 'slug' ) . '_max_execution_time_value'           => "300",
			$this->get( 'slug' ) . '_set_memory_limit'                   => "0",
			$this->get( 'slug' ) . '_memory_limit_value'                 => "512",
			$this->get( 'slug' ) . '_limit_posts_analysis'               => "1000",
			$this->get( 'slug' ) . '_dashboard_post_types'               => [ 'post', 'page' ],
			$this->get( 'slug' ) . '_juice_post_types'                   => [ 'post', 'page' ],

			//meta boxes -----------------------------------------------------------
			$this->get( 'slug' ) . '_interlinks_options_post_types'      => [ 'post', 'page' ],
			$this->get( 'slug' ) . '_interlinks_optimization_post_types' => [ 'post', 'page' ],

		];

	}

	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;

	}

	//retrieve data
	public function get( $index ) {
		return $this->data[ $index ];
	}

	/*
	 * Get the number of manual interlinks in a given string
	 *
	 * @param string $string The string in which the search should be performed
	 * @return int The number of internal links in the string
	 */
	public function get_manual_interlinks( $string ) {

		//remove the HTML comments
		$string = $this->remove_html_comments( $string );

		//remove script tags
		$string = $this->remove_script_tags( $string );

		/*
		 * Get the website url and escape the regex character. # and
		 * whitespace ( used with the 'x' modifier ) are not escaped, thus
		 * should not be included in the $site_url string
		 */
		$site_url = preg_quote( get_home_url() );

		//working regex
		$num_matches = preg_match_all(
			'{
            <a                      #1 Match the element a start-tag
            [^>]+                   #2 Match everything except > for at least one time
            href\s*=\s*             #3 Equal may have whitespaces on both sides
            ([\'"]?)                #4 Match double quotes, single quote or no quote ( captured for the backreference \1 )
            ' . $site_url . '       #5 The site URL ( Scheme and Domain )
            [^\'">\s]+              #6 The rest of the URL ( Path and/or File )
            (\1)                    #7 Backreference that matches the href value delimiter matched at line 4
            [^>]*                   #8 Any character except > zero or more times
            >                       #9 End of the start-tag
            .*?                     #10 Link text or nested tags. After the dot ( enclose in parenthesis ) negative lookbehinds can be applied to avoid specific stuff inside the link text or nested tags. Example with single negative lookbehind (.(?<!word1))*? Example with multiple negative lookbehind (.(?<!word1)(?<!word2)(?<!word3))*?
            <\/a\s*>                #11 Element a end-tag with optional white-spaces characters before the >
            }ix',
			$string, $matches );

		return $num_matches;

	}

	/*
	 * Get the raw post_content of the specified post
	 *
	 * @param $post_id The ID of the post
	 * @return string The raw post content
	 */
	public function get_raw_post_content( $post_id ) {

		global $wpdb;
		$table_name = $wpdb->prefix . "posts";
		$safe_sql   = $wpdb->prepare( "SELECT post_content FROM $table_name WHERE ID = %d", $post_id );
		$post_obj   = $wpdb->get_row( $safe_sql );

		return $post_obj->post_content;

	}

	/*
	 * The optimization is calculated based on:
	 * - the "Optimization Delta" option
	 * - the number of interlinks
	 * - the content length
	 * True is returned if the content is optimized, False if it's not optimized
	 *
	 * @param int $number_of_interlinks The overall number of interlinks ( manual interlinks + auto interlinks )
	 * @param int $content_length The content length
	 * @return bool True if is optimized, False if is not optimized
	 */
	public function calculate_optimization( $number_of_interlinks, $content_length ) {

		//get the values of the options
		$optimization_num_of_characters = (int) get_option( $this->get( 'slug' ) . '_optimization_num_of_characters' );
		$optimization_delta             = (int) get_option( $this->get( 'slug' ) . '_optimization_delta' );

		//determines if this post is optimized
		$optimal_number_of_interlinks = (int) $content_length / $optimization_num_of_characters;
		if (
			( $number_of_interlinks >= ( $optimal_number_of_interlinks - $optimization_delta ) ) and
			( $number_of_interlinks <= ( $optimal_number_of_interlinks + $optimization_delta ) )
		) {
			$is_optimized = true;
		} else {
			$is_optimized = false;
		}

		return $is_optimized;

	}

	/*
	 * The optimal number of interlinks is calculated by dividing the content
	 * length for the value in the "Characters per Interlink" option and
	 * converting the result to an integer
	 *
	 * @param int $content_length The content length
	 * @return int The number of recommended interlinks
	 */
	public function calculate_recommended_interlinks( $content_length ) {

		//get the values of the options
		$optimization_num_of_characters = get_option( $this->get( 'slug' ) . '_optimization_num_of_characters' );

		//determines the optimal number of interlinks
		$optimal_number_of_interlinks = $content_length / $optimization_num_of_characters;

		return intval( $optimal_number_of_interlinks, 10 );

	}

	/*
	 * The minimum number of interlinks suggestion is calculated by subtracting
	 * half of the optimization delta from the optimal number of interlinks
	 *
	 * @param int The post id
	 * @return int The minimum number of interlinks suggestion
	 */
	public function get_suggested_min_number_of_interlinks( $post_id ) {

		//get the content length of the raw post
		$content_length = mb_strlen( $this->get_raw_post_content( $post_id ) );

		//get the values of the options
		$optimization_num_of_characters = intval( get_option( $this->get( 'slug' ) . '_optimization_num_of_characters' ),
			10 );
		$optimization_delta             = intval( get_option( $this->get( 'slug' ) . '_optimization_delta' ), 10 );

		//determines the optimal number of interlinks
		$optimal_number_of_interlinks = $content_length / $optimization_num_of_characters;

		//get the minimum number of interlinks
		$min_number_of_interlinks = intval( ( $optimal_number_of_interlinks - ( $optimization_delta / 2 ) ), 10 );

		//set to zero negative values
		if ( $min_number_of_interlinks < 0 ) {
			$min_number_of_interlinks = 0;
		}

		return $min_number_of_interlinks;

	}

	/*
	 * The maximum number of interlinks suggestion is calculated by adding
	 * half of the optimization delta to the optimal number of interlinks
	 *
	 * @param int The post id
	 * @return int The maximum number of interlinks suggestion
	 */
	public function get_suggested_max_number_of_interlinks( $post_id ) {

		//get the content length of the raw post
		$content_length = mb_strlen( $this->get_raw_post_content( $post_id ) );

		///get the values of the options
		$optimization_num_of_characters = get_option( $this->get( 'slug' ) . '_optimization_num_of_characters' );
		$optimization_delta             = get_option( $this->get( 'slug' ) . '_optimization_delta' );

		//determines the optimal number of interlinks
		$optimal_number_of_interlinks = $content_length / $optimization_num_of_characters;

		return intval( ( $optimal_number_of_interlinks + ( $optimization_delta / 2 ) ), 10 );

	}

	/*
	 * Calculate the link juice of a links based on the given parameters.
	 *
	 * @param $post_content The post content
	 * @param $post_id The post id
	 * @param $link_postition The position of the link in the string ( the line where the link string starts )
	 * @return int The link juice of the link
	 */
	public function calculate_link_juice( $post_content, $post_id, $link_position ) {

		//Get the SEO power of the post
		$seo_power = get_post_meta( $post_id, '_daextinma_seo_power', true );
		if ( strlen( trim( $seo_power ) ) == 0 ) {
			$seo_power = (int) get_option( $this->get( 'slug' ) . '_default_seo_power' );
		}

		/*
		 * Divide the SEO power for the total number of links ( all the links,
		 * external and internal are considered )
		 */
		$juice_per_link = $seo_power / $this->get_number_of_links( $post_content );

		/*
		 * Calculate the index of the link on the post ( example 1 for the first
		 * link or 3 for the third link )
		 * A regular expression that counts the links on a string that starts
		 * from the beginning of the post and ends at the $link_position is used
		 */
		$post_content_before_the_link = substr( $post_content, 0, $link_position );
		$number_of_links_before       = $this->get_number_of_links( $post_content_before_the_link );

		/*
		 * Remove a percentage of the $juice_value based on the number of links
		 * before this one
		 */
		$penality_per_position_percentage = (int) get_option( $this->get( 'slug' ) . '_penality_per_position_percentage' );
		$link_juice                       = $juice_per_link - ( ( $juice_per_link / 100 * $penality_per_position_percentage ) * $number_of_links_before );

		//return the link juice or 0 if the calculated link juice is negative
		if ( $link_juice < 0 ) {
			$link_juice = 0;
		}

		return $link_juice;

	}

	/*
	 * Get the total number of links ( any kind of link: internal, external,
	 * nofollow, dofollow ) available in the passed string
	 *
	 * @param $s The string on which the number of links should be counted
	 * @return int The number of links found on the string
	 */
	public function get_number_of_links( $s ) {

		//remove the HTML comments
		$s = $this->remove_html_comments( $s );

		//remove script tags
		$s = $this->remove_script_tags( $s );

		$num_matches = preg_match_all(
			'{<a                                #1 Begin the element a start-tag
            [^>]+                               #2 Any character except > at least one time
            href\s*=\s*                         #3 Equal may have whitespaces on both sides
            ([\'"]?)                            #4 Match double quotes, single quote or no quote ( captured for the backreference \1 )
            [^\'">\s]+                          #5 The site URL
            \1                                  #6 Backreference that matches the href value delimiter matched at line 4     
            [^>]*                               #7 Any character except > zero or more times
            >                                   #8 End of the start-tag
            .*?                                 #9 Link text or nested tags. After the dot ( enclose in parenthesis ) negative lookbehinds can be applied to avoid specific stuff inside the link text or nested tags. Example with single negative lookbehind (.(?<!word1))*? Example with multiple negative lookbehind (.(?<!word1)(?<!word2)(?<!word3))*?
            <\/a\s*>                            #10 Element a end-tag with optional white-spaces characters before the >
            }ix',
			$s, $matches );

		return $num_matches;

	}

	/*
	 * Given a link returns it with the anchor link removed.
	 *
	 * @param $s The link that should be analyzed
	 * @return string The link with the link anchor removed
	 */
	public function remove_link_to_anchor( $s ) {

		$s = preg_replace_callback(
			'/([^#]+)               #Everything except # one or more times ( captured )
            \#.*                    #The # with anything the follows zero or more times
            /ux',
			array( $this, 'preg_replace_callback_4' ),
			$s
		);

		return $s;

	}

	/*
	 * Given an URL the parameter part is removed
	 *
	 * @param $s The URL
	 * @return string The URL with the URL parameters removed
	 */
	public function remove_url_parameters( $s ) {

		$s = preg_replace_callback(
			'/([^?]+)               #Everything except ? one or more time ( captured )
            \?.*                    #The ? with anything the follows zero or more times
            /ux',
			array( $this, 'preg_replace_callback_5' ),
			$s
		);

		return $s;

	}

	/*
	 * Callback of the preg_replace_callback() function
	 *
	 * This callback is used to avoid an anonimus function as a parameter of the
	 * preg_replace_callback() function for PHP backward compatibility
	 *
	 * Look for uses of preg_replace_callback_4 to find which
	 * preg_replace_callback() function is actually using this callback
	 */
	public function preg_replace_callback_4( $m ) {

		return $m[1];

	}

	/*
	 * Callback of the preg_replace_callback() function
	 *
	 * This callback is used to avoid an anonimus function as a parameter of the
	 * preg_replace_callback() function for PHP backward compatibility
	 *
	 * Look for uses of preg_replace_callback_5 to find which
	 * preg_replace_callback() function is actually using this callback
	 */
	public function preg_replace_callback_5( $m ) {

		return $m[1];

	}

	/*
	 * Remove the HTML comment ( comment enclosed between <!-- and --> )
	 *
	 * @param $content The HTML with the comments
	 * @return string The HTML without the comments
	 */
	public function remove_html_comments( $content ) {

		$content = preg_replace(
			'/
            <!--                                #1 Comment Start
            .*?                                 #2 Any character zero or more time with a lazy quantifier
            -->                                 #3 Comment End
            /ix',
			'',
			$content
		);

		return $content;

	}

	/*
	 * Remove the script tags
	 *
	 * @param $content The HTML with the script tags
	 * @return string The HTML without the script tags
	 */
	public function remove_script_tags( $content ) {

		$content = preg_replace(
			'/
            <                                   #1 Begin the start-tag
            script                              #2 The script tag name
            (\s+[^>]*)?                         #3 Match the rest of the start-tag
            >                                   #4 End the start-tag
            .*?                                 #5 The element content ( with the "s" modifier the dot matches also the new lines )
            <\/script\s*>                       #6 The script end-tag with optional white-spaces before the closing >
            /ixs',
			'',
			$content
		);

		return $content;

	}

	/*
	 * If $needle is present in the $haystack array echos 'selected="selected"'.
	 *
	 * @param $haystack Array
	 * @param $needle String
	 */
	public function selected_array( $array, $needle ) {

		if ( is_array( $array ) and in_array( $needle, $array ) ) {
			return 'selected="selected"';
		}

	}

	/*
	 * Given the post object, the HTML content of the Interlinks Optimization meta-box is returned.
	 *
	 * @param $post The post object.
	 * @return String The HTML of the Interlinks Optimization meta-box.
	 */
	public function generate_interlinks_optimization_metabox_html( $post ) {

		$html = '';

		$suggested_min_number_of_interlinks = $this->get_suggested_min_number_of_interlinks( $post->ID );
		$suggested_max_number_of_interlinks = $this->get_suggested_max_number_of_interlinks( $post->ID );
		$number_of_manual_interlinks        = $this->get_manual_interlinks( $post->post_content );
		$total_number_of_interlinks         = $number_of_manual_interlinks;
		if ( $total_number_of_interlinks >= $suggested_min_number_of_interlinks and $total_number_of_interlinks <= $suggested_max_number_of_interlinks ) {
			$html .= '<p>' . esc_html__( 'The number of internal links included in this post is optimized.', 'daext-interlinks-manager') . '</p>';
		} else {
			$html .= '<p>' . esc_html__( 'Please optimize the number of internal links.', 'daext-interlinks-manager') . '</p>';
			$html .= '<p>' . esc_html__( 'This post currently has', 'daext-interlinks-manager') . '&nbsp' . esc_html( $total_number_of_interlinks ) . '&nbsp' . esc_html( _n( 'internal link',
					'internal links', $total_number_of_interlinks, 'interlinks-manager' ) ) . '.&nbsp';

			if ( $suggested_min_number_of_interlinks === $suggested_max_number_of_interlinks ) {
				$html .= esc_html__( 'However, based on the content length and on your options, their number should be', 'daext-interlinks-manager') . '&nbsp' . esc_html( $suggested_min_number_of_interlinks ) . '.</p>';
			} else {
				$html .= esc_html__( 'However, based on the content length and on your options, their number should be included between', 'daext-interlinks-manager') . '&nbsp' . esc_html( $suggested_min_number_of_interlinks ) . '&nbsp' . esc_html__( 'and', 'daext-interlinks-manager') . '&nbsp' . esc_html( $suggested_max_number_of_interlinks ) . '.</p>';
			}
		}

		return $html;

	}

	/**
	 * Returns the number of items in the "anchors" database table with the specified "url".
	 *
	 * @param $url
	 *
	 * @return int
	 */
	public function get_anchors_with_url( $url ) {

		global $wpdb;
		$table_name  = $wpdb->prefix . $this->get( 'slug' ) . "_anchors";
		$safe_sql    = $wpdb->prepare( "SELECT COUNT(*) FROM $table_name WHERE url = %s ORDER BY id DESC", $url );
		$total_items = $wpdb->get_var( $safe_sql );

		return intval( $total_items );

	}

	/*
	 * Set the PHP "Max Execution Time" and "Memory Limit" based on the values defined in the options.
	 */
	public function set_met_and_ml() {

		/*
		 * Set the custom "Max Execution Time Value" defined in the options if
		 * the 'Set Max Execution Time' option is set to "Yes"
		 */
		if ( intval( get_option( $this->get( 'slug' ) . '_set_max_execution_time' ), 10 ) === 1 ) {
			ini_set( 'max_execution_time', intval( get_option( "daextinma_max_execution_time_value" ), 10 ) );
		}

		/*
		 * Set the custom "Memory Limit Value" ( in megabytes ) defined in the
		 * options if the 'Set Memory Limit' option is set to "Yes"
		 */
		if ( intval( get_option( $this->get( 'slug' ) . '_set_memory_limit' ), 10 ) === 1 ) {
			ini_set( 'memory_limit', intval( get_option( "daextinma_memory_limit_value" ), 10 ) . 'M' );
		}

	}

}