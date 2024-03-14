<script type="text/x-template" id="sby-dummy-lightbox-component">
    <div id="sbc_lightbox" class="ctf-lightbox-dummy-ctn  sbc_lightbox ctf-lightbox-transitioning" :data-visibility="dummyLightBoxScreen" :class="[(!$parent.valueIsEnabled(customizerFeedData.settings.disablelightbox) ? 'sbc_lightbox-active' : 'sbc_lightbox-disabled')]" :data-playerratio="customizerFeedData.settings.playerratio">
            <div class="sbc_lb-outerContainer">
                <div class="sbc-lb-player-img">
                    <img src="<?php echo CUSTOMIZER_PLUGIN_URL . 'assets/img/sby_lightbox_player.png'; ?>" alt="">
                </div>
                <div class="sbc-lb-video-details">
                    <div class="sbc-lb-video-header" :class="{'no-subscribe-bar': !customizerFeedData.settings.enablesubscriberlink}">
                        <span class="sbc-lb-video-header-left" v-if="customizerFeedData.settings.enablesubscriberlink">
                            <span class="sbc-channel-info">
                                <img src="<?php echo CUSTOMIZER_PLUGIN_URL . 'assets/img/sby_channel_logo.png'; ?>" alt="">
                                <span class="sbc-channel-name">
                                    <span class="video-title">@GoPro</span>
                                    <span v-if="customizerHeaderData" v-html="customizerHeaderData.statistics.subscriberCount" class="sbc-subscriber-count"></span>
                                </span>
                            </span>
                            <span class="sbc-channel-subscribe-btn">
                                <button>
                                    <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6.91732 10.5L10.3773 8.50004L6.91732 6.50004V10.5ZM14.624 5.28004C14.7107 5.59337 14.7707 6.01337 14.8107 6.54671C14.8573 7.08004 14.8773 7.54004 14.8773 7.94004L14.9173 8.50004C14.9173 9.96004 14.8107 11.0334 14.624 11.72C14.4573 12.32 14.0707 12.7067 13.4707 12.8734C13.1573 12.96 12.584 13.02 11.704 13.06C10.8373 13.1067 10.044 13.1267 9.31065 13.1267L8.25065 13.1667C5.45732 13.1667 3.71732 13.06 3.03065 12.8734C2.43065 12.7067 2.04398 12.32 1.87732 11.72C1.79065 11.4067 1.73065 10.9867 1.69065 10.4534C1.64398 9.92004 1.62398 9.46004 1.62398 9.06004L1.58398 8.50004C1.58398 7.04004 1.69065 5.96671 1.87732 5.28004C2.04398 4.68004 2.43065 4.29337 3.03065 4.12671C3.34398 4.04004 3.91732 3.98004 4.79732 3.94004C5.66398 3.89337 6.45732 3.87337 7.19065 3.87337L8.25065 3.83337C11.044 3.83337 12.784 3.94004 13.4707 4.12671C14.0707 4.29337 14.4573 4.68004 14.624 5.28004Z" fill="white"/>
                                    </svg>
                                    Subscribe
                                </button>
                            </span>
                        </span>
                        <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15.875 2.21125L14.2888 0.625L8 6.91375L1.71125 0.625L0.125 2.21125L6.41375 8.5L0.125 14.7888L1.71125 16.375L8 10.0863L14.2888 16.375L15.875 14.7888L9.58625 8.5L15.875 2.21125Z" fill="white"/>
                        </svg>
                    </div>
                    <div class="sbc-lb-video-description">
                        <p>The MACBA Girls take on the iconic plaza 💪 "Girls with Attitude" filmed and edited by GoPro Family member Gonzalo Gonzalez De Vega with GoPro HERO9 Black and MAX. @Macba Life </p>
                        <p>Featured skaters: </p>
                        <p>@catadiazsk8 @andreabntzz @marsbarreramauri @biggestyle @indy.pendent @raisaabal & @camilaruiztac</p>
                        <p>Film & Edit by @gochiestrella with GoPro</p>
                        <p>Special thanks to @doloresmagazine & @asiplanchaba</p>
                        <p>----------------------------------------------------------------</p>
                        <p>Want to snag products used in this video?</p>
                        <p>HERO9 Black: https://gopro.com/en/us/shop/cameras/hero9-black/CHDHX-901-master.html</p>
                        <p>MAX: https://gopro.com/en/us/shop/cameras/max/CHDHZ-202-master.html</p>
                        <p>-----------------------------------------------------------------</p>
                        <p>Shot 100% on GoPro: https://bit.ly/3mSwpdV</p>
                        <p>Get stoked and subscribe: http://goo.gl/HgVXpQ</p>
                        <br>
                        <p>Music Courtesy of Epidemic Sound</p>
                        <p>https://www.epidemicsound.com/</p>
                        <p>For more from GoPro, follow us:</p>
                        <p>Facebook: https://www.facebook.com/gopro</p>
                        <p>Twitter: https://twitter.com/gopro</p>
                        <p>Instagram: https://instagram.com/gopro</p>
                        <p>Tumblr: http://gopro.tumblr.com/</p>
                        <p>Pinterest: http://www.pinterest.com/gopro </p>
                        <p>Inside Line: https://gopro.com/news</p>
                        <p>GoPro: https://gopro.com/channel/</p>
                        <p>#GoPro</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>