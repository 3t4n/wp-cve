<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly    
?>
<div x-data="getData()" class='imagecomply-dashboard'>
	<div>
		<a
			href='https://www.imagecomply.com/'
			target='_blank'
			class='logo'
		>
			ImageComply
			<span>
				ImageComply
			</span>
		</a>
	</div>

	<h1 class='heading'>
		Dashboard
	</h1>
	<br>

	<form x-show="!lastSaved.imagecomply_license_key || invalidLicenseKey" x-ref='licenseKeyForm' class='license-key-form' @submit.prevent="onFormSubmit" style="display:none;">
		<p x-show="!lastSaved.imagecomply_license_key && !invalidLicenseKey">
			Set your license key to enable ImageComply on your site.<br /><br />Don't have one? <a href='https://www.imagecomply.com/pricing?utm_source=plugin-license-dashboard&utm_medium=plugin&utm_campaign=plugin' class='link' target='_blank'>Get one here</a> and get 30 <strong>free</strong> credits!
		</p>
		<p x-show="invalidLicenseKey">
			Looks like your site is using an invalid license key. Set a new license key to enable ImageComply on your site.<br /><br />Don't have one? <a href='https://www.imagecomply.com/pricing?utm_source=plugin-license-dashboard&utm_medium=plugin&utm_campaign=plugin' class='link' target='_blank'>Get one here</a>.
		</p>
		
		<input type='hidden' name='_wpnonce' x-bind:value='imagecomply_data.nonce_token' />
		<input type='hidden' name='action' value='imagecomply_update_license_key' />
		<label>
			<div>
				License Key
			</div>
			<input type='text' x-model='formData.imagecomply_license_key' name='imagecomply_license_key' />
		</label>
		<button type='submit' class='button--primary'>Save Settings</button>
	</form>

	<template x-if="lastSaved.imagecomply_license_key && !invalidLicenseKey">
		<div class='has-license-key'>
			<div>
				<h2>License</h2>
				Your license key is set. You can now use ImageComply on your site.<br />
				<span class='link' @click='removeApiKey'>Remove your license key</span> to change it or disable ImageComply.
			</div>
			<hr />
			<div>
				<h2>Before Using</h2>
				<p style="max-width: 750px;">
					View our <a href="https://www.imagecomply.com/pricing" target="_blank" class='link'>pricing</a> and <a href="https://www.imagecomply.com/terms-and-conditions" target="_blank" class='link'>Terms & Conditions</a> before using ImageComply.
				<br>
				<p style="max-width: 600px;">
					Have questions? Visit our website, <a href="https://www.imagecomply.com/" target="_blank" class='link'>imagecomply.com</a> for more information and to <a href="https://www.imagecomply.com/contact" target="_blank" class='link'>contact us</a>.
				</p>
			</div>
			<hr />
			<div>
				<h2>Credits</h2>
				You have <strong x-text='credits'></strong> credits remaining<span x-show="plan"> for Pro products</span>.<br /><br />
				<a href='https://www.imagecomply.com/pricing' target='_blank' class='button button--primary'>Buy Credits</a>
			</div>
			<hr x-show="plan" />
			<div x-show="plan">
				<h2>Plan</h2>
				You have a plan with unlimited credits.<br /><br />
				<a href='https://www.imagecomply.com/dashboard' target='_blank' class='button button--primary'>View Dashboard</a>
			</div>
			<hr />
			<div>
				<h2>Settings</h2>

				<br>

				<form @submit.prevent="onSettingsFormSubmit" class='settings-form'>
					<input type='hidden' name='_wpnonce' :value='imagecomply_data.nonce_token' />
					<input type='hidden' name='action' value='imagecomply_update_settings' />


					<div>
						<label>
							<span>Language:</span>
							<select x-model="settings.languageSelect" name="language">
								<template x-for="(option, index) in settings.languageOptions" :key="index">
									<option x-bind:selected="option === settings.imagecomply_alt_text_language" x-text="option" :value="option"></option>
								</template>
								<option x-bind:selected="!settings.languageOptions.includes(settings.languageSelect)">Other</option>
							</select>
							<input x-show="settings.languageSelect == 'Other'" x-model="settings.languageOther" type="text" id="otherLanguage" name="otherLanguage" placeholder="Please specify the language">

							<div x-data="{
								get language() {
									return settings.languageSelect !== 'Other' ? settings.languageSelect : settings.languageOther;
								}
							}" style="visibility:hidden">
								<input type="hidden" name="imagecomply_alt_text_language" x-bind:value="language" />
							</div>
						</label>
					</div>

					<div>
						<label>
							<span>Alt text keywords:</span>
							<input type="text" name="imagecomply_alt_text_keywords" x-model="settings.imagecomply_alt_text_keywords" />
						</label>
						<sub style="display:block;width:500px;margin-top:5px;">Comma separated list of keywords that will be considered when generating alt text for images.</sub>
					</div>

					<div>
						<label>
							<span>Alt text negative keywords:</span>
							<input type="text" name="imagecomply_alt_text_neg_keywords" x-model="settings.imagecomply_alt_text_neg_keywords" />
						</label>
						<sub style="display:block;width:500px;margin-top:5px;">Comma separated list of keywords that should be avoided when generating alt text for images.</sub>
					</div>
					
					<br />

					<div class='toggle-switch-container'>
						<div :class="[settings.imagecomply_generate_on_upload ? 'active' : 'inactive']" class="toggle-switch">
								<label for="toggle"></label>
								<input class="toggle-checkbox" type="checkbox" id="toggle" name="toggle" :checked="settings.imagecomply_generate_on_upload" @click="settings.imagecomply_generate_on_upload = !settings.imagecomply_generate_on_upload">
						</div>
						<label for="toggle">Generate ALT text on upload</label>
					</div>

					<h3>Media Library Settings</h3>

					<div class='toggle-switch-container'>
						<div :class="[settings.imagecomply_medialibrary_show_status ? 'active' : 'inactive']" class="toggle-switch">
								<label for="toggle2"></label>
								<input class="toggle-checkbox" type="checkbox" id="toggle2" name="toggle2" :checked="settings.imagecomply_medialibrary_show_status" @click="settings.imagecomply_medialibrary_show_status = !settings.imagecomply_medialibrary_show_status">
						</div>
						<label for="toggle2">Show Image Status</label>
					</div>

					<div class='toggle-switch-container'>
						<div :class="[settings.imagecomply_medialibrary_show_alt_text ? 'active' : 'inactive']" class="toggle-switch">
								<label for="toggle3"></label>
								<input class="toggle-checkbox" type="checkbox" id="toggle3" name="toggle3" :checked="settings.imagecomply_medialibrary_show_alt_text" @click="settings.imagecomply_medialibrary_show_alt_text = !settings.imagecomply_medialibrary_show_alt_text">
						</div>
						<label for="toggle3">Show Alt Text</label>
					</div>

					<?php
						/*
							<div class='toggle-switch-container'>
							<div :class="[settings.imagecomply_optimize_on_upload ? 'active' : 'inactive']" class="toggle-switch">
									<label for="toggle"></label>
									<input class="toggle-checkbox" type="checkbox" id="toggle" name="toggle" :checked="settings.imagecomply_optimize_on_upload" @click="settings.imagecomply_optimize_on_upload = !settings.imagecomply_optimize_on_upload">
							</div>
							<label for="toggle">Generate optimized images on upload</label>
						</div>
						*/
					?>

					<br>

					<?php
						// THIS ONE HAS THE OPTIMIZE ON UPLOAD CONDITION 

						//<button type='submit' class='button--black' :disabled='settings.imagecomply_generate_on_upload === lastSavedSettings.imagecomply_generate_on_upload && settings.imagecomply_optimize_on_upload === lastSavedSettings.imagecomply_optimize_on_upload'>Save Settings</button>
					?>

					<button type='submit' class='button--black' :disabled="settings.imagecomply_generate_on_upload === lastSavedSettings.imagecomply_generate_on_upload && settings.imagecomply_medialibrary_show_status === lastSavedSettings.imagecomply_medialibrary_show_status && settings.imagecomply_medialibrary_show_alt_text === lastSavedSettings.imagecomply_medialibrary_show_alt_text && settings.imagecomply_alt_text_keywords === lastSavedSettings.imagecomply_alt_text_keywords && settings.imagecomply_alt_text_neg_keywords === lastSavedSettings.imagecomply_alt_text_neg_keywords && ((settings.languageSelect !== 'Other' ? settings.languageSelect : settings.languageOther) === lastSavedSettings.imagecomply_alt_text_language || settings.languageSelect === 'Other' && !settings.languageOther)">Save Settings</button>
				</form>
			</div>
			<hr />
			<div class="actions">
				<style> 
					@keyframes spin {
						from {
							transform: rotate(0deg);
						}
						to {
							transform: rotate(360deg);
						}
					}
				</style>

				<h2>Bulk Actions</h2>

				<div>
					<label>
						<span>Number of images per batch:</span><br>
						<input type="number" x-model="settings.numberPerBatch" step="10" min="10" max="100" />
					</label>
					<br>
					<sub style="display:block;width:500px;margin-top:5px;">If it looks like your website isn't adding images to the ImageComply queue, try lowering this number by increments of 10</sub>
				</div>
				<br>
				<p style="margin:0;" x-show="!plan"><b class="gradient-text">Warning:</b> These may consume a lot of credits</p>

				<div style="display:flex;flex-direction:row;gap:20px;align-items:center;">
					<button class='button--primary' @click.prevent="onGenerateAllImages">
						Generate ALT text for all images
					</button>

					<div x-show="inProgress.alt_text" style="width:30px;height:30px;border:3px solid rgba(28, 28, 28, 0.25);border-radius:50%;border-top:3px solid rgb(28, 28, 28);animation: spin 1s linear infinite;;aspect-ratio:1/1;"></div> <span x-text="progress.alt_text"></span>
				</div>
				Generating ALT text for all images will not generate alt text for images that already have ALT text assigned to them. It will only generate alt text for images with no existing alt text.
				
				<?php
					// 	<div style="display:flex;flex-direction:row;gap:20px;align-items:center;">
					// 		<button class='button--black' style="width:100%;max-width: 338px;	" @click.prevent="onOptimizeImages">
					// 		Optimize all images
					// 	</button> 

					// 		<div x-show="inProgress.optimization" style="width:30px;height:30px;border:3px solid rgba(28, 28, 28, 0.25);border-radius:50%;border-top:3px solid rgb(28, 28, 28);animation: spin 1s linear infinite;aspect-ratio:1/1;"></div>
					// </div> 
					// <br> 
					// <button class='button--primary' style="width: 338px;" @click.prevent="onPerformAllActions">
					// 	Perform all actions
					// </button> 
				?>
			</div>
			
			<hr />
			<div>
				<h2>Need Assistance?</h2>

				Is something not working? Have a question? We're here to help.
				<br /><br /><br />

				<a href='https://www.imagecomply.com/contact' target='_blank' class='button button--black'>Contact Us</a>
			</div>
		</div>
	</template>

</div>