<div class="wmodes-label <?php echo esc_attr( $view_data[ 'ui_css_class' ] ); ?>">
    <div class="wmd-lbl-inner">
        <span class="wmd-lbl-text"><?php echo wp_kses( $view_data[ 'text' ], WModes_Main::get_allow_html() ); ?></span>
    </div>
</div>