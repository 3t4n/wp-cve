<div class="ep-box-row ep-my-2">
    <div class="ep-box-col-12 ep-text-secondary" id="ep_single_event_description">
        <?php $content = apply_filters( 'ep_event_description', $args->post->post_content );
        echo wpautop( wp_kses_post ( $content ) );?>
    </div>
</div>