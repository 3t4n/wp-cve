<?php

/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2016 GPL 3.0+
 * @license   GPL-2.0+
 * @link      http://codeat.co
 *
 * @phpcs:disable WordPress.Security.EscapeOutput
 */
?>
<div class="wrap glossary-settings">

	<h2><?php 
_e( 'Glossary General Settings', GT_TEXTDOMAIN );
?></h2>

	<div class="postbox settings-tab">
		<div class="inside">
			<a href="<?php 
echo  esc_html( add_query_arg( 'gl_count_terms', true ) ) ;
?>" class="button button-primary" style="float:right"><?php 
_e( 'Update Terms Count', GT_TEXTDOMAIN );
?></a>
			<div class="gl-labels">
				<strong><?php 
_e( 'Single Terms:', GT_TEXTDOMAIN );
?> <span><?php 
echo  gl_get_terms_count() ;
?></span></strong> &#124;
				<strong><?php 
_e( 'Additional Terms:', GT_TEXTDOMAIN );
?> <span><?php 
echo  gl_get_related_terms_count() ;
?></span></strong> &#124;
				<strong><?php 
_e( 'Total Glossary Terms:', GT_TEXTDOMAIN );
?> <span><?php 
echo  (int) gl_get_terms_count() + (int) gl_get_related_terms_count() ;
?></span></strong>
			</div>
			<small><?php 
_e( 'The glossary terms amount count is scheduled once a day. Use this button if you need to manually calculate it.', GT_TEXTDOMAIN );
?></small>
		</div>
	</div>

	<div id="tabs" class="settings-tab">
		<ul>
			<li><a href="#tabs-settings"><?php 
_e( 'Settings', GT_TEXTDOMAIN );
?></a></li>
<?php 
?>
			<li><a href="#tabs-shortcodes"><?php 
_e( 'Shortcodes', GT_TEXTDOMAIN );
?></a></li>
			<li><a href="#tabs-impexp"><?php 
_e( 'Import/Export', GT_TEXTDOMAIN );
?></a></li>
		</ul>
<?php 
$pro = ' <span class="gl-pro-label">' . __( 'This feature is available only for PRO users.', GT_TEXTDOMAIN ) . '</span>';
/* translators: The placeholder will be replace by a url */
$doc = __( '<a href="%s" target="_blank">Not sure? check out Glossary\'s documentation</a>', GT_TEXTDOMAIN );
//phpcs:ignore
require 'tabs/settings.php';
require 'tabs/extra.php';
require 'tabs/shortcodes.php';
require 'tabs/impexp.php';
?>
	</div>
<?php 

if ( !empty($pro) ) {
    ?>
	<div class="right-column-widget">
		<div class="right-column-settings-page metabox-holder">
			<div class="postbox codeat">
				<div class="inside">
					<a href="<?php 
    echo  get_dashboard_url(), 'edit.php?post_type=glossary&amp;page=glossary-pricing' ;
    ?>" target="_blank">
						<img src="https://glossary.codeat.co/glossary-free.jpg">
					</a>
				</div>
			</div>
		</div>
	</div>
	<?php 
}

?>
</div>
