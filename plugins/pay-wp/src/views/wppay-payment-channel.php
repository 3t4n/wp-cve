<table>
    <tr>
        <td>
            <label><input type="radio" name="channel"
                          value="<?php echo sanitize_text_field( $channel['gatewayID'] ); ?>"> <img
                        src="<?php echo esc_url_raw( $channel['iconURL'] ); ?>"> <?php echo sanitize_text_field( $channel['gatewayName'] ); ?>
            </label>
        </td>
    </tr>
</table>