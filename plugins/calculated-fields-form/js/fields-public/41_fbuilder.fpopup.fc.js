	$.fbuilder.controls['fpopup']=function(){};
	$.extend(
		$.fbuilder.controls['fpopup'].prototype,
		$.fbuilder.controls['ffields'].prototype,
		{
			title:"",
			titletag:"P",
			ftype:"fpopup",
			fields:[],
			open_onload:false,
			open_onclick:'',
			close_button:true,
			modal:true,
			dragging:false,
			resizing:false,
			position:'center', // center, top-left, top-right, bottom-left, bottom-right
			width:'360px',
			height:'360px',
			columns:1,
			rearrange: 0,
			show:function()
				{
				let w = String(this.width).replace(/[^\d,p,x,\%,\.]/g, ''),
					h = String(this.height).replace(/[^\d,p,x,\%,\.]/g, ''),
					p = '';

				w = 'width:'+(w.length ? w : '90%')+';max-width:90%;'+
					'min-width:'+(w.length ? w : '90%')+';';
				h = (h.length ? 'min-height:'+h+';height:'+h+';' : '')+'max-height:90%;';

				switch ( this.position ) {
					case 'center':
						p = 'top:50%;left:50%;transform:translate(-50%,-50%);';
					break;
					case 'top-left':
						p = 'top:20px;left:20px;';
					break;
					case 'top-right':
						p = 'top:20px;right:20px;';
					break;
					case 'bottom-left':
						p = 'bottom:20px;left:20px;';
					break;
					case 'bottom-right':
						p = 'bottom:20px;right:20px;';
					break;
				}
				return '<div class="fields '+cff_esc_attr(this.csslayout)+' '+this.name+(this.open_onload ? '' : ' hide-strong ')+' cff-popup-field cff-container-field '+'" id="field'+this.form_identifier+'-'+this.index+'">'+
				( this.modal ? '<div class="cff-popup-modal">' : '' )+
				'<div class="cff-popup-container" style="' + w + h + p + (this.resizing ? 'overflow:auto;resize:both;' : '')+'">'+
					'<div class="cff-popup-header"><'+this.titletag+' class="cff-popup-title">'+this.title+(this.close_button ? '</'+this.titletag+'><div class="cff-popup-close ui-icon ui-icon-close" title="close"></div>' : '')+'</div>'+
					'<div id="'+this.name+'" class="cff-popup-fields"></div>'+
					'<div class="clearer"></div>'+
				'</div>'+
				( this.modal ? '</div>' : '' )+
				'</div>';
				},
			after_show: function()
				{
					let me = this,
						pos1 = 0,
						pos2 = 0,
						pos3 = 0,
						pos4 = 0,
						e = $( '.' + me.name + ' .cff-popup-container' );

					function dragMouseDown(evt) {
						evt.preventDefault();
						pos3 = evt.clientX;
						pos4 = evt.clientY;
						$( document ).on( 'mouseup', closeDragElement );
						$( document ).on( 'mousemove', elementDrag );
					}

					function elementDrag(evt) {
						evt.preventDefault();
						// calculate the new cursor position:
						pos1 = pos3 - evt.clientX;
						pos2 = pos4 - evt.clientY;
						pos3 = evt.clientX;
						pos4 = evt.clientY;
						// set the element's new position:
						let o = e.offset(),
							sV = document.documentElement.scrollTop || document.body.scrollTop,
							sH = document.documentElement.scrollLeft || document.body.scrollLeft;

						e.offset({
							top: Math.min( Math.max( o.top-pos2, sV ), (document.documentElement.clientHeight || document.body.clientHeight)+sV - e.height() ),
							left: Math.min( Math.max( o.left-pos1, sH ), (document.documentElement.clientWidth || document.body.clientWidth)+sH - e.width() )
						});
					}

					function closeDragElement() {
						/* stop moving when mouse button is released:*/
						$( document ).off( 'mouseup' );
						$( document ).off( 'mousemove' );
					}

					$.fbuilder.controls['fcontainer'].prototype.after_show.call(this);
					$(document).on('click', '.cff-popup-close', function() {
						$(this).closest('.cff-popup-field').addClass('hide-strong');
					});

					if ( ! /^\s*$/.test( me.open_onclick ) ) {
						let btn = getField( me.open_onclick );
						if ( btn != false ) {
							$( document ).on( 'click', '#'+btn.jQueryRef().find( 'input' ).attr('id'), function(){
								SHOWFIELD(me.name);
							});
						}
					}

					if ( me.close_button ) {
						$(document).on('keyup', function(evt){
							if ( 'Escape' == evt.key ) HIDEFIELD(me.name);
						});
					}

					if ( me.dragging ) {
						e.find( '.cff-popup-header' ).css( 'cursor', 'move' ).on( 'mousedown', dragMouseDown );
					}
				},
			showHideDep:function(toShow, toHide, hiddenByContainer)
				{
					return $.fbuilder.controls['fcontainer'].prototype.showHideDep.call(this, toShow, toHide, hiddenByContainer);
				}
		}
	);