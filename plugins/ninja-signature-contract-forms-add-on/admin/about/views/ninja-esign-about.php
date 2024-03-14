<?php
// Silence is golden
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<div id="wpbody">

    <div id="wpbody-content" aria-label="Main content" tabindex="0">
        <div id="screen-meta" class="metabox-prefs">

            <div id="contextual-help-wrap" class="hidden no-sidebar" tabindex="-1" aria-label="Contextual Help Tab">
                <div id="contextual-help-back"></div>
                <div id="contextual-help-columns">
                    <div class="contextual-help-tabs">
                        <ul>
                        </ul>
                    </div>


                    <div class="contextual-help-tabs-wrap">
                    </div>
                </div>
            </div>
        </div>
<!--[if lt IE 9]><script>document.createElement('audio');document.createElement('video');</script><![endif]-->
        <div class="wrap approveme-about-wrap">

            <h1>Get Started with Ninja Forms &amp; WPESign by ApproveMe</h1>


            <div class="about-text top">Automatically collect digital signatures on contracts, after your visitors submit a form using ApproveMe's WP E-Signature.<a href="https://www.approveme.com/ueta-and-esign-act?utm_source=wprepo&amp;utm_medium=link&amp;utm_campaign=ninja-forms">UETA/ESIGN Compliant</a> legally binding contracts with WordPress.
            </div>

            <div align="center"> <div class="approveme-btn"><a class="approveme-demo-link" href="http://www.secure.approveme.com/demo/?utm_source=wprepo&utm_medium=link&utm_campaign=ninja-forms">Try a Live Demo of this Integration</a></div></div>

            <div id="esig-yt-video">
                <p align="center">

                    <?php add_thickbox(); ?>


                    <a href="//www.youtube.com/embed/y6gE7bXQOMY?&autoplay=1&rel=0&theme=light&hd=1&autohide=1&showinfo=0&color=white&showinfo=0?TB_iframe=true&width=960&height=540" class="thickbox">


                        <img src="<?php echo plugins_url('../assets/images/getting-started-video-thumb.jpg', __FILE__); ?>" align="center" width="70%">


                    </a>

                </p>
            </div>

            <div class="approveme-cta">
                <a href="https://www.approveme.com/?utm_source=wprepo&utm_medium=link&utm_campaign=ninja-forms" class="hvr-float-shadow">Get My ApproveMe Plugin</a>
            </div>

            <div class="changelog feature-list">
                <h2>Follow these easy steps to get started</h2>
                <!-- Start Step 1 -->
                <div class="approveme-feature-section approveme-table">
                    <div class="approveme-row">
                        <div class="approveme-column-2">
                            <h4><span class="esig-step">1</span> Download and Install the required plugins</h4>
                            <p>Congrats on installing our Digital Signature Ninja Forms Add-on.  You're almost ready!  ApproveMe's WP E-Signature requires a few more plugins/add-ons so you can unlock the power and automate Ninja Forms and legally binding Digital Signatures. You'll need to download the following:</p>

                            <p><a href="https://wordpress.org/plugins/ninja-forms/" target="_blank">1. Ninja Forms</a><br> <a href="https://www.approveme.com/?utm_source=wprepo&utm_medium=link&utm_campaign=ninja-forms">2. WP E-Signature</a> <em>(ultimate, business or professional license)<br> </em><a href="https://www.approveme.com/ninja-forms-special-pricing/?utm_source=wprepo&utm_medium=link&utm_campaign=ninja-forms">3. E-Signature Business Pack</a> <em>(included with the license above)<br> </em></p>


                        </div>

                        <div class="approveme-column-2 approveme-center">
                            <div class="approveme-image">
                                <p><br> <img src="<?php echo plugins_url('../assets/images/nf-add-on-step-1.png', __FILE__); ?>" class="esig-ninja-pic-border"  width="100%">

                                </p>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- End Step 1 -->
                <!-- Start Step 2 -->
                <div class="approveme-feature-section approveme-table">
                    <div class="approveme-row">
                        <div class="approveme-column-2">
                            <div class="approveme-image">
                                <p><br> <img src="<?php echo plugins_url('../assets/images/nf-add-on-step-2.jpg', __FILE__); ?>" class="esig-ninja-pic-border" width="100%"></p>
                            </div>
                        </div>

                        <div class="approveme-column-2">
                            <h4><span class="esig-step">2</span> Enter your ApproveMe license</h4>
                            <p>Once you have purchased a business license from ApproveMe you will need to <a href="admin.php?page=esign-licenses-general">enter your license key</a> to activate updates and support.</p>

                            <p>To download your license key you can log into your <a href="https://www.approveme.com/purchase-history?utm_source=wprepo&utm_medium=link&utm_campaign=ninja-forms">ApproveMe account here</a> (password was emailed with your receipt)</p>

                        </div>
                    </div>

                </div>
                <!-- End Step 2 -->
                <!-- Start Step 3 -->
                <div class="approveme-feature-section approveme-table">
                    <div class="approveme-row">
                        <div class="approveme-column-2">
                            <h4><span class="esig-step">3</span> Create a blank WordPress page</h4>
                            <p>After the ApproveMe plugins are installed and your <a href="admin.php?page=esign-settings">settings saved</a> you will need to create a blank/empty WordPress page for each Stand Alone Document (or agreement) that you will be creating.</p>

                            <div class="approveme-btn"> <p class="esig-mini-btn"><a href="post-new.php?post_type=page" class="esig-mini-btn">Create a Blank Page Now</a></p></div>

                            <p>To learn about Stand Alone Documents you can visit this <a href="https://www.approveme.com/wp-digital-signature-plugin-docs/faq/basic-document-vs-stand-alone-document/?utm_source=wprepo&utm_medium=link&utm_campaign=ninja-forms">helpful article</a></p>
                        </div>

                        <div class="approveme-column-2 approveme-center">
                            <div class="approveme-image">
                                <p><br> <img src="<?php echo plugins_url('../assets/images/nf-add-on-step-3.png', __FILE__); ?>" class="esig-ninja-pic-border" width="100%">

                                </p>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- End Step 3 -->
                <!-- Start Step 4 -->
                <div class="approveme-feature-section approveme-table">
                    <div class="approveme-row">
                        <div class="approveme-column-2">
                            <div class="approveme-image">
                                <p><br> <img src="<?php echo plugins_url('../assets/images/nf-add-on-step-4.png', __FILE__); ?>" class="esig-ninja-pic-border" width="100%"></p>
                            </div>
                        </div>

                        <div class="approveme-column-2">
                            <h4><span class="esig-step">4</span> Create a Stand Alone Document</h4>
                            <p>Ok you're doing great! You're almost there... now that you've created a blank WordPress page we need to create a Stand Alone Document and then attach that WordPress page to our Stand Alone Document.</p>

                            <div class="approveme-btn"><p class="esig-mini-btn"><a href="edit.php?post_type=esign&page=esign-add-document&esig_type=sad" class="esig-mini-btn" target="_blank">Create Stand Alone Doc Now</a></p></div>

                            <p>To learn more about Stand Alone Documents checkout the <a href="https://www.approveme.com/wp-digital-signature-plugin-docs/article/stand-alone-documents-add-on/?utm_source=wprepo&utm_medium=link&utm_campaign=ninja-forms">documentation page</a> for this add-on.</p>

                        </div>
                    </div>

                </div>
                <!-- End Step 4 -->
                <!-- Start Option 1 -->
                <div class="approveme-feature-section approveme-table">
                    <div class="approveme-row">
                        <div class="approveme-column-2">
                            <h4><span class="esig-step">5a</span> (optional) Insert Ninja Form User Data into a Contract</h4>
                            <p>This step will vary based on your desired outcome.  Digital signature by Approve Me is triggered when a Ninja Form is submitted. This add-on gives you the ability to integrate Ninja Form user data (that was inputted from the user when the form was submitted) into a new contract that will either be emailed to the user or will be displayed to the user after they submit you form.</p>


                            <p>To insert data into a Ninja Data, you will need the <a href="https://www.approveme.com/downloads/signer-input-fields/?utm_source=wprepo&utm_medium=link&utm_campaign=ninja-forms">Custom Fields</a> add-on by ApproveMe <em>(Ultimate, Business or Pro license required).</em></p>

                            <p><strong>How to add form fields data into a contract using Ninja Forms</strong><br><ol><li>Navigate to the Signer Input Fields/Custom Fields icon and select "Ninja Form Data".</li>


                                <li>Select the ninja Form that you have already created (and would like to connect to your contract) using the dropdown menu.</li><li>Choose the field data that you would like to insert into your new contract.</li></ol></p>



                            <p>A shortcode will be generated with this information.  Don't worry though, the actual field value that your user enters will be displayed in its place once they signing your contract.</p>
                            <p><em>This is an example of the auto-generated shortcode that will get inserted into the document you are creating after you select your desired field data from your  Ninja Form.</em> <br><span class="nf-shortcode-wrap">[esigninja formid="3" field_id="1" ]</span></p>

                            <p>You can move the shortcode that gets generated automatically, anywhere in your contract and it will display the user's submitted data wherever you have the shortcode located in your contract.</p>

                        </div>

                        <div class="approveme-column-2 approveme-center">
                            <div class="approveme-image">
                                <p><br> <img src="<?php echo plugins_url('../assets/images/nf-add-on-step-5a.jpg', __FILE__); ?>" class="esig-ninja-pic-border" width="100%">

                                    <img src="<?php echo plugins_url('../assets/images/nf-add-on-5a-1.png', __FILE__); ?>" class="esig-ninja-pic-border" width="100%">

                                    <img src="<?php echo plugins_url('../assets/images/nf-add-on-5a-2.png', __FILE__); ?>" class="esig-ninja-pic-border" width="100%">

                                </p>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- End Option 1 -->
                <!-- Start Option 2 -->
                <div class="approveme-feature-section approveme-table">
                    <div class="approveme-row">
                        <div class="approveme-column-2">
                            <div class="approveme-image">
                                <p><br> <img src="<?php echo plugins_url('../assets/images/nf-add-on-5a-3.png', __FILE__); ?>" class="esig-ninja-pic-border" width="100%"></p>
                            </div>
                        </div>

                        <div class="approveme-column-2">
                            <h4><span class="esig-step">5b</span> Choose your document options and save settings</h4>
                            <p>After you have entered all of your desired contract text and have connected your desired Ninja Forms field results to your Stand Alone Document, you are ready to choose/define your document options.</p>  <p><em><strong>Please Note:</strong> Before you can publish your contract, you will also need to connect your Stand Alone Document with the blank WordPress page you created earlier for your contract. You can do so by searching for and selecting the page from the "Display on this page" dropdown menu located under the "Document Options" section.</em></p>


                        </div>
                    </div>

                </div>
                <!-- End Option 2 -->
                <!-- Start Step 3 -->
                <div class="approveme-feature-section approveme-table">
                    <div class="approveme-row">
                        <div class="approveme-column-2">
                            <h4><span class="esig-step">6</span> Almost there... you're 50% complete</h4>
                            <p>Once you've published your Stand Alone Document you are 50% complete.  The next step is you will need to choose your "trigger" and "action" options for this document in ninja forms.</p>


                            <p>Click <strong style="color: #fff;
                                             background-color: #d2010c;
                                             padding: 5px;
                                             margin: 0 10px;
                                             text-transform: uppercase;
                                             font-weight: 400;">Let's Go Now!</strong> to define those last Ninja Form settings.</p>
                        </div>

                        <div class="approveme-column-2 approveme-center">
                            <div class="approveme-image">
                                <p><br> <img src="<?php echo plugins_url('../assets/images/nf-add-on-step-6.png', __FILE__); ?>" class="esig-ninja-pic-border" width="100%">

                                </p>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- End Step 3 -->
                <!-- Start Step 4 -->
                <div class="approveme-feature-section approveme-table">
                    <div class="approveme-row">
                        <div class="approveme-column-2">
                            <div class="approveme-image">
                                <p><br> <img src="<?php echo plugins_url('../assets/images/nf-add-on-step-7.png', __FILE__); ?>" class="esig-ninja-pic-border" width="100%"></p>
                            </div>
                        </div>

                        <div class="approveme-column-2">
                            <h4><span class="esig-step">7</span> Finish setting up the Ninja Form workflow/trigger</h4>
                            <p>You're so close to being finished!  Now you just need to choose your contract and the your desired actions for when this workflow is triggered.</p>

                            <p>Navigate to the "Forms" tab found in the WordPress dashboard.</p>
                            <p>Choose the form that you attached the Stand Alone Document.</p>
                            <p> Select the "WP E-Signature" option found in the form settings and define the remainder settings.</p>
                            <p>That's it!</p>


                            <p>To view a live Ninja to WP E-Signature demo you can check one out at:</p>  
                            <div align="center"> <div class="approveme-btn"><a class="approveme-demo-link" href="http://www.secure.approveme.com/demo/?utm_source=wprepo&utm_medium=link&utm_campaign=ninja-forms">Try a Live Demo of this Integration</a></div></div>
                            <br>
                        </div>
                    </div>

                </div>
                <!-- End Step 4 -->


                <h2>Still got questions? Login to your account at <br> <a href="https://www.approveme.com/?utm_source=wprepo&utm_medium=link&utm_campaign=ninja-forms">www.approveme.com</a></h2>
            </div>
        </div>



        <div class="clear"></div></div><!-- wpbody-content -->
    <div class="clear"></div></div>


<!-----------------approveme snip load here ------------------------>
<div id="approveme-iframe-wrapper">

    <script>

        if (window.addEventListener) {
            window.addEventListener("message", approvemehandlePostMessages, false);
        } else {
            window.attachEvent('onmessage', approvemehandlePostMessages);
        }
        function approvemehandlePostMessages(event) {
            var event_data = {}
            try {
                var event_data = JSON && JSON.parse(event.data) || $.parseJSON(event.data);
            } catch (err) {
                return;
            }
            if (event_data.type == "approveme-iframe-close-request") {
                jQuery("#approve-me-bar").remove();
            }

        }

    </script>
    <!------------------- https://www.approveme.com/apps/snip/ --------------->
    <iframe name="approveme-snify" src="https://www.approveme.com/apps/snip/?name=Caldera Forms" id="approve-me-bar" style="width: 889px; opacity: 1;
            left: 0px; top: auto; display: block; visibility: visible; border: 0px;
            background: rgba(0, 0, 0, 0); border-radius: 0px; box-shadow: none; color: rgb(0, 0, 0);
            cursor: auto; margin: 0px; line-height: normal; max-width: none; max-height: none; min-width: 0px;
            min-height: 0px; outline-width: 0px; padding: 0px; pointer-events: auto; z-index: 2147483647;
            zoom: 1; height: 107px; position: fixed; bottom: 0px; right: auto;">
    </iframe>
</div>

<!---------------------- Approveme snip loads end here  ----------------------------->
