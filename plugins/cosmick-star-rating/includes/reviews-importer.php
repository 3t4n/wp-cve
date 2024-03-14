<?php

add_action( 'admin_menu', function() {
    add_submenu_page( 'edit.php?post_type=reviews', 'Reviews Importer', 'Import Reviews', 'manage_options', 'reviews-importer-page', 'csr_reviews_importer_page_fun' );
});

function csr_reviews_importer_page_fun() {
    
    $log_data = "";

    if (isset($_POST['csr_file_upload_btn'])) {
        $target_file = WP_CONTENT_DIR .'/uploads/'. basename($_FILES["csr_file_upload"]["name"]);
        $file_extension = strtolower( pathinfo($target_file,PATHINFO_EXTENSION) );
        
        if( $file_extension == 'csv' && 'text/csv' == $_FILES["csr_file_upload"]["type"] ){
            
            if ( move_uploaded_file($_FILES["csr_file_upload"]["tmp_name"], $target_file) ) {
                $log_data = csr_reviews_start_importing_fun( $target_file );
            }else{
                $log_data = "Failed to upload file.!!";
            }
        }else{
            $log_data = "Invalid file uploaded. Please upload a valid CSV file!";
        }
    }

//    $file_suffix = date("m");
//    $file_path = WP_CONTENT_DIR . '/petland-blog/sync-log-' . $file_suffix . '.log';
//
//    $log_data_ = @file_get_contents($file_path);
//    $log_data = nl2br($log_data_);
//    $log_data = "";
    ?>
    <div class="wrap">
        <h1>Reviews Importer</h1>
        <div id="poststuff">
            <p>
                <strong><span style="color: red;">IMPORTANT:</span> Please upload the reviews using the test template to ensure that you are accurately using the columns. Any format different from the test template will fail.</strong>
                <a href="<?= plugins_url('template-reviews.csv', __FILE__ ) ?>">Test Template</a>
            </p>
            <form novalidate="novalidate" action="" method="post" enctype="multipart/form-data">
                <input type="file" name="csr_file_upload"  />
                <input type="submit" value="Start Importing" class="button button-primary" id="csr_file_upload_btn" name="csr_file_upload_btn" />            
            </form>
            
        </div>
        <div class="row" style="border:3px dashed #0085ba;margin-top: 20px;background: #fff;padding: 10px; width: 600px;height: 400px;overflow: auto;">
            <?php echo $log_data; ?>
        </div>
    </div>
    <?php
}

function csr_reviews_start_importing_fun( $reviews_import_file ) {
    
    global $wpdb;
    ob_start();
    
    $file = fopen( $reviews_import_file, "r" );

    if ( $file === FALSE ) {
        print( "Not a valid file!!!");        
    }else{

        $index = 0;
        $today = date('Y-m-d H:i:s');

        while (!feof($file)) {

            $review_row = fgetcsv($file);

            if (!isset($review_row[0])) {
                continue;
            }

            $reviewed_by = $review_row[0];
            $date = $review_row[1];
            $reviewed_message = $review_row[3];
            $customer_review = floatval($review_row[2]);

            $review_date = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $date)));

            $reviewed_by = sanitize_text_field($reviewed_by);
            $reviewed_message = sanitize_textarea_field($reviewed_message);

            if ($index > 0 && '﻿Author' != $reviewed_by && !empty($reviewed_by) && !empty($customer_review)) {

                echo $index . ' >>> Imported review: ';
                echo $reviewed_by . ' | ';
                echo $customer_review . ' | ' . $review_date;
                echo '<br>';

                //$reviewed_message = addslashes($reviewed_message);
                //$reviewed_message = htmlentities($reviewed_message);



                $customer_review_post = array(
                    'post_title' => $reviewed_by,
                    'post_content' => $reviewed_message,
                    'post_status' => 'publish',
                    'post_type' => 'reviews',
                    'post_author' => 1,
                    'post_date' => $review_date
                );


                $review_ID = wp_insert_post($customer_review_post);

                if ($review_ID > 0) {

                    update_post_meta($review_ID, 'review-import', $today);

                    $wpdb->insert($wpdb->prefix . 'csr_votes', array(
                        'post_id' => $review_ID,
                        'reviewer_id' => 1,
                        'overall_rating' => number_format($customer_review, 1),
                        'number_of_votes' => 0,
                        'sum_votes' => 0.0,
                        'review_type' => 'Other'
                            )
                    );
                } else {

                    if (is_wp_error($review_ID)) {
                        $error_string = $review_ID->get_error_message();
                    }
                    echo "<p>Index# {$index} Failed to import |  {$error_string}</p>";
                }
            } else {
                if($index > 0 )
                    echo $index . ' >>> '.$reviewed_by . ' - <strong>FAILED</strong>'.'<br>';
            }

            $index++;
        }
    }
    fclose($file);
    
    $output = ob_get_contents();
    ob_end_clean();
    
    @unlink( $reviews_import_file );
    
    return $output;
}
