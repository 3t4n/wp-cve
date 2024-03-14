(()=>{var e={4184:(e,t)=>{var i;!function(){"use strict";var o={}.hasOwnProperty;function r(){for(var e=[],t=0;t<arguments.length;t++){var i=arguments[t];if(i){var l=typeof i;if("string"===l||"number"===l)e.push(i);else if(Array.isArray(i)){if(i.length){var a=r.apply(null,i);a&&e.push(a)}}else if("object"===l){if(i.toString!==Object.prototype.toString&&!i.toString.toString().includes("[native code]")){e.push(i.toString());continue}for(var n in i)o.call(i,n)&&i[n]&&e.push(n)}}}return e.join(" ")}e.exports?(r.default=r,e.exports=r):void 0===(i=function(){return r}.apply(t,[]))||(e.exports=i)}()}},t={};function i(o){var r=t[o];if(void 0!==r)return r.exports;var l=t[o]={exports:{}};return e[o](l,l.exports,i),l.exports}i.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return i.d(t,{a:t}),t},i.d=(e,t)=>{for(var o in t)i.o(t,o)&&!i.o(e,o)&&Object.defineProperty(e,o,{enumerable:!0,get:t[o]})},i.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),i.r=e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})};var o={};(()=>{"use strict";i.r(o);const e=window.qf.blocks,t=(window.wp.element,window.qf.rendererCore),r=window.React,l=window.lodash,a=window.qf.utils;var n=i(4184),s=i.n(n);const c=window.emotion,d=window.tinycolor;var p=i.n(d);const u=e=>{let{order:i,selected:o,choiceLabel:l,clickHandler:a,theme:n}=e;const{answersColor:d}=n,u=(0,t.useMessages)(),[m,f]=(0,r.useState)(!1);return React.createElement("div",{role:"presentation",className:s()("multipleChoice__optionWrapper",{selected:o,clicked:m},c.css`
					background: ${p()(d).setAlpha(.1).toString()};

					border-color: ${d};
					color: ${d};

					&:hover {
						background: ${p()(d).setAlpha(.2).toString()};
					}

					&.selected {
						background: ${p()(d).setAlpha(.75).toString()};
						color: ${p()(d).isDark()?"#fff":"#333"};

						.multipleChoice__optionKey {
							color: ${p()(d).isDark()?"#fff":"#333"};

							border-color: ${p()(d).isDark()?"#fff":"#333"};
						}
					}
				`),onClick:()=>{a(),o||(f(!1),setTimeout((()=>{f(!0)}),0))}},React.createElement("span",{className:"multipleChoice__optionLabel"},l),React.createElement("span",{className:s()("multipleChoice__optionKey",c.css`
						background: ${p()(d).setAlpha(.1).toString()};
						color: ${d};
						border-color: ${p()(d).setAlpha(.4).toString()};
					`)},React.createElement("span",{className:s()("multipleChoice__optionKeyTip",c.css`
							background: ${d};
							color: ${p()(d).isDark()?"#fff":"#333"};
						`)},u["label.hintText.key"]),i))},m=window.wp.i18n,f=(0,a.keyframes)({"0%":{transform:"scale(1)"},"25%":{transform:"scale(0.94)"},"50%":{transform:"scale(0.98)"},"75%":{transform:"scale(0.95)"},"100%":{transform:"scale(1)"}}),h=a.css`
	& {
		display: flex;
		flex-direction: row;
		flex-wrap: wrap;
		width: 100%;
	}
	&.valigned {
		display: inline-flex;
		flex-direction: column;
		max-width: 100%;
		align-items: stretch;
		width: auto;
	}
	&:not(.valigned) {

		.multipleChoice__optionWrapper {
			max-width: 215px;
			@media(max-width: $break-small) {
				max-width: 480px;
			}
		}
	}

	.multipleChoice__optionWrapper {
		& {
			display: flex;
			flex-direction: row;
			align-items: center;
			min-width: 215px;
			flex: 1 1 0%;
			border-radius: 5px;
			cursor: pointer;
			padding: 10px;
			margin: 0 16px 16px 0;
			box-shadow: none;
			outline: none;
			position: relative;
			border-style: solid;
			border-width: 1px;
			appearance: none;
			text-align: ${(0,m.isRTL)()?"right":"left"};
			user-select: none;
			backface-visibility: hidden;
			-webkit-backface-visibility: hidden;

			@media(max-width: $break-small) {
				margin: 0 16px 10px 0;
				padding: 8px 10px;
				border-radius: 4px;
			}
		}


		&:hover .multipleChoice__optionKey .multipleChoice__optionKeyTip {
			visibility: visible !important;
			opacity: 1 !important;
			transform: none !important;
		}

		&.clicked {
			animation: ${f} 0.4s linear forwards;
		}

		.multipleChoice__optionLabel {
			flex-grow: 1;
			padding-right: 12px;
			overflow-wrap: break-word;
			max-width: calc(100% - 27px);
		}

		.multipleChoice__optionKey {
			& {
				position: relative;
				width: 27px;
				height: 27px;
				display: flex;
				flex-wrap: wrap;
				align-items: center;
				justify-content: center;
				border-radius: 50%;
				border-width: 1px;
				border-style: solid;
				font-size: 14px;
			}

			.multipleChoice__optionKeyTip {
				position: absolute;
				top: -25px;
				font-size: 10px;
				font-weight: bold;
				text-transform: uppercase;
				padding: 2px 3px;
				border-radius: 2px;
				transition: 0.2s all ease-in-out;
				transform: translateY(5px);
				visibility: hidden;
				opacity: 0;
			}
		}
	}
}`,b=e=>{let{id:i,attributes:o,val:n,isActive:s,setVal:c,setChoiceClicked:d,checkfieldValidation:p}=e;const{verticalAlign:m,yesLabel:f,noLabel:b,themeId:g}=o,w=(0,a.useCx)(),x=(0,t.useBlockTheme)(g),y=m,v=[{label:f,value:"yes",order:"Y",selected:"yes"===n},{label:b,value:"no",order:"N",selected:"no"===n}],k=(0,r.useCallback)((0,l.debounce)((e=>{const t=Object.values(e).join(""),o=v.findIndex((e=>e.order.toUpperCase()===t.toUpperCase()));document.querySelector(`#block-${i} .multiplechoice__options .multipleChoice__optionWrapper:nth-child(${o+1})`)?.click(),_={}}),100),[v]);let _={};const C=e=>{_[e.code]=String.fromCharCode(e.keyCode),k(_)};return(0,r.useEffect)((()=>{document.getElementById(`block-${i}`)?.addEventListener("keydown",C)}),[]),React.createElement("div",{className:"qf-multiple-choice-block"},React.createElement("div",{className:w("multiplechoice__options",{valigned:y},h)},v&&v.map(((e,t)=>React.createElement(u,{theme:x,key:`block-multiple-choice-${i}-choice-${e.value}`,choiceLabel:e.label,choiceValue:e.value,order:e.order.toUpperCase(),selected:e.selected,multiple:!1,clickHandler:()=>((e,t)=>{let i;t?(i="",c("")):(i=e,c(i),d(!1),setTimeout((()=>{d(!0)}),0)),p(i)})(e.value,e.selected)})))))};let g;const w={display:e=>{const{id:i,attributes:o,setIsValid:l,setIsAnswered:a,showNextBtn:n,setValidationErr:s,val:c,setVal:d,next:p,isActive:u,isAnimating:m,showErrMsg:f,isPreview:h,isReviewing:w}=e,{required:x,yesLabel:y,noLabel:v}=o,k=(0,t.useMessages)(),[_,C]=(0,r.useState)(null),$=e=>{!0!==x||e&&0!==e.length&&""!==e&&"yes"===e?(l(!0),s(null)):(l(!1),s(k["label.errorAlert.required"]))};return(0,r.useEffect)((()=>()=>clearTimeout(g)),[]),(0,r.useEffect)((()=>{u||clearTimeout(g),u||m||C(null)}),[u,m]),(0,r.useEffect)((()=>{clearTimeout(g),_&&c?.length>0&&(g=setTimeout((()=>{p()}),600))}),[_]),(0,r.useEffect)((()=>{!h&&w||$(c)}),[o]),(0,r.useEffect)((()=>{a(c?.length>0)}),[c,o]),React.createElement("div",{className:"qf-multiple-choice-block-renderer"},React.createElement(b,{attributes:o,id:i,val:c,isActive:u,checkfieldValidation:$,setVal:d,setChoiceClicked:e=>{f(!1),C(e)}}))},mergeTag:e=>{let{val:t,attributes:i}=e;const{yesLabel:o,noLabel:r}=i;return React.createElement(React.Fragment,null,"yes"===t?o:r)}},{name:x}={name:"legal",supports:{editable:!0,required:!0,attachment:!0,description:!0,logic:!0,theme:!0,points:!0,payments:!1,choices:!0,correctAnswers:!1},attributes:{yesLabel:{type:"string",description:"The label for the 'yes' option",default:"Yes"},noLabel:{type:"string",description:"The label for the 'no' option",default:"No"}},logicalOperators:["is","is_not"]};(0,e.setBlockRendererSettings)(x,w)})(),(window.qf=window.qf||{}).blocklibLegalBlockRenderer=o})();