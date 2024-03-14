<?php

class PL_Theme_Corposet_Service_Section {

	protected static $_instance = null;

	/**
	 * single itteration var
	 */
	protected $item_title;
	protected $item_subtitle;
	protected $image;
	protected $icon_value;
	protected $button;
	protected $link;

	// protected $newtab;

	/**
	 * Ensures only one instance is loaded or can be loaded.
	 *
	 * @return Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	function __construct() {
		$this->serviceSection();
	}

	function serviceSection() {
		$service_layout = get_theme_mod( 'service_layout', 3 );
		$service_bg     = get_theme_mod( 'service_bg', '');
		// $service_layout    = 2;
		$service_title       = get_theme_mod( 'service_title', 'WHAT CAN WE OFFER' );
		$service_sub_title   = get_theme_mod( 'service_sub_title', "Services We're offering" );
		$service_description = get_theme_mod( 'service_description', "Services We're offering" );

		$service_content_raw = get_theme_mod( 'service_repeater', service_default_json() );
		$service_content     = json_decode( $service_content_raw );
		// print_r($service_content);die;
		?>

		<section class="section services bg-size-cover" style="background-image: url(<?php echo $service_bg; ?>);" id="service-section">
			<div class="container">
				<div class="section-heading text-center">
					<h3 class="sub-title"><?php echo $service_title; ?></h3>
					<h2 class="ititle"><?php echo $service_sub_title; ?></h2>
					<p><?php echo $service_description; ?></p>
				</div>
				<div class="row">

					<?php
					foreach ( $service_content as $item ) {
						// print_r($item);die;
						$this->item_title    = ! empty( $item->title ) ? apply_filters( 'translate_single_string', $item->title, 'Service section' ) : '';
						$this->item_subtitle = ! empty( $item->subtitle ) ? apply_filters( 'translate_single_string', $item->subtitle, 'Service section' ) : '';
						$this->image_url     = ! empty( $item->image_url ) ? apply_filters( 'translate_single_string', $item->image_url, 'Service section' ) : '';
						/** @public type $service_choice */
						// $service_choice = !empty($item->choice) ? apply_filters('translate_single_string', $item->choice, 'Service section') : '';
						$this->icon_value = ! empty( $item->icon_value ) ? apply_filters( 'translate_single_string', $item->icon_value, 'Service section' ) : '';
						$this->button     = ! empty( $item->text ) ? apply_filters( 'translate_single_string', $item->text, 'Service section' ) : '';
						$this->link       = ! empty( $item->link ) ? apply_filters( 'translate_single_string', $item->link, 'Service section' ) : '';
						// $this->newtab = ( $item->newtab ) ? 'target=_blank' : 'target=_self';

						switch ( $service_layout ) {
							case 1:
								apply_filters( 'serviceLayOut1', $this->layOut( 'two' ) );
								break;
							case 2:
								apply_filters( 'serviceLayOut2', $this->layOut( 'three' ) );
								break;
							case 3:
								apply_filters( 'serviceLayOut3', $this->layOut( $class = '' ) );
								break;

							default:
								$this->layOut( 'two' );
								break;
						}
					}
					?>

				</div>
			</div>
		</section>
		<?php
	}

	function layOut( $class ) {
		?>
		<div class="col-sm-6 col-md-6 col-lg-4">
			<div class="service <?php echo $class; ?> hover_eff cover-bg">
				<div class="ser-img">
					<img src="<?php echo $this->image_url; ?>" alt="">
				</div>
				<div class="inner">
				<div class="top_icon">
								<i class="fa <?php echo $this->icon_value; ?>"></i>
							</div>
							<div class="bottom_text">
								<h2><a href="#"><?php echo $this->item_title; ?></a></h2>
								<p><?php echo $this->item_subtitle; ?></p>
								<?php if ( ! empty( $this->button ) && ! empty( $this->link ) ) { ?>
									<a href="<?php echo $this->link; ?>" class="btn btn-default" ><?php echo $this->button; ?> </a>
								<?php } ?>
							</div>
				</div>
			</div>
		</div>
		<?php
	}

}
