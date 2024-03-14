jQuery(document).ready(function(){
	// Add modal for SEO meta box
	siteseo_add_metaboxe_modal();  
});

// Add modal for SEO metabox
function siteseo_add_metaboxe_modal(){
	
	var modalDiv = jQuery(`
	<style id="siteseo-universal-metabox-css">${siteseo_universal_metabox_css()}</style>
	<div id="siteseo_cpt" class="siteseo-universal-metabox-modal">
		<div id="siteseo-metabox-container">
			<div class="siteseo-metabox-holder">
				<div class="siteseo-metabox-title">
					<h2>${SITESEO_I18N.seo_bar.title}</h2>
					<span class="siteseo-metabox-close-icon">&times<span>
				</div>
				<div class="siteseo-metabox-body">
					<iframe id="siteseo-universal-metabox-iframe" data-src="${SITESEO_DATA.ADMIN_META_URL}&post=${SITESEO_DATA.POST_ID}" style="width:100%;height:100%;border:0;display:none" onload="siteseo_iframe_loaded()"></iframe>
					<div id="siteseo-loader-overlay">
						<div class="siteseo-loader"> </div>&nbsp;&nbsp;
						<span class="siteseo-loader-heading"> Loading...</span>
					</div>
				</div>
			</div>
		</div>
		<div id="siteseo-universal-metabox-icon" role="button" tabindex="0">
			<div class="siteseo-metabox-icon-el">
				<img src="${SITESEO_DATA.SITESEO_URL_ASSETS}/img/logo-24.svg" alt="">
			</div>
		</div>
	</div>`);
	
	jQuery('body').append(modalDiv);
	
	// Toggle Modal
	modalDiv.find('.siteseo-metabox-icon-el').on('click', function(){
		modalDiv.toggleClass('siteseo-show-universal-modal');
		
		var iframe = modalDiv.find('#siteseo-universal-metabox-iframe');
		
		if(iframe.hasClass('siteseo-iframe-loaded') && iframe.attr('src') != ''){
			return;
		}
		
		modalDiv.find('.siteseo-metabox-holder #siteseo-loader-overlay').addClass('siteseo-loader-show');
		iframe.attr('src', iframe.data('src'));
		iframe.addClass('siteseo-iframe-loaded')
		
	});
	
	// Hide modal
	modalDiv.find('.siteseo-metabox-close-icon').on('click', function(){
		modalDiv.removeClass('siteseo-show-universal-modal');
	});
}

function siteseo_iframe_loaded(){
	
	var modalDiv = jQuery('.siteseo-universal-metabox-modal');
	var iframe = modalDiv.find('#siteseo-universal-metabox-iframe');
	
	modalDiv.find('.siteseo-metabox-holder #siteseo-loader-overlay').removeClass('siteseo-loader-show');
	iframe.show();
}

function siteseo_universal_metabox_css(){
	
	var css = `/* Universal metabox style Start */
@keyframes siteseo-icon-toggle{
0%{
	opacity: 0;
	bottom: -20px;
	visibility: hidden;
}
100% {
	opacity: 1;
	bottom: 0px;
	visibility: visible;
}
}

#siteseo_cpt.siteseo-universal-metabox-modal{
--primaryColor: #00308F;
--paragraphColor: #757575;
--fontSize: 13px;
--fontFamily: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto,
	Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
--color: #757575;
--colorDark: #1e1e1e;
--colorAlert: #eb0f00;
--colorWarning: #ffba00;
--colorSuccess: #4ab866;
--colorLowAlert: #e39f48;
--colorPre: #37864b;
--colorIcon: #d7dade;
--lineHeight: 24px;
--titleColor: #3c434a;
--titleFontSize: 20px;
--titleFontWeight: 500;
--titleMargin: 10px 0;
--backgroundPrimary: #00308F;
--backgroundPrimaryHover: #00308F;
--backgroundSecondaryHover: #f0f0f0;
--borderColor: rgb(203 213 225);
--borderColorLight: #dcdcde;
--borderColorLight40: rgba(220, 220, 222, 0.4);
--borderColorCard: #e2e4e7;
--borderColorTab: #c3c4c7;
--white: #ffffff;
--noticeBackgroundColor: #00308f05;
--noticeBorderColor: #00308f45;
}

#siteseo-metabox-container{
position: fixed;
left: 0px;
bottom: 0px;
z-index: 100100;
background-color: rgb(255, 255, 255);
width: 100%;
max-width: 100%;
min-width: 100%;
height: 400px;
box-sizing: border-box;
flex-shrink: 0;
box-shadow: rgba(0, 0, 0, 0.05) 0px 0px 0px 1px, rgba(0, 0, 0, 0.15) 0px 5px 30px 0px, rgba(0, 0, 0, 0.05) 0px 3px 3px 0px;
border-radius: 4px;
border: none;
animation-name: siteseo-icon-toggle;
animation-duration: 200ms;
animation-iteration-count: 1;
animation-fill-mode: forwards;
max-height: calc(100% - 93px);
text-transform: none;
display: none;
}

.siteseo-show-universal-modal #siteseo-metabox-container{
display: block;
}

#siteseo-metabox-container .siteseo-metabox-holder{
height: 100%;	
}

#siteseo-universal-metabox-icon{
position: fixed;
bottom: 10px;
left: 10px;
z-index: 100000;
}

.siteseo-show-universal-modal #siteseo-universal-metabox-icon{
display: none;
}

#siteseo-universal-metabox-icon .siteseo-metabox-icon-el{
background-color: var(--primaryColor);
display: inline-block;
padding: 6px;
border-radius: 50%;
cursor:pointer;
}

#siteseo-universal-metabox-icon .siteseo-metabox-icon-el img{
width: 40px;
}

#siteseo-metabox-container .siteseo-metabox-title{
border-bottom: 1px solid var(--borderColorLight);
padding: 6px 15px;
margin: 0px;
display: flex;
-webkit-box-align: center;
align-items: center;
z-index: 30;
background: rgb(255, 255, 255);
position: relative;
}

#siteseo-metabox-container .siteseo-metabox-title h2{
font-size: 16px !important;
margin: 0px !important;
display: block;
font-weight: 600 !important;
text-align: left;
flex: 1 1 0%;
color: var(--colorDark);
}

.siteseo-metabox-title .siteseo-metabox-close-icon{
font-size: 20px !important;
margin: 0px;
display: inline-flex;
font-weight: 900 !important;
text-align: left;
color: var(--colorDark);
line-height: 1 !important;
padding: 4px 10px 6px;
cursor: pointer;
}

.siteseo-metabox-title .siteseo-metabox-close-icon:hover{
outline: 1px solid var(--primaryColor);
}

#siteseo-metabox-container .siteseo-metabox-body{
display: flex;
height: calc(100% - 40px);
position: relative;
}

/*Metabox loader*/
#siteseo-loader-overlay{
position: absolute;
top: 0;
left: 0;
width: 100%;
height: 100%;
z-index: 9999;
display: none;
font-size: 20px;
font-weight: 500;
justify-content: center;
align-items: center;
background: #fff;
}

#siteseo-loader-overlay.siteseo-loader-show{
display: flex;
}

#siteseo-loader-overlay .siteseo-loader{
border: 4px solid #f3f3f3;
border-top: 4px solid #3498db;
border-radius: 50%;
width: 40px;
height: 40px;
animation: siteseo_spin 1s linear infinite;
}

@keyframes siteseo_spin {
0% {
transform: rotate(0deg);
}
100% {
transform: rotate(360deg);
}
}
/*Metabox loader End*/
/* Universal metabox style End */`;
	
	return css;
}