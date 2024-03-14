<?php
if ( 'no' !== $settings['show_category']  ) {
    // Include post category info
    ?>
    <div class="qodef-e-info-item qodef-e-info-category">
        <?php the_category( '<span class="qodef-category-separator"></span>' ); ?>
    </div>
    <?php
}