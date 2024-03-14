<?php 

namespace VENDOR\RECAPTCHA_GDPR_COMPLIANT;

defined( 'ABSPATH' ) or die( 'Are you ok?' );

/**
 * Class Dashboard_Widget: Reflects the module for adding a widget to the WP-Dashboard
 */

class Analysis
{
    /** Holding the instance of this class */
    public static $instance;

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
    }

    /** When the plugin is running
     */
    public function run()
    {
        if( get_option( Option::POW_DIRECT_ANALYSIS_MODE ) && current_user_can( 'manage_options' ) ){
            add_action( 'wp_ajax_get_patterns', [ $this, 'get_patterns' ] );
            $ajax = defined('DOING_AJAX') && DOING_AJAX;
            if( ! $ajax ){
                add_action( 'wp_enqueue_scripts', [ $this, 'add_javascript' ], PHP_INT_MAX );
            }
            add_action('wp_enqueue_scripts', [ $this, 'include_ressources' ] );
            add_action( 'wp_ajax_save_pattern_frontend', [ $this, 'save_pattern_callback' ] );
        }
    }

    /** Function to get rssource to the frontend */
    public function include_ressources(){
        wp_enqueue_style( 'gdpr-compliant-style-analysis', plugins_url( '/css/style_analysis.css', __DIR__ ) );
    }

    /** Function to get the patterns to apply the spam check to the frontend
     * 
     */
    public function get_patterns() {

        $existing_pattern = get_option( Option::POW_PARAMETER_PATTERN );
        $existing_lines_pattern = null;
        $existing_action = get_option( Option::POW_EXPLICIT_ACTION );
        $existing_lines_action = null;
        if( $existing_pattern )
            $existing_lines_pattern = preg_split( "/\r\n|\n|\r/", $existing_pattern, -1, PREG_SPLIT_NO_EMPTY );
        if( $existing_action )
            $existing_lines_action = preg_split('/\r\n|\n|\r/', $existing_action, -1, PREG_SPLIT_NO_EMPTY);

        $array_result = array(
            'patterns' => $existing_lines_pattern,
            'actions' => $existing_lines_action,
        );

        // Make your array as json
        wp_send_json( $array_result );

        // Don't forget to stop execution afterward.
        wp_die();

    }

    /** Save Pattern or Ajax-Action*/
    function save_pattern_callback() {
    
        // Get whitelisting parameters
        $pattern = stripslashes( sanitize_text_field( $_POST[ 'key' ] ) );
        $standard = filter_var( $_POST[ 'standard' ], FILTER_VALIDATE_BOOLEAN );
    
        $existing_option = null;
        if( $standard )
            $existing_option = get_option( Option::POW_EXPLICIT_ACTION );
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
            if( $standard ){
                update_option( Option::POW_EXPLICIT_ACTION, $updated_option );
                $property = __( 'Apply on actions', 'gdpr-compliant-recaptcha-for-all-forms' );
            }else{
                update_option( Option::POW_PARAMETER_PATTERN, $updated_option );    
                $property = __( 'Apply on pattern', 'gdpr-compliant-recaptcha-for-all-forms' );
            }   

            wp_send_json_success( array( 'message' => sprintf( __( 'Submission type added successfully. You can find and change it on the plugins settings page under the tab "Scope", in the property "%s".', 'gdpr-compliant-recaptcha-for-all-forms' ), $property ) ) );
        } else {
            // Pattern already in place
            wp_send_json_error( array( 'error_message' => __( 'Submission type already exists.', 'gdpr-compliant-recaptcha-for-all-forms' ) ) );
        }
    
        exit;
    }

    public function add_javascript() {
        ?>
        <script>
            var gdpr_compliant_recaptcha_analysis = {
                idCounter : 0,
                jsonArray : [],
                patterns : [],
                actions : [],
                currentName : '',
                originalXhrOpen : XMLHttpRequest.prototype.open,
                originalXhrSend : XMLHttpRequest.prototype.send,
                originalFetch : window.fetch,//.bind(window),
                /** Identify hidden fields in a formdata object and return its names into an array*/
                getHiddenFields : function(formData){
                    let hiddenFieldsArray = [];
                    const hiddenFields = form.querySelectorAll('input[type="hidden"][name]');
                    hiddenFields.forEach(hiddenField => {
                        if(hiddenField.name.includes('[')){
                            formDataJSON = gdpr_compliant_recaptcha_analysis.mergeNestedArrays(formDataJSON, gdpr_compliant_recaptcha_analysis.convertStringToJsonObject(submitButton.name, submitButton.value));
                        }else{
                            formDataJSON[submitButton.name] = submitButton.value;
                        }
                    });
                    return hiddenFieldsArray;
                },
                /** Functions to intercept submit actions */
                handleFormSubmission : function(form) {
                    //The function is either called from a trigger that is passing an event containing the form, or by the overwritten submit function passing the form directly
                    if (form instanceof Event) {
                        form = form.target;
                    }
                    // Get the form's action attribute
                    const formAction = form.getAttribute('action');
                    //Only recognize the submission if a new page will be requested by the forms action
                    const isHttpOrRelativeUrl = formAction && (/^https?:\/\//i.test(formAction) || formAction.startsWith('/'));

                    // If the action attribute is an HTTP URL or a relative URL, show user confirmation
                    if (isHttpOrRelativeUrl) {
                        /*const url = new URL(formAction, window.location.origin);
                        const urlSearchParams = url.searchParams;
                        // Get existing parameters from the form's action URL
                        const existingParameters = {};
                        for (const [key, value] of urlSearchParams.entries()) {
                            existingParameters[key] = value;
                        }*/

                        // Get form data and convert it to JSON format
                        const formData = new FormData(form);
                        let formDataJSON = {};
                        // Include existing parameters in formDataJSON
                        //Object.assign(formDataJSON, existingParameters);
                        formData.forEach((value, key) => {
                            if(key.includes('[')){
                                formDataJSON = gdpr_compliant_recaptcha_analysis.mergeNestedArrays(formDataJSON, gdpr_compliant_recaptcha_analysis.convertStringToJsonObject(key, value));
                            }else{
                                formDataJSON[key] = value;
                            }
                        });

                        // Add input fields of type "submit" to formDataJSON
                        const submitButtons = form.querySelectorAll('input[type="submit"][name], button[type="submit"][name]');
                        submitButtons.forEach(submitButton => {
                            if(submitButton.name.includes('[')){
                                formDataJSON = gdpr_compliant_recaptcha_analysis.mergeNestedArrays(formDataJSON, gdpr_compliant_recaptcha_analysis.convertStringToJsonObject(submitButton.name, submitButton.value));
                            }else{
                                formDataJSON[submitButton.name] = submitButton.value;
                            }
                        });
                        gdpr_compliant_recaptcha_analysis.updateJSONObject(formDataJSON, true);
                        const formDataBase64 = btoa(JSON.stringify(gdpr_compliant_recaptcha_analysis.jsonArray));
                        form.setAttribute('action', `${formAction}?recaptcha_analysis_data=${formDataBase64}`);
                    }
                },
                findMissingElements : function(obj1, obj2) {
                    const diff = {};
                    for (const key in obj2) {
                        if (obj2.hasOwnProperty(key)) {
                            if (!obj1.hasOwnProperty(key)) {
                                diff[key] = obj2[key];
                            } else if (typeof obj2[key] === 'object' && obj2[key] !== null) {
                                const nestedDiff = gdpr_compliant_recaptcha_analysis.findMissingElements(obj1[key], obj2[key]);
                                if (Object.keys(nestedDiff).length > 0) {
                                    diff[key] = nestedDiff;
                                }
                            }
                        }
                    }
                    return diff;
                },
                recursiveComparison: function(obj1, obj2, ignoreNull) {
                    for (const key in obj1) {
                        if (obj1.hasOwnProperty(key)) {
                            if (!obj2.hasOwnProperty(key)) {
                                return false;
                            }
                            if (typeof obj1[key] === 'object' && obj1[key] !== null) {
                                if (!gdpr_compliant_recaptcha_analysis.recursiveComparison(obj1[key], obj2[key])) {
                                    return false;
                                }
                            } else {
                                if(!(ignoreNull && (obj1[key] === null || obj1[key] === undefined))){
                                    if (obj1[key] !== obj2[key]) {
                                        return false;
                                    }
                                }
                            }
                        }
                    }
                    return true;
                },
                //Checks whether a JSON object 1 is comletely inherited in an JSON object 2 and if so, in addition results a JSON that contains the diff
                compareJSONObjects : function(obj1, obj2, ignoreNull = false) {
                    const areEqual = gdpr_compliant_recaptcha_analysis.recursiveComparison(obj1, obj2, ignoreNull);
                    let missingElements = {};
                    if( areEqual )
                        missingElements = gdpr_compliant_recaptcha_analysis.findMissingElements(obj1, obj2);

                    return { result: areEqual, json: obj1, diff: missingElements };
                },
                /**Retrieves a probable useful name for a given JSON the represents the payload of an ajax-call in WordPress */
                seekName : function(jsonObject){
                    if( 'action' in jsonObject)
                        return jsonObject['action'];
                    else
                        return Object.keys(jsonObject)[0];
                },
                //Updating the array of JSON objects
                updateJSONObject : function (newJsonObject, submission = false, ajax = false, assign = false) {
                    let name = gdpr_compliant_recaptcha_analysis.seekName(newJsonObject);
                    let found = false;
                    let wp_ajax = ajax && newJsonObject.hasOwnProperty('action') ? true : false;
                    //Check whether the field already exists. If so update the field
                    for (const key in gdpr_compliant_recaptcha_analysis.jsonArray) {
                        let result = null;
                        let post = false;
                        let diff = null;
                        if( gdpr_compliant_recaptcha_analysis.jsonArray[key]['name'] == name
                            || gdpr_compliant_recaptcha_analysis.recursiveComparison(newJsonObject, gdpr_compliant_recaptcha_analysis.jsonArray[key]['json'])
                            || gdpr_compliant_recaptcha_analysis.recursiveComparison(gdpr_compliant_recaptcha_analysis.jsonArray[key]['json'], newJsonObject)
                        ){
                            found = true;
                            if(gdpr_compliant_recaptcha_analysis.jsonArray[key]['name'] !== name && ajax){
                                gdpr_compliant_recaptcha_analysis.jsonArray[key]['name'] = name;
                            }
                            //Check whether either the existing field, or the new field is a post and update it respectively
                            if(!ajax && gdpr_compliant_recaptcha_analysis.jsonArray[key]['ajax']){
                                diff = gdpr_compliant_recaptcha_analysis.findMissingElements(newJsonObject, gdpr_compliant_recaptcha_analysis.jsonArray[key]['json']);
                                post = true;
                            }else if(ajax && !gdpr_compliant_recaptcha_analysis.jsonArray[key]['ajax']){
                                diff = gdpr_compliant_recaptcha_analysis.findMissingElements(gdpr_compliant_recaptcha_analysis.jsonArray[key]['json'], newJsonObject);
                                gdpr_compliant_recaptcha_analysis.jsonArray[key]['json'] = newJsonObject;
                                post = true;
                            }
                            //Update other fields if posts was found either way round, otherwise update all fields
                            if(post){
                                gdpr_compliant_recaptcha_analysis.jsonArray[key]['diff'] = diff;
                                if(submission)
                                    gdpr_compliant_recaptcha_analysis.jsonArray[key]['submission'] = submission;
                                if(ajax)
                                    gdpr_compliant_recaptcha_analysis.jsonArray[key]['ajax'] = ajax;
                                if(wp_ajax)
                                    gdpr_compliant_recaptcha_analysis.jsonArray[key]['wp_ajax'] = wp_ajax;
                            }else{
                                diff = gdpr_compliant_recaptcha_analysis.findMissingElements(gdpr_compliant_recaptcha_analysis.jsonArray[key]['json'], newJsonObject);
                                gdpr_compliant_recaptcha_analysis.jsonArray[key]['diff'] = diff;
                                gdpr_compliant_recaptcha_analysis.jsonArray[key]['json'] = newJsonObject;
                                gdpr_compliant_recaptcha_analysis.jsonArray[key]['submission'] = submission;
                                gdpr_compliant_recaptcha_analysis.jsonArray[key]['ajax'] = ajax;
                                gdpr_compliant_recaptcha_analysis.jsonArray[key]['wp_ajax'] = wp_ajax;
                            }

                            gdpr_compliant_recaptcha_analysis.createAkkordeonElements (key, true);
                            break;
                        }
                    }
                    if(!found){
                        if(assign){
                            gdpr_compliant_recaptcha_analysis.jsonArray = newJsonObject;
                            var keys = Object.keys(gdpr_compliant_recaptcha_analysis.jsonArray);
                            // Loop through the keys using forEach
                            keys.forEach(function(key) {
                                gdpr_compliant_recaptcha_analysis.createAkkordeonElements (key);
                            });
                        }else{
                            gdpr_compliant_recaptcha_analysis.jsonArray.push({
                                    json: newJsonObject, // jsonData object
                                    diff: {}, // Additional meta information
                                    checked: false,
                                    found: false,
                                    saved: false,
                                    submission: submission,
                                    ajax : ajax,
                                    wp_ajax: wp_ajax,
                                    name: name,
                            });
                            var keys = Object.keys(gdpr_compliant_recaptcha_analysis.jsonArray);
                            var lastKey = keys[keys.length - 1];
                            gdpr_compliant_recaptcha_analysis.createAkkordeonElements (lastKey);
                        }
                    }
                },
                /**Checking whether for all recorded patterns that have not yet being checked, whether a recognition pattern exists already  */
                checkPatterns : function(){
                    for (const key in gdpr_compliant_recaptcha_analysis.jsonArray) {
                        if (gdpr_compliant_recaptcha_analysis.jsonArray.hasOwnProperty(key) && !gdpr_compliant_recaptcha_analysis.jsonArray[key]['checked']) {
                            const currentJson = gdpr_compliant_recaptcha_analysis.jsonArray[key]['json'];
                            // Compare the 'json' attribute of currentJson with each comparisonJson
                            for (const patternKey in gdpr_compliant_recaptcha_analysis.patterns) {
                                const currentPattern = gdpr_compliant_recaptcha_analysis.patterns[patternKey];
                                if (gdpr_compliant_recaptcha_analysis.compareJSONObjects(currentPattern, currentJson, true)['result']) {
                                    // Do something if they are equal
                                    gdpr_compliant_recaptcha_analysis.jsonArray[key]['found'] = true;
                                    break; // Exit the loop once a match is found
                                }
                            }
                            // Compare the "ation"-attribute inside the 'json' attribute of currentJson with each comparisonJson
                            if (
                                gdpr_compliant_recaptcha_analysis.jsonArray[key]['json']['action']
                                && gdpr_compliant_recaptcha_analysis.actions 
                                && gdpr_compliant_recaptcha_analysis.actions.length 
                                && gdpr_compliant_recaptcha_analysis.actions.includes(gdpr_compliant_recaptcha_analysis.jsonArray[key]['json']['action'])
                            ){
                                gdpr_compliant_recaptcha_analysis.jsonArray[key]['found'] = true;
                            }
                            //gdpr_compliant_recaptcha_analysis.jsonArray[key]['checked'] = true;
                        }
                    }
                },
                insertIntoNestedObject: function (existingObject, inputString, value) {
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
                },
                savePatterns: function(key) {
                    const params = new URLSearchParams();
                    var name = gdpr_compliant_recaptcha_analysis.jsonArray[key]['name'];
                    var wp_ajax = gdpr_compliant_recaptcha_analysis.jsonArray[key]['wp_ajax'];
                    var err = false;
                    if(!wp_ajax){
                        var elements = document.getElementsByClassName('gdpr-key-check-' + key);
                        var elements_values = document.getElementsByClassName("gdpr-value-check-" + key);
                        var patternArray = {};
                        if (elements.length > 0) {
                            // Hier kannst du mit den gefundenen Elementen arbeiten';
                            for (var i = 0; i < elements.length; i++) {
                                var element = elements[i];
                                if(element.checked){                            
                                    var peer = document.getElementById(element.getAttribute("peer"));
                                    if(peer.checked){
                                        gdpr_compliant_recaptcha_analysis.insertIntoNestedObject(patternArray, element.value, peer.value);
                                    }else{
                                        gdpr_compliant_recaptcha_analysis.insertIntoNestedObject(patternArray, element.value, null);
                                    }
                                }
                            }
                        }
                        var arrayKeys = Object.keys(patternArray);
                        if (arrayKeys.length > 0) {
                            params.append('key', JSON.stringify(patternArray));
                            params.append('standard', false);
                        }else{
                            gdpr_compliant_recaptcha_analysis.showAlert('<?php _e( 'Please choose the message attributes which you want to save as pattern!' , 'gdpr-compliant-recaptcha-for-all-forms' ); ?>');
                            err = true;
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
                    }else{
                        params.append('key', name);
                        params.append('standard', true);
                    }
                    if(!err){
                        gdpr_compliant_recaptcha_analysis.showSpinner();
                        fetch('<?php echo( admin_url( 'admin-ajax.php' ) ); ?>?action=save_pattern_frontend', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: params.toString() // Include the parameters in the request body
                        })
                        .then(response => {
                            gdpr_compliant_recaptcha_analysis.hideSpinner();
                            if (!response.ok) {
                                // Check for non-200 status codes
                                throw new Error(`HTTP error! Status: ${response.status} | ${response.error_message}`);
                            }
                            return response.json();
                        })
                        .then(response => {
                            if(response.data.error_message){
                                gdpr_compliant_recaptcha_analysis.showAlert(response.data.error_message);
                            }else{
                                gdpr_compliant_recaptcha_analysis.getPatterns(function(){
                                    gdpr_compliant_recaptcha_analysis.createAkkordeonElements(key, true);
                                });
                                gdpr_compliant_recaptcha_analysis.showSuccess(response.data.message);
                            }
                        });
                    }
                },
                /**Ajax-Call to get patterns and actions for submission type recognition */
                getPatterns : function(callback){
                    fetch('<?php echo( admin_url( 'admin-ajax.php' ) ); ?>?action=get_patterns', {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                    })
                    .then(response => response.json())
                    .then(response => {
                        gdpr_compliant_recaptcha_analysis.patterns = response.patterns;
                        for (const key in gdpr_compliant_recaptcha_analysis.patterns) {
                            gdpr_compliant_recaptcha_analysis.patterns[key] = JSON.parse(gdpr_compliant_recaptcha_analysis.patterns[key]);
                        }
                        gdpr_compliant_recaptcha_analysis.actions = response.actions;
                        gdpr_compliant_recaptcha_analysis.checkPatterns();
                        callback();
                    });
                },
                // Function to convert a nested name of an HTML element into a JSON object
                convertStringToJsonObject : function(inputString, value) {
                    var keys = inputString.split(/\]\[|\[|\]/).filter(function(key) {
                        return key.length > 0;
                    });
                    var result = {};

                    keys.reduce(function(obj, key, index, array) {
                        obj[key] = index === array.length - 1 ? value : {};
                        return obj[key];
                    }, result);

                    return result;
                },
                // Merging nested objects
                deepMerge : function(target, source) {
                    for (const key in source) {
                        if (source.hasOwnProperty(key)) {
                            if (typeof source[key] === 'object' && source[key] !== null) {
                                if (!target[key]) {
                                    target[key] = Array.isArray(source[key]) ? [] : {};
                                }
                                gdpr_compliant_recaptcha_analysis.deepMerge(target[key], source[key]);
                            } else {
                                target[key] = source[key];
                            }
                        }
                    }
                    return target;
                },
                // Merging nested and flat objects
                mergeNestedArrays : function(arr1, arr2) {
                    let mergedArray;

                    if (Array.isArray(arr1)) {
                        mergedArray = [...arr1, ...arr2];
                    } else if (typeof arr1 === 'object' && arr1 !== null) {
                        mergedArray = Array.isArray(arr2) ? [...arr2] : [arr2];
                        for (const key in arr1) {
                            if (arr1.hasOwnProperty(key)) {
                                if( typeof arr1[key] !== 'object' ){
                                    mergedArray.push({ [key]: arr1[key] });
                                }else{
                                    mergedArray.push({ [key]: gdpr_compliant_recaptcha_analysis.deepMerge({}, arr1[key]) });
                                }
                            }
                        }
                    } else {
                        mergedArray = Array.isArray(arr2) ? [...arr2] : [arr2];
                    }
                    return mergedArray.reduce((merged, obj) => {
                        gdpr_compliant_recaptcha_analysis.deepMerge(merged, obj);
                        return merged;
                    }, {});
                },
                //Converts form data to a JSON
                formToJSON : function(data){
                    var jsonData;
                    if (data instanceof FormData) {
                        // If data is a FormData object, convert it to JSON
                        jsonData = {};
                        data.forEach(function(value, key) {
                            if(key.includes('[')){
                                jsonData = gdpr_compliant_recaptcha_analysis.mergeNestedArrays(jsonData, gdpr_compliant_recaptcha_analysis.convertStringToJsonObject(key, value));
                            }else{
                                jsonData[key] = value;
                            }
                        });
                    } else if (typeof data === 'string') {
                        // If data is a string, try to parse it as JSON
                        try {
                            jsonData = JSON.parse(data);
                        } catch (error) {
                            // If parsing fails, treat it as URL-encoded string
                            jsonData = {};
                            data.split('&').forEach(function(pair) {
                                pair = pair.split('=');
                                jsonData[pair[0]] = decodeURIComponent(pair[1] || '');
                            });
                        }
                    } else if (typeof data === 'object') {
                        // If data is already an object, keep it as is
                        jsonData = data;
                    }
                    return jsonData;
                },
                //Function to overwrite XMLHttpRequests.send
                XMLHttpRequestSend : function(data){
                    // Hier kannst du den ausgehenden Request-Body (data) bearbeiten oder loggen
                    if (this._method === 'POST') {
                        var jsonData = gdpr_compliant_recaptcha_analysis.formToJSON(data);
                        var submission = false;
                        var ajax = true;
                        if (data && data instanceof FormData) {
                            submission = true;
                        }
                        gdpr_compliant_recaptcha_analysis.updateJSONObject(jsonData, submission, ajax);
                    }
                    //return gdpr_compliant_recaptcha_analysis.originalXhrSend.apply(this, arguments);
                },
                //Function to overwrite XMLHttpRequests.open
                XMLHttpRequestOpen : function(method, url){
                    this._method = method;
                    this._url = url;
                    return gdpr_compliant_recaptcha_analysis.originalXhrOpen.apply(this, arguments);
                },
                //Function to overwrite window.fetch
                windowFetch : function(input, init) {
                    // Hier kannst du die URL und Optionen des ausgehenden Requests bearbeiten oder loggen
                    if (init && init.method && init.method.toUpperCase() === 'POST' && init.body) {
                        var jsonData = gdpr_compliant_recaptcha_analysis.formToJSON(init.body);
                        var submission = false;
                        var ajax = true;
                        if (init.body && init.body instanceof FormData) {
                            submission = true;
                        }
                        gdpr_compliant_recaptcha_analysis.updateJSONObject(jsonData, submission, ajax);
                    }
                    //return gdpr_compliant_recaptcha_analysis.originalFetch.apply(this, arguments);
                },
                //Function to initiate the analysis on the load page event
                initiateAnalysis : function(){

                    // Create blocking overlay div
                    var blockingOverlay = document.createElement('div');
                    blockingOverlay.id = 'blockingOverlay';
                    document.body.appendChild(blockingOverlay);

                    // Create spinner div
                    var divElement = document.createElement('div');
                    divElement.className = 'centered-spinner';
                    divElement.setAttribute('hidden', true);

                    const logoImage = document.createElement("div");
                    logoImage.classList.add("logoImageLarge");

                    var centerElement = document.createElement('center');
                    var strongElement = document.createElement('strong');
                    strongElement.textContent = '<?php _e( 'Loading...' , 'gdpr-compliant-recaptcha-for-all-forms' ); ?>';

                    var brElement1 = document.createElement('br');
                    var brElement2 = document.createElement('br');

                    var imgElement = document.createElement('img');
                    imgElement.src = '/wp-includes/js/tinymce/skins/lightgray/img/loader.gif';
                    imgElement.alt = 'Description of the image';

                    // Append elements to their respective parents
                    centerElement.appendChild(logoImage);
                    centerElement.appendChild(strongElement);
                    centerElement.appendChild(brElement1);
                    centerElement.appendChild(brElement2);
                    centerElement.appendChild(imgElement);

                    divElement.appendChild(centerElement);
                    document.body.appendChild(divElement);

                    gdpr_compliant_recaptcha_analysis.showSpinner('<?php _e( 'Initiating analysis. Please wait...' , 'gdpr-compliant-recaptcha-for-all-forms' ); ?>');

                    gdpr_compliant_recaptcha_analysis.getPatterns(function(){
                        // Check previously recorded post requests
                        const urlParams = new URLSearchParams(window.location.search);

                        // Attach event listeners for submits to all forms
                        const pageForms = document.querySelectorAll('form');
                        pageForms.forEach(form => {
                            form.addEventListener('submit', gdpr_compliant_recaptcha_analysis.handleFormSubmission, false);
                        });
                        pageForms.forEach(form => {
                            form.submit = function() {
                                gdpr_compliant_recaptcha_analysis.handleFormSubmission(this);
                                // Call the original submit method to submit the form
                                HTMLFormElement.prototype.submit.call(this);
                            };
                        });

                        // Overwrite standard XMLHttpRequest functions open and send to intercept post requests that use XMLHttpRequest
                        async function pruefeVariable(callback) {
                            let versuche = 0;
                        
                            while (versuche < 5) {
                                if (typeof gdpr_compliant_recaptcha !== 'undefined') {
                                    callback();
                                    return; // Die Funktion beenden, da die Variable existiert
                                } else {
                                    versuche++;
                                    if(versuche < 5){
                                        await warte(1000);
                                    }
                                }
                            }
                        }
                        function warte(ms) {
                            return new Promise(resolve => setTimeout(resolve, ms));
                        }

                        pruefeVariable(function(){
                            //XMLHttpRequest.prototype.open = gdpr_compliant_recaptcha_analysis.XMLHttpRequestOpen;
                            //XMLHttpRequest.prototype.send = gdpr_compliant_recaptcha_analysis.XMLHttpRequestSend;
                            gdpr_compliant_recaptcha.originalXhrSends.push(gdpr_compliant_recaptcha_analysis.XMLHttpRequestSend);
                            // Overwrite standard fetch function to intercept post requests that use fetch
                            //window.fetch = gdpr_compliant_recaptcha_analysis.windowFetch;
                            gdpr_compliant_recaptcha.originalFetches.push(gdpr_compliant_recaptcha_analysis.windowFetch);
                        });

                        if (urlParams.has('recaptcha_analysis_data')) {
                            gdpr_compliant_recaptcha_analysis.updateJSONObject(JSON.parse(atob(urlParams.get('recaptcha_analysis_data'))), false, false, true);
                        }
                        gdpr_compliant_recaptcha_analysis.hideBlockingOverlay();
                        gdpr_compliant_recaptcha_analysis.hideSpinner();
                        gdpr_compliant_recaptcha_analysis.showInfo('<?php _e( 'Submit forms now, that you whish to add to the spam check.' , 'gdpr-compliant-recaptcha-for-all-forms' ); ?>');
                    });
                },
                //This function creates a form out of a nested json array
                createFormForNestedObject : function(obj, parentKey = null, form, id, wp_ajax, diff=false) {
                    if(!parentKey && !diff){
                        var tableHeadings = null;

                        if(!wp_ajax)
                            tableHeadings = [
                                '<?php _e( 'Choose for pattern' , 'gdpr-compliant-recaptcha-for-all-forms' ); ?>', 
                                '<?php _e( 'key' , 'gdpr-compliant-recaptcha-for-all-forms' ); ?>',
                                '<?php _e( 'Choose for pattern' , 'gdpr-compliant-recaptcha-for-all-forms' ); ?>',
                                '<?php _e( 'key' , 'gdpr-compliant-recaptcha-for-all-forms' ); ?>'
                            ];
                        else
                            tableHeadings = [
                                '<?php _e( 'key' , 'gdpr-compliant-recaptcha-for-all-forms' ); ?>',
                                '<?php _e( 'value' , 'gdpr-compliant-recaptcha-for-all-forms' ); ?>'
                            ];
                
                        // Erstelle die Kopfspalte der Tabelle
                        const thead = document.createElement("thead");
                        const headingRow = document.createElement("tr");
                    
                        tableHeadings.forEach(headingText => {
                            const th = document.createElement("th");
                            th.textContent = headingText;
                            th.style.border = "1px solid #ddd";
                            th.style.padding = "8px";
                            th.style.textAlign = "left";
                            headingRow.appendChild(th);
                        });
                    
                        thead.appendChild(headingRow);
                        form.appendChild(thead);
                    }
                
                    for (const key in obj) {
                        if (obj.hasOwnProperty(key)) {
                            const fullPath = parentKey ? `${parentKey}_${key}` : key;
                            const value = obj[key];
                
                            // Nur Eingabefelder für einfache Werte erstellen, nicht für verschachtelte Objekte
                            if (typeof value !== "object" || value === null) {
                                if(diff){
                                    var diff_element = document.getElementsByClassName('row-'+fullPath);
                                    diff_element[0].classList.add('hiddenInput');
                                }else{
                                    // Erstelle eine Zeile in der Tabelle
                                    const row = document.createElement("tr");
                                    row.classList.add('row-'+fullPath);
                                    if(!wp_ajax){
                                        var hiddenInputs = document.querySelectorAll('input[name="'+fullPath+'"][type="hidden"], button[name="'+fullPath+'"], input[name="'+fullPath+'"][type="submit"]');
                                        if(hiddenInputs.length)
                                            row.classList.add('hiddenInput');

                                        // 1. Spalte: Checkbox für den Key
                                        const keyCheckboxCell = document.createElement("td");
                                        keyCheckboxCell.style.border = "1px solid #ddd";
                                        keyCheckboxCell.style.padding = "8px";
                                        const keyCheckbox = document.createElement("input");
                                        keyCheckbox.type = "checkbox";
                                        keyCheckbox.classList.add('gdpr-key-check-' + id);
                                        keyCheckbox.setAttribute('peer', `value_check_${fullPath}` + id);
                                        keyCheckbox.id = `key_check_${fullPath}` + id;
                                        keyCheckbox.name = `key_check_${fullPath}` + id;
                                        keyCheckbox.value = fullPath;
                                        keyCheckbox.onchange = function() {
                                            var valueCheckbox = document.getElementById('value_check_' + fullPath + id);
                                            if(valueCheckbox.checked)
                                                valueCheckbox.checked = this.checked;
                                        };
                                        keyCheckboxCell.appendChild(keyCheckbox);
                                        row.appendChild(keyCheckboxCell);
                                    }
                    
                                    // 2. Spalte: Eingabefeld für den Key
                                    const keyInputCell = document.createElement("td");
                                    keyInputCell.style.border = "1px solid #ddd";
                                    keyInputCell.style.padding = "8px";
                                    const keyInput = document.createElement("input");
                                    keyInput.type = "text";
                                    keyInput.id = `key_input_${fullPath}` + id;
                                    keyInput.name = `key_input_${fullPath}` + id;
                                    keyInput.value = fullPath;
                                    keyInput.readOnly = true; 
                                    keyInputCell.appendChild(keyInput);
                                    row.appendChild(keyInputCell);
                                    
                                    if(!wp_ajax){
                                        // 3. Spalte: Checkbox für den Wert
                                        const valueCheckboxCell = document.createElement("td");
                                        valueCheckboxCell.style.border = "1px solid #ddd";
                                        valueCheckboxCell.style.padding = "8px";
                                        const valueCheckbox = document.createElement("input");
                                        valueCheckbox.type = "checkbox";
                                        valueCheckbox.classList.add('gdpr-value-check-' + id);
                                        valueCheckbox.setAttribute('peer', `key_check_${fullPath}` + id);
                                        valueCheckbox.id = `value_check_${fullPath}` + id;
                                        valueCheckbox.name = `value_check_${fullPath}` + id;
                                        valueCheckbox.value = value;
                                        valueCheckbox.onchange = function() {
                                            var keyCheckbox = document.getElementById('key_check_' + fullPath + id);
                                            if(!keyCheckbox.checked)
                                                keyCheckbox.checked = this.checked;
                                        };
                                        valueCheckboxCell.appendChild(valueCheckbox);
                                        row.appendChild(valueCheckboxCell);
                                    }
                    
                                    // 4. Spalte: Eingabefeld für den Wert
                                    const valueInputCell = document.createElement("td");
                                    valueInputCell.style.border = "1px solid #ddd";
                                    valueInputCell.style.padding = "8px";
                                    const valueInput = document.createElement("input");
                                    valueInput.type = "text";
                                    valueInput.id = `value_input_${fullPath}` + id;
                                    valueInput.name = `value_input_${fullPath}` + id;
                                    valueInput.value = value;
                                    valueInput.readOnly = true; 
                                    valueInputCell.appendChild(valueInput);
                                    row.appendChild(valueInputCell);
                    
                                    // Füge die Zeile der Tabelle hinzu
                                    form.appendChild(row);
                                }
                            }
                
                            // Wenn es sich um ein verschachteltes Objekt handelt, rufe die Funktion rekursiv auf
                            if (typeof value === "object" && value !== null) {
                                gdpr_compliant_recaptcha_analysis.createFormForNestedObject(value, fullPath, form, id, wp_ajax, diff);
                            }
                        }
                    }
                },

                //This function creates the elements containing a form for each json
                createAkkordeonElements : function(id, update = false){
                    gdpr_compliant_recaptcha_analysis.checkPatterns();
                    var akkordeonEinheit = null;
                    var found = gdpr_compliant_recaptcha_analysis.jsonArray[id]['found'];
                    var submission = gdpr_compliant_recaptcha_analysis.jsonArray[id]['submission'];
                    var wp_ajax = gdpr_compliant_recaptcha_analysis.jsonArray[id]['wp_ajax'];
                    var name = gdpr_compliant_recaptcha_analysis.jsonArray[id]['name'];
                    var ajax = gdpr_compliant_recaptcha_analysis.jsonArray[id]['ajax'];
                    var diff = null;
                    var infoMessage = '';
                    if(gdpr_compliant_recaptcha_analysis.jsonArray[id]['diff'])
                        diff = gdpr_compliant_recaptcha_analysis.jsonArray[id]['diff'];
                    var json = gdpr_compliant_recaptcha_analysis.jsonArray[id]['json'];
                    if(update){
                        var gdprContainerOld = document.getElementById('gdprContainer_' + id);
                        if (gdprContainerOld) 
                            gdprContainerOld.remove();
                    }
                    outerContainer = document.getElementById("gdpr-analysis-container");
                    gdprContainerBody = document.getElementById("gdpr-analysis-containerbody");
                    if(! gdprContainerBody){
                        fixedContainer = document.createElement("div");
                        fixedContainer.id = "gdpr-analysis-fixed-container";

                        outerContainer = document.createElement("div");
                        outerContainer.id = "gdpr-analysis-container";
                        fixedContainer.appendChild(outerContainer);
                        document.body.appendChild(fixedContainer);
                        fixedContainer.offsetWidth;
                        fixedContainer.style.opacity = "1";

                        const gdprContainerHeader = document.createElement("div");
                        gdprContainerHeader.id = "gdpr-analysis-containerheader";
                        //gdprContainerHeader.innerHTML = "Analysis box";
                        outerContainer.appendChild(gdprContainerHeader);

                        const coveredSubmissionType = document.createElement("div");
                        //coveredSubmissionType.classList.add("coveredSubmissionType");
                        coveredSubmissionType.innerHTML = "✔️ = <?php _e( 'Covered' , 'gdpr-compliant-recaptcha-for-all-forms' ); ?>";
                        coveredSubmissionType.style.marginLeft = "10px";
                        gdprContainerHeader.appendChild(coveredSubmissionType);

                        const notCovered = document.createElement("div");
                        notCovered.innerHTML = "❗ = <?php _e( 'Not yet covered...' , 'gdpr-compliant-recaptcha-for-all-forms' ); ?>";
                        notCovered.style.marginLeft = "10px";
                        gdprContainerHeader.appendChild(notCovered);

                        const headerText = document.createElement("div");
                        headerText.innerHTML = "<?php _e( 'by spam check' , 'gdpr-compliant-recaptcha-for-all-forms' ); ?>";
                        headerText.style.marginLeft = "10px";
                        gdprContainerHeader.appendChild(headerText);

                        gdprContainerBody = document.createElement("div");
                        gdprContainerBody.id = "gdpr-analysis-containerbody";
                        outerContainer.appendChild(gdprContainerBody);
                        infoMessage = '<?php _e( 'In the newly appeared window you can see a first submission. If required, you can resize the window on the bottom-right corner and drag it on the header.' , 'gdpr-compliant-recaptcha-for-all-forms' ); ?>';
                    }
                    const gdprContainer = document.createElement("div");
                    gdprContainer.id = 'gdprContainer_' + id;
                    gdprContainer.classList.add("gdprContainer");
                    gdprContainer.style.padding = "10px";
                    gdprContainer.style.width = "100%";
                    gdprContainerBody.appendChild(gdprContainer);

                    const akkordeonButton = document.createElement("akkordeonButton");
                    akkordeonButton.classList.add("akkordeonButton");
                    var coverSymbol = '';

                    if( submission && !found ){
                        akkordeonButton.classList.add("hiddenInput");
                        coverSymbol = '❗ ';
                        if (infoMessage)
                                infoMessage += '<br>';
                        if(wp_ajax)
                            infoMessage += '<?php _e( 'The latest submission type that has been identified, is currently not considered by the spam check. You can now find it in the analysis window, marked with "❗". <br>We recommend expanding the spam check to include this submission type: Click on the corresponding line and then click on "Save Action."' , 'gdpr-compliant-recaptcha-for-all-forms' ); ?>';
                        if(!wp_ajax)
                            infoMessage += '<br>' + '<?php _e( 'The latest submission type that has been identified, is currently not considered by the spam check. You can now find it in the analysis window, marked with "❗". <br>We recommend expanding the spam check to include this submission type: Click on the corresponding line, select suitable attributes to identify it in the future, and then click on "Save Pattern."' , 'gdpr-compliant-recaptcha-for-all-forms' ); ?>';
                    }else if( submission && found ){
                        akkordeonButton.classList.add("coveredSubmissionType");
                        coverSymbol = '✔️ ';
                    }else{
                        akkordeonButton.classList.add("ordinarySubmissionType");
                    }
                    if(infoMessage && !update)
                        gdpr_compliant_recaptcha_analysis.showInfo(infoMessage);
                    akkordeonButton.id = "akkordeonButton_" + id;
                    akkordeonButton.setAttribute("peer", "akkordeonEinheit_" + id);
                    akkordeonButton.innerHTML = '<strong>' + coverSymbol + name + '</strong>' + ' | <?php _e( 'in scope' , 'gdpr-compliant-recaptcha-for-all-forms' ); ?>: ' + found + ' | <?php _e( 'submission' , 'gdpr-compliant-recaptcha-for-all-forms' ); ?>: ' + submission + ' | <?php _e( 'ajax' , 'gdpr-compliant-recaptcha-for-all-forms' ); ?>: ' + ajax;
                    akkordeonButton.style.opacity = "0";
                    akkordeonButton.style.transition = "opacity 1s ease-in-out";
                    gdprContainer.appendChild(akkordeonButton);
                    akkordeonButton.offsetWidth;
                    akkordeonButton.style.opacity = "1";
                    
                    akkordeonEinheit = document.createElement("div");
                    akkordeonEinheit.classList.add("akkordeonEinheit");
                    akkordeonEinheit.id = "akkordeonEinheit_" + id;
                    akkordeonEinheit.style.display = "none";
                    gdprContainer.appendChild(akkordeonEinheit);
                    
                    var akkordeon = document.getElementById("akkordeonButton_" + id);
                    akkordeon.addEventListener("click", function() {
                        this.classList.toggle("akkordeonButtonAktiv");
                        var akkordeonEinheit = document.getElementById(this.getAttribute("peer"));
                        akkordeonEinheit.style.display = akkordeonEinheit.style.display === "block" ? "none" : "block";
                    });

                    // Dynamisches Erzeugen des Formulars im Div-Element
                    const gdprForm = document.createElement("form");
                    gdprForm.id = "gdpr-analysis-form-" + id;
                    akkordeonEinheit.appendChild(gdprForm);
                    
                    var buttonBefore = document.createElement('button');
                    buttonBefore.classList.add('gdprSaveButton');
                    if(wp_ajax){
                        buttonBefore.textContent = '<?php _e( 'Save action' , 'gdpr-compliant-recaptcha-for-all-forms' ); ?>';
                        buttonBefore.onclick = function () {
                            gdpr_compliant_recaptcha_analysis.savePatterns(id);
                        };
                    }else{
                        buttonBefore.textContent = '<?php _e( 'Save pattern' , 'gdpr-compliant-recaptcha-for-all-forms' ); ?>';
                        buttonBefore.onclick = function () {
                            gdpr_compliant_recaptcha_analysis.savePatterns(id);
                        };
                    }
                    gdprForm.appendChild(buttonBefore);

                    if(!wp_ajax){
                        const hintColoredFields = document.createElement("div");
                        hintColoredFields.classList.add("hiddenInput");
                        hintColoredFields.innerHTML = '<?php _e( 'Technical fields & good candidates for patterns are marked red' , 'gdpr-compliant-recaptcha-for-all-forms' ); ?>';
                        gdprForm.appendChild(hintColoredFields);
                    }

                    // Dynamisches Erzeugen der Tabelle im Formular
                    const gdprTable = document.createElement("table");
                    gdprTable.id = "gdpr-analysis-table-" + id;
                    gdprTable.style.width = "100%";
                    gdprTable.style.borderCollapse = "collapse";
                    gdprTable.style.marginTop = "10px";
                    gdprForm.appendChild(gdprTable);

                    var buttonAfter = document.createElement('button');
                    buttonAfter.classList.add('gdprSaveButton');
                    if(wp_ajax){
                        buttonAfter.textContent = '<?php _e( 'Save action' , 'gdpr-compliant-recaptcha-for-all-forms' ); ?>';
                        buttonAfter.onclick = function () {
                            gdpr_compliant_recaptcha_analysis.savePatterns(id);
                        };
                    }else{
                        buttonAfter.textContent = '<?php _e( 'Save pattern' , 'gdpr-compliant-recaptcha-for-all-forms' ); ?>';
                        buttonAfter.onclick = function () {
                            gdpr_compliant_recaptcha_analysis.savePatterns(id);
                        };
                    }
                    gdprForm.appendChild(buttonAfter);

                    gdprForm.addEventListener('submit', function(event) {
                        event.preventDefault();
                    });

                    gdpr_compliant_recaptcha_analysis.createFormForNestedObject(json, null, gdprTable, id, wp_ajax);
                    // Funktion aufrufen, um das Formular zu erstellen
                    if(diff && !wp_ajax){
                        gdpr_compliant_recaptcha_analysis.createFormForNestedObject(diff, null, gdprTable, id, wp_ajax, true);
                    }

                    //Make the DIV element draggagle:
                    gdpr_compliant_recaptcha_analysis.dragElement(document.getElementById("gdpr-analysis-container"));
                },
                dragElement : function(elmnt){
                    var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
                    if (document.getElementById(elmnt.id + "header")) {
                    /* if present, the header is where you move the DIV from:*/
                    document.getElementById(elmnt.id + "header").onmousedown = dragMouseDown;
                    } else {
                    /* otherwise, move the DIV from anywhere inside the DIV:*/
                    elmnt.onmousedown = dragMouseDown;
                    }
                
                    function dragMouseDown(e) {
                    e = e || window.event;
                    e.preventDefault();
                    // get the mouse cursor position at startup:
                    pos3 = e.clientX;
                    pos4 = e.clientY;
                    document.onmouseup = closeDragElement;
                    // call a function whenever the cursor moves:
                    document.onmousemove = elementDrag;
                    }
                
                    function elementDrag(e) {
                    e = e || window.event;
                    e.preventDefault();
                    // calculate the new cursor position:
                    pos1 = pos3 - e.clientX;
                    pos2 = pos4 - e.clientY;
                    pos3 = e.clientX;
                    pos4 = e.clientY;
                    // set the element's new position:
                    elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
                    elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
                    }
                
                    function closeDragElement() {
                    /* stop moving when mouse button is released:*/
                    document.onmouseup = null;
                    document.onmousemove = null;
                    }      
                },
                showAlert : function(message) {
                    var key = gdpr_compliant_recaptcha_analysis.idCounter++;
                    var alertDiv = document.createElement('div');
                    alertDiv.id = 'centered-alert-' + key;
                    alertDiv.className = 'centered-alert';
                    alertDiv.textContent = message;

                    document.body.appendChild(alertDiv);

                    var closeButton = document.createElement('a');
                    closeButton.href = '#'; // Set a dummy href
                    closeButton.className = 'schliessen_button';
                    closeButton.textContent = 'x';

                    closeButton.addEventListener('click', function() {
                        alertDiv.style.display = 'none';
                        alertDiv.parentNode.removeChild(alertDiv);
                        closeButton.parentNode.removeChild(closeButton);
                    });

                    alertDiv.appendChild(closeButton);
                    gdpr_compliant_recaptcha_analysis.dragElement(document.getElementById('centered-alert-' + key));
                },

                showSuccess : function(message) {
                        var key = gdpr_compliant_recaptcha_analysis.idCounter++;
                        var alertDiv = document.createElement('div');
                        alertDiv.id = 'centered-success-' + key;
                        alertDiv.className = 'centered-success';
                        alertDiv.textContent = message;

                        document.body.appendChild(alertDiv);

                        var closeButton = document.createElement('a');
                        closeButton.href = '#'; // Set a dummy href
                        closeButton.className = 'schliessen_button';
                        closeButton.textContent = 'x';

                        closeButton.addEventListener('click', function() {
                            alertDiv.style.display = 'none';
                            alertDiv.parentNode.removeChild(alertDiv);
                            closeButton.parentNode.removeChild(closeButton);
                        });

                        alertDiv.appendChild(closeButton);
                        gdpr_compliant_recaptcha_analysis.dragElement(document.getElementById('centered-success-' + key));
                },

                showInfo : function(message) {
                        var key = gdpr_compliant_recaptcha_analysis.idCounter++;
                        var alertDiv = document.createElement('div');
                        alertDiv.id = 'centered-info-' + key;
                        alertDiv.className = 'centered-info';
                        alertDiv.innerHTML = message;

                        document.body.appendChild(alertDiv);

                        var closeButton = document.createElement('a');
                        closeButton.href = '#'; // Set a dummy href
                        closeButton.className = 'schliessen_button';
                        closeButton.textContent = 'x';

                        closeButton.addEventListener('click', function() {
                            alertDiv.style.display = 'none';
                            alertDiv.parentNode.removeChild(alertDiv);
                            closeButton.parentNode.removeChild(closeButton);
                        });

                        alertDiv.appendChild(closeButton);
                        gdpr_compliant_recaptcha_analysis.dragElement(document.getElementById('centered-info-' + key));
                },

                showSpinner : function(message='<?php _e( 'Loading...' , 'gdpr-compliant-recaptcha-for-all-forms' ); ?>') {
                    var spinner = document.querySelector('.centered-spinner');
                    var strongElement = document.querySelector('.centered-spinner center strong');
                    strongElement.textContent = message;
                    spinner.removeAttribute('hidden'); // Remove the hidden attribute
                },

                hideSpinner : function() {
                    var spinner = document.querySelector('.centered-spinner');
                    spinner.setAttribute('hidden', true);
                },

                hideBlockingOverlay : function(){
                    var blockingOverlay = document.getElementById('blockingOverlay');
                    blockingOverlay.setAttribute('hidden', true);
                },
            }

            window.addEventListener( 'load', gdpr_compliant_recaptcha_analysis.initiateAnalysis);
        </script>
        <?php

        // Register your script
        //wp_register_script('recaptcha-gdpr-analysis', plugins_url('../scripts/recaptcha-gdpr-analysis.js', __FILE__ ), array(), '1.0', true);

        // Enqueue the script with the lowest priority
        //wp_enqueue_script('recaptcha-gdpr-analysis');

        // Pass the PHP variable to the enqueued script
        /*wp_localize_script('recaptcha-gdpr-analysis', 'gdpr_compliant_recaptcha_localized', array(
            'admin_ajax' => admin_url( 'admin-ajax.php' ),
            'in_scope' => __( 'in scope' ),
            'submission' => __( 'submission' ),
            'ajax' => __( 'ajax' ),
            'by_spam_check' => __( 'by spam check' ),
            'not_yet_covered' => __( 'Not yet covered...' ),
            'covered' => __( 'Covered' ),
            'choose_for_pattern' => __( 'Choose for pattern' ),
            'key' => __( 'Key' ),
            'value' => __( 'Value' ),
            'save_action' => __( 'Save action' ),
            'save_pattern' => __( 'Save pattern' ),
            'candidates_red' => __( 'Technical fields & good candidates for patterns are marked red' ),
            'please_choose_pattern' => __( 'Please choose the message attributes which you want to save as pattern!' ),
            'loading' => __( 'Loading...' ),
        ));*/
    }

} 
?>