<?php
/**
 * Getting Started.
 */
?>
<div class="demo-import-plus-welcome" data-plugins="<?php echo esc_attr( $page_builders ); ?>">
    <div class="inner-wrap">
        <div class="inner">
            <div class="header">
                <span class="logo">
                    <h3 class="title"><?php esc_html_e( 'Getting Started', 'demo-importer-plus' ); ?></h3>
                </span>
                <a href="<?php echo esc_url( admin_url() ); ?>" class="close"><span class="dashicons dashicons-no-alt"></span></a>
            </div>
            <form id="demo-import-plus-welcome-form" enctype="multipart/form-data" method="post">
                <h1><?php esc_html_e( 'Select Page Builder', 'demo-importer-plus' ); ?></h1>
                <p><?php esc_html_e( 'We offer starter templates that can be imported in one click. These sites are available in the following page builders. Please choose your preferred page builder from the list below.', 'demo-importer-plus' ); ?></p>
                <div class="fields">
                    <ul class="page-builders">
                        <?php
                        $default_page_builder = $this->get_setting( 'page_builder' );
                        $page_builders        = $this->get_page_builders();
                        foreach ( $page_builders as $key => $page_builder ) {
                            ?>
                            <li data-page-builder="<?php echo esc_html( $page_builder['slug'] ); ?>">
                                <label>
                                    <input type="radio" name="page_builder" value="<?php echo esc_html( $page_builder['name'] ); ?>">
                                    <img src="<?php echo esc_url( $this->get_page_builder_image( $page_builder['slug'] ) ); ?>" />
                                    <div class="title"><?php echo esc_html( $page_builder['name'] ); ?></div>
                                </label>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
                <input type="hidden" name="message" value="saved" />
                <?php wp_nonce_field( 'demo-import-plus-welcome-screen', 'demo-import-plus-page-builder' ); ?>
            </form>
        </div>
    </div>
</div>
<?php
