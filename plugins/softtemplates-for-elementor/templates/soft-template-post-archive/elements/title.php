<?php
    // Include post title
    $title_tag = isset( $settings['title_tag'] ) && ! empty( $settings['title_tag'] ) ? $settings['title_tag'] : 'h1';
    ?>
    <<?php echo esc_attr( $title_tag ); ?> itemprop="name" class="qodef-e-title entry-title">
        <a itemprop="url" class="qodef-e-title-link" href="<?php the_permalink(); ?>">
            <?php the_title(); ?>
        </a>
    </<?php echo esc_attr( $title_tag ); ?>>