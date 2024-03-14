myjQuery = (typeof myjQuery != 'undefined' ) ? myjQuery : jQuery;
myjQuery(function(){
(function($) {
	$.fn.rcalendar = function(options){
	    var opt = $.extend({},
				{
				    title:"",
				    colors:["FFF","FCC","FC9","FF9","FFC","9F9","9FF","CFF","CCF","FCF",
                            "CCC","F66","F96","FF6","FF3","6F9","3FF","6FF","99F","F9F",
                            "BBB","F00","F90","FC6","FF0","3F3","6CC","3CF","66C","C6C",
                            "999","C00","F60","FC3","FC0","3C0","0CC","36F","63F","C3C",
                            "666","900","C60","C93","990","090","399","33F","60C","939",
                            "333","600","930","963","660","060","366","009","339","636",
                            "000","300","630","633","330","030","033","006","309","303"
                            ],
				    defaultColor:"F66",
                    defaultSaveColor:"6FF",
                    partial_colors:["CCC","F66","FF6","6F9","6FF","F9F",
                                    "999","C00","FC3","3C0","36F","C3C",
                                    "333","600","963","060","009","636"
                                    ],
				    partial_defaultColor:"F66",
                    separator2: " - ",
				    ajaxURL:pathCalendar,
				    dialogWidth:400,
				    edition:true,
				    readonly:false,
				    partialDate:true,
				    workingDates:[1,1,1,1,1,1,1],//default [1,1,1,1,1,1,1]
				    holidayDates:[],
				    startReservationWeekly:[1,1,1,1,1,1,1],//default [1,1,1,1,1,1,1]
				    startReservationDates:[],
				    fixedReservationDates:false,//0
				    fixedReservationDates_length:0,
				    firstDay:0,
				    language:"",
				    dformat:"mm/dd/yy",
				    minDate:"",
				    maxDate:"",
				    editid:"",/* Edition id, startdate and enddate for edition*/
				    startdate:"",
				    enddate:"",
				    showToolt:false,
				    numberOfMonths:1
				},options, true);
		if (opt.edition) opt.showTooltipOnMouseOver = true;
		if (opt.partialDate)
		{
		    opt.colors = opt.partial_colors;
		    opt.defaultColor = opt.partial_defaultColor;
		}		
		opt.id = $(this).attr("id");
		var id = $(this).attr("id");
		var timeOut = true;
		var data = new Array();
		var cacheData = new Array();
		$("#"+opt.id).html( '<div class="dp" id="dp'+opt.id+'"></div><input type="hidden" name="selDay_start'+opt.id+'" id="selDay_start'+opt.id+'" /><input type="hidden" name="selMonth_start'+opt.id+'" id="selMonth_start'+opt.id+'" /><input type="hidden" name="selYear_start'+opt.id+'" id="selYear_start'+opt.id+'" /><input type="hidden" name="selDay_end'+opt.id+'" id="selDay_end'+opt.id+'" /><input type="hidden" name="selMonth_end'+opt.id+'" id="selMonth_end'+opt.id+'" /><input type="hidden" name="selYear_end'+opt.id+'" id="selYear_end'+opt.id+'" />' );
        var selectedDates = {l:"",u:""};
		var dateToStr = function(d1,d2,dformat){
		    var str = $.datepicker.formatDate(dformat, d1);
		    if (d1.toString()!=d2.toString())
		        str += opt.separator2 + $.datepicker.formatDate(dformat, d2);
		    return str;
		};
		var setHiddenFields = function(dl,du){
		        $("#selDay_start"+opt.id).val((dl=="")?"":dl.getDate()).change();
            $("#selMonth_start"+opt.id).val((dl=="")?"":dl.getMonth()+1).change();
            $("#selYear_start"+opt.id).val((dl=="")?"":dl.getFullYear()).change();
            $("#selDay_end"+opt.id).val((du=="")?"":du.getDate()).change();
            $("#selMonth_end"+opt.id).val((du=="")?"":du.getMonth()+1).change();
            $("#selYear_end"+opt.id).val((du=="")?"":du.getFullYear()).change();
            
	    }
	  
	  var removeSpecialSelection = function(){
		    $("#"+opt.id).find(".specialSelection,.specialSel").each(function(){
		        if (
		        !$(this).hasClass("specialDateLeft") && !$(this).hasClass("specialDateRight") && 
		        !$(this).hasClass("specialDateStart"))
		        {
		            if ($(this).find(".triangle,triangle2"))
		            {
                    var a = $(this).find("a,span"); 
                    $(this).find("div").each(function(){
                        $(this).detach();
                    });
                    $(this).append(a);   
		            }
            }  
            $(this).removeClass("specialSelection").css("background","none");
        });      
            
	    }
	  var addStartDate = function(d,inst){ 
	      if ($("#"+opt.id).find(".specialSel").length>0)
	          inst.inline = true;
	      else
	      {
		        inst.inline = false;
		        removeSpecialSelection();
            var c = ".d" + $.datepicker.formatDate("yy-mm-dd",$.datepicker.parseDate(opt.dformat, d));
            $("#"+opt.id).find(c).addClass("specialSelection").css("background","#"+opt.defaultColor);       
        } 
	    }
	  var addSpecialSelection = function(l,u,inst){
		    inst.inline = false;
		    //$("#"+opt.id).find(".specialSelection").each(function(){
            //    $(this).removeClass("specialSelection").css("background","none")
            //}); 
            removeSpecialSelection();
        var c = "";
        
        var dl = new Date(l.getTime());
        dl.setDate(dl.getDate() + 1);
        while (dl.getTime()<u.getTime())
        {
            c = ".d" + $.datepicker.formatDate("yy-mm-dd",dl);
            $("#"+opt.id).find(c).addClass("specialSelection").css("background","#"+opt.defaultColor);
            dl.setDate(dl.getDate() + 1);
        }
        if (opt.partialDate) 
        {
            c = ".d" + $.datepicker.formatDate("yy-mm-dd",u); 
            $("#"+opt.id).find(c).addClass("specialSelection");
            if (!$("#"+opt.id).find(c).hasClass("specialDateLeft"))
            {
                var obj = $("#"+opt.id).find(c);
                var w = obj.css("width");
                var h = obj.css("height");
                var a = obj.find("a,span"); 
                var b = $('<div class="t"></div>').append(a);
                obj.append( b );
                obj.append( '<div class="triangle2"></div><div class="e">&nbsp;</div>' );
                $(c+' .t,'+c+' .e').css("width",w).css("height",h);
                $(c +' .triangle2').css("border-color","#"+opt.defaultColor+" transparent transparent transparent").css("border-width",h+" "+w+" 0 0");
               
            }
            else
                $("#"+opt.id).find(c).addClass("specialSelection").css("background","#"+opt.defaultColor);  
            c = ".d" + $.datepicker.formatDate("yy-mm-dd",l);
            $("#"+opt.id).find(c).addClass("specialSelection");
            if (!$("#"+opt.id).find(c).hasClass("specialDateRight"))
            {
                var obj = $("#"+opt.id).find(c);
                var w = obj.css("width");
                var h = obj.css("height");
                var a = obj.find("a,span"); 
                var b = $('<div class="t"></div>').append(a);
                obj.append( b );
                obj.append( '<div class="triangle"></div><div class="e">&nbsp;</div>' );
                $(c+' .t,'+c+' .e').css("width",w).css("height",h);
                $(c +' .triangle').css("border-color","transparent transparent #"+opt.defaultColor+" transparent").css("border-width","0 0 "+h+" "+w);               
            }
            else
                $("#"+opt.id).find(c).addClass("specialSelection").css("background","#"+opt.defaultColor);    
        } 
        else
        {
            c = ".d" + $.datepicker.formatDate("yy-mm-dd",l);  
            $("#"+opt.id).find(c).addClass("specialSelection").css("background","#"+opt.defaultColor);
            c = ".d" + $.datepicker.formatDate("yy-mm-dd",u);  
            $("#"+opt.id).find(c).addClass("specialSelection").css("background","#"+opt.defaultColor);  
        }        
           
	    }  
		var showDialogEdition = function(td,eventText,classn){
		    $(".myover").remove();
            $(".myoverAdd").dialog( "close" );
		    td.append("<div class=\""+classn+"\" >"+eventText+"</div>");
                $("."+classn).dialog({width:opt.dialogWidth, height: 300,
                  close: function( event, ui ) {
                    if ($(this).hasClass("myoverAdd"))
                    {
                        $(".myoverAdd").remove();
                        selectedDates.l = "";
                        selectedDates.u = "";
                        setHiddenFields(selectedDates.l,selectedDates.u);
                        reloadDataToCache();
                        render();
                    }
                  },
                  position: {
                    my: "left top",
                    at: "center bottom",
                    collision: "fit",
                    of: $("."+classn).parent()
                  }
                }).addClass("mv_dlg_nmonth").parent().addClass("mv_dlg");
                $("<div id=\"mv_corner\" />").appendTo($(".mv_dlg .ui-dialog-titlebar"));
                move_mv_dlg();
                $(".comboColor").click(function(){
                    $(this).find(".listColor").css("display","");
                    });
                $(".comboColor .listColor div").click(function(){
                    var c = $(this).attr("c");
                    $(this).parent().css("display","none");
                    $(this).parent().parent().css("background","#"+c).attr("c",c);
                    return false;
                    });
                $(".bCancelEvent").click(function(){   
                    try{
                    $( ".myover" ).dialog( "close" );
                    $( ".myoverAdd" ).dialog( "close" );
                    } catch (e) { }
                    return false;
                });
                $(".bSaveEvent").click(function(){

                    saveData($(this).parent().parent().parent().find(".eIndex").val(),$(this).parent().parent().find(".eTitle").val(),$(this).parent().parent().find(".eDesc").val(),$(this).parent().parent().find(".comboColor").attr("c"));
                    $(".myover").remove();
                    $(".myoverAdd").remove();
                    return false;
                });
		};
        var getEventEdition = function(d){
		    var str = "";
		    for (var i=0;i<data.length;i++)
            {
                if ( (d >= data[i].l && d <=data[i].u))
                {
                    str += '<div style="border-left:3px solid #'+data[i].c+';padding-left:10px;margin-bottom:20px;">';
                    str += '<div >'+dateToStr(data[i].l,data[i].u,opt.dformat)+'</div>';
                    str += '<div><input type="hidden" class="eIndex" id="eIndex'+data[i].id+'" value="'+i+'"/></div>';

                    str += '<div class="eRead">';
                    str += '<div>'+data[i].title+'</div>';
                    str += '<div>'+data[i].description+'</div>';
                    if (opt.edition)
                        str += '<div ><a href="" class="bEditEvent">Edit Event</a> &nbsp;  &nbsp; <a href="" class="bDelEvent">Delete Event</a></div>';
                    str += '</div>';
                    str += '<div class="eWrite" style="display:none">';
                    str +=     '<div class="comboColor" style="background:#'+data[i].c+'" c="'+data[i].c+'"><div class="listColor '+((opt.partialDate)?"partialDate":"")+'" style="display:none">';
                    for (var j=0;j<opt.colors.length;j++)
                        str += '<div style="background:#'+opt.colors[j]+';" c="'+opt.colors[j]+'"></div>';
                    str +=     '</div></div>';
                    str += '<div><input type="text" class="eTitle" id="eTitle'+data[i].id+'" value="'+data[i].title+'"/></div>';
                    str += '<div><textarea id="eDesc'+data[i].id+'" class="eDesc" >'+data[i].description+'</textarea></div>';
                    str += '<div ><a href="" class="bSaveEvent">Save Event</a> &nbsp;  &nbsp; <a href="" class="bCancelEvent">Cancel</a></div>';
                    str += '</div>';
                    str += '</div>';
                }
            }
		    return str;
		};
		var getAddEdition = function(){
		    var str = "";
                    str += '<div>';
                    str += '<div >'+dateToStr(selectedDates.l,selectedDates.u,opt.dformat)+'</div>';
                    str += '<div><input type="hidden" class="eIndex" id="eIndex" value="-1"/></div>';
                    str += '<div class="eWrite" >';
                    str +=     '<div class="comboColor" style="background:#'+opt.defaultSaveColor+'" c="'+opt.defaultSaveColor+'"><div class="listColor '+((opt.partialDate)?"partialDate":"")+'" style="display:none">';
                    for (var j=0;j<opt.colors.length;j++)
                        str += '<div style="background:#'+opt.colors[j]+';" c="'+opt.colors[j]+'"></div>';
                    str +=     '</div></div>';
                    str += '<div>Title<br /><input type="text" class="eTitle" id="eTitle" /></div>';
                    str += '<div>Description<br /><textarea id="eDesc" class="eDesc" ></textarea></div>';
                    str += '<div ><a href="" class="bSaveEvent">Save Event</a> &nbsp;  &nbsp; <a href="" class="bCancelEvent">Cancel</a></div>';
                    str += '</div>';
                    str += '</div>';
		    return str;
		};
		var render = function(){
		    if ($("#dp"+opt.id).find(".ui-datepicker-calendar").length==0)
		    {
		      createCalendar();
		      if (opt.startdate!="" && opt.enddate!="" && opt.editid!="")
              {
                  selectedDates = {l:$.datepicker.parseDate("yy-mm-dd",opt.startdate),u:$.datepicker.parseDate("yy-mm-dd",opt.enddate)};
                  $("#dp"+opt.id).datepicker("setDate", selectedDates.u);
                  setHiddenFields(selectedDates.l,selectedDates.u);
              }
		    }  
		    else
		    {
		        timeOut = true;
		        $("#dp"+opt.id).datepicker("refresh");
		    }

		};
		var createCalendar = function()
		{
		    try {$.fn.datepicker.noConflict();} catch (e) {}
		    var dStringClass = new Array();
            timeOut = true;
            if ($("#dp"+opt.id)) $("#dp"+opt.id).datepicker("destroy");
            cleanDatepicker();
            $("#dp"+opt.id).datepicker({
                showOtherMonths: false,
                showWeek: true,
                numberOfMonths:opt.numberOfMonths,
                onSelect: function(d,inst) {
                    timeOut = true;
                    if (opt.readonly)
                        return;
                    inst.inline = true;
                    if ((opt.fixedReservationDates) && (selectedDates.l=="" || (selectedDates.l!="" && selectedDates.u!="")))
                    {
                        selectedDates.l = $.datepicker.parseDate(opt.dformat,d);
                        selectedDates.u = "";
                        var tmpdate = new Date(selectedDates.l.getTime());
                        
                        tmpdate.setDate(tmpdate.getDate() + opt.fixedReservationDates_length - ((opt.partialDate)?0:1));
                        d = $.datepicker.formatDate(opt.dformat, tmpdate);
                    }                            
                    if (selectedDates.l=="" || (selectedDates.l!="" && selectedDates.u!=""))
                    {
                        selectedDates.l = $.datepicker.parseDate(opt.dformat,d);
                        selectedDates.u = "";
                        setHiddenFields(selectedDates.l,selectedDates.u);
                        addStartDate(d,inst); 
                    } 
                    else //if (selectedDates.u=="")
                    {
                        var l = selectedDates.l;
                        var u = $.datepicker.parseDate(opt.dformat,d);
                        var b = (l <= u);
                        var dl = b ? l : u;
                        var du = b ? u : l;
                        //check if possible
                        var isPossible = true;
                        for (var i=0;i<data.length;i++)
                        {
                            if ( (dl <= data[i].l && du >=data[i].u))
                                isPossible = false;
                        }
                        var tmpdate = new Date(dl.getTime());
                        while (tmpdate<=du)
                        {
                            if ((opt.workingDates[tmpdate.getDay()]==0)  || (opt.holidayDates.length>0 && opt.holidayDates.indexOf($.datepicker.formatDate('yy-mm-dd', tmpdate))!="-1"))
                            {
                                isPossible = false;
                                tmpdate = new Date(du.getTime());
                            }
                            tmpdate.setDate(tmpdate.getDate() + 1);    
                        }
                        //if (isPossible && (dl.toString()!=du.toString()))
                        if (isPossible && (!opt.partialDate || (dl.toString()!=du.toString()) ) )
                        {
                            selectedDates.l = dl;
                            selectedDates.u = du;
                            setHiddenFields(selectedDates.l,selectedDates.u);
                            addSpecialSelection(dl,du,inst);
                            if (opt.edition)
                            {
                                setTimeout(function(){
                                    showDialogEdition($(".d" + $.datepicker.formatDate("yy-mm-dd",du)),getAddEdition(),"myoverAdd");
                                },100);
                            }
                        }
                        else
                        {
                            if (opt.fixedReservationDates)
                                selectedDates.l = "";
                            else
                                selectedDates.l = $.datepicker.parseDate(opt.dformat,d);                             
                            addStartDate(d,inst);                         
                            selectedDates.u = "";
                            setHiddenFields(selectedDates.l,selectedDates.u);
                        }


                    }

                },
                onChangeMonthYear: function(){
                    timeOut = true;
                    $(".myover").remove();
                    try {$(".myoverAdd").dialog( "close" );} catch (e) { }
                },
                beforeShowDay: function (d) {
                        var c = "";
                        var n = 0;
                        var co = "";
                        var co_l = "";
                        var co_r = "";
                        var desc = "";
                        var dString = "";
                        if (typeof cacheData[$.datepicker.formatDate("yy-mm-dd",d)] !== 'undefined')
                        {
                            var obj = cacheData[$.datepicker.formatDate("yy-mm-dd",d)];
                            dString = opt.id+"dmy"+(d.getMonth()+1)+"_"+d.getDate()+"_"+d.getFullYear();
                            dStringClass[dStringClass.length]= {d:dString,c:obj.c,co_l:obj.co_l,co_r:obj.co_r,n:n,desc:obj.desc};
                            n = obj.st+obj.et+2*obj.mt;
                            if (n>1)
                            { 
                                if (obj.mt>0)
                                    c = "specialDate";
                                else
                                    c = "specialDate"+((opt.partialDate)?"Middle":"");
                            }    
                            else if (obj.st>0)
                                c = "specialDate"+((opt.partialDate)?"Left":"");
                            else if (obj.et>0)
                                c = "specialDate"+((opt.partialDate)?"Right":"");
                            c += " dw_active"; 
                        } 
                        if (timeOut)
                        {
                            timeOut = false;
                            setTimeout(function(){
                                $("#"+opt.id+" .ui-datepicker-header [title]").attr("title","");
                                $("#"+opt.id+" .ui-datepicker-calendar th [title]").attr("title",""); 
                                
                                paintCell();                                
                                $('#'+opt.id+' .specialSelection').removeClass("specialSel").css("background","#"+opt.defaultColor);
                                for (var i=0;i<dStringClass.length;i++)
                                {
                                    $('#'+opt.id+' .specialDate.'+dStringClass[i].d).css("background-color","#"+dStringClass[i].c).find("span").attr("title",dStringClass[i].desc);
                                    $('#'+opt.id+' .specialDate.'+dStringClass[i].d).attr("style", "background-color:#"+dStringClass[i].c+" !important");
                                    if (opt.partialDate)
                                    {
                                        $('#'+opt.id+' .specialDateRight.'+dStringClass[i].d+ ' .triangle2').css("border-color","#"+dStringClass[i].co_r+" transparent transparent transparent");
                                        $('#'+opt.id+' .specialDateLeft.'+dStringClass[i].d+ ' .triangle').css("border-color"," transparent transparent #"+dStringClass[i].co_l+" transparent");
                                        $('#'+opt.id+' .specialDateRight.'+dStringClass[i].d+ ' .triangle2').parent().find("span,a").attr("title",dStringClass[i].desc);
                                        $('#'+opt.id+' .specialDateLeft.'+dStringClass[i].d+ ' .triangle').parent().find("span,a").attr("title",dStringClass[i].desc)
                                        if (dStringClass[i].co_l!="" && dStringClass[i].co_r!="")
                                        {
                                            $('#'+opt.id+' .specialDateMiddle.'+dStringClass[i].d+ ' .triangle2').css("border-color","#"+dStringClass[i].co_r+" transparent transparent transparent");
                                            $('#'+opt.id+' .specialDateMiddle.'+dStringClass[i].d+ ' .triangle').css("border-color"," transparent transparent #"+dStringClass[i].co_l+" transparent");
                                        
                                        }
                                        
                                    }
                                }
                                if (opt.showTooltipOnMouseOver)
                                {
                                    $("#"+opt.id+" .ui-datepicker").on('mouseover', '.dw_active', function(){
                                        if (!$(this).hasClass("ui-datepicker-other-month"))
                                        {

                                            var tmpd = $(this).attr("class").split("dmy");
                                            var d = tmpd[1].split("_");
                                            var titleDay = new Date(d[2].substring(0,4),d[0]-1,d[1]);
                                            $(".myover").remove();
                                            var eventText = getEventEdition(titleDay);
                                            if (eventText!="")
                                            {
                                                showDialogEdition($(this),eventText,"myover");
                                                $(".bEditEvent").click(function(){
                                                    $(this).parent().parent().parent().find(".eRead").css("display","none");
                                                    $(this).parent().parent().parent().find(".eWrite").css("display","");
                                                    return false;
                                                })
                                                $(".bDelEvent").click(function(){
                                                    var ind = $(this).parent().parent().parent().find(".eIndex").val();
                                                    $.post(opt.ajaxURL+"?dex_bccf_calendar_load2=delete", [
                                                    { name: "id", value:data[ind].id  }
                                                    ], function(d) {
                                                        if (d) {
                                                            if (!d.isSuccess) {
                                                                alert("Deleting data error: "+d.msg)
                                                            }
                                                            else {
                                                                data.splice(ind,1);
                                                                reloadDataToCache();
                                                                render();
                                                            }
                                                        }
                                                    }, "json");
                                                    $(".myover").remove();
                                                    return false;
                                                })

                                            }
                                            return false;

                                       }
                                    }).on('mouseout', '.dw_active',function(){
                                    });
                                }
                                }, 1);
                        }
                        if ((opt.holidayDates.length>0 && opt.holidayDates.indexOf($.datepicker.formatDate('yy-mm-dd', d))!="-1"))
                            dString += " holidayDates ";
                        //if (opt.workingDates[d.getDay()]==0)
                        //    return [false,"ui-non-working"];
                        //else if (opt.holidayDates.indexOf($.datepicker.formatDate('yy-mm-dd', d))!="-1")
                        //    return [false,"ui-non-working"];
                        //else  
                        var r =  (
                        ((n>1 && opt.partialDate) 
                        || (n>0 && !opt.partialDate))
                        || (opt.workingDates[d.getDay()]==0 && (opt.startReservationDates.indexOf($.datepicker.formatDate('yy-mm-dd', d))=="-1"))
                        || (opt.holidayDates.length>0 && opt.holidayDates.indexOf($.datepicker.formatDate('yy-mm-dd', d))!="-1")
                        || (opt.startReservationWeekly[d.getDay()]==0 && (opt.startReservationDates.indexOf($.datepicker.formatDate('yy-mm-dd', d))=="-1"))
                        //|| (
                        //(opt.fixedReservationDates)
                        //&& (opt.startReservationDates.indexOf($.datepicker.formatDate('yy-mm-dd', d))!="-1")
                        //)
                        ?false:true); //startReservationDates
                        //return [(((n>1 && opt.partialDate) || (n>0 && !opt.partialDate))?false:true),c+" "+dString];
                        return [r,c+" "+dString+" d"+$.datepicker.formatDate("yy-mm-dd",d)]; 
                        
		    		}


                });
                adaptWH();
                paintCell();
                $("#dp"+opt.id).datepicker("option", $.datepicker.regional[opt.language]); 
                $("#dp"+opt.id).datepicker("option", "dateFormat", opt.dformat );
                $("#dp"+opt.id).datepicker("option", "minDate", opt.minDate );
                $("#dp"+opt.id).datepicker("option", "maxDate", opt.maxDate );
                $("#dp"+opt.id).datepicker("option", "firstDay", opt.firstDay );
                $.datepicker.setDefaults($.datepicker.regional['']); 
                $(".myover").remove();
        };
        var reloadDataToCache = function()
        {
            cacheData = new Array();
            for (var i=0;i<data.length;i++)
            {                
                var l = new Date(data[i].l.getTime());
                var j=0;               
                while (l < data[i].u)
                {
                    if (typeof cacheData[$.datepicker.formatDate("yy-mm-dd",l)] === 'undefined')
                        cacheData[$.datepicker.formatDate("yy-mm-dd",l)] = {st:0,mt:0,et:0,co_l:"",co_r:""};
                    ((j==0)?cacheData[$.datepicker.formatDate("yy-mm-dd",l)].st++:cacheData[$.datepicker.formatDate("yy-mm-dd",l)].mt++);
                    cacheData[$.datepicker.formatDate("yy-mm-dd",l)].co_l = ((j==0)?data[i].c:""); 
                    cacheData[$.datepicker.formatDate("yy-mm-dd",l)].c = data[i].c;  
                    cacheData[$.datepicker.formatDate("yy-mm-dd",l)].desc = data[i].description                  
                    l.setDate(l.getDate() + 1);
                    j++;
                }
                if (typeof cacheData[$.datepicker.formatDate("yy-mm-dd",l)] === 'undefined')
                    cacheData[$.datepicker.formatDate("yy-mm-dd",l)] = {st:0,mt:0,et:0,co_l:"",co_r:""};
                cacheData[$.datepicker.formatDate("yy-mm-dd",l)].et++;
                cacheData[$.datepicker.formatDate("yy-mm-dd",l)].c = data[i].c;
                cacheData[$.datepicker.formatDate("yy-mm-dd",l)].co_r = data[i].c;
                cacheData[$.datepicker.formatDate("yy-mm-dd",l)].desc = data[i].description 
            } 
        }
		var loadData = function()
		{   
		    try {
		        $.ajax({
                    type: "POST", //
                    async:false,
                    url: opt.ajaxURL+"?dex_bccf_calendar_load2=list&"+((opt.editid!="")?"editid="+opt.editid+"&":"")+"id="+opt.calendarId,
                    d: [],
			        dataType: "json",
                    dataFilter: function(d, type) {
                        return d;
                      },
                    success: function(d) {
                        if (!d.isSuccess) {
                            alert("Loading data error: "+d.msg)
                        }
                        else {
                            data = d.events;
                            for (var i=0;i<data.length;i++)
		                    {
		                        try{
		                        data[i].l = $.datepicker.parseDate("mm/dd/yy",data[i].dl);
		                        data[i].u = $.datepicker.parseDate("mm/dd/yy",data[i].du);
		                        } catch (e) { data.splice(i,1);i=0 }
		                            
		                    }
		                    reloadDataToCache();
                            render();
                        }
                    },
                    error: function(d) {
						try {
                            alert("Loading (processing) data error: "+d);
                        } catch (e) { }
                    }
                }); 
            } catch (e) { }
		};
		var saveData = function(ind,title,desc,c)
		{
		    if (ind=="-1")
		    {
		        $.post(opt.ajaxURL+"?dex_bccf_calendar_load2=add&id="+opt.calendarId, [
                    { name: "startdate", value: selectedDates.l.getFullYear()+"-"+(selectedDates.l.getMonth()+1)+"-"+selectedDates.l.getDate() },
                    { name: "enddate", value: selectedDates.u.getFullYear()+"-"+(selectedDates.u.getMonth()+1)+"-"+selectedDates.u.getDate() },
                    { name: "title", value: title },
                    { name: "description", value: desc },
                    { name: "color", value: c }

                    ], function(d) {
                        if (d) {
                            if (!d.isSuccess) {
                                alert("Saving (add) data error: "+d.msg)
                            }
                            else {
                                d.events[0].l = $.datepicker.parseDate("mm/dd/yy",d.events[0].dl);
                                d.events[0].u = $.datepicker.parseDate("mm/dd/yy",d.events[0].du);
                                data[data.length] = d.events[0];
                                selectedDates.l = "";
                                selectedDates.u = "";
                                setHiddenFields(selectedDates.l,selectedDates.u);
                                reloadDataToCache();
                                render();
                            }
                        }
                    }, "json");
            }
            else
            {
		        $.post(opt.ajaxURL+"?dex_bccf_calendar_load2=edit", [
                    { name: "id", value:data[ind].id  },
                    { name: "title", value: title },
                    { name: "description", value: desc },
                    { name: "color", value: c }
                    ], function(d) {
                        if (d) {
                            if (!d.isSuccess) {
                                alert("Saving (edit) data error: "+d.msg)
                            }
                            else {
                                data[ind].title = title;
                                data[ind].desc = desc;
                                data[ind].c = c;
                                reloadDataToCache();
                                render();
                            }
                        }
                    }, "json");
            }
		}
		loadData();
        return this;
	}
	function move_mv_dlg(){
        $(".mv_dlg").css("top",parseFloat($(".mv_dlg").css("top"))+17);
        $(".mv_dlg").css("left",parseFloat($(".mv_dlg").css("left"))-29);
    }
	function adaptWH()
	{
	    $(".rcalendar").each(function(){
                $(this).find(".ui-datepicker").css("width","");
                var h = 0;
                var w = 0;
                $(this).find(".ui-datepicker-group").each(function(){
                   var tmph = $(this).find(".ui-datepicker-calendar").css("height").replace("px","")*1 + $(this).find(".ui-datepicker-header").css("height").replace("px","")*1 +2;
                    if (h==0 || h < tmph) h = tmph;
                });
                $(this).find(".ui-datepicker-group").each(function(){
                    if (w < ($(this).css("width").replace("px","")*1)) w = $(this).css("width").replace("px","")*1;
                });
                if (h!=0) $(this).find(".ui-datepicker-group").each(function(){$(this).css("height",h+"px");});
                //if (w!=0) $(this).find(".ui-datepicker-group").each(function(){$(this).css("width",(w)+"px");});
            });
	}
	var paintCell = function(){
        $(".rcalendar").each(function(){
            var id = $(this).attr("id");
            var w = 0;
            $("#"+id+" .specialDateRight, #"+id+" .specialDateLeft").each(function(){
                if (w < ($(this).css("width").replace("px","")*1)) w = $(this).css("width").replace("px","")*1;
            });
            var h = 0;
            $("#"+id+" .specialDateRight, #"+id+" .specialDateLeft").each(function(){
                if (h < ($(this).css("height").replace("px","")*1)) h = $(this).css("height").replace("px","")*1;
            });
            if (w==0)
                $("#"+id+" .specialDate").each(function(){
                    if (w < ($(this).css("width").replace("px","")*1)) w = $(this).css("width").replace("px","")*1;
                });
            if (h==0)
                $("#"+id+" .specialDate").each(function(){
                    if (h < ($(this).css("height").replace("px","")*1)) h = $(this).css("height").replace("px","")*1;
                });
            w += 'px';
            h += 'px';
            if ($("#"+id+" .specialDateRight .triangle2").length==0)
            {
                $("#"+id+" .specialDateRight").each(function() {
                     var a = $( this ).find("a,span"); 
                     var b = $('<div class="t"></div>').append(a);
                     $(this).append( b );
                     $(this).append( '<div class="triangle2"></div><div class="e">&nbsp;</div>' );
                });
            }
            if ($("#"+id+" .specialDateLeft .triangle").length==0)
            {
                $("#"+id+" .specialDateLeft").each(function() {
                     var a = $( this ).find("a,span"); 
                     var b = $('<div class="t"></div>').append(a);
                     $(this).append( b );
                     $(this).append( '<div class="triangle"></div><div class="e">&nbsp;</div>' );
                });
            }
            if ($("#"+id+" .specialDateMiddle .triangle2").length==0)
            {
                $("#"+id+" .specialDateMiddle").each(function() {
                     var a = $( this ).find("span"); 
                     var b = $('<div class="t"></div>').append(a);
                     $(this).append( b );
                     $(this).append( '<div class="triangle2"></div><div class="triangle"></div><div class="e">&nbsp;</div>' );
                });
            }
            $('#'+id+' .specialDateLeft .t, #'+id+' .specialDateLeft .e, #'+id+' .specialDateRight .t, #'+id+' .specialDateRight .e, #'+id+' .specialDateMiddle .e').css("width",w).css("height",h);
            $('#'+id+' .specialDateRight .triangle2').css("border-width",h+" "+w+" 0 0");
            $('#'+id+' .specialDateLeft .triangle').css("border-width","0 0 "+h+" "+w);
            $('#'+id+' .specialDateMiddle .triangle2').css("border-width",h+" "+w+" 0 0");
            $('#'+id+' .specialDateMiddle .triangle').css("border-width","0 0 "+h+" "+w);
            
         });   
                
         //   }, 1);
    }
	$(window).resize(function() {
        adaptWH();
        paintCell();
    });
 /**   if (typeof is_bccf_admin === 'undefined')
    {
        try {
            $(document).tooltip({
              tooltipClass: "ahbtooltip"
            });
        } catch (e) { }
    }      */              
    function cleanDatepicker() {
       var old_fn = $.datepicker._updateDatepicker;
       $.datepicker._updateDatepicker = function(inst) {
          old_fn.call(this, inst);
          adaptWH();
       }
    }

})(myjQuery);
});