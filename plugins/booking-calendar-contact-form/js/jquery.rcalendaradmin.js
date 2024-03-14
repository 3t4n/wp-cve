myjQuery = (typeof myjQuery != 'undefined' ) ? myjQuery : jQuery;
myjQuery(function(){
(function($) {
	$( document ).ready(function() {
	    cAdmin = $("#calConfig").rcalendaradmin({"calendarId":1,
                                         "partialDate":false,
                                         "edition":false,
                                         "numberOfMonths":1,
                                         firstDay:0,
                                         workingDates:[1,1,1,1,1,1,1]
                                         });
                                         
        $(".wdCheck,.srCheck").click(function(){
            reloadCheck();
        });
        $("#fixedreservation").click(function(){
            $("#container_fixedreservation").css("display",($(this).is(":checked")?"block":"none"));
            //$("#startreslegend").css("display",($(this).is(":checked")?"block":"none"));
            reloadCheck();
        });
        reloadCheck = function(){
            var w = new Array(1,1,1,1,1,1,1);            
            $(".wdCheck").each(function(index){
                w[index] = (($(this).is(":checked"))?1:0);
                if ($(this).is(":checked"))
                    $("#c"+index).removeAttr("disabled");
                else
                    $("#c"+index).attr("disabled","disabled");
            });
            cAdmin.opt.workingDates = w;
            var w = new Array(1,1,1,1,1,1,1);            
            $(".srCheck").each(function(index){
                w[index] = (($(this).is(":checked"))?1:0);
            });            
            cAdmin.opt.startReservationWeekly = w;
            $("#container_fixedreservation").css("display",($("#fixedreservation").is(":checked")?"block":"none"));
            //$("#startreslegend").css("display",($("#fixedreservation").is(":checked")?"block":"none"));
            cAdmin.opt.fixedreservation = $("#fixedreservation").is(":checked");
            cAdmin.reload();
        }
        reloadCheck();
    });
	$.fn.rcalendaradmin = function(options){
	    var opt = $.extend({},
				{
				    workingDates:[1,1,1,1,1,1,1],//default [1,1,1,1,1,1,1]
				    startReservationWeekly:[1,1,1,1,1,1,1],//default [0,0,0,0,0,0,0]
				    dformat:"yy-mm-dd",
				    fixedreservation:false,//0
				    numberOfMonths:1,
				    holidays:[],
				    startreservation:[]
				},options, true);
		opt.id = $(this).attr("id");
		var id = $(this).attr("id");
		var data = new Array();
		$("#"+opt.id).html( '<div class="dp" id="dp'+opt.id+'"></div>' );
        var selectedDates = {l:"",u:""};
		var render = function(){
		    $("#dp"+opt.id).datepicker("refresh");
		};
		var createCalendar = function()
		{
            $("#dp"+opt.id).datepicker({
                firstDay:opt.firstDay,
                showOtherMonths: true,
                dateFormat:opt.dformat,
                numberOfMonths:opt.numberOfMonths,
                onSelect: function(d,inst) {
                    //if (opt.fixedreservation)
                    //{
                        if (opt.holidays.indexOf(d)!="-1")  // holiday
                        {
		                    opt.holidays.splice(opt.holidays.indexOf(d),1);
		                    opt.startreservation[opt.startreservation.length] = d;
		                }
		                else if (opt.startreservation.indexOf(d)!="-1") //startreservation
		                {
		                    opt.startreservation.splice(opt.startreservation.indexOf(d),1);
		                }
		                else  //none
		                    opt.holidays[opt.holidays.length] = d;
		            //}
		            //else
		            //{
		            //    if (opt.holidays.indexOf(d)!="-1")  // holiday
		            //        opt.holidays.splice(opt.holidays.indexOf(d),1);
		            //    else  //none
		            //        opt.holidays[opt.holidays.length] = d;
		            //}
		                
		            savedata();
		            render();
		        },
                beforeShowDay: function (d) {
                    var c =  new Array($.datepicker.formatDate('yy-mm-dd', d));
                    if (opt.workingDates[d.getDay()]==0) //nonworking
                        if (c.indexOf("nonworking")=="-1")
                            c.push("nonworking"); 
                    if (opt.startReservationWeekly[d.getDay()]==0) //nonworking
                        if (c.indexOf("nonworking")=="-1")
                            c.push("nonworking");
                    if (opt.holidays.indexOf($.datepicker.formatDate(opt.dformat, d))!="-1")
                        if (c.indexOf("holidays")=="-1")
                            c.push("holidays");
                    if (opt.startreservation.indexOf($.datepicker.formatDate(opt.dformat, d))!="-1")
                        if (c.indexOf("startreservation")=="-1")
                            c.push("startreservation");
                    return [true,c.join(" ")];
		        }


            });
        };
		var loadData = function()
		{
		    var h = $("#holidays").val().split(";");
		    for (var i=0;i<h.length;i++)
		    {
		        if (h[i]!="")
		            opt.holidays[opt.holidays.length] = h[i];
		    }
		    var h = $("#startreservation").val().split(";");
		    for (var i=0;i<h.length;i++)
		    {
		        if (h[i]!="")
		            opt.startreservation[opt.startreservation.length] = h[i];
		    }
		    createCalendar();
		};
		
		var savedata = function()
		{
		    var str = "";
		    for (var i=0;i<opt.holidays.length;i++)
		    {
		        str += ";"+opt.holidays[i];
		    }
            $("#holidays").val(str);
            var str = "";
		    for (var i=0;i<opt.startreservation.length;i++)
		    {
		        str += ";"+opt.startreservation[i];
		    }
            $("#startreservation").val(str);
		};
		loadData();
		this.opt = opt;
		this.reload = function(){render();};
        return this;
	}
})(myjQuery);
});