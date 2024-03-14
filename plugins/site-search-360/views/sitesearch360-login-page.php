<?php 
	$ss360_server_name = $_SERVER['SERVER_NAME'];
	$ss360_is_localhost = $ss360_server_name !== 'localhost' || $ss360_server_name !== '127.0.0.1';
	$ss360_domain_placeholder = !$ss360_is_localhost ? $ss360_server_name : 'domain.com';
	$ss360_prefilled_domain = !$ss360_is_localhost ? $ss360_server_name : '';
?>
<section id="ss360" class="wrap flex flex--center">
      <!-- Sign Up -->
      <div class="wrapper wrapper--small wrapper--fancy" id="ss360-signup">
        <?php if(!empty($_POST) && $_POST['action'] == 'ss360_register' && $ss360_result['status']!='success'){ ?>
        <section role="alert" class="block block--first flex flex--center flex--column alert alert--centered ss360-signup-warning">
          <p>
            <?php esc_html_e(sprintf(__('Looks like your account for the email address %1$s exists already for the site %2$s.', 'site-search-360'), $ss360_result['email'], $ss360_result['siteId'])) ?> 
            <?php esc_html_e('If you want to create a new account for another site, please use a different email address.', 'site-search-360') ?>
          </p>
        </section>
        <section role="alert" class="block flex flex--center flex--column ss360-signup-warning">
          <p>
          <?php esc_html_e('Did you want to sign in?', 'site-search-360') ?>&nbsp;<a href="#login" class="login-toggle"><strong><?php esc_html_e('Click here to login.','site-search-360') ?></strong></a>
          </p>
        </section>
        <?php } ?>
        <div class="block <?php if(empty($_POST)||(isset($ss360_result['status']) && $ss360_result['status']=='success')||$_POST['action']!='ss360_register'){echo 'block--first';}?> flex flex--column flex--center" id="ss360-signup-form">
            <h1><a href="https://sitesearch360.com" target="_blank" class="logo__link"><img aria-label="Site Search 360" class="logo" src="<?php echo plugins_url('images/logo.svg',  dirname(__FILE__))?>"></a></h1>
            <section class=" flex flex--column flex--center">
              <h2 class="center--sm login-heading"><?php esc_html_e('Get started for free', 'site-search-360') ?></h2>
              <form name="ss360_settings" class="form form--narrow" method="post" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>">
                <?php wp_nonce_field(); ?>
                <input type="hidden" name="action" value="ss360_register">
                <div class="flex flex--column">
                  <label for="emailInput" class="label label--login"><?php esc_html_e('E-mail address', 'site-search-360') ?></label>
                  <input type="email" required id="emailInput" placeholder="mail@<?php echo $ss360_domain_placeholder; ?>" name="email" class="input opacityChange">
                </div>
                <div class="flex flex--column">
                  <label for="siteInput" class="label label--login"><?php esc_html_e('Domain for your new search', 'site-search-360') ?></label>
				  <span role="alert" class="alert alert--inline" id="localhost-warning" style="display: none"><?php esc_html_e('Even when testing on localhost, please provide your site\'s domain.', 'site-search-360') ?></span>
                  <input type="text" pattern="^(https?:\/\/)?[A-Za-z0-9-._]+\.[A-Za-z]{2,10}" required id="siteInput" value="<?php echo $ss360_prefilled_domain; ?>"
                    placeholder="<?php echo $ss360_domain_placeholder; ?>" name="domain" class="input" title="<?php esc_html_e('Enter a valid domain name.','site-search-360') ?>">
                </div>
                <button class="button button--stretch flex flex--center"><?php esc_html_e('Create Account', 'site-search-360') ?></button>
              </form>
              <div class="hint hint--login">
                <span class="hidden--sm"><?php esc_html_e('Already signed up?', 'site-search-360')?>&nbsp;</span><a href="#login" class="login-toggle"><?php esc_html_e('Log in','site-search-360') ?></a>
              </div>
            </section>
        </div>

        <section class="block flex flex--column flex--center">
            <h2 style="margin-bottom: 20px" class="center--sm login-heading"><?php esc_html_e('Key Features', 'site-search-360') ?></h2>
            <ul class="features">
              <li class="feature"><?php esc_html_e('Super fast', 'site-search-360') ?></li>
              <li class="feature"><?php esc_html_e('Fully customizable', 'site-search-360') ?></li>
              <li class="feature"><?php esc_html_e('Accessibility-conscious', 'site-search-360') ?></li>
            </ul>
            <div class="hint hint--login hidden--sm">
              <span><?php esc_html_e('Not sure yet?', 'site-search-360') ?>&nbsp;<a href="https://www.sitesearch360.com/our-customers/" target="_blank"><?php esc_html_e('Check some of our integrations.', 'site-search-360') ?></a></span>
            </div>
            <div class="hint hint--login hidden--lg">
              <a href="https://docs.sitesearch360.com/filters-example?s=curry" target="_blank"><?php esc_html_e('Live demo.','site-search-360') ?></a>
            </div>
        </section>
      </div>


      <!-- Login -->
      <div class="wrapper wrapper--small wrapper--fancy" style="display: none;" id="ss360-login">
        <?php if(!empty($_POST) && $_POST['action'] == 'ss360_login' && $ss360_result['status']!='success'){ ?>
        <section class="block block--first flex flex--column flex--center alert alert--centered" role="alert" id="ss3360-login-warning">
            <span><?php esc_html_e('Invalid email and/or password.','site-search-360'); ?></span>
        </section>
        <?php } ?>
        <div class="block <?php if(empty($_POST)||(isset($ss360_result['status']) && $ss360_result['status']=='success')||$_POST['action']!='ss360_login'){echo 'block--first';}?> flex flex--column flex--center" id="ss360-login-form">
          <h1><a href="https://sitesearch360.com" target="_blank" class="logo__link"><img aria-label="Site Search 360" class="logo" src="<?php echo plugins_url('images/logo.svg',  dirname(__FILE__))?>"></a></h1>
          <section class=" flex flex--column flex--center">
            <h2 class="center--sm login-heading"><?php esc_html_e('Log in to your account', 'site-search-360') ?></h2>
            <form name="ss360_settings" class="form form--narrow" method="post" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>">
              <?php wp_nonce_field(); ?>
              <input type="hidden" name="action" value="ss360_login">
              <div class="flex flex--column">
                <label for="lEmailInput" class="label label--login"><?php esc_html_e('E-mail address or Site ID', 'site-search-360') ?></label>
                <input type="text" required id="lEmailInput" value="" name="email" class="input">
              </div>
              <div class="flex flex--column">
                <label for="passwordInput" class="label label--login"><?php esc_html_e('Password', 'site-search-360') ?></label>
                <input type="password" required id="passwordInput" name="password" class="input">
              </div>
              <button class="button button--stretch flex flex--center"><?php esc_html_e('Login', 'site-search-360') ?></button>
            </form>
            <div class="hint hint--login hidden--sm">
              <?php esc_html_e('Don\'t have an account?', 'site-search-360')?>&nbsp;<a href="#signup" id="signup-toggle"><?php esc_html_e('Get one now.','site-search-360') ?></a>
            </div>
            <div class="hint hint--login hidden--lg">
              <a href="#signup" id="signup-toggle-m"><?php esc_html_e('Sign Up.','site-search-360') ?></a>
            </div>
            <div class="hint hint--login">
              <a href="https://control.sitesearch360.com/forgotPassword" target="_blank"><?php esc_html_e('Forgot your password?','site-search-360') ?></a>
            </div>
          </section>
        </div>
      </div>
    </div>

    <script type="text/javascript">
    var isLoggingIn = <?php echo isset($ss360_is_logging_in) && $ss360_is_logging_in ? 'true' : 'false'; ?>;
    if(isLoggingIn || window.location.hash==="#login" || <?php echo !empty($_POST) && $_POST['action'] == 'ss360_login' && $ss360_result['status']!='success' ? 'true' : 'false' ?>){
        jQuery("#ss360-signup").hide();
        jQuery("#ss360-login").show();
    }

    jQuery(".login-toggle").on("click", function(){
      jQuery("#ss360-signup").hide();
      jQuery("#ss360-login").show();
      jQuery(".ss360-signup-warning").remove();
      jQuery("#ss360-signup-form").addClass("block--first");
    });

    jQuery("#signup-toggle, #signup-toggle-m").on("click", function(){
      jQuery("#ss360-login").hide();
      jQuery("#ss360-signup").show();
      jQuery("#ss3360-login-warning").remove();
      jQuery("#ss360-login-form").addClass("block--first");
    });

    (function(){
      var re = new RegExp(/^(https?:\/\/)?[A-Za-z0-9-._]+\.[A-Za-z]{2,10}/);
      var $siteInput = jQuery("#siteInput");
      var $localhostWarning = jQuery("#localhost-warning");
      if($siteInput.val().match(re)===null && $siteInput.val().indexOf("localhost")!==-1){
        $localhostWarning.show();
      }
      var timeoutId = -1;
      $siteInput.keyup(function(e){
          clearTimeout(timeoutId);
          timeoutId = setTimeout(function(){
            if(e.target.value.match(re)===null && e.target.value.indexOf("localhost")!==-1){
              $localhostWarning.show();
            }else {
              $localhostWarning.hide();
            }
          }, 300);      
      });
    }());
  
    </script>

    <script src="<?php echo plugins_url('assets/sitesearch360_admin_scripts.js',  dirname(__FILE__))  ?>" async></script>