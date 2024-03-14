<?php
namespace DarklupLite;
 /**
  * 
  * @package    DarklupLite - WP Dark Mode
  * @version    1.0.0
  * @author     
  * @Websites: 
  *
  */

/**
 * Switch Style class
 */
class Switch_Style {

	/**
	 * 
	 * @since 1.0.0
	 * @param number $style 
	 * @return void
	 */
	public static function switchStyle( $style, $preview = false ) {

		$getStyle = '';

		switch( $style ) {

			case '1' :
				$getStyle = self::style_15($preview);
				break;
			case '2' :
				$getStyle = self::style_1($preview);
				break;
            case '3' :
                $getStyle = self::style_2($preview);
                break;
            case '4' :
                $getStyle = self::style_8($preview);
                break;
			default :
				$getStyle = self::style_15($preview);
				break;
				
		}

		return $getStyle;

	}


    /**
     * Switch style 15
     *
     * @since 1.0.0
     * @return void
     */
    public static function style_15($preview = false) {
        ob_start();
        ?>
        <div class="darkluplite-switch-container darkluplite-dark-ignore">
            <label class="darkluplite-switch style15 darkluplite-dark-ignore">
                <input type="checkbox" class="toggle-checkbox <?php echo (!$preview) ? "switch-trigger" : ""; ?>">
                <div class="toggle-btn darkluplite-dark-ignore"> </div>
            </label>
        </div>
        <?php
        return ob_get_clean();
    }

	/**
	 * Switch style 1
	 * 
	 * @since 1.0.0
	 * @return void
	 */
	public static function style_1($preview = false) {
		ob_start();
		?>
        <div class="darkluplite-switch-container darkluplite-dark-ignore">
            <label class="darkluplite-switch style1 darkluplite-dark-ignore">
                <input type="checkbox" class="toggle-checkbox <?php echo (!$preview) ? "switch-trigger" : ""; ?>">
                <div class="toggle-btn darkluplite-dark-ignore">
                    <svg xmlns="http://www.w3.org/2000/svg" width="54.312" height="54.312" viewBox="0 0 54.312 54.312">
                        <circle id="style-1-light" cx="17.71" cy="17.71" r="17.71" transform="translate(9.446 9.446)" fill="#fff"/>
                        <g id="Group_1174" data-name="Group 1174">
                            <path id="style-1-light" d="M235.847,7.084A1.181,1.181,0,0,0,237.027,5.9V1.181a1.181,1.181,0,1,0-2.361,0V5.9A1.181,1.181,0,0,0,235.847,7.084Z" transform="translate(-208.691)" fill="#fff"/>
                            <path id="style-1-light" d="M235.847,426.667a1.181,1.181,0,0,0-1.181,1.181v4.723a1.181,1.181,0,0,0,2.361,0v-4.723A1.181,1.181,0,0,0,235.847,426.667Z" transform="translate(-208.691 -379.439)" fill="#fff"/>
                            <path id="style-1-light" d="M432.57,234.667h-4.723a1.181,1.181,0,1,0,0,2.361h4.723a1.181,1.181,0,0,0,0-2.361Z" transform="translate(-379.438 -208.692)" fill="#fff"/>
                            <path id="style-1-light" d="M7.084,235.847A1.181,1.181,0,0,0,5.9,234.666H1.181a1.181,1.181,0,1,0,0,2.361H5.9A1.181,1.181,0,0,0,7.084,235.847Z" transform="translate(0 -208.691)" fill="#fff"/>
                            <path id="style-1-light" d="M119.9,37.359a1.18,1.18,0,0,0,1.025.59,1.164,1.164,0,0,0,.59-.158,1.181,1.181,0,0,0,.432-1.613l-2.361-4.09a1.181,1.181,0,0,0-2.045,1.181Z" transform="translate(-104.395 -28.018)" fill="#fff"/>
                            <path id="style-1-light" d="M332.9,401.583a1.181,1.181,0,0,0-2.045,1.181l2.361,4.09a1.18,1.18,0,0,0,1.025.59,1.164,1.164,0,0,0,.59-.158,1.181,1.181,0,0,0,.432-1.613Z" transform="translate(-294.096 -356.612)" fill="#fff"/>
                            <path id="style-1-light" d="M402.117,121.864a1.164,1.164,0,0,0,.59-.158l4.09-2.361a1.181,1.181,0,0,0-1.095-2.092q-.044.023-.086.05l-4.09,2.361a1.181,1.181,0,0,0,.59,2.2v0Z" transform="translate(-356.557 -104.154)" fill="#fff"/>
                            <path id="style-1-light" d="M36.125,330.633l-4.09,2.361a1.181,1.181,0,0,0,.59,2.2,1.164,1.164,0,0,0,.59-.158l4.09-2.361a1.181,1.181,0,1,0-1.095-2.092q-.044.023-.086.05Z" transform="translate(-27.964 -293.873)" fill="#fff"/>
                            <path id="style-1-light" d="M31.67,119.325l4.09,2.361a1.164,1.164,0,0,0,.588.158,1.181,1.181,0,0,0,.59-2.2l-4.09-2.361a1.181,1.181,0,0,0-1.267,1.993q.042.027.086.05Z" transform="translate(-27.599 -104.135)" fill="#fff"/>
                            <path id="style-1-light" d="M406.453,332.976l-4.09-2.361a1.181,1.181,0,0,0-1.267,1.993c.028.018.057.034.086.05l4.09,2.361a1.164,1.164,0,0,0,.59.158,1.181,1.181,0,0,0,.59-2.2v0Z" transform="translate(-356.212 -293.855)" fill="#fff"/>
                            <path id="style-1-light" d="M331.27,37.793a1.164,1.164,0,0,0,.59.158,1.181,1.181,0,0,0,1.025-.59l2.361-4.09a1.181,1.181,0,0,0-2.045-1.181l-2.361,4.09A1.181,1.181,0,0,0,331.27,37.793Z" transform="translate(-294.078 -28.02)" fill="#fff"/>
                            <path id="style-1-light" d="M121.465,401.1a1.181,1.181,0,0,0-1.613.432l-2.361,4.09a1.181,1.181,0,0,0,.432,1.613,1.164,1.164,0,0,0,.59.158,1.181,1.181,0,0,0,1.025-.59l2.361-4.09a1.181,1.181,0,0,0-.434-1.612Z" transform="translate(-104.345 -356.557)" fill="#fff"/>
                        </g>
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" width="52.335" height="55.288" viewBox="0 0 52.335 55.288">
                        <path id="style-1-light" data-name="moon (2)" d="M27.664,55.285A26.915,26.915,0,0,0,52.358,39.709a21.107,21.107,0,0,1-8.9,1.756A21.743,21.743,0,0,1,21.741,19.746,22.508,22.508,0,0,1,33.453.329,41.274,41.274,0,0,0,27.664,0a27.641,27.641,0,0,0,0,55.283Zm0,0" transform="translate(-0.023 0.001)" fill="#fff"/>
                    </svg>
                </div>
            </label>
        </div>
		<?php
		return ob_get_clean();
	}
	/**
	 * Switch style 2
	 * 
	 * @since 1.0.0
	 * @return void
	 */
	
	public static function style_2($preview = false) {
		ob_start();
		?>
        <div class="darkluplite-switch-container darkluplite-dark-ignore">
            <label class="darkluplite-switch style2 darkluplite-dark-ignore">
                <input type="checkbox" class="toggle-checkbox <?php echo (!$preview) ? "switch-trigger" : ""; ?>">
                <div class="toggle-btn darkluplite-dark-ignore">
                    <div class="plate darkluplite-dark-ignore">
                        <svg xmlns="http://www.w3.org/2000/svg" width="54.312" height="54.312" viewBox="0 0 54.312 54.312">
                            <circle id="style-2-light" cx="17.71" cy="17.71" r="17.71" transform="translate(9.446 9.446)" fill="#fff"/>
                            <g id="Group_1174" data-name="Group 1174">
                                <path id="style-2-light" d="M235.847,7.084A1.181,1.181,0,0,0,237.027,5.9V1.181a1.181,1.181,0,1,0-2.361,0V5.9A1.181,1.181,0,0,0,235.847,7.084Z" transform="translate(-208.691)" fill="#fff"/>
                                <path id="style-2-light" d="M235.847,426.667a1.181,1.181,0,0,0-1.181,1.181v4.723a1.181,1.181,0,0,0,2.361,0v-4.723A1.181,1.181,0,0,0,235.847,426.667Z" transform="translate(-208.691 -379.439)" fill="#fff"/>
                                <path id="style-2-light" d="M432.57,234.667h-4.723a1.181,1.181,0,1,0,0,2.361h4.723a1.181,1.181,0,0,0,0-2.361Z" transform="translate(-379.438 -208.692)" fill="#fff"/>
                                <path id="style-2-light" d="M7.084,235.847A1.181,1.181,0,0,0,5.9,234.666H1.181a1.181,1.181,0,1,0,0,2.361H5.9A1.181,1.181,0,0,0,7.084,235.847Z" transform="translate(0 -208.691)" fill="#fff"/>
                                <path id="style-2-light" d="M119.9,37.359a1.18,1.18,0,0,0,1.025.59,1.164,1.164,0,0,0,.59-.158,1.181,1.181,0,0,0,.432-1.613l-2.361-4.09a1.181,1.181,0,0,0-2.045,1.181Z" transform="translate(-104.395 -28.018)" fill="#fff"/>
                                <path id="style-2-light" d="M332.9,401.583a1.181,1.181,0,0,0-2.045,1.181l2.361,4.09a1.18,1.18,0,0,0,1.025.59,1.164,1.164,0,0,0,.59-.158,1.181,1.181,0,0,0,.432-1.613Z" transform="translate(-294.096 -356.612)" fill="#fff"/>
                                <path id="style-2-light" d="M402.117,121.864a1.164,1.164,0,0,0,.59-.158l4.09-2.361a1.181,1.181,0,0,0-1.095-2.092q-.044.023-.086.05l-4.09,2.361a1.181,1.181,0,0,0,.59,2.2v0Z" transform="translate(-356.557 -104.154)" fill="#fff"/>
                                <path id="style-2-light" d="M36.125,330.633l-4.09,2.361a1.181,1.181,0,0,0,.59,2.2,1.164,1.164,0,0,0,.59-.158l4.09-2.361a1.181,1.181,0,1,0-1.095-2.092q-.044.023-.086.05Z" transform="translate(-27.964 -293.873)" fill="#fff"/>
                                <path id="style-2-light" d="M31.67,119.325l4.09,2.361a1.164,1.164,0,0,0,.588.158,1.181,1.181,0,0,0,.59-2.2l-4.09-2.361a1.181,1.181,0,0,0-1.267,1.993q.042.027.086.05Z" transform="translate(-27.599 -104.135)" fill="#fff"/>
                                <path id="style-2-light" d="M406.453,332.976l-4.09-2.361a1.181,1.181,0,0,0-1.267,1.993c.028.018.057.034.086.05l4.09,2.361a1.164,1.164,0,0,0,.59.158,1.181,1.181,0,0,0,.59-2.2v0Z" transform="translate(-356.212 -293.855)" fill="#fff"/>
                                <path id="style-2-light" d="M331.27,37.793a1.164,1.164,0,0,0,.59.158,1.181,1.181,0,0,0,1.025-.59l2.361-4.09a1.181,1.181,0,0,0-2.045-1.181l-2.361,4.09A1.181,1.181,0,0,0,331.27,37.793Z" transform="translate(-294.078 -28.02)" fill="#fff"/>
                                <path id="style-2-light" d="M121.465,401.1a1.181,1.181,0,0,0-1.613.432l-2.361,4.09a1.181,1.181,0,0,0,.432,1.613,1.164,1.164,0,0,0,.59.158,1.181,1.181,0,0,0,1.025-.59l2.361-4.09a1.181,1.181,0,0,0-.434-1.612Z" transform="translate(-104.345 -356.557)" fill="#fff"/>
                            </g>
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="52.335" height="55.288" viewBox="0 0 52.335 55.288">
                            <path id="style-2-light" data-name="moon (2)" d="M27.664,55.285A26.915,26.915,0,0,0,52.358,39.709a21.107,21.107,0,0,1-8.9,1.756A21.743,21.743,0,0,1,21.741,19.746,22.508,22.508,0,0,1,33.453.329,41.274,41.274,0,0,0,27.664,0a27.641,27.641,0,0,0,0,55.283Zm0,0" transform="translate(-0.023 0.001)" fill="#fff"/>
                        </svg>
                    </div>

                </div>
            </label>
        </div>
		<?php
		return ob_get_clean();
	}
    /**
     * Switch style 8
     *
     * @since 1.0.0
     * @return void
     */
    
	public static function style_8($preview = false) {
		ob_start();
		?>
        <div class="darkluplite-square-switch-container darkluplite-dark-ignore">
            <label class="darkluplite-square-switch style8 darkluplite-dark-ignore">
                <input type="checkbox" class="toggle-checkbox <?php echo (!$preview) ? "switch-trigger" : ""; ?>">
                <div class="toggle-btn darkluplite-dark-ignore">
                    <svg xmlns="http://www.w3.org/2000/svg" width="70.067" height="70.067" viewBox="0 0 70.067 70.067">
                        <circle id="style-8-light" cx="22.848" cy="22.848" r="22.848" transform="translate(12.185 12.185)" fill="#3b3b3b"/>
                        <g>
                            <path id="style-8-light" d="M236.189,9.139a1.523,1.523,0,0,0,1.523-1.523V1.523a1.523,1.523,0,0,0-3.046,0V7.616A1.523,1.523,0,0,0,236.189,9.139Z" transform="translate(-201.156)" fill="#3b3b3b"/>
                            <path id="style-8-light" d="M236.189,426.667a1.523,1.523,0,0,0-1.523,1.523v6.093a1.523,1.523,0,0,0,3.046,0V428.19A1.523,1.523,0,0,0,236.189,426.667Z" transform="translate(-201.156 -365.739)" fill="#3b3b3b"/>
                            <path id="style-8-light" d="M434.282,234.667h-6.093a1.523,1.523,0,1,0,0,3.046h6.093a1.523,1.523,0,0,0,0-3.046Z" transform="translate(-365.739 -201.157)" fill="#3b3b3b"/>
                            <path id="style-8-light" d="M9.139,236.189a1.523,1.523,0,0,0-1.523-1.523H1.523a1.523,1.523,0,0,0,0,3.046H7.616A1.523,1.523,0,0,0,9.139,236.189Z" transform="translate(0 -201.156)" fill="#3b3b3b"/>
                            <path id="style-8-light" d="M120.631,39.058a1.523,1.523,0,0,0,1.322.762,1.5,1.5,0,0,0,.762-.2,1.523,1.523,0,0,0,.557-2.081l-3.046-5.276a1.523,1.523,0,0,0-2.638,1.523Z" transform="translate(-100.626 -27.006)" fill="#3b3b3b"/>
                            <path id="style-8-light" d="M333.539,401.752a1.523,1.523,0,0,0-2.638,1.523l3.046,5.276a1.523,1.523,0,0,0,1.322.762,1.5,1.5,0,0,0,.762-.2,1.523,1.523,0,0,0,.557-2.081Z" transform="translate(-283.477 -343.737)" fill="#3b3b3b"/>
                            <path id="style-8-light" d="M402.46,123.241a1.5,1.5,0,0,0,.762-.2l5.276-3.046a1.523,1.523,0,0,0-1.412-2.7q-.057.03-.111.064L401.7,120.4a1.523,1.523,0,0,0,.762,2.842v0Z" transform="translate(-343.683 -100.393)" fill="#3b3b3b"/>
                            <path id="style-8-light" d="M37.482,330.685l-5.276,3.046a1.523,1.523,0,0,0,.762,2.842,1.5,1.5,0,0,0,.762-.2l5.276-3.046a1.523,1.523,0,0,0-1.412-2.7q-.057.03-.111.064Z" transform="translate(-26.954 -283.263)" fill="#3b3b3b"/>
                            <path id="style-8-light" d="M31.854,119.972l5.276,3.046a1.5,1.5,0,0,0,.759.2,1.523,1.523,0,0,0,.762-2.842l-5.276-3.046A1.523,1.523,0,0,0,31.74,119.9q.054.034.111.064Z" transform="translate(-26.602 -100.375)" fill="#3b3b3b"/>
                            <path id="style-8-light" d="M408.166,333.714l-5.276-3.046a1.523,1.523,0,0,0-1.634,2.571q.054.035.111.064l5.276,3.046a1.5,1.5,0,0,0,.762.2,1.523,1.523,0,0,0,.762-2.842v0Z" transform="translate(-343.351 -283.245)" fill="#3b3b3b"/>
                            <path id="style-8-light" d="M331.44,39.617a1.5,1.5,0,0,0,.762.2,1.524,1.524,0,0,0,1.322-.762l3.046-5.276a1.523,1.523,0,0,0-2.638-1.523l-3.046,5.276A1.523,1.523,0,0,0,331.44,39.617Z" transform="translate(-283.46 -27.008)" fill="#3b3b3b"/>
                            <path id="style-8-light" d="M122.664,401.142a1.523,1.523,0,0,0-2.081.557l-3.046,5.276a1.523,1.523,0,0,0,.557,2.081,1.5,1.5,0,0,0,.762.2,1.523,1.523,0,0,0,1.322-.762l3.046-5.276a1.523,1.523,0,0,0-.56-2.08Z" transform="translate(-100.577 -343.684)" fill="#3b3b3b"/>
                        </g>
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" width="65.636" height="69.339" viewBox="0 0 65.636 69.339">
                        <path id="style-8-light" d="M34.689,69.336A33.756,33.756,0,0,0,65.659,49.8,26.471,26.471,0,0,1,54.5,52,27.268,27.268,0,0,1,27.26,24.765,28.228,28.228,0,0,1,41.949.413,51.763,51.763,0,0,0,34.689,0a34.666,34.666,0,0,0,0,69.332Zm0,0" transform="translate(-0.023 0.001)" fill="#3b3b3b"/>
                    </svg>
                </div>
            </label>
        </div>
		<?php
		return ob_get_clean();
	}
	
}

