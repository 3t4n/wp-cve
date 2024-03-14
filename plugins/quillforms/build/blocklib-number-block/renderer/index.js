(()=>{var e={4184:(e,r)=>{var t;!function(){"use strict";var o={}.hasOwnProperty;function n(){for(var e=[],r=0;r<arguments.length;r++){var t=arguments[r];if(t){var a=typeof t;if("string"===a||"number"===a)e.push(t);else if(Array.isArray(t)){if(t.length){var i=n.apply(null,t);i&&e.push(i)}}else if("object"===a){if(t.toString!==Object.prototype.toString&&!t.toString.toString().includes("[native code]")){e.push(t.toString());continue}for(var s in t)o.call(t,s)&&t[s]&&e.push(s)}}}return e.join(" ")}e.exports?(n.default=n,e.exports=n):void 0===(t=function(){return n}.apply(r,[]))||(e.exports=t)}()}},r={};function t(o){var n=r[o];if(void 0!==n)return n.exports;var a=r[o]={exports:{}};return e[o](a,a.exports,t),a.exports}t.n=e=>{var r=e&&e.__esModule?()=>e.default:()=>e;return t.d(r,{a:r}),r},t.d=(e,r)=>{for(var o in r)t.o(r,o)&&!t.o(e,o)&&Object.defineProperty(e,o,{enumerable:!0,get:r[o]})},t.o=(e,r)=>Object.prototype.hasOwnProperty.call(e,r),t.r=e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})};var o={};(()=>{"use strict";t.r(o);const e=window.qf.blocks;function r(){return r=Object.assign?Object.assign.bind():function(e){for(var r=1;r<arguments.length;r++){var t=arguments[r];for(var o in t)Object.prototype.hasOwnProperty.call(t,o)&&(e[o]=t[o])}return e},r.apply(this,arguments)}window.wp.element;const n=window.qf.rendererCore,a=window.React,i=window.tinycolor;var s=t.n(i);const l=window.emotion;var p=t(4184),u=t.n(p);const c={display:e=>{const{id:t,attributes:o,setIsValid:i,setIsAnswered:p,setValidationErr:c,showNextBtn:d,blockWithError:b,val:f,setVal:m,showErrMsg:h,inputRef:w,isTouchScreen:g,setFooterDisplay:y,isPreview:v,isReviewing:x}=e,{setMax:S,max:k,setMin:O,min:j,required:M,placeholder:A}=o,C=(0,n.useMessages)(),N=(0,n.useBlockTheme)(o.themeId),q=s()(N.answersColor),E=e=>{!0!==M||0===e||e&&""!==e?S&&k>0&&e>k?(i(!1),c(C["label.errorAlert.maxNum"])):O&&j>=0&&e<j?(i(!1),c(C["label.errorAlert.minNum"])):(i(!0),c(null)):(i(!1),c(C["label.errorAlert.required"]))};(0,a.useEffect)((()=>{!v&&x||E(f)}),[o]);let P={};return g&&(P={type:"number"}),React.createElement("input",r({},P,{ref:w,className:u()(l.css`
					& {
						width: 100%;
						border: none !important;
						outline: none;
						padding-bottom: 8px;
						border-radius: 0 !important;
						background: transparent;
						background-color: transparent !important;
						transition: box-shadow 0.1s ease-out 0s;
						box-shadow: ${q.setAlpha(.3).toString()}
							0px 1px !important;

						-moz-appearance: textfield;
						-webkit-appearance: none;
						&::-webkit-outer-spin-button,
						&::-webkit-inner-spin-button {
							-webkit-appearance: none;
							margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
						}
					}

					&::placeholder {
						opacity: 0.3;
						/* Chrome, Firefox, Opera, Safari 10.1+ */
						color: ${N.answersColor};
					}

					&:-ms-input-placeholder {
						opacity: 0.3;
						/* Internet Explorer 10-11 */
						color: ${N.answersColor};
					}

					&::-ms-input-placeholder {
						opacity: 0.3;
						/* Microsoft Edge */
						color: ${N.answersColor};
					}

					&:focus {
						box-shadow: ${q.setAlpha(1).toString()}
							0px 2px !important;
						border: none !important;
						outline: none !important;
					}

					color: ${N.answersColor};
				`),id:"number-"+t,placeholder:!1===A?C["block.number.placeholder"]:A,onChange:e=>{e.preventDefault();const r=e.target.value;if(isNaN(r))b("Numbers only!");else if(0===r||r){const e=0==r?0:parseInt(r);m(e),h(!1),E(e),p(!0)}else m(""),E(""),p(!1)},value:f||0===f?f:"",onFocus:()=>{g&&y(!1)},onWheel:e=>e.target.blur(),onBlur:()=>{g&&y(!0)},autoComplete:"off"}))}},{name:d}={name:"number",supports:{editable:!0,required:!0,attachment:!0,description:!0,logic:!0,placeholder:!0,defaultValue:!0,theme:!0,numeric:!0,payments:!0},attributes:{setMax:{type:"boolean",default:!1},max:{type:"number",default:0},setMin:{type:"boolean",default:!1},min:{type:"number",default:0}},logicalOperators:["is","is_not","greater_than","lower_than"]};(0,e.setBlockRendererSettings)(d,c)})(),(window.qf=window.qf||{}).blocklibNumberBlockRenderer=o})();