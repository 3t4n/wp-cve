<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    $filepath = MJTC_PLUGIN_PATH . 'includes/css/style.php';
    $filestring = file_get_contents($filepath);
    $color1 = MJTC_includer::MJTC_getModel('majesticsupport')->getColorCode($filestring, 1);
    $color3 = MJTC_includer::MJTC_getModel('majesticsupport')->getColorCode($filestring, 3);
?>
<div id="msadmin-wrapper">
    <div id="msadmin-leftmenu">
        <?php  MJTC_includer::MJTC_getClassesInclude('msadminsidemenu'); ?>
    </div>
    <div id="msadmin-data">
    	<?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageTitle('shortcoses'); ?>
    	<div id="msadmin-data-wrp">
			<div id="ms-shortcode-wrapper">
				<div class="ms-shortcode-1"><?php echo esc_html(__('Majestic Support / Majestic Support Control Panel','majestic-support')); ?></div>
				<div class="ms-shortcode-2"><?php echo esc_html("[majesticsupport]"); ?></div>
				<div class="ms-shortcode-3"><?php echo esc_html(__("Majestic Support / Majestic Support main control panel",'majestic-support')); ?></div>
			</div>
			<div id="ms-shortcode-wrapper">
				<div class="ms-shortcode-1"><?php echo esc_html(__('Add Ticket','majestic-support')); ?></div>
				<div class="ms-shortcode-2"><?php echo esc_html("[majesticsupport_addticket]"); ?></div>
				<div class="ms-shortcode-3"><?php echo esc_html(__("Add new ticket form for both the user and the agent",'majestic-support')); ?></div>
			</div>
			<?php if(in_array('multiform', majesticsupport::$_active_addons)){ ?>
				<div id="ms-shortcode-wrapper">
					<div class="ms-shortcode-1"><?php echo esc_html(__('Add Ticket Using Multiform','majestic-support')); ?></div>
					<?php 
						$multiforms = majesticsupport::$_data[0]['multiforms'];
						foreach ($multiforms as $multiform) {
						 	$data = '<div class="ms-shortcode-2">
						 		[majesticsupport_addticket_multiform formid='.esc_attr($multiform->id).']';
					 			$data .= '<span class="ms-shortcode-name">('.esc_html(majesticsupport::MJTC_getVarValue($multiform->title)).'</span>';
						 		if (isset($multiform->departmentname)) {
						 			$data .= '<span class="ms-shortcode-dept"> - '.esc_html($multiform->departmentname).')</span>';
						 		} else {
						 			$data .= '<span class="ms-shortcode-dept">)</span>';
						 		}
					 		$data .= '</div>';
					 		echo wp_kses($data, MJTC_ALLOWED_TAGS);
						} ?>
					<div class="ms-shortcode-3"><?php echo esc_html(__("Add new ticket form for both the user and the agent",'majestic-support')); ?></div>
				</div>
			<?php } ?>
			<div id="ms-shortcode-wrapper">
				<div class="ms-shortcode-1"><?php echo esc_html(__('My Tickets','majestic-support')); ?></div>
				<div class="ms-shortcode-2"><?php echo esc_html("[majesticsupport_mytickets]"); ?></div>
				<div class="ms-shortcode-3"><?php echo esc_html(__("My tickets for both user and agent",'majestic-support')); ?></div>
			</div>
			<?php if(in_array('download', majesticsupport::$_active_addons)){ ?>
				<div id="ms-shortcode-wrapper">
					<div class="ms-shortcode-1"><?php echo esc_html(__('Downloads','majestic-support')); ?></div>
					<div class="ms-shortcode-2"><?php echo esc_html("[majesticsupport_downloads]"); ?></div>
					<div class="ms-shortcode-3"><?php echo esc_html(__("List downloads",'majestic-support')); ?></div>
				</div>
				<div id="ms-shortcode-wrapper">
					<div class="ms-shortcode-1"><?php echo esc_html(__('Latest Downloads','majestic-support')); ?></div>
					<div class="ms-shortcode-2"><?php echo esc_html("[majesticsupport_downloads_latest]"); ?></div>
					<div class="ms-shortcode-3"><?php echo esc_html(__("Show latest downloads. Options",'majestic-support')).': text_color="'.esc_attr($color3).'" '.esc_html(__("and",'majestic-support')).' background_color="'.esc_attr($color1).'" '.esc_html(__("i.e.",'majestic-support')).' [majesticsupport_downloads_latest text_color="'.esc_attr($color3).'" background_color="'.esc_attr($color1).'"]'; ?></div>
				</div>
				<div id="ms-shortcode-wrapper">
					<div class="ms-shortcode-1"><?php echo esc_html(__('Popular Downloads','majestic-support')); ?></div>
					<div class="ms-shortcode-2"><?php echo esc_html("[majesticsupport_downloads_popular]"); ?></div>
					<div class="ms-shortcode-3"><?php echo esc_html(__("Show popular downloads. Options",'majestic-support')).': text_color="'.esc_attr($color3).'" '.esc_html(__("and",'majestic-support')).' background_color="'.esc_attr($color1).'" '.esc_html(__("i.e.",'majestic-support')).' [majesticsupport_downloads_popular text_color="'.esc_attr($color3).'" background_color="'.esc_attr($color1).'"]'; ?></div>
				</div>
			<?php } ?>
			<?php if(in_array('knowledgebase', majesticsupport::$_active_addons)){ ?>
				<div id="ms-shortcode-wrapper">
					<div class="ms-shortcode-1"><?php echo esc_html(__('Knowledge Base','majestic-support')); ?></div>
					<div class="ms-shortcode-2"><?php echo esc_html("[majesticsupport_knowledgebase]"); ?></div>
					<div class="ms-shortcode-3"><?php echo esc_html(__("List knowledge base",'majestic-support')); ?></div>
				</div>
				<div id="ms-shortcode-wrapper">
					<div class="ms-shortcode-1"><?php echo esc_html(__('Latest Knowledge Base','majestic-support')); ?></div>
					<div class="ms-shortcode-2"><?php echo esc_html("[majesticsupport_knowledgebase_latest]"); ?></div>
					<div class="ms-shortcode-3"><?php echo esc_html(__("Show latest knowledge base. Options",'majestic-support')).': text_color="'.esc_attr($color3).'" '.esc_html(__("and",'majestic-support')).' background_color="'.esc_attr($color1).'" '.esc_html(__("i.e.",'majestic-support')).' [majesticsupport_knowledgebase_latest text_color="'.esc_attr($color3).'" background_color="'.esc_attr($color1).'"]'; ?></div>
				</div>
				<div id="ms-shortcode-wrapper">
					<div class="ms-shortcode-1"><?php echo esc_html(__('Popular knowledge base','majestic-support')); ?></div>
					<div class="ms-shortcode-2"><?php echo esc_html("[majesticsupport_knowledgebase_popular]"); ?></div>
					<div class="ms-shortcode-3"><?php echo esc_html(__("Show popular knowledge base. Options",'majestic-support')).': text_color="'.esc_attr($color3).'" '.esc_html(__("and",'majestic-support')).' background_color="'.esc_attr($color1).'" '.esc_html(__("i.e.",'majestic-support')).' [majesticsupport_knowledgebase_popular text_color="'.esc_attr($color3).'" background_color="'.esc_attr($color1).'"]'; ?></div>
				</div>
			<?php } ?>
			<?php if(in_array('faq', majesticsupport::$_active_addons)){ ?>
				<div id="ms-shortcode-wrapper">
					<div class="ms-shortcode-1"><?php echo esc_html(__("FAQs",'majestic-support')); ?></div>
					<div class="ms-shortcode-2"><?php echo esc_html("[majesticsupport_faqs]"); ?></div>
					<div class="ms-shortcode-3"><?php echo esc_html(__("List FAQs",'majestic-support')); ?></div>
				</div>
				<div id="ms-shortcode-wrapper">
					<div class="ms-shortcode-1"><?php echo esc_html(__("Latest FAQs",'majestic-support')); ?></div>
					<div class="ms-shortcode-2"><?php echo esc_html("[majesticsupport_faqs_latest]"); ?></div>
					<div class="ms-shortcode-3"><?php echo esc_html(__("Show latest FAQs. Options",'majestic-support')).': text_color="'.esc_attr($color3).'" '.esc_html(__("and",'majestic-support')).' background_color="'.esc_attr($color1).'" '.esc_html(__("i.e.",'majestic-support')).' [majesticsupport_faqs_latest text_color="'.esc_attr($color3).'" background_color="'.esc_attr($color1).'"]'; ?></div>
				</div>
				<div id="ms-shortcode-wrapper">
					<div class="ms-shortcode-1"><?php echo esc_html(__("Popular FAQs",'majestic-support')); ?></div>
					<div class="ms-shortcode-2"><?php echo esc_html("[majesticsupport_faqs_popular]"); ?></div>
					<div class="ms-shortcode-3"><?php echo esc_html(__("Show popular FAQs. Options",'majestic-support')).': text_color="'.esc_attr($color3).'" '.esc_html(__("and",'majestic-support')).' background_color="'.esc_attr($color1).'" '.esc_html(__("i.e.",'majestic-support')).' [majesticsupport_faqs_popular text_color="'.esc_attr($color3).'" background_color="'.esc_attr($color1).'"]'; ?></div>
				</div>
			<?php } ?>
			<?php if(in_array('announcement', majesticsupport::$_active_addons)){ ?>
				<div id="ms-shortcode-wrapper">
					<div class="ms-shortcode-1"><?php echo esc_html(__('Announcements','majestic-support')); ?></div>
					<div class="ms-shortcode-2"><?php echo esc_html("[majesticsupport_announcements]"); ?></div>
					<div class="ms-shortcode-3"><?php echo esc_html(__("List announcements",'majestic-support')); ?></div>
				</div>
				<div id="ms-shortcode-wrapper">
					<div class="ms-shortcode-1"><?php echo esc_html(__('Latest Announcements','majestic-support')); ?></div>
					<div class="ms-shortcode-2"><?php echo esc_html("[majesticsupport_announcements_latest]"); ?></div>
					<div class="ms-shortcode-3"><?php echo esc_html(__("Show latest announcements. Options",'majestic-support')).': text_color="'.esc_attr($color3).'" '.esc_html(__("and",'majestic-support')).' background_color="'.esc_attr($color1).'" '.esc_html(__("i.e.",'majestic-support')).' [majesticsupport_announcements_latest text_color="'.esc_attr($color3).'" background_color="'.esc_attr($color1).'"]'; ?></div>
				</div>
				<div id="ms-shortcode-wrapper">
					<div class="ms-shortcode-1"><?php echo esc_html(__('Popular Announcements','majestic-support')); ?></div>
					<div class="ms-shortcode-2"><?php echo esc_html("[majesticsupport_announcements_popular]"); ?></div>
					<div class="ms-shortcode-3"><?php echo esc_html(__("Show popular announcements. Options",'majestic-support')).': text_color="'.esc_attr($color3).'" '.esc_html(__("and",'majestic-support')).' background_color="'.esc_attr($color1).'" '.esc_html(__("i.e.",'majestic-support')).' [majesticsupport_announcements_popular text_color="'.esc_attr($color3).'" background_color="'.esc_attr($color1).'"]'; ?></div>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
