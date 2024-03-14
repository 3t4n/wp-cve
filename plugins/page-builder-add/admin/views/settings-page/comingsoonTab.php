<?php if ( ! defined( 'ABSPATH' ) ) exit; 


$isComingSoonToggle = (isset($_GET['page']) && $_GET['page'] == 'page-builder-ulpb-comingsoon-settings') ? 'toggleComingSoon' : false;


// Coming Soon settings
$comingSoonModeOptions = get_option('popb_csm_options', false);

if(!$comingSoonModeOptions){
    $comingSoonModeOptions = array(
        "csmId" => 'false',
        "csmStatus" => false,
    );
}

$comingSoonModePageId = false;
$comingSoonModePageId = sanitize_text_field($comingSoonModeOptions['csmId']);

if($comingSoonModePageId){
    $comingsoonPage_post_status = get_post_status($comingSoonModePageId);
    if($comingsoonPage_post_status == 'trash' || $comingsoonPage_post_status == false){
        $comingSoonModePageId = 'false';
        $comingSoonModeOptions['csmId'] = 'false';
        update_option('popb_csm_options', $comingSoonModeOptions, false);
    }
}


$comingSoonModeOptions['csmStatus'] = sanitize_text_field( $comingSoonModeOptions['csmStatus'] );

$editLink = admin_url()."edit.php?post_type=ulpb_post&page=page-builder-new-landing-page&thisPostID=".$comingSoonModePageId."&pageType=comingsoonpage";
$previewLink = get_preview_post_link($comingSoonModePageId);
$setupCspPageLink = admin_url( '/post-new.php?post_type=ulpb_post&pageType=comingsoonpage');


//Maintenance Mode Settings

$maintenanceModeSettings = get_option('popb_maintenance_options', false);

if(!$maintenanceModeSettings){
    $maintenanceModeSettings = array(
        "csmId" => 'true',
        "csmStatus" => false,
    );
}

$maintenanceModePageId = false;
$maintenanceModePageId = sanitize_text_field($maintenanceModeSettings['csmId']);

if($maintenanceModePageId){
    $maintenancePage_post_status = get_post_status($maintenanceModePageId);
    if($maintenancePage_post_status == 'trash' || $maintenancePage_post_status == false){
        $maintenanceModePageId = 'false';
        $maintenanceModeSettings['csmId'] = 'false';
        update_option('popb_maintenance_options', $maintenanceModeSettings, false);
    }
}


$maintenanceModeSettings['csmStatus'] = sanitize_text_field( $maintenanceModeSettings['csmStatus'] );

$maintenanceModeEditLink = admin_url()."edit.php?post_type=ulpb_post&page=page-builder-new-landing-page&thisPostID=".$maintenanceModePageId."&pageType=maintenancemodepage";
$maintenancepreviewLink = get_preview_post_link($maintenanceModePageId);
$setupMaintenancePageLink = admin_url( '/post-new.php?post_type=ulpb_post&pageType=maintenancemodepage');

?>

<div>
    <form id="comingSoonOptionsForm">
        <div class="csm-cards-container">
            <div class="options_card_pluginops coming-soon-ops-card" >
                <div>
                    <img src="<?php echo ULPB_PLUGIN_URL.'/images/settings/csp-image.png' ?>" alt="coming soon thumbnail">
                </div>
                <div>
                    <h3>Coming Soon Mode</h3>
                    <p>Enable/Disable coming soon mode and make your website inaccessible to visitors. Admin & Editors will be able to view the website.</p>
                </div>
                <div class="csp_btn_container">
                    <?php
                    if($comingSoonModePageId && $comingSoonModePageId !== 'false'){
                        ?>
                        <a href="<?php echo $editLink; ?>" target="_blank" class="csp_edit_btn" >Edit</a>
                        <a href="<?php echo $previewLink; ?>" target="_blank" class="csp_prev_btn">Preview</a>
                        <?php
                    } else{
                        ?>
                            <a href="<?php echo $setupCspPageLink; ?>" target="_blank" class="csp_edit_btn" >Setup Coming Soon Page</a>
                        <?php 
                    }
                    ?>
                    
                </div>
                <?php 
                if($comingSoonModePageId && $comingSoonModePageId !== 'false'){
                    ?>
                    <div class="po-csp-switch-container">
                        <label class="po-csp-switch">
                            <input type="checkbox" id="togglepo-csp-Switchcsmp1" <?php echo ($comingSoonModeOptions['csmStatus'] == 'true' ? 'checked' : '') ?> >
                            <span class="po-csp-slider"></span>
                        </label>
                        <p id="csm-switchStatus" class="switchStatus">
                            <?php echo ($comingSoonModeOptions['csmStatus'] == 'true' ? 'Active' : 'Inactive') ?>
                        </p>
                    </div>
                    <?php            
                }
                ?>
                
                <div class="notification-bubble success-bubble" id="ntf_bbl_SuccessCsm">Saved!</div>
                <div class="notification-bubble error-bubble" id="ntf_bbl_ErrorCsm">Error!</div>
                
            </div>

            <div class="options_card_pluginops maintenance-ops-card " >
                <div>
                    <img src="<?php echo ULPB_PLUGIN_URL.'/images/settings/maintenance-mode-icon.png' ?>" alt="Maintenance mode thumbnail">
                </div>
                <div>
                    <h3>Maintenance Mode</h3>
                    <p>Enable/Disable maintenance mode and make your website inaccessible to visitors & search engines.</p>
                </div>
                <div class="csp_btn_container">
                    <?php
                    if($maintenanceModePageId && $maintenanceModePageId !== 'false'){
                        ?>
                        <a href="<?php echo $maintenanceModeEditLink; ?>" target="_blank" class="csp_edit_btn" >Edit</a>
                        <a href="<?php echo $maintenancepreviewLink; ?>" target="_blank" class="csp_prev_btn">Preview</a>
                        <?php
                    } else{
                        ?>
                            <a href="<?php echo $setupMaintenancePageLink; ?>" target="_blank" class="csp_edit_btn" >Setup Maintenance Page</a>
                        <?php 
                    }
                    ?>
                    
                </div>
                <?php 
                if($maintenanceModePageId && $maintenanceModePageId !== 'false'){
                    ?>
                    <div class="po-csp-switch-container">
                        <label class="po-csp-switch">
                            <input type="checkbox" id="togglepo-csp-Switchmaintp1" <?php echo ($maintenanceModeSettings['csmStatus'] == 'true' ? 'checked' : '') ?> >
                            <span class="po-csp-slider"></span>
                        </label>
                        <p id="mntm-switchStatus" class="switchStatus">
                            <?php echo ($maintenanceModeSettings['csmStatus'] == 'true' ? 'Active' : 'Inactive') ?>
                        </p>
                    </div>
                    <?php            
                }
                ?>
                
                <div class="notification-bubble success-bubble" id="ntf_bbl_SuccessCsm">Saved!</div>
                <div class="notification-bubble error-bubble" id="ntf_bbl_ErrorCsm">Error!</div>
                
            </div>
        </div>

        <?php include("comingSoonOptionsPanel.php"); ?>


        
        
    </form>
    <br><br>
    <div style="
        position: fixed;
        bottom: 0;
        left:180px;
        padding: 0 20px;
        width: 90%;
        background: #fff;
        z-index: 1;
        height:110px;
    ">
        <button id="ulpb_settings_form_submit_coming_soon" class="popb-settings-submit-btn" >Save Changes</button>
    </div>
    <br><hr><br>
</div>




<script type="text/javascript">
	(function($){

		$('#ulpb_settings_form_submit_coming_soon').on('click',function(){
			

            let comingSoonModePageId = <?php echo $comingSoonModePageId ? $comingSoonModePageId : 'false'; ?>;
            let maintenanceModePageId = <?php echo $maintenanceModePageId ? $maintenanceModePageId : 'false'; ?>;

            let csmStatus = $("#togglepo-csp-Switchcsmp1").is(":checked") ? true : false;
            let mntmStatus = $("#togglepo-csp-Switchmaintp1").is(":checked") ? true : false;


			let csm_data = {
                comingSoon : {
                    csmId: comingSoonModePageId,
                    csmStatus: csmStatus,
                },
                maintenanceMode : {
                    csmId: maintenanceModePageId,
                    csmStatus: mntmStatus,
                }
            };


            const getMultiSelectValues = (selector) => {
                let select = jQuery(selector);
                let options = select.find('option');
                let selectedValues = [];
                options.each(function () {
                    let value = jQuery(this).val();
                    if (jQuery(this).is(':selected')) {
                        selectedValues.push(value);
                    }
                });

                return selectedValues;
            }

            let csmntExtraOptions = {
                alwaysOnMaintenace : $("#alwaysOnMaintenace").is(":checked") ? true : false,
                allowSearchBots : $("#allowSearchBots").is(":checked") ? true : false,
                searchIndexed : $("#searchIndexed").is(":checked") ? true : false,
                allowByUserRoles : getMultiSelectValues('.multiSelector1'),
                excludedUsers : getMultiSelectValues('.multiSelector2'),
                excludedIpAddress: document.getElementById('excludedIpAddress').value,
                exludePages: document.getElementById('exludePages').value
            };

            csm_data.csmntExtraOptions = csmntExtraOptions;

			$.ajax({
				url: "<?php echo admin_url('admin-ajax.php?action=ulpb_settings_comingSoon_data&POPB_settings_page_nonce='.$plugOps_pageBuilder_settings_nonce ); ?>",
				method: 'post',
				data: {settings: csm_data},
				success: function(result){
                    console.log(result);
                    $("#ntf_bbl_SuccessCsm").fadeIn().delay(1000).fadeOut();

				},
                error: function(result){
                    console.log(result);
                    $("#ntf_bbl_ErrorCsm").fadeIn().delay(1000).fadeOut();
                }
			});
			
			return false;   
		});

		$("#togglepo-csp-Switchcsmp1").change(function() {
			// Update the po-csp-switch status text
			let switchStatus = $(this).is(":checked") ? "Active" : "Inactive";
        	$("#csm-switchStatus").text(switchStatus);
            if($(this).is(":checked")){
                let toggleSwitch = document.getElementById('togglepo-csp-Switchmaintp1');
                toggleSwitch.checked = false;
        	    $("#mntm-switchStatus").text('Inactive');
            }
		});



        //maintenance mode switch

        $("#togglepo-csp-Switchmaintp1").change(function() {
			// Update the po-csp-switch status text
			let switchStatus = $(this).is(":checked") ? "Active" : "Inactive";
        	$("#mntm-switchStatus").text(switchStatus);
            //toggle other switch
            if($(this).is(":checked")){
                let toggleSwitch = document.getElementById('togglepo-csp-Switchcsmp1');
                toggleSwitch.checked = false;
        	    $("#csm-switchStatus").text('Inactive');
            }
            
		});
        
        $(document).ready(function(){
            <?php
                if($isComingSoonToggle == "toggleComingSoon"){
                    echo "$('.comingsoonSettingsTab').trigger('click');";
                }
            ?>
        });
        

	})(jQuery);
</script>