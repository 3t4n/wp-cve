<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
add_action('update_seo_meta_tags', 'seometa_site_admin_options_process');

//ref taken from - http://codex.wordpress.org/Data_Validation
function seometa_site_admin_options_process() {
    if(isset($_POST) && !empty($_POST))
    {
        foreach ($_POST as $key => $value) {
            update_option($key , sanitize_text_field($value ));
        }
    }
}

function seometa_site_admin_options() {	
	if ($_POST['action'] == 'update') { do_action( 'update_seo_meta_tags' ); }	
	?>
<div class="wpsmt_content_wrapper">
    <div id="wpsmt_content_top" class="wpsmt_content_cell">
        <div class="tool-box">
	<h3 class="title"><?php _e('Seo Meta Tags') ?></h3> 
	<p>You can enter your meta keywords and description for your homepage.<br>
	This plugin will add Meta description for each individual post as your excerpt of your post.
	This will help your blog to rank better in google. You can easily increase your blog traffic using this plugin.
        <a href="http://www.digcms.com/">digcms.com</a></p>
	
	<form method="post">
	<input type="hidden" name="action" value="update" />
	 <?php wp_nonce_field('seo_meta_tags'); ?>				
		
		<table class="form-table">     
                    <?php smt_input_text_field(SMT_HOME_KEYWORDS, 'Seo Meta Tags Keywords');?>
			
                        <tr>
				<td colspan='2'>
				<label for='seo_meta_tags[keywords]'>Example: <code>&lt;meta name='keywords' content='<strong><font color="blue">wp,Social Networking, Social Media, News, Web, Technology, Web 2.0, Tech, Information, Blog, Facebook, YouTube, Google, Top,Main Page,About wp,Advanced Topics,Backing Up Your Database,Backing Up Your wp Files,Blog Design and Layout,CSS,Contributing to wp,Core Update Host Compatibility,Database Description,Developer Documentation</font></strong>'&gt;</code></label>
				</td>
			</tr>

                        <?php smt_input_textarea_field(SMT_HOME_DESCRIPTION, 'Seo Meta Tags Description');?>
                        <tr>
				<td colspan='2'>
				<label for='seo_meta_tags[description]'>Example: <code>&lt;meta name='description' content='<strong><font color="blue">digcms.com is focused on design and web-development. We deliver useful information, latest trends and techniques, useful ideas, innovative approaches and tools. Social Media news blog covering cool new websites and social networks: Facebook, Google, Twitter, MySpace and YouTube.  The latest web technology news, via RSS daily.</font></strong>'&gt;</code></label>
				</td>
			</tr>
		</table>
	<p class="submit"><input type="submit" class="button-primary" value="Save Changes" /></p>
	</form>
	</div>
    </div>
    <div id="sidebar-container" class="wpsmt_content_cell">
        
    </div>
</div>
	
	<?php
}
