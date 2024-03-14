<?php
/**
 * Widget doropdown categories
 *
 * @package YAHMAN Add-ons
 */


class yahman_addons_dd_categories_widget extends WP_Widget {

	
	function __construct() {
		parent::__construct(
			'ya_dd_categories', // Base ID
			esc_html__( '[YAHMAN Add-ons] Categories', 'yahman-add-ons' ), // Name
			array( 'description' => esc_html__( 'Drop Down Categories widget without JavaScript', 'yahman-add-ons' ), ) // Args
		);
	}
	public function widget( $args, $instance ) {

		if(!YAHMAN_ADDONS_TEMPLATE){
			add_action( 'wp_footer', 'yahman_addons_enqueue_style_dd' );
		}

		$title = esc_html( ! empty( $instance['title'] ) ? $instance['title'] : '' );
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$widget_num = preg_replace('/[^0-9]/', '', $args['widget_id']);

		$args['before_widget'] = str_replace( 'widget_ya_dd_categories','widget_ya_dd_categories widget_ya_dd',$args['before_widget']);

		echo $args['before_widget'];

		if ( $title ) {
			echo $args['before_title'] . esc_html($title) . $args['after_title'];
		}

		echo '<nav class="dd_widget dib relative fs13 w100"><input type="checkbox" class="dn" id="toggle_dd_categories-'.$widget_num.'"><label for="toggle_dd_categories-'.$widget_num.'" class="dd_label f_box ai_c m_s w100 m0 p10">'.esc_html__( 'Select Category', 'yahman-add-ons' ).'<span class="caret_right" style="margin-left:auto;"><svg width="9" height="9" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M18.8,12c0,0.4-0.2,0.8-0.4,1.1L7.8,23.6C7.5,23.8,7.1,24,6.8,24c-0.8,0-1.5-0.7-1.5-1.5v-21C5.3,0.7,5.9,0,6.8,0 c0.4,0,0.8,0.2,1.1,0.4l10.5,10.5C18.6,11.2,18.8,11.6,18.8,12z"></path></svg></span><span class="caret_down dn" style="margin-left:auto;"><svg width="9" height="9" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M24,6.8c0,0.4-0.2,0.8-0.4,1.1L13.1,18.3c-0.3,0.3-0.7,0.4-1.1,0.4s-0.8-0.2-1.1-0.4L0.4,7.8C0.2,7.5,0,7.1,0,6.8 c0-0.8,0.7-1.5,1.5-1.5h21C23.3,5.3,24,5.9,24,6.8z"></path></svg></span></label><label for="toggle_dd_categories-'.$widget_num.'" class="dd_dummy absolute z1 dn w100" style="z-index:100;"></label><ul class="m_s absolute z5 m0 dn w100" style="list-style:none;z-index:101;">';
		$match = wp_dropdown_categories(array('echo'=>0,'hierarchical'=>1));
		$match = str_replace("<select  name='cat' id='cat' class='postform' >",'',$match);
		$match = str_replace('</select>','',$match);
		preg_match_all('/<option\sclass=[\'"][^\'"]+[\'"]\svalue=[\'"]([^\'"]+)[\'"]>([^\'"]+)<\/option>/iu',$match,$match);
		if (isset($match) && is_array($match)) {
			$i=0;
			while ($i < count($match[1])) {
				echo '<li><a href="'.esc_url(get_category_link($match[1][$i])).'" class="db">'.esc_html($match[2][$i]).'</a></li>'."\n";
				$i++;
			}
		}



		echo '</ul></nav>';
		echo $args['after_widget'];
	}
	public function form( $instance ) {
		$settings = array();
		$settings['title'] = ! empty( $instance['title'] ) ? $instance['title'] : '';

		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'yahman-add-ons' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['title'] ); ?>">
		</p>
		<?php
	}
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		return $instance;
	}

} // class yahman_addons_dd_categories_widget
