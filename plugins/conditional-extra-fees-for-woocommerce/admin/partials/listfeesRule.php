<div class="row">
    <div class="col-12 py-3 text-right"><a class="btn btn-primary btn-sm mr-3" href="<?php echo admin_url( 'admin.php?page=pisol-cefw&tab=pi_cefw_add_rule' ); ?>"><span class="dashicons dashicons-plus"></span><?php _e('Add fees rule','conditional-extra-fees-woocommerce'); ?></a>
    </div>
</div>
<?php

$shipping_methods = get_posts(array(
    'post_type'=>'pi_fees_rule',
    'numberposts'      => -1
));

?>
<div id="pisol-cefw-fees-list-view">
<table class="table text-center table-striped">
				<thead>
				<tr class="afrsm-head">
					<th><?php _e( 'Fees', 'conditional-extra-fees-woocommerce'); ?></th>
					<th><?php _e( 'Amount', 'conditional-extra-fees-woocommerce'); ?></th>
					<th><?php _e( 'Status', 'conditional-extra-fees-woocommerce'); ?></th>
					<th><?php _e( 'Actions', 'conditional-extra-fees-woocommerce'); ?></th>
				</tr>
				</thead>
                <tbody >
                

<?php
if(count($shipping_methods) > 0){
foreach($shipping_methods as $method){
    $fees   = get_post_meta( $method->ID, 'pi_fees', true );
    $fees_type   = get_post_meta( $method->ID, 'pi_fees_type', true );
    $fees_title  = get_the_title( $method->ID ) ? get_the_title( $method->ID ) : 'Shipping Method';
    $fees_status = get_post_meta( $method->ID, 'pi_status', true );
    echo '<tr  id="pisol_tr_container_'.$method->ID.'">';
    echo '<td><a href="'.admin_url( '/admin.php?page=pisol-cefw&tab=pi_cefw_add_rule&action=edit&id='.$method->ID ).'">'.esc_html($fees_title).'</a></td>';
    echo '<td>';
    
								if ( $fees_type == 'fixed' ) {
									echo $fees;
								} else {
									echo esc_html($fees).' %';
								}
							
    echo '</td>';
    echo '<td>';
    echo '<div class="custom-control custom-switch">
    <input type="checkbox" value="1" '.checked($fees_status,'on', false).' class="custom-control-input pi-cefw-status-change" name="pi_status" id="pi_status_'.$method->ID.'" data-id="'.esc_attr($method->ID).'">
    <label class="custom-control-label" for="pi_status_'.$method->ID.'"></label>
    </div>';
    echo '</td>';
    echo '<td>';
    echo '<a href="'.admin_url( '/admin.php?page=pisol-cefw&tab=pi_cefw_add_rule&action=edit&id='.$method->ID ).'" class="btn btn-primary btn-sm mr-2" title="Edit"><span class="dashicons dashicons-admin-customizer"></span></a>';
    echo '<a href="'.wp_nonce_url(admin_url( '/admin.php?page=pisol-cefw&action=cefw_delete&id='.$method->ID ), 'cefw-delete').'" class="btn btn-warning btn-sm" title="Delete"><span class="dashicons dashicons-trash"></span></a>';
    echo '</td>';
    echo '</tr>';
}
}else{
    echo '<tr>';
    echo '<td colspan="4" class="text-center">';
    echo __('There are no fees rule added yet, add them first','conditional-extra-fees-woocommerce' );
    echo '</td>';
    echo '</tr>';
}
?>
</tbody>
</table>
</div>