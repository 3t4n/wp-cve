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
                        <li><span>Mehr als <strong>400.000</strong> Videos</span></li>
                        <li><span><strong>5.000.000</strong> Mitglieder</span></li>
                        <li><span><strong>25.000</strong> geile <strong>Amateure</strong></span></li>
                        <li><span><strong>Täglich neuer</strong> Content</span></li>
                    </ul>
                </div>
            </div>

            <div class="mdh-row-content">
                <div class="mdh-side-profile">
                    <img src="<?echo $this->profile_pic_link?>" title="Register Now" alt="MyDirtyHobby.com">
                </div>
                <div class="mdh-reg-tabs">
                    <div class="mdh-tab-header">
                        <p><strong> Kostenloses Profil</strong>anlegen</p>
                    </div>
                    <div id="mdh-reg-form">
                        <form id="form_register" enctype="application/x-www-form-urlencoded" action="<?php echo $this->form_action; ?>" method="post">
                            <input type="hidden" name="country" value="de">
                            <input type="hidden" name="amateur_profile" value="<?php echo $this->redirect_link; ?>">
                            <div class="mdh-form-input">
                                <div class="mdh-form-control">
                                    <input type="text" name="username" placeholder="Benutzername">
                                </div>
                                <div class="mdh-form-control">
                                    <input id="mdh-pass-input" type="password" name="password" placeholder="Passwort" maxlength="32">
                                    <span id="mdh-pass-toggle" class="dashicons dashicons-visibility"></span>
                                </div>
                                <div class="mdh-form-control">
                                    <input type="text" name="email" placeholder="Email">
                                </div>
                                <div class="mdh-form-control">
                                    <select name="gender" class="mdh-form-control">
                                        <option value=" ">Geschlecht</option>
                                        <option value="M">Mann</option>
                                        <option value="F">Frau</option>
                                        <option value="P">Paar(m,w)</option>
                                        <option value="PMM">Paar(m,m)</option>
                                        <option value="PWW">Paar(w,w)</option>
                                        <option value="SM">Shemales</option>
                                        <option value="TS">Transsexuell</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <input type="submit" id="mdh-reg-button" name="send" value="Register" class="mdh-reg-button" data-optimized-track="Register">
                            </div>
                        </form>

                        <div class="mdh-small-print">
                            <p class="mdh-txt-small">
                                Durch einen Klick auf "Registrieren" bestätigen Sie, die <a href="https://cdn1-l-ha-e11.mdhcdn.com/u/TermsofUse_de.pdf" class="" target="_blank">Nutzungsbedingungen</a> und die <a href="https://www.mydirtyhobby.com/legal/privacy" class="" target="_blank">Datenschutzrichtlinie</a> zur Kenntnis genommen zu haben und erklären sich mit deren Geltung einverstanden.            </p>
                            <p class="mdh-txt-small">Bitte besuchen Sie <a href="https://epoch.com/de/billing_support" target="_blank">Epoch</a>, unseren autorisierten Vertriebspartner</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
