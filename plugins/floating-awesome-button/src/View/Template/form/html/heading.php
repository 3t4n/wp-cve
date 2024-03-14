<div class="py-4 my-4 border-b border-gray-200">
    <span class="text-lg"><?php echo esc_attr( $text ) ?></span>
    <?php if(isset($args['info'])): ?>
        <div class="text-gray-400">
            <em><?php echo do_shortcode( $args['info'] ); ?></em>
        </div>
    <?php endif; ?>
</div>