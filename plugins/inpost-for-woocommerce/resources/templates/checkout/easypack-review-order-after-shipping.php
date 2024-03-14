<?php
/**
 * Review Order After Shipping EasyPack
 *
 * @author
 * @package    EasyPack/Templates
 * @version
 */

use InspireLabs\WoocommerceInpost\Geowidget_v5;

$parcel_machine_selected = false;
$selected                = '';


// double fields for DIVI templates
add_action('woocommerce_review_order_before_submit', function() {
?>
<input
	type="hidden"
	id="divi_parcel_machine_id"
	name="parcel_machine_id"
	value=""
/>
<input
	type="hidden"
	id="divi_parcel_machine_desc"
	name="parcel_machine_desc"
	value=""
/>
<?php 
} );


?>

<tr class="easypack-parcel-machine">
    <th class="easypack-parcel-machine-label">
		<?php __( 'Select Parcel Locker', 'woocommerce-inpost' ); ?>
    </th>
    <td class="easypack-parcel-machine-select">
		<?php if ( defined( 'DOING_AJAX' ) && true === DOING_AJAX ): ?>

            <div class="easypack_show_geowidget" id="easypack_show_geowidget">
				<?php echo __( 'Select parcel locker', 'woocommerce-inpost' ); ?>
            </div>

            <script type="text/javascript">
                var geowidgetModal;

                function selectPointCallback(point) {
					
                    parcelMachineAddressDesc = point.location_description;
                    jQuery('.parcel_machine_id').val(point.name);
                    jQuery('#divi_parcel_machine_id').val(point.name);
                    jQuery('.parcel_machine_desc').val(parcelMachineAddressDesc);
                    jQuery('#divi_parcel_machine_desc').val(parcelMachineAddressDesc);
					
                    // some woo stores have re-built Checkout pages and multiple '#' id is possible
					jQuery('*[id*=selected-parcel-machine]').each(function(ind, elem) {						
						jQuery(elem).removeClass('hidden-paczkomat-data');
					});					
                    
                    if( typeof point.location_description != 'undefined' && point.location_description !== null ) {                        
                        jQuery('*[id*=selected-parcel-machine-id]').each(function(ind, elem) {							
							jQuery(elem).html(point.name + ' (' + point.location_description + ')');
						});                        
                    } else {                        
                        jQuery('*[id*=selected-parcel-machine-id]').each(function(ind, elem) {							
							jQuery(elem).html(point.name);
						});
                    }

                    // for some templates like Divi - add hidden fields for Parcel locker validation dynamically
                    var form = document.getElementsByClassName('checkout woocommerce-checkout')[0];
                    var additionalInput1 = document.createElement('input');
                    additionalInput1.type = 'hidden';
                    additionalInput1.name = 'parcel_machine_id';
                    additionalInput1.value = point.name;

                    var additionalInput2 = document.createElement('input');
                    additionalInput2.type = 'hidden';
                    additionalInput2.name = 'parcel_machine_desc';
                    additionalInput2.value = parcelMachineAddressDesc;

                    if(form) {
                        form.appendChild(additionalInput1);
                        form.appendChild(additionalInput2);
                    }
					
					
					let EasyPackPointObject = { 'pointName': point.name, 'pointDesc': parcelMachineAddressDesc };
					// Put the object into storage
					localStorage.setItem('EasyPackPointObject', JSON.stringify(EasyPackPointObject));

                    geowidgetModal.close();
                }

                document.addEventListener('click', function (e) {
                    e = e || window.event;
                    var target = e.target || e.srcElement;

                    if (target.hasAttribute('id') && target.getAttribute('id') == 'easypack_show_geowidget') {
                        e.preventDefault();
                        if( 'undefined' != typeof geowidgetModal.isOpen && ! geowidgetModal.isOpen ) {
                            geowidgetModal.open();
                        }
                    }
                });
				
				
				jQuery(document.body).on('updated_checkout', function() {

                    let EasyPackPointObject = localStorage.getItem('EasyPackPointObject');

                    if (EasyPackPointObject !== null) {
                        let point,
                            desc;

                        let pointData = JSON.parse(EasyPackPointObject);

                        if( typeof pointData.pointName != 'undefined' && pointData.pointName !== null ) {
                            point = pointData.pointName;
                        }

                        if( typeof pointData.pointDesc != 'undefined' && pointData.pointDesc !== null ) {
                            desc = pointData.pointDesc;
                        } else {
                            desc = '';
                        }
						
						if( typeof pointData.pointAddDesc != 'undefined' && pointData.pointAddDesc !== null ) {
							additional_desc = ' (' + pointData.pointAddDesc + ')'						
						} else {
							additional_desc = '';
						}
						
						

                        if( point && desc) {
                            jQuery('*[id*=selected-parcel-machine-id]').each(function(ind, elem) {
                                jQuery(elem).html(point + ' ' + desc + additional_desc);
                            });
                            jQuery('*[id*=selected-parcel-machine]').each(function(ind, elem) {
                                jQuery(elem).removeClass('hidden-paczkomat-data');
                            });
                            jQuery('.parcel_machine_id').val(point);
                            jQuery('.parcel_machine_desc').val(desc);
                            jQuery('#divi_parcel_machine_id').val(point);
                            jQuery('#divi_parcel_machine_desc').val(desc);

                            jQuery('#easypack_show_geowidget').text('<?php echo __( 'Change Parcel Locker', 'woocommerce-inpost' ); ?>');
                        } else if ( point ) {
                            jQuery('*[id*=selected-parcel-machine-id]').each(function(ind, elem) {
                                jQuery(elem).html(point);
                            });
                            jQuery('*[id*=selected-parcel-machine]').each(function(ind, elem) {
                                jQuery(elem).removeClass('hidden-paczkomat-data');
                            });
                            jQuery('.parcel_machine_id').val(point);
                            jQuery('.parcel_machine_desc').val('');
                            jQuery('#divi_parcel_machine_id').val(point);
                            jQuery('#divi_parcel_machine_desc').val('');
                            jQuery('#easypack_show_geowidget').text('<?php echo __( 'Change Parcel Locker', 'woocommerce-inpost' ); ?>');
                        }

                    }

                } );

                jQuery(document).ready(function () {
					// create modal with map

                    var wH = jQuery(window).height()-100;

                    geowidgetModal = new jBox('Modal', {
                        width: <?php echo esc_attr( Geowidget_v5::GEOWIDGET_WIDTH ); ?>,
                        height: wH,
                        attach: '#easypack_show_geowidget',
                        title: '<?php echo __( 'Select parcel locker', 'woocommerce-inpost' ); ?></a>',
                        content: `<?php printf( '<inpost-geowidget id="inpost-geowidget" onpoint="selectPointCallback"
                    token="%s"
                    language="pl" config="%s"></inpost-geowidget>',
							( new Geowidget_v5() )->get_token(),
							( new Geowidget_v5() )->get_pickup_delivery_configuration( $shipping_method_id ) )?>`
                    });

                    jQuery('#easypack_show_geowidget').on('click', function () { geowidgetModal.open() });
                });
            </script>


            <div id="selected-parcel-machine" class="hidden-paczkomat-data">
                <div><span class="font-height-600">
                <?php echo __( 'Selected parcel locker:', 'woocommerce-inpost' ); ?>
                </span></div>
                <span class="italic" id="selected-parcel-machine-id"></span>

                <input type="hidden" id="parcel_machine_id"
                       name="parcel_machine_id" class="parcel_machine_id"/>
                <input type="hidden" id="parcel_machine_desc"
                       name="parcel_machine_desc" class="parcel_machine_desc"/>
            </div>

		<?php else: ?>

		<?php endif ?>


    </td>
</tr>
