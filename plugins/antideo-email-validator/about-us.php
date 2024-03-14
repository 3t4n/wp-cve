<?php
defined('ABSPATH') or die('Nope nope nope...');

$tick = plugin_dir_url( __FILE__ ) . 'assets/tick.png';
$cross = plugin_dir_url( __FILE__ ) . 'assets/cross.png';
$tick_light =plugin_dir_url( __FILE__ ) . 'assets/ticklight.png';
$stars = plugin_dir_url( __FILE__ ) . 'assets/stars.png';
$logo = plugin_dir_url( __FILE__ ) . 'assets/antideomin.png';
$page_url = "https://www.antideo.com/wordpress-email-validation-plugin/";
$plugin_url = "https://wordpress.org/plugins/antideo-email-validator/";

?>
<style>
#wpcontent {
  padding-left: 0px !important; 
}
h3{
  font-size: 20px;
  line-height: 30px;
}
p{
  font-size: 16px;
}
.anticontainer{
  width: auto;
  clear: both;
  padding: 1%;
  background-color: #f9fbff;
}

.antititle{
  display: inline-flex;
}
.antititle h3{
  transform: translateY(30%);
  transform: translateX(3%);
}

.maincounts{
  width: 100%;
  margin: 2% 0%;
  text-align: left;
}

/* Style the tab */
.antitab {
  overflow: hidden;
  margin-bottom: 20px;
  width: 100%;
  background: #fff;
  border-top: 1px solid #d5e2ed;
  border-bottom: 1px solid #d5e2ed;
}

/* Style the buttons inside the tab */
.antitab button {
  text-decoration: none;
  padding: 17px 10px 15px 10px;
  border-bottom: 2px solid #fff;
  font-size: 14px;
  color: #393f4c;
  display: inline-block;
  margin-right: 25px;
  line-height: 1;
  outline: none;
  font-family: Lato,sans-serif;
  float: left;
  border:none;
  outline: none;
  cursor: pointer;
  transition: 0.3s;
  background:#fff;
}

/* Change background color of buttons on hover */
.antitab button:hover {
  color: #4776e6;
}

/* Create an active/current tablink class */
.antitab button.active {
  border-bottom: 2px solid #4776e6;
  color: #4776e6;
}

/* Style the tab content */
.antitabcontent {
  display: none;
  background-color: #fff;
  border-top: none;
  width: auto;
  min-height: 450px;
  border: 1px solid #d5e2ed;
  margin: 0;
}
.default{
  display: block;
}

.antiabout{
  width: auto;
  height: auto;
  padding: 20px;
  background-color: #fff;
}

.antiabout h4{
  font-size: 15px;
  font-weight: 500;
  color: #323231;
  line-height: 1;
}

.antiabout p{
  font-size: 16px;
  font-weight: 400;
  color: #323231
  line-height: 1.5;
}

.abouta{
  width: 100%;
  text-align: left;
  font-size: 16px;
  font-weight: 500;
  line-height: 1.5;
  position: relative;
}

.aboutb{
  float: right;
  width: 30%;
  text-align: center;
  margin: 0 0 0 20px;
}


.aboutdet{
  width: auto;
  background-color: #fff;
}

.comparedata{
  width: auto;
  height: auto;
}

.compmain{
  width: auto;
  background-color: #fff;
  padding: 40px;
  text-align: center;
}

.compmain h3{
  font-size: 22px;
  margin: 0 0 22px;
  color: #23282d;
  display: block;
  font-weight: 600;
}

.compmain p{
  font-size: 16px;
  line-height: 1.5;
  margin: 1em 0;
  font-weight: 600; 
}

.comparehead{
  width: 100%;
  display: flex;
  flex-wrap: wrap;
  margin: 0;
  justify-content: space-between;
}

.combheadbg{
  background-color: #f3f3f3;
}

.combdatabg{
  background-color: #fff;
  border-bottom: 1px solid #d5e2ed;
}

.combtitle{
  width: 30%;
  display: block;
  font-size: 16px;
  line-height: 50px;
  background-color: #f3f3f3;
  color: #000;
  padding: 20px 15px;
  text-align: left;
  padding-left: 15px;
  font-weight: 500;
}

div.combdatabg > div > div{
  width: 30%;
  display: block;
  font-size: 16px;
  line-height: 50px;
  background-color: #fff;
  color: #000;
  padding: 20px 15px;
  text-align: left;
  padding-left: 15px;
  font-weight: 500;
}

div.combdatabg > div > div > img{
  width:25px;
}

.pcolor{
  background-color: #fff;
}

.compfooter{
  width: 100%;
  padding: 40px 0;
  text-align: center;
  font-size: 18px;
  color: #509fe2;
  font-weight: 600;
  background-color: #f3f3f3;
}

.compfooter a{
  color: #509fe2;
}

.compfooter a:hover{
  color: #000;
}

.antifooter{
  width:auto;
  height: auto;
  font-size: 16px;
  line-height: 1.5;
  text-align: center;
  margin: 20px;
}

@media(max-width: 420px){
  .abouta{
    width: 100%;
  }

  .aboutb{
    width: 100%;
  }

  .comparehead{
    flex-direction: column;
  }

}
</style>

<div class="anticontainer">

  <div class="antititle">
    <img src="<?php echo $logo; ?>" width="70px" />
    <h3>
    <?php echo esc_html__('Antideo Email Validator' ,'antideo-email-validator'); ?>
    </h3>
  </div>

  <div class="maincounts">

    <div class="antitab">
      <button class="tablinks active" onclick="openTab(event, 'aboutus')"><?php echo esc_html__('About Us' ,'antideo-email-validator'); ?></button>
      <button class="tablinks" onclick="openTab(event, 'precompare')"><?php echo esc_html__('Free Vs Premium' ,'antideo-email-validator'); ?></button>
      <button class="tablinks" onclick="openTab(event, 'faq')"><?php echo esc_html__('FAQ' ,'antideo-email-validator'); ?></button>
    </div>

    <div id="aboutus" class="antitabcontent default">
      <div class="antiabout">
        <div class="abouta">
          <div class="aboutb">
            <img src="<?php echo $logo; ?>" />
          </div>
          <h3>
          <?php echo esc_html__('Thank you for using Antideo, one of the leading plugins to help you
            prevent fake signups in real time' ,'antideo-email-validator'); ?>
          </h3>
          <p>
          <?php echo esc_html__('Having run a number of websites our founders saw that the number of spam and fake 
            signups were just overwhelming and there wasn’t really a product out there which was
            reliable as well as cost efficient, giving birth to Antideo API. Though the API is easy to 
            integrate, a non tech person might find it difficult to use. The WordPress plugin is 
            created to enable a non tech person easily integrate the security functionality into any 
            of the forms that he or she may have on their website.' ,'antideo-email-validator'); ?>
          </p>
          <p><?php echo esc_html__('The motto of Antideo is to create easy to use and affordable solutions that is accessible
            by everyone, in-order to make the web world safer. Antideo has a number of advanced features 
            and products in the pipeline to empower website owners and prevent wasted effort and money
            on fake users, bots, spammers, scammers etc.' ,'antideo-email-validator'); ?>
          </p>
        </div>
      </div>    
    </div>

    <div id="precompare" class="antitabcontent">
      <div class="compmain">
        <h3><?php echo esc_html__('Free vs Premium' ,'antideo-email-validator'); ?></h3>
        <p><?php echo esc_html__('Get the most by upgrading to Premium and unlocking all of the powerful features.' ,'antideo-email-validator'); ?></p>
      </div>

      <div class="comparedata">
        <div class="combheadbg">
          <div class="comparehead">
            <div class="combtitle"><?php echo esc_html__('Feature' ,'antideo-email-validator'); ?></div>
            <div class="combtitle"><?php echo esc_html__('Free Version' ,'antideo-email-validator'); ?></div>
            <div class="combtitle"><?php echo esc_html__('Premium Version' ,'antideo-email-validator'); ?></div>
          </div>
        </div>

        <div class="combdatabg">
          <div class="comparehead">
            <div><?php echo esc_html__('Email Syntax Check' ,'antideo-email-validator'); ?></div>
            <div>
              <img src="<?php echo $tick; ?>" ><?php echo esc_html__('Yes' ,'antideo-email-validator'); ?>
            </div>
            <div>
              <img src="<?php echo $tick; ?>" ><?php echo esc_html__('Yes' ,'antideo-email-validator'); ?>
            </div>
          </div>
        </div>

        <div class="combdatabg">
          <div class="comparehead">
            <div><?php echo esc_html__('Free Email Detection' ,'antideo-email-validator'); ?></div>
            <div>
              <img src="<?php echo $tick; ?>" ><?php echo esc_html__('Yes' ,'antideo-email-validator'); ?>
            </div>
            <div>
              <img src="<?php echo $tick; ?>" ><?php echo esc_html__('Yes' ,'antideo-email-validator'); ?>
            </div>
          </div>
        </div>

        <div class="combdatabg">
          <div class="comparehead">
            <div><?php echo esc_html__('Generic Email Detection' ,'antideo-email-validator'); ?></div>
            <div>
              <img src="<?php echo $tick; ?>" ><?php echo esc_html__('Yes' ,'antideo-email-validator'); ?>
            </div>
            <div>
              <img src="<?php echo $tick; ?>" ><?php echo esc_html__('Yes' ,'antideo-email-validator'); ?>
            </div>
          </div>
        </div>

        <div class="combdatabg">
          <div class="comparehead">
            <div><?php echo esc_html__('Local Email &amp; Domain Blacklist' ,'antideo-email-validator'); ?></div>
            <div>
              <img src="<?php echo $tick; ?>" ><?php echo esc_html__('50 line items each' ,'antideo-email-validator'); ?>
            </div>
            <div>
              <img src="<?php echo $tick; ?>" ><?php echo esc_html__('Unlimited' ,'antideo-email-validator'); ?>
            </div>
          </div>
        </div>

        <div class="combdatabg">
          <div class="comparehead">
            <div><?php echo esc_html__('Local Email &amp; Domain Whitelist' ,'antideo-email-validator'); ?></div>
            <div>
              <img src="<?php echo $tick; ?>" ><?php echo esc_html__('50 line items each' ,'antideo-email-validator'); ?>
            </div>
            <div>
              <img src="<?php echo $tick; ?>" ><?php echo esc_html__('Unlimited' ,'antideo-email-validator'); ?>
            </div>
          </div>
        </div>

        <div class="combdatabg">
          <div class="comparehead">
            <div><?php echo esc_html__('MX Records Check' ,'antideo-email-validator'); ?></div>
            <div>
              <img src="<?php echo $cross; ?>" ><?php echo esc_html__('No' ,'antideo-email-validator'); ?>
            </div>
            <div>
              <img src="<?php echo $tick; ?>" ><?php echo esc_html__('Yes' ,'antideo-email-validator'); ?>
            </div>
          </div>
        </div>

        <div class="combdatabg">
          <div class="comparehead">
            <div><?php echo esc_html__('Disposable Email Detection' ,'antideo-email-validator'); ?></div>
            <div>
              <img src="<?php echo $tick_light; ?>" ><?php echo esc_html__('Using a static List' ,'antideo-email-validator'); ?>
            </div>
            <div>
              <img src="<?php echo $tick; ?>" ><?php echo esc_html__('Using a dynamic constantly updated list' ,'antideo-email-validator'); ?>
            </div>
          </div>
        </div>

        <div class="combdatabg">
          <div class="comparehead">
            <div><?php echo esc_html__('Support' ,'antideo-email-validator'); ?></div>
            <div>
              <img src="<?php echo $cross; ?>" ><?php echo esc_html__('No' ,'antideo-email-validator'); ?>
            </div>
            <div>
              <img src="<?php echo $tick; ?>" ><?php echo esc_html__('Email &amp; Chat' ,'antideo-email-validator'); ?>
            </div>
          </div>
        </div>

        <div class="compfooter">
          <a target="_blank" href="<?php echo $page_url; ?>" ><?php echo esc_html__('Get Antideo Premium Plugin today &amp; prevent fake signups' ,'antideo-email-validator'); ?></a>
        </div>
      </div>  
    </div>

    <div id="faq" class="antitabcontent">
      <div class="antiabout">
        <h4><?php echo esc_html__('In Disposable Email Detection, what is the difference between the static list and the dynamic list?' ,'antideo-email-validator'); ?></h4>
        <p>
        <?php echo esc_html__('The static list consists of a fixed set of disposable email vendors while the dynamic list gets 
          updated with new vendors as a when they are discovered' ,'antideo-email-validator'); ?>
        </p>

        <h4><?php echo esc_html__('Why should I subscribe to the premium email validation plugin and not just use the free version?' ,'antideo-email-validator'); ?></h4>
        <p>
        <?php echo esc_html__('The free version does provide the essential features needed to prevent fake sign-ups, 
          but the validation is based on a static list of disposable email vendors. New disposable email 
          vendors pop up frequently and the existing vendors come up with new domains  too. So with the 
          premium email validation plugin you can ensure your list stays updated and you have better 
          levels of protection' ,'antideo-email-validator'); ?>
        </p>
      
        <h4><?php echo esc_html__('I am not technical, how easy is it to setup the plugin?' ,'antideo-email-validator'); ?></h4>
        <p>
        <?php echo esc_html__('The plugin works out of the box with the major forms out there, and you donot need to be tech 
          savvy at all to setup the plugin. Once you install and activate the plugin from the plugin store, 
          you would be presented check boxes to select your preferences. That’s it, you are all set..' ,'antideo-email-validator'); ?>
        </p>
        
        <h4><?php echo esc_html__('Is there any other cost outside of $2/month?' ,'antideo-email-validator'); ?></h4>
        <p>
        <?php echo esc_html__('Nope, it\'s a $2/month flat fee for email validation without any restriction on the number of 
          queries that can be made. Please do-not confuse this with email verification, our service 
          uses a combination of methods to ascertain the validity of the emails but does-not do the end-point
          verification if the email exists. A full fledged email verification system is in the works and 
          we expect to release the functionality in the near future' ,'antideo-email-validator'); ?>
        </p>
        
        <h4><?php echo esc_html__('What if my site is not on WordPress but I would still like to use your services?' ,'antideo-email-validator'); ?></h4>
        <p>
        <?php echo esc_html__('Apart from the plugin we have a well functioning API service that can be integrated in to 
          any website or a form. Have a look at our ' ,'antideo-email-validator'); ?>
		  <a target="_blank" href="https://www.antideo.com/email-validation/" ><?php echo esc_html__('Email Validation API' ,'antideo-email-validator'); ?></a>
        </p>
        
        <h4><?php echo esc_html__('Do you have access to, or store the email addresses being validated through the plugin?' ,'antideo-email-validator'); ?></h4>
        <p>
        <?php echo esc_html__('We have built mechanisms to validate the email addresses within the plugin itself without 
          a need for the email address to be sent out to our database, so in short we never 
          access your prospect\'s/customer\'s email address. You do however have an option to build your own 
          blacklist within the plugin, which is passed on to us to alert our users of the scammer/spammers. 
          This crowdsourced list would help us provide better security to our users' ,'antideo-email-validator'); ?>
        </p>
      </div>
    </div>
  </div>

  <div class="antifooter">
  <?php echo esc_html__('Please rate Antideo Email Validator' ,'antideo-email-validator'); ?>
    <img src="<?php echo $stars; ?>"><?php echo esc_html__(' on ' ,'antideo-email-validator'); ?>
    <a target="_blank" href="https://wordpress.org/plugins/antideo-email-validator/" ><?php echo esc_html__('WordPress.org' ,'antideo-email-validator'); ?></a>
    <?php echo esc_html__('to help us spread the word. Thank you from the Antideo team!' ,'antideo-email-validator'); ?>
  </div>
</div>

<script>

  function openTab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("antitabcontent");
    for (i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
    tablinks.className = tablinks.className.replace(" default", "");
  }

</script>

