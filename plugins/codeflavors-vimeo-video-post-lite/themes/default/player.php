<?php
/**
 * @var Vimeotheque\Video_Post[] $videos
 * @var array $embed_options
 */

use Vimeotheque\Themes\Helper;
use function Themes\DefaultTheme\get_image_size;

?>
<div class="cvm-vim-playlist default <?php echo parent::get_css_classes() ;?>"<?php Helper::get_width();?>>
    <?php \Vimeotheque\Helper::embed_video( $videos[0], $embed_options, true ); ?>
    <div class="cvm-playlist-wrap">
		<div class="cvm-playlist">
			<?php foreach( $videos as $cvm_video ): ?>
			<div class="cvm-playlist-item">
				<a href="<?php Helper::get_post_permalink();?>"<?php Helper::get_video_data_attributes();?>>
					<?php Helper::get_thumbnail( get_image_size() );?>
                    <span class="cvm-title"><?php Helper::get_title();?></span>
				</a>
				<?php Helper::get_excerpt();?>
			</div>
			<?php endforeach;?>
		</div>
		<a href="#" class="playlist-visibility collapse"></a>
	</div>
    <div class="clear"></div>
</div>