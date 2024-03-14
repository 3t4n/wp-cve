<?php
if(empty($this->currentMap)){
	return;
}
?>
<style type="text/css" id="gmpMapStyles_<?php echo esc_attr($this->currentMap['view_id']);?>">
	#<?php echo esc_attr($this->currentMap['view_html_id']);?> {
        width: <?php echo esc_attr($this->htmlOptions['width_full']);?>;
        height: <?php echo esc_attr($this->htmlOptions['height_full']);?>;
		<?php if(!empty($this->htmlOptions['align'])) {?>
			float: <?php echo esc_attr($this->htmlOptions['align']);?>;
		<?php }?>
		<?php if(!empty($this->htmlOptions['border'])) {?>
			border: <?php echo esc_attr($this->htmlOptions['border']);?>;
		<?php }?>
		<?php if(!empty($this->htmlOptions['margin'])) {?>
			margin: <?php echo esc_attr($this->htmlOptions['margin']);?>;
		<?php }?>
    }
	#gmapControlsNum_<?php echo esc_attr($this->currentMap['view_id']);?> {
		width:<?php echo $this->htmlOptions['width_units'] === '%' ? '100%' : esc_attr($this->htmlOptions['width_full']);?>
	}
	.gmpMapDetailsContainer#gmpMapDetailsContainer_<?php echo esc_attr($this->currentMap['view_id']);?> {
		height:<?php echo esc_attr($this->htmlOptions['height_full']);?>;
	}
	.gmp_MapPreview#<?php echo esc_attr($this->currentMap['view_html_id']);?> {
		/*position:absolute;*/
		width: 100%;
	}
	#mapConElem_<?php echo esc_attr($this->currentMap['view_id']);?>{
		width: <?php echo esc_attr($this->htmlOptions['width_full']);?>
	}
    .gm-style .gm-style-iw-c{
        padding: 12px!important;
    }
	<?php if(isset($this->currentMap['params']['infownd_title_color'])) { ?>
		#<?php echo esc_attr($this->currentMap['view_html_id']);?> .gmpInfoWindowtitle {
			color: <?php echo esc_attr($this->currentMap['params']['infownd_title_color'])?> !important;
			font-size: <?php echo esc_attr($this->currentMap['params']['infownd_title_size'])?>px !important;
		}
	<?php }?>
    <?php if(isset($this->currentMap['params']['hide_marker_tooltip']) && $this->currentMap['params']['hide_marker_tooltip']) { ?>
        .gm-style .gm-style-iw-t {
            display: none !important;
        }
    <?php }?>
</style>
