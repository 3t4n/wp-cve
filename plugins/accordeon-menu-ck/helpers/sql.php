<?php

function accordeonmenuck_sql_install() {
	global $wpdb;
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	$table_styles_name = $wpdb->prefix . 'accordeonmenuck_styles';

	$charset_collate = $wpdb->get_charset_collate();

	// create the fonts table
	$sql = "CREATE TABLE IF NOT EXISTS $table_styles_name (
	`id` int(10) NOT NULL AUTO_INCREMENT,
	`name` text NOT NULL,
	`state` int(10) NOT NULL,
	`params` longtext NOT NULL,
	`layoutcss` text NOT NULL,
	PRIMARY KEY (`id`)
	) $charset_collate ;";

	dbDelta( $sql );
}

function accordeonmenuck_sql_install_data() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'accordeonmenuck_styles';
	// check if already stored
	$query = "SELECT * FROM " . $table_name . " WHERE id = 1";
	$result = $wpdb->get_row($query, OBJECT);
	if ($result) return;

	$wpdb->insert( 
		$table_name, 
		array( 
			'id' => '1', 
			'name' => 'Sample', 
			'state' => '1', 
			'params' => '{|qq|menustylesfontfamily|qq|:|qq||qq|,|qq|menustylestextisgfont|qq|:|qq|1|qq|,|qq|level1itemnormaltextstylesfontsize|qq|:|qq|12|qq|,|qq|level1itemnormaltextstylescolor|qq|:|qq|#636363|qq|,|qq|level1itemhovertextstylescolor|qq|:|qq|#000000|qq|,|qq|level1itemnormaltextdescstylesfontsize|qq|:|qq|10|qq|,|qq|level1itemnormaltextdescstylescolor|qq|:|qq||qq|,|qq|level1itemhovertextdescstylescolor|qq|:|qq||qq|,|qq|menustylesbgcolor1|qq|:|qq|#F0F0F0|qq|,|qq|menustylesbgcolor2|qq|:|qq|#E3E3E3|qq|,|qq|menustylesbgopacity|qq|:|qq||qq|,|qq|menustylesbgimage|qq|:|qq||qq|,|qq|menustylesbgpositionx|qq|:|qq||qq|,|qq|menustylesbgpositiony|qq|:|qq||qq|,|qq|menustylesbordertopcolor|qq|:|qq|#EFEFEF|qq|,|qq|menustylesbordertopwidth|qq|:|qq|1|qq|,|qq|menustylesbordertopstyle|qq|:|qq|solid|qq|,|qq|menustylesborderrightcolor|qq|:|qq|#EFEFEF|qq|,|qq|menustylesborderrightwidth|qq|:|qq|1|qq|,|qq|menustylesborderrightstyle|qq|:|qq|solid|qq|,|qq|menustylesborderbottomcolor|qq|:|qq|#EFEFEF|qq|,|qq|menustylesborderbottomwidth|qq|:|qq|1|qq|,|qq|menustylesborderbottomstyle|qq|:|qq|solid|qq|,|qq|menustylesborderleftcolor|qq|:|qq|#EFEFEF|qq|,|qq|menustylesborderleftwidth|qq|:|qq|1|qq|,|qq|menustylesborderleftstyle|qq|:|qq|solid|qq|,|qq|menustylesroundedcornerstl|qq|:|qq||qq|,|qq|menustylesroundedcornerstr|qq|:|qq||qq|,|qq|menustylesroundedcornersbr|qq|:|qq||qq|,|qq|menustylesroundedcornersbl|qq|:|qq||qq|,|qq|menustylesshadowcolor|qq|:|qq|#444444|qq|,|qq|menustylesshadowblur|qq|:|qq|3|qq|,|qq|menustylesshadowspread|qq|:|qq||qq|,|qq|menustylesshadowoffsetx|qq|:|qq||qq|,|qq|menustylesshadowoffsety|qq|:|qq||qq|,|qq|menustylesmargintop|qq|:|qq||qq|,|qq|menustylesmarginright|qq|:|qq||qq|,|qq|menustylesmarginbottom|qq|:|qq||qq|,|qq|menustylesmarginleft|qq|:|qq||qq|,|qq|menustylespaddingtop|qq|:|qq|5|qq|,|qq|menustylespaddingright|qq|:|qq|5|qq|,|qq|menustylespaddingbottom|qq|:|qq|5|qq|,|qq|menustylespaddingleft|qq|:|qq|5|qq|,|qq|level1itemgroupbgcolor1|qq|:|qq||qq|,|qq|level1itemgroupbgcolor2|qq|:|qq||qq|,|qq|level1itemgroupbgopacity|qq|:|qq||qq|,|qq|level1itemgroupbgimage|qq|:|qq||qq|,|qq|level1itemgroupbgpositionx|qq|:|qq||qq|,|qq|level1itemgroupbgpositiony|qq|:|qq||qq|,|qq|level1itemgroupbordertopcolor|qq|:|qq||qq|,|qq|level1itemgroupbordertopwidth|qq|:|qq||qq|,|qq|level1itemgroupbordertopstyle|qq|:|qq|solid|qq|,|qq|level1itemgroupborderrightcolor|qq|:|qq||qq|,|qq|level1itemgroupborderrightwidth|qq|:|qq||qq|,|qq|level1itemgroupborderrightstyle|qq|:|qq|solid|qq|,|qq|level1itemgroupborderbottomcolor|qq|:|qq||qq|,|qq|level1itemgroupborderbottomwidth|qq|:|qq||qq|,|qq|level1itemgroupborderbottomstyle|qq|:|qq|solid|qq|,|qq|level1itemgroupborderleftcolor|qq|:|qq||qq|,|qq|level1itemgroupborderleftwidth|qq|:|qq||qq|,|qq|level1itemgroupborderleftstyle|qq|:|qq|solid|qq|,|qq|level1itemgrouproundedcornerstl|qq|:|qq||qq|,|qq|level1itemgrouproundedcornerstr|qq|:|qq||qq|,|qq|level1itemgrouproundedcornersbr|qq|:|qq||qq|,|qq|level1itemgrouproundedcornersbl|qq|:|qq||qq|,|qq|level1itemgroupshadowcolor|qq|:|qq||qq|,|qq|level1itemgroupshadowblur|qq|:|qq||qq|,|qq|level1itemgroupshadowspread|qq|:|qq||qq|,|qq|level1itemgroupshadowoffsetx|qq|:|qq||qq|,|qq|level1itemgroupshadowoffsety|qq|:|qq||qq|,|qq|level1itemgroupmargintop|qq|:|qq||qq|,|qq|level1itemgroupmarginright|qq|:|qq||qq|,|qq|level1itemgroupmarginbottom|qq|:|qq||qq|,|qq|level1itemgroupmarginleft|qq|:|qq||qq|,|qq|level1itemgrouppaddingtop|qq|:|qq||qq|,|qq|level1itemgrouppaddingright|qq|:|qq||qq|,|qq|level1itemgrouppaddingbottom|qq|:|qq||qq|,|qq|level1itemgrouppaddingleft|qq|:|qq||qq|,|qq|level1itemnormaltextstylestextshadowcolor|qq|:|qq||qq|,|qq|level1itemnormaltextstylestextshadowblur|qq|:|qq||qq|,|qq|level1itemnormaltextstylestextshadowoffsetx|qq|:|qq||qq|,|qq|level1itemnormaltextstylestextshadowoffsety|qq|:|qq||qq|,|qq|level1itemnormalstylesbgcolor1|qq|:|qq||qq|,|qq|level1itemnormalstylesbgcolor2|qq|:|qq||qq|,|qq|level1itemnormalstylesbgopacity|qq|:|qq||qq|,|qq|level1itemnormalstylesbgimage|qq|:|qq||qq|,|qq|level1itemnormalstylesbgpositionx|qq|:|qq||qq|,|qq|level1itemnormalstylesbgpositiony|qq|:|qq||qq|,|qq|level1itemnormalstylesbordertopcolor|qq|:|qq||qq|,|qq|level1itemnormalstylesbordertopwidth|qq|:|qq||qq|,|qq|level1itemnormalstylesbordertopstyle|qq|:|qq|solid|qq|,|qq|level1itemnormalstylesborderrightcolor|qq|:|qq||qq|,|qq|level1itemnormalstylesborderrightwidth|qq|:|qq||qq|,|qq|level1itemnormalstylesborderrightstyle|qq|:|qq|solid|qq|,|qq|level1itemnormalstylesborderbottomcolor|qq|:|qq||qq|,|qq|level1itemnormalstylesborderbottomwidth|qq|:|qq||qq|,|qq|level1itemnormalstylesborderbottomstyle|qq|:|qq|solid|qq|,|qq|level1itemnormalstylesborderleftcolor|qq|:|qq||qq|,|qq|level1itemnormalstylesborderleftwidth|qq|:|qq||qq|,|qq|level1itemnormalstylesborderleftstyle|qq|:|qq|solid|qq|,|qq|level1itemnormalstylesroundedcornerstl|qq|:|qq||qq|,|qq|level1itemnormalstylesroundedcornerstr|qq|:|qq||qq|,|qq|level1itemnormalstylesroundedcornersbr|qq|:|qq||qq|,|qq|level1itemnormalstylesroundedcornersbl|qq|:|qq||qq|,|qq|level1itemnormalstylesshadowcolor|qq|:|qq||qq|,|qq|level1itemnormalstylesshadowblur|qq|:|qq||qq|,|qq|level1itemnormalstylesshadowspread|qq|:|qq||qq|,|qq|level1itemnormalstylesshadowoffsetx|qq|:|qq||qq|,|qq|level1itemnormalstylesshadowoffsety|qq|:|qq||qq|,|qq|level1itemnormalstylesmargintop|qq|:|qq||qq|,|qq|level1itemnormalstylesmarginright|qq|:|qq||qq|,|qq|level1itemnormalstylesmarginbottom|qq|:|qq||qq|,|qq|level1itemnormalstylesmarginleft|qq|:|qq||qq|,|qq|level1itemnormaltextstylespaddingtop|qq|:|qq|5|qq|,|qq|level1itemnormaltextstylespaddingright|qq|:|qq|5|qq|,|qq|level1itemnormaltextstylespaddingbottom|qq|:|qq|5|qq|,|qq|level1itemnormaltextstylespaddingleft|qq|:|qq|5|qq|,|qq|level1itemhovertextstylestextshadowcolor|qq|:|qq||qq|,|qq|level1itemhovertextstylestextshadowblur|qq|:|qq||qq|,|qq|level1itemhovertextstylestextshadowoffsetx|qq|:|qq||qq|,|qq|level1itemhovertextstylestextshadowoffsety|qq|:|qq||qq|,|qq|level1itemhoverstylesbgcolor1|qq|:|qq||qq|,|qq|level1itemhoverstylesbgcolor2|qq|:|qq||qq|,|qq|level1itemhoverstylesbgopacity|qq|:|qq||qq|,|qq|level1itemhoverstylesbgimage|qq|:|qq||qq|,|qq|level1itemhoverstylesbgpositionx|qq|:|qq||qq|,|qq|level1itemhoverstylesbgpositiony|qq|:|qq||qq|,|qq|level1itemhoverstylesbordertopcolor|qq|:|qq||qq|,|qq|level1itemhoverstylesbordertopwidth|qq|:|qq||qq|,|qq|level1itemhoverstylesbordertopstyle|qq|:|qq|solid|qq|,|qq|level1itemhoverstylesborderrightcolor|qq|:|qq||qq|,|qq|level1itemhoverstylesborderrightwidth|qq|:|qq||qq|,|qq|level1itemhoverstylesborderrightstyle|qq|:|qq|solid|qq|,|qq|level1itemhoverstylesborderbottomcolor|qq|:|qq||qq|,|qq|level1itemhoverstylesborderbottomwidth|qq|:|qq||qq|,|qq|level1itemhoverstylesborderbottomstyle|qq|:|qq|solid|qq|,|qq|level1itemhoverstylesborderleftcolor|qq|:|qq||qq|,|qq|level1itemhoverstylesborderleftwidth|qq|:|qq||qq|,|qq|level1itemhoverstylesborderleftstyle|qq|:|qq|solid|qq|,|qq|level1itemhoverstylesroundedcornerstl|qq|:|qq||qq|,|qq|level1itemhoverstylesroundedcornerstr|qq|:|qq||qq|,|qq|level1itemhoverstylesroundedcornersbr|qq|:|qq||qq|,|qq|level1itemhoverstylesroundedcornersbl|qq|:|qq||qq|,|qq|level1itemhoverstylesshadowcolor|qq|:|qq||qq|,|qq|level1itemhoverstylesshadowblur|qq|:|qq||qq|,|qq|level1itemhoverstylesshadowspread|qq|:|qq||qq|,|qq|level1itemhoverstylesshadowoffsetx|qq|:|qq||qq|,|qq|level1itemhoverstylesshadowoffsety|qq|:|qq||qq|,|qq|level1itemhoverstylesmargintop|qq|:|qq||qq|,|qq|level1itemhoverstylesmarginright|qq|:|qq||qq|,|qq|level1itemhoverstylesmarginbottom|qq|:|qq||qq|,|qq|level1itemhoverstylesmarginleft|qq|:|qq||qq|,|qq|level1itemhovertextstylespaddingtop|qq|:|qq||qq|,|qq|level1itemhovertextstylespaddingright|qq|:|qq||qq|,|qq|level1itemhovertextstylespaddingbottom|qq|:|qq||qq|,|qq|level1itemhovertextstylespaddingleft|qq|:|qq||qq|,|qq|menustylesimageplus|qq|:|qq|'.plugins_url().'/accordeon-menu-ck/images/plus.png|qq|,|qq|menustylesimageminus|qq|:|qq|'.plugins_url().'/accordeon-menu-ck/images/minus.png|qq|,|qq|menustylesparentarrowwidth|qq|:|qq|20|qq|,|qq|level2menustylesfontfamily|qq|:|qq||qq|,|qq|level2menustylestextisgfont|qq|:|qq||qq|,|qq|level2itemnormaltextstylesfontsize|qq|:|qq||qq|,|qq|level2itemnormaltextstylescolor|qq|:|qq|#636363|qq|,|qq|level2itemhovertextstylescolor|qq|:|qq|#000000|qq|,|qq|level2itemnormaltextdescstylesfontsize|qq|:|qq||qq|,|qq|level2itemnormaltextdescstylescolor|qq|:|qq||qq|,|qq|level2itemhovertextdescstylescolor|qq|:|qq||qq|,|qq|level2menustylesbgcolor1|qq|:|qq||qq|,|qq|level2menustylesbgcolor2|qq|:|qq||qq|,|qq|level2menustylesbgopacity|qq|:|qq||qq|,|qq|level2menustylesbgimage|qq|:|qq||qq|,|qq|level2menustylesbgpositionx|qq|:|qq||qq|,|qq|level2menustylesbgpositiony|qq|:|qq||qq|,|qq|level2menustylesbordertopcolor|qq|:|qq||qq|,|qq|level2menustylesbordertopwidth|qq|:|qq||qq|,|qq|level2menustylesbordertopstyle|qq|:|qq|solid|qq|,|qq|level2menustylesborderrightcolor|qq|:|qq||qq|,|qq|level2menustylesborderrightwidth|qq|:|qq||qq|,|qq|level2menustylesborderrightstyle|qq|:|qq|solid|qq|,|qq|level2menustylesborderbottomcolor|qq|:|qq||qq|,|qq|level2menustylesborderbottomwidth|qq|:|qq||qq|,|qq|level2menustylesborderbottomstyle|qq|:|qq|solid|qq|,|qq|level2menustylesborderleftcolor|qq|:|qq||qq|,|qq|level2menustylesborderleftwidth|qq|:|qq||qq|,|qq|level2menustylesborderleftstyle|qq|:|qq|solid|qq|,|qq|level2menustylesroundedcornerstl|qq|:|qq||qq|,|qq|level2menustylesroundedcornerstr|qq|:|qq||qq|,|qq|level2menustylesroundedcornersbr|qq|:|qq||qq|,|qq|level2menustylesroundedcornersbl|qq|:|qq||qq|,|qq|level2menustylesshadowcolor|qq|:|qq||qq|,|qq|level2menustylesshadowblur|qq|:|qq||qq|,|qq|level2menustylesshadowspread|qq|:|qq||qq|,|qq|level2menustylesshadowoffsetx|qq|:|qq||qq|,|qq|level2menustylesshadowoffsety|qq|:|qq||qq|,|qq|level2menustylesshadowinset|qq|:|qq|1|qq|,|qq|level2menustylesmargintop|qq|:|qq||qq|,|qq|level2menustylesmarginright|qq|:|qq||qq|,|qq|level2menustylesmarginbottom|qq|:|qq||qq|,|qq|level2menustylesmarginleft|qq|:|qq||qq|,|qq|level2menustylespaddingtop|qq|:|qq||qq|,|qq|level2menustylespaddingright|qq|:|qq||qq|,|qq|level2menustylespaddingbottom|qq|:|qq||qq|,|qq|level2menustylespaddingleft|qq|:|qq||qq|,|qq|level2itemgroupbgcolor1|qq|:|qq||qq|,|qq|level2itemgroupbgcolor2|qq|:|qq||qq|,|qq|level2itemgroupbgopacity|qq|:|qq||qq|,|qq|level2itemgroupbgimage|qq|:|qq||qq|,|qq|level2itemgroupbgpositionx|qq|:|qq||qq|,|qq|level2itemgroupbgpositiony|qq|:|qq||qq|,|qq|level2itemgroupbordertopcolor|qq|:|qq||qq|,|qq|level2itemgroupbordertopwidth|qq|:|qq||qq|,|qq|level2itemgroupbordertopstyle|qq|:|qq|solid|qq|,|qq|level2itemgroupborderrightcolor|qq|:|qq||qq|,|qq|level2itemgroupborderrightwidth|qq|:|qq||qq|,|qq|level2itemgroupborderrightstyle|qq|:|qq|solid|qq|,|qq|level2itemgroupborderbottomcolor|qq|:|qq||qq|,|qq|level2itemgroupborderbottomwidth|qq|:|qq||qq|,|qq|level2itemgroupborderbottomstyle|qq|:|qq|solid|qq|,|qq|level2itemgroupborderleftcolor|qq|:|qq||qq|,|qq|level2itemgroupborderleftwidth|qq|:|qq||qq|,|qq|level2itemgroupborderleftstyle|qq|:|qq|solid|qq|,|qq|level2itemgrouproundedcornerstl|qq|:|qq||qq|,|qq|level2itemgrouproundedcornerstr|qq|:|qq||qq|,|qq|level2itemgrouproundedcornersbr|qq|:|qq||qq|,|qq|level2itemgrouproundedcornersbl|qq|:|qq||qq|,|qq|level2itemgroupshadowcolor|qq|:|qq||qq|,|qq|level2itemgroupshadowblur|qq|:|qq||qq|,|qq|level2itemgroupshadowspread|qq|:|qq||qq|,|qq|level2itemgroupshadowoffsetx|qq|:|qq||qq|,|qq|level2itemgroupshadowoffsety|qq|:|qq||qq|,|qq|level2itemgroupmargintop|qq|:|qq||qq|,|qq|level2itemgroupmarginright|qq|:|qq||qq|,|qq|level2itemgroupmarginbottom|qq|:|qq||qq|,|qq|level2itemgroupmarginleft|qq|:|qq||qq|,|qq|level2itemgrouppaddingtop|qq|:|qq||qq|,|qq|level2itemgrouppaddingright|qq|:|qq||qq|,|qq|level2itemgrouppaddingbottom|qq|:|qq||qq|,|qq|level2itemgrouppaddingleft|qq|:|qq||qq|,|qq|level2itemnormaltextstylestextshadowcolor|qq|:|qq||qq|,|qq|level2itemnormaltextstylestextshadowblur|qq|:|qq||qq|,|qq|level2itemnormaltextstylestextshadowoffsetx|qq|:|qq||qq|,|qq|level2itemnormaltextstylestextshadowoffsety|qq|:|qq||qq|,|qq|level2itemnormalstylesbgcolor1|qq|:|qq||qq|,|qq|level2itemnormalstylesbgcolor2|qq|:|qq||qq|,|qq|level2itemnormalstylesbgopacity|qq|:|qq||qq|,|qq|level2itemnormalstylesbgimage|qq|:|qq||qq|,|qq|level2itemnormalstylesbgpositionx|qq|:|qq||qq|,|qq|level2itemnormalstylesbgpositiony|qq|:|qq||qq|,|qq|level2itemnormalstylesbordertopcolor|qq|:|qq||qq|,|qq|level2itemnormalstylesbordertopwidth|qq|:|qq||qq|,|qq|level2itemnormalstylesbordertopstyle|qq|:|qq|solid|qq|,|qq|level2itemnormalstylesborderrightcolor|qq|:|qq||qq|,|qq|level2itemnormalstylesborderrightwidth|qq|:|qq||qq|,|qq|level2itemnormalstylesborderrightstyle|qq|:|qq|solid|qq|,|qq|level2itemnormalstylesborderbottomcolor|qq|:|qq||qq|,|qq|level2itemnormalstylesborderbottomwidth|qq|:|qq||qq|,|qq|level2itemnormalstylesborderbottomstyle|qq|:|qq|solid|qq|,|qq|level2itemnormalstylesborderleftcolor|qq|:|qq||qq|,|qq|level2itemnormalstylesborderleftwidth|qq|:|qq||qq|,|qq|level2itemnormalstylesborderleftstyle|qq|:|qq|solid|qq|,|qq|level2itemnormalstylesroundedcornerstl|qq|:|qq||qq|,|qq|level2itemnormalstylesroundedcornerstr|qq|:|qq||qq|,|qq|level2itemnormalstylesroundedcornersbr|qq|:|qq||qq|,|qq|level2itemnormalstylesroundedcornersbl|qq|:|qq||qq|,|qq|level2itemnormalstylesshadowcolor|qq|:|qq||qq|,|qq|level2itemnormalstylesshadowblur|qq|:|qq||qq|,|qq|level2itemnormalstylesshadowspread|qq|:|qq||qq|,|qq|level2itemnormalstylesshadowoffsetx|qq|:|qq||qq|,|qq|level2itemnormalstylesshadowoffsety|qq|:|qq||qq|,|qq|level2itemnormalstylesmargintop|qq|:|qq||qq|,|qq|level2itemnormalstylesmarginright|qq|:|qq||qq|,|qq|level2itemnormalstylesmarginbottom|qq|:|qq||qq|,|qq|level2itemnormalstylesmarginleft|qq|:|qq||qq|,|qq|level2itemnormaltextstylespaddingtop|qq|:|qq|5|qq|,|qq|level2itemnormaltextstylespaddingright|qq|:|qq||qq|,|qq|level2itemnormaltextstylespaddingbottom|qq|:|qq|5|qq|,|qq|level2itemnormaltextstylespaddingleft|qq|:|qq|15|qq|,|qq|level2itemhovertextstylestextshadowcolor|qq|:|qq||qq|,|qq|level2itemhovertextstylestextshadowblur|qq|:|qq||qq|,|qq|level2itemhovertextstylestextshadowoffsetx|qq|:|qq||qq|,|qq|level2itemhovertextstylestextshadowoffsety|qq|:|qq||qq|,|qq|level2itemhoverstylesbgcolor1|qq|:|qq||qq|,|qq|level2itemhoverstylesbgcolor2|qq|:|qq||qq|,|qq|level2itemhoverstylesbgopacity|qq|:|qq||qq|,|qq|level2itemhoverstylesbgimage|qq|:|qq||qq|,|qq|level2itemhoverstylesbgpositionx|qq|:|qq||qq|,|qq|level2itemhoverstylesbgpositiony|qq|:|qq||qq|,|qq|level2itemhoverstylesbordertopcolor|qq|:|qq||qq|,|qq|level2itemhoverstylesbordertopwidth|qq|:|qq||qq|,|qq|level2itemhoverstylesbordertopstyle|qq|:|qq|solid|qq|,|qq|level2itemhoverstylesborderrightcolor|qq|:|qq||qq|,|qq|level2itemhoverstylesborderrightwidth|qq|:|qq||qq|,|qq|level2itemhoverstylesborderrightstyle|qq|:|qq|solid|qq|,|qq|level2itemhoverstylesborderbottomcolor|qq|:|qq||qq|,|qq|level2itemhoverstylesborderbottomwidth|qq|:|qq||qq|,|qq|level2itemhoverstylesborderbottomstyle|qq|:|qq|solid|qq|,|qq|level2itemhoverstylesborderleftcolor|qq|:|qq||qq|,|qq|level2itemhoverstylesborderleftwidth|qq|:|qq||qq|,|qq|level2itemhoverstylesborderleftstyle|qq|:|qq|solid|qq|,|qq|level2itemhoverstylesroundedcornerstl|qq|:|qq||qq|,|qq|level2itemhoverstylesroundedcornerstr|qq|:|qq||qq|,|qq|level2itemhoverstylesroundedcornersbr|qq|:|qq||qq|,|qq|level2itemhoverstylesroundedcornersbl|qq|:|qq||qq|,|qq|level2itemhoverstylesshadowcolor|qq|:|qq||qq|,|qq|level2itemhoverstylesshadowblur|qq|:|qq||qq|,|qq|level2itemhoverstylesshadowspread|qq|:|qq||qq|,|qq|level2itemhoverstylesshadowoffsetx|qq|:|qq||qq|,|qq|level2itemhoverstylesshadowoffsety|qq|:|qq||qq|,|qq|level2itemhoverstylesmargintop|qq|:|qq||qq|,|qq|level2itemhoverstylesmarginright|qq|:|qq||qq|,|qq|level2itemhoverstylesmarginbottom|qq|:|qq||qq|,|qq|level2itemhoverstylesmarginleft|qq|:|qq||qq|,|qq|level2itemhovertextstylespaddingtop|qq|:|qq||qq|,|qq|level2itemhovertextstylespaddingright|qq|:|qq||qq|,|qq|level2itemhovertextstylespaddingbottom|qq|:|qq||qq|,|qq|level2itemhovertextstylespaddingleft|qq|:|qq||qq|,|qq|level2menustylesimageplus|qq|:|qq||qq|,|qq|level2menustylesimageminus|qq|:|qq||qq|,|qq|level2menustylesparentarrowwidth|qq|:|qq||qq|,|qq|level3menustylesfontfamily|qq|:|qq||qq|,|qq|level3menustylestextisgfont|qq|:|qq||qq|,|qq|level3itemnormaltextstylesfontsize|qq|:|qq||qq|,|qq|level3itemnormaltextstylescolor|qq|:|qq|#636363|qq|,|qq|level3itemhovertextstylescolor|qq|:|qq|#000000|qq|,|qq|level3itemnormaltextdescstylesfontsize|qq|:|qq||qq|,|qq|level3itemnormaltextdescstylescolor|qq|:|qq||qq|,|qq|level3itemhovertextdescstylescolor|qq|:|qq||qq|,|qq|level3menustylesbgcolor1|qq|:|qq||qq|,|qq|level3menustylesbgcolor2|qq|:|qq||qq|,|qq|level3menustylesbgopacity|qq|:|qq||qq|,|qq|level3menustylesbgimage|qq|:|qq||qq|,|qq|level3menustylesbgpositionx|qq|:|qq||qq|,|qq|level3menustylesbgpositiony|qq|:|qq||qq|,|qq|level3menustylesbordertopcolor|qq|:|qq||qq|,|qq|level3menustylesbordertopwidth|qq|:|qq|1|qq|,|qq|level3menustylesbordertopstyle|qq|:|qq|solid|qq|,|qq|level3menustylesborderrightcolor|qq|:|qq||qq|,|qq|level3menustylesborderrightwidth|qq|:|qq||qq|,|qq|level3menustylesborderrightstyle|qq|:|qq|solid|qq|,|qq|level3menustylesborderbottomcolor|qq|:|qq||qq|,|qq|level3menustylesborderbottomwidth|qq|:|qq|1|qq|,|qq|level3menustylesborderbottomstyle|qq|:|qq|solid|qq|,|qq|level3menustylesborderleftcolor|qq|:|qq||qq|,|qq|level3menustylesborderleftwidth|qq|:|qq||qq|,|qq|level3menustylesborderleftstyle|qq|:|qq|solid|qq|,|qq|level3menustylesroundedcornerstl|qq|:|qq||qq|,|qq|level3menustylesroundedcornerstr|qq|:|qq||qq|,|qq|level3menustylesroundedcornersbr|qq|:|qq||qq|,|qq|level3menustylesroundedcornersbl|qq|:|qq||qq|,|qq|level3menustylesshadowcolor|qq|:|qq||qq|,|qq|level3menustylesshadowblur|qq|:|qq||qq|,|qq|level3menustylesshadowspread|qq|:|qq||qq|,|qq|level3menustylesshadowoffsetx|qq|:|qq||qq|,|qq|level3menustylesshadowoffsety|qq|:|qq||qq|,|qq|level3menustylesmargintop|qq|:|qq||qq|,|qq|level3menustylesmarginright|qq|:|qq||qq|,|qq|level3menustylesmarginbottom|qq|:|qq||qq|,|qq|level3menustylesmarginleft|qq|:|qq||qq|,|qq|level3menustylespaddingtop|qq|:|qq||qq|,|qq|level3menustylespaddingright|qq|:|qq||qq|,|qq|level3menustylespaddingbottom|qq|:|qq||qq|,|qq|level3menustylespaddingleft|qq|:|qq||qq|,|qq|level3itemgroupbgcolor1|qq|:|qq||qq|,|qq|level3itemgroupbgcolor2|qq|:|qq||qq|,|qq|level3itemgroupbgopacity|qq|:|qq||qq|,|qq|level3itemgroupbgimage|qq|:|qq||qq|,|qq|level3itemgroupbgpositionx|qq|:|qq||qq|,|qq|level3itemgroupbgpositiony|qq|:|qq||qq|,|qq|level3itemgroupbordertopcolor|qq|:|qq||qq|,|qq|level3itemgroupbordertopwidth|qq|:|qq||qq|,|qq|level3itemgroupbordertopstyle|qq|:|qq|solid|qq|,|qq|level3itemgroupborderrightcolor|qq|:|qq||qq|,|qq|level3itemgroupborderrightwidth|qq|:|qq||qq|,|qq|level3itemgroupborderrightstyle|qq|:|qq|solid|qq|,|qq|level3itemgroupborderbottomcolor|qq|:|qq||qq|,|qq|level3itemgroupborderbottomwidth|qq|:|qq||qq|,|qq|level3itemgroupborderbottomstyle|qq|:|qq|solid|qq|,|qq|level3itemgroupborderleftcolor|qq|:|qq||qq|,|qq|level3itemgroupborderleftwidth|qq|:|qq||qq|,|qq|level3itemgroupborderleftstyle|qq|:|qq|solid|qq|,|qq|level3itemgrouproundedcornerstl|qq|:|qq||qq|,|qq|level3itemgrouproundedcornerstr|qq|:|qq||qq|,|qq|level3itemgrouproundedcornersbr|qq|:|qq||qq|,|qq|level3itemgrouproundedcornersbl|qq|:|qq||qq|,|qq|level3itemgroupshadowcolor|qq|:|qq||qq|,|qq|level3itemgroupshadowblur|qq|:|qq||qq|,|qq|level3itemgroupshadowspread|qq|:|qq||qq|,|qq|level3itemgroupshadowoffsetx|qq|:|qq||qq|,|qq|level3itemgroupshadowoffsety|qq|:|qq||qq|,|qq|level3itemgroupmargintop|qq|:|qq||qq|,|qq|level3itemgroupmarginright|qq|:|qq||qq|,|qq|level3itemgroupmarginbottom|qq|:|qq||qq|,|qq|level3itemgroupmarginleft|qq|:|qq||qq|,|qq|level3itemgrouppaddingtop|qq|:|qq||qq|,|qq|level3itemgrouppaddingright|qq|:|qq||qq|,|qq|level3itemgrouppaddingbottom|qq|:|qq||qq|,|qq|level3itemgrouppaddingleft|qq|:|qq||qq|,|qq|level3itemnormaltextstylestextshadowcolor|qq|:|qq||qq|,|qq|level3itemnormaltextstylestextshadowblur|qq|:|qq||qq|,|qq|level3itemnormaltextstylestextshadowoffsetx|qq|:|qq||qq|,|qq|level3itemnormaltextstylestextshadowoffsety|qq|:|qq||qq|,|qq|level3itemnormalstylesbgcolor1|qq|:|qq||qq|,|qq|level3itemnormalstylesbgcolor2|qq|:|qq||qq|,|qq|level3itemnormalstylesbgopacity|qq|:|qq||qq|,|qq|level3itemnormalstylesbgimage|qq|:|qq||qq|,|qq|level3itemnormalstylesbgpositionx|qq|:|qq||qq|,|qq|level3itemnormalstylesbgpositiony|qq|:|qq||qq|,|qq|level3itemnormalstylesbordertopcolor|qq|:|qq||qq|,|qq|level3itemnormalstylesbordertopwidth|qq|:|qq||qq|,|qq|level3itemnormalstylesbordertopstyle|qq|:|qq|solid|qq|,|qq|level3itemnormalstylesborderrightcolor|qq|:|qq||qq|,|qq|level3itemnormalstylesborderrightwidth|qq|:|qq||qq|,|qq|level3itemnormalstylesborderrightstyle|qq|:|qq|solid|qq|,|qq|level3itemnormalstylesborderbottomcolor|qq|:|qq||qq|,|qq|level3itemnormalstylesborderbottomwidth|qq|:|qq||qq|,|qq|level3itemnormalstylesborderbottomstyle|qq|:|qq|solid|qq|,|qq|level3itemnormalstylesborderleftcolor|qq|:|qq||qq|,|qq|level3itemnormalstylesborderleftwidth|qq|:|qq||qq|,|qq|level3itemnormalstylesborderleftstyle|qq|:|qq|solid|qq|,|qq|level3itemnormalstylesroundedcornerstl|qq|:|qq||qq|,|qq|level3itemnormalstylesroundedcornerstr|qq|:|qq||qq|,|qq|level3itemnormalstylesroundedcornersbr|qq|:|qq||qq|,|qq|level3itemnormalstylesroundedcornersbl|qq|:|qq||qq|,|qq|level3itemnormalstylesshadowcolor|qq|:|qq||qq|,|qq|level3itemnormalstylesshadowblur|qq|:|qq||qq|,|qq|level3itemnormalstylesshadowspread|qq|:|qq||qq|,|qq|level3itemnormalstylesshadowoffsetx|qq|:|qq||qq|,|qq|level3itemnormalstylesshadowoffsety|qq|:|qq||qq|,|qq|level3itemnormalstylesmargintop|qq|:|qq||qq|,|qq|level3itemnormalstylesmarginright|qq|:|qq||qq|,|qq|level3itemnormalstylesmarginbottom|qq|:|qq||qq|,|qq|level3itemnormalstylesmarginleft|qq|:|qq||qq|,|qq|level3itemnormaltextstylespaddingtop|qq|:|qq|2|qq|,|qq|level3itemnormaltextstylespaddingright|qq|:|qq||qq|,|qq|level3itemnormaltextstylespaddingbottom|qq|:|qq|2|qq|,|qq|level3itemnormaltextstylespaddingleft|qq|:|qq|20|qq|,|qq|level3itemhovertextstylestextshadowcolor|qq|:|qq||qq|,|qq|level3itemhovertextstylestextshadowblur|qq|:|qq||qq|,|qq|level3itemhovertextstylestextshadowoffsetx|qq|:|qq||qq|,|qq|level3itemhovertextstylestextshadowoffsety|qq|:|qq||qq|,|qq|level3itemhoverstylesbgcolor1|qq|:|qq||qq|,|qq|level3itemhoverstylesbgcolor2|qq|:|qq||qq|,|qq|level3itemhoverstylesbgopacity|qq|:|qq||qq|,|qq|level3itemhoverstylesbgimage|qq|:|qq||qq|,|qq|level3itemhoverstylesbgpositionx|qq|:|qq||qq|,|qq|level3itemhoverstylesbgpositiony|qq|:|qq||qq|,|qq|level3itemhoverstylesbordertopcolor|qq|:|qq||qq|,|qq|level3itemhoverstylesbordertopwidth|qq|:|qq||qq|,|qq|level3itemhoverstylesbordertopstyle|qq|:|qq|solid|qq|,|qq|level3itemhoverstylesborderrightcolor|qq|:|qq||qq|,|qq|level3itemhoverstylesborderrightwidth|qq|:|qq||qq|,|qq|level3itemhoverstylesborderrightstyle|qq|:|qq|solid|qq|,|qq|level3itemhoverstylesborderbottomcolor|qq|:|qq||qq|,|qq|level3itemhoverstylesborderbottomwidth|qq|:|qq||qq|,|qq|level3itemhoverstylesborderbottomstyle|qq|:|qq|solid|qq|,|qq|level3itemhoverstylesborderleftcolor|qq|:|qq||qq|,|qq|level3itemhoverstylesborderleftwidth|qq|:|qq||qq|,|qq|level3itemhoverstylesborderleftstyle|qq|:|qq|solid|qq|,|qq|level3itemhoverstylesroundedcornerstl|qq|:|qq||qq|,|qq|level3itemhoverstylesroundedcornerstr|qq|:|qq||qq|,|qq|level3itemhoverstylesroundedcornersbr|qq|:|qq||qq|,|qq|level3itemhoverstylesroundedcornersbl|qq|:|qq||qq|,|qq|level3itemhoverstylesshadowcolor|qq|:|qq||qq|,|qq|level3itemhoverstylesshadowblur|qq|:|qq||qq|,|qq|level3itemhoverstylesshadowspread|qq|:|qq||qq|,|qq|level3itemhoverstylesshadowoffsetx|qq|:|qq||qq|,|qq|level3itemhoverstylesshadowoffsety|qq|:|qq||qq|,|qq|level3itemhoverstylesmargintop|qq|:|qq||qq|,|qq|level3itemhoverstylesmarginright|qq|:|qq||qq|,|qq|level3itemhoverstylesmarginbottom|qq|:|qq||qq|,|qq|level3itemhoverstylesmarginleft|qq|:|qq||qq|,|qq|level3itemhovertextstylespaddingtop|qq|:|qq||qq|,|qq|level3itemhovertextstylespaddingright|qq|:|qq||qq|,|qq|level3itemhovertextstylespaddingbottom|qq|:|qq||qq|,|qq|level3itemhovertextstylespaddingleft|qq|:|qq||qq|,|qq|level3menustylesimageplus|qq|:|qq||qq|,|qq|level3menustylesimageminus|qq|:|qq||qq|,|qq|level3menustylesparentarrowwidth|qq|:|qq||qq|,|qq|customcss|qq|:|qq||qq|}', 
			'layoutcss' => '|ID| { margin:0;padding:0; }
|ID| .accordeonck_desc { display:block; }
|ID| li.accordeonck { list-style: none;overflow: hidden; }
|ID| ul[class^=|qq|content|qq|] { margin:0;padding:0;width:auto; }
|ID| li.accordeonck > span { position: relative; display: block; }
|ID| li.accordeonck.parent > span { padding-right: 20px;}
|ID| li.parent > span span.toggler_icon { position: absolute; cursor: pointer; display: block; height: 100%; z-index: 10;right:0; background: url('.plugins_url().'/accordeon-menu-ck/images/plus.png) center center no-repeat !important;width: 20px;}
|ID| li.parent.open > span span.toggler_icon { right:0; background: url('.plugins_url().'/accordeon-menu-ck/images/minus.png) center center no-repeat !important;}
|ID| li.accordeonck.level2 > span { padding-right: 0px;}
|ID| li.level3 li.accordeonck > span { padding-right: 0px;}
|ID| a.accordeonck { display: block;text-decoration: none; }
|ID| a.accordeonck:hover { text-decoration: none; }
|ID| li.parent > span a { display: block;outline: none; }
|ID| li.parent.open > span a {  }
|ID|  {
	background: #F0F0F0;
	background-image: -o-linear-gradient(center top,#F0F0F0, #E3E3E3 100%);
	background-image: -webkit-gradient(linear, left top, left bottom,from(#F0F0F0), color-stop(100%, #E3E3E3));
	background-image: -moz-linear-gradient(center top,#F0F0F0, #E3E3E3 100%);
	background-image: linear-gradient(to bottom,#F0F0F0, #E3E3E3 100%);
	border-top: #EFEFEF 1px solid;
	border-bottom: #EFEFEF 1px solid;
	border-left: #EFEFEF 1px solid;
	border-right: #EFEFEF 1px solid;
	padding-top: 5px;
	padding-right: 5px;
	padding-bottom: 5px;
	padding-left: 5px;
	box-shadow: #444444 0px 0px 3px 0px;
	-moz-box-shadow: #444444 0px 0px 3px 0px;
	-webkit-box-shadow: #444444 0px 0px 3px 0px;
	font-family: ;
}

|ID| li.level1 {
}

|ID| li.level1 > span {
}

|ID| li.level1 > span a {
	color: #636363;
	padding-top: 5px;
	padding-right: 5px;
	padding-bottom: 5px;
	padding-left: 5px;
	font-size: 12px;
}

|ID| li.level1 > span .accordeonck_desc {
	font-size: 10px;
}

|ID| li.level1:hover > span {
}

|ID| li.level1:hover > span a {
	color: #000000;
}

|ID| li.level1:hover > span .accordeonck_desc {
}

|ID| li.level1 > ul {
	font-family: ;
}

|ID| li.level2 {
}

|ID| li.level2 > span {
}

|ID| li.level2 > span a {
	color: #636363;
	padding-top: 5px;
	padding-bottom: 5px;
	padding-left: 15px;
}

|ID| li.level2 > span .accordeonck_desc {
}

|ID| li.level2:hover > span {
}

|ID| li.level2:hover > span a {
	color: #000000;
}

|ID| li.level2:hover > span .accordeonck_desc {
}

|ID| li.level2 ul[class^=|qq|content|qq|] {
	font-family: ;
}

|ID| li.level2 li.accordeonck {
}

|ID| li.level2 li.accordeonck > span {
}

|ID| li.level2 li.accordeonck > span a {
	color: #636363;
	padding-top: 2px;
	padding-bottom: 2px;
	padding-left: 20px;
}

|ID| li.level2 li.accordeonck > span .accordeonck_desc {
}

|ID| li.level2 li.accordeonck:hover > span {
}

|ID| li.level2 li.accordeonck:hover > span a {
	color: #000000;
}

|ID| li.level2 li.accordeonck:hover > span .accordeonck_desc {
}'
		) 
	);
}