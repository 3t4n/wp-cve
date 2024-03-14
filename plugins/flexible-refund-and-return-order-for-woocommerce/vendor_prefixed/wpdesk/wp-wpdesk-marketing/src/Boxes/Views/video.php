<?php

namespace FRFreeVendor;

/**
 * @var \WPDesk\Library\Marketing\Boxes\Abstracts\BoxInterface $box
 * @var \WPDesk\Library\Marketing\Boxes\Helpers\BBCodes $bbcodes
 * @var \WPDesk\Library\Marketing\Boxes\Helpers\Markers $markers
 */
?>
<div class="wpdesk-marketing-box wpdesk-marketing-box-video wpdesk-marketing-box-<?php 
echo \esc_html($box->get_slug());
?>">
	<?php 
if (!empty($box->get_title())) {
    ?>
		<header>
			<h3>
				<?php 
    echo \esc_html($box->get_title());
    ?>
			</h3>
			<?php 
    if (!empty($box->get_description())) {
        ?>
				<p class="description"><?php 
        echo \wp_kses_post($bbcodes->replace($markers->replace(\wp_strip_all_tags($box->get_description()))));
        ?></p>
			<?php 
    }
    ?>
		</header>
		<section>
			<?php 
    $is_carousel = \count($box->get_links()) > 1 ? 'video-carousel' : 'video-single';
    ?>
			<?php 
    if (!empty($box->get_links())) {
        ?>
				<div class="<?php 
        echo \esc_attr($is_carousel);
        ?> owl-theme">
					<?php 
        foreach ($box->get_links() as $link) {
            ?>
						<div class="item-video">
							<?php 
            echo \wp_oembed_get($link['video']);
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            ?>
						</div>
					<?php 
        }
        ?>
				</div>
			<?php 
    }
    ?>
			<?php 
    if (!empty($box->get_button()['name'])) {
        ?>
				<p class="box-button">
					<a
							class="button button-primary"
							href="<?php 
        echo \esc_url($box->get_button()['url']);
        ?>"
							target="_blank"
					>
						<?php 
        echo \wp_kses_post($box->get_button()['name']);
        ?>
					</a>
				</p>
			<?php 
    }
    ?>
		</section>
	<?php 
}
?>
</div>
<?php 
