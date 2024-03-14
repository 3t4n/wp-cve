<?php
/**
 * Review Order After Shipping EasyPack
 *
 * @author
 * @package    EasyPack/Templates
 * @version
 */

$parcel_machine_selected = false;
$selected = '';


?>
<tr class="easypack-parcel-machine">
    <th class="easypack-parcel-machine-label">
        <?php __('Wybierz paczkomat', 'apaczka'); ?>
    </th>
    <td class="easypack-parcel-machine-select">

        <?php
        $cod = isset($_cod) && true === $_cod;

        $countryCode = 'pl';
        $lon = '51.507351';
        $lat = '-0.127758';
        $loc = '';
        ?>
        <?php if (defined('DOING_AJAX') && true === DOING_AJAX): ?>

            <?php $randomId = 'id'.rand(1, 9999); ?>
            <a id="popup-btn"></a>


            <button class="button alt"
                    name="geowidget_show_map"
                    id="geowidget_show_map"
                    value="<?php echo __('Wybierz paczkomat', 'apaczka'); ?>"
                    data-value="<?php echo __('Wybierz paczkomat',
                        'apaczka'); ?>">
                <?php echo __('Wybierz paczkomat', 'apaczka'); ?></button>

            <script type="text/javascript">
                var initiated = false;

                window.easyPackAsyncInit = (function () {
                    easyPack.init({});
                });
                jQuery('#geowidget_show_map').click(function (e) {
                    e.preventDefault();
                    easyPack.init({
                        apiEndpoint: '<?php echo shipxApi::API_GEOWIDGET_URL_PRODUCTION_PL?>',
                        defaultLocale: 'pl',
                        closeTooltip: false,
                        points: {
                            types: ['parcel_locker', 'pop']
                        },
                        map: {
                            <?php echo $loc?>
                            useGeolocation: true
                        }
                    });
                    easyPack.modalMap(function (point, modal) {
                        modal.closeModal();
                        var parcelMachineAddressDesc = getAddressByPoint(point);
                        jQuery('#parcel_machine_id').val(point.name);
                        jQuery('#parcel_machine_building_number').val(point.address_details.building_number);
                        jQuery('#parcel_machine_city').val(point.address_details.city);
                        jQuery('#parcel_machine_post_code').val(point.address_details.post_code);
                        jQuery('#parcel_machine_province').val(point.address_details.province);
                        jQuery('#parcel_machine_street').val(point.address_details.street);
                        jQuery('#parcel_machine_desc').val(parcelMachineAddressDesc);
                        jQuery('#selected-parcel-machine').removeClass('hidden');
                        jQuery('#selected-parcel-machine-id').html(parcelMachineAddressDesc);

                        jQuery.ajax({
                            url: '<?php echo admin_url('admin-ajax.php'); ?>',
                            data: {
                                action: 'save_parcel_machine_address_wc_session',
                                nonce: jQuery('#apaczka_pma_nonce').val(),
                                parcel_machine_address: point.address_details,
                                parcel_machine_id: point.name,
                            },
                            method: 'POST',
                            dataType: 'json',
                            success: function (data) {
                            }
                        })
                     
                    }, {width: 500, height: 600});

                    setTimeout(function () {
                        jQuery("html, body").animate({scrollTop: jQuery('#widget-modal').offset().top}, 1000);

                    }, 0);
                });

                jQuery(document).ready(function () {
                    if (false === initiated) {
                        if (typeof(easyPack) !== 'undefined') {
                            easyPack.init({
                                apiEndpoint: '<?php shipxApi::API_GEOWIDGET_URL_PRODUCTION_PL?>',
                                defaultLocale: 'pl',
                                closeTooltip: false,
                                points: {
                                    types: ['parcel_locker']
                                },
                                map: {
                                    <?php echo $loc?>
                                    useGeolocation: true
                                }
                            });
                        }


                        initiated = true;
                    }
                });

            </script>
        
            <?php if ( WC()->session->get( 'parcel_machine_address_city' ) ) : ?>
            <div id="selected-parcel-machine">
                <div><b><?php echo __('Wybrany paczkomat:', 'apaczka'); ?></b></div>
                <span class="italic" id="selected-parcel-machine-id">
                    <?php echo WC()->session->get( 'parcel_machine_id' ) ?><br>
                    <?php echo WC()->session->get( 'parcel_machine_address_street' ) . ' ' . WC()->session->get( 'parcel_machine_address_building_number' ) ?><br>
                    <?php echo WC()->session->get( 'parcel_machine_address_post_code' ) . ' ' . WC()->session->get( 'parcel_machine_address_city' ) ?><br>
                </span>
            </div>
            <?php else: ?>
            <div id="selected-parcel-machine" class="hidden">
                <div><b><?php echo __('Wybrany paczkomat:', 'apaczka'); ?></b></div>
                <span class="italic" id="selected-parcel-machine-id"></span>
            </div>
            <?php endif; ?>
            
            
            <?php wp_nonce_field( 'wc_apc_nonce_pma', 'apaczka_pma_nonce' ) ?>
            <input type="hidden" id="parcel_machine_id" name="parcel_machine_id" value="<?php echo WC()->session->get( 'parcel_machine_id' ) ?>"/>
            <input type="hidden" id="parcel_machine_desc" name="parcel_machine_desc"/>
            <input type="hidden" id="parcel_machine_city" name="parcel_machine_city" value="<?php echo WC()->session->get( 'parcel_machine_address_city' ) ?>"/>
            <input type="hidden" id="parcel_machine_street" name="parcel_machine_street" value="<?php echo WC()->session->get( 'parcel_machine_address_street' ) ?>" />
            <input type="hidden" id="parcel_machine_building_number" name="parcel_machine_building_number" value="<?php echo WC()->session->get( 'parcel_machine_address_building_number' ) ?>" />
            <input type="hidden" id="parcel_machine_post_code" name="parcel_machine_post_code" value="<?php echo WC()->session->get( 'parcel_machine_address_post_code' ) ?>" />
            <input type="hidden" id="parcel_machine_province" name="parcel_machine_province" />
        <?php else: ?>

        <?php endif ?>
    </td>
</tr>
