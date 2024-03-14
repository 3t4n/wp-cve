<?php
/**
 * Posts loop start template
 */

$preset = $this->get_settings_for_display('preset');

$show_image     = $this->get_settings_for_display('show_image');
$show_title     = $this->get_settings_for_display('show_title');
$show_more      = $this->get_settings_for_display('show_more');
$show_excerpt   = $this->get_settings_for_display('show_excerpt');
$excerpt_length   = $this->get_settings_for_display('excerpt_length');
$title_html_tag = $this->get_settings_for_display('title_html_tag');
$show_meta = $this->get_settings_for_display('show_meta');

$floating_date = $this->get_settings_for_display('floating_date');
$floating_date_style = $this->get_settings_for_display('floating_date_style');
$floating_category = $this->get_settings_for_display('floating_category');
$floating_postformat = $this->get_settings_for_display('floating_postformat');
$floating_counter = $this->get_settings_for_display('floating_counter');
$floating_counter_as = $this->get_settings_for_display('floating_counter_as');


$meta1_pos = $this->get_settings_for_display('meta_position1');
$meta2_pos = $this->get_settings_for_display('meta_position2');

$post_classes = ['lakit-posts__item'];
if( $show_image == 'yes' && has_post_thumbnail() ) {
    $post_classes[] = 'has-post-thumbnail';
}

if(!$this->cflag){
	if(filter_var($this->get_settings_for_display('enable_carousel'), FILTER_VALIDATE_BOOLEAN)){
		$post_classes[] = 'swiper-slide';
	}
	else{
		$post_classes[] = lastudio_kit_helper()->col_new_classes('columns', $this->get_settings_for_display());
	}
}
else{
    $post_classes[] = 'col-mob-1';
}

if(filter_var($this->get_settings_for_display('enable_masonry'), FILTER_VALIDATE_BOOLEAN)){
	$post_classes = array_merge($post_classes, lastudio_kit_helper()->get_post_terms(get_the_ID(), 'id'));
}

$post_format = get_post_format();

if(empty($post_format)){
    $post_format = 'standard';
}

$show_content = false;
if($show_title == 'yes' || $show_more == 'yes' || $show_meta == 'yes' || $show_excerpt || filter_var($floating_counter, FILTER_VALIDATE_BOOLEAN)){
    $show_content = true;
}

?>
<div class="<?php echo esc_attr(join(' ', $post_classes)) ?>">
    <div class="lakit-posts__outer-box">
        <div class="lakit-posts__inner-box"><?php
        if( $show_image == 'yes' && has_post_thumbnail() ) { ?>
            <div class="post-thumbnail lakit-posts__thumbnail">
                <a href="<?php the_permalink(); ?>" class="lakit-posts__thumbnail-link" aria-label="<?php the_title_attribute(['before' => esc_html__('Read more about ', 'lastudio-kit')]) ?>"><?php
                    the_post_thumbnail($this->get_settings_for_display( 'thumb_size' ), array(
                        'class' => 'lakit-posts__thumbnail-img wp-post-image la-lazyload-image'
                    ))
                    ?></a>
                <?php if(filter_var($floating_date, FILTER_VALIDATE_BOOLEAN)): ?>
                <div class="lakit-posts__floating_date lakit-posts__floating_date--<?php echo esc_attr($floating_date_style);?>">
                    <div class="lakit-posts__floating_date-inner"><strong><?php echo get_the_date( 'd' );?></strong><span><?php echo get_the_date( 'M' );?></span></div>
                </div>
                <?php endif; ?>
                <?php if(filter_var($floating_category, FILTER_VALIDATE_BOOLEAN)): ?>
                <div class="lakit-posts__floating_category">
                    <div class="lakit-posts__floating_category-inner"><?php echo get_the_category_list(' ') ?></div>
                </div>
                <?php endif; ?>

                <?php if(filter_var($floating_postformat, FILTER_VALIDATE_BOOLEAN)): ?>
                <div class="lakit-posts__floating_postformat lakit-posts__floating_postformat-<?php echo $post_format ?>"><?php echo $this->render_post_format_icon($post_format) ?></div>
                <?php endif; ?>
            </div>
        <?php }

        if($show_content) {

            echo '<div class="lakit-posts__inner-content">';

            if (filter_var($floating_counter, FILTER_VALIDATE_BOOLEAN)) {
                echo '<div class="lakit-floating-counter">';
                if (filter_var($floating_counter_as, FILTER_VALIDATE_BOOLEAN)) {
                    echo $this->_get_icon('counter_icon', '<span class="lakit-floating-counter--icon">%s</span>');
                } else {
                    echo '<span class="lakit-floating-counter--number"></span>';
                }
                echo '</div>';
            }

            echo '<div class="lakit-posts__inner-content-inner">';

            if ($meta1_pos == 'before_title') {
                $this->_load_template($this->_get_global_template('loop-meta1'));
            }
            if ($meta2_pos == 'before_title') {
                $this->_load_template($this->_get_global_template('loop-meta2'));
            }

            if ($show_title == 'yes') {
                $title_length = -1;
                $title_ending = $this->get_settings_for_display('title_trimmed_ending_text');

                if (filter_var($this->get_settings_for_display('title_trimmed'), FILTER_VALIDATE_BOOLEAN)) {
                    $title_length = $this->get_settings_for_display('title_length');
                }

                $title = get_the_title();
                if ($title_length > 0) {
                    $title = wp_trim_words($title, $title_length, $title_ending);
                }

                echo sprintf(
                    '<%1$s class="lakit-posts__title"><a href="%2$s" title="%3$s" rel="bookmark">%4$s</a></%1$s>',
                    esc_attr($title_html_tag),
                    esc_url(get_the_permalink()),
                    esc_html(get_the_title()),
                    esc_html($title)
                );
            }

            if ($meta1_pos == 'after_title') {
                $this->_load_template($this->_get_global_template('loop-meta1'));
            }
            if ($meta2_pos == 'after_title') {
                $this->_load_template($this->_get_global_template('loop-meta2'));
            }

            if ($show_excerpt) {
                $excerpt_length = absint($excerpt_length);
                if ($excerpt_length > 0) {
                    echo sprintf(
                        '<div class="lakit-posts__excerpt entry-excerpt">%1$s</div>',
                        wp_trim_words(get_the_excerpt(), $excerpt_length)
                    );
                } else {
                    echo '<div class="lakit-posts__excerpt entry-excerpt"></div>';
                }
            }

            if ($meta1_pos == 'after_content') {
                $this->_load_template($this->_get_global_template('loop-meta1'));
            }
            if ($meta2_pos == 'after_content') {
                $this->_load_template($this->_get_global_template('loop-meta2'));
            }


            if ($show_more == 'yes') {
                echo sprintf(
                    '<div class="lakit-posts__more-wrap lakit-btn-more-wrap"><a href="%2$s" class="elementor-button lakit-posts__btn-more lakit-btn-more" title="%3$s" rel="bookmark"><span class="btn__text">%1$s</span>%4$s</a></div>',
                    $this->get_settings_for_display('more_text'),
                    esc_url(get_the_permalink()),
                    esc_html(get_the_title()),
                    $this->_get_icon('more_icon', '<span class="lakit-btn-more-icon">%s</span>')
                );
            }

            echo '</div>';
            echo '</div>';
        }

    ?></div>
    </div>
</div>