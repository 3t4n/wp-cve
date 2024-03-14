<?php
 /*Template Name: IfSo Triggers
 */

$is_block_theme = function_exists('wp_is_block_theme') && wp_is_block_theme();
if($is_block_theme){
    echo "<head>" . wp_head() . "</head>";
    block_template_part('header');
}
else
    get_header();

/* restrict this page to only authorized members (administartors) */
$isUserAdmin = current_user_can('administrator');
$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$apply_the_content = (bool) ((isset($_REQUEST['apply_the_content'])) ? $_REQUEST['apply_the_content'] : ((isset($_COOKIE['ifso_trigger_the_content'])) ? $_COOKIE['ifso_trigger_the_content'] : true));
setcookie('ifso_trigger_the_content',(int)$apply_the_content,strtotime( '+30 days' ),'/');

if (!$isUserAdmin) {
	?>
	<div id="primary">
    	<div id="content" role="main">
    	You are not authorized to view this page
    	</div>
    </div>

	<?php
    if($is_block_theme)
        block_template_part('footer');
    else
        get_footer();
}
else {
$post = get_post();

$data_versions = get_post_meta( $post->ID, 'ifso_trigger_version', false );
$data_default = get_post_meta( $post->ID, 'ifso_trigger_default', true );

function display_version_content_html($version_symbol, $version_content) {
?>
	<div class="version-content-wrapper">
		<div class="version-symbol" style="padding:10px;border-bottom: 1px solid #e1e1e1;border-top: 1px solid #e1e1e1;"><?php echo $version_symbol; ?></div>
		<div class="version-content" style="padding:0 20px;margin: 30px 0;"><?php echo $version_content; ?></div>
	</div>
<?php
}

function display_version_content_with_symbol_html($version_index, $version_content) {
	$version_symbol = "Version ".chr(65 + $version_index);
	return display_version_content_html($version_symbol, $version_content);
}
?>
<style>
    .apply_the_content_controls .button{
        color: #3858e9!important;
        background: #f6f7f7;
        font-size: 13px;
        line-height: 2.15384615;
        min-height: 30px;
        padding: 0 10px;
        border-radius: 3px;
        border: 1px #3858e9 solid;
        margin: 10px;
        text-transform: initial;
    }
</style>
<div class="apply_the_content_controls">
    <?php if($apply_the_content): ?>
        <a class="button" href="<?php echo $url;?>&apply_the_content=0">View as simple text</a>
    <?php endif;if(!$apply_the_content): ?>
        <a class="button" href="<?php echo $url;?>&apply_the_content=1">View as post content</a>
    <?php endif; ?>
</div>
<div id="primary" style="background:#fff;">
    <div id="content" role="main">
    	<div class="data-versions-content-wrapper">

    	<?php 
    		if (!empty($data_versions)) {
				foreach($data_versions as $index => $version_content) {
                    if($apply_the_content)
                        $version_content = apply_filters('the_content',$version_content);
					display_version_content_with_symbol_html($index, $version_content);
    			}
	    	}

    		if (!empty($data_default)) {
                if($apply_the_content)
                    $data_default = apply_filters('the_content',$data_default);
				display_version_content_html("Default Version", $data_default);
    		}
    	?>

    	</div>
    </div>
</div>
<?php
    if($is_block_theme){
        block_template_part('footer');
        wp_footer();
    }
    else
        get_footer();
	}
?>