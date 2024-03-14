<div class="wrap">
	<div id="icon-options-general" class="icon32"> </div>
	
    <h2> 
        <?php esc_attr_e( "Google Sheet table view list", "wpgsi" ); ?> 
        <a class="button-secondary" href="<?php echo admin_url( 'admin.php?page=wpgsi-show&action=new' ); ?> " title="<?php esc_attr_e( 'Create new sheet table view' ); ?>"><?php esc_attr_e( 'Create new sheet table view' ); ?></a>
    </h2>

    <div id="wpgsi_google">
        <br>
        <table class="widefat">
            <thead>
                <tr>
                    <th> # </th>
                    <th> Spreadsheet name   </th>
                    <th> Worksheet  name    </th>
                    <th style='text-align: center;vertical-align: middle;'> Sync Frequency     </th>
                    <th style='text-align: center;vertical-align: middle;'> Shortcode          </th>
                    <th style='text-align: center;vertical-align: middle;'> Status             </th>
                </tr>
            </thead>

            <tbody>
                <?php
                    // getting all wpgsiShow custom post
                    $wpgsiShow = get_posts(array( 
                                    'post_type'     => 'wpgsiShow',
                                    'post_status'   => 'any', 
                                    'posts_per_page'=> -1 ,
                                )); 
                    // Looping for creating the row 
                    foreach ($wpgsiShow as $key => $value) {
                        echo ($key % 2 ) ? "<tr>" : "<tr class='alternate'>";
                            # ID
                            echo "<td>";
                                echo esc_html($value->ID);
                                echo"<br>";
                                echo"<a href='".esc_url(admin_url('admin.php?page=wpgsi-show&action=edit&id=').$value->ID)."'> Edit</a>";
                                echo"|";
                                echo" <a href='".esc_url(admin_url('admin.php?page=wpgsi-show&action=delete&id=').$value->ID)."'> Delete</a>";
                            echo"</td>";

                            #Spreadsheet name
                            if(isset($value->ID) AND !empty($value->ID)) {
                                echo "<td>";
                                    echo esc_html(get_post_meta($value->ID, 'spreadsheetName', true));
                                    echo"<br>";
                                    echo"<i style='opacity: 0.5; font-size: .85em;'>" . esc_html(get_post_meta($value->ID, 'spreadsheetID', true)) . "<i>";
                                echo "</td>";
                            } else {
                                echo "<td> -- </td>";
                            }

                            # Worksheet name
                            if(isset($value->ID) AND !is_null($value->ID)) {
                                echo "<td>";
                                    echo esc_html(get_post_meta($value->ID, 'worksheetName', true));
                                    echo"<br>";
                                    echo"<i style='opacity: 0.5; font-size: .85em;'>" . esc_html(get_post_meta($value->ID, 'worksheetID', true)) . "<i>";
                                echo "</td>";
                            } else {
                                echo "<td> -- </td>" ;
                            }

                            # Sync status
                            echo"<td style='text-align: center;vertical-align: middle;' >"; 
                                $syncFrequency = get_post_meta($value->ID, 'syncFrequency', true);
                                # getting last sync information 
                                $lastSyncTime = esc_html(get_post_meta($value->ID, 'lastSyncTime', true));
                                if( $syncFrequency == 'manually'){
                                    echo"<a class='button-secondary' title='last sync " . $lastSyncTime . "' href='admin.php?page=wpgsi-show&action=sync&id=" . esc_html($value->ID) . "' > click to sync Google sheet </a>";
                                } else {
                                    if(isset($this->syncFrequency[$syncFrequency])){
                                        echo esc_html($this->syncFrequency[$syncFrequency]);
                                    }
                                    echo"<br>";
                                    # check and Balance 
                                    if( $lastSyncTime ){ 
                                        echo"<i style='opacity: 0.5; font-size: .85em;'>last sync " . $lastSyncTime ."</i>";
                                    }
                                }
                            echo"</td>";

                            # Showing shotcode 
                            echo"<td style='text-align: center;vertical-align: middle;' title='Copy this shortcode and paste it into Page or Post.'> <code> [wpgsi id=\"". esc_html($value->ID) ."\"] </code> </td>";
                            # change this depends on  post status 
                            if(isset($value->post_status) AND $value->post_status == 'publish'){
                                echo"<td style='text-align: center;vertical-align: middle;' title='Enable and Disable sync and display.' >";
                                    echo"<input onclick='window.location=\"admin.php?page=wpgsi-show&action=status&id=" . esc_html($value->ID) . "\"'  type='checkbox' name='status' checked=checked >";
                                echo"</td>";
                            } else {
                                echo"<td style='text-align: center;vertical-align: middle;' title='Enable and Disable sync and display' >";
                                    echo"<input onclick='window.location=\"admin.php?page=wpgsi-show&action=status&id=" . esc_html($value->ID) . "\"'  type='checkbox' name='status' >"; 
                                echo"</td>";
                            }
                        echo"</tr>";
                    }
                ?>
            </tbody>

            <tfoot>
                <tr>
                    <th> # </th>
                    <th> Spreadsheet name </th>
                    <th> Worksheet  name  </th>
                    <th style='text-align: center;vertical-align: middle;'> Sync Frequency      </th>
                    <th style='text-align: center;vertical-align: middle;'> Shortcode           </th>
                    <th style='text-align: center;vertical-align: middle;'> Status              </th>
                </tr>
            </tfoot>
        </table>
        
    </div>
</div> 
