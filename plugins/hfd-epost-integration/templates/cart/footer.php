<?php
/**
 * Created by PhpStorm.
 * Date: 6/6/18
 * Time: 3:10 PM
 */

if( !defined( 'ABSPATH' ) ){
	exit; // Exit if accessed directly.
}

$setting = \Hfd\Woocommerce\Container::get( 'Hfd\Woocommerce\Setting' );
$layout = $setting->get( 'betanet_epost_layout' );
?>
<?php if( $layout == 'map' ): ?>
    <div id="israelpost-modal" style="display: none">
        <div id="israelpost-autocompelete">
            <input id="pac-input" class="controls" type="text" placeholder="<?php echo esc_html( __( 'Please enter an address', 'hfd-integration' ) ); ?>" />
        </div>
        <div id="legend" style="height: 45px;" class="pac-inner">
            <div style="float: left;">
                <span><?php echo esc_html( __( 'Lockers', 'hfd-integration' ) ); ?></span>
                <img src="<?php echo esc_html( $this->getSkinUrl( 'images/red-dot.png' ) ); ?>" alt="" width="20" height="32" />
            </div>
            <div style="float: left;">
                <span><?php echo esc_html( __( 'Store', 'hfd-integration' ) ); ?></span>
                <img src="<?php echo esc_html( $this->getSkinUrl( 'images/grn-dot.png' ) ); ?>" alt="" width="20" height="32" />
            </div>
        </div>
        <div id="israelpost-map" style="width: 100%; max-width: 750px; height: 450px;"></div>
    </div>
	<?php
	wp_enqueue_script( 'hfd-gscript', '//maps.googleapis.com/maps/api/js?v=3.37&libraries=places&language=he&key='.$setting->getGoogleApiKey() );
	wp_enqueue_script( 'hfd-gmaps', $this->getSkinUrl( 'js/infobox.js' ) );
	wp_enqueue_script( 'hfd-common-js', $this->getSkinUrl( 'js/common.js' ) );
	wp_enqueue_script( 'hfd-gmap-js', $this->getSkinUrl( 'js/map.js' ) );
	wp_enqueue_script( 'hfd-pickup-post', $this->getSkinUrl( 'js/pickup-post.js' ) );
	wp_enqueue_script( 'hfd-checkout-js', $this->getSkinUrl( 'js/checkout.js' ) );
	wp_enqueue_script( 'hfd-translator-js', $this->getSkinUrl( 'js/translator.js' ) );
	?>
    <script type="text/javascript">
        var $j
        document.addEventListener("DOMContentLoaded", function() {
            $j = jQuery;
            Translator.add( 'Select','<?php esc_html_e( 'Select', 'hfd-integration' ); ?>');
            Translator.add( 'Change pickup branch','<?php esc_html_e( 'Change pickup branch', 'hfd-integration' ); ?>');
            Translator.add( 'Please wait','<?php esc_html_e( 'Please wait', 'hfd-integration' ); ?>');
            Translator.add( 'Branch name','<?php esc_html_e( 'Branch name', 'hfd-integration' ); ?>');
            Translator.add( 'Branch address','<?php esc_html_e( 'Branch address', 'hfd-integration' ); ?>');
            Translator.add( 'Operating hours','<?php esc_html_e( 'Operating hours', 'hfd-integration' ); ?>');
            Translator.add( 'Please choose pickup branch','<?php esc_html_e( 'Please choose pickup branch', 'hfd-integration' ); ?>');
            Translator.add( 'Select a collection point','<?php esc_html_e( 'Select a collection point', 'hfd-integration' ); ?>');
			
			IsraelPostCommon.init({
				saveSpotInfoUrl: '<?php echo esc_html( admin_url( 'admin-ajax.php' ) ); ?>',
				getSpotsUrl: '<?php echo esc_html( admin_url( 'admin-ajax.php?action=get_spots' ) ); ?>',
				redDotPath: '<?php echo esc_html( $this->getSkinUrl( 'images/red-dot.png' ) ); ?>',
				grnDotPath: '<?php echo esc_html( $this->getSkinUrl( '/images/grn-dot.png' ) ); ?>'
			});
			/* jQuery( "body" ).on( "update_checkout", function(){
				IsraelPostCommon.destroy();
			}); */
			
			var hfdObj = false;
			jQuery( "body" ).on( "updated_checkout", function(e){
				var mainBlock = jQuery('#israelpost-additional');
				if( mainBlock.parent().find( 'input.shipping_method' ).is(':checked') ){
					/* if( !hfdObj ){
						IsraelPostCommon.init({
							saveSpotInfoUrl: '<?php echo esc_html( admin_url( 'admin-ajax.php' ) ); ?>',
							getSpotsUrl: '<?php echo esc_html( admin_url( 'admin-ajax.php?action=get_spots' ) ); ?>',
							redDotPath: '<?php echo esc_html( $this->getSkinUrl( 'images/red-dot.png' ) ); ?>',
							grnDotPath: '<?php echo esc_html( $this->getSkinUrl( '/images/grn-dot.png' ) ); ?>'
						});
						IsraelPost.showPickerPopup(e);
						//hfdObj = true;
					} */
					//console.log( hfdObj );
					mainBlock.show();
				}else{
					mainBlock.hide();
				}
			});
			jQuery( "body" ).on( "click", "#israelpost-additional .spot-picker", function(e){
				IsraelPost.showPickerPopup(e);
				return false;
			});
			
			jQuery( "body" ).on( 'click', '.selectspot', function(e){
				e.preventDefault();
				var spotId = $j(this).data('shopid');
				//console.log( IsraelPostMap.markers );
				spot = IsraelPostMap.markers[spotId].json;
				
				var html = this.spotTemplate = '<strong>' + Translator.translate('Branch name') + ':</strong> '+ spot.name +' <br/>'
				+ '<strong>' + Translator.translate('Branch address') + ':</strong> '+ spot.street +' '+ spot.house +', '+ spot.city +' <br/>'
				+ '<strong>' + Translator.translate('Operating hours') + ':</strong> '+ spot.remarks;
				
				jQuery( '#israelpost-additional .spot-detail' ).html( html );
				IsraelPost.saveSpotInfo(spot);
				IsraelPost.renderSpotId(spot.n_code);
				IsraelPost.closeModal();
				return false;
			});
        });
    </script>
<?php else:
	$helper = \Hfd\Woocommerce\Container::get('Hfd\Woocommerce\Helper\Spot');
	wp_enqueue_script( 'hfd-translator', $this->getSkinUrl( 'js/translator.js' ) );
	wp_enqueue_script( 'hfd-epost-list', $this->getSkinUrl( 'js/epost-list.js' ), array(), time() );
    ?>
    <script type="text/javascript">
        document.addEventListener( "DOMContentLoaded", function(){
			Translator.add( 'Select a collection point','<?php esc_html_e( 'Select a collection point', 'hfd-integration' ); ?>');
			Translator.add( 'Select pickup point','<?php esc_html_e( 'Select pickup point', 'hfd-integration' ); ?>');
			Translator.add( 'There is no pickup point','<?php esc_html_e( 'There is no pickup point', 'hfd-integration' ); ?>');
						
			var cityLoaded = false;
			jQuery( "body" ).on( "updated_shipping_method wc_fragments_loaded updated_checkout", function(){
				var mainBlock = jQuery('#israelpost-additional');
				if( mainBlock.siblings( 'input.shipping_method' ).is(':checked') ){
					if( !cityLoaded || mainBlock.find( '#city-list' ).find( 'option' ).length == 1 ){
						EpostList.init({
							saveSpotInfoUrl: '<?php echo esc_html( admin_url( 'admin-ajax.php' ) ); ?>',
							getSpotsUrl: '<?php echo esc_html( admin_url( 'admin-ajax.php?action=get_spots' ) ); ?>',
							cities: <?php echo json_encode( $helper->getCities() ); ?>
						});
						cityLoaded = true;
					}
					mainBlock.show();
				}else{
					mainBlock.hide();
				}
			});
			
			if( jQuery( "body" ).hasClass( "woocommerce-cart" ) ){
				var mainBlock = jQuery('#israelpost-additional');
				if( mainBlock.siblings( 'input.shipping_method' ).is(':checked') ){
					if( !cityLoaded || mainBlock.find( '#city-list' ).find( 'option' ).length == 1 ){
						EpostList.init({
							saveSpotInfoUrl: '<?php echo esc_html( admin_url( 'admin-ajax.php' ) ); ?>',
							getSpotsUrl: '<?php echo esc_html( admin_url( 'admin-ajax.php?action=get_spots' ) ); ?>',
							cities: <?php echo json_encode( $helper->getCities() ); ?>
						});
						cityLoaded = true;
					}
					mainBlock.show();
				}else{
					mainBlock.hide();
				}
			}
        });
    </script>
<?php endif;