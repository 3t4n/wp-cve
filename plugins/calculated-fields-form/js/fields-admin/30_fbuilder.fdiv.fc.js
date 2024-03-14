	$.fbuilder.typeList.push(
		{
			id:"fdiv",
			name:"Div",
			control_category:10
		}
	);
	$.fbuilder.controls[ 'fdiv' ]=function(){};
	$.extend(
		$.fbuilder.controls[ 'fdiv' ].prototype,
		$.fbuilder.controls[ 'fcontainer' ].prototype,
		{
			title: 'div',
			ftype:"fdiv",
			_developerNotes:'',
			fields:[],
			columns:1,
			rearrange: 0,
			collapsed:false,
			display:function()
				{
					return '<div class="fields '+this.name+((this.collapsed) ? ' collapsed' : '')+' '+this.ftype+'" id="field'+this.form_identifier+'-'+this.index+'" title="'+this.controlLabel('Div')+'" style="width:100%;"><div class="arrow ui-icon ui-icon-grip-dotted-vertical "></div><div title="Collapse" class="collapse ui-icon ui-icon-folder-collapsed "></div><div title="Uncollapse" class="uncollapse ui-icon ui-icon-folder-open "></div><div title="Delete" class="remove ui-icon ui-icon-trash "></div><div title="Duplicate" class="copy ui-icon ui-icon-copy "></div><div class="dfield" style="width:100%;"><div class="fcontainer"><span class="developer-note">'+$.fbuilder.htmlEncode(this._developerNotes)+'</span>'+$.fbuilder.controls['fcontainer'].prototype.columnsSticker.call(this)+'<label class="collapsed-label">Collapsed ['+this.name+']</label><div class="fieldscontainer"></div></div></div><div class="clearer" /></div>';
				},
			showTitle:function(){ return ''; },
			editItemEvents:function()
				{
					$.fbuilder.controls[ 'fcontainer' ].prototype.editItemEvents.call(this);
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
				}
	});