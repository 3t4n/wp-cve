<div class="cm-b-b" style="background-color:<?= $available_pincode_data['setting']['box_bg_color'] ?>;">
    
    <!-- ENTER PINCODE SECTION AREA -->
    <div class="cm_phone_pincode" id="phoeniixx-pincode-set-pincode-section" style="display:none;">
        <div class="cm_phone_content">
            <span>
                <p class="cm-f-sb tracking-tight" id="phoeniixx-pincode-data" style="color : <?= $available_pincode_data['setting']['label_txt_color'] ?>"><?= $available_pincode_data['setting']['enter_pincode_heading'] ?></p>
            </span>
            <form class="cm_phone_pincode_form">
                <div class="cm_phone_pincode_input">
                    <span style="border-radius: 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="cm_size_w"><path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" /></svg>
                        <input type="text" id="phoeniixx-pincode-input" required="required" value="<?= isset($_COOKIE['phoeniixx-pincode-zipcode']) ? $_COOKIE['phoeniixx-pincode-zipcode'] : $available_pincode_data['pincode'] ?>">
                    </span>
                    <input type="submit" value="<?= $available_pincode_data['setting']['change_btn_name'] ?>" id="phoeniixx-pincode-button" style="background:<?= $available_pincode_data['setting']['btn_bg_color'] ?>; color:<?= $available_pincode_data['setting']['btn_txt_color'] ?>;border-radius: 0;">
                </div>
            </form>
            <div class="cm_mess" role="alert" id="phoeniixx-pincode-message-display-1" >
                <div>
                    <span class="font-medium" id="phoeniixx-pincode-message"></span>
                </div>
            </div>
        </div>
    </div>

    <!-- SHOW PINCODE SECTION AREA -->
    <div class="cm-p-s" id="phoeniixx-pincode-show-pincode-section">
        <div class="cm_able_row">
            <span style="display:flex;justify-content: space-between; flex-wrap: wrap;" id="phoeniixx-pincode-appear-pincode-input">
                <p class="cm-f-sb tracking-tight" id="phoeniixx-pincode-data" style="color : <?= $available_pincode_data['setting']['label_txt_color'] ?>"><?= $available_pincode_data['setting']['available_pincode_heading'] ?>
                    <span style="text-decoration-line: underline;"> <?= $available_pincode_data['pincode'] ?></span> <br/> 
                    <span style="font-size: 13px; text-transform: capitalize;"> ( <?php
                        
                        if($available_pincode_data['setting']['show_city'] === 'yes'){
                            echo $available_pincode_data['pincode_data'][0]['city'].' ,';
                        }
                        if($available_pincode_data['setting']['show_state'] === 'yes'){
                            echo $available_pincode_data['pincode_data'][0]['state'].' , ' ;                          
                        }
                        echo $available_pincode_data['pincode_data'][0]['country'];
                        
                    ?> )</span>
                </p>

                <p class="cm_text_s text-gray-500 dark:text-gray-400 " style="color: rgb(107 114 128);"> 
                    <a style="background:<?= $available_pincode_data['setting']['btn_bg_color'] ?>; color:<?= $available_pincode_data['setting']['btn_txt_color'] ?>;" href="javascript:void(0)" id="phoeniixx-pincode-change"  class="cm_btn_chn inline-flex items-center" ><svg class="cm_size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg><?= $available_pincode_data['setting']['change_btn_name'] ?></a>
                </p>

            </span>

            <span class="cm_ab_content">
                <!-- <h5 class="cm_ab_list_hd tracking-wide text-center">We are avaiable and servicing at your location</h5> -->
                
                <!-- <hr class="my-8 h-px bg-gray-200 border-0 dark:bg-gray-700"> -->
                <ul class="cm_ab_list_content">
                
                    <?php if($available_pincode_data['setting']['enable_delivery_date'] === 'yes'): ?>    
                    <li class="cm_ab_list">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" /></svg>

                        <div class="cm_list_flx">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                            <span>
                                <h3 class="text-lg font-bold text-[#000]"><?= $available_pincode_data['setting']['dod_heading'] ?></h3>
                                <p class="text-sm font-medium text-[#000]"><?= date("D, jS M", strtotime("+ ".$available_pincode_data['pincode_data'][0]['dod']." day")); ?></p>
                            </span>

                            <span style="width:6%;" id="click_delivery_help_text">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" /></svg>
                            </span>
                        </div>
                    </li>
                     <?php endif; ?>

                    <li class="cm_ab_list">
                        <?php if($available_pincode_data['pincode_data'][0]['cod'] === 'yes'): ?>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" /></svg>
                        <?php else: ?>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-1.72 6.97a.75.75 0 10-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 101.06 1.06L12 13.06l1.72 1.72a.75.75 0 101.06-1.06L13.06 12l1.72-1.72a.75.75 0 10-1.06-1.06L12 10.94l-1.72-1.72z" clip-rule="evenodd" /></svg>
                        <?php endif; ?>

                        <div class="cm_list_flx">
                            
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" /></svg>

                            <span>
                                <h3 class="text-lg font-bold text-[#000]"><?= $available_pincode_data['setting']['cod_heading'] ?></h3>
                                <p class="text-sm font-medium text-[#000]"><?= $available_pincode_data['pincode_data'][0]['cod'] === 'yes' ? 'Available' : 'Not Available' ?></p>
                            </span>

                            <span style="width:6%;" id="click_cod_help_text">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" /></svg>
                            </span>
                        </div>
                    </li>

                    <li class="cm_ab_list" style="display: none; border-top:1px solid #bfafaf;" id="show_delivery_help_text">
                        <div class="cm_ab_flex flex p-4 mb-4 text-3xl text-blue-700 italic text-left rounded-lg dark:bg-blue-200 dark:text-blue-800" role="alert">
                            <div>
                                <span style="font-size:14px;"><?= $available_pincode_data['setting']['delivery_date_help_text'] ?></span>
                            </div>
                        </div>
                    </li>
                    <li class="cm_ab_list" style="display: none; border-top:1px solid #bfafaf;" id="show_cod_help_text">
                        <div class="cm_ab_flex flex p-4 mb-4 text-3xl text-blue-700 italic text-left rounded-lg dark:bg-blue-200 dark:text-blue-800" role="alert">
                            <div>
                                <span style="font-size:14px;"><?= $available_pincode_data['setting']['cod_help_text'] ?></span>
                            </div>
                        </div>
                    </li>
                </ul>
               
            </span>
        </div>
    </div>
</div>