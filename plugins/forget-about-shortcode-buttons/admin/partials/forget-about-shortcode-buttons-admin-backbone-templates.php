<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://codeamp.io
 * @since      1.0.0
 *
 * @package    Forget_About_Shortcode_Buttons
 * @subpackage Forget_About_Shortcode_Buttons/admin/partials
 */
?>
<!-- FASC Tiny MCE Templates. -->
<div id="fasc-popup-edit-button" style="display:none;" data-fasc-title="Edit Button" data-fasc-width="480px" data-fasc-height="600px" data-fasc-submit-label="Update">
     <div class="preview-button-area" style="position:relative;">
			<div class="centered-button">
				
			</div>
			<a href="#" class="fa fa-save fasc-save-btn"></a>
			
		</div>
		<div class="fasc-popup-toolbar">
			
			<div class="fasc-popup-toolbar-button-row">
			
				<div class="fasc-mce-button fasc-mce-button-color-picker" data-fasc-action="popup-colorpicker" data-fasc-action-target="fasc-foreground" data-fasc-button-active="0">
					<button role="presentation" type="button" tabindex="-1">
						<span class="fasc-ico-fg">
							<span class="fg-panel"></span>
							<span class="bg-panel"></span>
						</span>
					</button>
				</div>
				
				<div class="fasc-popup-colorpicker-container" data-fasc-colorpicker-name="fasc-foreground">
					<input type="text" id="fasc-popup-text-color" name="text-color" value="#ffffff" /><br />
					<input type="text" value="#ffffff" class="fasc-popup-color-input" value="#ffffff" />
				</div>
				
				<div class="fasc-mce-button fasc-mce-button-color-picker" data-fasc-action="popup-colorpicker" data-fasc-action-target="fasc-background" data-fasc-button-active="0">
					<button role="presentation" type="button" tabindex="-1">
						<span class="fasc-ico-bg">
							<span class="fg-panel"></span>
							<span class="bg-panel"></span>
						</span>
					</button>
				</div>
				
				<div class="fasc-popup-colorpicker-container" data-fasc-colorpicker-name="fasc-background">
					<input type="text" id="fasc-popup-button-color" name="button-color" value="#33809e" /><br />
					<input type="text" id="" class="fasc-popup-color-input" value="#33809e" />
				</div>
				
				<div class="fasc-mce-button fasc-mce-button-toggle" data-fasc-button-active="0" data-fasc-action='bold'><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-bold"></i></button></div>
				<div class="fasc-mce-button fasc-mce-button-toggle" data-fasc-button-active="0" data-fasc-action='italic'><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-italic"></i></button></div>
				<div class="fasc-mce-button fasc-mce-button-toggle" data-fasc-button-active="0" data-fasc-action='strikethrough'><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-strikethrough"></i></button></div>
				<!--<div class="fasc-mce-button fasc-mce-button-toggle" data-fasc-button-active="0" data-fasc-action='alignleft'><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-alignleft"></i></button></div>
				<div class="fasc-mce-button fasc-mce-button-toggle" data-fasc-button-active="0" data-fasc-action='aligncenter'><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-aligncenter"></i></button></div>
				<div class="fasc-mce-button fasc-mce-button-toggle" data-fasc-button-active="0" data-fasc-action='alignright'><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-alignright"></i></button></div>-->
				
				
				<!--<button role="presentation" type="button" tabindex="-1" style="float:right;"><i class="mce-ico mce-i-save"></i></button>-->
				
				<select name="button-type" id="fasc-popup-button-type">
					<option value="fasc-type-flat" selected="selected">Flat</option>
					<option value="fasc-type-flat fasc-rounded-medium">Flat Rounded</option>
					<option value="fasc-type-glossy">Glossy</option>
					<option value="fasc-type-glossy fasc-rounded-medium">Glossy Rounded</option>
					<option value="fasc-type-popout">Pop out</option>
					<option value="fasc-type-popout fasc-rounded-medium">Pop out Rounded</option>
				</select> 
				
				<select name="button-size" id="fasc-popup-button-size" size="1">
					<option value="fasc-size-xsmall">Extra Small</option>
					<option value="fasc-size-small">Small</option>
					<option value="fasc-size-medium" selected="selected">Medium</option>
					<option value="fasc-size-large">Large</option>
					<option value="fasc-size-xlarge">Extra Large</option>
				</select>
				
			</div>
			

			<!--<div class="fasc-popup-toolbar-button-row">
				
				<select name="button-type" id="fasc-popup-button-type">
					<option value="fasc-type-flat" selected="selected">Flat</option>
					<option value="fasc-type-flat fasc-rounded-medium">Flat Rounded</option>
					<option value="fasc-type-glossy">Glossy</option>
					<option value="fasc-type-glossy fasc-rounded-medium">Glossy Rounded</option>
					<option value="fasc-type-popout">Pop out</option>
					<option value="fasc-type-popout fasc-rounded-medium">Pop out Rounded</option>
				</select> 
				
				<select name="button-size" id="fasc-popup-button-size" size="1">
					<option value="fasc-size-xsmall">Extra Small</option>
					<option value="fasc-size-small">Small</option>
					<option value="fasc-size-medium" selected="selected">Medium</option>
					<option value="fasc-size-large">Large</option>
					<option value="fasc-size-xlarge">Extra Large</option>
				</select>
			</div>-->
		</div>
		
		<!-- <form action="/" method="get" accept-charset="utf-8"> -->
			
			<div class="tab-header">
				<ul>
					<li data-fasc-tab="1" class="active">Properties</li>
					<li data-fasc-tab="2">Icon</li>
					<li data-fasc-tab="3">Templates</li>
					<!--<li  data-fasc-tab="4" class="settings"><a href="#tab-4-content"><div data-code="f111" class="dashicons dashicons-admin-generic active"></div></a></li>-->
				</ul><div class="clear"></div>
			</div>
			
			<div data-fasc-tab="1" class="fasc-tab-content">
								
				<div class="inputrow main">
					<label for="fasc-popup-button-text">Text</label>
					<div class="inputwrap">
						<input type="text" name="button-text" value="" id="fasc-popup-button-text" value="" placeholder="Enter your text&hellip;" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="inputrow main">
					<label for="fasc-popup-button-url">URL</label>
					<div class="inputwrap">
						<input type="text" name="button-url" value="" id="fasc-popup-button-url" /><br />
					</div>
					<div class="inputwrap">
						<label for="fasc-popup-nofollow"><small>Add Nofollow</small> &nbsp;<input type="checkbox" id="fasc-popup-nofollow" name="fasc-popup-nofollow" value="1" /></label>
						<label for="fasc-popup-new-window"><small>Open link in a new tab</small> &nbsp;<input type="checkbox" id="fasc-popup-new-window" name="fasc-popup-new-window" value="1" /></label>
					</div>
					<div class="clear"></div>
				</div>
				
			</div>
			
			<div data-fasc-tab="2" class="fasc-tab-content">
				<div class="inputrow">
					<div class="inputwrap left">
						<select id="fasc-popup-icon-type-select">
							<option value="dashicons-grid">Dashicons</option>
							<option value="fa-web-grid">Web</option>
							<option value="fa-media-grid">Media</option>
							<option value="fa-form-grid">Form</option>
							<option value="fa-currency-grid">Currency</option>
							<option value="fa-editor-grid">Editor</option>
							<option value="fa-directional-grid">Directional</option>
							<option value="fa-brand-grid">Brand</option>
							<option value="fa-medical-grid">Medical</option>
						</select>
						</label>
					</div>
					
					<div class="inputwrap right">

						<label><input type="radio" name="fasc-ico-position" value="none" class="fasc-ico-position" checked="checked" /> None</label>
						<label><input type="radio" name="fasc-ico-position" value="before" class="fasc-ico-position" /> Before</label>
						<label><input type="radio" name="fasc-ico-position" value="after" class="fasc-ico-position" /> After</label>
						<!--<label><input type="radio" name="fasc-ico-position" value="center" /> Center</label>-->
						
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="ico-grid">
					<div class="grid-container" id="dashicons-grid">
						<input type="hidden" value="" id="fasc-ico-val" name="fasc-ico-val" />
						<?php require_once("dashicons-grid.php"); ?>
						<div class="clear"></div>
					</div>
					<div class="grid-container fontawesome" id="fa-media-grid">
						<input type="hidden" value="" id="fasc-ico-val" name="fasc-ico-val" />
						<?php require_once("fa-media-grid.php"); ?>
						<div class="clear"></div>
					</div>
					<div class="grid-container fontawesome" id="fa-form-grid">
						<input type="hidden" value="" id="fasc-ico-val" name="fasc-ico-val" />
						<?php require_once("fa-form-grid.php"); ?>
						<div class="clear"></div>
					</div>
					<div class="grid-container fontawesome" id="fa-web-grid">
						<input type="hidden" value="" id="fasc-ico-val" name="fasc-ico-val" />
						<?php require_once("fa-web-grid.php"); ?>
						<div class="clear"></div>
					</div>
					<div class="grid-container fontawesome" id="fa-currency-grid">
						<input type="hidden" value="" id="fasc-ico-val" name="fasc-ico-val" />
						<?php require_once("fa-currency-grid.php"); ?>
						<div class="clear"></div>
					</div>
					<div class="grid-container fontawesome" id="fa-editor-grid">
						<input type="hidden" value="" id="fasc-ico-val" name="fasc-ico-val" />
						<?php require_once("fa-editor-grid.php"); ?>
						<div class="clear"></div>
					</div>
					<div class="grid-container fontawesome" id="fa-directional-grid">
						<input type="hidden" value="" id="fasc-ico-val" name="fasc-ico-val" />
						<?php require_once("fa-directional-grid.php"); ?>
						<div class="clear"></div>
					</div>
					<div class="grid-container fontawesome" id="fa-brand-grid">
						<input type="hidden" value="" id="fasc-ico-val" name="fasc-ico-val" />
						<?php require_once("fa-brand-grid.php"); ?>
						<div class="clear"></div>
					</div>
					<div class="grid-container fontawesome" id="fa-medical-grid">
						<input type="hidden" value="" id="fasc-ico-val" name="fasc-ico-val" />
						<?php require_once("fa-medical-grid.php"); ?>
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
				</div>
			</div>
			<div data-fasc-tab="3" class="fasc-tab-content saved-buttons-tab">
				
				<div class="container-grid">
					
					<ul>
					</ul>				
					
					<div class="clear"></div>
				</div>
			</div>
			<div data-fasc-tab="4" class="fasc-tab-content">
				<strong>Button Attributes</strong>
				<div class="container-grid">
					
					
					
					<div class="clear"></div>
				</div>
			</div>
			
			
		<!-- </form> -->
</div>

<div class="fasc-thickbox" id="fasc-popup-template" style="display:none;">
	<div class="fasc-thickbox-title fasc-thickbox-header">
		<div class="fasc-thickbox-title-inner">
			
		</div>
		<div class="fasc-ajax-window-title"></div>
	</div>
	
	<div class="fasc-close-ajax-window">
		
		<div class="fasc-close-icon"><a href="#"></a></div>
	</div>
	
	<div class="fasc-ajax-content fasc-thickbox-content">
		
		
    </div>
	
	<div class="fasc-thickbox-frame-toolbar fasc-thickbox-footer">
		<div class="fasc-thickbox-toolbar">
			<p>
				<!--<a href="#" class="button-secondary fasc-meta-select-none">Select None</a> 
				<a href="#" class="button-secondary fasc-meta-select-all">Select All</a>  -->
				<a href="#" class="button-primary fasc-thickbox-action-right">Add Options</a>
				<!--<label class="replace-meta-options-label">
					Replace current options? &nbsp;<input type="checkbox" class="replace-options-checkbox" />
				</label> -->
			</p>
		</div>
	</div>
</div>