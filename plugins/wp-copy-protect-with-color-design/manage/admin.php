<?php

/***
 * 管理画面
***/

	$is_act = false;
	if(get_option('content_protect_plus_dir')){
		$file= get_option('content_protect_plus_dir');
		$is_act = false;
		
		foreach ((array) get_option('active_plugins') as $val) {
			if (preg_match('/'.preg_quote($file, '/').'/i', $val)) {
				$is_act = true;
			}
		}
	}


	$is_act2 = false;
	if(get_option('javascript-protection-proversion_dir')){
		$file= get_option('javascript-protection-proversion_dir');
		$is_act2 = false;
		
		foreach ((array) get_option('active_plugins') as $val) {
			if (preg_match('/'.preg_quote($file, '/').'/i', $val)) {
				$is_act2 = true;
			}
		}
	}

?>

<div class="wrap" style="float:left;"><br/>
	<h1>WP Content Copy Protection with Color Design <font size="2">v2.3.8</font></h1>

<?php
	 /***
	   *Saveされた時の処理
	 ***/

 	 $Protect_Copy_save = @$_POST['Protect_Copy_save'];
     $Protect_Copy_save = wp_kses($Protect_Copy_save, array());
		
		if ( isset( $Protect_Copy_save )){

		   //nonceチェック
	       if ( isset( $_POST['_wpnonce'] ) && $_POST['_wpnonce'] ) {
	            if ( check_admin_referer( 'WPprotect_plugin', '_wpnonce' ) ) {

		        	//POST取得
			        $protect_plugin_value_click = @$_POST['protect_plugin_value_click'];
					$protect_plugin_value_click = (int) $protect_plugin_value_click;
			        $protect_plugin_value_click = wp_kses($protect_plugin_value_click, array());

					$protect_plugin_value_select_text = @$_POST['protect_plugin_value_select_text'];
			        $protect_plugin_value_select_text = wp_kses($protect_plugin_value_select_text, array());
			        
			        $protect_plugin_value_subject = @$_POST['protect_plugin_value_subject'];
			        $protect_plugin_value_subject = wp_kses($protect_plugin_value_subject, array());

			        $protect_plugin_value_color = @$_POST['protect_plugin_value_color'];
			        $protect_plugin_value_color = wp_kses($protect_plugin_value_color, array());

			        $protect_plugin_value_user = @$_POST['protect_plugin_value_user'];
			        $protect_plugin_value_user = wp_kses($protect_plugin_value_user, array());
			        
			        $protect_plugin_value_admin = @$_POST['protect_plugin_value_admin'];
			        $protect_plugin_value_admin = wp_kses($protect_plugin_value_admin, array());
			        
			        $protect_plugin_value_f12 = @$_POST['protect_plugin_value_f12'];
			        $protect_plugin_value_f12 = wp_kses($protect_plugin_value_f12, array());
			        
			        $javascript_protection_proversion = @$_POST['javascript_protection_proversion'];
			        $javascript_protection_proversion = wp_kses($javascript_protection_proversion, array());

					$protect_plugin_value_print_no = @$_POST['protect_plugin_value_print_no'];
			        $protect_plugin_value_print_no = wp_kses($protect_plugin_value_print_no, array());

			        $protect_plugin_value_pages = @$_POST['protect_plugin_value_pages'];
			        $protect_plugin_value_pages = wp_kses($protect_plugin_value_pages, array());

			        $protect_plugin_value_posts = @$_POST['protect_plugin_value_posts'];
			        $protect_plugin_value_posts = wp_kses($protect_plugin_value_posts, array());
			        
			        $protect_plugin_value_include = @$_POST['protect_plugin_value_include'];
			        $protect_plugin_value_include = wp_kses($protect_plugin_value_include, array());

			        $protect_plugin_value_include_posts = @$_POST['protect_plugin_value_include_posts'];
			        $protect_plugin_value_include_posts = wp_kses($protect_plugin_value_include_posts, array());


					//データベース登録
					update_option('protect_plugin_value_click', $protect_plugin_value_click);
					update_option('protect_plugin_value_select_text', $protect_plugin_value_select_text);
					update_option('protect_plugin_value_subject', $protect_plugin_value_subject);
					update_option('protect_plugin_value_color', $protect_plugin_value_color);
					update_option('protect_plugin_value_user', $protect_plugin_value_user);
					update_option('protect_plugin_value_admin', $protect_plugin_value_admin);
					update_option('wp_content_plus_btn_f12', $protect_plugin_value_f12);
					update_option('javascript_protection_proversion', $javascript_protection_proversion);
					update_option('protect_plugin_value_print_no', $protect_plugin_value_print_no);
					update_option('protect_plugin_value_pages', $protect_plugin_value_pages);
					update_option('protect_plugin_value_posts', $protect_plugin_value_posts);
					update_option('protect_plugin_value_include', $protect_plugin_value_include);
					update_option('protect_plugin_value_include_posts', $protect_plugin_value_include_posts);
				
				}
			}
		}


	/***
	 * データを取得
	***/
	//登録データ
	$protect_plugin_value_click = get_option('protect_plugin_value_click');
	$protect_plugin_value_select_text = get_option('protect_plugin_value_select_text');
	$protect_plugin_value_subject = get_option('protect_plugin_value_subject');
	$protect_plugin_value_color = get_option('protect_plugin_value_color');
	$protect_plugin_value_user = get_option('protect_plugin_value_user');
	$protect_plugin_value_admin = get_option('protect_plugin_value_admin');
	$protect_plugin_value_print_no = get_option('protect_plugin_value_print_no');
	$protect_plugin_value_pages = get_option('protect_plugin_value_pages');
	$protect_plugin_value_posts = get_option('protect_plugin_value_posts');
	$protect_plugin_value_include = get_option('protect_plugin_value_include');
	$protect_plugin_value_include_posts = get_option('protect_plugin_value_include_posts');

?>

	<form method="post" id="protection_copy_form" action="">
		<?php wp_nonce_field( 'WPprotect_plugin', '_wpnonce' ); ?>

		<fieldset class="options">
		<table class="form-table">		

			<tr valign="top"> 
				<th width="108" scope="row"><?php _e('Disable right mouse click', $this->textdomain );?> :</th> 
				<td>
				<input type="radio" name="protect_plugin_value_click" value="<?php echo (esc_attr("1")); ?>" <?php if($protect_plugin_value_click == 1) echo('checked'); ?> />
				<?php _e('No right click + No message', $this->textdomain );?><br /><br />
				<input type="radio" name="protect_plugin_value_click" value="<?php echo (esc_attr("2")); ?>" <?php if($protect_plugin_value_click == 2) echo('checked'); ?> />
				<?php _e('No right click + Message', $this->textdomain );?>:<br />
				<input name="protect_plugin_value_subject" type="text" id="protect_plugin_value_subject" value="<?php echo _e(esc_attr($protect_plugin_value_subject));?>" size="30"/>
				<br /><?php _e('Less than 159 letters', $this->textdomain );?></td>
			</tr>

			<tr valign="top"> 
				<th width="108" scope="row"><?php _e('Selecting Text', $this->textdomain );?> :</th> 
				<td>
				<input type="checkbox" id="protect_plugin_value_select_text" name="protect_plugin_value_select_text" value="<?php echo esc_attr('protect_plugin_value_select_text'); ?>" <?php if($protect_plugin_value_select_text == true) { echo('checked="checked"'); } ?> />
				<?php _e('Yes', $this->textdomain ); ?><hr></td> 
			</tr>

			<tr valign="top"> 
				<th width="108" scope="row"><?php _e('Protect when Javascript is off', $this->textdomain );?> :</th> 
				<td>
				<input type="checkbox" id="javascript_protection_proversion" name="javascript_protection_proversion" value="<?php echo esc_attr('javascript_protection_proversion'); ?>" <?php if(get_option('javascript_protection_proversion') == true) { echo('checked="checked"'); } ?><?php if(!$is_act2){ ?>disabled="disabled"<?php } ?> />
				<?php _e('Yes', $this->textdomain ); ?>
				
				<?php if(!$is_act2){ ?>&nbsp;&nbsp;<a href= "https://global-s-h.com/shop/product/javascript-protection-pro-version/" target="_blank"><?php _e('Pro version from here!', $this->textdomain ); ?></a><?php } ?>
				<br /><br /><?php _e('Give a white blank page when the browser javascript is off to prevent your content', $this->textdomain ); ?>
				<hr>
				</td> 
			</tr>

			<tr valign="top">
			  <th scope="row"><?php _e('Warning back color', $this->textdomain );?> :</th>
			  <td><input type="text" name="protect_plugin_value_color" value="<?php echo esc_attr($protect_plugin_value_color); ?>"class="ProtectColorPicker" ></td>
				
		    </tr>

			<tr valign="top"> 
				<th width="108" scope="row"><?php _e('Disable F12 for Windows', $this->textdomain );?> :</th> 
				<td>
				<input type="checkbox" id="protect_plugin_value_f12" name="protect_plugin_value_f12" value="<?php echo esc_attr('protect_plugin_value_user'); ?>" <?php if(get_option('wp_content_plus_btn_f12') == true) { echo('checked="checked"'); } ?><?php if(!$is_act){ ?>disabled="disabled"<?php } ?> />
				<?php _e('Yes', $this->textdomain ); ?>
				
				<?php if(!$is_act){ ?>&nbsp;&nbsp;<a href= "https://global-s-h.com/shop/product/wp-content-copy-protection-with-color-design-plus/" target="_blank"><?php _e('Pro version from here!', $this->textdomain ); ?></a><?php } ?>
				<br /><br /><?php _e('Disable F12 to open the console window and Ctr+U', $this->textdomain ); ?>
				<hr>
				</td> 
			</tr>

			<tr valign="top"> 
				<th width="108" scope="row"><?php _e('"Not allowed" message on print preview page<br>(CTRL + P)', $this->textdomain );?> :</th> 
				<td>
				<input type="checkbox" id="protect_plugin_value_print_no" name="protect_plugin_value_print_no" value="<?php echo esc_attr('protect_plugin_value_print_no'); ?>" <?php if($protect_plugin_value_print_no == true) { echo('checked="checked"'); } ?> />
				<?php _e('Yes', $this->textdomain ); ?><hr></td> 
			</tr>

			<tr valign="top"> 
				<th width="108" scope="row"><?php _e('Exclude login users', $this->textdomain );?> :</th> 
				<td>
				<input type="checkbox" id="protect_plugin_value_user" name="protect_plugin_value_user" value="<?php echo esc_attr('protect_plugin_value_user'); ?>" <?php if($protect_plugin_value_user == true) { echo('checked="checked"'); } ?> />
				<?php _e('Yes', $this->textdomain ); ?><hr></td> 
			</tr>

			<tr valign="top"> 
				<th width="108" scope="row"><?php _e('Exclude admin user', $this->textdomain );?> :</th> 
				<td>
				<input type="checkbox" id="protect_plugin_value_admin" name="protect_plugin_value_admin" value="<?php echo esc_attr('protect_plugin_value_admin'); ?>" <?php if($protect_plugin_value_admin == true) { echo('checked="checked"'); } ?> />
				<?php _e('Yes', $this->textdomain ); ?><hr></td> 
			</tr>

			<tr valign="top"> 
				<th width="108" scope="row"><?php _e('Exclude pages', $this->textdomain );?> :</th> 
				<td>
				<input name="protect_plugin_value_pages" type="text" value="<?php echo _e(esc_attr($protect_plugin_value_pages));?>" size="30"/>
				<br /><?php _e('Input page ID. ex: 1,2,3,4', $this->textdomain ); ?></td> 
			</tr>

			<tr valign="top"> 
				<th width="108" scope="row"><?php _e('Exclude posts', $this->textdomain );?> :</th> 
				<td>
				<input name="protect_plugin_value_posts" type="text" value="<?php echo _e(esc_attr($protect_plugin_value_posts));?>" size="30"/>
				<br /><?php _e('Input post ID. ex: 1,2,3,4', $this->textdomain ); ?><hr></td> 
			</tr>

			<tr valign="top"> 
				<th width="108" scope="row"><?php _e('Protect only specified pages', $this->textdomain );?> :</th> 
				<td>
				<input name="protect_plugin_value_include" type="text" value="<?php echo _e(esc_attr($protect_plugin_value_include));?>" size="30"/>
				<br /><?php _e('Input page ID. ex: 1,2,3,4', $this->textdomain ); ?></td> 
			</tr>

			<tr valign="top"> 
				<th width="108" scope="row"><?php _e('Protect only specified posts', $this->textdomain );?> :</th> 
				<td>
				<input name="protect_plugin_value_include_posts" type="text" value="<?php echo _e(esc_attr($protect_plugin_value_include_posts));?>" size="30"/>
				<br /><?php _e('Input post ID. ex: 1,2,3,4', $this->textdomain ); ?><hr></td> 
			</tr>
		
			<tr>
			    <th width="108" scope="row"><?php _e('Save this setting', $this->textdomain );?> :</th> 
			    <td>
				<input type="submit" name="Protect_Copy_save" value="<?php _e(esc_attr('Save', $this->textdomain )); ?>" /><br /></td>
		    </tr>
		</table>
		</fieldset>
	</form>
	</table>

</div>


<div style="float:right;margin-top:160px;">

<?php
/***
 * レコメンドプラグイン
***/
function is_post_view_plugin_active($file) {
	$is_post_view_active = false;
	foreach ((array) get_option('active_plugins') as $val) {
		if (preg_match('/'.preg_quote($file, '/').'/i', $val)) {
			$is_post_view_active = true;
			break;
		}
	}
	return $is_post_view_active;
}
$is_post_view_active = is_post_view_plugin_active('post-views-stats-counter/wp_pvscounter.php');

if($is_post_view_active == false){ ?>
<div style="background-color:white;padding:15px;margin-bottom:70px;border-left:solid #46b450 5px;font-weight:500;">
<?php _e('Another recommended SEO plugin:', $this->textdomain );?><br>

	<?php if (is_multisite() == true){ ?>
	<a href="<?php echo site_url(); ?>/wp-admin/network/plugin-install.php?tab=plugin-information&plugin=post-views-stats-counter" target="_blank"><?php _e('Post Views Stats Counter', $this->textdomain );?></a><br><br>
	<?php }else{ ?>
	<a href="<?php echo site_url(); ?>/wp-admin/plugin-install.php?tab=plugin-information&plugin=post-views-stats-counter" target="_blank"><?php _e('Post Views Stats Counter', $this->textdomain );?></a><br><br>
	<?php } ?>

<?php _e('Additional plugins are available for free.', $this->textdomain );?><br>
</div>
<?php } 

/***
 * レコメンドプラグイン 終了
***/
?>

<?php _e('Please see the explanation of this plugin from here!', $this->textdomain );?>
<br />
<a href="https://global-s-h.com/wp_protect/en/" target="_blank">https://global-s-h.com/wp_protect/</a>

<br><a href="https://wordpress.org/support/plugin/wp-copy-protect-with-color-design" target="_blank"> <?php _e('Help page for troubles', $this->textdomain );?> </a> | <a href="https://global-s-h.com/wp_protect/en/index.php#donate" target="_blank"> <?php _e('Donate', $this->textdomain );?> </a> | 
<br /><br />
<iframe src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2Fwebshakehands&amp;width=285&amp;height=65&amp;show_faces=false&amp;colorscheme=light&amp;stream=false&amp;show_border=false&amp;header=false&amp;" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:305px; height:65px;" allowTransparency="true"></iframe>

</div>