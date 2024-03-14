( function( $ ) {

    'use strict';

    $( document ).ready( function() {

        $( document ).on( 'click', '#youzify-skill-button' , function( e ) {

            var current_wg_nbr = $( '.youzify-wg-item[data-wg=skills]' ).length + 1;

            if ( current_wg_nbr > youzify_maximum_skills  )  {
				// Show Error Message
                $.youzify_DialogMsg( 'error', Youzify_Skills.items_nbr + youzify_maximum_skills );
                return false;
            }

            e.preventDefault();

            var skills_title = $.ukai_form_input( {
                    input_desc      : Youzify_Skills.skill_desc_title,
                    cell            : youzify_skill_nextCell,
                    option_item     : 'title',
                    options_name    : 'youzify_skills',
                    label_title     : Youzify_Skills.bar_title,
                    input_type      : 'text',
                    inner_option    : true
                }),

                skills_color = $.ukai_form_input( {
                    option_item     : 'barcolor',
                    input_desc      : Youzify_Skills.skill_desc_color,
                    cell            : youzify_skill_nextCell,
                    options_name    : 'youzify_skills',
                    label_title     : Youzify_Skills.bar_color,
                    input_type      : 'color',
                    inner_option    : true
                }),

                skills_percent = $.ukai_form_input( {
                    option_item     : 'barpercent',
                    input_desc      : Youzify_Skills.skill_desc_percent,
                    cell            : youzify_skill_nextCell,
                    options_name    : 'youzify_skills',
                    label_title     : Youzify_Skills.bar_percent,
                    input_type      : 'number',
                    input_min       : '1',
                    input_max       : '100',
                    inner_option    : true
                });

            // Add Skill
            $( '<li class="youzify-wg-item" data-wg="skills">'+
                skills_title + skills_percent + skills_color
                + '<a class="youzify-delete-item"></a></li>'
            ).hide().prependTo( '.youzify-wg-skills-options' ).fadeIn( 400 );

            // increase ID number.
            youzify_skill_nextCell++;

            // CallBack ColorPicker
            $( '.youzify-picker-input' ).wpColorPicker();

            // Check Account Items List
            $.youzify_CheckList();

        });

        // ColorPicker
        $( '.youzify-picker-input' ).wpColorPicker();

        /**
         * Remove Items.
         */
        $( document ).on( 'click', '.youzify-delete-item', function( e ) {

            $( this ).parent().fadeOut( function() {

                // Remove Item
                $( this ).remove();

                // Check Widget Items
                $.youzify_CheckList();

            });

        });

        /**
         * Check Account Items
         */
        $.youzify_CheckList = function() {

            // Check Skills List.
            if ( $( '.youzify-wg-skills-options li' )[0] ) {
                $( '.youzify-no-skills' ).remove();
            } else if ( ! $( '.youzify-no-skills' )[0] ) {
                $( '.youzify-wg-skills-options' ).append(
                    '<p class="youzify-no-content youzify-no-skills">' + Youzify_Skills.no_items + '</p>'
                );
            }

        }

        // Check Account Items List.
        $.youzify_CheckList();

    });

})( jQuery );