<?php
/**
 * Instance counter class.
 *
 * @since      1.0.0
 *
 * @package    Podcast_Player
 */

namespace Podcast_Player\Frontend\Inc;

use Podcast_Player\Helper\Core\Singleton;

/**
 * Instance counter.
 *
 * @package    Podcast_Player
 * @author     vedathemes <contact@vedathemes.com>
 */
class Instance_Counter extends Singleton {
	/**
	 * Podcast instance counter.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    int
	 */
	private $counter = null;

	/**
	 * Check if there is at least one instance of podcast player.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    bool
	 */
	private $has_podcast = false;

	/**
	 * Check if there is at least one instance of video podcast player.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    bool
	 */
	private $has_vcast = false;

	/**
	 * CSS for all player instances on current page.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string
	 */
	private $css = '';

	/**
	 * Class cannot be instantiated directly.
	 *
	 * @since  1.0.0
	 */
	protected function __construct() {
		$this->counter = wp_rand( 1, 10000 );
	}

	/**
	 * Return current instance of a key.
	 *
	 * @since  1.0.0
	 *
	 * @return int
	 */
	public function get() {
		$this->has_podcast     = true;
		return $this->counter += 1;
	}

	/**
	 * Check if there is at least one instance of podcast player.
	 *
	 * @since 3.3.0
	 *
	 * @return bool
	 */
	public function has_podcast_player() {
		return $this->has_podcast;
	}

	/**
	 * Set video podcast instance.
	 *
	 * @since 6.7.0
	 *
	 * @param bool $status vcast status
	 * @return bool
	 */
	public function set_vcast($status = false) {
		$this->has_vcast = $status;
	}

	/**
	 * Check if there is at least one instance of video podcast player.
	 *
	 * @since 6.7.0
	 *
	 * @return bool
	 */
	public function has_vcast() {
		return $this->has_vcast;
	}

	/**
	 * Add custom CSS for current player's instance.
	 *
	 * @since 3.5.0
	 *
	 * @param string $css CSS for the player.
	 */
	public function add_css( $css ) {
		$this->css .= $css;
	}

	/**
	 * Print custom CSS in site header for podcast player.
	 *
	 * @since 3.5.0
	 */
	public function print_header_css() {
		$css = '.pp-podcast {opacity: 0;}';
		?>
		<style type="text/css"><?php echo wp_strip_all_tags( $css, true ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></style>
		<?php
	}

	/**
	 * Print custom CSS for all player's instances on current page.
	 *
	 * @since 3.5.0
	 */
	public function print_footer_css() {
		?>
		<style type="text/css"><?php echo wp_strip_all_tags( $this->css, true ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></style>
		<?php
	}
}
