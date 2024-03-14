<?php

defined('ABSPATH') or die("No direct script access!");

//Setting options page
/*******************************
 * Callback function to add the menu
 *******************************/
function show_settngs_page_callback_func(){

	add_submenu_page(
		'edit.php?post_type=sld',
		esc_html('Settings'),
		esc_html('Settings'),
		'manage_options',
		'sld_settings',
		'qcsettings_page_callback_func'
	);
	add_action( 'admin_init', 'sld_register_plugin_settings' );
  
} //show_settings_page_callback_func
add_action( 'admin_menu', 'show_settngs_page_callback_func');

function sld_register_plugin_settings() {
	//register our settings
	//general Section
	register_setting( 'qc-sld-plugin-settings-group', 'sld_enable_top_part' );
	register_setting( 'qc-sld-plugin-settings-group', 'sld_enable_upvote' );
	register_setting( 'qc-sld-plugin-settings-group', 'sld_add_new_button' );
	register_setting( 'qc-sld-plugin-settings-group', 'sld_add_item_link' );
	register_setting( 'qc-sld-plugin-settings-group', 'sld_enable_click_tracking' );
	register_setting( 'qc-sld-plugin-settings-group', 'sld_embed_credit_title' );
	register_setting( 'qc-sld-plugin-settings-group', 'sld_embed_credit_link' );
	register_setting( 'qc-sld-plugin-settings-group', 'sld_enable_scroll_to_top' );
  register_setting( 'qc-sld-plugin-settings-group', 'sld_enable_rtl' );
	//Language Settings
	register_setting( 'qc-sld-plugin-settings-group', 'sld_lan_add_link' );
	register_setting( 'qc-sld-plugin-settings-group', 'sld_lan_share_list' );
	//custom css section
	register_setting( 'qc-sld-plugin-settings-group', 'sld_custom_style' );
	//custom js section
	register_setting( 'qc-sld-plugin-settings-group', 'sld_custom_js' );
	//help sectio
	
}

function qcsettings_page_callback_func(){
	
	?>

<div class="wrap swpm-admin-menu-wrap">
  <h1><?php echo esc_html('SLD Settings Page'); ?></h1>
  <h2 class="nav-tab-wrapper sld_nav_container"> 
  <a class="nav-tab sld_click_handle nav-tab-active" href="#getting_started"><?php echo esc_html('Getting Started'); ?></a> 
  <a class="nav-tab sld_click_handle " href="#general_settings"><?php echo esc_html('General Settings'); ?></a> 
  <a class="nav-tab sld_click_handle" href="#language_settings"><?php echo esc_html('Language Settings'); ?></a> 
  <a class="nav-tab sld_click_handle" href="#custom_css"><?php echo esc_html("Custom CSS"); ?></a> 
  <a class="nav-tab sld_click_handle" href="#custom_js"><?php echo esc_html('Custom Javascript'); ?></a> 
  <a class="nav-tab sld_click_handle" href="#help"><?php echo esc_html('Help & Troubleshooting'); ?></a> 
  </h2>
  <form method="post" action="options.php">
    <?php settings_fields( 'qc-sld-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'qc-sld-plugin-settings-group' ); ?>
   
      <div id="getting_started">
        <div class="sld-container"><div class="sld-row">
          <div class="is-dismissible sld-Getting-Started " style="display:none">
            <div class="sld_Started_carousel slick-slider">
            
              <div class="sld_info_item">
                <div class="serviceBox">
                  <div class="service-count"><?php echo esc_html('Step 1'); ?></div>
                  <div class="service-icon"><span><i class="fa fa-thumbs-up"></i></span></div>
              
                  <div class="sldslider-Details">
                    <div class="description">
                      <h3><?php echo esc_html('Create List with Link Details '); ?></h3>
                       <?php echo esc_html('Go to New List and create one by giving it a name. Then simply start adding List items or Links by filling up the fields you want. Use the Add New button to add more Links to your list.'); ?>
                    </div>
                    <div class="Getting_Started_img">
                      <img src="<?php echo QCOPD_IMG_URL; ?>/image.png" />
                    </div>
                  </div>
                </div>
              </div>
            
              <div class="sld_info_item">
                <div class="serviceBox">
                  <div class="service-count"><?php echo esc_html('Step 2'); ?></div>
                  <div class="service-icon"><span><i class="fa fa-thumbs-up"></i></span></div>
                  <div class="sldslider-Details">
                    <div class="description">
                      <h3><?php echo esc_html('Create More Lists'); ?> </h3>
                       <?php echo esc_html('You can just create a single list and use the Single List mode. But this plugin works the best when you create a few Lists each conatining about 15-20 Llinks. This yields the best view.'); ?>
                    </div>
                    <div class="Getting_Started_img">
                      <img src="<?php echo QCOPD_IMG_URL; ?>/step2.png" />
                    </div>
                  </div>   
                </div>
              </div>          
            
              <div class="sld_info_item">
                <div class="serviceBox">
                  <div class="service-count"><?php echo esc_html('Step 3'); ?></div>
                  <div class="service-icon"><span><i class="fa fa-thumbs-up"></i></span></div>
                  <div class="sldslider-Details">
                    <div class="description">
                      <h3><?php echo esc_html('Generate and Paste Shortcode on a Page'); ?></h3>
                      <?php echo esc_html('Go to the page or post you want to display the directory. On the right sidebar you will see a ShortCode Generator block. Generate a shortcode with the options you want. Copy paste that to a section on your page.'); ?>
                    </div>
                    <div class="Getting_Started_img">
                      <img src="<?php echo QCOPD_IMG_URL; ?>/step3.png" />
                    </div>
                  </div>
                </div>          
              </div>   
            
            
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="general_settings" style="display:none">
      
      <table class="form-table">
        <tr valign="top">
          <th scope="row"><?php echo esc_html('Enable Top Area'); ?></th>
          <td><input type="checkbox" name="sld_enable_top_part" value="on" <?php echo (esc_attr( get_option('sld_enable_top_part') )=='on'?'checked="checked"':''); ?> />
            <i><?php echo esc_html('Top area includes Embed button (more options coming soon)'); ?></i></td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php echo esc_html('Enable Upvote'); ?></th>
          <td><input type="checkbox" name="sld_enable_upvote" value="on" <?php echo (esc_attr( get_option('sld_enable_upvote') )=='on'?'checked="checked"':''); ?> />
            <i><?php echo esc_html('Turn ON to visible Upvote feature for all templates.'); ?></i></td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php echo esc_html('Enable Add New Button'); ?></th>
          <td><input type="checkbox" name="sld_add_new_button" value="<?php echo esc_attr('on'); ?>" <?php echo (esc_attr( get_option('sld_add_new_button') )=='on'?'checked="checked"':''); ?> />
            <i><?php echo esc_html('The button will link to a page of your choice where you can place a contact form or instructions to submit links to your directory. Links have to be manually added by the admin.'); ?></i></td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php echo esc_html('Add Button Link'); ?></th>
          <td><input type="text" name="sld_add_item_link" size="100" value="<?php echo esc_attr( get_option('sld_add_item_link') ); ?>"  />
            <i><?php echo esc_html('Example: http://www.yourdomain.com'); ?></i></td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php echo esc_html('Track Outbound Clicks'); ?></th>
          <td><input type="checkbox" name="sld_enable_click_tracking" value="on" <?php echo (esc_attr( get_option('sld_enable_click_tracking') )=='on'?'checked="checked"':''); ?> />
            <i><?php echo esc_html('You need to have the analytics.js'); ?> [<a href="https://support.google.com/analytics/answer/1008080#GA" target="_blank"><?php echo esc_html('Analytics tracking code in every page of your site'); ?></a>].</i></td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php echo esc_html('Embed Credit Title'); ?></th>
          <td><input type="text" name="sld_embed_credit_title" size="100" value="<?php echo esc_attr( get_option('sld_embed_credit_title') ); ?>"  />
            <i><?php echo esc_html('This text will be displayed below embedded list in other sites.'); ?></i></td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php echo esc_html('Embed Credit Link'); ?></th>
          <td><input type="text" name="sld_embed_credit_link" size="100" value="<?php echo esc_attr( get_option('sld_embed_credit_link') ); ?>"  />
            <i><?php echo esc_html('This text will be displayed below embedded list in other sites.'); ?></i></td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php echo esc_html('Enable Scroll to Top Button'); ?></th>
          <td><input type="checkbox" name="sld_enable_scroll_to_top" value="on" <?php echo (esc_attr( get_option('sld_enable_scroll_to_top') )=='on'?'checked="checked"':''); ?> />
            <i><?php echo esc_html('Show Scroll to Top.'); ?></i></td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php echo esc_html('Enable RTL Direction'); ?></th>
          <td><input type="checkbox" name="sld_enable_rtl" value="on" <?php echo (esc_attr( get_option('sld_enable_rtl') )=='on'?'checked="checked"':''); ?> />
            <i><?php echo esc_html('If you make this option ON, then list items will be arranged in Right-to-Left direction.'); ?></i></td>
        </tr>
      </table>
    </div>
    <div id="language_settings" style="display:none">
      <table class="form-table">
        <tr valign="top">
          <th scope="row"><?php echo esc_html('Add New'); ?></th>
          <td><input type="text" name="sld_lan_add_link" size="100" value="<?php echo esc_attr( get_option('sld_lan_add_link') ); ?>"  />
            <i><?php echo esc_html('Change the language for Add New'); ?></i></td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php echo esc_html('Share List'); ?></th>
          <td><input type="text" name="sld_lan_share_list" size="100" value="<?php echo esc_attr( get_option('sld_lan_share_list') ); ?>"  />
            <i><?php echo esc_html('Change the language for Share List'); ?></i></td>
        </tr>
      </table>
    </div>
    <div id="custom_css" style="display:none">
      <table class="form-table">
        <tr valign="top">
          <th scope="row"><?php echo esc_html('Custom CSS (Use *!important* flag if the changes does not take place)'); ?></th>
          <td><textarea name="sld_custom_style" rows="10" cols="100"><?php echo esc_attr( get_option('sld_custom_style') ); ?></textarea>
            <i style="display:block;"><?php echo esc_html('Write your custom CSS here. Please do not use'); ?> <b><?php echo esc_html('style'); ?></b> <?php echo esc_html('tag in this textarea.'); ?></i></td>
        </tr>
      </table>
    </div>
    <div id="custom_js" style="display:none">
      <table class="form-table">
        <tr valign="top">
          <th scope="row"><?php echo esc_html('Custom Javascript'); ?></th>
          <td><textarea name="sld_custom_js" rows="10" cols="100"><?php echo esc_attr( get_option('sld_custom_js') ); ?></textarea>
            <i style="display:block;"><?php echo esc_html('Write your custom JS here. Please do not use'); ?> <b><?php echo esc_html('script'); ?></b> <?php echo esc_html('tag in this textarea.'); ?></i></td>
        </tr>
      </table>
    </div>
    <div id="help" style="display:none">
      <table class="form-table">
        <tr valign="top">
          <th scope="row"><?php echo esc_html('Help & Troubleshooting'); ?></th>
          <td><div class="wrap">
              <div id="poststuff">
                <div id="post-body" class="metabox-holder columns-2">
                  <div id="post-body-content" style="position: relative;"> 
                    
                    <!--<div>
							<img style="width: 200px;" src="<?php echo QCOPD_IMG_URL; ?>/simple-link-directory.png" alt="Simple Link Directory">
						</div>
						
						<div class="clear">
							<?php do_action('buypro_promotional_link'); ?>
						</div>-->
                    <div class="clear"></div>
                    <h3> <?php echo esc_html('== Frequently Asked Questions =='); ?></h3>
                    <div class="qcld_sld_tabs">
                      <div class="qcld_sld_tab">
                        <input type="radio" id="qcld_sld_tab_1" name="rd">
                        <label class="qcld_sld_tab-label" for="qcld_sld_tab_1"><?php echo esc_html('= I cannot save my List. delete item or add new items to the List ='); ?></label>
                        <div class="qcld_sld_tab-content">
                          <p><?php echo esc_html('The issue you are having with saving the Lists is because of a limitation set in your server. Your server probably has a low limit for how many form fields it will process at a time. So, after you have added a certain number of links, the server refuses to save new link items. The server’s configuration that dictates this is max_input_vars. Set it to a high limit like max_input_vars = 10000. You can do it with local php.ini file or htaccess if your server supports it, Otherwise, please contact your hosting company support if needed. '); ?></p>

                        </div>
                      </div>
                      <div class="qcld_sld_tab">
                        <input type="radio" id="qcld_sld_tab_2" name="rd">
                        <label class="qcld_sld_tab-label" for="qcld_sld_tab_2"><?php echo esc_html('= The sub title is not showing ='); ?></label>
                        <div class="qcld_sld_tab-content">
                         <p><?php echo esc_html('The default template does not show subtitles. Use style-1 from the shortcode generator to display subtitles.'); ?></p>
                        </div>
                      </div>
                      <div class="qcld_sld_tab">
                        <input type="radio" id="qcld_sld_tab_3" name="rd">
                        <label class="qcld_sld_tab-label" for="qcld_sld_tab_3"> <?php echo esc_html('= Does the free version have filter buttons? ='); ?></label>
                        <div class="qcld_sld_tab-content">
                          <p><?php echo esc_html('No. It is a pro version feature at the moment. But in the future, we have plans to make it available in the free version as well.'); ?></p>
                        </div>
                      </div>
                      <div class="qcld_sld_tab">
                        <input type="radio" id="qcld_sld_tab_4" name="rd">
                        <label class="qcld_sld_tab-label" for="qcld_sld_tab_4"><?php echo esc_html('= I cannot have more than 1 columns ='); ?></label>
                        <div class="qcld_sld_tab-content">
                          <p> <?php echo esc_html('To display more than one column, you need to create multiple Lists and choose to Show All Lists from the shortcode generator. A single list will always show in a single column.'); ?></p>
                        </div>
                      </div>
                      <div class="qcld_sld_tab">
                        <input type="radio" id="qcld_sld_tab_5" name="rd">
                        <label class="qcld_sld_tab-label" for="qcld_sld_tab_5"> <?php echo esc_html('= I’m having trouble grasping the use of categories. It seems like and that they can only be assigned to a list rather than a specific link. ='); ?></label>
                        <div class="qcld_sld_tab-content">
                          <p><?php echo esc_html('The base pillars of SLD are Lists, not individual links. The most common use case scenario of SLD is to create and display multiple Lists of many Links on specific topics. As such, there is no option for a Link (list item) to belong to multiple Lists or Categories. That would make the process of creating Lists slower. For each link you would have to select a List and a Category from drop downs despite the chances of a single List item to belong to multiple Lists are usually not that high. When you have dozens or hundreds of Lists that would become a real issue to create or manage your Lists.'); ?>
                          </p>
                        </div>
                      </div>
                      <div class="qcld_sld_tab">
                        <input type="radio" id="qcld_sld_tab_6" name="rd">
                        <label class="qcld_sld_tab-label" for="qcld_sld_tab_6"><?php echo esc_html('= Do you have pagination or load more items? I have thousands of links='); ?></label>
                        <div class="qcld_sld_tab-content">
                          <p><?php echo esc_html('Items in lists can be paginated in the pro version. This option is available in the shortcode generator. But you can also add the parameters manually.'); ?></p>
                         
                          <p><?php echo esc_html('Values: “true”, “false”. This option will allow you to paginate list items. It will break the list page wise. Example: paginate_items=“true”. You also need to add the parameter per_page. This option indicates the number of items per page. Default is “5”. paginate_items=“true” is required to get this parameter working. Example: per_page=“5”'); ?></p>
                          
                          <p><?php echo esc_html('Lists themselves cannot be paginated as the main concept of SLD is to be a Simple, One Page Directory.'); ?></p>
                        </div>
                      </div>
                      <div class="qcld_sld_tab">
                        <input type="radio" id="qcld_sld_tab_7" name="rd">
                        <label class="qcld_sld_tab-label" for="qcld_sld_tab_7"><?php echo esc_html('= I have a list to import but it does not work and there is no message saying why my import does not work. ='); ?></label>
                        <div class="qcld_sld_tab-content">
                          <p><?php echo esc_html('The most common reason for failed import is encoding. The CSV file itself and characters in it must be in utf-8 format. Please check your CSV file for any unusual/non-utf-8 characters. If the problem persists, please email us the CSV file.'); ?></p>
                        </div>
                      </div>
                      <div class="qcld_sld_tab">
                        <input type="radio" id="qcld_sld_tab_8" name="rd">
                        <label class="qcld_sld_tab-label" for="qcld_sld_tab_8"><?php echo esc_html('= I have some blank pages that are crawled by Google! How to avoid them? ='); ?></label>
                        <div class="qcld_sld_tab-content">
                         <p><?php echo esc_html('Like many, if not most, WordPress plugins SLD uses custom posts and WordPress creates slug URLs even though they are not being used by SLD at the moment. We are working on making use of them.'); ?></p>
                        <p><?php echo esc_html('But rest assured they are not harmful. They are generally not linked from anywhere and not indexed by Google. The only exception is if you have an XML sitemap generator that automatically scans and generates Links to these slug URLs. Yoast SEO plugin does that. You can exclude those slugs from xml sitemap. Go to Yoast->XML Sitemap->Post Types tag and select Manage List Items (sld) to Not in sitemap – then Save.'); ?></p>
                        </div>
                      </div>
                      <div class="qcld_sld_tab">
                        <input type="radio" id="qcld_sld_tab_9" name="rd">
                        <label class="qcld_sld_tab-label" for="qcld_sld_tab_9"><?php echo esc_html('= How can I upgrade from free version of SLD to Pro version? ='); ?></label>
                        <div class="qcld_sld_tab-content">
                           <p><?php echo esc_html('1. Download the latest pro version of the plugin from website'); ?></p>
                           <p><?php echo esc_html('2. Log in to your WordPress admin area and go to the Plugins management page.'); ?></p>
                           <p><?php echo esc_html('3. Deactivate and Delete the old version of the plugin (don’t worry – your data is safe)'); ?></p>
                           <p><?php echo esc_html('4. Upload and Activate the latest pro version of the plugin'); ?></p>
                           <p><?php echo esc_html('5. You are done.'); ?></p>
                      
                        </div>
                      </div>
                      <div class="qcld_sld_tab">
                        <input type="radio" id="qcld_sld_tab_10" name="rd">
                        <label class="qcld_sld_tab-label" for="qcld_sld_tab_10"><?php echo esc_html('= I have setup List Categories and Lists and neither is showing on home page. Did I install correctly? ='); ?></label>
                        <div class="qcld_sld_tab-content">
                         <p><?php echo esc_html('You have to put the short code on the WordPress oage or post page where you want to show the List/s. There is a Shortcode generator in your page or post visual editor. Use that to create shortcode and insert to your page, where you want to display the lists, easily.'); ?></p>
                        </div>
                      </div>
                      <div class="qcld_sld_tab">
                        <input type="radio" id="qcld_sld_tab_11" name="rd">
                        <label class="qcld_sld_tab-label" for="qcld_sld_tab_11"><?php echo esc_html('= Is SLD mobile friendly? Does it function well on cells and tablets? ='); ?></label>
                        <div class="qcld_sld_tab-content">
                         <p><?php echo esc_html('Yes, all templates are mobile device friendly and Responsive.'); ?></p>
                        </div>
                      </div>



                    </div>
                   <br><br>
                    <!-- <h3><?php echo esc_html('Please take a quick look at our'); ?> <a href="http://dev.quantumcloud.com/sld/tutorials/" class="button button-primary" target="_blank"><?php echo esc_html('Video Tutorials'); ?></a></h3> -->
                    <h3><?php echo esc_html('Note'); ?></h3>
                    <p><strong><?php echo esc_html('If you are having problem with adding more items or saving a list or your changes in the list are not getting saved then it is most likely because of a limitation set in your server. Your server has a limit for how many form fields it will process at a time. So, after you have added a certain number of links, the server refuses to save the List. The server’s configuration that dictates this is max_input_vars. You need to Set it to a high limit like max_input_vars = 15000. Since this is a server setting - you may need to contact your hosting company\'s support for this.'); ?></strong></p>
                    <h3 class="qcld_short_genarator_scroll_wrap"><?php echo esc_html('Shortcode Generator'); ?></h3>
                    <p><?php echo esc_html('We encourage you to use the ShortCode generator found in the toolbar of your page/post editor in visual mode.'); ?></p>
                    <img src="<?php echo QCOPD_IMG_URL; ?>/classic.jpg" alt="shortcode generator" />
                    <p><?php echo esc_html('See sample below for where to find it for Gutenberg.'); ?></p>
                    <img src="<?php echo QCOPD_IMG_URL; ?>/gutenburg.jpg" alt="shortcode generator" /> <img src="<?php echo QCOPD_IMG_URL; ?>/gutenburg2.jpg" alt="shortcode generator" />
                    <p><?php echo esc_html('This is how the shortcode generator will look like.'); ?></p>
                    <img src="<?php echo QCOPD_IMG_URL; ?>/shortcode-generator1.jpg" alt="shortcode generator" />
                    <div>
                      <h3><?php echo esc_html('Shortcode Example'); ?></h3>
                      <p> <strong><?php echo esc_html('You can use our given SHORTCODE GENERATOR to generate and insert shortcode easily, titled as "SLD" with WordPress content editor.'); ?></strong> </p>
                      <p> <strong><u><?php echo esc_html('For all the lists:'); ?></u></strong> <br>
                        <?php echo esc_html('[qcopd-directory mode="all" column="2" style="simple" orderby="date" order="DESC" enable_embedding="false"]'); ?> <br>
                        <br>
                        <strong><u><?php echo esc_html('For only a single list:'); ?></u></strong> <br>
                        <?php echo esc_html('[qcopd-directory mode="one" list_id="75"]'); ?> <br>
                        <br>
                        <strong><u><?php echo esc_html('Available Parameters:'); ?></u></strong> <br>
                      </p>
                      <p> <strong><?php echo esc_html('1. mode'); ?></strong> <br>
                        <?php echo esc_html('[Value for this option can be set as "one" or "all".]'); ?> </p>
                      <p> <strong><?php echo esc_html('2. column'); ?></strong> <br>
                        <?php echo esc_html('Avaialble values: "1", "2", "3" or "4".'); ?> </p>
                      <p> <strong><?php echo esc_html('3. style'); ?></strong> <br>
                        <?php echo esc_html('Avaialble values: "simple", "style-1", "style-2", "style-3".'); ?> <br>
                        <strong style="color: red;"> <?php echo esc_html('Only 4 templates are available in the free version. For more styles or templates, please purchase the'); ?> <a href="<?php echo esc_url('https://www.quantumcloud.com/products/simple-link-directory/'); ?>" target="_blank" target="_blank"><?php echo esc_html('premium version'); ?></a>. </strong> </p>
                      <p> <strong><?php echo esc_html('4. orderby'); ?></strong> <br>
                        <?php echo esc_html("Compatible order by values: 'ID', 'author', 'title', 'name', 'type', 'date', 'modified', 'rand' and 'menu_order'."); ?> </p>
                      <p> <strong><?php echo esc_html('5. order'); ?></strong> <br>
                        <?php echo esc_html('Value for this option can be set as "ASC" for Ascending or "DESC" for Descending order.'); ?> </p>
                      <p> <strong><?php echo esc_html('6. item_orderby'); ?></strong> <br>
                        <?php echo esc_html('Value for this option are "title", "upvotes", "timestamp" that will be set as "ASC" & others will be "DESC" order.'); ?> </p>
                      <p> <strong><?php echo esc_html('7. list_id'); ?></strong> <br>
                        <?php echo esc_html('Only applicable if you want to display a single list [not all]. You can provide specific list id here as a value. You can also get ready shortcode for a single list under "Manage List Items" menu.'); ?> </p>
                      <p> <strong><?php echo esc_html('8. enable_embedding'); ?></strong> <br>
                        <?php echo esc_html('Allow visitors to embed list in other sites. Supported values - "true", "false".'); ?> <br>
                        <?php echo esc_html('Example: enable_embedding="true"'); ?> </p>
                      <p> <strong><?php echo esc_html('8. upvote'); ?></strong> <br>
                        <?php echo esc_html('Allow visitors to list item. Supported values - "on", "off".'); ?> <br>
                        <?php echo esc_html('Example: upvote="on"'); ?> </p>
                      <p> <strong><?php echo esc_html('9. style-16 image show'); ?></strong> <br>
                        <?php echo esc_html(' Add the shortcode parameter enable_image="true" to show images with style-16'); ?> <br>
                        <?php echo esc_html('Example: enable_image="true"'); ?> </p>

                    </div>
                    <div style="padding: 15px 10px; border: 1px solid #ccc; text-align: center; margin-top: 20px;"> <?php echo esc_html('Crafted By:'); ?> <a href="<?php echo esc_url('http://www.quantumcloud.com'); ?>" target="_blank"><?php echo esc_html('Web Design Company'); ?></a> <?php echo esc_html('- QuantumCloud'); ?> </div>
                  </div>
                  <!-- /post-body-content --> 
                  
                </div>
                <!-- /post-body--> 
                
              </div>
              <!-- /poststuff --> 
              
            </div></td>
        </tr>
      </table>
    </div>
    <?php submit_button(); ?>
  </form>
</div>
<?php
	
}

