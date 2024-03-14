<?php 

namespace VENDOR\RECAPTCHA_GDPR_COMPLIANT;

defined( 'ABSPATH' ) or die( 'Are you ok?' );

/**
 * Class Message_Page: Reflects the module for the administration of messages
 */

class Message_Page
{
    /** Holding the instance of this class */
    public static $instance;

    private $listed_actions = null;
    private $listed_patterns = null;
    private $whitelisted_actions = null;
    private $whitelisted_sites = null;
    private $whitelisted_ips = null;
    private $hidden_actions = null;
    private $hidden_patterns = null;
    private $explicit_mode = null;

    /** Get an instance of the class
     * 
     */
    public static function getInstance()
    {
        require_once dirname( __FILE__ ) . '/class-option.php';

        if ( ! self::$instance instanceof self ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /** Constructor of the class
     */
    public function __construct()
    {
        add_action( 'init', [ $this, 'run' ] );
        add_action( 'wp_ajax_render_messages', [ $this, 'render_messages' ] );
        add_action( 'wp_ajax_render_message', [ $this, 'render_message' ] );
        add_action( 'wp_ajax_change_message_type', [ $this, 'change_message_type' ] );
        add_action( 'wp_ajax_delete_message', [ $this, 'delete_message' ] );
        add_action( 'wp_ajax_save_whitelist_parameter', [ $this, 'save_whitelist_parameter_callback' ] );
        add_action( 'wp_ajax_save_list_parameter', [ $this, 'save_list_parameter_callback' ] );
        add_action( 'wp_ajax_save_pattern', [ $this, 'save_pattern_callback' ] );
    }


    /** Wenn the plugin is run
     */
    public function run()
    {
        add_action( 'admin_init', [ $this, 'admin_init'] );
        add_action( 'admin_menu', [ $this, 'admin_menu'] );
    }

    /** Initialize the admin area*/
    public function admin_init(){
        //Registers the stly for the message_page but don't enqueue it yet
        wp_register_style( 'messagePageStyle', plugins_url( '/css/style_message_page.css', __DIR__ ), [], '1.0.2' );
    }

    /** Add the admin menu for messages
     *
     */
    public function admin_menu()
    {
        $main_menu_entry = __( 'ReCaptcha GDPR Messages', 'gdpr-compliant-recaptcha-for-all-forms' );
        $messages_menu_entry = __( 'Messages', 'gdpr-compliant-recaptcha-for-all-forms' );
        $spam_menu_entry = __( 'Spam', 'gdpr-compliant-recaptcha-for-all-forms' );
        $trash_menu_entry = __( 'Trash', 'gdpr-compliant-recaptcha-for-all-forms' );
        $analyse_menu_entry = __( 'Analytic Box', 'gdpr-compliant-recaptcha-for-all-forms' );

        $messages_count = Option::get_rows( '' , 1 );
        $spam_count = Option::get_rows( '' , 2 );
        $trash_count = Option::get_rows( '' , 3 );
        $analyse_count = Option::get_rows( '' , 4 );
        $overall_count = $messages_count + $spam_count + $trash_count + $analyse_count;

        function entry_counter( $counter ){
            return '<span class="update-plugins count-' . $counter . '"><span class="plugin-count">' . $counter . '</span></span>';
        }

        $page = add_menu_page(
            $main_menu_entry,
            $main_menu_entry . entry_counter( $overall_count ),
            'edit_pages',
            Option::PREFIX . 'messages',
            [ $this, 'message_page' ],
            'dashicons-email-alt2',
            get_option( Option::POW_MENU_POSITION ) ? get_option( Option::POW_MENU_POSITION ) : 0
        );
        add_action( "admin_print_styles-{$page}", [ $this, 'message_page_styles'] );

        $page = add_submenu_page(
            Option::PREFIX . 'messages',
            $messages_menu_entry,
            $messages_menu_entry . entry_counter( $messages_count ),
            'edit_pages',
            Option::PREFIX . 'messages',
            [$this, 'message_page']
        );
        add_action( "admin_print_styles-{$page}", [ $this, 'message_page_styles'] );

        $page = add_submenu_page(
            Option::PREFIX . 'messages',
            $spam_menu_entry,
            $spam_menu_entry . entry_counter( $spam_count ),
            'edit_pages',
            Option::PREFIX . 'spam',
            [$this, 'spam_page']
        );
        add_action( "admin_print_styles-{$page}", [ $this, 'message_page_styles'] );

        $page = add_submenu_page(
            Option::PREFIX . 'messages',
            $trash_menu_entry,
            $trash_menu_entry . entry_counter( $trash_count ),
            'edit_pages',
            Option::PREFIX . 'trash',
            [ $this, 'trash_page' ]
        );
        add_action( "admin_print_styles-{$page}", [ $this, 'message_page_styles' ] );

        $page = add_submenu_page(
            Option::PREFIX . 'messages',
            $analyse_menu_entry,
            $analyse_menu_entry . entry_counter( $analyse_count ),
            'edit_pages',
            Option::PREFIX . 'analyse',
            [ $this, 'analyse_page' ]
        );
        add_action( "admin_print_styles-{$page}", [ $this, 'message_page_styles' ] );
    }

    /**Add style only for message page */
    function message_page_styles() {
        wp_enqueue_style( 'messagePageStyle' );
    }

    /*Initialize message_page*/
    public function message_page() {
        $this->render_message_page( 1 );
    }

    /*Initialize spam_page*/
    public function spam_page() {
        $this->render_message_page( 2 );
    }

    /*Initialize trash_page*/
    public function trash_page() {
        $this->render_message_page( 3 );
    }

    /*Initialize analyse_page*/
    public function analyse_page() {
        $this->render_message_page( 4 );
    }

    /** Add the page for saved messages */
    public function render_message_page( $messageType ) {
        $this->explicit_mode = get_option( Option::POW_EXPLICIT_MODE );
        $titles = [
            1 => __( 'Messages', 'gdpr-compliant-recaptcha-for-all-forms' ),
            2 => __( 'Spam', 'gdpr-compliant-recaptcha-for-all-forms' ),
            3 => __( 'Trash', 'gdpr-compliant-recaptcha-for-all-forms' ),
            4 => __( 'Analytic Box', 'gdpr-compliant-recaptcha-for-all-forms' ),
        ];
        $search = null;
        if ( array_key_exists( 'search', $_POST ) )
            $search = filter_var( $_POST[ 'search' ], FILTER_UNSAFE_RAW );
        $rows = Option::get_rows( $search , $messageType );
        $this->show_evaluation_request( $messageType, $rows );
        ?>
        <h1><?php echo( $titles[ $messageType ] ); ?></h1>
        <div class="centered-spinner" hidden>
                <center>
                    <strong><?php _e( 'Loading' , 'gdpr-compliant-recaptcha-for-all-forms' ); ?>...</strong><br><br>
                    <img src="/wp-includes/js/tinymce/skins/lightgray/img/loader.gif" alt="<?php _e( 'Loading' , 'gdpr-compliant-recaptcha-for-all-forms' ); ?>">
                </center>
        </div>
        <?php
        if( $messageType == 4 ){
            ?>
            <div class="filter">
                <div class="filter_head">
                    <label>    
                    <h3>Filters</h3>
                    Show messages that are already in scope of specific rules</label>
                </div><br>
                <div class="filter_checkboxes">
            <?php
            if( $this->explicit_mode ){
            ?>
                <label>
                    <input type="checkbox" id="listedActions" onchange="messageSearch(1);"> <?php echo( sprintf( __( "Explicit actions %s" , 'gdpr-compliant-recaptcha-for-all-forms' ),'<label style="font-size: 30px;"><b>⚙️✔️</b></label>' ) ); ?>
                </label>
                <label>
                    <input type="checkbox" id="listedPatterns" onchange="messageSearch(1);"> <?php echo( sprintf( __( "Explicit patterns %s" , 'gdpr-compliant-recaptcha-for-all-forms' ),'<label style="font-size: 30px;"><b>🔍✔️</b></label>' ) ); ?>
                </label>
                <label>
                    <input type="checkbox" id="whitelistedActions" onchange="messageSearch(1);"> <?php echo( sprintf( __( "Whitelisted actions %s" , 'gdpr-compliant-recaptcha-for-all-forms' ),'<label style="font-size: 30px;"><b>🚫</b></label>' ) ); ?>
                </label>
                <label>
                    <input type="checkbox" id="whitelistedSites" onchange="messageSearch(1);"> <?php echo( sprintf( __( "Whitelisted sites %s" , 'gdpr-compliant-recaptcha-for-all-forms' ),'<label style="font-size: 30px;"><b>📄</b></label>' ) ); ?>
                </label>
                <label>
                    <input type="checkbox" id="whitelistedIPs" onchange="messageSearch(1);"> <?php echo( sprintf( __( "Whitelisted IPs %s" , 'gdpr-compliant-recaptcha-for-all-forms' ),'<label style="font-size: 30px;"><b>🌐</b></label>' ) ); ?>
                </label>
                <label>
                    <input type="checkbox" id="hiddenActions" onchange="messageSearch(1);"> <?php echo( sprintf( __( "Hidden actions %s" , 'gdpr-compliant-recaptcha-for-all-forms' ),'<label style="font-size: 30px;"><b>⚙️🚫</b></label>' ) ); ?>
                </label>
                <label>
                    <input type="checkbox" id="hiddenPatterns" onchange="messageSearch(1);"> <?php echo( sprintf( __( "Hidden patterns %s" , 'gdpr-compliant-recaptcha-for-all-forms' ),'<label style="font-size: 30px;"><b>🔍🚫</b></label>' ) ); ?>
                </label>
            <?php
            }else{
                ?>
                <label>
                    <input type="checkbox" id="whitelistedActions" onchange="messageSearch(1);"> <?php echo( sprintf( __( "Whitelisted actions %s" , 'gdpr-compliant-recaptcha-for-all-forms' ),'<label style="font-size: 30px;"><b>🚫</b></label>' ) ); ?>
                </label>
                <label>
                    <input type="checkbox" id="whitelistedSites" onchange="messageSearch(1);"> <?php echo( sprintf( __( "Whitelisted sites %s" , 'gdpr-compliant-recaptcha-for-all-forms' ),'<label style="font-size: 30px;"><b>📄</b></label>' ) ); ?>
                </label>
                <label>
                    <input type="checkbox" id="whitelistedIPs" onchange="messageSearch(1);"> <?php echo( sprintf( __( "Whitelisted IPs %s" , 'gdpr-compliant-recaptcha-for-all-forms' ),'<label style="font-size: 30px;"><b>🌐</b></label>' ) ); ?>
                </label>
                <label>
                    <input type="checkbox" id="hiddenActions" onchange="messageSearch(1);"> <?php echo( sprintf( __( "Hidden actions %s" , 'gdpr-compliant-recaptcha-for-all-forms' ),'<label style="font-size: 30px;"><b>⚙️🚫</b></label>' ) ); ?>
                </label>
                <label>
                    <input type="checkbox" id="hiddenPatterns" onchange="messageSearch(1);"> <?php echo( sprintf( __( "Hidden patterns %s" , 'gdpr-compliant-recaptcha-for-all-forms' ),'<label style="font-size: 30px;"><b>🔍🚫</b></label>' ) ); ?>
                </label>
            <?php
            }
        ?>
        <br>
        </div></div><br>
        <?php
        }
        ?>
        <div class="action_bar">
            <?php $this->get_action_bar( $messageType ); ?>
        </div>
        <div class = "paginator"></div>
        <br><div id="results"></div><br>
        <div class = "paginator"></div>
        <script type="text/javascript">

            function showSpinner() {
                var spinner = document.querySelector('.centered-spinner');
                spinner.removeAttribute('hidden'); // Remove the hidden attribute
            }

            function hideSpinner() {
                var spinner = document.querySelector('.centered-spinner');
                spinner.setAttribute('hidden', true); // Set the hidden attribute
            }

            function showAlert(message) {
                        var alertDiv = document.createElement('div');
                        alertDiv.className = 'centered-alert';
                        alertDiv.textContent = message;

                        document.body.appendChild(alertDiv);

                        setTimeout(function () {
                            document.body.removeChild(alertDiv);
                        }, 5000); // Adjust the timeout value as needed
            }

            function showSuccess(message) {
                        var alertDiv = document.createElement('div');
                        alertDiv.className = 'centered-success';
                        alertDiv.textContent = message;

                        document.body.appendChild(alertDiv);

                        setTimeout(function () {
                            document.body.removeChild(alertDiv);
                        }, 5000); // Adjust the timeout value as needed
            }

            document.addEventListener('DOMContentLoaded', function () {
                messageSearch(1);
                document.querySelector('.messageSearch').addEventListener('keyup', function() {
                    messageSearch(1);
                });
                document.querySelector('.check_messages').addEventListener('click', function() {
                    var checkboxes = document.querySelectorAll('.check_message');
                    for (var i = 0; i < checkboxes.length; i++) {
                        checkboxes[i].checked = this.checked;
                    }
                });
            });

            function messageSearch( page ) {
                var search = document.querySelector('.messageSearch').value;
                var listedActions = document.querySelector('#listedActions') && document.querySelector('#listedActions').checked ? '&listedActions=' + document.querySelector('#listedActions').checked : '';
                var listedPatterns = document.querySelector('#listedPatterns') && document.querySelector('#listedPatterns').checked ? '&listedPatterns=' + document.querySelector('#listedPatterns').checked : '';
                var whitelistedActions = document.querySelector('#whitelistedActions') && document.querySelector('#whitelistedActions').checked ? '&whitelistedActions=' + document.querySelector('#whitelistedActions').checked : '';
                var whitelistedSites = document.querySelector('#whitelistedSites') && document.querySelector('#whitelistedSites').checked ? '&whitelistedSites=' + document.querySelector('#whitelistedSites').checked : '';
                var whitelistedIPs = document.querySelector('#whitelistedIPs') && document.querySelector('#whitelistedIPs').checked ? '&whitelistedIPs=' + document.querySelector('#whitelistedIPs').checked : '';
                var hiddenActions = document.querySelector('#hiddenActions') && document.querySelector('#hiddenActions').checked ? '&hiddenActions=' + document.querySelector('#hiddenActions').checked : '';
                var hiddenPatterns = document.querySelector('#hiddenPatterns') && document.querySelector('#hiddenPatterns').checked ? '&hiddenPatterns=' + document.querySelector('#hiddenPatterns').checked : '';
                showSpinner();
                fetch('<?php echo( admin_url( 'admin-ajax.php' ) ); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `action=render_messages&page=${page}&search=${search}&messageType=${<?php echo( esc_js( $messageType ) ); ?>}&search_nonce=${'<?php echo( wp_create_nonce( 'render-messages_'.esc_js( $messageType ) ) ); ?>'}${listedActions}${listedPatterns}${whitelistedActions}${whitelistedSites}${whitelistedIPs}${hiddenActions}${hiddenPatterns}`
                })
                .then(response => response.json())
                .then(function(response) {
                    if( response.success == 1 ) {
                        document.querySelector('#results').innerHTML = response.result;
                        document.querySelector('.paginator').innerHTML = response.paginator;
                        var akkordeon = document.getElementsByClassName( 'akkordeonButton' );
                        for (var x = 0; x < akkordeon.length; x++) {
                            akkordeon[x].addEventListener( 'click', function() {
                                this.classList.toggle( 'akkordeonButtonAktiv' );
                                var akkordeonEinheit = this.nextElementSibling;
                                if ( akkordeonEinheit.style.display === 'block' ) {
                                    akkordeonEinheit.style.display = 'none';
                                } else {
                                    akkordeonEinheit.style.display = 'block';
                                }
                            });
                            akkordeon[x].addEventListener( 'click', function() {
                                var id = this.id.split( '_' )[ 1 ];
                                getDetail(id);
                            }, { once: true } );
                        }
                    } else {
                        showAlert(response.error_message);
                        location.reload();
                    }
                    hideSpinner();
                });
            }

            function getDetail(id) {
                var search = document.querySelector('.messageSearch').value;
                showSpinner();
                fetch('<?php echo( admin_url( 'admin-ajax.php' ) ); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `action=render_message&messageType=${ <?php echo( esc_js( $messageType ) ); ?> }&messageID=${ id }&message_nonce=${ document.querySelector(`#messageNonce${ id }`).value }`
                })
                .then(response => response.json())
                .then(function (response) {
                    if (response.success === 1) {
                        const elements = document.createRange().createContextualFragment(response.result);
                        elements.querySelectorAll(".returnAttribute").forEach(function(element) {
                            const regex = new RegExp(search, "gi");
                            element.innerHTML = element.textContent.replace(regex, function(x) {
                            return '<span style="background-color:yellow;">' + x + "</span>";
                            });
                        });
                        document.querySelector("#messageDetails" + id).innerHTML = "";
                        document.querySelector("#messageDetails" + id).appendChild(elements);
                    } else {
                        showAlert(response.error_message);
                        location.reload();
                    }
                    hideSpinner();
                });
            }

            function doDeleteAll( e ){
                e.preventDefault();
                var confirmed = confirm("<?php 
                                            $currentTitle = $titles[ $messageType ];
                                            _e( sprintf( 'You are about to delete all messages from \"%s\". Are you sure?', $currentTitle ) , 'gdpr-compliant-recaptcha-for-all-forms' ); 
                                            ?>");

                // If the user clicked "Yes", process the form
                if (confirmed) {
                    var search = document.querySelector('.messageSearch').value;
                    deleteMessage( null, search );
                }
            }

            function doMessageAction( e ){
                e.preventDefault();
                if ( document.querySelector('#messageAction').value !== 'bulk' ) {
                    var search = document.querySelector('.messageSearch').value;
                    var messageAction = document.querySelector('#messageAction').value;
                    var form = messageAction.split('_')[0];
                    var changeType = messageAction.split('_')[1];
                    if (form == 'deleteForm') {
                        deleteMessages( form, search );
                    } else {
                        moveMessages( changeType, form, search );
                    }
                }
            }

            function moveMessages( changeType, form, search ){

                let check_message = document.querySelectorAll('.check_message:checked');

                let messages = Array.from(check_message).map(el => {
                    let id = el.id.split('_')[2];
                    let formFinal = document.querySelector('#' + form + id);
                    let serialized = Array.from(new FormData(formFinal)).map(kv => kv.join("=")).join("&");
                    return serialized;
                });

                document.querySelectorAll('.check_messages').forEach(function(el) {
                    el.checked = false;
                });

                if( messages.length > 0 ){
                    changeMessageType( messages, changeType, search );
                }
            }

            function moveMessage( e, form, messageID, changeType ){
                e.preventDefault();
                var search = document.querySelector('.messageSearch').value;
                var messages = [];

                const formFinal = document.querySelector('#' + form + messageID);
                const formData = new FormData(formFinal);
                let serialized = "";
                for (const [key, value] of formData.entries()) {
                    serialized += key + "=" + value + "&";
                }
                serialized = serialized.slice(0, -1);

                messages.push( serialized );
                changeMessageType( messages, changeType, search );
            }

            function changeMessageType( messages, changeType, search ) {

                if (document.readyState === 'complete') {
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '<?php echo( admin_url( 'admin-ajax.php' ) ); ?>', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    
                    xhr.onreadystatechange = function() {
                        if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                            var response = JSON.parse(this.responseText);
                            if (response.success === 1) {
                                document.querySelector('#results').innerHTML = response.result;
                                document.querySelector('.paginator').innerHTML = response.paginator;
                                var akkordeon = document.getElementsByClassName('akkordeonButton');
                                for (var x = 0; x < akkordeon.length; x++) {
                                    akkordeon[x].addEventListener('click', function() {
                                        this.classList.toggle('akkordeonButtonAktiv');
                                        var akkordeonEinheit = this.nextElementSibling;
                                        if (akkordeonEinheit.style.display === 'block') {
                                        akkordeonEinheit.style.display = 'none';
                                        } else {
                                        akkordeonEinheit.style.display = 'block';
                                        }
                                    });
                                    akkordeon[x].addEventListener('click', function() {
                                        var id = this.id.split('_')[1];
                                        getDetail(id);
                                    }, { once: true });
                                }
                                showSuccess("<?php _e( 'Message moved successfully!', 'gdpr-compliant-recaptcha-for-all-forms' ) ?> ");
                            } else {
                                showAlert(response.error_message);
                            }
                            hideSpinner();
                        }
                    };
                    var listedActions = document.querySelector('#listedActions') && document.querySelector('#listedActions').checked ? '&listedActions=' + document.querySelector('#listedActions').checked : '';
                    var listedPatterns = document.querySelector('#listedPatterns') && document.querySelector('#listedPatterns').checked ? '&listedPatterns=' + document.querySelector('#listedPatterns').checked : '';
                    var whitelistedActions = document.querySelector('#whitelistedActions') && document.querySelector('#whitelistedActions').checked ? '&whitelistedActions=' + document.querySelector('#whitelistedActions').checked : '';
                    var whitelistedSites = document.querySelector('#whitelistedSites') && document.querySelector('#whitelistedSites').checked ? '&whitelistedSites=' + document.querySelector('#whitelistedSites').checked : '';
                    var whitelistedIPs = document.querySelector('#whitelistedIPs') && document.querySelector('#whitelistedIPs').checked ? '&whitelistedIPs=' + document.querySelector('#whitelistedIPs').checked : '';
                    var hiddenActions = document.querySelector('#hiddenActions') && document.querySelector('#hiddenActions').checked ? '&hiddenActions=' + document.querySelector('#hiddenActions').checked : '';
                    var hiddenPatterns = document.querySelector('#hiddenPatterns') && document.querySelector('#hiddenPatterns').checked ? '&hiddenPatterns=' + document.querySelector('#hiddenPatterns').checked : '';
                    showSpinner();
                    xhr.send(
                        "action=change_message_type" +
                        "&messageType=<?php echo( esc_js( $messageType ) ); ?>" +
                        "&changeType=" + changeType +
                        "&messages=" + encodeURIComponent(JSON.stringify(messages)) +
                        "&search=" + search +
                        "&search_nonce=<?php echo( wp_create_nonce( 'render-messages_'.esc_js( $messageType ) ) ); ?>" +
                        listedActions +
                        listedPatterns +
                        whitelistedActions +
                        whitelistedSites +
                        whitelistedIPs +
                        hiddenActions +
                        hiddenPatterns
                    );
                }

            }

            function deleteMessages( form, search ){

                let check_message = document.querySelectorAll('.check_message:checked');

                let messages = Array.from(check_message).map(el => {
                    let id = el.id.split('_')[2];
                    let formFinal = document.querySelector('#' + form + id);
                    let serialized = Array.from(new FormData(formFinal)).map(kv => kv.join("=")).join("&");
                    return serialized;
                });

                document.querySelectorAll('.check_messages').forEach(function(el) {
                    el.checked = false;
                });
                
                if( messages.length > 0 ){
                    deleteMessage( messages, search );
                }
            }

            function deleteSingleMessage( e, form, messageID ){
                e.preventDefault();
                var search = document.querySelector('.messageSearch').value;
                var messages = [];


                const formFinal = document.querySelector('#' + form + messageID);
                const formData = new FormData(formFinal);
                let serialized = "";
                for (const [key, value] of formData.entries()) {
                    serialized += key + "=" + value + "&";
                }
                serialized = serialized.slice(0, -1);

                messages.push( serialized );

                deleteMessage( messages, search );
            }

            function deleteMessage( messages, search ) {

                if (document.readyState === 'complete') {
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", '<?php echo( admin_url( "admin-ajax.php" ) ); ?>');
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            var response = JSON.parse(xhr.responseText);
                            if (response.success == 1) {
                                document.querySelector("#results").innerHTML = response.result;
                                document.querySelector(".paginator").innerHTML = response.paginator;

                                var akkordeon = document.getElementsByClassName("akkordeonButton");
                                for (var i = 0; i < akkordeon.length; i++) {
                                akkordeon[i].addEventListener("click", function() {
                                    this.classList.toggle("akkordeonButtonAktiv");
                                    var akkordeonEinheit = this.nextElementSibling;
                                    if (akkordeonEinheit.style.display === "block") {
                                    akkordeonEinheit.style.display = "none";
                                    } else {
                                    akkordeonEinheit.style.display = "block";
                                    }
                                });
                                akkordeon[i].addEventListener("click", function() {
                                    var id = this.id.split("_")[1];
                                    getDetail(id);
                                }, { once: true });
                                }
                                showSuccess("<?php _e( 'Message deleted successfully!', 'gdpr-compliant-recaptcha-for-all-forms' ) ?> ");
                            } else {
                                showAlert(response.error_message);
                            }
                        }
                        hideSpinner();
                    };
                    var listedActions = document.querySelector('#listedActions') && document.querySelector('#listedActions').checked ? '&listedActions=' + document.querySelector('#listedActions').checked : '';
                    var listedPatterns = document.querySelector('#listedPatterns') && document.querySelector('#listedPatterns').checked ? '&listedPatterns=' + document.querySelector('#listedPatterns').checked : '';
                    var whitelistedActions = document.querySelector('#whitelistedActions') && document.querySelector('#whitelistedActions').checked ? '&whitelistedActions=' + document.querySelector('#whitelistedActions').checked : '';                
                    var whitelistedSites = document.querySelector('#whitelistedSites') && document.querySelector('#whitelistedSites').checked ? '&whitelistedSites=' + document.querySelector('#whitelistedSites').checked : '';
                    var whitelistedIPs = document.querySelector('#whitelistedIPs') && document.querySelector('#whitelistedIPs').checked ? '&whitelistedIPs=' + document.querySelector('#whitelistedIPs').checked : '';
                    var hiddenActions = document.querySelector('#hiddenActions') && document.querySelector('#hiddenActions').checked ? '&hiddenActions=' + document.querySelector('#hiddenActions').checked : '';
                    var hiddenPatterns = document.querySelector('#hiddenPatterns') && document.querySelector('#hiddenPatterns').checked ? '&hiddenPatterns=' + document.querySelector('#hiddenPatterns').checked : '';
                    showSpinner();
                    xhr.send(
                        "action=delete_message" +
                        "&messageType=<?php echo( esc_js( $messageType ) ); ?>" +
                        "&messages=" + encodeURIComponent(JSON.stringify(messages)) +
                        "&search=" + search +
                        "&search_nonce=<?php echo( wp_create_nonce( "render-messages_".esc_js( $messageType ) )); ?>" +
                        listedActions +
                        listedPatterns +
                        whitelistedActions +
                        whitelistedSites +
                        whitelistedIPs +
                        hiddenActions +
                        hiddenPatterns
                    );
                }

            }

            function saveListParameter(e, listKey, buttonId, hide = false) {
                e.preventDefault();
                var xhr = new XMLHttpRequest();
                xhr.open("POST", '<?php echo( admin_url( "admin-ajax.php" ) ); ?>', true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success == 1) {
                            //if(hide){
                            document.querySelector("#results").innerHTML = response.result;
                            document.querySelector(".paginator").innerHTML = response.paginator;

                            var akkordeon = document.getElementsByClassName("akkordeonButton");
                            for (var i = 0; i < akkordeon.length; i++) {
                                akkordeon[i].addEventListener("click", function() {
                                    this.classList.toggle("akkordeonButtonAktiv");
                                    var akkordeonEinheit = this.nextElementSibling;
                                    if (akkordeonEinheit.style.display === "block") {
                                    akkordeonEinheit.style.display = "none";
                                    } else {
                                    akkordeonEinheit.style.display = "block";
                                    }
                                });
                                akkordeon[i].addEventListener("click", function() {
                                    var id = this.id.split("_")[1];
                                    getDetail(id);
                                }, { once: true });
                            }
                            showSuccess("<?php _e( 'Action added successfully!', 'gdpr-compliant-recaptcha-for-all-forms' ) ?> ");
                            /*}else{
                                document.getElementById(buttonId).style.display = "none";
                                showSuccess(response.data.message);
                            }*/
                        } else {
                            showAlert(response.error_message);
                        }
                    }
                    hideSpinner();
                };
                var listedActions = document.querySelector('#listedActions') && document.querySelector('#listedActions').checked ? '&listedActions=' + document.querySelector('#listedActions').checked : '';
                var listedPatterns = document.querySelector('#listedPatterns') && document.querySelector('#listedPatterns').checked ? '&listedPatterns=' + document.querySelector('#listedPatterns').checked : '';
                var whitelistedActions = document.querySelector('#whitelistedActions') && document.querySelector('#whitelistedActions').checked ? '&whitelistedActions=' + document.querySelector('#whitelistedActions').checked : '';                
                var whitelistedSites = document.querySelector('#whitelistedSites') && document.querySelector('#whitelistedSites').checked ? '&whitelistedSites=' + document.querySelector('#whitelistedSites').checked : '';
                var whitelistedIPs = document.querySelector('#whitelistedIPs') && document.querySelector('#whitelistedIPs').checked ? '&whitelistedIPs=' + document.querySelector('#whitelistedIPs').checked : '';
                var hiddenActions = document.querySelector('#hiddenActions') && document.querySelector('#hiddenActions').checked ? '&hiddenActions=' + document.querySelector('#hiddenActions').checked : '';
                var hiddenPatterns = document.querySelector('#hiddenPatterns') && document.querySelector('#hiddenPatterns').checked ? '&hiddenPatterns=' + document.querySelector('#hiddenPatterns').checked : '';
                var search = document.querySelector('.messageSearch').value;
                showSpinner()
                xhr.send(
                    "action=save_list_parameter" +
                    "&messageType=<?php echo( esc_js( $messageType ) ); ?>" +
                    "&listKey=" + listKey +
                    "&hide=" + hide +
                    "&security_nonce=<?php echo( wp_create_nonce( "save_list_nonce_" . esc_js( $messageType ) )); ?>" +
                    "&search=" + search +
                    "&search_nonce=<?php echo( wp_create_nonce( "render-messages_".esc_js( $messageType ) )); ?>" +
                    listedActions +
                    listedPatterns +
                    whitelistedActions +
                    whitelistedSites +
                    whitelistedIPs +
                    hiddenActions +
                    hiddenPatterns
                );
            }

            function saveWhitelistParameter(e, whitelistKey, buttonId) {
                e.preventDefault();
                if (document.readyState === 'complete') {
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", '<?php echo( admin_url( "admin-ajax.php" ) ); ?>');
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            var response = JSON.parse(xhr.responseText);
                            if (response.success == 1) {
                                document.querySelector("#results").innerHTML = response.result;
                                document.querySelector(".paginator").innerHTML = response.paginator;

                                var akkordeon = document.getElementsByClassName("akkordeonButton");
                                for (var i = 0; i < akkordeon.length; i++) {
                                    akkordeon[i].addEventListener("click", function() {
                                        this.classList.toggle("akkordeonButtonAktiv");
                                        var akkordeonEinheit = this.nextElementSibling;
                                        if (akkordeonEinheit.style.display === "block") {
                                        akkordeonEinheit.style.display = "none";
                                        } else {
                                        akkordeonEinheit.style.display = "block";
                                        }
                                    });
                                    akkordeon[i].addEventListener("click", function() {
                                        var id = this.id.split("_")[1];
                                        getDetail(id);
                                    }, { once: true });
                                }
                                showSuccess("<?php _e( 'Whitelisting successfully!', 'gdpr-compliant-recaptcha-for-all-forms' ) ?> ");
                            } else {
                                showAlert(response.error_message);
                            }
                        }
                        hideSpinner();
                    };
                    var listedActions = document.querySelector('#listedActions') && document.querySelector('#listedActions').checked ? '&listedActions=' + document.querySelector('#listedActions').checked : '';
                    var listedPatterns = document.querySelector('#listedPatterns') && document.querySelector('#listedPatterns').checked ? '&listedPatterns=' + document.querySelector('#listedPatterns').checked : '';
                    var whitelistedActions = document.querySelector('#whitelistedActions') && document.querySelector('#whitelistedActions').checked ? '&whitelistedActions=' + document.querySelector('#whitelistedActions').checked : '';                
                    var whitelistedSites = document.querySelector('#whitelistedSites') && document.querySelector('#whitelistedSites').checked ? '&whitelistedSites=' + document.querySelector('#whitelistedSites').checked : '';
                    var whitelistedIPs = document.querySelector('#whitelistedIPs') && document.querySelector('#whitelistedIPs').checked ? '&whitelistedIPs=' + document.querySelector('#whitelistedIPs').checked : '';
                    var hiddenActions = document.querySelector('#hiddenActions') && document.querySelector('#hiddenActions').checked ? '&hiddenActions=' + document.querySelector('#hiddenActions').checked : '';
                    var hiddenPatterns = document.querySelector('#hiddenPatterns') && document.querySelector('#hiddenPatterns').checked ? '&hiddenPatterns=' + document.querySelector('#hiddenPatterns').checked : '';
                    var search = document.querySelector('.messageSearch').value;
                    showSpinner();
                    xhr.send(
                        "action=save_whitelist_parameter" +
                        "&messageType=<?php echo( esc_js( $messageType ) ); ?>" +
                        "&whitelistKey=" + whitelistKey +
                        "&search=" + search +
                        "&search_nonce=<?php echo( wp_create_nonce( "render-messages_".esc_js( $messageType ) )); ?>" +
                        listedActions +
                        listedPatterns +
                        whitelistedActions +
                        whitelistedSites +
                        whitelistedIPs +
                        hiddenActions +
                        hiddenPatterns
                    );
                }
            }

            function insertIntoNestedObject(existingObject, inputString, value) {
                // String in ein Array aufteilen
                var keys = inputString.split("->");
                
                // Aktuelles Objekt auf das bestehende Objekt setzen
                var currentObject = existingObject;

                // Iteriere durch die Schlüssel und erstelle das verschachtelte assoziative Array
                for (var i = 0; i < keys.length; i++) {
                    var key = keys[i];
                    if (i === keys.length - 1) {
                        // Wenn wir den letzten Schlüssel erreicht haben, setze den Wert
                        currentObject[key] = value;
                    } else {
                        // Andernfalls erstelle ein neues leeres Objekt, wenn der Schlüssel noch nicht existiert
                        if (!currentObject[key]) {
                            currentObject[key] = {};
                        }
                        currentObject = currentObject[key];
                    }
                }
            }

            function savePattern(e, messageID, buttonId, hide = false) {
                e.preventDefault();
                var elements = document.getElementsByClassName("check_return_attribute_" + messageID);
                var elements_values = document.getElementsByClassName("check_return_value_" + messageID);
                var patternArray = {};
                if (elements.length > 0) {
                    // Hier kannst du mit den gefundenen Elementen arbeiten';
                    for (var i = 0; i < elements.length; i++) {
                        var element = elements[i];
                        if(element.checked){                            
                            var peer = document.getElementById(element.getAttribute("peer"));
                            if(peer.checked){
                                insertIntoNestedObject(patternArray, element.value, peer.value);
                            }else{
                                insertIntoNestedObject(patternArray, element.value, null);
                            }
                        }
                    }
                }
                var arrayKeys = Object.keys(patternArray);
                if (arrayKeys.length > 0) {
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", '<?php echo( admin_url( "admin-ajax.php" ) ); ?>', true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            var response = JSON.parse(xhr.responseText);
                            if (response.success == 1) {
                                //if(hide){
                                document.querySelector("#results").innerHTML = response.result;
                                document.querySelector(".paginator").innerHTML = response.paginator;

                                var akkordeon = document.getElementsByClassName("akkordeonButton");
                                for (var i = 0; i < akkordeon.length; i++) {
                                    akkordeon[i].addEventListener("click", function() {
                                        this.classList.toggle("akkordeonButtonAktiv");
                                        var akkordeonEinheit = this.nextElementSibling;
                                        if (akkordeonEinheit.style.display === "block") {
                                        akkordeonEinheit.style.display = "none";
                                        } else {
                                        akkordeonEinheit.style.display = "block";
                                        }
                                    });
                                    akkordeon[i].addEventListener("click", function() {
                                        var id = this.id.split("_")[1];
                                        getDetail(id);
                                    }, { once: true });
                                }
                                showSuccess("<?php _e( 'Pattern saved successfully!', 'gdpr-compliant-recaptcha-for-all-forms' ) ?> ");
                                /*}else{
                                    // Den Button ausblenden
                                    document.getElementById(buttonId).style.display = "none";
                                    showSuccess(response.data.message);
                                }*/
                            } else {
                                // Fehler beim Speichern
                                showAlert(response.data.error_message);
                            }
                        }
                        hideSpinner();
                    };
                    var listedActions = document.querySelector('#listedActions') && document.querySelector('#listedActions').checked ? '&listedActions=' + document.querySelector('#listedActions').checked : '';
                    var listedPatterns = document.querySelector('#listedPatterns') && document.querySelector('#listedPatterns').checked ? '&listedPatterns=' + document.querySelector('#listedPatterns').checked : '';
                    var whitelistedActions = document.querySelector('#whitelistedActions') && document.querySelector('#whitelistedActions').checked ? '&whitelistedActions=' + document.querySelector('#whitelistedActions').checked : '';                
                    var whitelistedSites = document.querySelector('#whitelistedSites') && document.querySelector('#whitelistedSites').checked ? '&whitelistedSites=' + document.querySelector('#whitelistedSites').checked : '';
                    var whitelistedIPs = document.querySelector('#whitelistedIPs') && document.querySelector('#whitelistedIPs').checked ? '&whitelistedIPs=' + document.querySelector('#whitelistedIPs').checked : '';
                    var hiddenActions = document.querySelector('#hiddenActions') && document.querySelector('#hiddenActions').checked ? '&hiddenActions=' + document.querySelector('#hiddenActions').checked : '';
                    var hiddenPatterns = document.querySelector('#hiddenPatterns') && document.querySelector('#hiddenPatterns').checked ? '&hiddenPatterns=' + document.querySelector('#hiddenPatterns').checked : '';
                    var search = document.querySelector('.messageSearch').value;
                    showSpinner();
                    xhr.send(
                        "action=save_pattern" +
                        "&messageType=<?php echo( esc_js( $messageType ) ); ?>" +
                        "&key=" + JSON.stringify(patternArray) +
                        "&hide=" + hide +
                        "&security_nonce=<?php echo( wp_create_nonce( "save_pattern_nonce_" . esc_js( $messageType ) )); ?>" +
                        "&search=" + search +
                        "&search_nonce=<?php echo( wp_create_nonce( "render-messages_".esc_js( $messageType ) )); ?>" +
                        listedActions +
                        listedPatterns +
                        whitelistedActions +
                        whitelistedSites +
                        whitelistedIPs +
                        hiddenActions +
                        hiddenPatterns
                    );
                }else{
                    showAlert('<?php echo( __( 'Please choose the message attributes which you want to save as pattern!', 'gdpr-compliant-recaptcha-for-all-forms' ) ); ?>');
                    function blinkElement(element, times, speed) {
                        var count = 0;
                        var interval = setInterval(function () {
                            element.style.visibility = (element.style.visibility === 'hidden') ? 'visible' : 'hidden';

                            if (++count === times * 2) {
                                clearInterval(interval);
                            }
                        }, speed);
                    }
                    // Iterate through the elements and set the background color to red
                    for (var i = 0; i < elements.length; i++) {
                        blinkElement(elements[i], 2, 500);
                        elements[i].style.boxShadow = "0 0 10px lightcoral";
                    }
                    for (var i = 0; i < elements_values.length; i++) {
                        blinkElement(elements_values[i], 2, 500);
                        elements_values[i].style.boxShadow = "0 0 10px lightcoral";
                    }
                }
            }

        </script>
        <?php
    }

    public function render_message() {
        $message_type;
        $message_id;
        $message_nonce;
        if ( ! ( 
                isset( $_POST[ 'messageID' ] )
                && isset( $_POST[ 'messageType' ] )
                && isset( $_POST[ 'message_nonce' ] )
                ) 
        ){
            $array_result = array(
                'success' => 0,
                'error_message' => __( 'Render action is invalid!', 'gdpr-compliant-recaptcha-for-all-forms' )
            );
            wp_send_json($array_result);
            exit;
        }else{
            $message_type = filter_var( $_POST[ 'messageType' ], FILTER_SANITIZE_NUMBER_INT );
            $message_id = filter_var( $_POST[ 'messageID' ], FILTER_SANITIZE_NUMBER_INT );
            $message_nonce = filter_var( $_POST[ 'message_nonce' ], FILTER_UNSAFE_RAW );
            if( ! wp_verify_nonce( $message_nonce, 'get-detail-'.$message_id.$message_type ) ){
                $array_result = array(
                    'success' => 0,
                    'error_message' => __( 'Render action is invalid!', 'gdpr-compliant-recaptcha-for-all-forms' )
                );
                wp_send_json( $array_result );
                exit;
            }
        }
        $details = $this->get_message_details( $message_id, $message_type );
        $rgm_ajax = false;
        if( isset( $details[ 0 ] ) )
            $rgm_ajax = esc_attr( $details[ 0 ]->rgm_ajax );
        $html_details ='
            <table class="widefat striped message">
                <thead>
                    <tr class="table-header">
        ';
        if( $message_type == 4 && ! $rgm_ajax )
            $html_details .='<th>' . __( 'Choose<br> pattern', 'gdpr-compliant-recaptcha-for-all-forms' ) . '</th>';
        $html_details .= '<th>' . __( 'Field', 'gdpr-compliant-recaptcha-for-all-forms' ) . '</th>';
        if( $message_type == 4 && ! $rgm_ajax )
            $html_details .='<th>' . __( 'Choose<br> pattern', 'gdpr-compliant-recaptcha-for-all-forms' ) . '</th>';
        $html_details .= '
                        <th>' . __( 'Value', 'gdpr-compliant-recaptcha-for-all-forms' ) . '</th>
                    </tr>
                </thead>
                <tbody>
        ';
        //Set the details page for each message
        foreach ( $details as $detail ) {
            $html_details .= '<tr class="table-body">';
            if( $message_type == 4 && ! $rgm_ajax )
                if( $detail->rgm_posted )
                    $html_details .= '<td class="check_returnAttribute_' . $message_id . '"> <input type="checkbox" value="' . esc_attr( $detail->rgd_attribute )  . '" onchange="document.getElementById(\'check_return_value_' . $message_id . '_' . $detail->rgd_id . '\').checked *= this.checked" name="check_return_attribute" peer="check_return_value_' . $message_id . '_' . $detail->rgd_id . '" class="check_return_attribute_' . $message_id . '" id="check_return_attribute_' . $message_id . '_' . $detail->rgd_id . '" /></td>';
                else
                    $html_details .= '<td class="check_returnAttribute_' . $message_id . '"> </td>';
            $html_details .= '<td class="returnAttribute">' . esc_attr( $detail->rgd_attribute ) . ':</td>';
            if( $message_type == 4 && ! $rgm_ajax )
                if( $detail->rgm_posted )
                    $html_details .= '<td class="check_returnValue_' . $message_id . '"> <input type="checkbox" value="' . esc_attr( $detail->rgd_value )  . '" onchange="document.getElementById(\'check_return_attribute_' . $message_id . '_' . $detail->rgd_id . '\').checked += this.checked " name="check_return_value" class="check_return_value_' . $message_id . '" id="check_return_value_' . $message_id . '_' . $detail->rgd_id . '" /></td>';
                else
                    $html_details .= '<td class="check_returnValue_' . $message_id . '"> </td>';
            $html_details .= '<td class="returnAttribute">' . esc_attr( $detail->rgd_value ) . '</td>
                </tr>';
        }
        $html_details .= '</tbody></table>';
        $array_result = array(
            'success' => 1,
            'result' => $html_details
        );

        wp_send_json( $array_result );
    }

    /** List Ajax-Action*/
    function save_list_parameter_callback() {

        // Überprüfen der Sicherheitsnonce
        $message_type = filter_var( $_POST[ 'messageType' ], FILTER_VALIDATE_INT );
        $security_nonce = filter_var( $_POST[ 'security_nonce' ], FILTER_UNSAFE_RAW );

        if ( ! wp_verify_nonce( $security_nonce, 'save_list_nonce_' . $message_type ) ) {
            $array_result = array(
                'error_message' => __( 'Unauthorized request!', 'gdpr-compliant-recaptcha-for-all-forms' )
            );
            wp_send_json_error( $array_result );
            exit;
        }
    
        // Get whitelisting parameters
        $list_key = sanitize_text_field( $_POST[ 'listKey' ] );
        // Sanitize the boolean using filter_var()
        $hide = filter_var( $_POST[ 'hide' ], FILTER_VALIDATE_BOOLEAN );

        $existing_option = null;
        if( $hide )
            $existing_option = get_option( Option::POW_HIDE_ACTION );
        else
            $existing_option = get_option( Option::POW_EXPLICIT_ACTION );

        $existing_lines = preg_split( "/\r\n|\n|\r/", $existing_option );
    
        // Check, whether the listing-parameter already exists
        if ( ! in_array( $list_key, $existing_lines ) ) {
            // If not add the new parameter
            $existing_lines[] = $list_key;
    
            // Transform to String again
            $updated_option = implode( "\n", $existing_lines );
    
            // Save the option
            if( $hide )
                update_option( Option::POW_HIDE_ACTION, $updated_option );    
            else
                update_option( Option::POW_EXPLICIT_ACTION, $updated_option );
            // Erfolgsmeldung zurückgeben
            //wp_send_json_success( array( 'message' => __( 'Ajax-action successfully listed.', 'gdpr-compliant-recaptcha-for-all-forms' ) ) );
            $this->render_messages();
        } else {
            // Whitelisting parametert already in place
            wp_send_json_error( array( 'error_message' => __( 'Ajax-action already listed.', 'gdpr-compliant-recaptcha-for-all-forms' ) ) );
        }
    
        exit;
    }

    /** Whitelist Ajax-Action*/
    function save_whitelist_parameter_callback() {

        // Überprüfen der Sicherheitsnonce
        $message_type = filter_var( $_POST[ 'messageType' ], FILTER_VALIDATE_INT );
        $search_nonce = filter_var( $_POST[ 'search_nonce' ], FILTER_UNSAFE_RAW );

        if ( ! wp_verify_nonce( $search_nonce, 'render-messages_' . $message_type ) ) {
            $array_result = array(
                'error_message' => __( 'Unauthorized request!', 'gdpr-compliant-recaptcha-for-all-forms' )
            );
            wp_send_json_error( $array_result );
            exit;
        }
    
        // Get whitelisting parameters
        $whitelist_key = sanitize_text_field( $_POST[ 'whitelistKey' ] );
    
        $existing_option = get_option( Option::POW_ACTION_WHITELIST );
        $existing_lines = preg_split( "/\r\n|\n|\r/", $existing_option );
    
        // Check, whether the whitelisting-parameter already exists
        if ( ! in_array( $whitelist_key, $existing_lines ) ) {
            // If not add the new parameter
            $existing_lines[] = $whitelist_key;
    
            // Transform to String again
            $updated_option = implode( "\n", $existing_lines );
    
            // Save the option
            update_option( Option::POW_ACTION_WHITELIST, $updated_option );

        }

        $this->render_messages();

    }

    /** Save Pattern*/
    function save_pattern_callback() {

        // Check security nonce
        $message_type = filter_var( $_POST[ 'messageType' ], FILTER_VALIDATE_INT );
        $security_nonce = filter_var( $_POST[ 'security_nonce' ], FILTER_UNSAFE_RAW );

        if ( ! wp_verify_nonce( $security_nonce, 'save_pattern_nonce_' . $message_type ) ) {
            $array_result = array(
                'error_message' => __( 'Unauthorized request!', 'gdpr-compliant-recaptcha-for-all-forms' )
            );
            wp_send_json_error( $array_result );
            exit;
        }
    
        // Get whitelisting parameters
        $pattern = stripslashes( sanitize_text_field( $_POST[ 'key' ] ) );
        $hide = filter_var( $_POST[ 'hide' ], FILTER_VALIDATE_BOOLEAN );
    
        $existing_option = null;
        if( $hide )
            $existing_option = get_option( Option::POW_HIDE_PATTERN );
        else
            $existing_option = get_option( Option::POW_PARAMETER_PATTERN );
        $existing_lines = preg_split( "/\r\n|\n|\r/", $existing_option );
    
        // Check, whether the whitelisting-parameter already exists
        if ( ! in_array( $pattern, $existing_lines ) ) {
            // If not add the new parameter
            $existing_lines[] = $pattern;
    
            // Transform to String again
            $updated_option = implode( "\n", $existing_lines );
    
            // Save the option
            if( $hide )
                update_option( Option::POW_HIDE_PATTERN, $updated_option );
            else
                update_option( Option::POW_PARAMETER_PATTERN, $updated_option );    
            $this->render_messages();
            // Erfolgsmeldung zurückgeben
            //wp_send_json_success( array( 'message' => __( 'Pattern added successfully.', 'gdpr-compliant-recaptcha-for-all-forms' ) ) );
        } else {
            // Pattern already in place
            wp_send_json_error( array( 'error_message' => __( 'Pattern already exists.', 'gdpr-compliant-recaptcha-for-all-forms' ) ) );
        }
    
        exit;
    }

    /** Add the page for saved messages */
    public function render_messages() {
        $search;
        $message_type;
        $search_nonce;
        if ( ! ( 
                isset( $_POST[ 'search' ] ) 
                && isset( $_POST[ 'messageType' ] )
                && isset( $_POST[ 'search_nonce' ] )
                ) 
            ){
            $array_result = array(
                'success' => 0,
                'error_message' => __( 'Multiple render action is invalid!', 'gdpr-compliant-recaptcha-for-all-forms' )
            );
            wp_send_json( $array_result );
            exit;
        }else{
            
            $this->listed_actions = isset( $_POST[ 'listedActions' ] ) ? $_POST[ 'listedActions' ] : null;
            $this->listed_patterns = isset( $_POST[ 'listedPatterns' ] ) ? $_POST[ 'listedPatterns' ] : null ;
            $this->whitelisted_actions = isset( $_POST[ 'whitelistedActions' ] ) ? $_POST[ 'whitelistedActions' ] : null;
            $this->whitelisted_sites = isset( $_POST[ 'whitelistedSites' ] ) ? $_POST[ 'whitelistedSites' ] : null ;
            $this->whitelisted_ips = isset( $_POST[ 'whitelistedIPs' ] ) ? $_POST[ 'whitelistedIPs' ] : null ;
            $this->hidden_actions = isset( $_POST[ 'hiddenActions' ] ) ? $_POST[ 'hiddenActions' ] : null;
            $this->hidden_patterns = isset( $_POST[ 'hiddenPatterns' ] ) ? $_POST[ 'hiddenPatterns' ] : null;
            $search = filter_var( $_POST[ 'search' ], FILTER_UNSAFE_RAW );
            $message_type = filter_var( $_POST[ 'messageType' ], FILTER_VALIDATE_INT );
            $search_nonce = filter_var( $_POST[ 'search_nonce' ], FILTER_UNSAFE_RAW );
            if( ! wp_verify_nonce( $search_nonce, 'render-messages_' . $message_type ) ){
                $array_result = array(
                    'success' => 0,
                    'error_message' => __( 'Multiple render action is invalid!', 'gdpr-compliant-recaptcha-for-all-forms' )
                );
                wp_send_json( $array_result );
                exit;
            }
        }

        $explicit_mode = get_option( Option::POW_EXPLICIT_MODE );

        $existing_actions_list = get_option( Option::POW_EXPLICIT_ACTION );
        $existing_actions_lines = array_filter( preg_split( "/\r\n|\n|\r/", $existing_actions_list ) );

        $existing_patterns_list = get_option( Option::POW_PARAMETER_PATTERN );
        $existing_patterns_lines = array_filter( preg_split( "/\r\n|\n|\r/", $existing_patterns_list ) );

        $existing_whitelist_actions_list = get_option( Option::POW_ACTION_WHITELIST );
        $existing_whitelist_actions_lines = array_filter( preg_split( "/\r\n|\n|\r/", $existing_whitelist_actions_list ) );

        $existing_whitelist_sites_list = get_option( Option::POW_SITE_WHITELIST );
        $existing_whitelist_sites_lines = array_filter( preg_split( "/\r\n|\n|\r/", $existing_whitelist_sites_list ) );

        $existing_whitelist_ips_list = get_option( Option::POW_IP_WHITELIST );
        $existing_whitelist_ips_lines = preg_split( "/\r\n|\n|\r/", $existing_whitelist_ips_list );
        $existing_whitelist_ips_lines = array_map( [ 'VENDOR\RECAPTCHA_GDPR_COMPLIANT\Option', 'hash_Values' ], $existing_whitelist_ips_lines );

        $hidden_actions_list = get_option( Option::POW_HIDE_ACTION );
        $hidden_actions_lines = array_filter( preg_split( "/\r\n|\n|\r/", $hidden_actions_list ) );

        $hidden_patterns_list = get_option( Option::POW_HIDE_PATTERN );
        $hidden_patterns_lines = array_filter( preg_split( "/\r\n|\n|\r/", $hidden_patterns_list ) );
        
        $rows = Option::get_rows( 
            $search, 
            $message_type,
            false,
            $message_type == 4 && $hidden_actions_list && ! $this->hidden_actions ? $hidden_actions_lines : [ '-' ] ,
            $message_type == 4 && $existing_actions_list && ! $this->listed_actions && $explicit_mode ? $existing_actions_lines : [ '-' ],
            $message_type == 4 && $existing_whitelist_actions_list && $this->whitelisted_actions ? $action_whitelisted : [ '-' ],
            $message_type == 4 && $existing_patterns_list && ! $this->listed_patterns && $explicit_mode ? $existing_patterns_lines : [],
            $message_type == 4 && $hidden_patterns_list && ! $this->hidden_patterns ? $hidden_patterns_lines : []
        );
        $per_page = 25;
        $pages = ceil( $rows / $per_page );

        $page = 1;
        if( isset( $_POST[ 'page' ] ) ){
            $page_raw = filter_var( $_POST[ 'page' ], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE );
            if( $pages >= $page_raw && $page_raw > 0 ){
                $page = $page_raw;
            }
        }

        $start = ( $page - 1 ) * $per_page;
        $messages = $this->get_messages( 
            $search, 
            $message_type, 
            $start, 
            $per_page, 
            $message_type == 4 && $hidden_actions_list && ! $this->hidden_actions ? $hidden_actions_lines : [ '-' ] ,
            $message_type == 4 && $existing_actions_list && ! $this->listed_actions && $explicit_mode ? $existing_actions_lines : [ '-' ],
            $message_type == 4 && $existing_whitelist_actions_list && $this->whitelisted_actions ? $action_whitelisted : [ '-' ],
            $message_type == 4 && $existing_patterns_list && ! $this->listed_patterns && $explicit_mode ? $existing_patterns_lines : [],
            $message_type == 4 && $hidden_patterns_list && ! $this->hidden_patterns ? $hidden_patterns_lines : [],
        );

        $html = "";
        $paginator = $this->get_paginator( $rows, $page, $pages );
        $count = 0;
        foreach ( $messages as $message ) {
            
            $rgm_id = esc_attr( $message->rgm_id );
            $rgm_title = esc_attr( $message->rgm_title );
            $rgm_date = esc_attr( $message->rgm_date );
            $rgm_ajax = esc_attr( $message->rgm_ajax );
            $rgm_action = esc_attr( $message->rgm_action );
            $rgm_ip = esc_attr( $message->rgm_ip );
            $rgm_site = esc_attr( $message->rgm_site );

            $message_details = $this->get_message_details( $rgm_id, $message_type );
            $message_details_array = Option::convertToJsonObject( $message_details, 'rgd_attribute', 'rgd_value' );

            // Check, whether the whitelisting-parameter already exists
            $action_listed = trim( $rgm_action ) && in_array( trim( $rgm_action ), $existing_actions_lines );
            $action_whitelisted = trim( $rgm_action ) && in_array( trim( $rgm_action ), array_map('trim', $existing_whitelist_actions_lines ), true );
            $site_whitelisted = trim( $rgm_site ) && in_array( trim( $rgm_site ), $existing_whitelist_sites_lines );
            $ip_whitelisted = trim( $rgm_ip ) && in_array( trim( $rgm_ip ), $existing_whitelist_ips_lines );
            if( 
                $message_type != 4
                || (
                    $explicit_mode 
                    || ( ! $explicit_mode && ( ( $site_whitelisted && $this->whitelisted_sites ) || ( $ip_whitelisted && $this->whitelisted_ips ) || ! ( $site_whitelisted || $ip_whitelisted ) ) )
                )
            ){
                $html.= '
                    <table id="resultTable">
                    <col style="width:40px">
                    <tr>
                    <td class="check_message_col"> <input type="checkbox" name="check_message" class="check_message" id="check_message_'.$rgm_id.'" /></td>
                    <td><button class="akkordeonButton" id="akkordeonButton_'.$rgm_id.'">
                                '.$rgm_date.' '.$rgm_title.'
                    </button>
                    <div class="akkordeonEinheit">
                        <div id="messageDetails'.$rgm_id.'">
                        </div>
                        <input type="hidden" name="messageNonce" id="messageNonce'.$rgm_id.'" value="'.wp_create_nonce( 'get-detail-'.$rgm_id.$message_type ).'" />
                ';
                $html.= '<table><th>';
                if (  $message_type != 1 && $message_type != 4 ){
                    $html.= '
                            <td>
                            <form id="messageForm'.$rgm_id.'" onSubmit="moveMessage( event, \'messageForm\', '.$rgm_id.', 1 );">
                                <input type="hidden" name="messsageID" id="messsageID" value="'.$rgm_id.'" />
                                <input type="hidden" name="moveNonce" id="moveNonce" value="'.wp_create_nonce( 'move-message-'.$rgm_id.$message_type.'1' ).'" />
                                <input type="submit" class="trashButton button-secondary" id="trashButton" name="trashButton" value="'.__( 'Move to Messages', 'gdpr-compliant-recaptcha-for-all-forms' ).'" />
                            </form>
                            </td>
                    ';
                }
                if (  $message_type != 2 && $message_type != 4 ){
                    $html.= '
                            <td>
                            <form id="spamForm'.$rgm_id.'" onSubmit="moveMessage( event, \'spamForm\', '.$rgm_id.', 2 );">
                                <input type="hidden" name="messsageID" id="messsageID" value="'.$rgm_id.'" />
                                <input type="hidden" name="moveNonce" id="moveNonce" value="'.wp_create_nonce( 'move-message-'.$rgm_id.$message_type.'2' ).'" />
                                <input type="submit" class="trashButton button-secondary" id="trashButton" name="trashButton" value="'.__( 'Move to Spam', 'gdpr-compliant-recaptcha-for-all-forms' ).'" />
                            </form>
                            </td>
                    ';
                }
                if ( $message_type != 3 && $message_type != 4 ){
                    $html.= '
                            <td>
                            <form id="trashForm'.$rgm_id.'" onSubmit="moveMessage( event, \'trashForm\', '.$rgm_id.', 3 );">
                                <input type="hidden" name="messsageID" id="messsageID" value="'.$rgm_id.'" />
                                <input type="hidden" name="moveNonce" id="moveNonce" value="'.wp_create_nonce( 'move-message-'.$rgm_id.$message_type.'3' ).'" />
                                <input type="submit" class="trashButton button-secondary" id="trashButton" name="trashButton" value="'.__( 'Move to Trash', 'gdpr-compliant-recaptcha-for-all-forms' ).'" />
                            </form>
                            </td>
                    ';
                }
                if ( $rgm_ajax && $rgm_action && ! $explicit_mode && ! $action_whitelisted ){
                    $html.= '
                        <td>
                        <form id="whitelistForm'.$rgm_id.'" onSubmit="saveWhitelistParameter(event, \''.$rgm_action.'\', \'block_Button_' . $rgm_id . '\');">
                            <input type="hidden" name="messsageID" id="messsageID" value="'.$rgm_id.'" />
                            <input type="submit" id="block_Button_' . $rgm_id . '" class="blockButton button-primary" name="blockButton" value="'.__('Whitelist type of submission', 'gdpr-compliant-recaptcha-for-all-forms').'" />
                        </form>
                        </td>
                    ';
                }
                if ( $rgm_ajax && $rgm_action && $explicit_mode && ! $action_listed ){
                    $html.= '
                        <td>
                        <form id="whiteList'.$rgm_id.'" onSubmit="saveListParameter(event, \''.$rgm_action.'\', \'list_Button_' . $rgm_id . '\', false);">
                            <input type="hidden" name="messsageID" id="messsageID" value="'.$rgm_id.'" />
                            <input type="submit" id="list_Button_' . $rgm_id . '" class="listButton button-primary" name="listButton" value="'.__('Enhance spam check on type of action', 'gdpr-compliant-recaptcha-for-all-forms').'" />
                        </form>
                        </td>
                        <td>
                        <form id="hideList'.$rgm_id.'" onSubmit="saveListParameter(event, \''.$rgm_action.'\', \'hide_Button_' . $rgm_id . '\', true);">
                            <input type="hidden" name="messsageID" id="messsageID" value="'.$rgm_id.'" />
                            <input type="submit" id="hide_Button_' . $rgm_id . '" class="hideButton button-primary" name="hideButton" value="'.__('Hide action', 'gdpr-compliant-recaptcha-for-all-forms').'" />
                        </form>
                        </td>
                    ';
                }
                if ( $message_type == 4 && ! $rgm_ajax ){
                    $html.= '
                        <td>
                        <form id="patternForm' . $rgm_id . '" onSubmit="savePattern(event, \'' . $rgm_id . '\', \'pattern_Button_' . $rgm_id . '\', false);">
                            <input type="hidden" name="messsageID" id="messsageID" value="'.$rgm_id.'" />
                            <input type="submit" id="pattern_Button_' . $rgm_id . '" class="patternButton button-primary" name="patternButton" value="'.__('Enhance spam check on type of submission', 'gdpr-compliant-recaptcha-for-all-forms').'" />
                        </form>
                        </td>
                        <td>
                        <form id="hidePatternForm' . $rgm_id . '" onSubmit="savePattern(event, \'' . $rgm_id . '\', \'hide_Pattern_Button_' . $rgm_id . '\', true);">
                            <input type="hidden" name="messsageID" id="messsageID" value="'.$rgm_id.'" />
                            <input type="submit" id="hide_Pattern_Button_' . $rgm_id . '" class="hidePatternButton button-primary" name="hidePatternButton" value="'.__('Hide pattern', 'gdpr-compliant-recaptcha-for-all-forms').'" />
                        </form>
                        </td>
                    ';
                }
                if ( $message_type == 3 || $message_type == 4 ){
                    $html.= '
                            <td>
                            <form id="deleteForm'.$rgm_id.'" onSubmit="deleteSingleMessage( event, \'deleteForm\', '.$rgm_id.' );">
                                <input type="hidden" name="messsageID" id="messsageID" value="'.$rgm_id.'" />
                                <input type="hidden" name="deleteNonce" id="deleteNonce" value="'.wp_create_nonce( 'delete-message-'.$rgm_id.$message_type ).'" />
                                <input type="submit" class="trashButton button-primary" id="trashButton" name="trashButton" value="'.__( 'Delete', 'gdpr-compliant-recaptcha-for-all-forms' ).'" />
                            </form>
                            </td>
                    ';
                }
                $html.= '</th></table>';
                $html.= '</div></td>
                </tr>
                </table>';
            }
        }
        $array_result = array(
            'success' => 1,
            'result' => $html,
            'paginator' => $paginator
        );
        
        wp_send_json($array_result);

    }

    /* Show a request to evaluate the plugin if not yet shown*/
    private function show_evaluation_request( $message_type, $rows ){
        // Firstly check the preconditions
        if( $message_type == 2 // Are we on the spam page for spam messages?
            && $rows > 10 // Is the number of spam messages greater than 10?
        ){

            $review_link = '<a href="https://wordpress.org/support/plugin/gdpr-compliant-recaptcha-for-all-forms/reviews/#new-post">Help us and rate it</a>';
            $faq_link = '<a href="https://wordpress.org/support/plugin/gdpr-compliant-recaptcha-for-all-forms/">Get help in the support forum</a>';
            $line_break = '<br>';
            $smiley = '<span class="large-smiley">&#128578;</span>';
            $thinking_smiley = '<span class="large-smiley">&#129300;</span>';
            
            $message = __('%s Happy with the plugin? %s %s %s Problems, questions, hints, improvements? %s', 'gdpr-compliant-recaptcha-for-all-forms');
            $message_with_links = sprintf($message, $smiley, $review_link, $line_break, $thinking_smiley, $faq_link);

            add_settings_error(
                Option::PREFIX . 'options',
                'my-plugin-success',
                $message_with_links,
                'info'
            );
            settings_errors( Option::PREFIX . 'options' );
        }
    }

    /* Returns the action bar*/
    private function get_action_bar( $messageType ){
        ?>
        <input type="checkbox" class="check_messages" />
        <select name="messageAction" id="messageAction">
            <option value="bulk"><?php _e( 'Bulk actions', 'gdpr-compliant-recaptcha-for-all-forms' ); ?></option>
        <?php
        if( $messageType != 1 && $messageType != 4 ){
            ?>
            <option value="messageForm_1"><?php _e( 'Move to Messages', 'gdpr-compliant-recaptcha-for-all-forms' ); ?></option>
            <?php
        }
        if( $messageType != 2 && $messageType != 4 ){
            ?>
            <option value="spamForm_2"><?php _e( 'Move to Spam', 'gdpr-compliant-recaptcha-for-all-forms' ); ?></option>
            <?php
        }
        if( $messageType != 3 && $messageType != 4 ){
            ?>
            <option value="trashForm_3"><?php _e( 'Move to Trash', 'gdpr-compliant-recaptcha-for-all-forms' ); ?></option>
            <?php
        }
        if( $messageType == 3 || $messageType == 4 ){
            ?>
            <option value="deleteForm"><?php _e( 'Delete', 'gdpr-compliant-recaptcha-for-all-forms' ); ?></option>
            <?php
        }
        ?>
        </select>
        <input type="button" class="ApplyButton button button-primary" id="ApplyButton" name="ApplyButton" value="<?php _e( 'Apply', 'gdpr-compliant-recaptcha-for-all-forms' ); ?>" onClick="doMessageAction(event);" />
        <input type="button" class="DeleteAllButton button button-primary" id="DeleteAllButton" name="DeleteAllButton" value="<?php _e( 'Delete all', 'gdpr-compliant-recaptcha-for-all-forms' ); ?>" onClick="doDeleteAll(event);" />
        <?php _e( 'Search', 'gdpr-compliant-recaptcha-for-all-forms' ); ?>: <input type="text" class="messageSearch" name="messageSearch" />
        <?php
        return;
    }

    /* Returns a paginator*/
    private function get_paginator( $rows, $page, $pages ){
        $html = $rows . ' ' . __( 'messages', 'gdpr-compliant-recaptcha-for-all-forms' ) . ' ';
        $html .= '<input type="button" onClick="messageSearch(1);" value="<<" '.( $page > 1 ? '' : 'disabled ').'/>';
        $html .= '<input type="button" onClick="messageSearch( '.( ( $page - 1 ) < 1 ? 1 : ( $page - 1 ) ) .' );" value="<" '.( $page > 1 ? '' : 'disabled ').'/>';
        $html .= ' ' . $page . ' ' . __( 'out of', 'gdpr-compliant-recaptcha-for-all-forms' ). ' ' . $pages . ' ';
        $html .= '<input type="button" onClick="messageSearch( '.( ( $page + 1 ) > $pages ? $pages : ( $page + 1 ) ) .' );" value=">" '.( $page < $pages ? '' : 'disabled ' ).'/>';
        $html .= '<input type="button" onClick="messageSearch('.$pages.');" value=">>" '.( $page < $pages ? '' : 'disabled ' ).'/>';
        return $html;
    }

    /**Get all messages */
    private function get_messages( $search, $messageType, $start, $pages, $hidden_actions = [ '-' ], $existing_actions = [ '-' ], $whitelisted_actions = [ '-' ], $existing_patterns = [], $hidden_patterns = [] ){
        global $wpdb;

        $hidden_actions_placeholders = implode( ', ', array_fill( 0, count( $hidden_actions ), '%s' ) );
        $existing_actions_placeholders = implode( ', ', array_fill( 0, count( $existing_actions ), '%s' ) );
        $whitelisted_actions_placeholders = implode( ', ', array_fill( 0, count( $whitelisted_actions ), '%s' ) );
        $parameters = array_merge(
            [ $messageType ], 
            $hidden_actions,
            $existing_actions,
            $whitelisted_actions,
            [ $search, $search, $start, $pages, ]
        );
        $sqlArray = [];
        //For each pattern build a sub-seelect to check whether the conditions match
        foreach ( $existing_patterns as $pattern ) {
            $pattern = Option::generate_paths( json_decode( $pattern, true ), '' );
            $conditions = array();
            foreach ( $pattern as $paramPath => $value ) {
                if ( $value === null ) {
                    $conditions[] = "(rgd.rgd_attribute LIKE '{$paramPath}')";
                } else {
                    $conditions[] = "(rgd.rgd_attribute LIKE '{$paramPath}' AND rgd.rgd_value = '{$value}')";
                }
            }

            $sqlArray[] = " AND rgd.rgm_id NOT IN (
                    SELECT rgd.rgm_id
                    FROM " . $wpdb->prefix . "recaptcha_gdpr_details_rgd rgd
                    WHERE " . implode(' OR ', $conditions) . "
                    GROUP BY rgd.rgm_id
                    HAVING COUNT(DISTINCT rgd.rgd_attribute) = " . count( $pattern ) . "
                )"
            ;
        }
        $hiddenSqlArray = [];
        //For each pattern build a sub-seelect to check whether the conditions match
        foreach ( $hidden_patterns as $pattern ) {
            $pattern = Option::generate_paths( json_decode( $pattern, true ), '' );
            $conditions = array();
            foreach ( $pattern as $paramPath => $value ) {
                if ( $value === null ) {
                    $conditions[] = "(rgd.rgd_attribute LIKE '{$paramPath}')";
                } else {
                    $conditions[] = "(rgd.rgd_attribute LIKE '{$paramPath}' AND rgd.rgd_value = '{$value}')";
                }
            }

            $hiddenSqlArray[] = " AND rgd.rgm_id NOT IN (
                    SELECT rgd.rgm_id
                    FROM " . $wpdb->prefix . "recaptcha_gdpr_details_rgd rgd
                    WHERE " . implode(' OR ', $conditions) . "
                    GROUP BY rgd.rgm_id
                    HAVING COUNT(DISTINCT rgd.rgd_attribute) = " . count( $pattern ) . "
                )"
            ;
        }

        // Anfrage ausführen,
        $results = $wpdb->get_results(
            $wpdb->prepare( "SELECT DISTINCT rgm.*
                            FROM " . $wpdb->prefix . "recaptcha_gdpr_message_rgm rgm
                            JOIN " . $wpdb->prefix . "recaptcha_gdpr_details_rgd rgd
                              ON rgm.rgm_id = rgd.rgm_id
                            WHERE rgm.rgm_type = %d
                              AND COALESCE(rgm.rgm_action, '') NOT IN ($hidden_actions_placeholders)
                              AND COALESCE(rgm.rgm_action, '') NOT IN ($existing_actions_placeholders)
                              AND COALESCE(rgm.rgm_action, '') NOT IN ($whitelisted_actions_placeholders)
                              AND ( rgd.rgd_attribute LIKE CONCAT('%',%s,'%')
                                    OR rgd.rgd_value LIKE CONCAT('%',%s,'%')
                                  )
                              " . implode( '', $sqlArray ) .  implode( '', $hiddenSqlArray ) . "
                            ORDER BY rgm.rgm_date DESC
                            LIMIT %d, %d
                           ", $parameters
            )
        );
        return $results;
    }

    /**Get all message details */
    private function get_message_details( $messageID, $messageType ){
        global $wpdb;
        // Anfrage ausführen
        $results = $wpdb->get_results(
            $wpdb->prepare( "SELECT rgm.rgm_ajax, rgd.*
                            FROM " . $wpdb->prefix . "recaptcha_gdpr_details_rgd rgd
                            JOIN " . $wpdb->prefix . "recaptcha_gdpr_message_rgm rgm
                              ON rgm.rgm_id = rgd.rgm_id
                            WHERE rgd.rgm_id = %d
                              AND rgm.rgm_type = %d
                           ", $messageID, $messageType
            )
        );

        return $results;
    }

    /**Delete message*/
    public function delete_message( $messages ){
        if ( ! ( 
                isset( $_POST[ 'search' ] )
                && isset( $_POST[ 'search_nonce' ] )
                && isset( $_POST[ 'messageType' ] ) 
                )
            ){
            $array_result = array(
                'success' => 0,
                'error_message' => __( 'Delete action is invalid!', 'gdpr-compliant-recaptcha-for-all-forms' )
            );
            wp_send_json( $array_result );
            exit;
        }

        global $wpdb;
        $message;
        $message_type = filter_var( $_POST[ 'messageType' ], FILTER_VALIDATE_INT );
        $wpdb->query( 'START TRANSACTION' );
        $arrayVariable = json_decode(stripslashes($_POST['messages']));

        if( $arrayVariable ){
            foreach( $arrayVariable as $raw_message ) {
                parse_str( $raw_message, $message );
                
                if ( ! ( 
                        isset( $message[ 'deleteNonce' ] )
                        && isset( $message[ 'messsageID' ] )
                        && wp_verify_nonce( $message[ 'deleteNonce' ], 'delete-message-'.$message[ 'messsageID' ].$message_type )
                        )
                ){
                    $array_result = array(
                        'success' => 0,
                        'error_message' => __( 'Delete action is invalid!', 'gdpr-compliant-recaptcha-for-all-forms' )
                    );
                    wp_send_json($array_result);
                }
                $message_id = filter_var( $message[ 'messsageID' ], FILTER_VALIDATE_INT );

                // Anfrage ausführen
                $wpdb->query(
                    $wpdb->prepare("DELETE FROM " . $wpdb->prefix . "recaptcha_gdpr_message_rgm
                                    WHERE rgm_id = %d
                                    AND rgm_type = %d
                                ", $message_id, $message_type
                    )
                );

                // Anfrage ausführen
                $wpdb->query(
                    $wpdb->prepare( "DELETE FROM " . $wpdb->prefix . "recaptcha_gdpr_details_rgd
                                    WHERE rgm_id = %d
                                ", $message_id
                    )
                );

            }
        }else{
            // Anfrage ausführen
            $wpdb->query(
                $wpdb->prepare("DELETE FROM " . $wpdb->prefix . "recaptcha_gdpr_message_rgm
                                WHERE rgm_type = %d
                            ", $message_type
                )
            );

            // Anfrage ausführen
            $wpdb->query("DELETE rgd FROM " . $wpdb->prefix . "recaptcha_gdpr_details_rgd rgd
                            LEFT JOIN " . $wpdb->prefix . "recaptcha_gdpr_message_rgm rgm ON rgd.rgm_id = rgm.rgm_id
                            WHERE rgm.rgm_id IS NULL"
            );
        }
        $wpdb->query( 'COMMIT') ;
        $this->render_messages();

    }

    /**Change the type of the message 1 = clean, 2= spam, 3= trash*/
    public function change_message_type(){

        if ( ! ( 
                isset( $_POST[ 'search' ] )
                && isset( $_POST[ 'search_nonce' ] )
                && isset( $_POST[ 'messageType' ] )
                && isset( $_POST[ 'changeType' ] )
                && isset( $_POST[ 'messages' ] )
               ) 
            ){
            $array_result = array(
                'success' => 0,
                'error_message' => __( 'Change action is invalid!', 'gdpr-compliant-recaptcha-for-all-forms' )
            );
            wp_send_json($array_result);
            exit;
        }

        global $wpdb;
        $message;
        $message_type = filter_var( $_POST[ 'messageType' ], FILTER_VALIDATE_INT );
        $change_type = filter_var( $_POST[ 'changeType' ], FILTER_VALIDATE_INT );
        $arrayVariable = json_decode(stripslashes($_POST['messages']));
        
        foreach( $arrayVariable as $raw_message ) {
            parse_str( $raw_message, $message );
            if ( ! ( 
                    isset( $message[ 'moveNonce' ] )
                    && isset( $message[ 'messsageID' ] )
                    && wp_verify_nonce( $message[ 'moveNonce' ], 'move-message-'.$message[ 'messsageID' ].$message_type.$change_type )
                    )
            ){
                $array_result = array(
                    'success' => 0,
                    'error_message' => __( 'Change action is invalid!', 'gdpr-compliant-recaptcha-for-all-forms' )
                );
                wp_send_json( $array_result );
            }
            $message_id = filter_var( $message[ 'messsageID' ], FILTER_VALIDATE_INT );

            $wpdb->query(
                $wpdb->prepare( "UPDATE " . $wpdb->prefix . "recaptcha_gdpr_message_rgm
                                SET rgm_type = %d
                                WHERE rgm_id = %d
                                  AND rgm_type = %d
                            ", $change_type, $message_id, $message_type
                )
            );

        }
    
        $this->render_messages();
    }

} 
?>