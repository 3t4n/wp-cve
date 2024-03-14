<?php

class Youzify_Profile_Portfolio_Widget {

    /**
     * Content.
     */
    function widget() {

        // Get Portfolio Photos
        $portfolio_photos = get_the_author_meta( 'youzify_portfolio', bp_displayed_user_id() );

        if ( empty( $portfolio_photos ) ) {
            return;
        }

        ?>

    	<ul class="youzify-portfolio-content">

    	<?php

            foreach ( $portfolio_photos as $photo ) :

            // Get Original Image Link.
            $original_image = wp_get_attachment_url( $photo['image'] );

            // If Photo Link is not available replace it with Photo Source Link
            $photo_link  = ! empty( $photo['link'] ) ? $photo['link'] : $original_image;

    	?>

		<li>
            <figure class="youzify-project-item">
                <div class="youzify-projet-img"><img loading="lazy" <?php echo youzify_get_image_attributes( $photo['image'], 'youzify-medium', 'profile-portfolio-widget' ); ?> alt=""></div>
				<figcaption class="youzify-pf-buttons">
                        <a class="youzify-pf-url" href="<?php echo esc_url( $photo_link ); ?>" target="_blank" ><i class="fas fa-link"></i></a>
                        <a class="youzify-pf-zoom"><i class="fas fa-search"></i></a>
                        <a class="youzify-lightbox-img" href="<?php echo $original_image; ?>" data-youzify-lightbox="youzify-portfolio" <?php if ( ! empty( $photo['title'] ) ) { echo "data-title='" . esc_attr( $photo['title'] ) . "'"; } ?>></a>
				</figcaption>
			</figure>
		</li>

    	<?php endforeach;?>

    	</ul>

    	<?php
    }

}