<?php


// Shortcodes Settings

add_action( 'ucd_settings_content', 'ucd_shortcodes_page' );
function ucd_shortcodes_page() {
		global $ucd_active_tab;
		if ( 'shortcodes' != $ucd_active_tab )
		return;
?>

  	<h3><?php _e( 'Usable Shortcodes', 'ultimate-client-dash' ); ?></h3>

		<!-- Begin settings form -->
    <form action="options.php" method="post">

						<!-- Dashboard Styling Option Section -->

						<div class="ucd-inner-wrapper settings-shortcodes">
						<p class="ucd-settings-desc">Useful shortcodes you can use throughout your website to dynamically populate data. Example: Auto update copyright date in footer based on current year.</p>

						<div class="ucd-form-message"><?php settings_errors('ucd-notices'); ?></div>

								<table class="form-table">
								<tbody>

                  <tr class="ucd-title-holder">
                  <th><h2 class="ucd-inner-title"><?php _e( 'Site Information', 'ultimate-client-dash' ) ?></h2></th>
                  </tr>

                        <tr class="ucd-shortcode-tr ucd-pro-version">
                        <th>
														<input class="ucd-shortcode-holder" type="text" value="[site-title]" id="siteTitle" readonly="readonly">
															 <div class="ucd-shortcode-tooltip">
																		<button id="siteTitle" type="button" onclick="ucdCopyToClipboard('siteTitle')" onmouseout="ucdClipboardOut()"><span class="ucd-shortcode-tooltip-text" id="siteTitleTip">Copy to clipboard</span>Copy</button>
															 </div>
												</th>
                            <td><p>Shortcode to display the site title.</p></td>
                        </tr>

                        <tr class="ucd-shortcode-tr ucd-pro-version">
                        <th>
														<input class="ucd-shortcode-holder" type="text" value="[site-description]" id="siteDescription" readonly="readonly">
															 <div class="ucd-shortcode-tooltip">
																		<button id="siteDescription" type="button" onclick="ucdCopyToClipboard('siteDescription')" onmouseout="ucdClipboardOut()"><span class="ucd-shortcode-tooltip-text" id="siteDescriptionTip">Copy to clipboard</span>Copy</button>
															 </div>
												</th>
                            <td><p>Shortcode to display the site description.</p></td>
                        </tr>

                        <tr class="ucd-shortcode-tr ucd-pro-version">
                        <th>
														<input class="ucd-shortcode-holder" type="text" value="[url]" id="siteUrl" readonly="readonly">
															 <div class="ucd-shortcode-tooltip">
																		<button id="siteUrl" type="button" onclick="ucdCopyToClipboard('siteUrl')" onmouseout="ucdClipboardOut()"><span class="ucd-shortcode-tooltip-text" id="urlTip">Copy to clipboard</span>Copy</button>
															 </div>
												</th>
                            <td><p>Shortcode to display the site url.</p></td>
                        </tr>

                        <tr class="ucd-shortcode-tr ucd-pro-version">
                        <th>
														<input class="ucd-shortcode-holder" type="text" value="[admin-email]" id="adminEmail" readonly="readonly">
															 <div class="ucd-shortcode-tooltip">
																		<button id="adminEmail" type="button" onclick="ucdCopyToClipboard('adminEmail')" onmouseout="ucdClipboardOut()"><span class="ucd-shortcode-tooltip-text" id="adminEmailTip">Copy to clipboard</span>Copy</button>
															 </div>
												</th>
                            <td><p>Shortcode to display the admin email.</p></td>
                        </tr>

                <tr class="ucd-title-holder">
                <th><h2 class="ucd-inner-title"><?php _e( 'User Information', 'ultimate-client-dash' ) ?></h2></th>
                </tr>

                      <tr class="ucd-shortcode-tr ucd-pro-version">
                      <th>
													<input class="ucd-shortcode-holder" type="text" value="[username]" id="siteUsername" readonly="readonly">
															 <div class="ucd-shortcode-tooltip">
																		<button id="siteUsername" type="button" onclick="ucdCopyToClipboard('siteUsername')" onmouseout="ucdClipboardOut()"><span class="ucd-shortcode-tooltip-text" id="usernameTip">Copy to clipboard</span>Copy</button>
															 </div>											</th>
                          <td><p>Shortcode to display the current users username.</p></td>
                      </tr>

                      <tr class="ucd-shortcode-tr ucd-pro-version">
                      <th>
													<input class="ucd-shortcode-holder" type="text" value="[email]" id="siteEmail" readonly="readonly">
															 <div class="ucd-shortcode-tooltip">
																		<button id="siteEmail" type="button" onclick="ucdCopyToClipboard('siteEmail')" onmouseout="ucdClipboardOut()"><span class="ucd-shortcode-tooltip-text" id="emailTip">Copy to clipboard</span>Copy</button>
															 </div>
											</th>
                          <td><p>Shortcode to display the current users email.</p></td>
                      </tr>

											<tr class="ucd-shortcode-tr ucd-pro-version">
                      <th>
													<input class="ucd-shortcode-holder" type="text" value="[first-name]" id="siteFirstName" readonly="readonly">
															 <div class="ucd-shortcode-tooltip">
																		<button id="siteFirstName" type="button" onclick="ucdCopyToClipboard('siteFirstName')" onmouseout="ucdClipboardOut()"><span class="ucd-shortcode-tooltip-text" id="firstNameTip">Copy to clipboard</span>Copy</button>
															 </div>
											</th>
                          <td><p>Shortcode to display the current users first name.</p></td>
                      </tr>

											<tr class="ucd-shortcode-tr ucd-pro-version">
                      <th>
													<input class="ucd-shortcode-holder" type="text" value="[last-name]" id="siteLastName" readonly="readonly">
															 <div class="ucd-shortcode-tooltip">
																		<button id="siteLastName" type="button" onclick="ucdCopyToClipboard('siteLastName')" onmouseout="ucdClipboardOut()"><span class="ucd-shortcode-tooltip-text" id="lastNameTip">Copy to clipboard</span>Copy</button>
															 </div>
											</th>
                          <td><p>Shortcode to display the current users last name.</p></td>
                      </tr>

											<tr class="ucd-shortcode-tr ucd-pro-version">
                      <th>
													<input class="ucd-shortcode-holder" type="text" value="[display-name]" id="siteDisplayName" readonly="readonly">
															 <div class="ucd-shortcode-tooltip">
																		<button id="siteDisplayName" type="button" onclick="ucdCopyToClipboard('siteDisplayName')" onmouseout="ucdClipboardOut()"><span class="ucd-shortcode-tooltip-text" id="displayNameTip">Copy to clipboard</span>Copy</button>
															 </div>
											</th>
                          <td><p>Shortcode to display the current users display name.</p></td>
                      </tr>


                <tr class="ucd-title-holder">
								<th><h2 class="ucd-inner-title"><?php _e( 'Date', 'ultimate-client-dash' ) ?></h2></th>
								</tr>

                      <tr class="ucd-shortcode-tr ucd-pro-version">
                      <th>
													<input class="ucd-shortcode-holder" type="text" value="[year]" id="siteYear" readonly="readonly">
															 <div class="ucd-shortcode-tooltip">
																		<button id="siteYear" type="button" onclick="ucdCopyToClipboard('siteYear')" onmouseout="ucdClipboardOut()"><span class="ucd-shortcode-tooltip-text" id="yearTip">Copy to clipboard</span>Copy</button>
															 </div>
											</th>
                          <td><p>Shortcode to display the current year.</p></td>
                      </tr>

                      <tr class="ucd-shortcode-tr ucd-pro-version">
                      <th>
												<input class="ucd-shortcode-holder" type="text" value="[month]" id="siteMonth" readonly="readonly">
														 <div class="ucd-shortcode-tooltip">
																	<button id="siteMonth" type="button" onclick="ucdCopyToClipboard('siteMonth')" onmouseout="ucdClipboardOut()"><span class="ucd-shortcode-tooltip-text" id="monthTip">Copy to clipboard</span>Copy</button>
														 </div>
											</th>
                          <td><p>Shortcode to display the current month.</p></td>
                      </tr>

                      <tr class="ucd-shortcode-tr ucd-pro-version">
                      <th>
												<input class="ucd-shortcode-holder" type="text" value="[day]" id="siteDay" readonly="readonly">
														 <div class="ucd-shortcode-tooltip">
																	<button id="siteDay" type="button" onclick="ucdCopyToClipboard('siteDay')" onmouseout="ucdClipboardOut()"><span class="ucd-shortcode-tooltip-text" id="dayTip">Copy to clipboard</span>Copy</button>
														 </div>
											</th>
                          <td><p>Shortcode to display the current day.</p></td>
                      </tr>

                <tr class="ucd-title-holder">
								<th><h2 class="ucd-inner-title"><?php _e( 'Symbols', 'ultimate-client-dash' ) ?></h2></th>
								</tr>

                      <tr class="ucd-shortcode-tr ucd-pro-version">
                      <th>
												<input class="ucd-shortcode-holder" type="text" value="[copyright]" id="siteCopyright" readonly="readonly">
														 <div class="ucd-shortcode-tooltip">
																	<button id="siteCopyright" type="button" onclick="ucdCopyToClipboard('siteCopyright')" onmouseout="ucdClipboardOut()"><span class="ucd-shortcode-tooltip-text" id="copyrightTip">Copy to clipboard</span>Copy</button>
														 </div>
											</th>
                          <td><p>Shortcode to display copyright symbol.</p></td>
                      </tr>

                      <tr class="ucd-shortcode-tr ucd-pro-version">
                      <th>
												<input class="ucd-shortcode-holder" type="text" value="[registered]" id="siteRegistered" readonly="readonly">
														 <div class="ucd-shortcode-tooltip">
																	<button id="siteRegistered" type="button" onclick="ucdCopyToClipboard('siteRegistered')" onmouseout="ucdClipboardOut()"><span class="ucd-shortcode-tooltip-text" id="registeredTip">Copy to clipboard</span>Copy</button>
														 </div>
											</th>
                          <td><p>Shortcode to display the registered symbol.</p></td>
                      </tr>

                      <tr class="ucd-shortcode-tr ucd-pro-version">
                      <th>
													<input class="ucd-shortcode-holder" type="text" value="[trademark]" id="siteTrademark" readonly="readonly">
															 <div class="ucd-shortcode-tooltip">
																		<button id="siteTrademark" type="button" onclick="ucdCopyToClipboard('siteTrademark')" onmouseout="ucdClipboardOut()"><span class="ucd-shortcode-tooltip-text" id="trademarkTip">Copy to clipboard</span>Copy</button>
															 </div>
											</th>
                          <td><p>Shortcode to display the trademark symbol.</p></td>
                      </tr>

								</tbody>
								</table>
						</div>
      </form>
<?php }
