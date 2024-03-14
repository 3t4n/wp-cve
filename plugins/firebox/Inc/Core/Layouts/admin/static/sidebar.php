<?php
/**
 * @package         FireBox
 * @version         2.1.8 Free
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}
$plugin_slug = $this->data->get('plugin_slug');
$plugin_name = $this->data->get('plugin_name');
$call_to_action_label = $this->data->get('call_to_action_label');
?>
<div class="fpframework-admin-container--sidebar--outer border-r-[1px] border-grey-3 border-solid border-t-0 border-l-0 border-b-0 sticky top-2 w-[207px] max-h-screen z-1 shrink-0 dark:border-default">
	<div class="fpframework-admin-container--sidebar--outer--inner shrink-0 px-3 py-5 h-full flex flex-col justify-between relative">
		<a href="#" class="fpf-admin-sidebar-toggle p-[3px] rounded-full border-grey-3 text-default hover:border-grey-1 dark:text-white dark:bg-dark-1 dark:border-default dark:hover:border-gray-400 border-solid border absolute -right-[16px] top-[46px] bg-[#f8f8f8] inline-flex shadow-none">
			<svg class="fpf-admin-sidebar-shrink-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<mask id="mask0_465_8999" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="24" height="24">
					<rect width="24" height="24" fill="#D9D9D9"/>
				</mask>
				<g mask="url(#mask0_465_8999)">
					<path d="M13.9995 17.6534L8.3457 11.9995L13.9995 6.3457L15.0534 7.39953L10.4534 11.9995L15.0534 16.5995L13.9995 17.6534Z" fill="currentColor"/>
				</g>
			</svg>
			<svg class="fpf-admin-sidebar-expand-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<mask id="mask0_406_22915" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="24" height="24">
					<rect width="24" height="24" fill="#D9D9D9"/>
				</mask>
				<g mask="url(#mask0_406_22915)">
					<path d="M3.5 17.6344V16.1345H20.5V17.6344H3.5ZM3.5 12.7498V11.2499H20.5V12.7498H3.5ZM3.5 7.86521V6.36523H20.5V7.86521H3.5Z" fill="currentColor"/>
				</g>
			</svg>
		</a>
		
		<div class="flex flex-col gap-5 fpframework-admin-container--sidebar--outer--inner--item">
			<div class="h-[45px] flex">
				<img src="<?php echo esc_url(FBOX_MEDIA_ADMIN_URL . 'images/logo_full.svg'); ?>" class="w-[106px] block dark:hidden" alt="<?php esc_attr_e($plugin_name); ?> Logo" />
				<img src="<?php echo esc_url(FBOX_MEDIA_ADMIN_URL . 'images/logo_white_full.svg'); ?>" class="w-[106px] hidden dark:block" alt="<?php esc_attr_e($plugin_name); ?> Logo" />
			</div>
			<a href="<?php echo esc_url(admin_url('post-new.php?post_type=' . esc_attr($plugin_slug))); ?>" class="fpf-open-library-modal text-sm rounded bg-accent text-white no-underline px-2 py-1 flex items-center justify-center gap-1 hover:bg-accent-hover">
				<svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<mask id="mask0_498_1527" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="24" height="24">
						<rect width="24" height="24" fill="#D9D9D9"/>
					</mask>
					<g mask="url(#mask0_498_1527)">
						<path d="M11.25 16.75H12.75V12.75H16.75V11.25H12.75V7.25H11.25V11.25H7.25V12.75H11.25V16.75ZM12.0016 21.5C10.6877 21.5 9.45268 21.2506 8.29655 20.752C7.1404 20.2533 6.13472 19.5765 5.2795 18.7217C4.42427 17.8669 3.74721 16.8616 3.24833 15.706C2.74944 14.5504 2.5 13.3156 2.5 12.0017C2.5 10.6877 2.74933 9.45268 3.248 8.29655C3.74667 7.1404 4.42342 6.13472 5.27825 5.2795C6.1331 4.42427 7.13834 3.74721 8.29398 3.24833C9.44959 2.74944 10.6844 2.5 11.9983 2.5C13.3122 2.5 14.5473 2.74933 15.7034 3.248C16.8596 3.74667 17.8652 4.42342 18.7205 5.27825C19.5757 6.1331 20.2527 7.13834 20.7516 8.29398C21.2505 9.44959 21.5 10.6844 21.5 11.9983C21.5 13.3122 21.2506 14.5473 20.752 15.7034C20.2533 16.8596 19.5765 17.8652 18.7217 18.7205C17.8669 19.5757 16.8616 20.2527 15.706 20.7516C14.5504 21.2505 13.3156 21.5 12.0016 21.5ZM12 20C14.2333 20 16.125 19.225 17.675 17.675C19.225 16.125 20 14.2333 20 12C20 9.76664 19.225 7.87498 17.675 6.32498C16.125 4.77498 14.2333 3.99998 12 3.99998C9.76664 3.99998 7.87498 4.77498 6.32498 6.32498C4.77498 7.87498 3.99998 9.76664 3.99998 12C3.99998 14.2333 4.77498 16.125 6.32498 17.675C7.87498 19.225 9.76664 20 12 20Z" fill="white"/>
					</g>
				</svg>
				<?php esc_html_e($call_to_action_label); ?>
			</a>
			<div class="flex flex-col">
				<?php foreach ($this->data->get('navigation', []) as $item): ?>
					<a href="<?php echo esc_url($item['url']); ?>" class="py-[11px] font-medium no-underline shadow-none text-base <?php echo (isset($item['slug']) && $item['slug'] === $this->data->get('current_page')) ? ' text-accent dark:text-dark-accent' : 'text-default dark:text-white'; ?> hover:text-accent dark:hover:text-dark-accent-hover"<?php echo isset($item['external']) ? ' target="_blank"' : ''; ?>><?php esc_html_e($item['label']); ?></a>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="flex flex-col gap-5 fpframework-admin-container--sidebar--outer--inner--item">
			
			<div class="flex flex-col gap-[10px] dark:text-white">
				<div class="flex items-center gap-[4px]">
					<svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
						<mask id="mask0_830_2298" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="24" height="25">
							<rect y="0.5" width="24" height="24" fill="#D9D9D9"/>
						</mask>
						<g mask="url(#mask0_830_2298)">
							<path d="M11.25 16.1923H12.7596V11.6365L14.6096 13.4865L15.6538 12.4423L12.0096 8.79808L8.35578 12.4423L9.39998 13.4865L11.25 11.6365V16.1923ZM12.0016 22C10.6877 22 9.45268 21.7506 8.29655 21.252C7.1404 20.7533 6.13472 20.0765 5.2795 19.2217C4.42427 18.3669 3.74721 17.3616 3.24833 16.206C2.74944 15.0504 2.5 13.8156 2.5 12.5017C2.5 11.1877 2.74933 9.95268 3.248 8.79655C3.74667 7.6404 4.42342 6.63472 5.27825 5.7795C6.1331 4.92427 7.13834 4.24721 8.29398 3.74833C9.44959 3.24944 10.6844 3 11.9983 3C13.3122 3 14.5473 3.24933 15.7034 3.748C16.8596 4.24667 17.8652 4.92342 18.7205 5.77825C19.5757 6.6331 20.2527 7.63834 20.7516 8.79398C21.2505 9.94959 21.5 11.1844 21.5 12.4983C21.5 13.8122 21.2506 15.0473 20.752 16.2034C20.2533 17.3596 19.5765 18.3652 18.7217 19.2205C17.8669 20.0757 16.8616 20.7527 15.706 21.2516C14.5504 21.7505 13.3156 22 12.0016 22ZM12 20.5C14.2333 20.5 16.125 19.725 17.675 18.175C19.225 16.625 20 14.7333 20 12.5C20 10.2666 19.225 8.37498 17.675 6.82498C16.125 5.27498 14.2333 4.49998 12 4.49998C9.76664 4.49998 7.87498 5.27498 6.32498 6.82498C4.77498 8.37498 3.99998 10.2666 3.99998 12.5C3.99998 14.7333 4.77498 16.625 6.32498 18.175C7.87498 19.725 9.76664 20.5 12 20.5Z" fill="#EF2B10"/>
						</g>
					</svg>
					<span class="text-xl"><?php esc_html_e(fpframework()->_('FPF_UPGRADE_TO_PRO')); ?></span>
				</div>
				<?php esc_html_e(fpframework()->_('FPF_UPGRADE_NOW_20_OFF')); ?>
				<a href="#" class="fpf-modal-opener block border border-[#EF2B10] border-solid rounded-[4px] p-1 font-semibold text-center text-[#EF2B10] no-underline hover:bg-[#EF2B10] hover:text-white" data-fpf-modal="#fpfUpgradeToPro" data-fpf-plugin="<?php esc_attr_e($this->data->get('plugin_name')); ?>"><?php esc_html_e(fpframework()->_('FPF_UPGRADE_NOW')); ?></a>
			</div>
			
			<div class="flex flex-col">
				<a href="https://www.fireplugins.com/contact/?topic=Other" target="_blank" class="py-[11px] font-medium no-underline text-default dark:text-white shadow-none hover:text-accent dark:hover:text-dark-accent text-base"><?php esc_html_e(fpframework()->_('FPF_GIVE_FEEDBACK')); ?></a>
				<a href="https://www.fireplugins.com/changelog/" target="_blank" class="py-[11px] font-medium no-underline text-default dark:text-white shadow-none hover:text-accent dark:hover:text-dark-accent text-base"><?php esc_html_e(fpframework()->_('FPF_WHATS_NEW')); ?></a>
				<a href="https://www.fireplugins.com/docs/<?php esc_attr_e($plugin_slug); ?>/" target="_blank" class="py-[11px] font-medium no-underline text-default dark:text-white shadow-none hover:text-accent dark:hover:text-dark-accent text-base"><?php esc_html_e(fpframework()->_('FPF_HELP')); ?></a>
				<a href="#" class="py-[11px] flex items-center justify-between gap-1 font-medium no-underline text-default dark:text-white shadow-none hover:text-accent dark:hover:text-dark-accent text-base fpframework-toggle-theme">
					<?php esc_html_e(fpframework()->_('FPF_DARK_MODE')); ?>
					<label class="flex cursor-pointer w-[30px] h-[16px] rounded-full bg-white border-[2px] border-solid border-default p-[1px] hover:border-black dark:bg-accent dark:hover:bg-accent-hover dark:p-[3px] dark:justify-end dark:border-none">
						<input type="checkbox" class="hidden">
						<div class="w-[10px] h-[10px] bg-default rounded-full dark:bg-white"></div>
					</label>
				</a>
				<div class="py-[11px] flex justify-between gap-1 font-medium text-base text-default dark:text-white">
					<?php esc_html_e(fpframework()->_('FPF_VERSION')); ?>
					<span class="inline-flex items-center gap-[4px]">
						<span class="flex hidden fpf-plugin-version-outdated" title="<?php esc_html_e(sprintf(fpframework()->_('FPF_PLUGIN_OUDATED_PLEASE_UPDATE'), firebox()->_('FB_PLUGIN_NAME'))); ?>">
							<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
								<mask id="mask0_760_5715" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="16" height="16">
									<rect width="16" height="16" fill="#D9D9D9"/>
								</mask>
								<g mask="url(#mask0_760_5715)">
									<path d="M7.49984 11.166H8.4998V7.33267H7.49984V11.166ZM7.99982 6.19165C8.15239 6.19165 8.28027 6.14005 8.38347 6.03685C8.48667 5.93365 8.53827 5.80577 8.53827 5.6532C8.53827 5.50064 8.48667 5.37276 8.38347 5.26955C8.28027 5.16635 8.15239 5.11475 7.99982 5.11475C7.84725 5.11475 7.71937 5.16635 7.61617 5.26955C7.51297 5.37276 7.46137 5.50064 7.46137 5.6532C7.46137 5.80577 7.51297 5.93365 7.61617 6.03685C7.71937 6.14005 7.84725 6.19165 7.99982 6.19165ZM8.00094 14.3327C7.12498 14.3327 6.30163 14.1664 5.53087 13.834C4.7601 13.5015 4.08965 13.0504 3.5195 12.4805C2.94935 11.9106 2.49798 11.2404 2.16539 10.47C1.8328 9.69959 1.6665 8.8764 1.6665 8.00045C1.6665 7.12449 1.83273 6.30114 2.16517 5.53038C2.49762 4.75962 2.94878 4.08916 3.51867 3.51902C4.08857 2.94886 4.75873 2.49749 5.52915 2.1649C6.29956 1.83231 7.12275 1.66602 7.9987 1.66602C8.87466 1.66602 9.69802 1.83224 10.4688 2.16468C11.2395 2.49713 11.91 2.94829 12.4801 3.51818C13.0503 4.08808 13.5017 4.75824 13.8343 5.52867C14.1668 6.29908 14.3331 7.12226 14.3331 7.99822C14.3331 8.87417 14.1669 9.69753 13.8345 10.4683C13.502 11.239 13.0509 11.9095 12.481 12.4796C11.9111 13.0498 11.2409 13.5012 10.4705 13.8338C9.70008 14.1664 8.87689 14.3327 8.00094 14.3327ZM7.99982 13.3327C9.48871 13.3327 10.7498 12.816 11.7832 11.7827C12.8165 10.7493 13.3332 9.48822 13.3332 7.99933C13.3332 6.51044 12.8165 5.24933 11.7832 4.216C10.7498 3.18267 9.48871 2.666 7.99982 2.666C6.51093 2.666 5.24982 3.18267 4.21649 4.216C3.18315 5.24933 2.66649 6.51044 2.66649 7.99933C2.66649 9.48822 3.18315 10.7493 4.21649 11.7827C5.24982 12.816 6.51093 13.3327 7.99982 13.3327Z" fill="#F4B400"/>
								</g>
							</svg>
						</span>
						<span class="flex fpf-plugin-version-updated" title="<?php esc_html_e(sprintf(fpframework()->_('FPF_PLUGIN_IS_UP_TO_DATE'), firebox()->_('FB_PLUGIN_NAME'))); ?>">
							<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
								<mask id="mask0_873_7867" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="16" height="16">
									<rect width="16" height="16" fill="#D9D9D9"/>
								</mask>
								<g mask="url(#mask0_873_7867)">
									<path d="M7.05367 10.8372L11.5357 6.35514L10.8332 5.65259L7.05367 9.43207L5.15367 7.53207L4.45112 8.23462L7.05367 10.8372ZM8.00094 14.3346C7.12498 14.3346 6.30163 14.1684 5.53087 13.8359C4.7601 13.5035 4.08965 13.0523 3.5195 12.4824C2.94935 11.9125 2.49798 11.2424 2.16539 10.472C1.8328 9.70154 1.6665 8.87836 1.6665 8.0024C1.6665 7.12645 1.83273 6.30309 2.16517 5.53234C2.49762 4.76157 2.94878 4.09111 3.51867 3.52097C4.08857 2.95081 4.75873 2.49944 5.52915 2.16685C6.29956 1.83426 7.12275 1.66797 7.9987 1.66797C8.87466 1.66797 9.69802 1.83419 10.4688 2.16664C11.2395 2.49908 11.91 2.95025 12.4801 3.52014C13.0503 4.09004 13.5017 4.7602 13.8343 5.53062C14.1668 6.30103 14.3331 7.12421 14.3331 8.00017C14.3331 8.87612 14.1669 9.69948 13.8345 10.4702C13.502 11.241 13.0509 11.9115 12.481 12.4816C11.9111 13.0518 11.2409 13.5031 10.4705 13.8357C9.70008 14.1683 8.87689 14.3346 8.00094 14.3346ZM7.99982 13.3346C9.48871 13.3346 10.7498 12.818 11.7832 11.7846C12.8165 10.7513 13.3332 9.49017 13.3332 8.00129C13.3332 6.5124 12.8165 5.25129 11.7832 4.21795C10.7498 3.18462 9.48871 2.66795 7.99982 2.66795C6.51093 2.66795 5.24982 3.18462 4.21649 4.21795C3.18315 5.25129 2.66649 6.5124 2.66649 8.00129C2.66649 9.49017 3.18315 10.7513 4.21649 11.7846C5.24982 12.818 6.51093 13.3346 7.99982 13.3346Z" fill="#0F9D58"/>
								</g>
							</svg>
						</span>
						<?php esc_html_e($this->data->get('plugin_version')); ?>
					</span>
				</div>
			</div>
		</div>
	</div>
</div>