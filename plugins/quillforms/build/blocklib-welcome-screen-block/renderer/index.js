(()=>{var e={4184:(e,t)=>{var o;!function(){"use strict";var n={}.hasOwnProperty;function a(){for(var e=[],t=0;t<arguments.length;t++){var o=arguments[t];if(o){var i=typeof o;if("string"===i||"number"===i)e.push(o);else if(Array.isArray(o)){if(o.length){var r=a.apply(null,o);r&&e.push(r)}}else if("object"===i){if(o.toString!==Object.prototype.toString&&!o.toString.toString().includes("[native code]")){e.push(o.toString());continue}for(var c in o)n.call(o,c)&&o[c]&&e.push(c)}}}return e.join(" ")}e.exports?(a.default=a,e.exports=a):void 0===(o=function(){return a}.apply(t,[]))||(e.exports=o)}()}},t={};function o(n){var a=t[n];if(void 0!==a)return a.exports;var i=t[n]={exports:{}};return e[n](i,i.exports,o),i.exports}o.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return o.d(t,{a:t}),t},o.d=(e,t)=>{for(var n in t)o.o(t,n)&&!o.o(e,n)&&Object.defineProperty(e,n,{enumerable:!0,get:t[n]})},o.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),o.r=e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})};var n={};(()=>{"use strict";o.r(n);const e=window.qf.blocks,t=(window.wp.element,window.qf.rendererCore),a=window.React,i=window.wp.data,r=window.lodash,c=window.emotion;var l=o(4184),s=o.n(l);const m=e=>{let{attributes:t,isPreview:o}=e;return React.createElement("div",{className:s()("renderer-core-block-attachment",c.css`
					${"split-right"!==t.layout&&"split-left"!==t.layout&&`\n\t\t\t\t\tmax-width: ${t?.attachmentMaxWidth};\n\t\t\t\t\tmargin: auto;\n\t\t\t\t\ttext-align: center;\n\t\t\t\t\t`}
					overflow: hidden;
				`)},t.attachment&&t.attachment.url?React.createElement("img",{alt:"",src:t.attachment.url,className:s()("renderer-core-block-attachment__image",c.css`
							${"split-right"!==t.layout&&"split-left"!==t.layout&&`border-radius: ${t.attachmentBorderRadius};\n\t\t\t\t\t\t\t margin: auto;\n\t\t\t\t\t\t\t`}
						`)}):React.createElement(React.Fragment,null,o&&React.createElement("div",{className:"renderer-core-block-attachment__placeholder"},React.createElement("svg",{className:"renderer-core-block-attachment__placeholder-icon",focusable:"false",viewBox:"0 0 24 24",role:"presentation"},React.createElement("circle",{cx:"12",cy:"12",r:"3.2"}),React.createElement("path",{d:"M9 2L7.17 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2h-3.17L15 2H9zm3 15c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"})))))},u=e=>{let{isSticky:o,buttonText:n,next:a,theme:i}=e;const r=(0,t.useMessages)(),l="ontouchstart"in window||navigator.maxTouchPoints>0||navigator.msMaxTouchPoints>0;return React.createElement("div",{className:s()("qf-welcome-screen-block__action-wrapper",{"is-sticky":o},c.css`
					& {
						display: flex;
						justify-content: center;
						align-items: center;
						margin-top: 20px;
					}
					// &.is-sticky {
					// 	position: absolute;
					// 	bottom: 0;
					// 	right: 0;
					// 	left: 0;
					// 	width: 100%;
					// 	background-color: rgba(0, 0, 0, 0.05);
					// 	box-shadow: rgba(0, 0, 0, 0.1) 0 -1px;
					// 	height: 70px;
					// 	display: flex;
					// 	align-items: center;
					// 	justify-content: center;

					// 	.qf-welcome-screen-block__action {
					// 		margin: 0 auto;
				`)},React.createElement("div",{className:"qf-welcome-screen-block__action"},React.createElement(t.Button,{theme:i,onClick:a},n)),React.createElement("div",{className:s()("qf-welcome-screen-block__action-helper-text",c.css`
						color: ${i.questionsColor};
						font-size: 12px;
					`)},!l&&React.createElement(t.HTMLParser,{value:r["label.hintText.enter"]})))},p={display:e=>{let{attributes:o}=e;const{isPreview:n,deviceWidth:l}=(0,t.useFormContext)(),[p,d]=(0,a.useState)(!1),[h,f]=(0,a.useState)(!1),b=(0,t.useBlockTheme)(o.themeId),g=(0,a.useRef)(),w=(0,a.useRef)(),{goToBlock:y}=(0,i.useDispatch)("quillForms/renderer-core"),{walkPath:v}=(0,i.useSelect)((e=>({walkPath:e("quillForms/renderer-core").getWalkPath()})));(0,a.useEffect)((()=>(d(!0),()=>d(!1))),[]);let x=r.noop;return v[0]&&v[0].id&&(x=()=>y(v[0].id)),React.createElement("div",{className:c.css`
				height: 100%;
				position: relative;
				outline: none;
			`,ref:g,tabIndex:"0",onKeyDown:e=>{"Enter"===e.key&&(e.stopPropagation(),x())}},React.createElement("div",{className:s()("qf-welcome-screen-block__wrapper","blocktype-welcome-screen-block",`renderer-core-block-${o?.layout}-layout`,{"with-sticky-footer":h,active:p},c.css`
						& {
							position: absolute;
							top: 0;
							left: 0;
							right: 0;
							bottom: 0;
							z-index: 6;
							display: flex;
							${("stack"===o.layout||"mobile"===l&&("float-left"===o.layout||"float-right"===o.layout))&&"flex-direction: column;\n\t\t\t\t\t\t\t.qf-welcome-screen-block__content-wrapper {\n\n\t\t\t\t\t\t\t\tposition: absolute;\n\t\t\t\t\t\t\t\ttop: 0;\n\t\t\t\t\t\t\t\tright: 0;\n\t\t\t\t\t\t\t\tleft: 0;\n\t\t\t\t\t\t\t}"}
							justify-content: center;
							width: 100%;
							height: 100%;
							overflow-y: auto;
							opacity: 0;
							visibility: hidden;
							transition: all 0.4s ease-in-out;
							-webkit-transition: all 0.4s ease-in-out;
							-moz-transition: all 0.4s ease-in-out;
						}

						&.active {
							opacity: 1;
							visibility: visible;
						}
						// &.with-sticky-footer {
						// 	display: block;
						// 	.qf-welcome-screen-block__content-wrapper {
						// 		height: calc(100% - 70px);

						// 	}
						// }
						.qf-welcome-screen-block__content-wrapper {
							display: flex;
							flex-direction: column;
							justify-content: center;
							max-width: 700px;
							padding: 30px;
							word-wrap: break-word;
							text-align: center;
							margin-right: auto;
							margin-left: auto;
							min-height: 100%;
						}
					`)},React.createElement("div",{className:"qf-welcome-screen-block__content-wrapper"},React.createElement("div",{className:"qf-welcome-screen-block__content",ref:w},("stack"===o.layout||"mobile"===l&&("float-left"===o.layout||"float-right"===o.layout))&&React.createElement(m,{isPreview:n,attributes:o}),React.createElement("div",{className:c.css`
								margin-top: 25px;
							`},o?.label&&React.createElement("div",{className:s()("renderer-components-block-label",c.css`
										color: ${b.questionsColor};
										font-family: ${b.questionsLabelFont};
										@media ( min-width: 768px ) {
											font-size: ${b.questionsLabelFontSize.lg} !important;
											line-height: ${b.questionsLabelLineHeight.lg} !important;
										}
										@media ( max-width: 767px ) {
											font-size: ${b.questionsLabelFontSize.sm} !important;
											line-height: ${b.questionsLabelLineHeight.sm} !important;
										}
									`)},React.createElement(t.HTMLParser,{value:o.label})),o?.description&&""!==o.description&&React.createElement("div",{className:s()("renderer-components-block-description",c.css`
												color: ${b.questionsColor};
												font-family: ${b.questionsDescriptionFont};
												@media ( min-width: 768px ) {
													font-size: ${b.questionsDescriptionFontSize.lg} !important;
													line-height: ${b.questionsDescriptionLineHeight.lg} !important;
												}
												@media ( max-width: 767px ) {
													font-size: ${b.questionsDescriptionFontSize.sm} !important;
													line-height: ${b.questionsDescriptionLineHeight.sm} !important;
												}
											`)},React.createElement(t.HTMLParser,{value:o.description})),o.customHTML&&React.createElement("div",{className:s()("renderer-components-block-custom-html",c.css`
											color: ${b.questionsColor};
										`),dangerouslySetInnerHTML:{__html:o?.customHTML}})),React.createElement(u,{theme:b,next:x,isSticky:h,buttonText:o.buttonText}))),("stack"!==o.layout&&"mobile"!==l||"mobile"===l&&("split-left"===o.layout||"split-right"===o.layout))&&React.createElement("div",{className:s()("renderer-core-block-attachment-wrapper",c.css`
								img {
									object-position: ${100*o?.attachmentFocalPoint?.x}%
										${100*o?.attachmentFocalPoint?.y}%;
								}
							`)},React.createElement(m,{isPreview:n,attributes:o}))))}},{name:d}={name:"welcome-screen",attributes:{buttonText:{type:"string",default:"Let's start!"}},supports:{editable:!1,required:!1,attachment:!0,description:!0,logic:!1}};(0,e.setBlockRendererSettings)(d,p)})(),(window.qf=window.qf||{}).blocklibWelcomeScreenBlockRenderer=n})();