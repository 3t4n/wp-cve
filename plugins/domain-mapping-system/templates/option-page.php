<?php

if ( !empty($instance) && !empty($dms_fs) && $instance instanceof DMS && $dms_fs instanceof Freemius ) {
    $platform = $instance->platform;
    $isWpcsPlatformTenant = !empty($platform) && $platform instanceof DMS_Wpcs && DMS_Wpcs::isTenant();
    $save_button_disable = DMS_Helper::disableSaveButton( $platform );
    ?>
    <div class="dms-n">
        <form id="dms-map" method="post" action="<?php 
    echo  admin_url( 'admin-post.php' ) ;
    ?>">
            <div class="dms-n-row dms-n-config">
                <h3 class="dms-n-row-header"><?php 
    _e( 'Domain Mapping System Configuration', $instance->plugin_name );
    ?></h3>
                <p class="dms-n-row-subheader">
                    <span class="dms-n-row-subheader-important"><?php 
    _e( 'Important!', $instance->plugin_name );
    ?></span>
                    <span>
                    <?php 
    
    if ( empty($platform) ) {
        printf( __( 'This plugin requires configuration with your DNS host and on your server (cPanel, etc). Please see %1$sour documentation%2$s for configuration requirements.', $instance->plugin_name ), '<a class="dms-n-row-subheader-link" target="_blank" href="https://docs.domainmappingsystem.com">', '</a>' );
    } else {
        $platform->printGeneralNotice();
    }
    
    ?>
                </span>
                </p>
                <div class="dms-n-config-in">
					<?php 
    
    if ( $save_button_disable ) {
        ?>
                        <div class="updated dms-disabled-delay-note">
                            <p><strong><?php 
        _e( 'Important!', $instance->plugin_name );
        ?></strong></p>
                            <p><span><?php 
        echo  __( 'It takes up to 3 minutes to process each domain change. Please wait...' ) ;
        ?> <b
                                            class="timer"><?php 
        echo  $save_button_disable ;
        ?></b></span></p>
                        </div>
					<?php 
    }
    
    // Check and show mdm import
    if ( !empty($instance->mdm_import_instance) && $instance->mdm_import_instance->showImportNote() ) {
        $instance->mdm_import_instance->show();
    }
    // Show admin notices
    DMS_Helper::showSunriseNotices();
    $instance->showAdminNotice();
    
    if ( is_null( $platform ) || !empty($platform) && $platform->showNavigation() ) {
        ?>
                        <nav class="nav-tab-wrapper">
                            <a href="#domains"
                               class="dms nav-tab <?php 
        echo  ( empty($platform) || $platform->showMappingForm() ? 'nav-tab-active' : '' ) ;
        ?>"><?php 
        _e( 'Domain Mapping', $instance->plugin_name );
        ?></a>
							<?php 
        
        if ( !empty($platform) ) {
            // If platform is not WPCS, then not supported by us
            ?>
                                <a href="#hosting-config"
                                   class="dms nav-tab <?php 
            echo  ( !empty($platform) && !$platform->showMappingForm() ? 'nav-tab-active' : '' ) ;
            ?>"><?php 
            _e( 'Hosting Config', $instance->plugin_name );
            ?></a>
							<?php 
        }
        
        ?>
                            <a href="#api"
                               class="dms nav-tab"><?php 
        _e( 'Developer Tools', $instance->plugin_name );
        ?></a>
                        </nav>
					<?php 
    }
    
    ?>
                    <div id="domains" class="dms-n-config-container dms-tab-container">
						<?php 
    if ( !empty($platform) && !$platform->showMappingForm() ) {
        ?>
                            <div class="dms-hide-mapping-overlay"></div>
						<?php 
    }
    ?>
                        <h3 class="dms-n-row-header dms-n-config-header"><?php 
    _e( 'Domains', $instance->plugin_name );
    ?></h3>
                        <!-- New Table START -->
						<?php 
    $options = DMS::getDMSOptions();
    $data = $instance->getData( 1000, 0, false );
    $archive_global_mapping = get_option( 'dms_archive_global_mapping' );
    $woo_shop_global_mapping = get_option( 'dms_woo_shop_global_mapping' );
    $dms_global_parent_page_mapping = get_option( 'dms_global_parent_page_mapping' );
    $shop_mapping_match = false;
    $global_parent_match = false;
    $shop_page_association = ( !empty($woo_shop_global_mapping) ? DMS_Helper::getShopPageAssociation() : false );
    
    if ( !empty($data) ) {
        $data_count = count( $data );
        $values = [];
        $possibleMainDomains = [];
        $primary = null;
        $row_key = 0;
        foreach ( $data as $key => $map ) {
            
            if ( !empty($data[$key - 1]->id) && $map->id != $data[$key - 1]->id ) {
                $shop_mapping_match = false;
                $global_parent_match = false;
            }
            
            
            if ( $key == 0 || !empty($data[$key - 1]) && $map->id != $data[$key - 1]->id ) {
                $domains[] = [
                    'id'   => $map->id,
                    'host' => $map->host . (( !empty($map->path) ? '/' . $map->path : '' )),
                    'main' => $map->main,
                ];
                $values = [];
                $primary = null;
            }
            
            $values[] = $map->value;
            if ( !empty($map->primary) ) {
                $primary = $map->value;
            }
            
            if ( $key == $data_count - 1 || !empty($data[$key + 1]) && $map->id != $data[$key + 1]->id ) {
                $row_key++;
                ?>
                                    <div class="dms-n-config-table">
                                        <button class="dms-n-config-table-dropdown opened">
                                            <i></i>
                                        </button>
                                        <div class="dms-n-config-table-in">
                                            <div class="dms-n-config-table-row first">
                                                <div class="dms-n-config-table-column domain">
                                                    <div class="dms-n-config-table-header">
                                                        <p>
                                                            <span><?php 
                _e( 'Enter Mapped Domain', $instance->plugin_name );
                ?></span>
                                                        </p>
                                                    </div>
                                                    <div class="dms-n-config-table-body">
                                                        <span class="dms-n-config-table-body-scheme"><?php 
                echo  DMS_Helper::getScheme() ;
                ?>://</span>
                                                        <input type="text"
                                                               name="dms_map[domains][<?php 
                echo  $row_key ;
                ?>][host]"
                                                               class="dms-n-config-table-input"
                                                               placeholder="example.com"
                                                               value="<?php 
                echo  $map->host ;
                ?>"/>
                                                        <span class="slash">/</span>
                                                    </div>
                                                </div>
                                                <div class="dms-n-config-table-column subdirectory">
                                                    <div class="dms-n-config-table-header">
                                                        <p>
                                                            <span><?php 
                _e( 'Enter Subdirectory (optional)', $instance->plugin_name );
                ?></span>
															<?php 
                ?>
                                                                <a href="<?php 
                echo  $dms_fs->get_upgrade_url() ;
                ?>">
																	<?php 
                _e( 'Upgrade', $instance->plugin_name );
                ?>
                                                                    &#8594;
                                                                </a>
															<?php 
                ?>
                                                        </p>
                                                    </div>
                                                    <div class="dms-n-config-table-body">
                                                        <input type="text"
                                                               name="dms_map[domains][<?php 
                echo  $row_key ;
                ?>][path]"
															<?php 
                echo  ( !$dms_fs->can_use_premium_code__premium_only() ? 'disabled' : '' ) ;
                ?>
                                                               class="dms-n-config-table-input"
                                                               placeholder="Sub Directory"
                                                               value="<?php 
                echo  $map->path ;
                ?>"/>
                                                        <span class="slash">/</span>
                                                    </div>
                                                </div>
                                                <div class="dms-n-config-table-column content">
                                                    <div class="dms-n-config-table-header">
                                                        <p>
                                                            <span><?php 
                _e( 'Select the Published Content to Map for this Domain.', $instance->plugin_name );
                ?></span>
															<?php 
                ?>
                                                                <a href="<?php 
                echo  $dms_fs->get_upgrade_url() ;
                ?>">
																	<?php 
                _e( 'Upgrade', $instance->plugin_name );
                ?>
                                                                    &#8594;
                                                                </a>
															<?php 
                ?>
                                                        </p>
                                                    </div>
                                                    <div class="dms-n-config-table-body">
                                                        <select class="dms dms-n-config-table-select"
                                                                name="dms_map[domains][<?php 
                echo  $row_key ;
                ?>][mappings][values][]"
                                                                data-index="<?php 
                echo  $row_key ;
                ?>"
                                                                data-placeholder="The choice is yours."
                                                                value="<?php 
                echo  $map->value ;
                ?>"
															<?php 
                echo  ( $dms_fs->can_use_premium_code__premium_only() ? 'multiple' : '' ) ;
                ?>>
                                                            <option></option>
															<?php 
                foreach ( $options as $key_inner => $optgroup ) {
                    ?>
                                                                <optgroup label="<?php 
                    echo  $key_inner ;
                    ?>">
																	<?php 
                    foreach ( $optgroup as $option ) {
                        $id = $option['id'];
                        ?>
                                                                        <option <?php 
                        echo  ( in_array( $id, $values ) ? 'selected' : '' ) ;
                        ?>
                                                                                data-primary="<?php 
                        echo  (int) ($id == $primary) ;
                        ?>"
                                                                                class="level-0"
                                                                                value="<?php 
                        echo  $option['id'] ;
                        ?>">
																			<?php 
                        for ( $i = 0 ;  $i < count( get_post_ancestors( $option['id'] ) ) ;  $i++ ) {
                            echo  '- ' ;
                        }
                        ?>
																			<?php 
                        echo  $option['title'] ;
                        ?>
                                                                        </option>
																		<?php 
                    }
                    ?>
                                                                </optgroup>
																<?php 
                }
                ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="dms-n-config-table-row">
                                                <div class="dms-n-config-table-column code">
                                                    <div class="dms-n-config-table-header">
                                                        <p>
                                                            <span><?php 
                _e( 'Custom HTML Code', $instance->plugin_name );
                ?></span>
															<?php 
                ?>
                                                                <a href="<?php 
                echo  $dms_fs->get_upgrade_url() ;
                ?>">
																	<?php 
                _e( 'Upgrade', $instance->plugin_name );
                ?>
                                                                    &#8594;
                                                                </a>
															<?php 
                ?>
                                                        </p>
                                                    </div>
                                                    <div class="dms-n-config-table-body">
                                                        <input type="text"
                                                               name="dms_map[domains][<?php 
                echo  $row_key ;
                ?>][custom_html]"
                                                               class="dms-n-config-table-input-code"
                                                               placeholder="</Code here>"
                                                               value="<?php 
                echo  esc_html( stripslashes( ( !empty($map->custom_html) ? $map->custom_html : '' ) ) ) ;
                ?>"
															<?php 
                echo  ( !$dms_fs->can_use_premium_code__premium_only() ? 'disabled' : '' ) ;
                ?>/>
                                                    </div>
                                                </div>
                                                <div class="dms-n-config-table-column favicon">
                                                    <div class="dms-n-config-table-header">
                                                        <p>
                                                            <span><?php 
                _e( 'Favicon per Domain', $instance->plugin_name );
                ?></span>
															<?php 
                ?>
                                                                <a href="<?php 
                echo  $dms_fs->get_upgrade_url() ;
                ?>">
																	<?php 
                _e( 'Upgrade', $instance->plugin_name );
                ?>
                                                                    &#8594;
                                                                </a>
															<?php 
                ?>
                                                        </p>
                                                    </div>
                                                    <div class="dms-n-config-table-body">
                                                        <div class="dms-n-config-table-favicon">
															<?php 
                
                if ( !empty($map->attachment_id) ) {
                    ?>
                                                                <img class="favicon <?php 
                    echo  ( !$dms_fs->can_use_premium_code__premium_only() ? 'disabled' : '' ) ;
                    ?>"
                                                                     src="<?php 
                    echo  wp_get_attachment_image_url( $map->attachment_id ) ;
                    ?>"
                                                                     alt=""
                                                                     width="25px">
															<?php 
                }
                
                ?>
                                                            <input type="button" name="upload-btn"
                                                                   class="<?php 
                echo  ( !$dms_fs->can_use_premium_code__premium_only() ? 'disabled' : '' ) ;
                ?> upload upload-btn"
                                                                   id="<?php 
                echo  $row_key ;
                ?>"
                                                                   value="<?php 
                echo  __( 'Upload Image', $instance->plugin_name ) ;
                ?>">
															<?php 
                ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="dms-n-config-table-delete">
                                            <i></i>
                                        </button>
										<?php 
                if ( $isWpcsPlatformTenant ) {
                    $platform->drawSetTenantMainDomainButton( $map->id, $save_button_disable );
                }
                ?>
                                        <div style="display: none">
                                            <input type="hidden" class="dms-map-id"
                                                   name="dms_map[domains][<?php 
                echo  $row_key ;
                ?>][id]"
                                                   value="<?php 
                echo  $map->id ;
                ?>">
                                        </div>
                                    </div>
									<?php 
            }
        
        }
    }
    
    ?>
                        <!-- New Table END -->
                        <div class="dms-n-row-footer">
							<?php 
    
    if ( empty($platform) || !$platform instanceof DMS_Wpcs ) {
        ?>
                                <div class="dms-n-row-add">
                                    <input type="hidden" id="dms-domains-to-remove" name="dms_map[domains_to_remove]"
                                           value=""
                                           style="display: none">
                                    <a class="dms-add-row" href="#">
										<?php 
        _e( '+ Add Domain Map Entry', $instance->plugin_name );
        ?>
                                    </a>
                                </div>
								<?php 
    }
    
    ?>
                        </div>
                        <div class="dms-n-post-types-footer">
                            <div class="dms-n-row-submit">
                                <input type="submit" <?php 
    echo  ( $save_button_disable ? 'disabled data-disabled_delay="' . $save_button_disable . '"' : '' ) ;
    ?> value="Save" class="dms-submit">
                            </div>
                        </div>
                        <div id="dms-default-select" style="display: none">
                            <select <?php 
    echo  ( $dms_fs->can_use_premium_code__premium_only() ? 'multiple' : '' ) ;
    ?>
                                    data-placeholder="<?php 
    _e( 'The choice is yours.', $instance->plugin_name );
    ?>">
                                <option></option>
								<?php 
    foreach ( $options as $key => $optgroup ) {
        ?>
                                    <optgroup label="<?php 
        echo  $key ;
        ?>">
										<?php 
        foreach ( $optgroup as $option ) {
            ?>
                                            <option class="level-0" data-primary="0"
                                                    value="<?php 
            echo  $option['id'] ;
            ?>">
												<?php 
            for ( $i = 0 ;  $i < count( get_post_ancestors( $option['id'] ) ) ;  $i++ ) {
                echo  '- ' ;
            }
            ?>
												<?php 
            echo  $option['title'] ;
            ?>
                                            </option>
											<?php 
        }
        ?>
                                    </optgroup>
									<?php 
    }
    ?>
                            </select>
                        </div>
                    </div>
                    <div id="api" class="dms-tab-container">
                        <ul class="dms-n-api">
                            <li class="dms-n-row-subheader">
                                <a class="dms-n-row-subheader-link"
                                   href="https://docs.domainmappingsystem.com/features/rest-api"
                                   target="_blank"><?php 
    echo  __( 'See our documentation for more', $instance->plugin_name ) ;
    ?></a>
                            </li>
                        </ul>
                    </div>
					<?php 
    
    if ( is_null( $platform ) || !empty($platform) && $platform->showConfigForm() ) {
        ?>
                        <div id="hosting-config" class="dms-tab-container dms-hosting-platform-container">
                            <h3 class="dms-n-row-header dms-n-config-header"><?php 
        _e( 'Allow Domain Mapping System to automatically manage Addon or Alias Domains in your hosting platform by setting up the configuration details below.', $instance->plugin_name );
        ?>
                            </h3>
                            <p class="dms-n-row-subheader"><?php 
        echo  __( 'Detected Hosting Platform', $instance->plugin_name ) ;
        ?></p>
                            <div class="updated">
                                <p><?php 
        echo  ( !empty($platform) ? $platform->getName() : __( 'Hosting Platform Not Yet Integrated - Manual Configuration Required', $instance->plugin_name ) ) ;
        ?></p>
                            </div>
                            <p class="dms-n-row-subheader"><?php 
        echo  sprintf( __( 'We are currently building integrations for multiple hosting platforms. Check currently %s Supported Hosting Platforms %s or contact us at support@domainmappingsystem.com to request yours.', $instance->plugin_name ), '<a class="dms-n-row-subheader-link" target="_blank" href="https://docs.domainmappingsystem.com/faqs/what-hosting-companies-are-supported">', '</a>' ) ;
        ?></p>
							<?php 
        if ( !empty($platform) ) {
            $platform->drawForm();
        }
        ?>
                        </div>
					<?php 
    }
    
    if ( $isWpcsPlatformTenant ) {
        $platform->drawSetTenantDomainAsMainForm();
    }
    ?>
                </div>
            </div>
            <div class="dms-n-row dms-n-additional">
				<?php 
    if ( !empty($platform) && !$platform->showMappingForm() ) {
        ?>
                    <div class="dms-hide-mapping-overlay"></div>
				<?php 
    }
    ?>
                <div class="dms-n-additional-accordion opened">
                    <div class="dms-n-additional-accordion-header">
                        <h3>
                            <span><?php 
    _e( 'Additional Options', $instance->plugin_name );
    ?></span>
                        </h3>
                        <i></i>
                    </div>
                    <div class="dms-n-additional-accordion-body">
                        <ul>
                            <li>
                                <div class="dms-n-additional-accordion-li">
                                    <div class="dms-n-additional-accordion-checkbox">
                                        <input
                                                class="checkbox"
                                                type="checkbox"
											<?php 
    echo  ( !$dms_fs->can_use_premium_code__premium_only() ? 'disabled=disabled' : '' ) ;
    ?>
                                                id="dms_enable_query_strings"
                                                name="dms_enable_query_strings"
											<?php 
    $opt = get_option( 'dms_enable_query_strings' );
    if ( $opt === 'on' && $dms_fs->can_use_premium_code__premium_only() ) {
        echo  "checked=\"checked\"" ;
    }
    ?>
                                        />
                                    </div>
                                    <div class="dms-n-additional-accordion-content">
                                        <span class="label">
                                            <?php 
    _e( 'Support for query string parameters (e.g. - UTM, etc).', $instance->plugin_name );
    ?>
                                        </span>
										<?php 
    ?>
                                            <a class="upgrade" href="<?php 
    echo  $dms_fs->get_upgrade_url() ;
    ?>">
												<?php 
    _e( 'Upgrade', $instance->plugin_name );
    ?>&#8594;
                                            </a>
										<?php 
    ?>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="dms-n-additional-accordion-li">
                                    <div class="dms-n-additional-accordion-checkbox">
                                        <input
                                                class="checkbox"
                                                type="checkbox"
											<?php 
    echo  ( !$dms_fs->can_use_premium_code__premium_only() ? 'disabled=disabled' : '' ) ;
    ?>
                                                id="dms_force_site_visitors"
                                                name="dms_force_site_visitors"
											<?php 
    $opt = get_option( 'dms_force_site_visitors' );
    if ( $opt === 'on' && $dms_fs->can_use_premium_code__premium_only() ) {
        echo  "checked=\"checked\"" ;
    }
    ?>
                                        />
                                    </div>
                                    <div class="dms-n-additional-accordion-content">
                                        <span class="label">
                                            <?php 
    _e( 'Force site visitors to see only mapped domains of a page (e.g. - disallow visitors to see the primary domain of a page).', $instance->plugin_name );
    ?>
                                        </span>
										<?php 
    ?>
                                            <a class="upgrade" href="<?php 
    echo  $dms_fs->get_upgrade_url() ;
    ?>">
												<?php 
    _e( 'Upgrade', $instance->plugin_name );
    ?>&#8594;
                                            </a>
										<?php 
    ?>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="dms-n-additional-accordion-li">
                                    <div class="dms-n-additional-accordion-checkbox">
                                        <input
                                                class="checkbox"
                                                type="checkbox"
											<?php 
    echo  ( !$dms_fs->can_use_premium_code__premium_only() ? 'disabled=disabled' : '' ) ;
    ?>
                                                id="dms_global_mapping"
                                                name="dms_global_mapping"
											<?php 
    $opt = get_option( 'dms_global_mapping' );
    if ( $opt === 'on' && $dms_fs->can_use_premium_code__premium_only() ) {
        echo  "checked=\"checked\"" ;
    }
    ?>
                                        />
                                    </div>
                                    <div class="dms-n-additional-accordion-content">
                                        <span class="label">
                                            <?php 
    _e( 'Enable Global Domain Mapping (all pages will be served for your mapped domains).', $instance->plugin_name );
    ?>
                                        </span>
                                        <span class="dms-main-domain-container">
                                            <?php 
    
    if ( !empty($domains) && count( $domains ) > 1 ) {
        ?>
	                                            <?php 
        _e( 'Select the domain [+path] to serve for all unmapped pages:', $instance->plugin_name );
        ?>
                                                <select name="dms_main_domain"
                                                        class="dms-main-domain" <?php 
        echo  ( !$dms_fs->can_use_premium_code__premium_only() ? 'disabled=disabled' : '' ) ;
        ?>>
                                                        <option value="0"><?php 
        echo  __( 'Select domain', $instance->plugin_name ) ;
        ?></option>
                                                        <?php 
        foreach ( $domains as $domain ) {
            if ( !empty($domain['host']) && !empty($domain['path']) && !empty($domain['values']) ) {
            }
            ?>
                                                                <option value="<?php 
            echo  $domain['host'] ;
            ?>" <?php 
            echo  ( $domain['main'] ? 'selected' : '' ) ;
            ?> ><?php 
            echo  $domain['host'] ;
            ?></option>
                                                        <?php 
        }
        ?>
                                                    </select>
                                            <?php 
    }
    
    ?>
                                        </span>
										<?php 
    ?>
                                            <a class="upgrade" href="<?php 
    echo  $dms_fs->get_upgrade_url() ;
    ?>">
												<?php 
    _e( 'Upgrade', $instance->plugin_name );
    ?>&#8594;
                                            </a>
										<?php 
    ?>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="dms-n-additional-accordion-li">
                                    <div class="dms-n-additional-accordion-checkbox">
                                        <input
                                                class="checkbox"
                                                type="checkbox"
											<?php 
    echo  ( !$dms_fs->can_use_premium_code__premium_only() ? 'disabled=disabled' : '' ) ;
    ?>
                                                id="dms_archive_global_mapping"
                                                name="dms_archive_global_mapping"
											<?php 
    if ( $archive_global_mapping === 'on' && $dms_fs->can_use_premium_code__premium_only() ) {
        echo  "checked=\"checked\"" ;
    }
    ?>
                                        />
                                    </div>
                                    <div class="dms-n-additional-accordion-content">
                                        <span class="label">
                                            <?php 
    _e( 'Global Archive Mapping - All posts within an archive or category automatically map to the specified domain (archive mappings override Global Domain Mapping).', $instance->plugin_name );
    ?>
                                        </span>
										<?php 
    ?>
                                            <a class="upgrade" href="<?php 
    echo  $dms_fs->get_upgrade_url() ;
    ?>">
												<?php 
    _e( 'Upgrade', $instance->plugin_name );
    ?>&#8594;
                                            </a>
										<?php 
    ?>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="dms-n-additional-accordion-li">
                                    <div class="dms-n-additional-accordion-checkbox">
                                        <input
                                                class="checkbox"
                                                type="checkbox"
											<?php 
    echo  ( !$dms_fs->can_use_premium_code__premium_only() ? 'disabled=disabled' : '' ) ;
    ?>
                                                id="dms_woo_shop_global_mapping"
                                                name="dms_woo_shop_global_mapping"
											<?php 
    $opt = get_option( 'dms_woo_shop_global_mapping' );
    if ( $opt === 'on' && $dms_fs->can_use_premium_code__premium_only() ) {
        echo  "checked=\"checked\"" ;
    }
    ?>
                                        />
                                    </div>
                                    <div class="dms-n-additional-accordion-content">
                                        <span class="label">
                                            <?php 
    _e( 'Global Product Mapping - When you map a domain to the Shop page, all products on your site will be available through that domain.', $instance->plugin_name );
    ?>
                                        </span>
										<?php 
    ?>
                                            <a class="upgrade" href="<?php 
    echo  $dms_fs->get_upgrade_url() ;
    ?>">
												<?php 
    _e( 'Upgrade', $instance->plugin_name );
    ?>&#8594;
                                            </a>
										<?php 
    ?>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="dms-n-additional-accordion-li">
                                    <div class="dms-n-additional-accordion-checkbox">
                                        <input
                                                class="checkbox"
                                                type="checkbox"
											<?php 
    echo  ( !$dms_fs->can_use_premium_code__premium_only() ? 'disabled=disabled' : '' ) ;
    ?>
                                                id="dms_global_parent_page_mapping"
                                                name="dms_global_parent_page_mapping"
											<?php 
    $opt = get_option( 'dms_global_parent_page_mapping' );
    if ( $opt === 'on' && $dms_fs->can_use_premium_code__premium_only() ) {
        echo  "checked=\"checked\"" ;
    }
    ?>
                                        />
                                    </div>
                                    <div class="dms-n-additional-accordion-content">
                                        <span class="label">
                                            <?php 
    _e( 'Global Parent Page Mapping - Automatically map all pages attached to a Parent Page.', $instance->plugin_name );
    ?>
                                        </span>
										<?php 
    ?>
                                            <a class="upgrade" href="<?php 
    echo  $dms_fs->get_upgrade_url() ;
    ?>">
												<?php 
    _e( 'Upgrade', $instance->plugin_name );
    ?>&#8594;
                                            </a>
										<?php 
    ?>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="dms-n-additional-accordion-li">
                                    <div class="dms-n-additional-accordion-checkbox">
                                        <input
                                                class="checkbox"
                                                type="checkbox"
											<?php 
    echo  ( !$dms_fs->can_use_premium_code__premium_only() ? 'disabled=disabled' : '' ) ;
    ?>
                                                id="dms_rewrite_urls_on_mapped_page"
                                                name="dms_rewrite_urls_on_mapped_page"
											<?php 
    $opt = get_option( 'dms_rewrite_urls_on_mapped_page' );
    if ( $opt === 'on' && $dms_fs->can_use_premium_code__premium_only() ) {
        echo  "checked=\"checked\"" ;
    }
    ?>
                                        />
                                    </div>
                                    <div class="dms-n-additional-accordion-content">
                                        <span class="label">
                                            <?php 
    _e( 'Rewrite all URLs on a mapped domain with:', $instance->plugin_name );
    $rewrite_scenario = get_option( 'dms_rewrite_urls_on_mapped_page_sc' );
    ?>
                                            <select name="dms_rewrite_urls_on_mapped_page_sc"
                                                    <?php 
    echo  ( !$dms_fs->can_use_premium_code__premium_only() ? 'disabled=disabled' : '' ) ;
    ?>>
                                                    <option value="1" <?php 
    echo  ( $rewrite_scenario === '1' && $dms_fs->can_use_premium_code__premium_only() ? 'selected' : '' ) ;
    ?>><?php 
    echo  __( 'Global Rewriting', $instance->plugin_name ) ;
    ?></option>
                                                    <option value="2" <?php 
    echo  ( $rewrite_scenario === '2' && $dms_fs->can_use_premium_code__premium_only() ? 'selected' : '' ) ;
    ?>><?php 
    echo  __( 'Selective Rewriting', $instance->plugin_name ) ;
    ?></option>
                                                </select>
                                                <?php 
    echo  sprintf(
        __( '%s Warning: %s Global Rewriting may create dead links if you haven’t mapped internally linked pages properly. Read more in our %s Documentation > %s', $instance->plugin_name ),
        '<strong>',
        '</strong>',
        '<a class="info" href="https://docs.domainmappingsystem.com/features/url-rewriting" target="_blank" >',
        '</a>'
    ) ;
    ?>
                                            <?php 
    ?>
                                                <a class="upgrade" href="<?php 
    echo  $dms_fs->get_upgrade_url() ;
    ?>">
                                                    <?php 
    _e( 'Upgrade', $instance->plugin_name );
    ?>&#8594;
                                                </a>
                                            <?php 
    ?>
                                        </span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="dms-n-additional-accordion-li">
                                    <strong><?php 
    _e( 'Yoast SEO', $instance->plugin_name );
    ?></strong>
                                </div>
                            </li>
                            <li>
                                <div class="dms-n-additional-accordion-li">
                                    <div class="dms-n-additional-accordion-checkbox">
                                        <input
                                                class="checkbox"
                                                type="checkbox"
											<?php 
    echo  ( !$dms_fs->can_use_premium_code__premium_only() ? 'disabled=disabled' : '' ) ;
    ?>
                                                id="dms_seo_options_per_domain"
                                                name="dms_seo_options_per_domain"
											<?php 
    $opt = get_option( 'dms_seo_options_per_domain' );
    if ( $opt === 'on' && $dms_fs->can_use_premium_code__premium_only() ) {
        echo  'checked="checked"' ;
    }
    ?>
                                        />
                                    </div>
                                    <div class="dms-n-additional-accordion-content">
                                        <span class="label">
                                            <?php 
    _e( 'Duplicate SEO Options - Each mapped page will have duplicated Yoast SEO options for each mapped domain tied to it.', $instance->plugin_name );
    ?>
                                        </span>
										<?php 
    ?>
                                            <a class="upgrade" href="<?php 
    echo  $dms_fs->get_upgrade_url() ;
    ?>">
												<?php 
    _e( 'Upgrade', $instance->plugin_name );
    ?>&#8594;
                                            </a>
										<?php 
    ?>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="dms-n-additional-accordion-li">
                                    <div class="dms-n-additional-accordion-checkbox">
                                        <input
                                                class="checkbox"
                                                type="checkbox"
											<?php 
    echo  ( !$dms_fs->can_use_premium_code__premium_only() ? 'disabled=disabled' : '' ) ;
    ?>
                                                id="dms_seo_sitemap_per_domain"
                                                name="dms_seo_sitemap_per_domain"
											<?php 
    $opt = get_option( 'dms_seo_sitemap_per_domain' );
    if ( $opt === 'on' && $dms_fs->can_use_premium_code__premium_only() ) {
        echo  "checked=\"checked\"" ;
    }
    ?>
                                        />
                                    </div>
                                    <div class="dms-n-additional-accordion-content">
                                        <span class="label">
                                            <?php 
    _e( 'Sitemap per Domain - Dynamically generate a unique sitemap per domain.', $instance->plugin_name );
    ?>
                                        </span>
										<?php 
    ?>
                                            <a class="upgrade" href="<?php 
    echo  $dms_fs->get_upgrade_url() ;
    ?>">
												<?php 
    _e( 'Upgrade', $instance->plugin_name );
    ?>&#8594;
                                            </a>
										<?php 
    ?>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="dms-n-additional-accordion-li del">
                                    <div class="dms-n-additional-accordion-checkbox">
                                        <input
                                                class="checkbox"
                                                type="checkbox"
                                                id="dms_delete_upon_uninstall"
                                                name="dms_delete_upon_uninstall"
					                        <?php 
    $opt = get_option( 'dms_delete_upon_uninstall' );
    if ( $opt === 'on' ) {
        echo  'checked="checked"' ;
    }
    ?>
                                        />
                                    </div>
                                    <div class="dms-n-additional-accordion-content">
                                        <span class="label">
                                            <?php 
    _e( 'Delete plugin, data, and settings (full removal) when uninstalling.', $instance->plugin_name );
    ?>
                                            <?php 
    echo  sprintf( __( '%s Warning: %s This action is irreversible.', $instance->plugin_name ), '<strong>', '</strong>' ) ;
    ?>
                                        </span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="dms-n-row-submit">
                            <input type="submit" <?php 
    echo  ( $save_button_disable ? 'disabled data-disabled_delay="' . $save_button_disable . '"' : '' ) ;
    ?>
                                   value="<?php 
    _e( 'Save', $instance->plugin_name );
    ?>"
                                   class="dms-submit"/>
                        </div>
                        <input name="action" value="save_dms_mapping" type="hidden">
						<?php 
    wp_nonce_field( 'save_dms_mapping_action', 'save_dms_mapping_nonce' );
    ?>
                    </div>
                </div>
            </div>
            <div class="dms-n-row dms-n-post-types">
                <h3 class="dms-n-row-header"><?php 
    _e( 'Available Post Types', $instance->plugin_name );
    ?></h3>
                <p class="dms-n-row-subheader"><?php 
    _e( 'Select the Post Types or Custom Taxonomies that should be available for Domain Mapping System.', $instance->plugin_name );
    ?></p>
                <div class="dms-n-post-types-in">
                    <div class="dms-n-post-types-container">
                        <label class="dms-n-post-types-label <?php 
    echo  ( get_option( 'dms_use_page' ) == 'on' ? 'checked' : '' ) ;
    ?>"
                               for="dms_use_page">
                            <input class="dms-n-post-types-checkbox" name="dms_use_page" type="checkbox" id="dms_use_page"
								<?php 
    echo  ( get_option( 'dms_use_page' ) == 'on' ? ' checked="checked"' : '' ) ;
    ?>>
                            <span>
                                <?php 
    _e( 'Pages', $instance->plugin_name );
    ?>
                            </span>
                        </label>
                        <label class="dms-n-post-types-label <?php 
    echo  ( get_option( 'dms_use_post' ) == 'on' ? 'checked' : '' ) ;
    ?>"
                               for="dms_use_post">
                            <input class="dms-n-post-types-checkbox" name="dms_use_post" type="checkbox" id="dms_use_post"
								<?php 
    echo  ( get_option( 'dms_use_post' ) == 'on' ? ' checked="checked"' : '' ) ;
    ?>>
                            <span>
                                <?php 
    _e( 'Posts', $instance->plugin_name );
    ?>
                            </span>
                        </label>
                        <label class="dms-n-post-types-label <?php 
    echo  ( get_option( 'dms_use_categories' ) == 'on' ? 'checked' : '' ) ;
    ?>"
                               for="dms_use_categories">
                            <input class="dms-n-post-types-checkbox" name="dms_use_categories" type="checkbox" id="dms_use_categories"
								<?php 
    echo  ( get_option( 'dms_use_categories' ) == 'on' ? ' checked="checked"' : '' ) ;
    ?>>
                            <span>
                        <?php 
    _e( 'Blog Categories', $instance->plugin_name );
    ?>
                    </span>
                        </label>
						<?php 
    $types = DMS::getCustomPostTypes();
    foreach ( $types as $type ) {
        $value = get_option( "dms_use_{$type['name']}" );
        ?>
                            <label class="dms-n-post-types-label <?php 
        echo  ( $value == "on" ? ' checked' : '' ) ;
        ?>" for="dms_use_<?php 
        echo  $type['name'] ;
        ?>">
                                <input class="dms-n-post-types-checkbox" name="dms_use_<?php 
        echo  $type['name'] ;
        ?>" type="checkbox" id="dms_use_<?php 
        echo  $type['name'] ;
        ?>"
									<?php 
        echo  ( $value == 'on' ? 'checked="checked"' : '' ) ;
        ?>>
                                <span><?php 
        echo  $type["label"] ;
        ?></span>
                            </label>
							<?php 
        
        if ( !empty($type['has_archive']) ) {
            $value = get_option( "dms_use_{$type['name']}_archive" );
            ?>
                                <label class="dms-n-post-types-label <?php 
            echo  ( $value == "on" ? ' checked' : '' ) ;
            ?>" for="dms_use_<?php 
            echo  $type['name'] ;
            ?>_archive">
                                    <input class="dms-n-post-types-checkbox" name="dms_use_<?php 
            echo  $type['name'] ;
            ?>_archive" type="checkbox"
                                           id="dms_use_<?php 
            echo  $type['name'] ;
            ?>_archive" <?php 
            echo  ( $value == 'on' ? 'checked="checked"' : '' ) ;
            ?>>
                                    <span><?php 
            echo  $type['label'] ;
            ?><strong><?php 
            echo  __( 'Archive', $instance->plugin_name ) ;
            ?></strong></span>
                                </label>
								<?php 
        }
    
    }
    ?>
                    </div>
                    <div class="dms-n-post-types-footer">
                        <div class="dms-n-row-submit">
                            <input type="submit" class="dms-submit"
								<?php 
    echo  ( $save_button_disable ? 'disabled data-disabled_delay="' . $save_button_disable . '"' : '' ) ;
    ?>
                                   value="<?php 
    _e( 'Save', $instance->plugin_name );
    ?>"/>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
<?php 
}
