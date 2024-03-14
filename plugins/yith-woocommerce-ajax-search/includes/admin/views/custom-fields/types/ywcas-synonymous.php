<?php
/**
 * Template for displaying the synonymous field
 *
 * @var array $field The field.
 * @package YITH\Search\Views
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$values = (array) maybe_unserialize( $field['value'] );

$name     = $field['name'];
$field_id = $field['id'];
if ( empty( $values ) ) {
	$values[] = '';
}
?>
<div class="ywcas-synonymous-main-wrapper">
<?php
foreach ( $values as $key => $value ) :
	$current_name = $name . '[' . $key . ']';
	$current_id   = $field_id . '[' . $key . ']';
	?>
	<div id="<?php echo esc_attr( $current_id ); ?>-container" data-index="<?php echo esc_attr( $key ); ?>" class="yit_options rm_option rm_input rm_text ywcas-synonymous">
		<div class="option">
			<?php
			yith_plugin_fw_get_field(
				array(
					'id'                => $current_id,
					'name'              => $current_name,
					'type'              => 'text',
					'value'             => $value,
					'custom_attributes' => array(
						'placeholder' => $field['placeholder'],
					),
				),
				true,
				false
			);
			?>
			<?php
			yith_plugin_fw_get_action_buttons(
				array(
					array(
						'type'   => 'action-button',
						'title'  => _x( 'Delete', 'Tip to delete the sender info', 'yith-woocommerce-ajax-search' ),
						'icon'   => 'trash',
						'url'    => '',
						'action' => 'delete',
						'class'  => 'action__trash',
					),
				),
				true
			);
			?>
		</div>
	</div>
	<?php
endforeach;
?>
</div>
<span class="ywcas-add-synonymous"><a href="#"><?php esc_html_e( '+ Add another list of synonyms', 'yith-woocommerce-ajax-search' ); ?></a></span>
<?php
$current_name = $name . '[{{data.id}}]';
$current_id   = $field_id . '[{{data.id}}]';
?>
<script type="text/html" id="tmpl-ywcas-synonymous">
	<div id="<?php echo esc_attr( $current_id ); ?>-container" data-index="<?php echo esc_attr( $key ); ?>" class="yit_options rm_option rm_input rm_text ywcas-synonymous">
		<div class="option">
			<?php
			yith_plugin_fw_get_field(
				array(
					'id'                => $current_id,
					'name'              => $current_name,
					'type'              => 'text',
					'value'             => '',
					'custom_attributes' => array(
						'placeholder' => '',
					),
				),
				true,
				false
			);
			?>
			<?php
			yith_plugin_fw_get_action_buttons(
				array(
					array(
						'type'   => 'action-button',
						'title'  => _x( 'Delete', 'Tip to delete the sender info', 'yith-woocommerce-ajax-search' ),
						'icon'   => 'trash',
						'url'    => '',
						'action' => 'delete',
						'class'  => 'action__trash',
					),
				),
				true
			);
			?>
		</div>
	</div>
</script>
