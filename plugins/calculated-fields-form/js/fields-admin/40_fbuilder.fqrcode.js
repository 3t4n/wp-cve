	$.fbuilder.typeList.push(
		{
			id:"fqrcode",
			name:"QRCode",
			control_category:1
		}
	);
	$.fbuilder.controls[ 'fqrcode' ]=function(){};
	$.extend(
		$.fbuilder.controls[ 'fqrcode' ].prototype,
		$.fbuilder.controls[ 'ffields' ].prototype,
		{
			title:"",
			ftype:"fqrcode",
			predefined:"",
			required:false,
			exclude:false,
			readonly:false,
			size:"medium",
			display:function()
				{
					return '<div class="fields '+this.name+' '+this.ftype+'" id="field'+this.form_identifier+'-'+this.index+'" title="'+this.controlLabel('QRCode')+'"><div class="arrow ui-icon ui-icon-grip-dotted-vertical "></div><div title="Delete" class="remove ui-icon ui-icon-trash "></div><div title="Duplicate" class="copy ui-icon ui-icon-copy "></div><label>'+this.title+''+((this.required)?"*":"")+'</label><div class="dfield"><span class="uh">'+this.userhelp+'</span></div><div class="clearer" /></div>';
				},
			editItemEvents:function()
				{
					var me = this, evt = [];
					$.fbuilder.controls[ 'ffields' ].prototype.editItemEvents.call(this,evt);
				},
			showSpecialDataInstance: function()
				{
					return '';
				}
	});