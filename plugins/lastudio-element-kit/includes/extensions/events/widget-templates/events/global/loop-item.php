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
$more_text = $this->get_settings_for_display( 'more_text' );


$meta1_pos = $this->get_settings_for_display('meta_position1');
$meta2_pos = $this->get_settings_for_display('meta_position2');

$post_classes = ['lakit-posts__item'];
if( $show_image == 'yes' && has_post_thumbnail() ) {
    $post_classes[] = 'has-post-thumbnail';
}

$post_classes[] = 'cpt-' . get_post_type();

if(filter_var($this->get_settings_for_display('enable_carousel'), FILTER_VALIDATE_BOOLEAN)){
    $post_classes[] = 'swiper-slide';
}
else{
    $post_classes[] = lastudio_kit_helper()->col_new_classes('columns', $this->get_settings_for_display());
}

if(filter_var($this->get_settings_for_display('enable_masonry'), FILTER_VALIDATE_BOOLEAN)){
	$post_classes = array_merge($post_classes, lastudio_kit_helper()->get_post_terms(get_the_ID(), 'id'));
}

$post_format = get_post_format();
if(empty($post_format)){
    $post_format = 'standard';
}

$post_link = get_the_permalink();
$buy_link = get_post_meta( get_the_ID(), 'event_ticket_link', true ) ?: get_the_permalink();

$show_meta = $this->get_settings_for_display('show_meta');
$show_content = false;
if($show_title == 'yes' || $show_more == 'yes' || $show_meta == 'yes' || $show_excerpt ){
    $show_content = true;
}

?>
<div class="<?php echo esc_attr(join(' ', $post_classes)) ?>">
    <div class="lakit-posts__outer-box">
        <div class="lakit-posts__inner-box"><?php

        if( $show_image == 'yes' && has_post_thumbnail() ) { ?>
            <div class="post-thumbnail lakit-posts__thumbnail">
                <a href="<?php echo $post_link; ?>" class="lakit-posts__thumbnail-link"><?php
                    the_post_thumbnail($this->get_settings_for_display( 'thumb_size' ), array(
                        'class' => 'lakit-posts__thumbnail-img wp-post-image la-lazyload-image'
                    ))
                ?></a>
            </div>
        <?php }

        if($show_content) {

            echo '<div class="lakit-posts__inner-content">';

            echo '<div class="lakit-posts__inner-content-inner">';

            if( $preset === 'evt-2' ){
                echo '<div class="lakit-posts__wrap-title">';
            }

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
                    esc_url($post_link),
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
                }
            }

            if ($meta1_pos == 'after_content') {
                $this->_load_template($this->_get_global_template('loop-meta1'));
            }
            if ($meta2_pos == 'after_content') {
                $this->_load_template($this->_get_global_template('loop-meta2'));
            }

            if( $preset === 'evt-2' ){
                echo '</div>';
            }

            if ($show_more == 'yes' && !in_array($preset, ['evt-1', 'evt-2']) ) {
                echo sprintf(
                    '<div class="lakit-posts__more-wrap lakit-btn-more-wrap"><a href="%2$s" class="elementor-button lakit-posts__btn-more lakit-btn-more" title="%3$s" rel="bookmark"><span class="btn__text">%1$s</span>%4$s</a></div>',
                    $more_text,
                    esc_url($buy_link),
                    esc_html(get_the_title()),
                    $this->_get_icon('more_icon', '<span class="lakit-btn-more-icon">%s</span>')
                );
            }

            if ($meta1_pos == 'after_button') {
                $this->_load_template($this->_get_global_template('loop-meta1'));
            }
            if ($meta2_pos == 'after_button') {
                $this->_load_template($this->_get_global_template('loop-meta2'));
            }
        }

        echo '</div>';
        echo '</div>';

        if($show_more == 'yes' && in_array($preset, ['evt-1', 'evt-2'])){
            echo sprintf(
                '<div class="lakit-posts__more-wrap lakit-btn-more-wrap"><a href="%2$s" class="elementor-button lakit-posts__btn-more lakit-btn-more" title="%3$s" rel="bookmark"><span class="btn__text">%1$s</span>%4$s</a></div>',
                $more_text,
                esc_url($buy_link),
                esc_html(get_the_title()),
                $this->_get_icon('more_icon', '<span class="lakit-btn-more-icon">%s</span>')
            );
        }

    ?></div>
    </div>
</div>