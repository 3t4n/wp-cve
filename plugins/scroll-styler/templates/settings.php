<?php require_once(sprintf("%s/ScrollStylerProcess.php", str_replace('/templates', '', dirname(__FILE__)))); ?>

<div class="scroll-styler">

    <div class="scroll-styler__container">

        <div class="scroll-styler__header">
            <div class="scroll-styler__header-title">Scroll Styler</div>
        </div>

        <div class="scroll-styler__body">

            <div class="scroll-styler__main">
                <form class="scroll-styler__form" action="" method="post">

                    <?php if (isset($_POST['scrollStylerOptionsEnabled'])) { ?>
                    <div class="scroll-styler__info-box-container scroll-styler__info-box-container--is-toggle">
                        <div class="scroll-styler__info-box scroll-styler__info-box--success">
                            <div class="scroll-styler__info-box-icon scroll-styler__info-box-icon--success"></div>
                            <div class="scroll-styler__info-box-desc"><?php echo $scrollStylerProcess->getLang('formSuccessDesc'); ?></div>
                        </div>
                    </div>
                    <?php } ?>

                    <div class="scroll-styler__form-info">
                        <span class="scroll-styler__form-info-icon"></span>
                        <span class="scroll-styler__form-info-text"><?php echo $scrollStylerProcess->getLang('formDesc'); ?></span>
                    </div>

                    <div class="scroll-styler__form-body">

                        <div class="scroll-styler__form-row scroll-styler__form-row--px">
                            <div class="scroll-styler__form-label"><?php echo $scrollStylerProcess->getLang('scrollbarWidthLabel'); ?> <span class="scroll-styler__form-label-details"><?php echo $scrollStylerProcess->getLang('minLabel'); ?> 5px | <?php echo $scrollStylerProcess->getLang('maxLabel'); ?> 30px</span></div>
                            <input class="scroll-styler__form-control" id="scroll-styler-scrollbar-width" name="scrollbarWidth" type="number" min="5" max="30" value="<?php echo $scrollStylerProcess->data['scrollbarWidth']; ?>">
                        </div>

                        <div class="scroll-styler__form-row scroll-styler__form-row--px">
                            <div class="scroll-styler__form-label"><?php echo $scrollStylerProcess->getLang('scrollbarTrackPaddingLabel'); ?> <span class="scroll-styler__form-label-details"><?php echo $scrollStylerProcess->getLang('minLabel'); ?> 0px | <?php echo $scrollStylerProcess->getLang('maxLabel'); ?> 5px</span></div>
                            <input class="scroll-styler__form-control" id="scroll-styler-scrollbar-track-padding" name="scrollbarTrackPadding" type="number" min="0" max="5" value="<?php echo $scrollStylerProcess->data['scrollbarTrackPadding']; ?>">
                        </div>

                        <div class="scroll-styler__form-row">
                            <div class="scroll-styler__form-label"><?php echo $scrollStylerProcess->getLang('scrollbarTrackBgColorLabel'); ?></div>
                            <input class="scroll-styler__form-control js-minicolors" id="scroll-styler-scrollbar-track-background-color" name="scrollbarTrackBgColor" type="text" maxlength="7" value="<?php echo $scrollStylerProcess->data['scrollbarTrackBgColor']; ?>">
                        </div>

                        <div class="scroll-styler__form-row">
                            <div class="scroll-styler__form-label"><?php echo $scrollStylerProcess->getLang('scrollbarThumbBgColorLabel'); ?></div>
                            <input class="scroll-styler__form-control js-minicolors" id="scroll-styler-scrollbar-thumb-background-color" name="scrollbarThumbBgColor" type="text" maxlength="25" data-format="rgb" data-opacity="true" value="<?php echo $scrollStylerProcess->data['scrollbarThumbBgColor']; ?>">
                        </div>

                        <div class="scroll-styler__form-row">
                            <div class="scroll-styler__form-label"><?php echo $scrollStylerProcess->getLang('scrollbarThumbBgColorHoverLabel'); ?></div>
                            <input class="scroll-styler__form-control js-minicolors" id="scroll-styler-scrollbar-thumb-background-color-hover" name="scrollbarThumbBgColorHover" type="text" maxlength="25" data-format="rgb" data-opacity="true" value="<?php echo $scrollStylerProcess->data['scrollbarThumbBgColorHover']; ?>">
                        </div>

                        <div class="scroll-styler__form-row">
                            <div class="scroll-styler__form-label"><?php echo $scrollStylerProcess->getLang('scrollbarThumbBgColorActiveLabel'); ?></div>
                            <input class="scroll-styler__form-control js-minicolors" id="scroll-styler-scrollbar-thumb-background-color-active" name="scrollbarThumbBgColorActive" type="text" maxlength="25" data-format="rgb" data-opacity="true" value="<?php echo $scrollStylerProcess->data['scrollbarThumbBgColorActive']; ?>">
                        </div>

                        <div class="scroll-styler__form-row scroll-styler__form-row--px">
                            <div class="scroll-styler__form-label"><?php echo $scrollStylerProcess->getLang('scrollbarThumbBorderRadiusLabel'); ?> <span class="scroll-styler__form-label-details"><?php echo $scrollStylerProcess->getLang('minLabel'); ?> 0px | <?php echo $scrollStylerProcess->getLang('maxLabel'); ?> 15px</span></div>
                            <input class="scroll-styler__form-control" id="scroll-styler-scrollbar-thumb-border-radius" name="scrollbarThumbBorderRadius" type="number" min="0" max="15" value="<?php echo $scrollStylerProcess->data['scrollbarThumbBorderRadius']; ?>">
                        </div>

                    </div>

                    <div class="scroll-styler__form-row scroll-styler__form-row--btn-bar">
                        <input type="hidden" name="scrollStylerOptionsEnabled" value="true">
                        <button class="scroll-styler__btn" type="submit" title="<?php echo $scrollStylerProcess->getLang('saveButtonText'); ?>"><?php echo $scrollStylerProcess->getLang('saveButtonText'); ?></button>
                    </div>
                </form>
            </div>
            
            <div class="scroll-styler__aside">

                <h2 class="scroll-styler__promo-box-main-title">Other Plugins</h2>

                <h3 class="scroll-styler__promo-box-title">WordPress Plugins</h3>

                <div class="scroll-styler__promo-box">
                    <div class="scroll-styler__promo">
                        <a class="scroll-styler__promo-link scroll-styler__promo-link--eav" href="https://1.envato.market/ag5YM" target="_blank">
                            <img class="scroll-styler__promo-img" src="<?php echo  plugins_url(); ?>/scroll-styler/assets/img/elegant-age-verification-responsive-age-gate-plugin-for-wordpress.png" alt="Elegant Age Verification – Responsive Age Checker WordPress Plugin">
                            <span class="scroll-styler__promo-text"><b>Elegant Age Verification</b><br>Responsive age-checker WordPress plugin</span>
                        </a>
                    </div>
                </div>

                <h3 class="scroll-styler__promo-box-title">JavaScript &amp; jQuery Plugins<br><em>NOT for WordPress</em></h3>

                <div class="scroll-styler__promo-box">
                    <div class="scroll-styler__promo">
                        <a class="scroll-styler__promo-link scroll-styler__promo-link--gdpr" href="https://1.envato.market/qRo6n" target="_blank">
                            <img class="scroll-styler__promo-img" src="<?php echo  plugins_url(); ?>/scroll-styler/assets/img/gdpr-cookie-law-responsive-javascript-gdpr-consent-plugin.png" alt="GDPR Cookie Law – JavaScript Cookie Popup Plugin">
                            <span class="scroll-styler__promo-text"><b>GDPR Cookie Law</b><br>JavaScript Cookie Popup Plugin</span>
                        </a>
                    </div>

                    <div class="scroll-styler__promo">
                        <a class="scroll-styler__promo-link scroll-styler__promo-link--gdpr" href="https://1.envato.market/50vqn" target="_blank">
                            <img class="scroll-styler__promo-img" src="<?php echo  plugins_url(); ?>/scroll-styler/assets/img/gdpr-cookie-law-jquery-gdpr-cookie-compliance-plugin.png" alt="GDPR Cookie Law – jQuery Cookie Popup Plugin">
                            <span class="scroll-styler__promo-text"><b>GDPR Cookie Law</b><br>jQuery Cookie Popup Plugin</span>
                        </a>
                    </div>

                    <div class="scroll-styler__promo">
                        <a class="scroll-styler__promo-link scroll-styler__promo-link--elegant-scroll-to-top" href="https://1.envato.market/mg2my1" target="_blank">
                            <img class="scroll-styler__promo-img" src="<?php echo  plugins_url(); ?>/scroll-styler/assets/img/elegant-scroll-to-top-back-to-top-javascript-plugin.png" alt="Elegant Scroll to Top – Back to Top JavaScript Plugin">
                            <span class="scroll-styler__promo-text"><b>Elegant Scroll to Top</b><br> Back to Top JavaScript Plugin</span>
                        </a>
                    </div>

                    <div class="scroll-styler__promo">
                        <a class="scroll-styler__promo-link scroll-styler__promo-link--gdpr" href="https://1.envato.market/j695n" title="Elegant Elements – jQuery HTML Form Plugin" target="_blank">
                            <img class="scroll-styler__promo-img" src="<?php echo  plugins_url(); ?>/scroll-styler/assets/img/elegant-elements-jquery-forms.png" alt="Elegant Elements – jQuery HTML Form Plugin">
                            <span class="scroll-styler__promo-text"><b>Elegant Elements</b><br> jQuery HTML Form Plugin</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>