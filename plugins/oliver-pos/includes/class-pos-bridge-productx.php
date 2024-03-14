<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if someone accessed directly.
}

/**
 * class OP_Points_Rewards_Extension_page
 * Description manage extension page of plugin
 */
class Pos_Bridge_Productx {

    // op points and rewards manager
    private $op_bridge;

    /**
     * Class construct
     *
     * @since 1.0.0
     * @param object $op_points_rewards OP_Points_Rewards
     * @return void void
     */
    public function __construct($op_bridge = null)
    {
        $this->op_bridge = $op_bridge;

        // call actions
        $this->actions();

        // call render_style
        // $this->render_style();

        // call render_html
        $this->render_html();

        // call render_script
        $this->render_script();
        
        exit;
    }

    /**
     * Add actions
     *
     * @since 1.0.0
     * @return void void
     */
    public function actions()
    {
        // action goes here
    }

    /**
     * render style
     *
     * @since 1.0.0
     * @return void void
     */
    public function render_style($var = null)
    {

    }

     /**
     * render html
     *
     * @since 1.0.0
     * @return void void
     */
    public function render_html($var = null)
    {
        $url = '';

        if ( $_GET['product'] ) {
            $url = $_GET['product'];
        }

        echo file_get_contents( $url );
    }

    /**
     * create script
     *
     * @since 1.0.0
     * @return void void
     */
    public function render_script($var = null)
    {
        ?>
            <script>
                var oprOliverExtensionTargetOrigin = "<?php echo get_option('op_points_rewards_extenstion_origin_url') ?>";

                /**
                 * This function run on page load
                 *
                 * @since 1.0.0
                 * @return void void
                 */
                window.addEventListener('load', (event) => {
                    document.getElementsByTagName("header")[0].style.display = "none";
                    document.getElementsByTagName("footer")[0].style.display = "none";
                    document.querySelector("section.related").style.display = "none";
                    document.querySelector("div.woocommerce-tabs").style.display = "none";
                });

                /**
                 * Received messages send by origin
                 *
                 * @since 1.0.0
                 * @return void void
                 */
                window.addEventListener('message', function(e) {
                    if (e.origin !== oprOliverExtensionTargetOrigin) {
                        console.log("Invalid origin " + e.origin);
                    } else {
                        console.log("received data", JSON.parse(e.data))

                        if (typeof e.data !== "undefined") {
                            oprGetPoints(e.data);
                        }
                    }
                }, false);

                /**
                 * Bind DOM events
                 *
                 * @since 1.0.0
                 * @return void void
                 */
                function bindEvent(element, eventName, eventHandler) {
                    element.addEventListener(eventName, eventHandler, false);
                }

                bindEvent(document.querySelector("form.cart"), 'submit', function(e){
                    let productx_id = document.querySelector("form.cart").elements.namedItem("add-to-cart").value;

                    let oprXhttp = new XMLHttpRequest();
                    let oPosCartUrl = `<?php echo home_url('wp-json/pos-bridge/set-oliver-pos-productx-id') ?>`;
                    oprXhttp.open("POST", `${oPosCartUrl}`, true);
                    //Send the proper header information along with the request
                    oprXhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    oprXhttp.send(`productx_id=${productx_id}`);
                    
                    return true;
                });
                
                // ===== Old way =====
                // bindEvent(document.querySelector("form.cart"), 'submit', function(e){
                //     e.preventDefault();
                //     let getFormElements = document.querySelector("form.cart").elements;
                //     console.log("form elements", getFormElements);

                //     let getFormElementsData = {};

                //     for(var i=0; i< getFormElements.length; i++){
                //         getFormElementsData[getFormElements[i].name] = getFormElements[i].value;
                //     }
                    
                //     console.log("form elements", getFormElementsData);

                //     return false;
                // });

                /**
                 * Send a message to the parent
                 *
                 * @since 1.0.0
                 * @return void void
                 */
                var sendMessage = function (msg) {
                    console.log("send data", msg);
                    window.parent.postMessage(msg, '*');
                };

            </script>
        <?php
    }

}