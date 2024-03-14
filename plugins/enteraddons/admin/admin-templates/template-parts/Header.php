<?php
namespace Enteraddons\Admin;
/**
 * Enteraddons admin section
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */


trait  Header {

	public $headerContent = '';

	public function header_area() {
		?>
		<header class="header">
		    <div class="container">
		    	<?php 
		    	$content = $this->headerContent;
		    	if( !empty( $content['logourl'] ) ):
		    	?>
		        <div class="logo text-center">
		            <img src="<?php echo esc_url( $content['logourl'] ); ?>" alt="">
		        </div>
		        <?php 
		    	endif;
		        ?>
		        <div class="content text-center">
		        	<?php 
		        	if( !empty( $content['title'] ) ) {
		        		echo '<h2>'.esc_html( $content['title'] ).'</h2>';
		        	}
		        	//
		        	if( !empty( $content['desc'] ) ) {
		        		echo '<p>'.esc_html( $content['desc'] ).'</p>';
		        	}
		        	?>
		        </div>
		    </div>
		</header>
		<?php
	}

	public function header_content( array $arg ) {

		$default = array(
			'logourl' 	=> 'logo.png',
			'title' 	=> 'Welcome To ThemeLooks',
			'desc' 		=> 'Deep is now installed and ready to use! Let’s convert your imaginations to real things on the web!',
		);

		$content = wp_parse_args( $arg, $default );

		$this->headerContent =  $content;

	}

}
?>