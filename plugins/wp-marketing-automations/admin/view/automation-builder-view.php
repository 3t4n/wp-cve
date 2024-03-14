<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$automation_id   = $this->get_automation_id();
$automation_meta = BWFAN_Model_Automations::get_automation_with_data( $automation_id );

if ( false === $automation_id || ! is_array( $automation_meta ) || 0 === count( $automation_meta ) ) {
	wp_die( esc_html__( 'Automation doesn\'t exists, something is wrong.', 'wp-marketing-automations' ) );
}

$trigger_events      = isset( $automation_meta['event'] ) ? $automation_meta['event'] : '';
$saved_integrations  = array();
$parent_source       = isset( $automation_meta['source'] ) ? $automation_meta['source'] : '';
$a_track_id          = isset( $automation_meta['meta']['a_track_id'] ) ? $automation_meta['meta']['a_track_id'] : '';
$trigger_events_meta = isset( $automation_meta['meta']['event_meta'] ) ? $automation_meta['meta']['event_meta'] : [];
$saved_integrations  = isset( $automation_meta['meta']['actions'] ) ? $automation_meta['meta']['actions'] : [];

$automation_sticky_line = __( 'Now Building', 'wp-marketing-automations' );
$automation_onboarding  = true;
$automation_title       = ( isset( $automation_meta['meta'] ) && isset( $automation_meta['meta']['title'] ) ) ? $automation_meta['meta']['title'] : '';
$status                 = ( 1 === absint( $automation_meta['status'] ) ) ? 'publish' : 'sandbox';

if ( class_exists( 'BWFAN_Header' ) ) {
	$header_ins = new BWFAN_Header();
	$header_ins->set_level_1_navigation_active( 'automations' );
	$header_ins->set_back_link( 1, admin_url( 'admin.php?page=autonami&path=/automations-v1' ) );
	$header_ins->set_level_2_side_type( 'both' );
	$header_ins->set_level_2_title( $automation_title );

	$automation_migrated = isset( $automation_meta['meta'] ) && isset( $automation_meta['meta']['v1_migrate'] ) && $automation_meta['meta']['v1_migrate'] == 1 ? true : false;
	$automation_edit_html = ! $automation_migrated ? '<a class="bwfan_header_l2_edit" href="javascript:void(0)" data-izimodal-open="#modal-update-automation" data-izimodal-transitionin="comingIn"><i class="dashicons dashicons-edit"></i></a>' : '';
	$header_ins->set_level_2_post_title( $automation_edit_html );
	$header_ins->set_level_2_side_navigation( BWFAN_Header::level_2_navigation_single_automation( $automation_id ) );
	$header_ins->set_level_2_navigation_pos( 'right' );
	ob_start();
	if ( ! $automation_migrated ) {
		?>
        <div class="bwfan_head_mr" data-status="<?php echo ( 'publish' !== $status ) ? 'sandbox' : 'live'; ?>">
            <span class="bwfan_head_automation_state_on" <?php echo ( 'publish' !== $status ) ? ' style="display:none"' : ''; ?>><?php esc_html_e( 'Active', 'wp-marketing-automations' ); ?></span>
            <span class="bwfan_head_automation_state_off" <?php echo ( 'publish' === $status ) ? 'style="display:none"' : ''; ?>> <?php esc_html_e( 'Inactive', 'wp-marketing-automations' ); ?></span>
            <div class="automation_state_toggle bwfan_toggle_btn">
                <input name="offer_state" id="state<?php echo esc_html( $automation_id ); ?>" data-id="<?php echo esc_html( $automation_id ); ?>" type="checkbox" class="bwfan-tgl bwfan-tgl-ios" <?php echo ( 'publish' === $status ) ? 'checked="checked"' : ''; ?> <?php echo esc_html__( BWFAN_Core()->automations->current_automation_sync_state ); ?> />
                <label for="state<?php echo esc_html( $automation_id ); ?>" class="bwfan-tgl-btn bwfan-tgl-btn-small"></label>
            </div>
        </div>
		<?php
	} else {
		?>
        <div class="bwf-automation-migrated">
			<?php echo __( 'Migrated', 'wp-marketing-automations' ) ?>
        </div>
		<?php
	}
	$status = ob_get_clean();
	$header_ins->set_level_2_right_html( $status );

	echo $header_ins->render( $automation_migrated );
}
?>
<style>
    #wpwrap {
        background: #fff;
    }
</style>
<div class="bwfan_body bwfan_sec_automation">
    <div class="bwfan_wrap bwfan_box_size">
        <div class="bwfan_p20 bwfan_box_size">
            <div class="bwfan_wrap_inner">
				<?php
				/**
				 * Any registered section should also apply an action in order to show the content inside the tab
				 * like if action is 'stats' then add_action('bwfan_dashboard_page_stats', __FUNCTION__);
				 */
				if ( false === has_action( 'bwfan_dashboard_page_' . $this->get_automation_section() ) ) {
					include_once( $this->admin_path . '/view/section-' . $this->get_automation_section() . '.php' );
				} else {
					/**
					 * Allow other add-ons to show the content
					 */
					do_action( 'bwfan_dashboard_page_' . $this->get_automation_section() );
				}
				do_action( 'bwfan_automation_page', $this->get_automation_section(), $automation_id );
				?>
                <div class="bwfan_clear"></div>
            </div>
        </div>
    </div>
</div>

<div class="bwfan_izimodal_default" style="display: none" id="modal-update-automation">
    <div class="sections">
        <form class="bwfan_update_automation" data-bwf-action="update_automation">
            <div class="bwfan_vue_forms" id="part-add-funnel">
                <div class="form-group featured field-input">
                    <label for="title"><?php esc_html( __( 'Automation Name', 'wp-marketing-automations' ) ); ?></label>
                    <div class="field-wrap">
                        <div class="wrapper">
                            <input id="title" type="text" name="title" placeholder="<?php echo esc_html( __( 'Enter Automation Name', 'wp-marketing-automations' ) ); ?>" class="form-control" value="<?php echo esc_html( $automation_title ); ?>" required>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="_wpnonce" value="<?php esc_attr_e( wp_create_nonce( 'bwfan-action-admin' ) ); ?>"/>
            </div>
            <fieldset>
                <div class="bwfan_form_submit">
                    <input type="hidden" name="automation_id" value="<?php echo esc_html( $automation_id ); ?>">
                    <input type="submit" class="bwfan-display-none" value="<?php echo esc_html( __( 'Update', 'wp-marketing-automations' ) ); ?>"/>
                    <a href="javascript:void(0)" class="bwfan_update_form_submit bwfan_btn_blue"><?php echo esc_html( __( 'Update', 'wp-marketing-automations' ) ); ?></a>
                </div>
                <div class="bwfan_form_response">
                </div>
            </fieldset>
        </form>
        <div class="bwfan-automation-create-success-wrap bwfan-display-none">
            <div class="bwfan-automation-create-success-logo">
                <div class="swal2-icon swal2-success swal2-animate-success-icon" style="display: flex;">
                    <span class="swal2-success-line-tip"></span>
                    <span class="swal2-success-line-long"></span>
                    <div class="swal2-success-ring"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bwfan_izimodal_default" style="display: none" id="modal-plus-icon-add">
    <div class="sections bwfan_add_block_wrap">
        <div class="bwfan_add_next_block" data-type="action">
            <div class="bwfan_add_block_icon"><i class="dashicons dashicons-networking"></i></div>
            <div class="bwfan_add_block_label">Direct Action</div>
            <div class="bwfan_add_block_desc">Run Actions directly.</div>
        </div>
        <div class="bwfan_add_next_block" data-type="conditional">
            <div class="bwfan_add_block_icon"><i class="dashicons dashicons-editor-help"></i></div>
            <div class="bwfan_add_block_label">Conditional Action</div>
            <div class="bwfan_add_block_desc">Add condition based action, apply rules which will be executed before Actions.</div>
        </div>
    </div>
</div>

<div class="bwfan_success_modal iziModal" style="display: none" id="modal_automation_success">
</div>

<?php
$event_source        = BWFAN_Core()->sources->get_source_localize_data();
$all_triggers_events = BWFAN_Core()->sources->get_sources_events_localize_data();
$all_source_event    = BWFAN_Load_Sources::get_sources_events_arr();
$group               = [];
$all_groups          = [];

$all_integrations         = BWFAN_Core()->integration->get_integration_actions_localize_data();
$integration_actions_name = BWFAN_Core()->integration->get_mapped_arr_integration_name_with_action_name();
$integrations_object      = BWFAN_Core()->integration->get_integration_localize_data();
$integrations_group       = [];
$sub_integrations         = [];

/** Events */
foreach ( $event_source as $key => $group_data ) {
	if ( ! isset( $group[ $group_data['group_slug'] ] ) ) {
		$group[ $group_data['group_slug'] ] = [
			'label'    => $group_data['group_name'],
			'subgroup' => [ $group_data['slug'] ],
			'priority' => $group_data['priority']
		];
		$all_groups[]                       = $group_data['slug'];
	} else {
		if ( ! in_array( $group_data['slug'], $group[ $group_data['group_slug'] ]['subgroup'] ) ) {
			$group[ $group_data['group_slug'] ]['subgroup'][] = $group_data['slug'];
		}
		$all_groups[] = $group_data['slug'];
	}
}
$group['all'] = [
	'subgroup' => $all_groups,
	'show'     => false,
	'priority' => 999
];

$filter_events_arr = [];

/** Filter v1 events */
foreach ( $all_triggers_events as $key => $evtdata ) {
	$v1Event = [];
	foreach ( $evtdata as $evtKey => $evtVal ) {
		if ( $evtVal['support_v1'] ) {
			$v1Event[ $evtKey ] = $evtVal;
		}
	}
	if ( ! empty( $v1Event ) ) {
		$filter_events_arr[ $key ] = $v1Event;
	}
}

$filter_integration_arr = [];

/** Filter v1 actions */
foreach ( $all_integrations as $key => $actiondata ) {
	$v1Actions = [];
	foreach ( $actiondata as $actionKey => $actionVal ) {
		if ( $actionVal['support_v1'] ) {
			$v1Actions[ $actionKey ] = $actionVal;
		}
	}
	if ( ! empty( $v1Actions ) ) {
		$filter_integration_arr[ $key ] = $v1Actions;
	}
}

/** Actions */
foreach ( $integrations_object as $key => $data ) {
	if ( ! isset( $integrations_group[ $data['group_slug'] ] ) ) {
		$integrations_group[ $data['group_slug'] ] = [
			'label'    => $data['group_name'],
			'subgroup' => [ $data['slug'] => $data['nice_name'] ],
			'priority' => isset( $data['priority'] ) ? $data['priority'] : 1000
		];
		$sub_integrations[ $data['slug'] ]         = $data['nice_name'];
	} else {
		if ( ! in_array( $data['slug'], $integrations_group[ $data['group_slug'] ]['subgroup'] ) ) {
			$integrations_group[ $data['group_slug'] ]['subgroup'][ $data['slug'] ] = $data['nice_name'];
		}
		$sub_integrations[ $data['slug'] ] = $data['nice_name'];
	}
}

$integrations_group = apply_filters( 'bwfan_modify_actions_groups', $integrations_group );
$all_integrations   = apply_filters( 'bwfan_modify_integrations', $filter_integration_arr );

$integrations_group['all'] = [
	'subgroup' => $sub_integrations,
	'show'     => false,
	'priority' => 999
];
$templates                 = bwfan_is_autonami_pro_active() ? BWFAN_Model_Templates::bwfan_get_templates( 0, 0, '', [] ) : [];

if ( ! empty( $templates ) ) {
	$templates = array_map( function ( $template ) {
		if ( ! empty( $template['data'] ) ) {
			$template['data'] = json_decode( $template['data'] );
		}

		return $template;
	}, $templates );
}

$link_triggers = bwfan_is_autonami_pro_active() && class_exists( 'BWFAN_Model_Link_Triggers' ) ? BWFAN_Model_Link_Triggers::get_link_triggers( '', 2, '', '', false ) : [];

if ( ! empty( $link_triggers ) && isset( $link_triggers['links'] ) ) {
	$actions       = BWFCRM_Core()->actions->get_all_action_list();
	$link_triggers = array_map( function ( $link ) use ( $actions ) {
		$temp = [];
		if ( isset( $link['data'] ) ) {
			$temp = $link['data'];
			unset( $link['data'] );
		}
		if ( isset( $temp['actions'] ) && ! empty( $temp['actions'] ) && is_array( $temp['actions'] ) ) {
			$action = [];
			foreach ( $temp['actions'] as $key => $value ) {
				if ( isset( $actions[ $key ] ) ) {
					$action[] = $actions[ $key ];
				}
			}
			$temp['actions'] = $action;
		}

		return array_merge( $link, $temp );
	}, $link_triggers['links'] );
}

$localized_data = [
	'actions'            => $filter_events_arr,
	'all_source'         => $event_source,
	'groupdata'          => $group,
	'source_event'       => $all_source_event,
	'integration_list'   => $all_integrations,
	'integration_group'  => $integrations_group,
	'woocommerce_enable' => BWFAN_Plugin_Dependency::woocommerce_active_check(),
	'templates'          => $templates,
	'link_triggers'      => $link_triggers,
	'siteurl'            => get_site_url(),
];
echo '<script id="bwfanAutomationEvents">
    var automationEventActionData = ' . wp_json_encode( $localized_data ) . ';
</script>';
?>

<div class="bwfan_izimodal_default" style="display: none" id="modal-autonami-event">
    <div class="bwfan-search-filter-modal-wrap">
        <div class="bwfan-modal-header bwfan_p15">
            <div class="modal-header-title bwfan_heading_l bwfan_head_mr"><?php _e( 'Select an Event' ) ?></div>
            <div class="modal-header-search">
                <span class="dashicons dashicons-search modal-search-icon"></span>
                <input type="search" id="modal-search-field" placeholder="Search Event">
            </div>
            <span class="dashicons dashicons-no-alt bwfan_btn_close bwfan_modal_close" data-izimodal-close></span>
        </div>
        <div class="bwfan-modal-content">
            <div class="bwfan-modal-sidebar bwfan_p15">
                <div class="bwfan-modal-widget-wrap">
                    <label class="bwfan-widget-checkbox-wrap" id="bwf-event-search-row" style="display: none">
                        <input type="radio" name="widget_filter" value="all" class="bwfan-widget-filter ">
                        <span class="bwfan-widget-checkbox-label">Search results</span>
                    </label>
					<?php
					uasort( $group, function ( $a, $b ) {
						return $a['priority'] <=> $b['priority'];
					} );
					foreach ( $group as $key => $filter ) {
						if ( isset( $filter['show'] ) && ! $filter['show'] ) {
							continue;
						}
						?>
                        <label class="bwfan-widget-checkbox-wrap">
                            <input type="radio" name="widget_filter" value="<?php echo $key ?>" class="bwfan-widget-filter ">
                            <span class="bwfan-widget-checkbox-label"><?php echo $filter['label'] ?></span>
                        </label>
						<?php
					}
					?>
                </div>
            </div>
            <div class="bwfan-modal-content-content bwfan_p15" id="bwfan-modal-content-content">
            </div>
        </div>
        <div class="bwfan-modal-footer bwfan_p15 bwfan_tr">
            <button type="button" class="button button-primary fixed_button" id="bwf-modal-event-continue" disabled><?php _e( 'Continue' ) ?></button>
        </div>
    </div>
</div>

<div class="bwfan_izimodal_default" style="display: none" id="modal-autonami-event-action">
    <div class="bwfan-search-filter-modal-wrap">
        <div class="bwfan-modal-header bwfan_p15">
            <div class="modal-header-title bwfan_heading_l bwfan_head_mr"><?php _e( 'Select an Action' ) ?></div>
            <div class="modal-header-search">
                <span class="dashicons dashicons-search modal-search-icon"></span>
                <input type="search" id="modal-search-action-field" placeholder="Search Action">
            </div>
            <span class="dashicons dashicons-no-alt bwfan_btn_close bwfan_modal_close" data-izimodal-close></span>
        </div>
        <div class="bwfan-modal-content">
            <div class="bwfan-modal-sidebar bwfan_p15">
                <div class="bwfan-modal-widget-wrap">
                    <label class="bwfan-widget-checkbox-wrap" id="bwf-action-search-row" style="display: none">
                        <input type="radio" name="widget_filter" value="all" class="bwfan-widget-filter ">
                        <span class="bwfan-widget-checkbox-label">Search results</span>
                    </label>
					<?php
					uasort( $integrations_group, function ( $a, $b ) {
						return $a['priority'] <=> $b['priority'];
					} );
					foreach ( $integrations_group as $key => $filter ) {
						if ( isset( $filter['show'] ) && ! $filter['show'] ) {
							continue;
						}
						?>
                        <label class="bwfan-widget-checkbox-wrap">
                            <input type="radio" name="widget_filter" value="<?php echo $key ?>" class="bwfan-widget-filter ">
                            <span class="bwfan-widget-checkbox-label"><?php echo $filter['label'] ?></span>
                        </label>
						<?php
					}
					?>
                </div>
            </div>
            <div class="bwfan-modal-content-content bwfan_p15" id="bwfan-modal-content-content">
            </div>
        </div>
        <div class="bwfan-modal-footer bwfan_p15 bwfan_tr">
            <input type="hidden" name="selected_action_group_id"/>
            <input type="hidden" name="selected_action_action_id"/>
            <button type="button" class="button button-primary fixed_button" id="bwf-modal-action-continue" disabled><?php _e( 'Continue' ) ?></button>
        </div>
    </div>
</div>

<div class="bwfan_izimodal_default" style="display: none" id="modal-autonami-template-selector">
    <div class="bwfan-search-filter-modal-wrap">
        <div class="bwfan-modal-header bwfan_p15">
            <div class="modal-header-title bwfan_heading_l bwfan_head_mr"><?php _e( 'My Templates' ) ?></div>
            <div class="modal-header-search">
                <span class="dashicons dashicons-search modal-search-icon"></span>
                <input type="search" id="modal-search-template-field" placeholder="Search by name">
            </div>
            <span class="dashicons dashicons-no-alt bwfan_btn_close bwfan_modal_close" id="bwfan-modal-template-close" data-izimodal-close></span>
        </div>
        <div class="bwfan-modal-content">
            <div class="bwfan-modal-sidebar bwfan_p15">
                <div class="bwfan-modal-widget-wrap">
                    <label class="bwfan-widget-checkbox-wrap">
                        <input type="radio" name="widget_filter" value="all" class="bwfan-widget-filter is-selected" checked>
                        <span class="bwfan-widget-checkbox-label">All</span>
                    </label>
                    <label class="bwfan-widget-checkbox-wrap">
                        <input type="radio" name="widget_filter" value="1" class="bwfan-widget-filter ">
                        <span class="bwfan-widget-checkbox-label">Rich Text</span>
                    </label>
                    <label class="bwfan-widget-checkbox-wrap">
                        <input type="radio" name="widget_filter" value="3" class="bwfan-widget-filter ">
                        <span class="bwfan-widget-checkbox-label"> Raw HTML</span>
                    </label>
                    <label class="bwfan-widget-checkbox-wrap">
                        <input type="radio" name="widget_filter" value="4" class="bwfan-widget-filter ">
                        <span class="bwfan-widget-checkbox-label">Drag and Drop</span>
                    </label>
                </div>
            </div>
            <div class="bwfan-modal-content-content bwfan_p15" id="bwfan-modal-template-content">
            </div>
        </div>
    </div>
</div>

<div class="bwfan_izimodal_default" style="display: none" id="modal-autonami-link-trigger-selector">
    <div class="bwfan-search-filter-modal-wrap">
        <div class="bwfan-modal-header bwfan_p15">
            <div class="modal-header-title bwfan_heading_l bwfan_head_mr"><?php _e( 'Link Triggers' ) ?></div>
            <div class="modal-header-search">
                <span class="dashicons dashicons-search modal-search-icon"></span>
                <input type="search" id="modal-search-link-trigger-field" placeholder="Search by name">
            </div>
            <span class="dashicons dashicons-no-alt bwfan_btn_close bwfan_modal_close" id="bwfan-modal-template-close" data-izimodal-close></span>
        </div>
        <div class="bwfan-modal-content">
            <div class="bwfan-modal-content-content bwfan_p15" id="bwfan-modal-link-trigger-content">
            </div>
        </div>
    </div>
</div>
<div class="bwfan_izimodal_default" style="display: none" id="modal-autonami-migrator-modal">
    <div class="bwfan-search-filter-modal-wrap">
        <div class="bwfan-modal-header bwfan_p15">
            <div class="modal-header-title bwfan_heading_l bwfan_head_mr"><?php _e( 'Migrate Automation' ) ?></div>
            <div class="modal-header-search"></div>
            <span class="dashicons dashicons-no-alt bwfan_btn_close bwfan_modal_close" data-izimodal-close></span>
        </div>
        <div class="bwfan-modal-content">
            <div class="bwf-migrator-modal__modal-content">
                <div class="bwf-migrate-icon">
                    <svg width="166" height="139" viewBox="0 0 166 139" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g style="mix-blend-mode:multiply" opacity="0.3">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M92.288 99.7514C105.589 99.0949 119.345 98.498 131.695 100.147C145.909 102.045 163.256 104.788 165.274 109.497C167.339 114.314 145.095 116.912 140.889 121.598C136.307 126.701 152.456 133.265 140.506 137.089C129.113 140.734 108.375 138.24 92.288 137.149C79.1091 136.255 69.2836 133.434 57.431 131.456C43.1341 129.07 21.9447 128.99 15.7619 124.377C9.62336 119.796 20.3442 114.609 29.3415 110.496C36.741 107.113 49.5282 105.612 61.1411 103.629C71.1764 101.916 80.9459 100.311 92.288 99.7514Z" fill="#ADD3E4"/>
                        </g>
                        <path d="M13.0166 105.034C17.1393 97.4748 4.1249 93.9203 0.987772 96.9186C-2.14971 99.9172 8.2514 113.772 13.0166 105.034Z" fill="#E28CB5"/>
                        <path d="M18.488 102.727C26.5782 102.727 22.9169 88.2349 19.4579 88.2349C15.1214 88.2352 9.14085 102.727 18.488 102.727Z" fill="#E28CB5"/>
                        <path d="M24.724 107.984C31.394 113.422 37.2562 101.255 34.8845 97.6183C32.5128 93.9821 17.0143 101.699 24.724 107.984Z" fill="#E28CB5"/>
                        <path d="M16.2188 111.582C15.093 108.973 13.4452 106.542 11.3214 104.358C7.70794 100.641 3.9074 98.8978 3.86922 98.8808C3.75605 98.8297 3.62273 98.8801 3.5717 98.9931C3.52067 99.1064 3.571 99.2396 3.68382 99.291C3.72097 99.3077 7.45588 101.023 11.0099 104.683C13.0928 106.828 14.7077 109.212 15.8096 111.77C15.9345 112.06 16.0508 112.353 16.1626 112.648H16.6434C16.5111 112.29 16.3705 111.934 16.2188 111.582Z" fill="#CC6697"/>
                        <path d="M19.2559 91.2065C19.2705 91.0831 19.1823 90.9712 19.0591 90.9562C18.9355 90.9413 18.8241 91.0296 18.8095 91.1533C18.7949 91.2764 17.6605 100.988 17.7185 112.648H18.168C18.11 101.018 19.2414 91.3293 19.2559 91.2065Z" fill="#CC6697"/>
                        <path d="M32.6391 99.2463C32.57 99.1426 32.4304 99.1148 32.3273 99.184C32.295 99.2056 29.0499 101.392 25.7115 104.987C23.8931 106.945 21.8262 109.571 20.312 112.648H20.8147C22.2949 109.706 24.2812 107.19 26.0336 105.301C29.337 101.742 32.545 99.58 32.5773 99.5584C32.68 99.4896 32.7078 99.3499 32.6391 99.2463Z" fill="#CC6697"/>
                        <path d="M18.1688 112.648H17.7193H16.6428H16.162H9.8867C9.64543 112.648 9.47428 112.884 9.54892 113.114L9.94501 114.331L10.1457 114.947L13.0134 123.759C13.0912 123.998 13.314 124.16 13.565 124.16H23.9019C24.1529 124.16 24.3757 123.998 24.4535 123.759L27.3213 114.947L27.5215 114.331L27.9176 113.114C27.9926 112.884 27.8215 112.648 27.5802 112.648H20.8116H20.309H18.1688Z" fill="#D1E5ED"/>
                        <path d="M27.5217 114.331L27.3214 114.947H10.1451L9.94482 114.331H27.5217Z" fill="#A6BFCA"/>
                        <path d="M72.4718 80.1228H6.35848C4.49817 80.1228 2.99023 78.6111 2.99023 76.7462V13.6452C2.99023 11.7803 4.49817 10.2686 6.35848 10.2686H72.4718C74.3321 10.2686 75.8401 11.7803 75.8401 13.6452V76.7462C75.8401 78.6111 74.3321 80.1228 72.4718 80.1228Z" fill="#ADD3E4"/>
                        <path d="M58.7492 33.5737H53.4839L52.5644 36.5586H48.4595L54.3049 20.5811H57.9173L63.8065 36.5586H59.6797L58.7492 33.5737ZM54.4034 30.5999H57.8297L56.1111 25.0583L54.4034 30.5999Z" fill="white"/>
                        <path d="M44.2382 20.3794H12.8689C12.3105 20.3794 11.8579 19.9258 11.8579 19.366C11.8579 18.8063 12.3105 18.3525 12.8689 18.3525H44.2382C44.7965 18.3525 45.2491 18.8063 45.2491 19.366C45.2491 19.9258 44.7965 20.3794 44.2382 20.3794Z" fill="white"/>
                        <path d="M34.1537 25.4361H12.8689C12.3105 25.4361 11.8579 24.9824 11.8579 24.4227C11.8579 23.863 12.3105 23.4092 12.8689 23.4092H34.1537C34.7121 23.4092 35.1646 23.863 35.1646 24.4227C35.1646 24.9824 34.7121 25.4361 34.1537 25.4361Z" fill="white"/>
                        <path d="M18.1858 33.7196H12.5799C12.1811 33.7196 11.8579 33.3957 11.8579 32.9958C11.8579 32.5959 12.1811 32.272 12.5799 32.272H18.1858C18.5847 32.272 18.9079 32.5959 18.9079 32.9958C18.9079 33.3957 18.5847 33.7196 18.1858 33.7196Z" fill="#0073AA"/>
                        <path d="M59.3155 58.5773H41.9911C41.5922 58.5773 41.269 58.2533 41.269 57.8535V54.9289C41.269 54.5291 41.5922 54.2051 41.9911 54.2051H59.3155C59.7144 54.2051 60.0376 54.5291 60.0376 54.9289V57.8535C60.0376 58.2533 59.7144 58.5773 59.3155 58.5773Z" fill="white"/>
                        <path d="M66.2961 66.525H41.991C41.5922 66.525 41.269 66.2011 41.269 65.8012V62.8767C41.269 62.4768 41.5922 62.1528 41.991 62.1528H66.2961C66.695 62.1528 67.0181 62.4768 67.0181 62.8767V65.8012C67.0181 66.2011 66.6945 66.525 66.2961 66.525Z" fill="white"/>
                        <path d="M22.6499 71.2438C22.6499 70.4983 23.2526 69.8936 23.9967 69.8936C18.7558 69.8936 14.4921 65.6193 14.4921 60.3656C14.4921 55.1118 18.7558 50.8375 23.9967 50.8375C29.2375 50.8375 33.5012 55.1118 33.5012 60.3656C33.5012 59.6201 34.104 59.0154 34.848 59.0154C35.5916 59.0154 36.1948 59.6197 36.1948 60.3656C36.1948 53.623 30.7231 48.1377 23.9971 48.1377C17.2711 48.1377 11.7993 53.623 11.7993 60.3656C11.7993 67.1081 17.2711 72.5934 23.9971 72.5934C23.2531 72.5938 22.6499 71.9892 22.6499 71.2438Z" fill="white"/>
                        <path d="M34.8412 59.0156C34.0976 59.0156 33.4944 59.6199 33.4944 60.3658C33.4944 65.6196 29.2307 69.8938 23.9898 69.8938C23.2462 69.8938 22.6431 70.4985 22.6431 71.244C22.6431 71.9895 23.2458 72.5941 23.9898 72.5941C30.7158 72.5941 36.1876 67.1084 36.1876 60.3662C36.1876 59.6199 35.5848 59.0156 34.8412 59.0156Z" fill="#0073AA"/>
                        <path d="M3.04688 14.2686C3.04688 12.0594 4.83774 10.2686 7.04688 10.2686H71.8011C74.0103 10.2686 75.8011 12.0594 75.8011 14.2686V15.357H3.04688V14.2686Z" fill="#005D89"/>
                        <ellipse cx="9.39592" cy="12.8126" rx="1.26897" ry="1.2721" fill="white"/>
                        <ellipse cx="13.624" cy="12.8126" rx="1.26897" ry="1.2721" fill="white"/>
                        <ellipse cx="17.8539" cy="12.8126" rx="1.26897" ry="1.2721" fill="white"/>
                        <path d="M96.2511 9.66689C96.7125 15.274 100.78 18.7892 103.167 18.7892C103.525 18.7892 103.915 18.7251 104.322 18.6131V21.4099H110.376V14.2994C110.432 14.1523 110.486 13.9892 110.54 13.815C113.224 13.5582 113.112 9.11583 111.518 9.11583C111.227 9.11583 111.034 9.39044 110.911 9.66689L110.516 10.0634V8.05641C110.516 8.05641 109.625 8.14013 107.871 5.46387C107.114 6.61375 101.079 8.41878 96.2359 7.61057C96.2355 7.6102 96.2233 8.60854 96.2511 9.66689Z" fill="#FCBEB7"/>
                        <path d="M103.585 0C107.761 0 108.596 2.23012 108.596 2.23012C110.377 2.23012 113.105 5.4919 111.463 9.1157L111.454 9.12421L110.516 10.0637V8.05665C110.516 8.05665 109.625 8.14036 107.872 5.4641C107.115 6.61398 101.08 8.41902 96.2364 7.6108C96.2364 7.6108 96.2238 8.60915 96.2516 9.66712C96.2516 9.66824 96.2516 9.66936 96.2516 9.67047C94.443 4.4295 98.0739 0 103.585 0Z" fill="#311643"/>
                        <path d="M105.38 10.2774C105.565 10.2774 105.714 10.1277 105.714 9.94276V9.31796C105.714 9.13305 105.565 8.9834 105.38 8.9834C105.195 8.9834 105.046 9.13305 105.046 9.31796V9.94276C105.046 10.1277 105.196 10.2774 105.38 10.2774Z" fill="#311643"/>
                        <path d="M99.1862 10.2774C99.3708 10.2774 99.5203 10.1277 99.5203 9.94276V9.31796C99.5203 9.13305 99.3708 8.9834 99.1862 8.9834C99.0015 8.9834 98.8521 9.13305 98.8521 9.31796V9.94276C98.8524 10.1277 99.0019 10.2774 99.1862 10.2774Z" fill="#311643"/>
                        <path d="M102.924 15.1027C103.52 15.1027 103.926 14.8781 104.172 14.6736C104.482 14.4157 104.61 14.1344 104.616 14.1225C104.651 14.0454 104.616 13.9547 104.539 13.9199C104.462 13.885 104.372 13.9194 104.337 13.9961C104.32 14.0339 103.896 14.9234 102.666 14.7807C102.582 14.7711 102.506 14.831 102.496 14.9155C102.486 15 102.547 15.0756 102.631 15.0852C102.733 15.0971 102.831 15.1027 102.924 15.1027Z" fill="#311643"/>
                        <path d="M107.597 13.9715C108.509 13.9715 109.248 13.2309 109.248 12.3173C109.248 11.4037 108.509 10.6631 107.597 10.6631C106.684 10.6631 105.945 11.4037 105.945 12.3173C105.945 13.2309 106.684 13.9715 107.597 13.9715Z" fill="#F49795"/>
                        <path d="M96.4224 10.9436C96.6318 12.0546 96.9826 13.0673 97.4262 13.9689C98.2998 13.9259 98.9943 13.2026 98.9943 12.3169C98.9943 11.4034 98.255 10.6631 97.343 10.6631C97.0022 10.6631 96.6854 10.7665 96.4224 10.9436Z" fill="#F49795"/>
                        <path d="M96.8472 9.63059C96.8472 10.9231 97.8973 11.9745 99.1875 11.9745C100.449 11.9745 101.479 10.9706 101.526 9.71916C102.226 9.32228 102.849 9.60729 103.042 9.71735C103.088 10.9699 104.119 11.9749 105.38 11.9749C106.671 11.9749 107.721 10.9235 107.721 9.63095C107.721 8.3384 106.671 7.28711 105.38 7.28711C104.175 7.28711 103.18 8.20465 103.054 9.37899C102.748 9.23854 102.17 9.07102 101.514 9.38007C101.389 8.20536 100.393 7.28711 99.1872 7.28711C97.8973 7.28674 96.8472 8.33804 96.8472 9.63059ZM103.347 9.63059C103.347 8.50739 104.259 7.59326 105.381 7.59326C106.503 7.59326 107.416 8.50702 107.416 9.63059C107.416 10.7542 106.503 11.668 105.381 11.668C104.259 11.668 103.347 10.7542 103.347 9.63059ZM97.1532 9.63059C97.1532 8.50739 98.0656 7.59326 99.1875 7.59326C100.309 7.59326 101.222 8.50702 101.222 9.63059C101.222 10.7542 100.309 11.668 99.1875 11.668C98.066 11.668 97.1532 10.7542 97.1532 9.63059Z" fill="white"/>
                        <path d="M104.322 21.4098V18.6131C105.691 18.2362 107.25 17.3132 108.421 16.4331L104.322 21.4098Z" fill="#F9AFAB"/>
                        <path d="M89.8501 111.672H96.2621L102.481 78.5474L107.044 64.1905L118.088 111.672H124.5L118.526 58.4028H94.8375L89.8501 111.672Z" fill="#0073AA"/>
                        <path d="M95.752 114.254V118.792C95.752 119.322 95.3235 119.75 94.7951 119.75H81.9429C81.8208 119.75 81.7217 119.651 81.7217 119.529V118.655C81.7217 118.273 81.94 117.925 82.2834 117.759L89.5301 114.254H95.752Z" fill="#2F3D6C"/>
                        <path d="M118.783 114.254V118.792C118.783 119.322 119.212 119.75 119.74 119.75H132.592C132.714 119.75 132.813 119.651 132.813 119.529V118.655C132.813 118.273 132.595 117.925 132.252 117.759L125.005 114.254H118.783Z" fill="#2F3D6C"/>
                        <path d="M96.2593 111.672L95.7764 114.254H89.5283L89.8473 111.672H96.2593Z" fill="#FCBEB7"/>
                        <path d="M124.499 111.672L125.006 114.254H118.784L118.087 111.672H124.499Z" fill="#FCBEB7"/>
                        <path d="M118.654 59.5453L94.6025 60.9061L94.8371 58.4028H118.526" fill="#005D89"/>
                        <path d="M104.332 19.0238L95.1672 23.0074C94.496 23.2968 93.9236 23.7756 93.5195 24.3856L89.8501 29.9233L95.0244 34.4958L96.4068 33.3134L94.8375 58.4031H118.526L117.074 42.0436L117.396 33.3134L118.626 34.5403L124.092 30.3317L121.155 25.538C120.429 24.3525 119.378 23.3995 118.129 22.7921L110.376 19.0234C110.376 19.0238 106.859 21.3343 104.332 19.0238Z" fill="#7EB9D6"/>
                        <path d="M102.382 19.87C103.667 20.9483 105.272 21.5349 106.968 21.5349C108.754 21.5349 110.439 20.8842 111.755 19.6954L111.516 19.5791C110.257 20.6856 108.659 21.2896 106.968 21.2896C105.372 21.2896 103.858 20.7512 102.634 19.7603L102.382 19.87Z" fill="white"/>
                        <path d="M118.42 23.5748C118.42 24.701 119.332 25.6137 120.457 25.6137C120.692 25.6137 120.918 25.5736 121.129 25.4999C120.478 24.4537 119.575 23.591 118.505 22.9907C118.45 23.1756 118.42 23.3717 118.42 23.5748Z" fill="#005D89"/>
                        <path d="M92.9722 25.2056C93.2504 25.3483 93.5657 25.4291 93.8998 25.4291C95.0243 25.4291 95.936 24.516 95.936 23.3902C95.936 23.1557 95.8964 22.93 95.8235 22.7202L95.1638 23.0071C94.4926 23.2965 93.9198 23.7757 93.5161 24.3852L92.9722 25.2056Z" fill="#005D89"/>
                        <path d="M117.191 82.6638C118.886 82.6638 120.261 81.2876 120.261 79.59C120.261 77.8923 118.886 76.5161 117.191 76.5161C115.496 76.5161 114.122 77.8923 114.122 79.59C114.122 81.2876 115.496 82.6638 117.191 82.6638Z" fill="#005D89"/>
                        <path d="M96.7163 82.6638C98.4114 82.6638 99.7855 81.2876 99.7855 79.59C99.7855 77.8923 98.4114 76.5161 96.7163 76.5161C95.0211 76.5161 93.647 77.8923 93.647 79.59C93.647 81.2876 95.0211 82.6638 96.7163 82.6638Z" fill="#005D89"/>
                        <path d="M63.6755 23.9221L63.9326 23.8791L63.9726 23.7216C64.0481 23.4248 64.3056 23.2106 64.6105 23.1898L68.2163 22.946L70.7247 21.9243L69.3245 20.1741C69.0925 19.884 69.1251 19.4638 69.3989 19.2129L72.3531 21.4926C72.6428 21.7164 72.8833 21.9977 73.0595 22.3186L73.9841 24.0058L73.9793 24.0118L87.4875 33.4673L89.8497 29.9227L95.024 34.4952L88.5469 40.2313C88.1628 40.5633 87.5918 40.5573 87.2148 40.2171L72.0101 26.5157L69.0999 26.7928C68.4435 26.8555 67.7812 26.7892 67.1499 26.5983L65.6051 26.1307C65.3428 26.0514 65.2059 25.7627 65.3102 25.5089L64.2098 25.2969C64.0041 25.2572 63.8486 25.0879 63.8268 24.8793L63.8079 24.7011H63.7406C63.5245 24.7011 63.3491 24.5254 63.3491 24.309C63.348 24.1174 63.4864 23.9536 63.6755 23.9221Z" fill="#FCBEB7"/>
                        <path d="M113.259 56.4879L115.668 55.411C115.835 55.3365 115.965 55.1972 116.027 55.0252L116.376 54.0621L115.159 54.3612L114.771 55.1961C114.64 55.477 114.31 55.6033 114.025 55.4806L114.532 53.948C114.627 53.7041 114.81 53.5044 115.044 53.3891L117.588 52.1363L121.977 37.8808L118.624 34.5401L124.09 30.3315L127.286 35.9023C127.726 36.6683 127.779 37.5966 127.431 38.4077L118.56 58.7239L116.8 58.8055C116.588 58.8151 116.388 58.7079 116.28 58.526L116.088 58.2055L115.161 58.2815C114.963 58.2978 114.77 58.2103 114.651 58.0498L114.434 57.7553L114.304 57.7897C113.949 57.8834 113.576 57.7212 113.403 57.3973L112.76 57.6122C112.614 57.165 112.83 56.6798 113.259 56.4879Z" fill="#FCBEB7"/>
                        <path d="M125.42 37.1906C125.42 38.1945 126.136 39.0313 127.085 39.2154L127.432 38.4075C127.78 37.5963 127.727 36.6677 127.287 35.9021L126.891 35.2129C126.04 35.4664 125.42 36.256 125.42 37.1906Z" fill="#F9AFAB"/>
                        <path d="M85.7524 38.8997L87.2133 40.2178C87.5903 40.5583 88.1612 40.5643 88.5453 40.2319L89.9766 38.9942C89.5636 38.2363 88.7607 37.7217 87.8375 37.7217C86.9531 37.7217 86.1791 38.1934 85.7524 38.8997Z" fill="#F9AFAB"/>
                    </svg>
                </div>
                <div class="bwf-h1">Note: You would need to manually create an equivalent Automation in Next-Gen Builder.</div>
                <div class="bwf-modal-content-list">
                    <div class="modal-content-list-heading">Please confirm</div>
                    <div class="bwf-modal-list-wrap">
                        <div class="bwf-modal-list-item">
                            <input id="migratoropt1" class="bwf-modal-list-item-checkbox" type="checkbox" value="1">
                            <span>Yes, I have manually created this automation in the Automation Next-Gen Builder (new version) and no longer need this automation to work in older version.</span>
                        </div>
                        <div class="bwf-modal-list-item">
                            <input id="migratoropt2" class="bwf-modal-list-item-checkbox" type="checkbox" value="1">
                            <span>Yes, I understand that this automation will not trigger for new contacts after I confirm migration.</span>
                        </div>
                        <div class="bwf-modal-list-item">
                            <input id="migratoropt3" class="bwf-modal-list-item-checkbox" type="checkbox" value="1">
                            <span>Yes, I understand that all the Scheduled Tasks for older contacts will still run.</span>
                        </div>
                        <div class="bwf-modal-list-item">
                            <input id="migratoropt4" class="bwf-modal-list-item-checkbox" type="checkbox" value="1">
                            <span>Yes, I understand that I would not be able to edit the Automation after the migration is confirmed.</span>
                        </div>
                    </div>
                </div>
                <div class="bwf-modal-note">
                    <strong>Note: </strong>If you are unsure about migration, click here to read our step-by-step guide to migrating the automation. You can also contact our support for any further
                    questions.
                    <a href="https://funnelkit.com/docs/autonami-2/automations/migrate-from-older-version/?utm_source=WordPress&utm_medium=Automation+Nextgen+Migrate&utm_campaign=Lite+Plugin" target="_blank" class="bwf-a-no-underline">Learn More</a>
                </div>
                <div class="bwf-modal-action">
                    <button type="button" class="components-button is-secondary" style="margin-right: 20px;" id="modal-migrate-cancel">Cancel</button>
                    <button type="button" disabled class="components-button is-primary" id="modal-migrate-confirm">Yes, I confirm migration</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bwfan_izimodal_default" style="display: none" id="modal-autonami-save-as-template">
    <div class="sections">
        <div class="bwfan_save_as_template" data-bwf-action="save_as_template">
            <div class="bwfan_vue_forms" id="part-add-funnel">
                <div class="form-group featured field-input">
                    <label for="title"><?php esc_html( __( 'Template Name', 'wp-marketing-automations' ) ); ?></label>
                    <div class="field-wrap">
                        <div class="wrapper">
                            <input id="save-as-template-title" type="text" name="title" placeholder="<?php echo esc_html( __( 'Enter Template Name', 'wp-marketing-automations' ) ); ?>" class="form-control" value="" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bwfan_template_error_response"></div>
            <fieldset>
                <a href="javascript:void(0)" class="bwfan_save_as_template_save bwfan_btn_blue"><?php echo esc_html( __( 'Add', 'wp-marketing-automations' ) ); ?></a>
            </fieldset>
        </div>
        <div class="bwfan-template-create-success-wrap bwfan-display-none">
            <div class="bwfan-automation-create-success-logo">
                <div class="swal2-icon swal2-success swal2-animate-success-icon" style="display: flex;">
                    <span class="swal2-success-line-tip"></span>
                    <span class="swal2-success-line-long"></span>
                    <div class="swal2-success-ring"></div>
                </div>
            </div>
        </div>
    </div>
</div>