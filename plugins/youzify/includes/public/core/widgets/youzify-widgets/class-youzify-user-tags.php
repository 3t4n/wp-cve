<?php

class Youzify_Profile_User_Tags_Widget {

    public function __construct() {}

    /**
     * Content.
     */
    function widget() {

        if ( ! bp_is_active( 'xprofile' ) ) {
            return;
        }

        // Get Slides.
        $tags = youzify_option( 'youzify_user_tags' );

        if ( empty( $tags ) ) {
            return;
        }

        // Get Data.
        $tags_content  = '';
        $display_icon  = youzify_option( 'youzify_enable_user_tags_icon', 'on' );
        $border_type   = youzify_option( 'youzify_wg_user_tags_border_style', 'radius' );
        $display_desc  = youzify_option( 'youzify_enable_user_tags_description', 'on' );

        global $field;

        foreach ( $tags as $tag ) :

            // Get Data
            $field = xprofile_get_field( $tag['field'],  bp_displayed_user_id() );

            // Unserialize Profile field
            $field_values = isset( $field->data->value ) ? maybe_unserialize( $field->data->value ) : '';

            if ( empty( $field_values ) ) {
                continue;
            }

            ob_start();


            ?>

            <div class="youzify-utag-item youzify-utag-item-<?php echo $field->id; ?>">
                <div class="youzify-utag-name">
                    <?php if ( 'on' == $display_icon ) : ?><i class="<?php echo apply_filters( 'youzify_user_tags_name_icon', $tag['icon'] ); ?>"></i><?php endif; ?>
                    <?php echo wp_unslash( $tag['name'] ); ?>
                </div>
                <?php if ( 'on' == $display_desc && ! empty( $tag['description'] ) ) : ?>
                <div class="youzify-utag-description"><?php echo $tag['description']; ?></div>
                <?php endif; ?>
                <div class="youzify-utag-values youzify-utags-border-<?php echo $border_type; ?>">
                    <?php foreach( (array) $field_values as $key => $value ) : ?>
                        <?php $value = apply_filters( 'bp_get_the_profile_field_value', $value, $field->type,  $field->id ); ?>
                            <div class="youzify-utag-value-item youzify-utag-value-<?php echo $key; ?>"><?php echo $value; ?></div>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php

            $content = ob_get_contents();

            ob_end_clean();

            $tags_content .= $content;

        endforeach;

        if ( empty( $tags_content ) ) {
            return;
        }

        ?>

        <div class="youzify-user-tags"><?php echo $tags_content ?></div>

        <?php
    }

}