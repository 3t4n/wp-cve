<?php

$taxonomies = $APSC->Helper->get_taxonomies();

$first_taxonomy = reset( $taxonomies );

$select_tax = $first_taxonomy->name;

if( !empty( $_GET['taxonomy'] ) ) {
	
	$select_tax = strip_tags( $_GET['taxonomy'] );

}

$select_taxonomy = get_taxonomy( $select_tax );

$terms = $APSC->Helper->get_terms( $select_tax );

?>

<div class="wrap <?php echo $APSC->main_slug; ?>">

	<h1><?php printf( __( '%1$s %2$s for %3$s %4$s' , $APSC->ltd ) , __( 'Customize' ) ,  __( 'Sort' , $APSC->ltd ) , __( 'Taxonomies' , $APSC->ltd ) , __( 'Archives' ) ); ?></h1>
	
	<h2 class="nav-tab-wrapper"><?php $this->tabs(); ?></h2>
	
	<p>&nbsp;</p>
	
	<form id="<?php echo esc_attr( $this->action ); ?>_select_taxonomy_form" class="<?php echo $APSC->main_slug; ?>_form" method="get" action="<?php echo esc_url( $APSC->Helper->get_action_link() ); ?>">
	
		<input type="hidden" name="page" value="<?php echo esc_attr( $APSC->main_slug ); ?>" />
		<input type="hidden" name="<?php echo esc_attr( $this->tab_name ); ?>" value="<?php echo esc_attr( $this->current_tab ); ?>" />
	
		<p>
			<?php _e( 'Choose the Taxonomy:' , $APSC->ltd ); ?>
	
			<select name="taxonomy">
			
				<?php foreach( $taxonomies as $taxonomy ) : ?>
				
					<option value="<?php echo esc_attr( $taxonomy->name ); ?>" <?php selected( $select_tax , $taxonomy->name ); ?>><?php echo $taxonomy->label; ?></option>
				
				<?php endforeach; ?>
					
			</select>
			
			<span class="spinner"></span>
			
		</p>
		
	</form>

	<div class="metabox-holder columns-1">
	
		<form id="<?php echo esc_attr( $this->action ); ?>_update_form" class="<?php echo $APSC->main_slug; ?>_form" method="post" action="<?php echo esc_url( $APSC->Helper->get_action_link() ); ?>">

			<input type="hidden" name="<?php echo $APSC->Form->field; ?>" value="Y">
			<?php wp_nonce_field( $this->nonce . '_update' , $this->nonce . '_update' ); ?>
			
			<?php
				$args = array(
					'id' => 'default',
					'title' => sprintf( __( 'All %s' , $APSC->ltd ) , $select_taxonomy->label ),
					'name_field' => 'default_' . esc_html( $select_taxonomy->name ),
				);
			?>

			<?php $this->settings_section( $args ); ?>
			
			<h3><?php printf( __( 'Individual %s sort settings' , $APSC->ltd ) , $select_taxonomy->label ); ?></h3>
			
			<?php if( !empty( $terms ) ) : ?>
			
				<?php foreach( $terms as $term ) : ?>
					
					<?php
						$args = array(
							'id' => esc_html( $term->name ),
							'name_field' => esc_html( $select_taxonomy->name ) . '_' . $term->term_id,
							'individual' => true,
							'parent_name' => $select_taxonomy->label,
						);

						$term_link = get_term_link( $term );
						
						if( empty( $term_link ) or is_wp_error( $term_link ) ) {
							
							$args['title'] = $term->name;
							
						} else {

							$args['title'] = sprintf( '<a href="%s" target="_blank">%s</a>' , esc_url( $term_link ) , $term->name );
							
						}
					?>
	
					<?php $this->settings_section( $args ); ?>
					
				<?php endforeach; ?>
				
			<?php endif; ?>

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
#<?php echo esc_attr( $this->action ); ?>_select_taxonomy_form .spinner {
    float: none;
}
</style>
<script>
jQuery(document).ready(function($) {

	$('#<?php echo esc_attr( $this->action ); ?>_select_taxonomy_form select').on('change', function() {
		
		$('#<?php echo esc_attr( $this->action ); ?>_select_taxonomy_form .spinner').css('visibility', 'visible');
		$('#<?php echo esc_attr( $this->action ); ?>_select_taxonomy_form').submit();

	});

});
</script>
