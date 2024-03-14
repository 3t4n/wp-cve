<?php

if( ! function_exists( 'walker_core_custom_controls' ) ) :
/**
 * Register Custom Controls
*/
function walker_core_custom_controls( $wp_customize ){
    if( ! class_exists( 'Walker_Radio_Image_Control_Vertical' ) ){

        /**
         * Create a Radio-Image control
         * 
         * @link http://ottopress.com/2012/making-a-custom-control-for-the-theme-customizer/
         */
        class Walker_Radio_Image_Control_Vertical extends WP_Customize_Control {
            
            /**
             * Declare the control type.
             *
             * @access public
             * @var string
             */
            public $type = 'radio-image-veritical';
            
            /**
             * Render the control to be displayed in the Customizer.
             */
            public function render_content() {
                if ( empty( $this->choices ) ) {
                    return;
                }           
                
                $name = '_gridchamp-radio-' . $this->id;
                ?>
                <span class="customize-control-title">
                    <?php echo esc_html( $this->label ); ?>
                    <?php if ( ! empty( $this->description ) ) : ?>
                        <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
                    <?php endif; ?>
                </span>
                <div id="input_<?php echo esc_attr( $this->id ); ?>" class="image vertical-layout">
                    <?php foreach ( $this->choices as $value => $label ) : ?>
                            <label for="<?php echo esc_attr( $this->id ) . esc_attr( $value ); ?>">
                                <input class="radio-image-select" type="radio" value="<?php echo esc_attr( $value ); ?>" id="<?php echo esc_attr( $this->id ) . esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php $this->link(); checked( $this->value(), $value ); ?>>
                                <img src="<?php echo esc_html( $label ); ?>" alt="<?php echo esc_attr( $value ); ?>" title="<?php echo esc_attr( $value ); ?>">
                                </input>
                            </label><br />
                    <?php endforeach; ?>
                </div>
                <?php
            }
        }
    }

    if( ! class_exists( 'Walker_Core_Dropdown_Pages_Control' ) ):
    class Walker_Core_Dropdown_Pages_Control extends WP_Customize_Control{
    private $pages = false;

    public function __construct($manager, $id, $args = array(), $options = array())
    {
        $this->pages = get_pages($options);

        parent::__construct( $manager, $id, $args );
    }

    /**
     * Render the content of the category dropdown
     *
     * @return HTML
     */
    public function render_content()
       {
            if(!empty($this->pages))
            {
                ?>
                    <label>
                      <span class="customize-pages-select-control customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                       <san class="description customize-control-description"> <?php echo esc_html( $this->description ); ?></san>
                      <select <?php $this->link(); ?>>
                        <option><?php echo esc_html('None','walker-core');?></option>
                           <?php
                                foreach ( $this->pages as $page )
                                {
                                    printf('<option value="%s" %s>%s</option>', $page->post_title, selected($this->value(), $page->post_title, false), $page->post_title);
                                }
                           ?>
                      </select>
                    </label>
                <?php
            }
       }
 }
endif;
}


if( class_exists( 'WP_Customize_control' ) ){

    class Walker_Core_Custom_Text extends WP_Customize_Control{
        public $type = 'walker-core-custom-text';
        /**
        * Render the content on the theme customizer page
        */
        public function render_content()
        {
            ?>
            <label>
                <strong class="customize-text_editor"><?php echo wp_kses_post( $this->label ); ?></strong>
                <br />
                <span class="customize-text_editor_desc">
                    <?php echo wp_kses_post( $this->description ); ?>
                </span>
            </label>
            <?php
        }
    }
    
}

/**
 * Class to create a custom menu control
 */
if( class_exists( 'WP_Customize_control' ) ){
if( ! class_exists('WalkerCore_Menu_Dropdown_Custom_Control') ):
    class WalkerCore_Menu_Dropdown_Custom_Control extends WP_Customize_Control{
        
        private $menus = false;
        public $type = 'walker-core-custom-menu';
        public function __construct($manager, $id, $args = array(), $options = array()){
            $this->menus = wp_get_nav_menus($options);

            parent::__construct( $manager, $id, $args );
        }

        /**
         * Render the content on the theme customizer page
        */
        public function render_content(){
            if(!empty($this->menus))
            {
                ?>
                    <label>
                        <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                        <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
                        <select <?php $this->link(); ?>>
                            <?php
                                printf('<option value="%s" %s>%s</option>', '', selected($this->value(), '', false),__('Select Menu', 'walker-core') );
                             ?>
                        <?php
                            foreach ( $this->menus as $menu )
                            {
                                printf('<option value="%s" %s>%s</option>', $menu->term_id, selected($this->value(), $menu->term_id, false), $menu->name);
                            }
                        ?>
                        </select>
                    </label>
                <?php
            }
        }
    }
endif;
}
endif;
add_action( 'customize_register', 'walker_core_custom_controls' );
?>