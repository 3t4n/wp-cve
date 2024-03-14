<?php 
    error_reporting(0); 
    include_dynamic_post_scripts_for_backend(); 
    $class  = new Post_Type_Dynamic_Post;
    $json   = $class->return_result();
    $api_timeout;
    $timeout_msg;
    if(isset($json->timeout_message)){
        $api_timeout=true;
        $timeout_msg = $json->timeout_message;
    }else{
        $api_timeout=false;
    }

    $date = date("d");
    $time = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
    $time_format = $time->format('h:i:s a m/d/Y');
    $get_12_hour_time = date('h:i A', strtotime($time_format));

    ?>
    <div class="main-plugin-container">
        <div class="wrap">
            <div class="dp-section">
                <div class="row">
                    <div class="col-md-12">
                        <div class="dp-logo">
                            <img src="<?php echo plugins_url( 'assets/dp-logo.png', dirname(__FILE__) );?> ">
                            <?php ?>
                        </div>
                    </div>
                </div>
                <?php if(isset($json->timeout_message) && $json->timeout_message) { ?>
                <div class="row">
                    <div class="alert-message alert-message-info">
                        <h5>Important</h5>
                    <div><strong><?php echo $json->timeout_message;?></strong></div>
                    </div>
                </div>
            <?php } ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="clearfix"></div>
                        <ul class="nav nav-tabs tabtop">
                            <li class="active"> <a href="#common-settings" data-toggle="tab"> Common Settings </a> </li>
                            <li> <a href="#article-settings" data-toggle="tab"> Article Settings</a> </li>
                            <li> <a href="#how-to-use-shortcode" data-toggle="tab"> How to use shortcode</a> </li>
                        </ul>
                        <div class="tab-content margin-tops margin-bottoms">
                            <div class="tab-pane active fade in" id="common-settings">
                                <div class="col-md-12">
                                    <h4>API Pull Date</h4>
                                    <p>Dynamic Post plugin will auto pull your selected article categories every month on the <strong><font color="red"> 2nd of that month at 2 AM your time.</font></strong></p>
                                    <h4>API Key</h4>
                                    <p>Enter your <strong>Free/Full</strong> API Key and click <kbd>Save Changes</kbd>, wait while new setting load.</p>
                                    <h4>Custom CSS</h4>
                                    <p>If you want to add custom CSS you can write in the custom CSS field and then click on <kbd>Save Changes</kbd>, wait while new setting load.</p>
                                    <h4>Free API Key</h4>
                                    <p>The Free Key will only display current months articles. If you want to accumulate articles over time and have an <strong>"Archive"</strong> in your blog and also have <strong>Images/Videos</strong> for a more better user experience and/or for <strong>SEO</strong> purposes, please <strong>upgrade to Full Version</strong>.</p>
                                   <h4>Full API Key</h4>
<strong>Features:</strong> Article Archive for SEO starts at time of subscription, Images/Videos, SEO Meta Tags <a href="https://shop.service2client.com/subscribe-now/all/dynamic-content/wordpress-content-seo-plugin-m.html"><strong>Upgrade to Full Version</strong></a>
                                    <h4>Setup Instructions</h4>
                                    <p><a href="https://helpdesk.service2client.com/kb/a67/dynamic-post-plugin.aspx" target="_new"><strong>Need more help? Submit A Ticket</strong></a></p>
                                    <p>&nbsp;</p>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="article-settings">
                                <div class="col-md-12">
                                    <h4>Display Single Category Articles in Blog or Front facing website</h4>
                                    <p>If you want to display an article (s) of a particular category on the website, select the category from the list by clicking on the checkbox and then click on the <kbd>Post Articles</kbd> button. This will add the article to your blog and save your choice for next month's auto-post. The articles will auto-post on the 2nd or every month. </p>
                                    <h4>Retrive all Category Articles</h4>
                                    <p>If you want to retrieve the articles of all categories, select all categories from the toggle at the top of the list and then click on the <kbd>Post Articles</kbd> button to make the changes and <kbd>Post Articles</kbd> saves your choice for next month's auto-post.</p>
                                    <h4>Removing &amp; Reloading Articles</h4>
                                    <p>If you don't want a particular article to display in your blog, you must go into WordPress "Posts" and move it to the Trash. It will not be retrieved in the future if left in the WordPress "Trash."</p>
                                    <p>If you would like to retrieve the article again to update the article delete it from the "Trash" and <kbd>Post Articles</kbd> again.</p>
                                    <div class="alert-message alert-message-info">
                                        <h5>Important</h5>
                                        <p>Suppose you are switching from the Free API Key to a Full API Key in the middle of the month and want to see the articles with images/videos. In that case, you must move the articles to the Trash and Permanently Delete them, then <kbd>Post Articles</kbd> manually.</p>
                                    </div>
                                    <div class="alert-message alert-message-warning">
                                        <h5>Warning</h5>
                                        <p>Full API Key clients do not permanently delete past months' articles from the WordPress "Trash," as the Dynamic Post plugin only retrieves the current month's articles. If you delete articles from the Trash, the only way to get them back would be to request them from <a href="mailto:helpdesk@service2client.com">helpdesk@service2client.com</a> and manually copy and paste them back into your blog.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="how-to-use-shortcode">
                                <div class="col-md-12">
                                    <h4>Category Shortcode</h4>
                                    <p>If you want to display articles in a <strong>post/page</strong>, copy the category shortcode <kbd>( eg. [dynamic-post cat="your_category_name"] )</kbd> and paste it in a post/page and save the changes.</p>
                                    <h4>View All Articles On One Page</h4>
                                    <p><strong> <kbd>[dynamic-post] </kbd></strong></p>
                                    <h4>Archive Shortcode</h4>
                                    <p>If you want to display our articles for a specific date, copy the archive shortcode <kbd>(e.g. [dynamic-posts cat="your_category_name" month="month_name" year="year"] )</kbd> and paste it into any post or page.</p>
                                    <div class="alert-message alert-message-info">
                                        <h5>Important</h5>
                                        <p>Note that the Archive Shortcode will only work for the Full API Key.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>

                <div class="row">
                    <?php
                        $get_full_api_key_from_database = get_option('api_key');                   
                        if( $json->message == 'Valid Licensed API Key; Articles found' )
                        { ?>
                            <div class="col-md-6">
                                <div class="alert alert-info">
                                    <span class="glyphicon glyphicon-info-sign"></span> <strong>Full API Key</strong>
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <hr class="message-inner-separator">
                                    <p><?php echo $get_full_api_key_from_database; ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="alert alert-info">
                                    <span class="glyphicon glyphicon-info-sign"></span> <strong>Free API Key</strong>
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <hr class="message-inner-separator">
                                    <p>79C6DA03-9130-4649-8448-15B4AB2CC7DF</p>
                                </div>
                            </div>
                        <?php
                        }
                        else if( $json->message == 'Free API Key Articles' )
                        { ?>
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <span class="glyphicon glyphicon-info-sign"></span> <strong>Free API Key</strong>
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <hr class="message-inner-separator">
                                    <p>79C6DA03-9130-4649-8448-15B4AB2CC7DF</p>
                                </div>
                            </div>
                        <?php
                        }
                        else
                        { ?>
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <span class="glyphicon glyphicon-info-sign"></span> <strong>Free API Key</strong>
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <hr class="message-inner-separator">
                                    <p>79C6DA03-9130-4649-8448-15B4AB2CC7DF</p>
                                </div>
                            </div>
                        <?php
                        }
                    ?>
                </div>
                <form method="post" action="options.php" >
                    <div class="panel panel-primary">
                        <div class="panel-heading">Common Settings</div>
                        <div class="panel-body">
                            <div class="settings_options_table">
                                <?php @settings_fields('wp_plugin_dynamic_post-group'); ?>
                                <?php /*changed settings field*/ ?>
                                <?php @do_settings_fields('dynamic_post','wp_plugin_dynamic_post-group'); ?>
                                <?php do_settings_sections('wp_plugin_dynamic_post'); ?>
                            </div>
                            <div class="plugin-buttons">
                                <?php @submit_button(); ?>
                                <a href="https://shop.service2client.com/subscribe-now/all/dynamic-content/wordpress-content-seo-plugin-m.html" class="button button-warning" target="_blank"><i class="fa fa-refresh"></i> Upgrade to Full Version</a>
                                <a href="https://dynamicontent.net/" class="button button-warning" target="_blank"><i class="fa fa-search"></i> Search Articles</a>
                                <a href="https://shop.service2client.com/subscribe-now/all/dynamic-content/dc-trust-writer.html" class="button button-warning" target="_blank"><i class="fa fa-shopping-cart"></i> Order Custom Content</a>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-primary">
                        <div class="panel-heading">Article Display Settings</div>
                        <div class="panel-body">
                            <p>Select the categories you want to display in your Blog, then click on <kbd>Post Articles</kbd> button below. If you want to display articles on a <strong>page/post</strong>, you can copy and paste the shortcode into that page. The category shortcodes will work for Free &amp; Full versions.</p>
                            <p>To display all of the selected articles on one page/post, place the shortcode <kbd>[dynamic-post]</kbd> anywhere on your site.
                                  </p><p>If you want to display articles in a <strong>post/page</strong>, copy the category shortcode <kbd>( eg. [dynamic-post cat="your_category_name"] )</kbd> and paste it in a post/page and <kbd>Save Changes</kbd></p>
                                  
                                  
                                  <div class="table-responsive">
                                    <table class="table table-striped second_options">
                                    <?php
                                        $saved_cats = get_option('saved_cats');
                                        $curdate = date('Y-m');
                                       
                                        if ( $json->message == 'Valid Licensed API Key; Articles found' ){
                                            if ($date <= '02'){
                                                if ($date = '02' && $get_12_hour_time == '02:00 AM') {
                                                    echo '<thead>
                                                                <tr class="info">
                                                                    <th>
                                                                        <label class="custom-control custom-checkbox">
                                                                            <input type="checkbox" checked id="parent" name="check-all" class="custom-control-input" value="1">
                                                                            <span class="custom-control-indicator"></span>Categories
                                                                        </label>
                                                                    </th>
                                                                    <th>Articles</th>
                                                                    <th>Start Date</th>
                                                                    <th>Shortcodes</th>
                                                                </tr>
                                                        </thead>';
                                                    foreach($json->articlelistnewarray as $key=> $dyc)
                                                    { ?>
                                                        <tr>
                                                            <td>
                                                                <label class="custom-control custom-checkbox">
                                                                    <input type="checkbox" <?php in_array($key, $saved_cats) ? ' checked' : ''; ?> name="<?php echo $key; ?>" class="child custom-control-input" value="1">
                                                                    <span class="custom-control-indicator"></span><?php echo $key; ?>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                
                                                                foreach($dyc as $dyc1)
                                                                    {
                                                                        $ptitle = $dyc1->title;
                                                                        $pcatstartdate = $dyc1->catstartdate;
                                                                        $pshortcode = $dyc1->catshortcode;
                                                                        ?>
                                                                        <?php echo $ptitle; ?><br><br>
                                                                        <?php
                                                                    }
                                                                ?>
                                                            </td>
                                                            <td><?php echo $pcatstartdate; ?></td>
                                                            <td><?php echo $pshortcode; ?></td>
                                                        </tr>
                                                    <?php
                                                    }
                                                }else{
                                                    echo '<h2><strong>Dynamic Post will retrieve your articles on the 2nd at 11:55 PM CST</strong></h2>';
                                                }
                                            }
                                            else
                                            {
                            
                                                echo '<thead>
                                                        <tr class="info">
                                                            <th>
                                                                <label class="custom-control custom-checkbox">
                                                                    <input type="checkbox" checked id="parent" name="check-all" class="custom-control-input" value="1">
                                                                    <span class="custom-control-indicator"></span>Categories
                                                                </label>
                                                            </th>
                                                            <th>Articles</th>
                                                            <th>Start Date</th>
                                                            <th>Shortcodes</th>
                                                        </tr>
                                                    </thead>';
                                                foreach($json->articlelistnewarray as $key=> $dyc)
                                                { ?>
                                                    <tr>
                                                        <td>
                                                            <label class="custom-control custom-checkbox">
                                                                <input type="checkbox" <?php if($saved_cats){in_array($key, $saved_cats) ? ' checked' : ''; }?> name="<?php echo $key; ?>" class="child custom-control-input" value="1"
                                                                <?php 
                                                                if($saved_cats){
                                                                    echo ( is_array( $saved_cats ) && in_array( addslashes($key), $saved_cats[0]) ) ? " checked" : " "; 
                                                                }
                                                                ?>
                                                                >
                                                                <span class="custom-control-indicator"></span><?php echo $key; ?>
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                foreach($dyc as $dyc1)
                                                                {
                                                                    $ptitle = $dyc1->title;
                                                                    $pcatstartdate = $dyc1->catstartdate;
                                                                    $pshortcode = $dyc1->catshortcode;
                                                                    ?>
                                                                    <?php echo $ptitle; ?><br><br>
                                                                    <?php
                                                                }
                                                            ?>
                                                        </td>
                                                        <td><?php echo $pcatstartdate; ?></td>
                                                        <td><?php echo $pshortcode; ?></td>
                                                    </tr>
                                                <?php
                                                }
                                            }
                                            //echo '<pre>';  print_r($json->articlelistnewarray); die;
                                        }
                                        else if ( $json->message == 'Free API Key Articles' )
                                        {
                                            echo '<thead>
                                                    <tr class="info">
                                                        <th>
                                                            <label class="custom-control custom-checkbox">
                                                                <input type="checkbox" checked id="parent" name="check-all" class="custom-control-input" value="1">
                                                                <span class="custom-control-indicator"></span>Categories
                                                            </label>
                                                        </th>
                                                        <th>Articles</th>
                                                        <th>Shortcodes</th>
                                                    </tr>
                                                </thead>';
                                            foreach( $json->articlelistnewarray as $key=> $dyc )
                                            { ?>
                                                <tr>
                                                    <td>
                                                        <label class="custom-control custom-checkbox">
                                                            
                                                            <input type="checkbox" <?php if($saved_cats){in_array($key, $saved_cats) ? 'checked' : 'c'; }?> name="<?php echo $key; ?>" class="child custom-control-input asd" value="1"
                                                            <?php 
                                                            if($saved_cats){
                                                                echo ( is_array( $saved_cats ) && in_array( addslashes($key), $saved_cats[0]) ) ? " checked" : " "; 
                                                            }
                                                                ?>
                                                            >
                                                            <span class="custom-control-indicator"></span><?php echo $key; ?>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <?php
                                                            foreach($dyc as $dyc1)
                                                            {
                                                                $ptitle = $dyc1->title;
                                                                $pshortcode = $dyc1->catshortcode;
                                                                ?>
                                                                <?php echo $ptitle; ?><br><br>
                                                                <?php
                                                            }
                                                        ?>
                                                    </td>
                                                    <td><?php echo $pshortcode; ?></td>
                                                </tr>
                                            <?php
                                            }
                                        }
                                        else
                                        { ?>
                                            <thead>
                                                <tr class="info">
                                                    <th>Categories</th>
                                                    <th>Articles</th>
                                                    <th>Shortcodes</th>
                                                </tr>
                                            </thead>
                                            <tr class="danger">
                                                <td colspan="3"><center>No articles found !</center></td>
                                            </tr>
                                        <?php
                                        }
                                    ?>
                                </table>
                            </div>
                            <div id="success-message" class="alert alert-success fade in alert-dismissable"></div>
                            <div id="con-refreshButton">
                                <button type="button" id="refreshButton" class="button button-primary" data-loading-text="<i class='fa fa-refresh fa-spin'></i> Processing articles, Please wait..."> Post Articles</button>
                                <!--<div class="uil-reload-css hide" style="-webkit-transform:scale(0.19)"><div>-->
                            </div>
                        </div>
                    </div>
                    <?php                  
                        if($json->message == 'Valid Licensed API Key; Articles found')
                        { ?>
                            <div class="panel panel-primary">
                                <div class="panel-heading">Article Archives</div>
                                <div class="panel-body">
                                    <p>Copy &amp; Paste the shortcode in a <strong>page/post</strong> to desplay articles for the specific month. You can paste multiple shortcodes anywhere on your site.</p><p>If you want to display our articles for a specific date, copy the archive shortcode <kbd>(e.g. [dynamic-posts cat="your_category_name" month="month_name" year="year"] )</kbd> and paste it into any post or page.</p>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr class="info">
                                                    <th align="left">Categories</th>
                                                    <th align="left">Articles</th>
                                                    <th align="left">Shortcodes</th>
                                                </tr>
                                            </thead>
                                            <?php                                            
                                                //echo '<pre>';  print_r($json);die;
                                                foreach($json->articlelistnewarray as $key=> $dyc)
                                                { ?>
                                                    <tr>
                                                        <td><?php echo $key; ?></td>
                                                        <td>
                                                            <?php
                                                                foreach($dyc as $dyc1)
                                                                {
                                                                    $ptitle = $dyc1->title;
                                                                    $particleshortcode = $dyc1->articleshortcode;
                                                                    ?>
                                                                    <?php echo $ptitle; ?><br><br>
                                                                    <?php
                                                                }
                                                            ?>
                                                        </td>
                                                        <td><?php echo $particleshortcode; ?></td>
                                                    </tr>
                                                <?php
                                                }
                                            ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                    ?>
                </form>
            </div>
        </div>
    </div>
    <!-- Main Pugin Container div ends here -->


    <div class="modal fade product_view" id="terms_of_service_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <a href="#" data-dismiss="modal" class="class pull-right"><span class="glyphicon glyphicon-remove"></span></a>
                    <h3 class="modal-title">Terms of Service</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h4>Please read our terms of service.</h4>
                            <span>The use of this plugin is subject to the following terms of service:</span><br/><br/>
                            <ul>
                                <li>The Disclaimer at the bottom of the articles has hyperlinks to service2client.com. These hyperlinks may change without notice.</li>
                                <li>You are also agreeing to the Terms of Service for <a href="https://www.service2client.com/terms-and-conditions">Service2Client.com</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer ng-scope">
                    <button class="btn btn-primary" id="agree">Agree</button>
                    <button class="btn btn-warning" id="disagree">Disagree</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        jQuery(document).ready(function($){
            jQuery('#refreshButton').click(function(){        
                var api_timeout = "<?php echo $api_timeout;?>";
                var timeout_msg =  "<?php echo $timeout_msg;?>";
                if(api_timeout==true){
                    alert(timeout_msg);
                    return false;
                }else{
                    var catname = [];
                    $('.child').each(function(i,v){
                        if(this.checked)
                            catname.push(v.name);
                    });
                    if(catname.length){
                        var $this = $(this);
                        $this.button('loading');
                        jQuery.ajax({
                            type: 'POST',
                            url: "<?php echo esc_url( home_url() ) ?>/wp-admin/admin-ajax.php",
                            data : {
                                        data_catgname : catname,
                                        action : 'api_call',
                                },
                            success: function(data){

                                $('#refreshButton').show();
                                setTimeout(function(){
                                $this.button('reset');
                                });
                                $('#success-message').show();
                                $("#success-message").html("<i class='fa fa-check-circle fa-3'></i> Articles retrieved ! Please navigate to Posts->All Posts to view the articles.");
                                $("#success-message").fadeTo(6000, 500).slideUp(500, function(){
                                    $("#success-message").slideUp(500);
                                });
                            }
                        });
                    }
                    else {
                        var date = new Date();
                        if (date.getDate() <= '02'){
                            alert('Dynamic Post will retrieve your articles on the 2nd at 11:55 PM CST')
                        }else{
                            alert('Please select atleast one category to retrive articles !');
                        }
                    
                        return false;
                    }
                }
                //console.log(catname);
                //return false;
            });
            jQuery('#feat_ured').parent().parent().parent().before('<tr class="cache-message"><th></th><td class="message" style="position:relative; left:-215px"><strong>Important! - Save Settings and clear all WordPress and hosting caching plugins to see these updates!</strong></td></tr>');
        });
        jQuery(document).ready(function($){
            jQuery.ajax({
            type: 'POST',
            url: "<?php echo esc_url( home_url() ) ?>/wp-admin/admin-ajax.php",
            data:{
                    action : 'check_api_type',
                },
                success: function(data){
                    $('.pluging-type').text(data);
                    if(data == 'Free API Key' || $('#api_key').val()=='79C6DA03-9130-4649-8448-15B4AB2CC7DF'){
                        $('div.settings_options_table tr:nth-child(4)').hide();//hide meta deta
                        $('div.settings_options_table tr:nth-child(6)').hide();//message
                        $('div.settings_options_table tr:nth-child(7)').hide();//Hide Content Image
                        $('div.settings_options_table tr:nth-child(8)').hide();//Featured Thumbnail
                        $('div.settings_options_table tr:nth-child(9)').hide();//Featured Image
                        $('.settings_options_table .pluging-type.api-key-msg').text('Free API Key');
                    }else if(data == 'Full API Key'){
                        $('.settings_options_table .pluging-type.api-key-msg').text('Full API Key');
                    }else if(data == 'Invalid API Key / API Key not found'){
                        $('div.settings_options_table tr:nth-child(4)').hide();//hide meta deta
                        $('div.settings_options_table tr:nth-child(6)').hide();//message
                        $('div.settings_options_table tr:nth-child(7)').hide();//Hide Content Image
                        $('div.settings_options_table tr:nth-child(8)').hide();//Featured Thumbnail
                        $('div.settings_options_table tr:nth-child(9)').hide();//Featured Image
                        $('.settings_options_table .pluging-type.api-key-msg').text('Invalid API Key');
                    }
                    else if(data.trim()=='' || $('#api_key').val().length ==0){
                        $('.settings_options_table .pluging-type.api-key-msg').text('Invalid API Key');
                    }
                }
            });
        });
        jQuery(document).ready(function(){
            jQuery("#parent").click(function(){
                jQuery(".child").prop("checked", this.checked);
            });
            jQuery('.child').click(function(){
                if (jQuery('.child:checked').length == jQuery('.child').length){
                    jQuery('#parent').prop('checked', true);
                } else {
                    jQuery('#parent').prop('checked', false);
                }
            });
            jQuery("#terms_of_use").click(function (event){
                        event.preventDefault();
                jQuery('#terms_of_service_modal').modal({
                    show: true,
                    backdrop: true
                });
            });

            jQuery("#agree").click(function (event){
                event.preventDefault();
                jQuery("#terms_of_use").prop("checked", true); 
                jQuery("#terms_of_service_modal").modal("hide");
            });
            jQuery("#disagree").click(function (event){
                event.preventDefault();
                jQuery("#terms_of_use").prop("checked", false); 
                jQuery("#terms_of_service_modal").modal("hide");
            });

        });


        jQuery('#hide_images').on('change', function() { 
        if (!this.checked) {
            jQuery('.toggle_hide_images').text('Pages Thumbnail Image is NOT visible (Used on WordPress Editor like Beaver Builder, Elementor, Divi etc.)');
        }else{
            jQuery('.toggle_hide_images').text('Pages Thumbnail Image is visible (Used on WordPress Editor like Beaver Builder, Elementor, Divi etc.).');
        }
        });

        jQuery('#feat_ured').on('change', function() { 
        if (!this.checked) {
            jQuery('.toggle_feat_ured').text('Post Embeded Content Thumbnail Image is Not visible.');
        
        }else{
            jQuery('.toggle_feat_ured').text('Post Embeded Content Thumbnail Image is visible.');
        }
        });

        jQuery('#feat_ured2').on('change', function() { 
        if (!this.checked) {
            jQuery('.toggle_feat_ured2').text('Post Featured Image is NOT visible.');
        
        }else{
            jQuery('.toggle_feat_ured2').text('Post Feature Image is visible.');
        }
        });

        jQuery('#auto_up').on('change', function() { 
        if (!this.checked) {
            jQuery('.toggle_auto_up').text('Auto Posting is Turned OFF.');
        }else{
            jQuery('.toggle_auto_up').text('Auto Posting is Turned ON.');
        }
        });

        jQuery('#hide_metadata').on('change', function() { 
        if (!this.checked) {
        jQuery('.toggle_hide_metadata').text('Use this option if you are doing your own SEO on these posts.');
        }else{
            jQuery('.toggle_hide_metadata').text('Preoptimized Meta keywords & Description.');
        }
        });
        jQuery('#canonical_metadata').on('change', function() { 
        if (!this.checked) {
        jQuery('.toggle_canonical_metadata').text('Dynamicontent.net canonical.');
        }else{
            jQuery('.toggle_canonical_metadata').text('Yourdomain.com canonical.');
        }
        });

        // function myalert(){
        //     var alerted = localStorage.getItem('alerted') || '';
        //         if (alerted != 'yes') {
        //         alert("If your changes are not affected , please clear cache.");
        //         localStorage.setItem('alerted','yes');
        //         }
        // }


    </script>