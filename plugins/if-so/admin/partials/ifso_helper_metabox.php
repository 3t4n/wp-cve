<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>

<?php if(!empty($_REQUEST['post']) && !empty($_REQUEST['action']) && $_REQUEST['action']==='edit'  && !isset($_COOKIE['ifso_hide_need_help'])){ ?>
<div class="ifso-modal-need-help">
    <span class="closeX">X</span>
    <span class="content"><p>Is everything working as expected?</p> We are here to help.</span>
</div>
<?php } ?>

<div class="helper-metabox-container">
	<ul class="ifso-helper-metabox-doc">
        <li><a href="https://www.if-so.com/help/documentation/how-to-create-dynamic-content-trigger/?utm_source=Plugin&utm_medium=helpBox&utm_campaign=creatingAtrigger" target="_blank"><?php _e('How to create a trigger', 'if-so'); ?></a></li>
	<li><a href="https://www.if-so.com/help/documentation/?utm_source=Plugin&utm_medium=helpBox&utm_campaign=HowItWorks" target="_blank"><?php _e('What is If-So', 'if-so'); ?></a></li>
    <li><a href="https://www.if-so.com/help/troubleshooting/?utm_source=Plugin&utm_medium=helpBox&utm_campaign=troubleshooting" target="_blank"><?php _e('Troubleshooting', 'if-so'); ?></a></li>
	<li><a href="https://www.if-so.com/help/frequently-asked-questions/general-faqs/?utm_source=Plugin&utm_medium=helpBox&utm_campaign=FAQs" target="_blank"><?php _e('FAQs', 'if-so'); ?></a></li>
	<li><a href="https://www.if-so.com/help/documentation/dynamic-keyword-insertion/?utm_source=Plugin&utm_medium=helpBox&utm_campaign=dki" target="_blank"><?php _e('Dynamic Keyword Insertion', 'if-so'); ?></a></li>

	</ul>
	<?php _e('Need further assistance?', 'if-so'); ?><br>
    <a href="https://www.if-so.com/help/support/?utm_source=Plugin&utm_medium=helpBox&utm_campaign=contactSupport" target="_blank"><?php _e('Contact support', 'if-so'); ?></a>
</div>
