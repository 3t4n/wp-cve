<div class="wrap">
    <h1 class="wp-heading-inline">
        <?php echo __(esc_html(get_admin_page_title()), $this->plugin_name); ?>
    </h1>

    <div class="ays-assistant-features-wrap">
        <div class="comparison">
            <table>
                <thead>
                    <tr>
                        <th class="tl tl2"></th>
                        <th class="product" style="background:#69C7F1; border-top-left-radius: 5px; border-left:0px;">
                            <span style="display: block"><?php echo __('Personal',$this->plugin_name)?></span>
                            <img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/avatars/personal_avatar.png'; ?>" alt="Free" title="Free" width="100"/>
                        </th>
                        <th class="product" style="background:#69C7F1;">
                            <span style="display: block"><?php echo  __('Business',$this->plugin_name)?></span>
                            <img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/avatars/business_avatar.png'; ?>" alt="Business" title="Business" width="100"/>
                        </th>
                        <th class="product" style="border-top-right-radius: 5px; border-right:0px; background:#69C7F1;">
                            <span style="display: block"><?php echo __('Developer',$this->plugin_name)?></span>
                            <img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/avatars/pro_avatar.png'; ?>" alt="Developer" title="Developer" width="100"/>
                        </th>
                    </tr>
                    <tr>
                        <th></th>
                        <th class="price-info">
                            <div class="price-now">
                                <span><?php echo __('Free',$this->plugin_name)?></span>
                            </div>
                        </th>
                        <th class="price-info">
                            <!-- <div class="price-now"><span>$49</span></div> -->
                            <div class="price-now"><span style="text-decoration: line-through; color: red;">$49</span>
                            </div>
                            <div class="price-now"><span>$39</span>
                            </div> 
                            <!-- <div class="price-now"><span style="color: red; font-size: 12px;">Until December 31</span>
                            </div> -->
                            <div class="ays-assistant-pracing-table-td-flex">
                                <a href="https://ays-pro.com/wordpress/chatgpt-assistant?utm_source=gpt-free-dashboard&utm_medium=gpt-pro-features&utm_campaign=buy-now-btn" target="_blank" class="price-buy"><?php echo __('Buy now',$this->plugin_name)?><span class="hide-mobile"></span></a>
                                <span><?php echo __('(ONE-TIME PAYMENT)',$this->plugin_name)?></span>
                            </div>
                        </th>
                        <th class="price-info">
                            <!-- <div class="price-now"><span>$129</span></div> -->
                            <div class="price-now"><span span style="text-decoration: line-through; color: red;">$129</span>
                            </div>
                            <div class="price-now"><span>$103</span>
                            </div> 
                            <!-- <div class="price-now"><span style="color: red; font-size: 12px;">Until December 31</span>
                            </div>  -->
                            <div class="ays-assistant-pracing-table-td-flex">
                                <a href="https://ays-pro.com/wordpress/chatgpt-assistant?utm_source=gpt-free-dashboard&utm_medium=gpt-pro-features&utm_campaign=buy-now-btn" target="_blank" class="price-buy"><?php echo __('Buy now',$this->plugin_name)?><span class="hide-mobile"></span></a>
                                <span><?php echo __('(ONE-TIME PAYMENT)',$this->plugin_name)?></span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td colspan="4"><?php echo __('Support for',$this->plugin_name)?></td>
                    </tr>
                    <tr class="compare-row">
                        <td><?php echo __('Support for',$this->plugin_name)?></td>
                        <td><?php echo __('1 site',$this->plugin_name)?></td>
                        <td><?php echo __('5 site',$this->plugin_name)?></td>
                        <td><?php echo __('Unlimited sites',$this->plugin_name)?></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="3"><?php echo __('Update for',$this->plugin_name)?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Update for',$this->plugin_name)?></td>
                        <td><?php echo __('1 months',$this->plugin_name)?></td>
                        <td><?php echo __('12 months',$this->plugin_name)?></td>
                        <td><?php echo __('Lifetime',$this->plugin_name)?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="4"><?php echo __('Support for',$this->plugin_name)?></td>
                    </tr>
                    <tr class="compare-row">
                        <td><?php echo __('Support for',$this->plugin_name)?></td>
                        <td><?php echo __('1 months',$this->plugin_name)?></td>
                        <td><?php echo __('12 months',$this->plugin_name)?></td>
                        <td><?php echo __('Lifetime',$this->plugin_name)?></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Usage for lifetime',$this->plugin_name)?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Usage for lifetime',$this->plugin_name)?></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Responsive design',$this->plugin_name)?></td>
                    </tr>
                    <tr class="compare-row">
                        <td><?php echo __('Responsive design',$this->plugin_name)?></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                    </tr>   
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Dark mode',$this->plugin_name)?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Dark mode',$this->plugin_name)?></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('One click copy',$this->plugin_name)?></td>
                    </tr>
                    <tr class="compare-row">
                        <td><?php echo __('One click copy',$this->plugin_name)?></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Front end chat',$this->plugin_name)?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Front end chat',$this->plugin_name)?></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                    </tr>                
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Permissions by user role',$this->plugin_name)?></td>
                    </tr>
                    <tr class="compare-row">
                        <td><?php echo __('Permissions by user role',$this->plugin_name)?></td>
                        <td><span>-</span></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Text to speech for response',$this->plugin_name)?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Text to speech for response',$this->plugin_name)?></td>
                        <td><span>-</span></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Embedding',$this->plugin_name)?></td>
                    </tr>
                    <tr class="compare-row">
                        <td><?php echo __('Embedding',$this->plugin_name)?></td>
                        <td><span>-</span></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Chat themes',$this->plugin_name)?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Chat themes',$this->plugin_name)?></td>
                        <td><span>-</span></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Chat logs',$this->plugin_name)?></td>
                    </tr>
                    <tr class="compare-row">
                        <td><?php echo __('Chat logs',$this->plugin_name)?></td>
                        <td><span>-</span></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Export chat',$this->plugin_name)?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Export chat',$this->plugin_name)?></td>
                        <td><span>-</span></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Information form',$this->plugin_name)?></td>
                    </tr>
                    <tr class="compare-row">
                        <td><?php echo __('Information form',$this->plugin_name)?></td>
                        <td><span>-</span></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Suggest a title',$this->plugin_name)?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Suggest a title',$this->plugin_name)?></td>
                        <td><span>-</span></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Content generator',$this->plugin_name)?></td>
                    </tr>
                    <tr class="compare-row">
                        <td><?php echo __('Content generator',$this->plugin_name)?></td>
                        <td><span>-</span></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Image generator',$this->plugin_name)?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Image generator',$this->plugin_name)?></td>
                        <td><span>-</span></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('GPT-4',$this->plugin_name)?></td>
                    </tr>
                    <tr class="compare-row">
                        <td><?php echo __('GPT-4',$this->plugin_name)?></td>
                        <td><span>-</span></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('GPT-4 turbo',$this->plugin_name)?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('GPT-4 turbo',$this->plugin_name)?></td>
                        <td><span>-</span></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Google Gemini',$this->plugin_name)?></td>
                    </tr>
                    <tr class="compare-row">
                        <td><?php echo __('Google Gemini',$this->plugin_name)?></td>
                        <td><span>-</span></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                        <td><img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/check.png'; ?>" width="20"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <!-- <td><a href="https://wordpress.org/plugins/ays-chatgpt-assistant/" target="_blank" class="price-buy"><?php // echo __('Download',$this->plugin_name)?><span class="hide-mobile"></span></a></td> -->
                        <td></td>
                        <td>
                            <div class="ays-assistant-pracing-table-td-flex">
                                <a href="https://ays-pro.com/wordpress/chatgpt-assistant?utm_source=gpt-free-dashboard&utm_medium=gpt-pro-features&utm_campaign=buy-now-btn" target="_blank" class="price-buy"><?php echo __('Buy now',$this->plugin_name)?><span class="hide-mobile"></span></a>
                                <span style="line-height:1.5em;font-size:11px;color:#96a3bd;margin-top:5px;"><?php echo __('(ONE-TIME PAYMENT)',$this->plugin_name)?></span>
                            </div>
                        </td>
                        <td>
                            <div class="ays-assistant-pracing-table-td-flex">
                                <a href="https://ays-pro.com/wordpress/chatgpt-assistant?utm_source=gpt-free-dashboard&utm_medium=gpt-pro-features&utm_campaign=buy-now-btn" target="_blank" class="price-buy"><?php echo __('Buy now',$this->plugin_name)?><span class="hide-mobile"></span></a>
                                <span style="line-height:1.5em;font-size:11px;color:#96a3bd;margin-top:5px;"><?php echo __('(ONE-TIME PAYMENT)',$this->plugin_name)?></span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="ays-assistant-sm-content-row-sg">
        <div class="ays-assistant-sm-guarantee-container-sg ays-assistant-sm-center-box-sg">
            <img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL ?>/images/money_back_logo.webp" alt="Best money-back guarantee logo">
            <div class="ays-assistant-sm-guarantee-text-container-sg">
                <h3><?php echo __("30 day money back guarantee !!!", 'chatgpt-assistant'); ?></h3>
                <p>
                    <?php echo __("We're sure that you'll love our ChatGPT Assistant plugin, but, if for some reason, you're not
                    satisfied in the first 30 days of using our product, there is a money-back guarantee and
                    we'll issue a refund.", 'chatgpt-assistant'); ?>
                </p>
            </div>
        </div>
    </div>
</div>

