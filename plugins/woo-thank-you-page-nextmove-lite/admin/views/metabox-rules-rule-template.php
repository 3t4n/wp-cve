<?php
defined( 'ABSPATH' ) || exit;
?>
<td class="rule-type">
	<?php
	$types = apply_filters( 'xlwcty_xlwcty_rule_get_rule_types', array() );
	// create field
	$args = array(
		'input'   => 'select',
		'name'    => 'xlwcty_rule[<%= groupId %>][<%= ruleId %>][rule_type]',
		'class'   => 'rule_type',
		'choices' => $types,
	);

	xlwcty_Input_Builder::create_input_field( $args, 'html_always' );
	?>
</td>

<?php
XLWCTY_Common::render_rule_choice_template( array(
	'group_id'  => 0,
	'rule_id'   => 0,
	'rule_type' => 'general_always',
	'condition' => false,
	'operator'  => false,
) );
?>
<td class="loading" colspan="2" style="display:none;"><?php _e( 'Loading...', 'woo-thank-you-page-nextmove-lite' ); ?></td>
<td class="add"><a href="#" class="xlwcty-add-rule button"><?php _e( 'AND', 'woo-thank-you-page-nextmove-lite' ); ?></a></td>
<td class="remove"><a href="#" class="xlwcty-remove-rule xlwcty-button-remove"></a></td>
