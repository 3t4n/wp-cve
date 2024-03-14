<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$isSCCFreeVersion = defined( 'STYLISH_COST_CALCULATOR_VERSION' );
require_once SCC_DIR . '/lib/wp-google-fonts/google-fonts.php';

if ( ! $f1->translation ) {
    $translateArray = '[{"key":"Total","lang":"en","translation":""},{"key":"Description","lang":"en","translation":""},{"key":"Unit Price","lang":"en","translation":""},{"key":"Quantity","lang":"en","translation":""},{"key":"Price","lang":"en","translation":""},{"key":"SEND","lang":"en","translation":""},{"key":"Total Price","lang":"en","translation":""},{"key":"Summary","lang":"en","translation":""},{"key":"Email Quote","lang":"en","translation":""},{"key":"Email Quote Form","lang":"en","translation":""},{"key":"Prove that you are not a robot","lang":"en","translation":""},{"key":"Please wait...","lang":"en","translation":""},{"key":"Submit","lang":"en","translation":""},{"key":"Cancel","lang":"en","translation":""},{"key":"Email Confirmation","lang":"en","translation":""},{"key":"Thank you! We sent your quote to","lang":"en","translation":""},{"key":"Remember to check your spam folder.","lang":"en","translation":""},{"key":"Detailed List","lang":"en","translation":""},{"key":"Choose an option...","lang":"en","translation":""},{"key":"Search an option...","lang":"en","translation":""},{"key":"Your Name","lang":"en","translation":""},{"key":"Your Email","lang":"en","translation":""},{"key":"Your Phone","lang":"en","translation":""},{"key":"Your Phone (Optional)","lang":"en","translation":""},{"key":"Coupon Code","lang":"en","translation":""},{"key":"Please choose an option","lang":"en","translation":""},{"key":"Enter your coupon code","lang":"en","translation":""},{"key":"This code is not valid","lang":"en","translation":""},{"key":"Discount percentage","lang":"en","translation":""},{"key":"Your discount has been applied correctly","lang":"en","translation":""},{"key":"Your discount has not been applied because the total price has to be between","lang":"en","translation":""},{"key":"The total price must be a minimum of","lang":"en","translation":""},{"key":"TAX","lang":"en","translation":""},{"key":"Invoice ID","lang":"en","translation":""}]';
} else {
    $translateArray = $f1->translation;
}
$translateArray = json_decode( stripslashes( $translateArray ) );

?>
<div class="row">
	<div class="col-12">
		<!-- Calculator Title User Input-->
		<div class="row">
		<input type="text" id="id_form_input" value="<?php echo intval( $f1->id ); ?>" hidden>
		<div class="col-lg-6 col-md-6 col-xs-8 ms-2 my-4 scc-edit-calculator-name" style="display:inherit">
			<div class="col-lg-10 col-md-10 col-xs-10 d-inline-block" style="min-width: 600px; margin-right: 10px;">
				<input type="text" class="input_pad" id="costcalculatorname" placeholder="Enter the name of this calculator" value="<?php echo esc_attr( $f1->formname ); ?>" />
			</div>
			<!---SAVE BUTTON-->
			<div class="col-lg-2 col-md-2 col-xs-2 d-inline-block text-end scc-save-btn-cont"  data-setting-tooltip-type="" data-bs-original-title="">
				<button class="btn btn-primary m-0  d-inline-flex align-items-center align-items-center  scc-top-save-btn" onClick="saveDataFields()">
					<i class="scc-btn-spinner scc-save-btn-spinner scc-d-none"></i> Save
				</button>
			</div>
		</div>
		<!--END SAVE BUTTON-->
		</div>
		<!--END-->

		<!---EMBED BUTTON-->
	</div>
</div>

<div class="modal fade" id="settingsModal" tabindex="-1" aria-labelledby="settingsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable scc-modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="settingsModalLabel">Font Settings</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
		<div id="dfscc_tab_font_settings_">
		<div class="row ms-4 mb-5" id="myDIV">
			<!-- START OF FONT SETTINGS AND COLORS -->
			<div class="col-12 col-md-3 col-lg-3 addedFieldsStyle font-settings-container scc-title-total-font-settings" style="min-height: 270px;margin-top:30px;margin-right:20px;box-shadow: 0px 0px 8px 1px rgba(163,163,163,0.27);">
				<div class="font-settings-title-head">
					Title & Total Font Settings <i class="material-icons-outlined more-settings-info text-white" title="Choose the font settings for the section titles and the total price, in your calculator form." style="margin-right:5px">help_outline</i>
				</div><br>
				<div style="margin-top:20px;" class="col-md-12 clearfix use-premium-tooltip">
					<label class="scc_label col-xs-8 col-md-6">Font Size</label>
					<select name="titlepricefontsize" id="titlepricefontsize" style="box-shadow: 1px 1px 1px #999; border: 0 none;" class="col-xs-4 col-md-6" disabled>
						<option class="form-control servicepricefontsize" value="0">Size</option>
						<?php
                        for ( $n = 10; $n <= 70; $n++ ) {
                            if ( $n . 'px' == $f1->titleFontSize ) {
                                $select_ser = 'selected';
                            } else {
                                $select_ser = '';
                            }
                            ?>
							<option class="form-control servicepricefontsize" value="<?php echo intval( $n ); ?>px" <?php echo esc_attr( $select_ser ); ?>><?php echo intval( $n ); ?>px</option>
							<?php
                        }
?>
					</select>
				</div>
				<div style="margin-top:20px;" class="col-md-12 clearfix use-premium-tooltip">
					<label class="scc_label col-xs-8 col-md-6">Title Font Type</label>
					<select id="titlescc_fonttype" style="box-shadow: 1px 1px 1px #999; border: 0 none;" class="col-md-6 col-xs-4" disabled>
						<?php
$allfonts  = json_decode( $scc_googlefonts_var->gf_get_local_fonts() );
$fontIndex = 0;

foreach ( $allfonts->items as $allfont ) {
    ?>
							<option <?php selected( $f1->titleFontType, $fontIndex ); ?> value="<?php echo intval( $fontIndex ); ?>"><?php echo esc_attr( $allfont->family ); ?></option>
							<?php
    $fontIndex++;
}
?>
					</select>
				</div>
				<div  style="margin-top:20px;" class="col-md-12 clearfix use-premium-tooltip">
					<label class="scc_label col-xs-8 col-md-6">Title Font Weight 
					<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="title-font-weight-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
					</label>
					<select id="titlescc_fonttype_variant" class="col-xs-4 col-md-6" style="box-shadow: 1px 1px 1px #999; border: 0 none;" disabled>
					  <?php
$allfonts  = json_decode( $scc_googlefonts_var->gf_get_local_fonts() );
$fontIndex = 0;

foreach ( $allfonts->items as $allfont ) {
    ?>
							<option <?php selected( $f1->titleFontType, $fontIndex ); ?> value="<?php echo intval( $fontIndex ); ?>"> <?php echo esc_attr( $allfont->family ); ?></option>
							<?php
    $fontIndex++;
}
?>
					</select>
				</div>
				<div style="margin-top:20px;margin-bottom:20px;" class="col-md-12 clearfix use-premium-tooltip">
					<label class="scc_label col-xs-8 col-md-6" style="margin-bottom:5px;margin-bottom:5px;">Font Color</label>
					<div class="wp-picker-container use-premium-tooltip"><button type="button" class="button wp-color-result " aria-expanded="false" style="background-color: rgb(0, 0, 0);" disabled="disabled" ><span class="wp-color-result-text">Select Color</span></button><span class="wp-picker-input-wrap hidden"><label><span class="screen-reader-text">Color value</span><input type="text" class="color-picker col-md-4 wp-color-picker" id="titlecolorPicker" value="#000"></label><input type="button" class="button button-small wp-picker-clear tooltipadmin-top" value="Clear" aria-label="Clear color" disabled="disabled"></span><div class="wp-picker-holder"><div class="iris-picker iris-border" style="display: none; width: 255px; height: 202.125px; padding-bottom: 23.2209px;"><div class="iris-picker-inner"><div class="iris-square" style="width: 182.125px; height: 182.125px;"><a class="iris-square-value ui-draggable ui-draggable-handle" href="#" style="left: 0px; top: 182.125px;"><span class="iris-square-handle ui-slider-handle"></span></a><div class="iris-square-inner iris-square-horiz" style="background-image: -webkit-linear-gradient(left, rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255));"></div><div class="iris-square-inner iris-square-vert" style="background-image: -webkit-linear-gradient(top, rgba(0, 0, 0, 0), rgb(0, 0, 0));"></div></div><div class="iris-slider iris-strip" style="height: 205.346px; width: 28.2px; background-image: -webkit-linear-gradient(top, rgb(0, 0, 0), rgb(0, 0, 0));"><div class="iris-slider-offset ui-slider ui-corner-all ui-slider-vertical ui-widget ui-widget-content"><span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default" style="bottom: 0%;"></span></div></div></div><div class="iris-palette-container"><a class="iris-palette" tabindex="0" style="background-color: rgb(0, 0, 0); height: 19.5784px; width: 19.5784px; margin-left: 0px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(255, 255, 255); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(221, 51, 51); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(221, 153, 51); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(238, 238, 34); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(129, 215, 66); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(30, 115, 190); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(130, 36, 227); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a></div></div></div></div>
				</div>
			</div>
			<!--- END OF TITLE FONT STTINGS --->
			<!-- OLD SERVICE FONT --->
			<div class="col-12 col-md-3 col-lg-3 addedFieldsStyle font-settings-container scc-element-font-settings" style="min-height: 270px;margin-top:30px;margin-right:20px;box-shadow: 0px 0px 8px 1px rgba(163,163,163,0.27);">
				<div class="font-settings-title-head">
					Element Font Settings <i class="material-icons-outlined more-settings-info text-white" title="Choose the font settings for the element titles in your calculator form. For example, the title of your dropdown menu." style="margin-right:5px">help_outline</i>
				</div><br>
				<div style="margin-top:20px;" class="col-md-12 clearfix use-premium-tooltip">
					<label class="scc_label col-xs-8 col-md-6">Font Size</label>
					<select name="servicepricefontsize" id="servicepricefontsize" style="box-shadow: 1px 1px 1px #999; border: 0 none;" class="col-md-6 col-xs-4" disabled>
						<option class="form-control servicepricefontsize" value="0">Size</option>
						<?php
for ( $n = 8; $n <= 40; $n++ ) {
    if ( $n . 'px' == $f1->ServicefontSize ) {
        $select_ser = 'selected=selected';
    } else {
        $select_ser = '';
    }
    ?>
							<option class="form-control servicepricefontsize" value="<?php echo intval( $n ); ?>px" <?php echo esc_attr( $select_ser ); ?>><?php echo intval( $n ); ?>px</option>
							<?php
}
?>
					</select>
				</div>
				<div style="margin-top:20px;" class="col-md-12 clearfix use-premium-tooltip">
					<label class="scc_label col-xs-8 col-md-6">Font Type</label>
					<?php
                    $allfonts = json_decode( $scc_googlefonts_var->gf_get_local_fonts() );
?>
					<select id='scc_fonttype' class="col-xs-4 col-md-6" style="box-shadow: 1px 1px 1px #999; border: 0 none;" disabled>
						<?php
    $fontIndex = 0;

foreach ( $allfonts->items as $allfont ) {
    $selected = ( $fontIndex == $f1->fontType ) ? 'selected' : '';
    ?>
							<option <?php echo esc_html( $selected ); ?> value="<?php echo esc_html( $fontIndex ); ?>"><?php echo esc_html( $allfont->family ); ?></option>
							<?php
    $fontIndex++;
}
?>
					</select>
				</div>
				<div style="margin-top:20px;" class="col-md-12 clearfix use-premium-tooltip">
					<label class="scc_label col-xs-8 col-md-6">Font Weight 
					<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="font-weight-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
					</label>
					<select id='scc_fonttype_variant' class="col-xs-4 col-md-6" style="box-shadow: 1px 1px 1px #999; border: 0 none;" disabled>
					</select>
				</div>
				<div style="margin-top:20px;margin-bottom:20px;" class="col-md-12 clearfix use-premium-tooltip">
					<label class="scc_label col-xs-8 col-md-6">Font Color</label>
					<div class="wp-picker-container use-premium-tooltip"><button type="button" class="button wp-color-result " aria-expanded="false" style="background-color: rgb(0, 0, 0);" disabled="disabled" ><span class="wp-color-result-text">Select Color</span></button><span class="wp-picker-input-wrap hidden"><label><span class="screen-reader-text">Color value</span><input type="text" class="color-picker col-md-4 wp-color-picker" id="titlecolorPicker" value="#000"></label><input type="button" class="button button-small wp-picker-clear tooltipadmin-top" value="Clear" aria-label="Clear color" disabled="disabled"></span><div class="wp-picker-holder"><div class="iris-picker iris-border" style="display: none; width: 255px; height: 202.125px; padding-bottom: 23.2209px;"><div class="iris-picker-inner"><div class="iris-square" style="width: 182.125px; height: 182.125px;"><a class="iris-square-value ui-draggable ui-draggable-handle" href="#" style="left: 0px; top: 182.125px;"><span class="iris-square-handle ui-slider-handle"></span></a><div class="iris-square-inner iris-square-horiz" style="background-image: -webkit-linear-gradient(left, rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255));"></div><div class="iris-square-inner iris-square-vert" style="background-image: -webkit-linear-gradient(top, rgba(0, 0, 0, 0), rgb(0, 0, 0));"></div></div><div class="iris-slider iris-strip" style="height: 205.346px; width: 28.2px; background-image: -webkit-linear-gradient(top, rgb(0, 0, 0), rgb(0, 0, 0));"><div class="iris-slider-offset ui-slider ui-corner-all ui-slider-vertical ui-widget ui-widget-content"><span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default" style="bottom: 0%;"></span></div></div></div><div class="iris-palette-container"><a class="iris-palette" tabindex="0" style="background-color: rgb(0, 0, 0); height: 19.5784px; width: 19.5784px; margin-left: 0px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(255, 255, 255); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(221, 51, 51); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(221, 153, 51); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(238, 238, 34); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(129, 215, 66); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(30, 115, 190); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(130, 36, 227); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a></div></div></div></div>
				</div>
			</div>
			<!-- END OF SERVICE FONT SETTING -->
			<div class="col-12 col-md-3 col-lg-3 addedFieldsStyle font-settings-container scc-object-settings" style="min-height: 270px;margin-top:30px;box-shadow: 0px 0px 8px 1px rgba(163,163,163,0.27);">
				<div class="font-settings-title-head">
					Object Settings <i class="material-icons-outlined more-settings-info text-white" title="Choose the color for the objects in your calculator form. For example, the total bar style, slider handle bar, drodpown menu border, etc." style="margin-right:5px">help_outline</i>
				</div><br>
				<div style="margin-top:20px;" class="col-md-12 clearfix use-premium-tooltip">
					<label class="scc_label col-xs-8 col-md-6">Object Size</label>
					<select name="servicepricefontsize" id="objectservicepricefontsize" class="col-xs-4 col-md-6" style="box-shadow: 1px 1px 1px #999; border: 0 none;" disabled>
						<option class="form-control servicepricefontsize" value="0">Size</option>
						<?php
for ( $n = 1; $n <= 100; $n++ ) {
    if ( $n . 'px' == $f1->objectSize ) {
        $select_ser = 'selected';
    } else {
        $select_ser = '';
    }
    ?>
							<option class="form-control servicepricefontsize" value="<?php echo intval( $n ); ?>px" <?php echo esc_attr( $select_ser ); ?>><?php echo intval( $n ); ?>px</option>
							<?php
}
?>
					</select>
				</div>
				<div style="margin-top:20px;margin-bottom:20px;" class="col-md-12 clearfix use-premium-tooltip">
					<label class="scc_label col-xs-8 col-md-6">Object Color</label>
					<div class="wp-picker-container use-premium-tooltip"><button type="button" class="button wp-color-result " aria-expanded="false" style="background-color: rgb(0, 0, 0);" disabled="disabled" ><span class="wp-color-result-text">Select Color</span></button><span class="wp-picker-input-wrap hidden"><label><span class="screen-reader-text">Color value</span><input type="text" class="color-picker col-md-4 wp-color-picker" id="titlecolorPicker" value="#000"></label><input type="button" class="button button-small wp-picker-clear tooltipadmin-top" value="Clear" aria-label="Clear color" disabled="disabled"></span><div class="wp-picker-holder"><div class="iris-picker iris-border" style="display: none; width: 255px; height: 202.125px; padding-bottom: 23.2209px;"><div class="iris-picker-inner"><div class="iris-square" style="width: 182.125px; height: 182.125px;"><a class="iris-square-value ui-draggable ui-draggable-handle" href="#" style="left: 0px; top: 182.125px;"><span class="iris-square-handle ui-slider-handle"></span></a><div class="iris-square-inner iris-square-horiz" style="background-image: -webkit-linear-gradient(left, rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255));"></div><div class="iris-square-inner iris-square-vert" style="background-image: -webkit-linear-gradient(top, rgba(0, 0, 0, 0), rgb(0, 0, 0));"></div></div><div class="iris-slider iris-strip" style="height: 205.346px; width: 28.2px; background-image: -webkit-linear-gradient(top, rgb(0, 0, 0), rgb(0, 0, 0));"><div class="iris-slider-offset ui-slider ui-corner-all ui-slider-vertical ui-widget ui-widget-content"><span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default" style="bottom: 0%;"></span></div></div></div><div class="iris-palette-container"><a class="iris-palette" tabindex="0" style="background-color: rgb(0, 0, 0); height: 19.5784px; width: 19.5784px; margin-left: 0px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(255, 255, 255); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(221, 51, 51); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(221, 153, 51); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(238, 238, 34); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(129, 215, 66); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(30, 115, 190); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(130, 36, 227); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a></div></div></div></div>
				</div>
			</div>
		</div>
	</div>
	</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary settings-modal-save-btn" onclick="saveDataFields()">
			<span class="btn-text">Save changes</span>
			<span class="btn-text-alt">Loading</span>
			<i class="fas fa-spinner btn-spinner fa-spin"></i>
		</button>
      </div>
    </div>
  </div>
</div><!-- END OF MODAL -->
<div class="modal fade" id="settingsModal1" tabindex="-1" aria-labelledby="settingsModal1Label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable scc-modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="settingsModal1Label">Calculator Settings</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

	<!-- IMPLEMENTAR FONTS AND COLOR PICKER -->
	<div id="dfscc_tab_calculator_">
		<div class="row my-3 ms-2">
			<div class="col-md-6 scc-calculator-settings-search">
				<div class="content-search-settings form-group px-0">
					<input style="padding: 20px 10px!important;" class="form-control ui-autocomplete-input" id="ssc-search-term" type="text" placeholder="Search in settings" autocomplete="off">
				</div>
			</div>
		</div>
		<div class="row" id="calc-settings-items">
			<div class="col-md-6" style="padding-left:30px;padding-bottom:20px;">
				<!--Start Left Section-->
				<div class="row" style="display: block;">
					<div class="clearfix more-settings-section col-md-12 scc-frontend-options-section">
						<!--Start Blue Section - FrontEnd-->
						<div style="font-size:140%;margin-bottom: 30px;" class="sccsubtitle scc_email_quote_label">
							<span style="font-weight:800;">FRONTEND</span> OPTIONS
						</div>
						<div class="row col-md-12">
							<!--START Of Entire Form Fields Section--->
							<div class="row col-xs-12 col-md-12 scc-form-field-element-styles scc-setting-subsection">
								<span class="scc-calc-settings-title">Form Fields &amp; Elements Style</span>
								<hr class="scc-calc-settings-hr">
								<div class="scc-vcenter">
									<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
										<label id="label_element_style" class="scc-calc-settings-lbl">Elements Style Skin
											<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="element-style-skin-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
										</label>
									</div>
									<div class="col-xs-6 col-md-6" style="padding:0;">
										<select onchange="changeFieldStyle(this)" name="form_field_style" class="form-control" style="height:40px;">
											<option class="form-control" value="style_1" <?php echo ( $f1->elementSkin == 'style_1' ) ? 'selected' : ''; ?>>Style 1 - Inline</option>
											<option class="form-control" value="style_2" <?php echo ( $f1->elementSkin == 'style_2' ) ? 'selected' : ''; ?>>Style 2 - Block</option>
										</select>
									</div>
								</div>
								<div class="col-md-12 scc-vcenter" 
								<?php
        if ( $f1->elementSkin == 'style_1' ) {
            echo 'style="display: none; margin-top: 10px;"';
        }
?>
								>
									<div class="col-xs-5 col-md-5" style="padding-left:0;margin-left: 10px;">
										<label id="label_add_container" class="scc-calc-settings-lbl">Add container <i class="material-icons-outlined more-settings-info" title="Add a container around each form field (element) that has a different backgound color. This helps visually separate each element and might be a better UX." style="margin-right:5px">help_outline</i></label>
									</div>
									<div class="col-xs-6 col-md-6" style="padding:0;">
										<!--- Style2 Box Shaddow Toggle Option -->
										<div id="toggle_boxshadow_style2" style="padding-top:1px;">
											<div class="switch-container">
												<label class="scc-switch">
													<div class="scc-switch">
														<input type="checkbox" name="toggle_boxshadow_style2" placeholder="" value="toggle_boxshadow_style2" id="toggle_boxshadow_style2" class="form-control" 
														<?php
                        if ( $f1->addContainer == 'true' ) {
                            echo 'checked';
                        }
?>
														>
														<span class="slider round"></span>
													</div>
												</label>
											</div>
										</div>
									</div><!-- end of Style2 Box Shaddow Toggle Option  -->
								</div>

								<!-- start of calculator max width settings -->
								<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
									<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
										<label id="label_element_style" class="scc-calc-settings-lbl">Calculator Form - Container Max Width
										<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="calculator-max-with-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
										</label>
									</div>
									<div class="col-xs-6 col-md-6">
										<input type="number" min="0" name="scc_wrapper_max_width" id="scc_wrapper_max_width" class="form-control" value="<?php echo esc_attr( $f1->wrapper_max_width ); ?>" style="max-width:100px;min-height: 40px;padding-right: 5px;border:2px solid #F0F0F0;" />
									</div>
								</div>
								<!-- end of calculator max width settings -->

							</div>
							<!--End Of Entire Form Fields Section--->
							<!-- Start Woocommerce add to cart behaviour -->
							<div class="row col-xs-12 col-md-12 scc-woocommerce-settings scc-setting-subsection">
								<span class="scc-calc-settings-title">WooCommerce Settings</span>
								<hr class="scc-calc-settings-hr">
								<div class="col-xs-12 col-md-12" style="margin-top: 10px;">
									<div class="col-xs-12 col-md-12 scc-vcenter">
										<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
											<label id="label_addtocart" class="scc-calc-settings-lbl">Add-To-Cart Redirection
											<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="add-to-cart-redirection-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
											</label>
										</div>
										<div class="col-xs-6 col-md-6" style="padding:0">
											<select name="scc_wc_cart_btn_action" class="form-control" style="height:40px; margin-bottom: 0;">
												<option class="form-control" value="open_checkout" 
												<?php
                                                if ( $f1->addtoCheckout == 'open_checkout' ) {
                                                    echo 'selected';
                                                }
?>
												>Open Checkout Page</option>
												<option class="form-control" value="open_cart" 
												<?php
if ( $f1->addtoCheckout == 'open_cart' ) {
    echo 'selected';
}
?>
												>Open Cart Page</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<!-- End Woocommerce add to cart behaviour -->
							<!-- START User Action BIG SECTION -->
							<div class="row col-md-12 scc-cal-settings-row scc-setting-subsection scc-cta-button-calc-settings">
								<span class="scc-calc-settings-title">User Action Buttons</span>
								<hr class="scc-calc-settings-hr">
								<!-- Start User Action Button Style -->
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 scc-vcenter" style="margin-top:10px;">
									<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5" style="cursor:pointer">
										<label id="label_buton_style" class="scc-calc-settings-lbl">Button Style</label>
										<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="button-style-visibility-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
									</div>
									<div class="col-xs-6 col-md-6" style="padding:0">
										<select name="scc_user_action_btn_style" class="form-control" style="height:40px;">
											<option class="form-control" value="1" 
											<?php
                                            if ( $f1->buttonStyle == '1' ) {
                                                echo 'selected';
                                            }
?>
											>Style 1 - Standard Rounded</option>
											<option class="form-control" value="2" 
											<?php
if ( $f1->buttonStyle == '2' ) {
    echo 'selected';
}
?>
											>Style 2 - Animated</option>
											<option class="form-control" disabled>Style 3 - Liquid Effect</option>
											<option class="form-control" disabled>Style 4 - Animated Borders</option>
										</select>
									</div>
								</div>
								<!-- End User Action Button Style -->
								<!-- Start of Button styles -->
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 scc-vcenter" style=" display: none;">
									<select name="scc_btn_style" class="form-control scc_btn_style" style="font-size: 13px!important;height:40px;">
										<option class="form-control" value="0" selected="">Default</option>
										<option class="form-control" value="1">With Hover Animation Effect</option>
									</select>
								</div><!-- Start of Button styles -->
								<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
									<!-- Start Payment Button Hover Effect Toggle Button -->
									<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
										<label id="label_turnoffborder" class="scc-calc-settings-lbl">Turn off <b>Border</b> for Pay Btns
											<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="turn-off-border-pay-buttons-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
										</label>
									</div>
									<div class="col-xs-6 col-md-6">
										<label class="scc-switch">
											<div class="scc-switch">
												<input type="checkbox" name="paybuttonhovereffect" id="paybuttonhovereffect" class="form-control" 
												<?php
    if ( $f1->turnoffborder == 'true' ) {
        echo 'checked';
    }
?>
												>
												<span class="slider round"></span>
											</div>
										</label>
									</div>
								</div><!-- End Payment Button Hover Effect Toggle Button -->
								<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
									<!-- Start Send Quote Button -->
									<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
										<label id="label_turnoffemai" class="scc-calc-settings-lbl">Show <b>Email Quote</b> button 
										<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="show-email-quote-button-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
									</div>
									<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
										<label class="scc-switch">
											<div class="scc-switch">
												<input type="checkbox" name="scc_send_quote" placeholder="" value="turn_off_send_quote" id="scc_send_quote" class="form-control scc_send_quote" 
												<?php
if ( $f1->turnoffemailquote !== 'true' ) {
    echo 'checked';
}
?>
												>
												<span class="slider round"></span>
											</div>
										</label>
									</div>
								</div><!-- End of Send Quote Button -->
								<!-- Turn off View Detailed List button -->
								<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
									<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
										<label id="label_turnoffview" class="scc-calc-settings-lbl">Show <b>View Detailed List</b> button 
											  <i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="show-view-detailed-list-button-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
									</div>
									<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
										<label class="scc-switch">
											<div class="scc-switch">
												<input type="checkbox" name="scc_detailed_list" placeholder="" value="turn_off_viewed_detailed_list" id="scc_detailed_list" class="form-control scc_detailed_list" 
												<?php
if ( $f1->turnviewdetails !== 'true' ) {
    echo 'checked';
}
?>
												>
												<span class="slider round"></span>
											</div>
										</label>
									</div>
								</div><!-- End Turn off View Detailed List button -->
								<!-- Start Turn Off Coupon Discount -->
								<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
									<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
										<label id="label_turnoffcupon" class="scc-calc-settings-lbl">Show <b>Coupon</b> button 
										<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="show-coupon-button-info-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
									</div>
									<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
										<label class="scc-switch">
											<div class="scc-switch">
												<input type="checkbox" name="turn_off_coupon" placeholder="" value="turn_off_coupon" id="turn_off_coupon" class="form-control" 
												<?php
if ( $f1->turnoffcoupon !== 'true' ) {
    echo 'checked';
}
?>
												>
												<span class="slider round"></span>
											</div>
										</label>
									</div>
								</div><!-- End of Turn Off Coupon Display -->
							</div>
							<!-- End of USER ACTIONS section close -->
							<!-- START Total Price SECTION -->
							<div class="row col-md-12 scc-cal-settings-row scc-total-price-settings scc-setting-subsection">
								<span class="scc-calc-settings-title">Total Price Settings</span>
								<hr class="scc-calc-settings-hr">
								<!-- Start SCC Total Price Style View -->
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 scc-vcenter" style="margin-top:10px;">
									<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
										<label id="label_barstyle" class="scc-calc-settings-lbl">Bar Style 
											<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="bar-style-visibility-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
										</label>
									</div>
									<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding:0">
										<select name="scc_total_price_style_view" class="form-control scc_total_price_style_view" style="height:40px;">
											<option class="form-control" value="0">Choose a style</option>
											<option class="form-control" value="scc_tp_style1" 
											<?php
                                            if ( $f1->barstyle == 'scc_tp_style1' ) {
                                                echo 'selected';
                                            }

if ( $f1->barstyle !== 'scc_tp_style1' && $f1->barstyle !== 'scc_tp_style2' &&
                                            $f1->barstyle !== 'scc_tp_style3' && $f1->barstyle !== 'scc_tp_style4' ) {
    echo 'selected';
}
?>
											>Style 1 | Total Price</option>
											<option class="form-control" value="scc_tp_style2" 
											<?php
                                            if ( $f1->barstyle == 'scc_tp_style2' ) {
                                                echo 'selected';
                                            }
?>
											>Style 2 | Total Price</option>
											<option class="form-control" value="scc_tp_style3" 
											<?php
if ( $f1->barstyle == 'scc_tp_style3' ) {
    echo 'selected';
}
?>
											>Style 3 | Total Price</option>
											<option class="form-control" value="scc_tp_style4" 
											<?php
if ( $f1->barstyle == 'scc_tp_style4' ) {
    echo 'selected';
}
?>
											>Style 4 | Total Price</option>
										</select>
									</div>
								</div><!-- End SCC Total Price Style View -->
								<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
									<!--Start Turn on total bar price float -->
									<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
										<label id="label_turnofffloating" class="scc-calc-settings-lbl">Show Floating for Total Bar (Style 1 only) 
										<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="show-floating-total-bar-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
										</label>
									</div>
									<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
										<label class="scc-switch">
											<div class="scc-switch">
												<input type="checkbox" name="turn_on_total_price_float" placeholder="" value="turn_on_total_price_float" id="turn_on_total_price_float" class="form-control" 
												<?php
    if ( $f1->turnofffloating == 'true' ) {
        echo 'checked';
    }
?>
												>
												<span class="slider round"></span>
											</div>
										</label>
									</div>
								</div><!-- End  Turn on total bar price float -->
								<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
									<!-- Start Total Price Toggle Button -->
									<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
										<label id="label_removetotalprice" class="scc-calc-settings-lbl">Remove the <b>Total Price</b> 
											<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="remove-total-price-info-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
									</label>
									</div>
									<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
										<label class="scc-switch">
											<div class="scc-switch">
												<input type="checkbox" name="scc_remove_total_price_frntd" placeholder="" value="scc_turn_off_total_price_view" id="scc_remove_total_price_frntd" class="form-control" 
												<?php
if ( $f1->removeTotal == 'true' ) {
    echo 'checked';
}
?>
												>
												<span class="slider round"></span>
											</div>
										</label>
									</div>
								</div><!-- End Total Price Toggle Button -->
								<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
									<!-- Start Total Price Conditional Revelation -->
									<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
										<label id="label_showtotalpricev" class="scc-calc-settings-lbl">Minimum Total Price 
										<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="minimum-total-price-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
										</label>
									</div>
									<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
										<div style="padding:0" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
											<input type="number" min="0" name="scc_minimum_total_price" id="scc_minimum-total" oninput="javascript:(jQuery(this).val() > 0) ? jQuery('#scc_minimum-total-action').show() : jQuery('#scc_minimum-total-action').hide()" class="form-control has-related-field" value="<?php echo esc_attr( $f1->minimumTotal ); ?>" style="max-width:70px;min-height: 40px;padding-right: 5px;">
										</div>
									</div>
								</div><!-- End Total Price Conditional Revelation -->
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 scc-vcenter" id="scc_minimum-total-action" <?php echo ( $f1->minimumTotal == '' || $f1->minimumTotal <= 0 ) ? 'style="padding:0;display: none"' : 'style="padding:0;"'; ?>>
									<!-- Start Total Price Conditional Revelation Placeholder -->
									<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
										<label class="scc-calc-settings-lbl">Minimum Total - Choose what happens<i class="material-icons-outlined more-settings-info" title="Choose what happens if the minimum total is not satisfied.">help_outline</i></label>
									</div>
									<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding:0">
										<select name="scc_minimum_total_placeholder" id="scc_minimum_total_placeholder" class="form-control" style="height:40px;">
											<option class="form-control" value="show-notice" 
											<?php
                                            if ( $f1->minimumTotalChoose == 'show-notice' ) {
                                                echo 'selected';
                                            }
?>
											>Show a notice</option>
											<option class="form-control" value="silent-hide" 
											<?php
if ( $f1->minimumTotalChoose == 'silent-hide' ) {
    echo 'selected';
}
?>
											>Hide the total price and user-action-buttons</option>
										</select>
									</div>
								</div><!-- End Total Price Conditional Revelation Placeholder -->
								<!-- Start Total Price Range Settings -->
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 scc-vcenter" id="scc_price_range_total">
									<!-- Start Total Price Conditional Revelation Placeholder -->
									<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
										<label class="scc-calc-settings-lbl">Total Price Range
											<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="total-price-range-settings-tt" style="margin-right:5px">help_outline</i>
										</label>
									</div>
									<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
										<div style="padding:0" class="col-xs-6 col-sm-6 col-md-6 col-lg-6 use-premium-tooltip">
											<input disabled type="number" min="0" name="total_price_range_settings_percent" id="total_price_range_settings_percent" class="form-control has-related-field" value="0" style="max-width:70px;min-height: 40px;padding-right: 5px; border:2px solid #F0F0F0;">
										</div>
									</div>
								</div><!-- End Total Price Range Settings -->
							</div><!-- End Total Price SECTION -->
							<div class="row col-md-12 scc-cal-settings-row scc-detailed-list-pdf-settings" style="margin-left:1px;">
								<!-- Start Detailed List Section-->
								<span class="scc-calc-settings-title">Detailed List Settings</span>
								<hr class="scc-calc-settings-hr">
								<!-- Start Detailed List Title Toggle Button -->
								<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
									<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
										<label id="labe_removetitle" class="scc-calc-settings-lbl">Show <b>Title</b> 
										<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="show-title-info-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
									</label>
									</div>
									<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
										<label class="scc-switch">
											<div class="scc-switch">
												<input type="checkbox" name="scc_remove_detailed_list_title" placeholder="" value="scc_remove_detailed_list_title" id="scc_remove_detailed_list_title" class="form-control" 
												<?php
    if ( $f1->removeTitle !== 'true' ) {
        echo 'checked';
    }
?>
												>
												<span class="slider round"></span>
											</div>
										</label>
									</div>
								</div><!-- End Detailed List Title Toggle Button -->
								<!--turn off unit price column -->
								<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
									<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
										<label id="label_turnoffprice" class="scc-calc-settings-lbl">Show <b>Unit Price</b> Column 
											<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="show-unit-price-column-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
										</label>
									</div>
									<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
										<label class="scc-switch">
											<div class="scc-switch">
												<input type="checkbox" value="turn_off_save_icon" name="scc_no_unit_col" id="scc_no_unit_col" class="form-control" 
												<?php
if ( $f1->turnoffUnit !== 'true' ) {
    echo 'checked';
}
?>
												>
												<span class="slider round"></span>
											</div>
										</label>
									</div>
								</div><!-- end of turn off unit price column -->
								<!--turn off qty column -->
								<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
								<div class="col-xs-12 col-sm-6 col-md-5 col-lg-5">
									<label id="label_turnoffqty" class="scc-calc-settings-lbl">Show <b>Quantity</b> Column 
										<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="show-quantity-column-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
									</label>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
									<label class="scc-switch">
										<div class="scc-switch">
											<input type="checkbox" value="turn_off_save_icon" name="scc_no_qty_col" id="scc_no_qty_col" class="form-control" 
											<?php
                                            if ( $f1->turnoffQty !== 'true' ) {
                                                echo 'checked';
                                            }
?>
											>
											<span class="slider round"></span>
										</div>
									</label>
								</div>
							</div><!-- end of turn off qty column -->
								<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
									<!--turn off save icon -->
									<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
										<label id="label_turnoffsave" class="scc-calc-settings-lbl">Show <b>Save</b> Icon 
											<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="show-save-icon-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
										</label>
									</div>
									<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
										<label class="scc-switch">
											<div class="scc-switch">
												<input type="checkbox" name="scc_save_icon" placeholder="" value="turn_off_save_icon" id="scc_save_icon" class="form-control scc_save_icon" 
												<?php
    if ( $f1->turnoffSave !== 'true' ) {
        echo 'checked';
    }
?>
												>
												<span class="slider round"></span>
											</div>
										</label>
									</div>
								</div><!-- end of turn off save icon -->
								<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
									<!-- Start Turn Off Tax Display -->
									<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
										<label id="label_turnofftax" class="scc-calc-settings-lbl">Show <b>Tax</b> Display 
											<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="show-tax-display-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
										</label>
									</div>
									<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
										<label class="scc-switch">
											<div class="scc-switch">
												<input type="checkbox" name="turn_off_tax" placeholder="" value="turn_off_tax" id="turn_off_tax" class="form-control" 
												<?php
if ( $f1->turnoffTax !== 'true' ) {
    echo 'checked';
}
?>
												>
												<span class="slider round"></span>
											</div>
										</label>
									</div>
								</div><!-- End of Turn Off Tax Display -->
								<!-- Start of invoice ID toggle -->
								<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
								<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
									<label for="show_invoice_number" class="scc-calc-settings-lbl">Show <b>Invoice Number</b>
										<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="show-invoice-number-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
									</label>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
									<label class="scc-switch">
										<div class="scc-switch use-premium-tooltip">
											<input type="checkbox" name="show_invoice_number" id="show_invoice_number" onchange="javascript:sccBackendUtils.handleSettingsToggleCheckbox(this)" class="form-control">
											<span class="slider round"></span>
										</div>
									</label>
								</div>
							</div><!-- End of invoice ID toggle -->
							<!-- Start of invoice start number -->
							<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
								<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
									<label for="invoice_starting_number" class="scc-calc-settings-lbl"><b>Invoice Number</b> Starting Number 
										<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="invoice-number-starting-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
									</label>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
									<div style="padding:0" class="col-xs-6 col-sm-6 col-md-6 col-lg-6 use-premium-tooltip">
										<input type="number" disabled="" name="invoice_starting_number" id="invoice_starting_number" class="form-control" style="max-width:70px;min-height: 40px;padding-right: 5px;border:2px solid #F0F0F0;">
									</div>
								</div>
							</div><!-- End of invoice start number -->
								<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
									<!--Start of Any Global Settings Button-->
									<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
										<label id="label_footern" class="scc-calc-settings-lbl">Footer Notes 
											<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="footer-notes-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
										</label>
									</div>
									<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
										<a href="/wp-admin/admin.php?page=scc-global-settings" target="_blank" class="scc-calc-settings-btn">In Global Settings</a>
									</div>
								</div>
								<!--End of Any Global Settings Button-->
							</div>
							<!--End of Detailed List Section-->
							<div class="row col-md-12 scc-cal-settings-row scc-search-box-settings scc-setting-subsection">
								<span class="scc-calc-settings-title">Search Box Settings</span>
								<hr class="scc-calc-settings-hr">
								<!-- Start Search Box settings -->
								<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
									<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
										<label class="scc-calc-settings-lbl">Show <b>Search Box</b> 
											<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="show-search-box-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
										</label>
									</div>
									<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
										<label class="scc-switch use-premium-tooltip">
											<div class="scc-switch">
												<input disabled type="checkbox" name="scc_show_searchbar" class="form-control">
												<span class="slider round"></span>
											</div>
										</label>
									</div>
								</div>
								<!-- End Search Box settings -->
							</div>
						</div>
						<!--End Of Inside Div-->
					</div> <!-- blue section close -->
				</div>
			</div>
			<div class="col-md-6" style="padding-left:15px;padding-bottom:20px;">
				<!-- Right Section -->
				<div class="clearfix more-settings-section col-md-12 scc-currency-tax-settings-section">
					<!-- Start Blue Section - Currency & Tax-->
					<div style="font-size:140%;" class="sccsubtitle scc_email_quote_label">
						<span style="font-weight:800;">CURRENCY &amp; TAX</span> SETTINGS
					</div>
					<br>
					<!-- Start TAX Percentage Input -->
					<div class="row col-md-12 scc-cal-settings-row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-left: 10px;">
							<span class="scc-calc-settings-title">Tax / VAT</span>
							<hr class="scc-calc-settings-hr">
							<div class="scc-vcenter" style="margin-left: 10px;">
								<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
									<label id="label_taxvat" class="scc-calc-settings-lbl">Tax/VAT</label>
									<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="tax-vat-info-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
								</div>
								<div style="padding:0;display:flex;" class="">
									<input type="number" min="1" max="99" name="scc_tax_amount" value="<?php echo intval( $f1->taxVat ); ?>" id="scc_tax_amount" class="form-control" style="max-width:70px;min-height: 40px;padding-right: 5px;">
									<span style="color:#b7c1fb;font-size:20px;font-weight:bold;display:inline-block;padding-left:5px;padding-top:5px;">%</span>
								</div>
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 scc-vcenter" style="margin-left:10px">
							<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5" style="margin-left:10px">
								<label id="label_show_taxvat" class="scc-calc-settings-lbl">Show Tax/VAT before Total</label>
								<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="show-tax-vat-before-total-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
							</div>
							<div style="padding:5px 0 0 0;display:flex;" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
								<label class="scc-switch">
									<div class="scc-switch">
										<input type="checkbox" name="scc_show_taxvat" name="scc_show_taxvat" placeholder="" value="scc_show_taxvat" id="scc_show_taxvat" class="form-control" 
										<?php
                                        if ( $f1->showTaxBeforeTotal == 'true' ) {
                                            echo 'checked';
                                        }
?>
										>
										<span class="slider round"></span>
									</div>
								</label>
							</div>
						</div>
					</div>
					<!-- END TAX Percentage Input -->
					<div class="row col-md-12 scc-cal-settings-row">
						<!-- Start of END Currency Symbol or Letters -->
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-left: 10px;">
							<span class="scc-calc-settings-title">Currency</span>
							<hr class="scc-calc-settings-hr">
							<!-- Start of Currency Symbol or Letters -->
							<div class="scc-vcenter" style="margin-left: 10px;">
								<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
									<label id="label_symbolpl" class="scc-calc-settings-lbl"> Symbol Placement Style 
									<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="symbol-placement-style-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
									</label>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding:0">
									<select name="scc_currency_style" class="form-control scc_currency_style" style="font-size: 13px!important;height:40px;">
										<option class="form-control" value="0" 
										<?php
if ( $f1->symbol == '0' ) {
    echo 'selected';
}
?>
										>Currency Symbol (Example: $53)</option>
										<option class="form-control" value="1" 
										<?php
if ( $f1->symbol == '1' ) {
    echo 'selected';
}
?>
										>Currency Letters (Example: 53 CAD)</option>
									</select>
								</div>
							</div>
							<!-- End of Currency Symbol or Letters -->
							<!-- Start Currency label Toggle Button -->
							<div class="scc-vcenter" style="margin-left: 10px;">
								<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
									<label id="label_removecurla" class="scc-calc-settings-lbl">Show Currency label </label>
									<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="show-currency-label-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding: 0;">
									<label class="scc-switch">
										<div class="scc-switch">
											<input type="checkbox" name="scc_remove_currency_label" placeholder="" value="scc_remove_currency_label" id="scc_remove_currency_label" class="form-control" 
											<?php
    if ( $f1->removeCurrency !== 'true' ) {
        echo 'checked';
    }
?>
											>
											<span class="slider round"></span>
										</div>
									</label>
								</div>
							</div>
							<div class="scc-vcenter" style="margin-left: 10px;">
								<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
									<label id="label_removecurla" class="scc-calc-settings-lbl">Allow users to choose their currency</label>
									<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="allow-users-choose-currency-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding: 0;">
									<label class="scc-switch">
										<div class="scc-switch">
											<input type="checkbox" name="scc_frontend_allow_currency_switching" placeholder="" value="scc_frontend_allow_currency_switching" id="scc_frontend_allow_currency_switching" class="form-control" 
											<?php
if ( isset( $f1->allow_currency_switching ) && $f1->allow_currency_switching ) {
    echo 'checked';
}
?>
											>
											<span class="slider round"></span>
										</div>
									</label>
								</div>
							</div>
							<div class="scc-vcenter" style="margin-left: 10px;">
								<!--Start of Any Global Settings Button-->
								<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
									<label id="label_curselec" class="scc-calc-settings-lbl">Currency Selector ($,€,£) 
									<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="currency-selector-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
									</label>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding:0">
									<a href="/wp-admin/admin.php?page=scc-global-settings" target="_blank" class="scc-calc-settings-btn">In Global Settings</a>
								</div>
							</div>
							<!--End of Any Global Settings Button-->
							<!--Start of Any Global Settings Button-->
							<div class="scc-vcenter" style="margin-left: 10px;">
								<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
									<label id="label_autocurr" class="scc-calc-settings-lbl">Auto Currency Conversion
									<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="auto-currency-conversion-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
									</label>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding:0">
									<a href="/wp-admin/admin.php?page=scc-global-settings" target="_blank" class="scc-calc-settings-btn">In Global Settings</a>
								</div>
							</div>
							<!--End of Any Global Settings Button-->
							<!--Start of Any Global Settings Button-->
							<div class="scc-vcenter" style="margin-left: 10px;">
								<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
									<label id="label_currform" class="scc-calc-settings-lbl">Currency Format (dot/comma)
									<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="currency-format-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
									</label>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding:0">
									<a href="/wp-admin/admin.php?page=scc-global-settings" target="_blank" class="scc-calc-settings-btn">In Global Settings</a>
								</div>
							</div>
							<!--End of Any Global Settings Button-->
						</div>
					</div><!-- End Currency label Toggle Button 2 -->
				</div><!-- End Blue Section - Currency & Tax-->
				<div class="clearfix more-settings-section col-md-12 scc-pdf-settings-section">
					<!-- Start Blue Section - PDF Options -->
					<div style="font-size:140%;" class="sccsubtitle scc_email_quote_label">
						<span style="font-weight:800;">PDF</span> OPTIONS
					</div>
					<div class="row col-md-12 scc-cal-settings-row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-left:10px">
						<!--Start of Any Global Settings Button-->
						<div class="scc-vcenter" style="margin-left: 10px;">
						<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
							<label id="label_pdffont" class="scc-calc-settings-lbl">PDF Font Style 
							<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="pdf-font-style-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
							</label>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding:0">
							<a href="/wp-admin/admin.php?page=scc-global-settings#scc_pdf_settings" target="_blank" class="scc-calc-settings-btn">In Global Settings</a>
						</div>
						</div>
						<div class="scc-vcenter" style="margin-left: 10px;">
							<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
								<label id="label_pdfdate" class="scc-calc-settings-lbl">PDF Date Format 
								<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="pdf-date-format-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
								</label>
							</div>
							<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding:0">
								<a href="/wp-admin/admin.php?page=scc-global-settings#scc_pdf_settings" target="_blank" class="scc-calc-settings-btn">In Global Settings</a>
							</div>
						</div>
						</div>
					</div>
					<!--End of Any Global Settings Button-->
				</div><!-- End Blue Section - PDF Options -->
				<div class="clearfix more-settings-section col-md-12 scc-email-setting-section">
					<!-- Start Blue Section - Email Settings -->
					<div style="font-size:140%;" class="sccsubtitle scc_email_quote_label">
						<span style="font-weight:800;">EMAIL</span> SETTINGS
					</div>
					<div class="row col-md-12 scc-cal-settings-row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-left:10px">
							<div class="scc-vcenter" style="margin-left:10px">
								<!--Start of Any Global Settings Button-->
								<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
									<label id="label_emailsub" class="scc-calc-settings-lbl">Email Subject
									<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="email-subject-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
									</label>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding:0">
									<a href="/wp-admin/admin.php?page=scc-global-settings#scc_email_quote_settings" target="_blank" class="scc-calc-settings-btn">In Global Settings</a>
								</div>
							</div>
							<!--End of Any Global Settings Button-->
							<div class="scc-vcenter" style="margin-left:10px">
								<!--Start of Any Global Settings Button-->
								<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
									<label id="label_emailbody" class="scc-calc-settings-lbl">Email Body 
									<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="email-body-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
									</label>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding:0">
									<a href="/wp-admin/admin.php?page=scc-global-settings#scc_email_quote_settings" target="_blank" class="scc-calc-settings-btn">In Global Settings</a>
								</div>
							</div>
							<!--End of Any Global Settings Button-->
							<div class="scc-vcenter" style="margin-left:10px">
								<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
									<label id="label_email_quote_recipients" class="scc-calc-settings-lbl">Email Quote Recipient(s) 
									<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="email-quote-recipient-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
									</label>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding:0">
									<select name="email_quote_recipients" class="form-control" style="font-size: 13px!important;height:40px;">
										<option class="form-control" selected>Admin and User</option>
									</select>
								</div>
							</div>
							<!--turn off unit price column -->
							<div class="scc-vcenter" style="margin-left:10px">
								<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
									<label id="label_email_quote_recipients" class="scc-calc-settings-lbl">Include Email Form Data to User's Email  
										<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="include-quote-submitted-data-tt" style="margin-right:5px">help_outline</i>
									</label>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
									<label class="scc-switch">
										<div class="scc-switch">
											<input type="checkbox" name="include_quote_data_in_user_email" id="include_quote_data_in_user_email" class="form-control">
											<span class="slider round"></span>
										</div>
									</label>
								</div>
							</div><!-- end of turn off unit price column -->
							<!--turn off unit price column -->
							<div class="scc-vcenter" style="margin-left:10px">
								<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
									<label id="label_add_user_files_to_attachment" class="scc-calc-settings-lbl">Add User Uploaded Files As Attachment(s)  
										<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="user-files-as-attachment-tt" style="margin-right:5px">help_outline</i>
									</label>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
									<label class="scc-switch">
										<div class="scc-switch">
											<input type="checkbox" id="toggle_add_user_files_to_attachment" class="form-control">
											<span class="slider round"></span>
										</div>
									</label>
								</div>
							</div><!-- end of turn off unit price column -->
						</div>
					</div>
				</div><!-- End Blue Section - Email Settings -->
				
				<div class="clearfix more-settings-section col-md-12 scc-webhook-events-section" id="scc-event-actions">
					<!-- Start Blue Sec - Webhook Events -->
					<div style="font-size:140%;margin-bottom:10px" class="sccsubtitle scc_email_quote_label">
						<span style="font-weight:800;">WEBHOOK</span> EVENTS TRIGGER
					</div>
					<span class="scc-calc-settings-title" style="margin-left:10px;">Raw Webhooks</span>
					<hr class="scc-calc-settings-hr">
					<div class="row col-md-12 scc-cal-settings-row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-left:10px">
							<div class="scc-vcenter" style="margin-left:10px">
								<!-- Start of email quote webhook setup -->
								<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5 
								<?php
                                if ( $isSCCFreeVersion ) {
                                    echo 'use-premium-tooltip';
                                }
?>
								">
									<label id="label_usercompl" class="scc-calc-settings-lbl">
										User completes an <b>Email Quote Form</b>
										<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="user-completes-email-quote-form-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
									</label>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 
								<?php
if ( $isSCCFreeVersion ) {
    echo 'use-premium-tooltip';
}
?>
								">
									<label class="scc-switch">
										<div class="scc-switch">
											<input 
											<?php
            if ( $isSCCFreeVersion ) {
                echo 'disabled';
            }
?>
											type="checkbox" data-target="quote-fillup" name="scc_set_webhook_quote" id="scc_set_webhook_quote" class="form-control webhook-setup" 
		<?php
        if ( $f1->userCompletes == 'true' ) {
            echo 'checked';
        }
?>
		>
											<span class="slider round"></span>
										</div>
									</label>
									<i class="material-icons webhook-setup 
									<?php
                            if ( $isSCCFreeVersion ) {
                                echo 'disabled';
                            }
?>
									" data-event-type="quote-fillup">edit</i>
								</div>
							</div><!-- End of email quote webhook setup -->
							<div class="scc-vcenter" style="margin-left:10px">
								<!-- Start Toggle Btn for Detailed List Webhook -->
								<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5 
								<?php
                                if ( $isSCCFreeVersion ) {
                                    echo 'use-premium-tooltip';
                                }
?>
								">
									<label id="label_userclicks" class="scc-calc-settings-lbl">
										User clicks the <b>Detailed List</b> button
										<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="user-clicks-detailed-list-button-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
									</label>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 
								<?php
if ( $isSCCFreeVersion ) {
    echo 'use-premium-tooltip';
}
?>
								">
									<label class="scc-switch">
										<div class="scc-switch">
											<input 
											<?php
            if ( $isSCCFreeVersion ) {
                echo 'disabled';
            }
?>
											type="checkbox" data-target="detail-btn" name="scc_set_webhook_detail_view" id="scc_set_webhook_detail_view" class="form-control webhook-setup" 
											<?php
if ( $f1->userClicksf == 'true' ) {
    echo 'checked';
}
?>
		>
											<span class="slider round"></span>
										</div>
									</label>
									<i class="material-icons webhook-setup 
									<?php
                                    if ( $isSCCFreeVersion ) {
                                        echo 'disabled';
                                    }
?>
									" data-event-type="detail-btn">edit</i>
								</div>
							</div><!-- End of email quote custom js setup -->
							<div class="scc-calc-settings-title" id="scc_custom_js_code" style="margin-top:20px;margin-left: 10px;">Trigger Custom Code</div>
							<hr class="scc-calc-settings-hr">
							<small style="margin-left:10px">What can you do? Redirect to thank you page, conversion tracking, pop-up messages, much more.</small>
							<!-- Start of email quote custom js setup -->
							<div class="scc-vcenter" style="margin-left: 10px;">
								<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5 
								<?php
                                if ( $isSCCFreeVersion ) {
                                    echo 'use-premium-tooltip';
                                }
?>
								">
									<label id="label_email_qot_btn_click" class="scc-calc-settings-lbl">
										User completes an <b>Email Quote Form</b>
										<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="user-completes-email-quote-form-js-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
									</label>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 
								<?php
if ( $isSCCFreeVersion ) {
    echo 'use-premium-tooltip';
}
?>
								">
									<label class="scc-switch">
										<div class="scc-switch">
											<input 
											<?php
            if ( $isSCCFreeVersion ) {
                echo 'disabled';
            }
?>
											type="checkbox" data-target="quote-fillup" name="scc_set_customJs_quote" id="scc_set_customJs_quote" class="form-control custom-js-setup">
											<span class="slider round"></span>
										</div>
									</label>
									<i class="material-icons custom-js-setup 
									<?php
                                    if ( $isSCCFreeVersion ) {
                                        echo 'disabled';
                                    }
?>
									" data-event-type="quote-fillup">edit</i>
								</div>
							</div>
							<!-- Start Toggle Btn for Detailed List Custom JS -->
							<div class="scc-vcenter" style="margin-left: 10px;">
								<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5 
								<?php
                                if ( $isSCCFreeVersion ) {
                                    echo 'use-premium-tooltip';
                                }
?>
								">
									<label id="label_detailed_list_btn_click" class="scc-calc-settings-lbl">
										User clicks the <b>Detailed List</b> button
										<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="user-clicks-detailed-list-button-js-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
									</label>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 
								<?php
if ( $isSCCFreeVersion ) {
    echo 'use-premium-tooltip';
}
?>
								">
									<label class="scc-switch">
										<div class="scc-switch">
											<input 
											<?php
            if ( $isSCCFreeVersion ) {
                echo 'disabled';
            }
?>
											type="checkbox" data-target="detail-btn" name="scc_set_customJs_detail_view" id="scc_set_customJs_detail_view" class="form-control custom-js-setup">
											<span class="slider round"></span>
										</div>
									</label>
									<i class="material-icons custom-js-setup 
									<?php
                                    if ( $isSCCFreeVersion ) {
                                        echo 'disabled';
                                    }
?>
									" data-event-type="detail-btn">edit</i>
								</div>
							</div><!-- End Toggle Btn for Detailed List Custom JS -->
						</div>
					</div>
				</div><!-- End Toggle Btn for Detailed List Webhook -->
			</div>
		</div>
	</div>
	</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary settings-modal-save-btn" onclick="saveDataFields()">
			<span class="btn-text">Save changes</span>
			<span class="btn-text-alt">Loading</span>
			<i class="fas fa-spinner btn-spinner fa-spin"></i>
		</button>
      </div>
    </div>
  </div>
</div><!-- END OF MODAL -->
<div class="modal fade" id="settingsModal2" tabindex="-1" aria-labelledby="settingsModal2Label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable scc-modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="settingsModal2Label">Wordings</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
	<div id="dfscc_tab_translations_" class="my-4 py-2 px-4 col-xs-12 col-sm-10 col-md-8 col-lg-5 mx-auto" style="margin-left:35px;box-shadow: 1px 4px 10px #ddd;">

		<div class="row m-0">
			<div style="font-size:140%;" class="sccsubtitle scc_email_quote_label"><span style="font-weight:800;">LANGUAGE TRANSLATION</span> &amp; WORD CHANGES</div><br>
		</div>

		<!-- Start Local Translate style="display:none;" id="myDIV4"-->
		<div class="row">
			<div class="col-xs-4 col-md-4 col-sm-4 col-lg-5"><b>Frontend Word</b> </div>
			<div class="col-xs-4 col-md-4 col-sm-4 col-lg-5"><b>New Word / Translation</b> </div>
			<div class="col-md-1 "></div>
		</div>
		<?php
        //var_dump($translateArray[1]->translation);
        foreach ( $translateArray as $value ) {
            ?>
			<div class="row translation-row">
				<div class="col-xs-4 col-xs-4 col-md-4 col-sm-4 col-lg-5"><input type="text" value="<?php echo esc_attr( $value->key ); ?>" disabled=""></div>
				<div class="col-xs-4 col-xs-4 col-md-4 col-sm-4 col-lg-5"><input type="text" value="<?php echo esc_attr( $value->translation ); ?>"></div>
				<div class="col-md-1"><a href="javascript:void(0)" class="translate-del-btn material-icons" onclick="remove_rowTran(this)">close</a></div>
			</div>
		<?php } ?>

		<!-- End Local Translate -->
		<div class="col-lg-12 col-md-12 col-xs-12 scc-add-new-translate-button">
			<a href="javascript:void(0)" onclick="addNewTran(this)" class="crossnadd" style="font-size: 13px;">+ Add New Translate</a>
		</div>
	</div>
	</div>
<div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary settings-modal-save-btn" onclick="saveDataFields()">
			<span class="btn-text">Save changes</span>
			<span class="btn-text-alt">Loading</span>
			<i class="fas fa-spinner btn-spinner fa-spin"></i>
		</button>
      </div>
    </div>
  </div>
</div><!-- END OF MODAL -->
</div>
<div id="Webhook1" style="display:none">
	<h4 style="font-weight: bolder;">Quote Fillup Webhook</h4>
	<div class="form-group">
		<label for="" style="font-weight: normal;">Webhook link</label>
		<input class="from-control" type="text" name="" style="width: 100%;">
	</div>
	<div class="row">
		<div class="btn-group col-md-12 justify-content-end">
			<button class="btn " onclick="sssclose(this)">Cancel</button>
			<button class="btn " onclick="sssclose(this)" style="background-color: #006BB4;color:white">Save</button>
		</div>
	</div>
</div>
<div id="Webhook2" style="display:none">
	<h4 style="font-weight: bolder;">Detailed View Button Webhook</h4>
	<div class="form-group">
		<label for="" style="font-weight: normal;">Webhook link</label>
		<input class="from-control" type="text" name="" style="width: 100%;">
	</div>
	<div class="row">
		<div class="btn-group col-md-12 justify-content-end">
			<button class="btn " onclick="sssclose(this)">Cancel</button>
			<button class="btn " onclick="sssclose(this)" style="background-color: #006BB4;color:white">Save</button>
		</div>
	</div>
</div>
<div class="modal fade" id="quote_lists_modal" tabindex="-1" aria-labelledby="settingsModalLabel" aria-hidden="true">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	  </div>
	  <div class="modal-body">
	  <h5 class="modal-title" id="settingsModalLabel">Screenshot for the Quotes Management Screen</h5>
	  <div style="padding-bottom:20px;padding-top:20px;text-align:center;font-size:20px;color:#484848;"><span>Below is a screenshot for the Quotes Managment feature that comes with Stylish Cost Calculator premium. <br>View and manage quotes generated by users who have used the "Email Quotes" feature.</span>
	  <br><br>
	  <a class=" btn btn-settings-bar buynow-btn" style="color: #000; padding:20px;" target="_blank" href="https://www.stylishcostcalculator.com">Buy Now</a>
	  <a class=" btn btn-settings-bar buynow-btn" style="color: #000; padding: 20px;" target="_blank" href="https://designful.freshdesk.com/en/support/solutions/articles/48001216303-leads-quotes-management-system-a-complete-guide">Learn More</a></div>
	  <img src="<?php echo esc_url( SCC_URL . 'assets/images/quotes.png' ); ?>" style="max-width:80%;">
	  </div>
	</div>
  </div>
</div>
<script>
	/**
	 * *Show tooltip on hover eye
	 */
	jQuery(document).ready(function() {
		jQuery('.material-icons-outlined.more-settings-info:not(.t-img), .material-icons.v-align-middle').tooltip({
			placement: 'right'
		})
		jQuery('.use-tooltip').tooltip({
			placement: 'right'});
		jQuery('.tooltipadmin-right[title]').tooltip({
			position: {
				my: "center bottom",
				at: "right top"
			}
		})
		//IMG tooltip
		jQuery('.material-icons-outlined.more-settings-info[data-toggle="tooltip"]').tooltip({
			placement: 'right',
			html: true,
			customClass:'tooltip-img'
		});
	})
	


	/**
	 * *Available settings for autoload
	 * !autoload jquery needs to be loaded
	 */
		// prepare the items for search
		let availableTagsCollection = jQuery('label.scc-calc-settings-lbl', '#calc-settings-items').map((i, e) => ({
		labelTitle: jQuery(e).clone().find('i' || ">*").remove().end().text().trim(),
		labelNode: e
	})).get();
	jQuery(document).ready(function($a) {
		if (jQuery.ui) {
			$a("#ssc-search-term").autocomplete({
				source: availableTagsCollection.map(e => e.labelTitle),
				select: function(e, ui) {
					scc_deplacement_script(availableTagsCollection.find(e => e.labelTitle == ui.item.value).labelNode);
					ui.item.value = "";
				},
			});
		} else {
			alert("no cargo jquery-ui no cargado");
		}
	});
	/**
	 * *Moves where the option is located
	 */
	function scc_deplacement_script(htmlNode) {
		jQuery([document.documentElement, document.body]).animate({
			scrollTop: jQuery(htmlNode).offset().top - 50
		}, 1000);
		const shownModalBody = document.querySelector('.modal.show .modal-body');
		const position = htmlNode.offsetTop;
		shownModalBody.scrollTo({
			top: position - 80,
			behavior: 'smooth'
		});
		var originalColor = jQuery(htmlNode).css("color");
		var originalBackground = jQuery(htmlNode).parent().css("background-color")
		var $el = jQuery(htmlNode);
		var $div = jQuery(htmlNode).parent();
		$el.css("color", "white");
		$el.css("font-weight", "bold")
		$div.css("padding", "5px");
		$div.css("border-radius", "5px");
		$div.css("background-color", "#FF9A00");

		setTimeout(function() {
			$el.css("color", originalColor);
			$div.css("padding", "")
			$el.css("font-weight", "normal");
			$div.css("background-color", originalBackground);
		}, 4000);
		//console.log(document.getElementById(labelId));
	};

	/***
	 * *Closes the thickbox shown in webhooks
	 */
	function sssclose() {
		event.preventDefault();
		jQuery("#TB_closeWindowButton").click()
	}

	function changeFieldStyle(element) {
		var value = jQuery(element).val()
		var add_element = jQuery(element).closest('.scc-vcenter').next().hide()
		if (value == "style_2") {
			add_element.show()
		} else {
			add_element.hide()
		}
	}

	/**
	 * *Save Translation in database
	 * !dont delete or change the first 2 rows of classes or divs
	 * $translateArray = '[ { "key": "Total", "lang": "en", "translation": "" }, { "key": "Description", "lang": "en", "translation": "" }, { "key": "Unit Price", "lang": "en", "translation": "" }, { "key": "Quantity", "lang": "en", "translation": "" }, { "key": "Price", "lang": "en", "translation": "" }, { "key": "SEND", "lang": "en", "translation": "" }, { "key": "Total Price", "lang": "en", "translation": "" }, { "key": "Summary", "lang": "en", "translation": "" }, { "key": "Email Quote", "lang": "en", "translation": "" }, { "key": "Email Quote Form", "lang": "en", "translation": "" }, { "key": "Prove that you are not a robot", "lang": "en", "translation": "" }, { "key": "Please wait...", "lang": "en", "translation": "" }, { "key": "Submit", "lang": "en", "translation": "" }, { "key": "Cancel", "lang": "en", "translation": "" }, { "key": "Email Confirmation", "lang": "en", "translation": "" }, { "key": "Thank you! We sent your quote to", "lang": "en", "translation": "" }, { "key": "Remember to check your spam folder.", "lang": "en", "translation": "" }, { "key": "Detailed List", "lang": "en", "translation": "" }, { "key": "Choose an option...", "lang": "en", "translation": "" }, { "key": "Your Name", "lang": "en", "translation": "" }, { "key": "Your Email", "lang": "en", "translation": "" }, { "key": "Your Phone", "lang": "en", "translation": "" }, { "key": "Your Phone (Optional)", "lang": "en", "translation": "" }, { "key": "Coupon Code", "lang": "en", "translation": "" }, { "key": "Please choose an option", "lang": "en", "translation": "" }, { "key": "Enter your coupon code", "lang": "en", "translation": "" }, { "key": "This code is not valid", "lang": "en", "translation": "" }, { "key": "Discount percentage", "lang": "en", "translation": "" }, { "key": "Your discount has been applied correctly", "lang": "en", "translation": "" }, { "key": "Your discount has not been applied because the total price has to be between", "lang": "en", "translation": "" }, { "key": "The total price must be a minimum of", "lang": "en", "translation": "" } ]';
	 * 
	 */

	function getTranslationData() {
		var arrayTr = []
		var con = jQuery("#dfscc_tab_translations_").find(".row").slice(2).each(function(e) {
			var c = {}
			var a
			var b
			var s = jQuery(this).find("input").each(function(e) {
				var empt
				if (e == 0) {
					var i = jQuery(this).val()
					a = i
				}
				if (e == 1) {
					var i2 = jQuery(this).val()
					b = i2
				}
			})
			if (a != "") {
				c["key"] = a
				c["lang"] = "en"
				c["translation"] = b
				arrayTr.push(c)
			}
			//validate if first is not empty to add
		})
		return JSON.stringify(arrayTr)
	}


	/**
	 * *Removes one row from dom
	 */
	function remove_rowTran(element) {
		jQuery(element).parent().parent().remove()
	}

	/**
	 * *add row to dom
	 */
	function addNewTran(element) {
		jQuery("#dfscc_tab_translations_ div:last").before(render_row())

		function render_row() {
			var el = '<div class="row translation-row">'
			el += '    <div class="col-xs-4 col-md-4 col-sm-4 col-lg-5"><input type="text" value=""></div>'
			el += '    <div class="col-xs-4 col-md-4 col-sm-4 col-lg-5"><input type="text" value=""></div>'
			el += '    <div class="col-md-1"><a href="javascript:void(0)" class="translate-del-btn material-icons" onclick="remove_rowTran(this)">cancel</a></div>'
			el += '</div>'
			return el
		}
	}

	// save fromname
	function saveDataFields() {
		showLoadingChanges()
		var strTrans = getTranslationData()
		// idform
		var id_form = jQuery("#id_form_input").val();
		// name of form
		var formname = jQuery("body").find("#costcalculatorname").val();

		var activeModal = document.querySelector('.modal.show');

		// CALCULATOR SETTINGS
		var elementSkin = jQuery('select[name="form_field_style"]').val()
		var addContainer = jQuery('input[name="toggle_boxshadow_style2"]').is(":checked")
		// woocommerce
		// user action buttons
		var buttonStyle = jQuery('select[name="scc_user_action_btn_style"]').val()
		var turnviewdetails = !jQuery('input[name="scc_detailed_list"]').is(":checked")

		
		// total price settings
		var barstyle = jQuery('select[name="scc_total_price_style_view"]').val()
		var turnofffloating = jQuery('input[name="turn_on_total_price_float"]').is(":checked")
		// detailed list settings
		var removeTitle = !jQuery('input[name="scc_remove_detailed_list_title"]').is(":checked")
		var turnoffUnit = !jQuery('input[name="scc_no_unit_col"]').is(":checked")
		var turnoffQty = !jQuery('input[name="scc_no_qty_col"]').is(":checked")

		var turnoffSave = !jQuery('input[name="scc_save_icon"]').is(":checked")
		var turnoffTax = !jQuery('input[name="turn_off_tax"]').is(":checked")
		// currency & tax
		var symbol = jQuery('select[name="scc_currency_style"]').val()
		var removeCurrency = !jQuery('input[name="scc_remove_currency_label"]').is(":checked")
		// webhook
		var userCompletes = jQuery('input[name="scc_set_webhook_quote"]').is(":checked")
		var userClicksf = jQuery('input[name="scc_set_webhook_detail_view"]').is(":checked")
		var calcWrapperMaxWidth = jQuery('input[name="scc_wrapper_max_width"]').val()
		var turnoffQty = !jQuery('input[name="scc_no_qty_col"]').is(":checked")


		var json = {
			formname,
			elementSkin,
			addContainer,
			buttonStyle,
			turnviewdetails,
			barstyle,
			turnofffloating,
			removeTitle,
			turnoffUnit,
			turnoffQty,
			turnoffSave,
			turnoffTax,
			symbol,
			removeCurrency,
			userCompletes,
			userClicksf,
			calcWrapperMaxWidth
		}
		// Change form table settings names

		jQuery.ajax({
			url: ajaxurl,
			type:'POST',
			data: {
				action: 'sccSaveForm',
				id_form: id_form,
				data: json,
				translations: strTrans,
				nonce: pageEditCalculator.nonce
			},
			beforeSend: function() {
				if ( activeModal ) {
					activeModal.querySelector('.settings-modal-save-btn').setAttribute('disabled', true);
				}
			},
			success: function(data) {
				var datajson = JSON.parse(data)
				if (datajson.passed == true) {
					showSweet(true, "The changes have been saved.")
					disableInputT()
					if ( activeModal ) {
						const openedModalInstance = bootstrap.Modal.getInstance(activeModal);
						// Hide the modal
						openedModalInstance.hide();
					}
				} else {
					showSweet(false, datajson.msj)
				}
			}
		}).always(function() {
			activeModal.querySelector('.settings-modal-save-btn').removeAttribute('disabled');
		})
		
		function disableInputT(){
			jQuery('#dfscc_tab_translations_ .row > div > input').each(function(i,e){
				if(Number.isInteger(i/2)){
					jQuery(this).attr('disabled','true')
				}
			})
			
			
		}
	}

	// color picker 
	jQuery(document).ready(function() {
		const tabfont = jQuery("#dfscc_tab_font_settings_");
		const tabcalculator = jQuery("#dfscc_tab_calculator_");
		const tabtrabs = jQuery("#dfscc_tab_translations_");
		const tabembed = jQuery("#df_scc_tabembed_");

		const b_font = jQuery("#btn_dfscc_tab_font_settings_");
		const b_calc = jQuery("#btn_dfscc_tab_calculator_");
		const b_tans = jQuery("#btn_dfscc_tab_translations_");
		const b_embe = jQuery("#btn_df_scc_tabembed_");

		function handleSettingsTabs(target, srcBtn) {
			let tabs = [tabembed, tabfont, tabcalculator, tabtrabs];
			let btns = [b_font, b_calc, b_tans, b_embe];
			let targetIndex = tabs.findIndex(e => e == target);
			let btnIndex = btns.findIndex(e => e == srcBtn);
			if (targetIndex >= 0 && btnIndex >= 0) {
				delete btns[btnIndex];
				delete tabs[targetIndex];
			}
			target.slideToggle({
				complete: function() {
					if (target.attr('id') == 'df_scc_tabembed_') return;
					if (target.is(':visible')) {
						srcBtn.find('.material-icons-outlined').text('expand_less')
					} else {
						srcBtn.find('.material-icons-outlined').text('expand_more')
					}
				}
			});
			tabs.forEach(e => {
				e.hide();
			});
			btns.forEach(e => e.find('.material-icons-outlined').text('expand_more'))
		}
		<?php if ( ! $isSCCFreeVersion ) { ?>
		jQuery('.color-picker').wpColorPicker();
		<?php } ?>
	});
	// fonts
	var gFonts = JSON.parse(<?php echo json_encode( $scc_googlefonts_var->gf_get_local_fonts() ); ?>);

	jQuery('#titlescc_fonttype').on('change', (event) => {
		let selectedFont = jQuery(event.currentTarget).val();
		let selectedFontVariant = gFonts.items[selectedFont].variants;
		let variantSelectionElement = document.getElementById('titlescc_fonttype_variant');
		variantSelectionElement.innerHTML = '';
		for (var i = 0; i < selectedFontVariant.length; i++) {
			var opt = selectedFontVariant[i];
			var el = document.createElement("option");
			el.textContent = opt;
			el.value = opt;
			variantSelectionElement.appendChild(el);
		}
		jQuery(variantSelectionElement).val('regular');
	}).trigger('change');
	jQuery('#scc_fonttype').change((event) => {
		let selectedFont = jQuery(event.currentTarget).val();
		let selectedFontVariant = gFonts.items[selectedFont].variants;
		let variantSelectionElement = document.getElementById('scc_fonttype_variant');
		variantSelectionElement.innerHTML = '';
		for (var i = 0; i < selectedFontVariant.length; i++) {
			var opt = selectedFontVariant[i];
			var el = document.createElement("option");
			el.textContent = opt;
			el.value = opt;
			variantSelectionElement.appendChild(el);
		}
		jQuery(variantSelectionElement).val('regular');
	}).trigger('change');
		jQuery('#scc_fonttype_variant').val('<?php echo esc_attr( $f1->fontWeight ); ?>');
		jQuery('#titlescc_fonttype_variant').val('<?php echo esc_attr( $f1->titleFontWeight ); ?>');
</script>
<style>
	#premium-icon {
		vertical-align: middle;
	}
	a.buynow-btn:hover,
	a.buynow-btn:focus {
		color: #fff !important;
	}
	#quote_lists_modal {
		margin-top: 30px;
		
	}
	#quote_lists_modal .modal-body h5{
		padding-top: 15px;
		text-align: center;
		font-size: 40px;
		font-weight: bold;
		color: #314af3;
	}
	#quote_lists_modal .modal-dialog{
		
		margin-left: 173px;
		max-width: 100%;
		margin-right: 1em;
	}

	#quote_lists_modal .modal-content{

	}

	#quote_lists_modal .modal-header{
		border-bottom: unset;
	}

	@media screen and (max-width:950px){
		#quote_lists_modal .modal-dialog{
		margin-left: 50px;
		}
	}

	@media screen and (max-width:782px) {
		#quote_lists_modal .modal-dialog{
		margin-left: 20px;
		}
	}

	.closemodal {
		float: right;
		border: 1px solid;
		border-radius: 5px;
		cursor: pointer;
		width: 20px;
		height: 20px;
		text-align: center;
	}
	#dfscc_tab_translations_ .row {
		padding-top: 10px;
	}

	#dfscc_tab_translations_ .row input {
		width: 100%;
	}

	#dfscc_tab_translations_ .row a {
		font-size: 15px;

	}

	#TB_window {
		border-radius: 8px !important;
	}

	#TB_title {
		background: none !important;
		border-bottom: none !important;
	}
</style>
