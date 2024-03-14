<?php

namespace Photonic_Plugin\Layouts\Features;

use Photonic_Plugin\Core\Photonic;
use Photonic_Plugin\Lightboxes\BaguetteBox;
use Photonic_Plugin\Lightboxes\BigPicture;
use Photonic_Plugin\Lightboxes\Colorbox;
use Photonic_Plugin\Lightboxes\Fancybox;
use Photonic_Plugin\Lightboxes\Fancybox2;
use Photonic_Plugin\Lightboxes\Fancybox3;
use Photonic_Plugin\Lightboxes\Fancybox4;
use Photonic_Plugin\Lightboxes\Featherlight;
use Photonic_Plugin\Lightboxes\GLightbox;
use Photonic_Plugin\Lightboxes\Image_Lightbox;
use Photonic_Plugin\Lightboxes\Lightbox;
use Photonic_Plugin\Lightboxes\Lightcase;
use Photonic_Plugin\Lightboxes\Lightgallery;
use Photonic_Plugin\Lightboxes\Magnific;
use Photonic_Plugin\Lightboxes\None;
use Photonic_Plugin\Lightboxes\PhotoSwipe;
use Photonic_Plugin\Lightboxes\PhotoSwipe5;
use Photonic_Plugin\Lightboxes\PrettyPhoto;
use Photonic_Plugin\Lightboxes\Spotlight;
use Photonic_Plugin\Lightboxes\Strip;
use Photonic_Plugin\Lightboxes\Swipebox;
use Photonic_Plugin\Lightboxes\Thickbox;
use Photonic_Plugin\Lightboxes\VenoBox;

trait Can_Use_Lightbox {
	/**
	 * @return Lightbox
	 */
	public static function get_lightbox(): Lightbox {
		$map = [
			'baguettebox'   => 'BaguetteBox.php',
			'bigpicture'    => 'BigPicture.php',
			'colorbox'      => 'Colorbox.php',
			'fancybox'      => 'Fancybox.php',
			'fancybox2'     => 'Fancybox2.php',
			'fancybox3'     => 'Fancybox3.php',
			'fancybox4'     => 'Fancybox4.php',
			'featherlight'  => 'Featherlight.php',
			'glightbox'     => 'GLightbox.php',
			'imagelightbox' => 'Image_Lightbox.php',
			'lightcase'     => 'Lightcase.php',
			'lightgallery'  => 'Lightgallery.php',
			'magnific'      => 'Magnific.php',
			'photoswipe'    => 'PhotoSwipe.php',
			'photoswipe5'    => 'PhotoSwipe5.php',
			'prettyphoto'   => 'PrettyPhoto.php',
			'spotlight'     => 'Spotlight.php',
			'swipebox'      => 'Swipebox.php',
			'strip'         => 'Strip.php',
			'thickbox'      => 'Thickbox.php',
			'venobox'       => 'VenoBox.php',
			'none'          => 'None.php',
		];
		$library = Photonic::$library;
		require_once PHOTONIC_PATH . '/Lightboxes/' . $map[$library];

		if ('baguettebox' === $library) {
			$lightbox = BaguetteBox::get_instance();
		}
		elseif ('bigpicture' === $library) {
			$lightbox = BigPicture::get_instance();
		}
		elseif ('colorbox' === $library) {
			$lightbox = Colorbox::get_instance();
		}
		elseif ('fancybox' === $library) {
			$lightbox = Fancybox::get_instance();
		}
		elseif ('fancybox2' === $library) {
			$lightbox = Fancybox2::get_instance();
		}
		elseif ('fancybox3' === $library) {
			$lightbox = Fancybox3::get_instance();
		}
		elseif ('fancybox4' === $library) {
			$lightbox = Fancybox4::get_instance();
		}
		elseif ('featherlight' === $library) {
			$lightbox = Featherlight::get_instance();
		}
		elseif ('glightbox' === $library) {
			$lightbox = GLightbox::get_instance();
		}
		elseif ('imagelightbox' === $library) {
			$lightbox = Image_Lightbox::get_instance();
		}
		elseif ('lightcase' === $library) {
			$lightbox = Lightcase::get_instance();
		}
		elseif ('lightgallery' === $library) {
			$lightbox = Lightgallery::get_instance();
		}
		elseif ('magnific' === $library) {
			$lightbox = Magnific::get_instance();
		}
		elseif ('photoswipe' === $library) {
			$lightbox = PhotoSwipe::get_instance();
		}
		elseif ('photoswipe5' === $library) {
			$lightbox = PhotoSwipe5::get_instance();
		}
		elseif ('prettyphoto' === $library) {
			$lightbox = PrettyPhoto::get_instance();
		}
		elseif ('spotlight' === $library) {
			$lightbox = Spotlight::get_instance();
		}
		elseif ('swipebox' === $library) {
			$lightbox = Swipebox::get_instance();
		}
		elseif ('strip' === $library) {
			$lightbox = Strip::get_instance();
		}
		elseif ('thickbox' === $library) {
			$lightbox = Thickbox::get_instance();
		}
		elseif ('venobox' === $library) {
			$lightbox = VenoBox::get_instance();
		}
		else {
			$lightbox = None::get_instance();
		}
		return $lightbox;
	}
}
