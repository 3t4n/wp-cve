<?php 
if (!defined('WPINC')) {
    die('Closed');
}
$rm_admin_email = get_option('admin_email');
$help_link = "https://registrationmagic.com/feedback-support-form/";
$deactivate_reasons = array(
    'feature_not_available'=> array(
        'title' => ''.__("Doesn't have the feature I need",'custom-registration-form-builder-with-submission-manager'),
        'input_placeholder' => __("Please let us know the missing feature...",'custom-registration-form-builder-with-submission-manager'),
        'feedback_icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 194 194" fill-rule="nonzero" stroke-linejoin="round" stroke-miterlimit="2" fill="#2271b1" xmlns:v="https://vecta.io/nano"><path d="M193.119 96.559h-6.743c-.002 24.814-10.048 47.246-26.307 63.51s-38.696 26.305-63.508 26.307-47.246-10.048-63.511-26.307-26.305-38.696-26.307-63.51 10.047-47.245 26.305-63.51C49.314 16.791 71.746 6.745 96.56 6.744s47.245 10.047 63.508 26.305c16.259 16.265 26.305 38.697 26.307 63.51h6.743C193.117 43.229 149.89.002 96.56 0S.002 43.229 0 96.559s43.229 96.557 96.56 96.56 96.557-43.23 96.559-96.56zm-146.79-21.69c0-4.546 3.686-8.23 8.232-8.23s8.232 3.684 8.232 8.23-3.686 8.232-8.232 8.232-8.232-3.686-8.232-8.232zm83.999 0a8.23 8.23 0 0 1 8.23-8.23c4.546 0 8.232 3.684 8.232 8.23s-3.686 8.232-8.232 8.232-8.23-3.686-8.23-8.232zm-71.589 44.979l2.975-1.585-12.409-23.289a3.37 3.37 0 0 0-2.975-1.787c-1.247 0-2.39.685-2.975 1.787l-12.409 23.289.011-.022-2.472 5.142c-.627 1.71-.963 3.568-.954 5.604.002 10.385 8.416 18.797 18.799 18.799a18.8 18.8 0 0 0 18.799-18.799 17.55 17.55 0 0 0-.2-2.804c-.213-1.352-.63-2.604-1.169-3.825-.543-1.231-1.2-2.472-2.031-4.068l-.014-.027-2.975 1.585-2.991 1.558c1.123 2.145 1.818 3.497 2.163 4.471.176.493.282.9.359 1.369s.116 1.014.117 1.741c-.002 3.34-1.345 6.332-3.532 8.525-2.193 2.186-5.185 3.529-8.525 3.53s-6.332-1.344-8.525-3.53c-2.186-2.193-3.529-5.185-3.53-8.525.008-1.39.175-2.242.552-3.315.381-1.065 1.054-2.372 2.058-4.217l.013-.022 9.433-17.704 9.433 17.704-.014-.027 2.991-1.558zm13.342-4.869a45.58 45.58 0 0 1 24.625-7.175 45.57 45.57 0 0 1 27.197 8.946c1.498 1.106 3.608.79 4.716-.708s.79-3.608-.706-4.716a52.33 52.33 0 0 0-31.207-10.266c-10.39-.002-20.1 3.023-28.255 8.236-1.569 1.003-2.029 3.088-1.027 4.657s3.088 2.028 4.657 1.025z"></path></svg>',
        'show_help_link' => false
    ),
    'feature_not_working'=> array(
        'title'=> ''.__("One of the features didn't work",'custom-registration-form-builder-with-submission-manager'),
        'input_placeholder'=>  '',
        'feedback_icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 194 194" fill-rule="nonzero" stroke-linejoin="round" stroke-miterlimit="2" fill="#272525" xmlns:v="https://vecta.io/nano"><path d="M193.75 96.876h-6.764c-.002 24.893-10.081 47.399-26.393 63.717-16.318 16.312-38.824 26.39-63.717 26.391s-47.401-10.08-63.717-26.391C16.847 144.275 6.767 121.769 6.765 96.876s10.081-47.401 26.393-63.717S71.981 6.767 96.876 6.765s47.399 10.081 63.717 26.393c16.312 16.317 26.391 38.822 26.393 63.717h6.764C193.748 43.372 150.38.003 96.876 0 43.372.003.003 43.372 0 96.876c.003 53.504 43.372 96.873 96.876 96.874s96.873-43.37 96.874-96.874zM46.482 75.115a8.26 8.26 0 0 1 8.259-8.259 8.26 8.26 0 0 1 8.257 8.259 8.26 8.26 0 0 1-8.257 8.259 8.26 8.26 0 0 1-8.259-8.259zm84.272 0a8.26 8.26 0 0 1 8.259-8.259 8.26 8.26 0 0 1 8.259 8.259 8.26 8.26 0 0 1-8.259 8.259 8.26 8.26 0 0 1-8.259-8.259zm-76.059 61.364c3.529-8.142 9.357-15.072 16.659-19.959a45.71 45.71 0 0 1 25.522-7.733 45.72 45.72 0 0 1 25.517 7.73 46.15 46.15 0 0 1 16.659 19.951 3.38 3.38 0 0 0 4.449 1.757c1.714-.744 2.501-2.735 1.757-4.449a52.9 52.9 0 0 0-19.104-22.881 52.48 52.48 0 0 0-29.279-8.873 52.48 52.48 0 0 0-29.285 8.876 52.92 52.92 0 0 0-19.102 22.889c-.743 1.714.043 3.706 1.757 4.449s3.706-.043 4.449-1.757z"></path></svg>',
        'show_help_link' => __("Get free help to fix the feature",'custom-registration-form-builder-with-submission-manager')
    ),           
    'plugin_difficult_to_use' => array(
        'title' => ''.__("Difficult or confusing to use.",'custom-registration-form-builder-with-submission-manager'),
        'input_placeholder' => __("Could you please share the plugin's name",'custom-registration-form-builder-with-submission-manager'),
        'feedback_icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 194 194" fill-rule="nonzero" stroke-linejoin="round" stroke-miterlimit="2" fill="#272525" xmlns:v="https://vecta.io/nano"><path d="M193.743 96.87h-6.765c-.002 24.896-10.081 47.404-26.392 63.72-16.316 16.3-38.821 26.376-63.714 26.392-24.894-.016-47.399-10.092-63.717-26.392-16.311-16.316-26.389-38.824-26.391-63.72s10.079-47.399 26.391-63.717C49.473 16.842 71.978 6.765 96.872 6.763s47.398 10.079 63.714 26.391c16.311 16.318 26.391 38.823 26.392 63.717h6.765C193.742 43.367 150.375 0 96.872-.002S.002 43.367 0 96.87s43.369 96.861 96.873 96.877c53.502-.016 96.869-43.377 96.871-96.877zm-139.05 39.605c3.529-8.15 9.355-15.074 16.658-19.961s16.065-7.736 25.521-7.736a45.69 45.69 0 0 1 25.517 7.736 46.17 46.17 0 0 1 16.657 19.945c.743 1.719 2.736 2.499 4.451 1.767a3.39 3.39 0 0 0 1.757-4.457c-4.056-9.344-10.73-17.271-19.103-22.874s-18.459-8.882-29.278-8.882-20.913 3.279-29.284 8.882-15.049 13.53-19.103 22.89c-.743 1.703.045 3.709 1.759 4.441a3.37 3.37 0 0 0 4.449-1.751zM41.18 76.354c2.685 4.646 7.733 7.782 13.494 7.782 5.372 0 10.133-2.738 12.921-6.869a3.98 3.98 0 0 0-1.07-5.525A3.98 3.98 0 0 0 61 72.813a7.61 7.61 0 0 1-6.326 3.363c-2.827.002-5.277-1.528-6.606-3.812-1.102-1.901-3.537-2.55-5.439-1.449a3.98 3.98 0 0 0-1.449 5.439v-.002zm104.491-3.989c-1.329 2.284-3.779 3.814-6.606 3.812a7.61 7.61 0 0 1-6.326-3.363 3.98 3.98 0 0 0-5.525-1.071 3.98 3.98 0 0 0-1.07 5.525 15.59 15.59 0 0 0 12.921 6.869c5.761 0 10.808-3.136 13.494-7.782a3.98 3.98 0 0 0-6.887-3.989h-.002z"></path></svg>',
        'show_help_link' => false
    ),
    'plugin_broke_site' => array(
        'title' => ''.__("The plugin broke my site",'custom-registration-form-builder-with-submission-manager'),
        'input_placeholder' => '',
        'feedback_icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 194 194" fill-rule="nonzero" stroke-linejoin="round" stroke-miterlimit="2" fill="#272525" xmlns:v="https://vecta.io/nano"><path d="M193.75 96.874h-6.765c-.002 24.893-10.081 47.399-26.393 63.717-16.317 16.312-38.822 26.391-63.716 26.393s-47.401-10.081-63.719-26.393c-16.312-16.318-26.39-38.824-26.391-63.717s10.08-47.399 26.391-63.717C49.475 16.845 71.981 6.767 96.876 6.765s47.399 10.08 63.716 26.391c16.312 16.318 26.391 38.824 26.393 63.717h6.765C193.748 43.37 150.38.001 96.876 0S.001 43.37 0 96.874s43.37 96.873 96.876 96.876c53.504-.003 96.873-43.372 96.874-96.876zM46.481 81.027a8.26 8.26 0 0 1 8.259-8.259 8.26 8.26 0 0 1 8.259 8.259 8.26 8.26 0 0 1-8.259 8.257 8.26 8.26 0 0 1-8.259-8.257zm84.273 0a8.26 8.26 0 0 1 8.257-8.259 8.26 8.26 0 0 1 8.259 8.259 8.26 8.26 0 0 1-8.259 8.257 8.26 8.26 0 0 1-8.257-8.257zm-76.059 61.364a46.14 46.14 0 0 1 16.659-19.959 45.7 45.7 0 0 1 25.522-7.733c9.457-.001 18.212 2.846 25.517 7.73s13.128 11.812 16.657 19.951c.743 1.714 2.736 2.501 4.451 1.757a3.38 3.38 0 0 0 1.757-4.449 52.9 52.9 0 0 0-19.104-22.881c-8.372-5.603-18.459-8.875-29.279-8.873s-20.914 3.271-29.285 8.876-15.049 13.54-19.104 22.889c-.743 1.714.045 3.706 1.759 4.449s3.706-.045 4.449-1.757zM46 55.595l21.629 13.099c1.598.968 3.677.457 4.645-1.14s.457-3.679-1.141-4.647l-21.63-13.098A3.382 3.382 0 0 0 46 55.595zm98.246-5.786l-21.63 13.099c-1.597.968-2.108 3.048-1.141 4.647s3.048 2.108 4.647 1.14l21.629-13.099a3.382 3.382 0 1 0-3.504-5.786z"></path></svg>',
        'show_help_link' => __("Get free help to fix the site",'custom-registration-form-builder-with-submission-manager')
    ),
    'temporary_deactivation' => array(
            'title' => ''.__("It's a temporary deactivation",'custom-registration-form-builder-with-submission-manager'),
            'input_placeholder' => '',
            'feedback_icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 194 194" fill-rule="nonzero" stroke-linejoin="round" stroke-miterlimit="2" fill="#272525" xmlns:v="https://vecta.io/nano"><path d="M193.75 96.876h-6.765c-.002 24.893-10.081 47.399-26.393 63.717-16.317 16.312-38.822 26.39-63.717 26.391s-47.399-10.08-63.717-26.391c-16.312-16.318-26.39-38.824-26.391-63.717s10.08-47.401 26.391-63.717C49.475 16.846 71.981 6.767 96.875 6.765s47.401 10.081 63.717 26.393 26.391 38.822 26.393 63.717h6.765C193.749 43.372 150.38.003 96.875 0 43.371.003.002 43.372 0 96.876s43.37 96.873 96.874 96.874 96.874-43.37 96.876-96.874zM46.481 75.115a8.26 8.26 0 0 1 8.259-8.259 8.26 8.26 0 0 1 8.259 8.259 8.26 8.26 0 0 1-8.259 8.259 8.26 8.26 0 0 1-8.259-8.259zm84.273 0a8.26 8.26 0 0 1 8.257-8.259 8.26 8.26 0 0 1 8.259 8.259 8.26 8.26 0 0 1-8.259 8.259 8.26 8.26 0 0 1-8.257-8.259zm13.157 36.403c-3.114 10.018-9.351 18.674-17.569 24.822s-18.403 9.782-29.467 9.784-21.242-3.636-29.465-9.784-14.456-14.804-17.569-24.822c-.556-1.784-2.451-2.781-4.234-2.225a3.38 3.38 0 0 0-2.225 4.234c3.548 11.409 10.635 21.243 19.978 28.232a55.78 55.78 0 0 0 33.517 11.13 55.78 55.78 0 0 0 33.518-11.13c9.339-6.988 16.43-16.823 19.979-28.232.554-1.784-.443-3.68-2.227-4.234s-3.679.441-4.234 2.225z"></path><path d="M49.141 107.177l-9.585 4.588c-1.686.805-2.397 2.826-1.592 4.511s2.827 2.397 4.513 1.59l9.585-4.586c1.686-.807 2.397-2.827 1.592-4.511s-2.827-2.399-4.513-1.592zm92.548 6.103l9.585 4.586a3.38 3.38 0 0 0 4.511-1.59c.807-1.686.094-3.706-1.59-4.511l-9.586-4.588c-1.684-.807-3.704-.094-4.511 1.592a3.38 3.38 0 0 0 1.592 4.511z"></path></svg>',
            'show_help_link' => __("Get free help to fix the issue",'custom-registration-form-builder-with-submission-manager')
    ),
    'plugin_has_design_issue' => array(
        'title' => ''.__("It has design or layout issues.",'custom-registration-form-builder-with-submission-manager'),
        'input_placeholder' => '',
        'feedback_icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 194 194" fill-rule="nonzero" stroke-linejoin="round" stroke-miterlimit="2" fill="#272525" xmlns:v="https://vecta.io/nano"><path d="M193.748 96.876h-6.765c-.002 24.893-10.081 47.399-26.393 63.716-16.318 16.312-38.822 26.391-63.716 26.393s-47.401-10.081-63.719-26.393C16.843 144.275 6.765 121.77 6.762 96.876c.003-24.895 10.081-47.401 26.393-63.717C49.473 16.847 71.979 6.767 96.874 6.766s47.398 10.081 63.716 26.393c16.312 16.317 26.391 38.822 26.393 63.717h6.765C193.746 43.372 150.376.002 96.874 0S0 43.372-.002 96.876s43.37 96.873 96.876 96.874 96.873-43.372 96.874-96.874zM47.791 94.455l17.921-9.709a4.23 4.23 0 0 0 2.214-3.719 4.24 4.24 0 0 0-2.214-3.719L47.791 67.6a4.23 4.23 0 0 0-5.732 1.703 4.23 4.23 0 0 0 1.703 5.732l11.059 5.992-11.059 5.992a4.23 4.23 0 0 0-1.703 5.732 4.23 4.23 0 0 0 5.732 1.703zm102.192-7.436l-11.059-5.992 11.059-5.992a4.23 4.23 0 0 0 1.705-5.732 4.23 4.23 0 0 0-5.732-1.703l-17.923 9.709c-1.364.74-2.214 2.168-2.214 3.719a4.23 4.23 0 0 0 2.214 3.719l17.923 9.709a4.23 4.23 0 0 0 5.732-1.703 4.23 4.23 0 0 0-1.703-5.732h-.002zm.225 39.816a7.9 7.9 0 0 1-2.958 4.136c-1.402 1.009-3.161 1.609-5.137 1.611a8.41 8.41 0 0 1-5.161-1.756 8.48 8.48 0 0 1-2.993-4.435l-3.259.904 1.113 3.195.021-.008a3.37 3.37 0 0 0 1.939-1.734c.39-.815.439-1.746.135-2.598-1.054-2.951-2.983-5.473-5.476-7.262a15.2 15.2 0 0 0-8.867-2.848c-3.421-.002-6.609 1.14-9.15 3.056s-4.467 4.608-5.419 7.738c-.522 1.711-1.558 3.122-2.959 4.136s-3.161 1.609-5.137 1.611a8.41 8.41 0 0 1-5.161-1.756c-1.431-1.105-2.499-2.657-2.991-4.435l-.086-.266a15.57 15.57 0 0 0-5.457-7.2c-2.474-1.797-5.536-2.884-8.832-2.884a15.17 15.17 0 0 0-9.15 3.056c-2.539 1.916-4.467 4.608-5.42 7.738a7.9 7.9 0 0 1-2.958 4.136c-1.402 1.009-3.161 1.609-5.137 1.611a8.41 8.41 0 0 1-5.161-1.756c-1.436-1.103-2.499-2.657-2.993-4.435a3.382 3.382 0 1 0-6.517 1.808 15.22 15.22 0 0 0 5.379 7.983 15.17 15.17 0 0 0 9.292 3.165c3.365.002 6.535-1.041 9.094-2.891a14.67 14.67 0 0 0 5.474-7.654 8.48 8.48 0 0 1 3.015-4.301 8.4 8.4 0 0 1 5.081-1.695c1.805.002 3.464.586 4.858 1.595a8.81 8.81 0 0 1 3.082 4.064l3.174-1.17-3.26.904c.895 3.222 2.813 6.003 5.38 7.983a15.17 15.17 0 0 0 9.292 3.165c3.365.002 6.535-1.041 9.094-2.891 2.561-1.843 4.521-4.507 5.476-7.654a8.48 8.48 0 0 1 3.013-4.301c1.418-1.068 3.161-1.694 5.081-1.695a8.4 8.4 0 0 1 4.922 1.579 8.5 8.5 0 0 1 3.048 4.039l3.187-1.137-1.113-3.195-.022.008a3.38 3.38 0 0 0-2.147 4.099c.895 3.222 2.813 6.003 5.38 7.983a15.17 15.17 0 0 0 9.292 3.165c3.365.002 6.535-1.041 9.094-2.891a14.67 14.67 0 0 0 5.474-7.654c.543-1.788-.466-3.677-2.252-4.22s-3.677.465-4.22 2.252h-.002z"></path></svg>',
        'show_help_link' => false
    ),
    'plugin_missing_documentation' => array(
        'title' => ''.__("Missing documentation.",'custom-registration-form-builder-with-submission-manager'),
        'input_placeholder' => __("Please share the reason",'custom-registration-form-builder-with-submission-manager'),
        'feedback_icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 194 194" fill-rule="nonzero" stroke-linejoin="round" stroke-miterlimit="2" fill="#272525" xmlns:v="https://vecta.io/nano"><path d="M46.483 75.116a8.26 8.26 0 0 1 8.259-8.257 8.26 8.26 0 0 1 8.257 8.257 8.26 8.26 0 0 1-8.257 8.259 8.26 8.26 0 0 1-8.259-8.259zm84.276 0c0-4.561 3.693-8.257 8.262-8.257 4.553 0 8.246 3.696 8.246 8.257s-3.693 8.259-8.246 8.259a8.26 8.26 0 0 1-8.262-8.259zm62.992 21.761h-6.766c0 24.894-10.077 47.402-26.394 63.719s-38.811 26.392-63.708 26.394-47.406-10.082-63.724-26.394S6.768 121.772 6.765 96.877c.003-24.894 10.082-47.401 26.394-63.719C49.477 16.846 71.984 6.767 96.883 6.766s47.391 10.08 63.708 26.392c16.317 16.319 26.394 38.825 26.394 63.719h6.766C193.751 43.372 150.387.002 96.883 0 43.372.002.002 43.372.001 96.877s43.372 96.876 96.882 96.879c53.504-.003 96.868-43.373 96.868-96.879zM71.732 125.732h6.764c0-8.171 2.292-15.515 5.779-20.614 1.751-2.553 3.773-4.54 5.906-5.858 2.149-1.32 4.362-1.993 6.702-1.996 2.324.003 4.553.677 6.686 1.996 3.2 1.972 6.161 5.475 8.278 10.082 2.133 4.599 3.407 10.263 3.407 16.39.016 8.171-2.276 15.515-5.779 20.614-1.751 2.553-3.773 4.54-5.906 5.858s-4.362 1.993-6.686 1.998c-2.34-.005-4.553-.677-6.702-1.998-3.2-1.971-6.161-5.475-8.278-10.082-2.117-4.599-3.407-10.263-3.407-16.39h-6.764c.008 9.421 2.584 18.003 6.971 24.431 2.181 3.211 4.855 5.89 7.928 7.794 3.056 1.904 6.575 3.013 10.252 3.01 3.677.003 7.18-1.106 10.252-3.01 4.601-2.864 8.294-7.439 10.857-12.998 2.579-5.569 4.028-12.161 4.028-19.227 0-9.419-2.579-18.003-6.957-24.431-2.197-3.211-4.855-5.888-7.928-7.794s-6.575-3.012-10.252-3.009c-3.677-.003-7.195 1.106-10.252 3.009-4.617 2.864-8.294 7.439-10.87 13-2.568 5.567-4.028 12.161-4.029 19.226z"></path></svg>',
        'show_help_link' => false
	),
    'other_reasons' => array(
        'title' => ''.__("Other reasons.",'custom-registration-form-builder-with-submission-manager'),
        'input_placeholder' => __("Please share the reason",'custom-registration-form-builder-with-submission-manager'),
        'feedback_icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 194 194" fill-rule="nonzero" stroke-linejoin="round" stroke-miterlimit="2" fill="#272525" xmlns:v="https://vecta.io/nano"><path d="M46.482 75.118a8.26 8.26 0 0 1 8.259-8.257 8.26 8.26 0 0 1 8.257 8.257c0 4.561-3.696 8.254-8.257 8.254s-8.259-3.693-8.259-8.254zm84.275 0c0-4.561 3.693-8.257 8.262-8.257a8.27 8.27 0 0 1 8.262 8.257c0 4.561-3.709 8.254-8.262 8.254-4.569 0-8.262-3.693-8.262-8.254zm62.993 21.77h-6.766c0 24.882-10.077 47.392-26.394 63.709s-38.811 26.394-63.709 26.394-47.404-10.077-63.722-26.394S6.767 121.77 6.765 96.888c.002-24.902 10.082-47.409 26.394-63.728C49.477 16.847 71.983 6.769 96.881 6.767s47.392 10.08 63.709 26.393c16.317 16.319 26.394 38.825 26.394 63.728h6.766c0-53.514-43.364-96.885-96.869-96.886S.001 43.374 0 96.888c.002 53.505 43.372 96.869 96.881 96.869s96.869-43.364 96.869-96.869zM48.612 125.781h96.536a3.38 3.38 0 0 0 3.375-3.375c0-1.863-1.512-3.391-3.375-3.391H48.612c-1.869 0-3.383 1.528-3.383 3.391a3.38 3.38 0 0 0 3.383 3.375z"></path></svg>',
        'show_help_link' => false
	),
);
?>
<script type="text/javascript">
    jQuery(document).ready(function(){
        
        var rmDeactivateLocation;
        // Shows feedback dialog     
        jQuery('#the-list').find( '[data-slug="custom-registration-form-builder-with-submission-manager"] span.deactivate a' ).click(function(event){
            jQuery("#rm-deactivate-feedback-dialog-wrapper, .rm-modal-overlay").show();
            rmDeactivateLocation = jQuery(this).attr('href');
            event.preventDefault();
        });

        // skip and deactivation
        jQuery(document).on('click', '#rm_save_plugin_feedback_direct_deactivation', function() {
            location.href = rmDeactivateLocation;
        });
        
        jQuery("#rm-feedback-btn").click(function(e) {
            e.preventDefault();
            var selectedVal = jQuery("input[name='rm_feedback_key']:checked").val();
            var message = jQuery('#'+selectedVal+' textarea#rm-plugin-feedback').val();
            var addOption = jQuery('#'+selectedVal+' #rm-inform-email').prop("checked") == true ? 1 : 0;
            var email = addOption == 1 ? jQuery('#'+selectedVal+' input[name=rm_user_support_email]').val() : '';
            if(selectedVal === undefined) {
                //location.href= rmDeactivateLocation;
                return;
            }
            
            var data = {
                'action': 'rm_post_feedback',
                'rm_sec_nonce': '<?php echo wp_create_nonce('rm_ajax_secure'); ?>',
                'feedback': selectedVal,
                'add_option': addOption,
                'email': email,
                'msg': message
            };
            jQuery(".rm-ajax-loader").show();
            jQuery.post(ajaxurl, data, function (response) {
                jQuery(".rm-ajax-loader").hide();
                location.href= rmDeactivateLocation;  
            });
        });
        
        jQuery("input[name='rm_feedback_key']").change(function(){
                var selectedVal= jQuery(this).val();
                var reasonElement= jQuery("#reason_" + selectedVal);
                jQuery(".rm-deactivate-feedback-dialog-input-wrapper .rminput").hide();
                if(reasonElement!==undefined)
                {
                    reasonElement.show();  
                }
                var helplinkElement= jQuery("#help_link_" + selectedVal);
                if(typeof helplinkElement !== "undefined")
                {
                    helplinkElement.show();  
                } else {
                    helplinkElement.hide();  
                }
        });
        
        jQuery("#rm-feedback-cancel-btn").click(function(){
            jQuery("#rm-deactivate-feedback-dialog-wrapper").hide();
        });
        
        jQuery(".rm-modal-close, .rm-modal-overlay").click(function(){
            jQuery(".rm-modal-view").hide();
        });
});
</script>    
<div class="rmagic rm-hide-version-number">
    <div id="rm-deactivate-feedback-dialog-wrapper"  class="rm-modal-view" style="display:none; float:right">
        <div class="rm-modal-overlay"></div>
        <div  class="rm-modal-wrap rm-deactivate-feedback" >

            <div class="rm-modal-titlebar rm-new-form-popup-header rm-mt-3 rm-ps-3">
                <div class="rm-modal-title  rm-mt-2 rm-fw-normal rm-mb-1 rm-pb-2 rm-pt-2 rm-pl-3">
                    <div class="rm-fs-4 rm-fw-normal rm-text-dark rm-pb-2"> <?php _e("Uninstalling RegistrationMagic?",'custom-registration-form-builder-with-submission-manager') ?></div>
                    <div class="rm-fs-6 rm-text-dark rm-text-small rm-pl-3"><?php _e("Please let us know what went wrong.",'custom-registration-form-builder-with-submission-manager') ?></div>
                </div>
                <span class="rm-modal-close material-icons rm-text-dark rm-fs-5">close</span>
            </div>
            <div class="rm-modal-container">
                <form id="rm-deactivate-feedback-dialog-form" method="post">
                    <input type="hidden" name="action" value="rm_deactivate_feedback" />
                    <div class="rm-px-3 rm-settings-checkout-field-manager">
                        <div class="rm-deactivate-feedback-wrap rm-px-3 rm-pb-2">
                            
                            <div class="rm-plugin-deactivation-message rm-mr-3 rm-text-danger" style="display:none"><?php _e('Please select one option','custom-registration-form-builder-with-submission-manager'); ?></div>
                            
                            <div id="rm-deactivate-feedback-dialog-form-body" class="rm-box-row">
                            <?php foreach ($deactivate_reasons as $reason_key => $reason) : ?>
                                <div class="rm-deactivate-feedback-dialog-input-wrapper rm-mb-2 rm-box-col-6" >  
                                    <div class="rm-deactive-feedback-box">
                                        <input id="rm-deactivate-feedback-<?php echo esc_attr($reason_key); ?>" class="rm-deactivate-feedback-dialog-input rm-d-none" type="radio" name="rm_feedback_key" value="<?php echo esc_attr($reason_key); ?>" required/>
                                        <label for="rm-deactivate-feedback-<?php echo esc_attr($reason_key); ?>" class="rm-deactivate-feedback-dialog-label rm-lh-0 rm-border rm-border-dark rm-border-dark rm-rounded rm-p-3 rm-box-w-100 rm-di-flex rm-align-items-center">
                                            <span class="rm-feedback-emoji rm-mr-2 "><?php echo wp_kses($reason['feedback_icon'],RM_Utilities::expanded_allowed_tags()); ?></span>
                                            <?php echo wp_kses_post((string)$reason['title']); ?>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                    
                    <div class="rm-box-wrap rm-feedback-form-input-bg">       
            <!-- feature_not_available Feedback Form 1 -->
            <div class="rm-box-row rm-feedback-form-feature-box" id="feature_not_available" data-condition="feature_not_available" style="display: none;">
                <div class="rm-box-col-12">
                    <div class="rm-feedback-form rm-feedback-form-input-bg">
                        <div class="rm-p-4">
                            <label class="rm-inline-block rm-pb-2"><?php _e('Please tell us about the missing feature (optional)','custom-registration-form-builder-with-submission-manager'); ?></label>
                            <textarea id="rm-plugin-feedback" name="rm-plugin-feedback" class="rm-plugin-feedback-textarea rm-box-w-100" rows="2" cols="50"></textarea>
                            <div class="rm-pt-1 rm-plugin-feedback-email-check rm-d-flex rm-align-items-center rm-mt-1">
                                <input type="checkbox" class="rm-my-0 rm-mr-2" id="rm-inform-email" name="rm-inform-email" value="1">
                                <label for="rm-inform-email" class="rm-text-small"><?php _e('Create a support ticket to request addition of this feature.','custom-registration-form-builder-with-submission-manager'); ?></label>
                            </div>
                            <div class="rm-feedback-user-email rm-mt-2" style="display: none">
                                <input type="email" name="rm_user_support_email" value="<?php echo sanitize_email( $rm_admin_email ); ?>" placeholder="Your Email">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- feature_not_available Feedback Form 1 End -->
            
            <!-- feature_not_working Feedback Form 2 -->
            
            <div class="rm-box-row rm-feedback-form-feature-box" id="feature_not_working" data-condition="feature_not_working" style="display: none;">
                <div class="rm-box-col-12">
                    <div class="rm-feedback-form rm-feedback-form-input-bg">
                        <div class="rm-p-4">
                            <label class="rm-inline-block rm-pb-2"><?php _e('Please tell us about the broken feature (optional)','custom-registration-form-builder-with-submission-manager'); ?></label>
                            <textarea id="rm-plugin-feedback" name="rm-plugin-feedback" class="rm-plugin-feedback-textarea rm-box-w-100" rows="2" cols="50"></textarea>
                            <div class="rm-pt-1 rm-plugin-feedback-email-check rm-d-flex rm-align-items-center rm-mt-1">
                                <input type="checkbox" class="rm-my-0 rm-mr-2" id="rm-inform-email" name="rm-inform-email" value="1">
                            <label for="rm-inform-email" class="rm-text-small"><?php _e('Also create support ticket.','custom-registration-form-builder-with-submission-manager'); ?></label>
                            </div>
                            <div class="rm-feedback-user-email rm-mt-2" style="display: none">
                                <input type="email" name="rm_user_support_email" value="<?php echo sanitize_email( $rm_admin_email ); ?>" placeholder="Your Email">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- feature_not_working Feedback Form 2 End --->
            
            <!-- plugin_difficult_to_use Feedback Form 3 -->
            
            <div class="rm-box-row rm-feedback-form-feature-box" id="plugin_difficult_to_use" data-condition="plugin_difficult_to_use" style="display: none;">
                <div class="rm-box-col-12">
                    <div class="rm-feedback-form rm-feedback-form-input-bg">
                        <div class="rm-p-4">
                            <label class="rm-inline-block rm-pb-2"><?php _e('Please tell us which part of the plugin was confusing (optional)','custom-registration-form-builder-with-submission-manager'); ?></label>
                            <textarea id="rm-plugin-feedback" name="rm-plugin-feedback" class="rm-plugin-feedback-textarea rm-box-w-100" rows="2" cols="50"></textarea>
                        
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- plugin_difficult_to_use Feedback Form 3 End --->
            
            <!-- plugin_broke_site Feedback Form 4 -->
            
            <div class="rm-box-row rm-feedback-form-feature-box" id="plugin_broke_site" data-condition="plugin_broke_site" style="display: none;">
                <div class="rm-box-col-12">
                    <div class="rm-feedback-form rm-feedback-form-input-bg">
                        <div class="rm-p-4">
                            <label class="rm-inline-block rm-pb-2"><?php _e('Please paste any errors or warnings you see (optional)','custom-registration-form-builder-with-submission-manager'); ?></label>
                            <textarea id="rm-plugin-feedback" name="rm-plugin-feedback" class="rm-plugin-feedback-textarea rm-box-w-100" rows="2" cols="50"></textarea>
                            <div class="rm-pt-1 rm-plugin-feedback-email-check rm-d-flex rm-align-items-center rm-mt-1">
                                <input type="checkbox" class="rm-my-0 rm-mr-2" id="rm-inform-email" name="rm-inform-email" value="1">
                            <label for="rm-inform-email" class="rm-text-small"><?php _e('Also create support ticket','custom-registration-form-builder-with-submission-manager'); ?></label>
                            </div>
                            <div class="rm-feedback-user-email rm-mt-2" style="display: none">
                                <input type="email" name="rm_user_support_email" value="<?php echo sanitize_email( $rm_admin_email ); ?>" placeholder="Your Email">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- plugin_broke_site Feedback Form 4 End --->
            
            <!-- temporary_deactivation Feedback Form 5 -->
            
            <div class="rm-box-row rm-feedback-form-feature-box" id="temporary_deactivation" data-condition="temporary_deactivation" style="display: none;">
    
            </div>
            
            <!-- temporary_deactivationg Feedback Form 5 End --->
            
            <!-- plugin_has_design_issue Feedback Form 6 -->
            
            <div class="rm-box-row rm-feedback-form-feature-box" id="plugin_has_design_issue" data-condition="plugin_has_design_issue" style="display: none;">
                <div class="rm-box-col-12">
                    <div class="rm-feedback-form rm-feedback-form-input-bg">
                        <div class="rm-p-4">
                            <label class="rm-inline-block rm-pb-2"><?php _e('Please tell us which page had design issues (optional)','custom-registration-form-builder-with-submission-manager'); ?></label>
                            <textarea id="rm-plugin-feedback" name="rm-plugin-feedback" class="rm-plugin-feedback-textarea rm-box-w-100" rows="2" cols="50"></textarea>
                            <div class="rm-pt-1 rm-plugin-feedback-email-check rm-d-flex rm-align-items-center rm-mt-1">
                                <input type="checkbox" class="rm-my-0 rm-mr-2" id="rm-inform-email" name="rm-inform-email" value="1">
                                <label for="rm-inform-email" class="rm-text-small"><?php _e('Also create support ticket','custom-registration-form-builder-with-submission-manager'); ?></label>
                            </div>
                            <div class="rm-feedback-user-email rm-mt-2" style="display: none">
                                <input type="email" name="rm_user_support_email" value="<?php echo sanitize_email( $rm_admin_email ); ?>" placeholder="Your Email">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- plugin_has_design_issue Feedback Form 6 End --->
            

            <!-- plugin_missing_documentation Feedback Form 7 -->

            <div class="rm-box-row rm-feedback-form-feature-box" id="plugin_missing_documentation" data-condition="plugin_missing_documentation" style="display: none;">
                <div class="rm-box-col-12">
                    <div class="rm-feedback-form rm-feedback-form-input-bg">
                        <div class="rm-p-4">
                            <label class="rm-inline-block rm-pb-2"><?php _e('Please tell us which feature lacked documentation (optional)','custom-registration-form-builder-with-submission-manager'); ?></label>
                            <textarea id="rm-plugin-feedback" name="rm-plugin-feedback" class="rm-plugin-feedback-textarea rm-box-w-100" rows="2" cols="50"></textarea>
                            <div class="rm-pt-1 rm-plugin-feedback-email-check rm-d-flex rm-align-items-center rm-mt-1">
                                    <input type="checkbox" class="rm-my-0 rm-mr-2" id="rm-inform-email" name="rm-inform-email" value="1" >
                                    <label for="rm-inform-email" class="rm-text-small"><?php _e('Also create support ticket','custom-registration-form-builder-with-submission-manager'); ?></label>
                            </div>
                            <div class="rm-feedback-user-email rm-mt-2" style="display: none">
                                <input type="email" name="rm_user_support_email" value="<?php echo sanitize_email( $rm_admin_email ); ?>" placeholder="Your Email">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- plugin_missing_documentation Feedback Form 7 End --->
            
            
            <!-- other_reasons Feedback Form 8 -->

            <div class="rm-box-row rm-feedback-form-feature-box" id="other_reasons" data-condition="other_reasons" style="display: none;">
                <div class="rm-box-col-12">
                    <div class="rm-feedback-form rm-feedback-form-input-bg">
                        <div class="rm-p-4">
                            <label class="rm-inline-block rm-pb-2"><?php _e('You can add more details about the issue here (optional)','custom-registration-form-builder-with-submission-manager'); ?></label>
                            <textarea id="rm-plugin-feedback" name="rm-plugin-feedback" class="rm-plugin-feedback-textarea rm-box-w-100" rows="2" cols="50"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- other_reasons Feedback Form 8 End --->
                    
            </div>       
                    <div class="rm-ajax-loader" style="display:none">
                        <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
                        <span class="sr-only"><?php _e("Loading...",'custom-registration-form-builder-with-submission-manager') ?></span>
                    </div>
                   

                    <div class="rm-box-w-100 rm-d-flex rm-justify-content-between rm-p-2 rm-py-3 rm-border-top rm-align-items-center">
                    <a href="javascript:void(0);" class="rm-mr-3 button rm-feedback-skip-button" id="rm_save_plugin_feedback_direct_deactivation" title="Skip &amp; Deactivate">Skip &amp; Deactivate</a>
                    <!--<input type="button" id="rm-feedback-cancel-btn" class="rm-feedback-cancel-btn" value="← &nbsp; Cancel"/>-->
                    <!--<input type="submit" class="button button-primary button-large" id="rm-feedback-btn" value="<?php _e("Submit & Deactivate",'custom-registration-form-builder-with-submission-manager') ?>"/>-->
                    <button class="button button-primary button-large" type="submit" id="rm-feedback-btn"><?php _e("Submit & Deactivate",'custom-registration-form-builder-with-submission-manager') ?></button>
                    
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<style>
/*-- Feedback Form---*/
.rm-deactivate-feedback-dialog-input {
    display: none;
}

.rm-feedback-emoji svg {
    width: 32px;
    fill: #272525;
}

.rm-deactive-feedback-box label{    
    transition: all .5s;
    padding: 0.6rem 1rem!important;
}

.rm-feedback-form-input-bg {
    background-color: #F0F6FC;
}

.rm-plugin-feedback-textarea {
    border:1px solid #A0C6EA;
}

.rm-feedback-form-feature-box input[type=checkbox] {
    background-color: #fff;
    border: 1px solid #d7dce0;
    box-shadow: none;
    width: 16px;
    height: 16px;
}

.rmagic .rm-feedback-form-feature-box input[type=checkbox]:checked{
    background-color: transparent;
    border-color: transparent !important;
}

.rm-feedback-form-feature-box input[type=checkbox]:checked::before{
    font-family: "Material Icons";
    content: "\e876" !important;
    color: #fff;
    top: 11px;
    margin-top: 4px;
    background-color: #2371b1;
    width: 16px;
    height: 16px;
    margin: 0px;
    border-radius: 4px;
    padding-top: 0px;
    box-sizing: border-box;
    font-size: 15px;
    border: 0px;
    font-weight: 800;
}

.rm-feedback-skip-button {
    background-color: #fff !important;
}

.rm-feedback-user-email input {
    border: 1px solid #A0C6EA;
    width: 100%;
    max-width: 296px;
    font-size: 12px;
}

#rm-deactivate-feedback-dialog-form .rm-border-dark {
    --rm-border-opacity: 1;
    border-color: rgba(var(--rm-border-dark-color),var(--rm-border-opacity))!important;
}

#rm-deactivate-feedback-dialog-form .rm-deactive-feedback-box input[type="radio"]:checked + label{
    background: #FAFCFF;
    border-color: #2271B1 !important;
}

 #rm-deactivate-feedback-dialog-form .rm-deactive-feedback-box input[type="radio"]:checked + label .rm-feedback-emoji svg{
    fill: #2271B1;
}


#rm-deactivate-feedback-dialog-form .rm-deactive-feedback-box label:hover{
    background: #FAFCFF;
    border-color: #2271B1 !important;
}

#rm-deactivate-feedback-dialog-form .rm-deactive-feedback-box label:hover .rm-feedback-emoji svg{
    fill: #2271B1;
}

#rm-deactivate-feedback-dialog-for .rm-plugin-feedback-email-check label{
    color: #7998B4;
}

#rm-deactivate-feedback-dialog-form .rm-feedback-form-feature-box textarea.rm-plugin-feedback-textarea{
    min-height: 42px;
}

#rm-deactivate-feedback-dialog-wrapper.rm-modal-view{
    height: 80%;
}

#rm-deactivate-feedback-dialog-form .rm-plugin-feedback-email-check label {
    color: #7998B4;
}

</style>
<script>
jQuery( function( $ ) {
    $( document ).on( 'change', 'input[name="rm_feedback_key"]', function() {
        var rm_selectedVal = $(this).val();
        //var rm_reasonElement = $( '#rm_reason_' + rm_selectedVal );
        jQuery(".rm-feedback-form-feature-box").hide();
        //console.log(rm_selectedVal);
        
        $('.rm-feedback-form-feature-box').each(function () {
            var condition = $(this).data('condition');
            
            //console.log(`${rm_selectedVal} and ${condition}`);

            // Check if the condition matches rm_selectedVal
            if (condition == rm_selectedVal) {
                
                console.log(rm_selectedVal);
            
                // Show the box if the condition is true
                $(this).show();
            } else {
                // Hide the box if the condition is not true
                $(this).hide();
            }
        });
    });

    var $informEmailCheckboxes = $(".rm-plugin-feedback-email-check input");
    var $feedbackUserEmailDivs = $(".rm-feedback-user-email");

    $informEmailCheckboxes.change(function () {
        // Find the index of the checkbox that was changed
        var index = $informEmailCheckboxes.index(this);
        //console.log(this.checked);

        if (this.checked) {
            // Checkbox is checked, so show the corresponding feedbackUserEmailDiv
            $feedbackUserEmailDivs.eq(index).show();
        } else {
            // Checkbox is unchecked, so hide the corresponding feedbackUserEmailDiv
            $feedbackUserEmailDivs.eq(index).hide();
        }
    });
    
    
    
    
    
    $(document).ready(function() {
    $('#rm-feedback-btn').submit(function(e) {
        // Prevent the form from submitting by default
        
        console.log('ssss');
        e.preventDefault();

        // Check if any radio input is checked
        if (!$('input[name="rm_feedback_key"]:checked').length > 0) {
            // If no radio button is checked, show the error message
            $('.rm-plugin-deactivation-message').show();
        } else {
            // If a radio button is checked, hide the error message and submit the form
            $('.rm-plugin-deactivation-message').hide();
            // Here, you might want to proceed with form submission
            // You can do so by either using this.submit() or AJAX to handle form submission
            // For demonstration purposes, let's log a success message
            console.log('Form submitted successfully!');
        }
    });
});



    $( document ).on( 'click', '#rm-feedback-btn', function(e) {
        e.preventDefault();
        let selectedVal = $( 'input[name="rm_feedback_key"]:checked' ).val();

            // Check if any radio input is checked
        if (!$('input[name="rm_feedback_key"]:checked').length > 0) {
            // If no radio button is checked, show the error message
            $('.rm-plugin-deactivation-message').show();
        } else {
            // If a radio button is checked, hide the error message and submit the form
            $('.rm-plugin-deactivation-message').hide();
            // Here, you might want to proceed with form submission
            // You can do so by either using this.submit() or AJAX to handle form submission
            // For demonstration purposes, let's log a success message
            //console.log('Form submitted successfully!');
        }
        
    });

    
});
</script>
