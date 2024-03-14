<?php 
 function CPIW_PincodeImport(){
        if(isset($_GET['import']) && $_GET['import'] == 'error') {  ?>
                <div class="notice notice-error is-dismissible">
                     <p><?php echo  esc_html( __( 'Import failed, invalid file extension or something bad happened.' , 'check-pincode-in-woocommerce' ) ); ?></p>
                </div>
            <?php
        }

        if(isset($_GET['import']) && $_GET['import'] == 'success') {
            $records = '';
            if(isset($_GET['records']) && $_GET['records'] != '') {
                $records = sanitize_text_field($_GET['records']);
            } ?>
                <div class="notice notice-success is-dismissible">
                     <p><?php echo  esc_html( __( 'Total Records inserted: '.$records , 'check-pincode-in-woocommerce' ) ); ?></p>
                </div>
            <?php

        } ?>

        <div id="poststuff">
            <div class="postbox">
                <div class="postbox-header">
                    <h2><?php echo __('Bulk Import Post Codes','check-pincode-in-woocommerce');?></h2>
                </div>
                <div class="inside">
                    <form method='post' enctype='multipart/form-data' class="cpiw_import">
                        <?php wp_nonce_field( 'CPIW_add_pincode_action', 'CPIW_add_pincode_field' ); ?>
                        <table class="form-table">
                            <tbody>
                                <tr>
                                    <th>
                                        <label for="<?php echo esc_attr('Pincode'); ?>"><?php echo esc_html( __( 'Import pincode csv', 'check-pincode-in-woocommerce' ) ); ?>
                                        </label>
                                    </th>
                                    <td>
                                        <input type="file" name="import_file" required="" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                        <input type="hidden" name="action" value="cpiw_import_postcodes">
                                        <input type="submit" class="button button-primary" name="pincodeimport" value="Import">
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="<?php echo esc_attr('downloadsamplefile'); ?>"><?php echo esc_html( __( 'Download sample file', 'check-pincode-in-woocommerce' ) ); ?>
                                        </label>
                                    </th>
                                    <td>
                                        <a class="button button-primary" href="<?php echo CPIW_PLUGIN_DIR.'/sample.csv'; ?>" download='sample.csv' class="cpiw_demo_file"><?php echo esc_html( __( 'Download sample file', 'check-pincode-in-woocommerce' ) ); ?></a>
                                        <p class="description"><?php echo esc_html( __( 'This is the sample file of pincodes for csv import.', 'check-pincode-in-woocommerce' ) ); ?></p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
      <?php   
    }
?>