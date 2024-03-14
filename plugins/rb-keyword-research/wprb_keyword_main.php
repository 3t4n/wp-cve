<?php


function register_session(){
    if( !session_id() )
        session_start();
}
add_action('init','register_session',1);

if (isset($_POST['google'])) {
	$google = $_POST['google'];
	$_SESSION['rbeky_cntry'] = $google;
}

$option_value = '';
if (isset($_SESSION['rbeky_cntry'])) {
    $option_value = $_SESSION['rbeky_cntry'];
}
?>
<div class="wrap">

<h1>RB Keyword Research</h1>

	<p>RB Keyword research, gathers what people are searching for in Google's auto-suggest tool, these keywords are great for building your keyword list.</p>
    <p>The <strong>Pro version</strong> includes Bing and Yahoo keywords too! Upgrade including more search engines available <a target="_blank" href="https://www.redcrowndigital.com/wp_plugins">SEO KeyWord Generator Pro</a>.</p>

<h2>Step #1) Set Country:</h2>
<p>
<form method="post" action="">
<select name="google">
    <option value="google.com" <?php echo ($option_value === '') ? 'selected' : ''?>>Select One</option>
    <option value="google.com" <?php echo ($option_value === 'google.com') ? 'selected' : ''?>>United States</option>
    <option value="google.com.af" <?php echo ($option_value === 'google.com.af') ? 'selected' : ''?>>Afghanistan</option>
    <option value="google.com.ag" <?php echo ($option_value === 'google.com.ag') ? 'selected' : ''?>>Antigua</option>
    <option value="google.com.au" <?php echo ($option_value === 'google.com.au') ? 'selected' : ''?>>Australia</option>
    <option value="google.at" <?php echo ($option_value === 'google.at') ? 'selected' : ''?>>Austria</option>
    <option value="google.bs" <?php echo ($option_value === 'google.bs') ? 'selected' : ''?>>Bahamas</option>
    <option value="google.be" <?php echo ($option_value === 'google.be') ? 'selected' : ''?>>Belgium</option>
    <option value="google.bt" <?php echo ($option_value === 'google.bt') ? 'selected' : ''?>>Bhutan</option>
    <option value="google.com.bo" <?php echo ($option_value === 'google.com.bo') ? 'selected' : ''?>>Bolivia</option>
    <option value="google.com.br" <?php echo ($option_value === 'google.com.br') ? 'selected' : ''?>>Brazil</option>
    <option value="google.com.kh" <?php echo ($option_value === 'google.com.kh') ? 'selected' : ''?>>Cambodia</option>
    <option value="google.ca" <?php echo ($option_value === 'google.ca') ? 'selected' : ''?>>Canada</option>
    <option value="google.cl" <?php echo ($option_value === 'google.cl') ? 'selected' : ''?>>Chile</option>
    <option value="google.cn" <?php echo ($option_value === 'google.cn') ? 'selected' : ''?>>China</option>
    <option value="google.com.co" <?php echo ($option_value === 'google.com.co') ? 'selected' : ''?>>Colombia</option>
    <option value="google.co.cr" <?php echo ($option_value === 'google.com.cr') ? 'selected' : ''?>>Costa Rica</option>
    <option value="google.cz" <?php echo ($option_value === 'google.cz') ? 'selected' : ''?>>Czech Republic</option>
    <option value="google.dk" <?php echo ($option_value === 'google.dk') ? 'selected' : ''?>>Denmark</option>
    <option value="google.com.eg" <?php echo ($option_value === 'google.com.eg') ? 'selected' : ''?>>Egypt</option>
    <option value="google.fi" <?php echo ($option_value === 'google.fi') ? 'selected' : ''?>>Finland</option>
    <option value="google.fr" <?php echo ($option_value === 'google.fr') ? 'selected' : ''?>>France</option>
    <option value="google.de" <?php echo ($option_value === 'google.de') ? 'selected' : ''?>>Germany</option>
    <option value="google.com.gh" <?php echo ($option_value === 'google.com.gh') ? 'selected' : ''?>>Ghana</option>
    <option value="google.com.hk" <?php echo ($option_value === 'google.com.hk') ? 'selected' : ''?>>Hong Kong</option>
    <option value="google.co.in" <?php echo ($option_value === 'google.com.in') ? 'selected' : ''?>>India</option>
    <option value="google.co.id" <?php echo ($option_value === 'google.co.id') ? 'selected' : ''?>>Indonesia</option>
    <option value="google.it" <?php echo ($option_value === 'google.it') ? 'selected' : ''?>>Italy</option>
    <option value="google.co.jp" <?php echo ($option_value === 'google.com.jp') ? 'selected' : ''?>>Japan</option>
    <option value="google.co.ke" <?php echo ($option_value === 'google.com.ke') ? 'selected' : ''?>>Kenya</option>
    <option value="google.com.my" <?php echo ($option_value === 'google.com.my') ? 'selected' : ''?>>Malaysia</option>
    <option value="google.com.mx" <?php echo ($option_value === 'google.com.mx') ? 'selected' : ''?>>Mexico</option>
    <option value="google.nl" <?php echo ($option_value === 'google.com.nl') ? 'selected' : ''?>>Netherlands</option>
    <option value="google.co.nz" <?php echo ($option_value === 'google.com.nz') ? 'selected' : ''?>>New Zealand</option>
    <option value="google.com.pe" <?php echo ($option_value === 'google.com.pe') ? 'selected' : ''?>>Peru</option>
    <option value="google.com.ph" <?php echo ($option_value === 'google.com.ph') ? 'selected' : ''?>>Philippines</option>
    <option value="google.pl" <?php echo ($option_value === 'google.com.pl') ? 'selected' : ''?>>Poland</option>
    <option value="google.ru" <?php echo ($option_value === 'google.com.ru') ? 'selected' : ''?>>Russia</option>
    <option value="google.com.sg" <?php echo ($option_value === 'google.com.sg') ? 'selected' : ''?>>Singapore</option>
    <option value="google.co.za" <?php echo ($option_value === 'google.co.za') ? 'selected' : ''?>>South Africa</option>
    <option value="google.es" <?php echo ($option_value === 'google.es') ? 'selected' : ''?>>Spain</option>
    <option value="google.ch" <?php echo ($option_value === 'google.ch') ? 'selected' : ''?>>Switzerland</option>
    <option value="google.se" <?php echo ($option_value === 'google.se') ? 'selected' : ''?>>Sweden</option>
    <option value="google.co.th" <?php echo ($option_value === 'google.co.th') ? 'selected' : ''?>>Thailand</option>
    <option value="google.com.tr" <?php echo ($option_value === 'google.com.tr') ? 'selected' : ''?>>Turkey</option>
    <option value="google.co.uk" <?php echo ($option_value === 'google.co.uk') ? 'selected' : ''?>>United Kingdom</option>
    <option value="google.co.uy" <?php echo ($option_value === 'google.co.uy') ? 'selected' : ''?>>Uruguay</option>
    <option value="google.co.ve" <?php echo ($option_value === 'google.co.ve') ? 'selected' : ''?>>Venezuela</option>
</select>
<input type="submit" value="Set Country"/>
</form>
</p>

<?php
if (isset($_SESSION['rbeky_cntry'])) {
    $country = $_SESSION['rbeky_cntry'];
}
$google = '';
if ($google == "" && empty($_SESSION['rbeky_cntry'])) {
    echo '<span class="rbkred">Set Your Country!</span>';
} else {
    $rbkeywordres_gg = get_option('rbkeywordres_gg', '' . $google . '');
    echo '<span class="rbkred">Set: ' . $country . '</span>';
}

$rbkeyword_alphabets = get_option('rbkeyword_alphabets', 'a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z');
$letters_arr = explode(',', trim($rbkeyword_alphabets));
$letters = array_filter($letters_arr);
 ?>
<h2>
	Step #2) Enter the Keyword:
</h2>
	<table class="rbkeytable">

		<tbody>

		<tr>

			<td><input style="width:100%" type="text" value="" autocomplete="off" placeholder="Keyword..." size="14" class="newtag form-input-tip" id="rbkeyword_search_txt"></td>

			<td style="width: 135px;" ><input style="width:100%"  type="button" tabindex="3" value="Search" class="button" id="rbkeyword_more"></td>

			<td style="width: 38px;"><input style="width:100%"  type="button" tabindex="3" value="x" class="button tagadd" id="rbkeyword_clean"></td>

		</tr>

		<tr><td colspan="3">

			<div class="hidden" id="rbkeyword_body">

				<div id="rbkeyword_keywords" class="wp-tab-panel"></div>

				<div class="rbkeywordcheckbox"><label><input type="checkbox" id="rbkeyword_check" value="s">Check/uncheck all</label></div>

				<input type="button" value="My keyword list" class="button" id="rbkeyword_list_btn">

				<p><h2>RB Keyword Research found (<b><i><u><span class="rbkeyword_count"></span></u></i></b>) keywords for the term

				(<b><i><u><span class="rbkeyword_keyword_status"></span></u></i></b>) </h2>

				</p>

			</div>

		</td></tr>

		</tbody>

	</table>

		<div style="display: none"  id="rbkeyword-list-wrap">

		<textarea style="width:100%;height: 300px;" id="rbkeyword-list"></textarea>

	</div>
	</div>

	<script type="text/javascript">

		var rbkeyword_google = '<?php echo $rbkeywordres_gg ?>';

		var rbkeyword_letters = <?php echo json_encode($letters) ?> ;

	</script>
