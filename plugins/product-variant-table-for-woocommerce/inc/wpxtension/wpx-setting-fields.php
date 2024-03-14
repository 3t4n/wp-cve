<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WPXtension_Setting_Fields' ) ) {
    class WPXtension_Setting_Fields {

        public static $_plugin = "";

        protected static $_instance = null;

        public static function instance($_plugin) {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self($_plugin);
            }

            return self::$_instance;
        }

        /*
         * Construct of the Class.
         *
         */

        public function __construct($_plugin){

            self::$_plugin = $_plugin;
            // echo "<h1>".self::$_plugin."</h1>";

        }


        /**
         *
         * Display Lock icon if Pro is not installed
         *
         */

        public static function pro_not_exist($plugin){

            // echo "<h1>".self::$_plugin."</h1>";
            if( !$plugin ){

                return '<small class="wpx-lock-wrapper"><span class="dashicons dashicons-lock"></span></small>';
            }

        }

        


        /**
         *
         * Disable feilds class for <td> if pro is not activated
         *
         */

        public static function disable_for_pro($need_pro, $pro_exists){

            if( $need_pro && !$pro_exists ){

                return 'wpx-need-pro';
            }
        }

        // Select Field 

        public static function select($options = []){

            $pro_exists = isset( $options['pro_exists'] ) ? $options['pro_exists'] : false;

            ?>
                <tr class="<?php echo esc_attr($options['tr_class']); ?>" valign="top" data-new-tag="<?php echo ( isset( $options['tag'] ) ) ? esc_attr($options['tag']) : ''; ?>">

                    <td class="row-title" scope="row">
                        <?php
                            $label = ( $options['need_pro'] === true ) ? self::pro_not_exist($pro_exists) . esc_attr($options['label']) : esc_attr($options['label']);
                            echo sprintf(
                                '<label>%s</label>',
                                $label
                            );
                            // echo $options['need_pro'];
                        ?>
                    </td>
                    <td class="<?php echo self::disable_for_pro($options['need_pro'], $pro_exists); ?>">

                        <select class="regular-ele-width<?php echo ( isset( $options['ele_class'] ) ) ? esc_attr($options['ele_class']) : ''; ?>" name='<?php echo esc_attr($options['name']); ?>'>
                            <?php 
                                foreach( $options['option'] as $select_option ){
                            ?>
                            <option value="<?php echo esc_attr($select_option['value']); ?>" <?php echo ( $select_option['need_pro'] === true && !$pro_exists ) ? 'disabled' : ''; ?>
                                <?php echo $options['value'] == $select_option['value'] ? "selected" : ''; ?>><?php echo esc_attr($select_option['name']); ?></option>
                            <?php 
                                } 
                            ?>
                        </select>

                        <?php
                            if( isset($options['note']) && $options['note'] !== '' ):
                        ?>
                            <p style="font-style: italic; color: red;"><?php echo $options['note']; ?></p>
                        <?php

                            endif;
                        ?>
                    </td>

                </tr>
            <?php
        }

        public static function checkbox($options = []){
            $pro_exists = isset( $options['pro_exists'] ) ? $options['pro_exists'] : false;
            ?>
                <tr class="<?php echo esc_attr($options['tr_class']); ?>" valign="top" data-new-tag="<?php echo ( isset( $options['tag'] ) ) ? esc_attr($options['tag']) : ''; ?>">

                    <td class="row-title" scope="row">

                        <label for="tablecell">
                            <?php
                                $label = ( $options['need_pro'] === true ) ? self::pro_not_exist($pro_exists) . esc_attr($options['label']) : esc_attr($options['label']);
                                echo $label;
                            ?>
                        </label>
                    </td>
                    <td class="<?php echo self::disable_for_pro($options['need_pro'], $pro_exists); ?>">
                        <label>
                            <input class="<?php echo ( isset( $options['ele_class'] ) ) ? esc_attr($options['ele_class']) : ''; ?>" type='checkbox' name='<?php echo esc_attr($options['name']); ?>' value='<?php echo esc_attr( $options['default_value'] ); ?>' <?php checked( esc_attr($options['value'] ), esc_attr( $options['default_value'] ), true ); ?> />
                            <?php echo $options['checkbox_label']; ?>
                        </label>
                        <?php if( isset( $options['note'] ) && $options['note'] !== ''  ): ?>
                            <p style="font-style: italic; color: red;"><?php echo $options['note']; ?></p>
                        <?php endif; ?>

                        <?php if( isset( $options['note_info'] ) && $options['note_info'] !== ''  ): ?>
                            <p style="font-style: italic; color: #222;"><?php echo $options['note_info']; ?></p>
                        <?php endif; ?>
                    </td>

                </tr>
            <?php
        }

        public static function color($options = []){
            $pro_exists = isset( $options['pro_exists'] ) ? $options['pro_exists'] : false;
            ?>
                <tr class="<?php echo esc_attr($options['tr_class']); ?>" valign="top" data-new-tag="<?php echo ( isset( $options['tag'] ) ) ? esc_attr($options['tag']) : ''; ?>">

                    <td class="row-title" scope="row">
                        <label for="tablecell">
                            <?php
                                $label = ( $options['need_pro'] === true ) ? self::pro_not_exist($pro_exists) . esc_attr($options['label']) : esc_attr($options['label']);
                                echo $label;
                            ?>
                        </label>
                    </td>
                    <td class="<?php echo self::disable_for_pro($options['need_pro'],$pro_exists); ?>">
                        <label>
                            <input class="color-field<?php echo ( isset( $options['ele_class'] ) ) ? esc_attr($options['ele_class']) : ''; ?>" type='text' name='<?php echo esc_attr($options['name']); ?>' value='<?php echo esc_attr( $options['value'] ); ?>'/>
                        </label>
                        <p style="font-style: italic; color: red;"><?php echo $options['note']; ?></p>
                    </td>

                </tr>
            <?php
        }

        public static function number($options = []){
            $pro_exists = isset( $options['pro_exists'] ) ? $options['pro_exists'] : false;
            ?>
                <tr class="<?php echo esc_attr($options['tr_class']); ?>" valign="top" data-new-tag="<?php echo ( isset( $options['tag'] ) ) ? esc_attr($options['tag']) : ''; ?>">

                    <td class="row-title" scope="row">
                        <?php
                            $label = ( $options['need_pro'] === true ) ? self::pro_not_exist($pro_exists) . esc_attr($options['label']) : esc_attr($options['label']);
                            echo $label;
                        ?>
                    </td>
                    <td class="<?php echo self::disable_for_pro($options['need_pro'],$pro_exists); ?>">
                        <label class="wpx-number-group">
                            <input class="wpx-number<?php echo ( isset( $options['ele_class'] ) ) ? esc_attr($options['ele_class']) : ''; ?>" type='number' min="0" name='<?php echo esc_attr($options['name']); ?>' value='<?php echo esc_attr( $options['value'] ); ?>'/>
                            <span>PX</span>
                        </label>
                        <p style="font-style: italic; color: red;"><?php echo $options['note']; ?></p>
                    </td>

                </tr>
            <?php
        }


        public static function text($options = []){
            $pro_exists = isset( $options['pro_exists'] ) ? $options['pro_exists'] : false;
            ?>
                <tr class="<?php echo esc_attr($options['tr_class']); ?>" valign="top" data-new-tag="<?php echo ( isset( $options['tag'] ) ) ? esc_attr($options['tag']) : ''; ?>">

                    <td class="row-title" scope="row">
                        <label for="tablecell">
                            <?php
                                $label = ( $options['need_pro'] === true ) ? self::pro_not_exist($pro_exists) . esc_attr($options['label']) : esc_attr($options['label']);
                                echo $label;
                            ?>
                        </label>
                    </td>
                    <td class="<?php echo self::disable_for_pro($options['need_pro'],$pro_exists); ?>">
                        <label>
                            <input class='regular-text<?php echo ( isset( $options['ele_class'] ) ) ? esc_attr($options['ele_class']) : ''; ?>' type='text' name='<?php echo esc_attr($options['name']); ?>' value='<?php echo esc_attr( $options['value'] ); ?>' placeholder='<?php echo esc_attr($options['placeholder']); ?>' />
                        </label>

                        <?php if( isset( $options['note'] ) && $options['note'] !== ''  ): ?>
                            <p style="font-style: italic; color: red;"><?php echo $options['note']; ?></p>
                        <?php endif; ?>

                        <?php if( isset( $options['note_info'] ) && $options['note_info'] !== ''  ): ?>
                            <p style="font-style: italic; color: #222;"><?php echo $options['note_info']; ?></p>
                        <?php endif; ?>
                    </td>

                </tr>
            <?php
        }


        // Select Field 

        public static function multiselect($options = []){

            $pro_exists = isset( $options['pro_exists'] ) ? $options['pro_exists'] : false;

            ?>
                <tr class="<?php echo esc_attr($options['tr_class']); ?>" valign="top" data-new-tag="<?php echo ( isset( $options['tag'] ) ) ? esc_attr($options['tag']) : ''; ?>">

                    <td class="row-title" scope="row">
                        <?php
                            $label = ( $options['need_pro'] === true ) ? self::pro_not_exist($pro_exists) . esc_attr($options['label']) : esc_attr($options['label']);
                            echo sprintf(
                                '<label>%s</label>',
                                $label
                            );
                            // echo $options['need_pro'];
                        ?>
                    </td>
                    <td class="<?php echo self::disable_for_pro($options['need_pro'], $pro_exists); ?>">

                        <select class="regular-ele-width<?php echo ( isset( $options['ele_class'] ) ) ? esc_attr($options['ele_class']) : ''; ?>" name="<?php echo esc_attr($options['name']); ?>" multiple="multiple">
                            <?php 
                                foreach( $options['option'] as $select_option ){
                            ?>
                            <option value="<?php echo esc_attr($select_option['value']); ?>" <?php echo ( $select_option['need_pro'] === true && !$pro_exists ) ? 'disabled' : ''; ?>
                                <?php echo ( in_array( $select_option['value'], $options['value'] ) ) ? "selected" : ''; ?>><?php echo esc_attr($select_option['name']); ?></option>
                            <?php 
                                } 
                            ?>
                        </select>

                        <?php
                            if( isset($options['note']) && $options['note'] !== '' ):
                        ?>
                            <p style="font-style: italic; color: red;"><?php echo $options['note']; ?></p>
                        <?php

                            endif;
                        ?>
                    </td>

                </tr>
            <?php
        }

    }

}
