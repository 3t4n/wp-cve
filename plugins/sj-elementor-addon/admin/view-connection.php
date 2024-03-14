<div class="wrap sjea-clear">
   <div id="poststuff">
      <div id="post-body" class="metabox-holder columns-2">
         <div id="postbox-container-1" class="postbox-container">
            <div id="side-sortables" class="meta-box-sortables ui-sortable">
               <div class="postbox ">
                  <button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Contact Plugin Developers</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                  <h2 class="ui-sortable-handle"><span>Contact Plugin Developers</span></h2>
                  <div class="inside">
                     <?php echo SJEaAdminSettings::get_support_form(); ?>
                  </div>
               </div>
            </div>
         </div>
         <div id="postbox-container-2" class="postbox-container">
            <div id="normal-sortables" class="meta-box-sortables ui-sortable">
               <div class="postbox ">
                  <button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Mailer Campaigns</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                  <h2 class="ui-sortable-handle"><span>Mailer Campaigns</span></h2>
                  <div class="inside">
                     <?php 
                     $campaigns = SJEaModelHelper::get_campaigns(); 
                     ?>
                     <table class="sjea-campaign-wrap wp-list-table widefat fixed striped posts">
                        <thead>
                          <tr>
                              <th class="manage-column column-campaign-name column-primary">
                                  <span>Campaign Name</span>
                              </th>
                              <th class="manage-column column-details  column-service">Service</th>
                              <th class="manage-column column-details  column-service-account">Service Account</th>
                              <th class="manage-column column-details  column-action">Action</th>
                          </tr>
                        </thead>

                        <tbody id="the-list">
                        <?php if ( count( $campaigns ) > 0 ) { ?>
                           
                           <?php foreach ( $campaigns as $name => $data ) { ?>
                              <tr class="hentry">
                                 <td class="column-campaign-name column-primary">
                                     <strong><?php echo $name; ?></strong>
                                 </td>
                                 <td class="column-service"><?php echo $data['service']; ?></td>
                                 <td class="column-service-account"><?php echo $data['service_account']; ?></td>
                                 <td class="column-action"><a class="sjea-delete-campaign" data-campaign="<?php echo $name; ?>" href="#">Delete</a></td>
                              </tr>
                           <?php } ?>
                        <?php }else{ ?>
                          <tr class="sjea-no-items">
                              <td class="colspanchange" colspan="5">No Campaign Found.</td>
                          </tr>
                        <?php } ?>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
         <div id="postbox-container-3" class="sjea-new-connections postbox-container">
            <div id="normal-sortables" class="meta-box-sortables ui-sortable">
               <div class="postbox ">
                  <button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Clear Cache</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                  <h2 class="ui-sortable-handle"><span>Create New Campaign</span></h2>
                  <div class="inside">
                     <div class="connection-configration" >
                     <?php 
                        SJEaModelHelper::render_settings_field( 'campaign', array(
                           'row_class'     => 'sjea-campaign-row',
                           'class'         => 'sjea-campaign-input',
                           'type'          => 'text',
                           'label'         => __( 'Campaign Name', 'sjea' ),
                           'help'          => __( 'Used to identify this campaign within the subscriber module and can be anything you like.', 'sjea' ),
                        ));

                        $services = SJEaServices::get_services_data();
                        // Build the select options.
                        $options  = array( '' => __( 'Choose...', 'sjea' ) );
                        
                        foreach ( $services as $key => $service ) {
                           $options[ $key ] = $service['name'];
                        }

                        // Render the service select.
                        SJEaModelHelper::render_settings_field( 'service', array(
                           'row_class'     => 'sjea-service-select-row',
                           'class'         => 'sjea-service-select',
                           'type'          => 'select',
                           'label'         => __( 'Service', 'sjea' ),
                           'options'       => $options,
                        ));
                     ?>
                     </div>
                     <div class="sjea-save-campaign-wrap sjea-hidden">
                        <input type="submit" class="sjea-save-campaign" name="sjea-save-campaign" value="Save Campaign">
                     </div>
                     <div class="sjea-loader sjea-hidden"></div>
                  </div>
               </div>
            </div>
         </div>
         
         <?php wp_nonce_field('sjea-save-connection', 'sjea-save-connection-nonce'); ?>
         <?php //<input type="submit" class="sjea-save-connection" name="sjea-save-connection" value="Save Settings"> ?>
      </div>
   </div>
</div>