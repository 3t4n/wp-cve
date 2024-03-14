<?php
defined('ABSPATH') or die("No direct script access!");

//Setting options page
/*******************************
 * Callback function to add the menu
 *******************************/
if(!function_exists('ilist_show_settngs_page_callback_func')){
  function ilist_show_settngs_page_callback_func(){

  	add_submenu_page(
  		'edit.php?post_type=ilist',
  		esc_html('Settings', 'iList' ),
  		esc_html('Settings', 'iList' ),
  		'manage_options',
  		'ilist_settings',
  		'qc_ilist_settings_page_callback_func'
  	);
  	add_action( 'admin_init', 'ilist_register_plugin_settings' );
  } //show_settings_page_callback_func
}
add_action( 'admin_menu', 'ilist_show_settngs_page_callback_func');

if(!function_exists('ilist_register_plugin_settings')){
  function ilist_register_plugin_settings() {

    $args = array(
      'type' => 'string', 
      'sanitize_callback' => 'sanitize_text_field',
      'default' => NULL,
    );  

    $args_email = array(
      'type' => 'string', 
      'sanitize_callback' => 'sanitize_email',
      'default' => NULL,
    );    
  	//register our settings
  	//general Section
  	register_setting( 'qc-ilist-plugin-settings-group', 'sl_enable_rtl', $args );
  	register_setting( 'qc-ilist-plugin-settings-group', 'sl_enable_embed_list', $args );
  	register_setting( 'qc-ilist-plugin-settings-group', 'sl_embed_title', $args );
  	register_setting( 'qc-ilist-plugin-settings-group', 'sl_embed_link', $args );

  	//Language Settings
  	register_setting( 'qc-ilist-plugin-settings-group', 'ilist_lan_share_list', $args );

    // OpenAI
    register_setting( 'qc-ilist-plugin-settings-group', 'sl_openai_auto_generate_enable', $args );
    register_setting( 'qc-ilist-plugin-settings-group', 'sl_openai_api_key', $args );
    register_setting( 'qc-ilist-plugin-settings-group', 'sl_openai_engines', $args );
    register_setting( 'qc-ilist-plugin-settings-group', 'sl_openai_max_token', $args );
    register_setting( 'qc-ilist-plugin-settings-group', 'sl_openai_temperature', $args );
    register_setting( 'qc-ilist-plugin-settings-group', 'sl_openai_presence_penalty', $args );
    register_setting( 'qc-ilist-plugin-settings-group', 'sl_openai_frequency_penalty', $args );

  	//custom css section
  	register_setting( 'qc-ilist-plugin-settings-group', 'sl_custom_style', $args );

  	
  }
}


if(!function_exists('qc_ilist_settings_page_callback_func')){
  function qc_ilist_settings_page_callback_func(){
  	
?>

  <div class="wrap swpm-admin-menu-wrap">
    <h1><?php echo esc_html( 'iList Settings Page' , 'iList' ); ?></h1>
    <h2 class="nav-tab-wrapper ilist_nav_container"> 
      <a class="nav-tab ilist_click_handle nav-tab-active" href="#getting_started"><?php echo esc_html( 'Getting Started' , 'iList' ); ?></a> 
      <a class="nav-tab ilist_click_handle " href="#general_settings"><?php echo esc_html( 'General Settings' , 'iList' ); ?></a> 
      <a class="nav-tab ilist_click_handle" href="#language_settings"><?php echo esc_html( 'Language Settings' , 'iList' ); ?></a> 
      <a class="nav-tab ilist_click_handle" href="#openai_settings"><?php echo esc_html( 'OpenAI' , 'iList' ); ?></a> 
      <a class="nav-tab ilist_click_handle" href="#custom_css"><?php echo esc_html( 'Custom Css' , 'iList' ); ?></a> 
      <a class="nav-tab ilist_click_handle" href="#help"><?php echo esc_html( 'Help' , 'iList' ); ?></a> </h2>
    <form method="post" action="options.php">
      <?php settings_fields( 'qc-ilist-plugin-settings-group' ); ?>
      <?php do_settings_sections( 'qc-ilist-plugin-settings-group' ); ?>
      <div id="getting_started">
        <div class="PortfolioX-Slider"> 
          
          <!-- Set up your HTML -->
          <div class="Slider-Hero-Slider owl-carousel owl-theme" id="carousel">
            <div class="PortfolioX-section">
              <div class="service-count"><?php echo esc_html( 'Step 1' , 'iList' ); ?></div>
              <div class="PortfolioX-details">
                <h2><?php echo esc_html( '// Create a new iList (infographic)' , 'iList' ); ?></h2>
                <p> <?php echo esc_html( 'Go to New iList and start by giving it a name. Select a iList type. Info Lists comprises of only text, Graphics Lists only images. Select Infographic Lists for images and texts both.' , 'iList' ); ?> <br />
                  <br />
                  <strong><?php echo esc_html( 'Choose a template. Add a simple Chart if you want to.' , 'iList' ); ?></strong></p>
              </div>
              <div class="PortfolioX-img"><img src="<?php echo QCOPD_IMG_URL1; ?>/step1.jpg" alt=""></div>
            </div>
            <div class="PortfolioX-section">
              <div class="service-count"><?php echo esc_html( 'Step 2' , 'iList' ); ?></div>
              <div class="PortfolioX-details">
                <h2><?php echo esc_html( '// Add Infographic Details' , 'iList' ); ?></h2>
                <p> <?php echo esc_html( 'Start adding Infographic details with a Title, Description and an Image for each entry. Click the Add Another Entry button to add as many entries you need. A typical Infographic can have 5-10 entries.' , 'iList' ); ?><br />
                  <br />
                  <strong><?php echo esc_html( 'Save the iList and Publish.' , 'iList' ); ?></strong> </p>
              </div>
              <div class="PortfolioX-img"><img src="<?php echo QCOPD_IMG_URL1; ?>/step2.jpg" alt=""></div>
            </div>
            <div class="PortfolioX-section">
              <div class="service-count"><?php echo esc_html( 'Step 3' , 'iList' ); ?></div>
              <div class="PortfolioX-details">
                <h2><?php echo esc_html( '// Generate and Paste Shortcode on a Page ' , 'iList' ); ?></h2>
                <p><?php echo esc_html( 'The final step is to publish your infographic on a page or post. This is done with a shortcode and the easiest way is to use the Shortcode Generator. After you generated the ShortCode, simply paste it exactly where you want the Infographic to show up.' , 'iList' ); ?></p>
                <br />
                <p style="color:#FF0004;"><?php echo esc_html( 'NB: iLists must be published before you add the shortcode to a page. iLists won\'t display on your page if it is in Draft mode. Don\'t worry, your iLists won\'t show until you add the shortcode to a page or post.' , 'iList' ); ?> </p>
              </div>
              <div class="PortfolioX-img"><img src="<?php echo QCOPD_IMG_URL1; ?>/step4.jpg" alt=""></div>
            </div>
            
            
          </div>
        </div>
      </div>
      <div id="general_settings" style="display:none">
        <table class="form-table">
          <tr valign="top">
            <th scope="row"><?php echo esc_html( 'Enable RTL Direction' , 'iList' ); ?></th>
            <td><input type="checkbox" name="sl_enable_rtl" value="on" <?php echo (esc_attr( get_option('sl_enable_rtl') )=='on'?'checked="checked"':''); ?> />
              <i><?php echo esc_html( 'If you make this option ON, then list heading and list items will be arranged in Right-to-Left direction.' , 'iList' ); ?></i></td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php echo esc_html( 'Enable embed List button on listing pages' , 'iList' ); ?></th>
            <td><input type="checkbox" name="sl_enable_embed_list" value="on" <?php echo (esc_attr( get_option('sl_enable_embed_list') )=='on'?'checked="checked"':''); ?> />
              <i><?php echo esc_html( 'Enable embed link button to generate iFrame embed code for particular list.' , 'iList' ); ?></i></td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php echo esc_html( 'Title for Embed option' , 'iList' ); ?></th>
            <td><input type="text" name="sl_embed_title" size="100" value="<?php echo esc_attr( get_option('sl_embed_title') ); ?>"  />
              <i><?php echo esc_html( 'Credit title displayed in embed option.' , 'iList' ); ?></i></td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php echo esc_html( 'Link for Embed option' , 'iList' ); ?></th>
            <td><input type="text" name="sl_embed_link" size="100" value="<?php echo esc_attr( get_option('sl_embed_link') ); ?>"  />
              <i><?php echo esc_html( 'Credit link displayed in embed option.' , 'iList' ); ?></i></td>
          </tr>
        </table>
      </div>
      <div id="language_settings" style="display:none">
        <table class="form-table">
          <tr valign="top">
            <th scope="row"><?php echo esc_html( 'Generate Embed Code' , 'iList' ); ?></th>
            <td><input type="text" name="ilist_lan_share_list" size="100" value="<?php echo esc_attr( get_option('ilist_lan_share_list') ); ?>"  />
              <br/>
              <i><?php echo esc_html( 'Change the language for Generate Embed Code' , 'iList' ); ?></i></td>
          </tr>
        </table>
      </div>
      <div id="openai_settings" style="display:none">
        <table class="form-table">
          <tr valign="top">
            <th scope="row"><?php echo esc_html( 'Enable OpenAI Auto-generate Description' , 'iList' ); ?></th>
            <td><input type="checkbox" name="sl_openai_auto_generate_enable" value="on" <?php echo (esc_attr( get_option('sl_openai_auto_generate_enable') )=='on'?'checked="checked"':''); ?> />
              <i><?php echo esc_html( 'Enable OpenAI Auto-generate Description' , 'iList' ); ?></i></td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php echo esc_html( 'API KEY' , 'iList' ); ?></th>
            <td><input type="text" name="sl_openai_api_key" size="100" value="<?php echo esc_attr( get_option('sl_openai_api_key') ); ?>"  />
              <i><?php echo esc_html( 'Open AI settings API KEY' , 'iList' ); ?> <a class="qcld_help_link" href="<?php echo esc_url('https://beta.openai.com/account/api-keys'); ?>" target="_blank"><?php echo esc_html( 'Get Your Api Key' , 'iList' ); ?></a></i></td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php echo esc_html( 'Engines' , 'iList' ); ?></th>
            <td>
              <select name="sl_openai_engines">
                <option><?php echo esc_html( 'Select Engines' , 'iList' ); ?></option>
                <option value="gpt-3.5-turbo" selected><?php echo esc_html( 'GPT-3 turbo' , 'iList' ); ?></option>
                <option value="gpt-3.5-turbo-instruct-0914"> <?php echo esc_html( 'gpt-3.5-turbo-instruct-0914' , 'iList' ); ?> </option>
                <option value="gpt-3.5-turbo-instruct"> <?php echo esc_html( 'gpt-3.5-turbo-instruct' , 'iList' ); ?> </option>

              </select>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php echo esc_html( 'Max token' , 'iList' ); ?></th>
            <td><input type="text" name="sl_openai_max_token" id="sl_openai_max_token" size="100" value="<?php echo esc_attr( get_option('sl_openai_max_token') ); ?>"  />
              <p><?php echo esc_html( 'Max token ( Min: 0, Max:4000 ) . Depending on the model' , 'iList' ); ?></p></td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php echo esc_html( 'Temperature' , 'iList' ); ?></th>
            <td><input type="number" name="sl_openai_temperature" id="sl_openai_temperature" size="100" min="0" max="1" step="0.1" value="<?php echo esc_attr( get_option('sl_openai_temperature') ); ?>"  />
              <p><?php echo esc_html( 'Temperature is a value between 0 and 1 that essentially lets you control how confident the model should be when making these predictions' , 'iList' ); ?></p></td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php echo esc_html( 'Presence Penalty' , 'iList' ); ?></th>
            <td><input type="number" name="sl_openai_presence_penalty" id="sl_openai_presence_penalty" size="100" min="-2" max="2" step="0.1" value="<?php echo esc_attr( get_option('sl_openai_presence_penalty') ); ?>"  />
              <p><?php echo esc_html( 'Number between -2.0 and 2.0. Positive values penalize new tokens based on whether they appear in the text so far, increasing the model’s likelihood to talk about new topics.' , 'iList' ); ?></p></td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php echo esc_html( 'Frequency Penalty' , 'iList' ); ?></th>
            <td><input type="number" name="sl_openai_frequency_penalty" id="sl_openai_frequency_penalty" size="100" min="-2" max="2" step="0.1" value="<?php echo esc_attr( get_option('sl_openai_frequency_penalty') ); ?>"  />
              <p><?php echo esc_html( 'Number between -2.0 and 2.0. Positive values penalize new tokens based on their existing frequency in the text so far, decreasing the model’s likelihood to repeat the same line verbatim.' , 'iList' ); ?></p></td>
          </tr>
          <tr valign="top">
            <th scope="row"></th>
            <td>
              <p style="color:indianred;"><b>**<?php esc_html_e('If Auto Generate content with OpenAI is not working, then likely you hit your OpenAI usage limit. Add a', 'seo-help'); ?> <a href="<?php echo esc_url('https://platform.openai.com/account/billing/overview'); ?>" target="_blank"> <?php esc_html_e('billing detail', 'seo-help'); ?> </a> <?php esc_html_e('and increase the Usage limit.', 'iList'); ?></b></p>
            </td>
          </tr>
        </table>
      </div>
      <div id="custom_css" style="display:none">
        <table class="form-table">
          <tr valign="top">
            <th scope="row"><?php echo esc_html( 'Custom Css' , 'iList' ); ?></th>
            <td><textarea name="sl_custom_style" rows="10" cols="100"><?php echo esc_attr( get_option('sl_custom_style') ); ?></textarea>
              <br />
              <i><?php echo esc_html( 'Write your custom CSS here. Please do not use' , 'iList' ); ?> <b><?php echo esc_html( 'style' , 'iList' ); ?></b> <?php echo esc_html( 'tag in this textarea.' , 'iList' ); ?></i></td>
          </tr>
        </table>
      </div>
      <div id="help" style="display:none">
        <table class="form-table">
          <tr valign="top">
            <th scope="row"><?php echo esc_html( 'Help' , 'iList' ); ?></th>
            <td><div>
                <h1><?php echo esc_html( 'Welcome to the Infographic Maker - iList! You are' , 'iList' ); ?> <strong><?php echo esc_html( 'awesome' , 'iList' ); ?></strong>, <?php echo esc_html( 'by the way' , 'iList' ); ?> <img draggable="false" class="emoji" alt="🙂" src="<?php echo QCOPD_IMG_URL1; ?>/1f642.svg"></h1>
                <h3><?php echo esc_html( 'Getting Started' , 'iList' ); ?></h3>
                <p><?php echo esc_html( 'In principle, an infographic is a List created with three building blocks – Texts, Images and Charts laid out in a visually impressive manner to convey a specific idea that anyone can easily grasp. iList lets you make Lists with Images, Texts, and Charts (pro feature).' , 'iList' ); ?> </p>
                <p><?php echo esc_html( 'With that in mind you should start with the following simple steps.' , 'iList' ); ?></p>
                <br>
                <p><b><?php echo esc_html( 'Step 1: Go to our Infographic Maker iList and Press on that New iList button.' , 'iList' ); ?> </b></p>
                <br>
                <p><b><?php echo esc_html( 'Step 2: Begin by giving your infographic a Title. Preferably a catchy one to grab user’s interest. In the next row, we have three options –  Info Lists, Graphic Lists, and Infographics. Infographics is selected by default – so nothing needs to be done there.' , 'iList' ); ?></b></p>
                <br>
                <p><b> <?php echo esc_html( 'Step 3: Next, you can select a template for your InfoGraphic from a lightbox window which shows a preview of all the available templates. Click on the template you want to start with. Don’t worry. You can change it later. Anytime!.' , 'iList' ); ?></b></p>
                <br>
                <p><b><?php echo esc_html( 'Step 4: Now comes the fun part. You can now start adding your bullet points for the Lists. Each bullet point or list items can have a Title Text, Description Text, and Image (or Icon).' , 'iList' ); ?></b></p>
                <br>
                <p><b><?php echo esc_html( 'Step 5: The final step is to publish your infographic on a page or post. This is done with a shortcode and the easiest way is to use the Shortcode Generator. After you generated the ShortCode, simply paste it exactly where you want the Infographic to show up.' , 'iList' ); ?></b></p>
                <br>
                <p style="color:red"><b><?php echo esc_html( 'NB:' , 'iList' ); ?></b> <?php echo esc_html( 'iLists must be published before you add the shortcode to a page. iLists won\'t display on your page if it is in Draft mode. Don\'t worry, your iLists won\'t show until you add the shortcode to a page or post.' , 'iList' ); ?></p>
                <br>
                <p><?php echo esc_html( 'That’s it! The above steps are for the basic usages. There are a lot of advanced options and tons more templates available with the' , 'iList' ); ?> <a href="https://www.quantumcloud.com/products/infographic-maker-ilist/" target="_blank"><?php echo esc_html( 'Professional version' , 'iList' ); ?></a> <?php echo esc_html( 'if you ever feel the need. If you had any specific questions about how something works, do not hesitate to contact us from the' , 'iList' ); ?> <a href="<?php echo get_site_url().'/wp-admin/edit.php?post_type=ilist&page=qcpro-promo-page-ilist-free-page-123za'; ?>"><?php echo esc_html( 'Support Page' , 'iList' ); ?></a>. <img draggable="false" class="emoji" alt="🙂" src="<?php echo QCOPD_IMG_URL1; ?>/1f642.svg"></p>
                <p><strong><a href="<?php echo esc_url( 'https://www.quantumcloud.com/resources/make-infographics-ilist/' , 'iList' ); ?>" target="_blank"><?php echo esc_html( 'Check' , 'iList' ); ?></a> <?php echo esc_html( 'This Article we Created for the Pro Version for More Details with Images and Screenshots' , 'iList' ); ?></strong></p>
                <h3><?php echo esc_html( 'Shortcode Generator' , 'iList' ); ?></h3>
                <p> <?php echo esc_html( 'We encourage you to use the ShortCode generator found in the toolbar of your page/post editor in visual mode.' , 'iList' ); ?></p>
                <img src="<?php echo QCOPD_IMG_URL1; ?>/classic.jpg" alt="shortcode generator" />
                <p><?php echo esc_html( 'See sample below for where to find it for Gutenberg.' , 'iList' ); ?></p>
                <img src="<?php echo QCOPD_IMG_URL1; ?>/gutenburg.jpg" alt="shortcode generator" /> <img src="<?php echo QCOPD_IMG_URL1; ?>/gutenburg2.jpg" alt="shortcode generator" />
                <p><?php echo esc_html( 'This is how the shortcode generator will look like.' , 'iList' ); ?></p>
                <img src="<?php echo QCOPD_IMG_URL1; ?>/shortcode-generator1.jpg" alt="shortcode generator" /> 
                
                
                <h3><?php echo esc_html( 'Shortcode Example' , 'iList' ); ?></h3>
                <p> <?php echo esc_attr( '[qcld-ilist mode="one" list_id="75"]' , 'iList' ); ?><br>
                  <br>
                  <strong><u><?php echo esc_html( 'Available Parameters:' , 'iList' ); ?></u></strong> <br>
                </p>
                <p> <strong><?php echo esc_html( '1. mode' , 'iList' ); ?></strong> <br>
                  <?php echo esc_html( 'Value for this option can be set as "one" or "all".', 'iList' ); ?>
                  <?php echo esc_attr( 'Example: mode="one"' , 'iList' ); ?> </p>
                <p> <strong><?php echo esc_html( '2. column' , 'iList' ); ?></strong> <br>
                  <?php echo esc_html( 'Avaialble values: "1", "2", "3".', 'iList' ); ?>
                   <?php echo esc_attr( 'Example: column=1' , 'iList' ); ?></p>
                <p> <strong><?php echo esc_html( '6. list_id' , 'iList' ); ?></strong> <br>
                   <?php echo esc_html( 'Only applicable if you want to display a single list [not all]. You can provide specific list id here as a value. You can also get ready shortcode for a single list under "Manage List Items" menu.
                  Example: list_id="404"' , 'iList' ); ?></p>
                <p> <strong><?php echo esc_html( '9. upvote' , 'iList' ); ?></strong> <br>
                  <?php echo esc_html( 'Values: on or off. This options allows upvoting of your list items.' , 'iList' ); ?><br>
                   <?php echo esc_attr( 'Example: upvote="on"' , 'iList' ); ?></p>
              </div></td>
          </tr>
        </table>
      </div>
      <?php submit_button(); ?>
    </form>
  </div>

  <?php
  	
  }
}

