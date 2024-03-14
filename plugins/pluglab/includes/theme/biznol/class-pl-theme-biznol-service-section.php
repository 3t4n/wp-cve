<?php

class PL_Theme_Biznol_Service_Section {

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
		$service_layout    = get_theme_mod( 'service_layout', 1 );
		$service_title     = get_theme_mod( 'service_title', 'WHAT CAN WE OFFER' );
		$service_sub_title = get_theme_mod( 'service_sub_title', "Services We're offering" );

		$service_content_raw = get_theme_mod( 'service_repeater', service_default_json() );
		$service_content     = json_decode( $service_content_raw );
		?>

		<section class="our-service space-module wow fadeInUpBig" data-wow-delay="0ms" data-wow-duration="1500ms">
			<div class="container">
				<div class="section-heading text-center">
					<h3 class="sub-title"><?php echo $service_title; ?></h3>
					<h2 class="ititle"><?php echo $service_sub_title; ?></h2>
				</div>
				<div class="row">

					<?php
					foreach ( $service_content as $item ) {
						// print_r($item);die;
						$this->item_title    = ! empty( $item->title ) ? apply_filters( 'translate_single_string', $item->title, 'Service section' ) : '';
						$this->item_subtitle = ! empty( $item->subtitle ) ? apply_filters( 'translate_single_string', $item->subtitle, 'Service section' ) : '';
						/** @public type $service_choice */
						// $service_choice = !empty($item->choice) ? apply_filters('translate_single_string', $item->choice, 'Service section') : '';
						$this->icon_value = ! empty( $item->icon_value ) ? apply_filters( 'translate_single_string', $item->icon_value, 'Service section' ) : '';
						$this->button     = ! empty( $item->text ) ? apply_filters( 'translate_single_string', $item->text, 'Service section' ) : '';
						$this->link       = ! empty( $item->link ) ? apply_filters( 'translate_single_string', $item->link, 'Service section' ) : '';
						// $this->newtab = ( $item->newtab ) ? 'target=_blank' : 'target=_self';

						switch ( $service_layout ) {
							case 1:
								apply_filters( 'serviceLayOut1', $this->layOut1() );
								break;
							case 2:
								apply_filters( 'serviceLayOut2', $this->layOut2() );
								break;

							default:
								$this->layOut1();
								break;
						}
					}
					?>

				</div>
			</div>
		</section>
		<?php
	}

	function layOut1() {
		?>
		<div class="col-md-4">
			<div class="service">
				<div class="icon">
					<i class="fa <?php echo $this->icon_value; ?>"></i>
				</div>
				<h3 class="heading"><?php echo $this->item_title; ?></h3>
				<p><?php echo $this->item_subtitle; ?></p>
				<?php if ( ! empty( $this->button ) && ! empty( $this->link ) ) { ?>
					<a href="<?php echo $this->link; ?>" ><?php echo $this->button; ?> <i class="fa fa-arrow-right"></i></a>
					<?php } ?>
			</div>
		</div>
		<?php
	}

	function layOut2() {
		// echo 'comming soon';
	}

}
