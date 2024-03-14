	$.fbuilder.controls['fButton']=function(){};
	$.extend(
		$.fbuilder.controls['fButton'].prototype,
		$.fbuilder.controls['ffields'].prototype,
		{
			ftype:"fButton",
            sType:"button",
            sValue:"button",
			sLoading:false,
            sMultipage:false,
			sOnclick:"",
            sOnmousedown:"",
			userhelp:"A description of the section goes here.",
			show:function()
				{
                    var esc  = cff_esc_attr,
                        type = this.sType,
                        clss = '';

                    if(this.sType == 'calculate')
                    {
                        type = 'button';
                        clss = 'calculate-button';
                    }
					if(this.sType == 'print')
                    {
                        type = 'button';
                    }
					else if(this.sType == 'reset')
					{
						clss = 'reset-button';
					}

                    return '<div class="fields '+esc(this.csslayout)+' '+this.name+' cff-button-field" id="field'+this.form_identifier+'-'+this.index+'"><input id="'+this.name+'" type="'+type+'" value="'+esc(this.sValue)+'" class="field '+clss+'" /><span class="uh">'+this.userhelp+'</span><div class="clearer"></div></div>';
				},
            after_show:function()
                {
					var me = this;
					$('#'+this.name).on( 'mousedown', function(){eval(me.sOnmousedown);});
					$('#'+this.name).on( 'click',
                        function()
                            {
                                var e = $(this), f = e.closest('form'), fid = me.form_identifier;
                                if(e.hasClass('calculate-button'))
                                {
                                    var items = $.fbuilder['forms'][fid].getItems();
									if(me.sLoading)
									{
										f.find('.cff-processing-form').remove();
										$('<div class="cff-processing-form"></div>').appendTo(e.closest('#fbuilder'));
									}
									$(document).on('equationsQueueEmpty', function(evt, id){
										if(id == fid)
										{
											if(me.sLoading) f.find('.cff-processing-form').remove();
											$(document).off('equationsQueueEmpty');
											for(var i = 0, h = items.length; i < h; i++)
											{
												if(items[i].ftype == 'fsummary')
												{
													items[i].update();
												}
											}
										}
									});

                                    $.fbuilder['calculator'].defaultCalc('#'+e.closest('form').attr('id'), false, true);
                                }
								if(e.hasClass('reset-button')) {
									RESETFORM(e[0].form);
									setTimeout(function(){ eval(me.sOnclick); }, 55)
								} else {
									eval(me.sOnclick);
								}

                                if(me.sType == 'print')
                                {
                                    fbuilderjQuery.fbuilder.currentFormId = f.attr('id');
                                    PRINTFORM(me.sMultipage);
                                }
                            }
                  );
                }
		}
	);