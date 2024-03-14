<?php
    /** @var int[] $sortedChannels */
    /** @var array[] $possibleChannels */
?>
<tr valign="top">
	<th scope="row" class="titledesc">
		<label for="<?php echo esc_attr($field_key); ?>"><?php echo wp_kses_post($data['title']); ?> <?php echo $this->get_tooltip_html($data); // WPCS: XSS ok. ?></label>
	</th>
	<td class="forminp">
		<fieldset>
			<table class="form-table" id="wppay-payments">
				<tbody>
					<tr valign="top">
						<td>
							<p><b><?php _e('Payment group location', 'pay-wp'); ?></b></p>
						</td>
					</tr>
					<tr valign="top">
						<td style="padding: 0 10px;">
							<ul id="payments-sortable">
								<?php
								if ( isset( $sortedChannels ) && ! empty( $sortedChannels ) ) {
									foreach ( $sortedChannels as $gatewayId ) {
										$channel = $possibleChannels[ $gatewayId ] ?? '';
										if ( ! empty( $channel ) ) {
                                        ?>
                                        <li class="ui-state-default">
                                            <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                                            <?php echo $channel['gatewayName']; ?><br /> <img height="50" src="<?php echo $channel['iconURL']; ?>" alt="" />
                                            <input type="hidden" name="<?php echo $field_key; ?>[]" value="<?php echo $channel['gatewayID']; ?>" />
                                        </li>
                                <?php
                                        }
									}
								}
								?>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</fieldset>
	</td>
</tr>
