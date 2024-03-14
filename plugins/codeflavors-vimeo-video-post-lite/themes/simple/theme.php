<?php
/**
 * @author CodeFlavors
 */

namespace Vimeotheque\Theme\Simple;

use Vimeotheque\Themes\Helper;
use Vimeotheque\Video_Post;

/**
 * @var Video_Post[] $videos
 */

?>
<div class="vimeotheque-playlist simple <?php echo parent::get_css_classes() ;?>" style="display:none; <?php Helper::get_width('', '') ;?>">
	<?php
		global $post;

		foreach ( $videos as $cvm_video ) :
			$post = $cvm_video->get_post();
			setup_postdata( $cvm_video->get_post() );
	?>
	<article>
        <div class="entry-content">
            <div class="featured-image">
                <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( get_the_title() );?>"><?php the_post_thumbnail();?></a>
            </div>
	        <?php the_title( sprintf( '<h3 class="entry-title default-max-width"><a href="%s">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
        </div>
    </article>
	<?php
        endforeach;

        wp_reset_postdata();
    ?>
</div>
