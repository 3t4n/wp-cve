<?php
 if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="fb-6310">
<?php for($i=1;$i<=10;$i++){?>  
  <div class="fb-6310-row fb-6310_team-style-boxed"> 
    <div class="fb-padding-15 fb-border-top<?php echo esc_attr($i); ?>">	 
      <?php require_once(FLIPBOXBUILDER_DIR_PATH."template/design-".$i.".php");?>
    </div>
    <div class="fb-6310-template-list fb-border<?php echo esc_attr($i); ?> ">
      <?php esc_html_e('Template ','flipbox-builder-text-domain'); echo esc_html($i); ?>	  
	  <button type="button" <?php if($templates==$i) { ?> disabled="disabled"  <?php } ?> class="fb-btn-success wpm_choosen_style design_btn flip-button-space" id="templates_btn<?php echo esc_attr($i); ?>" onclick="select_template('<?php echo esc_attr($i); ?>')"><?php if($templates==$i){  echo "Selected"; } else { echo "Select"; } ?></button>
		<input type="radio" name="templates" id="design<?php echo esc_attr($i); ?>" value="<?php echo esc_attr($i); ?>" <?php if($templates==$i){  echo "checked"; } ?> style="display:none"/>
	</div>
   
  </div><br>
<?php } ?>
</div>