<div class="modal fade " tabindex="-1" role="dialog" id="signUpModal">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="loader" style="display: none">
                <div class="lds-dual-ring"></div>
            </div>
            <div class="style-block">
                <div class="form-block">
                    <div>
                        <div class="form-title">Welcome to ConveyThis!</div>
                        <div class="form-subtitle">Enter your email to get api key</div>
                    </div>
                    <div class="register-form" id="register-div">
                        <form id="register_form" method="post" style="" autocomplete="off">
                            <div class="d-flex reg-input">
                                <input class="style-input modal-email-get-start" name="email" type="email" placeholder="Email Address" required="" autocomplete="new-password">
                            </div>
                            <div class="form-group mt-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input me-2" id="agree" name="i-agree" value="1">
                                    <label for="agree">I agree to the <a href="//app.conveythis.com/legal/terms-and-conditions/" target="_blank" class="grey-link">ConveyThis terms</a>.</label>
                                </div>
                            </div>
                            <button class="style-btn modal-get-start" type="submit">Register Now</button>
                        </form>
                    </div>
                </div>
                <div class="img-block"><img src="<?php echo CONVEY_PLUGIN_DIR.'/app/widget/images/img-rocket.jpg' ?>" alt="ConveyThis"></div>
            </div>
        </div>
    </div>
</div>