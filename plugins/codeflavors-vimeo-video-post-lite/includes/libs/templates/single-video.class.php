<?php
/**
 * @author  CodeFlavors
 * @project vimeotheque-templates
 */

namespace Vimeotheque\Templates;

use Vimeotheque\Player\Player;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

class Single_Video {

	/**
	 * Constructor
	 */
	public function __construct(){

		add_action(
			'wp_head',
			[$this, 'add_scripts']
		);

        add_filter(
            'vimeotheque\player\embed_width',
            function( $width ){
	            if( \Vimeotheque\Helper::is_video() && $width < 900 ){
                    $width = 900;
                }

                return $width;
            }, 10, 1
        );

	}

	/**
     * Embed front-end scripts.
     *
	 * @return void
	 */
	public function add_scripts(){
		if( \Vimeotheque\Helper::is_video() ){
			if( current_theme_supports( 'vimeotheque-next-video-card' ) ){
                $player = new Player( \Vimeotheque\Helper::get_video_post() );
				$next_post = Helper::get_adjacent_post( false, '', false );
				if( $next_post ){

?>
<script>
	var vimeotheque = vimeotheque || {};
    vimeotheque.current_post = {
        'embed_url' : '<?php echo esc_attr( $player->get_embed_url() );?>'
    };
    vimeotheque.next_post = {
        'title': '<?php echo esc_attr( $next_post->post_title );?>',
		'permalink': '<?php echo get_permalink( $next_post );?>',
		'featured_image': '<?php echo get_the_post_thumbnail_url( $next_post )?>',
		'duration': '<?php echo Helper::get_the_video_duration();?>'
	}
</script>
<?php
				}
			}
		}
	}

}