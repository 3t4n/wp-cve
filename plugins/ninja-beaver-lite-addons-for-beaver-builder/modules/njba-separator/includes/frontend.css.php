.fl-node-<?php echo $id; ?> .njba-icon {
    margin: 0 auto;
    display: table;
<?php if($settings->separator_type === 'separator_normal') {
	$width =  ($settings->separator_normal_width !== '' ? $settings->separator_normal_width.'%;' : '50%;'); ?> width: <?php echo $width;
    } ?>
}

<?php if($settings->icon_position === 'left'){ ?>
.fl-node-<?php echo $id; ?> .njba-icon-separator-left {
    text-align: left;
    margin-left: 0;
}

.fl-node-<?php echo $id; ?> .njba-divider-content.njba-divider {
    text-align: left;
    width: 20%;
}

.fl-node-<?php echo $id; ?> .njba-separator-line.njba-side-right {
    float: left;
    height: 10px;
    width: 100px;
}

<?php } ?>
<?php if($settings->icon_position === 'right'){ ?>
.fl-node-<?php echo $id; ?> .njba-icon-separator-right {
    text-align: right;
    margin-right: 0;
}

.fl-node-<?php echo $id; ?> .njba-divider-content.njba-divider {
    text-align: right;
    width: 20%;
}

.fl-node-<?php echo $id; ?> .njba-separator-line.njba-side-left {
    float: right;
    height: 10px;
    width: 100px;
}

<?php } ?>
<?php if($settings->icon_position === 'center'){ ?>
.fl-node-<?php echo $id; ?> .njba-divider-content.njba-divider {
    text-align: center;
    width: 20%;
}

<?php } ?>
.fl-node-<?php echo $id; ?> .njba-separator-line > span {
<?php if( $settings->separator_border_width ) { ?> border-top: <?php echo $settings->separator_border_width.'px'; ?>;
<?php } ?> <?php if( $settings->separator_border_color ) { ?> border-color: <?php echo '#'.$settings->separator_border_color; ?>;
<?php } ?> <?php if( $settings->separator_border_style ) { ?> border-top-style: <?php echo $settings->separator_border_style; ?>;
<?php } ?> display: block;
}

<?php if($settings->separator_type === 'separator_icon'){ ?>
.fl-node-<?php echo $id; ?> .njba-divider-content i {
<?php if( $settings->separator_icon_font_size ) { ?> font-size: <?php echo $settings->separator_icon_font_size.'px'; ?>;
<?php } ?> <?php if( $settings->separator_icon_font_color ) { ?> color: <?php echo '#'.$settings->separator_icon_font_color; ?>;
<?php } ?>
}

<?php } ?>
<?php if($settings->separator_type === 'separator_text'){ ?>
.fl-node-<?php echo $id; ?> .njba-divider-content .separator-text {
<?php if( $settings->separator_text_line_height ) { ?> line-height: <?php echo $settings->separator_text_line_height.'px'; ?>;
<?php } ?> <?php if( $settings->separator_text_font_size ) { ?> font-size: <?php echo $settings->separator_text_font_size.'px'; ?>;
<?php } ?> <?php if( $settings->separator_text_font_color ) { ?> color: <?php echo '#'.$settings->separator_text_font_color; ?>;
<?php } ?>
}

<?php } ?>
