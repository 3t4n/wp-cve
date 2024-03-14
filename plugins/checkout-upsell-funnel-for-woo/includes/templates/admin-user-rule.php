<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$item_index          = $item_index ?? '';
$item_index          = $item_index ?: '{item_index}';
$index               = $index ?? '';
$index               = $index ?: '{index}';
$prefix              = $prefix ?? '';
$prefix              = $prefix ?: '{prefix}';
$params              = isset($params) && is_array($params) ?$params : array();
$type                = $type ?? 'user_logged';
$woo_currency_symbol = $woo_currency_symbol ?? get_woocommerce_currency_symbol();
if ( empty( $woo_users_role ) ) {
	$woo_users_role = wp_roles()->roles;
}

$conditions             = array(
	'user_logged'       => esc_html__( 'Only logged in', 'checkout-upsell-funnel-for-woo' ),
	'user_role_include' => esc_html__( 'Include user role', 'checkout-upsell-funnel-for-woo' ),
	'user_role_exclude' => esc_html__( 'Exclude user role', 'checkout-upsell-funnel-for-woo' ),
	'user_include'      => esc_html__( 'Include user', 'checkout-upsell-funnel-for-woo' ),
	'user_exclude'      => esc_html__( 'Exclude user', 'checkout-upsell-funnel-for-woo' ),
);
$limit_per_day          = $limit_per_day ?? 1;
$user_logged            = $user_logged ?? 1;
$user_role_include      = isset($user_role_include) && is_array($user_role_include) ? $user_role_include : array();
$user_role_exclude      = isset($user_role_exclude) && is_array($user_role_exclude) ? $user_role_exclude : array();
$user_include           = isset($user_include) && is_array($user_include) ? $user_include : array();
$user_exclude           = isset($user_exclude) && is_array($user_exclude) ? $user_exclude : array();
$name_condition_type    = $prefix . 'user_rule_type[' . $index . '][]';
$name_limit_per_day     = $prefix . 'limit_per_day[' . $index . ']';
$name_user_logged       = $prefix . 'user_logged[' . $index . ']';
$name_user_role_include = $prefix . 'user_role_include[' . $index . '][]';
$name_user_role_exclude = $prefix . 'user_role_exclude[' . $index . '][]';
$name_user_include      = $prefix . 'user_include[' . $index . '][]';
$name_user_exclude      = $prefix . 'user_exclude[' . $index . '][]';
?>
<div class="vi-ui placeholder segment vi-wcuf-condition-wrap-wrap vi-wcuf-user-condition-wrap-wrap">
    <div class="fields">
        <div class="four wide field">
            <select name="<?php echo esc_attr( $name_condition_type ); ?>"
                    data-wcuf_name="<?php echo esc_attr( $name_condition_type ); ?>"
                    data-wcuf_name_default="{prefix_default}user_rule_type[{index_default}][]"
                    data-wcuf_prefix="<?php echo esc_attr( $prefix ); ?>"
                    class="vi-ui fluid dropdown vi-wcuf-condition-type vi-wcuf-user-condition-user_rule_type">
                <option disabled><?php esc_html_e('Limit per day', 'checkout-upsell-funnel-for-woo'); ?></option>
				<?php
				foreach ( $conditions as $condition_k => $condition_v ) {
					$check = '';
					if ( $type == $condition_k ) {
						$check = 'selected';
					}
					echo sprintf( '<option value="%s" %s >%s</option>', $condition_k, $check, esc_html( $condition_v ) );
				}
				?>
            </select>
        </div>
        <div class="thirteen wide field vi-wcuf-condition-value-wrap-wrap">
            <div class="field vi-wcuf-condition-wrap vi-wcuf-user-condition-wrap vi-wcuf-condition-limit_per_day-wrap <?php echo esc_attr($type === 'limit_per_day' ? '' :  'vi-wcuf-hidden' ); ?>">
                <input type="number" min="1" step="1"
                       name="<?php echo esc_attr( $type === 'limit_per_day' ? $name_limit_per_day  : ''); ?>"
                       data-wcuf_allow_empty="1"
                       data-wcuf_name="<?php echo esc_attr( $name_limit_per_day ) ?>"
                       data-wcuf_name_default="{prefix_default}limit_per_day[{index_default}]"
                       data-wcuf_prefix="<?php echo esc_attr( $prefix ); ?>"
                       placeholder="<?php esc_attr_e( 'leave blank to not limit this', 'checkout-upsell-funnel-for-woo' ); ?>"
                       class="vi-wcuf-pd-condition-limit_per_day vi-wcuf-condition-value" value="<?php echo esc_attr( $limit_per_day ?: '' ) ?>">
            </div>
            <div class="field vi-wcuf-condition-wrap vi-wcuf-user-condition-wrap vi-wcuf-condition-user_logged-wrap <?php echo esc_attr( $type === 'user_logged' ? '' : 'vi-wcuf-hidden' ); ?>">
                <select class="vi-ui fluid dropdown vi-wcuf-user-condition-user_logged"
                        name="<?php echo esc_attr($type === 'user_logged' ?  $name_user_logged  : ''); ?>"
                        data-wcuf_name_default="{prefix_default}user_logged[{index_default}]"
                        data-wcuf_prefix="<?php echo esc_attr( $prefix ); ?>"
                        data-wcuf_name="<?php echo esc_attr( $name_user_logged ) ?>">
                    <option value="0" <?php selected( $user_logged, 0 ) ?>>
						<?php esc_html_e( 'No', 'checkout-upsell-funnel-for-woo' ); ?>
                    </option>
                    <option value="1" <?php selected( $user_logged, 1 ) ?>>
						<?php esc_html_e( 'Yes', 'checkout-upsell-funnel-for-woo' ); ?>
                    </option>
                </select>
            </div>
            <div class="field vi-wcuf-condition-wrap vi-wcuf-user-condition-wrap vi-wcuf-condition-user_role_include-wrap <?php echo esc_attr( $type === 'user_role_include' ? '' : 'vi-wcuf-hidden' ); ?>">
                <select class="vi-wcuf-search-select2 vi-wcuf-search-user-role vi-wcuf-user-condition-user_role_include vi-wcuf-condition-value"
                        data-type_select2="user_role"
                        name="<?php echo esc_attr($type === 'user_role_include' ?  $name_user_role_include  : ''); ?>"
                        data-wcuf_name_default="{prefix_default}user_role_include[{index_default}][]"
                        data-wcuf_prefix="<?php echo esc_attr( $prefix ); ?>"
                        data-wcuf_name="<?php echo esc_attr( $name_user_role_include ) ?>" multiple>
					<?php
					if ( $woo_users_role && is_array( $woo_users_role ) && count( $woo_users_role ) ) {
						foreach ( $woo_users_role as $k => $v ) {
							echo sprintf( '<option value="%s" %s>%s</option>', $k, selected(in_array( $k, $user_role_include ) ,true), esc_html( $v['name'] ) );
						}
					}
					?>
                </select>
            </div>
            <div class="field vi-wcuf-condition-wrap vi-wcuf-user-condition-wrap vi-wcuf-condition-user_role_exclude-wrap <?php echo esc_attr($type === 'user_role_exclude' ? '' :  'vi-wcuf-hidden' ); ?>">
                <select class="vi-wcuf-search-select2 vi-wcuf-search-user-role vi-wcuf-user-condition-user_role_exclude vi-wcuf-condition-value"
                        data-type_select2="user_role"
                        name="<?php echo  esc_attr($type === 'user_role_exclude' ? $name_user_role_exclude  : ''); ?>"
                        data-wcuf_name_default="{prefix_default}user_role_exclude[{index_default}][]"
                        data-wcuf_prefix="<?php echo esc_attr( $prefix ); ?>"
                        data-wcuf_name="<?php echo esc_attr( $name_user_role_exclude ) ?>" multiple>
					<?php
					if ( $woo_users_role && is_array( $woo_users_role ) && count( $woo_users_role ) ) {
						foreach ( $woo_users_role as $k => $v ) {
							echo sprintf( '<option value="%s" %s>%s</option>', $k, selected(in_array( $k, $user_role_exclude ) ,true), esc_html( $v['name'] ) );
						}
					}
					?>
                </select>
            </div>
            <div class="field vi-wcuf-condition-wrap vi-wcuf-user-condition-wrap vi-wcuf-condition-user_include-wrap <?php echo esc_attr($type === 'user_include' ? '' :  'vi-wcuf-hidden' ); ?>">
                <select class="vi-wcuf-search-select2 vi-wcuf-search-user vi-wcuf-user-condition-user_include vi-wcuf-condition-value"
                        data-type_select2="user"
                        name="<?php echo esc_attr($type === 'user_include' ?  $name_user_include  : ''); ?>"
                        data-wcuf_name_default="{prefix_default}user_include[{index_default}][]"
                        data-wcuf_prefix="<?php echo esc_attr( $prefix ); ?>"
                        data-wcuf_name="<?php echo esc_attr( $name_user_include ) ?>" multiple>
					<?php
					if ( $user_include && is_array( $user_include ) && count( $user_include ) ) {
						foreach ( $user_include as $user_id ) {
							$user = get_user_by( 'id', $user_id );
							if ( $user ) {
								echo sprintf( '<option value="%s" selected>%s</option>', $user_id, esc_html( $user->display_name ) );
							}
						}
					}
					?>
                </select>
            </div>
            <div class="field vi-wcuf-condition-wrap vi-wcuf-user-condition-wrap vi-wcuf-condition-user_exclude-wrap <?php echo esc_attr($type === 'user_exclude' ? '' :  'vi-wcuf-hidden' ); ?>">
                <select class="vi-wcuf-search-select2 vi-wcuf-search-user vi-wcuf-user-condition-user_exclude vi-wcuf-condition-value"
                        data-type_select2="user"
                        name="<?php echo esc_attr($type === 'user_exclude' ?  $name_user_exclude  : ''); ?>"
                        data-wcuf_name_default="{prefix_default}user_exclude[{index_default}][]"
                        data-wcuf_prefix="<?php echo esc_attr( $prefix ); ?>"
                        data-wcuf_name="<?php echo esc_attr( $name_user_exclude ) ?>" multiple>
					<?php
					if ( $user_exclude && is_array( $user_exclude ) && count( $user_exclude ) ) {
						foreach ( $user_exclude as $user_id ) {
							$user = get_user_by( 'id', $user_id );
							if ( $user ) {
								echo sprintf( '<option value="%s" selected>%s</option>', $user_id, esc_html( $user->display_name ) );
							}
						}
					}
					?>
                </select>
            </div>
        </div>
        <div class="field vi-wcuf-revmove-condition-btn-wrap">
             <span class="vi-wcuf-revmove-condition-btn vi-wcuf-user_rule-revmove-condition"
                   data-tooltip="<?php esc_html_e( 'Remove', 'woo-pricing-and-discount-rules' ); ?>">
                 <i class="times icon"></i>
             </span>
        </div>
    </div>
</div>
