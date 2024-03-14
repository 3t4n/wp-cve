(()=>{var e={4184:(e,r)=>{var t;!function(){"use strict";var o={}.hasOwnProperty;function a(){for(var e=[],r=0;r<arguments.length;r++){var t=arguments[r];if(t){var n=typeof t;if("string"===n||"number"===n)e.push(t);else if(Array.isArray(t)){if(t.length){var s=a.apply(null,t);s&&e.push(s)}}else if("object"===n){if(t.toString!==Object.prototype.toString&&!t.toString.toString().includes("[native code]")){e.push(t.toString());continue}for(var i in t)o.call(t,i)&&t[i]&&e.push(i)}}}return e.join(" ")}e.exports?(a.default=a,e.exports=a):void 0===(t=function(){return a}.apply(r,[]))||(e.exports=t)}()}},r={};function t(o){var a=r[o];if(void 0!==a)return a.exports;var n=r[o]={exports:{}};return e[o](n,n.exports,t),n.exports}t.n=e=>{var r=e&&e.__esModule?()=>e.default:()=>e;return t.d(r,{a:r}),r},t.d=(e,r)=>{for(var o in r)t.o(r,o)&&!t.o(e,o)&&Object.defineProperty(e,o,{enumerable:!0,get:r[o]})},t.o=(e,r)=>Object.prototype.hasOwnProperty.call(e,r),t.r=e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})};var o={};(()=>{"use strict";t.r(o),t.d(o,{rendererSettings:()=>d});const e=window.qf.blocks,r=(window.wp.element,window.qf.rendererCore),a=window.React,n=window.tinycolor;var s=t.n(n);const i=window.emotion;var l=t(4184),c=t.n(l);const{name:p}={name:"short-text",supports:{editable:!0,required:!0,attachment:!0,description:!0,defaultValue:!0,placeholder:!0,logic:!0,theme:!0},attributes:{minCharacters:{type:["boolean","number"],default:!1},setMaxCharacters:{type:"boolean",default:!1},maxCharacters:{type:"number",multipleOf:1}},logicalOperators:["is","is_not","starts_with","ends_with","contains","not_contains"]},d={display:e=>{const{id:t,attributes:o,setIsValid:n,setIsAnswered:l,setValidationErr:p,showNextBtn:d,blockWithError:u,val:h,setVal:f,showErrMsg:w,inputRef:b,isTouchScreen:m,setFooterDisplay:g,isPreview:x,isReviewing:v}=e,y=(0,r.useMessages)(),C=(0,r.useBlockTheme)(o.themeId),S=s()(C.answersColor),{minCharacters:O,maxCharacters:k,setMaxCharacters:A,required:M,placeholder:_}=o,j=e=>{!0!==M||e&&""!==e?A&&k>0&&e?.length>k?(n(!1),p(y["label.errorAlert.maxCharacters"])):!1!==O&&O>0&&e?.length<O?(n(!1),p(y["label.errorAlert.minCharacters"])):(n(!0),p(null)):(n(!1),p(y["label.errorAlert.required"]))};return(0,a.useEffect)((()=>{!x&&v||j(h)}),[o]),React.createElement("input",{ref:b,className:c()(i.css`
					& {
						width: 100%;
						border: none !important;
						border-radius: 0 !important;
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
						color: ${C.answersColor};
					}

					&:-ms-input-placeholder {
						opacity: 0.3;
						/* Internet Explorer 10-11 */
						color: ${C.answersColor};
					}

					&::-ms-input-placeholder {
						opacity: 0.3;
						/* Microsoft Edge */
						color: ${C.answersColor};
					}

					&:focus {
						box-shadow: ${S.setAlpha(1).toString()}
							0px 2px !important;
					}

					color: ${C.answersColor} !important;
					-webkit-appearance: none;
				`),id:"short-text-"+t,placeholder:!1===_?y["block.shortText.placeholder"]:_,onChange:e=>{const r=e.target.value;A&&k>0&&r.length>k?u(y["label.errorAlert.maxCharacters"]):(f(r),w(!1),j(r)),r&&""!==r?(l(!0),d(!0)):l(!1)},value:h?h.toString():"",onFocus:()=>{m&&g(!1)},onBlur:()=>{m&&g(!0)},autoComplete:"off"})}};(0,e.setBlockRendererSettings)(p,d)})(),(window.qf=window.qf||{}).blocklibShortTextBlockRenderer=o})();