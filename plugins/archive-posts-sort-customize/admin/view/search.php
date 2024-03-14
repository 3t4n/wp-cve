<div class="wrap <?php echo $APSC->main_slug; ?>">

	<h1><?php printf( __( '%1$s %2$s for %3$s %4$s' , $APSC->ltd ) , __( 'Customize' ) ,  __( 'Sort' , $APSC->ltd ) , __( 'Search' ) , __( 'Archives' ) ); ?></h1>
	
	<h2 class="nav-tab-wrapper"><?php $this->tabs(); ?></h2>
	
	<p>&nbsp;</p>
	
	<div class="metabox-holder columns-1">
	
		<form id="<?php echo esc_attr( $this->action ); ?>_update_form" class="<?php echo $APSC->main_slug; ?>_form" method="post" action="<?php echo esc_url( $APSC->Helper->get_action_link() ); ?>">

			<input type="hidden" name="<?php echo $APSC->Form->field; ?>" value="Y">
			<?php wp_nonce_field( $this->nonce . '_update' , $this->nonce . '_update' ); ?>
			
			<?php
				$args = array(
					'id' => 'default',
					'title' => sprintf( '<a href="%s" target="_blank">%s</a>' , esc_url( get_search_link( 'Hello' ) ) , __( 'Search' ) ),
					'name_field' => 'default',
				);
			?>

			<?php $this->settings_section( $args ); ?>

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
