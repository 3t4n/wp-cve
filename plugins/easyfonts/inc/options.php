<?php

// If this file is called directly, abort.
if (!defined("WPINC")){
	die;
}

function easyfonts_options_page() {
  // Add an options page to the Settings menu
  add_options_page(
    'Easy Fonts Options',
    'Easy Fonts',
    'manage_options',
    'easyfonts',
    'easyfonts_options_page_html'
  );
}
add_action('admin_menu', 'easyfonts_options_page');

function easyfonts_options_page_html() {
  // Check if the user has permission to access this page
  if(!current_user_can('manage_options')) {
    return;
  }
  
  // Check if the form has been submitted
  if(isset($_POST['easyfonts_submit'])) {
    // Sanitize the submitted form data
    $host_google_fonts_locally_link = isset($_POST['easyfonts_host_google_fonts_locally_link']) ? sanitize_text_field($_POST['easyfonts_host_google_fonts_locally_link']) : false;
	  $host_google_fonts_locally_import = isset($_POST['easyfonts_host_google_fonts_locally_import']) ? sanitize_text_field($_POST['easyfonts_host_google_fonts_locally_import']) : false;
    $remove_resource_hints = isset($_POST['easyfonts_remove_resource_hints']) ? sanitize_text_field($_POST['easyfonts_remove_resource_hints']) : false;
	$remove_inline_css_fontface = isset($_POST['easyfonts_remove_inline_css_fontface']) ? sanitize_text_field($_POST['easyfonts_remove_inline_css_fontface']) : false;
	$remove_inline_script_font = isset($_POST['easyfonts_remove_inline_script_font']) ? sanitize_text_field($_POST['easyfonts_remove_inline_script_font']) : false;
    
    // Update the 'host_google_fonts_locally link' option
    update_option(
      'easyfonts_host_google_fonts_locally_link',
      $host_google_fonts_locally_link ? true : false
    );
	// Update the 'host_google_fonts_locally @import' option
	update_option(
      'easyfonts_host_google_fonts_locally_import',
      $host_google_fonts_locally_import ? true : false
    );
    
    // Update the 'remove_resource_hints' option
    update_option(
      'easyfonts_remove_resource_hints',
      $remove_resource_hints ? true : false
    );
	update_option(
      'easyfonts_remove_inline_css_fontface',
      $remove_inline_css_fontface ? true : false
    );
	update_option(
      'easyfonts_remove_inline_script_font',
      $remove_inline_script_font ? true : false
    );
    
    // Display a success message
    ?>
    <div class="notice notice-success is-dismissible">
      <p>Your changes have been saved.</p>
    </div>
    <?php
  }
  
  // Get the current values of the options
  $host_google_fonts_locally_link = get_option('easyfonts_host_google_fonts_locally_link', false);
  $host_google_fonts_locally_import = get_option('easyfonts_host_google_fonts_locally_import', false);
  $remove_resource_hints = get_option('easyfonts_remove_resource_hints', false);
  $remove_inline_css_fontface = get_option('easyfonts_remove_inline_css_fontface', false);
  $remove_inline_script_font = get_option('easyfonts_remove_inline_script_font', false);
  
  ?>
  <div class="easyfontwrap">
    
    <form method="post">
		<div class="heading">
			<h1>Easy Fonts Options</h1> </div><p class="confirm">
			To confirm and obtain the list of fonts that are loading on your website <a href="https://easywpstuff.com/google-fonts-checker/" target="_blank">click here</a>
			</p>
      <table class="form-table">
        <tr>
          <th scope="row">
            <div class="easyfonts_host_google_fonts_locally_link">Process Google fonts Stylesheet</div>
          </th>
          <td>
            <div class="checkbox-wrapper-2"><label for="easyfonts_host_google_fonts_locally_link"><input class="sc-gJwTLC ikxBAC" type="checkbox" name="easyfonts_host_google_fonts_locally_link" id="easyfonts_host_google_fonts_locally_link" value="1" <?php echo $host_google_fonts_locally_link ? 'checked' : ''; ?>><span class="slider"></span></label></div>
            <p class="description">If enabled, the plugin will download Google Fonts from <code><strong>&lt;link&gt;</strong></code> and host them locally.</p>
          </td>
        </tr>
		  <tr>
          <th scope="row">
            <div class="easyfonts_host_google_fonts_locally_import">Process @import inline style </div>
          </th>
          <td>
            <div class="checkbox-wrapper-2"><label for="easyfonts_host_google_fonts_locally_import"><input class="sc-gJwTLC ikxBAC" type="checkbox" name="easyfonts_host_google_fonts_locally_import" id="easyfonts_host_google_fonts_locally_import" value="1" <?php echo $host_google_fonts_locally_import ? 'checked' : ''; ?>><span class="slider"></span></label></div>
            <p class="description">If enabled, the plugin will process <code><strong>@import</strong></code> rules from inline <code><strong>&lt;style&gt;</strong></code> tags.</p>
          </td>
        </tr>
		<tr>
          <th scope="row">
			              <div class="easyfonts_remove_inline_css_fontface">Process @font-face statement</div>
          </th>
          <td>
            <div class="checkbox-wrapper-2"><label for="easyfonts_remove_inline_css_fontface"><input type="checkbox" class="sc-gJwTLC ikxBAC" name="easyfonts_remove_inline_css_fontface" id="easyfonts_remove_inline_css_fontface" value="1" <?php echo $remove_inline_css_fontface ? 'checked' : ''; ?>><span class="slider"></span></label></div>
            <p class="description">If enabled, the plugin will process <code><strong>@font-face</strong></code> statement from inline <code><strong>&lt;style&gt;</strong></code> tags.</p>
          </td>
        </tr>
        <tr>
          <th scope="row">
			              <div class="easyfonts_remove_resource_hints">Remove Resource Hints</div>
          </th>
          <td>
			  <div class="checkbox-wrapper-2"><label for="easyfonts_remove_resource_hints" class="switch"><input class="sc-gJwTLC ikxBAC" type="checkbox" name="easyfonts_remove_resource_hints" id="easyfonts_remove_resource_hints" value="1" <?php echo $remove_resource_hints ? 'checked' : ''; ?>><span class="slider"></span></label></div>
            <p class="description">If enabled, the plugin will remove resource hints (such as <code><strong>preconnect</strong></code>, <code><strong>prefetch</strong></code>, etc) from the website.</p>
          </td>
        </tr>
		
		<tr>
          <th scope="row">
			              <label for="easyfonts_remove_inline_script_font">Remove webfont.js fonts </label>
          </th>
          <td>
            <div class="checkbox-wrapper-2"><label for="easyfonts_remove_inline_script_font"><input type="checkbox" class="sc-gJwTLC ikxBAC" name="easyfonts_remove_inline_script_font" id="easyfonts_remove_inline_script_font" value="1" <?php echo $remove_inline_script_font ? 'checked' : ''; ?>><span class="slider"></span></label></div>
            <p class="description">If enabled, the plugin will remove google fonts loading from <code><strong>webfont.js</strong></code> loader inline <code><strong>script</strong></code> tags.</p>
          </td>
        </tr>
      </table>
      <?php submit_button('Save Changes', 'primary', 'easyfonts_submit'); ?>
		<button type="submit" name="easyfonts_clear_font_cache" class="button remove">Remove All stored Fonts</button>
		<?php if (get_option('easyfonts_host_google_fonts_locally_link', false) || get_option('easyfonts_host_google_fonts_locally_import', false) || get_option('easyfonts_remove_inline_css_fontface', false)) : ?>
	  <button type="submit" name="easyfonts_preload" class="button preload">Preload Fonts</button>
		<?php endif; ?>
	  
    </form>
	  <div>
     
	  </div>
	  <?php if (get_option('easyfonts_host_google_fonts_locally_link', false) || get_option('easyfonts_host_google_fonts_locally_import', false)) { easyfonts_list_styles();} ?>
  </div>
  <?php
	// Check if the 'Clear Font Cache' button has been clicked
  if(isset($_POST['easyfonts_clear_font_cache'])) {
    easyfonts_clear_font_cache();
  }
	if(isset($_POST['easyfonts_preload'])) {
    easyfonts_preload();
  }
	if(isset($_POST['easyfonts_submit'])){
    if (get_option('easyfonts_host_google_fonts_locally_link', false) || get_option('easyfonts_host_google_fonts_locally_import', false) || get_option('easyfonts_remove_inline_css_fontface', false)) {
        easyfonts_preload_save();
    }
  }
}

function easyfonts_clear_font_cache() {
  // Get the WordPress uploads directory
  $uploads_dir = wp_upload_dir();
  
  // Build the path to the 'easyfonts' folder
  $easyfonts_dir = $uploads_dir['basedir'] . '/easyfonts/';
  
  // Open the 'easyfonts' folder
  $handle = opendir($easyfonts_dir);
  
  // Iterate over the files in the 'easyfonts' folder
  while(($file = readdir($handle)) !== false) {
    // Skip the current and parent directories
    if($file == '.' || $file == '..') {
      continue;
    }
    
    // Build the path to the file
    $file_path = $easyfonts_dir . $file;
    
    // Delete the file
    unlink($file_path);
  }
  
  // Close the 'easyfonts' folder
  closedir($handle);
  
  // Display a success message
  ?>
  <div class="notice notice-success is-dismissible">
    <p>The fonts have been removed.</p>
  </div>
  <?php
}

function easyfonts_preload_save() {
    // Get the current user
    $current_user = wp_get_current_user();
    // Check if the user is an admin
    if (user_can($current_user, 'manage_options')) {
        // Get the cookies for the current user
        $cookies = wp_get_current_user()->get_session_tokens();
        // Add the cookies and headers to the request
        $options = array(
            'cookies' => $cookies,
            'headers' => array(
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3',
            ),
        );
        // Append a query string to the homepage URL to visit it as an admin
        $home_url = home_url() . '?easyfonts_preload=1';
        // Visit the homepage as admin to preload the fonts
        wp_remote_get($home_url, $options);
    }
}


function easyfonts_preload() {
	
    $current_user = wp_get_current_user();
    // Check if the user is an admin
    if (user_can($current_user, 'manage_options')) {
        // Get the cookies for the current user
        $cookies = wp_get_current_user()->get_session_tokens();
        // Add the cookies and headers to the request
        $options = array(
            'cookies' => $cookies,
            'headers' => array(
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3',
            ),
        );
        // Append a query string to the homepage URL to visit it as an admin
        $home_url = home_url() . '?easyfonts_preload=1';
        // Visit the homepage as admin to preload the fonts
        wp_remote_get($home_url, $options);
    }
  
  // Display a success message
  ?>
  <div class="notice notice-success is-dismissible">
    <p>The fonts have been preloaded.</p>
  </div>
  <?php
}

function easyfonts_list_styles() {
    $easyfonts_dir = wp_upload_dir()['basedir'].'/easyfonts/';
    $css_files = array();
    $style_data = array();
    try {
    if(!is_dir($easyfonts_dir)){
       throw new Exception("Please make sure to preload the font or visit the homepage");
    }
    $dir = new DirectoryIterator($easyfonts_dir);
        // iterate over the files in the directory
        foreach ($dir as $file) {
            if (!$file->isFile()) continue;
            if($file->getExtension() == 'css'){
                $css_files[] = $file->getFilename();
            }
        }
        if(!empty($css_files)){
            echo '<table class="styled-table">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Hosted Fonts CSS URL</th>';
            echo '<th>Font Families</th>';
            echo '<th>Variants</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            foreach($css_files as $file_name) {
                $file_url = wp_upload_dir()['baseurl'].'/easyfonts/'.$file_name;
                $file_path = $easyfonts_dir.$file_name;
                $file_content = file_get_contents($file_path);
                $font_family = array();
                $variant = array();
                $variant_italic = array();
                $variant_normal = array();
                preg_match_all("/@font-face\s*{[^}]+}/", $file_content, $matches);
                if (count($matches[0]) > 0) {
                    foreach ($matches[0] as $font_face) {
                        if (preg_match("/font-family:([^;]+);/", $font_face, $font_family_match)) {
                            $font_family[] = trim($font_family_match[1]);
                        }
                        if (preg_match("/font-style:([^;]+);/", $font_face, $variant_match)) {
    $style = trim($variant_match[1]);
    if($style == 'italic'){
        if (preg_match("/font-weight:([^;]+);/", $font_face, $variant_match)) {
            $variant = trim($variant_match[1]);
            if (!in_array($variant, $variant_italic)) {
                $variant_italic[] = $variant;
            }
        }
    }elseif($style == 'normal'){
        if (preg_match("/font-weight:([^;]+);/", $font_face, $variant_match)) {
            $variant = trim($variant_match[1]);
            if (!in_array($variant, $variant_normal)) {
                $variant_normal[] = $variant;
            }
        }
    }
}

                    }
                }
                $font_family = array_unique($font_family);
                $style_data[] = array(
                    'file_url' => $file_url,
                    'font_families' => $font_family,
                    'variant' => 'italic '.implode(',', $variant_italic) . ' | normal '.implode(',', $variant_normal)
                );
            }
            if(!empty($style_data)){
                foreach($style_data as $style){
                    echo '<tr>';
                    echo '<td>'. esc_url($style['file_url']) .'</td>';
                    echo '<td>'. esc_attr(implode(',', $style['font_families'])) .'</td>';
                    echo '<td>'. esc_html($style['variant']) .'</td>';
                    echo '</tr>';
                }
            }
            echo '</tbody>';
            echo '</table>';
        }else{
			if (get_option('easyfonts_host_google_fonts_locally_link', false) || get_option('easyfonts_host_google_fonts_locally_import', false)) {
            echo 'fonts styles are not found. Preload Font first or visit the homepage.';
			}
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
