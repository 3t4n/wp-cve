<?php

function hq_contact_form()
{
    ob_start();
    $captcha =  file_get_contents('https://one-switch.us5.hqrentals.app/form/7e913945-a78a-4163-94ac-d94c7cf1d327/get-captcha');
    ?>

    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $("#hq-form").on("submit", function(e){

                console.log("Captcha running...");

                var recaptcha = $("#g-recaptcha-response").val();
                var error = false;
                
                if (recaptcha === "") {
                    error = true;
                    alert("Please check the recaptcha");
                    grecaptcha.reset();
                }

                if (error) {
                    e.preventDefault();
                } else {
                    $('#hq-success').fadeIn();

                    setTimeout(function(){
                        $("#hq-success").fadeOut();
                    }, 5000)
                }
             })

        });
    </script>

    <?php if (get_locale() == 'en_US') : ?>
    <div class="fw-page-builder-content">
        <section id="section-5cf6e66ddb8b2" class="fw-main-row auto ">
            <div>
                <div class="fw-container sections">
                    <div class="fw-row">

                        <div id="column-5cf6e66ddbc3d" class=" fw-col-sm-8 wow fadeInUp animated animated" data-wow-offset="120" data-wow-duration="1.5s" style="visibility: visible; animation-duration: 1.5s; animation-name: fadeInUp;">
                            <div class="fw-main-row">
                                <div class="fw-col-inner">

                                    <div class="fw-heading fw-heading-h1 wow fadeIn animated animated" data-wow-offset="120" data-wow-duration="1.5s" style="visibility: visible; animation-duration: 1.5s; animation-name: fadeIn;">
                                        <div class="fw-special-title-half">
                                            <h1 class="fw-special-title" style="margin-bottom: 10px!important;">Contact Us</h1>
                                        </div>
                                            <h4 style="margin-bottom: 30px!important;">24/7 Dedicated Customer Support</h4>
                                    </div>

                                    <div class="contact fw-contact-form">
                                        <div class="fw-row wrap-forms wrap-contact-forms wow rollIn animated contact-form animated animated" data-wow-offset="10" data-wow-duration="1.55s" style="visibility: visible; animation-duration: 1.55s; animation-name: rollIn;">

                                            <form id="hq-form" method="post" action="https://one-switch.us5.hqrentals.app/form/7e913945-a78a-4163-94ac-d94c7cf1d327?redirect=https://rentcar.rent" class="fw_form_fw_form" data-fw-ext-forms-type="contact-forms" _lpchecked="1">
              
                                                <div class="fw-row">
                                                    <div class="fw-col-xs-12 fw-col-sm-6 form-builder-item">
                                                        <div class="field-text">
                                                            <input class="form-control" type="text" name="field_338" placeholder="First Name" value="" id="id-1" required="required">
                                                        </div>
                                                    </div>
                                                    <div class="fw-col-xs-12 fw-col-sm-6 form-builder-item">
                                                        <div class="field-text">
                                                            <input class="form-control" type="text" name="field_339" placeholder="Last Name" value="" id="id-2" required="required">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="fw-row">
                                                    <div class="fw-col-xs-12 fw-col-sm-6 form-builder-item">
                                                        <div class="field-text">
                                                            <input class="form-control" type="text" name="field_341" placeholder="Phone Number" value="" id="id-3" required="required">
                                                        </div>
                                                    </div>
                                                    <div class="fw-col-xs-12 fw-col-sm-6 form-builder-item">
                                                        <div class="field-text">
                                                            <input class="form-control" type="email" name="field_342" placeholder="Email" value="" id="id-4" required="required">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="fw-row">
                                                    <div class="fw-col-xs-12 form-builder-item">
                                                        <div class="field-textarea">
                                                            <textarea class="form-control" name="field_343" placeholder="Message" id="id-5" required="required"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="fw-row">
                                                    <div class="hq-captcha">
                                                        <?php echo $captcha; ?>
                                                    </div>
                                                </div>
                                                <div class="fw-col-sm-12 field-submit text-center">
                                                    <input type="submit" class="default btn submit-message" value="Submit Message">

                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <div id="hq-success" style="display: none;">
                                        <span>Thank you, your message has been sent succesfully.</span>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div id="column-5cf6e66ddc081" class=" fw-col-sm-4 wow fadeInUp animated animated" data-wow-offset="120" data-wow-duration="1.5s" style="visibility: visible; animation-duration: 1.5s; animation-name: fadeInUp;">
                            <div class="fw-main-row">
                                <div class="fw-col-inner">

                                    <div class="contact">
                                        <h4 class="contact-box-title">Have Questions?</h4>
                                        <div class="contact-box">
                                            
                                            <div class="contact-box-name">Toll Free</div>
                                            <div class="contact-box-phon"><span class="highlight">Phone:</span><a href="tel:+18664369044"> +1 (866) 436-9044</a></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                    </div>

                                    <div class="contact">
                                        <div class="contact-box">
                                            <div class="contact-box-name">Venezuela</div>
                                            <div class="contact-box-phon"><span class="highlight">Phone:</span> <a href="tel:+582617700424"> +58 (261) 770-0424</a> / <a href="tel:+582123354993">+58 (212) 335-4993</a></div>
                                            <div class="contact-box-email"><span class="highlight">WhatsApp:</span><a href="https://api.whatsapp.com/send?phone=584146568149" target="_blank"> +58 (414) 656-8149</a></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        <div class="contact-box">
                                            <div class="contact-box-name">USA</div>
                                            <div class="contact-box-email"><span class="highlight">WhatsApp:</span><a href="https://api.whatsapp.com/send?phone=13055875313" target="_blank"> +1 (305) 587-5313</a></div>
                                            <div class="contact-box-email"><span class="highlight">WhatsApp:</span><a href="https://api.whatsapp.com/send?phone=13059886125" target="_blank"> +1 (305) 988-6125</a></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        <div class="contact-box">
                                            <div class="contact-box-name">Colombia</div>
                                            <div class="contact-box-phon"><span class="highlight">Phone:</span><a href="tel:+57(1)5085112"> +57 (1) 508-5112</a></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="contact-box-border">&nbsp;</div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- Begin Locations-->
                    <div class="fw-row" style="margin-top: 30px;">
                        <div id="column-as8d7as7d" class="fw-col-sm-4 wow fadeInUp animated animated" data-wow-offset="120" data-wow-duration="1.5s" style="visibility: visible; animation-duration: 1.5s; animation-name: fadeInUp;">
                            <div class="fw-main-row">
                                <div class="fw-col-inner">
                                    <div class="contact">
                                        <div class="contact-box">
                                            <div class="contact-box-name">Miami</div>
                                            <div style="margin: 10px 0;"><a href="http://maps.google.com/maps?q=3256+NW+24th+Street+Rd%2c+Miami%2c+FL%2c+33142%2c+United+States+(MIAMI)" target="_blank"><span>3256 NW 24th Street Rd <br>Miami FL, 33142 <br> United States</span></a></div>
                                            <div class="contact-box-phon"><span class="highlight">Phone:</span><a href="tel:+13054707556"> +1 (305) 470 7556</a></div>
                                            <div class="contact-box-phon"><span class="highlight">Toll-Free:</span><a href="tel:+18664369044"> +1 (866) 436-9044</a></div>
                                            <div class="contact-box-phon"><span class="highlight">WhatsApp:</span><a href="https://api.whatsapp.com/send?phone=17868622219" target="_blank"> +1 (786) 862-2219</a></div>
                                            <div class="contact-box-phon"><span class="highlight">Fax:</span><a href="tel:+17866521536"> +1 (786) 652-1536</a></div> 
                                            <div class="contact-box-email"><span class="highlight">E-mail:</span><a href="mailto:help@rentcar.rent"> help@rentcar.rent</a><br><a href="mailto:ayuda@rentcar.rent">ayuda@rentcar.rent</a></div>
                                            <div class="contact-box-phon" style="margin-top: 20px"><strong>Opening Hours</strong></div>
                                            <div class="hours">Mon - Sun 06:00AM - 11:00PM</div>
                                            <div class="contact-box-phon"><a target="_blank" href="https://rentcar.rent/faqs/">Instructions for pick-up and delivery your vehicle</a></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="contact-box-border">&nbsp;</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="column-97as7d798as" class="fw-col-sm-4 wow fadeInUp animated animated" data-wow-offset="120" data-wow-duration="1.5s" style="visibility: visible; animation-duration: 1.5s; animation-name: fadeInUp;">
                            <div class="fw-main-row">
                                <div class="fw-col-inner">
                                    <div class="contact">
                                        <div class="contact-box">
                                            <div class="contact-box-name">Orlando</div>
                                            <div style="margin: 10px 0;"><a href="https://www.google.com/maps/place/3255+McCoy+Rd,+Belle+Isle,+FL+32812,+EE.+UU./@28.4515522,-81.3440854,17z/data=!3m1!4b1!4m5!3m4!1s0x88e77cd3117fd1d1:0x61bde943225bfa3c!8m2!3d28.4515522!4d-81.3418967" target="_blank"><span>3255 McCoy Rd <br>Belle Isle FL, 32812 <br> United States</span></a></div>
                                            <div class="contact-box-phon"><span class="highlight">Phone:</span><a href="tel:+13218006002"> +1 (321) 800-6002</a></div>
                                            <div class="contact-box-phon"><span class="highlight">Toll-Free:</span><a href="tel:+18664369044"> +1 (866) 436-9044</a></div>
                                            <div class="contact-box-phon"><span class="highlight">WhatsApp:</span><a href="https://api.whatsapp.com/send?phone=13059886125" target="_blank"> +1 (305) 988-6125</a></div>
                                            <div class="contact-box-phon"><span class="highlight">Fax:</span><a href="tel:+17866521536"> +1 (786) 652-1536</a></div> 
                                            <div class="contact-box-email"><span class="highlight">E-mail:</span><a href="help@rentcar.rent"> help@rentcar.rent</a><br><a href="mailto:ayuda@rentcar.rent">ayuda@rentcar.rent</a></div>
                                            <div class="contact-box-phon" style="margin-top: 20px"><strong>Opening Hours</strong></div>
                                            <div class="hours">Mon - Sun 06:00AM - 11:00PM</div>
                                            <div class="contact-box-phon"><a target="_blank" href="https://rentcar.rent/faqs/">Instructions for pick-up and delivery your vehicle</a></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="contact-box-border">&nbsp;</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                                                <div id="column-28as7d8sa4" class="fw-col-sm-4 wow fadeInUp animated animated" data-wow-offset="120" data-wow-duration="1.5s" style="visibility: visible; animation-duration: 1.5s; animation-name: fadeInUp;">
                            <div class="fw-main-row">
                                <div class="fw-col-inner">
                                    <div class="contact">
                                        <div class="contact-box">
                                            <div class="contact-box-name">Fort Lauderdale</div>
                                            <div style="margin: 10px 0;"><a href="https://goo.gl/maps/h6kBArr7XH93PqdM8" target="_blank"><span>321 W State Road 84<br>Fort Lauderdale Florida, 33315 <br> United States</span></a></div>
                                            <div class="contact-box-phon"><span class="highlight">Phone:</span><a href="tel:+19549004536"> +1 (954) 900-4536</a></div>
                                            <div class="contact-box-phon"><span class="highlight">WhatsApp:</span><a href="https://api.whatsapp.com/send?phone=13056009793" target="_blank"> +1 (305) 600-9793</a></div>
                                            <div class="contact-box-phon"><span class="highlight">Fax:</span><a href="tel:+7866521536"> +1 (786) 652-1536</a></div>
                                            <div class="contact-box-email"><span class="highlight">E-mail:</span><a href="help@rentcar.rent"> help@rentcar.rent</a><br><a href="mailto:ayuda@rentcar.rent">ayuda@rentcar.rent</a><br><br></div>
                                            <div class="contact-box-phon" style="margin-top: 20px"><strong>Opening Hours</strong></div>
                                            <div class="hours">Mon - Sat 06:00AM - 7:00PM<br>Sun 9:00AM - 5:00PM</div>
                                            <div class="contact-box-phon"><a target="_blank" href="https://rentcar.rent/faqs/">Instructions for pick-up and delivery your vehicle</a></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="contact-box-border">&nbsp;</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Locations-->
                </div>
            </div>
        </section>
    </div>
    <?php elseif (get_locale() == 'es_ES') : ?>
    <div class="fw-page-builder-content">
        <section id="section-5cf6e66ddb8b2" class="fw-main-row auto ">
            <div>
                <div class="fw-container sections">
                    <div class="fw-row">

                        <div id="column-5cf6e66ddbc3d" class=" fw-col-sm-8 wow fadeInUp animated animated" data-wow-offset="120" data-wow-duration="1.5s" style="visibility: visible; animation-duration: 1.5s; animation-name: fadeInUp;">
                            <div class="fw-main-row">
                                <div class="fw-col-inner">

                                    <div class="fw-heading fw-heading-h1 wow fadeIn animated animated" data-wow-offset="120" data-wow-duration="1.5s" style="visibility: visible; animation-duration: 1.5s; animation-name: fadeIn;">
                                        <div class="fw-special-title-half">
                                            <h1 class="fw-special-title" style="margin-bottom: 10px!important;">Contacto</h1>
                                        </div>
                                            <h4 style="margin-bottom: 30px!important;">Atención al Cliente 24/7</h4>
                                    </div>

                                    <div class="contact fw-contact-form">
                                        <div class="fw-row wrap-forms wrap-contact-forms wow rollIn animated contact-form animated animated" data-wow-offset="10" data-wow-duration="1.55s" style="visibility: visible; animation-duration: 1.55s; animation-name: rollIn;">

                                            <form id="hq-form" method="post" action="https://one-switch.us5.hqrentals.app/form/7e913945-a78a-4163-94ac-d94c7cf1d327?redirect=https://rentcar.rent" class="fw_form_fw_form" data-fw-ext-forms-type="contact-forms" _lpchecked="1">
              
                                                <div class="fw-row">
                                                    <div class="fw-col-xs-12 fw-col-sm-6 form-builder-item">
                                                        <div class="field-text">
                                                            <input class="form-control" type="text" name="field_338" placeholder="Nombre" value="" id="id-1" required="required">
                                                        </div>
                                                    </div>
                                                    <div class="fw-col-xs-12 fw-col-sm-6 form-builder-item">
                                                        <div class="field-text">
                                                            <input class="form-control" type="text" name="field_339" placeholder="Apellido" value="" id="id-2" required="required">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="fw-row">
                                                    <div class="fw-col-xs-12 fw-col-sm-6 form-builder-item">
                                                        <div class="field-text">
                                                            <input class="form-control" type="text" name="field_341" placeholder="Teléfono" value="" id="id-3" required="required">
                                                        </div>
                                                    </div>
                                                    <div class="fw-col-xs-12 fw-col-sm-6 form-builder-item">
                                                        <div class="field-text">
                                                            <input class="form-control" type="email" name="field_342" placeholder="Email" value="" id="id-4" required="required">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="fw-row">
                                                    <div class="fw-col-xs-12 form-builder-item">
                                                        <div class="field-textarea">
                                                            <textarea class="form-control" name="field_343" placeholder="Mensaje" id="id-5" required="required"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="fw-row">
                                                    <div class="hq-captcha">
                                                        <?php echo $captcha; ?>
                                                    </div>
                                                </div>
                                                <div class="fw-col-sm-12 field-submit text-center">
                                                    <input type="submit" class="default btn submit-message" value="Enviar Mensaje">

                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <div id="hq-success" style="display: none;">
                                        <span>Gracias, su mensaje se ha enviado con éxito.</span>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div id="column-5cf6e66ddc081" class=" fw-col-sm-4 wow fadeInUp animated animated" data-wow-offset="120" data-wow-duration="1.5s" style="visibility: visible; animation-duration: 1.5s; animation-name: fadeInUp;">
                            <div class="fw-main-row">
                                <div class="fw-col-inner">

                                    <div class="contact">
                                        <h4 class="contact-box-title">Preguntas?</h4>
                                        <div class="contact-box">
                                            
                                            <div class="contact-box-name">Llamada Gratuita</div>
                                            <div class="contact-box-phon"><span class="highlight">Teléfono:</span><a href="tel:+18664369044"> +1 (866) 436-9044</a></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                    </div>

                                    <div class="contact">
                                        <div class="contact-box">
                                            <div class="contact-box-name">Venezuela</div>
                                            <div class="contact-box-phon"><span class="highlight">Teléfono:</span> <a href="tel:+582617700424"> +58 (261) 770-0424</a> / <a href="tel:+582123354993">+58 (212) 335-4993</a></div>
                                            <div class="contact-box-email"><span class="highlight">WhatsApp:</span><a href="https://api.whatsapp.com/send?phone=584146568149" target="_blank"> +58 (414) 656-8149</a></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        <div class="contact-box">
                                            <div class="contact-box-name">USA</div>
                                            <div class="contact-box-email"><span class="highlight">WhatsApp:</span><a href="https://api.whatsapp.com/send?phone=13055875313" target="_blank"> +1 (305) 587-5313</a></div>
                                            <div class="contact-box-email"><span class="highlight">WhatsApp:</span><a href="https://api.whatsapp.com/send?phone=13059886125" target="_blank"> +1 (305) 988-6125</a></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        <div class="contact-box">
                                            <div class="contact-box-name">Colombia</div>
                                            <div class="contact-box-phon"><span class="highlight">Teléfono:</span><a href="tel:+57(1)5085112"> +57 (1) 508-5112</a></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="contact-box-border">&nbsp;</div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!--Begin Locations-->
                    <div class="fw-row" style="margin-top: 30px;">
                        <div id="column-28as7d8sa4" class="fw-col-sm-4 wow fadeInUp animated animated" data-wow-offset="120" data-wow-duration="1.5s" style="visibility: visible; animation-duration: 1.5s; animation-name: fadeInUp;">
                            <div class="fw-main-row">
                                <div class="fw-col-inner">
                                    <div class="contact">
                                        <div class="contact-box">
                                            <div class="contact-box-name">Fort Lauderdale</div>
                                            <div style="margin: 10px 0;"><a href="https://goo.gl/maps/h6kBArr7XH93PqdM8" target="_blank"><span>321 W State Road 84<br>Fort Lauderdale Florida, 33315 <br> United States</span></a></div>
                                            <div class="contact-box-phon"><span class="highlight">Teléfono:</span><a href="tel:+19549004536"> +1 (954) 900-4536</a></div>
                                            <div class="contact-box-phon"><span class="highlight">WhatsApp:</span><a href="https://api.whatsapp.com/send?phone=13056009793" target="_blank"> +1 (305) 600-9793</a></div>
                                            <div class="contact-box-phon"><span class="highlight">Fax:</span><a href="tel:+7866521536"> +1 (786) 652-1536</a></div>
                                            <div class="contact-box-email"><span class="highlight">E-mail:</span><a href="help@rentcar.rent"> help@rentcar.rent</a><br><a href="mailto:ayuda@rentcar.rent">ayuda@rentcar.rent</a><br><br></div>
                                            <div class="contact-box-phon" style="margin-top: 20px"><strong>Horario</strong></div>
                                            <div class="hours">Lun - Sab 06:00AM - 07:00PM<br>Dom 9:00AM - 5:00PM</div>
                                            <div class="contact-box-phon"><a target="_blank" href="https://rentcar.rent/faqs-2/">Instrucciones de recogida y entrega de tu vehículo</a></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="contact-box-border">&nbsp;</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="column-as8d7as7d" class="fw-col-sm-4 wow fadeInUp animated animated" data-wow-offset="120" data-wow-duration="1.5s" style="visibility: visible; animation-duration: 1.5s; animation-name: fadeInUp;">
                            <div class="fw-main-row">
                                <div class="fw-col-inner">
                                    <div class="contact">
                                        <div class="contact-box">
                                            <div class="contact-box-name">Miami</div>
                                            <div style="margin: 10px 0;"><a href="http://maps.google.com/maps?q=3256+NW+24th+Street+Rd%2c+Miami%2c+FL%2c+33142%2c+United+States+(MIAMI)" target="_blank"><span>3256 NW 24th Street Rd <br>Miami FL, 33142 <br> United States</span></a></div>
                                            <div class="contact-box-phon"><span class="highlight">Teléfono:</span><a href="tel:+13054707556"> +1 (305) 470 7556</a></div>
                                            <div class="contact-box-phon"><span class="highlight">Llamada Gratuita:</span><a href="tel:+18664369044"> +1 (866) 436-9044</a></div>
                                            <div class="contact-box-phon"><span class="highlight">WhatsApp:</span><a href="https://api.whatsapp.com/send?phone=17868622219" target="_blank"> +1 (786) 862-2219</a></div>
                                            <div class="contact-box-phon"><span class="highlight">Fax:</span><a href="tel:+17866521536"> +1 (786) 652-1536</a></div> 
                                            <div class="contact-box-email"><span class="highlight">E-mail:</span><a href="help@rentcar.rent"> help@rentcar.rent</a><br><a href="mailto:ayuda@rentcar.rent">ayuda@rentcar.rent</a></div>
                                            <div class="contact-box-phon" style="margin-top: 20px"><strong>Horario</strong></div>
                                            <div class="hours">Lu - Do 06:00AM - 11:00PM</div>
                                            <div class="contact-box-phon"><a target="_blank" href="https://rentcar.rent/faqs-2/">Instrucciones de recogida y entrega de tu vehículo</a></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="contact-box-border">&nbsp;</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="column-97as7d798as" class="fw-col-sm-4 wow fadeInUp animated animated" data-wow-offset="120" data-wow-duration="1.5s" style="visibility: visible; animation-duration: 1.5s; animation-name: fadeInUp;">
                            <div class="fw-main-row">
                                <div class="fw-col-inner">
                                    <div class="contact">
                                        <div class="contact-box">
                                            <div class="contact-box-name">Orlando</div>
                                            <div style="margin: 10px 0;"><a href="https://www.google.com/maps/place/3255+McCoy+Rd,+Belle+Isle,+FL+32812,+EE.+UU./@28.4515522,-81.3440854,17z/data=!3m1!4b1!4m5!3m4!1s0x88e77cd3117fd1d1:0x61bde943225bfa3c!8m2!3d28.4515522!4d-81.3418967" target="_blank"><span>3255 McCoy Rd <br>Belle Isle FL, 32812<br> United States</span></a></div>
                                            <div class="contact-box-phon"><span class="highlight">Teléfono:</span><a href="tel:+13218006002"> +1 (321) 800-6002</a></div>
                                            <div class="contact-box-phon"><span class="highlight">Llamada Gratuita:</span><a href="tel:+18664369044"> +1 (866) 436-9044</a></div>
                                            <div class="contact-box-phon"><span class="highlight">WhatsApp:</span><a href="https://api.whatsapp.com/send?phone=13059886125" target="_blank"> +1 (305) 988-6125</a></div>
                                            <div class="contact-box-phon"><span class="highlight">Fax:</span><a href="tel:+17866521536"> +1 (786) 652-1536</a></div> 
                                            <div class="contact-box-email"><span class="highlight">E-mail:</span><a href="help@rentcar.rent"> help@rentcar.rent</a><br><a href="mailto:ayuda@rentcar.rent">ayuda@rentcar.rent</a></div>
                                            <div class="contact-box-phon" style="margin-top: 20px"><strong>Horario</strong></div>
                                            <div class="hours">Lu - Do 06:00AM - 11:00PM</div>
                                            <div class="contact-box-phon"><a target="_blank" href="https://rentcar.rent/faqs-2/">Instrucciones de recogida y entrega de tu vehículo</a></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="contact-box-border">&nbsp;</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End Locations-->
                </div>
            </div>
        </section>
    </div>
    <?php else : ?>
    <div class="fw-page-builder-content">
        <section id="section-5cf6e66ddb8b2" class="fw-main-row auto ">
            <div>
                <div class="fw-container sections">
                    <div class="fw-row">

                        <div id="column-5cf6e66ddbc3d" class=" fw-col-sm-8 wow fadeInUp animated animated" data-wow-offset="120" data-wow-duration="1.5s" style="visibility: visible; animation-duration: 1.5s; animation-name: fadeInUp;">
                            <div class="fw-main-row">
                                <div class="fw-col-inner">

                                    <div class="fw-heading fw-heading-h1 wow fadeIn animated animated" data-wow-offset="120" data-wow-duration="1.5s" style="visibility: visible; animation-duration: 1.5s; animation-name: fadeIn;">
                                        <div class="fw-special-title-half">
                                            <h1 class="fw-special-title" style="margin-bottom: 10px!important;">Contato</h1>
                                        </div>
                                            <h4 style="margin-bottom: 30px!important;">Suporte ao Cliente 24/7</h4>
                                    </div>

                                    <div class="contact fw-contact-form">
                                        <div class="fw-row wrap-forms wrap-contact-forms wow rollIn animated contact-form animated animated" data-wow-offset="10" data-wow-duration="1.55s" style="visibility: visible; animation-duration: 1.55s; animation-name: rollIn;">

                                            <form id="hq-form" method="post" action="https://one-switch.us5.hqrentals.app/form/7e913945-a78a-4163-94ac-d94c7cf1d327?redirect=https://rentcar.rent" class="fw_form_fw_form" data-fw-ext-forms-type="contact-forms" _lpchecked="1">
              
                                                <div class="fw-row">
                                                    <div class="fw-col-xs-12 fw-col-sm-6 form-builder-item">
                                                        <div class="field-text">
                                                            <input class="form-control" type="text" name="field_338" placeholder="Nome" value="" id="id-1" required="required">
                                                        </div>
                                                    </div>
                                                    <div class="fw-col-xs-12 fw-col-sm-6 form-builder-item">
                                                        <div class="field-text">
                                                            <input class="form-control" type="text" name="field_339" placeholder="Sobrenome" value="" id="id-2" required="required">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="fw-row">
                                                    <div class="fw-col-xs-12 fw-col-sm-6 form-builder-item">
                                                        <div class="field-text">
                                                            <input class="form-control" type="text" name="field_341" placeholder="Telefone" value="" id="id-3" required="required">
                                                        </div>
                                                    </div>
                                                    <div class="fw-col-xs-12 fw-col-sm-6 form-builder-item">
                                                        <div class="field-text">
                                                            <input class="form-control" type="email" name="field_342" placeholder="Email" value="" id="id-4" required="required">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="fw-row">
                                                    <div class="fw-col-xs-12 form-builder-item">
                                                        <div class="field-textarea">
                                                            <textarea class="form-control" name="field_343" placeholder="Mensagem" id="id-5" required="required"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="fw-row">
                                                    <div class="hq-captcha">
                                                        <?php echo $captcha; ?>
                                                    </div>
                                                </div>
                                                <div class="fw-col-sm-12 field-submit text-center">
                                                    <input type="submit" class="default btn submit-message" value="Enviar Mensagem">

                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <div id="hq-success" style="display: none;">
                                        <span>Obrigado, sua mensagem foi enviada com sucesso.</span>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div id="column-5cf6e66ddc081" class=" fw-col-sm-4 wow fadeInUp animated animated" data-wow-offset="120" data-wow-duration="1.5s" style="visibility: visible; animation-duration: 1.5s; animation-name: fadeInUp;">
                            <div class="fw-main-row">
                                <div class="fw-col-inner">

                                    <div class="contact">
                                        <h4 class="contact-box-title">Tem Perguntas?</h4>
                                        <div class="contact-box">
                                            
                                            <div class="contact-box-name">Grátis</div>
                                            <div class="contact-box-phon"><span class="highlight">Telefone:</span><a href="tel:+18664369044"> +1 (866) 436-9044</a></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                    </div>

                                    <div class="contact">
                                        <div class="contact-box">
                                            <div class="contact-box-name">Venezuela</div>
                                            <div class="contact-box-phon"><span class="highlight">Telefone:</span> <a href="tel:+582617700424"> +58 (261) 770-0424</a> / <a href="tel:+582123354993">+58 (212) 335-4993</a></div>
                                            <div class="contact-box-email"><span class="highlight">WhatsApp:</span><a href="https://api.whatsapp.com/send?phone=584146568149" target="_blank"> +58 (414) 656-8149</a></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        <div class="contact-box">
                                            <div class="contact-box-name">USA</div>
                                            <div class="contact-box-email"><span class="highlight">WhatsApp:</span><a href="https://api.whatsapp.com/send?phone=13055875313" target="_blank"> +1 (305) 587-5313</a></div>
                                            <div class="contact-box-email"><span class="highlight">WhatsApp:</span><a href="https://api.whatsapp.com/send?phone=13059886125" target="_blank"> +1 (305) 988-6125</a></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        <div class="contact-box">
                                            <div class="contact-box-name">Colombia</div>
                                            <div class="contact-box-phon"><span class="highlight">Telefone:</span><a href="tel:+57(1)5085112"> +57 (1) 508-5112</a></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="contact-box-border">&nbsp;</div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="fw-row" style="margin-top: 30px;">
                        <!--
                        <div id="column-28as7d8sa4" class="fw-col-sm-4 wow fadeInUp animated animated" data-wow-offset="120" data-wow-duration="1.5s" style="visibility: visible; animation-duration: 1.5s; animation-name: fadeInUp;">
                            <div class="fw-main-row">
                                <div class="fw-col-inner">
                                    <div class="contact">

                                        <div class="contact-box">
                                            <div class="contact-box-name">Fort Lauderdale</div>
                                            <div style="margin: 10px 0;"><a href="http://maps.google.com/maps?q=1000+WEST+Pembroke+RD+Suite+111%2c+Hallandale%2c+Florida%2c+33009%2c+United+States+(Fort+Lauderdale)" target="_blank"><span>1000 WEST Pembroke RD Suite 111 <br>Hallandale Florida, 33009 <br> United States</span></a></div>
                                            <div class="contact-box-phon"><span class="highlight">Telefone:</span><a href="tel:+13059626801"> +1 (305) 962-6801</a></div>
                                            <div class="contact-box-phon"><span class="highlight">Grátis:</span><a href="tel:+18664369044"> +1 (866) 436-9044</a></div>
                                            <div class="contact-box-phon"><span class="highlight">WhatsApp:</span><a href="https://api.whatsapp.com/send?phone=13056009793" target="_blank"> +1 (305) 600-9793</a></div>
                                            <div class="contact-box-phon"><span class="highlight">Fax:</span><a href="tel:+13053286625"> +1 (305) 328-6625</a></div>
                                            <div class="contact-box-email"><span class="highlight">E-mail:</span><a href="mailto:reservasryp@gmail.com"> reservasryp@gmail.com</a></div>
                                            <div class="contact-box-phon" style="margin-top: 20px"><strong>Horário de abertura</strong></div>
                                            <div class="hours">Seg - Dom 05:00AM - 11:00PM</div>
                                            <div class="contact-box-phon"><a target="_blank" href="https://rentcar.rent/faqs-3/">Instruções para recolha e entrega do seu veículo</a></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="contact-box-border">&nbsp;</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        -->
                        <div id="column-as8d7as7d" class="fw-col-sm-6 wow fadeInUp animated animated" data-wow-offset="120" data-wow-duration="1.5s" style="visibility: visible; animation-duration: 1.5s; animation-name: fadeInUp;">
                            <div class="fw-main-row">
                                <div class="fw-col-inner">
                                    <div class="contact">
                                        <div class="contact-box">
                                            <div class="contact-box-name">Miami</div>
                                            <div style="margin: 10px 0;"><a href="http://maps.google.com/maps?q=3256+NW+24th+Street+Rd%2c+Miami%2c+FL%2c+33142%2c+United+States+(MIAMI)" target="_blank"><span>3256 NW 24th Street Rd <br>Miami FL, 33142 <br> United States</span></a></div>
                                            <div class="contact-box-phon"><span class="highlight">Telefone:</span><a href="tel:+13054707556"> +1 (305) 470 7556</a></div>
                                            <div class="contact-box-phon"><span class="highlight">Grátis:</span><a href="tel:+18664369044"> +1 (866) 436-9044</a></div>
                                            <div class="contact-box-phon"><span class="highlight">WhatsApp:</span><a href="https://api.whatsapp.com/send?phone=17868622219" target="_blank"> +1 (786) 862-2219</a></div>
                                            <div class="contact-box-phon"><span class="highlight">Fax:</span><a href="tel:+17866521536"> +1 (786) 652-1536</a></div> 
                                            <div class="contact-box-email"><span class="highlight">E-mail:</span><a href="mailto:customerservice.osrc@gmail.com"> customerservice.osrc@gmail.com</a><br><a href="mailto:cotizaciones.osrc@gmail.com">cotizaciones.osrc@gmail.com</a></div>
                                            <div class="contact-box-phon" style="margin-top: 20px"><strong>Horário de abertura</strong></div>
                                            <div class="hours">Seg - Dom 06:00AM - 11:00PM</div>
                                            <div class="contact-box-phon"><a target="_blank" href="https://rentcar.rent/faqs-3/">Instruções para recolha e entrega do seu veículo</a></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="contact-box-border">&nbsp;</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="column-97as7d798as" class="fw-col-sm-6 wow fadeInUp animated animated" data-wow-offset="120" data-wow-duration="1.5s" style="visibility: visible; animation-duration: 1.5s; animation-name: fadeInUp;">
                            <div class="fw-main-row">
                                <div class="fw-col-inner">
                                    <div class="contact">
                                        <div class="contact-box">
                                            <div class="contact-box-name">Orlando</div>
                                            <div style="margin: 10px 0;"><a href="https://www.google.com/maps/place/3255+McCoy+Rd,+Belle+Isle,+FL+32812,+EE.+UU./@28.4515522,-81.3440854,17z/data=!3m1!4b1!4m5!3m4!1s0x88e77cd3117fd1d1:0x61bde943225bfa3c!8m2!3d28.4515522!4d-81.3418967" target="_blank"><span>3255 McCoy Rd <br>Belle Isle FL, 32812<br> United States</span></a></div>
                                            <div class="contact-box-phon"><span class="highlight">Telefone:</span><a href="tel:+13059886125"> +1 (305) 988-6125</a></div>
                                            <div class="contact-box-phon"><span class="highlight">Grátis:</span><a href="tel:+18664369044"> +1 (866) 436-9044</a></div>
                                            <div class="contact-box-phon"><span class="highlight">WhatsApp:</span><a href="https://api.whatsapp.com/send?phone=13059886125" target="_blank"> +1 (305) 988-6125</a></div>
                                            <div class="contact-box-phon"><span class="highlight">Fax:</span><a href="tel:+17866521536"> +1 (786) 652-1536</a></div> 
                                            <div class="contact-box-email"><span class="highlight">E-mail:</span><a href="mailto:customerservice.osrc@gmail.com"> customerservice.osrc@gmail.com</a><br><a href="mailto:cotizaciones.osrc@gmail.com">cotizaciones.osrc@gmail.com</a></div>
                                            <div class="contact-box-phon" style="margin-top: 20px"><strong>Horário de abertura</strong></div>
                                            <div class="hours">Seg - Dom 06:00AM - 11:00PM</div>
                                            <div class="contact-box-phon"><a target="_blank" href="https://rentcar.rent/faqs-3/">Instruções para recolha e entrega do seu veículo</a></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="contact-box-border">&nbsp;</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php endif; ?>

    <style type="text/css">
        @media (max-width: 768px) {
            form .fw-col-xs-12.fw-col-sm-6.form-builder-item {
                padding-bottom: 0;
            }
            #contact .fw-col-sm-4 {
                padding-bottom: 0!important;
                padding-top: 0!important;
            }
            #contact .fw-col-inner {
                padding-bottom: 0;
            }
        }
        #contact ul li label {
            width: 100px;
        }
        #contact form {
            padding: 0 15px;
        }
        .hq-captcha {
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .g-recaptcha div {
            margin: auto;
        }
    </style>
        

    <?php

    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}

add_shortcode('hq_contact_form', 'hq_contact_form');