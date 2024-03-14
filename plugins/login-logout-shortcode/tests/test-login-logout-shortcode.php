<?php

require_once( "login-logout-shortcode.php" );

class Login_Logout_Shortcode_Test extends WP_UnitTestCase {
    public function setUp() {
        parent::setUp();
    }

    public function tearDown() {
        global $current_user;

        $current_user = null;

        $_SERVER["REQUEST_URI"] = "";
    }

    public function test_login_logout_shortcode_is_registered_to_shortcode_handler() {
        global $shortcode_tags;

        $this->assertTrue( array_key_exists(
            "login-logout",
            $shortcode_tags
        ) );

        $expected = "login_logout_shortcode";
        $this->assertEquals( $expected, $shortcode_tags["login-logout"] );
    }

    public function test_when_not_logged_in_and_no_params_should_show_login_link_redirecting_to_logout_page_by_default() {
        $expected  = '<a href="' . esc_url( wp_login_url() ) . '">';
        $expected .= esc_html( "Login" ) . '</a>';

        $actual = do_shortcode( "[login-logout]" );

        $this->assertEquals( $expected, $actual );
    }

    public function test_when_not_logged_in_and_text_to_login_should_show_login_link_and_text_to_login() {
        $expected  = '<a href="' . esc_url( wp_login_url() ) . '">';
        $expected .= esc_html( "Please just log me in" ) . '</a>';

        $actual = do_shortcode( '[login-logout text_to_login="Please just log me in"]' );

        $this->assertEquals( $expected, $actual );
    }

    public function test_put_class_param_should_have_class_on_login_logout_link() {
        $expected  = '<a href="' . esc_url( wp_login_url() ) . '" ';
        $expected .= 'class="btn btn-primary">';
        $expected .= esc_html( "Login" ) . '</a>';

        $actual = do_shortcode( '[login-logout class="btn btn-primary"]' );

        $this->assertEquals( $expected, $actual );
    }

    public function test_put_redirect_param_should_have_redirect_on_login_logout_link() {
        $expected  = '<a href="' . esc_url( wp_login_url( "home" ) ) . '">';
        $expected .= esc_html( "Login" ) . '</a>';

        $actual = do_shortcode( '[login-logout redirect="home"]' );

        $this->assertEquals( $expected, $actual );
    }

    public function test_when_login_and_no_param_should_show_logout_link_redirecting_to_logout_page_by_default() {
        global $current_user;

        $user_id = $this->factory->user->create( array(
            "user_login"  => "test_author",
            "description" => "test_author",
            "role"        => "author",
        ) );
        $current_user = $this->factory->user->get_object_by_id( $user_id );

        $expected  = '<a href="' . esc_url( wp_logout_url() ) . '">';
        $expected .= esc_html( "Logout" ) . '</a>';

        $actual = do_shortcode( '[login-logout]' );

        $this->assertEquals( $expected, $actual );
    }

    public function test_when_login_and_text_to_logout_should_show_logout_link_and_text_to_logout() {
        global $current_user;

        $user_id = $this->factory->user->create( array(
            "user_login"  => "test_author",
            "description" => "test_author",
            "role"        => "author",
        ) );
        $current_user = $this->factory->user->get_object_by_id( $user_id );

        $expected  = '<a href="' . esc_url( wp_logout_url() ) . '">';
        $expected .= esc_html( "Get me out of here" ) . '</a>';

        $actual = do_shortcode( '[login-logout text_to_logout="Get me out of here"]' );

        $this->assertEquals( $expected, $actual );
    }

    public function test_when_login_and_logout_should_redirect_to_server_uri() {
        $_SERVER["REQUEST_URI"] = "/category/uncategorized/";

        $expected  = '<a href="' . esc_url( wp_login_url( "/category/uncategorized/" ) ) . '">';
        $expected .= esc_html( "Login" ) . '</a>';

        $actual = do_shortcode( "[login-logout]" );

        $this->assertEquals( $expected, $actual );

        global $current_user;

        $user_id = $this->factory->user->create( array(
            "user_login"  => "test_author",
            "description" => "test_author",
            "role"        => "author",
        ) );
        $current_user = $this->factory->user->get_object_by_id( $user_id );

        $expected  = '<a href="' . esc_url( wp_logout_url( "/category/uncategorized/" ) ) . '">';
        $expected .= esc_html( "Logout" ) . '</a>';

        $actual = do_shortcode( '[login-logout]' );

        $this->assertEquals( $expected, $actual );
    }

    public function test_put_login_url_should_have_login_url_on_login_logout_link() {
        $expected  = '<a href="' . esc_url("/my-account/") . '">';
        $expected .= esc_html( "Login" ) . '</a>';

        $actual = do_shortcode( '[login-logout login_url="/my-account/"]' );
        $this->assertEquals( $expected, $actual ); 

    }
}
