<div class="breakpoint">
	<table>
		<thead class="breakpoint-header">
			<tr>
				<th>
					<label><?php _e( 'Window width:', 'sliderpro' ); ?></label>
				</th>
				<th>
					<input type="text" name="breakpoint_width" value="<?php echo isset( $breakpoint_settings['breakpoint_width'] ) ? esc_attr( $breakpoint_settings['breakpoint_width'] ) : ( isset( $width ) ? esc_attr( $width ) : '' ); ?>" />

					<span class="remove-breakpoint"></span>
				</th>
			</tr>
		</thead>
	
		<tbody class="breakpoint-settings">
			<?php
				if ( isset( $breakpoint_settings ) && ! empty( $breakpoint_settings ) ) {
					foreach ( $breakpoint_settings as $setting_name => $setting_value ) {
						if ( $setting_name !== 'breakpoint_width' ) {
							echo $this->create_breakpoint_setting( $setting_name, $setting_value );
						}
                    }
				}
			?>
		</tbody>
	</table>
	<div class="add-breakpoint-setting-group">
        <a class="button add-breakpoint-setting" href="#"><?php _e( 'Add Setting', 'sliderpro' ); ?> <span class="add-breakpoint-setting-arrow">&#9660</span></a>
        <ul class="breakpoint-setting-name">
        	<?php
				$default_breakpoint_settings = BQW_SliderPro_Settings::getBreakpointSettings();

				foreach ( $default_breakpoint_settings as $setting_name ) {
					if ( $setting_name !== 'breakpoint_width' ) {
						$setting = BQW_SliderPro_Settings::getSettings( $setting_name );
						echo '<li><a href="#" data-type="' . $setting_name . '">' . $setting['label'] . '</a></li>';
					}
				}
			?>
        </ul>
    </div>
</div>