<div class="wmodes-textblock <?php echo esc_attr( $view_data[ 'ui_css_class' ] ); ?>">
    <div class="wmodes-tb-contents">
        <?php echo wp_kses_post( do_shortcode( $view_data[ 'contents' ] ) ); ?>
    </div>
</div>