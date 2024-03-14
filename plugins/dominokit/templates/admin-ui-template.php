<?php $plugin_version = $GLOBALS['version']; ?>
<div id="admin-ui-option">
    <div class="container-fluid">
        <div class="row row-wookit">
            <div class="col-12">
                <div class="page-title">
                    <div class="woo-col-6">
                        <i>
                            <img src="<?php echo DOMKIT_IMAGES . '/svg/toolbox-icon.svg' ?>">
                        </i>
                        <span>
                        <?php echo esc_html__('Woocommerce toolkit', 'dominokit') ?>
                        </span>
                    </div>

                    <div class="woo-col-6 woo-plugin-version">
                        <span>
                            <?php
                            $current_version = sprintf(esc_html__('Current version: %s', 'dominokit'), $plugin_version);
                            echo wp_kses_post($current_version);
                            ?>
                        </span>
                    </div>

                </div>
            </div>
            <div class="col-3">
                <section class="side-box">
                    <nav class="categories">
                        <ul>
                            <li v-for="(option, index) in options">
                                <a @click.prevent="isActivated = index" href="#"
                                   :class="{'active': isActivated === index}">
                                    <span>{{ option.title }}</span>
                                    <div class="icon"></div>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </section>
            </div>
            <div class="col-9">
                <div class="wookit-content" v-if="isActivated === index"
                     v-for="(option, index) in options">
                    <transition-group name="fade-up" target="div" appear>
                        <div class="content-tab" :key="index">
                            <div class="wookit-title">
                                <span>
                                    {{ option.title }}
                                </span>
                                <div class="wookit-description">
                                    {{ option.description }}
                                </div>
                            </div>
                            <div class="wookit-switch" v-if="option.tab1">
                                <div class="opt-col-6">
                                    <h3><?= __('Moving non-existent products to the bottom of the store list', 'dominokit') ?></h3>
                                    <p><?= __('If your template has this feature, please disable it', 'dominokit') ?></p>
                                </div>
                                <div class="opt-col-6 opt-title">
                                    <label class="switch">
                                        <input type="checkbox" @click="toggleCheckbox" v-model="checkbox">
                                        <div class="slider round"></div>
                                    </label>
                                </div>
                            </div>

                            <div class="wookit-switch" v-if="option.tab1">
                                <div class="opt-col-6">
                                    <h3><?= __('Changing the default WooCommerce button (add to cart)', 'dominokit') ?></h3>
                                    <p><?= __('If your template has this feature, please disable it', 'dominokit') ?></p>
                                </div>
                                <div class="opt-col-6 opt-title">
                                    <label class="switch">
                                        <input type="checkbox" @click="toggleWooCart" v-model="wooCart">
                                        <div class="slider round"></div>
                                    </label>
                                </div>
                                <transition name="fade-up" target="div" appear>
                                    <div class="form-group btnWooCart" v-if="wooCart">
                                        <label for="text-cart"><?php echo esc_html__('Add to cart button', 'dominokit'); ?></label>
                                        <input type="text" id="text-cart"
                                               placeholder="<?php echo esc_html__('Add to cart button text', 'dominokit'); ?>"
                                               v-model="txtBtnCart" @blur="toggleWooCartTxt">
                                    </div>
                                </transition>
                            </div>


                            <div class="wookit-switch" v-if="option.tab1">
                                <div class="dominokit-pro" v-if="!isPro">
                                    <a href="#" @click.prevent="switchPro">
                                        <div class="dominokitTagPro tagKitPro1">
                                            <p><?php echo esc_html__('Upgrade to pro', 'dominokit') ?></p>
                                        </div>
                                    </a>
                                </div>

                                <div class="dominokit-pro" v-else-if="!isLicense">
                                    <a :href="licenseUrl">
                                        <div class="dominokitTagPro tagKitLicense1">
                                            <p><?php echo esc_html__('License registration', 'dominokit') ?></p>
                                        </div>
                                    </a>
                                </div>

                                <div class="opt-col-6">
                                    <h3><?= __('Add cart button address', 'dominokit') ?></h3>
                                    <p><?= __('Be sure to enter the internal address (for the external address, you must define a foreign product', 'dominokit') ?></p>
                                </div>
                                <div class="opt-col-6 opt-title">
                                    <label class="switch">
                                        <input type="checkbox" @click="toggleWooSingle" v-model="wooSingle">
                                        <div class="slider round"></div>
                                    </label>
                                </div>

                                <transition name="fade-up" target="div" appear>
                                    <div class="form-group btnWooCart" v-if="wooSingle">
                                        <label for="url-cart"><?= __('Add to cart button url', 'dominokit') ?></label>
                                        <input class="form-control  dominokit-url" type="text" id="url-cart"
                                               v-model="urlBtnCart"
                                               placeholder="<?= __('Add to cart button url', 'dominokit') ?>"
                                               @blur="toggleWooSingleUrl($event, option)">
                                    </div>
                                </transition>
                            </div>

                            <div class="wookit-switch" v-if="option.tab1">
                                <div class="dominokit-pro" v-if="!isPro">
                                    <a href="#" @click.prevent="switchPro">
                                        <div class="dominokitTagPro tagKitPro2">
                                            <p><?php echo esc_html__('Upgrade to pro', 'dominokit') ?></p>
                                        </div>
                                    </a>
                                </div>

                                <div class="dominokit-pro" v-else-if="!isLicense">
                                    <a :href="licenseUrl">
                                        <div class="dominokitTagPro tagKitLicense2">
                                            <p><?php echo esc_html__('License registration', 'dominokit') ?></p>
                                        </div>
                                    </a>
                                </div>

                                <div class="opt-col-6">
                                    <h3><?= __('Hide price for guest user', 'dominokit') ?></h3>
                                    <p><?= __('Enable hiding product prices for guests', 'dominokit') ?></p>
                                </div>
                                <div class="opt-col-6 opt-title">
                                    <label class="switch">
                                        <input type="checkbox" @click="toggleWooHidePrice" v-model="wooHidePrice">
                                        <div class="slider round"></div>
                                    </label>
                                </div>

                                <transition name="fade-up" target="div" appear>
                                    <div class="form-group btnWooCart" v-if="wooHidePrice">
                                        <label for="btn-hide-price"><?= __('Button text instead of price', 'dominokit') ?></label>
                                        <input class="form-control" type="text" id="btn-hide-price"
                                               v-model="btnHidePrice"
                                               placeholder="<?= __('Button text instead of price', 'dominokit') ?>"
                                               @blur="toggleWooPriceTxt">
                                    </div>
                                </transition>

                                <transition name="fade-up" target="div" appear>
                                    <div class="form-group btnWooCart" v-if="wooHidePrice">
                                        <label for="btn-hide-price"><?= __('The address of the button replaces the price', 'dominokit') ?></label>
                                        <input class="form-control dominokit-url" type="text" id="btn-hide-price"
                                               v-model="btnHidePriceUrl"
                                               placeholder="<?= __('The address of the button replaces the price', 'dominokit') ?>"
                                               @blur="toggleWooPriceUrl($event, option)">
                                    </div>
                                </transition>
                            </div>

                            <div class="wookit-switch" v-if="option.tab1">
                                <div class="dominokit-pro" v-if="!isPro">
                                    <a href="#" @click.prevent="switchPro">
                                        <div class="dominokitTagPro tagKitPro3">
                                            <p><?php echo esc_html__('Upgrade to pro', 'dominokit') ?></p>
                                        </div>
                                    </a>
                                </div>

                                <div class="dominokit-pro" v-else-if="!isLicense">
                                    <a :href="licenseUrl">
                                        <div class="dominokitTagPro tagKitLicense3">
                                            <p><?php echo esc_html__('License registration', 'dominokit') ?></p>
                                        </div>
                                    </a>
                                </div>

                                <div class="opt-col-6">
                                    <h3><?= __('replacing the desired text instead of the price (the price should be zero)', 'dominokit') ?></h3>
                                    <p><?= __('If the price is zero or empty, the desired text will be displayed instead of the price', 'dominokit') ?></p>
                                </div>
                                <div class="opt-col-6 opt-title">
                                    <label class="switch">
                                        <input type="checkbox" @click="toggleWooReplacePrice" v-model="wooReplacePrice">
                                        <div class="slider round"></div>
                                    </label>
                                </div>

                                <transition name="fade-up" target="div" appear>
                                    <div class="form-group btnWooCart" v-if="wooReplacePrice">
                                        <label for="btn-hide-price"><?= __('Custom text', 'dominokit') ?></label>
                                        <input class="form-control" type="text" id="btn-hide-price"
                                               v-model="txtReplacePrice"
                                               placeholder="<?= __('Enter your desired text', 'dominokit') ?>"
                                               @blur="toggleWooReplaceTxt">
                                    </div>
                                </transition>
                            </div>


                            <div class="wookit-switch" v-if="option.tab2">
                                <div class="opt-col-6">
                                    <h3><?php echo esc_html__('Solarization of WooCommerce', 'dominokit') ?></h3>
                                    <p><?php echo esc_html__('Solarization of WooCommerce and WordPress dates', 'dominokit') ?></p>
                                </div>
                                <div class="opt-col-6 opt-title">
                                    <label class="switch">
                                        <input type="checkbox" @click="toggleShamsi" v-model="wooShamsi">
                                        <div class="slider round"></div>
                                    </label>
                                </div>
                            </div>

                            <div class="wookit-switch" v-if="option.tab2">
                                <div class="opt-col-6">
                                    <h3><?php echo esc_html__('datepicker for WooCommerce', 'dominokit') ?></h3>
                                    <p><?php echo esc_html__('Adding the Persian calendar to WooCommerce', 'dominokit') ?></p>
                                </div>
                                <div class="opt-col-6 opt-title">
                                    <label class="switch">
                                        <input type="checkbox" @click="toggleDatepicker" v-model="wooDatepicker">
                                        <div class="slider round"></div>
                                    </label>
                                </div>
                            </div>

                            <div class="dominokitProduct" v-if="option.tab3">
                                <div class="boxProduct" v-for="(product, index) in products">
                                    <a target="_blank" class="boxLink" :href="product.link"></a>
                                    <div class="boxImage">
                                        <img :src="product.thumb" :alt="product.title">
                                    </div>
                                    <div class="boxTitle">
                                        <h3>{{ product.title }}</h3>
                                    </div>
                                    <div class="boxInfo">
                                        <div class="boxPrice">
                                            {{ product.price }}
                                            <span>تومان</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="!isPro">
                                <div class="dominokitPro" v-if="option.tab4">
                                    <div class="boxPro">
                                        <div class="boxProTitle">
                                            <h2>از انتخاب افزونه دومینوکیت سپاسگزاریم</h2>
                                        </div>
                                        <div class="boxProDesc">
                                            <p>
                                                با ارتقا به نسخه پرو میتوانید به ویژگی ها و امکانات جذاب تری از افزونه
                                                دومینوکیت
                                                دسترسی داشته باشید و ووکامرس خود را سفارشی کنید. از جمله ویژگی های
                                                دومینوکیت
                                                پرو
                                                میتوان به موارد زیر اشاره کرد:
                                            </p>
                                        </div>
                                        <div class="boxProList">
                                            <ul>
                                                <li>جایگزین کردن متن دلخواه به جای قیمت "صفر"</li>
                                                <li>عدم نمایش قیمت برای کاربر مهمان</li>
                                                <li>تغییر متن و لینک دکمه "افزودن به سبد خرید"</li>
                                                <li>امکانات نسخه جدید (به زودی)</li>
                                            </ul>
                                        </div>

                                        <div class="boxProButton">
                                            <a href="https://www.zhaket.com/web/dominokit-plugin" target="_blank">دریافت
                                                دومینوکیت پرو</a>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </transition-group>
                </div>
            </div>
        </div>
    </div>
</div>
