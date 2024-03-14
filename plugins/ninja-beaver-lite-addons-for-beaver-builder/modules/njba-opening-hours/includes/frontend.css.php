ul.njba-opening-times-list {
    list-style: none;
    padding-left: 0 !important;
    margin: 0;
}

.njba-opening-times-list > li {
    padding-right: 0;
    line-height: normal;
}

span.njba-opening-day {
    color: #333333;
    display: block;
    font-size: 20px;
    font-weight: bold;
    text-align: center;
}

.njba-opening-hours span {
    color: #333333;
    font-size: 16px;
    text-align: center;
}

ul.njba-opening-times-list.inline .njba-opening-day {
    float: left;
}

ul.njba-opening-times-list.inline .njba-opening-hours {
    float: right;
}

ul.njba-opening-times-list.inline .njba-opening-hours {
    display: inline-block;
}

ul.njba-opening-times-list.stacked .njba-opening-hours span {
    display: block;
}

span.njba-opening-hours-separator {
    padding: 0 10px;
}

<?php 	$number_panels = count($settings->day_panels);
		$width = 100 / $number_panels;
?>
ul.njba-opening-times-list.inline > li {
    width: 100%;
    float: left;
}

.fl-node-<?php echo $id; ?> ul.njba-opening-times-list.stacked > li {
    width: <?php echo $width ?>%;
    float: left;
    position: relative;
}

.fl-node-<?php echo $id; ?> span.njba-opening-day {
<?php if( $settings->day_color ) { ?> color: <?php echo '#'.$settings->day_color; ?>;
<?php } ?> <?php if( $settings->day_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->day_font ); ?><?php } ?> <?php if( !empty($settings->day_font_size['desktop'] )) { ?> font-size: <?php echo $settings->day_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->day_margin['top']) ) { ?> margin-top: <?php echo $settings->day_margin['top'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->day_margin['bottom'] ) ) { ?> margin-bottom: <?php echo $settings->day_margin['bottom'].'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> ul.njba-opening-times-list.inline .njba-opening-hours {
<?php if( !empty($settings->time_padding['top']) ) { ?> padding-top: <?php echo $settings->time_padding['top'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->time_padding['bottom'] ) ) { ?> padding-bottom: <?php echo $settings->time_padding['bottom'].'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> ul.njba-opening-times-list.stacked .njba-opening-hours span {
<?php if( !empty($settings->time_padding['top']) ) { ?> padding-top: <?php echo $settings->time_padding['top'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->time_padding['bottom'] ) ) { ?> padding-bottom: <?php echo $settings->time_padding['bottom'].'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-opening-hours span {
<?php if( $settings->time_color ) { ?> color: <?php echo '#'.$settings->time_color; ?>;
<?php } ?> <?php if( $settings->time_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->time_font ); ?><?php } ?> <?php if( !empty($settings->time_font_size['desktop'] )) { ?> font-size: <?php echo $settings->time_font_size['desktop'].'px'; ?>;
<?php } ?>

}

.fl-node-<?php echo $id; ?> ul.njba-opening-times-list.inline li {
<?php if( $settings->border_style !== 'none' ) { ?> border-bottom: <?php echo $settings->border_style.' '; echo $settings->border_width.'px '; echo '#'.$settings->border_color; ?>;
<?php } ?> <?php if( !empty($settings->box_padding['top']) ) { ?> padding-top: <?php echo $settings->box_padding['top'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->box_padding['bottom'] ) ) { ?> padding-bottom: <?php echo $settings->box_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->box_padding['left']) ) { ?> padding-left: <?php echo $settings->box_padding['left'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->box_padding['right'] ) ) { ?> padding-right: <?php echo $settings->box_padding['right'].'px'; ?>;
<?php } ?>

}

/*ul.njba-opening-times-list.inline li:last-child {border-bottom:none;}*/
ul.njba-opening-times-list.stacked li:before {
    content: "";
    position: absolute;
    right: 0;
    transform: translate(50%, -50%);
    top: 50%;
}

.fl-node-<?php echo $id; ?> ul.njba-opening-times-list.stacked li:last-child:before {
    background-color: transparent;
}

.fl-node-<?php echo $id; ?> ul.njba-opening-times-list.stacked li:before {
<?php if($settings->stacked_border_width !== ''){ ?> width: <?php echo $settings->stacked_border_width.'px'; ?>;
<?php } ?> <?php if($settings->stacked_border_height !== ''){ ?> height: <?php echo $settings->stacked_border_height.'px'; ?>;
<?php } ?> <?php if($settings->stacked_border_color !== ''){ ?> background-color: #<?php echo $settings->stacked_border_color; ?>;
<?php } ?>
}

@media ( max-width: 992px ) {

    .fl-node-<?php echo $id; ?> ul.njba-opening-times-list.stacked > li {
        width: 33.33%;
    }

    .fl-node-<?php echo $id; ?> span.njba-opening-day {
    <?php if( !empty($settings->day_font_size['medium'] )) { ?> font-size: <?php echo $settings->day_font_size['medium'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-opening-hours span {
    <?php if( !empty($settings->time_font_size['medium'] )) { ?> font-size: <?php echo $settings->time_font_size['medium'].'px'; ?>;
    <?php } ?>
    }
}

@media ( max-width: 768px ) {

    .fl-node-<?php echo $id; ?> ul.njba-opening-times-list.stacked > li {
        width: 33.33%;
    }

    .fl-node-<?php echo $id; ?> ul.njba-opening-times-list.inline .njba-opening-hours span {
        display: block;
    }

    .fl-node-<?php echo $id; ?> span.njba-opening-hours-separator {
        display: none !important;
    }
}

@media (max-width: 767px) {
    .fl-node-<?php echo $id; ?> ul.njba-opening-times-list.stacked > li {
        width: 50%;
    }

}

@media (max-width: 480px) {
    .fl-node-<?php echo $id; ?> span.njba-opening-day {
    <?php if( !empty($settings->day_font_size['small'] )) { ?> font-size: <?php echo $settings->day_font_size['small'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-opening-hours span {
    <?php if( !empty($settings->time_font_size['small'] )) { ?> font-size: <?php echo $settings->time_font_size['small'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> ul.njba-opening-times-list.stacked li:before {
        background-color: transparent;
    }

}
