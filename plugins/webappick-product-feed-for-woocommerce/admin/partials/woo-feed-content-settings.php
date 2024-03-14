<?php
/**
 * Content Settings Table
 *
 * @package WooFeed
 * @subpackage Editor
 * @version 1.0.0
 * @since WooFeed 3.2.6
 * @author KD <mhamudul.hk@gmail.com>
 * @copyright 2019 WebAppick <support@webappick.com>
 */
if ( ! defined( 'ABSPATH' ) ) {
	die(); // silence
}
/**
 * @global array $feedRules
 * @global Woo_Feed_Dropdown $wooFeedDropDown
 * @global Woo_Feed_Merchant $merchant
 */
global $feedRules, $wooFeedDropDown, $merchant;
?>
<table class="widefat fixed">
	<thead>
		<tr>
            <th colspan="2" class="woo-feed-table-heading">
                <span class="woo-feed-table-heading-title"><?php esc_html_e( 'Content Settings', 'woo-feed' ); ?></span>
                <?php woo_feed_clear_cache_button(); ?>
            </th>
		</tr>
	</thead>
	<tbody>
        <tr>
            <th><label for="feed_country"><?php esc_html_e( 'Country', 'woo-feed' ); ?> <span class="requiredIn">*</span></label></th>
            <td>
                <select wftitle="<?php esc_attr_e( 'Select a country', 'woo-feed' ); ?>" name="feed_country" id="feed_country" class="generalInput wfmasterTooltip" required>
                    <?php
                    $shop_country = WC()->countries->get_base_country();
                    $default_country = ! empty( $feedRules['feed_country'] ) ? $feedRules['feed_country'] : $shop_country;
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    echo $wooFeedDropDown->countriesDropdown( $default_country );
                    ?>
                </select>
            </td>
        </tr>
		<tr>
			<th><label for="provider"><?php esc_html_e( 'Template', 'woo-feed' ); ?> <span class="requiredIn">*</span></label></th>
			<td>
				<select wftitle="<?php esc_attr_e( 'Select a template', 'woo-feed' ); ?>" name="provider" id="provider" class="generalInput wfmasterTooltip" required>
					<?php
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo $wooFeedDropDown->merchantsDropdown( $feedRules['provider'] );
					?>
				</select>
			</td>
		</tr>
		<tr>
			<th><label for="filename"><?php esc_html_e( 'File Name', 'woo-feed' ); ?> <span class="requiredIn">*</span></label></th>
			<td>
				<input name="filename" value="<?php echo isset( $feedRules['filename'] ) ? esc_attr( $feedRules['filename'] ) : ''; ?>" type="text" id="filename" class="generalInput wfmasterTooltip" wftitle="<?php esc_attr_e( 'Filename should be unique. Otherwise it will override the existing filename.', 'woo-feed' ); ?>" required>
			</td>
		</tr>
		<tr>
			<th><label for="feedType"><?php esc_html_e( 'File Type', 'woo-feed' ); ?> <span class="requiredIn">*</span></label></th>
			<td>
				<select name="feedType" id="feedType" class="generalInput" required>
					<option value=""></option>
					<?php
					foreach ( woo_feed_get_file_types() as $file_type => $label ) {
						printf( '<option value="%1$s" %3$s>%2$s</option>', esc_attr( $file_type ), esc_html( $label ), selected( $feedRules['feedType'], $file_type, false ) );
					}
					?>
				</select>
				<span class="spinner" style="float: none; margin: 0;"></span>
			</td>
		</tr>
        <?php
        $isItemWrapperHide = 'table-row';
        if ( isset($feedRules['provider']) && 'custom' !== $feedRules['provider'] ) {
            $isItemWrapperHide = 'none';
        } elseif ( isset($feedRules['feedType']) && 'xml' !== $feedRules['feedType'] ) {
            if ( isset($feedRules['provider']) && 'custom' === $feedRules['provider'] ) {
                $isItemWrapperHide = 'none';
            }
        }
        ?>
        <tr class="itemWrapper" style="display: <?php echo esc_attr($isItemWrapperHide); ?>;">
            <th><label for="itemsWrapper"><?php esc_html_e( 'Items Wrapper', 'woo-feed' ); ?> <span class="requiredIn">*</span></label></th>
            <td>
                <input name="itemsWrapper" id="itemsWrapper" type="text" value="<?php echo esc_attr( wp_unslash($feedRules['itemsWrapper']) ); ?>" class="generalInput" required="required">
            </td>
        </tr>

        <tr class="itemWrapper" style="display: <?php echo esc_attr($isItemWrapperHide); ?>;">
            <th><label for="itemWrapper"><?php esc_html_e( 'Single Item Wrapper', 'woo-feed' ); ?> <span class="requiredIn">*</span></label></th>
            <td>
                <input name="itemWrapper" id="itemWrapper" type="text" value="<?php echo esc_attr( wp_unslash($feedRules['itemWrapper'] ) ); ?>" class="generalInput" required="required">
            </td>
        </tr>

        <?php
        $isDelimiterHide = 'table-row';
        if ( isset( $feedRules['feedType'] ) ) {
            if ( empty($feedRules['feedType']) || 'xml' === $feedRules['feedType'] || 'json' === $feedRules['feedType'] ) {
                $isDelimiterHide = 'none';
            }
        }
        ?>
		<tr class="wf_csvtxt" style="display: <?php echo esc_attr($isDelimiterHide); ?>;">
			<th><label for="delimiter"><?php esc_html_e( 'Delimiter', 'woo-feed' ); ?> <span class="requiredIn">*</span></label></th>
			<td>
				<select name="delimiter" id="delimiter" class="generalInput">
					<?php
					foreach ( woo_feed_get_csv_delimiters() as $k => $v ) {
						printf( '<option value="%1$s" %3$s>%2$s</option>', esc_attr( $k ), esc_html( $v ), selected( $feedRules['delimiter'], $k, false ) );
					}
					?>
				</select>
			</td>
		</tr>
		<tr class="wf_csvtxt" style="display: <?php echo esc_attr($isDelimiterHide); ?>;">
			<th><label for="enclosure"><?php esc_html_e( 'Enclosure', 'woo-feed' ); ?> <span class="requiredIn">*</span></label></th>
			<td>
				<select name="enclosure" id="enclosure" class="generalInput">
					<?php
					foreach ( woo_feed_get_csv_enclosure() as $k => $v ) {
						/** @noinspection HtmlUnknownAttribute */
						printf( '<option value="%1$s" %3$s>%2$s</option>', esc_attr( $k ), esc_html( $v ), selected( $feedRules['enclosure'], $k, false ) );
					}
					?>
				</select>
			</td>
		</tr>
	</tbody>
</table>
<?php
// End of file woo-feed-content-settings.php
