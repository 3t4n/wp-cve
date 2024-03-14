<div id="mdhRegister-wrap">
    <div id="mdhRegister">
        <div class="mdhRegister-header">
            <span id="mdhRegister-close">x</span>
        </div>
        <div class="mdh-content">

            <div class="mdh-row-head">
                <img class="mdhModal-logo" src="<?php  echo plugins_url( '../img/logo_mdh.png', __FILE__ ); ?>">
                <div class="mdh-header-text">
                    <ul>
                        <li><span>More than <strong>400.000</strong> Videos</span></li>
                        <li><span><strong>5.000.000</strong> members</span></li>
                        <li><span><strong>25.000</strong> horny  <strong>amateurs</strong></span></li>
                        <li><span><strong>Daily new</strong> content</span></li>
                    </ul>
                </div>
            </div>

            <div class="mdh-row-content">
                <div class="mdh-side-profile">
                    <img src="<?echo $this->profile_pic_link?>" title="Register Now" alt="MyDirtyHobby.com">
                </div>
                <div class="mdh-reg-tabs">
                    <div class="mdh-tab-header">
                        <p>REGISTER NOW FOR <strong>FREE</strong></p>
                    </div>
                    <div id="mdh-reg-form">
                        <form id="form_register" enctype="application/x-www-form-urlencoded" action="<?php echo $this->form_action; ?>" method="post">
                            <input type="hidden" name="country" value="de">
                            <input type="hidden" name="amateur_profile" value="<?php echo $this->redirect_link; ?>">
                            <div class="mdh-form-input">
                                <div class="mdh-form-control">
                                    <input type="text" name="username" placeholder="Username">
                                </div>
                                <div class="mdh-form-control">
                                    <input id="mdh-pass-input" type="password" name="password" placeholder="Password" maxlength="32">
                                    <span id="mdh-pass-toggle" class="dashicons dashicons-visibility"></span>
                                </div>
                                <div class="mdh-form-control">
                                    <input type="text" name="email" placeholder="Email">
                                </div>
                                <div class="mdh-form-control">
                                    <select name="gender" class="mdh-form-control">
                                        <option value=" ">Gender</option>
                                        <option value="M">Male</option>
                                        <option value="F">Female</option>
                                        <option value="P">Couple(m,f)</option>
                                        <option value="PMM">Couple(m,m)</option>
                                        <option value="PWW">Couple(f,f)</option>
                                        <option value="SM">Shemales</option>
                                        <option value="TS">Transsexuals</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <input type="submit" id="mdh-reg-button" name="send" value="Register" class="mdh-reg-button" data-optimized-track="Register">
                            </div>
                        </form>

                        <div class="mdh-small-print">
                            <p class="mdh-txt-small">By clicking on "Register" you certify that you have read and agree to abide by the <a href="https://cdn1-l-ha-e11.mdhcdn.com/u/TermsofUse_en.pdf" class="" target="_blank">Terms of Use</a> and the <a href="https://www.mydirtyhobby.com/legal/privacy" class="" target="_blank">Privacy Policy</a>.</p>
                            <p class="mdh-txt-small">Please visit <a href="https://epoch.com/en/billing_support" target="_blank">Epoch</a>, our authorized sales agent</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
