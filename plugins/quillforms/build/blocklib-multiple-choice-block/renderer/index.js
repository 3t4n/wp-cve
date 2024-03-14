(()=>{var e={4184:(e,t)=>{var r;!function(){"use strict";var o={}.hasOwnProperty;function i(){for(var e=[],t=0;t<arguments.length;t++){var r=arguments[t];if(r){var l=typeof r;if("string"===l||"number"===l)e.push(r);else if(Array.isArray(r)){if(r.length){var n=i.apply(null,r);n&&e.push(n)}}else if("object"===l){if(r.toString!==Object.prototype.toString&&!r.toString.toString().includes("[native code]")){e.push(r.toString());continue}for(var a in r)o.call(r,a)&&r[a]&&e.push(a)}}}return e.join(" ")}e.exports?(i.default=i,e.exports=i):void 0===(r=function(){return i}.apply(t,[]))||(e.exports=r)}()}},t={};function r(o){var i=t[o];if(void 0!==i)return i.exports;var l=t[o]={exports:{}};return e[o](l,l.exports,r),l.exports}r.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return r.d(t,{a:t}),t},r.d=(e,t)=>{for(var o in t)r.o(t,o)&&!r.o(e,o)&&Object.defineProperty(e,o,{enumerable:!0,get:t[o]})},r.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),r.r=e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})};var o={};(()=>{"use strict";r.r(o);const e=window.qf.blocks,t=(window.wp.element,window.qf.rendererCore),i=window.React,l=window.lodash,n=window.qf.utils;var a=r(4184),c=r.n(a);const s=window.emotion,d=window.tinycolor;var p=r.n(d);const u=e=>{let{order:r,selected:o,choiceLabel:l,choiceValue:n,clickHandler:a,theme:d,isAnswerLocked:u,blockId:m,correctIncorrectQuiz:f}=e;const{answersColor:h}=d,b=(0,t.useMessages)(),[w,g]=(0,i.useState)(!1);return React.createElement("div",{role:"presentation",className:c()("multipleChoice__optionWrapper",{selected:o,locked:u,clicked:w,correct:u&&f?.enabled&&f?.showAnswersDuringQuiz&&f?.questions?.[m]?.correctAnswers?.includes(n),wrong:u&&f?.enabled&&f?.showAnswersDuringQuiz&&o&&!f?.questions?.[m]?.correctAnswers?.includes(n)},s.css`
					background: ${p()(h).setAlpha(.1).toString()};

					border-color: ${h};
					color: ${h};

					${!u&&`&:hover {\n\t\t\t\t\t\tbackground: ${p()(h).setAlpha(.2).toString()};\n\t\t\t\t\t}`}

					&.selected {
						background: ${p()(h).setAlpha(.75).toString()};
						color: ${p()(h).isDark()?"#fff":"#333"};

						.multipleChoice__optionKey {
							color: ${p()(h).isDark()?"#fff":"#333"};

							border-color: ${p()(h).isDark()?"#fff":"#333"};
						}
					}

					&.locked {
						pointer-events: none;
						cursor: default !important;
					}
				`),onClick:()=>{u||(a(),o||(g(!1),setTimeout((()=>{g(!0)}),0)))}},React.createElement("span",{className:"multipleChoice__optionLabel"},l),React.createElement("span",{className:c()("multipleChoice__optionKey",s.css`
						background: ${p()(h).setAlpha(.1).toString()};
						color: ${h};
						border-color: ${p()(h).setAlpha(.4).toString()};
					`)},React.createElement("span",{className:c()("multipleChoice__optionKeyTip",s.css`
							background: ${h};
							color: ${p()(h).isDark()?"#fff":"#333"};
							${u&&"display: none !important;"}
						`)},b["label.hintText.key"]),r))},m=window.wp.i18n,f=(0,n.keyframes)({"0%":{transform:"scale(1)"},"25%":{transform:"scale(0.94)"},"50%":{transform:"scale(0.98)"},"75%":{transform:"scale(0.95)"},"100%":{transform:"scale(1)"}}),h=n.css`
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

		&.correct {
			background: #7bc178 !important;
			border-color: #5da458 !important;
		}

		&.wrong {
			background: #d4494c;
			border-color: #ffa39e;
			
		}

		&.correct, &.wrong {
			color: #fff;
			.multipleChoice__optionKey {
				background: transparent !important;
				border-color: #fff !important;
				color: #fff !important;
			}
			
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
}`,b=e=>{let{id:r,attributes:o,val:a,isActive:c,correctIncorrectQuiz:s,isAnswerLocked:d,setVal:p,setChoiceClicked:m,checkfieldValidation:f}=e;const{verticalAlign:b,multiple:w,choices:g,themeId:x,max:v,min:y}=o,k=(0,n.useCx)(),C=(0,t.useBlockTheme)(x),_="a".charCodeAt(0),A=b,$=(0,l.cloneDeep)(g).map(((e,t)=>(e.label||(e.label="Choice "+(t+1)),e.selected=!!(a&&a.length>0&&a.includes(e.value)),e.order=(e=>{const t=[e];let r,o,i,l;for(r=0;r<t.length;)t[r]>25&&(l=Math.floor(t[r]/26),t[r+1]=l-1,t[r]%=26),r+=1;for(o="",i=0;i<t.length;i+=1)o=String.fromCharCode(_+t[i])+o;return o})(t),e))),S=(0,i.useCallback)((0,l.debounce)((e=>{const t=Object.values(e).join(""),o=$.findIndex((e=>e.order.toUpperCase()===t.toUpperCase()));document.querySelector(`#block-${r} .multiplechoice__options .multipleChoice__optionWrapper:nth-child(${o+1})`)?.click(),E={}}),100),[$]);let E={};const R=e=>{d||(E[e.code]=String.fromCharCode(e.keyCode),S(E))};return(0,i.useEffect)((()=>{document.getElementById(`block-${r}`)?.addEventListener("keydown",R)}),[]),React.createElement("div",{className:"qf-multiple-choice-block"},React.createElement("div",{className:k("multiplechoice__options",{valigned:A},h)},$&&$.length>0&&$.map(((e,t)=>React.createElement(u,{theme:C,blockId:r,key:`block-multiple-choice-${r}-choice-${e.value}`,choiceLabel:e.label,choiceValue:e.value,order:e.order.toUpperCase(),isAnswerLocked:d,selected:e.selected,correctIncorrectQuiz:s,multiple:w,clickHandler:()=>{((e,t)=>{let r;r=a?.length>0?(0,l.cloneDeep)(a):[],t?s?.enabled&&s?.showAnswersDuringQuiz||(r.splice(r.findIndex((t=>t===e)),1),p(r)):(w?r.push(e):r=[e],p(r),m(!1),setTimeout((()=>{m(!0)}),0)),f(r)})(e.value,e.selected)}})))))};let w;const g={display:e=>{const{id:r,attributes:o,setIsValid:n,setIsAnswered:a,showNextBtn:c,setValidationErr:s,val:d,setVal:p,next:u,isAnswerLocked:m,isActive:f,isAnimating:h,showErrMsg:g,isPreview:x,isReviewing:v,setIsAnswerCorrect:y}=e,{multiple:k,required:C,min:_,max:A}=o,$=(0,t.useMessages)(),S=(0,t.useCorrectIncorrectQuiz)(),[E,R]=(0,i.useState)(null),I=e=>{if(!0!==C||e&&0!==e.length){if((0,l.size)(e)>0&&S?.enabled&&S?.showAnswersDuringQuiz){const t=e.every((e=>S?.questions?.[r]?.correctAnswers?.includes(e)));y(t)}k&&_&&(0,l.size)(e)<_?(n(!1),s($["label.errorAlert.minChoices"])):k&&A&&(0,l.size)(e)>A?(n(!1),s($["label.errorAlert.maxChoices"])):(n(!0),s(null))}else n(!1),s($["label.errorAlert.required"])};return(0,i.useEffect)((()=>()=>clearTimeout(w)),[]),(0,i.useEffect)((()=>{f||clearTimeout(w),f||h||R(null)}),[f,h]),(0,i.useEffect)((()=>{clearTimeout(w),E&&d?.length>0&&!k&&(w=setTimeout((()=>{u()}),600))}),[E]),(0,i.useEffect)((()=>{!x&&v||I(d)}),[o,S]),(0,i.useEffect)((()=>{a(d?.length>0),k&&d?.length>0&&c(!0)}),[d,o]),React.createElement("div",{className:"qf-multiple-choice-block-renderer"},React.createElement(b,{attributes:o,id:r,val:d,isActive:f,isAnswerLocked:m,correctIncorrectQuiz:S,checkfieldValidation:I,setVal:p,setChoiceClicked:e=>{g(!1),R(e)}}))},mergeTag:e=>{let{val:t,attributes:r}=e;const{choices:o}=r,i=t.map((e=>{const t=o.findIndex((t=>t.value===e));let r="Choice "+(t+1);return o[t].label&&(r=o[t].label),r}));return React.createElement(React.Fragment,null,(0,l.join)(i,","))}},{name:x}={name:"multiple-choice",supports:{editable:!0,required:!0,attachment:!0,description:!0,logic:!0,theme:!0,points:!0,payments:!0,choices:!0,correctAnswers:!0},attributes:{choices:{type:"array",items:{type:"object",properties:{value:{type:"string"},label:{type:"string"}}},default:[{value:"124e4567e89b",label:"Choice 1"}]},max:{type:["number","boolean"],default:!1},min:{type:["number","boolean"],default:!1},verticalAlign:{type:"boolean",default:!1},multiple:{type:"boolean"}},logicalOperators:["is","is_not"]};(0,e.setBlockRendererSettings)(x,g)})(),(window.qf=window.qf||{}).blocklibMultipleChoiceBlockRenderer=o})();