<?php
/**
 * @copyright	Copyright (C) since 2018. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.ceikay.com
 */
Namespace Accordeonmenuck;

defined('CK_LOADED') or die;
wp_enqueue_media();

$modalclass = ($this->input->get('modal', '', 'string') === '1') ? 'ckmodal' : '';
//require_once ACCORDEONMENUCK_PATH . '/helpers/menuhelper.php';
?>
<link rel="stylesheet" href="<?php echo ACCORDEONMENUCK_MEDIA_URL ?>/assets/font-awesome.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo ACCORDEONMENUCK_MEDIA_URL ?>/assets/ckframework.css" type="text/css" />
<link rel="stylesheet" href="<?php echo ACCORDEONMENUCK_MEDIA_URL ?>/assets/jscolor/jscolor.css" type="text/css" />
<link rel="stylesheet" href="<?php echo ACCORDEONMENUCK_MEDIA_URL ?>/assets/admin.css" type="text/css" />

<script type="text/javascript" src="<?php echo ACCORDEONMENUCK_MEDIA_URL ?>/assets/jscolor/jscolor.js"></script>
<script type="text/javascript" src="<?php echo ACCORDEONMENUCK_MEDIA_URL ?>/assets/admin.js"></script>

<style>
#stylescontainerleft, #stylescontainerright {
	float :left;
	width: 50%;
	padding: 10px;
	box-sizing: border-box;
}

#previewarea {
	max-height: calc(100% - 50px);
	/*overflow: auto;*/
}

.ckinterface input.color {
	width: 90px;
}

</style>

<div class="ckadminwrap <?php echo $modalclass; ?>" id="ckedition">
	<input type="hidden" id="id" name="id" value="<?php echo (int)$this->item->id; ?>" />
	<input type="hidden" id="layoutcss" name="layoutcss" value="" />
	<input type="hidden" id="params" name="params" value="<?php echo htmlspecialchars($this->item->params); ?>" />
	<input type="hidden" id="returnFunc" name="returnFunc" value="<?php echo htmlspecialchars($this->input->get('returnFunc', '', 'cmd')); ?>" />
	<div class="cktoolbar">
		<a href="javascript:void(0)" class="" onclick="ckSaveEdition()" id="cktoolbar_save"><span class="fa fa-check"></span> <?php echo __('Save', 'accordeon-menu-ck') ?></a>
		<a href="javascript:void(0)" class="" onclick="ckPreviewStylesparams()" id="cktoolbar_preview"><span class="fa fa-eye"></span> <?php echo __('Preview', 'accordeon-menu-ck') ?></a>
		<a href="javascript:void(0)" class="" onclick="ckClearFields()"><span class="fa fa-times-circle"></span> <?php echo __('Reset settings', 'accordeon-menu-ck') ?></a>
		<?php /* <a href="javascript:void(0)" class="btn-action" onclick="ckImportParams()"><span class="dashicons dashicons-upload"></span><?php echo __('Import') ?></a>
		<a href="javascript:void(0)" class="btn-action" onclick="ckExportParams()"><span class="dashicons dashicons-download"></span><?php echo __('Export') ?></a> */ ?>
	</div>
	<div id="ckeditiongfont"></div>
<div id="stylescontainer" style="min-width: 850px;" class="animateck">
	<div id="stylescontainerleft" style="width:800px;" class="ckinterface">
		<label for="name" style="display: inline-block;"><?php echo CKText::_('Name'); ?></label>
		<input type="text" id="name" name="name" value="<?php echo $this->item->name; ?>" />
		<div id="styleswizard_options" class="styleswizard">
		<div class="menustylescustom" data-prefix="menustyles" data-rule="" style="display:none;"></div>
		<div class="menustylescustom" data-prefix="level1itemgroup" data-rule="li.level1" style="display:none;"></div>
		<div class="menustylescustom" data-prefix="level1itemnormalstyles" data-rule="li.level1 > span" style="display:none;"></div>
		<div class="menustylescustom" data-prefix="level1itemnormaltextstyles" data-rule="li.level1 > span a" style="display:none;"></div>
		<div class="menustylescustom" data-prefix="level1itemnormaltextdescstyles" data-rule="li.level1 > span .accordeonck_desc" style="display:none;"></div>
		<div class="menustylescustom" data-prefix="level1itemhoverstyles" data-rule="li.level1:hover > span" style="display:none;"></div>
		<div class="menustylescustom" data-prefix="level1itemhovertextstyles" data-rule="li.level1:hover > span a" style="display:none;"></div>
		<div class="menustylescustom" data-prefix="level1itemhovertextdescstyles" data-rule="li.level1:hover > span .accordeonck_desc" style="display:none;"></div>

		<div class="menustylescustom" data-prefix="level2menustyles" data-rule="li.level1 > ul" style="display:none;"></div>
		<div class="menustylescustom" data-prefix="level2itemgroup" data-rule="li.level2" style="display:none;"></div>
		<div class="menustylescustom" data-prefix="level2itemnormalstyles" data-rule="li.level2 > span" style="display:none;"></div>
		<div class="menustylescustom" data-prefix="level2itemnormaltextstyles" data-rule="li.level2 > span a" style="display:none;"></div>
		<div class="menustylescustom" data-prefix="level2itemnormaltextdescstyles" data-rule="li.level2 > span .accordeonck_desc" style="display:none;"></div>
		<div class="menustylescustom" data-prefix="level2itemhoverstyles" data-rule="li.level2:hover > span" style="display:none;"></div>
		<div class="menustylescustom" data-prefix="level2itemhovertextstyles" data-rule="li.level2:hover > span a" style="display:none;"></div>
		<div class="menustylescustom" data-prefix="level2itemhovertextdescstyles" data-rule="li.level2:hover > span .accordeonck_desc" style="display:none;"></div>

		<div class="menustylescustom" data-prefix="level3menustyles" data-rule="li.level2 ul[class^=|qq|content|qq|]" style="display:none;"></div>
		<div class="menustylescustom" data-prefix="level3itemgroup" data-rule="li.level2 li.accordeonck" style="display:none;"></div>
		<div class="menustylescustom" data-prefix="level3itemnormalstyles" data-rule="li.level2 li.accordeonck > span" style="display:none;"></div>
		<div class="menustylescustom" data-prefix="level3itemnormaltextstyles" data-rule="li.level2 li.accordeonck > span a" style="display:none;"></div>
		<div class="menustylescustom" data-prefix="level3itemnormaltextdescstyles" data-rule="li.level2 li.accordeonck > span .accordeonck_desc" style="display:none;"></div>
		<div class="menustylescustom" data-prefix="level3itemhoverstyles" data-rule="li.level2 li.accordeonck:hover > span" style="display:none;"></div>
		<div class="menustylescustom" data-prefix="level3itemhovertextstyles" data-rule="li.level2 li.accordeonck:hover > span a" style="display:none;"></div>
		<div class="menustylescustom" data-prefix="level3itemhovertextdescstyles" data-rule="li.level2 li.accordeonck:hover > span .accordeonck_desc" style="display:none;"></div>

		<div class="cktablink current" tab="tab_mainmenu" data-group="main"><?php echo __('Main menu', 'accordeon-menu-ck'); ?></div>
		<div class="cktablink" tab="tab_submenu" data-group="main"><?php echo __('Submenu', 'accordeon-menu-ck'); ?> - <span><?php echo __('Level', 'accordeon-menu-ck'); ?> 2</span></div>
		<div class="cktablink" tab="tab_subsubmenu" data-group="main"><?php echo __('Submenu', 'accordeon-menu-ck'); ?> - <span><?php echo __('Level', 'accordeon-menu-ck'); ?> 3+</span></div>
		<div class="cktablink" tab="tab_customcss" data-group="main"><?php echo __('Custom CSS', 'accordeon-menu-ck'); ?></div>
		<div class="cktablink" tab="tab_presets" data-group="main"><?php echo __('Presets', 'accordeon-menu-ck'); ?></div>
		<div class="clr"></div>
		<div class="cktab current hascol" id="tab_mainmenu" data-group="main">
		<div class="ckpopupstyleswizard_col_left">
			<div class="cktablink2 current" tab="tab_menustyles" data-group="mainmenu"><?php echo __('Menu', 'accordeon-menu-ck'); ?></div>
			<div class="cktablink2" tab="tab_level1itemgroup" data-group="mainmenu"><?php echo __('Group of links', 'accordeon-menu-ck'); ?></div>
			<div class="cktablink2" tab="tab_level1itemnormalstyles" data-group="mainmenu"><?php echo __('Menu link', 'accordeon-menu-ck'); ?></div>
			<div class="cktablink2" tab="tab_level1itemhoverstyles" data-group="mainmenu"><?php echo __('Menu link hover', 'accordeon-menu-ck'); ?></div>
			<div class="cktablink2" tab="tab_level1itemparentarrow" data-group="mainmenu"><?php echo __('Parent icon', 'accordeon-menu-ck'); ?></div>
		</div>
		<div class="ckpopupstyleswizard_col_right">
			<div class="cktab2 current" id="tab_menustyles" data-group="mainmenu">
				<div class="ckheading"><?php echo __('Text', 'accordeon-menu-ck'); ?></div>
				<div class="ckrow">
					<label for="menustylesfontfamily"><?php echo __('Font style', 'accordeon-menu-ck'); ?></label>
					<img class="ckicon" src="<?php echo $this->interface->imagespath ?>/font_add.png" />
					<input type="text" id="menustylesfontfamily" name="menustylesfontfamily" class="menustyles hasTip gfonturl" onchange="ckCleanGfontName(this);" title="<?php echo __('Enter the name of a google font to use, for example : Open+Sans+Condensed:300', 'accordeon-menu-ck'); ?>" style="max-width:none;width:250px;" />
					<input type="hidden" id="menustylestextisgfont" name="menustylestextisgfont" class="isgfont menustyles" />
				</div>
				<div class="ckrow">
					<label for="">&nbsp;</label><img class="ckicon" src="<?php echo $this->interface->imagespath ?>/font.png" />
					<div class="ckbutton-group">
						<input class="menustyles" type="radio" value="left" id="menustylestextalignleft" name="menustylestextalign" />
						<label class="ckbutton first" for="menustylestextalignleft"><img class="ckicon" src="<?php echo $this->interface->imagespath ?>/text_align_left.png" />
						</label><input class="menustyles" type="radio" value="center" id="menustylestextaligncenter" name="menustylestextalign" />
						<label class="ckbutton"  for="menustylestextaligncenter"><img class="ckicon" src="<?php echo $this->interface->imagespath ?>/text_align_center.png" />
						</label><input class="menustyles" type="radio" value="right" id="menustylestextalignright" name="menustylestextalign" />
						<label class="ckbutton last"  for="menustylestextalignright"><img class="ckicon" src="<?php echo $this->interface->imagespath ?>/text_align_right.png" /></label>
					</div>
					<div class="ckbutton-group">
						<input class="level1itemnormaltextstyles" type="radio" value="lowercase" id="level1itemnormaltextstylestexttransformlowercase" name="level1itemnormaltextstylestexttransform" />
						<label class="ckbutton first hasTip" title="<?php echo __('Lowercase', 'accordeon-menu-ck'); ?>" for="level1itemnormaltextstylestexttransformlowercase"><img class="ckicon" src="<?php echo $this->interface->imagespath ?>/text_lowercase.png" />
						</label><input class="level1itemnormaltextstyles" type="radio" value="uppercase" id="level1itemnormaltextstylestexttransformuppercase" name="level1itemnormaltextstylestexttransform" />
						<label class="ckbutton hasTip" title="<?php echo __('Uppercase', 'accordeon-menu-ck'); ?>" for="level1itemnormaltextstylestexttransformuppercase"><img class="ckicon" src="<?php echo $this->interface->imagespath ?>/text_uppercase.png" />
						</label><input class="level1itemnormaltextstyles" type="radio" value="capitalize" id="level1itemnormaltextstylestexttransformcapitalize" name="level1itemnormaltextstylestexttransform" />
						<label class="ckbutton hasTip" title="<?php echo __('Capitalize', 'accordeon-menu-ck'); ?>" for="level1itemnormaltextstylestexttransformcapitalize"><img class="ckicon" src="<?php echo $this->interface->imagespath ?>/text_capitalize.png" />
						</label><input class="level1itemnormaltextstyles" type="radio" value="default" id="level1itemnormaltextstylestexttransformdefault" name="level1itemnormaltextstylestexttransform" />
						<label class="ckbutton hasTip" title="<?php echo __('Default', 'accordeon-menu-ck'); ?>" for="level1itemnormaltextstylestexttransformdefault"><img class="ckicon" src="<?php echo $this->interface->imagespath ?>/text_default.png" />
						</label>
					</div>
				</div>
				<div class="ckrow">
					<label for="level1itemnormaltextstylesfontweightbold"></label>
					<img class="ckicon" src="<?php echo $this->interface->imagespath ?>/text_bold.png" />
					<div class="ckbutton-group">
						<input class="level1itemnormaltextstyles" type="radio" value="bold" id="level1itemnormaltextstylesfontweightbold" name="level1itemnormaltextstylesfontweight" />
						<label class="ckbutton first hasTip" title="" for="level1itemnormaltextstylesfontweightbold" style="width:auto;"><?php echo __('Bold', 'accordeon-menu-ck'); ?>
						</label><input class="level1itemnormaltextstyles" type="radio" value="normal" id="level1itemnormaltextstylesfontweightnormal" name="level1itemnormaltextstylesfontweight" />
						<label class="ckbutton hasTip" title="" for="level1itemnormaltextstylesfontweightnormal" style="width:auto;"><?php echo __('Normal', 'accordeon-menu-ck'); ?>
						</label>
					</div>
				</div>
				<div class="ckrow">
					<label><?php echo __('Title style', 'accordeon-menu-ck'); ?></label>
					<img class="ckicon" src="<?php echo $this->interface->imagespath ?>/style.png" />
					<input type="text" id="level1itemnormaltextstylesfontsize" name="level1itemnormaltextstylesfontsize" class="level1itemnormaltextstyles hasTip" style="width:30px;" title="<?php echo __('Font size (set the value in px, em, or %, the default unit is px)', 'accordeon-menu-ck'); ?>" />
					<img class="ckicon" src="<?php echo $this->interface->imagespath ?>/color.png" />
					<span><?php echo __('Normal', 'accordeon-menu-ck'); ?></span>
					<input type="text" id="level1itemnormaltextstylescolor" name="level1itemnormaltextstylescolor" class="level1itemnormaltextstyles hasTip <?php echo $this->interface->colorpicker_class; ?>" title="<?php echo __('Link color. Use the colorpicker or write your own value', 'accordeon-menu-ck'); ?>" />
					<img class="ckicon" src="<?php echo $this->interface->imagespath ?>/color.png" />
					<span><?php echo __('Hover', 'accordeon-menu-ck'); ?></span>
					<input type="text" id="level1itemhovertextstylescolor" name="level1itemhovertextstylescolor" class="level1itemhovertextstyles hasTip <?php echo $this->interface->colorpicker_class; ?>" title="<?php echo __('Link color. Use the colorpicker or write your own value', 'accordeon-menu-ck'); ?>" />
				</div>
				<div class="ckrow">
					<label><?php echo __('Description style', 'accordeon-menu-ck'); ?></label>
					<img class="ckicon" src="<?php echo $this->interface->imagespath ?>/style.png" />
					<input type="text" id="level1itemnormaltextdescstylesfontsize" name="level1itemnormaltextdescstylesfontsize" class="level1itemnormaltextdescstyles hasTip" style="width:30px;" title="<?php echo __('Font size (set the value in px, em, or %, the default unit is px)', 'accordeon-menu-ck'); ?>" />
					<img class="ckicon" src="<?php echo $this->interface->imagespath ?>/color.png" />
					<span><?php echo __('Normal', 'accordeon-menu-ck'); ?></span>
					<input type="text" id="level1itemnormaltextdescstylescolor" name="level1itemnormaltextdescstylescolor" class="level1itemnormaltextdescstyles hasTip <?php echo $this->interface->colorpicker_class; ?>" title="<?php echo __('Link color. Use the colorpicker or write your own value', 'accordeon-menu-ck'); ?>" />
					<img class="ckicon" src="<?php echo $this->interface->imagespath ?>/color.png" />
					<span><?php echo __('Hover', 'accordeon-menu-ck'); ?></span>
					<input type="text" id="level1itemhovertextdescstylescolor" name="level1itemhovertextdescstylescolor" class="level1itemhovertextdescstyles hasTip <?php echo $this->interface->colorpicker_class; ?>" title="<?php echo __('Link color. Use the colorpicker or write your own value', 'accordeon-menu-ck'); ?>" />
				</div>
				<div class="ckheading"><?php echo __('Appearance', 'accordeon-menu-ck'); ?></div>
				<?php
				$this->interface->createBackgroundColor('menustyles');
				$this->interface->createBackgroundImage('menustyles');
				$this->interface->createBorders('menustyles');
				$this->interface->createRoundedCorners('menustyles');
				$this->interface->createShadow('menustyles');
				?>
				<div class="ckheading"><?php echo __('Dimensions', 'accordeon-menu-ck'); ?></div>
				<?php
				$this->interface->createMargins('menustyles');
				?>
			</div>
			<div class="cktab2" id="tab_level1itemgroup" data-group="mainmenu">
				<?php echo Helper::getProMessage(); ?>
			</div>
			<div class="cktab2" id="tab_level1itemnormalstyles" data-group="mainmenu">
				<?php
				$this->interface->createPadding('level1itemnormaltextstyles');
				echo Helper::getProMessage();
				?>
			</div>
			<div class="cktab2" id="tab_level1itemhoverstyles" data-group="mainmenu">
				<?php 
					echo Helper::getProMessage();
				?>
			</div>
			<div class="cktab2" id="tab_level1itemparentarrow" data-group="mainmenu">
				<div class="ckrow">
					<label for="menustylesimageplus"><?php echo __('Plus image', 'accordeon-menu-ck'); ?></label>
					<img class="ckicon" src="<?php echo $this->interface->imagespath ?>/image.png" />
					<div class="ckbutton-group">
						<input type="text" id="menustylesimageplus" name="menustylesimageplus" class="hasTip menustyles" title="<?php echo __('Select the image to put as +', 'accordeon-menu-ck'); ?>" onchange="ckCheckGradientImageConflict(this, 'menustylesbgcolor2')" style="max-width: none; width: 150px;"/>
						<a class="modal ckbutton" onclick="ckOpenMediaManager(this, '<?php echo get_site_url() ?>');" ><?php echo __('Select', 'accordeon-menu-ck'); ?></a>
						<a class="ckbutton" href="javascript:void(0)" onclick="jQuery(this).parent().find('input').val('').trigger('change');"><?php echo __('Clear', 'accordeon-menu-ck'); ?></a>
					</div>
				</div>
				<div class="ckrow">
					<label for="menustylesimageminus"><?php echo __('Minus image', 'accordeon-menu-ck'); ?></label>
					<img class="ckicon" src="<?php echo $this->interface->imagespath ?>/image.png" />
					<div class="ckbutton-group">
						<input type="text" id="menustylesimageminus" name="menustylesimageminus" class="hasTip menustyles" title="<?php echo __('Select the image to put as -', 'accordeon-menu-ck'); ?>" onchange="ckCheckGradientImageConflict(this, 'menustylesbgcolor2')" style="max-width: none; width: 150px;"/>
						<a class="modal ckbutton" onclick="ckOpenMediaManager(this, '<?php echo get_site_url() ?>');" ><?php echo __('Select', 'accordeon-menu-ck'); ?></a>
						<a class="ckbutton" href="javascript:void(0)" onclick="jQuery(this).parent().find('input').val('').trigger('change');"><?php echo __('Clear', 'accordeon-menu-ck'); ?></a>
					</div>
				</div>
				<div class="ckrow">
					<label for="menustylesparentarrowwidth"><?php echo __('Image width', 'accordeon-menu-ck'); ?></label>
					<img class="ckicon" src="<?php echo $this->interface->imagespath ?>/width.png" />
					<input type="text" id="menustylesparentarrowwidth" name="menustylesparentarrowwidth" class="menustyles hasTip" style="width:30px;" title="<?php echo __('Width of the element. Set the value in px.', 'accordeon-menu-ck'); ?>" />
				</div>
				<div class="ckrow">
					<label for="menustylesparentarrowposition"><?php echo __('Image position', 'accordeon-menu-ck'); ?></label>
					<img class="ckicon" src="<?php echo $this->interface->imagespath ?>/text_padding_left.png" />
					<div class="ckbutton-group">
						<input class="menustyles" type="radio" value="left" id="menustylesparentarrowpositionleft" name="menustylesparentarrowposition" />
						<label class="ckbutton first hasTip" title="" for="menustylesparentarrowpositionleft" style="width:auto;"><?php echo __('Left', 'accordeon-menu-ck'); ?>
						</label><input class="menustyles" type="radio" value="right" id="menustylesparentarrowpositionright" name="menustylesparentarrowposition" />
						<label class="ckbutton hasTip" title="" for="menustylesparentarrowpositionright" style="width:auto;"><?php echo __('Right', 'accordeon-menu-ck'); ?>
						</label>
					</div>
				</div>
			</div>
		</div>
		<div style="clear:both;"></div>
		</div>
		<div class="cktab hascol" id="tab_submenu" data-group="main">
			<div class="ckpopupstyleswizard_col_left">
				<div class="cktablink2 current" tab="tab_level2menustyles" data-group="submenu"><?php echo __('Submenu', 'accordeon-menu-ck'); ?></div>
				<div class="cktablink2" tab="tab_level2itemgroup" data-group="submenu"><?php echo __('Group of links', 'accordeon-menu-ck'); ?></div>
				<div class="cktablink2" tab="tab_level2itemnormalstyles" data-group="submenu"><?php echo __('Submenu link', 'accordeon-menu-ck'); ?></div>
				<div class="cktablink2" tab="tab_level2itemhoverstyles" data-group="submenu"><?php echo __('Submenu link hover', 'accordeon-menu-ck'); ?></div>
				<div class="cktablink2" tab="tab_level2itemparentarrow" data-group="submenu"><?php echo __('Parent icon', 'accordeon-menu-ck'); ?></div>
				
			</div>
			<div class="ckpopupstyleswizard_col_right">
				<div class="cktab2 current" id="tab_level2menustyles" data-group="submenu">
					<div class="ckheading"><?php echo __('Text', 'accordeon-menu-ck'); ?></div>
					<div class="ckrow">
						<label for="level2menustylesfontfamily"><?php echo __('Font style', 'accordeon-menu-ck'); ?></label>
						<img class="ckicon" src="<?php echo $this->interface->imagespath ?>/font_add.png" />
						<input type="text" id="level2menustylesfontfamily" name="level2menustylesfontfamily" class="level2menustyles hasTip gfonturl" onchange="ckCleanGfontName(this);" title="<?php echo __('Enter the name of a google font to use, for example : Open+Sans+Condensed:300', 'accordeon-menu-ck'); ?>" style="max-width:none;width:250px;" />
						<input type="hidden" id="level2menustylestextisgfont" name="level2menustylestextisgfont" class="isgfont level2menustyles" />
					</div>
					<div class="ckrow">
						<label for="">&nbsp;</label><img class="ckicon" src="<?php echo $this->interface->imagespath ?>/font.png" />
						<div class="ckbutton-group">
							<input class="level2menustyles" type="radio" value="left" id="level2menustylestextalignleft" name="level2menustylestextalign" />
							<label class="ckbutton first" for="level2menustylestextalignleft"><img class="ckicon" src="<?php echo $this->interface->imagespath ?>/text_align_left.png" />
							</label><input class="level2menustyles" type="radio" value="center" id="level2menustylestextaligncenter" name="level2menustylestextalign" />
							<label class="ckbutton"  for="level2menustylestextaligncenter"><img class="ckicon" src="<?php echo $this->interface->imagespath ?>/text_align_center.png" />
							</label><input class="level2menustyles" type="radio" value="right" id="level2menustylestextalignright" name="level2menustylestextalign" />
							<label class="ckbutton last"  for="level2menustylestextalignright"><img class="ckicon" src="<?php echo $this->interface->imagespath ?>/text_align_right.png" /></label>
						</div>
						<div class="ckbutton-group">
							<input class="level2itemnormaltextstyles" type="radio" value="lowercase" id="level2itemnormaltextstylestexttransformlowercase" name="level2itemnormaltextstylestexttransform" />
							<label class="ckbutton first hasTip" title="<?php echo __('Lowercase', 'accordeon-menu-ck'); ?>" for="level2itemnormaltextstylestexttransformlowercase"><img class="ckicon" src="<?php echo $this->interface->imagespath ?>/text_lowercase.png" />
							</label><input class="level2itemnormaltextstyles" type="radio" value="uppercase" id="level2itemnormaltextstylestexttransformuppercase" name="level2itemnormaltextstylestexttransform" />
							<label class="ckbutton hasTip" title="<?php echo __('Uppercase', 'accordeon-menu-ck'); ?>" for="level2itemnormaltextstylestexttransformuppercase"><img class="ckicon" src="<?php echo $this->interface->imagespath ?>/text_uppercase.png" />
							</label><input class="level2itemnormaltextstyles" type="radio" value="capitalize" id="level2itemnormaltextstylestexttransformcapitalize" name="level2itemnormaltextstylestexttransform" />
							<label class="ckbutton hasTip" title="<?php echo __('Capitalize', 'accordeon-menu-ck'); ?>" for="level2itemnormaltextstylestexttransformcapitalize"><img class="ckicon" src="<?php echo $this->interface->imagespath ?>/text_capitalize.png" />
							</label><input class="level2itemnormaltextstyles" type="radio" value="default" id="level2itemnormaltextstylestexttransformdefault" name="level2itemnormaltextstylestexttransform" />
							<label class="ckbutton hasTip" title="<?php echo __('Default', 'accordeon-menu-ck'); ?>" for="level2itemnormaltextstylestexttransformdefault"><img class="ckicon" src="<?php echo $this->interface->imagespath ?>/text_default.png" />
							</label>
						</div>
					</div>
					<div class="ckrow">
						<label for="level2itemnormaltextstylesfontweightbold"></label>
						<img class="ckicon" src="<?php echo $this->interface->imagespath ?>/text_bold.png" />
						<div class="ckbutton-group">
							<input class="level2itemnormaltextstyles" type="radio" value="bold" id="level2itemnormaltextstylesfontweightbold" name="level2itemnormaltextstylesfontweight" />
							<label class="ckbutton first hasTip" title="" for="level2itemnormaltextstylesfontweightbold" style="width:auto;"><?php echo __('Bold', 'accordeon-menu-ck'); ?>
							</label><input class="level2itemnormaltextstyles" type="radio" value="normal" id="level2itemnormaltextstylesfontweightnormal" name="level2itemnormaltextstylesfontweight" />
							<label class="ckbutton hasTip" title="" for="level2itemnormaltextstylesfontweightnormal" style="width:auto;"><?php echo __('Normal', 'accordeon-menu-ck'); ?>
							</label>
						</div>
					</div>
					<div class="ckrow">
						<label><?php echo __('Title style', 'accordeon-menu-ck'); ?></label>
						<img class="ckicon" src="<?php echo $this->interface->imagespath ?>/style.png" />
						<input type="text" id="level2itemnormaltextstylesfontsize" name="level2itemnormaltextstylesfontsize" class="level2itemnormaltextstyles hasTip" style="width:30px;" title="<?php echo __('Font size (set the value in px, em, or %, the default unit is px)', 'accordeon-menu-ck'); ?>" />
						<img class="ckicon" src="<?php echo $this->interface->imagespath ?>/color.png" />
						<span><?php echo __('Normal', 'accordeon-menu-ck'); ?></span>
						<input type="text" id="level2itemnormaltextstylescolor" name="level2itemnormaltextstylescolor" class="level2itemnormaltextstyles hasTip <?php echo $this->interface->colorpicker_class; ?>" title="<?php echo __('Link color. Use the colorpicker or write your own value', 'accordeon-menu-ck'); ?>" />
						<img class="ckicon" src="<?php echo $this->interface->imagespath ?>/color.png" />
						<span><?php echo __('Hover', 'accordeon-menu-ck'); ?></span>
						<input type="text" id="level2itemhovertextstylescolor" name="level2itemhovertextstylescolor" class="level2itemhovertextstyles hasTip <?php echo $this->interface->colorpicker_class; ?>" title="<?php echo __('Link color. Use the colorpicker or write your own value', 'accordeon-menu-ck'); ?>" />
					</div>
					<div class="ckrow">
						<label><?php echo __('Description style', 'accordeon-menu-ck'); ?></label>
						<img class="ckicon" src="<?php echo $this->interface->imagespath ?>/style.png" />
						<input type="text" id="level2itemnormaltextdescstylesfontsize" name="level2itemnormaltextdescstylesfontsize" class="level2itemnormaltextdescstyles hasTip" style="width:30px;" title="<?php echo __('Font size (set the value in px, em, or %, the default unit is px)', 'accordeon-menu-ck'); ?>" />
						<img class="ckicon" src="<?php echo $this->interface->imagespath ?>/color.png" />
						<span><?php echo __('Normal', 'accordeon-menu-ck'); ?></span>
						<input type="text" id="level2itemnormaltextdescstylescolor" name="level2itemnormaltextdescstylescolor" class="level2itemnormaltextdescstyles hasTip <?php echo $this->interface->colorpicker_class; ?>" title="<?php echo __('Link color. Use the colorpicker or write your own value', 'accordeon-menu-ck'); ?>" />
						<img class="ckicon" src="<?php echo $this->interface->imagespath ?>/color.png" />
						<span><?php echo __('Hover', 'accordeon-menu-ck'); ?></span>
						<input type="text" id="level2itemhovertextdescstylescolor" name="level2itemhovertextdescstylescolor" class="level2itemhovertextdescstyles hasTip <?php echo $this->interface->colorpicker_class; ?>" title="<?php echo __('Link color. Use the colorpicker or write your own value', 'accordeon-menu-ck'); ?>" />
					</div>
					<div class="ckheading"><?php echo __('Appearance', 'accordeon-menu-ck'); ?></div>
					<?php
					$this->interface->createBackgroundColor('level2menustyles');
					$this->interface->createBackgroundImage('level2menustyles');
					$this->interface->createBorders('level2menustyles');
					$this->interface->createRoundedCorners('level2menustyles');
					$this->interface->createShadow('level2menustyles');
					?>
					<div class="ckheading"><?php echo __('Dimensions', 'accordeon-menu-ck'); ?></div>
					<?php
					$this->interface->createMargins('level2menustyles');
					?>
				</div>
				<div class="cktab2" id="tab_level2itemgroup" data-group="submenu">
					<?php 
						echo Helper::getProMessage();
					?>
				</div>
				<div class="cktab2" id="tab_level2itemnormalstyles" data-group="submenu">
					<?php
						$this->interface->createPadding('level2itemnormaltextstyles');
						echo Helper::getProMessage();
					?>
				</div>
				<div class="cktab2" id="tab_level2itemhoverstyles" data-group="submenu">
					<?php
						echo Helper::getProMessage();
					?>
				</div>
				<div class="cktab2" id="tab_level2itemparentarrow" data-group="submenu">
					<?php
						echo Helper::getProMessage();
					?>
				</div>
			</div>
			<div style="clear:both;"></div>
		</div>
		<div class="cktab hascol" id="tab_subsubmenu" data-group="main">
			<div class="ckpopupstyleswizard_col_left">
				<div class="cktablink2 current" tab="tab_level3menustyles" data-group="subsubmenu"><?php echo __('Submenu', 'accordeon-menu-ck'); ?></div>
				<div class="cktablink2" tab="tab_level3itemgroup" data-group="subsubmenu"><?php echo __('Group of links', 'accordeon-menu-ck'); ?></div>
				<div class="cktablink2" tab="tab_level3itemnormalstyles" data-group="subsubmenu"><?php echo __('Submenu link', 'accordeon-menu-ck'); ?></div>
				<div class="cktablink2" tab="tab_level3itemhoverstyles" data-group="subsubmenu"><?php echo __('Submenu link hover', 'accordeon-menu-ck'); ?></div>
				<div class="cktablink2" tab="tab_level3itemparentarrow" data-group="subsubmenu"><?php echo __('Parent icon', 'accordeon-menu-ck'); ?></div>
				
			</div>
			<div class="ckpopupstyleswizard_col_right">
				<div class="cktab2 current" id="tab_level3menustyles" data-group="subsubmenu">
					<div class="ckheading"><?php echo __('Text', 'accordeon-menu-ck'); ?></div>
					<div class="ckrow">
						<label for="level3menustylesfontfamily"><?php echo __('Font style', 'accordeon-menu-ck'); ?></label>
						<img class="ckicon" src="<?php echo $this->interface->imagespath ?>/font_add.png" />
						<input type="text" id="level3menustylesfontfamily" name="level3menustylesfontfamily" class="level3menustyles hasTip gfonturl" onchange="ckCleanGfontName(this);" title="<?php echo __('Enter the name of a google font to use, for example : Open+Sans+Condensed:300', 'accordeon-menu-ck'); ?>" style="max-width:none;width:250px;" />
						<input type="hidden" id="level3menustylestextisgfont" name="level3menustylestextisgfont" class="isgfont level3menustyles" />
					</div>
					<div class="ckrow">
						<label for="">&nbsp;</label><img class="ckicon" src="<?php echo $this->interface->imagespath ?>/font.png" />
						<div class="ckbutton-group">
							<input class="level3menustyles" type="radio" value="left" id="level3menustylestextalignleft" name="level3menustylestextalign" />
							<label class="ckbutton first" for="level3menustylestextalignleft"><img class="ckicon" src="<?php echo $this->interface->imagespath ?>/text_align_left.png" />
							</label><input class="level3menustyles" type="radio" value="center" id="level3menustylestextaligncenter" name="level3menustylestextalign" />
							<label class="ckbutton"  for="level3menustylestextaligncenter"><img class="ckicon" src="<?php echo $this->interface->imagespath ?>/text_align_center.png" />
							</label><input class="level3menustyles" type="radio" value="right" id="level3menustylestextalignright" name="level3menustylestextalign" />
							<label class="ckbutton last"  for="level3menustylestextalignright"><img class="ckicon" src="<?php echo $this->interface->imagespath ?>/text_align_right.png" /></label>
						</div>
						<div class="ckbutton-group">
							<input class="level3itemnormaltextstyles" type="radio" value="lowercase" id="level3itemnormaltextstylestexttransformlowercase" name="level3itemnormaltextstylestexttransform" />
							<label class="ckbutton first hasTip" title="<?php echo __('Lowercase', 'accordeon-menu-ck'); ?>" for="level3itemnormaltextstylestexttransformlowercase"><img class="ckicon" src="<?php echo $this->interface->imagespath ?>/text_lowercase.png" />
							</label><input class="level3itemnormaltextstyles" type="radio" value="uppercase" id="level3itemnormaltextstylestexttransformuppercase" name="level3itemnormaltextstylestexttransform" />
							<label class="ckbutton hasTip" title="<?php echo __('Uppercase', 'accordeon-menu-ck'); ?>" for="level3itemnormaltextstylestexttransformuppercase"><img class="ckicon" src="<?php echo $this->interface->imagespath ?>/text_uppercase.png" />
							</label><input class="level3itemnormaltextstyles" type="radio" value="capitalize" id="level3itemnormaltextstylestexttransformcapitalize" name="level3itemnormaltextstylestexttransform" />
							<label class="ckbutton hasTip" title="<?php echo __('Capitalize', 'accordeon-menu-ck'); ?>" for="level3itemnormaltextstylestexttransformcapitalize"><img class="ckicon" src="<?php echo $this->interface->imagespath ?>/text_capitalize.png" />
							</label><input class="level3itemnormaltextstyles" type="radio" value="default" id="level3itemnormaltextstylestexttransformdefault" name="level3itemnormaltextstylestexttransform" />
							<label class="ckbutton hasTip" title="<?php echo __('Default', 'accordeon-menu-ck'); ?>" for="level3itemnormaltextstylestexttransformdefault"><img class="ckicon" src="<?php echo $this->interface->imagespath ?>/text_default.png" />
							</label>
						</div>
					</div>
					<div class="ckrow">
						<label for="level3itemnormaltextstylesfontweightbold"></label>
						<img class="ckicon" src="<?php echo $this->interface->imagespath ?>/text_bold.png" />
						<div class="ckbutton-group">
							<input class="level3itemnormaltextstyles" type="radio" value="bold" id="level3itemnormaltextstylesfontweightbold" name="level3itemnormaltextstylesfontweight" />
							<label class="ckbutton first hasTip" title="" for="level3itemnormaltextstylesfontweightbold" style="width:auto;"><?php echo __('Bold', 'accordeon-menu-ck'); ?>
							</label><input class="level3itemnormaltextstyles" type="radio" value="normal" id="level3itemnormaltextstylesfontweightnormal" name="level3itemnormaltextstylesfontweight" />
							<label class="ckbutton hasTip" title="" for="level3itemnormaltextstylesfontweightnormal" style="width:auto;"><?php echo __('Normal', 'accordeon-menu-ck'); ?>
							</label>
						</div>
					</div>
					<div class="ckrow">
						<label><?php echo __('Title style', 'accordeon-menu-ck'); ?></label>
						<img class="ckicon" src="<?php echo $this->interface->imagespath ?>/style.png" />
						<input type="text" id="level3itemnormaltextstylesfontsize" name="level3itemnormaltextstylesfontsize" class="level3itemnormaltextstyles hasTip" style="width:30px;" title="<?php echo __('Font size (set the value in px, em, or %, the default unit is px)', 'accordeon-menu-ck'); ?>" />
						<img class="ckicon" src="<?php echo $this->interface->imagespath ?>/color.png" />
						<span><?php echo __('Normal', 'accordeon-menu-ck'); ?></span>
						<input type="text" id="level3itemnormaltextstylescolor" name="level3itemnormaltextstylescolor" class="level3itemnormaltextstyles hasTip <?php echo $this->interface->colorpicker_class; ?>" title="<?php echo __('Link color. Use the colorpicker or write your own value', 'accordeon-menu-ck'); ?>" />
						<img class="ckicon" src="<?php echo $this->interface->imagespath ?>/color.png" />
						<span><?php echo __('Hover', 'accordeon-menu-ck'); ?></span>
						<input type="text" id="level3itemhovertextstylescolor" name="level3itemhovertextstylescolor" class="level3itemhovertextstyles hasTip <?php echo $this->interface->colorpicker_class; ?>" title="<?php echo __('Link color. Use the colorpicker or write your own value', 'accordeon-menu-ck'); ?>" />
					</div>
					<div class="ckrow">
						<label><?php echo __('Description style', 'accordeon-menu-ck'); ?></label>
						<img class="ckicon" src="<?php echo $this->interface->imagespath ?>/style.png" />
						<input type="text" id="level3itemnormaltextdescstylesfontsize" name="level3itemnormaltextdescstylesfontsize" class="level3itemnormaltextdescstyles hasTip" style="width:30px;" title="<?php echo __('Font size (set the value in px, em, or %, the default unit is px)', 'accordeon-menu-ck'); ?>" />
						<img class="ckicon" src="<?php echo $this->interface->imagespath ?>/color.png" />
						<span><?php echo __('Normal', 'accordeon-menu-ck'); ?></span>
						<input type="text" id="level3itemnormaltextdescstylescolor" name="level3itemnormaltextdescstylescolor" class="level3itemnormaltextdescstyles hasTip <?php echo $this->interface->colorpicker_class; ?>" title="<?php echo __('Link color. Use the colorpicker or write your own value', 'accordeon-menu-ck'); ?>" />
						<img class="ckicon" src="<?php echo $this->interface->imagespath ?>/color.png" />
						<span><?php echo __('Hover', 'accordeon-menu-ck'); ?></span>
						<input type="text" id="level3itemhovertextdescstylescolor" name="level3itemhovertextdescstylescolor" class="level3itemhovertextdescstyles hasTip <?php echo $this->interface->colorpicker_class; ?>" title="<?php echo __('Link color. Use the colorpicker or write your own value', 'accordeon-menu-ck'); ?>" />
					</div>
					<div class="ckheading"><?php echo __('Appearance', 'accordeon-menu-ck'); ?></div>
					<?php
					$this->interface->createBackgroundColor('level3menustyles');
					$this->interface->createBackgroundImage('level3menustyles');
					$this->interface->createBorders('level3menustyles');
					$this->interface->createRoundedCorners('level3menustyles');
					$this->interface->createShadow('level3menustyles');
					?>
					<div class="ckheading"><?php echo __('Dimensions', 'accordeon-menu-ck'); ?></div>
					<?php
					$this->interface->createMargins('level3menustyles');
					?>
				</div>
				<div class="cktab2" id="tab_level3itemgroup" data-group="subsubmenu">
					<?php 
						echo Helper::getProMessage();
					?>
				</div>
				<div class="cktab2" id="tab_level3itemnormalstyles" data-group="subsubmenu">
					<?php
					$this->interface->createPadding('level3itemnormaltextstyles');
					echo Helper::getProMessage();
					?>
				</div>
				<div class="cktab2" id="tab_level3itemhoverstyles" data-group="subsubmenu">
					<?php
					echo Helper::getProMessage();
					?>
				</div>
				<div class="cktab2" id="tab_level3itemparentarrow" data-group="subsubmenu">
					<?php
					echo Helper::getProMessage();
					?>
				</div>
			</div>
			<div style="clear:both;"></div>
		</div>
		<div class="cktab" id="tab_presets" data-group="main">
			<?php
			echo Helper::getProMessage();
			?>
		</div>
		<div class="cktab" id="tab_customcss" data-group="main">
			<textarea id="customcss" name="customcss" style="width: 100%;min-height:500px;box-sizing:border-box;"></textarea>
		</div>
		</div>
	</div>
	<div id="stylescontainerright" style="width: 200px;">
		<div id="previewarea" >
			<div class="ckstyle"></div>
			<div class="inner" style="width: 200px;">
				<?php $this->renderPreviewMenu() ?>
			</div>
		</div>
	</div>
	<div style="clear:both;"></div>
</div>
<?php //require_once('default_importexport.php'); ?>
</div>
<?php echo Helper::copyright() ?>
<script type="text/javascript">
	var ACCORDEONMENUCK_ADMIN_EDIT_STYLE_URL = '<?php echo ACCORDEONMENUCK_ADMIN_EDIT_STYLE_URL ?>';
	var URIBASE = "<?php echo ACCORDEONMENUCK_URI_BASE; ?>";
	var CKTOKEN = 'CKTOKEN=<?php echo CKFof::getToken('accordeonmenuck') ?>';
	var CKCSSREPLACEMENT = new Object();
	<?php foreach (\Accordeonmenuck\Helper::getCssReplacement() as $tag => $rep) { ?>
	CKCSSREPLACEMENT['<?php echo $tag ?>'] = '<?php echo $rep ?>';
	<?php } ?>

	jQuery(document).ready(function($){

		var modalpopup = jQuery('#ckedition.ckmodal');
		if (modalpopup.length) {
			jQuery(document.body).prepend(modalpopup);
		}

		ckInitTabsStyles();

		// launch the preview when the user do a change
		$('#styleswizard_options input,#styleswizard_options select,#styleswizard_options textarea').change(function() {
			ckPreviewStylesparams();
		});
//		 jQuery('.cktip').tooltip({"html": true,"container": "body"});
		ckApplyStylesparams();
		ckSetFloatingOnPreview();
		// ckLoadGfontStylesheets();
	});
</script>
