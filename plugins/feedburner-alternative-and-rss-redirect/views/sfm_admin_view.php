<?php
define('SFM_CONNECT_LINK','https://api.follow.it/?');
define('SFM_BETTER_FEED',"https://api.follow.it/feedburner-alternative");
define('SFM_MAIN_FEED',sfm_get_bloginfo('rss2_url'));

$sfmRedirectObj = new sfmRedirectActions();
$feeds_data = $sfmRedirectObj->sfmListActiveRss();

/* main button classes */
$mainfeed_data = $sfmRedirectObj->sfmCheckActiveMainRss();
/* check for feedburner */
$maintn_cls = "process_butt_large  activate_redirect";
$main_title = "Click here to activate redirect";
$main_text  = "Click here to activate redirect";
$show_box1  = "none";
$show_box2  = "none";
$show_revers= false;
$main_sub_url= "javascript:void(0);";

$comment_cls    = "activate_redirect";
$comment_title  = "Activate Redirect";
$comment_text   = "Activate Redirect";
$comment_box    = false;
$comment_sub_url= "javascript:void(0);";
$comment_feed_url   = $feeds_data['comment_url'];
$comment_revers = false;

foreach ($mainfeed_data as $feedData)
{
    if($feedData['feed_type'] == "main_rss")
    {
        $maintn_cls = "process_butt_large  sfrd_finish";
        $main_title = "Redirect is active!";
        $main_text  = "Redirect is active!";
        $main_sub_url   = $feedData['feed_url'];
        $show_revers    = true;

        if($sfmRedirectObj->sfm_CheckFeedBurner())
        {
            $show_box2="block";
            $show_box1="none";
        }
        else
        {
            $show_box2="none";
            $show_box1="block";
        }
        $main_feedId=$feedData['sf_feedid'];
    }

    if($feedData['feed_type'] =="comment_rss")
    {
        $comment_cls="sfrd_redirect_active";
        $comment_title="Redirect is active!";
        $comment_text="Redirect is active!";
        $comment_box=true;
        $comment_sub_url=$feedData['feed_url'];
        $comment_feed_url=$feedData['feed_url'];
        $comment_revers=true;
        $comment_feedId=$feedData['sf_feedid'];
    }
}
?>

<!-- main admin section area -->
<div class="sfrd_wapper">
    <div class="sfrd_wapper_conatnt">
        <h2>Welcome to the follow.it RSS Redirect plugin</h2>

        <p>
            This plugin takes care of all your RSS redirects so that you can use follow.it (which is also the <a href="<?php echo SFM_BETTER_FEED; ?>" title="<?php echo SFM_BETTER_FEED; ?>" target="_new"><strong>better Feedburner</strong></a>).
        </p>
        <p>
            Click on the button below to activate the redirect of your main RSS feed (<a href="<?php echo SFM_MAIN_FEED; ?>" title="<?php echo SFM_MAIN_FEED; ?>" target="_new"><?php echo SFM_MAIN_FEED; ?></a>) to your feed on follow.it. <strong>You can always reverse it again</strong>.
        </p>

        <div class="sfrd_wapper_large_button_main">
            <div class="sfrd_wapper_large_button">
               <a href="javascript:void(0);" id="main_rss" red_type="main_rss" title="<?php echo $main_title; ?>"  class="<?php echo $maintn_cls; ?>"><?php echo $main_text; ?></a>
               <a href="<?php echo (strpos($main_sub_url,'api.follow.it')>0)?str_replace('api.follow.it','follow.it',$main_sub_url):$main_sub_url; ?>" target="_new" style="display: <?php echo ($show_revers)? "block" : 'none' ?>" title="Open the new feed " class="open_new_feed">Open the new feed</a>
               <a href="javascript:void(0);" style="display: <?php echo ($show_revers)? "block" : 'none' ?>" red_type="main_rss" feed_id="<?php if(isset($main_feedId)) {echo $main_feedId;} ?>" title="Reverse redirect" class="reverse_redirect">Reverse redirect</a>
            </div>
        </div>

        <!-- activation error box -->
        <div class="sfrd_green_box1 sfrd_three sfm_error" style="display: none;">
            <p></p>
        </div>
        <!-- activation error box -->

        <!-- activation green box -->
        <div class="sfrd_green_box1 sfrd_one sfm_box_main_box1" style="display: <?php echo $show_box1; ?>">
            <p>If you're a former Feedburner user make sure to also <strong>redirect your Feedburner feed</strong> to your original feed. <strong class="inc_pop" style="cursor: pointer;"><u>Need instructions?</u></strong></p>
            <p>We also suggest that you connect your feed to an account on follow.it (it's FREE):</p>
            <ul>
                <li>
                    You'll be able to <strong>import email subscribers</strong> (important if you had Feedburner email subscribers)
                </li>
                <li>You'll get <strong>access to enlightening statistics</strong></li>
                <li>You'll get <strong>listed in our blog directory</strong> - getting you more readers!</li>
            </ul>

            <form id="calimingOptimizationForm" method="get" action="https://api.follow.it/wpclaimfeeds/getFullAccess" target="_blank">
                <div class="sfsi_plus_inputbtn">
                    <input type="hidden" name="feed_id" value="<?php if(isset($main_feedId)) {echo $main_feedId;} ?>" />
                    <input type="email" name="email" value="<?php echo bloginfo('admin_email'); ?>"  />
                    <input type="hidden" name="ActRedirect_nonce" value="<?php echo wp_create_nonce("ActRedirect"); ?>">
                     <input type="hidden" name="sfmReverseRedirect_nonce" value="<?php echo wp_create_nonce("sfmReverseRedirect"); ?>">
                     <input type="hidden" name="sfmProcessFeeds_nonce" value="<?php echo wp_create_nonce("sfmProcessFeeds"); ?>">
                </div>
                <div class='sfsi_plus_more_services_link'>
                    <a class="pop-up" href="javascript:" id="mainRssconnect" title="Connect feed to a follow.it account >">
                        Connect feed to a follow.it account >
                    </a>
                </div>
                <p>
                    This will create you FREE account on follow.it, using above email<br>
                    All data will be treated highly confidentially, see the <a href="https://follow.it/info/privacy" target="_blank">Privacy Policy</a>
                </p>
            </form>
        </div>

        <div class="sfrd_green_box sfrdFeedBurnerBox" style="display: <?php echo $show_box2; ?>">
            <ul>
                <li>
                    <div class="sfrd_list_number">1</div>
                    <div class="sfrd_list_contant"><span>Insert the new subscription form</span>
                    We noticed you're using a <em>Feedburner subscription form</em> on your website. If you want to continue to use a form you need to insert the new form. Go to <strong onclick="window.location='widgets.php'" style="cursor: pointer;"><u>widgets</u></strong> and drag &amp; drop it to your sidebar.</div>
                </li>
                <li>
                    <div class="sfrd_list_number">2</div>
                    <div class="sfrd_list_contant"><span>Redirect your Feedburner feed</span>
                    Some of your subscribers may have an url like &ldquo;http://feeds.feedburner.com/yourblog&rdquo; in their feed readers. To fix this, follow <strong class="inc_pop"><u>these instructions.</u></strong></div>
                </li>
                <li>
                    <div class="sfrd_list_number">3</div>
                    <div class="sfrd_list_contant"><span>Connect your feed to a follow.it account</span>
                    We also suggest that you connect your feed to an account on follow.it (it's FREE):</div>
                    <ul>
                        <li>You'll be able to <strong>import email subscribers</strong> (important if you had Feedburner email subscribers)</li>
                        <li>You'll get <strong>access to enlightening statistics</strong></li>
                        <li>You'get <strong>listed in our blog directory</strong> - getting you more readers!</li>
                    </ul>
                </li>
            </ul>

            <form id="calimingOptimizationForm" method="get" action="https://api.follow.it/wpclaimfeeds/getFullAccess" target="_blank">
                <div class="sfsi_plus_inputbtn">
                    <input type="hidden" name="feed_id" value="<?php if(isset($main_feedId)) {echo $main_feedId;} ?>" />
                    <input type="email" name="email" value="<?php echo bloginfo('admin_email'); ?>"  />
                    <input type="hidden" name="ActRedirect_nonce" value="<?php echo wp_create_nonce("ActRedirect"); ?>">
                     <input type="hidden" name="sfmReverseRedirect_nonce" value="<?php echo wp_create_nonce("sfmReverseRedirect"); ?>">
                     <input type="hidden" name="sfmProcessFeeds_nonce" value="<?php echo wp_create_nonce("sfmProcessFeeds"); ?>">
                </div>
                <div class='sfsi_plus_more_services_link'>
                    <a class="pop-up" href="javascript:" id="mainRssconnect" title="Connect feed to a follow.it account >">
                        Connect feed to a follow.it account >
                    </a>
                </div>
                <p>
                    This will create you FREE account on follow.it, using above email<br>
                    All data will be treated highly confidentially, see the <a href="https://follow.it/info/privacy" target="_blank">Privacy Policy</a>
                </p>
            </form>
        </div>
        <!-- end active green box -->


        <!-- all other active feeds section -->
        <p class="bottom_txt">
            You also seem to offer some secondary feeds - click on &ldquo;Activate Redirect&rdquo; to apply the redirect for those as well.
        </p>
        <div class="sfrd_feedmaster_main">
           <div class="sfrd_feedmaster_tab">
                <h3>
                    Comments feed<span><a href="<?php echo (strpos($comment_sub_url,'api.follow.it')>0)?str_replace('api.follow.it','follow.it',$comment_sub_url):$comment_feed_url; ?>" title="<?php echo (strpos($comment_sub_url,'api.follow.it')>0)?str_replace('api.follow.it','follow.it',$comment_sub_url):$comment_feed_url; ?>" target="_new"><?php echo (strpos($comment_sub_url,'api.follow.it')>0)?str_replace('api.follow.it','follow.it',$comment_sub_url):$comment_feed_url; ?></a></span>
                </h3>
                <small>

                <a href="<?php echo (strpos($comment_sub_url,'api.follow.it')>0)?str_replace('api.follow.it','follow.it',$comment_sub_url):$comment_sub_url; ?>"  target="_new" style="display: <?php echo ($comment_revers)? "block" : 'none' ?>" title="Open the new feed " class="open_new_feed1">Open the new feed</a>

                <a href="javascript:void(0);" feed_id="<?php if(isset($comment_feedId)) { echo $comment_feedId; } ?>" red_type="comment_rss" style="display: <?php echo ($comment_revers)? "block" : 'none' ?>" title="Reverse redirect" class="reverse_redirect1">Reverse redirect</a>

                <a  href="javascript:void(0);" id="comment_rss"  red_type="comment_rss"  class="<?php echo $comment_cls; ?>" title="Activate Redirect"><?php echo $comment_text; ?></a></small>

                <div class=" clear"></div>
            </div>
            <?php
                if($comment_box) :
                    $feedId = $comment_feedId;
                    $feed_connect = SFM_CONNECT_LINK.base64_encode("userprofile=wordpress&feed_id=".$comment_feedId);  include(SFM_DOCROOT."/views/sfm_pop1.php");
                endif;
            ?>
            <!-- all other active categories feeds section -->
            <?php
                foreach($feeds_data['categoires'] as $fcat_data)  :
                    $activeFdata=$sfmRedirectObj->sfmGetRssDetail(array("category_rss",$fcat_data->cat_ID));

                    if(!empty($activeFdata)) :
                    ?>
                        <div class="sfrd_feedmaster_tab">
                            <h3>Category &ldquo;<?php echo $fcat_data->cat_name;?>&rdquo; feed<span><a href="<?php echo $activeFdata->feed_url; ?>" title="<?php  echo $activeFdata->feed_url;  ?>" target="_new" id="cat_<?php echo $fcat_data->cat_ID; ?>"><?php  echo (strpos($activeFdata->feed_url,'api.follow.it')>0)?str_replace('api.follow.it','follow.it',$activeFdata->feed_url):$activeFdata->feed_url;  ?></a></span></h3>
                            <small>
                             <a href="<?php echo (strpos($activeFdata->feed_url,'api.follow.it')>0)?str_replace('api.follow.it','follow.it',$activeFdata->feed_url):$activeFdata->feed_url; ?>" target="_new" style="display: block" title="Open the new feed " class="open_new_feed1">Open the new feed</a>
                             <a href="javascript:void(0);" style="display:block" title="Reverse redirect" red_type="category_rss"  feed_id="<?php echo $activeFdata->sf_feedid; ?>" class="reverse_redirect1">Reverse redirect</a>
                             <a href="javascript:void(0);" id="category_rss" red_type="category_rss"   rcat=" <?php echo $fcat_data->cat_ID; ?> " class="sfrd_redirect_active" title="Redirect is active!">Redirect is active!</a>
                            </small>
                            <div class=" clear"></div>
                        </div>
                        <?php
                            $feedId = $activeFdata->sf_feedid;
                            $feed_connect = SFM_CONNECT_LINK.base64_encode("userprofile=wordpress&feed_id=".$activeFdata->sf_feedid);  include(SFM_DOCROOT."/views/sfm_pop1.php");
                        ?>

                    <?php
                        else :
                    ?>
                        <div class="sfrd_feedmaster_tab">
                            <h3>Category &ldquo;<?php echo $fcat_data->cat_name;?>&rdquo; feed<span><a href="<?php echo get_category_feed_link($fcat_data->cat_ID); ?>" title="<?php echo get_category_feed_link($fcat_data->cat_ID); ?>" target="_new" id="cat_<?php echo $fcat_data->cat_ID; ?>"><?php echo get_category_feed_link($fcat_data->cat_ID); ?></a></span></h3>
                            <small>
                             <a href="" target="_new" style="display: none;" title="Open the new feed " class="open_new_feed1">Open the new feed</a>
                             <a href="javascript:void(0);" style="display: none;" red_type="category_rss" title="Reverse redirect" class="reverse_redirect1">Reverse redirect</a>

                             <a href="javascript:void(0);" id="category_rss" red_type="category_rss"  rcat=" <?php echo $fcat_data->cat_ID; ?> " class="activate_redirect" title="Activate Redirect">Activate Redirect</a>
                            </small>
                            <div class=" clear"></div>
                        </div>

                    <?php
                        endif;
                    ?>

            <?php
                endforeach;
            ?>  <!-- END all other active categories feeds section -->

            <!-- all other active author feeds section -->
            <?php
                foreach($feeds_data['authors'] as $fauth_data)  :
                    $activeFdata=$sfmRedirectObj->sfmGetRssDetail(array("author_rss",$fauth_data['post_author']));

                    if(!empty($activeFdata)) :
                    ?>
                    <div class="sfrd_feedmaster_tab">
                        <h3>Author &ldquo;<?php echo $fauth_data['user_login']; ?>&rdquo; feed<span><a href="<?php echo (strpos($activeFdata->feed_url,'api.follow.it')>0)?str_replace('api.follow.it','follow.it',$activeFdata->feed_url):$activeFdata->feed_url; ?>" title="<?php echo (strpos($activeFdata->feed_url,'api.follow.it')>0)?str_replace('api.follow.it','follow.it',$activeFdata->feed_url):$activeFdata->feed_url; ?>" target="_new" id="author_<?php echo (strpos($activeFdata->feed_url,'api.follow.it')>0)?str_replace('api.follow.it','follow.it',$activeFdata->feed_url):$fauth_data['post_author']; ?>"><?php echo $activeFdata->feed_url; ?></a></span></h3>
                        <small>
                         <a href="<?php echo (strpos($activeFdata->feed_url,'api.follow.it')>0)?str_replace('api.follow.it','follow.it',$activeFdata->feed_url):$activeFdata->feed_url; ?>" target="_new" style="display: block" title="Open the new feed " class="open_new_feed1">Open the new feed</a>
                         <a href="javascript:void(0);" style="display:block" title="Reverse redirect" red_type="author_rss" feed_id="<?php echo $activeFdata->sf_feedid; ?>" class="reverse_redirect1">Reverse redirect</a>
                         <a href="javascript:void(0);" id="author_rss" red_type="author_rss" rauthor="<?php echo $fauth_data['post_author']; ?>" class="sfrd_redirect_active" title="Redirect is active!">Redirect is active!</a>

                        </small>
                        <div class=" clear"></div>
                    </div>
                    <?php
                        $feedId = $activeFdata->sf_feedid;
                        $feed_connect = SFM_CONNECT_LINK.base64_encode("userprofile=wordpress&feed_id=".$activeFdata->sf_feedid);  include(SFM_DOCROOT."/views/sfm_pop1.php");
                    ?>

                    <?php
                        else :
                    ?>

                    <div class="sfrd_feedmaster_tab">
                        <h3>Author &ldquo;<?php echo $fauth_data['user_login']; ?>&rdquo; feed<span><a href="<?php echo get_author_feed_link($fauth_data['post_author']); ?>" title="<?php echo get_author_feed_link($fauth_data['post_author']); ?>" target="_new" id="author_<?php echo $fauth_data['post_author']; ?>"><?php echo get_author_feed_link($fauth_data['post_author']); ?></a></span></h3>
                        <small>
                         <a href="" target="_new" style="display: none;" title="Open the new feed " class="open_new_feed1">Open the new feed</a>
                         <a href="javascript:void(0);" style="display: none;" red_type="author_rss" title="Reverse redirect" class="reverse_redirect1">Reverse redirect</a>
                         <a href="javascript:void(0);" id="author_rss" red_type="author_rss" rauthor="<?php echo $fauth_data['post_author']; ?>" class="activate_redirect" title="Activate Redirect">Activate Redirect</a>
                        </small>
                        <div class=" clear"></div>
                    </div>

                    <?php
                        endif;
                    ?>

            <?php
                endforeach;
            ?><!-- END  other active author feeds section -->

            <!-- Custom feeds section -->
            <?php $CustomFdata = $sfmRedirectObj->sfmGetCustomFeeds(); ?>
            <?php $cnt=0; foreach($CustomFdata as $customfdata)  : ?>

                <div class="sfrd_feedmaster_tab sfm_customFeeds">
                    <h3>&ldquo;Custom feed <?php echo $cnt+1; ?>&rdquo; <span><a href="<?php echo $customfdata->feed_url; ?>" title="<?php echo (strpos($customfdata->feed_url,'api.follow.it')>0)?str_replace('api.follow.it','follow.it',$customfdata->feed_url):$customfdata->feed_url; ?>" target="_new" id="custom_<?php echo (strpos($customfdata->feed_url,'api.follow.it')>0)?str_replace('api.follow.it','follow.it',$customfdata->feed_url):$customfdata->sf_feedid; ?>"><?php echo (strpos($customfdata->feed_url,'api.follow.it')>0)?str_replace('api.follow.it','follow.it',$customfdata->feed_url):$customfdata->feed_url; ?></a></span></h3>
                    <small>
                     <a href="<?php echo (strpos($customfdata->feed_url,'api.follow.it')>0)?str_replace('api.follow.it','follow.it',$customfdata->feed_url):$customfdata->feed_url; ?>" target="_new" style="display: block" title="Open the new feed " class="open_new_feed1">Open the new feed</a>
                     <a href="javascript:void(0);" style="display:block" title="Reverse redirect" red_type="custom_rss" feed_id="<?php echo $customfdata->sf_feedid; ?>" class="reverse_redirect1">Reverse redirect</a>
                     <a href="javascript:void(0);" id="custom_rss" red_type="custom_rss" class="sfrd_redirect_active" title="Redirect is active!">Redirect is active!</a>

                    </small>
                    <div class=" clear"></div>
                </div>
                <?php
                    $feedId = $customfdata->sf_feedid;
                    $feed_connect = SFM_CONNECT_LINK.base64_encode("userprofile=wordpress&feed_id=".$customfdata->sf_feedid);  include(SFM_DOCROOT."/views/sfm_pop1.php");
                ?>

            <?php $cnt++; endforeach; ?>
            <input type="hidden" id="sfmCusCounter" value="<?php echo $cnt; ?>" />
        </div> <!-- END all other active feeds section -->

        <p class="bottom_txt">If you have any other feed for which you need a redirect please enter it below:</p>
        <div class="sfrd_feedmaster_add SFMcustomFeedLinks">
        <!-- list all custom links -->

            <div class="sfrd_feedmaster_add_row">
                <input name="sfmcustom_link" id="sfmcustom_link" type="text" class="sfmCustomUrl"  placeholder="http://www.yourblog.com/feed-url"><a href="javascript:void(0);" id="custom_rss" red_type="custom_rss" class="activate_redirect" title="Activate Redirect">Activate Redirect</a>
            </div>
            <!-- activation error box -->
            <div class="sfrd_green_box1 sfrd_customError" style="display: none;" >
                <p></p>
            </div>
            <!-- activation error box -->
            <!--<div class="sfrd_feedmaster_add_another"><a href="javascript:void(0);" id="addCustomFeed" title="+ Add Another" class="sfrd_feedmaster_add_another">+ Add Another</a></div>-->
        </div>
        <!-- END Custom feeds section -->

        <p class="sfrd_help"><a href="mailto:help@follow.it" title="Need help or have questions? Get in touch with us">Need help or have questions? Get in touch with us</a></p>

    </div>
</div>
<!-- END main admin section -->
<!-- instruction pop-up-->

<div class="sfrd_popup_overlay" style="display: none;"></div>
<div class="sfrd_popup" style="display: none;">
    <a href="javascript:void(0);" title="Close" class="sfrd_close close_incPopUp"><img src="<?php echo SFM_PLUGURL ?>images/close.jpg" alt="Close"></a>
    <div class="sfrd_popup_contant">

        <h1>How do I redirect my Feedburner feed?</h1>
        <div class="sfrd_row">
            <div class="sfrd_left">
                <div class="sfrd_arrow"><img src="<?php echo SFM_PLUGURL ?>images/arrow1.png" alt=""></div>
                <div class="sfrd_contant_middle">
                    <p>Go to your Feedburner account and select the feed you want to redirect.</p>
                    <p>
                        Make sure that in the &ldquo;edit feed details&rdquo; section you have entered the <strong>original feed</strong> as source.
                    </p>
                </div>
            </div>
            <div class="sfrd_right"><img src="<?php echo SFM_PLUGURL ?>images/screen1.jpg" alt=""></div>
        </div>
        <div class="sfrd_row1">

            <h2>Before you do the following, make sure you downloaded all email subscribers!</h2>

            <div class="sfrd_right1"><img src="<?php echo SFM_PLUGURL ?>images/screen2.jpg" alt=""></div>
            <div class="sfrd_left1">
            <div class="sfrd_arrow1"><img src="<?php echo SFM_PLUGURL ?>images/arrow2.png" alt=""></div>
            <div class="sfrd_contant_middle1">
            <p>
                Then go to &ldquo;delete feed&rdquo; and check the box &ldquo;<strong>with permanent redirection</strong>&rdquo;.
            </p>
            </div>
            </div>
        </div>


        <p class="sfrd_bottom">All RSS-subscribers who subscribed to your Feedburner feed will now be redirected to your original feed, which in turn redirects to your feed on  <strong>follow.it.</strong></p>
        <a href="mailto:help@follow.it" title="Need help or have questions? Get in touch with us">Need help or have questions? Get in touch with us</a>
    </div>
</div>

<div class="sfrd_overlayGear" style="display: none;">
    <img src="<?php echo SFM_PLUGURL ."/images/gear.gif"; ?>" alt="" />
</div>
<!-- END instruction pop-up-->

<!-- CARROUSEL -->
<?php do_action('ins_global_print_carrousel'); ?>
<!-- END OF CARROUSEL -->

<!-- SUPPORT CHAT -->
<jdiv class="label_e50 _bottom_ea7 notranslate" id="rrs_support_chat" style="background: linear-gradient(95deg, rgb(47, 50, 74) 20%, rgb(66, 72, 103) 80%);right: 30px;bottom: 0px;width: 310px;">
<jdiv class="hoverl_bc6"></jdiv>
<jdiv class="text_468 _noAd_b4d contentTransitionWrap_c73" style="font-size: 15px;font-family: Arial, Arial;font-style: normal;color: rgb(240, 241, 241);position: absolute;top: 8px;line-height: 13px;">
  <span>Connect with support (click to load)</span><br>
  <span style="color: #eee;font-size: 10px;">
    This will establish connection to the chat servers
  </span>
</jdiv>
  <jdiv class="leafCont_180">
    <jdiv class="leaf_2cc _bottom_afb">
      <jdiv class="cssLeaf_464"></jdiv>
  </jdiv>
</jdiv>
</jdiv>
<!-- END OF SUPPORT CHAT -->
