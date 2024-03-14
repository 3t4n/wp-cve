	$.fbuilder.typeList.push(
		{
			id:"fpopup",
			name:"Popup",
			control_category:10
		}
	);
	$.fbuilder.controls[ 'fpopup' ]=function(){};
	$.extend(
		$.fbuilder.controls[ 'fpopup' ].prototype,
		$.fbuilder.controls[ 'fcontainer' ].prototype,
		{
			title:"",
			titletag:"P",
			ftype:"fpopup",
			_developerNotes:'',
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
			collapsed:false, // Admin
			display:function()
				{
					return '<div class="fields '+this.name+((this.collapsed) ? ' collapsed' : '')+' '+this.ftype+'" id="field'+this.form_identifier+'-'+this.index+'" title="'+this.controlLabel('Popup')+'" style="width:100%;"><div class="arrow ui-icon ui-icon-grip-dotted-vertical "></div><div title="Collapse" class="collapse ui-icon ui-icon-folder-collapsed "></div><div title="Uncollapse" class="uncollapse ui-icon ui-icon-folder-open "></div><div title="Delete" class="remove ui-icon ui-icon-trash "></div><div title="Duplicate" class="copy ui-icon ui-icon-copy "></div><div class="dfield" style="width:100%;">'+
					'<div class="cff-popup-header">'+(this.title.length ? '<' + this.titletag+ ' class="cff-popup-title">'+this.title+'</' + this.titletag+ '>' : '')+
					(this.close_button ? '<div class="cff-popup-close ui-icon ui-icon-close"></div>' : '')+
					'</div>'+
					'<div class="fcontainer">'+$.fbuilder.controls['fcontainer'].prototype.columnsSticker.call(this)+'<span class="developer-note">'+$.fbuilder.htmlEncode(this._developerNotes)+'</span><label class="collapsed-label">Collapsed ['+this.name+']</label><div class="fieldscontainer"></div></div></div><div class="clearer" /></div>';
				},
			editItemEvents:function()
				{
					$.fbuilder.controls[ 'fcontainer' ].prototype.editItemEvents.call(this);
					$.fbuilder.controls[ 'ffields' ].prototype.editItemEvents.call(
						this,
						[
							{s:"#sOpenOnload",e:"click", l:"open_onload", f:function(el){return el.is(':checked');}},
							{s:"#sOpenOnclick",e:"change", l:"open_onclick"},
							{s:"#sTitleTag",e:"change", l:"titletag"},
							{s:"#sModal",e:"click", l:"modal", f:function(el){return el.is(':checked');}},
							{s:"#sDragging",e:"click", l:"dragging", f:function(el){return el.is(':checked');}},
							{s:"#sResizing",e:"click", l:"resizing", f:function(el){return el.is(':checked');}},
							{s:"#sCloseButton",e:"click", l:"close_button", f:function(el){return el.is(':checked');}},
							{s:"#sPosition",e:"change", l:"position"},
							{s:"#sWidth",e:"change keyup", l:"width"},
							{s:"#sHeight",e:"change keyup", l:"height"}
						]
					);
				},
			remove : function()
				{
					return $.fbuilder.controls[ 'fcontainer' ].prototype.remove.call(this);
				},
			duplicateItem: function( currentField, newField )
				{
					return $.fbuilder.controls[ 'fcontainer' ].prototype.duplicateItem.call( this, currentField, newField );
				},
			after_show:function()
				{
					return $.fbuilder.controls[ 'fcontainer' ].prototype.after_show.call(this);
				},
			showTitle: function()
				{
					let me = this;
					return '<label>Popup Title</label><textarea class="large" name="sTitle" id="sTitle">'+cff_esc_attr(me.title)+'</textarea>'+
					'<div><label>Title Tag</label><select class="large" id="sTitleTag" name="sTitleTag">'+
					['H1', 'H2', 'H3', 'H4', 'H5', 'H6', 'P'].reduce(function(o, t){ return o += '<option value="'+t+'" '+(t == me.titletag ? 'SELECTED' : '')+'>'+t+'</option>';}, '')+'</select>';
				},
			showPopupSettings:function()
				{
					let me = this;
					function getButtons( btn ) {
						let items = me.fBuild.getItems(),
						opts = '<option value="">- Select Button -</option>';
						for ( var i=0, k = items.length; i<k; i++ )
						{
							if ( items[i].ftype == 'fButton' )
							{
								opts += '<option value="'+items[i].name+'" '+( items[i].name == btn ? 'selected="SELECTED"' : '')+'>' + items[i].name+( typeof items[i].sValue != 'undefined' ? ' ('+cff_esc_attr(items[i].sValue)+')' : '')+'</option>';
							}
						}
						return opts;
					};
					return '<label><input type="checkbox" name="sModal" id="sModal" '+( me.modal ? "checked" : "" )+'> Modal popup</label>'+
					'<label><input type="checkbox" name="sDragging" id="sDragging" '+( me.dragging ? "checked" : "" )+'> Allow dragging</label>'+
					'<label><input type="checkbox" name="sResizing" id="sResizing" '+( me.resizing ? "checked" : "" )+'> Allow resizing</label>'+
					'<label><input type="checkbox" name="sOpenOnload" id="sOpenOnload" '+( me.open_onload ? "checked" : "" )+'> Open on form load</label>'+
					'<label>Open on-click button</label>'+
					'<select name="sOpenOnclick" id="sOpenOnclick" class="large">' + getButtons( me.open_onclick ) + '</select>'+
					'<div class="groupBox"><label>To open the popup by coding, call the piece of code</label>SHOWFIELD('+me.name.replace(/fieldname/i, '')+');</div>'+
					'<label><input type="checkbox" name="sCloseButton" id="sCloseButton" '+( me.close_button ? "checked" : "" )+'> Include close button</label>'+
					'<div class="groupBox"><label>To close the popup by coding, call the piece of code</label>HIDEFIELD('+me.name.replace(/fieldname/i, '')+');</div>'+
					'<label>Popup position</label>'+
					'<select name="sPosition" id="sPosition" class="large">'+
					'<option value="center" '+( me.position == 'center' ? 'selected' : '' )+'>Center</option>'+
					'<option value="top-left" '+( me.position == 'top-left' ? 'selected' : '' )+'>Top Left</option>'+
					'<option value="top-right" '+( me.position == 'top-right' ? 'selected' : '' )+'>Top Right</option>'+
					'<option value="bottom-left" '+( me.position == 'bottom-left' ? 'selected' : '' )+'>Bottom Left</option>'+
					'<option value="bottom-right" '+( me.position == 'bottom-right' ? 'selected' : '' )+'>Bottom Right</option>'+
					'</select>'+
					'<div class="clearer"></div>'+
					'<div class="column width50"><label>Width (px or %)</label><input type="text" name="sWidth" id="sWidth" value="'+cff_esc_attr(me.width)+'" class="large"></div>'+
					'<div class="column width50"><label>Height (px or %)</label><input type="text" name="sHeight" id="sHeight" value="'+cff_esc_attr(me.height)+'" class="large"></div>'+
					'<div class="clearer"></div>';
				},
			showSpecialDataInstance: function()
			{
				return $.fbuilder.controls[ 'fcontainer' ].prototype.showSpecialDataInstance.call(this) + this.showPopupSettings();
			}
	});