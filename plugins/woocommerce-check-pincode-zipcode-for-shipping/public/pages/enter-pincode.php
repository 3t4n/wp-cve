<div class="cm-b-b" style="background-color:<?= $enter_pincode_data['setting']['box_bg_color'] ?>;">
    <div class="cm_phone_pincode">
        <div class="cm_phone_content">
            <span>
                <p class="cm-f-sb tracking-wide" id="phoeniixx-pincode-data" style="color : <?= $enter_pincode_data['setting']['label_txt_color'] ?>"><?= $enter_pincode_data['setting']['enter_pincode_heading'] ?></p>
            </span>
            <div class="cm_phone_pincode_form">
                <div class="cm_phone_pincode_input">
                    <span style="border-radius:0;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="cm_size_w"><path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" /></svg>
                        <input type="text" id="phoeniixx-pincode-input" required="required" value="<?= isset($_COOKIE['phoeniixx-pincode-zipcode']) ? $_COOKIE['phoeniixx-pincode-zipcode'] : '' ?>">
                    </span>
                    <input type="submit" value="<?= $enter_pincode_data['setting']['check_btn_name'] ?>" id="phoeniixx-pincode-button" style="background:<?= $enter_pincode_data['setting']['btn_bg_color'] ?>; color:<?= $enter_pincode_data['setting']['btn_txt_color'] ?>;border-radius: 0;">
                </div>
            </div>
            <div class="cm_mess" role="alert" id="phoeniixx-pincode-message-display-1" >
                <div>
                    <span class="cm-font-medium" id="phoeniixx-pincode-message"></span>
                </div>
            </div>
        </div>
    </div>
</div>





