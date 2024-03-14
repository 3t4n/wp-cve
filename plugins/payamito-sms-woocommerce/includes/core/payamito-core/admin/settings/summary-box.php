<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
add_action( 'kianfr_options_after_header', function () { ?>
    <div class="kainfr_after_header kainfar-wrapper">
        <div class="payamito payamito-summary">
            <div class="container">

                <div class="row">
                    <div class="col-md-3 pt-2  align-self-center">
                        <img width="125" src="<?php
						echo PAYAMITO_URL . "/assets/images/sms.gif" ?>" class="img-fluid ms-4" alt="payamito">
                        <h6><?php
							esc_html_e( "Payamito is the creator of intelligent SMS communication ", 'payamito' ) ?></h6>
                    </div>

                    <div class="col-md-2 align-self-center">
                        <p id="payamito_crediet" class="fs-4 mb-0"></p>
                        <p class="text-black-50 fs-6"><?php
							esc_html_e( "Crediet", 'payamito' ) ?> </p>
                    </div>

                    <div class="col-md-3 align-self-center">
                        <div class="row mt-3">
                            <div class="col-12">
                                <p><?php
									esc_html_e( 'Sended statistics', 'payamito' ); ?></p>
                            </div>
                            <div class="col-6 col-sm-6 mt-1">
                                <p id="payamito_all" class="fs-6 mb-0" style="color: #333 !important;">*</p>
                                <p class="text-black-50"><?php
									esc_html_e( "All", 'payamito' ) ?></p>
                            </div>

                            <div class="col-6 col-sm-6 mt-1">
                                <p id="payamito_today" class="fs-6 mb-0" style="color: #333 !important;">*</p>
                                <p class="text-black-50"><?php
									esc_html_e( "Today", 'payamito' ) ?></p>
                            </div>

                            <div class="col-6 col-sm-6 mt-1">
                                <p id="payamito_7days" class="fs-6 mb-0" style="color: #333 !important;">*</p>
                                <p class="text-black-50"><?php
									esc_html_e( "7 Days ", 'payamito' ) ?></p>
                            </div>

                            <div class="col-6 col-sm-6 mt-1">
                                <p id="payamito_30days" class="fs-6 mb-0" style="color: #333 !important;">*</p>
                                <p class="text-black-50"><?php
									esc_html_e( "30 Days", 'payamito' ) ?></p>
                            </div>

                        </div>
                    </div>

                    <div class="col-md-4 align-self-center">
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-6 col-md-3"><a href="https://instagram.com/payamito_sms"><img
                                                    src="<?php
													echo PAYAMITO_URL . "/assets/images/instagram.png" ?>" alt=""
                                                    width="100"></a></div>
                                    <div class="col-6 col-md-3"><a href="https://t.me/payamito"><img src="<?php
											echo PAYAMITO_URL . "/assets/images/telegram.png" ?>" alt=""
                                                                                                     width="100"></a>
                                    </div>
                                    <div class="col-6 col-md-3"><a href="https://www.youtube.com/payamito_sms"><img
                                                    src="<?php
													echo PAYAMITO_URL . "/assets/images/youtube.png" ?>" alt=""
                                                    width="100"></a></div>
                                    <div class="col-6 col-md-3"><a href="http://aparat.com/payamito"><img src="<?php
											echo PAYAMITO_URL . "/assets/images/aparat.png" ?>" alt="" width="100"></a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="row align-items-center">
                                    <div class="btn-group" role="group">
                                        <a href="https://payamito.com/login/" type="button"
                                           class="btn btn-outline-secondary" target="_blank"><?php
											_e( 'My account', 'payamito' ) ?></a>
                                        <a href="https://payamito.com/login/" type="button"
                                           class="btn btn-outline-secondary" target="_blank"><?php
											_e( 'Charge panel', 'payamito' ) ?></a>
                                        <a href="https://panel.payamito.com/?module=Ticketing" type="button"
                                           class="btn btn-outline-secondary" target="_blank"><?php
											_e( 'support ticket', 'payamito' ) ?></a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
	<?php
} ) ?>