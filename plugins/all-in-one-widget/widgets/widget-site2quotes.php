<?php
/**
 * Site2Quotes Widget Class
 */
if ( !defined('ABSPATH')) exit;

class Themeidol_QuoteOfDay_Site2Quotes extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'themeidol_site2quotes_widget', // Base ID
			__('Themeidol-Quote of the Day - Site2Quotes', 'themeidol-all-widget'), // Name
			array( 'description' => __( 'Display quote of the day on your website/blog automatically updated everyday!', 'themeidol-all-widget' ), ) // Args
		);
		// Refreshing the widget's cached output with each new post
	    add_action( 'save_post',    array( $this, 'flush_widget_cache' ) );
	    add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
	    add_action( 'delete_attachment', array( $this, 'flush_group_cache' ) );
	    add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );   
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$cache    = (array) wp_cache_get( 'themeidol-site2quotes', 'widget' );

         if(!is_array($cache)) $cache = array();
      
         if(isset($cache[$args['widget_id']])){
            echo $cache[$args['widget_id']];
            return;
         }
     	 ob_start();
		$qtype = apply_filters('widget_title', esc_attr($instance['qtype']));
		$qdisplay = apply_filters('widget_title', esc_attr($instance['qdisplay']));

                $title_hash = array(
                     "none" => "Select Category",
					 "any" => "All",
                     "love" => "Love Quotes (Popular)",
                     "inspirational" => "Inspirational Quotes (Popular)",
                     "funny" => "Funny Quotes",
					 "friendship" => "Friendship Quotes",
					 "life" => "Life Quotes",
					 "motivational" => "Motivational Quotes",
					 "birthday" => "Birthday Quotes",
					 "famous" => "Famous Quotes",
					 "relationship" => "Relationship Quotes",
					 "positive" => "Positive Quotes",
					 "sad" => "Sad Quotes",
					 "happiness" => "Happiness Quotes",
					 "family" => "Family Quotes",
					 "smile" => "Smile Quotes",
					 "best" => "Best Quotes",
					 "success" => "Success Quotes",
					 "romantic" => "Romantic Quotes",
					 "good" => "Good Quotes",
					 "anniversary" => "Anniversary Quotes",
					 "attitude" => "Attitude Quotes",
					 "trust" => "Trust Quotes",
                 );
                if (strpos($args['before_widget'], 'widget ') !== false) {
            		$before_widget = preg_replace('/widget /', "idol-widget ", $args['before_widget'], 1);
        		}
		echo $before_widget;
		if ( ! empty( $qtype) )
			$webpageurl=$_SERVER['REQUEST_URI'];
			$website=$_SERVER['SERVER_NAME'];
			$visitorip=$_SERVER['REMOTE_ADDR'];
					$resp = wp_remote_get('http://api.site2quotes.com/wp/quotations.aspx?category=' .$qtype. '&qdisplay=' .$qdisplay. '&visitorip=' .$visitorip. '&domain=' .$website. '&webpage=' .$webpageurl. '' );
			if ( 200 == $resp['response']['code'] ) {
			$body = $resp['body'];
			echo __($body,'text_domain');
			// perform action with the content.
		}

		echo $args['after_widget'];

	$widget_string = ob_get_flush();
	$cache[$args['widget_id']] = $widget_string;
	wp_cache_add('themeidol-site2quotes', $cache, 'widget');
	}

	public function flush_widget_cache() {
    		wp_cache_delete( 'themeidol-site2quotes', 'widget' );
  	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'qtype' ] ) ) {
			$qtype = esc_attr($instance[ 'qtype' ]);
		}
		else {
			$qtype = "day";
		}
        if ( isset( $instance[ 'qdisplay' ] ) ) {
			$qdisplay = esc_attr($instance[ 'qdisplay' ]);
		}
		else {
			$qdisplay = "image";
		}
		?>
	         <p>
			<select id="<?php echo $this->get_field_id( 'qtype' ); ?>" name="<?php echo $this->get_field_name( 'qtype' ); ?>" class="widefat" style="width:100%;">
				<option value="none" <?php if ( 'none' == $qtype ) echo 'selected="selected"'; ?>>Select Category</option>
				<option value="any" <?php if ( 'any' == $qtype ) echo 'selected="selected"'; ?>>Show Quotes of any Category</option>
				<option value="love" <?php if ( 'love' == $qtype ) echo 'selected="selected"'; ?>>Love Quotes (Popular)</option>
				<option value="inspirational" <?php if ( 'inspirational' == $qtype ) echo 'selected="selected"'; ?>>Inspirational Quotes (Popular)</option>
				<option value="funny" <?php if ( 'funny' == $qtype ) echo 'selected="selected"'; ?>>Funny Quotes</option>
				<option value="friendship" <?php if ( 'friendship' == $qtype ) echo 'selected="selected"'; ?>>Friendship Quotes</option>
				<option value="life" <?php if ( 'life' == $qtype ) echo 'selected="selected"'; ?>>Life Quotes</option>
				<option value="motivational" <?php if ( 'motivational' == $qtype ) echo 'selected="selected"'; ?>>Motivational Quotes</option>
				<option value="birthday" <?php if ( 'birthday' == $qtype ) echo 'selected="selected"'; ?>>Birthday Quotes</option>
				<option value="famous" <?php if ( 'famous' == $qtype ) echo 'selected="selected"'; ?>>Famous Quotes</option>
				<option value="relationship" <?php if ( 'relationship' == $qtype ) echo 'selected="selected"'; ?>>Relationship Quotes</option>
				<option value="positive" <?php if ( 'positive' == $qtype ) echo 'selected="selected"'; ?>>Positive Quotes</option>
				<option value="sad" <?php if ( 'sad' == $qtype ) echo 'selected="selected"'; ?>>Sad Quotes</option>
				<option value="happiness" <?php if ( 'happiness' == $qtype ) echo 'selected="selected"'; ?>>Happiness Quotes</option>
				<option value="family" <?php if ( 'family' == $qtype ) echo 'selected="selected"'; ?>>Family Quotes</option>
				<option value="smile" <?php if ( 'smile' == $qtype ) echo 'selected="selected"'; ?>>Smile Quotes</option>
				<option value="best" <?php if ( 'best' == $qtype ) echo 'selected="selected"'; ?>>Best Quotes</option>
				<option value="success" <?php if ( 'success' == $qtype ) echo 'selected="selected"'; ?>>Success Quotes</option>
				<option value="romantic" <?php if ( 'romantic' == $qtype ) echo 'selected="selected"'; ?>>Romantic Quotes</option>
				<option value="good" <?php if ( 'good' == $qtype ) echo 'selected="selected"'; ?>>Good Quotes</option>
				<option value="anniversary" <?php if ( 'anniversary' == $qtype ) echo 'selected="selected"'; ?>>Anniversary Quotes</option>
				<option value="attitude" <?php if ( 'attitude' == $qtype ) echo 'selected="selected"'; ?>>Attitude Quotes</option>
				<option value="trust" <?php if ( 'trust' == $qtype ) echo 'selected="selected"'; ?>>Trust Quotes</option>
			</select>
            <label for="<?php echo $this->get_field_id( 'qdisplay' ); ?>">Select Display Type:</label> 
			<select id="<?php echo $this->get_field_id( 'qdisplay' ); ?>" name="<?php echo $this->get_field_name( 'qdisplay' ); ?>" class="widefat" style="width:100%;">
            <option value="image" <?php if ( 'image' == $qdisplay ) echo 'selected="selected"'; ?>>Image</option>
			<option value="text" <?php if ( 'text' == $qdisplay ) echo 'selected="selected"'; ?>>Text</option>
            </select>
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['qtype'] = ( ! empty( $new_instance['qtype'] ) ) ? strip_tags( $new_instance['qtype'] ) : '';
$instance['qdisplay'] = ( ! empty( $new_instance['qdisplay'] ) ) ? strip_tags( $new_instance['qdisplay'] ) : '';
		return $instance;
	}


} // class QuoteOfDay_Site2Quotes
add_action( 'widgets_init', create_function( '', 'return register_widget("Themeidol_QuoteOfDay_Site2Quotes");' ) );