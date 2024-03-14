<?php
use Tussendoor\OpenRDW\Config;
	/**
	 * Our contact form 7 back-end view.
	 */
	if (isset($args)) {
		$args = wp_parse_args($args, array());
	}

	if (!isset($args['content'])) {
		$args['content'] = '';
	}

?>
<div class="control-box">
	<fieldset>
		
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><?php echo esc_html__('Veld soort:', 'open-rdw-kenteken-voertuiginformatie'); ?></th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><?php echo esc_html__('Veld soort:', 'open-rdw-kenteken-voertuiginformatie'); ?></legend>
							<label><input type="checkbox" name="required" /> <?php echo esc_html__('Verplicht veld', 'open-rdw-kenteken-voertuiginformatie'); ?></label>
						</fieldset>
					</td>
				</tr>

				<tr>
					<th scope="row"><label for="<?php echo esc_attr($args['content'] . '-name'); ?>"><?php echo esc_html__('Naam:', 'open-rdw-kenteken-voertuiginformatie'); ?></label></th>
					<td><input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr($args['content'] . '-name'); ?>" /></td>
				</tr>

				<tr>
					<th scope="row"><label for="<?php echo esc_attr($args['content'] . '-values'); ?>"><?php echo esc_html__('Standaard waarde:', 'open-rdw-kenteken-voertuiginformatie'); ?></label></th>
					<td><input type="text" name="values" class="oneline" id="<?php echo esc_attr($args['content'] . '-values'); ?>" /><br />
					<label class="mt-10 inline-block"><input type="checkbox" name="placeholder" class="option" /> <?php echo esc_html__('Gebruik deze tekst als placeholder', 'open-rdw-kenteken-voertuiginformatie'); ?></label></td>
				</tr>

				<tr>
					<th scope="row"><label for="<?php echo esc_attr($args['content'] . '-id'); ?>"><?php echo esc_html__('Id attribuut:', 'open-rdw-kenteken-voertuiginformatie'); ?></label></th>
					<td><input type="text" name="id" class="idvalue oneline option" id="<?php echo esc_attr($args['content'] . '-id'); ?>" /></td>
				</tr>

				<tr>
					<th scope="row"><label for="<?php echo esc_attr($args['content'] . '-class'); ?>"><?php echo esc_html__('Class attribuut:', 'open-rdw-kenteken-voertuiginformatie'); ?></label></th>
					<td><input type="text" name="class" class="classvalue oneline option" id="<?php echo esc_attr($args['content'] . '-class'); ?>" /></td>
				</tr>

			</tbody>
		</table>
	</fieldset>
	<fieldset>
		<br>
		<strong><?php echo esc_html__('Gebruik de shortcode in combinatie met de volgende velden:', 'open-rdw-kenteken-voertuiginformatie'); ?></strong>
		<div class="rdw-expand-fields cf-7-row-expand">
			<ul>

			<?php

				$categories = array();

				foreach ($fields as $value) {

					if (!in_array($value['category'], $categories)) {
						
						$categories[] = $value['category'];

						echo '<li class="ui-sortable">';
						echo '<a>'.$value['category'].'</a>';
						echo '<ul style="display:none;">';

						foreach ($fields as $key => $value) {
							
							if (end($categories) == $value['category']) {
								
								echo '<li class="ui-sortable-handle">';
								echo '<label style="display: block;">';
								echo $value['label'] . ': ';
								echo '<input type="text" onClick="this.select();" readonly="readonly" value="[text ' . $key . ']" />';
								echo '</label>';
								echo '</li>';

							}

						}
						echo '</ul>';
						echo '</li>';

					}

				}
			
			?>
			</ul>
		</div>
	</fieldset>
</div>

<div class="insert-box rdw-insert-box">
	<input type="text" name="open_rdw" class="tag code mx-width-80-pt" readonly="readonly" onfocus="this.select()" />

	<div class="submitbox">
		<input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr__('Label invoegen', 'open-rdw-kenteken-voertuiginformatie'); ?>" />
	</div>

	<br class="clear" />

	<p class="description mail-tag"><label for="<?php echo esc_attr($args['content'] . '-mailtag'); ?>"><?php echo sprintf(esc_html__("Om de waarde-invoer via dit veld in een e-mailveld te gebruiken, moet u de corresponderende e-mailtag (%s) invoegen in het veld op het tabblad E-mail.", 'open-rdw-kenteken-voertuiginformatie') , '<strong><span class="mail-tag"></span></strong>'); ?><input type="text" class="mail-tag code hidden" readonly="readonly" id="<?php echo esc_attr($args['content'] . '-mailtag'); ?>" /></label></p>
</div>