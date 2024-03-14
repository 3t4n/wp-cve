<style>
    .content-bar > p{
        display: none;
    }
</style>



<?php

if ( ! function_exists('get_prayer_comment')) {
    function get_prayer_comment($id)
    {
        global $wpdb, $table_prefix;
        $result = $wpdb->get_results("select * from ".$table_prefix."prayer_comment where prayer_id=".$id." order by comment_date asc");

        return $result;
    }
}
/**
 * Render Shortcode.
 * @author Flipper Code <hello@flippercode.com>
 * @package  Maps
 */
global $wpdb, $paged, $max_num_pages, $wp_rewrite, $wp_query;

$modelFactory = new FactoryModelWPE();

?>

<script>

    function form_submit(obj) {
        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(obj.email.value)) {
            return (true)
        }
        alert("Please enter valid email address")
        return (false)
    }

    function hide(id) {
        jQuery("#" + id).toggle();
    }

    function hidebutton(id) {
        jQuery("#commentview" + id).toggle();
        jQuery(".hide" + id).attr("style", "display:none");
        jQuery(".show" + id).attr("style", "display:");
    }

    function showbutton(id) {
        jQuery("#commentview" + id).toggle();
        jQuery(".hide" + id).attr("style", "display:");
        jQuery(".show" + id).attr("style", "display:none");
    }

</script>

<script type="text/javascript">
    function validateMyForm() {
        var contents = jQuery("#comment").val().split(" ");

        var filtered = contents.filter(function (element) {
            return element != "";
        });

        if (filtered.length <= 2) {
            return false;
        }
        else {
            return true;
        }

        /*
        // The field is empty, submit the form.
        if (!document.getElementById("webtext").value) {
            return true;
        }
        // the field has a value it's a spam bot
        else {
            alert("Spam detected. Please try again")
            return false;
        }
        */
    }
</script>

<style>
    /*#author{
        display: inline !important;
        border: 1px solid #a9a9a9 !important;
        height: 20px !important;
        width: 50% !important;
        text-align:left;
    }
    */
</style>
<?php
$lxt_options = get_option('_wpe_prayer_engine_settings');
$lxt_options = unserialize($lxt_options);

$comment = false;
if (isset($lxt_options['wpe_prayer_comment'])&&$lxt_options['wpe_prayer_comment'] == 'true') {
    $comment = true;
}
$comment_status = 0;
if (isset($lxt_options['wpe_prayer_comment_status'])&& $lxt_options['wpe_prayer_comment_status'] == 'true') {
    $comment_status = 1;
}
/*
if($_POST['prayer_comment']){
	//print_r($_POST);
	global $wpdb,$table_prefix;
	$user = wp_get_current_user();

	$data=array( 'prayer_id'=> sanitize_text_field($_POST['prayer_id']),
	'comment_content'=>sanitize_textarea_field($_POST['comment']),
	'comment_author_IP'=>$_SERVER['REMOTE_ADDR'],
	'user_id' => $user->ID,
	'comment_date' => date('Y-m-d H:i:s'),
	'status' =>$comment_status
	);
	$wpdb->insert($table_prefix."prayer_comment",$data);

	//wp_setcookie($user_login, $user_pass, true);
	// wp_set_current_user($new_user_id, $user_login);
	//do_action('wp_login', $user_login);
}
*/
$_SESSION['error_msg'] = "";
if (isset($_POST['author'])) {


    //$data=explode(" ",$_POST['author']);
    //print_r($data);
    /* $user_login=$data[0].rand(0, 99999);
    $displayname=$data[0];
    $user_pass=md5($user_login);
    $user_email= sanitize_email($_POST['email']);
    $user_first=$data[0];
    $user_last=$data[1];*/
    /*$new_user_id = wp_insert_user(array(
    'user_login'		=> $user_login,
    'user_pass'	 	=> $user_pass,
    'user_email'		=> $user_email,
    'first_name'		=> $user_first,
    'last_name'		=> $user_last,
    'display_name'          =>$displayname,
    'user_registered'	=> date('Y-m-d H:i:s'),
    'role'			=> 'subscriber'
    )
    );*/
    //print_r($new_user_id);
    //die;
    if (empty($new_user_id->errors)) {
        // send an email to the admin alerting them of the registration
        //wp_new_user_notification($new_user_id);
        global $wpdb, $table_prefix;
        $data = array(
            'prayer_id' =>  sanitize_text_field($_POST['prayer_id']),
            'comment_author' =>  sanitize_text_field($_POST['author']),
            'comment_author_email' =>  sanitize_email($_POST['email']),
            'comment_content' => sanitize_textarea_field($_POST['comment']),
            'comment_author_IP' => $_SERVER['REMOTE_ADDR'],
            'comment_date' => date('Y-m-d H:i:s'),
            'status' => $comment_status,
        );
		$num_found1= preg_match_all('/(((http|https|ftp|ftps)\:\/\/)|(www\.))[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\:[0-9]+)?(\/\S*)?/', $_POST['comment'], $results, PREG_PATTERN_ORDER);
	    $string = preg_replace('/\s+/', ' ', trim($_POST['comment']));
	    $words = explode(" ", $_POST['comment']);
	    $length_count = count($words);
	    $lang=get_bloginfo("language");
	    $arr = array("en-US", "es-MX" , "fr-CA" , "pl_PL","es-ES","es-VE","ru-RU","sr-RS","es-EC","nl-NL");
	    if(in_array($lang, $arr) and $length_count > 2) {
        if (!empty($_POST['comment']) && empty($num_found1)) {
		$wpdb->insert($table_prefix."prayer_comment", $data);
		echo '<script>window.history.replaceState( null, null, window.location.href );</script>';
		}}
        //echo '<script>wondow.location="'.home_url().'/prayer-request/"</script>';
        // log the new user in
        // wp_setcookie($user_login, $user_pass, true);
        //wp_set_current_user($new_user_id, $user_login);
        // do_action('wp_login', $user_login);
        // send the newly created user to the home page after logging them in
        ///wp_redirect("");
        //echo '<script>window.location = "/prayer/wp-admin"; </script>';
        //wp_redirect("/prayer/view-prayer/wp-admin");
        //exit();
    }
    /*else{
        global $wpdb,$table_prefix;
        //echo "SELECT ID from ".$table_prefix."users where user_emial='".$user_email."'";
        $id=$wpdb->get_results("SELECT ID from ".$table_prefix."users where user_email='".$user_email."'");
        $userid = $id[0]->ID;//get_user_id_from_string( $user_email );
        $data=array( 'prayer_id'=> sanitize_text_field($_POST['prayer_id']),
        'comment_content'=> sanitize_textarea_field($_POST['comment']),
        'comment_author_IP'=>$_SERVER['REMOTE_ADDR'],
        'user_id' => $userid,
        'comment_date' => date('Y-m-d H:i:s'),
        'status' =>$comment_status
        );
        $wpdb->insert($table_prefix."prayer_comment",$data);

        // log the new user in
        wp_setcookie($user_login, $user_pass, true);
        wp_set_current_user($userid, $user_login);
        do_action('wp_login', $user_login);
        //$_SESSION['error_msg']=$new_user_id->errors['existing_user_email'][0];

    }*/
}
?>


<?php
wp_enqueue_script('wpp-frontend');
wp_enqueue_style('wpp-frontend');
$settings = unserialize(get_option('_wpe_prayer_engine_settings'));

$wcsl_js_lang = array(
	'ajax_url' => admin_url('admin-ajax.php'),
	'nonce' => wp_create_nonce('wpe-call-nonce'),
	'confirm' => __('Are you sure to delete item ?', WPE_TEXT_DOMAIN),
    'loading_image' => WPE_IMAGES.'loader.gif',
);
if (isset($data['layout_post_setting']['pagination_style'])) {$wcsl_js_lang['pagination_style'] = $data['layout_post_setting']['pagination_style'];}
$wcsl_js_lang['WCSL_IMAGES'] = WPE_IMAGES;
$wcsl_js_lang['loading_text'] =('...');
$wcsl_js_lang['prayed_text'] = ('...');if(isset( $settings['wpe_pray_text'] ) and !empty ($settings['wpe_pray_text'])) {$wcsl_js_lang['pray1_text'] = $settings['wpe_pray_text'];} else {$wcsl_js_lang['pray1_text'] = __('Pray', WPE_TEXT_DOMAIN);}
$wcsl_js_lang['pray_time_interval']='';if(isset($settings['wpe_prayer_time_interval'])){$wcsl_js_lang['pray_time_interval'] = intval($settings['wpe_prayer_time_interval']);}
$script = "var wcsl_js_lang = " . wp_json_encode($wcsl_js_lang) . ";";
wp_enqueue_script('wpp-frontend');
wp_add_inline_script('wpp-frontend', $script, 'before');

$prayer_obj = $modelFactory->create_object('prayer');
$paged = (isset($_GET['page_num']) && intval($_GET['page_num'])) ? $_GET['page_num'] : 1;
if(!empty ($settings['wpe_num_prayer_per_page'])) {$prayers_per_page = intval($settings['wpe_num_prayer_per_page']);} else {$prayers_per_page = 10;}

    
$limit = ($paged - 1) * $prayers_per_page;
$wpe_fetch_req_from = array('1', '=', '1');
if (isset($settings['wpe_fetch_req_from']) != '') {
    switch ($settings['wpe_fetch_req_from']) {
        case 'all' :
            break;
        default :
            $wpe_fetch_req_from = array('DATEDIFF(now(), prayer_time)', '<=', $settings['wpe_fetch_req_from']);
            break;
    }
}
$prayers = $prayer_obj->fetch(array(
    array('prayer_status', '=', 'approved'),
    array('request_type', '=', 'prayer_request'),
    $wpe_fetch_req_from,
), 'prayer_id', false, $limit, $prayers_per_page);
$total_prayers = $prayer_obj->fetch(array(
    array('prayer_status', '=', 'approved'),
    array('request_type', '=', 'prayer_request'),
    $wpe_fetch_req_from,
));
$max_num_pages = ceil(sizeof($total_prayers) / $prayers_per_page);
echo '<p>&nbsp;</p>';
if (empty($prayers)) {
    if (current_user_can('manage_options')) {
        printf('<a href="%s">'.__('Share prayer request',
                WPE_TEXT_DOMAIN), admin_url('admin.php?page=wpe_form_prayer'));
    }
    //echo '</div>';

    return;
}
if ( ! empty($prayers)) {
    echo '<div class="wsl_prayer_engine">';
    echo '<div class="wsl_prayer_enginelist"><ul>';

    $prayer_performed_obj = $modelFactory->create_object('prayers_performed');
    $total_prayer_performed = $prayer_performed_obj->count_prayers_for_each_request();

    $lxt_options = get_option('_wpe_prayer_engine_settings');
    $lxt_options = unserialize($lxt_options);
    $prayer_hide_flag = $prayer_hide_count_flag = false;

    if ( ! empty($lxt_options) && array_key_exists('wpe_hide_prayer', $lxt_options)) {
        $prayer_hide_flag = filter_var($lxt_options['wpe_hide_prayer'], FILTER_VALIDATE_BOOLEAN);
    }
    if ( ! empty($lxt_options) && array_key_exists('wpe_hide_prayer_count', $lxt_options)) {
        $prayer_hide_count_flag = filter_var($lxt_options['wpe_hide_prayer_count'], FILTER_VALIDATE_BOOLEAN);
    }

    if ( isset($settings['wpe_login_required']) && $settings['wpe_login_required']!='false' && !is_user_logged_in() ) {
        if ( ! empty($_SERVER['HTTP_CLIENT_IP'])) {
            //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif ( ! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $prayer_performed_obj = $prayer_performed_obj->fetch(array(array('user_ip', '=', $ip)));
    } else {
        $ip = '';
        $prayer_performed_obj = $prayer_performed_obj->fetch(array(array('user_id', '=', get_current_user_id())));
    }
    foreach ($prayers as $pray) {

        $prayer_author_info = get_userdata($pray->prayer_author);
        echo '<li>
		<div class="wsl_prayer_left">';
        if ( !$prayer_hide_flag) {
			if(empty ($settings['wpe_pray_btn_color'])) {$settings['wpe_pray_btn_color']='';}if(empty ($settings['wpe_pray_text_color'])) {$settings['wpe_pray_text_color']='';}
            echo '<div class="wsl_prayer_right">
            <style>
            .wsl_prayer_enginelist .wsl_prayer_right input[type="submit"]{
                background-color:'.$settings['wpe_pray_btn_color'].';
                color:'.$settings['wpe_pray_text_color'].';
            }
            </style>
            ';
            //echo get_current_user_id();
            //print_r($prayer_performed_obj);
            //echo $pray->prayer_id;
            if (is_user_logged_in()) {
                if (false && in_array($pray->prayer_id, $prayer_performed_obj)) {
                    echo '<input type="submit" name="do_pray" class="prayed" id="do_pray_'.$pray->prayer_id.'" value="'.$wcsl_js_lang['prayed_text'].'" />';
                } else { ?>
                   <!-- echo $_SERVER['REMOTE_ADDR'];
                        echo esc_html(base64_encode($ip)); -->
                    <input type="submit" onclick="do_pray(<?php echo esc_html($pray->prayer_id); ?>,'<?php echo esc_html(base64_encode($ip)); ?>');" name="do_pray"
                           id="do_pray_<?php echo esc_html($pray->prayer_id); ?>" value="<?php echo $wcsl_js_lang['pray1_text']; ?>"/>
                <?php }
            } else{
                if (false && in_array($pray->prayer_id, $prayer_performed_obj)) {
                    echo '<input type="submit" name="do_pray" class="prayed" id="do_pray_'.$pray->prayer_id.'" value="'.$wcsl_js_lang['prayed_text'].'" />';
                } else {
                    ?>
                    <input type="submit"
                           onclick="do_pray(<?php echo esc_html($pray->prayer_id); ?>,'<?php echo esc_html(base64_encode($ip)); ?>');"
                           name="do_pray" id="do_pray_<?php echo esc_html($pray->prayer_id); ?>"
                           value="<?php echo $wcsl_js_lang['pray1_text']; ?>"/>
                <?php }
            }
            echo '</div>';
        }
        echo nl2br($pray->prayer_messages).'<div class="postmeta">';
        if (isset($settings['wpe_display_author']) && $settings['wpe_display_author'] == 'true') {
			$firstname = strtok(isset($prayer_author_info), ' ');
            echo ($pray->prayer_author_name != '') ? strtok(($pray->prayer_author_name),' ').' | ' : (($prayer_author_info->display_name != '') ? strtok(($prayer_author_info->display_name),' ').'| ' : '');
        }
		$offset = get_option('gmt_offset');
        if (isset($settings['wpe_date']) && $settings['wpe_date'] == 'true')
		{$timeago = date_i18n(get_option('date_format'),strtotime( $pray->prayer_time )+$offset*3600 );} else {
		if (isset($settings['wpe_ago']) && $settings['wpe_ago'] == 'true') {
		$timeago= __('ago',WPE_TEXT_DOMAIN).' '.human_time_diff( strtotime($pray->prayer_time), current_time( 'timestamp', 1 ) );
		}else{$timeago=human_time_diff( strtotime($pray->prayer_time), current_time( 'timestamp', 1 ) ).' '.__('ago',WPE_TEXT_DOMAIN);}}
		echo $timeago;
        // echo date( 'F j, Y \a\t h:i A',strtotime($pray->prayer_time)+$user_timezone*3600).'.<br>';
        if ( ! $prayer_hide_count_flag) {
            echo ' | '.__('Prayed',WPE_TEXT_DOMAIN).' '.'<span id="prayer_count'.$pray->prayer_id.'">', ( ! empty($total_prayer_performed[$pray->prayer_id])) ? $total_prayer_performed[$pray->prayer_id] : 0, '</span>'.' '.__('times',WPE_TEXT_DOMAIN);
        }
        echo '</div></div>';
        ?>
        <?php $comm = get_prayer_comment($pray->prayer_id);
        //print_r($comm);
        ?>

        <!-- full comment section -->

        <?php if ($comment): ?>
            <!-- view the Comment  -->
            <?php if ( ! empty($comm)): $i = 1; ?>
                <ol class="comment-list" id="commentview<?php echo esc_html($pray->prayer_id); ?>">
                <?php
                $avaliable_comment = 0;
                foreach ($comm as $cmt):
                    //print_r($cmt);
                    if ($cmt->status == 0) {
                        continue;
                    } else {
                        $avaliable_comment = 1;
                    }
                    ?>
                    <li id="comment-<?php echo esc_html($i); ?>"
                        class="comment byuser comment-author-admin bypostauthor even thread-even depth-1">
                        <article id="div-comment-2" class="comment-body">
                            <footer class="comment-meta">
                                <div class="comment-author vcard">
                                    <!--                                        <img alt="" src="<?php //echo get_avatar_url($cmt->user_id);
                                    ?>" srcset="http://1.gravatar.com/avatar/4698d99e7de449ccf6dcd6dc40580f3a?s=112&d=mm&r=g 2x" class="avatar avatar-56 photo" height="56" width="56">-->
                                    <?php
                                    //$user_info = get_userdata($cmt->user_id);
                                    //print_r($user_info);
                                    ?>
                                    <b class="fn"><?php echo esc_html($cmt->comment_author);?></b> <span class="says"> </span>
                                    <?php
									$offset = get_option('gmt_offset');
                                    if (isset($settings['wpe_date']) && $settings['wpe_date'] == 'true')
									{$timeagoc = date_i18n(get_option('date_format'),strtotime( $cmt->comment_date )+$offset*3600 );} else {
									if (isset($settings['wpe_ago']) && $settings['wpe_ago'] == 'true') {
									$timeagoc= __('ago',WPE_TEXT_DOMAIN).' '.human_time_diff( strtotime($cmt->comment_date), current_time( 'timestamp', 1 ) );
									}else{$timeagoc=human_time_diff( strtotime($cmt->comment_date), current_time( 'timestamp', 1 ) ).' '.__('ago',WPE_TEXT_DOMAIN);}}
                                    ?>
                                    <time datetime="2016-12-19T19:46:35+00:00"><?php echo esc_html($timeagoc)?></time>
                                </div>
                                <!-- .comment-author -->

                                <!--                                <div class="comment-metadata">
		<time datetime="2016-12-19T19:46:35+00:00"><?php //ssecho date('F d, Y  \a\t H:i A' , strtotime($cmt->comment_date));
                                ?></time>
		</div> .comment-metadata -->

                            </footer><!-- .comment-meta -->

                            <div class="comment-content">
                                <p><?php echo esc_html($cmt->comment_content); ?></p>
                            </div><!-- .comment-content -->
                        </article><!-- .comment-body -->
                    </li>
                    <?php $i++; endforeach;
            endif; ?>
            </ol>
            <div style="clear:both"></div>
            <!-- End  view the Comment  -->

            <?php /* if(is_user_logged_in()){ ?>
			<div class="row">
			<div style='color:red;'><?php echo esc_html($_SESSION['error_msg']); ?></div>
			<button class="btn" onclick="hide('commentform<?php echo esc_html($pray->prayer_id);?>')" >Reply</button>
			<?php if($avaliable_comment==1): ?>
			<button class="btn show<?php echo esc_html($pray->prayer_id);?>" onclick="showbutton(<?php echo esc_html($pray->prayer_id);?>)" style="display:none;" >Show replies</button>
			<button class="btn hide<?php echo esc_html($pray->prayer_id);?>" onclick="hidebutton(<?php echo esc_html($pray->prayer_id);?>)" >Hide replies</button>
			<?php endif; ?>
			</div>
			<form action="" class="comment-form" id="commentform<?php echo esc_html($pray->prayer_id);?>" method="post" name="commentform" style="display:none;" >
			<p></p>
			<p class="comment-form-comment"><label for="comment">Reply</label>
			<textarea aria-required="true" cols="20" data-gramm="true" data-gramm_editor="true" data-gramm_id="0ef3c169-41f7-823e-4f2c-cec914ccc786" data-txt_gramm_id="0ef3c169-41f7-823e-4f2c-cec914ccc786" id="comment" maxlength="65525" name="comment" required="required" rows="4" spellcheck="false" style="z-index: position: fixed; line-height: 15px; font-size: 14px; transition: none; background: transparent !important;"></textarea></p>
			<p></p>
			<p class="form-submit"><input class="submit" id="submit" name="submit" type="submit" value="Submit">
			<input id="prayer_id" name="prayer_id" type="hidden" value="<?php echo esc_html($pray->prayer_id);?>">
			<input id="prayer_comment" name="prayer_comment" type="hidden" value="yes"></p>
			<input id="_wp_unfiltered_html_comment_disabled" name="_wp_unfiltered_html_comment" type="hidden" value="d7e040f76a">
			</form>
		<?php }else{  */ ?>
            <div class="row">
                <div style='color:red;'><?php echo esc_html($_SESSION['error_msg']); ?></div>
                <button class="btn" onclick="hide('commentloginform<?php echo esc_html($pray->prayer_id); ?>')"><?php _e('Reply',
                        WPE_TEXT_DOMAIN); ?></button>
                <?php if (isset($avaliable_comment) && ($avaliable_comment == 1)): ?>
                    <button class="btn show<?php echo esc_html($pray->prayer_id); ?>"
                            onclick="showbutton(<?php echo esc_html($pray->prayer_id); ?>)"
                            style="display:none;"><?php _e('Show replies', WPE_TEXT_DOMAIN); ?></button>
                    <button class="btn hide<?php echo esc_html($pray->prayer_id); ?>"
                            onclick="hidebutton(<?php echo esc_html($pray->prayer_id); ?>)"><?php _e('Hide replies',    
							WPE_TEXT_DOMAIN); ?></button>
                <?php endif; ?>
            </div>
            <form action="" class="comment-form" id="commentloginform<?php echo esc_html($pray->prayer_id); ?>" method="post"
                  name="commentloginform" style="display:none;" onsubmit="return validateMyForm();">
                <p class="comment-notes"><span id="email-notes"></span><span class="required"></span></p>
                <p class="comment-form-comment"><label for="comment"></label>
                    <textarea aria-required="true" cols="40" id="comment" maxlength="65525" name="comment"
                              required="required" rows="4" oninvalid="this.setCustomValidity(' ')" onkeyup="setCustomValidity('')"></textarea>
                <div class="error-comment"></div>
                </p>
                <p class="comment-form-author" style="display:none;">
                    <label for="email"><?php _e('Name', WPE_TEXT_DOMAIN); ?><span class="required">*</span></label>

                <div class="error-name"></div>
                </p>
                <p class="comment-form-email">
                    <label for="email"><?php _e('Name', WPE_TEXT_DOMAIN); ?><span class="required"></span></label>
                    <input aria-required="true" id="author" maxlength="100" name="author" required="required" size="20"
                           type="text" value="" oninvalid="this.setCustomValidity(' ')" onkeyup="setCustomValidity('')" oninput="setCustomValidity('')">
                <div class="error-email"></div>
                </p>
                <p class="comment-form-email">
                    <label for="email"><?php _e('Email', WPE_TEXT_DOMAIN); ?><span class="required"></span></label>
                    <input aria-describedby="email-notes" aria-required="true" id="email" maxlength="100" name="email"
                           required="required" size="20" type="email" value="" oninvalid="this.setCustomValidity(' ')" onkeyup="setCustomValidity('')">
                <div class="error-email"></div>
                </p>
                <input id="prayer_id" name="prayer_id" type="hidden" value="<?php echo esc_html($pray->prayer_id); ?>">
                <div style="display:none;">
                    <p class="webtext"><label for="webtext"><?php _e('Website', WPE_TEXT_DOMAIN); ?></label> <input
                                id="webtext" maxlength="100" name="webtext" size="20" type="text" value="">
                </div>
                </p>
                <p class="form-submit"><input class="submit" id="submit" name="submit" type="submit"
                                              value="<?php _e('Submit', WPE_TEXT_DOMAIN); ?>"> <input
                            id="comment_post_ID" name="comment_post_ID" type="hidden" value="8"> <input
                            id="comment_parent" name="comment_parent" type="hidden" value="0"></p>

            </form>
            <?php // } ?>

        <?php endif; ?>

        <!-- end comment section  -->
        <?php
        echo '</li>';
    }
    echo '</ul></div>';
    echo '</div><div class="clear"></div>';
}
$pagination = array(
    'base' => add_query_arg('page_num', '%#%', get_permalink()),
    'format' => '',
    'total' => $max_num_pages,
    'current' => $paged,
    'prev_text' => __('Prev', WPE_TEXT_DOMAIN),
    'next_text' => __('Next', WPE_TEXT_DOMAIN),
    'end_size' => 1,
    'mid_size' => 2,
    'show_all' => false,
    'type' => 'plain',
);
echo '<div class="prayers_pagination">'.paginate_links($pagination).'</div>';

add_action('wp_head', 'myplugin_ajaxurl');
function myplugin_ajaxurl() {
   ?> 
    <script type="text/javascript">
    var ajaxnonce = <?php echo json_encode( wp_create_nonce( "itr_ajax_nonce" ) ); ?>;
    <?php $settings = unserialize(get_option('_wpe_prayer_engine_settings')); ?>
    var wcsl_js_lang = <?php echo json_encode( array(
         'ajax_url' => admin_url('admin-ajax.php'),
         'loading_text' => "...",
         'prayed_text' => "...",
         'pray1_text' => (isset( $settings['wpe_pray_text'] ) and ! empty( $settings['wpe_pray_text'] )) ? $settings['wpe_pray_text'] : __('Pray', WPE_TEXT_DOMAIN),
         'pray_time_interval' => (isset( $settings['wpe_prayer_time_interval'] ) and ! empty( $settings['wpe_prayer_time_interval'] )) ? $settings['wpe_prayer_time_interval'] : '',
         'loading_image' => WPE_IMAGES.'loader.gif',
       ) ); ?>
      <?php
      if(isset( $settings['wpe_pray_text'] ) and !empty ($settings['wpe_pray_text'])) 
      {$wcsl_js_lang['pray1_text'] = $settings['wpe_pray_text'];}
       else {$wcsl_js_lang['pray1_text'] = __('Pray', WPE_TEXT_DOMAIN);}
      if(isset($settings['wpe_prayer_time_interval'])){
        $wcsl_js_lang['pray_time_interval'] = intval($settings['wpe_prayer_time_interval']);}
      ?>
  </script>
  <?php   
}
?>
