<?php if ($_POST): ?>
    <div class="notice notice-success is-dismissible"><p>Your setting has been saved</p></div>
<?php endif; ?>

<div id="kirimemail">
    <div id="keheading">
        <h1 class="kebrand">
            <img src="<?php echo esc_attr($logo); ?>" alt="KIRIM.EMAIL" height="50">
        </h1>
        <ul class="kenav">
            <li class="help">
                <!-- go to tutorial -->
                <a href="#" target="_blank">Help</a>
            </li>
            <li>
                <a href="#" id="show-kesetting">Account Settings</a>
            </li>
        </ul>
    </div>
    <div id="kebody">
        <div id="kesetting" class="hidden">
            <h2 class="ketitle">
                Account Settings
                <span id="close-kesetting" class="keclose">Close</span>
            </h2>
            <form method="post" novalidate="novalidate">
                <div class="keform-row">
                    <label>Username</label>
                    <div class="kefield">
                        <input type="text" class="keform-input" name="api_username" id="api_username"
                               value="<?php echo esc_attr(get_option('ke_wpform_api_username')); ?>"/>
                        <div class="keform-hint">Fill your KIRIM.EMAIL Username here</div>
                    </div>
                </div>
                <div class="keform-row">
                    <label>API Token</label>
                    <div class="kefield">
                        <input type="text" class="keform-input" name="api_token" id="api_token"
                               value="<?php echo esc_attr(get_option('ke_wpform_api_token')); ?>"/>
                        <div class="keform-hint">
                            Fill your KIRIM.EMAIL API Token here.
                            <!-- go to tutorial -->
                            <a href="<?php echo esc_attr(KIRIMEMAIL_APP_URL); ?>account/tokenconfig" target="_blank">How
                                to get
                                API Token?</a>
                        </div>
                    </div>
                </div>
                <div class="keform-row kebutton-box">
                    <div class="kefield">
                        <button type="submit" class="kebutton">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
        <div id="kecontent">
            <!-- show this if user aren't connect -->
            <div class="keblank-state keblank-account <?php echo ($page == 'no-account') ? '' : 'hidden'; ?>">
                <img src="<?php echo esc_attr($alert); ?>" alt="Not Connected" width="64">
                <h3>You aren't connected to KIRIM.EMAIL yet!</h3>
                <p>Please connect your KIRIM.EMAIL account first <br> via <strong>"Account Settings"</strong></p>
            </div>
            <!-- show this if user don't have any form -->
            <div class="keblank-state keblank-form <?php echo ($page == 'no-form') ? '' : 'hidden'; ?>">
                <img src="<?php echo esc_attr($blank); ?>" alt="Not Connected" width="64">
                <h3>You don't have any form yet!</h3>
                <p>Your form list will be shown here</p>
                <!--<a href="#" class="kebutton">Create a New Form</a>-->
            </div>
            <!-- show this if user have form -->
            <?php add_thickbox(); ?>
            <div class="kepanel <?php echo ($page == 'form') ? '' : 'hidden'; ?>">
                <div class="kepanel-heading">
                    <h3>Manage Form - Set Default Form</h3>
                    <!--<a href="#" class="kebutton">Create a New Form</a>-->
                </div>
                <div class="kepanel-body">
                    <table id="ke-table" class="ketable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Views</th>
                            <th>Submit</th>
                            <th>Popup</th>
                            <th>Bar</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script id="template-popup" type="text/template">
    <label class="ketoggle">
        <input type="checkbox" name="popup[]" value="%url%" data-object="widget" %checked%/>
        <span class="active">On</span>
        <span class="disabled">Off</span>
    </label>
</script>

<script id="template-bar" type="text/template">
    <label class="ketoggle">
        <input type="checkbox" name="bar[]" value="%url%" data-object="bar" %checked%/>
        <span class="active">On</span>
        <span class="disabled">Off</span>
    </label>
</script>

<script id="template-action" type="text/template">
    <a data-url="<?php echo esc_attr(KIRIMEMAIL_APP_URL . 'forms/edit/'); ?>%id%?embed=1&TB_iframe=true&width=%width%&height=%height%"
       class="kebutton button" id="kebutton" data-title="Edit Form" data-caption="Edit Form">Edit</a>
</script>

<!-- KIRIM EMAIL CONTENT END -->
