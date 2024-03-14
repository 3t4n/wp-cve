<?php
if ( ! defined( 'ABSPATH' ) ) exit;

header('Location: https://www.if-so.com/help/documentation/dynamic-keyword-insertion/');
?>

<div class="content">
<h1>Dynamic Keyword Insertion (DKI)</h1>

<p>&nbsp;</p>

<p>If-So Dynamic Keyword Insertion lets you insert keywords into your webpages using simple shortcodes.</p>

<p>&nbsp;</p>

<p>With If-So DKI you can display:</p>

<p>&nbsp;</p>

<ul>
<li>A value of a query string parameter</li>
<li>The user&rsquo;s location: country, state, city, or continent</li>
<li>The user name</li>
<li>The user&rsquo;s time zone</li>
<li>The referral source</li>
<li>The user&rsquo;s browser language</li>
<li>A time, calculated according to the user timezone</li>
<li>WooCommerce&nbsp; - number of product in the cart, the value of items in the cart, MORE</li>
</ul>

<p>&nbsp;</p>

<h2><strong>The available shortcodes</strong></h2>

<h3><strong>Query String DKI</strong></h3>

<p><em>Display value</em> of a query string</p>

<p>[ifsoDKI type=&quot;querystring&quot; parameter=&quot;YOUR-PARAMETER&quot; fallback=&quot; Default value you choose - optional&quot;]</p>

<p><em>The shortcode above will display the value of the parameter &ldquo;YOUR-PARAMETER&rdquo;. I.e. if the page URL is example.com?YOUR_PARAMETER=Awesome, the word Awesome will be displayed instead of the shortcode.</em></p>

<p>&ldquo;fallback&rdquo; - add this parameter to the shortcode if you want to set a fallback that will be displayed if the URL doesn&rsquo;t include the query parameter.</p>

<p>&nbsp;! Note: Some parameters are reserved by WordPress, make sure not to use them as parameters. <a href="https://developer.wordpress.org/reference/functions/register_taxonomy/#reserved-terms">Click here to See the full list of reserved parameters</a>.</p>

<p>&nbsp;</p>

<h3><strong>Geolocation DKI</strong></h3>

<p>&nbsp;</p>

<p>Display the user&rsquo;s location: country, city, state, continent, or timezone.</p>

<p>&nbsp;</p>

<p><em>Insert the user&rsquo;s country</em></p>

<p>[ifsoDKI type=&#39;geo&#39; show=&#39;country&#39;]</p>

<p><em>Insert the user&rsquo;s state</em></p>

<p>[ifsoDKI type=&#39;geo&#39; show=&#39;state&#39;]</p>

<p><em>Insert the user&rsquo;s city</em></p>

<p>[ifsoDKI type=&#39;geo&#39; show=&#39;city&#39;]</p>

<p>&nbsp;</p>

<p><em>Insert the user&rsquo;s continent</em></p>

<p>[ifsoDKI type=&#39;geo&#39; show=&#39;continent&#39;]</p>

<p><em>Insert the user&rsquo;s timezone</em></p>

<p>[ifsoDKI type=&#39;geo&#39; show=&#39;timezone&#39;]</p>

<p>&nbsp;</p>

<p>* IP-to-location detection might not be 100%. If we don&rsquo;t detect your location correctly please click here, we will fix it.</p>

<p>&nbsp;</p>

<h3><strong>User details (for logged-in users)</strong></h3>

<p><br />
&nbsp;</p>

<p><em>Insert the user&rsquo;s name</em></p>

<p>[ifso_user_details show=&quot;firstName&quot;]</p>

<p><em>Replace the value of the show parameter to insert other user details:</em></p>

<p><em>Last name: </em><em>show=&quot;lastName&quot;</em></p>

<p><em>Full name: </em><em>show=&quot;fullName&quot;</em></p>

<p><em>Email: </em><em>show=&quot;email&quot;</em></p>

<p><strong><em>Fallback parameter</em></strong></p>

<p><em>Add a fallback=value to set the value that will be displayed if the user is not logged in or if the user data is blank.</em></p>

<p>[ifso_user_details show=&quot;firstName&quot; fallback=&quot;Default value - optional&quot;]</p>

<p><a href="https://www.if-so.com/user-details-dki/">Learn more about the User Details shortcode</a></p>

<p>&nbsp;</p>

<h3><strong>Login Link</strong></h3>

<p><em>Display a login/logout link:</em></p>

<p>[ifso_login_link login_redirect=&quot;https://example.com/account&quot; ]</p>

<p><a href="https://www.if-so.com/log-in-out-shortcode/">Learn more about the Login Link shortcode</a></p>

<p>&nbsp;</p>

<h3><strong>Referrer Source DKI</strong></h3>

<p><em>Insert the referrer source</em></p>

<p>[ifsoDKI type=&#39;referrer&#39; fallback=&quot;Default value - optional&quot;]</p>

<p><em>Insert only the domain of the referrer source, without permalinks</em></p>

<p>[ifsoDKI type=&#39;referrer&#39; show=&#39;domain-only&#39; fallback=&quot;Default value - optional&quot;]</p>

<h3><strong>Browser Language DKI</strong></h3>

<p><em>Insert the user&rsquo;s primary browser language</em></p>

<p>[ifsoDKI type=&#39;language&#39; show=&#39;primary-only&#39;]</p>

<p><em>Insert a list of the user&rsquo;s non-primary browser languages</em></p>

<p>[ifsoDKI type=&#39;language&#39; show=&#39;all-except-primary&#39;]</p>

<p><em>Insert a list of the user&rsquo;s browser languages</em></p>

<p>[ifsoDKI type=&#39;language&#39; show=&#39;all&#39;]</p>

<p><br />
&nbsp;</p>

<h3><strong>Total Number of Pages visits</strong></h3>

<p><em>Display the number of website pages that were visited by the user</em></p>

<p>[[ifsoDKI type=&#39;viewcount&#39; show=&#39;visit-count&#39;]&#39;]</p>

<p><br />
&nbsp;</p>

<h2>WooCommerce DKI shortcodes</h2>

<p>&nbsp;</p>

<h2>Items in the cart</h2>

<p><em>Number of items in the cart:</em></p>

<p>[IfsoWCDKI type=&#39;cart&#39; show=&#39;number&#39;]</p>

<p><em>Value of items in the cart:</em></p>

<p>[IfsoWCDKI type=&#39;cart&#39; show=&#39;value&#39;]</p>

<p>&nbsp;</p>
</div>