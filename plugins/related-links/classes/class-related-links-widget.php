<?php

if (!class_exists('Related_Links_Widget')) 
{
class Related_Links_Widget 
{
	/**
	 * Constructor
	 */
	public function __construct()
	{		
		// Set hooks
		add_action( 'widgets_init', array( $this, 'add_widgets' ) );
	}
	
	/**
	 * Add widget
	 */
	public function add_widgets()
	{	
		register_widget( 'Related_Links_Widget_Box' );
	}
	
}
}

if (!class_exists('Related_Links_Widget_Box')) 
{
class Related_Links_Widget_Box extends WP_Widget 
{

	/**
	 * Constructor
	 */
	public function __construct() 
	{
		parent::__construct( false, __( 'Related Links', 'related-links' ), array( 'description' => __( 'A list of related links', 'related-links' ) ) );
	}

	/**
	 * Sanitize widget form values as they are saved.
	 */
	public function update( $new_instance, $old_instance ) 
	{
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		
		return $instance;
	}
	
	/**
	 * Backend
	 */
	public function form( $instance ) 
	{
		if ( isset( $instance[ 'title' ] ) ) 
		{
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Related Links', 'related-links' );
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
	}
	
	/**
	 * Frontend
	 */
	public function widget( $args, $instance ) 
	{
		extract( $args );

		$title = apply_filters( 'widget_title', $instance['title'] );
		$links = get_related_links();
		?>
		<?php if ( !empty( $links ) ) : ?>
		
			<?php echo $before_widget; ?>
			
			<?php if ( !empty( $title ) ) : ?>
			
				<?php echo $before_title . $title . $after_title; ?>
				
			<?php endif; ?>
			
			<?php related_links(); ?>
			
			<?php echo $after_widget; ?>
			
		<?php endif; ?>
		
		<?php
	}

}
}
?>