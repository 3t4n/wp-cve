<?php
function sbs_6310_export_full_plugin() {
    if( !ini_get('allow_url_fopen') ) {
       echo "<p style='color: green; font-size: 14px;'>
       In your cPanel, default allow_url_fopen is not enable. You need to enable. Please contact with hosting providers or 	follow the below URL. <br />
       <a href='https://www.youtube.com/watch?v=tUW6CkZEW8k'>How to Enable Allow_url_fopen in cPanel</a>
       </p>";
       return;
     }
    global $wpdb;
    $category_table = $wpdb->prefix . 'sbs_6310_category';
    
    $item_table = $wpdb->prefix . 'sbs_6310_item';
    $style_table = $wpdb->prefix . 'sbs_6310_style';
 
    $path = wp_upload_dir();
    $file = $path['path'] . '/team-skill-members-with-slider-backup.csv'; 
    $fp = fopen( $file, "w" ); 

    //member Table
    $data = $wpdb->get_results('SELECT * FROM ' . $item_table . ' ORDER BY id DESC', ARRAY_A);
    foreach ( $data as $selectedData ) {
       $sqlData = [
             "insert into {$item_table} set ", 
             "id='".esc_sql($selectedData['id'])."', ", 
             "name='".esc_sql($selectedData['name'])."', ", 
             "designation='".esc_sql($selectedData['designation'])."', ", 
             "profile_details_type='".esc_sql($selectedData['profile_details_type'])."', ", 
             "profile_url='".esc_sql($selectedData['profile_url'])."', ", 
             "open_new_tab='".esc_sql($selectedData['open_new_tab'])."', ", 
             "profile_details='".esc_sql($selectedData['profile_details'])."', ", 
             "effect='".esc_sql($selectedData['effect'])."', ", 
             "image='".esc_sql($selectedData['image'])."', ", 
             "image_hover='".esc_sql($selectedData['image_hover'])."', ", 
             "iconids='".esc_sql($selectedData['iconids'])."', ", 
             "iconurl='".esc_sql($selectedData['iconurl'])."', ", 
             "skills='".esc_sql($selectedData['skills'])."', ", 
             "contacts='".esc_sql($selectedData['contacts'])."', ", 
             "category='".esc_sql($selectedData['category'])."'"
          ];
       fputcsv($fp, $sqlData);	
    }
 
    //style Table
    $data = $wpdb->get_results('SELECT * FROM ' . $style_table . ' ORDER BY id DESC', ARRAY_A);
    foreach ( $data as $selectedData ) {
       $sqlData = [
          "insert into {$style_table} set ", 
          "id='".esc_sql($selectedData['id'])."', ", 
          "name='".esc_sql($selectedData['name'])."', ", 
          "style_name='".esc_sql($selectedData['style_name'])."', ", 
          "css='".esc_sql($selectedData['css'])."', ", 
          "memberid='".esc_sql($selectedData['memberid'])."', ", 
          "grid_order='".esc_sql($selectedData['grid_order'])."', ", 
          "template_order='".esc_sql($selectedData['template_order'])."'"
       ];
       fputcsv($fp, $sqlData);				
    }
    fclose($fp);
    echo '<a href="'.$path['url'].'/team-skill-members-with-slider-backup.csv" target="_blank" id="export-service-box-plugin">Download</a>';
 }
 
 function sbs_6310_import_full_plugin($url) {
    if( !ini_get('allow_url_fopen') ) {
       echo "<p style='color: green; font-size: 14px;'>
       In your cPanel, default allow_url_fopen is not enable. You need to enable. Please contact with hosting providers or 	follow the below URL. <br />
       <a href='https://www.youtube.com/watch?v=tUW6CkZEW8k'>How to Enable Allow_url_fopen in cPanel</a>
       </p>";
       return;
     }
    global $wpdb;
    
    $item_table = $wpdb->prefix . 'sbs_6310_item';
    $style_table = $wpdb->prefix . 'sbs_6310_style';
    $wpdb->query("TRUNCATE {$item_table}");
    $wpdb->query("TRUNCATE {$style_table}");
 
    $file = fopen($url, "r");
    while(! feof($file)) {
       $list  = fgetcsv($file);
       $list = implode('', $list);
       $wpdb->query($list);
    }
    fclose($file);
    echo "<p style='color: green; font-size: 14px;'>Data import successfully.</p>";
 }
 
 function sbs_6310_import_team_member($url) {
    global $wpdb;
    if( !ini_get('allow_url_fopen') ) {
       echo "<p style='color: green; font-size: 14px;'>
       In your cPanel, default allow_url_fopen is not enable. You need to enable. Please contact with hosting providers or 	follow the below URL. <br />
       <a href='https://www.youtube.com/watch?v=tUW6CkZEW8k'>How to Enable Allow_url_fopen in cPanel</a>
       </p>";
       return;
     }
    $file = fopen($url, "r");
    while(! feof($file)) {
       $list  = fgetcsv($file);
       if($list[0] == 'Name' && $list[1] == 'Designation' && $list[2] == 'Profile Details Type') {
          continue;
       }
       $list = implode('', $list);
       $wpdb->query($list);
    }
    fclose($file);
    echo "<p style='color: green; font-size: 14px;'>Team members import successfully.</p>";
 }
 
 function sbs_6310_export_service_box()
 {
    global $wpdb;
    $item_table = $wpdb->prefix . 'sbs_6310_item';
    $path = wp_upload_dir();
    $file = $path['path'] . '/team-skill-members.csv'; 
    $data = $wpdb->get_results('SELECT * FROM ' . $item_table . ' ORDER BY id DESC', ARRAY_A);
 
    $val = '';
    $fp = fopen( $file, "w" ); 
    fputcsv($fp, 
       [
          'Name',
          'Designation',
          'Profile Details Type',
          'Profile URL',
          'Open new tab',
          'Profile Details',
          'Popup Effect Appearance',
          'Image URL',
          'Hover Image URL',
          'Icon Names',
          'Icon URL',
          'Skills',
          'Contacts',
          'Category'
       ]
    );
    foreach ( $data as $selectedData ) {
          $sqlData = [
             "insert into {$item_table} set ",  
             "name='".esc_sql($selectedData['name'])."', ", 
             "designation='".esc_sql($selectedData['designation'])."', ", 
             "profile_details_type='".esc_sql($selectedData['profile_details_type'])."', ", 
             "profile_url='".esc_sql($selectedData['profile_url'])."', ", 
             "open_new_tab='".esc_sql($selectedData['open_new_tab'])."', ", 
             "profile_details='".esc_sql($selectedData['profile_details'])."', ", 
             "effect='".esc_sql($selectedData['effect'])."', ", 
             "image='".esc_sql($selectedData['image'])."', ", 
             "image_hover='".esc_sql($selectedData['image_hover'])."', ", 
             "iconids='".esc_sql($selectedData['iconids'])."', ", 
             "iconurl='".esc_sql($selectedData['iconurl'])."', ", 
             "skills='".esc_sql($selectedData['skills'])."', ", 
             "contacts='".esc_sql($selectedData['contacts'])."', ", 
             "category='".esc_sql($selectedData['category'])."'"
          ];
          fputcsv($fp, $sqlData);	
   }
    fclose( $fp );
    echo '<a href="'.$path['url'].'/team-skill-members.csv" target="_blank" id="export-skill-team-member-link">Download</a>';
 }
?>