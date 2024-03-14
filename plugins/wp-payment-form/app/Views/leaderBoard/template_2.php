<?php 
$assetUrl = WPPAYFORM_URL . 'assets/images/global'; 
$nodonorData = WPPAYFORM_URL . 'assets/images/empty-cart.svg';
?>
<div class="wpf-leaderboard-temp-one wpf-bg-white wpf-template-wrapper" data-show-total="<?php echo $show_total == 'true' ? 'true' : 'false';?>"   data-show-name="<?php echo $show_name == 'true' ? 'true' : 'false';?>" data-show-avatar="<?php echo $show_avatar == 'true' ? 'true' : 'false';?>">
    <div class="wpf-leaderboard">
        <div class="wpf-user-column">
            <!-- Top 3 donor section start -->
            <div class="wpf-top-donor-card-wrapper">
                <div class="wpf-top-donor-cards">
                    <?php $top = 0; ?>
                    <?php foreach ($topThreeDonars as $key => $topThreeDonar) :
                        $top = $top + 1;
                        $class = "card-" . $top;
                    ?>
                        <div class="wpf-top-donor-card <?php echo $class ?>">
                            <div class="wpf-user-serial">
                                <span class="wpf-user-serial-text"><?php echo $top ?></span>
                            </div>
                            <div class="info">
                                <?php if ($show_avatar == 'true') : ?>
                                    <div class="wpf-user-avatar">
                                        <?php echo get_avatar($topThreeDonar['customer_email'], 96); ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($show_name == 'true') : ?>
                                    <div class="wpf-user-name">
                                        <span class="wpf-user-name-text"><?php echo $topThreeDonar['customer_name'] ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if ($show_total == 'true') : ?>
                                    <div class="wpf-user-amount">
                                        <span class="wpf-text-currency"><?php echo $topThreeDonar['currency']  ?></span>
                                        <span class="wpf-text-amount"><?php echo $topThreeDonar['grand_total'] ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <!-- <div class="wpf-top-donor-card card-two">
                        <div class="wpf-user-serial">
                            <span class="wpf-user-serial-text">1</span>
                        </div>
                        <div class="info">
                            <div class="wpf-user-avatar">
                                <img src="https://secure.gravatar.com/avatar/0d1b0b6b6b6b6b6b6b6b6b6b6b6b6b6b?s=96&amp;d=mm&amp;r=g" alt="User Avatar">
                            </div>
                            <div class="wpf-user-name">
                                <span class="wpf-user-name-text">Json Roy Kobi</span>
                            </div>
                            <div class="wpf-user-amount">
                                <span class="wpf-text-amount">$1000</span>
                            </div>
                        </div>
                    </div>
                    <div class="wpf-top-donor-card card-three">
                        <div class="wpf-user-serial">
                            <span class="wpf-user-serial-text">3</span>
                        </div>
                        <div class="info">
                            <div class="wpf-user-avatar">
                                <img src="https://secure.gravatar.com/avatar/0d1b0b6b6b6b6b6b6b6b6b6b6b6b6b6b?s=96&amp;d=mm&amp;r=g" alt="User Avatar">
                            </div>
                            <div class="wpf-user-name">
                                <span class="wpf-user-name-text">Json Roy Kobi</span>
                            </div>
                            <div class="wpf-user-amount">
                                <span class="wpf-text-amount">$1000</span>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
            <!-- donor filter section -->
            <div class="wpf-donor-filter-section">
                <div class="wpf-search-section">
                    <input type="text" class="wpf-search-input" placeholder=Search donor">
                    <span class="dashicons dashicons-search wpf-search-icon"></span>
                </div>
                <div class="wpf-filter-section">
                    <div class="filter-radio-button">
                        <div class="wpf-radio-button" data-sort-key="created_at" key_value="true">
                            <span class="dashicons dashicons-arrow-up-alt wpf-filter-icon"></span>
                            <input type="radio" id="newest" name="wpf_donation_temp_1" value="newest">
                            <label for="newest">Newest</label>
                        </div>
                        <div class="wpf-radio-button" data-sort-key="created_at" key_value="">
                            <span class="dashicons dashicons-arrow-down-alt wpf-filter-icon"></span>
                            <input type="radio" id="oldest" name="wpf_donation_temp_1" value="oldest">
                            <label for="oldest">Oldest</label>
                        </div>
                        <div class="wpf-radio-button" data-sort-key="grand_total" key_value="true">
                            <span class="dashicons dashicons-businessperson wpf-filter-icon"></span>
                            <input type="radio" id="top_donar" name="wpf_donation_temp_1" value="top_donar">
                            <label for="top_donar">Top Donor</label>
                        </div>
                    </div>
                </div>
            </div>
            <!-- donor list section -->
            <div class="wpf-user" data-per-page="<?php echo $per_page ?>" data-orderby="<?php echo $orderby ?>" data-form_id="<?php echo $form_id ?>">

                <?php
                $donarIndex = 0;
                foreach ($donars as $key => $donor) :

                ?>
                    <div class="wpf-user-row">
                        <div class="wpf-user-serial">
                            <span class="wpf-user-serial-text"><?php echo  ++$donarIndex ?></span>
                        </div>
                        <?php if ($show_avatar == 'true') : ?>
                            <div class="wpf-user-avatar">
                                <?php echo get_avatar($topThreeDonar['customer_email'], 96); ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($show_name == 'true') : ?>
                            <div class="wpf-user-name">
                                <span class="wpf-user-name-text"><?php echo $donor['customer_name'] ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if ($show_total == 'true') : ?>
                            <div class="wpf-user-amount">
                                <span class="wpf-user-amount-text">Amount Donated</span>
                                <span class="wpf-user-amount">
                                    <span class="wpf-text-currency"><?php echo $donor['currency']  ?></span>
                                    <span class="wpf-text-amount"><?php echo $donor['grand_total'] ?></span>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if ($total <= 0) : ?>
                <div class="wpf-no-donor-found">
                    <img src="<?php echo $nodonorData ?>" alt="No Donor Found" class="wpf-no-donor-found-image" style="width: 280px">
                    <p style="background: inherit; color: #000; size: 20px;">No donor found yet!</p>
                </div>
                
            <?php endif; ?>
            <div class="wpf-leaderboard-loader">
                <span class="loader hide"></span>
            </div>
            <?php if ($total > 0) : ?>
                <div class="wpf-leaderboard-load-more-wrapper" >
                    <button class="wpf-load-more <?php echo $has_more_data == false ? 'disabled' : '' ?>">Load More</button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>