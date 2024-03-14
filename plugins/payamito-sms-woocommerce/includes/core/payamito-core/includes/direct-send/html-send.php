<div>
    <button class="button button-primary " type="button" value="send" id="btnPayamitoDirectSend"><?php
		esc_html_e( 'Fire', 'payamito' ) ?></button>
    <div id="payamitoDirectSendldBar" class="ldBar label-center" data-stroke="#ff9100" data-preset="fan"
         style="width:100%;height:100px"></div>

    <div style="display:none" id="divpayamitoDirectSendLoger">
        <label class="custom-control-label"
               style="position: absolute;padding: 2% 0%; font-size: 15px;right: 7%;font-family: Vazir !important;font-weight: 400;"
               for="payamitoDirectSendLoger"><?php
			esc_html_e( 'Send Status', "payamito" ) ?></label>
        <textarea id="payamitoDirectSendLoger" readonly
                  style=" background:#fffefa ;width:100% ;border: 1px solid #ff9100; ;overflow-y: scroll;resize: none; overflow:auto;color:rgb(133 133 133) ; text-align:end"
                  name="w3review" rows="4" cols="50"></textarea>

    </div>
</div>

<script>
    jQuery(document).ready(function ($) {

        var payamitoDirectSend = {
            'canceled': false,
            'mobileNumbers': [],
            'progress': 0,
            'message': [],
            'is_pattern_active': true,
            'separator': ',',
            'patterns': [],
            'patternID': '',
            'progressBar': Object,
            'progressIncreser': 0,
            'type': 1,
            'value': null,
            'senderNumber': '',
            'counterLog': 0,
            check: function () {
                if (this.mobileNumbers !== "") {
                    document.getElementById('payamitoDirectSendldBar').ldBar;
                    this.progressBar.set(0);
                    if (this.is_pattern_active === true) {
                        this.patterns = this.preparePattern(this.patterns);
                        if (this.patterns === false) {
                            Swal.fire({
                                title: '<?php esc_html_e( 'Error!', "payamito" ) ?>',
                                text: '<?php esc_html_e( 'There is not any pattern', "payamito" ) ?>',
                                icon: 'error',
                                confirmButtonText: '<?php esc_html_e( 'Ok', "payamito" ) ?>'
                            })
                        } else {
                            this.prepareMobileNumbers(this.mobileNumbers, this.separator);
                            this.value = this.patterns;
                            this.type = 1;
                            this.progressIncreser = 100 / this.mobileNumbers.length
                            this.send(this.type, this.patterns, [this.mobileNumbers[0]], this.patternID);
                            this.mobileNumbers.splice(0, 1);
                            $("#btnPayamitoDirectSend").html('<?php esc_html_e( 'Cancel', "payamito" ) ?>')
                        }
                    } else {
                        if (this.message.length != 0) {
                            if (this.senderNumber === '') {
                                Swal.fire({
                                    title: '<?php esc_html_e( 'Error!', "payamito" ) ?>',
                                    text: '<?php esc_html_e( 'Please fill sender number', "payamito" ) ?>',
                                    icon: 'error',
                                    confirmButtonText: '<?php esc_html_e( 'Ok', "payamito" ) ?>'
                                })
                            } else {
                                this.value = this.message;
                                this.type = 2;
                                this.prepareMobileNumbers(this.mobileNumbers, this.separator);
                                this.progressIncreser = 100 / this.mobileNumbers.length
                                this.send(this.type, this.message, this.mobileNumbers[0], this.patternID, this.senderNumber);
                                this.mobileNumbers.splice(0, 1);
                                $("#btnPayamitoDirectSend").html('<?php esc_html_e( 'Cancel', "payamito" ) ?>')
                            }
                        } else {
                            Swal.fire({
                                title: '<?php esc_html_e( 'Error!', "payamito" ) ?>',
                                text: '<?php esc_html_e( 'Please enter your message', "payamito" ) ?>',
                                icon: 'error',
                                confirmButtonText: '<?php esc_html_e( 'Ok', "payamito" ) ?>'
                            })
                        }
                    }
                } else {
                    Swal.fire({
                        title: '<?php esc_html_e( 'Error!', "payamito" ) ?>',
                        text: '<?php esc_html_e( 'Please enter mobile numbers', "payamito" ) ?>',
                        icon: 'error',
                        confirmButtonText: '<?php esc_html_e( 'Ok', "payamito" ) ?>'
                    })
                }
            },
            init: function () {

                canceled = $("#btnPayamitoDirectSend").html() == 'Cancel' || $("#btnPayamitoDirectSend").html() == 'لغو' ? true : false;
                if (canceled === true) {
                    payamitoDirectSend.cancel();
                } else {

                    payamitoDirectSend.canceled = false;
                    payamitoDirectSend.mobileNumbers = [];
                    payamitoDirectSend.progress = 0;
                    payamitoDirectSend.mobileNumbers = $('[name="payamito[payamito_direct_mobile_numbers]"]').val().trim();
                    payamitoDirectSend.message = $('[name="payamito[payamito_direct_message]"').val().trim();
                    payamitoDirectSend.is_pattern_active = document.getElementsByName('payamito[payamito_direct_send_active_pattern]')[0].value === '1' ? true : false;
                    payamitoDirectSend.separator = $('[name="payamito[payamito_direct_send_separator]"').find(":selected").val();
                    payamitoDirectSend.patterns = $('[data-field-id="[payamito_direct_send_repeater]"').find(":input");
                    payamitoDirectSend.patternID = document.getElementsByName('payamito[payamito_direct_send_pattern_id]')[0].value;
                    payamitoDirectSend.senderNumber = document.getElementsByName('payamito[payamito_direct_sender_number]')[0].value;
                    payamitoDirectSend.progressBar = new ldBar("#payamitoDirectSendldBar", {});
                    payamitoDirectSend.progressIncreser = 0;
                    payamitoDirectSend.type = 1;
                    payamitoDirectSend.value = null
                    payamitoDirectSend.check();
                }
            },
            send: function (type, value, mobileNumber, patternID = null, senderNumber = 0) {

                $.post(
                    '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ) ?>', {
                        'action': 'send',
                        'mobileNumbers': mobileNumber,
                        'type': type,
                        'value': value,
                        'patternID': patternID,
                        'senderNumber': senderNumber,
                    },
                ).done(function (response, status) {

                    if (response.logShow === '1') {
                        $("#divpayamitoDirectSendLoger").css("display", "block");
                        $("#payamitoDirectSendLoger").append(payamitoDirectSend.counterLog + ": " + response.result + '\n');
                        ++payamitoDirectSend.counterLog;
                        $("#payamitoDirectSendLoger")[0].scrollTop = $("#payamitoDirectSendLoger")[0].scrollHeight;
                    }
                    let length = payamitoDirectSend.mobileNumbers.length;
                    if (payamitoDirectSend.canceled === true) {

                        Swal.fire({
                            title: '<?php esc_html_e( 'Warning', "payamito" ) ?>',
                            text: '<?php esc_html_e( 'Sending canceled', "payamito" ) ?>',
                            icon: 'warning',
                            confirmButtonText: '<?php esc_html_e( 'Ok', "payamito" ) ?>'
                        });
                        $("#btnPayamitoDirectSend").html('<?php esc_html_e( 'Fire', "payamito" ) ?>');
                        length = 0;
                    } else {

                    }
                    if (response.exit == true) {
                        length = 0;
                        Swal.fire({
                            title: response.title,
                            text: response.message,
                            icon: response.type,
                            confirmButtonText: '<?php esc_html_e( 'Ok', "payamito" ) ?>'
                        })
                        $("#btnPayamitoDirectSend").html('<?php esc_html_e( 'Fire', "payamito" ) ?>')
                    }
                    payamitoDirectSend.progressIncreser = 100 / payamitoDirectSend.mobileNumbers.length
                    if (length !== 0) {
                        payamitoDirectSend.send(payamitoDirectSend.type, payamitoDirectSend.value, [payamitoDirectSend.mobileNumbers[0]], payamitoDirectSend.patternID)

                    }
                    payamitoDirectSend.progress += payamitoDirectSend.progressIncreser;
                    payamitoDirectSend.progressBar.set(payamitoDirectSend.progress);
                    payamitoDirectSend.mobileNumbers.splice(0, 1);
                    if (length === 0) {
                        $("#btnPayamitoDirectSend").html('<?php esc_html_e( 'Fire', "payamito" ) ?>')

                    }
                }).always();
            },
            cancel: function () {
                payamitoDirectSend.canceled = true;
                $("#btnPayamitoDirectSend").html('<?php esc_html_e( 'Fire', "payamito" ) ?>')
            },
            prepare: function (is_pattern, value) {
                if (is_pattern === true) {
                    payamitoDirectSend.preparePattern(value)
                } else {

                }
            },
            preparePattern: function (pattern) {

                if (pattern.length != 0) {

                    let values = [];
                    let type = typeof pattern;
                    let counter = 0;
                    let temporary = [];
                    Object.keys(pattern).forEach(key => {

                        child = pattern[key];
                        value = child.value;
                        if (value !== undefined) {
                            counter++;
                            temporary.push(value);
                            if (temporary.length == 2) {
                                values.push(temporary);
                                temporary = [];
                                counter = 0;
                            }
                        }
                    });
                    return values;
                }
                return false;
            },
            prepareMessage: function (message) {

            },
            prepareMobileNumbers: function (mobilenumbers, sepretor) {

                let arrayMobileNumbers;
                switch (sepretor) {
                    case "n":
                        arrayMobileNumbers = mobilenumbers.split(/\r?\n/);
                        break;
                    case "s":
                        arrayMobileNumbers = mobilenumbers.split(" ");
                        break;
                    case "c":
                        arrayMobileNumbers = mobilenumbers.split(",");
                        break;
                    default:

                        arrayMobileNumbers = undefined;
                }
                if (this.type === 2) {
                    if (arrayMobileNumbers.length > 100) {
                        let group = [];
                        let container = [];
                        let counter = 100;
                        let result = new Array(Math.ceil(arrayMobileNumbers.length / counter))
                            .fill()
                            .map(_ => arrayMobileNumbers.splice(0, counter));

                        this.mobileNumbers = result;
                    } else {
                        this.mobileNumbers = [arrayMobileNumbers];
                    }

                } else {
                    this.mobileNumbers = arrayMobileNumbers;
                }
            }
        }
        $("#btnPayamitoDirectSend").on('click', payamitoDirectSend.init)
    });
</script>