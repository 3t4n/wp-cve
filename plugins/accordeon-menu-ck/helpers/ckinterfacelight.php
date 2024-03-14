<?php
/**
 * @copyright	Copyright (C) 2016. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author Cedric KEIFLIN https://www.joomlack.fr - https://www.ceikay.com
 */
Namespace Accordeonmenuck;

// No direct access to this file
defined('CK_LOADED') or die;

/**
 * Interface is a class to render the fields
 *
 */
class CKInterfaceLight {

	public $imagespath = 'https://media.ceikay.com/images';
	
	public $colorpicker_class = 'color {required:false,pickerPosition:\'top\',pickerBorder:2,pickerInset:3,hash:true}';

	public function createAll($prefix, $textshadow = false) {
		?>
		<div class="ckheading"><?php echo __('Text', 'accordeon-menu-ck'); ?></div>
		<?php
		$this->createText($prefix);
		if ($textshadow) $this->createTextShadow($prefix);
		?>
		<div class="ckheading"><?php echo __('Appearance', 'accordeon-menu-ck'); ?></div>
		<?php
		$this->createBackgroundColor($prefix);
		$this->createBackgroundImage($prefix);
		$this->createBorders($prefix);
		$this->createRoundedCorners($prefix);
		$this->createShadow($prefix);
		?>
		<div class="ckheading"><?php echo __('Dimensions', 'accordeon-menu-ck'); ?></div>
		<?php
		$this->createMargins($prefix);
		$this->createDimensions($prefix);
		/*
		?>
		<div class="ckheading"><?php echo __('Animations', 'accordeon-menu-ck'); ?></div>
		<?php
	$this->createAnimations($prefix);
		 */
	}

	public function createBackgroundColor($prefix) {
	?>
	<div class="ckrow">
		<label for="<?php echo $prefix; ?>bgcolor1"><?php echo __('Background color', 'accordeon-menu-ck'); ?></label>
		<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
		<input type="text" id="<?php echo $prefix; ?>bgcolor1" name="<?php echo $prefix; ?>bgcolor1" class="hasTip <?php echo $prefix; ?> <?php echo $this->colorpicker_class; ?>" title="<?php echo __('Background color. Use the colorpicker or write your own value', 'accordeon-menu-ck'); ?>"/>
		<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
		<input type="text" id="<?php echo $prefix; ?>bgcolor2" name="<?php echo $prefix; ?>bgcolor2" class="hasTip <?php echo $prefix; ?> <?php echo $this->colorpicker_class; ?>" title="<?php echo __('Gradient color. Use the colorpicker or write your own value. Note that if you use a gradient you can not use a background image.', 'accordeon-menu-ck'); ?>" onchange="ckCheckGradientImageConflict(this, '<?php echo $prefix; ?>bgimage')"/>
		<img class="ckicon" src="<?php echo $this->imagespath ?>/layers.png" />
		<input type="text" id="<?php echo $prefix; ?>bgopacity" name="<?php echo $prefix; ?>bgopacity" class="hasTip <?php echo $prefix; ?>" style="width:30px;" title="<?php echo __('Background opacity value from 0 to 1', 'accordeon-menu-ck'); ?>"/>
	</div>
	<?php
	}
	
	public function createBackgroundImage($prefix) {
	?>
	<div class="ckrow">
		<label for="<?php echo $prefix; ?>bgimage"><?php echo __('Background image', 'accordeon-menu-ck'); ?></label>
		<img class="ckicon" src="<?php echo $this->imagespath ?>/image.png" />
		<div class="ckbutton-group">
			<input type="text" id="<?php echo $prefix; ?>bgimage" name="<?php echo $prefix; ?>bgimage" class="hasTip <?php echo $prefix; ?>" title="<?php echo __('Select the image to use as background. . Note that if you use a background image you can not use a gradient', 'accordeon-menu-ck'); ?>" onchange="ckCheckGradientImageConflict(this, '<?php echo $prefix; ?>bgcolor2')" style="max-width: none; width: 150px;"/>
			<a class=" ckbutton" onclick="ckOpenMediaManager(this, '<?php echo get_site_url() ?>');" ><?php echo __('Select', 'accordeon-menu-ck'); ?></a>
			<a class="ckbutton" href="javascript:void(0)" onclick="jQuery(this).parent().find('input').val('');"><?php echo __('Clear', 'accordeon-menu-ck'); ?></a>
		</div>
	</div>
	<div class="ckrow">
		<label></label>
		<img class="ckicon" src="<?php echo $this->imagespath ?>/offsetx.png" /><span><input type="text" id="<?php echo $prefix; ?>bgpositionx" name="<?php echo $prefix; ?>bgpositionx" class="<?php echo $prefix; ?> hasTip" style="width:30px;" title="<?php echo __('Background image position in the X axis (horizontal)', 'accordeon-menu-ck'); ?>" /></span>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/offsety.png" /></span><span><input type="text" id="<?php echo $prefix; ?>bgpositiony" name="<?php echo $prefix; ?>bgpositiony" class="<?php echo $prefix; ?> hasTip" style="width:30px;" title="<?php echo __('Background image position in the Y axis (vertical)', 'accordeon-menu-ck'); ?>" /></span>
		<div class="ckbutton-group">
			<input class="" type="radio" value="repeat" id="<?php echo $prefix; ?>bgimagerepeatrepeat" name="<?php echo $prefix; ?>bgimagerepeat" class="<?php echo $prefix; ?>" />
			<label class="ckbutton first" for="<?php echo $prefix; ?>bgimagerepeatrepeat"><img class="ckicon" src="<?php echo $this->imagespath ?>/bg_repeat.png" />
			</label><input class="<?php echo $prefix; ?>" type="radio" value="repeat-x" id="<?php echo $prefix; ?>bgimagerepeatrepeat-x" name="<?php echo $prefix; ?>bgimagerepeat" />
			<label class="ckbutton"  for="<?php echo $prefix; ?>bgimagerepeatrepeat-x"><img class="ckicon" src="<?php echo $this->imagespath ?>/bg_repeat-x.png" />
			</label><input class="<?php echo $prefix; ?>" type="radio" value="repeat-y" id="<?php echo $prefix; ?>bgimagerepeatrepeat-y" name="<?php echo $prefix; ?>bgimagerepeat" />
			<label class="ckbutton last"  for="<?php echo $prefix; ?>bgimagerepeatrepeat-y"><img class="ckicon" src="<?php echo $this->imagespath ?>/bg_repeat-y.png" />
			</label><input class="<?php echo $prefix; ?>" type="radio" value="no-repeat" id="<?php echo $prefix; ?>bgimagerepeatno-repeat" name="<?php echo $prefix; ?>bgimagerepeat" />
			<label class="ckbutton last"  for="<?php echo $prefix; ?>bgimagerepeatno-repeat"><img class="ckicon" src="<?php echo $this->imagespath ?>/bg_no-repeat.png" /></label>
		</div>
	</div>
	<div class="ckrow">
		<label for="<?php echo $prefix; ?>bgimagesize"><?php echo __('Background size', 'accordeon-menu-ck'); ?></label>
		<img class="ckicon" src="<?php echo $this->imagespath ?>/arrow_out.png" />
		<span>
			<select id="<?php echo $prefix; ?>bgimagesize" name="<?php echo $prefix; ?>bgimagesize" class="<?php echo $prefix; ?>">
				<option value="auto"><?php echo CKText::_('Default') ?></option>
				<option value="cover"><?php echo CKText::_('Cover') ?></option>
				<option value="contain"><?php echo CKText::_('Contain') ?></option>
			</select>
		</span>
	</div>
	<?php
	}
	public function createRoundedCorners($prefix) {
	?>
	<div class="ckrow">
		<label for="<?php echo $prefix; ?>roundedcornerstl"><?php echo __('Border radius', 'accordeon-menu-ck'); ?></label>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/border_radius_tl.png" /></span><span ><input type="text" id="<?php echo $prefix; ?>roundedcornerstl" name="<?php echo $prefix; ?>roundedcornerstl" class="<?php echo $prefix; ?> hasTip" style="width:30px;" title="<?php echo __('Border radius Top Left width (set the value in px, em, or %, the default unit is px)', 'accordeon-menu-ck'); ?>" /></span>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/border_radius_tr.png" /></span><span ><input type="text" id="<?php echo $prefix; ?>roundedcornerstr" name="<?php echo $prefix; ?>roundedcornerstr" class="<?php echo $prefix; ?> hasTip" style="width:30px;" title="<?php echo __('Border radius Top Right width (set the value in px, em, or %, the default unit is px)', 'accordeon-menu-ck'); ?>" /></span>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/border_radius_br.png" /></span><span ><input type="text" id="<?php echo $prefix; ?>roundedcornersbr" name="<?php echo $prefix; ?>roundedcornersbr" class="<?php echo $prefix; ?> hasTip" style="width:30px;" title="<?php echo __('Border radius Bottom Right width (set the value in px, em, or %, the default unit is px)', 'accordeon-menu-ck'); ?>" /></span>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/border_radius_bl.png" /></span><span ><input type="text" id="<?php echo $prefix; ?>roundedcornersbl" name="<?php echo $prefix; ?>roundedcornersbl" class="<?php echo $prefix; ?> hasTip" style="width:30px;" title="<?php echo __('Border radius Bottom Left width (set the value in px, em, or %, the default unit is px)', 'accordeon-menu-ck'); ?>" /></span>
	</div>
	<?php
	}
	public function createShadow($prefix) {
	?>
	<div class="ckrow">
		<label for="<?php echo $prefix; ?>shadowcolor"><?php echo __('Shadow', 'accordeon-menu-ck'); ?></label>
		<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
		<span><input type="text" id="<?php echo $prefix; ?>shadowcolor" name="<?php echo $prefix; ?>shadowcolor" class="<?php echo $prefix; ?> <?php echo $this->colorpicker_class; ?>" /></span>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/shadow_blur.png" /></span><span ><input type="text" id="<?php echo $prefix; ?>shadowblur" name="<?php echo $prefix; ?>shadowblur" class="<?php echo $prefix; ?> hasTip" style="width:30px;" title="<?php echo __('Blur distance', 'accordeon-menu-ck'); ?>" /></span>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/shadow_spread.png" /></span><span ><input type="text" id="<?php echo $prefix; ?>shadowspread" name="<?php echo $prefix; ?>shadowspread" class="<?php echo $prefix; ?> hasTip" style="width:30px;" title="<?php echo __('Spread distance (optional)', 'accordeon-menu-ck'); ?>" /></span>
	</div>
	<div class="ckrow">
		<label></label>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/offsetx.png" /></span><span ><input type="text" id="<?php echo $prefix; ?>shadowoffsetx" name="<?php echo $prefix; ?>shadowoffsetx" class="<?php echo $prefix; ?> hasTip" style="width:30px;" title="<?php echo __('Shadow offset in the X axis (horizontal)', 'accordeon-menu-ck'); ?>" /></span>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/offsety.png" /></span><span ><input type="text" id="<?php echo $prefix; ?>shadowoffsety" name="<?php echo $prefix; ?>shadowoffsety" class="<?php echo $prefix; ?> hasTip" style="width:30px;" title="<?php echo __('Shadow offset in the Y axis (vertical)', 'accordeon-menu-ck'); ?>" /></span>
		<div class="ckbutton-group">
			<input class="<?php echo $prefix; ?>" type="radio" value="0" id="<?php echo $prefix; ?>shadowinsetno" name="<?php echo $prefix; ?>shadowinset" />
			<label class="ckbutton last"  for="<?php echo $prefix; ?>shadowinsetno" style="width:auto;"><?php echo __('Out', 'accordeon-menu-ck'); ?>
			</label><input class="<?php echo $prefix; ?>" type="radio" value="1" id="<?php echo $prefix; ?>shadowinsetyes" name="<?php echo $prefix; ?>shadowinset" />
			<label class="ckbutton last"  for="<?php echo $prefix; ?>shadowinsetyes" style="width:auto;"><?php echo __('In', 'accordeon-menu-ck'); ?></label>
		</div>
	</div>
	<?php
	}
	public function createTextShadow($prefix) {
	?>
	<div class="ckrow">
		<label for="<?php echo $prefix; ?>textshadowcolor"><?php echo __('Text shadow', 'accordeon-menu-ck'); ?></label>
		<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
		<span><input type="text" id="<?php echo $prefix; ?>textshadowcolor" name="<?php echo $prefix; ?>textshadowcolor" class="<?php echo $prefix; ?> <?php echo $this->colorpicker_class; ?>" /></span>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/shadow_blur.png" /></span><span ><input type="text" id="<?php echo $prefix; ?>textshadowblur" name="<?php echo $prefix; ?>textshadowblur" class="<?php echo $prefix; ?> hasTip" style="width:30px;" title="<?php echo __('Blur distance', 'accordeon-menu-ck'); ?>" /></span>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/offsetx.png" /></span><span ><input type="text" id="<?php echo $prefix; ?>textshadowoffsetx" name="<?php echo $prefix; ?>textshadowoffsetx" class="<?php echo $prefix; ?> hasTip" style="width:30px;" title="<?php echo __('Shadow offset in the X axis (horizontal)', 'accordeon-menu-ck'); ?>" /></span>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/offsety.png" /></span><span ><input type="text" id="<?php echo $prefix; ?>textshadowoffsety" name="<?php echo $prefix; ?>textshadowoffsety" class="<?php echo $prefix; ?> hasTip" style="width:30px;" title="<?php echo __('Shadow offset in the Y axis (vertical)', 'accordeon-menu-ck'); ?>" /></span>
	</div>
	<?php
	}

	public function createDimensions($prefix) {
	?>
	<div class="ckrow">
		<label for="<?php echo $prefix; ?>width"><?php echo __('Dimensions', 'accordeon-menu-ck'); ?></label>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/width.png" /></span><span ><input type="text" id="<?php echo $prefix; ?>width" name="<?php echo $prefix; ?>width" class="<?php echo $prefix; ?> hasTip" style="width:30px;" title="<?php echo __('Width of the element. Set the value in px.', 'accordeon-menu-ck'); ?>" /></span>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/height.png" /></span><span ><input type="text" id="<?php echo $prefix; ?>height" name="<?php echo $prefix; ?>height" class="<?php echo $prefix; ?> hasTip" style="width:30px;" title="<?php echo __('Height of the element. Set the value in px.', 'accordeon-menu-ck'); ?>" /></span>
	</div>
	<?php
	}

	public function createMargins($prefix) {
		$this->createMargin($prefix);
		$this->createPadding($prefix);
	}

	public function createMargin($prefix) {
	?>
	<div class="ckrow">
		<label for="<?php echo $prefix; ?>margintop"><?php echo __('Margin', 'accordeon-menu-ck'); ?></label>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/margin_top.png" /></span><span ><input type="text" id="<?php echo $prefix; ?>margintop" name="<?php echo $prefix; ?>margintop" class="<?php echo $prefix; ?> hasTip" style="width:30px;" title="<?php echo __('Margin top (set the value in px, em, or %, the default unit is px)', 'accordeon-menu-ck'); ?>" /></span>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/margin_right.png" /></span><span ><input type="text" id="<?php echo $prefix; ?>marginright" name="<?php echo $prefix; ?>marginright" class="<?php echo $prefix; ?> hasTip" style="width:30px;" title="<?php echo __('Margin right (set the value in px, em, or %, the default unit is px)', 'accordeon-menu-ck'); ?>" /></span>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/margin_bottom.png" /></span><span ><input type="text" id="<?php echo $prefix; ?>marginbottom" name="<?php echo $prefix; ?>marginbottom" class="<?php echo $prefix; ?> hasTip" style="width:30px;" title="<?php echo __('Margin bottom (set the value in px, em, or %, the default unit is px)', 'accordeon-menu-ck'); ?>" /></span>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/margin_left.png" /></span><span ><input type="text" id="<?php echo $prefix; ?>marginleft" name="<?php echo $prefix; ?>marginleft" class="<?php echo $prefix; ?> hasTip" style="width:30px;" title="<?php echo __('Margin left (set the value in px, em, or %, the default unit is px)', 'accordeon-menu-ck'); ?>" /></span>
	</div>
	<?php
	}
	
	public function createPadding($prefix) {
	?>
	<div class="ckrow">
		<label for="<?php echo $prefix; ?>paddingtop"><?php echo __('Padding (inside margin)', 'accordeon-menu-ck'); ?></label>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/padding_top.png" /></span><span ><input type="text" id="<?php echo $prefix; ?>paddingtop" name="<?php echo $prefix; ?>paddingtop" class="<?php echo $prefix; ?> hasTip" style="width:30px;" title="<?php echo __('Padding top (set the value in px, em, or %, the default unit is px)', 'accordeon-menu-ck'); ?>" /></span>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/padding_right.png" /></span><span ><input type="text" id="<?php echo $prefix; ?>paddingright" name="<?php echo $prefix; ?>paddingright" class="<?php echo $prefix; ?> hasTip" style="width:30px;" title="<?php echo __('Padding right (set the value in px, em, or %, the default unit is px)', 'accordeon-menu-ck'); ?>" /></span>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/padding_bottom.png" /></span><span ><input type="text" id="<?php echo $prefix; ?>paddingbottom" name="<?php echo $prefix; ?>paddingbottom" class="<?php echo $prefix; ?> hasTip" style="width:30px;" title="<?php echo __('Padding bottom (set the value in px, em, or %, the default unit is px)', 'accordeon-menu-ck'); ?>" /></span>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/padding_left.png" /></span><span ><input type="text" id="<?php echo $prefix; ?>paddingleft" name="<?php echo $prefix; ?>paddingleft" class="<?php echo $prefix; ?> hasTip" style="width:30px;" title="<?php echo __('Padding left(set the value in px, em, or %, the default unit is px)', 'accordeon-menu-ck'); ?>" /></span>
	</div>
	<?php
	}

	public function createBorders($prefix) {
	?>
	<div class="ckrow">
		<label for="<?php echo $prefix; ?>bordertopcolor"><?php echo __('Border', 'accordeon-menu-ck'); ?></label>
		<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
		<span><input type="text" id="<?php echo $prefix; ?>bordertopcolor" name="<?php echo $prefix; ?>bordertopcolor" class="<?php echo $prefix; ?> <?php echo $this->colorpicker_class; ?>" title="<?php echo __('Border color. Use the colorpicker or write your own value', 'accordeon-menu-ck'); ?>"/></span>
		<span><input type="text" id="<?php echo $prefix; ?>bordertopwidth" name="<?php echo $prefix; ?>bordertopwidth" class="<?php echo $prefix; ?> hasTip" style="width:30px;border-top-color:#237CA4;" title="<?php echo __('Border top width (set the value in px, em, or %, the default unit is px)', 'accordeon-menu-ck'); ?>" /></span>
		<span>
			<select id="<?php echo $prefix; ?>bordertopstyle" name="<?php echo $prefix; ?>bordertopstyle" class="<?php echo $prefix; ?> hasTip" style="width: 70px; border-radius: 0px;">
				<option value="solid">solid</option>
				<option value="dotted">dotted</option>
				<option value="dashed">dashed</option>
			</select>
		</span>
	</div>
	<div class="ckrow">
		<label></label>
		<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
		<span><input type="text" id="<?php echo $prefix; ?>borderrightcolor" name="<?php echo $prefix; ?>borderrightcolor" class="<?php echo $prefix; ?> <?php echo $this->colorpicker_class; ?>" title="<?php echo __('Border color. Use the colorpicker or write your own value', 'accordeon-menu-ck'); ?>"/></span>
		<span><input type="text" id="<?php echo $prefix; ?>borderrightwidth" name="<?php echo $prefix; ?>borderrightwidth" class="<?php echo $prefix; ?> hasTip" style="width:30px;border-right-color:#237CA4;" title="<?php echo __('Border right width (set the value in px, em, or %, the default unit is px)', 'accordeon-menu-ck'); ?>" /></span>
		<span>
			<select id="<?php echo $prefix; ?>borderrightstyle" name="<?php echo $prefix; ?>borderrightstyle" class="<?php echo $prefix; ?> hasTip" style="width: 70px; border-radius: 0px;">
				<option value="solid">solid</option>
				<option value="dotted">dotted</option>
				<option value="dashed">dashed</option>
			</select>
		</span>
	</div>
	<div class="ckrow">
		<label></label>
		<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
		<span><input type="text" id="<?php echo $prefix; ?>borderbottomcolor" name="<?php echo $prefix; ?>borderbottomcolor" class="<?php echo $prefix; ?> <?php echo $this->colorpicker_class; ?>" title="<?php echo __('Border color. Use the colorpicker or write your own value', 'accordeon-menu-ck'); ?>"/></span>
		<span><input type="text" id="<?php echo $prefix; ?>borderbottomwidth" name="<?php echo $prefix; ?>borderbottomwidth" class="<?php echo $prefix; ?> hasTip" style="width:30px;border-bottom-color:#237CA4;" title="<?php echo __('Border bottom width (set the value in px, em, or %, the default unit is px)', 'accordeon-menu-ck'); ?>" /></span>
		<span>
			<select id="<?php echo $prefix; ?>borderbottomstyle" name="<?php echo $prefix; ?>borderbottomstyle" class="<?php echo $prefix; ?> hasTip" style="width: 70px; border-radius: 0px;">
				<option value="solid">solid</option>
				<option value="dotted">dotted</option>
				<option value="dashed">dashed</option>
			</select>
		</span>
	</div>
	<div class="ckrow">
		<label></label>
		<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
		<span><input type="text" id="<?php echo $prefix; ?>borderleftcolor" name="<?php echo $prefix; ?>borderleftcolor" class="<?php echo $prefix; ?> <?php echo $this->colorpicker_class; ?>" title="<?php echo __('Border color. Use the colorpicker or write your own value', 'accordeon-menu-ck'); ?>"/></span>
		<span><input type="text" id="<?php echo $prefix; ?>borderleftwidth" name="<?php echo $prefix; ?>borderleftwidth" class="<?php echo $prefix; ?> hasTip" style="width:30px;border-left-color:#237CA4;" title="<?php echo __('Border left width  (set the value in px, em, or %, the default unit is px)', 'accordeon-menu-ck'); ?>" /></span>
		<span>
			<select id="<?php echo $prefix; ?>borderleftstyle" name="<?php echo $prefix; ?>borderleftstyle" class="<?php echo $prefix; ?> hasTip" style="width: 70px; border-radius: 0px;">
				<option value="solid">solid</option>
				<option value="dotted">dotted</option>
				<option value="dashed">dashed</option>
			</select>
		</span>
	</div>
	<?php
	}

	public function createText($prefix) { 
	?>
	<div class="ckrow">
		<label for="<?php echo $prefix; ?>textgfont"><?php echo __('Font style', 'accordeon-menu-ck'); ?></label>
		<img class="ckicon" src="<?php echo $this->imagespath ?>/font_add.png" />
		<input type="text" id="<?php echo $prefix; ?>textgfont" name="<?php echo $prefix; ?>textgfont" class="<?php echo $prefix; ?> hasTip gfonturl" onchange="ckCleanGfontName(this);" title="<?php echo __('Enter the name of a google font to use, for example : Open+Sans+Condensed:300', 'accordeon-menu-ck'); ?>" style="max-width:none;width:250px;" />
		<input type="hidden" id="<?php echo $prefix; ?>textisgfont" name="<?php echo $prefix; ?>textisgfont" class="isgfont <?php echo $prefix; ?>" />
	</div>
	<div class="ckrow">
		<label></label>
		<img class="ckicon" src="<?php echo $this->imagespath ?>/style.png" />
		<input type="text" id="<?php echo $prefix; ?>fontsize" name="<?php echo $prefix; ?>fontsize" class="<?php echo $prefix; ?> hasTip" style="width:50px;" title="<?php echo __('Font size (set the value in px, em, or %, the default unit is px)', 'accordeon-menu-ck'); ?>" />
		<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
		<span><?php echo __('Normal', 'accordeon-menu-ck'); ?></span>
		<input type="text" id="<?php echo $prefix; ?>fontcolor" name="<?php echo $prefix; ?>fontcolor" class="<?php echo $prefix; ?> hasTip <?php echo $this->colorpicker_class; ?>" title="<?php echo __('Link color. Use the colorpicker or write your own value', 'accordeon-menu-ck'); ?>" />
	</div>
	<div class="ckrow">
		<label for="">&nbsp;</label><img class="ckicon" src="<?php echo $this->imagespath ?>/font.png" />
		<div class="ckbutton-group">
			<input class="<?php echo $prefix; ?>" type="radio" value="left" id="<?php echo $prefix; ?>textalignleft" name="<?php echo $prefix; ?>textalign" />
			<label class="ckbutton first" for="<?php echo $prefix; ?>textalignleft"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_align_left.png" />
			</label><input class="<?php echo $prefix; ?>" type="radio" value="center" id="<?php echo $prefix; ?>textaligncenter" name="<?php echo $prefix; ?>textalign" />
			<label class="ckbutton"  for="<?php echo $prefix; ?>textaligncenter"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_align_center.png" />
			</label><input class="<?php echo $prefix; ?>" type="radio" value="right" id="<?php echo $prefix; ?>textalignright" name="<?php echo $prefix; ?>textalign" />
			<label class="ckbutton last"  for="<?php echo $prefix; ?>textalignright"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_align_right.png" /></label>
		</div>
		<label for="">&nbsp;</label>
		<div class="ckbutton-group">
			<input class="<?php echo $prefix; ?>" type="radio" value="lowercase" id="<?php echo $prefix; ?>texttransformlowercase" name="<?php echo $prefix; ?>texttransform" />
			<label class="ckbutton first hasTip" title="<?php echo __('Lowercase', 'accordeon-menu-ck'); ?>" for="<?php echo $prefix; ?>texttransformlowercase"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_lowercase.png" />
			</label><input class="<?php echo $prefix; ?>" type="radio" value="uppercase" id="<?php echo $prefix; ?>texttransformuppercase" name="<?php echo $prefix; ?>texttransform" />
			<label class="ckbutton hasTip" title="<?php echo __('Uppercase', 'accordeon-menu-ck'); ?>" for="<?php echo $prefix; ?>texttransformuppercase"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_uppercase.png" />
			</label><input class="<?php echo $prefix; ?>" type="radio" value="capitalize" id="<?php echo $prefix; ?>texttransformcapitalize" name="<?php echo $prefix; ?>texttransform" />
			<label class="ckbutton hasTip" title="<?php echo __('Capitalize', 'accordeon-menu-ck'); ?>" for="<?php echo $prefix; ?>texttransformcapitalize"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_capitalize.png" />
			</label><input class="<?php echo $prefix; ?>" type="radio" value="default" id="<?php echo $prefix; ?>texttransformdefault" name="<?php echo $prefix; ?>texttransform" />
			<label class="ckbutton hasTip" title="<?php echo __('Default', 'accordeon-menu-ck'); ?>" for="<?php echo $prefix; ?>texttransformdefault"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_default.png" />
			</label>
		</div>
	</div>
	<div class="ckrow">
		<label for="<?php echo $prefix; ?>fontweightbold"></label>
		<img class="ckicon" src="<?php echo $this->imagespath ?>/text_bold.png" />
		<div class="ckbutton-group">
			<input class="<?php echo $prefix; ?>" type="radio" value="bold" id="<?php echo $prefix; ?>fontweightbold" name="<?php echo $prefix; ?>fontweight" />
			<label class="ckbutton first hasTip" title="" for="<?php echo $prefix; ?>fontweightbold" style="width:auto;"><?php echo __('Bold', 'accordeon-menu-ck'); ?>
			</label><input class="<?php echo $prefix; ?>" type="radio" value="normal" id="<?php echo $prefix; ?>fontweightnormal" name="<?php echo $prefix; ?>fontweight" />
			<label class="ckbutton hasTip" title="" for="<?php echo $prefix; ?>fontweightnormal" style="width:auto;"><?php echo __('Normal', 'accordeon-menu-ck'); ?>
			</label>
		</div>
		<img class="ckicon" src="<?php echo $this->imagespath ?>/shape_align_middle.png" />
		<input type="text" id="<?php echo $prefix; ?>lineheight" name="<?php echo $prefix; ?>lineheight" class="<?php echo $prefix; ?> hasTip" style="width:30px;" title="" />
		<img class="ckicon" src="<?php echo $this->imagespath ?>/text_padding_left.png" />
		<input type="text" id="<?php echo $prefix; ?>textindent" name="<?php echo $prefix; ?>textindent" class="<?php echo $prefix; ?> hasTip" style="width:30px;" title="" />
	</div>
	<?php
	}

	public function createAnimations($prefix) {
	?>
		<div class="ckrow">
			<label for="<?php echo $prefix; ?>animdur"><?php echo __('Duration', 'accordeon-menu-ck'); ?></label>
			<img class="ckicon" src="<?php echo $this->imagespath ?>/hourglass.png" />
			<input class="<?php echo $prefix; ?>" type="text" name="<?php echo $prefix; ?>animdur" id="<?php echo $prefix; ?>animdur" value="1" /> [s]
		</div>
		<div class="ckrow">
			<label for="<?php echo $prefix; ?>animdelay"><?php echo __('Delay', 'accordeon-menu-ck'); ?></label>
			<img class="ckicon" src="<?php echo $this->imagespath ?>/hourglass.png" />
			<input class="<?php echo $prefix; ?>" type="text" name="<?php echo $prefix; ?>animdelay" id="<?php echo $prefix; ?>animdelay" value="0" /> [s]
		</div>
		<div class="ckrow">
			<label for="<?php echo $prefix; ?>animfade"><?php echo __('Fade', 'accordeon-menu-ck'); ?></label>
			<img class="ckicon" src="<?php echo $this->imagespath ?>/shading.png" />
			<select class="<?php echo $prefix; ?>" type="list" name="<?php echo $prefix; ?>animfade" id="<?php echo $prefix; ?>animfade" value="" style="width: 100px;" >
				<option value="0"><?php echo __('No', 'accordeon-menu-ck'); ?></option>
				<option value="1"><?php echo __('Yes', 'accordeon-menu-ck'); ?></option>
			</select>
		</div>
		<div class="ckrow">
			<label for="<?php echo $prefix; ?>animmove"><?php echo __('Move', 'accordeon-menu-ck'); ?></label>
			<img class="ckicon" src="<?php echo $this->imagespath ?>/shape_square_go.png" />
			<select class="<?php echo $prefix; ?>" type="list" name="<?php echo $prefix; ?>animmove" id="<?php echo $prefix; ?>animmove" value="" style="width: 100px;" >
				<option value="0"><?php echo __('No', 'accordeon-menu-ck'); ?></option>
				<option value="1"><?php echo __('Yes', 'accordeon-menu-ck'); ?></option>
			</select>
			<select class="<?php echo $prefix; ?> hasTip" title="<?php echo __('Direction', 'accordeon-menu-ck'); ?>" type="list" name="<?php echo $prefix; ?>animmovedir" id="<?php echo $prefix; ?>animmovedir" value="" style="width: 100px;" >
				<option value="ltrck"><?php echo __('Left to right', 'accordeon-menu-ck'); ?></option>
				<option value="rtlck"><?php echo __('Right to left', 'accordeon-menu-ck'); ?></option>
				<option value="ttbck"><?php echo __('Top to bottom', 'accordeon-menu-ck'); ?></option>
				<option value="bttck"><?php echo __('Bottom to top', 'accordeon-menu-ck'); ?></option>
			</select>
			<input class="<?php echo $prefix; ?> hasTip" title="<?php echo __('Distance', 'accordeon-menu-ck'); ?>" type="text" name="<?php echo $prefix; ?>animmovedist" id="<?php echo $prefix; ?>animmovedist" value="40" /> [px]
		</div>
		
		<div class="ckrow">
			<label for="<?php echo $prefix; ?>animrotrad"><?php echo __('Rotate', 'accordeon-menu-ck'); ?></label>
			<img class="ckicon" src="<?php echo $this->imagespath ?>/shape_rotate_clockwise.png" />
			<select class="<?php echo $prefix; ?>" type="list" name="<?php echo $prefix; ?>animrot" id="<?php echo $prefix; ?>animrot" value="" style="width: 100px;" >
				<option value="0"><?php echo __('No', 'accordeon-menu-ck'); ?></option>
				<option value="1"><?php echo __('Yes', 'accordeon-menu-ck'); ?></option>
			</select>
			<select class="<?php echo $prefix; ?>" type="list" name="<?php echo $prefix; ?>animrotrad" id="<?php echo $prefix; ?>animrotrad" value="" style="width: 100px;" >
				<option value="45">45°</option>
				<option value="90">90°</option>
				<option value="180">180°</option>
				<option value="270">270°</option>
				<option value="360">360°</option>
			</select>
		</div>
		<div class="ckrow">
			<label for="<?php echo $prefix; ?>animscale"><?php echo __('Scale', 'accordeon-menu-ck'); ?></label>
			<img class="ckicon" src="<?php echo $this->imagespath ?>/shape_handles.png" />
			<select class="<?php echo $prefix; ?>" type="list" name="<?php echo $prefix; ?>animscale" id="<?php echo $prefix; ?>animscale" value="" style="width:100px;" >
				<option value="0"><?php echo __('No', 'accordeon-menu-ck'); ?></option>
				<option value="1"><?php echo __('Yes', 'accordeon-menu-ck'); ?></option>
			</select>
		</div>
		<div class="ckrow">
			<a class="ckbutton" href="javascript:void(0)" onclick="ckPlayAnimationPreview('<?php echo $prefix; ?>')"><i class="icon icon-play"></i><?php echo __('Play animation', 'accordeon-menu-ck'); ?></a>
		</div>
	<?php
	}
}
