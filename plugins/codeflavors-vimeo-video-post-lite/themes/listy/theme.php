<?php
/**
 * @author  CodeFlavors
 */

namespace Vimeotheque\Theme\Listy;

use Vimeotheque\Helper;
use Vimeotheque\Video_Post;

/**
 * @var Video_Post[] $videos
 */

?>
<div class="vimeotheque-playlist listy <?php echo parent::get_css_classes() ;?>" <?php \Vimeotheque\Themes\Helper::get_width( 'style="', '"' ) ;?>>

	<?php
        global $post;

        foreach( $videos as $cvm_video ):
            $post = $cvm_video->get_post();
            setup_postdata( $post );
    ?>
        <article>
            <header class="entry-header">
                <div class="entry-meta">
                    <time class="entry-date published"><?php the_date();?></time>
                </div>
                <h3 class="entry-title"><?php the_title();?></h3>
            </header>
	        <?php Helper::embed_video( $post ); ?>
            <div class="entry-content" data-open_text="<?php esc_attr_e( 'Read more', 'codeflavors-vimeo-video-post-lite' );?>">
                <?php the_video_content();?>
            </div>
        </article>

	<?php
        endforeach;

        wp_reset_postdata();
    ?>
</div>

