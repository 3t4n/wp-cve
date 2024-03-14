<?php

$posts = get_posts( array( 'posts_per_page' => 1 ) );

if( !empty( $posts[0]->post_date ) ) {

	$now = strtotime( $posts[0]->post_date );

} else {

	$now = current_time( 'timestamp' );

}

$now_date = array( 'y' => date( 'Y' , $now ) , 'm' => date( 'm' , $now ) , 'd' => date( 'd' , $now ) );

?>

<div class="wrap <?php echo $APSC->main_slug; ?>">

	<h1><?php printf( __( '%1$s %2$s for %3$s %4$s' , $APSC->ltd ) , __( 'Customize' ) ,  __( 'Sort' , $APSC->ltd ) , __( 'Date' ) , __( 'Archives' ) ); ?></h1>
	
	<h2 class="nav-tab-wrapper"><?php $this->tabs(); ?></h2>
	
	<p>&nbsp;</p>
	
	<div class="metabox-holder columns-1">
	
		<form id="<?php echo esc_attr( $this->action ); ?>_update_form" class="<?php echo $APSC->main_slug; ?>_form" method="post" action="<?php echo esc_url( $APSC->Helper->get_action_link() ); ?>">

			<input type="hidden" name="<?php echo $APSC->Form->field; ?>" value="Y">
			<?php wp_nonce_field( $this->nonce . '_update' , $this->nonce . '_update' ); ?>
			
			<?php
				$args = array(
					'id' => 'default',
					'title' => sprintf( __( 'All %s' , $APSC->ltd ) , __( 'Date' ) ),
					'name_field' => 'default',
				);
			?>

			<?php $this->settings_section( $args ); ?>
			
			<h3><?php printf( __( 'Individual %s sort settings' , $APSC->ltd ) , __( 'Date' ) ); ?></h3>
			
			<?php
				$sections = array(
					'yearly' => array( 'title' => sprintf( '<a href="%s" target="_blank">%s</a>' , esc_url( get_year_link( $now_date['y'] ) ) , __( 'Yearly Archives' , $APSC->ltd ) ) ),
					'monthly' => array( 'title' => sprintf( '<a href="%s" target="_blank">%s</a>' , esc_url( get_month_link( $now_date['y'] , $now_date['m'] ) ) , __( 'Monthly Archives' , $APSC->ltd ) ) ),
					'daily' => array( 'title' => sprintf( '<a href="%s" target="_blank">%s</a>' , esc_url( get_day_link( $now_date['y'] , $now_date['m'] , $now_date['d'] ) ) , __( 'Daily Archives' , $APSC->ltd ) ) ),
				);
			?>
			
			<?php foreach( $sections as $section_id => $section_args ) : ?>
				
				<?php
					$args = array(
						'id' => $section_id,
						'title' => $section_args['title'],
						'name_field' => $section_id,
						'individual' => true,
						'parent_name' => __( 'Date' ),
					);
				?>

				<?php $this->settings_section( $args ); ?>
				
			<?php endforeach; ?>

			<p class="submit">
				<input type="submit" class="button button-primary" value="<?php _e( 'Save' ); ?>" />
			</p>

		</form>

		<form id="<?php echo esc_attr( $this->action ); ?>_remove_form" class="<?php echo $APSC->main_slug; ?>_form" method="post" action="<?php echo esc_url( $APSC->Helper->get_action_link() ); ?>">

			<input type="hidden" name="<?php echo $APSC->Form->field; ?>" value="Y">
			<?php wp_nonce_field( $this->nonce . '_remove' , $this->nonce . '_remove' ); ?>

			<p class="submit">
				<input type="submit" class="button button-secondary" value="<?php _e( 'Reset Settings' , $APSC->ltd ); ?>" />
			</p>

		</form>

	</div>
	
</div>

<style>
</style>
<script>
</script>
