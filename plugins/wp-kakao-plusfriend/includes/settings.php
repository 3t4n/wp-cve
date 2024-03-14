<?php
/**
 * Settings functions for the plugin.
 */

namespace KakaoPlusfriend\Settings;

// Get settings with defaults
function get_settings() {
    $defaults = [
        'app_key'       => '',
        'plusfriend_id' => '',
        'friend_btn' => 0,
        'friend_btn_size'   => 'small',
        'friend_btn_color'  => 'yellow',
        'friend_btn_supportMultipleDensities' => 0,
        'chat_btn'  => 0,
        'chat_btn_title'    => 'consult', 
        'chat_btn_size'     => 'small', 
        'chat_btn_color'    => 'yellow', 
        'chat_btn_shape'    => 'pc', 
        'chat_btn_supportMultipleDensities' => 0 
    ];
    $settings = get_option( 'kakao_plusfriend_settings', [] );
    $settings = wp_parse_args( $settings, $defaults );
    return $settings;
}

// Setup settings
function setup() {
	add_action(
		'plugins_loaded',
		function() {
			add_action( 'admin_menu', __NAMESPACE__ . '\settings_menu' );
            add_action( 'admin_init', __NAMESPACE__ . '\settings_init' );
		}
	);
}

// Sets up the plugin settings menu
function settings_menu() {
	add_options_page(
		esc_html__( 'Kakao Plusfriend Settings', 'kakao-plusfriend' ),
		esc_html__( 'Kakao Plusfriend', 'kakao-plusfriend' ),
		'manage_options',
		'kakao-plusfriend',
		__NAMESPACE__ . '\\kakao_plusfriend_settings_page'
	);
}

// Register and define the settings
function settings_init() {
    register_setting( 'kakao_plusfriend_settings', 'kakao_plusfriend_settings' );
}

// Render the settings page
function kakao_plusfriend_settings_page() {
    $settings = get_settings();
    // var_dump( $settings );
?>
    <div class="wrap">
        <h2>Kakao Plusfriend Settings</h2>
        <!-- <p>플러스친구 플러그인은 간편하게 플러스친구 추가하기와 1:1 채팅 기능을 사용할 수 있게 도와줍니다.</p> -->

        <form method="post" action="options.php">
            <?php settings_fields( 'kakao_plusfriend_settings' ); ?>

            <h3 class="title">General Settings</h3>
            <p><a href="https://developers.kakao.com/docs/js/getting-started" target="_blank">카카오 플러스친구 시작하기</a> 과정에 따라 발급받은 앱의 키와 플러스친구 계정 ID를 추가하세요.</p>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row">APP KEY</th>
                    <td><input type="text" name="kakao_plusfriend_settings[app_key]" 
                            value="<?php echo esc_attr( $settings['app_key'] ); ?>" /></td>
                </tr>    
                <tr valign="top">
                    <th scope="row">Plusfriend Id</th>
                    <td><input type="text" name="kakao_plusfriend_settings[plusfriend_id]" 
                            value="<?php echo esc_attr( $settings['plusfriend_id'] ); ?>" /></td>
                </tr>    
            </table>    

            <h3 class="title">플러스친구 추가 버튼</h3>
            <p>
                <label><input type="checkbox" 
                       name="kakao_plusfriend_settings[friend_btn]" 
                       value="1" 
                       <?php checked( 1, $settings['friend_btn'] ); ?> />
                    웹페이지에 플러스친구 친구추가 버튼을 생성합니다.
                </label>    
            </p>
            
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Size
                        <p class="description">친구추가 버튼의 사이즈</p>    
                    </th>
                    <td>
                        <label><input type="radio" 
                            name="kakao_plusfriend_settings[friend_btn_size]" 
                            value="small"  
                            <?php checked( 'small', $settings['friend_btn_size'] ); ?> />small</label>
                        <br>    
                        <label><input type="radio" 
                            name="kakao_plusfriend_settings[friend_btn_size]" 
                            value="large"  
                            <?php checked( 'large', $settings['friend_btn_size'] ); ?> />large</label>
                    </td>
                </tr>    
                <tr valign="top">
                    <th scope="row">Color
                        <p class="description">친구추가 버튼의 배경색</p>    
                    </th>
                    <td>
                        <label><input type="radio" 
                            name="kakao_plusfriend_settings[friend_btn_color]" 
                            value="yellow"  
                            <?php checked( 'yellow', $settings['friend_btn_color'] ); ?> />yellow</label>
                        <br>    
                        <label><input type="radio" 
                            name="kakao_plusfriend_settings[friend_btn_color]" 
                            value="black"  
                            <?php checked( 'black', $settings['friend_btn_color'] ); ?> />black</label>
                    </td>
                </tr>    
                <tr valign="top">
                    <th scope="row">supportMultipleDensities</th>
                    <td>
                        <label><input type="checkbox" 
                           name="kakao_plusfriend_settings[friend_btn_supportMultipleDensities]" 
                           value="1" 
                           <?php checked( 1, $settings['friend_btn_supportMultipleDensities'] ); ?> />
                        </label>
                        <p class="description">화면 배율에 따라 2x 3x 이미지를 사용, IE 미지원</p>    
                    </td>
                </tr>    
            </table>    


            <h3 class="title">플러스친구 1:1 채팅 버튼</h3>
            <p>
                <label>
                    <input type="checkbox" 
                        name="kakao_plusfriend_settings[chat_btn]" 
                        value="1" <?php checked( 1, $settings['chat_btn'] ); ?> />
                    웹페이지에 플러스친구 1:1 채팅 버튼을 생성합니다.
                </label>
            </p>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Title
                        <p class="description">1:1 채팅 버튼에 들어갈 제목</p>    
                    </th>
                    <td>
                        <label><input type="radio" 
                            name="kakao_plusfriend_settings[chat_btn_title]" 
                            value="consult"  
                            <?php checked( 'consult', $settings['chat_btn_title'] ); ?> />consult</label>
                        <br>    
                        <label><input type="radio" 
                            name="kakao_plusfriend_settings[chat_btn_title]" 
                            value="question"  
                            <?php checked( 'question', $settings['chat_btn_title'] ); ?> />question</label>
                    </td>
                </tr>    
                <tr valign="top">
                    <th scope="row">Size
                        <p class="description">1:1 채팅 버튼의 사이즈</p>    
                    </th>
                    <td>
                        <label><input type="radio" 
                            name="kakao_plusfriend_settings[chat_btn_size]" 
                            value="small"  
                            <?php checked( 'small', $settings['chat_btn_size'] ); ?> />small</label>
                        <br>    
                        <label><input type="radio" 
                            name="kakao_plusfriend_settings[chat_btn_size]" 
                            value="large"  
                            <?php checked( 'large', $settings['chat_btn_size'] ); ?> />large</label>
                    </td>
                </tr>    
                <tr valign="top">
                    <th scope="row">Color
                        <p class="description">1:1 채팅 버튼의 배경색</p>    
                    </th>
                    <td>
                        <label><input type="radio" 
                            name="kakao_plusfriend_settings[chat_btn_color]" 
                            value="yellow"  
                            <?php checked( 'yellow', $settings['chat_btn_color'] ); ?> />yellow</label>
                        <br>    
                        <label><input type="radio" 
                            name="kakao_plusfriend_settings[chat_btn_color]" 
                            value="mono"  
                            <?php checked( 'mono', $settings['chat_btn_color'] ); ?> />mono</label>
                    </td>
                </tr>    
                <tr valign="top">
                    <th scope="row">Shape
                        <p class="description">1:1 채팅 버튼의 모양</p>    
                    </th>
                    <td>
                        <label><input type="radio" 
                            name="kakao_plusfriend_settings[chat_btn_shape]" 
                            value="pc"  
                            <?php checked( 'pc', $settings['chat_btn_shape'] ); ?> />pc</label>
                        <br>    
                        <label><input type="radio" 
                            name="kakao_plusfriend_settings[chat_btn_shape]" 
                            value="mobile"  
                            <?php checked( 'mobile', $settings['chat_btn_shape'] ); ?> />mobile</label>
                    </td>
                </tr>    
                <tr valign="top">
                    <th scope="row">supportMultipleDensities</th>
                    <td>
                        <label><input type="checkbox" 
                           name="kakao_plusfriend_settings[chat_btn_supportMultipleDensities]" 
                           value="1" 
                           <?php checked( 1, $settings['chat_btn_supportMultipleDensities'] ); ?> />
                        </label>
                        <p class="description">화면 배율에 따라 2x 3x 이미지를 사용, IE 미지원</p>    
                    </td>
                </tr>    
            </table>    

            <?php submit_button(); ?>
        </form>    
    </div>    
<?php
}
