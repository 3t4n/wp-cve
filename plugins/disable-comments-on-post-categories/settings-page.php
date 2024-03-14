
<?php if ( is_multisite() ) { ?>


  <h3><?php _e("Currently Multi-Site is not Supported", "st_disable_post_cat"); 
  
  exit;?></h3>

<div class="wrap">
    <div class="welcome-panel" id="welcome-panel">
        <div class="welcome-panel-content">
            <h3><?php _e("Disable Comments on Post Categories", "st_disable_post_cat"); ?></h3>
            <p><strong><?php _e("Super Simple & Fast Way To Disable comments on Posts of specific Categories.","st_disable_post_cat"); ?></strong></p>
        </div>
    </div>



  
 <?php } ?>
<?php


$current = $_GET['tab'];
if(!$current) $current = "st_homepage";
$tabs = array( 'st_homepage' => 'Settings', 'st_faq' => 'FAQ');
echo '<h3 class="nav-tab-wrapper" style="width:100%">';
foreach( $tabs as $tab => $name )
{


    $class = ( $tab == $current ) ? ' nav-tab-active' : '';
    echo "<a class='nav-tab$class' href='?page=disable_categories_comments&tab=$tab' style='font-weight:200;
font-size:16px;line-height:24px;'>$name</a>&nbsp;";

}
echo '</h3>';
?>


    <?php
	
	if (!empty($_POST))
	
	{

		
	update_option('st_disable_comments_post_cat', $_POST['disable']);
	
	
	
	}
	
    if(isset($_GET['tab']))
        $SeletedTab = $_GET['tab'];
    else
        $SeletedTab = 'st_homepage';

    if ($SeletedTab=='st_homepage') { ?>



        <div id="dashboard-widgets-wrap">

            <div class="metabox-holder columns-2" id="dashboard-widgets">
                <!--left side panel-->
                <div class="postbox-container" id="postbox-container-1" style="width: 70%">
                    <div class="meta-box-sortables ui-sortable" id="normal-sortables"><div class="postbox " id="dashboard_right_now">
                            <div title="Click to toggle" class="handlediv"><br></div>
                            <h3 class="hndle" style="font-size:18px"><span><?php _e("Disable Comments on the Following Post Categories", "st_disable_post_cat"); ?></span></h3>
						
                            <div class="inside" style="padding-right:10px">


                                <form method="post" action="#">

                                <div class="section" style="font-size:15px">
                                    
									

                                    <?php
									
										$all_cats =  get_categories( array('type'=>'page','taxonomy' =>'category','hide_empty' => 0) );
										
										$disable_cat = get_option('st_disable_comments_post_cat');
										
                                
          								// When running for the first time, $disable_cat will be an empty string. 
			        						//So this check will convert it into array so that we can do array comparison
					           				if(empty($disable_cat)){$disable_cat = array();}
								

                                    foreach ( $all_cats as $all_cat ) {
                                             $checked = "";
										
										  if(empty($disable_cat)){$disable_cat = array();}
										
											  
											if(  in_array($all_cat->term_id, $disable_cat))
											
											{
											$checked = 'checked';
											}
											
                                        									
										
										     
											 

										?>


                                     <p  style="padding-left:10px; font-size:15px;"><input type="checkbox" name="disable[]" value="<?php echo $all_cat->term_id;?>"<?php echo $checked;?>><?php echo $all_cat->name;?> (<?php echo $all_cat->count; ?> posts)   </p>




                                        <?php    }   ?>

                                </div>
								<h3></h3>

                                <div class="section" style="padding:20px 10px 10px 10px;">
                                    <input type="submit" name="option_submit" id="option_submit" class="btn btn-primary" value="<?php _e('Update',"st_disable_post_cat"); ?>" />
                                </div>
                                    </form>

                                <br class="clear">
                            </div>
                        </div>
                    </div>
                </div>


                <!--rigth side panel-->
                <!--<div class="postbox-container" id="postbox-container-2">
                <div class="meta-box-sortables ui-sortable" id="side-sortables"><div class="postbox " id="dashboard_quick_press">
                        <div title="Click to toggle" class="handlediv"><br></div><h3 class="hndle"><span>Webriti Premium Plugins & Themes Shop</span></h3>
                        <div class="inside" style="text-align: center;">
                            <p><strong>Our Recently WordPress Premium Themes</strong></p>
                            <ul>
                                <li><h3>Spa Salon</h3></li>
                                <li><img class="theme-snaps" src="<?php /*echo plugins_url("other-snaps/spasalon.png", __FILE__); */?>" /></li>
                                <br>
                                <li><h3>Busiprof</h3></li>
                                <li><img class="theme-snaps" src="<?php /*echo plugins_url("other-snaps/busiprof.png", __FILE__); */?>" /></li>
                                <br>
                                <li><h3>Rambo (coming soon)</h3></li>
                                <li><img class="theme-snaps" src="<?php /*echo plugins_url("other-snaps/rambo.png", __FILE__); */?>" /></li>
                            </ul>
                            <br>
                            <a href="http://www.webriti.com" target="_blank" class="button button-primary button-large">Checkout All Themes At Webriti.Com</a>
                        </div>
                    </div>
                </div>
            </div>-->
                <div class="clear"></div>
            </div>
        </div>

  
        <div id="dashboard-widgets-wrap">

           
    <?php } ;?>






 <?php if ($SeletedTab=='st_faq') { ?>


 <?php 
global $current_user ;

get_currentuserinfo();
//print_r($current_user); ?>


        <div id="dashboard-widgets-wrap">

            <div class="metabox-holder columns-2" id="dashboard-widgets">
                <!--left side panel-->
                <div class="postbox-container" id="postbox-container-1" style="width: 70%">
                    <div class="meta-box-sortables ui-sortable" id="normal-sortables"><div class="postbox " id="dashboard_right_now">
                            <div title="Click to toggle" class="handlediv"><br></div>
                            <h3 class="hndle" style="font-size:18px"><span><?php _e("What it Does?", "st_disable_post_cat"); ?></span></h3>
							
                            <div class="inside" style="padding-right:10px">
<div class = "welcome-panel-content" style="text-align: left; font-size: 15px; line-height:1.6em;">

As the name suggests, this plug-in lets you Disable Comments on Posts of specific Categories.
<br>
<br>
<strong style="font-size: 16px;">The plugin will:</strong> 
<br class="clear">
<br>
<strong>1.</strong> Disables the comment form. 
<br> <strong>2.</strong> Hide the existing comments , if any. 
<br> <strong>3.</strong> De-register the comment-reply script on selected categories. 
  <br><br>if you have any feature request then share them on the forums and we will try our best to incorporate. 
<br>

</div>
                                

                                <br class="clear">
                            </div>
                        </div>
                    </div>
                </div>


              
                <div class="clear"></div>
            </div>
        </div>

  
        <div id="dashboard-widgets-wrap">

            <div class="metabox-holder columns-2" id="dashboard-widgets">
                <!--left side panel-->
                <div class="postbox-container" id="postbox-container-1" style="width: 70%">
                    <div class="meta-box-sortables ui-sortable" id="normal-sortables"><div class="postbox " id="dashboard_right_now">
                            <div title="Click to toggle" class="handlediv"><br></div>
                            <h3 class="hndle" style="font-size:18px"><span><?php _e("About", "st_disable_post_cat"); ?></span></h3>
                            <div class="inside" style="padding-right:10px">


                           
						   <div class = "welcome-panel-content" style="text-align: left; font-size: 15px; line-height:1.6em;">

<h2>Hi  <strong><?php echo $current_user->display_name;?></strong>, </h2><br>
My name is <strong>Ankit</strong> and I am the developer of this plugin. 

<br><br>I am an entrepreneur who is in love with wordpress. I am currently working on my <strong><a href = "http://spoontalk.com">startup Spoontalk. </a></strong>

<br>
<br>
<strong style="font-size: 16px;">What is Spoontalk?</strong> 
<br class="clear">
<br>
<strong>1.</strong> Spoontalk lets you create and send beautiful email newsletters in minutes. <a href = "http://spoontalk.com"> Do check it out</a>
<br> <strong>2.</strong> We also regularly publish Wordpress related Tutorials. Check them out <a href = "http://spoontalk.com/wp">here</a>

<br>

</div>
                                

                                <br class="clear">
						   
						   
						   
						   

                                <br class="clear">
                            </div>
                        </div>
                    </div>
                </div>
				</div>

    <?php } ;?>

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
