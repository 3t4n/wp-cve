(()=>{var e={4184:(e,t)=>{var r;!function(){"use strict";var o={}.hasOwnProperty;function n(){for(var e=[],t=0;t<arguments.length;t++){var r=arguments[t];if(r){var a=typeof r;if("string"===a||"number"===a)e.push(r);else if(Array.isArray(r)){if(r.length){var i=n.apply(null,r);i&&e.push(i)}}else if("object"===a){if(r.toString!==Object.prototype.toString&&!r.toString.toString().includes("[native code]")){e.push(r.toString());continue}for(var s in r)o.call(r,s)&&r[s]&&e.push(s)}}}return e.join(" ")}e.exports?(n.default=n,e.exports=n):void 0===(r=function(){return n}.apply(t,[]))||(e.exports=r)}()},3999:(e,t)=>{"use strict";var r=/^[-!#$%&'*+\/0-9=?A-Z^_a-z{|}~](\.?[-!#$%&'*+\/0-9=?A-Z^_a-z`{|}~])*@[a-zA-Z0-9](-*\.?[a-zA-Z0-9])*\.[a-zA-Z](-?[a-zA-Z0-9])+$/;t.G=function(e){if(!e)return!1;if(e.length>254)return!1;if(!r.test(e))return!1;var t=e.split("@");return!(t[0].length>64||t[1].split(".").some((function(e){return e.length>63})))}}},t={};function r(o){var n=t[o];if(void 0!==n)return n.exports;var a=t[o]={exports:{}};return e[o](a,a.exports,r),a.exports}r.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return r.d(t,{a:t}),t},r.d=(e,t)=>{for(var o in t)r.o(t,o)&&!r.o(e,o)&&Object.defineProperty(e,o,{enumerable:!0,get:t[o]})},r.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),r.r=e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})};var o={};(()=>{"use strict";r.r(o);const e=window.qf.blocks,t=(window.wp.element,window.qf.rendererCore),n=(window.wp.data,window.React),a=window.tinycolor;var i=r.n(a);const s=window.emotion;var l=r(4184),p=r.n(l),c=r(3999);const d={display:e=>{const{id:r,attributes:o,setIsValid:a,setIsAnswered:l,setValidationErr:d,showNextBtn:u,val:f,setVal:h,showErrMsg:w,next:m,inputRef:g,isTouchScreen:b,setFooterDisplay:v,isPreview:y,isReviewing:x}=e,S=(0,t.useBlockTheme)(o.themeId),A=(0,t.useMessages)(),_=i()(S.answersColor),{required:k,placeholder:C}=o,O=e=>{!0!==k||e&&""!==e&&0!==e.length?e&&!c.G(e)&&e.length>0?(a(!1),d(A["label.errorAlert.email"])):(a(!0),d(null)):(a(!1),d(A["label.errorAlert.required"]))};return(0,n.useEffect)((()=>{!y&&x||O(f)}),[k]),React.createElement("input",{ref:g,className:p()(s.css`
					& {
						width: 100%;
						border: none !important;
						outline: none;
						padding-bottom: 8px !important;
						padding-left: 0 !important;
						padding-right: 0 !important;
						border-radius: 0 !important;
						background: transparent;
						background-color: transparent !important;
						transition: box-shadow 0.1s ease-out 0s;
						-webkit-appearance: none;
						box-shadow: ${_.setAlpha(.3).toString()}
							0px 1px !important;
					}

					&::placeholder {
						opacity: 0.3;
						/* Chrome, Firefox, Opera, Safari 10.1+ */
						color: ${S.answersColor};
					}

					&:-ms-input-placeholder {
						opacity: 0.3;
						/* Internet Explorer 10-11 */
						color: ${S.answersColor};
					}

					&::-ms-input-placeholder {
						opacity: 0.3;
						/* Microsoft Edge */
						color: ${S.answersColor};
					}

					&:focus {
						box-shadow: ${_.setAlpha(1).toString()}
							0px 2px !important;
						border: none !important;
						outline: none !important;
					}

					color: ${S.answersColor} !important;
				`),id:"email-"+r,type:"email",placeholder:!1===C?A["block.email.placeholder"]:C,onChange:e=>{const t=e.target.value;O(t),h(t),w(!1),t?(l(!0),u(!0)):l(!1)},value:f&&f.length>0?f:"",onFocus:()=>{b&&v(!1)},onBlur:()=>{b&&v(!0)},autoComplete:"off"})}},{name:u}={name:"email",supports:{editable:!0,required:!0,attachment:!0,description:!0,placeholder:!0,defaultValue:!0,logic:!0,theme:!0},attributes:{},logicalOperators:["is","is_not","starts_with","contains","ends_with","not_contains"]};(0,e.setBlockRendererSettings)(u,d)})(),(window.qf=window.qf||{}).blocklibEmailBlockRenderer=o})();