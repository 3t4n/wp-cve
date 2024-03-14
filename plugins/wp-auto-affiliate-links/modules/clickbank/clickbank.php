<?php


$aalClickbank = new aalModule('clickbank','Clickbank Links',4);
$aalModules[] = $aalClickbank;

$aalClickbank->aalModuleHook('content','aalClickbankDisplay');


add_action( 'admin_init', 'aal_clickbank_register_settings' );


function aal_clickbank_register_settings() { 
   register_setting( 'aal_clickbank_settings', 'aal_clickbankid' );
   register_setting( 'aal_clickbank_settings', 'aal_clickbankcat' );
   register_setting( 'aal_clickbank_settings', 'aal_clickbankgravity' );
  // register_setting( 'aal_clickbank_settings', 'aal_clickbankactive' );
}




function aalClickbankDisplay() {

	?>
	
	<script type="text/javascript">


function aal_getScript(url, callback) {
   var script = document.createElement("script");
   script.type = "text/javascript";
   script.src = url;

   script.onreadystatechange = callback;
   script.onload = callback;

   document.getElementsByTagName("head")[0].appendChild(script);
}


/* aal_getScript("//autoaffiliatelinks.com/api/api.php?action=allcats", function(){
 
	var maincat = document.getElementById("aal_clickbankcat");
			number = maincats.length;
			
			var i=0;
			for(ir=1;ir<number+1;ir++) {
				ovalue = maincats[ir-1][0];
				otext = maincats[ir-1][1];
				if(maincats[ir-1][2] != 0) otext = '--- ' + otext;
				option= new Option(otext,ovalue);
				document.getElementById("aal_clickbankcat").options[ir] = option; 
				
				if("<?php echo get_option('aal_clickbankcat'); ?>" == ovalue) document.getElementById("aal_clickbankcat").selectedIndex = ir;
	
			}

}); */


<?php if(get_option('aal_clickbankcat')) { ?>
jQuery( document ).ready(function() {
    document.getElementById("aal_clickbankcat").selectedIndex = <?php echo get_option('aal_clickbankcat'); ?>;
});
<?php } ?>


function aal_clickbank_validate() {
	
		if(!document.aal_clickbankform.aal_clickbankcat.value) { alert("Please select a category"); return false; }
		if(!document.aal_clickbankform.aal_clickbankid.value) { alert("Please add your clickbank ID"); return false; }
		if(!document.aal_clickbankform.aal_clickbankgravity.value) { alert("If you wish to get all the products regardless the gravity, leave the gravity field with 0"); return false; }
				
	}



	
	</script>
	
	
	
<div class="wrap">  
    <div class="icon32" id="icon-options-general"></div>  
        
        
                <h2>Clickbank Links</h2>
                <br />
                <?php echo aal_apimanagement_back(); ?>
                <br /><br /><br />
                Once you add your affiliate ID and activate clickbank links, they will start to appear on your website. The manual links that you add will have priority.<br />
                This feature will only work if you have set the API Key in the respective menu.
                <br /><br />
                
<div class="aal_general_settings">
		<form method="post" action="options.php" name="aal_clickbankform" onsubmit="return aal_clickbank_validate();"> 
<?php
		settings_fields( 'aal_clickbank_settings' );
		do_settings_sections('aal_clickbank_settings_display');
?>
		<span class="aal_label">Affiliate ID:</span> <input type="text" name="aal_clickbankid" value="<?php echo get_option('aal_clickbankid'); ?>" /><br />
	<span class="aal_label">Category: </span><select id="aal_clickbankcat"  name="aal_clickbankcat" ><option  disabled value="">-Select a cateogry-	</option>
<option value="1">Arts & Entertainment</option>
<option value="2">--- Art</option>
<option value="3">--- Body Art</option>
<option value="4">--- Dance</option>
<option value="5">--- Fashion</option>
<option value="6">--- Film & Television</option>
<option value="7">--- General</option>
<option value="8">--- Magic Tricks</option>
<option value="9">--- Music</option>
<option value="10">--- Photography</option>
<option value="11">--- Radio</option>
<option value="12">Betting Systems</option>
<option value="13">--- Casino Table Games</option>
<option value="14">--- Football</option>
<option value="15">--- General</option>
<option value="16">--- Horse Racing</option>
<option value="17">--- Poker</option>
<option value="18">--- Soccer</option>
<option value="19">Business / Investing</option>
<option value="20">--- Careers, Industries & Professions</option>
<option value="21">--- Commodities</option>
<option value="22">--- Debt</option>
<option value="23">--- Derivatives</option>
<option value="24">--- Economics</option>
<option value="25">--- Equities & Stocks</option>
<option value="26">--- Foreign Exchange</option>
<option value="27">--- General</option>
<option value="28">--- International Business</option>
<option value="29">--- Management & Leadership</option>
<option value="30">--- Marketing & Sales</option>
<option value="31">--- Personal Finance</option>
<option value="32">--- Real Estate</option>
<option value="33">--- Small Biz / Entrepreneurship</option>
<option value="34">Computers / Internet</option>
<option value="35">--- General</option>
<option value="36">--- Graphics</option>
<option value="37">--- Hardware</option>
<option value="38">--- Networking</option>
<option value="39">--- Programming</option>
<option value="40">--- Software</option>
<option value="41">--- Web Hosting</option>
<option value="42">--- Web Site Design</option>
<option value="43">Cooking, Food & Wine</option>
<option value="44">--- BBQ</option>
<option value="45">--- Baking</option>
<option value="46">--- Cooking</option>
<option value="47">--- Drinks & Beverages</option>
<option value="48">--- General</option>
<option value="49">--- Recipes</option>
<option value="50">--- Regional & Intl.</option>
<option value="51">--- Special Diet</option>
<option value="52">--- Special Occasions</option>
<option value="53">--- Vegetables / Vegetarian</option>
<option value="54">--- Wine Making</option>
<option value="55">E-business & E-marketing</option>
<option value="56">--- Affiliate Marketing</option>
<option value="57">--- Article Marketing</option>
<option value="58">--- Auctions</option>
<option value="59">--- Banners</option>
<option value="60">--- Blog Marketing</option>
<option value="61">--- Consulting</option>
<option value="62">--- Copywriting</option>
<option value="63">--- E-commerce Operations</option>
<option value="64">--- Email Marketing</option>
<option value="65">--- General</option>
<option value="66">--- Market Research</option>
<option value="67">--- Marketing</option>
<option value="68">--- Niche Marketing</option>
<option value="69">--- Paid Surveys</option>
<option value="70">--- Pay Per Click Advertising</option>
<option value="71">--- Promotion</option>
<option value="72">--- SEM & SEO</option>
<option value="73">--- Social Media Marketing</option>
<option value="74">--- Video Marketing</option>
<option value="75">Education</option>
<option value="76">--- Admissions</option>
<option value="77">--- Educational Materials</option>
<option value="78">--- Higher Education</option>
<option value="79">--- K-12</option>
<option value="80">--- Test Prep & Study Guides</option>
<option value="81">Employment & Jobs</option>
<option value="82">--- Cover Letter & Resume Guides</option>
<option value="83">--- General</option>
<option value="84">--- Job Listings</option>
<option value="85">--- Job Search Guides</option>
<option value="86">--- Job Skills / Training</option>
<option value="87">Fiction</option>
<option value="88">--- General</option>
<option value="89">Games</option>
<option value="90">--- Console Guides & Repairs</option>
<option value="91">--- General</option>
<option value="92">--- Strategy Guides</option>
<option value="93">Green Products</option>
<option value="94">--- Alternative Energy</option>
<option value="95">--- Conservation & Efficiency</option>
<option value="96">--- General</option>
<option value="97">Health & Fitness</option>
<option value="98">--- Addiction</option>
<option value="99">--- Beauty</option>
<option value="100">--- Dental Health</option>
<option value="101">--- Dietary Supplements</option>
<option value="102">--- Diets & Weight Loss</option>
<option value="103">--- Exercise & Fitness</option>
<option value="104">--- General</option>
<option value="105">--- Meditation</option>
<option value="106">--- Mens Health</option>
<option value="107">--- Mental Health</option>
<option value="108">--- Nutrition</option>
<option value="109">--- Remedies</option>
<option value="110">--- Sleep and Dreams</option>
<option value="111">--- Spiritual Health</option>
<option value="112">--- Strength Training</option>
<option value="113">--- Womens Health</option>
<option value="114">--- Yoga</option>
<option value="115">Home & Garden</option>
<option value="116">--- Animal Care & Pets</option>
<option value="117">--- Crafts & Hobbies</option>
<option value="118">--- Entertaining</option>
<option value="119">--- Gardening & Horticulture</option>
<option value="120">--- General</option>
<option value="121">--- Homebuying</option>
<option value="122">--- How-to & Home Improvements</option>
<option value="123">--- Interior Design</option>
<option value="124">--- Sewing</option>
<option value="125">--- Weddings</option>
<option value="126">Languages</option>
<option value="127">--- Arabic</option>
<option value="128">--- Chinese</option>
<option value="129">--- English</option>
<option value="130">--- French</option>
<option value="131">--- German</option>
<option value="132">--- Hebrew</option>
<option value="133">--- Italian</option>
<option value="134">--- Japanese</option>
<option value="135">--- Other</option>
<option value="136">--- Sign Language</option>
<option value="137">--- Spanish</option>
<option value="138">--- Thai</option>
<option value="139">Mobile</option>
<option value="140">--- Apps</option>
<option value="141">--- Developer Tools</option>
<option value="142">--- General</option>
<option value="143">Parenting & Families</option>
<option value="144">--- Divorce</option>
<option value="145">--- Education</option>
<option value="146">--- Genealogy</option>
<option value="147">--- General</option>
<option value="148">--- Marriage</option>
<option value="149">--- Parenting</option>
<option value="150">--- Pregnancy & Childbirth</option>
<option value="151">--- Special Needs</option>
<option value="152">Politics / Current Events</option>
<option value="153">--- General</option>
<option value="154">Reference</option>
<option value="155">--- Automotive</option>
<option value="156">--- Catalogs & Directories</option>
<option value="157">--- Consumer Guides</option>
<option value="158">--- Education</option>
<option value="159">--- Gay / Lesbian</option>
<option value="160">--- General</option>
<option value="161">--- Law & Legal Issues</option>
<option value="162">--- The Sciences</option>
<option value="163">--- Writing</option>
<option value="164">Self-Help</option>
<option value="165">--- Dating Guides</option>
<option value="166">--- General</option>
<option value="167">--- Marriage & Relationships</option>
<option value="168">--- Motivational / Transformational</option>
<option value="169">--- Personal Finance</option>
<option value="170">--- Public Speaking</option>
<option value="171">--- Self Defense</option>
<option value="172">--- Self-Esteem</option>
<option value="173">--- Stress Management</option>
<option value="174">--- Success</option>
<option value="175">--- Survival</option>
<option value="176">--- Time Management</option>
<option value="177">Software & Services</option>
<option value="178">--- Communications</option>
<option value="179">--- Digital Photos</option>
<option value="180">--- Drivers</option>
<option value="181">--- Education</option>
<option value="182">--- Email</option>
<option value="183">--- Foreign Exchange Investing</option>
<option value="184">--- General</option>
<option value="185">--- Graphic Design</option>
<option value="186">--- Internet Tools</option>
<option value="187">--- MP3 & Audio</option>
<option value="188">--- Other Investment Software</option>
<option value="189">--- Personal Finance</option>
<option value="190">--- Productivity</option>
<option value="191">--- Registry Cleaners</option>
<option value="192">--- Security</option>
<option value="193">--- System Optimization</option>
<option value="194">--- Utilities</option>
<option value="195">--- Video</option>
<option value="196">--- Web Design</option>
<option value="197">Spirituality, New Age & Alternative Beliefs</option>
<option value="198">--- Astrology</option>
<option value="199">--- General</option>
<option value="200">--- Hypnosis</option>
<option value="201">--- Magic</option>
<option value="202">--- Numerology</option>
<option value="203">--- Paranormal</option>
<option value="204">--- Psychics</option>
<option value="205">--- Religion</option>
<option value="206">--- Tarot</option>
<option value="207">--- Witchcraft</option>
<option value="208">Sports</option>
<option value="209">--- Automotive</option>
<option value="210">--- Baseball</option>
<option value="211">--- Basketball</option>
<option value="212">--- Cycling</option>
<option value="213">--- General</option>
<option value="214">--- Golf</option>
<option value="215">--- Hockey</option>
<option value="216">--- Individual Sports</option>
<option value="217">--- Martial Arts</option>
<option value="218">--- Mountaineering</option>
<option value="219">--- Other Team Sports</option>
<option value="220">--- Outdoors & Nature</option>
<option value="221">--- Racket Sports</option>
<option value="222">--- Running</option>
<option value="223">--- Soccer</option>
<option value="224">--- Training</option>
<option value="225">--- Water Sports</option>
<option value="226">--- Winter Sports</option>
<option value="227">Travel</option>
<option value="228">--- Asia</option>
<option value="229">--- Europe</option>
<option value="230">--- General</option>
<option value="231">--- Specialty Travel</option>
<option value="232">--- United States</option>
<option value="233">All categories</option>
	</select>
	<br />
		<span class="aal_label">Minimum gravity: </span><input type="text" name="aal_clickbankgravity" value="<?php echo get_option('aal_clickbankgravity'); ?>" /><br />
		<!-- <span class="aal_label">Status: </span><select name="aal_clickbankactive">
			<option value="0" <?php if(get_option('aal_clickbankactive')=='0') echo "selected"; ?> >Inactive</option>
			<option value="1" <?php if(get_option('aal_clickbankactive')=='1') echo "selected"; ?> >Active</option>
		</select><br /> -->




<?php
	submit_button('Save');
	echo '</form></div>';
	
	update_option('aal_settings_updated',time());	
	?>
	<a href="<?php echo admin_url('admin.php?page=aal_apimanagement'); ?>" class="button button-primary">Back to API Management</a>

	<?php
	echo '</div>';

		

?>


</div>




<?php 
	

} ?>