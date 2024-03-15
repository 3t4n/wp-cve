<?php

final class MPFE_Plugin_Menu_Pages
{
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_pages' ) );
    }
    
    public function add_plugin_pages()
    {
        /*top level menu page*/
        add_menu_page(
            esc_html__( "Music Player for Elementor", "music-player-for-elementor" ),
            esc_html__( "Music Player for Elementor", "music-player-for-elementor" ),
            "administrator",
            "mpfe-dashboard",
            null,
            'dashicons-format-audio',
            2
        );
        /*sub-menu page*/
        add_submenu_page(
            "mpfe-dashboard",
            esc_html__( "How to use", "music-player-for-elementor" ),
            esc_html__( "How to use", "music-player-for-elementor" ),
            "administrator",
            "mpfe-dashboard",
            array( $this, 'mpfe_dashboard_page' ),
            1
        );
        /*sub-menu page*/
        add_submenu_page(
            "mpfe-dashboard",
            esc_html__( "Import Templates", "music-player-for-elementor" ),
            esc_html__( "Import Player Templates", "music-player-for-elementor" ),
            "administrator",
            "mpfe-import",
            array( $this, 'mpfe_import_page' ),
            2
        );
        /*sub-menu page*/
        add_submenu_page(
            "mpfe-dashboard",
            esc_html__( "Widgets Pack for Musicians", "music-player-for-elementor" ),
            esc_html__( "Widgets Pack for Musicians", "music-player-for-elementor" ),
            "administrator",
            "mpfe-musician-tools",
            array( $this, 'mpfe_musician_tools_page' ),
            9999999
        );
    }
    
    public function mpfe_import_page()
    {
        /*free templates*/
        $templates = array(
            array(
            'img'       => 'mpfe-default.jpg',
            'file'      => 'mpfe-default.json',
            'available' => '1',
        ),
            array(
            'img'       => 'mpfe-compact-default.jpg',
            'file'      => 'mpfe-compact-default.json',
            'available' => '1',
        ),
            array(
            'img'       => 't1.jpg',
            'file'      => '',
            'available' => '0',
        ),
            array(
            'img'       => 't2.jpg',
            'file'      => '',
            'available' => '0',
        ),
            array(
            'img'       => 't3.jpg',
            'file'      => '',
            'available' => '0',
        ),
            array(
            'img'       => 't4.jpg',
            'file'      => '',
            'available' => '0',
        )
        );
        $img_path = MPFE_DIR_URL . 'img/templates/';
        ?>

		<div class="mpfe_wrap_templates">
			<h1 class="mpfe_import_head">Import Player Templates</h1>
			<div class="mpfe_import_messages">
				<div class="mpfe_import_notice mpfe_import_success">
					<?php 
        echo  esc_html__( 'Template imported successfully! You can find it under My Templates/Saved Templates and you can insert it anywhere with Elementor editor.', 'music-player-for-elementor' ) ;
        ?>
					<a href="https://elementor.com/help/adding-templates/" target="_blank"><?php 
        echo  esc_html__( "See how to insert a template in Elementor." ) ;
        ?></a>
					<br>
					<strong>
						<?php 
        echo  esc_html__( 'Please remember to add your own media files to the player.', 'music-player-for-elementor' ) ;
        ?>
					</strong>
				</div>
				<div class="mpfe_import_notice mpfe_import_failed">
					<?php 
        echo  esc_html__( 'Sorry, we could not import the template. Please contact Music Player for Elementor support team!', 'music-player-for-elementor' ) ;
        ?>
				</div>
			</div>
			<div class="mpfe_player_templates">
				<?php 
        $templates_no = count( $templates );
        foreach ( $templates as $index => $template ) {
            ?>
					<?php 
            $is_available = ( 0 == $template['available'] ? false : true );
            $cta = ( $is_available ? "IMPORT AS TEMPLATE" : "PRO FEATURES" );
            $cta_css = ( $is_available ? "cta_text import_available" : "cta_text import_pro" );
            if ( 0 == $index ) {
                ?>
							<div class="mpfe_template_col_left">
						<?php 
            }
            ?>
							<div class="mpfe_templ_container">
								<img src="<?php 
            echo  esc_attr( esc_url( $img_path . $template['img'] ) ) ;
            ?>">
								<div class="mpfe_import_overlay">
									<div class="<?php 
            echo  esc_attr( $cta_css ) ;
            ?>" data-file="<?php 
            echo  esc_attr( $template['file'] ) ;
            ?>">
										<?php 
            
            if ( $is_available ) {
                echo  esc_html( $cta ) ;
            } else {
                ?>
											<a href="<?php 
                echo  esc_url( admin_url( 'admin.php?page=mpfe-dashboard-pricing' ) ) ;
                ?>"><?php 
                echo  esc_html( $cta ) ;
                ?></a>
										<?php 
            }
            
            ?>
									</div>
									<div class="mpfe_importing">
										<?php 
            echo  esc_html__( 'Please wait...', 'music-player-for-elementor' ) ;
            ?>
									</div>
								</div>
							</div>
						<?php 
            if ( $index == intval( $templates_no / 2 ) ) {
                ?>
							</div>
							<div class="mpfe_template_col_right">
						<?php 
            }
            ?>

						<?php 
            if ( $index == $templates_no - 1 ) {
                ?>
						</div>
						<?php 
            }
            ?>
				<?php 
        }
        ?>
			</div>
		</div>
		<?php 
    }
    
    public function mpfe_dashboard_page()
    {
        ?>
		<div class="mpfe_wrap">
			<div class="mpfe_promo_img">
				<img src="<?php 
        echo  esc_attr( esc_url( MPFE_DIR_URL . '/img/mpfe-promo.jpg' ) ) ;
        ?>">
			</div>
			<div class="mpfe_wrap_head">
				<h1>Welcome to Music Player for Elementor!</h1>
			</div>
			<div class="mpfe_welcome wpfe_wrap_block">
				<h4>Congratulations for using a premium designed music player addon!</h4>
				We want to make sure that everything is nice and clear for you. Please review the short FAQ below, we promise that it will not take more than one minute.
			</div>

			<div class="wpfe_wrap_block">
				<h4>What is it?</h4>
				<div class="mpfe_wrap_desc">
					Music Player for Elementor is a professionally designed and flexible <strong>audio player widget for Elementor</strong> page builder, perfect for musicians, artists, record labels, recording studios, DJs, podcasters, digital product stores and anyone working in the music industry.
				</div>
			</div>

			<div class="wpfe_wrap_block">
				<h4><span class="vibrant_color">New!</span> A second player widget, <span class="vibrant_color">the compact player</span>.</h4>
				<div class="mpfe_wrap_desc">
					Starting with the version 1.5.3, we have introduced a second player widget, a <strong>compact player</strong>, perfect for podcasters.
				</div>
				<img class="img_in_helper" src="<?php 
        echo  esc_attr( esc_url( MPFE_DIR_URL . '/img/compact-player-desktop.png' ) ) ;
        ?>">
			</div>

			<div class="wpfe_wrap_block">
				<h4>Do I need a special configuration?</h4>
				<div class="mpfe_wrap_desc">
					No initial configuration is needed to use the audio player. Just make sure you have the free version of <strong>Elementor plugin installed and active</strong>. 
				</div>
			</div>

			<div class="wpfe_wrap_block">
				<h4 class="vibrant_color">How to use the player?</h4>
				<div class="mpfe_wrap_desc">
					You can <a href="<?php 
        echo  esc_url( admin_url( 'admin.php?page=mpfe-import' ) ) ;
        ?>">import</a> one of the available player templates and customize the widget appearance according to your needs, or you can create the player from scratch, using one of the two widgets available.<br>
					<strong>Edit any page in Elementor</strong> page builder, search for <strong>"Music Player"</strong> in the widgets list and drag the music player widget anywhere on your page. You can add or remove songs to your playlist, customize the audio player images, add purchase links for your album, change the player layout or add individual promo links for each song.<br><br>Please check the <strong>below video</strong> for more details about the customization options that this plugin offers.

					<div class="mpfe_img_yt">
						<a href="https://youtu.be/6CagCkhVauI" target="_blank">
							<img src="<?php 
        echo  esc_attr( esc_url( MPFE_DIR_URL . '/img/YouTubeImgCover.jpg' ) ) ;
        ?>" class="img_yt">
						</a>
					</div>
				</div>
			</div>

			<div class="mpfe-header-quick-links">
				<a href="https://wordpress.org/support/plugin/music-player-for-elementor/" class="button helper-quick-link  button-primary" target="_blank">
					Report a problem			</a>
				<a href="https://wordpress.org/support/plugin/music-player-for-elementor/" class="button helper-quick-link  button-primary" target="_blank">
					Suggest a feature			</a>
			</div>
		</div>

		<?php 
        ?>
		<div class="wpfe_wrap_block wpfe_promo">
			<div class="mpfe_icon_pro text_center">
				<img src="<?php 
        echo  esc_attr( MPFE_DIR_URL . "/img/icon-256x256.png" ) ;
        ?>">
			</div>
			<div class="mpfe_wrap_desc text_center">
				<a href="<?php 
        echo  esc_url( admin_url( 'admin.php?page=mpfe-dashboard-pricing' ) ) ;
        ?>" class="mpfe_go_pro vibrant_color">GO PRO</a> and create a customized audio player for your website,  unlocking the full potential of Music Player for Elementor.
			</div>
			<div class="mpfe_wrap_desc pro_features">
				<div class="single_feat">
					<span><img class="checked" src="<?php 
        echo  esc_attr( MPFE_DIR_URL . "/img/checked.png" ) ;
        ?>"></span>
					<strong>Unlimited colors and fonts</strong> for layout elements
				</div>
				<div class="single_feat">
					<span><img class="checked" src="<?php 
        echo  esc_attr( MPFE_DIR_URL . "/img/checked.png" ) ;
        ?>"></span>
					<strong>Autoplay</strong> option
				</div>
				<div class="single_feat">
					<span><img class="checked" src="<?php 
        echo  esc_attr( MPFE_DIR_URL . "/img/checked.png" ) ;
        ?>"></span>
					<strong>Shuffle and repeat</strong> buttons
				</div>
				<div class="single_feat">
					<span><img class="checked" src="<?php 
        echo  esc_attr( MPFE_DIR_URL . "/img/checked.png" ) ;
        ?>"></span>
					<strong>WooCommerce integration</strong>: Ajax add to cart option for each song 
				</div>
				<div class="single_feat">
					<span><img class="checked" src="<?php 
        echo  esc_attr( MPFE_DIR_URL . "/img/checked.png" ) ;
        ?>"></span>
					<strong>Link each song to popular streaming platforms</strong>: Spotify, Amazon Music, Apple Music, YouTube Music and Beatport
				</div>
				<div class="single_feat">
					<span><img class="checked" src="<?php 
        echo  esc_attr( MPFE_DIR_URL . "/img/checked.png" ) ;
        ?>"></span>
					<strong>Modern gradient backgrounds</strong> for layout elements (buttons, player controls)
				</div>
				<div class="single_feat">
					<span><img class="checked" src="<?php 
        echo  esc_attr( MPFE_DIR_URL . "/img/checked.png" ) ;
        ?>"></span>
					Priority <strong>email support</strong>
				</div>
			</div>
			<div class="text_center">
				<a href="<?php 
        echo  esc_url( admin_url( 'admin.php?page=mpfe-dashboard-pricing' ) ) ;
        ?>" class="mpfe_adm_btn">PLANS & FEATURES</a>
			</div>
			
			<?php 
        
        if ( !mpfe_fs()->can_use_premium_code() ) {
            ?>
			<div class="text_center mpfe_trial_test">
				Not ready to buy? <a href="<?php 
            echo  esc_url( mpfe_fs()->get_trial_url() ) ;
            ?>">Test all our premium features</a> with a 7-day free trial. <span style="text-decoration: underline;">No credit-card, risk free!</span>
			</div>
			<?php 
        }
        
        ?>
		</div>
		<?php 
        ?>

		<?php 
    }
    
    public function mpfe_musician_tools_page()
    {
        $promo_url = "https://musicpack.smartwpress.com/";
        $more_widgets = array(
            array(
            'title' => 'Discography Vinyl Style',
            'img'   => 'discography-vinyl-style.jpg',
        ),
            array(
            'title' => 'Discography Creative',
            'img'   => 'discography-creative.jpg',
        ),
            array(
            'title' => 'Discography Grid',
            'img'   => 'discography-grid.jpg',
        ),
            array(
            'title' => 'Events List (Bright)',
            'img'   => 'events-list-bright.jpg',
        ),
            array(
            'title' => 'Events List (Dark)',
            'img'   => 'events-list-dark.jpg',
        ),
            array(
            'title' => 'Events Cards',
            'img'   => 'events-cards.jpg',
        ),
            array(
            'title' => 'Music Player (Pro Features)',
            'img'   => 'music-player.jpg',
        ),
            array(
            'title' => 'Music Player (Dark - Pro Features)',
            'img'   => 'music-player-dark.jpg',
        ),
            array(
            'title' => 'About Me',
            'img'   => 'about-me.jpg',
        ),
            array(
            'title' => 'Video Section',
            'img'   => 'about-video-section.jpg',
        ),
            array(
            'title' => 'Discography Single',
            'img'   => 'album-single.jpg',
        ),
            array(
            'title' => 'Upcoming Events Countdown',
            'img'   => 'upcoming-event-countdown.jpg',
        ),
            array(
            'title' => 'Upcoming Events Countdown (Light)',
            'img'   => 'upcoming-event-countdown-style2.jpg',
        ),
            array(
            'title' => 'Video Section (Creative)',
            'img'   => 'video-section.jpg',
        ),
            array(
            'title' => 'Artists',
            'img'   => 'artists.jpg',
        ),
            array(
            'title' => 'Blog',
            'img'   => 'blog.jpg',
        ),
            array(
            'title' => 'Contact Form',
            'img'   => 'contact-form.jpg',
        ),
            array(
            'title' => 'Contact Form Creative',
            'img'   => 'contact-form-creative.jpg',
        ),
            array(
            'title' => 'Gallery',
            'img'   => 'gallery.jpg',
        ),
            array(
            'title' => 'Gallery Archive',
            'img'   => 'gallery-archive.jpg',
        ),
            array(
            'title' => 'Gallery Single',
            'img'   => 'gallery-single.jpg',
        ),
            array(
            'title' => 'Newsletter MailChimp Form',
            'img'   => 'newsletter-mailchimp-form.jpg',
        ),
            array(
            'title' => 'Quote',
            'img'   => 'quote.jpg',
        ),
            array(
            'title' => 'Testimonial',
            'img'   => 'testimonial.jpg',
        )
        );
        ?>
		<div class="mpfe_wrap">
			<div class="mpfe_wrap_head_tc">
				<div class="mpfe_left">
					<h1>The complete pack for musicians</h1>
					<div>
					<p class="mpfe_admin_content">Build a spectacular musician website using our complete package of <strong>Elementor Widgets and Templates</strong>. </p>
					<p class="mpfe_admin_content">With 20+ additional custom designed <strong>Elementor widgets</strong>, ready to use <strong>Elementor templates</strong>, complete designs for landing pages, custom posts for your discography, events, gallery, artist and videos, a professional support system and bundled premium plugins, <strong>Music Pack</strong> is the perfect WordPress plugin for everyone working in the music industry.</p>
					<p class="btn_cont">
						<a href="<?php 
        echo  esc_url( $promo_url ) ;
        ?>" target="_blank" class="mpfe_adm_btn">VIEW FEATURES</a>
					</p>
					<!--
					<div class="star_rating">
						<img src="<?php 
        echo  esc_attr( MPFE_DIR_URL . "/img/slide-star-rating.png" ) ;
        ?>"><span class="star_rating_msg">4.88 / 5 rating after 1000+ customers</span>
					</div>
					-->
					</div>
					</div>
					<div class="mpfe_right">
				</div>
			</div>

			<div class="mpfe_more_widgets">
				<?php 
        foreach ( $more_widgets as $pro_widget ) {
            ?>
					<div class="mpfe_widget_container">
						<a class="mpfe_widget_lnk" href="<?php 
            echo  esc_attr( esc_url( $promo_url ) ) ;
            ?>" target="_blank">
							<img class="pro_widget_img" src="<?php 
            echo  esc_attr( esc_url( MPFE_DIR_URL . "/img/pro/" . $pro_widget['img'] ) ) ;
            ?>">
							<div class="pro_widget_name"><?php 
            echo  esc_html( $pro_widget['title'] ) ;
            ?> </div>
						</a>
					</div>
				<?php 
        }
        ?>
			</div>
		</div>

		<?php 
    }

}
if ( is_admin() ) {
    $mpfe_menu_pages = new MPFE_Plugin_Menu_Pages();
}