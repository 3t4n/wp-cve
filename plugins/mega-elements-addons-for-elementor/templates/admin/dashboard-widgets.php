<?php
/**
 * Widgets Controls.
 * 
 * @package Mega_Elements
 */
$local_widgets    = \MegaElementsAddonsForElementor\Widgets_Manager::get_local_widgets_map();
$inactive_widgets = \MegaElementsAddonsForElementor\Widgets_Manager::get_inactive_widgets();
?>
<div class="mega-elements-tab-content mega-elements-widgets-content">
    <div class="block-wrap">
        <form id="mega-elements-elem-all-widgtsdata" method="post">
            <div class="mega-elements-block">
                <h2 class="mega-elements-title">
                    <?php esc_html_e( 'Mega Elements Widgets', 'mega-elements-addons-for-elementor' ); ?>
                    <img src="<?php echo esc_url(MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . '/assets/admin/dashboard/images/widget-icon.jpg'); ?>" alt="widget" />
                </h2>
                <div class="mega-elements-savetop-btn">
                    <button type="submit" style="float:right;" class="mega-elements-btn mega-elements-save-chkbx-toogles"><?php esc_html_e( 'Save Changes', 'mega-elements-addons-for-elementor' ); ?></button>
                </div>
                <div class="mega-elements-desc">
                    <p><?php esc_html_e( 'Here is the list of all our widgets. You can enable or disable any widgets to optimize the loading performance. Make sure to click on the Save Changes button after making the changes.', 'mega-elements-addons-for-elementor' ); ?></p>
                </div>
            </div>

            <div class="mega-elements-block">
                <div class="mega-elements-btns">
                    <button class="mega-elements-btn btn-enable">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="11.077" viewBox="0 0 16 11.077"><path d="M425,212q3.518,0,7.857,5.147a.6.6,0,0,1,.047.718l-.047.065-.2.231q-4.221,4.915-7.66,4.916-3.518,0-7.857-5.147a.6.6,0,0,1-.047-.718l.047-.065.2-.231Q421.561,212,425,212Zm0,1.231c-1.748,0-3.878,1.312-6.314,4.041l-.235.266.036.041c2.459,2.811,4.611,4.2,6.381,4.264l.132,0c1.748,0,3.877-1.312,6.314-4.041l.234-.267-.035-.041c-2.459-2.811-4.611-4.2-6.381-4.264Zm0,1.231a3.078,3.078,0,1,1-3.146,3.077A3.112,3.112,0,0,1,425,214.462Zm0,1.231a1.847,1.847,0,1,0,1.888,1.846A1.867,1.867,0,0,0,425,215.692Z" transform="translate(-417 -212)" fill="#4c40f7"/></svg>
                        <?php esc_html_e( 'Enable all', 'mega-elements-addons-for-elementor' ); ?>
                    </button>
                    <button class="mega-elements-btn btn-orange">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="12" viewBox="0 0 16 12"><path d="M11.986,20.135l.909-.909A2.989,2.989,0,0,1,9.226,22.9l.909-.909A1.991,1.991,0,0,0,11.986,20.135Zm5.951-.378A10.472,10.472,0,0,0,15.3,16.823l-.728.728A10.006,10.006,0,0,1,16.911,20,8.6,8.6,0,0,1,10,24a7.353,7.353,0,0,1-1.68-.2l-.821.821A8.467,8.467,0,0,0,10,25c5.234,0,7.829-4.563,7.937-4.757A.5.5,0,0,0,17.937,19.757Zm-2.084-4.9-11,11a.5.5,0,1,1-.707-.707l1.4-1.4a10.492,10.492,0,0,1-3.483-3.5.5.5,0,0,1,0-.486C2.171,19.563,4.766,15,10,15a8.422,8.422,0,0,1,3.528.765l1.619-1.619a.5.5,0,0,1,.707.707ZM6.292,23l1.266-1.266a3,3,0,0,1,4.178-4.178l1.024-1.024A7.364,7.364,0,0,0,10,16a8.6,8.6,0,0,0-6.912,4A9.819,9.819,0,0,0,6.292,23ZM8.283,21.01l2.726-2.726A1.978,1.978,0,0,0,10,18a2,2,0,0,0-2,2A1.975,1.975,0,0,0,8.283,21.01Z" transform="translate(-2 -14)" fill="#ff7c00"/></svg>
                        <?php esc_html_e( 'Disable All', 'mega-elements-addons-for-elementor' ); ?>
                    </button>
                </div>
                <div class="mega-elements-widget-list">
                    <?php foreach( $local_widgets as $widget_key => $widget_data ) : 
                        $checked = ! in_array( $widget_key, $inactive_widgets ) ? true : false;    
                    ?>
                        <div class="mega-elements-widget">
                            <label class="widget-label" for="<?php echo esc_attr( $widget_key ); ?>">
                                <?php echo $widget_data['icon']; ?>
                                <?php echo esc_html( $widget_data['name'] ); ?>
                            </label>
                            <div class="checkbox-wrap">
                                <input <?php checked( $checked ); ?> class="mega-elements-widget-chckbx" type="checkbox" name="active_widgets[]" value="<?php echo esc_attr( $widget_key ); ?>" id="<?php echo esc_attr( $widget_key ); ?>">
                                <label for="<?php echo esc_attr( $widget_key ); ?>"></label>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <button type="submit" style="float:right;" class="mega-elements-btn mega-elements-save-chkbx-toogles">
                <?php esc_html_e( 'Save Changes', 'mega-elements-addons-for-elementor' ); ?>
            </button>
        </form>
    </div>
</div>