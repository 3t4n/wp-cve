<?php

class Youzify_Profile_Skills_Widget {

    /**
     * Content.
     */
    function widget() {

        // Variables.
        $skills = get_the_author_meta( 'youzify_skills', bp_displayed_user_id() );

        if ( empty( $skills ) ) {
            return false;
        }

        echo '<div class="youzify-skills-content youzify-default-content">';

        foreach ( $skills as $skill ) :

            // Make sure title and barpercent are filled.
            if ( empty( $skill['title'] ) || empty( $skill['barpercent'] ) ) {
                continue;
            }

            // Get Item Class
            $class = $skill['barpercent'] > 95 ? 'youzify-skillbar clearfix youzify-whitepercent' : 'youzify-skillbar clearfix';

            ?>

            <div class="<?php echo $class; ?>" data-percent="<?php echo $skill['barpercent']; ?>%">
                <div class="youzify-skillbar-bar" style="background-color:<?php echo $skill['barcolor']; ?>">
                    <span class="youzify-skillbar-title"><?php echo $skill['title']; ?></span>
                </div>
                <div class="youzify-skill-bar-percent"><?php echo $skill['barpercent']; ?>%</div>
            </div>

            <?php

        endforeach;

        echo '</div>';

    }

}