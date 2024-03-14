<?php

class CleanLogin_NavMenuLinks{
    function __construct(){
    }

    function load(){
        add_action( 'admin_init', array( $this, 'add_nav_menu_meta_boxes' ) );
    }

    function add_nav_menu_meta_boxes(){
        add_meta_box( 'clean_login_nav_menu_links', __( 'Clean Login', 'clean-login' ), array( $this, 'nav_menu_link' ), 'nav-menus', 'side', 'low' );
    }

    function links(){
        $links = array();

        if( !empty( CleanLogin_Controller::get_login_url() ) )
            $links[ CleanLogin_Controller::get_login_url() ] = __( 'Login', 'clean-login' );

        if( !empty( CleanLogin_Controller::get_edit_url() ) )
            $links[ CleanLogin_Controller::get_edit_url() ] = __( 'Edit profile', 'clean-login' );

        if( !empty( CleanLogin_Controller::get_restore_password_url() ) )
            $links[ CleanLogin_Controller::get_restore_password_url() ] = __( 'Restore password', 'clean-login' );

        if( !empty( CleanLogin_Controller::get_register_url() ) )
            $links[ CleanLogin_Controller::get_register_url() ] = __( 'Register', 'clean-login' );
        
        return $links;
    }
    
    function nav_menu_link(){?>
        <div id="posttype-clean-login-links" class="posttypediv">
            <div id="tabs-panel-clean-login-links" class="tabs-panel tabs-panel-active">
                <ul id ="clean-login-links-checklist" class="categorychecklist form-no-clear">
                    <?php
                        $i = -1;
                        foreach ( $this->links() as $key => $value ) :
                            ?>
                            <li>
                                <label class="menu-item-title">
                                    <input type="checkbox" class="menu-item-checkbox" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-object-id]" value="<?php echo esc_attr( $i ); ?>" /> <?php echo esc_html( $value ); ?>
                                </label>
                                <input type="hidden" class="menu-item-type" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-type]" value="custom" />
                                <input type="hidden" class="menu-item-title" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-title]" value="<?php echo esc_attr( $value ); ?>" />
                                <input type="hidden" class="menu-item-url" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-url]" value="<?php echo esc_url( $key ); ?>" />
                                <input type="hidden" class="menu-item-classes" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-classes]" />
                            </li>
                            <?php
                            $i--;
                        endforeach;
					?>
                </ul>
            </div>
            <p class="button-controls wp-clearfix" data-items-type="posttype-clean-login-links">
                <span class="list-controls hide-if-no-js">
                    <input type="checkbox" id="page-tab" class="select-all">
                    <label for="page-tab">Select All</label>
                </span>
                <span class="add-to-menu">
					<button type="submit" class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e( 'Add to menu', 'clean-login' ); ?>" name="add-post-type-menu-item" id="submit-posttype-clean-login-links"><?php esc_html_e( 'Add to menu', 'clean-login' ); ?></button>
					<span class="spinner"></span>
				</span>
            </p>
        </div>
    <?php }
}

