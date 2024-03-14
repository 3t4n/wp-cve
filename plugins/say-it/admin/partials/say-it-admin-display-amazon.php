<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.david-manson.com
 * @since      1.0.0
 *
 * @package    Say_It
 * @subpackage Say_It/admin/partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) die;
$options = $this->options;
$amazon_voices = $this->amazon_polly->get_voices();
$amazon_voice = ( isset( $options['amazon_voice'] ) && ! empty( $options['amazon_voice'] ) ) ? esc_attr( $options['amazon_voice'] ) : 'Kimberly';
?>

<?php if($this->amazon_polly->get_aws_errors()): ?>
    <p class="notice notice-warning inline">Oups, something wrong with Amazon AWS Configuration, please check the Amazon Tab<br><?php echo print_r($this->amazon_polly->get_aws_errors()); ?></p>
<?php else: ?>
    <table class="form-table">
        <tbody>
            <tr>
                <th scope="row">
                    <label for="<?php echo $this->plugin_name; ?>[amazon_voice]">
                        <?php _e( 'Amazon Voice', $this->plugin_name ); ?>
                    </label>
                </th>
                <td>
                <select name="<?php echo $this->plugin_name; ?>[amazon_voice]" id="<?php echo $this->plugin_name; ?>-amazon_voice">
                    <?php foreach ($amazon_voices['Voices'] as $key => $value): ?>
                        <option <?php if ( $amazon_voice == $value['Name'] ) echo 'selected="selected"'; ?> value="<?php echo $value['Name']; ?>"><?php echo $value['LanguageName']; ?> - <?php echo $value['Name']; ?></option>
                    <?php endforeach; ?>
                </select>
                </td>
            </tr>

        </tbody>
    </table>
<?php endif; ?>