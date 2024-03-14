<?php /** @var ShipX_Shipment_Model $shipment */

use InspireLabs\WoocommerceInpost\EasyPack;
use InspireLabs\WoocommerceInpost\EasyPack_Helper;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Model;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Parcel_Model; ?>
<?php
if ( ! defined('ABSPATH') ) {
    exit;
} ?>

<?php
$status_service = EasyPack()->get_shipment_status_service();
?>

<?php if ( true === $wrong_api_env ): ?>
    <?php
    $internal_data = $shipment->getInternalData();
    $origin_api = $shipment->getInternalData()->getApiVersion();
    if ( $internal_data->getApiVersion()
        === $internal_data::API_VERSION_PRODUCTION
    ):?>

    <?php endif; ?>

    <?php if ( $internal_data->getApiVersion()
        === $internal_data::API_VERSION_PRODUCTION
    ): ?>
        <span style="font-weight: bold; color: #a00">
            <?php _e('This shipment was created in production API. Change API environment to production to process this shipment', 'woocommerce-inpost') ?>
        </span>

    <?php endif; ?>

    <?php if ( $internal_data->getApiVersion()
        === $internal_data::API_VERSION_SANDBOX
    ): ?>
        <span style="font-weight: bold; color: #a00">
            <?php _e('This shipment was created in sandbox API. Change API environment to sandbox to process this shipment', 'woocommerce-inpost') ?>
        </span>
    <?php endif; ?>
    <?php return; ?>
<?php endif; ?>

<?php $first_parcel = true;
$shipment_service = EasyPack()->get_shipment_service();
?>

<?php
$class = array('wc-enhanced-select');
$custom_attributes = array('style' => 'width:100%;');
if ( $disabled ) {
    $custom_attributes[ 'disabled' ] = 'disabled';
    $class[] = 'easypack-disabled';
}
?>


<p>
    <label for="parcel_machine_id"><?php _e('Selected parcel locker', 'woocommerce-inpost')?></label>
    <input value="<?php echo esc_attr( $parcel_machine_id ); ?>" type="text"
           class="settings-geowidget" id="parcel_machine_id" name="parcel_machine_id" data-geowidget_config="<?php echo sanitize_text_field( $geowidget_config ); ?>"
        <?php echo $disabled ? ' disabled ' : ''; ?>
    >
</p>
<p>
    <span style="font-weight: bold"><?php _e('Service:', 'woocommerce-inpost') ?>
    </span>
    <span>
        <?php echo esc_html( $selected_service ); ?>
    </span>
</p>

<p><span style="font-weight: bold"><?php _e('Status:', 'woocommerce-inpost') ?> </span>
    <?php if ($shipment instanceof ShipX_Shipment_Model): ?>
    <?php $status = $shipment->getInternalData()->getStatus() ?>
    <?php $status_title = $shipment->getInternalData()->getStatusTitle() ?>
    <?php $status_desc = $shipment->getInternalData()->getStatusDescription() ?>
    <span title="<?php echo esc_attr( $status_desc ); ?>"><?php echo esc_html( $status_title ); ?>
        (<?php echo esc_html( $status ); ?>)</span></p>
<?php if ( $shipment->isCourier() ) {
    $send_method = 'courier';
}

if ( $shipment->isParcelMachine() ) {
    $send_method = 'parcel_machine';
}
?>
<?php else: ?>
    <?php _e('Not created yet (new)', 'woocommerce-inpost') ?>
<?php endif ?>

<?php if ( ! empty($shipment instanceof ShipX_Shipment_Model
    && $shipment->getInternalData()->getTrackingNumber())
): ?>
    <span style="font-weight: bold">
            <?php _e('Tracking number:', 'woocommerce-inpost') ?>
    </span>

    <a target="_blank"
       href="<?php echo esc_url( $shipment_service->getTrackingUrl($shipment) ); ?>">
        <?php echo esc_html( $shipment->getInternalData()->getTrackingNumber() ); ?>
    </a>
    <div class="padding-bottom15"></div>
<?php endif ?>

<?php include('costs/html-order-metabox-costs.php'); ?>

<p><?php _e('Attributes:', 'woocommerce-inpost'); ?>
<ul id="easypack_parcels" style="list-style: none">
    <?php /** @var ShipX_Shipment_Parcel_Model $parcel */ ?>
    <?php /** @var ShipX_Shipment_Parcel_Model[] $parcels */ ?>

    <?php foreach ($parcels as $parcel) : ?>
        <li>
            <?php if ( $status == 'new' ) : ?>
                <?php
                $params = array(
                    'type' => 'select',
                    'options' => $package_sizes,
                    'class' => array('easypack_parcel'),
                    'input_class' => array('easypack_parcel'),
                    'label' => '',
                );

                $saved_meta_data = get_post_meta( $order_id, '_easypack_parcels', true );

                $saved_package_size = isset( $saved_meta_data[0]['package_size'] )
                    ? $saved_meta_data[0]['package_size']
                    : Easypack_Helper()->get_parcel_size_from_settings($order_id);

                woocommerce_form_field( 'parcel[]', $params, $saved_package_size );


                $cod_amount = isset( $saved_meta_data[0]['cod_amount'] )
                    ? $saved_meta_data[0]['cod_amount']
                    : $order->get_total();

                ?>
                <?php _e( 'COD amount: ', 'woocommerce-inpost' ); ?>
                <label style="display: block" for="cod_amount[]">
                    <input class="easypack_cod_amount" type="number" style=""
                           value="<?php echo esc_attr( $cod_amount ); ?>"
                           placeholder="0.00" step="any" min="0"
                           name="cod_amount[]">
                </label>


                <?php if ( $status == 'new' && ! $first_parcel ) : ?>
                    <button class="button easypack_remove_parcel"><?php _e('Remove', 'woocommerce-inpost'); ?></button>
                <?php endif; ?>
            <?php else : ?>
                <?php _e( 'Size', 'woocommerce-inpost' ); ?>:
                <?php echo '<span style="font-size: 16px">'; ?>
                <?php echo esc_html( EasyPack_Helper()->convert_size_to_symbol( $parcel->getTemplate() ) ); ?>
                <?php echo '</span>'; ?>
                <br>
                <?php if( $shipment->getCod() ) {
                    $cod_amount = $shipment->getCod()->getAmount();
                } else {
                    $cod_amount = 0;
                } ?>
                <?php _e( 'COD amount', 'woocommerce-inpost' ); ?>: <?php echo esc_html( $cod_amount ); ?>
            <?php endif; ?>
        </li>
        <?php $first_parcel = false; ?>
    <?php endforeach; ?>
</ul>

</p>


<?php include('services/html-service-insurance.php'); ?>
<?php include('html-field-reference.php'); ?>


<?php
$custom_attributes = array('style' => 'width:100%;');
if ( $disabled || $send_method_disabled ) {
    $custom_attributes[ 'disabled' ] = 'disabled';
}
$params = array(
    'type' => 'select',
    'options' => $send_methods,
    'class' => array('wc-enhanced-select'),
    'custom_attributes' => $custom_attributes,
    'label' => __('Send method', 'woocommerce-inpost'),
);

$send_method = get_post_meta( $order_id, '_easypack_send_method', true )
    ? get_post_meta( $order_id, '_easypack_send_method', true )
    : $send_method;

woocommerce_form_field('easypack_send_method', $params, $send_method);
?>

<p>
    <?php if ( $status == 'new' ) : ?>
        <button id="easypack_send_parcels"
                class="button button-primary"><?php _e('Send parcel', 'woocommerce-inpost'); ?></button>
    <?php endif; ?>

    <?php include( 'html-no-funds-alert.php' ); ?>

    <?php if ( $shipment instanceof ShipX_Shipment_Model
        && !empty($shipment->getInternalData()->getTrackingNumber())) : ?>
        <input id="get_stickers" type="submit" class="button button-primary"
               value="<?php _e('Get sticker(s)', 'woocommerce-inpost'); ?>">
        <input type="hidden" name="easypack_get_stickers_request"
               id="easypack_get_stickers_request">
        <input type="hidden" name="easypack_parcel"
               value="<?php echo esc_attr( $shipment->getInternalData()->getOrderId() ); ?>">
    <?php endif; ?>

    <span id="easypack_spinner" class="spinner"></span>
</p>

<p id="easypack_error"></p>

<a href="#" download id="easypack_download" target="_blank" hidden></a>

<script type="text/javascript">

    <?php if ( ! empty($shipment instanceof ShipX_Shipment_Model
        && $shipment->getInternalData()->getTrackingNumber())
    ) { ?>

    document.addEventListener('click', function (e) {
        e = e || window.event;
        var target = e.target || e.srcElement;
        if (target.hasAttribute('id') && target.getAttribute('id') == 'get_stickers') {
            e.preventDefault();
            e.stopPropagation();
            jQuery('#easypack_error').html('');

            var beforeSend = function(){
                jQuery("#easypack_spinner").addClass("is-active");
                jQuery('#easypack_send_parcels').attr('disabled', true);
            };

            var action = 'easypack';
            var easypack_action = 'easypack_create_bulk_labels';
            var order_ids = <?php echo esc_attr( $order_id ); ?>;
            beforeSend();
            var request = new XMLHttpRequest();
            request.open('POST', ajaxurl, true);
            request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
            request.responseType = 'blob';

            request.onload = function() {
                // Only handle status code 200
                if( request.status === 200 && request.response.size > 0 ) {

                    var content_type = request.getResponseHeader("content-type");
                    if( content_type === 'application/pdf' ) {

                        var filename = 'inpost_zamowenie_' + order_ids + '.pdf';

                        // download file
                        var blob = new Blob([request.response], {type: 'application/pdf'});
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = filename;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    } else {
                        // some error occured
                        let text_from_blob = new Blob([request.response], { type: 'text/html' });
                        var reader = new FileReader();
                        reader.onload = function() {
                            let textResponse = JSON.parse(reader.result);
                            console.log(textResponse);
                            if( textResponse.details.key == 'ParcelLabelExpired' ) {
                                jQuery('#easypack_error').html('Etykieta wygasła');
                                jQuery('#easypack_error').css('color', '#f00');
                            } else {
                                alert(reader.result);
                            }
                        };
                        reader.readAsText(text_from_blob);
                        jQuery("#easypack_spinner").removeClass("is-active");
                        jQuery('#easypack_send_parcels').attr('disabled', false);
                        return;
                    }

                    jQuery("#easypack_spinner").removeClass("is-active");
                    jQuery('#easypack_send_parcels').attr('disabled', false);
                } else {
                    jQuery('#easypack_error').html('Wystąpił błąd');
                    jQuery('#easypack_error').css('color', '#f00');
                }

                jQuery("#easypack_spinner").removeClass("is-active");
                jQuery('#easypack_send_parcels').attr('disabled', false);
            };

            request.send('action=' + action + '&easypack_action=' + easypack_action +'&security=' + easypack_nonce + '&order_ids=' + JSON.stringify([order_ids]));
        }
    });
    <?php } ?>

    jQuery(document).ready(function() {
        if(jQuery('select.easypack_parcel').val() === 'xlarge') {
            jQuery('#easypack_send_method option').each(function(ind, elem) {                
                if(jQuery(elem).val() === 'parcel_machine') {                    
                    jQuery(elem).prop('disabled', 'disabled');
                }
            });
        }        
    });
	
    jQuery('select.easypack_parcel').on('change', function () {
        if(jQuery(this).val() === 'xlarge') {            
            jQuery('#easypack_send_method option').each(function(ind, elem) {                
                if(jQuery(elem).val() === 'parcel_machine') {                    
                    jQuery(elem).prop('disabled', 'disabled');
                }
            });
        } else {            
            jQuery('#easypack_send_method option').each(function(ind, elem) {                
				jQuery(elem).prop('disabled', false);                
            });
        }
    });

    jQuery('#easypack_send_parcels').click(function (e) {
        jQuery('#easypack_error').html('');
        jQuery(this).attr('disabled', true);
        jQuery("#easypack_spinner").addClass("is-active");
        var parcels = [];
        jQuery('select.easypack_parcel').each(function (i) {
            parcels[i] = jQuery(this).val();
        });

        if (!parcels.length ) {
            let alternate_parcels_find = jQuery('#easypack_parcels').find('select').val();
            parcels.push(alternate_parcels_find);
        }

        var insurance_amounts = [];
        jQuery('input.insurance_amount').each(function (i) {
            insurance_amounts[i] = jQuery(this).val();
        });

        var cod_amounts = [];
        jQuery('input.easypack_cod_amount').each(function (i) {
            cod_amounts[i] = jQuery(this).val();
        });


        var data = {
            action: 'easypack',
            easypack_action: 'courier_c2c_create_package_cod',
            security: easypack_nonce,
            order_id: <?php echo esc_attr( $order_id ); ?>,
            parcel_machine_id: jQuery('#parcel_machine_id').val(),
            parcels: parcels,
            cod_amounts: cod_amounts,
            send_method: jQuery('#easypack_send_method').val(),
            insurance_amounts: insurance_amounts,
            reference_number: jQuery('#reference_number').val()
        };
        jQuery.post(ajaxurl, data, function (response) {
            //console.log(response);
            if (response != 0) {
                response = JSON.parse(response);
                //console.log(response);
                //console.log(response.status);
                if (response.status == 'ok') {
                    jQuery("#easypack_parcel_machines .inside").html(response.content);

                    return false;
                }
                else {
                    //alert(response.message);
                    jQuery('#easypack_error').html(response.message);
                    jQuery('#easypack_error').css('color', '#f00');
                }
            }
            else {
                //alert('Bad response.');
                jQuery('#easypack_error').html('Invalid response.');
                jQuery('#easypack_error').css('color', '#f00');
            }
            jQuery("#easypack_spinner").removeClass("is-active");
            jQuery('#easypack_send_parcels').attr('disabled', false);
        });
        return false;

    });

</script>
