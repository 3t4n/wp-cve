<?php
  $iscustomervalid = Mo_lla_MoWpnsUtility::icr();
?>
<style>
  .mowpns-support-container{
    display:flex;
    margin : 10px auto; 
    align-items:bottom;
    justify-content:center;
    padding:20px;
    gap:10px;
  }

  .mowpns-pricing-box{
      min-width:300px;
      display:flex;
      flex-direction:column;
      background-color:#fff;
      margin-top:20px;
  }

  .mowpns-box-premium{
    min-width:330px;
    margin:0;
    box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.55);
  }

  .mowpns-box-heading{
    display:flex;
    justify-content:center;
    align-items:center;
    padding:10px;
    color:#fff;
    font-weight:bold;
    font-size:1rem;
  }

  .mowpns-free{
    background-color:#329901;
  }

  .mowpns-premium{
    background-color:#FFD707;
  }

  .mowpns-price-section{
    display:flex;
    flex-direction:column;
    padding:10px;
    align-items:center;
    font-size:1.3rem;
    font-weight:bold;
    margin-top:20px;
    border-bottom:1px solid #dfdfdf;
    min-height:70px;
  }

  .mowpns-premium-price{
    min-height:90px;
  }
  .button-upgrade{
    background-color:#0081E3 !important;
  }

  .mowpns-feature-list-div{
    padding-left:25px;
    font-weight:bold;
    margin-bottom:15px;
  }

  .mowpns-disabled{
    color:#cfcfcf;
  }

  li{
    list-style-type:  none;
    /* list-style-position: inside; */
  }

  .mowpns-green::before {
    content: "•"; 
    color: #3DC000;
    display: inline-block; 
    width: 0.6em;
    margin-left: -0.2em;
    font-size:25px;
  } 

  .mowpns-red::before {
    content: "•"; 
    color: red;
    display: inline-block; 
    width: 0.6em;
    margin-left: -0.2em;
    font-size:25px;
  }

  .mowpns-disabled::before{
    content: "•"; 
    color: #dfdfdf;
    display: inline-block; 
    width: 0.6em;
    margin-left: -0.2em;
    font-size:25px;
  }

</style>
<div class="mowpns-support-container">
    <div class="mowpns-pricing-box">

      <div class="mowpns-box-heading mowpns-free">
          Current plan
      </div>

      <div class="mowpns-price-section">
          <div>$0</div>
          <br>
          <div>Free</div>
      </div>

      <div class="mowpns-feature-list-div">
         <ul>
						<li class="mowpns-green"> Brute force protection </li>
						<li class="mowpns-green"> Rename login URL </li>
						<li class="mowpns-green"> Login Security - RECAPTCHA v2/v3 </li>
						<li class="mowpns-green"> Login and spam protection </li>
						<li class="mowpns-green"> IP blocking & whitelisting</li>
						<li class="mowpns-red"> Basic rate limiting</li>
						<li class="mowpns-red"> Basic firewall</li>
						<li class="mowpns-disabled"> Real-Time IP Blocklist</li>
						<li class="mowpns-disabled"> Country Blocking</li>
						<li class="mowpns-disabled"> Crawlers and Bot detection</li>
						<li class="mowpns-disabled"> Plugin/Theme Vulnerability Monitoring</li>
						<li class="mowpns-disabled"> File Change Detection </li>
            <li class="mowpns-disabled"> Premium Customer Support</li>
						<!-- <li class="mowpns-disabled"> <a href="https://plugins.miniorange.com/wp-security-pro#pricing" target="_blank">More...<a> </li> -->
					</ul>
      </div>

    </div>

    <div class="mowpns-pricing-box mowpns-box-premium">
      <div class="mowpns-box-heading mowpns-premium">
          Total Website Security
      </div>
      <div class="mowpns-price-section mowpns-premium-price">
          <div><s style="text-decoration: line-through;">$95</s> $49/<sub><small>year</small></sub></div>
          <br>
          <div><button class="button button-primary button-small button-upgrade" id="molla_upgrade_button" onclick="mowpns_upgrade('wp_security_premium_plan')">Upgrade now</button></div>
      </div>

      <div class="mowpns-feature-list-div">
      <ul>
						<li class="mowpns-green"> Brute force protection </li>
						<li class="mowpns-green"> Rename login URL </li>
						<li class="mowpns-green"> Login Security - RECAPTCHA v2/v3 </li>
						<li class="mowpns-green"> Login and spam protection </li>
						<li class="mowpns-green"> IP blocking & whitelisting</li>
						<li class="mowpns-green"> Advanced rate limiting</li>
						<li class="mowpns-green"> Advanced firewall</li>
						<li class="mowpns-green"> Real-Time IP Blocklist</li>
						<li class="mowpns-green"> Country Blocking</li>
						<li class="mowpns-green"> Crawlers and Bot detection</li>
						<li class="mowpns-green"> Plugin/Theme Vulnerability Monitoring</li>
						<li class="mowpns-green"> File Change Detection </li>
            <li class="mowpns-green"> Premium Customer Support</li>
						<!-- <li class="mowpns-green"> <a href="https://plugins.miniorange.com/wp-security-pro#pricing" target="_blank">More...<a> </li> -->
					</ul>
      </div>
    </div>
</div>

<form class="plan_redirect" id="lla_loginform"
                  action="https://login.xecurify.com/moas/login"
                  target="_blank" method="post" style="display:none;">
                <input type="email" name="username" value="<?php esc_attr( get_option( "mo_lla_admin_email" ) ) ?>"/>
                <input type="text" name="redirectUrl"
                       value="https://login.xecurify.com/moas/initializepayment"/>
                <input type="text" name="requestOrigin" id="requestOrigin"/>
</form>
            
<form class="registration_redirect" id="lla_registration_form"
      action="<?php esc_url( $profile_url ) ?>"
      method="post" style="display:none;">
    
</form>

<script>
  var iscustomervalid = ' <?php esc_attr( $iscustomervalid ) ?>';
      function mowpns_upgrade(plan){
        if(iscustomervalid){
        jQuery('#requestOrigin').val(plan);
        jQuery('#lla_loginform').submit();
        }else{
        jQuery('#lla_registration_form').submit();
        }        
    }

    jQuery(".molla-support-div").addClass("molla-support-closed");
		jQuery("#molla-slide-support").removeClass("dashicons-arrow-right-alt2");
		jQuery("#molla-slide-support").addClass("molla-support-icon");

   
</script>
 