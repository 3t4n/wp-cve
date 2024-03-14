<div class="sbc-feedtemplates-pp-ctn sbc-feedtemplates-ctn sb-fs-boss sbc-center-boss" v-if="viewsActive.feedtypesPopup">
	<div class="sbc-feedtemplates-popup sbc-popup-inside">
		<div class="sbc-popup-cls" @click.prevent.default="activateView('feedtypesPopup')"><svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M14 1.41L12.59 0L7 5.59L1.41 0L0 1.41L5.59 7L0 12.59L1.41 14L7 8.41L12.59 14L14 12.59L8.41 7L14 1.41Z" fill="#141B38"/>
            </svg>
        </div>
        <div class="sbc-source-top sbc-fs">
            <h2>{{selectFeedTypeScreen.updateSourceHeading}}</h2>
            <p class="sbc-feedtemplate-alert sbc-fs">
                <span v-html="svgIcons['info']"></span>
                {{selectFeedTypeScreen.updateHeadingWarning}}
            </p>
            <div class="sbc-feedtemplates sbc-fs" :class="{'sby-free-style' : !sbyIsPro}">
                <div class="sbc-feedtemplates-list">
                    <div :class="['sbc-feedtemplate-el', 'sbc-feed-template-' + feedTypeEl.type]" v-for="(feedTypeEl, feedTypeIn) in feedTypes" :data-active="selectedFeedTypeCustomizer(feedTypeEl.type, true)" :data-feed-type="feedTypeEl.type" @click.prevent.default="chooseCustomizerFeedType(feedTypeEl)">
                        <div class="sbc-feedtemplate-el-img sbc-fs" v-html="svgIcons[feedTypeEl.icon]"></div>
                        <div class="sbc-feedtemplate-el-info sbc-fs">
                            <p class="sb-small-p sb-bold sb-dark-text" v-html="feedTypeEl.title"></p>
                            <span class="sb-caption sb-lightest">{{feedTypeEl.description}}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sbc-feedtemplates sbc-fs sbc-customizer-feedtypes-list" v-if="!sbyIsPro || sbyLicenseNoticeActive">
                <h4 class="sbc-customizer-subheading">
                    {{selectFeedTypeScreen.feedTypeAdvancedHeading}} 
                    <span class="sb-breadcrumb-pro-label" v-if="!sbyLicenseNoticeActive">PRO</span>
                    <span class="sb-breadcrumb-pro-label" v-if="sbyLicenseNoticeActive">Expired</span>
                </h4>
                <div class="sbc-feedtemplates-list">
                    <div class="sbc-feedtype-el" v-for="(feedTypeEl, feedTypeIn) in advancedFeedTypes" :data-active="selectedFeed == feedTypeEl.type && feedTypeEl.type != 'socialwall'" :data-type="feedTypeEl.type" @click.prevent.default="activateProExtPopup(feedTypeEl)">
                        <div class="sbc-feedtype-el-img sbc-fs" v-html="svgIcons[feedTypeEl.icon]" :data-feed-type="feedTypeEl.type"></div>
                        <div class="sbc-feedtype-el-info sbc-fs">
                            <span class="sb-small-p sb-bold sb-dark-text" v-html="feedTypeEl.title"></span>
                            <span class="sb-caption sb-lightest sb-small-text">{{feedTypeEl.description}}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sbc-srcs-popup-btns sbc-fs">
                <button class="sbc-srcs-update sbc-btn sbc-fs sb-btn-orange" @click.prevent.default="updateFeedTypeCustomizer()">
                    <svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M6.08058 8.36133L14.0355 0.406383L15.8033 2.17415L6.08058 11.8969L0.777281 6.59357L2.54505 4.8258L6.08058 8.36133Z" fill="white"/>
                    </svg>
                    <span>{{genericText.update}}</span>
                </button>
                <button class="ctf-fb-source-btn sbc-fs sb-btn-grey" @click.prevent.default="activateView('feedtypesPopup')">
                    <svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16.3337 5.34163L15.1587 4.16663L10.5003 8.82496L5.84199 4.16663L4.66699 5.34163L9.32533 9.99996L4.66699 14.6583L5.84199 15.8333L10.5003 11.175L15.1587 15.8333L16.3337 14.6583L11.6753 9.99996L16.3337 5.34163Z" fill="#141B38"/>
                    </svg>
                    {{genericText.cancel}}
                </button>
            </div>
        </div>
	</div>
</div>