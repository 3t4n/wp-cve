<?php
$isEsignatureActive  = (function_exists("WP_E_Sig")) ? true : false;
$isEsigAndProActive = (function_exists("WP_E_Sig") && class_exists("ESIG_SAD_Admin")) ? true : false;
?>

<!-- Start Step 1 -->
<div class="approveme-feature-section approveme-table">
    <div class="approveme-row">
        <div class="approveme-column-2">
            <h4><span class="esig-step">1</span> Download and Install the required plugins</h4>
            <p>Congrats on installing our Digital Signature Formidable Forms Add-on. You're almost ready! ApproveMe's WP E-Signature requires a few more plugins/add-ons so you can unlock the power and automate Formidable Forms and legally binding Digital Signatures. You'll need to download the following:</p>

            <p>1. <a href="https://wordpress.org/plugins/formidable/" target="_blank">Formidable Forms</a><br>2. <a href="https://www.approveme.com/wp-e-signature/?utm_source=wprepo&utm_medium=link&utm_campaign=formidable-forms">WP E-Signature</a> <em>(Elite,Plus license)<br> </em>3. WP E-Signature Add-ons Pack <em>(included with the Elite and Plus license)</em></p>
        </div>

        <div class="approveme-column-2 approveme-center">
            <div class="approveme-image">
                <p><br> <img src="<?php echo esc_url(plugins_url('../assets/images/formidableform-add-on-step-1.png', __FILE__)); ?>" class="esig-formidable-pic-border" width="100%">

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
                <p><br> <img src="<?php echo esc_url(plugins_url('../assets/images/formidableform-add-on-step-2.jpg', __FILE__)); ?>" class="esig-formidable-pic-border" width="100%"></p>
            </div>
        </div>

        <div class="approveme-column-2">
            <h4><span class="esig-step">2</span> Enter your ApproveMe license</h4>
            <?php $licenseUrl =  ($isEsignatureActive) ? '<a href="admin.php?page=esign-licenses-general">enter your license key</a>' : 'enter your license key'; ?>
            <p>Once you have purchased an Elite or Plus licenses from ApproveMe, you will need to <?php echo $licenseUrl; ?> .</p>

            <p>To view your license key you can log in to your <a href="https://www.approveme.com/sign-in/?utm_source=wprepo&utm_medium=link&utm_campaign=formidable-forms" target="_blank">ApproveMe account here</a> (password was emailed with your receipt)</p>

        </div>
    </div>

</div>
<!-- End Step 2 -->
<!-- Start Step 3 -->
<div class="approveme-feature-section approveme-table">
    <div class="approveme-row">
        <div class="approveme-column-2">
            <h4><span class="esig-step">3</span> Create a blank WordPress page</h4>
            <?php $settingsUrl =  ($isEsignatureActive) ? '<a href="admin.php?page=esign-settings">settings are saved</a>' : 'settings are saved'; ?>
            <p>After your WP E-Signature plugins are installed and your <?php echo $settingsUrl; ?>, you will need to create a blank WordPress page for each stand alone document that you will be creating. To start, create one blank WordPress page, give it a title, and publish it with the content blank.</p>

            <?php if ($isEsigAndProActive) : ?>
                <div class="approveme-btn">
                    <p class="approveme-mini-btn"><a href="post-new.php?post_type=page" class="approveme-mini-btn outlined" target="_blank">Create a Blank Page Now</a></p>
                </div>
            <?php endif; ?>
            <p>To learn about stand alone documents, you can visit this <a href="https://wpe.approveme.com/article/156-basic-document-vs-stand-alone-document/?utm_source=wprepo&utm_medium=link&utm_campaign=formidable-forms" target="_blank">helpful article</a></p>
        </div>

        <div class="approveme-column-2">
            <div class="approveme-image">
                <p><br> <img src="<?php echo esc_url(plugins_url('../assets/images/formidableform-add-on-step-3.png', __FILE__)); ?>" class="esig-formidable-pic-border" align="left" width="100%">

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
                <p><br> <img src="<?php echo esc_url(plugins_url('../assets/images/formidableform-add-on-step-4.png', __FILE__)); ?>" class="esig-formidable-pic-border" width="100%"></p>
            </div>
        </div>

        <div class="approveme-column-2">
            <h4><span class="esig-step">4</span> Create a Stand Alone Document</h4>
            <p>Ok you're doing great! You're almost there... now that you've created a blank WordPress page we need to create a Stand Alone Document and then attach that WordPress page to our stand alone document.</p>

            <?php if ($isEsigAndProActive) : ?>
                <div class="approveme-btn">
                    <p class="approveme-mini-btn approveme-center"><a href="edit.php?post_type=esign&page=esign-add-document&esig_type=sad" class="approveme-mini-btn outlined" target="_blank">Create Stand Alone Doc Now</a></p>
                </div>
            <?php endif; ?>

        </div>
    </div>

</div>
<!-- End Step 4 -->
<!-- Start Option 1 -->
<div class="approveme-feature-section approveme-table">
    <div class="approveme-row">
        <div class="approveme-column-2">
            <h4><span class="esig-step">5a</span> (optional) Insert Formidable Form User Data into a Contract</h4>
            <p>This step will vary based on your desired outcome. Digital signature by Approve Me is triggered when a Formidable Form is submitted. This add-on gives you the ability to integrate formidable Form user data (that was inputted from the user when the form was submitted) into a new contract that will either be emailed to the user or will be displayed to the user after they submit you form.</p>


            <p>To insert data into a Formidable Data, you will need the <a href="https://www.approveme.com/downloads/signer-input-fields/?utm_source=wprepo&utm_medium=link&utm_campaign=formidable-forms">Custom Fields</a> add-on by ApproveMe <em>(ultimate, business or pro license required).</em></p>

            <p><strong>How to add form fields data into a contract using Formidable Forms</strong><br>
            <ol>
                <li>Navigate to the Signer Input Fields/Custom Fields icon and select "Formidable Form Data".</li>


                <li>Select the Formidable Form that you have already created (and would like to connect to your contract) using the dropdown menu.</li>
                <li>Choose the field data that you would like to insert into your new contract.</li>
            </ol>
            </p>



            <p>A shortcode will be generated with this Formidable form formormation. Don't worry though, the actual field value that your user enters will be displayed in its place once they signing your contract.</p>
            <p><em>This is an example of the auto-generated shortcode that will get inserted into the document you are creating after you select your desired field data from your formidable Form.</em> <br><span class="formidable-shortcode-wrap">[esigformidable formid="3" field_id="1" ]</span></p>

            <p>You can move the shortcode that gets generated automatically, anywhere in your contract and it will display the user's submitted data wherever you have the shortcode located in your contract.</p>
            <img src="<?php echo esc_url(plugins_url('../assets/images/gf-team-contracts.png', __FILE__)); ?>" class="gf-team-contracts-img" alt="Team Contracts" />

        </div>

        <div class="approveme-column-2 approveme-center">
            <div class="approveme-image">
                <p><br> <img src="<?php echo esc_url(plugins_url('../assets/images/formidableform-add-on-step-5a.jpg', __FILE__)); ?>" class="esig-formidable-pic-border" width="100%">

                    <img src="<?php echo esc_url(plugins_url('../assets/images/formidableform-add-on-5a-1.png', __FILE__)); ?>" class="esig-formidable-pic-border" width="100%">

                    <img src="<?php echo esc_url(plugins_url('../assets/images/formidableform-add-on-5a-2.png', __FILE__)); ?>" class="esig-formidable-pic-border" width="100%">

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
                <p><br> <img src="<?php echo esc_url(plugins_url('../assets/images/formidableform-add-on-5a-3.png', __FILE__)); ?>" class="esig-formidable-pic-border" width="100%"></p>
            </div>
        </div>

        <div class="approveme-column-2">
            <h4><span class="esig-step">5b</span> Choose your document options and save settings</h4>
            <p>After you have entered all of your desired contract text and have connected your desired Formidable Forms field results to your Stand Alone Document, you are ready to choose/define your document options.</p>
            <p><em><strong>Please Note:</strong> Before you can publish your contract, you will also need to connect your Stand Alone Document with the blank WordPress page you created earlier for your contract. You can do so by searching for and selecting the page from the "Display on this page" dropdown menu located under the "Document Options" section.</em></p>


        </div>
    </div>

</div>
<!-- End Option 2 -->
<!-- Start Step 3 -->
<div class="approveme-feature-section approveme-table">
    <div class="approveme-row">
        <div class="approveme-column-2">
            <h4><span class="esig-step">6</span> Almost there... you're 50% complete</h4>
            <p>Once you've published your Stand Alone Document you are 50% complete. The next step is you will need to choose your "trigger" and "action" options for this document in formidable forms.</p>


            <p style="line-height: 2;">Click <span ctyle="margin-bottom:5px;"><strong style="color: #fff;
                                             background-color: #d2010c;
                                             padding: 5px;
                                             margin: 0 10px;
                                             text-transform: uppercase;
                                             font-weight: 400;">Let's Go Now!</strong> </span>to define those last Formidable Forms action. If you do not see this screen, you can open your Formidable form in editing mode, visit the form action tab, and create e-signature action.</p>
            <img src="<?php echo esc_url(plugins_url('../assets/images/gf-completed-steps.png', __FILE__)); ?>" class="gf-completed-steps-img" alt="Completed Steps" />
        </div>

        <div class="approveme-column-2">
            <div class="approveme-image">
                <p><br> <img src="<?php echo esc_url(plugins_url('../assets/images/formidableform-add-on-step-6.png', __FILE__)); ?>" class="esig-formidable-pic-border" align="left" width="100%">

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
                <p><br> <img src="<?php echo esc_url(plugins_url('../assets/images/formidableform-add-on-step-7.png', __FILE__)); ?>" class="esig-formidable-pic-border" width="100%"></p>
            </div>
        </div>

        <div class="approveme-column-2">
            <h4><span class="esig-step">7</span> Finish setting up the Formidable Form workflow/trigger</h4>
            <p>You're so close to being finished! Now you just need to choose your contract and the your desired actions for when this workflow is triggered.</p>

            <p>Navigate to the "Forms" tab found in the WordPress dashboard.</p>
            <p>Choose the form that you attached the Stand Alone Document.</p>
            <p>Select "Settings"</p>
            <p style="display:inline-block;"><br> <img src="<?php echo esc_url(plugins_url('../assets/images/formidableform-add-on-step-7b.png', __FILE__)); ?>" class="esig-formidable-pic-border" align="left" width="100%">
            </p><br>
            <p>Choose "Form Actions"</p>
            <p style="display:inline-block;"><br> <img src="<?php echo esc_url(plugins_url('../assets/images/formidableform-add-on-step-7c.png', __FILE__)); ?>" class="esig-formidable-pic-border" align="left" width="100%">
            </p><br>
            <p>And Select "E-Signature" option found in the form action settings. Now define the remainder settings.</p>
            <p style="display:inline-block;"><br> <img src="<?php echo esc_url(plugins_url('../assets/images/formidableform-add-on-step-7d.png', __FILE__)); ?>" class="esig-formidable-pic-border" align="left" width="100%">
            </p><br>
            <p>That's it!</p>


            <p>To view a live Formidable to WP E-Signature demo you can check one out at:</p>
            <br>
            <div align="center">
                <div class="approveme-btn"><a class="button-border" href="http://www.secure.approveme.com/demo/?utm_source=wprepo&utm_medium=link&utm_campaign=formidable-forms">Try a Live Demo of this Integration</a></div>
            </div>

        </div>
    </div>

</div>
<!-- End Step 4 -->