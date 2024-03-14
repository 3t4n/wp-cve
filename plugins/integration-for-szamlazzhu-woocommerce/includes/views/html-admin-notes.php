<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//Get saved values
$saved_values = get_option('wc_szamlazz_notes');
$countries_obj = new WC_Countries();
$countries = $countries_obj->__get('countries');

//Setup conditions
$conditions = WC_Szamlazz_Conditions::get_conditions('notes');

?>

<tr valign="top">
	<th scope="row" class="titledesc"><?php echo esc_html( $data['title'] ); ?></th>
	<td class="forminp <?php echo esc_attr( $data['class'] ); ?>">
		<div class="wc-szamlazz-settings-notes">
			<?php if($saved_values): ?>
				<?php foreach ( $saved_values as $note_id => $note ): ?>

					<div class="wc-szamlazz-settings-note wc-szamlazz-settings-repeat-item">
						<textarea placeholder="<?php _e('Note', 'wc-szamlazz'); ?>" data-name="wc_szamlazz_notes[X][note]"><?php echo esc_textarea($note['comment']); ?></textarea>
						<div class="wc-szamlazz-settings-note-if">
							<div class="wc-szamlazz-settings-note-if-header">
								<label>
									<input type="checkbox" data-name="wc_szamlazz_notes[X][condition_enabled]" <?php checked( $note['conditional'] ); ?> class="condition" value="yes">
									<span><?php _e('Add note to invoice, if', 'wc-szamlazz'); ?></span>
								</label>
								<select data-name="wc_szamlazz_notes[X][logic]">
									<option value="and" <?php if(isset($note['logic'])) selected( $note['logic'], 'and' ); ?>><?php _e('All', 'wc-szamlazz'); ?></option>
									<option value="or" <?php if(isset($note['logic'])) selected( $note['logic'], 'or' ); ?>><?php _e('One', 'wc-szamlazz'); ?></option>
								</select>
								<span><?php _e('of the following match', 'wc-szamlazz'); ?></span>
								<a href="#" class="delete-note"><?php _e('delete', 'wc-szamlazz'); ?></a>
							</div>
							<ul class="wc-szamlazz-settings-note-if-options conditions" <?php if(!$note['conditional']): ?>style="display:none"<?php endif; ?> <?php if(isset($note['conditions'])): ?>data-options="<?php echo esc_attr(json_encode($note['conditions'])); ?>"<?php endif; ?>></ul>
							<div class="wc-szamlazz-settings-note-if-header wc-szamlazz-settings-note-if-append" <?php if(!$note['conditional']): ?>style="display:none"<?php endif; ?>>
								<label>
									<input type="checkbox" data-name="wc_szamlazz_notes[X][append]" <?php if(isset($note['append'])) { checked( $note['append'] ); } ?> value="yes">
									<span><?php _e('Add to the end of an existing note', 'wc-szamlazz'); ?></span>
								</label>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
		<div class="wc-szamlazz-settings-note-add">
			<a href="#"><span class="dashicons dashicons-plus-alt"></span> <span><?php _e('Add a new note', 'wc-szamlazz'); ?></span></a>
		</div>
		<?php echo $this->get_description_html( $data ); // WPCS: XSS ok. ?>
	</td>
</tr>

<script type="text/html" id="wc_szamlazz_note_sample_row">
	<div class="wc-szamlazz-settings-notes">
		<div class="wc-szamlazz-settings-note wc-szamlazz-settings-repeat-item">
			<textarea placeholder="<?php _e('Note', 'wc-szamlazz'); ?>" data-name="wc_szamlazz_notes[X][note]"><?php if(!get_option('wc_szamlazz_notes')) { echo esc_textarea($this->get_option('note')); } ?></textarea>
			<div class="wc-szamlazz-settings-note-if">

				<div class="wc-szamlazz-settings-note-if-header">
					<label>
						<input type="checkbox" data-name="wc_szamlazz_notes[X][condition_enabled]" class="condition" value="yes">
						<span><?php _e('Add note to invoice, if', 'wc-szamlazz'); ?></span>
					</label>
					<select data-name="wc_szamlazz_notes[X][logic]">
						<option value="and"><?php _e('All', 'wc-szamlazz'); ?></option>
						<option value="or"><?php _e('One', 'wc-szamlazz'); ?></option>
					</select>
					<span><?php _e('of the following match', 'wc-szamlazz'); ?></span>
					<a href="#" class="delete-note"><?php _e('delete', 'wc-szamlazz'); ?></a>
				</div>
				<ul class="wc-szamlazz-settings-note-if-options conditions" style="display:none"></ul>
				<div class="wc-szamlazz-settings-note-if-header wc-szamlazz-settings-note-if-append" style="display:none">
					<label>
						<input type="checkbox" data-name="wc_szamlazz_notes[X][append]" value="yes">
						<span><?php _e('Add to the end of an existing note', 'wc-szamlazz'); ?></span>
					</label>
				</div>
			</div>
		</div>
	</div>
</script>

<?php echo WC_Szamlazz_Conditions::get_sample_row('notes'); ?>
