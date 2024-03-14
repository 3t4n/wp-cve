	$.fbuilder.typeList.push(
		{
			id:"fSectionBreak",
			name:"Section Break",
			control_category:1
		}
	);
	$.fbuilder.controls[ 'fSectionBreak' ] = function(){};
	$.extend(
		$.fbuilder.controls[ 'fSectionBreak' ].prototype,
		$.fbuilder.controls[ 'ffields' ].prototype,
		{
			title:"Section Break",
			ftype:"fSectionBreak",
			userhelp:"A description of the section goes here.",
			display:function()
				{
					return '<div class="fields '+this.name+' '+this.ftype+'" id="field'+this.form_identifier+'-'+this.index+'" title="'+this.controlLabel('Section Break')+'"><div class="arrow ui-icon ui-icon-grip-dotted-vertical "></div><div title="Delete" class="remove ui-icon ui-icon-trash "></div><div title="Duplicate" class="copy ui-icon ui-icon-copy "></div><div class="section_break"></div><label>'+this.title+'</label><span class="uh">'+this.userhelp+'</span><div class="clearer" /></div>';
				},
			showTitle: function()
				{
					return '<label>Field Label</label><textarea class="large" name="sTitle" id="sTitle">'+cff_esc_attr(this.title)+'</textarea>';
				},
			editItemEvents:function()
				{
					$.fbuilder.controls[ 'ffields' ].prototype.editItemEvents.call(this);
				}
	});