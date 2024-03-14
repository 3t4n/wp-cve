<?php
	
global $announcement_bar;
header("Content-type: text/css; charset: UTF-8"); ?>
/**
 * Announcement Bar
 * @uses:		esc_attr_e( Announcement_Bar::get_setting( '' ) );
 * @author:	Austin Passy
 * @link:		http://austin.passy.co
 */

/* Start custom user input */
<?php esc_attr_e( wp_specialchars_decode( stripslashes( Announcement_Bar::get_setting( 'custom_css' ) ), 1, 0, 1 ) ) . "\n\n"; ?>
/* End custom user input */

/*
body {
	padding-top: <?php if ( function_exists( 'is_admin_bar_showing' ) && is_admin_bar_showing() ) esc_attr_e( Announcement_Bar::get_setting( 'height' ) ) + 28 . 'px'; else esc_attr_e( Announcement_Bar::get_setting( 'height' ) );  ?> !important;
}
*/

/* Override the admin-bar in WP 3.1 */
.admin-bar #announcementbar-container {
	z-index: 10000;
}

/* Extra div to handle some IE scenarios with absolute positioning. */
#announcementbar-container {
	display: table;
	position: relative;
	width: 100%;
	z-index: 99996;
}

#announcementbar {
	background: <?php esc_attr_e( Announcement_Bar::get_setting( 'background' ) ); ?>;
    color: <?php esc_attr_e( Announcement_Bar::get_setting( 'color' ) ); ?>;
    direction: ltr;
    font: 12px Arial,Helvetica,sans-serif;
    height: <?php esc_attr_e( Announcement_Bar::get_setting( 'height' ) ); ?>;
    left: 0;
    margin: 0 auto;
    min-width: 960px;
    position: fixed;
    top: -<?php esc_attr_e( Announcement_Bar::get_setting( 'height' ) ); ?>;
    width: 100%;
    -webkit-box-shadow: 0 0 15px rgba(0,0,0,0.3);
    -moz-box-shadow: 0 0 15px rgba(0,0,0,0.3);
    -o-box-shadow: 0 0 15px rgba(0,0,0,0.3);
	box-shadow: 0 0 15px rgba(0,0,0,0.3);
}
#announcementbar.show-if-no-js {
	display: block;
}

.announcement {
    color: <?php esc_attr_e( Announcement_Bar::get_setting( 'color' ) ); ?>;
    display: block;
    font-size: <?php esc_attr_e( Announcement_Bar::get_setting( 'size' ) ); ?>;
	line-height: <?php esc_attr_e( Announcement_Bar::get_setting( 'height' ) ); ?>;
    margin: 0 auto;
    text-align: center;
    width: 860px;
	z-index: 99999;
}
.announcement p {
	z-index: 99999;
}
.announcement a {
    color: <?php esc_attr_e( Announcement_Bar::get_setting( 'a_color' ) ); ?>;
    text-decoration: none;
	z-index: 99999;
}

/* Branding link. */
#announcementbar-container .branding {
    margin: 0 auto;
    width: 960px;
	z-index: 99999;
}
#announcementbar-container .branding a {
	background: 0 none;
	display: block;
    float: left;
	width: 30px;
	height: <?php esc_attr_e( Announcement_Bar::get_setting( 'height' ) ); ?>;
	margin: 0;
	padding: 0;
    <?php if ( Announcement_Bar::get_setting( 'height' ) == '28px' ) echo "font: normal normal normal 26px/26px Georgia, Times, 'Times New Roman', serif !important;"; ?>
    <?php if ( Announcement_Bar::get_setting( 'height' ) == '33px' ) echo "font: normal normal normal 30px/31px Georgia, Times, 'Times New Roman', serif !important;"; ?>
    <?php if ( Announcement_Bar::get_setting( 'height' ) == '40px' ) echo "font: normal normal normal 34px/34px Georgia, Times, 'Times New Roman', serif !important;"; ?>
    <?php if ( Announcement_Bar::get_setting( 'height' ) >= '50px' ) echo "font: normal normal normal 44px/44px Georgia, Times, 'Times New Roman', serif !important;"; ?>
	color: #fff;
 	text-align: center;
	position: absolute;
    text-decoration: none;
    -webkit-text-shadow: 0 1px 0 #000;
    -moz-text-shadow: 0 1px 0 #000;
    -o-text-shadow: 0 1px 0 #000;
    text-shadow: 0 1px 0 #000;
    top: 0;
	z-index: 99999;
}

/* Toggle div wrapper. */
#announcementbar-container .tab {
	height: 0;
	position: fixed;
	top: 0;
    width: 100%;
	z-index: 99997;
}

/* Wrapper for the open/close button. */
#announcementbar-container .tab .toggle {
  	clear: both;
	display: block;
	position: relative;
	width: 92%;
	/*height: <?php esc_attr_e( Announcement_Bar::get_setting( 'height' ) ); ?>;*/
	/*line-height: <?php esc_attr_e( Announcement_Bar::get_setting( 'height' ) ); ?>;*/
	margin: 0 auto;
	z-index: 99997;
}

/* Open/close link. */
#announcementbar-container .tab a {
  	background: rgb(0,0,0);
  	background: rgba(0,0,0,0.15);
	display: block;
	float: right;
	position: relative;
	width: 30px;
	height: <?php esc_attr_e( Announcement_Bar::get_setting( 'height' ) ); ?>;
	margin: 0;
	padding: 0;
    <?php if ( Announcement_Bar::get_setting( 'height' ) == '28px' ) echo "font: normal normal bold 20px/22px Georgia, Times, 'Times New Roman', serif !important;"; ?>
    <?php if ( Announcement_Bar::get_setting( 'height' ) == '33px' ) echo "font: normal normal bold 24px/24px Georgia, Times, 'Times New Roman', serif !important;"; ?>
    <?php if ( Announcement_Bar::get_setting( 'height' ) == '40px' ) echo "font: normal normal bold 31px/27px Georgia, Times, 'Times New Roman', serif !important;"; ?>
    <?php if ( Announcement_Bar::get_setting( 'height' ) >= '50px' ) echo "font: normal normal bold 44px/44px Georgia, Times, 'Times New Roman', serif !important;"; ?>
	color: #fff;
 	text-align: center;
}

/* Open link. */
#announcementbar-container .tab a.open {
	-webkit-border-radius: 0 0 5px 5px;
	-moz-border-radius: 0 0 5px 5px;
	-o-border-radius: 0 0 5px 5px;
	border-radius: 0 0 5px 5px;
}
#announcementbar-container .tab a.open:hover {
  	background: rgb(0,0,0);
  	background: rgba(0,0,0,0.4);
}

/* Close link. */
#announcementbar-container .tab a.close {
}	

/* Open/close link hover. */
#announcementbar-container .bradning a:hover, #announcementbar-container .tab a:hover {
	cursor: pointer;
	text-decoration: none;
}

/* Open/close link arrows. */
#announcementbar-container .tab a .arrow {
	font-style: normal;
}