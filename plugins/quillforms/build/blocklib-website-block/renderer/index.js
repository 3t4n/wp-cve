(()=>{var e={4184:(e,t)=>{var r;!function(){"use strict";var o={}.hasOwnProperty;function n(){for(var e=[],t=0;t<arguments.length;t++){var r=arguments[t];if(r){var a=typeof r;if("string"===a||"number"===a)e.push(r);else if(Array.isArray(r)){if(r.length){var s=n.apply(null,r);s&&e.push(s)}}else if("object"===a){if(r.toString!==Object.prototype.toString&&!r.toString.toString().includes("[native code]")){e.push(r.toString());continue}for(var i in r)o.call(r,i)&&r[i]&&e.push(i)}}}return e.join(" ")}e.exports?(n.default=n,e.exports=n):void 0===(r=function(){return n}.apply(t,[]))||(e.exports=r)}()}},t={};function r(o){var n=t[o];if(void 0!==n)return n.exports;var a=t[o]={exports:{}};return e[o](a,a.exports,r),a.exports}r.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return r.d(t,{a:t}),t},r.d=(e,t)=>{for(var o in t)r.o(t,o)&&!r.o(e,o)&&Object.defineProperty(e,o,{enumerable:!0,get:t[o]})},r.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),r.r=e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})};var o={};(()=>{"use strict";r.r(o);const e=window.qf.blocks,t=(window.wp.element,window.qf.rendererCore),n=window.React,a=window.tinycolor;var s=r.n(a);const i=window.emotion;var l=r(4184),d=r.n(l);const p={display:e=>{const{id:r,attributes:o,setIsValid:a,setIsAnswered:l,setValidationErr:p,showNextBtn:c,val:u,setVal:w,showErrMsg:f,inputRef:h,isTouchScreen:b,setFooterDisplay:g,isPreview:v,isReviewing:m}=e,y=(0,t.useMessages)(),x=(0,t.useBlockTheme)(o.themeId),S=s()(x.answersColor),{required:_,placeholder:C}=o,O=e=>{var t;!0!==_||e&&""!==e?(t=e,!new RegExp("^(https?:\\/\\/)?((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|((\\d{1,3}\\.){3}\\d{1,3}))(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*(\\?[;&a-z\\d%_.~+=-]*)?(\\#[-a-z\\d_]*)?$","i").test(t)&&e?(a(!1),p(y["label.errorAlert.url"])):(a(!0),p(null))):(a(!1),p(y["label.errorAlert.required"]))};return(0,n.useEffect)((()=>{!v&&m||O(u)}),[o]),React.createElement("input",{ref:h,className:d()(i.css`
					& {
						width: 100%;
						border: none;
						outline: none;
						padding-bottom: 8px;
						background: transparent;
						transition: box-shadow 0.1s ease-out 0s;
						box-shadow: ${S.setAlpha(.3).toString()}
							0px 1px !important;
					}

					&::placeholder {
						opacity: 0.3;
						/* Chrome, Firefox, Opera, Safari 10.1+ */
						color: ${x.answersColor};
					}

					&:-ms-input-placeholder {
						opacity: 0.3;
						/* Internet Explorer 10-11 */
						color: ${x.answersColor};
					}

					&::-ms-input-placeholder {
						opacity: 0.3;
						/* Microsoft Edge */
						color: ${x.answersColor};
					}

					&:focus {
						box-shadow: ${S.setAlpha(1).toString()}
							0px 2px !important;
					}

					color: ${x.answersColor};
				`),id:"website-"+r,placeholder:!1===C?"https://":C,onChange:e=>{const t=e.target.value;O(t),w(t),f(!1),""!==t?(c(!0),l(!0)):l(!1)},value:u&&u.length>0?u:"",onFocus:()=>{b&&g(!1)},onBlur:()=>{b&&g(!0)},autoComplete:"off"})}},{name:c}={name:"website",supports:{editable:!0,required:!0,attachment:!0,description:!0,placeholder:!0,defaultValue:!0,logic:!0,theme:!0},attributes:{},logicalOperators:["is","is_not","starts_with","contains","ends_with","not_contains"]};(0,e.setBlockRendererSettings)(c,p)})(),(window.qf=window.qf||{}).blocklibWebsiteBlockRenderer=o})();