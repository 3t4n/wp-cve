<?php

if (!defined('ABSPATH'))
    die('Restricted Access');
	// Add widgets files


	class MJTC_main_plugin_pages_widget extends WP_Widget {


	/* ---------------------------------------------------------------------------
	 * Constructor
	 * --------------------------------------------------------------------------- */
	function __construct(){
		$widget_ops = array( 'classname' => 'MJTC_main_plugin_pages_widget', 'description' => esc_html__( 'Majestic Support Pages', 'majestic-support' ) );
		parent::__construct( 'MJTC_main_plugin_pages_widget_options', esc_html__( 'Majestic Support Pages', 'majestic-support' ), $widget_ops );
		$this->alt_option_name = 'MJTC_main_plugin_pages_widget_options';
	}


	/* ---------------------------------------------------------------------------
	 * Outputs the HTML for this widget.
	 * --------------------------------------------------------------------------- */
	function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) ) $args['widget_id'] = null;
		extract( $args, EXTR_SKIP );
		echo wp_kses($before_widget, MJTC_ALLOWED_TAGS);
		$mod = "majesticsupportpages";
		$layoutName = $mod . uniqid();
    	$instance['mspageid'] = majesticsupport::getPageid();
		$data = '['.wp_kses($instance['majesticsupportpages'], MJTC_ALLOWED_TAGS).']';
		echo wp_kses($data, MJTC_ALLOWED_TAGS);

		echo wp_kses($after_widget, MJTC_ALLOWED_TAGS);
	}


	/* ---------------------------------------------------------------------------
	 * Deals with the settings when they are saved by the admin.
	 * --------------------------------------------------------------------------- */

	public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['majesticsupportpages'] = (!empty($new_instance['majesticsupportpages']) ) ? MJTC_majesticsupportphplib::MJTC_strip_tags($new_instance['majesticsupportpages']) : '';
        return $instance;
    }


	/* ---------------------------------------------------------------------------
	 * Displays the form for this widget on the Widgets page of the WP Admin area.
	 * --------------------------------------------------------------------------- */
	function form( $instance ) {

		$majesticsupportpages = isset( $instance['majesticsupportpages'] ) ?  $instance['majesticsupportpages']  : 'majesticsupport';
		?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'majesticsupportpages' ) ); ?>"><?php __( 'Majestic Support Pages', 'majestic-support' ); ?>:</label>
				<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'majesticsupportpages' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'majesticsupportpages' ) ); ?>" >
					<option value="majesticsupport" <?php echo wp_kses(( $majesticsupportpages == 'majesticsupport' ) ? 'selected="selected"' : false, MJTC_ALLOWED_TAGS); ?>><?php echo esc_html__('Majestic Support control panel','majestic-support'); ?></option>
					<option value="majesticsupport_addticket" <?php echo wp_kses(( $majesticsupportpages == 'majesticsupport_addticket' ) ? 'selected="selected"' : false, MJTC_ALLOWED_TAGS); ?>><?php echo esc_html__('Add ticket','majestic-support'); ?></option>
					<option value="majesticsupport_mytickets" <?php echo wp_kses(( $majesticsupportpages == 'majesticsupport_mytickets' ) ? 'selected="selected"' : false, MJTC_ALLOWED_TAGS); ?>><?php echo esc_html__('My tickets','majestic-support'); ?></option>

				<?php if(in_array('download', majesticsupport::$_active_addons)){ ?>
					<option value="majesticsupport_downloads" <?php echo wp_kses(( $majesticsupportpages == 'majesticsupport_downloads' ) ? 'selected="selected"' : false, MJTC_ALLOWED_TAGS); ?>><?php echo esc_html__('List downloads','majestic-support'); ?></option>
					<option value="majesticsupport_downloads_latest" <?php echo wp_kses(( $majesticsupportpages == 'majesticsupport_downloads_latest' ) ? 'selected="selected"' : false, MJTC_ALLOWED_TAGS); ?>><?php echo esc_html__('Latest downloads','majestic-support'); ?></option>
					<option value="majesticsupport_downloads_popular" <?php echo wp_kses(( $majesticsupportpages == 'majesticsupport_downloads_popular' ) ? 'selected="selected"' : false, MJTC_ALLOWED_TAGS); ?>><?php echo esc_html__('Popular downloads','majestic-support'); ?></option>
				<?php }?>

				<?php if(in_array('knowledgebase', majesticsupport::$_active_addons)){ ?>
					<option value="majesticsupport_knowledgebase" <?php echo wp_kses(( $majesticsupportpages == 'majesticsupport_knowledgebase' ) ? 'selected="selected"' : false, MJTC_ALLOWED_TAGS); ?>><?php echo esc_html__('List knowledge base','majestic-support'); ?></option>
					<option value="majesticsupport_knowledgebase_latest" <?php echo wp_kses(( $majesticsupportpages == 'majesticsupport_knowledgebase_latest' ) ? 'selected="selected"' : false, MJTC_ALLOWED_TAGS); ?>><?php echo esc_html__('Latest knowledge base','majestic-support'); ?></option>
					<option value="majesticsupport_knowledgebase_popular" <?php echo wp_kses(( $majesticsupportpages == 'majesticsupport_knowledgebase_popular' ) ? 'selected="selected"' : false, MJTC_ALLOWED_TAGS); ?>><?php echo esc_html__('Popular knowledge base','majestic-support'); ?></option>
				<?php }?>

				<?php if(in_array('faq', majesticsupport::$_active_addons)){ ?>
					<option value="majesticsupport_faqs" <?php echo wp_kses(( $majesticsupportpages == 'majesticsupport_faqs' ) ? 'selected="selected"' : false, MJTC_ALLOWED_TAGS); ?>><?php echo esc_html__('List FAQ`s','majestic-support'); ?></option>
					<option value="majesticsupport_faqs_latest" <?php echo wp_kses(( $majesticsupportpages == 'majesticsupport_faqs_latest' ) ? 'selected="selected"' : false, MJTC_ALLOWED_TAGS); ?>><?php echo esc_html__('Latest FAQ`s','majestic-support'); ?></option>
					<option value="majesticsupport_faqs_popular" <?php echo wp_kses(( $majesticsupportpages == 'majesticsupport_faqs_popular' ) ? 'selected="selected"' : false, MJTC_ALLOWED_TAGS); ?>><?php echo esc_html__('Popular FAQ`s','majestic-support'); ?></option>
				<?php }?>

				<?php if(in_array('announcement', majesticsupport::$_active_addons)){ ?>
					<option value="majesticsupport_announcements" <?php echo wp_kses(( $majesticsupportpages == 'majesticsupport_announcements' ) ? 'selected="selected"' : false, MJTC_ALLOWED_TAGS); ?>><?php echo esc_html__('List announcements','majestic-support'); ?></option>
					<option value="majesticsupport_announcements_latest" <?php echo wp_kses(( $majesticsupportpages == 'majesticsupport_announcements_latest' ) ? 'selected="selected"' : false, MJTC_ALLOWED_TAGS); ?>><?php echo esc_html__('Latest announcements','majestic-support'); ?></option>
					<option value="majesticsupport_announcements_popular" <?php echo wp_kses(( $majesticsupportpages == 'majesticsupport_announcements_popular' ) ? 'selected="selected"' : false, MJTC_ALLOWED_TAGS); ?>><?php echo esc_html__('Popular announcements','majestic-support'); ?></option>
				<?php }?>
				</select>
			</p>
		<?php
	}
}

	function MJTC_main_plugin_register_widgets(){
		register_widget('MJTC_main_plugin_pages_widget');
	}

	add_action('widgets_init','MJTC_main_plugin_register_widgets');
?>
