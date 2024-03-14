<?php
/**
 * Report parameter template: search-fields
 *
 * @package UpStream
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) || ! isset( $section_id ) ) {
	exit;
}

$users_info = upstream_get_viewable_users();

$users = $users_info['by_uid'];

foreach ( $fields as $field_name => $field ) :
	$fname = 'upstream_report__' . $section_id . '_' . $field_name;

	if ( $field['display'] ) {
		$display_fields[ $field_name ] = $field['title'];
	}
	if ( ! $field['search'] ) {
		continue;
	}
	?>
	<div class="row">

		<div class="col-lg-12 col-xs-12">
			<div class="form-group">
				<label><?php echo esc_html( $field['title'] ); ?></label>

				<?php if ( 'string' === $field['type'] || 'text' === $field['type'] ) : ?>
					<input class="form-control" type="text" name="<?php print esc_attr( $fname ); ?>">
				<?php elseif ( 'user_id' === $field['type'] ) : ?>
					<select class="form-control" name="<?php print esc_attr( $fname ); ?>[]" multiple>
						<?php foreach ( $users as $user_id => $username ) : ?>
							<option value="<?php echo esc_attr( $user_id ); ?>"><?php echo esc_html( $username ); ?></option>
						<?php endforeach; ?>
					</select>
				<?php elseif ( 'select' === $field['type'] ) : ?>
					<select class="form-control" name="<?php print esc_attr( $fname ); ?>[]" multiple>
						<?php foreach ( call_user_func( $field['options_cb'] ) as $key => $value ) : ?>
							<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></option>
						<?php endforeach; ?>
					</select>
				<?php elseif ( 'number' === $field['type'] ) : ?>
					Between
					<input type="text" name="<?php print esc_attr( $fname ); ?>_lower"> and
					<input type="text" name="<?php print esc_attr( $fname ); ?>_upper">
					<input type="hidden" name="<?php print esc_attr( $fname ); ?>" value="number">
				<?php elseif ( 'date' === $field['type'] ) : ?>
					Between
					<input type="text" class="r-datepicker" name="<?php print esc_attr( $fname ); ?>_start"> and
					<input type="text" class="r-datepicker" name="<?php print esc_attr( $fname ); ?>_end">
					<input type="hidden" name="<?php print esc_attr( $fname ); ?>" value="date">
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php

endforeach;
