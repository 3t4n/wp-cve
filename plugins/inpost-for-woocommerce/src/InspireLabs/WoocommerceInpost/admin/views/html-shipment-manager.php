<?php
/**
 * @var ShipX_Dispatch_Order_Point_Model[]   $view_var_points
 * @var array                                $view_var_send_methods
 * @var array                                $view_var_statuses
 * @var array                                $view_var_services
 * @var EasyPack_Shipment_Manager_List_Table $view_var_shipment_manager_list_table
 * @var int $dispatch_point
 *
 *
 */

use InspireLabs\WoocommerceInpost\admin\EasyPack_Shipment_Manager;
use InspireLabs\WoocommerceInpost\admin\EasyPack_Shipment_Manager_List_Table;
use InspireLabs\WoocommerceInpost\EasyPack;
use InspireLabs\WoocommerceInpost\shipx\models\courier_pickup\ShipX_Dispatch_Order_Point_Model;

?>

<?php $is_courier_context = EasyPack_Shipment_Manager::is_courier_context() ?>
<div class="wrap">
    <div id="icon-users" class="icon32"></div>
    <h2><?php _e( 'InPost Shipments', 'woocommerce-inpost' ); ?></h2>
	<?php $view_var_shipment_manager_list_table->prepare_items(); ?>
    <form method="get">
        <input type="hidden" name="page" value="easypack_shipment">
		<?php if ( true === $is_courier_context ): ?>
            <div style="float:left;">
				<?php

				$point_select_items = [];
				foreach ( $view_var_points as $k => $point ) {
					$point_select_items[ $k ] = $point;
				}

				$params = [
					'type'    => 'select',
					'selected'    => $dispatch_point,
					'options' => $point_select_items,
					'class'   => [ 'wc-enhanced-select' ],
					'label'   => __( 'Dispatch point ', 'woocommerce-inpost' ),
				];
				woocommerce_form_field( 'dispatch_point', $params );
				?>

            </div>

            <div style="float:left;">
                <p>&nbsp;
                    <span class="tips"
                          data-tip="<?php esc_attr_e( 'From the list, select the packages that you want to be sent by courier.', 'woocommerce-inpost' ); ?>">
						<button id="easypack_get_courier" class="button-primary">
                            <?php _e( 'Get courier', 'woocommerce-inpost' ); ?>
                        </button>&nbsp;
					</span>
                </p>

            </div>

            <div style="float:left;">
                <p><span id="easypack_spinner_get_courier" class="spinner"></span></p>
            </div>
            <div style="clear:both;"></div>

        <?php else:?>

            <p style="font-style: italic"><?php _e( 'Filter shipments by courier to show pickup options', 'woocommerce-inpost' ) ?></p>

        <?php endif; ?>


        <div style="float:none;">
            <h3><?php _e( 'Filters', 'woocommerce-inpost' ) ?></h3>

			<?php
			$params = [
				'type'        => 'select',
				'options'     => $view_var_send_methods,
				'class'       => [ 'wc-enhanced-select' ],
				'label'       => __( 'Send method ', 'woocommerce-inpost' ),
				'label_class' => 'admin-label',
				'input_class' => [ 'admin-input' ],
			];
			woocommerce_form_field( 'send_method', $params, EasyPack_Shipment_Manager::getSendingMethodFilterFromRequest() );
			?>
        </div>
        <div style="float:none;">
			<?php
			$params = [
				'type'        => 'select',
				'options'     => $view_var_statuses,
				'class'       => [ 'wc-enhanced-select' ],
				'label'       => __( 'Shipment status', 'woocommerce-inpost' ),
				'label_class' => 'admin-label',
				'input_class' => [ 'admin-input', 'max-width-select' ],
			];
			woocommerce_form_field( 'status', $params, EasyPack_Shipment_Manager::getStatusFilterFromRequest() );
			?>
        </div>
        <div style="float:none;">
			<?php
			$params = [
				'type'        => 'select',
				'options'     => $view_var_services,
				'class'       => [ 'wc-enhanced-select' ],
				'label'       => __( 'Service', 'woocommerce-inpost' ),
				'label_class' => 'admin-label',
				'input_class' => [ 'admin-input' ],
			];
			woocommerce_form_field( 'service', $params, EasyPack_Shipment_Manager::getServiceFilterFromRequest() );
			?>
        </div>
        <div style="float:none;">
			<?php
			$params = [
				'type'        => 'text',
				'class'       => [ '' ],
				'label'       => __( 'Tracking number', 'woocommerce-inpost' ),
				'label_class' => 'admin-label',
				'input_class' => [ 'admin-input' ],
			];
			woocommerce_form_field( 'tracking_number', $params, EasyPack_Shipment_Manager::getTrackingNumberFilterFromRequest() );
			?>
        </div>
        <div style="float:none;">
			<?php
			$params = [
				'type'        => 'number',
				'class'       => [ '' ],
				'label'       => __( 'Order ID', 'woocommerce-inpost' ),
				'label_class' => 'admin-label',
				'input_class' => [ 'admin-input' ],
			];
			woocommerce_form_field( 'order_id', $params, EasyPack_Shipment_Manager::getOrderIdFilterFromRequest() );
			?>
        </div>
        <div style="float:none;">
			<?php
			$params = [
				'type'        => 'text',
				'class'       => [ '' ],
				'label'       => __( 'Reference number', 'woocommerce-inpost' ),
				'label_class' => 'admin-label',
				'input_class' => [ 'admin-input' ],
			];
			woocommerce_form_field( 'reference_number', $params, EasyPack_Shipment_Manager::getReferenceNumberFilterFromRequest() );
			?>
        </div>
        <div style="float:none;">
			<?php
			$params = [
				'type'        => 'text',
				'class'       => [ '' ],
				'label'       => __( 'Receiver email', 'woocommerce-inpost' ),
				'label_class' => 'admin-label',
				'input_class' => [ 'admin-input' ],
			];
			woocommerce_form_field( 'receiver_email', $params, EasyPack_Shipment_Manager::getReceiverEmailFilterFromRequest() );
			?>
        </div>
        <div style="float:none;">
			<?php
			$params = [
				'type'        => 'text',
				'class'       => [ '' ],
				'label'       => __( 'Receiver phone', 'woocommerce-inpost' ),
				'label_class' => 'admin-label',
				'input_class' => [ 'admin-input' ],
			];
			woocommerce_form_field( 'receiver_phone', $params, EasyPack_Shipment_Manager::getReceiverPhoneFilterFromRequest() );
			?>
        </div>
        <div style="float:left;">
            <p>
                <input class="button button-primary" type="submit" value="<?php _e( 'Filter parcels', 'woocommerce-inpost' ); ?>"/>
            </p>
        </div>
        <div style="clear:both;"></div>


        <h3><?php _e( 'Actions for selected items', 'woocommerce-inpost' ) ?></h3>

        <div style="float:left;">
            <p>
				<?php if ( EasyPack_API()->is_pl() ) : ?>
                    <span>
				<?php else : ?>
                    <span class="tips" data-tip="">
				<?php endif; ?>
                    <button class="button button-primary" id="get_stickers" name="get_stickers">
                        <?php _e( 'Get stickers', 'woocommerce-inpost' ); ?>
                    </button>
				</span>
            </p>
        </div>
        <div style="width: 10px; display: block"></div>
        <div style="float:left; padding-left: 10px;">
            <p>
				<?php if ( EasyPack_API()->is_pl() && true === $is_courier_context ) : ?>
                    <span>
				<?php else : ?>
                    <span class="tips" data-tip="">
				<?php endif; ?>
                    <button class="button button-primary" id="get_return_stickers" name="get_return_stickers">
                        <?php _e( 'Get return stickers', 'woocommerce-inpost' ); ?>
                    </button>
				</span>
            </p>
        </div>

        <div style="float:left;">
            <p><span id="easypack_spinner_get_stickers" class="spinner"></span>
            </p>
        </div>
        <div style="clear:both;"></div>


        <div style="float:left;">
            <p>
				<?php if ( EasyPack_API()->api_country() == EasyPack_API()::COUNTRY_PL ) : ?>
                    <span>
				<?php else : ?>
                    <span class="tips" data-tip="<?php esc_attr_e( 'From the list, select the packages that you want to be collected to be sent. If Courier has been chosen, the collection of your packages by a courier will be arranged.',
                        'woocommerce-inpost' ); ?>">
				<?php endif; ?>&nbsp;
				</span>
            </p>
        </div>
        <div style="float:left;">
            <p><span id="easypack_spinner_get_stickers" class="spinner"></span>
            </p>
        </div>
        <div style="clear:both;"></div>
		<?php /*
		<div style="float:left;">
			<p>
				<a id="easypack_create_manifest" class="button-primary" href=""><?php _e( 'Create manifest', 'woocommerce-inpost' ); ?></a>
			</p>
		</div>
		<div style="float:left;">
			<p><span id="easypack_spinner_create_manifest" class="spinner"></span></p>
		</div>
		<div style="clear:both;"></div>
	*/ ?>
    </form>
    <form id="easypack_shipment_form" method="post">
        <input type="hidden" id="easypack_create_manifest_input"
               name="easypack_create_manifest_input" value="0"/>
        <input type="hidden" id="easypack_dispatch_point"
               name="easypack_dispatch_point" value="0"/>
        <input type="hidden"
               name="page" value="easypack_shipment">
        <input type="hidden" name="easypack_get_stickers_request"
               id="easypack_get_stickers_request" value="0"/>
        <input type="hidden" name="easypack_get_stickers_ret_request"
               id="easypack_get_stickers_ret_request" value="0"/>
        <input type="hidden" name="easypack_get_sticker_single_request"
               id="easypack_get_sticker_single_request" value="0"/>
        <input type="hidden" name="get_sticker_order_id"
               id="get_sticker_order_id" value=""/>
        <input type="hidden" name="easypack_get_sticker_single_request_ret"
               id="easypack_get_sticker_single_request_ret" value="0"/>


		<?php $view_var_shipment_manager_list_table->display(); ?>

    </form>

</div>

<script type="text/javascript">

    jQuery('#easypack_get_courier').click(function () {

        var parcels = [];
        var count_parcels = 0;
        jQuery('input.easypack_parcel').each(function (i) {
            if (jQuery(this).is(':checked')) {
                parcels[i] = jQuery(this).val();
                count_parcels++;
            }
        });
        if (count_parcels == 0) {
            alert('<?php _e( 'No parcels selected.', 'woocommerce-inpost' ) ?>');
            jQuery("#easypack_spinner_get_stickers").removeClass("is-active");
            return false;
        }
        jQuery('#easypack_create_manifest_input').val(1);
        jQuery('#easypack_dispatch_point').val(jQuery('#dispatch_point').val());
        jQuery("#easypack_shipment_form").submit();
        return false;
    });


    jQuery('#refresh_statuses_btn').click(function () {
        var obj = jQuery('<input>', {
            'type': 'hidden',
            'name': 'refresh_statuses',
            'value': '1'
        });
        jQuery('#easypack_shipment_form').append(obj).submit();
        return false;
    });


    jQuery('#get_stickers').click(function () {
        var parcels = [];
        var count_parcels = 0;
        jQuery('input.easypack_parcel').each(function (i) {
            if (jQuery(this).is(':checked')) {
                parcels[i] = jQuery(this).val();
                count_parcels++;
            }
        });
        if (count_parcels === 0) {
            alert('<?php _e( 'No parcels selected.', 'woocommerce-inpost' ) ?>');
            jQuery('#easypack_spinner_get_stickers').removeClass("is-active");
            return false;
        }

        jQuery('#easypack_get_stickers_request').val('1');
        jQuery('#easypack_shipment_form').attr('target', '_blank');
        jQuery('#easypack_shipment_form').submit();
        jQuery('#easypack_shipment_form').attr('target', '_self');
        jQuery('#easypack_get_stickers_request').val('0');

        return false;
    });

    jQuery('.get_sticker_action').click(function () {
        var parcel = jQuery(this).data('id');

        jQuery('#get_sticker_order_id').val(parcel);
        jQuery('#easypack_get_sticker_single_request').val('1');
        jQuery('#easypack_shipment_form').attr('target', '_blank');
        jQuery('#easypack_shipment_form').submit();
        jQuery('#easypack_shipment_form').attr('target', '_self');
        jQuery('#easypack_get_sticker_single_request').val('0');
        jQuery('#order_id').val('');

        return false;
    });

    jQuery('.get_sticker_return_action').click(function () {
        var parcel = jQuery(this).data('id');

        jQuery('#get_sticker_order_id').val(parcel);
        jQuery('#easypack_get_sticker_single_request_ret').val('1');
        jQuery('#easypack_shipment_form').attr('target', '_blank');
        jQuery('#easypack_shipment_form').submit();
        jQuery('#easypack_shipment_form').attr('target', '_self');
        jQuery('#easypack_get_sticker_single_request_ret').val('0');
        jQuery('#order_id').val('');

        return false;
    });

    jQuery('#get_return_stickers').click(function () {
        var parcels = [];
        var count_parcels = 0;
        jQuery('input.easypack_parcel').each(function (i) {
            if (jQuery(this).is(':checked')) {
                parcels[i] = jQuery(this).val();
                count_parcels++;
            }
        });
        if (count_parcels == 0) {
            alert('<?php _e( 'No parcels selected.', 'woocommerce-inpost' ) ?>');
            jQuery('#easypack_spinner_get_stickers').removeClass("is-active");
            return false;
        }

        jQuery('#easypack_get_stickers_ret_request').val('1');
        jQuery('#easypack_shipment_form').attr('target', '_blank');
        jQuery('#easypack_shipment_form').submit();
        jQuery('#easypack_shipment_form').attr('target', '_self');
        jQuery('#easypack_get_stickers_ret_request').val('0');

        return false;
    });

    jQuery('.easypack_parcel').change(function () {
        var easypack_get_courier_disabled = false;
        var easypack_get_courier_count = 0;
        jQuery('.easypack_parcel').each(function () {
            if (jQuery(this).is(':checked')) {
                easypack_get_courier_count++;
                if (jQuery(this).data('status') !== 'created'
                    && jQuery(this).data('status') !== 'confirmed') {
                    easypack_get_courier_disabled = true;
                }
            }
        });
        if (easypack_get_courier_count == 0) easypack_get_courier_disabled = true;
        jQuery('#easypack_get_courier').attr('disabled', easypack_get_courier_disabled);
    });

</script>

<style>
    .optional {
        display: none;
    }
</style>