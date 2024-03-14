<?php

/**
 * Skills Settings.
 */
function youzify_skills_widget_settings() {

    // Call Scripts
    wp_enqueue_script( 'youzify-skills', YOUZIFY_ASSETS . 'js/youzify-skills.min.js', array( 'jquery', 'youzify-builder' ), YOUZIFY_VERSION, true );
    wp_enqueue_script( 'iris', admin_url( 'js/iris.min.js' ), array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ), YOUZIFY_VERSION, true );

    // Color Picker.
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker', admin_url( 'js/color-picker.min.js' ), array( 'iris' ), YOUZIFY_VERSION, true );

    wp_localize_script( 'wp-color-picker', 'wpColorPickerL10n', array(
            'clear'         => __( 'Clear', 'youzify' ),
            'defaultString' => __( 'Default', 'youzify' ),
            'pick'          => __( 'Select Color', 'youzify' ),
            'current'       => __( 'Current Color', 'youzify' ),
        )
    );

    // Skill Translations.
    wp_localize_script( 'youzify-skills', 'Youzify_Skills', array(
            'skill_desc_percent' => __( 'Skill bar percent', 'youzify' ),
            'skill_desc_title'   => __( 'Type skill title', 'youzify' ),
            'skill_desc_color'   => __( 'Skill bar color', 'youzify' ),
            'bar_percent'        => __( 'Percent (%)', 'youzify' ),
            'bar_title'          => __( 'Title', 'youzify' ),
            'bar_color'          => __( 'Color', 'youzify' ),
            'items_nbr'          => __( 'The number of items allowed is ', 'youzify' ),
            'no_items'           => __( 'No items found!', 'youzify' )
        )
    );


    global $Youzify_Settings;

    // Get Args
    $args = youzify_get_profile_widget_args( 'skills' );

    $Youzify_Settings->get_field(
        array(
            'title'          => youzify_option( 'youzify_wg_skills_title', __( 'Skills', 'youzify' ) ),
            'button_text'    => __( 'Add New Skill', 'youzify' ),
            'id'             => $args['id'],
            'icon'           => $args['icon'],
            'button_id'      => 'youzify-skill-button',
            'widget_section' => true,
            'type'           => 'open',
        )
    );

    $Youzify_Settings->get_field(
        array(
            'id'   => 'youzify-skills-data',
            'type' => 'hidden'
        ), false, 'youzify_data'
    );

    echo '<ul class="youzify-wg-opts youzify-wg-skills-options">';

    // Get Data
    $i = 0;
    $skills = get_the_author_meta( 'youzify_skills', bp_displayed_user_id() );

    if ( ! empty( $skills ) ) :

    foreach ( $skills as $skill ) : $i++; ?>

        <li class="youzify-wg-item" data-wg="skills">

            <!-- Option Item. -->
            <div class="uk-option-item">
                <div class="youzify-option-inner">
                    <div class="option-infos">
                        <label><?php _e( 'Title', 'youzify' ); ?></label>
                        <p class="option-desc"><?php _e( 'Type skill title', 'youzify' ); ?></p>
                    </div>
                    <div class="option-content">
                        <input type="text" name="youzify_skills[<?php echo $i; ?>][title]" value="<?php echo $skill['title']; ?>">
                    </div>
                </div>
            </div>

            <!-- Option Item. -->
            <div class="uk-option-item">
                <div class="youzify-option-inner">
                    <div class="option-infos">
                        <label><?php _e( 'Percent (%)', 'youzify' ); ?></label>
                        <p class="option-desc"><?php _e( 'Skill bar percent', 'youzify' ); ?></p>
                    </div>
                    <div class="option-content">
                        <input type="number" min="1" max="100" name="youzify_skills[<?php echo $i; ?>][barpercent]" value="<?php echo $skill['barpercent']; ?>">
                    </div>
                </div>
            </div>

            <!-- Option Item. -->
            <div class="uk-option-item">
                <div class="youzify-option-inner">
                    <div class="option-infos">
                        <label><?php _e( 'Color', 'youzify' ); ?></label>
                        <p class="option-desc"><?php _e( 'Skill bar color', 'youzify' ); ?></p>
                    </div>
                    <div class="option-content">
                        <input type="text" class="youzify-picker-input" name="youzify_skills[<?php echo $i; ?>][barcolor]" value="<?php echo $skill['barcolor']; ?>">
                    </div>
                </div>
            </div>

            <a class="youzify-delete-item"></a>

        </li>

        <?php endforeach; endif; ?>

        <script>
            var youzify_skill_nextCell = <?php echo $i+1 ?>,
                youzify_maximum_skills = <?php echo youzify_option( 'youzify_wg_max_skills', 5 ); ?>;
        </script>

    <?php

    echo '</ul>';

    $Youzify_Settings->get_field( array( 'type' => 'close' ) );

}

/**
 * Skills Content .
 */
function get_user_skills() {

}