var Total_Soft_PTable_Set_Count = 0;
var Total_Soft_PTable_Col_Type = "";
var TS_Col_Last_Id = [];
var New_Col_data = [];
let arr = []
let New_Cop_Set = [];
var New_Cop_Set_Edit = "Total_Soft_PTable_Edit_Theme";
var Total_Soft_PTable_Edit1 = 'Total_Soft_PTable_Select_New_Cols';
var Theme = 'Theme';
var id_col_index=0;
var flag='false';
var flag_edit='false';
var flag_edit_col='false';
 var flag_id=0;
 var flag_img = 0;
 let PTable_Manag_Num=0;
 let PTable_Manag_Count=1;
let Inp_Shadow_Color='';
Total_Soft_PTable_TImage_Type = (thisType) => {
    if ( flag_img ==1) {return false;}
        flag_img = 1;
       
    jQuery("#Total_Soft_PTable_TImage").val(thisType);
    jQuery("#Total_Soft_PTable_Col_Type").val(thisType);
    Total_Soft_PTable_Col_Type = thisType;
    Total_Soft_PTable_Edit1 = 'Total_Soft_PTable_Edit1';
    jQuery.ajax({
        type: 'POST',
        url: object.ajaxurl,
        data: {
            action: 'Total_Soft_PTable_Select_Manager', 
            foobarType: Total_Soft_PTable_Col_Type, 
        },
        success: function (response) {
            var datas = JSON.parse(response);
            var data = datas[0];
            PTable_Manag_Num=datas[1];
            
            jQuery(".Image_Container").fadeOut();
            jQuery(".Total_Soft_PTable_MainDiv").fadeIn().css("display", "flex");
            for (var i = 0; i < data.length; i++) {
                var Total_Soft_PTable_T = data[i]['Total_Soft_PTable_Them'].replace("type", "Theme ");
                jQuery(".Total_Soft_PTable_AMOTable").append(
                    '   <tr id="Total_Soft_PTable_AMOTable_tr_' + data[i]["id"] + '">' +
                    '     <td>' + parseInt(parseInt(i) + 1) + '</td>' +
                    '     <td>' + data[i]["Total_Soft_PTable_Title"] + '</td>' +
                    '     <td>Pricing ' + Total_Soft_PTable_T + '</td>' +
                    '     <td>' + data[i]["Total_Soft_PTable_Cols_Count"] + '</td>' +
                    '     <td><i class="totalsoft totalsoft-file-text" onclick="TotalSoftPTable_Clone(' + data[i]["id"] + ')"></i></td>' +
                    '     <td><i class="totalsoft totalsoft-pencil"  onclick=" Total_Soft_PTable_AMD2_But_Edit(' + data[i]["id"] + ',2)"></i></td>' +
                    '     <td>' +
                    '       <i class="totalsoft totalsoft-trash" onclick="TotalSoftPTable_Del(' + data[i]["id"] + ')"></i> ' +
                    '       <span class="Total_Soft_PTable_Del_Span">' +
                    '           <i class="Total_Soft_PTable_Del_Span_Yes totalsoft totalsoft-check" onclick="TotalSoftPTable_Del_Yes(' + data[i]["id"] + ')"></i>' +
                    '           <i class="Total_Soft_PTable_Del_Span_No totalsoft totalsoft-times" onclick="TotalSoftPTable_Del_No(' + data[i]["id"] + ')"></i>' +
                    '       </span>' +
                    '     </td>' +
                    ' </tr>'
                );
            }
           
        }
    })

}

TotalSoftPTable_Exit_From_Full_Page = () => {
    location.reload();
}

//Admin Menu
function Total_Soft_PTable_AMD2_But1(PTable_ID) {
    PTable_ID = parseInt(parseInt( PTable_Manag_Num)+PTable_Manag_Count);
    jQuery('.Total_Soft_PTable_AMD2').animate({'opacity': 0}, 500);
    jQuery('.Total_Soft_PTable_AMMTable').animate({'opacity': 0}, 500);
    jQuery('.Total_Soft_PTable_AMOTable').animate({'opacity': 0}, 500);
    jQuery('.Total_Soft_PTable_Save').animate({'opacity': 1}, 500);
    jQuery('.Total_Soft_PTable_Update').animate({'opacity': 0}, 500);
    jQuery('#Total_Soft_PTable_ID').html('[Total_Soft_Pricing_Table id="' + PTable_ID + '"]');
    jQuery('#Total_Soft_PTable_TID').html('&lt;?php echo do_shortcode(&#039;[Total_Soft_Pricing_Table id="' + PTable_ID + '"]&#039;);?&gt');


    setTimeout(function () {
        jQuery('.Total_Soft_PTable_AMD2').css('display', 'none');
        jQuery('.Total_Soft_PTable_AMMTable').css('display', 'none');
        jQuery('.Total_Soft_PTable_AMOTable').css('display', 'none');
        jQuery('.Total_Soft_PTable_Save').css('display', 'block');
        jQuery('.Total_Soft_PTable_Update').css('display', 'none');
        jQuery('.Total_Soft_PTable_AMD3').css('display', 'block');
        jQuery('.Total_Soft_PTable_AMMain_Div').css('display', 'block');
    }, 500)
    setTimeout(function () {
        jQuery('.Total_Soft_PTable_AMD3').animate({'opacity': 1}, 500);
        jQuery('.Total_Soft_PTable_AMMain_Div').animate({'opacity': 1}, 500);
    }, 500)

    New_Cop_Set_Edit = "Total_Soft_PTable_Edit_New_Theme";
    Total_Soft_PTable_Edit1 = 'Total_Soft_PTable_Select_New_Cols';
    Total_Soft_PTable_AMD2_But_Edit(PTable_ID,1)

}

function Total_Soft_PTable_AMD2_But_Edit(PTable_ID,key) {
    var Create_Action = Total_Soft_PTable_Edit1;
    if(key==2)Create_Action='Total_Soft_PTable_Edit1';
    jQuery('#Total_SoftPTable_Update').val(PTable_ID);
jQuery('body').css('overflow', 'hidden');
    var last_id = parseInt(parseInt(jQuery("#Total_Soft_PTable_New_Col_Last_Id").val()) + 1);
    var last_Index = parseInt(parseInt(last_id) - 1);

    jQuery.ajax({
        type: 'POST',
        url: object.ajaxurl,
        data: {
            action: Create_Action, 
            foobarUpdate_Id: PTable_ID, 
            foobarType: Total_Soft_PTable_Col_Type, 

        },
        beforeSend: function () {
        },
        success: function (response) {
            var PT_new_Array = JSON.parse(response);
            var Total_Soft_PTable_Select_Icon = jQuery('#Total_Soft_PTable_Select_Icon').html();
            var data = [], dataSet = [], dataMan = [];
            data.push(PT_new_Array[0]);
            dataSet.push(PT_new_Array[1]);
            dataMan.push(PT_new_Array[2]);
            if (Create_Action=='Total_Soft_PTable_Select_New_Cols') {
            id_col_index++;
            let d = data[0];
            
            New_Cop_Set.push(d[0]);
            var Total_Soft_PTable_Add_Set = parseInt(parseInt(jQuery('#Total_Soft_PTable_Add_Set').val()) + 1);
            jQuery('#Total_Soft_PTable_Add_Set').val(Total_Soft_PTable_Add_Set);
            var datamax=parseInt(PT_new_Array[3])+id_col_index;
            var datamax_id = datamax+1;
            jQuery("#Total_Soft_PTable_New_Col_Last_Id").val(datamax_id);
                

            }
            data = data[0];
            dataSet = dataSet[0];
            dataMan = dataMan[0];

            data.sort(function (a, b) {
                return a["index"] - b["index"]
            });
            dataSet.sort(function (a, b) {
                return a["index"] - b["index"]
            });
            jQuery('.TS_Desctop_View').html('' +

                '<div class="TS_PTable_Container"  id="TS_PTable_Container">'
            )
            for (var i = 0; i < dataSet.length; i++) {
                if (Create_Action=='Total_Soft_PTable_Select_New_Cols') {
                    dataSet[i]["id"]=datamax_id;
                    dataSet[i]["index"]=datamax;
                }
                if (dataSet[i]["TS_PTable_TType"] == "type1") {


                    jQuery('.TS_Desctop_View').append('' +
                        '    <style type="text/css">' +
                        '            :root {' +
                        '              --pseudo-backgroundcolor' + dataSet[i]["id"] + ':'+dataSet[i]["TS_PTable_ST_07"]+';' +
                        '           }' +
                        '            .TS_PTable_Container_Col_' + dataSet[i]["id"] + ' {' +
                        '               position: relative;' +
                        '               min-height: 1px;' +
                        '               width: ' + dataSet[i]["TS_PTable_ST_01"] + '%;' +
                        '               margin-bottom: 30px;' +
                        '               opacity: 1 !important' +
                        '           }' +
                        '            #TS_PTable_Col_' + dataSet[i]["id"] + ' {' +
                        '               margin-bottom:40px;' +
                        '               }' +
                        '        @media not screen and (min-width: 820px) {' +
                        '            .TS_PTable_Container_Col_' + dataSet[i]["id"] + ' {' +
                        '                width: 70%;' +
                        '                margin: 0 15% 40px 15%;' +
                        '                padding: 0 10px;' +
                        '            }' +
                        '        }' +
                        '    @media not screen and (min-width: 400px) {' +
                        '        .TS_PTable_Container_Col_' + dataSet[i]["id"] + ' {' +
                        '            width: 100%;' +
                        '            margin: 0 0 40px 0;' +
                        '            padding: 0 5px;' +
                        '        }' +
                        '    }' +
                        '     .TS_PTable_Shadow_' + dataSet[i]["id"] + ' {' +
                        '         position: relative;' +
                        '         z-index: 0;' +
                        '     }' +
                        '    .Box_Shadow_1_01_' + dataSet[i]["id"] + '{' +
                        '         box-shadow: 0 10px 6px -6px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '         -webkit-box-shadow: 0 10px 6px -6px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '         -moz-box-shadow: 0 10px 6px -6px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '     }' +
                        '     .Box_Shadow_1_02_' + dataSet[i]["id"] + ':before, .Box_Shadow_1_02_' + dataSet[i]["id"] + ':after {' +
                        '            bottom: 15px;' +
                        '            left: 10px;' +
                        '            width: 50%;' +
                        '            height: 20%;' +
                        '            max-width: 300px;' +
                        '            max-height: 100px;' +
                        '            -webkit-box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '            -moz-box-shadow: 0 15px 10px  var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '            box-shadow: 0 15px 10px  var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '            -webkit-transform: rotate(-3deg);' +
                        '            -moz-transform: rotate(-3deg);' +
                        '            -ms-transform: rotate(-3deg);' +
                        '            -o-transform: rotate(-3deg);' +
                        '            transform: rotate(-3deg);' +
                        '            z-index: -1;' +
                        '            position: absolute;' +
                        '            content: "";' +
                        '     }' +
                        '     .Box_Shadow_1_02_' + dataSet[i]["id"] + ':after {' +
                        '            transform: rotate(3deg);' +
                        '            -moz-transform: rotate(3deg);' +
                        '            -webkit-transform: rotate(3deg);' +
                        '            right: 10px;' +
                        '            left: auto;' +
                        '    }' +
                        '    .Box_Shadow_1_03_' + dataSet[i]["id"] + ':before, .Box_Shadow_1_03_' + dataSet[i]["id"] + ':after {' +
                        '       bottom: 15px;' +
                        '       left: 10px;' +
                        '       width: 50%;' +
                        '       height: 20%;' +
                        '       max-width: 300px;' +
                        '       max-height: 100px;' +
                        '       -webkit-box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '       -moz-box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '       box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '       -webkit-transform: rotate(-3deg);' +
                        '       -moz-transform: rotate(-3deg);' +
                        '       -ms-transform: rotate(-3deg);' +
                        '       -o-transform: rotate(-3deg);' +
                        '       transform: rotate(-3deg);' +
                        '       z-index: -1;' +
                        '       position: absolute;' +
                        '       content: "";' +
                        '    }' +
                        '    .Box_Shadow_1_04_' + dataSet[i]["id"] + ':after {' +
                        '        bottom: 15px;' +
                        '        right: 10px;' +
                        '        width: 50%;' +
                        '        height: 20%;' +
                        '        max-width: 300px;' +
                        '        max-height: 100px;' +
                        '        -webkit-box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '        -moz-box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '        box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '        -webkit-transform: rotate(3deg);' +
                        '        -moz-transform: rotate(3deg);' +
                        '        -ms-transform: rotate(3deg);' +
                        '        -o-transform: rotate(3deg);' +
                        '        transform: rotate(3deg);' +
                        '        z-index: -1;' +
                        '        position: absolute;' +
                        '        content: "";' +
                        '    }' +
                        '     .Box_Shadow_1_05_' + dataSet[i]["id"] + ':before, .Box_Shadow_1_05_' + dataSet[i]["id"] + ':after {' +
                        '         top: 15px;' +
                        '         left: 10px;' +
                        '         width: 50%;' +
                        '         height: 20%;' +
                        '         max-width: 300px;' +
                        '         max-height: 100px;' +
                        '         z-index: -1;' +
                        '         position: absolute;' +
                        '         content: "";' +
                        '         background: var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '         box-shadow: 0 -15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '         -webkit-box-shadow: 0 -15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '         -moz-box-shadow: 0 -15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '         transform: rotate(3deg);' +
                        '         -moz-transform: rotate(3deg);' +
                        '         -webkit-transform: rotate(3deg);' +
                        '     }' +
                        '     .Box_Shadow_1_05_' + dataSet[i]["id"] + ':after {' +
                        '           transform: rotate(-3deg);' +
                        '           -moz-transform: rotate(-3deg);' +
                        '           -webkit-transform: rotate(-3deg);' +
                        '           right: 10px;' +
                        '           left: auto;' +
                        '      }' +
                        '       .Box_Shadow_1_06_' + dataSet[i]["id"] + ' {' +
                        '           position: relative;' +
                        '           box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '           -webkit-box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '           -moz-box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '       }' +
                        '       .Box_Shadow_1_06_' + dataSet[i]["id"] + ':before, .Box_Shadow_1_06_' + dataSet[i]["id"] + ':after {' +
                        '           content: "";' +
                        '           position: absolute;' +
                        '           z-index: -1;' +
                        '           box-shadow: 0 0 20px  var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -webkit-box-shadow: 0 0 20px  --pseudo-backgroundcolor' + dataSet[i]["id"] + ';' +
                        '           -moz-box-shadow: 0 0 20px  --pseudo-backgroundcolor' + dataSet[i]["id"] + ';' +
                        '           top: 50%;' +
                        '           bottom: 0;' +
                        '           left: 10px;' +
                        '           right: 10px;' +
                        '           border-radius: 100px / 10px;' +
                        '       }' +
                        '       .Box_Shadow_1_07_' + dataSet[i]["id"] + ' {' +
                        '           position: relative;' +
                        '           box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '           -webkit-box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '           -moz-box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '       }' +
                        '       .Box_Shadow_1_07_' + dataSet[i]["id"] + ':before, .Box_Shadow_1_07_' + dataSet[i]["id"] + ':after {' +
                        '           content: "";' +
                        '           position: absolute;' +
                        '           z-index: -1;' +
                        '           box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -webkit-box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -moz-box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           top: 0;' +
                        '           bottom: 0;' +
                        '           left: 10px;' +
                        '           right: 10px;' +
                        '           border-radius: 100px / 10px;' +
                        '       }' +
                        '       .Box_Shadow_1_07_' + dataSet[i]["id"] + ':after {' +
                        '           right: 10px;' +
                        '           left: auto;' +
                        '           transform: skew(8deg) rotate(3deg);' +
                        '           -moz-transform: skew(8deg) rotate(3deg);' +
                        '           -webkit-transform: skew(8deg) rotate(3deg);' +
                        '       }' +
                        '       .Box_Shadow_1_08_' + dataSet[i]["id"] + ' {' +
                        '           position: relative;' +
                        '           box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '           -webkit-box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '           -moz-box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '       }' +
                        '       .Box_Shadow_1_08_' + dataSet[i]["id"] + ':before, .Box_Shadow_1_08_' + dataSet[i]["id"] + ':after {' +
                        '           content: "";' +
                        '           position: absolute;' +
                        '           z-index: -1;' +
                        '           box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -webkit-box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -moz-box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           top: 10px;' +
                        '           bottom: 10px;' +
                        '           left: 0;' +
                        '           right: 0;' +
                        '           border-radius: 100px / 10px;' +
                        '       }' +
                        '       .Box_Shadow_1_08_' + dataSet[i]["id"] + ':after {' +
                        '           right: 10px;' +
                        '           left: auto;' +
                        '           transform: skew(8deg) rotate(3deg);' +
                        '           -moz-transform: skew(8deg) rotate(3deg);' +
                        '           -webkit-transform: skew(8deg) rotate(3deg);' +
                        '       }' +
                        '       .Box_Shadow_1_09_' + dataSet[i]["id"] + ' {' +
                        '           box-shadow: 0 0 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -webkit-box-shadow: 0 0 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -moz-box-shadow: 0 0 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '       }' +
                        '       .Box_Shadow_1_10_' + dataSet[i]["id"] + '{' +
                        '           box-shadow: 4px -4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '           -moz-box-shadow: 4px -4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '           -webkit-box-shadow: 4px -4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ' ) ;' +
                        '       }' +
                        '       .Box_Shadow_1_11_' + dataSet[i]["id"] + '{' +
                        '           box-shadow: 5px 5px 3px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '           -moz-box-shadow: 5px 5px 3px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '           -webkit-box-shadow: 5px 5px 3px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '       }' +
                        '       .Box_Shadow_1_12_' + dataSet[i]["id"] + '{' +
                        '            box-shadow: 2px 2px white, 4px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -moz-box-shadow: 2px 2px white, 4px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -webkit-box-shadow: 2px 2px white, 4px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '       }' +
                        '       .Box_Shadow_1_13_' + dataSet[i]["id"] + '{' +
                        '            box-shadow: 8px 8px 18px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -moz-box-shadow: 8px 8px 18px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -webkit-box-shadow: 8px 8px 18px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '       }' +
                        '       .Box_Shadow_1_14_' + dataSet[i]["id"] + '{' +
                        '            box-shadow: 0 8px 6px -6px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -moz-box-shadow: 0 8px 6px -6px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -webkit-box-shadow: 0 8px 6px -6px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '       }' +
                        '       .Box_Shadow_1_15_' + dataSet[i]["id"] + '{' +
                        '            box-shadow: 0 0 18px 7px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -moz-box-shadow: 0 0 18px 7px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -webkit-box-shadow: 0 0 18px 7px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '       }' +
                        '    .TS_PTable__' + dataSet[i]["id"] + ' {' +
                        '        padding: 30px 0 !important;' +
                        '        border: ' + dataSet[i]["TS_PTable_ST_05"] + 'px solid ' + dataSet[i]["TS_PTable_ST_04"] + ';' +
                        '        text-align: center;' +
                        '        overflow-x: hidden;' +
                        '        position: relative;' +
                        '        background-color: ' + dataSet[i]["TS_PTable_ST_03"] + ';' +
                        '    }' +
                        '    .TS_PTable__' + dataSet[i]["id"] + ':before {' +
                        '        content: "";' +
                        '        border-right: 70px solid ' + dataSet[i]["TS_PTable_ST_28"] + ';' +
                        '        border-top: 70px solid transparent;' +
                        '        border-bottom: 70px solid transparent;' +
                        '        position: absolute;' +
                        '        top: 30px;' +
                        '        right: -100px;' +
                        '        transition: all 0.3s ease 0s;' +
                        '    }' +
                        '    .TS_PTable__' + dataSet[i]["id"] + ':hover:before {' +
                        '        right: 0;' +
                        '    }' +
                        '    .TS_PTable_Title_' + dataSet[i]["id"] + ' {' +
                        '        font-size: ' + dataSet[i]["TS_PTable_ST_08"] + 'px;' +
                        '        font-family: ' + dataSet[i]["TS_PTable_ST_09"] + ';' +
                        '        color: ' + dataSet[i]["TS_PTable_ST_10"] + ';' +
                        '        margin:  0 !important;' +
                        '        padding: 10px !important;' +
                        '        width: max-content;' +
                        '    }' +
                        '    .Feut_Hover,.TS_PTable_Title_' + dataSet[i]["id"] + ':focus,.TS_PTable_Title_' + dataSet[i]["id"] + ':hover,.TS_PTable_Cur_' + dataSet[i]["id"] + ':hover ,.TS_PTable_Cur_' + dataSet[i]["id"] + ':focus,.TS_PTable_Val_' + dataSet[i]["id"] + ':hover,.TS_PTable_Val_' + dataSet[i]["id"] + ':focus,.TS_PTable_PPlan_' + dataSet[i]["id"] + ':hover,.TS_PTable_PPlan_' + dataSet[i]["id"] + ':focus{' +
                        // '       box-shadow: 0 0 0 1pt #676666;' +
                        '       border-radius: 5px;' +
                        '       cursor: auto;' +
                        '    }' +
                        '    .TS_PTable_Title_IconTB_' + dataSet[i]["id"] + ' {' +
                        '        display: block;' +
                        '    }' +
                        '    .TS_PTable_Title_IconLR_' + dataSet[i]["id"] + ' {' +
                        '        margin: 0 10px !important;' +
                        '    }' +
                        '    .TS_PTable_Title_Icon_' + dataSet[i]["id"] + ' {' +
                        '        color: ' + dataSet[i]["TS_PTable_ST_11"] + ';' +
                        '        font-size: ' + dataSet[i]["TS_PTable_ST_12"] + 'px;' +
                        '    }' +
                        '    .TS_PTable_PValue_' + dataSet[i]["id"] + ' {' +
                        '        font-size: ' + dataSet[i]["TS_PTable_ST_14"] + 'px;' +
                        '        font-family: ' + dataSet[i]["TS_PTable_ST_15"] + ';' +
                        '        color: ' + dataSet[i]["TS_PTable_ST_16"] + ';' +
                        '    }' +
                        '    .TS_PTable_PPlan_' + dataSet[i]["id"] + ' {' +
                        '        display: inline-block;' +
                        '        font-size: ' + dataSet[i]["TS_PTable_ST_17"] + 'px;' +
                        '        color: ' + dataSet[i]["TS_PTable_ST_18"] + ';' +
                        '    }' +
                        '    .TS_PTable_Features_' + dataSet[i]["id"] + ', .TS_PTable_Features_Add {' +
                        '        padding: 0 !important;' +
                        '        margin: 20px 0 !important;' +
                        '        list-style: none;' +
                        '    }' +
                        '    .TS_PTable_Features_' + dataSet[i]["id"] + ' li:before {' +
                        '        content: "" !important;' +
                        '        display: none !important;' +
                        '    }' +
                        '    .TS_PTable_Features_' + dataSet[i]["id"] + ' li, .TS_PTable_Features_Add li {' +
                        '        font-size: ' + dataSet[i]["TS_PTable_ST_22"] + 'px;' +
                        '        font-family: ' + dataSet[i]["TS_PTable_ST_23"] + ';' +
                        '        padding: 8px;' +
                        '        display: flex;' +
                        '        align-items: center;' +
                        '        justify-content: center;' +
                        '    }' +
                        '    .TS_PTable_Features_' + dataSet[i]["id"] + ' li:nth-child(even), .TS_PTable_Features_Add li:nth-child(even) {' +
                        '        color: ' + dataSet[i]["TS_PTable_ST_21"] + ';' +
                        '        background: ' + dataSet[i]["TS_PTable_ST_19"] + ';' +
                        '    }' +
                        '    .TS_PTable_Features_' + dataSet[i]["id"] + ' li:nth-child(odd), .TS_PTable_Features_Add li:nth-child(odd) {' +
                        '        color: ' + dataSet[i]["TS_PTable_ST_21_1"] + ';' +
                        '        background: ' + dataSet[i]["TS_PTable_ST_20"] + ';' +
                        '    }' +
                        '    .TS_PTable_FIcon_' + dataSet[i]["id"] + ' {' +
                        '        color: ' + dataSet[i]["TS_PTable_ST_24"] + ';' +
                        '        font-size: ' + dataSet[i]["TS_PTable_ST_26"] + 'px;' +
                        '        margin: 0 10px !important;' +
                        '    }' +
                        '    .TS_PTable_FIcon_' + dataSet[i]["id"] + '.TS_PTable_FCheck {' +
                        '        color: ' + dataSet[i]["TS_PTable_ST_25"] + ';' +
                        '    }' +
                        '    .TS_PTable_Button_' + dataSet[i]["id"] + ' {' +
                        '        display: inline-block;' +
                        '        padding: 7px 30px !important;' +
                        '        background: ' + dataSet[i]["TS_PTable_ST_28"] + ';' +
                        '        color: ' + dataSet[i]["TS_PTable_ST_29"] + ';' +
                        '        font-size: ' + dataSet[i]["TS_PTable_ST_30"] + 'px;' +
                        '        font-family: ' + dataSet[i]["TS_PTable_ST_31"] + ';' +
                        '        text-decoration: none;' +
                        '        outline: none;' +
                        '        box-shadow: none;' +
                        '        -webkit-box-shadow: none;' +
                        '        -moz-box-shadow: none;' +
                        '        border-bottom: none;' +
                        '        transition: all 0.5s ease 0s;' +
                        '        cursor: pointer !important;' +
                        '    }' +
                        '    .TS_PTable_Button_' + dataSet[i]["id"] + ':hover, .TS_PTable_Button_' + dataSet[i]["id"] + ':focus {' +
                        '        text-decoration: none;' +
                        '        outline: none;' +
                        '        box-shadow: none;' +
                        '        -webkit-box-shadow: none;' +
                        '        -moz-box-shadow: none;' +
                        '        border-bottom: none;' +
                        '        background: ' + dataSet[i]["TS_PTable_ST_28"] + ';' +
                        '        color: ' + dataSet[i]["TS_PTable_ST_29"] + ';' +
                        '        font-size: ' + dataSet[i]["TS_PTable_ST_30"] + 'px;' +
                        '        font-family: ' + dataSet[i]["TS_PTable_ST_31"] + ';' +
                        '    }' +
                        '    .TS_PTable_BIconA_' + dataSet[i]["id"] + ', .TS_PTable_BIconB_' + dataSet[i]["id"] + ' {' +
                        '        font-size: ' + dataSet[i]["TS_PTable_ST_32"] + 'px;' +
                        '        color: ' + dataSet[i]["TS_PTable_ST_33"] + ';' +
                        '    }' +
                        '    .TS_PTable_BIconB_' + dataSet[i]["id"] + ' {' +
                        '        margin: 0 10px 0 0 !important;' +
                        '    }' +
                        '    .TS_PTable_BIconA_' + dataSet[i]["id"] + '{' +
                        '        margin: 0 10px !important;' +
                        '    }' +
                        '    .TS_PTable__' + dataSet[i]["id"] + ':hover .TS_PTable_Button_' + dataSet[i]["id"] + ' {' +
                        '        border-radius: 30px;' +
                        '    }' +
                        '</style>'
                    )
                } else if (dataSet[i]["TS_PTable_TType"] == "type2") {
                    jQuery('.TS_PTable_Container').append('' +
                        '    <style type="text/css">' +
                         '            :root {' +
                        '              --pseudo-backgroundcolor' + dataSet[i]["id"] + ':'+dataSet[i]["TS_PTable_ST_05"]+';' +
                        '           }' +
                        '       .TS_PTable_Container_Col_' + dataSet[i]["id"] + ' {' +
                        '              position: relative;' +
                        '              min-height: 1px;' +
                        '              float: left;' +
                        '              width: ' + dataSet[i]["TS_PTable_ST_01"] + '%;' +
                        '              margin-bottom: 40px !important;' +
                        '       }' +

                        '        @media not screen and (min-width: 820px) {' +
                        '            .TS_PTable_Container {' +
                        '                padding: 20px 5px;' +
                        '            }' +

                        '            .TS_PTable_Container_Col_' + dataSet[i]["id"] + ' {' +
                        '                width: 70%;' +
                        '                margin: 0 15% 40px 15%;' +
                        '                padding: 0 10px;' +
                        '            }' +
                        '        }' +

                        '        @media not screen and (min-width: 400px) {' +
                        '            .TS_PTable_Container {' +
                        '                padding: 20px 0;' +
                        '            }' +

                        '            .TS_PTable_Container_Col_' + dataSet[i]["id"] + ' {' +
                        '                width: 100%;' +
                        '                margin: 0 0 40px 0;' +
                        '                padding: 0 5px;' +
                        '            }' +
                        '        }' +

                        '        .TS_PTable_Shadow_' + dataSet[i]["id"] + ' {' +
                        '            position: relative;' +
                        '            z-index: 0;' +
                        '        }' +

                        '        .TS_PTable_Shadow_' + dataSet[i]["id"] + ':before, .TS_PTable_Shadow_' + dataSet[i]["id"] + ':after {' +
                        '            bottom: 15px;' +
                        '            left: 10px;' +
                        '            width: 50%;' +
                        '            height: 20%;' +
                        '            max-width: 300px;' +
                        '            max-height: 100px;' +
                        '            -webkit-box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '            -moz-box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '            box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '            -webkit-transform: rotate(-3deg);' +
                        '            -moz-transform: rotate(-3deg);' +
                        '            -ms-transform: rotate(-3deg);' +
                        '            -o-transform: rotate(-3deg);' +
                        '            transform: rotate(-3deg);' +
                        '            z-index: -1;' +
                        '            position: absolute;' +
                        '            content: "";' +
                        '        }' +
                        '    .TS_PTable_Shadow_' + dataSet[i]["id"] + ':after {' +
                        '            transform: rotate(3deg);' +
                        '            -moz-transform: rotate(3deg);' +
                        '            -webkit-transform: rotate(3deg);' +
                        '            right: 10px;' +
                        '            left: auto;' +
                        '        }' +
                        '    .Box_Shadow_2_01_' + dataSet[i]["id"] + ' {' +
                        '         box-shadow: 0 10px 6px -6px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '         -webkit-box-shadow: 0 10px 6px -6px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '         -moz-box-shadow: 0 10px 6px -6px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '     }' +
                        '     .Box_Shadow_2_02_' + dataSet[i]["id"] + ':before, .Box_Shadow_2_02_' + dataSet[i]["id"] + ':after {' +
                        '            bottom: 15px;' +
                        '            left: 10px;' +
                        '            width: 50%;' +
                        '            height: 20%;' +
                        '            max-width: 300px;' +
                        '            max-height: 100px;' +
                        '            -webkit-box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '            -moz-box-shadow: 0 15px 10px  var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '            box-shadow: 0 15px 10px  var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '            -webkit-transform: rotate(-3deg);' +
                        '            -moz-transform: rotate(-3deg);' +
                        '            -ms-transform: rotate(-3deg);' +
                        '            -o-transform: rotate(-3deg);' +
                        '            transform: rotate(-3deg);' +
                        '            z-index: -1;' +
                        '            position: absolute;' +
                        '            content: "";' +
                        '     }' +
                        '     .Box_Shadow_2_02_' + dataSet[i]["id"] + ':after {' +
                        '            transform: rotate(3deg);' +
                        '            -moz-transform: rotate(3deg);' +
                        '            -webkit-transform: rotate(3deg);' +
                        '            right: 10px;' +
                        '            left: auto;' +
                        '    }' +
                        '    .Box_Shadow_2_03_' + dataSet[i]["id"] + ':before, .Box_Shadow_2_03_' + dataSet[i]["id"] + ':after {' +
                        '       bottom: 15px;' +
                        '       left: 10px;' +
                        '       width: 50%;' +
                        '       height: 20%;' +
                        '       max-width: 300px;' +
                        '       max-height: 100px;' +
                        '       -webkit-box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '       -moz-box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '       box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '       -webkit-transform: rotate(-3deg);' +
                        '       -moz-transform: rotate(-3deg);' +
                        '       -ms-transform: rotate(-3deg);' +
                        '       -o-transform: rotate(-3deg);' +
                        '       transform: rotate(-3deg);' +
                        '       z-index: -1;' +
                        '       position: absolute;' +
                        '       content: "";' +
                        '    }' +
                        '    .Box_Shadow_2_04_' + dataSet[i]["id"] + ':after {' +
                        '        bottom: 15px;' +
                        '        right: 10px;' +
                        '        width: 50%;' +
                        '        height: 20%;' +
                        '        max-width: 300px;' +
                        '        max-height: 100px;' +
                        '        -webkit-box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '        -moz-box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '        box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '        -webkit-transform: rotate(3deg);' +
                        '        -moz-transform: rotate(3deg);' +
                        '        -ms-transform: rotate(3deg);' +
                        '        -o-transform: rotate(3deg);' +
                        '        transform: rotate(3deg);' +
                        '        z-index: -1;' +
                        '        position: absolute;' +
                        '        content: "";' +
                        '    }' +
                        '     .Box_Shadow_2_05_' + dataSet[i]["id"] + ':before, .Box_Shadow_2_05_' + dataSet[i]["id"] + ':after {' +
                        '         top: 15px;' +
                        '         left: 10px;' +
                        '         width: 50%;' +
                        '         height: 20%;' +
                        '         max-width: 300px;' +
                        '         max-height: 100px;' +
                        '         z-index: -1;' +
                        '         position: absolute;' +
                        '         content: "";' +
                        '         background: var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '         box-shadow: 0 -15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '         -webkit-box-shadow: 0 -15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ' );' +
                        '         -moz-box-shadow: 0 -15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ' );' +
                        '         transform: rotate(3deg);' +
                        '         -moz-transform: rotate(3deg);' +
                        '         -webkit-transform: rotate(3deg);' +
                        '     }' +
                        '     .Box_Shadow_2_05_' + dataSet[i]["id"] + ':after {' +
                        '           transform: rotate(-3deg);' +
                        '           -moz-transform: rotate(-3deg);' +
                        '           -webkit-transform: rotate(-3deg);' +
                        '           right: 10px;' +
                        '           left: auto;' +
                        '      }' +
                        '       .Box_Shadow_2_06_' + dataSet[i]["id"] + ' {' +
                        '           position: relative;' +
                        '           box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ' ), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '           -webkit-box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ' ), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '           -moz-box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '       }' +
                        '       .Box_Shadow_2_06_' + dataSet[i]["id"] + ':before, .Box_Shadow_2_06_' + dataSet[i]["id"] + ':after {' +
                        '           content: "";' +
                        '           position: absolute;' +
                        '           z-index: -1;' +
                        '           box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -webkit-box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -moz-box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           top: 50%;' +
                        '           bottom: 0;' +
                        '           left: 10px;' +
                        '           right: 10px;' +
                        '           border-radius: 100px / 10px;' +
                        '       }' +
                        '       .Box_Shadow_2_07_' + dataSet[i]["id"] + ' {' +
                        '           position: relative;' +
                        '           box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '           -webkit-box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '           -moz-box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '       }' +
                        '       .Box_Shadow_2_07_' + dataSet[i]["id"] + ':before, .Box_Shadow_2_07_' + dataSet[i]["id"] + ':after {' +
                        '           content: "";' +
                        '           position: absolute;' +
                        '           z-index: -1;' +
                        '           box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -webkit-box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -moz-box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           top: 0;' +
                        '           bottom: 0;' +
                        '           left: 10px;' +
                        '           right: 10px;' +
                        '           border-radius: 100px / 10px;' +
                        '       }' +
                        '       .Box_Shadow_2_07_' + dataSet[i]["id"] + ':after {' +
                        '           right: 10px;' +
                        '           left: auto;' +
                        '           transform: skew(8deg) rotate(3deg);' +
                        '           -moz-transform: skew(8deg) rotate(3deg);' +
                        '           -webkit-transform: skew(8deg) rotate(3deg);' +
                        '       }' +
                        '       .Box_Shadow_2_08_' + dataSet[i]["id"] + ' {' +
                        '           position: relative;' +
                        '           box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '           -webkit-box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '           -moz-box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '       }' +
                        '       .Box_Shadow_2_08_' + dataSet[i]["id"] + ':before, .Box_Shadow_2_08_' + dataSet[i]["id"] + ':after {' +
                        '           content: "";' +
                        '           position: absolute;' +
                        '           z-index: -1;' +
                        '           box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -webkit-box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -moz-box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           top: 10px;' +
                        '           bottom: 10px;' +
                        '           left: 0;' +
                        '           right: 0;' +
                        '           border-radius: 100px / 10px;' +
                        '       }' +
                        '       .Box_Shadow_2_08_' + dataSet[i]["id"] + ':after {' +
                        '           right: 10px;' +
                        '           left: auto;' +
                        '           transform: skew(8deg) rotate(3deg);' +
                        '           -moz-transform: skew(8deg) rotate(3deg);' +
                        '           -webkit-transform: skew(8deg) rotate(3deg);' +
                        '       }' +
                        '       .Box_Shadow_2_09_' + dataSet[i]["id"] + ' {' +
                        '           box-shadow: 0 0 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -webkit-box-shadow: 0 0 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -moz-box-shadow: 0 0 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '       }' +
                        '       .Box_Shadow_2_10_' + dataSet[i]["id"] + '{' +
                        '           box-shadow: 4px -4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -moz-box-shadow: 4px -4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '           -webkit-box-shadow: 4px -4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '       }' +
                        '       .Box_Shadow_2_11_' + dataSet[i]["id"] + '{' +
                        '           box-shadow: 5px 5px 3px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '           -moz-box-shadow: 5px 5px 3px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '           -webkit-box-shadow: 5px 5px 3px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '       }' +
                        '       .Box_Shadow_2_12_' + dataSet[i]["id"] + '{' +
                        '            box-shadow: 2px 2px white, 4px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -moz-box-shadow: 2px 2px white, 4px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -webkit-box-shadow: 2px 2px white, 4px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '       }' +
                        '       .Box_Shadow_2_13_' + dataSet[i]["id"] + '{' +
                        '            box-shadow: 8px 8px 18px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -moz-box-shadow: 8px 8px 18px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -webkit-box-shadow: 8px 8px 18px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '       }' +
                        '       .Box_Shadow_2_14_' + dataSet[i]["id"] + '{' +
                        '            box-shadow: 0 8px 6px -6px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -moz-box-shadow: 0 8px 6px -6px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -webkit-box-shadow: 0 8px 6px -6px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '       }' +
                        '       .Box_Shadow_2_15_' + dataSet[i]["id"] + '{' +
                        '            box-shadow: 0 0 18px 7px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -moz-box-shadow: 0 0 18px 7px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -webkit-box-shadow: 0 0 18px 7px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '       }' +
                        '       .TS_PTable__' + dataSet[i]["id"] + ' {' +
                        '           text-align: center;' +
                        '           position: relative;' +
                        '           background: ' + dataSet[i]["TS_PTable_ST_19"] + ' ;' +
                        '        }' +
                        '        .TS_PTable_Div1_' + dataSet[i]["id"] + ' {' +
                        '            background-color: ' + dataSet[i]["TS_PTable_ST_03"] + ' ;' +
                        '            padding: 30px 0 1px !important;' +
                        '        }' +
                        '        .TS_PTable_Title_' + dataSet[i]["id"] + ' {' +
                        '            font-size:  ' + dataSet[i]["TS_PTable_ST_06"] + 'px;' +
                        '            font-family:  ' + dataSet[i]["TS_PTable_ST_07"] + ';' +
                        '            color:  ' + dataSet[i]["TS_PTable_ST_08"] + ';' +
                        '            margin:  0 !important;' +
                        '            padding: 10px !important;' +
                        '        }' +
                        '       .Feut_Hover,.TS_PTable_Title_' + dataSet[i]["id"] + ':focus,.TS_PTable_Title_' + dataSet[i]["id"] + ':hover,.TS_PTable_PCur_' + dataSet[i]["id"] + ':hover ,.TS_PTable_PCur_' + dataSet[i]["id"] + ':focus,.TS_PTable_PVal_' + dataSet[i]["id"] + ':hover,.TS_PTable_PVal_' + dataSet[i]["id"] + ':focus,.TS_PTable_PPlan_' + dataSet[i]["id"] + ':hover,.TS_PTable_PPlan_' + dataSet[i]["id"] + ':focus{' +
                        // '          box-shadow: 0 0 0 1pt #676666;' +
                        '          border-radius: 5px;' +
                        '          cursor: auto;' +
                        '       }' +
                        '        .TS_PTable_Title_Icon_' + dataSet[i]["id"] + ' {' +
                        '            display: block;' +
                        '        }' +
                        '        .TS_PTable_Title_Icon_' + dataSet[i]["id"] + ' {' +
                        '            color: ' + dataSet[i]["TS_PTable_ST_09"] + ';' +
                        '            font-size: ' + dataSet[i]["TS_PTable_ST_10"] + 'px;' +
                        '        }' +
                        '        .TS_PTable_PValue_' + dataSet[i]["id"] + ' {' +
                        '            padding: 20px 0 14px !important;' +
                        '            margin: 23px 0px 30px 0px !important;' +
                        '            background: ' + dataSet[i]["TS_PTable_ST_11"] + ';' +
                        '            font-family: ' + dataSet[i]["TS_PTable_ST_12"] + ';' +
                        '            color: ' + dataSet[i]["TS_PTable_ST_13"] + ';' +
                        '            position: relative;' +
                        '            transition: all 0.3s ease-in-out 0s;' +
                        '            -moz-transition: all 0.3s ease-in-out 0s;' +
                        '            -webkit-transition: all 0.3s ease-in-out 0s;' +
                        '        }' +
                        '        .TS_PTable__' + dataSet[i]["id"] + ':hover .TS_PTable_PValue_' + dataSet[i]["id"] + ' {' +
                        '            background: ' + dataSet[i]["TS_PTable_ST_17"] + '!important;' +
                        '            color: ' + dataSet[i]["TS_PTable_ST_18"] + ';' +
                        '        }' +
                        '        .TS_PTable_PValue_' + dataSet[i]["id"] + ':before, .TS_PTable_PValue_' + dataSet[i]["id"] + ':after {' +
                        '            content: "";' +
                        '            display: block;' +
                        '            border-width: 13px 5px 11px;' +
                        '            border-style: solid;' +
                        '            border-color: transparent ' + dataSet[i]["TS_PTable_ST_11"] + ' ' + dataSet[i]["TS_PTable_ST_11"] + ' transparent;' +
                        '            position: absolute;' +
                        '            left: 0;' +
                        '            transition: all 0.3s ease-in-out 0s;' +
                        '            -moz-transition: all 0.3s ease-in-out 0s;' +
                        '            -webkit-transition: all 0.3s ease-in-out 0s;' +
                        '        }' +
                        '        .TS_PTable_PValue_' + dataSet[i]["id"] + ':after {' +
                        '            border-width: 11px 5px;' +
                        '            border-color: transparent transparent ' + dataSet[i]["TS_PTable_ST_11"] + ' ' + dataSet[i]["TS_PTable_ST_11"] + ';' +
                        '            left: auto;' +
                        '            right: 0;' +
                        '        }' +
                        '        .TS_PTable__' + dataSet[i]["id"] + ':hover .TS_PTable_PValue_' + dataSet[i]["id"] + ':before {' +
                        '            border-color: transparent ' + dataSet[i]["TS_PTable_ST_17"] + ' ' + dataSet[i]["TS_PTable_ST_17"] + ' transparent;' +
                        '        }' +
                        '        .TS_PTable__' + dataSet[i]["id"] + ':hover .TS_PTable_PValue_' + dataSet[i]["id"] + ':after {' +
                        '            border-color: transparent transparent ' + dataSet[i]["TS_PTable_ST_17"] + ' ' + dataSet[i]["TS_PTable_ST_17"] + ';' +
                        '        }' +
                        '        .TS_PTable_Amount_' + dataSet[i]["id"] + ' {' +
                        '            display: inline-block;' +
                        '            font-size: ' + dataSet[i]["TS_PTable_ST_15"] + 'px;' +
                        '            position: relative;' +
                        '        }' +
                        '        .TS_PTable_PCur_' + dataSet[i]["id"] + ' {' +
                        '            font-size: ' + dataSet[i]["TS_PTable_ST_14"] + 'px;' +
                        '            top: 0 !important;' +
                        '            vertical-align: super !important;' +
                        '            line-height: 1 !important;' +
                        '        }' +
                        '        .TS_PTable_PPlan_' + dataSet[i]["id"] + ' {' +
                        '            font-size: ' + dataSet[i]["TS_PTable_ST_16"] + 'px;' +
                        '        }' +
                        '        .TS_PTable_Features_' + dataSet[i]["id"] + ' {' +
                        '            padding: 0 !important;' +
                        '            margin: 0 !important;' +
                        '            list-style: none;' +
                        '            background: ' + dataSet[i]["TS_PTable_ST_19"] + ';' +
                        '        }' +
                        '        .TS_PTable_Features_' + dataSet[i]["id"] + ' li:before {' +
                        '            content: "" !important;' +
                        '            display: none !important;' +
                        '        }' +
                        '        .TS_PTable_Features_' + dataSet[i]["id"] + ' li {' +
                        '            background: ' + dataSet[i]["TS_PTable_ST_19"] + ';' +
                        '            color: ' + dataSet[i]["TS_PTable_ST_20"] + ';' +
                        '            font-size: ' + dataSet[i]["TS_PTable_ST_21"] + 'px;' +
                        '            font-family: ' + dataSet[i]["TS_PTable_ST_22"] + ';' +
                        '            line-height: 1;' +
                        '            padding: 10px;' +
                        '            display: flex;' +
                        '            align-items: center;' +
                        '            justify-content: center;' +
                        '        }' +
                        '        .TS_PTable_FIcon_' + dataSet[i]["id"] + ' {' +
                        '            color: ' + dataSet[i]["TS_PTable_ST_23"] + ';' +
                        '            font-size: ' + dataSet[i]["TS_PTable_ST_25"] + 'px;' +
                        '            margin: 0 10px !important;' +
                        '        }' +
                        '        .TS_PTable_FIcon_' + dataSet[i]["id"] + '.TS_PTable_FCheck {' +
                        '            color: ' + dataSet[i]["TS_PTable_ST_24"] + ';' +
                        '        }' +
                        '        .TS_PTable_Div2_' + dataSet[i]["id"] + ' {' +
                        '            background-color: ' + dataSet[i]["TS_PTable_ST_03"] + ';' +
                        '            padding: 20px 0 30px !important;' +
                        '        }' +
                        '        .TS_PTable_Button_' + dataSet[i]["id"] + ' {' +
                        '            /*display: flex;*/' +
                        '                width:100%' +
                        '            justify-content: center;' +
                        '            padding: 10px 0 !important;' +
                        '            width: 100%;' +
                        '            font-size: ' + dataSet[i]["TS_PTable_ST_27"] + 'px ;' +
                        '            font-family: ' + dataSet[i]["TS_PTable_ST_28"] + ';' +
                        '            background: ' + dataSet[i]["TS_PTable_ST_11"] + ';' +
                        '            color: ' + dataSet[i]["TS_PTable_ST_13"] + ';' +
                        '            border-top: 2px solid' + dataSet[i]["TS_PTable_ST_13"] + ';' +
                        '            border-bottom: 2px solid' + dataSet[i]["TS_PTable_ST_13"] + ';' +
                        '            transition: all 0.5s ease 0s;' +
                        '            -moz-transition: all 0.5s ease 0s;' +
                        '            -webkit-transition: all 0.5s ease 0s;' +
                        '            text-decoration: none;' +
                        '            outline: none;' +
                        '            box-shadow: none;' +
                        '            -webkit-box-shadow: none;' +
                        '            -moz-box-shadow: none;' +
                        '            cursor: pointer !important;' +
                        '        }' +
                        '        .TS_PTable__' + dataSet[i]["id"] + ':hover .TS_PTable_Button_' + dataSet[i]["id"] + ' {' +
                        '            background: ' + dataSet[i]["TS_PTable_ST_17"] + ';' +
                        '            border-top: 2px solid ' + dataSet[i]["TS_PTable_ST_18"] + ';' +
                        '            border-bottom: 2px solid ' + dataSet[i]["TS_PTable_ST_18"] + ';' +
                        '            color:  ' + dataSet[i]["TS_PTable_ST_18"] + ';' +
                        '        }' +
                        '        .TS_PTable_Button_' + dataSet[i]["id"] + ':hover, .TS_PTable_Button_' + dataSet[i]["id"] + ':focus {' +
                        '            text-decoration: none;' +
                        '            outline: none;' +
                        '            box-shadow: none;' +
                        '            -webkit-box-shadow: none;' +
                        '            -moz-box-shadow: none;' +
                        '        }' +
                        '        .TS_PTable_BIconA_' + dataSet[i]["id"] + ', .TS_PTable_BIconB_' + dataSet[i]["id"] + ' {' +
                        '            font-size: ' + dataSet[i]["TS_PTable_ST_29"] + 'px;' +
                        '        }' +
                        '        .TS_PTable_BIconB_' + dataSet[i]["id"] + ' {' +
                        '            margin: 0 10px 0 0 !important;' +
                        '        }' +
                        '        .TS_PTable_BIconA_' + dataSet[i]["id"] + ' {' +
                        '            margin: 0 10px !important;' +
                        '        }' +
                        '</style>'
                    )
                } else if (dataSet[i]["TS_PTable_TType"] == "type3") {
                    jQuery('.TS_PTable_Container').append('' +
                        ' <style type="text/css">' +
                        '      :root {' +
                        '              --pseudo-backgroundcolor:'+dataSet[i]["TS_PTable_ST_07"]+';' +
                        '        }' +
                        '    .TS_PTable_Container_Col_' + dataSet[i]["id"] + ' {' +
                        '   position: relative;' +
                        '   min-height: 1px;' +
                        '   float: left;' +
                        '   width:' + dataSet[i]["TS_PTable_ST_01"] + '%;' +
                        '           margin-bottom: 30px !important;' +
                        '        }' +
                        '        @media not screen and (min-width: 820px) {' +
                        '           .TS_PTable_Container {' +
                        '               padding: 20px 5px;' +
                        '           }' +

                        '           .TS_PTable_Container_Col_' + dataSet[i]["id"] + ' {' +
                        '               width: 70%;' +
                        '               margin: 0 15% 40px 15%;' +
                        '               padding: 0 10px;' +
                        '           }' +
                        '       }' +

                        '        @media not screen and (min-width: 400px) {' +
                        '            .TS_PTable_Container {' +
                        '                padding: 20px 0;' +
                        '            }' +

                        '            .TS_PTable_Container_Col_' + dataSet[i]["id"] + ' {' +
                        '                width: 100%;' +
                        '                margin: 0 0 40px 0;' +
                        '                padding: 0 5px;' +
                        '            }' +
                        '        }' +

                        '        .TS_PTable_Shadow_' + dataSet[i]["id"] + ' {' +
                        '           position: relative;' +
                        '           z-index: 0;' +
                        '       }' +

                        '        .TS_PTable_Shadow_' + dataSet[i]["id"] + ' { ' +
                        '               box-shadow: 8px 8px 18px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '); ' +
                        '               -moz-box-shadow: 8px 8px 18px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '); ' +
                        '               -webkit-box-shadow: 8px 8px 18px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '); ' +
                        '         } ' +
                        '    .Box_Shadow_3_01_' + dataSet[i]["id"] + ' {' +
                        '         box-shadow: 0 10px 6px -6px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '         -webkit-box-shadow: 0 10px 6px -6px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '         -moz-box-shadow: 0 10px 6px -6px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '     }' +
                        '     .Box_Shadow_3_02_' + dataSet[i]["id"] + ':before, .Box_Shadow_3_02_' + dataSet[i]["id"] + ':after {' +
                        '            bottom: 15px;' +
                        '            left: 10px;' +
                        '            width: 50%;' +
                        '            height: 20%;' +
                        '            max-width: 300px;' +
                        '            max-height: 100px;' +
                        '            -webkit-box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '            -moz-box-shadow: 0 15px 10px  var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '            box-shadow: 0 15px 10px  var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '            -webkit-transform: rotate(-3deg);' +
                        '            -moz-transform: rotate(-3deg);' +
                        '            -ms-transform: rotate(-3deg);' +
                        '            -o-transform: rotate(-3deg);' +
                        '            transform: rotate(-3deg);' +
                        '            z-index: -1;' +
                        '            position: absolute;' +
                        '            content: "";' +
                        '     }' +
                        '     .Box_Shadow_3_02_' + dataSet[i]["id"] + ':after {' +
                        '            transform: rotate(3deg);' +
                        '            -moz-transform: rotate(3deg);' +
                        '            -webkit-transform: rotate(3deg);' +
                        '            right: 10px;' +
                        '            left: auto;' +
                        '    }' +
                        '    .Box_Shadow_3_03_' + dataSet[i]["id"] + ':before, .Box_Shadow_3_03_' + dataSet[i]["id"] + ':after {' +
                        '       bottom: 15px;' +
                        '       left: 10px;' +
                        '       width: 50%;' +
                        '       height: 20%;' +
                        '       max-width: 300px;' +
                        '       max-height: 100px;' +
                        '       -webkit-box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '       -moz-box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '       box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '       -webkit-transform: rotate(-3deg);' +
                        '       -moz-transform: rotate(-3deg);' +
                        '       -ms-transform: rotate(-3deg);' +
                        '       -o-transform: rotate(-3deg);' +
                        '       transform: rotate(-3deg);' +
                        '       z-index: -1;' +
                        '       position: absolute;' +
                        '       content: "";' +
                        '    }' +
                        '    .Box_Shadow_3_04_' + dataSet[i]["id"] + ':after {' +
                        '        bottom: 15px;' +
                        '        right: 10px;' +
                        '        width: 50%;' +
                        '        height: 20%;' +
                        '        max-width: 300px;' +
                        '        max-height: 100px;' +
                        '        -webkit-box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '        -moz-box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '        box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '        -webkit-transform: rotate(3deg);' +
                        '        -moz-transform: rotate(3deg);' +
                        '        -ms-transform: rotate(3deg);' +
                        '        -o-transform: rotate(3deg);' +
                        '        transform: rotate(3deg);' +
                        '        z-index: -1;' +
                        '        position: absolute;' +
                        '        content: "";' +
                        '    }' +
                        '     .Box_Shadow_3_05_' + dataSet[i]["id"] + ':before, .Box_Shadow_3_05_' + dataSet[i]["id"] + ':after {' +
                        '         top: 15px;' +
                        '         left: 10px;' +
                        '         width: 50%;' +
                        '         height: 20%;' +
                        '         max-width: 300px;' +
                        '         max-height: 100px;' +
                        '         z-index: -1;' +
                        '         position: absolute;' +
                        '         content: "";' +
                        '         background: var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '         box-shadow: 0 -15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '         -webkit-box-shadow: 0 -15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '         -moz-box-shadow: 0 -15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '         transform: rotate(3deg);' +
                        '         -moz-transform: rotate(3deg);' +
                        '         -webkit-transform: rotate(3deg);' +
                        '     }' +
                        '     .Box_Shadow_3_05_' + dataSet[i]["id"] + ':after {' +
                        '           transform: rotate(-3deg);' +
                        '           -moz-transform: rotate(-3deg);' +
                        '           -webkit-transform: rotate(-3deg);' +
                        '           right: 10px;' +
                        '           left: auto;' +
                        '      }' +
                        '       .Box_Shadow_3_06_' + dataSet[i]["id"] + ' {' +
                        '           position: relative;' +
                        '           box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '           -webkit-box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '           -moz-box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '       }' +
                        '       .Box_Shadow_3_06_' + dataSet[i]["id"] + ':before, .Box_Shadow_3_06_' + dataSet[i]["id"] + ':after {' +
                        '           content: "";' +
                        '           position: absolute;' +
                        '           z-index: -1;' +
                        '           box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -webkit-box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -moz-box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           top: 50%;' +
                        '           bottom: 0;' +
                        '           left: 10px;' +
                        '           right: 10px;' +
                        '           border-radius: 100px / 10px;' +
                        '       }' +
                        '       .Box_Shadow_3_07_' + dataSet[i]["id"] + ' {' +
                        '           position: relative;' +
                        '           box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '           -webkit-box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '           -moz-box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '       }' +
                        '       .Box_Shadow_3_07_' + dataSet[i]["id"] + ':before, .Box_Shadow_3_07_' + dataSet[i]["id"] + ':after {' +
                        '           content: "";' +
                        '           position: absolute;' +
                        '           z-index: -1;' +
                        '           box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -webkit-box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -moz-box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           top: 0;' +
                        '           bottom: 0;' +
                        '           left: 10px;' +
                        '           right: 10px;' +
                        '           border-radius: 100px / 10px;' +
                        '       }' +
                        '       .Box_Shadow_3_07_' + dataSet[i]["id"] + ':after {' +
                        '           right: 10px;' +
                        '           left: auto;' +
                        '           transform: skew(8deg) rotate(3deg);' +
                        '           -moz-transform: skew(8deg) rotate(3deg);' +
                        '           -webkit-transform: skew(8deg) rotate(3deg);' +
                        '       }' +
                        '       .Box_Shadow_3_08_' + dataSet[i]["id"] + ' {' +
                        '           position: relative;' +
                        '           box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '           -webkit-box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor ' + dataSet[i]["id"] + ') inset;' +
                        '           -moz-box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '       }' +
                        '       .Box_Shadow_3_08_' + dataSet[i]["id"] + ':before, .Box_Shadow_3_08_' + dataSet[i]["id"] + ':after {' +
                        '           content: "";' +
                        '           position: absolute;' +
                        '           z-index: -1;' +
                        '           box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -webkit-box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -moz-box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           top: 10px;' +
                        '           bottom: 10px;' +
                        '           left: 0;' +
                        '           right: 0;' +
                        '           border-radius: 100px / 10px;' +
                        '       }' +
                        '       .Box_Shadow_3_08_' + dataSet[i]["id"] + ':after {' +
                        '           right: 10px;' +
                        '           left: auto;' +
                        '           transform: skew(8deg) rotate(3deg);' +
                        '           -moz-transform: skew(8deg) rotate(3deg);' +
                        '           -webkit-transform: skew(8deg) rotate(3deg);' +
                        '       }' +
                        '       .Box_Shadow_3_09_' + dataSet[i]["id"] + ' {' +
                        '           box-shadow: 0 0 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -webkit-box-shadow: 0 0 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -moz-box-shadow: 0 0 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '       }' +
                        '       .Box_Shadow_3_10_' + dataSet[i]["id"] + '{' +
                        '           box-shadow: 4px -4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '           -moz-box-shadow: 4px -4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '           -webkit-box-shadow: 4px -4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '       }' +
                        '       .Box_Shadow_3_11_' + dataSet[i]["id"] + '{' +
                        '           box-shadow: 5px 5px 3px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '           -moz-box-shadow: 5px 5px 3px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '           -webkit-box-shadow: 5px 5px 3px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '       }' +
                        '       .Box_Shadow_3_12_' + dataSet[i]["id"] + '{' +
                        '            box-shadow: 2px 2px white, 4px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -moz-box-shadow: 2px 2px white, 4px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -webkit-box-shadow: 2px 2px white, 4px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '       }' +
                        '       .Box_Shadow_3_13_' + dataSet[i]["id"] + '{' +
                        '            box-shadow: 8px 8px 18px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -moz-box-shadow: 8px 8px 18px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -webkit-box-shadow: 8px 8px 18px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '       }' +
                        '       .Box_Shadow_3_14_' + dataSet[i]["id"] + '{' +
                        '            box-shadow: 0 8px 6px -6px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -moz-box-shadow: 0 8px 6px -6px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -webkit-box-shadow: 0 8px 6px -6px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '       }' +
                        '       .Box_Shadow_3_15_' + dataSet[i]["id"] + '{' +
                        '            box-shadow: 0 0 18px 7px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -moz-box-shadow: 0 0 18px 7px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -webkit-box-shadow: 0 0 18px 7px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '       }' +
                        '        .TS_PTable__' + dataSet[i]["id"] + ' {' +
                        '          text-align: center;' +
                        '          position: relative;' +
                        '          border: ' + dataSet[i]["TS_PTable_ST_05"] + 'px solid ' + dataSet[i]["TS_PTable_ST_04"] + ';' +
                        '            margin-top: 30px;' +
                        '        }' +

                        '        .TS_PTable_Div1_' + dataSet[i]["id"] + ' {' +
                        '            background-color: ' + dataSet[i]["TS_PTable_ST_03"] + ';' +
                        '            padding: 50px 0 1px !important;' +
                        '        }' +

                        '        .TS_PTable_Div2_' + dataSet[i]["id"] + ' {' +
                        '            background-color: ' + dataSet[i]["TS_PTable_ST_03"] + ';' +
                        '            padding: 20px 0 25px !important;' +
                        '        }' +

                        '        .TS_PTable_Title_Icon_' + dataSet[i]["id"] + ' {' +
                        '            width: 80px;' +
                        '            height: 80px;' +
                        '            border-radius: 50%;' +
                        '            background: ' + dataSet[i]["TS_PTable_ST_12"] + ';' +
                        '            border: ' + dataSet[i]["TS_PTable_ST_05"] + 'px solid ' + dataSet[i]["TS_PTable_ST_04"] + ';' +
                        '            position: absolute;' +
                        '            top: -40px;' +
                        '            left: 50%;' +
                        '            padding: 10px !important;' +
                        '            transform: translateX(-50%);' +
                        '            -moz-transform: translateX(-50%);' +
                        '            -webkit-transform: translateX(-50%);' +
                        '            transition: all 0.5s ease 0s;' +
                        '            -moz-transition: all 0.5s ease 0s;' +
                        '            -webkit-transition: all 0.5s ease 0s;' +
                        '        }' +

                        '        .TS_PTable__' + dataSet[i]["id"] + ':hover .TS_PTable_Title_Icon_' + dataSet[i]["id"] + ' {' +
                        '            background: ' + dataSet[i]["TS_PTable_ST_15"] + ' !important;' +
                        '            transform: translateX(-50%) !important;' +
                        '            -moz-transform: translateX(-50%) !important;' +
                        '            -webkit-transform: translateX(-50%) !important;' +
                        '        }' +

                        '        .TS_PTable_Title_Icon_' + dataSet[i]["id"] + ' i {' +
                        '            width: 100%;' +
                        '            height: 100%;' +
                        '            line-height: 58px;' +
                        '            border-radius: 50%;' +
                        '            color: ' + dataSet[i]["TS_PTable_ST_12"] + ';' +
                        '            background: ' + dataSet[i]["TS_PTable_ST_13"] + ';' +
                        '            font-size: ' + dataSet[i]["TS_PTable_ST_14"] + 'px;' +
                        '            transition: all 0.5s ease 0s;' +
                        '            -moz-transition: all 0.5s ease 0s;' +
                        '            -webkit-transition: all 0.5s ease 0s;' +
                        '        }' +

                        '        .TS_PTable__' + dataSet[i]["id"] + ':hover .TS_PTable_Title_Icon_' + dataSet[i]["id"] + ' i {' +
                        '            color: ' + dataSet[i]["TS_PTable_ST_15"] + ';' +
                        '            background: ' + dataSet[i]["TS_PTable_ST_15"] + ' !important;' +
                        '        }' +

                        '        .TS_PTable_PValue_' + dataSet[i]["id"] + ' {' +
                        '            display: inline-block;' +
                        '            font-family: ' + dataSet[i]["TS_PTable_ST_17"] + ';' +
                        '            color: ' + dataSet[i]["TS_PTable_ST_18"] + ';' +
                        '            font-size: ' + dataSet[i]["TS_PTable_ST_19"] + 'px;' +
                        '            position: relative;' +
                        '        }' +

                        '        .TS_PTable_PCur_' + dataSet[i]["id"] + ' {' +
                        '            font-size: ' + dataSet[i]["TS_PTable_ST_19"] + 'px;' +
                        '            top: 0 !important;' +
                        '            vertical-align: super !important;' +
                        '            line-height: 1 !important;' +
                        '        }' +

                        '        .TS_PTable_PPlan_' + dataSet[i]["id"] + ' {' +
                        '            display: block;' +
                        '            font-family: ' + dataSet[i]["TS_PTable_ST_17"] + ';' +
                        '            color: ' + dataSet[i]["TS_PTable_ST_18"] + ';' +
                        '            font-size: ' + dataSet[i]["TS_PTable_ST_21"] + 'px;' +
                        '            width: max-content;' +
                        '        }' +

                        '        .TS_PTable_Header_' + dataSet[i]["id"] + ' {' +
                        '            position: relative;' +
                        '            z-index: 1;' +
                        '        }' +

                        '        .TS_PTable_Header_' + dataSet[i]["id"] + ':after {' +
                        '            content: "" !important;' +
                        '            width: 100% !important;' +
                        '            height: 1px;' +
                        '            background: ' + dataSet[i]["TS_PTable_ST_04"] + ';' +
                        '            position: absolute;' +
                        '            top: 50%;' +
                        '            left: 0;' +
                        '            z-index: -1;' +
                        '        }' +

                        '        .TS_PTable_Title_' + dataSet[i]["id"] + ' {' +
                        '            width: fit-content;' +
                        '            margin: 10px auto !important;' +
                        '            padding: 10px 15px !important;' +
                        '            font-size: ' + dataSet[i]["TS_PTable_ST_08"] + 'px;' +
                        '            font-family: ' + dataSet[i]["TS_PTable_ST_09"] + ';' +
                        '            color: ' + dataSet[i]["TS_PTable_ST_10"] + ';' +
                        '            background: ' + dataSet[i]["TS_PTable_ST_11"] + ';' +
                        '            position: relative;' +
                        '            z-index: 1;' +
                        '        }' +
                        '        .Feut_Hover,.TS_PTable_Title_' + dataSet[i]["id"] + ':focus,.TS_PTable_Title_' + dataSet[i]["id"] + ':hover,.TS_PTable_PCur_' + dataSet[i]["id"] + ':hover ,.TS_PTable_PCur_' + dataSet[i]["id"] + ':focus,.TS_PTable_PVal_' + dataSet[i]["id"] + ':hover,.TS_PTable_PVal_' + dataSet[i]["id"] + ':focus,.TS_PTable_PPlan_' + dataSet[i]["id"] + ':hover,.TS_PTable_PPlan_' + dataSet[i]["id"] + ':focus{' +
                        // '           box-shadow: 0 0 0 1pt #676666;' +
                        '           border-radius: 5px;' +
                        '           cursor: auto;' +
                        '        }' +
                        '        .TS_PTable_Features_' + dataSet[i]["id"] + ' {' +
                        '            list-style: none;' +
                        '            padding: 0 !important;' +
                        '            margin: 0 !important;' +
                        '            background: ' + dataSet[i]["TS_PTable_ST_22"] + ';' +
                        '        }' +

                        '        .TS_PTable_Features_' + dataSet[i]["id"] + ' li:before {' +
                        '            content: "" !important;' +
                        '            display: none !important;' +
                        '        }' +

                        '        .TS_PTable_Features_' + dataSet[i]["id"] + ' li {' +
                        '            background: ' + dataSet[i]["TS_PTable_ST_22"] + ';' +
                        '            color: ' + dataSet[i]["TS_PTable_ST_23"] + ';' +
                        '            font-size: ' + dataSet[i]["TS_PTable_ST_24"] + 'px;' +
                        '            font-family: ' + dataSet[i]["TS_PTable_ST_25"] + ';' +
                        '            line-height: 1;' +
                        '            padding: 10px;' +
                        '            display: flex;' +
                        '            align-items: center;' +
                        '            justify-content: center;' +
                        '        }' +

                        '        .TS_PTable_FIcon_' + dataSet[i]["id"] + ' {' +
                        '            color: ' + dataSet[i]["TS_PTable_ST_26"] + ';' +
                        '            font-size: ' + dataSet[i]["TS_PTable_ST_28"] + 'px;' +
                        '            margin: 0 10px !important;' +
                        '        }' +

                        '        .TS_PTable_FIcon_' + dataSet[i]["id"] + '.TS_PTable_FCheck {' +
                        '            color: ' + dataSet[i]["TS_PTable_ST_27"] + ';' +
                        '        }' +

                        '        .TS_PTable_Button_' + dataSet[i]["id"] + ' {' +
                        '            display: inline-block;' +
                        '            font-size: ' + dataSet[i]["TS_PTable_ST_30"] + 'px;' +
                        '            font-family: ' + dataSet[i]["TS_PTable_ST_31"] + ';' +
                        '            color: ' + dataSet[i]["TS_PTable_ST_35"] + ';' +
                        '            background: ' + dataSet[i]["TS_PTable_ST_34"] + ';' +
                        '            border: 1px solid ' + dataSet[i]["TS_PTable_ST_35"] + ';' +
                        '            padding: 5px 20px !important;' +
                        '            transition: all 0.5s ease 0s;' +
                        '            -moz-transition: all 0.5s ease 0s;' +
                        '            -webkit-transition: all 0.5s ease 0s;' +
                        '            text-decoration: none;' +
                        '            outline: none;' +
                        '            box-shadow: none;' +
                        '            -webkit-box-shadow: none;' +
                        '            -moz-box-shadow: none;' +
                        '            cursor: pointer !important;' +
                        '        }' +

                        '        .TS_PTable_Button_' + dataSet[i]["id"] + ':hover {' +
                        '            background: ' + dataSet[i]["TS_PTable_ST_36"] + ';' +
                        '            color: ' + dataSet[i]["TS_PTable_ST_37"] + ';' +
                        '            border: 1px solid ' + dataSet[i]["TS_PTable_ST_37"] + ';' +
                        '        }' +

                        '        .TS_PTable_Button_' + dataSet[i]["id"] + ':hover, .TS_PTable_Button_' + dataSet[i]["id"] + ':focus {' +
                        '            text-decoration: none;' +
                        '            outline: none;' +
                        '            box-shadow: none;' +
                        '            -webkit-box-shadow: none;' +
                        '            -moz-box-shadow: none;' +
                        '        }' +

                        '        .TS_PTable_BIconA_' + dataSet[i]["id"] + ', .TS_PTable_BIconB_' + dataSet[i]["id"] + ' {' +
                        '            font-size: ' + dataSet[i]["TS_PTable_ST_32"] + 'px;' +
                        '        }' +

                        '        .TS_PTable_BIconB_' + dataSet[i]["id"] + ' {' +
                        '            margin: 0 10px 0 0 !important;' +
                        '        }' +

                        '        .TS_PTable_BIconA_' + dataSet[i]["id"] + ' {' +
                        '            margin: 0 10px !important;' +
                        '        }' +
                        '</style>'
                    )
                } else if (dataSet[i]["TS_PTable_TType"] == "type4") {
                    jQuery('.TS_PTable_Container').append('' +
                        ' <style type="text/css">' +
                        '            :root {' +
                        '              --pseudo-backgroundcolor' + dataSet[i]["id"] + ':'+dataSet[i]["TS_PTable_ST_06"]+';' +
                        '           }' +
                        '         .TS_PTable_Container_Col_' + dataSet[i]["id"] + ' {' +
                        '             position: relative;' +
                        '             min-height: 1px;' +
                        '             float: left;' +
                        '             width: ' + dataSet[i]["TS_PTable_ST_01"] + '%;' +
                        '             margin-bottom: 70px !important;' +
                        '         }' +
                        '         @media not screen and (min-width: 820px) {' +
                        '             .TS_PTable_Container {' +
                        '                 padding: 20px 5px;' +
                        '             }' +
                        '             .TS_PTable_Container_Col_' + dataSet[i]["id"] + ' {' +
                        '                 width: 70%;' +
                        '                 margin: 0 15% 40px 15%;' +
                        '                 padding: 0 10px;' +
                        '             }' +
                        '         }' +
                        '         @media not screen and (min-width: 400px) {' +
                        '             .TS_PTable_Container {' +
                        '                 padding: 20px 0;' +
                        '             }' +
                        '             .TS_PTable_Container_Col_' + dataSet[i]["id"] + ' {' +
                        '                 width: 100%;' +
                        '                 margin: 0 0 40px 0;' +
                        '                 padding: 0 5px;' +
                        '             }' +
                        '         }' +
                        '         .TS_PTable_Shadow_' + dataSet[i]["id"] + ' {' +
                        '             position: relative;' +
                        '             z-index: 0;' +
                        '         }' +
                        '         .TS_PTable_Shadow_' + dataSet[i]["id"] + ' {' +
                        '             box-shadow: 8px 8px 18px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '             -moz-box-shadow: 8px 8px 18px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '             -webkit-box-shadow: 8px 8px 18px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '         }' +
                        '    .Box_Shadow_4_01_' + dataSet[i]["id"] + ' {' +
                        '         box-shadow: 0 10px 6px -6px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '         -webkit-box-shadow: 0 10px 6px -6px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '         -moz-box-shadow: 0 10px 6px -6px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '     }' +
                        '     .Box_Shadow_4_02_' + dataSet[i]["id"] + ':before, .Box_Shadow_4_02_' + dataSet[i]["id"] + ':after {' +
                        '            bottom: 15px;' +
                        '            left: 10px;' +
                        '            width: 50%;' +
                        '            height: 20%;' +
                        '            max-width: 300px;' +
                        '            max-height: 100px;' +
                        '            -webkit-box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '            -moz-box-shadow: 0 15px 10px  var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '            box-shadow: 0 15px 10px  var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '            -webkit-transform: rotate(-3deg);' +
                        '            -moz-transform: rotate(-3deg);' +
                        '            -ms-transform: rotate(-3deg);' +
                        '            -o-transform: rotate(-3deg);' +
                        '            transform: rotate(-3deg);' +
                        '            z-index: -1;' +
                        '            position: absolute;' +
                        '            content: "";' +
                        '     }' +
                        '     .Box_Shadow_4_02_' + dataSet[i]["id"] + ':after {' +
                        '            transform: rotate(3deg);' +
                        '            -moz-transform: rotate(3deg);' +
                        '            -webkit-transform: rotate(3deg);' +
                        '            right: 10px;' +
                        '            left: auto;' +
                        '    }' +
                        '    .Box_Shadow_4_03_' + dataSet[i]["id"] + ':before, .Box_Shadow_4_03_' + dataSet[i]["id"] + ':after {' +
                        '       bottom: 15px;' +
                        '       left: 10px;' +
                        '       width: 50%;' +
                        '       height: 20%;' +
                        '       max-width: 300px;' +
                        '       max-height: 100px;' +
                        '       -webkit-box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '       -moz-box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '       box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '       -webkit-transform: rotate(-3deg);' +
                        '       -moz-transform: rotate(-3deg);' +
                        '       -ms-transform: rotate(-3deg);' +
                        '       -o-transform: rotate(-3deg);' +
                        '       transform: rotate(-3deg);' +
                        '       z-index: -1;' +
                        '       position: absolute;' +
                        '       content: "";' +
                        '    }' +
                        '    .Box_Shadow_4_04_' + dataSet[i]["id"] + ':after {' +
                        '        bottom: 15px;' +
                        '        right: 10px;' +
                        '        width: 50%;' +
                        '        height: 20%;' +
                        '        max-width: 300px;' +
                        '        max-height: 100px;' +
                        '        -webkit-box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '        -moz-box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '        box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '        -webkit-transform: rotate(3deg);' +
                        '        -moz-transform: rotate(3deg);' +
                        '        -ms-transform: rotate(3deg);' +
                        '        -o-transform: rotate(3deg);' +
                        '        transform: rotate(3deg);' +
                        '        z-index: -1;' +
                        '        position: absolute;' +
                        '        content: "";' +
                        '    }' +
                        '     .Box_Shadow_4_05_' + dataSet[i]["id"] + ':before, .Box_Shadow_4_05_' + dataSet[i]["id"] + ':after {' +
                        '         top: 15px;' +
                        '         left: 10px;' +
                        '         width: 50%;' +
                        '         height: 20%;' +
                        '         max-width: 300px;' +
                        '         max-height: 100px;' +
                        '         z-index: -1;' +
                        '         position: absolute;' +
                        '         content: "";' +
                        '         background: var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '         box-shadow: 0 -15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '         -webkit-box-shadow: 0 -15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '         -moz-box-shadow: 0 -15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '         transform: rotate(3deg);' +
                        '         -moz-transform: rotate(3deg);' +
                        '         -webkit-transform: rotate(3deg);' +
                        '     }' +
                        '     .Box_Shadow_4_05_' + dataSet[i]["id"] + ':after {' +
                        '           transform: rotate(-3deg);' +
                        '           -moz-transform: rotate(-3deg);' +
                        '           -webkit-transform: rotate(-3deg);' +
                        '           right: 10px;' +
                        '           left: auto;' +
                        '      }' +
                        '       .Box_Shadow_4_06_' + dataSet[i]["id"] + ' {' +
                        '           position: relative;' +
                        '           box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '           -webkit-box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '           -moz-box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '       }' +
                        '       .Box_Shadow_4_06_' + dataSet[i]["id"] + ':before, .Box_Shadow_4_06_' + dataSet[i]["id"] + ':after {' +
                        '           content: "";' +
                        '           position: absolute;' +
                        '           z-index: -1;' +
                        '           box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -webkit-box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -moz-box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           top: 50%;' +
                        '           bottom: 0;' +
                        '           left: 10px;' +
                        '           right: 10px;' +
                        '           border-radius: 100px / 10px;' +
                        '       }' +
                        '       .Box_Shadow_4_07_' + dataSet[i]["id"] + ' {' +
                        '           position: relative;' +
                        '           box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '           -webkit-box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '           -moz-box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '       }' +
                        '       .Box_Shadow_4_07_' + dataSet[i]["id"] + ':before, .Box_Shadow_4_07_' + dataSet[i]["id"] + ':after {' +
                        '           content: "";' +
                        '           position: absolute;' +
                        '           z-index: -1;' +
                        '           box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -webkit-box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -moz-box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           top: 0;' +
                        '           bottom: 0;' +
                        '           left: 10px;' +
                        '           right: 10px;' +
                        '           border-radius: 100px / 10px;' +
                        '       }' +
                        '       .Box_Shadow_4_07_' + dataSet[i]["id"] + ':after {' +
                        '           right: 10px;' +
                        '           left: auto;' +
                        '           transform: skew(8deg) rotate(3deg);' +
                        '           -moz-transform: skew(8deg) rotate(3deg);' +
                        '           -webkit-transform: skew(8deg) rotate(3deg);' +
                        '       }' +
                        '       .Box_Shadow_4_08_' + dataSet[i]["id"] + ' {' +
                        '           position: relative;' +
                        '           box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '           -webkit-box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '           -moz-box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '       }' +
                        '       .Box_Shadow_4_08_' + dataSet[i]["id"] + ':before, .Box_Shadow_4_08_' + dataSet[i]["id"] + ':after {' +
                        '           content: "";' +
                        '           position: absolute;' +
                        '           z-index: -1;' +
                        '           box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -webkit-box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -moz-box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           top: 10px;' +
                        '           bottom: 10px;' +
                        '           left: 0;' +
                        '           right: 0;' +
                        '           border-radius: 100px / 10px;' +
                        '       }' +
                        '       .Box_Shadow_4_08_' + dataSet[i]["id"] + ':after {' +
                        '           right: 10px;' +
                        '           left: auto;' +
                        '           transform: skew(8deg) rotate(3deg);' +
                        '           -moz-transform: skew(8deg) rotate(3deg);' +
                        '           -webkit-transform: skew(8deg) rotate(3deg);' +
                        '       }' +
                        '       .Box_Shadow_4_09_' + dataSet[i]["id"] + ' {' +
                        '           box-shadow: 0 0 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -webkit-box-shadow: 0 0 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -moz-box-shadow: 0 0 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '       }' +
                        '       .Box_Shadow_4_10_' + dataSet[i]["id"] + '{' +
                        '           box-shadow: 4px -4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '           -moz-box-shadow: 4px -4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '           -webkit-box-shadow: 4px -4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '       }' +
                        '       .Box_Shadow_4_11_' + dataSet[i]["id"] + '{' +
                        '           box-shadow: 5px 5px 3px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '           -moz-box-shadow: 5px 5px 3px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '           -webkit-box-shadow: 5px 5px 3px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '       }' +
                        '       .Box_Shadow_4_12_' + dataSet[i]["id"] + '{' +
                        '            box-shadow: 2px 2px white, 4px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -moz-box-shadow: 2px 2px white, 4px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -webkit-box-shadow: 2px 2px white, 4px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '       }' +
                        '       .Box_Shadow_4_13_' + dataSet[i]["id"] + '{' +
                        '            box-shadow: 8px 8px 18px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -moz-box-shadow: 8px 8px 18px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -webkit-box-shadow: 8px 8px 18px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '       }' +
                        '       .Box_Shadow_4_14_' + dataSet[i]["id"] + '{' +
                        '            box-shadow: 0 8px 6px -6px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -moz-box-shadow: 0 8px 6px -6px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -webkit-box-shadow: 0 8px 6px -6px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '       }' +
                        '       .Box_Shadow_4_15_' + dataSet[i]["id"] + '{' +
                        '            box-shadow: 0 0 18px 7px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -moz-box-shadow: 0 0 18px 7px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -webkit-box-shadow: 0 0 18px 7px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '       }' +
                        '         .TS_PTable__' + dataSet[i]["id"] + ' {' +
                        '             text-align: center;' +
                        '             position: relative;' +
                        '         }' +
                        '         .TS_PTable_Div1_' + dataSet[i]["id"] + ' {' +
                        '             background-color: ' + dataSet[i]["TS_PTable_ST_03"] + ';' +
                        '             padding: 30px 0 !important;' +
                        '             transition: all 0.3s ease 0s;' +
                        '             -moz-transition: all 0.3s ease 0s;' +
                        '             -webkit-transition: all 0.3s ease 0s;' +
                        '             position: relative;' +
                        '         }' +
                        '         .TS_PTable__' + dataSet[i]["id"] + ':hover .TS_PTable_Div1_' + dataSet[i]["id"] + ' {' +
                        '             background-color: ' + dataSet[i]["TS_PTable_ST_04"] + ';' +
                        '         }' +
                        '         .TS_PTable_Div1_' + dataSet[i]["id"] + ':before, .TS_PTable_Div1_' + dataSet[i]["id"] + ':after {' +
                        '             content: "" !important;' +
                        '             width: 16px !important;' +
                        '             height: 16px !important;' +
                        '             border-radius: 50%;' +
                        '             border: 1px solid ' + dataSet[i]["TS_PTable_ST_12"] + ';' +
                        '             position: absolute;' +
                        '             bottom: 12px;' +
                        '         }' +
                        '         .TS_PTable_Div1_' + dataSet[i]["id"] + ':before {' +
                        '             left: 40px;' +
                        '         }' +
                        '         .TS_PTable_Div1_' + dataSet[i]["id"] + ':after {' +
                        '             right: 40px;' +
                        '         }' +
                        '         .TS_PTable_Title_' + dataSet[i]["id"] + ' {' +
                        '             font-size: ' + dataSet[i]["TS_PTable_ST_07"] + 'px;' +
                        '             font-family: ' + dataSet[i]["TS_PTable_ST_08"] + ';' +
                        '             color: ' + dataSet[i]["TS_PTable_ST_09"] + ';' +
                        '             margin: 0 0 15px 0 !important;' +
                        '             padding: 15px !important;' +
                        '             letter-spacing: 2px !important;' +
                        '         }' +
                        '         .Feut_Hover,.TS_PTable_Title_' + dataSet[i]["id"] + ':focus,.TS_PTable_Title_' + dataSet[i]["id"] + ':hover,.TS_PTable_PCur_' + dataSet[i]["id"] + ':hover ,.TS_PTable_PCur_' + dataSet[i]["id"] + ':focus,.TS_PTable_PVal_' + dataSet[i]["id"] + ':hover,.TS_PTable_PVal_' + dataSet[i]["id"] + ':focus,.TS_PTable_PPlan_' + dataSet[i]["id"] + ':hover,.TS_PTable_PPlan_' + dataSet[i]["id"] + ':focus{' +
                        // '            box-shadow: 0 0 0 1pt #676666;' +
                        '            border-radius: 5px;' +
                        '            cursor: auto;' +
                        '         }' +
                        '         .TS_PTable__' + dataSet[i]["id"] + ':hover .TS_PTable_Title_' + dataSet[i]["id"] + ' {' +
                        '             color: ' + dataSet[i]["TS_PTable_ST_10"] + ';' +
                        '         }' +
                        '         .TS_PTable_Amount_' + dataSet[i]["id"] + ' {' +
                        '             display: inline-block;' +
                        '             font-family: ' + dataSet[i]["TS_PTable_ST_11"] + ';' +
                        '             color: ' + dataSet[i]["TS_PTable_ST_12"] + ';' +
                        '             font-size: ' + dataSet[i]["TS_PTable_ST_15"] + 'px;' +
                        '             position: relative;' +
                        '             transition: all 0.3s ease 0s;' +
                        '             -moz-transition: all 0.3s ease 0s;' +
                        '             -webkit-transition: all 0.3s ease 0s;' +
                        '             margin-bottom: 20px !important;' +
                        '         }' +
                        '         .TS_PTable_PCur_' + dataSet[i]["id"] + ' {' +
                        '             font-size: ' + dataSet[i]["TS_PTable_ST_14"] + 'px;' +
                        '             top: 0px !important;' +
                        '             vertical-align: super !important;' +
                        '             line-height: 1 !important;' +
                        '         }' +
                        '         .TS_PTable_PPlan_' + dataSet[i]["id"] + ' {' +
                        '             font-size: ' + dataSet[i]["TS_PTable_ST_16"] + 'px;' +
                        '             color: ' + dataSet[i]["TS_PTable_ST_09"] + ';' +
                        '             bottom: 0;' +
                        '         }' +
                        '         .TS_PTable__' + dataSet[i]["id"] + ':hover .TS_PTable_Amount_' + dataSet[i]["id"] + ' {' +
                        '             color: ' + dataSet[i]["TS_PTable_ST_13"] + ';' +
                        '         }' +
                        '         .TS_PTable__' + dataSet[i]["id"] + ':hover .TS_PTable_PPlan_' + dataSet[i]["id"] + ' {' +
                        '             color: ' + dataSet[i]["TS_PTable_ST_10"] + ';' +
                        '         }' +
                        '         .TS_PTable_Content_' + dataSet[i]["id"] + ' {' +
                        '             padding-top: 50px;' +
                        '             background: ' + dataSet[i]["TS_PTable_ST_17"] + ';' +
                        '             position: relative;' +
                        '         }' +
                        '         .TS_PTable_Content_' + dataSet[i]["id"] + ':before, .TS_PTable_Content_' + dataSet[i]["id"] + ':after {' +
                        '             content: "" !important;' +
                        '             width: 16px !important;' +
                        '             height: 16px !important;' +
                        '             border-radius: 50%;' +
                        '             border: 1px solid ' + dataSet[i]["TS_PTable_ST_18"] + ';' +
                        '             position: absolute;' +
                        '             top: 12px;' +
                        '         }' +
                        '         .TS_PTable_Content_' + dataSet[i]["id"] + ':before {' +
                        '             left: 40px;' +
                        '         }' +
                        '         .TS_PTable_Content_' + dataSet[i]["id"] + ':after {' +
                        '             right: 40px;' +
                        '         }' +
                        '         .TS_PTable_Features_' + dataSet[i]["id"] + ' {' +
                        '             padding: 0 10px !important;' +
                        '             margin: 0 !important;' +
                        '             list-style: none;' +
                        '         }' +
                        '         .TS_PTable_Features_' + dataSet[i]["id"] + ':before, .TS_PTable_Features_' + dataSet[i]["id"] + ':after {' +
                        '             content: "" !important;' +
                        '             width: 8px !important;' +
                        '             height: 46px !important;' +
                        '             border-radius: 3px;' +
                        '             background: ' + dataSet[i]["TS_PTable_ST_09"] + ';' +
                        '             position: absolute;' +
                        '             top: -73px;' +
                        '             z-index: 1;' +
                        '             box-shadow: 0 0 5px #707070;' +
                        '             transition: all 0.3s ease 0s;' +
                        '         }' +
                        '         .TS_PTable__' + dataSet[i]["id"] + ':hover .TS_PTable_Features_' + dataSet[i]["id"] + ':before, .TS_PTable__' + dataSet[i]["id"] + ':hover .TS_PTable_Features_' + dataSet[i]["id"] + ':after {' +
                        '             background: ' + dataSet[i]["TS_PTable_ST_10"] + ' !important;' +
                        '         }' +
                        '         .TS_PTable_Features_' + dataSet[i]["id"] + ':before {' +
                        '             left: 45px;' +
                        '         }' +
                        '         .TS_PTable_Features_' + dataSet[i]["id"] + ':after {' +
                        '             right: 45px;' +
                        '         }' +
                        '         .TS_PTable_Features_' + dataSet[i]["id"] + ' li {' +
                        '             background: ' + dataSet[i]["TS_PTable_ST_17"] + ';' +
                        '             color: ' + dataSet[i]["TS_PTable_ST_18"] + ';' +
                        '             font-size: ' + dataSet[i]["TS_PTable_ST_19"] + 'px;' +
                        '             font-family: ' + dataSet[i]["TS_PTable_ST_20"] + ';' +
                        '             border-bottom: 1px solid ' + dataSet[i]["TS_PTable_ST_18"] + ' !important;' +
                        '             line-height: 1;' +
                        '             padding: 10px;' +
                        '            display: flex;' +
                        '            align-items: center;' +
                        '            justify-content: center;' +
                        '         }' +
                        '         .TS_PTable_Features_' + dataSet[i]["id"] + ' li:last-child {' +
                        '             border-bottom: none;' +
                        '         }' +
                        '         .TS_PTable_Features_' + dataSet[i]["id"] + ' li:before {' +
                        '             content: "" !important;' +
                        '             display: none !important;' +
                        '         }' +
                        '         .TS_PTable_FIcon_' + dataSet[i]["id"] + ' {' +
                        '             color: ' + dataSet[i]["TS_PTable_ST_21"] + ';' +
                        '             font-size: ' + dataSet[i]["TS_PTable_ST_23"] + 'px;' +
                        '             margin: 0 10px !important;' +
                        '         }' +
                        '         .TS_PTable_FIcon_' + dataSet[i]["id"] + '.TS_PTable_FCheck {' +
                        '             color: ' + dataSet[i]["TS_PTable_ST_22"] + ';' +
                        '         }' +
                        '         .TS_PTable_Button_' + dataSet[i]["id"] + ' {' +
                        '             display: inline-block;' +
                        '             padding: 5px 20px !important;' +
                        '             margin: 15px 0 !important;' +
                        '             font-size: ' + dataSet[i]["TS_PTable_ST_25"] + 'px;' +
                        '             font-family: ' + dataSet[i]["TS_PTable_ST_26"] + ';' +
                        '             background: ' + dataSet[i]["TS_PTable_ST_29"] + ';' +
                        '             color: ' + dataSet[i]["TS_PTable_ST_30"] + ';' +
                        '             transition: all 0.3s ease 0s;' +
                        '             -moz-transition: all 0.3s ease 0s;' +
                        '             -webkit-transition: all 0.3s ease 0s;' +
                        '             text-decoration: none;' +
                        '             outline: none;' +
                        '             box-shadow: none;' +
                        '             -webkit-box-shadow: none;' +
                        '             -moz-box-shadow: none;' +
                        '             cursor: pointer !important;' +
                        '         }' +
                        '         .TS_PTable__' + dataSet[i]["id"] + ':hover .TS_PTable_Button_' + dataSet[i]["id"] + ' {' +
                        '             background: ' + dataSet[i]["TS_PTable_ST_31"] + ';' +
                        '             color: ' + dataSet[i]["TS_PTable_ST_32"] + ';' +
                        '         }' +
                        '         .TS_PTable_Button_' + dataSet[i]["id"] + ':hover, .TS_PTable_Button_' + dataSet[i]["id"] + ':focus {' +
                        '             text-decoration: none;' +
                        '             outline: none;' +
                        '             box-shadow: none;' +
                        '             -webkit-box-shadow: none;' +
                        '             -moz-box-shadow: none;' +
                        '         }' +
                        '         .TS_PTable_BIconA_' + dataSet[i]["id"] + ', .TS_PTable_BIconB_' + dataSet[i]["id"] + ' {' +
                        '             font-size: ' + dataSet[i]["TS_PTable_ST_27"] + 'px;' +
                        '         }' +
                        '         .TS_PTable_BIconB_' + dataSet[i]["id"] + ' {' +
                        '             margin: 0 10px 0 0 !important;' +
                        '         }' +
                        '         .TS_PTable_BIconA_' + dataSet[i]["id"] + ' {' +
                        '             margin: 0 10px !important;' +
                        '         }' +
                        '     </style>'
                    )
                } else if (dataSet[i]["TS_PTable_TType"] == "type5") {
                    jQuery('.TS_PTable_Container').append('' +
                        ' <style type="text/css">' +
                        '            :root {' +
                        '              --pseudo-backgroundcolor' + dataSet[i]["id"] + ':'+dataSet[i]["TS_PTable_ST_05"]+';' +
                        '           }' +
                        '     .TS_PTable_Container_Col_' + dataSet[i]["id"] + ' {' +
                        '               position: relative;' +
                        '               min-height: 1px;' +
                        '               float: left;' +
                        '               width: ' + dataSet[i]["TS_PTable_ST_01"] + '%;' +
                        '               margin-bottom: 30px !important;' +
                        '               transition: transform 0.5s ease 0s;' +
                        '               -moz-transition: transform 0.5s ease 0s;' +
                        '               -webkit-transition: transform 0.5s ease 0s;' +
                        '         }' +
                        '         .TS_PTable_Container_Col_' + dataSet[i]["id"] + ':hover {' +
                        '           z-index: 1;' +
                        '         }' +
                        '         @media not screen and (min-width: 820px) {' +
                        '             .TS_PTable_Container {' +
                        '                 padding: 20px 5px;' +
                        '             }' +
                        '             .TS_PTable_Container_Col_' + dataSet[i]["id"] + ' {' +
                        '                 width: 70%;' +
                        '                 margin: 0 15% 40px 15%;' +
                        '                 padding: 0 10px;' +
                        '             }' +
                        '         }' +
                        '         @media not screen and (min-width: 400px) {' +
                        '             .TS_PTable_Container {' +
                        '                 padding: 20px 0;' +
                        '             }' +
                        '             .TS_PTable_Container_Col_' + dataSet[i]["id"] + ' {' +
                        '                 width: 100%;' +
                        '                 margin: 0 0 40px 0;' +
                        '                 padding: 0 5px;' +
                        '             }' +
                        '         }' +
                        '         .TS_PTable_Shadow_' + dataSet[i]["id"] + ' {' +
                        '             position: relative;' +
                        '             z-index: 0;' +
                        '             border-radius: 10px;' +
                        '         }' +
                        '         .TS_PTable_Shadow_' + dataSet[i]["id"] + ' {' +
                        '             box-shadow: 0 0 18px 7px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '             -moz-box-shadow: 0 0 18px 7px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '             -webkit-box-shadow: 0 0 18px 7px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '         }' +
                        '    .Box_Shadow_5_01_' + dataSet[i]["id"] + ' {' +
                        '         box-shadow: 0 10px 6px -6px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '         -webkit-box-shadow: 0 10px 6px -6px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '         -moz-box-shadow: 0 10px 6px -6px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '     }' +
                        '     .Box_Shadow_5_02_' + dataSet[i]["id"] + ':before, .Box_Shadow_5_02_' + dataSet[i]["id"] + ':after {' +
                        '            bottom: 15px;' +
                        '            left: 10px;' +
                        '            width: 50%;' +
                        '            height: 20%;' +
                        '            max-width: 300px;' +
                        '            max-height: 100px;' +
                        '            -webkit-box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '            -moz-box-shadow: 0 15px 10px  var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '            box-shadow: 0 15px 10px  var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '            -webkit-transform: rotate(-3deg);' +
                        '            -moz-transform: rotate(-3deg);' +
                        '            -ms-transform: rotate(-3deg);' +
                        '            -o-transform: rotate(-3deg);' +
                        '            transform: rotate(-3deg);' +
                        '            z-index: -1;' +
                        '            position: absolute;' +
                        '            content: "";' +
                        '     }' +
                        '     .Box_Shadow_5_02_' + dataSet[i]["id"] + ':after {' +
                        '            transform: rotate(3deg);' +
                        '            -moz-transform: rotate(3deg);' +
                        '            -webkit-transform: rotate(3deg);' +
                        '            right: 10px;' +
                        '            left: auto;' +
                        '    }' +
                        '    .Box_Shadow_5_03_' + dataSet[i]["id"] + ':before, .Box_Shadow_5_03_' + dataSet[i]["id"] + ':after {' +
                        '       bottom: 15px;' +
                        '       left: 10px;' +
                        '       width: 50%;' +
                        '       height: 20%;' +
                        '       max-width: 300px;' +
                        '       max-height: 100px;' +
                        '       -webkit-box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '       -moz-box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '       box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '       -webkit-transform: rotate(-3deg);' +
                        '       -moz-transform: rotate(-3deg);' +
                        '       -ms-transform: rotate(-3deg);' +
                        '       -o-transform: rotate(-3deg);' +
                        '       transform: rotate(-3deg);' +
                        '       z-index: -1;' +
                        '       position: absolute;' +
                        '       content: "";' +
                        '    }' +
                        '    .Box_Shadow_5_04_' + dataSet[i]["id"] + ':after {' +
                        '        bottom: 15px;' +
                        '        right: 10px;' +
                        '        width: 50%;' +
                        '        height: 20%;' +
                        '        max-width: 300px;' +
                        '        max-height: 100px;' +
                        '        -webkit-box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '        -moz-box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '        box-shadow: 0 15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '        -webkit-transform: rotate(3deg);' +
                        '        -moz-transform: rotate(3deg);' +
                        '        -ms-transform: rotate(3deg);' +
                        '        -o-transform: rotate(3deg);' +
                        '        transform: rotate(3deg);' +
                        '        z-index: -1;' +
                        '        position: absolute;' +
                        '        content: "";' +
                        '    }' +
                        '     .Box_Shadow_5_05_' + dataSet[i]["id"] + ':before, .Box_Shadow_5_05_' + dataSet[i]["id"] + ':after {' +
                        '         top: 15px;' +
                        '         left: 10px;' +
                        '         width: 50%;' +
                        '         height: 20%;' +
                        '         max-width: 300px;' +
                        '         max-height: 100px;' +
                        '         z-index: -1;' +
                        '         position: absolute;' +
                        '         content: "";' +
                        '         background: var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '         box-shadow: 0 -15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '         -webkit-box-shadow: 0 -15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '         -moz-box-shadow: 0 -15px 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '         transform: rotate(3deg);' +
                        '         -moz-transform: rotate(3deg);' +
                        '         -webkit-transform: rotate(3deg);' +
                        '     }' +
                        '     .Box_Shadow_5_05_' + dataSet[i]["id"] + ':after {' +
                        '           transform: rotate(-3deg);' +
                        '           -moz-transform: rotate(-3deg);' +
                        '           -webkit-transform: rotate(-3deg);' +
                        '           right: 10px;' +
                        '           left: auto;' +
                        '      }' +
                        '       .Box_Shadow_5_06_' + dataSet[i]["id"] + ' {' +
                        '           position: relative;' +
                        '           box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '           -webkit-box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '           -moz-box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '       }' +
                        '       .Box_Shadow_5_06_' + dataSet[i]["id"] + ':before, .Box_Shadow_5_06_' + dataSet[i]["id"] + ':after {' +
                        '           content: "";' +
                        '           position: absolute;' +
                        '           z-index: -1;' +
                        '           box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -webkit-box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -moz-box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           top: 50%;' +
                        '           bottom: 0;' +
                        '           left: 10px;' +
                        '           right: 10px;' +
                        '           border-radius: 100px / 10px;' +
                        '       }' +
                        '       .Box_Shadow_5_07_' + dataSet[i]["id"] + ' {' +
                        '           position: relative;' +
                        '           box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '           -webkit-box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '           -moz-box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '       }' +
                        '       .Box_Shadow_5_07_' + dataSet[i]["id"] + ':before, .Box_Shadow_5_07_' + dataSet[i]["id"] + ':after {' +
                        '           content: "";' +
                        '           position: absolute;' +
                        '           z-index: -1;' +
                        '           box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -webkit-box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -moz-box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           top: 0;' +
                        '           bottom: 0;' +
                        '           left: 10px;' +
                        '           right: 10px;' +
                        '           border-radius: 100px / 10px;' +
                        '       }' +
                        '       .Box_Shadow_5_07_' + dataSet[i]["id"] + ':after {' +
                        '           right: 10px;' +
                        '           left: auto;' +
                        '           transform: skew(8deg) rotate(3deg);' +
                        '           -moz-transform: skew(8deg) rotate(3deg);' +
                        '           -webkit-transform: skew(8deg) rotate(3deg);' +
                        '       }' +
                        '       .Box_Shadow_5_08_' + dataSet[i]["id"] + ' {' +
                        '           position: relative;' +
                        '           box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '           -webkit-box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '           -moz-box-shadow: 0 1px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + '), 0 0 40px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') inset;' +
                        '       }' +
                        '       .Box_Shadow_5_08_' + dataSet[i]["id"] + ':before, .Box_Shadow_5_08_' + dataSet[i]["id"] + ':after {' +
                        '           content: "";' +
                        '           position: absolute;' +
                        '           z-index: -1;' +
                        '           box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -webkit-box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -moz-box-shadow: 0 0 20px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           top: 10px;' +
                        '           bottom: 10px;' +
                        '           left: 0;' +
                        '           right: 0;' +
                        '           border-radius: 100px / 10px;' +
                        '       }' +
                        '       .Box_Shadow_5_08_' + dataSet[i]["id"] + ':after {' +
                        '           right: 10px;' +
                        '           left: auto;' +
                        '           transform: skew(8deg) rotate(3deg);' +
                        '           -moz-transform: skew(8deg) rotate(3deg);' +
                        '           -webkit-transform: skew(8deg) rotate(3deg);' +
                        '       }' +
                        '       .Box_Shadow_5_09_' + dataSet[i]["id"] + ' {' +
                        '           box-shadow: 0 0 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -webkit-box-shadow: 0 0 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '           -moz-box-shadow: 0 0 10px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ');' +
                        '       }' +
                        '       .Box_Shadow_5_10_' + dataSet[i]["id"] + '{' +
                        '           box-shadow: 4px -4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '           -moz-box-shadow: 4px -4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '           -webkit-box-shadow: 4px -4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '       }' +
                        '       .Box_Shadow_5_11_' + dataSet[i]["id"] + '{' +
                        '           box-shadow: 5px 5px 3px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '           -moz-box-shadow: 5px 5px 3px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '           -webkit-box-shadow: 5px 5px 3px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '       }' +
                        '       .Box_Shadow_5_12_' + dataSet[i]["id"] + '{' +
                        '            box-shadow: 2px 2px white, 4px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -moz-box-shadow: 2px 2px white, 4px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -webkit-box-shadow: 2px 2px white, 4px 4px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '       }' +
                        '       .Box_Shadow_5_13_' + dataSet[i]["id"] + '{' +
                        '            box-shadow: 8px 8px 18px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -moz-box-shadow: 8px 8px 18px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -webkit-box-shadow: 8px 8px 18px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '       }' +
                        '       .Box_Shadow_5_14_' + dataSet[i]["id"] + '{' +
                        '            box-shadow: 0 8px 6px -6px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -moz-box-shadow: 0 8px 6px -6px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -webkit-box-shadow: 0 8px 6px -6px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '       }' +
                        '       .Box_Shadow_5_15_' + dataSet[i]["id"] + '{' +
                        '            box-shadow: 0 0 18px 7px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -moz-box-shadow: 0 0 18px 7px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '            -webkit-box-shadow: 0 0 18px 7px var(--pseudo-backgroundcolor' + dataSet[i]["id"] + ') ;' +
                        '       }' +
                        '         .TS_PTable__' + dataSet[i]["id"] + ' {' +
                        '             text-align: center;' +
                        '             position: relative;' +
                        '             background-color: ' + dataSet[i]["TS_PTable_ST_03"] + ';' +
                        '             padding-bottom: 40px !important;' +
                        '            border-radius: 10px;' +
                        '            transition: all 0.5s ease 0s;' +
                        '            -moz-transition: all 0.5s ease 0s;' +
                        '            -webkit-transition: all 0.5s ease 0s;' +
                        '        }' +
                        '        .TS_PTable_Div1_' + dataSet[i]["id"] + ' {' +
                        '             background-color: ' + dataSet[i]["TS_PTable_ST_18"] + ';' +
                        '             padding: 40px 0 !important;' +
                        '            border-radius: 10px 10px 50% 50%;' +
                        '            transition: all 0.5s ease 0s;' +
                        '            -moz-transition: all 0.5s ease 0s;' +
                        '            -webkit-transition: all 0.5s ease 0s;' +
                        '            position: relative;' +
                        '        }' +
                        '        .TS_PTable__' + dataSet[i]["id"] + ':hover .TS_PTable_Div1_' + dataSet[i]["id"] + ' {' +
                        '             background-color: ' + dataSet[i]["TS_PTable_ST_19"] + ' !important;' +
                        '         }' +
                        '        .TS_PTable_Div1_' + dataSet[i]["id"] + ' i {' +
                        '             font-size: ' + dataSet[i]["TS_PTable_ST_09"] + 'px;' +
                        '             color: ' + dataSet[i]["TS_PTable_ST_10"] + ';' +
                        '             margin-bottom: 10px;' +
                        '            transition: all 0.5s ease 0s;' +
                        '            -moz-transition: all 0.5s ease 0s;' +
                        '            -webkit-transition: all 0.5s ease 0s;' +
                        '        }' +
                        '        .TS_PTable__' + dataSet[i]["id"] + ':hover .TS_PTable_Div1_' + dataSet[i]["id"] + ' i {' +
                        '             color: ' + dataSet[i]["TS_PTable_ST_11"] + ';' +
                        '         }' +
                        '        .TS_PTable_Title_' + dataSet[i]["id"] + ' {' +
                        '             font-size: ' + dataSet[i]["TS_PTable_ST_06"] + 'px;' +
                        '             font-family: ' + dataSet[i]["TS_PTable_ST_07"] + ';' +
                        '             color: ' + dataSet[i]["TS_PTable_ST_08"] + ';' +
                        '             margin: 5px 0 0 0 !important;' +
                        '            padding: 15px !important;' +
                        '        }' +
                        '        .Feut_Hover,.TS_PTable_Title_' + dataSet[i]["id"] + ':focus,.TS_PTable_Title_' + dataSet[i]["id"] + ':hover,.TS_PTable_PCur_' + dataSet[i]["id"] + ':hover ,.TS_PTable_PCur_' + dataSet[i]["id"] + ':focus,.TS_PTable_PVal_' + dataSet[i]["id"] + ':hover,.TS_PTable_PVal_' + dataSet[i]["id"] + ':focus,.TS_PTable_PPlan_' + dataSet[i]["id"] + ':hover,.TS_PTable_PPlan_' + dataSet[i]["id"] + ':focus{' +
                        // '           box-shadow: 0 0 0 1pt #676666;' +
                        '           border-radius: 5px;' +
                        '           cursor: auto;' +
                        '        }' +
                        '        .TS_PTable_Amount_' + dataSet[i]["id"] + ' {' +
                        '             font-family: ' + dataSet[i]["TS_PTable_ST_12"] + ';' +
                        '             color: ' + dataSet[i]["TS_PTable_ST_13"] + ';' +
                        '             font-size: ' + dataSet[i]["TS_PTable_ST_16"] + 'px;' +
                        '             position: relative;' +
                        '            transition: all 0.5s ease 0s;' +
                        '            -moz-transition: all 0.5s ease 0s;' +
                        '            -webkit-transition: all 0.5s ease 0s;' +
                        '        }' +
                        '        .TS_PTable_PCur_' + dataSet[i]["id"] + ' {' +
                        '             font-size: ' + dataSet[i]["TS_PTable_ST_15"] + 'px;' +
                        '         }' +
                        '        .TS_PTable_PPlan_' + dataSet[i]["id"] + ' {' +
                        '             font-size: ' + dataSet[i]["TS_PTable_ST_17"] + 'px;' +
                        '             display: block;' +
                        '        }' +
                        '        .TS_PTable__' + dataSet[i]["id"] + ':hover .TS_PTable_Amount_' + dataSet[i]["id"] + ' {' +
                        '             color: ' + dataSet[i]["TS_PTable_ST_14"] + ';' +
                        '         }' +
                        '        .TS_PTable_Features_' + dataSet[i]["id"] + ' {' +
                        '             padding: 0 !important;' +
                        '            margin: 0 0 30px 0 !important;' +
                        '            list-style: none;' +
                        '        }' +
                        '        .TS_PTable_Features_' + dataSet[i]["id"] + ' li {' +
                        '             color: ' + dataSet[i]["TS_PTable_ST_20"] + ';' +
                        '             font-size: ' + dataSet[i]["TS_PTable_ST_21"] + 'px;' +
                        '             font-family: ' + dataSet[i]["TS_PTable_ST_22"] + ';' +
                        '             line-height: 1;' +
                        '            padding: 10px;' +
                        '            display: flex;' +
                        '            align-items: center;' +
                        '            justify-content: center;' +
                        '        }' +
                        '        .TS_PTable_Features_' + dataSet[i]["id"] + ' li:before {' +
                        '             content: "" !important;' +
                        '            display: none !important;' +
                        '        }' +
                        '        .TS_PTable_FIcon_' + dataSet[i]["id"] + ' {' +
                        '             color: ' + dataSet[i]["TS_PTable_ST_23"] + ';' +
                        '             font-size: ' + dataSet[i]["TS_PTable_ST_25"] + 'px;' +
                        '             margin: 0 10px !important;' +
                        '        }' +
                        '        .TS_PTable_FIcon_' + dataSet[i]["id"] + '.TS_PTable_FCheck {' +
                        '             color: ' + dataSet[i]["TS_PTable_ST_24"] + ';' +
                        '         }' +
                        '        .TS_PTable_Button_' + dataSet[i]["id"] + ' {' +
                        '             display: inline-block;' +
                        '            padding: 10px 35px !important;' +
                        '            font-size: ' + dataSet[i]["TS_PTable_ST_27"] + 'px;' +
                        '             font-family: ' + dataSet[i]["TS_PTable_ST_28"] + ';' +
                        '             background: ' + dataSet[i]["TS_PTable_ST_31"] + ';' +
                        '             color: ' + dataSet[i]["TS_PTable_ST_32"] + ';' +
                        '             border-radius: 20px;' +
                        '            transition: all 0.3s ease 0s;' +
                        '            -moz-transition: all 0.3s ease 0s;' +
                        '            -webkit-transition: all 0.3s ease 0s;' +
                        '            text-decoration: none;' +
                        '            outline: none;' +
                        '            box-shadow: none;' +
                        '            -webkit-box-shadow: none;' +
                        '            -moz-box-shadow: none;' +
                        '            cursor: pointer !important;' +
                        '        }' +
                        '        .TS_PTable_Button_' + dataSet[i]["id"] + ':hover {' +
                        '             box-shadow: 0 0 10px ' + dataSet[i]["TS_PTable_ST_31"] + ';' +
                        '             -moz-box-shadow: 0 0 10px ' + dataSet[i]["TS_PTable_ST_31"] + ';' +
                        '             -webkit-box-shadow: 0 0 10px ' + dataSet[i]["TS_PTable_ST_31"] + ';' +
                        '             background: ' + dataSet[i]["TS_PTable_ST_31"] + ';' +
                        '             color: ' + dataSet[i]["TS_PTable_ST_32"] + ';' +
                        '         }' +
                        '        .TS_PTable_Button_' + dataSet[i]["id"] + ':hover {' +
                        '             text-decoration: none;' +
                        '            outline: none;' +
                        '        }' +
                        '        .TS_PTable_Button_' + dataSet[i]["id"] + ':focus {' +
                        '             text-decoration: none;' +
                        '            outline: none;' +
                        '            box-shadow: none;' +
                        '            -webkit-box-shadow: none;' +
                        '            -moz-box-shadow: none;' +
                        '        }' +
                        '        .TS_PTable_BIconA_' + dataSet[i]["id"] + ', .TS_PTable_BIconB_' + dataSet[i]["id"] + ' {' +
                        '             font-size: ' + dataSet[i]["TS_PTable_ST_29"] + 'px;' +
                        '         }' +
                        '        .TS_PTable_BIconB_' + dataSet[i]["id"] + ' {' +
                        '             margin: 0 10px 0 0 !important;' +
                        '        }' +
                        '        .TS_PTable_BIconA_' + dataSet[i]["id"] + ' {' +
                        '             margin: 0 10px !important;' +
                        '        }' +
                        '    </style>'
                    )
                }
            }
            for (var i = 0; i < data.length; i++) {
                jQuery('#Total_Soft_PTable_Col_Sel_Count').val(data.length);
                if (Create_Action=='Total_Soft_PTable_Select_New_Cols') {
                    data[i]["id"]=datamax_id;
                    data[i]["index"]=datamax;
                }
                var last_id = parseInt(parseInt(jQuery("#Total_Soft_PTable_New_Col_Last_Id").val()) + parseInt(data[i]['id']));
                var Total_Soft_PTable_M_03 = dataMan['Total_Soft_PTable_M_03']
                var number = data[i]['id'];


                jQuery('#TS_PTable_TSetting_' + number).val(data[i]['TS_PTable_TSetting']);
                jQuery('#TS_PTable_PCur_' + number).val(data[i]['TS_PTable_PCur']);
                jQuery('#TS_PTable_PVal_' + number).val(data[i]['TS_PTable_PVal']);
                jQuery('#TS_PTable_PPlan_' + number).val(data[i]['TS_PTable_PPlan']);
                jQuery('#TS_PTable_BLink_' + number).val(data[i]['TS_PTable_BLink']);
                var TS_PTable_FText = data[i]['TS_PTable_FText'].split('TSPTFT');
                var TS_PTable_FIcon = data[i]['TS_PTable_FIcon'].split('TSPTFI');
                var TS_PTable_FCheck = data[i]['TS_PTable_C_01'].split('TSPTFC');

                if (data[i]["TS_PTable_TType"] == "type1") {
                    jQuery('.TS_PTable_Container').append('' +
                        '<div class=" TS_PTable_Container_Col_' + number + ' Total_Soft_PTable_AMMain_Div2_Cols1" id="TS_PTable_Col_' + number + '">' +
                        '    <input type="hidden" class="Total_Soft_PTable_Select" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_ID" id="TS_PTable_ST1_' + number + '_ID" value="' + number + '">' +
                        '    <input type="hidden" class="Total_Soft_PTable_Select" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_00" id="TS_PTable_ST1_' + number + '_00" value="Theme_' + number + '">' +
                        '    <input type="hidden" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_index" id="TS_PTable_ST1_' + number + '_index" value="' + dataSet[i]['index'] + '">' +
                        '    <input type="hidden" name="Total_Soft_PTable_Cols_Id" class="Total_Soft_PTable_Cols_Id" value="' + number + '">' +
                        '    <input type="hidden" name="Total_Soft_PTable_Set_Title" class="Total_Soft_PTable_Set_Title" value="Theme_' + number + '">' +
                        '       <div class="TS_PTable_Parent">' +
                        '           <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Actions">' +
                        '              <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action1">' +
                        '                 <i class="totalsoft totalsoft-arrows" title="Reorder" onmouseup="TotalSoftPTable_ColumnDivdel()" onmousedown="TotalSoftPTable_ColumnDivSort(' + number + ',' + data[i]['index'] + ')"></i>' +
                        '              </div>' +
                        '              <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action2">' +
                        '                  <i class="totalsoft totalsoft-file-text" title="Make Duplicate" onClick="TotalSoftPTable_Dup_Col(' + number + ', ' + parseInt(parseInt(i) + 1) + ',this)"></i>' +
                        '              </div>' +
                        '              <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action3">' +
                        '                  <i class="totalsoft totalsoft-trash" title="Delete Column" onClick="TotalSoftPTable_Del_Col(' + number + ')"></i>' +
                        '              </div>' +
                        '              <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action4">' +
                        '                  <i class="totalsoft totalsoft-pencil" title="Edit Column" onClick="TotalSoftPTable_Col_Dragdown(); TotalSoftPTable_Edit_' + Theme + '(this,' + number + ')"></i>' +
                        '              </div>' +
                        '           </div>' +
                        '           <div class="TS_PTable_Shadow_' + number + '">' +
                        '               <div class="TS_PTable__' + number + ' TS_PTable_Parent">' +
                        '                   <div class="relative title_Position_' + number + '">' +
                        '                       <span class=" TS_PTable_Title_IconTB_' + number + ' TS_PTable_Title_Icon_" >' +
                        '                            <i onClick="TS_Get_Icon_Title(this, ' + number + ')" class="total_Soft_Icon_Change  TS_PTable_Title_Icon_' + number + ' totalsoft totalsoft-' + ((data[0]['TS_PTable_TIcon']=='none' )?'plus-square-o':data[0]['TS_PTable_TIcon']) + '"></i>' +
                        '                           <input type="hidden" class="Total_Soft_PTable_Select" id="TS_PTable_TIcon_' + number + '" name="TS_PTable_TIcon_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_TIcon'] + '">' +
                        '                        </span>' +
                        '                       <div class="title_h3_Container">' +
                        '                           <h3 class="h3_hover TS_PTable_Title_' + number + ' TS_PTable_Title_" onInput="TS_Change_H3_Val(this)" contentEditable="true" onClick="jQuery(this).focus();">' + data[i]['TS_PTable_TText'] + '</h3>' +
                        '                           <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_TText_Hidden TS_PTable_TText" id="TS_PTable_New_TText_' + number + '" name="TS_PTable_TText_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_TText'] + '">' +
                        '                       </div>' +
                        '                   </div>' +
                        '                   <div class="span_hover TS_PTable_PValue_' + number + '">' +
                        '                       <span class="TS_PTable_Cur_' + number + '" contentEditable="true" oninput="TS_Change_Cur_Val(this)" onClick="jQuery(this).focus();">' + data[i]['TS_PTable_PCur'] + '</span>' +
                        '                       <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_PCur_Hidden" id="TS_PTable_New_PCur_' + number + '" name="TS_PTable_PCur_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_PCur'] + '">' +
                        '                       <span class="TS_PTable_Val_' + number + '" contentEditable="true" oninput="TS_Change_Val_Val(this)" onClick="jQuery(this).focus();">' + data[i]['TS_PTable_PVal'] + '</span>' +
                        '                       <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_PVal_Hidden" id="TS_PTable_New_PVal_' + number + '" name="TS_PTable_PVal_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_PVal'] + '">' +
                        '                       <span class=" TS_PTable_PPlan_' + number + '" contentEditable="true" oninput="TS_Change_Plan_Val(this)" onClick="jQuery(this).focus();">' + data[i]['TS_PTable_PPlan'] + '</span>' +
                        '                       <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_PPlan_Hidden" id="TS_PTable_New_PPlan_' + number + '" name="TS_PTable_PPlan_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_PPlan'] + '">' +
                        '                   </div>' +
                        '                    <div class="relative feuture_cont_' + number + '">' +
                        '                       <ul class="TS_PTable_Features_' + number + ' TS_PTable_Features ">');
                    var TS_PTable_FChek = '';

                    var Total_Soft_PTable_Select_Icon = jQuery('#Total_Soft_PTable_Select_Icon').html();
                    for (j = 0; j < data[i]['TS_PTable_FCount']; j++) {
 
                        if (TS_PTable_FCheck[j] != '') {
                            
                            TS_PTable_FChek = 'TS_PTable_FCheck';
                        } else {
                            TS_PTable_FChek = '';
                        }
                        jQuery(".TS_PTable_Features_" + number).append('' +
                            '                       <li onMouseOver="TS_PTable_Features_Li(this)" onMouseOut="TS_PTable_Features_Li_Out(this)" class="TS_Li_' + number + '_' + parseInt(parseInt(j) + 1) + '">' +
                            '                                   <div class="Hiddem_Li_Container">' +
                            '                                   <span class="hiddenChangeText"><i class="totalsoft totalsoft-times TS_PTable_FDI TS_PTable_FDI_' + number + '" title="Delete Feature" onClick="TotalSoftPTable_Del_FT(' + number + ', ' + parseInt(parseInt(j) + 1) + ')"></i>     </span>' +
                            '                                   <span class="TS_PTable_FChecked TS_PTable_FCheckedHide TS_PTable_FChecked_' + number + '">' +
                            '                                           <input onClick="TS_PTable_TS_PTable_FChecked_label(this,' + parseInt(parseInt(i) + 1) + ')" type="checkbox" class="' + TS_PTable_FChek + '" id="TS_PTable_FChecked_' + number + '_' + parseInt(parseInt(j) + 1) + '" name="TS_PTable_FChecked_' + parseInt(parseInt(i) + 1) + '_' + parseInt(parseInt(j) + 1) + '" value="' + parseInt(parseInt(j) + 1) + '">' +
                            '                                           <label class="totalsoft totalsoft-question-circle-o"  for="TS_PTable_FChecked_' + number + '_' + parseInt(parseInt(j) + 1) + '"></label>' +
                            '                                   </span>' +
                            '                               </div>' +
                            '                               <div class="feut_container_' + number + ' feut_container">' +
                            '                                   <div class="feut_text_cont">' +
                            '                                       <span onmouseover="TS_Change_Feut_Val_Hover(this)" onmouseout="TS_Change_Feut_Val_Over(this)" class="TS_PTable_FText_' + number + '_' + parseInt(parseInt(j) + 1) + '" oninput="TS_Change_Feut_Val(this)" contentEditable="true" onClick="jQuery(this).focus();">' + TS_PTable_FText[j] + '</span>' +
                            '                                       <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_FText_' + number + ' TS_PTable_Feut_Hidden"  name="TS_PTable_FText_' + parseInt(parseInt(i) + 1) + '_' + parseInt(parseInt(j) + 1) + '" value="' + TS_PTable_FText[j] + '">' +
                            '                                   </div>' +
                            '                                   <div class="feut_icon_cont">' +
                            '                                       <i onClick="TS_Get_Icon_Feuture(this, ' + number + ', ' + parseInt(parseInt(j) + 1) + ')" class="totalsoft  TS_PTable_FIcon_' + number + ' ' + TS_PTable_FChek + ' totalsoft-' + (TS_PTable_FIcon[j]!='none'?TS_PTable_FIcon[j]:'plus-square-o ') + '"></i>' +
                            '                                       <input type="hidden"  class="Total_Soft_PTable_Select  TS_PTable_FIcon_' + number + '_' + parseInt(parseInt(j) + 1) + '" name="TS_PTable_FIcon_' + parseInt(parseInt(i) + 1) + '_' + parseInt(parseInt(j) + 1) + '" value="' + TS_PTable_FIcon[j] + '">' +
                            '                                   </div>' +
                            '                               </div>' +
                            '                         </li>')
                    }
                    jQuery(".feuture_cont_" + number).append('' +
                        '                      </ul>' +
                        '                      <input type="hidden"  class="TS_PTable_FCount" id="TS_PTable_FCount_' + number + '" name="TS_PTable_FCount_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_FCount'] + '">' +
                        '                  </div>' +
                        '                  <table id="Total_Soft_PTable_Features_Col_' + number + '"></table>' +
                        '                  <div class="Total_Soft_PTable_Features_New" onClick="Total_Soft_PTable_Features_New(' + number + ');Total_Soft_PTable_Features_New_Col(' + number + ',' + parseInt(parseInt(i) + 1) + ',1)">' +
                        '                      <span class="Total_Soft_PTable_Features_New1">' +
                        '                         <i class="Total_Soft_PTable_Features_New_Icon totalsoft totalsoft-plus-circle" style="margin-right: 5px;"></i>Add New </span>' +
                        '                  </div>' +
                        '                  <div class="relative flex_center">' +
                        '                      <div onclick="TS_Get_Icon_Button(this, ' + number + ')" class="TS_PTable_Button_' + number + '">' +
                        '                           <span class="Button_cont Button_cont_' + number + '">' +
                        '                               <span class="Button_text_cont">' +
                        '                                   <span onmouseover="TS_Change_Feut_Val_Hover(this)" onmouseout="TS_Change_Feut_Val_Over(this)" class="TS_PTable_BText_' + number + '" contentEditable="true">' + data[i]['TS_PTable_BText'] + '</span>' +
                       
                        '                                   <input type="hidden" class="Total_Soft_PTable_Select" id="TS_PTable_BText_' + number + '" name="TS_PTable_BText_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_BText'] + '">' +
                        '                               </span>' +
                        '                               <span class="Button_icon_cont">' +
                        '                                   <i  class="totalsoft   TS_PTable_BIconA_' + number + ' totalsoft-' +( data[i]['TS_PTable_BIcon']!='none'? data[i]['TS_PTable_BIcon']:'plus-square-o ') + '" ></i>' +
                        '                                   <select onchange= "TS_ChangeBut_Icon_Select(this,' + number + ')" class="Total_Soft_PTable_Select  TS_PTable_BIcon TS_PTable_BIcon_' + number + '" onchange="ChangeValueForHiddenBIcon(this)"  name="TS_PTable_BIcon_' + number + '" style="font-family: FontAwesome, Arial;">'+Total_Soft_PTable_Select_Icon+'</select>'+
          
                        '                                   <input type="hidden" class="Total_Soft_PTable_Select" id="TS_PTable_BIcon_' + number + '" name="TS_PTable_BIcon_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_BIcon'] + '">' +
                        '                               </span>' +
                        '                           </span>' +
                        '                           <div class="TS_PTable_Button_Link_' + number + ' TS_PTable_Button_Link">' +
                        '                              <i class="totalsoft  totalsoft-link" ></i>' +
                        '                              <input type="text" class="Total_Soft_PTable_Select TS_PTable_BLink " id="TS_PTable_BLink_' + number + '" name="TS_PTable_BLink_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_BLink'] + '">' +
                        '                           </div>' +
                        '                      </div>' +
                        '                  </div>' +
                        '              </div>' +
                        '          </div>' +
                        '    </div>' +
                        '</div>'
                    );
                    jQuery(".TS_PTable_Container").css("gap", Total_Soft_PTable_M_03 + "px");
                      jQuery('.TS_PTable_BIcon_' + number ).val( jQuery('#TS_PTable_BIcon_' + number  ).val());
                    for (var k = 1; k <= parseInt(data[i]['TS_PTable_FCount']); k++) {
                        jQuery("#TS_PTable_FIcon_" + number + "_" + k).val(TS_PTable_FIcon[k - 1]);
                        if (jQuery('#TS_PTable_FChecked_' + number + '_' + k).val() == TS_PTable_FCheck[k - 1]) {
                            jQuery('#TS_PTable_FChecked_' + number + '_' + k).attr('checked', 'checked');
                        }
                    }

                    jQuery("#TS_PTable_Col_" + number).append('' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_01" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_01" value="' + dataSet[i]['TS_PTable_ST_01'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_02" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_02" value="' + dataSet[i]['TS_PTable_ST_02'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_03" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_03" value="' + dataSet[i]['TS_PTable_ST_03'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_04" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_04" value="' + dataSet[i]['TS_PTable_ST_04'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_05" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_05" value="' + dataSet[i]['TS_PTable_ST_05'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_06" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_06" value="' + dataSet[i]['TS_PTable_ST_06'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_07" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_07" value="' + dataSet[i]['TS_PTable_ST_07'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_08" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_08" value="' + dataSet[i]['TS_PTable_ST_08'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_09" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_09" value="' + dataSet[i]['TS_PTable_ST_09'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_10" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_10" value="' + dataSet[i]['TS_PTable_ST_10'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_11" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_11" value="' + dataSet[i]['TS_PTable_ST_11'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_12" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_12" value="' + dataSet[i]['TS_PTable_ST_12'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_13" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_13" value="' + dataSet[i]['TS_PTable_ST_13'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_14" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_14" value="' + dataSet[i]['TS_PTable_ST_14'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_15" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_15" value="' + dataSet[i]['TS_PTable_ST_15'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_16" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_16" value="' + dataSet[i]['TS_PTable_ST_16'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_17" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_17" value="' + dataSet[i]['TS_PTable_ST_17'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_18" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_18" value="' + dataSet[i]['TS_PTable_ST_18'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_19" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_19" value="' + dataSet[i]['TS_PTable_ST_19'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_20" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_20" value="' + dataSet[i]['TS_PTable_ST_20'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_21" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_21" value="' + dataSet[i]['TS_PTable_ST_21'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_21_1" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_21_1" value="' + dataSet[i]['TS_PTable_ST_21_1'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_22" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_22" value="' + dataSet[i]['TS_PTable_ST_22'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_23" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_23" value="' + dataSet[i]['TS_PTable_ST_23'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_24" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_24" value="' + dataSet[i]['TS_PTable_ST_24'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_25" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_25" value="' + dataSet[i]['TS_PTable_ST_25'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_26" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_26" value="' + dataSet[i]['TS_PTable_ST_26'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_27" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_27" value="' + dataSet[i]['TS_PTable_ST_27'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_28" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_28" value="' + dataSet[i]['TS_PTable_ST_28'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_29" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_29" value="' + dataSet[i]['TS_PTable_ST_29'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_30" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_30" value="' + dataSet[i]['TS_PTable_ST_30'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_31" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_31" value="' + dataSet[i]['TS_PTable_ST_31'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_32" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_32" value="' + dataSet[i]['TS_PTable_ST_32'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_33" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_33" value="' + dataSet[i]['TS_PTable_ST_33'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_34" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_34" value="' + dataSet[i]['TS_PTable_ST_34'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_35" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_35" value="' + dataSet[i]['TS_PTable_ST_35'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_36" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_36" value="' + dataSet[i]['TS_PTable_ST_36'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_37" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_37" value="' + dataSet[i]['TS_PTable_ST_37'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_38" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_38" value="' + dataSet[i]['TS_PTable_ST_38'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_39" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_39" value="' + dataSet[i]['TS_PTable_ST_39'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST1_' + number + '_40" name="TS_PTable_ST1_' + parseInt(parseInt(i) + 1) + '_40" value="' + dataSet[i]['TS_PTable_ST_40'] + '">'
                    )
                    TS_Ch_Inp_1_Shadow_Type( '.TS_PTable_ST1_' + number + '_06', number); 
                    if (dataSet[i]['TS_PTable_ST_02'] == 'on') {
                    

                        jQuery(".TS_PTable_Container_Col_" + dataSet[i]['id']).css({
                            "-webkit-transform": "scale(1, 1.05)",
                            "-moz-transform": "scale(1, 1.05)",
                            "transform": "scale(1, 1.05)",
                        });

                    }else{
                        jQuery(".TS_PTable_Container_Col_" + dataSet[i]['id']).css({
                            "-webkit-transform": "scale(1, 1)",
                            "-moz-transform": "scale(1, 1)",
                            "transform": "scale(1, 1)",
                        })
                    }
                    if (dataSet[i]['TS_PTable_ST_13'] == "after") {
                        jQuery(".title_Position_" + dataSet[i]['id'] + "").attr("style", "display:flex; flex-direction:row-reverse; justify-content:center;");
                    } else if (dataSet[i]['TS_PTable_ST_13'] == "before") {
                        jQuery(".title_Position_" + dataSet[i]['id'] + "").attr("style", "display:flex; justify-content:center;");
                    } else if (dataSet[i]['TS_PTable_ST_13'] == "above") {
                        jQuery(".title_Position_" + dataSet[i]['id'] + "").attr("style", "display:flex; flex-direction:column;");
                    } else if (dataSet[i]['TS_PTable_ST_13'] == "under") {
                        jQuery(".title_Position_" + dataSet[i]['id'] + "").attr("style", "display:flex; flex-direction:column-reverse;");
                    }

                    if (dataSet[i]['TS_PTable_ST_27'] == "after") {
                        jQuery(".feut_container_" + dataSet[i]['id'] + "").attr("style", "flex-direction:row;");
                    } else if (dataSet[i]['TS_PTable_ST_27'] == "before") {
                        jQuery(".feut_container_" + dataSet[i]['id'] + "").attr("style", "flex-direction:row-reverse;");
                    }

                    if (dataSet[i]['TS_PTable_ST_34'] == "after") {
                        jQuery(".Button_cont_" + dataSet[i]['id'] + "").attr("style", "flex-direction:row;");
                    } else if (dataSet[i]['TS_PTable_ST_34'] == "before") {
                        jQuery(".Button_cont_" + dataSet[i]['id'] + "").attr("style", "flex-direction:row-reverse;");
                    }

                } else if (data[i]["TS_PTable_TType"] == "type2") {
                    jQuery('.TS_PTable_Container').append('' +
                        '<div class=" TS_PTable_Container_Col_' + number + ' Total_Soft_PTable_AMMain_Div2_Cols1" id="TS_PTable_Col_' + number + '">' +
                        '    <input type="text" style="display:none;" class="Total_Soft_PTable_Select" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_ID" id="TS_PTable_ST2_' + number + '_ID" value="' + number + '">' +
                        '    <input type="text" style="display:none;" class="Total_Soft_PTable_Select" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_00" id="TS_PTable_ST2_' + number + '_00" value="Theme_' + number + '">' +
                        '    <input type="text" style="display:none" name="Total_Soft_PTable_Cols_Id" class="Total_Soft_PTable_Cols_Id" value="' + number + '">' +
                        '    <input type="hidden" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_index" id="TS_PTable_ST2_' + number + '_index" value="' + dataSet[i]['index'] + '">' +

                        '    <input type="text" style="display:none" name="Total_Soft_PTable_Set_Title" class="Total_Soft_PTable_Set_Title" value="Theme_' + number + '">' +
                        '       <div class="TS_PTable_Parent">' +
                        '           <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Actions">' +
                        '              <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action1">' +
                        '                 <i class="totalsoft totalsoft-arrows" title="Reorder" onmouseup="TotalSoftPTable_ColumnDivdel()" onmousedown="TotalSoftPTable_ColumnDivSort(' + number + ',' + data[i]['index'] + ')"></i>' +
                        '              </div>' +
                        '              <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action2">' +
                        '                  <i class="totalsoft totalsoft-file-text" title="Make Duplicate" onClick="TotalSoftPTable_Dup_Col(' + number + ', ' + parseInt(parseInt(i) + 1) + ',this)"></i>' +
                        '              </div>' +
                        '              <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action3">' +
                        '                  <i class="totalsoft totalsoft-trash" title="Delete Column" onClick="TotalSoftPTable_Del_Col(' + number + ')"></i>' +
                        '              </div>' +
                        '              <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action4">' +
                        '                  <i class="totalsoft totalsoft-pencil" title="Edit Column" onClick="TotalSoftPTable_Col_Dragdown(); TotalSoftPTable_Edit_' + Theme + '(this,' + number + ')"></i>' +
                        '              </div>' +
                        '           </div>' +
                        '    <div class="TS_PTable_Shadow_' + number + '">' +
                        '        <div class="TS_PTable__' + number + ' TS_PTable_Parent">' +
                        '            <div class="TS_PTable_Div1_' + number + '">' +
                        '                <span class="TS_PTable_Title_Icon_' + number + '">' +
                        '                    <i  onClick="TS_Get_Icon_Title(this, ' + number + ')" class="totalsoft totalsoft-' + ((data[i]['TS_PTable_TIcon']=='none' )?'plus-square-o':data[i]['TS_PTable_TIcon']) + '"></i>' +
                        '                   <input type="hidden" class="Total_Soft_PTable_Select" id="TS_PTable_TIcon_' + number + '" name="TS_PTable_TIcon_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_TIcon'] + '">' +
                        '                </span>' +
                        '                <div class="title_h3_Container">' +
                        '                   <h3 class=" h3_hover TS_PTable_Title_' + number + '" onInput="TS_Change_H3_Val(this)" contentEditable="true" onClick="jQuery(this).focus();">' + data[i]['TS_PTable_TText'] + '</h3>' +
                        '                   <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_TText_Hidden TS_PTable_TText" id="TS_PTable_New_TText_' + number + '" name="TS_PTable_TText_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_TText'] + '">' +
                        '                </div>' +
                        '                <div class="span_hover TS_PTable_PValue_' + number + '">' +
                        '                    <span class="TS_PTable_Amount_' + number + '">' +
                        '                        <sup class="TS_PTable_PCur_' + number + '" contentEditable="true" oninput="TS_Change_Cur_Val(this)" onClick="jQuery(this).focus();">' + data[i]['TS_PTable_PCur'] + '</sup>' +
                        '                       <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_PCur_Hidden" id="TS_PTable_New_PCur_' + number + '" name="TS_PTable_PCur_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_PCur'] + '">' +
                        '                        <span class="TS_PTable_PPrice_' + number + ' TS_PTable_PVal_' + number + '" contentEditable="true" oninput="TS_Change_Val_Val(this)" onClick="jQuery(this).focus();">' + data[i]['TS_PTable_PVal'] + '</span>' +
                        '                       <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_PVal_Hidden" id="TS_PTable_New_PVal_' + number + '" name="TS_PTable_PVal_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_PVal'] + '">' +
                        '                        <sub class="TS_PTable_PPlan_' + number + '" contentEditable="true" oninput="TS_Change_Plan_Val(this)" onClick="jQuery(this).focus();">' + data[i]['TS_PTable_PPlan'] + '</sub>' +
                        '                       <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_PPlan_Hidden" id="TS_PTable_New_PPlan_' + number + '" name="TS_PTable_PPlan_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_PPlan'] + '">' +
                        '                    </span>' +
                        '                </div>' +

                        '           <div class="relative feuture_cont_' + number + '">' +
                        '               <ul class="TS_PTable_Features_' + number + ' TS_PTable_Features">');
                    var TS_PTable_FChek = '';
                    var Total_Soft_PTable_Select_Icon = jQuery('#Total_Soft_PTable_Select_Icon').html();
                    for (j = 0; j < data[i]['TS_PTable_FCount']; j++) {
                        if (TS_PTable_FCheck[j] != '') {
                            TS_PTable_FChek = 'TS_PTable_FCheck';
                        } else {
                            TS_PTable_FChek = '';
                        }
                        jQuery(".TS_PTable_Features_" + number).append('' +
                            '                       <li onMouseOver="TS_PTable_Features_Li(this)" onMouseOut="TS_PTable_Features_Li_Out(this)" class="TS_Li_' + number + '_' + parseInt(parseInt(j) + 1) + '">' +
                            '                                   <div class="Hiddem_Li_Container">' +
                            '                                   <span class="hiddenChangeText"><i class="totalsoft totalsoft-times TS_PTable_FDI TS_PTable_FDI_' + number + '" title="Delete Feature" onClick="TotalSoftPTable_Del_FT(' + number + ', ' + parseInt(parseInt(j) + 1) + ')"></i>     </span>' +
                            '                                   <span class="TS_PTable_FChecked TS_PTable_FCheckedHide TS_PTable_FChecked_' + number + '">' +
                            '                                           <input type="checkbox" onClick="TS_PTable_TS_PTable_FChecked_label(this,' + parseInt(parseInt(i) + 1) + ')"  class="' + TS_PTable_FChek + '" id="TS_PTable_FChecked_' + number + '_' + parseInt(parseInt(j) + 1) + '" name="TS_PTable_FChecked_' + parseInt(parseInt(i) + 1) + '_' + parseInt(parseInt(j) + 1) + '" value="' + parseInt(parseInt(j) + 1) + '">' +
                            '                                           <label class="totalsoft totalsoft-question-circle-o" for="TS_PTable_FChecked_' + number + '_' + parseInt(parseInt(j) + 1) + '"></label>' +
                            '                                   </span>' +
                            '                               </div>' +
                            '                               <div class="feut_container_' + number + ' feut_container">' +
                            '                                   <div class="feut_text_cont">' +
                            '                                       <span onmouseover="TS_Change_Feut_Val_Hover(this)" onmouseout="TS_Change_Feut_Val_Over(this)" class="TS_PTable_FText_' + number + '_' + parseInt(parseInt(j) + 1) + '" oninput="TS_Change_Feut_Val(this)" contentEditable="true" onClick="jQuery(this).focus();">' + TS_PTable_FText[j] + '</span>' +
                            '                                       <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_FText_' + number + ' TS_PTable_Feut_Hidden"  name="TS_PTable_FText_' + parseInt(parseInt(i) + 1) + '_' + parseInt(parseInt(j) + 1) + '" value="' + TS_PTable_FText[j] + '">' +
                            '                                   </div>' +
                            '                                   <div class="feut_icon_cont">' +
                            '                                       <i onClick="TS_Get_Icon_Feuture(this, ' + number + ', ' + parseInt(parseInt(j) + 1) + ')" class="totalsoft  TS_PTable_FIcon_' + number + ' ' + TS_PTable_FChek + ' totalsoft-' + (TS_PTable_FIcon[j]!='none'?TS_PTable_FIcon[j]:'plus-square-o ') + '"></i>' +
                            '                                       <input type="hidden"  class="Total_Soft_PTable_Select  TS_PTable_FIcon_' + number + '_' + parseInt(parseInt(j) + 1) + '" name="TS_PTable_FIcon_' + parseInt(parseInt(i) + 1) + '_' + parseInt(parseInt(j) + 1) + '" value="' + TS_PTable_FIcon[j] + '">' +
                            '                                   </div>' +
                            '                               </div>' +
                            '                         </li>')
                    }
                    jQuery(".feuture_cont_" + number).append('' +
                        '            </ul>' +
                        '                      <input type="hidden"  class="TS_PTable_FCount" id="TS_PTable_FCount_' + number + '" name="TS_PTable_FCount_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_FCount'] + '">' +

                        '        </div>' +
                        '          <div class="Total_Soft_PTable_Features_New" onClick="Total_Soft_PTable_Features_New(' + number + ');Total_Soft_PTable_Features_New_Col(' + number + ',' + parseInt(parseInt(i) + 1) + ',2)">' +
                        '              <span class="Total_Soft_PTable_Features_New1">' +
                        '                 <i class="Total_Soft_PTable_Features_New_Icon totalsoft totalsoft-plus-circle" style="margin-right: 5px;"></i>Add New </span>' +
                        '          </div>' +
                        '          <div class="TS_PTable_Div2_' + number + ' relative flex_center" >' +
                        '             <div onclick="TS_Get_Icon_Button(this, ' + number + ')" class="TS_PTable_Button_' + number + '">' +
                        '                  <span class="Button_cont Button_cont_' + number + '">' +
                        '                      <span class="Button_text_cont">' +
                        '                          <span onmouseover="TS_Change_Feut_Val_Hover(this)" onmouseout="TS_Change_Feut_Val_Over(this)" class="TS_PTable_BText_' + number + '" contentEditable="true">' + data[i]['TS_PTable_BText'] + '</span>' +
                        '                          <input type="hidden" class="Total_Soft_PTable_Select" id="TS_PTable_BText_' + number + '" name="TS_PTable_BText_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_BText'] + '">' +
                        '                      </span>' +
                        '                      <span class="Button_icon_cont">' +
                        '                          <i class="totalsoft   TS_PTable_BIconA_' + number + ' totalsoft-' + ( data[i]['TS_PTable_BIcon']!='none'? data[i]['TS_PTable_BIcon']:'plus-square-o ') + '" ></i>' +
                        '                          <select onchange= "TS_ChangeBut_Icon_Select(this,' + number + ')" class="Total_Soft_PTable_Select  TS_PTable_BIcon TS_PTable_BIcon_' + number + '" onchange="ChangeValueForHiddenBIcon(this)"  name="TS_PTable_BIcon_' + number + '" style="font-family: FontAwesome, Arial;">'+Total_Soft_PTable_Select_Icon+'</select>'+
          
                        '                          <input type="hidden" class="Total_Soft_PTable_Select" id="TS_PTable_BIcon_' + number + '" name="TS_PTable_BIcon_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_BIcon'] + '">' +
                        '                      </span>' +
                        '                  </span>' +
                        '                  <div class="TS_PTable_Button_Link_' + number + ' TS_PTable_Button_Link">' +
                        '                     <i class="totalsoft  totalsoft-link" ></i>' +
                        '                     <input type="text" class="Total_Soft_PTable_Select TS_PTable_BLink " id="TS_PTable_BLink_' + number + '" name="TS_PTable_BLink_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_BLink'] + '">' +
                        '                  </div>' +
                        '             </div>' +
                        '          </div>' +
                        '        </div>' +
                        '      </div>' +
                        '    </div>' +
                        '   </div>' +
                        '</div>'
                    )
                     jQuery('.TS_PTable_BIcon_' + number ).val( jQuery('#TS_PTable_BIcon_' + number  ).val());
                    if (dataSet[i]["TS_PTable_ST_02"] == "on") {
                        jQuery('.TS_PTable_PValue_' + dataSet[i]["id"]).addClass("top-23");
                    } else {
                        jQuery('.TS_PTable_PValue_' + dataSet[i]["id"]).addClass("top-24");
                    }
                    if (dataSet[i]["TS_PTable_ST_02"] == "on") {
                        jQuery('.TS_PTable_PValue_' + dataSet[i]["id"]).addClass("top-21");
                    } else {
                        jQuery('.TS_PTable_PValue_' + dataSet[i]["id"]).addClass("top-22");
                    }
                    if (dataSet[i]["TS_PTable_ST_02"] == 'on') {
                        jQuery(".TS_PTable_Container_Col_" + dataSet[i]['id']).css({
                            "-webkit-transform": "scale(1, 1.05)",
                            "-moz-transform": "scale(1, 1.05)",
                            "transform": "scale(1, 1.05)",
                        })
                    }else{
                        jQuery(".TS_PTable_Container_Col_" + dataSet[i]['id']).css({
                            "-webkit-transform": "scale(1, 1)",
                            "-moz-transform": "scale(1, 1)",
                            "transform": "scale(1, 1)",
                        })
                    }
                    for (var k = 1; k <= parseInt(data[i]['TS_PTable_FCount']); k++) {
                        jQuery("#TS_PTable_FIcon_" + number + "_" + k).val(TS_PTable_FIcon[k - 1]);
                        if (jQuery('#TS_PTable_FChecked_' + number + '_' + k).val() == TS_PTable_FCheck[k - 1]) {
                            jQuery('#TS_PTable_FChecked_' + number + '_' + k).attr('checked', 'checked');
                        }
                    }
                    if (dataSet[i]['TS_PTable_ST_26'] == "after") {
                        jQuery(".feut_container_" + dataSet[i]['id'] + "").attr("style", "flex-direction:row;");
                    } else if (dataSet[i]['TS_PTable_ST_26'] == "before") {
                        jQuery(".feut_container_" + dataSet[i]['id'] + "").attr("style", "flex-direction:row-reverse;");
                    }

                    if (dataSet[i]['TS_PTable_ST_30'] == "after") {
                        jQuery(".Button_cont_" + dataSet[i]['id'] + "").attr("style", "flex-direction:row;");
                    } else if (dataSet[i]['TS_PTable_ST_30'] == "before") {
                        jQuery(".Button_cont_" + dataSet[i]['id'] + "").attr("style", "flex-direction:row-reverse;");
                    }

                    jQuery("#TS_PTable_Col_" + number).append('' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_01" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_01" value="' + dataSet[i]['TS_PTable_ST_01'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_02" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_02" value="' + dataSet[i]['TS_PTable_ST_02'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_03" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_03" value="' + dataSet[i]['TS_PTable_ST_03'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_04" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_04" value="' + dataSet[i]['TS_PTable_ST_04'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_05" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_05" value="' + dataSet[i]['TS_PTable_ST_05'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_06" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_06" value="' + dataSet[i]['TS_PTable_ST_06'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_07" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_07" value="' + dataSet[i]['TS_PTable_ST_07'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_08" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_08" value="' + dataSet[i]['TS_PTable_ST_08'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_09" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_09" value="' + dataSet[i]['TS_PTable_ST_09'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_10" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_10" value="' + dataSet[i]['TS_PTable_ST_10'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_11" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_11" value="' + dataSet[i]['TS_PTable_ST_11'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_12" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_12" value="' + dataSet[i]['TS_PTable_ST_12'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_13" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_13" value="' + dataSet[i]['TS_PTable_ST_13'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_14" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_14" value="' + dataSet[i]['TS_PTable_ST_14'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_15" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_15" value="' + dataSet[i]['TS_PTable_ST_15'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_16" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_16" value="' + dataSet[i]['TS_PTable_ST_16'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_17" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_17" value="' + dataSet[i]['TS_PTable_ST_17'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_18" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_18" value="' + dataSet[i]['TS_PTable_ST_18'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_19" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_19" value="' + dataSet[i]['TS_PTable_ST_19'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_20" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_20" value="' + dataSet[i]['TS_PTable_ST_20'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_21" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_21" value="' + dataSet[i]['TS_PTable_ST_21'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_21_1" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_21_1" value="0">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_22" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_22" value="' + dataSet[i]['TS_PTable_ST_22'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_23" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_23" value="' + dataSet[i]['TS_PTable_ST_23'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_24" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_24" value="' + dataSet[i]['TS_PTable_ST_24'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_25" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_25" value="' + dataSet[i]['TS_PTable_ST_25'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_26" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_26" value="' + dataSet[i]['TS_PTable_ST_26'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_27" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_27" value="' + dataSet[i]['TS_PTable_ST_27'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_28" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_28" value="' + dataSet[i]['TS_PTable_ST_28'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_29" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_29" value="' + dataSet[i]['TS_PTable_ST_29'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_30" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_30" value="' + dataSet[i]['TS_PTable_ST_30'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_31" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_31" value="' + dataSet[i]['TS_PTable_ST_31'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_32" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_32" value="' + dataSet[i]['TS_PTable_ST_32'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_33" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_33" value="' + dataSet[i]['TS_PTable_ST_33'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_34" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_34" value="' + dataSet[i]['TS_PTable_ST_34'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_35" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_35" value="' + dataSet[i]['TS_PTable_ST_35'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_36" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_36" value="' + dataSet[i]['TS_PTable_ST_36'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_37" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_37" value="' + dataSet[i]['TS_PTable_ST_37'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_38" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_38" value="' + dataSet[i]['TS_PTable_ST_38'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_39" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_39" value="' + dataSet[i]['TS_PTable_ST_39'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST2_' + number + '_40" name="TS_PTable_ST2_' + parseInt(parseInt(i) + 1) + '_40" value="' + dataSet[i]['TS_PTable_ST_40'] + '">'
                    )
                     TS_Ch_Inp_2_Shadow_Type( '.TS_PTable_ST2_' + number + '_06', number); 
                } else if (data[i]["TS_PTable_TType"] == "type3") {
                    jQuery('.TS_PTable_Container').append('' +
                        '<div class=" TS_PTable_Container_Col_' + number + ' Total_Soft_PTable_AMMain_Div2_Cols1" id="TS_PTable_Col_' + number + '">' +
                        '    <input type="text" style="display:none;" class="Total_Soft_PTable_Select" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_ID" id="TS_PTable_ST3_' + number + '_ID" value="' + number + '">' +
                        '    <input type="text" style="display:none;" class="Total_Soft_PTable_Select" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_00" id="TS_PTable_ST3_' + number + '_00" value="Theme_' + number + '">' +
                        '    <input type="text" style="display:none" name="Total_Soft_PTable_Cols_Id" class="Total_Soft_PTable_Cols_Id" value="' + number + '">' +
                        '    <input type="hidden" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_index" id="TS_PTable_ST3_' + number + '_index" value="' + dataSet[i]['index'] + '">' +
                        '    <input type="text" style="display:none" name="Total_Soft_PTable_Set_Title" class="Total_Soft_PTable_Set_Title" value="Theme_' + number + '">' +
                        '       <div class="TS_PTable_Parent">' +
                        '           <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Actions">' +
                        '              <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action1">' +
                        '                 <i class="totalsoft totalsoft-arrows" title="Reorder" onmouseup="TotalSoftPTable_ColumnDivdel()" onmousedown="TotalSoftPTable_ColumnDivSort(' + number + ',' + data[i]['index'] + ')"></i>' +
                        '              </div>' +
                        '              <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action2">' +
                        '                  <i class="totalsoft totalsoft-file-text" title="Make Duplicate" onClick="TotalSoftPTable_Dup_Col(' + number + ', ' + parseInt(parseInt(i) + 1) + ')"></i>' +
                        '              </div>' +
                        '              <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action3">' +
                        '                  <i class="totalsoft totalsoft-trash" title="Delete Column" onClick="TotalSoftPTable_Del_Col(' + number + ')"></i>' +
                        '              </div>' +
                        '              <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action4">' +
                        '                  <i class="totalsoft totalsoft-pencil" title="Edit Column" onClick="TotalSoftPTable_Col_Dragdown(); TotalSoftPTable_Edit_' + Theme + '(this,' + number + ')"></i>' +
                        '              </div>' +
                        '           </div>' +
                        '      <div class="TS_PTable_Shadow_' + number + '">' +
                        '          <div class="TS_PTable__' + number + '">' +
                        '              <div class="TS_PTable_Div1_' + number + '">' +
                        '                  <div class="TS_PTable_Title_Icon_' + number + '">' +
                        '                      <i onClick="TS_Get_Icon_Title(this, ' + number + ')" class="totalsoft totalsoft-' + ((data[i]['TS_PTable_TIcon']=='none' )?'plus-square-o':data[i]['TS_PTable_TIcon']) + '"></i>' +
                        '                       <input type="hidden" class="Total_Soft_PTable_Select" id="TS_PTable_TIcon_' + number + '" name="TS_PTable_TIcon_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_TIcon'] + '">' +
                        '                  </div>' +
                        '                  <div class="span_hover TS_PTable_PValue_' + number + '">' +
                        '                      <sup><span class="TS_PTable_PCur_' + number + '" contentEditable="true" oninput="TS_Change_Cur_Val(this)" onClick="jQuery(this).focus();">' + data[i]['TS_PTable_PCur'] + '</span> <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_PCur_Hidden" id="TS_PTable_New_PCur_' + number + '" name="TS_PTable_PCur_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_PCur'] + '"></sup>' +
                        '                      <span class="TS_PTable_PVal_' + number + '" contentEditable="true" oninput="TS_Change_Val_Val(this)" onClick="jQuery(this).focus();">' + data[i]['TS_PTable_PVal'] + '</span>' +
                        '                       <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_PVal_Hidden" id="TS_PTable_New_PVal_' + number + '" name="TS_PTable_PVal_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_PVal'] + '">' +
                        '                  </div>' +
                        '                  <div class=" span_hover title_PPlan_Container">' +
                        '                       <span class="TS_PTable_PPlan_' + number + '"  contentEditable="true" oninput="TS_Change_Plan_Val(this)" onClick="jQuery(this).focus();">' + data[i]['TS_PTable_PPlan'] + '</span>' +
                        '                       <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_PPlan_Hidden" id="TS_PTable_New_PPlan_' + number + '" name="TS_PTable_PPlan_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_PPlan'] + '">' +
                        '                  </div>' +
                        '                  <div class="TS_PTable_Header_' + number + '">' +
                        '                      <h3 class="h3_hover TS_PTable_Title_' + number + '" onInput="TS_Change_H3_Val(this)" contentEditable="true" onClick="jQuery(this).focus();">' + data[i]['TS_PTable_TText'] + '</h3>' +
                        '                      <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_TText_Hidden TS_PTable_TText" id="TS_PTable_New_TText_' + number + '" name="TS_PTable_TText_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_TText'] + '">' +
                        '                  </div>' +
                        '              </div>' +
                        '              <div class="TS_PTable_Content_' + number + '">' +
                        '<div class="relative feuture_cont_' + number + '">' +
                        '                  <ul class="TS_PTable_Features_' + number + ' TS_PTable_Features">')
                    var TS_PTable_FChek = '';
                    var Total_Soft_PTable_Select_Icon = jQuery('#Total_Soft_PTable_Select_Icon').html();
                    for (j = 0; j < data[i]['TS_PTable_FCount']; j++) {
                        if (TS_PTable_FCheck[j] != '') {
                            TS_PTable_FChek = 'TS_PTable_FCheck';
                        } else {
                            TS_PTable_FChek = '';
                        }
                        jQuery(".TS_PTable_Features_" + number).append('' +
                            '                       <li onMouseOver="TS_PTable_Features_Li(this)" onMouseOut="TS_PTable_Features_Li_Out(this)" class="TS_Li_' + number + '_' + parseInt(parseInt(j) + 1) + '">' +
                            '                                   <div class="Hiddem_Li_Container">' +
                            '                                   <span class="hiddenChangeText"><i class="totalsoft totalsoft-times TS_PTable_FDI TS_PTable_FDI_' + number + '" title="Delete Feature" onClick="TotalSoftPTable_Del_FT(' + number + ', ' + parseInt(parseInt(j) + 1) + ')"></i>     </span>' +
                            '                                   <span class="TS_PTable_FChecked TS_PTable_FCheckedHide TS_PTable_FChecked_' + number + '">' +
                            '                                           <input type="checkbox "onClick="TS_PTable_TS_PTable_FChecked_label(this,' + parseInt(parseInt(i) + 1) + ')" class="' + TS_PTable_FChek + '" id="TS_PTable_FChecked_' + number + '_' + parseInt(parseInt(j) + 1) + '" name="TS_PTable_FChecked_' + parseInt(parseInt(i) + 1) + '_' + parseInt(parseInt(j) + 1) + '" value="' + parseInt(parseInt(j) + 1) + '">' +
                            '                                           <label class="totalsoft totalsoft-question-circle-o" for="TS_PTable_FChecked_' + number + '_' + parseInt(parseInt(j) + 1) + '"></label>' +
                            '                                   </span>' +
                            '                               </div>' +
                            '                               <div class="feut_container_' + number + ' feut_container">' +
                            '                                   <div class="feut_text_cont">' +
                            '                                       <span onmouseover="TS_Change_Feut_Val_Hover(this)" onmouseout="TS_Change_Feut_Val_Over(this)" class="TS_PTable_FText_' + number + '_' + parseInt(parseInt(j) + 1) + '" oninput="TS_Change_Feut_Val(this)" contentEditable="true" onClick="jQuery(this).focus();">' + TS_PTable_FText[j] + '</span>' +
                            '                                       <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_FText_' + number + ' TS_PTable_Feut_Hidden"  name="TS_PTable_FText_' + parseInt(parseInt(i) + 1) + '_' + parseInt(parseInt(j) + 1) + '" value="' + TS_PTable_FText[j] + '">' +
                            '                                   </div>' +
                            '                                   <div class="feut_icon_cont">' +
                            '                                       <i onClick="TS_Get_Icon_Feuture(this, ' + number + ', ' + parseInt(parseInt(j) + 1) + ')" class="totalsoft  TS_PTable_FIcon_' + number + ' ' + TS_PTable_FChek + ' totalsoft-' + (TS_PTable_FIcon[j]!='none'?TS_PTable_FIcon[j]:'plus-square-o ') + '"></i>' +
                            '                                       <input type="hidden"  class="Total_Soft_PTable_Select  TS_PTable_FIcon_' + number + '_' + parseInt(parseInt(j) + 1) + '" name="TS_PTable_FIcon_' + parseInt(parseInt(i) + 1) + '_' + parseInt(parseInt(j) + 1) + '" value="' + TS_PTable_FIcon[j] + '">' +
                            '                                   </div>' +
                            '                               </div>' +
                            '                         </li>')
                    }
                    jQuery(".feuture_cont_" + number).append('' +
                        '                  </ul>' +
                        '           <input type="hidden"  class="TS_PTable_FCount" id="TS_PTable_FCount_' + number + '" name="TS_PTable_FCount_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_FCount'] + '">' +
                        '              </div>' +
                        '              </div>' +
                        '                      <div class="Total_Soft_PTable_Features_New" onClick="Total_Soft_PTable_Features_New(' + number + ');Total_Soft_PTable_Features_New_Col(' + number + ',' + parseInt(parseInt(i) + 1) + ',3)">' +
                        '                          <span class="Total_Soft_PTable_Features_New1">' +
                        '                             <i class="Total_Soft_PTable_Features_New_Icon totalsoft totalsoft-plus-circle" style="margin-right: 5px;"></i>Add New </span>' +
                        '                      </div>' +
                        '              <div class="TS_PTable_Div2_' + number + '  relative flex_center ">' +
                        '                 <div onclick="TS_Get_Icon_Button(this, ' + number + ')" class="TS_PTable_Button_' + number + '">' +
                        '                      <span class="Button_cont Button_cont_' + number + '">' +
                        '                          <span class="Button_text_cont">' +
                        '                              <span onmouseover="TS_Change_Feut_Val_Hover(this)" onmouseout="TS_Change_Feut_Val_Over(this)" class="TS_PTable_BText_' + number + '" contentEditable="true">' + data[i]['TS_PTable_BText'] + '</span>' +
                        '                              <input type="hidden" class="Total_Soft_PTable_Select" id="TS_PTable_BText_' + number + '" name="TS_PTable_BText_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_BText'] + '">' +
                        '                          </span>' +
                        '                          <span class="Button_icon_cont">' +
                        '                              <i  class="totalsoft   TS_PTable_BIconA_' + number + ' totalsoft-' + ( data[i]['TS_PTable_BIcon']!='none'? data[i]['TS_PTable_BIcon']:'plus-square-o ') + '" ></i>' +
                        '                                   <select onchange= "TS_ChangeBut_Icon_Select(this,' + number + ')" class="Total_Soft_PTable_Select  TS_PTable_BIcon TS_PTable_BIcon_' + number + '" onchange="ChangeValueForHiddenBIcon(this)"  name="TS_PTable_BIcon_' + number + '" style="font-family: FontAwesome, Arial;">'+Total_Soft_PTable_Select_Icon+'</select>'+
          
                        '                              <input type="hidden" class="Total_Soft_PTable_Select" id="TS_PTable_BIcon_' + number + '" name="TS_PTable_BIcon_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_BIcon'] + '">' +
                        '                          </span>' +
                        '                      </span>' +
                        '                      <div class="TS_PTable_Button_Link_' + number + ' TS_PTable_Button_Link">' +
                        '                         <i class="totalsoft  totalsoft-link" ></i>' +
                        '                         <input type="text" class="Total_Soft_PTable_Select TS_PTable_BLink " id="TS_PTable_BLink_' + number + '" name="TS_PTable_BLink_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_BLink'] + '">' +
                        '                      </div>' +
                        '                 </div>' +
                        '              </div>' +
                        '          </div>' +
                        '      </div>' +
                        '    </div>' +
                        '  </div>'
                    )
                     jQuery('.TS_PTable_BIcon_' + number ).val( jQuery('#TS_PTable_BIcon_' + number  ).val());
                    jQuery(".TS_PTable_Container").css("gap", Total_Soft_PTable_M_03 + "px");
                    if (dataSet[i]["TS_PTable_ST_02"] == 'on') {
                        jQuery(".TS_PTable_Container_Col_" + dataSet[i]['id']).css({
                            "-webkit-transform": "scale(1, 1.05)",
                            "-moz-transform": "scale(1, 1.05)",
                            "transform": "scale(1, 1.05)",
                        })
                    }else{
                        jQuery(".TS_PTable_Container_Col_" + dataSet[i]['id']).css({
                            "-webkit-transform": "scale(1, 1)",
                            "-moz-transform": "scale(1, 1)",
                            "transform": "scale(1, 1)",
                        })
                    }
                    for (var k = 1; k <= parseInt(data[i]['TS_PTable_FCount']); k++) {
                        jQuery("#TS_PTable_FIcon_" + number + "_" + k).html(Total_Soft_PTable_Select_Icon);
                        jQuery("#TS_PTable_FIcon_" + number + "_" + k).val(TS_PTable_FIcon[k - 1]);
                        if (jQuery('#TS_PTable_FChecked_' + number + '_' + k).val() == TS_PTable_FCheck[k - 1]) {
                            jQuery('#TS_PTable_FChecked_' + number + '_' + k).attr('checked', 'checked');
                        }
                    }
                    if (dataSet[i]['TS_PTable_ST_29'] == "after") {
                        jQuery(".feut_container_" + dataSet[i]['id'] + "").attr("style", "flex-direction:row;");
                    } else if (dataSet[i]['TS_PTable_ST_29'] == "before") {
                        jQuery(".feut_container_" + dataSet[i]['id'] + "").attr("style", "flex-direction:row-reverse;");
                    }

                    if (dataSet[i]['TS_PTable_ST_33'] == "after") {
                        jQuery(".Button_cont_" + dataSet[i]['id'] + "").attr("style", "flex-direction:row;");
                    } else if (dataSet[i]['TS_PTable_ST_33'] == "before") {
                        jQuery(".Button_cont_" + dataSet[i]['id'] + "").attr("style", "flex-direction:row-reverse;");
                    }


                    jQuery("#TS_PTable_Col_" + number).append('' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_01" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_01" value="' + dataSet[i]['TS_PTable_ST_01'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_02" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_02" value="' + dataSet[i]['TS_PTable_ST_02'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_03" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_03" value="' + dataSet[i]['TS_PTable_ST_03'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_04" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_04" value="' + dataSet[i]['TS_PTable_ST_04'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_05" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_05" value="' + dataSet[i]['TS_PTable_ST_05'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_06" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_06" value="' + dataSet[i]['TS_PTable_ST_06'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_07" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_07" value="' + dataSet[i]['TS_PTable_ST_07'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_08" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_08" value="' + dataSet[i]['TS_PTable_ST_08'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_09" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_09" value="' + dataSet[i]['TS_PTable_ST_09'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_10" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_10" value="' + dataSet[i]['TS_PTable_ST_10'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_11" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_11" value="' + dataSet[i]['TS_PTable_ST_11'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_12" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_12" value="' + dataSet[i]['TS_PTable_ST_12'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_13" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_13" value="' + dataSet[i]['TS_PTable_ST_13'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_14" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_14" value="' + dataSet[i]['TS_PTable_ST_14'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_15" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_15" value="' + dataSet[i]['TS_PTable_ST_15'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_16" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_16" value="' + dataSet[i]['TS_PTable_ST_16'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_17" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_17" value="' + dataSet[i]['TS_PTable_ST_17'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_18" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_18" value="' + dataSet[i]['TS_PTable_ST_18'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_19" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_19" value="' + dataSet[i]['TS_PTable_ST_19'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_20" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_20" value="' + dataSet[i]['TS_PTable_ST_20'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_21" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_21" value="' + dataSet[i]['TS_PTable_ST_21'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_21_1" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_21_1" value="' + dataSet[i]['TS_PTable_ST_21_1'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_22" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_22" value="' + dataSet[i]['TS_PTable_ST_22'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_23" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_23" value="' + dataSet[i]['TS_PTable_ST_23'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_24" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_24" value="' + dataSet[i]['TS_PTable_ST_24'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_25" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_25" value="' + dataSet[i]['TS_PTable_ST_25'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_26" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_26" value="' + dataSet[i]['TS_PTable_ST_26'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_27" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_27" value="' + dataSet[i]['TS_PTable_ST_27'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_28" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_28" value="' + dataSet[i]['TS_PTable_ST_28'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_29" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_29" value="' + dataSet[i]['TS_PTable_ST_29'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_30" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_30" value="' + dataSet[i]['TS_PTable_ST_30'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_31" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_31" value="' + dataSet[i]['TS_PTable_ST_31'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_32" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_32" value="' + dataSet[i]['TS_PTable_ST_32'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_33" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_33" value="' + dataSet[i]['TS_PTable_ST_33'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_34" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_34" value="' + dataSet[i]['TS_PTable_ST_34'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_35" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_35" value="' + dataSet[i]['TS_PTable_ST_35'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_36" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_36" value="' + dataSet[i]['TS_PTable_ST_36'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_37" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_37" value="' + dataSet[i]['TS_PTable_ST_37'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_38" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_38" value="' + dataSet[i]['TS_PTable_ST_38'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_39" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_39" value="' + dataSet[i]['TS_PTable_ST_39'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST3_' + number + '_40" name="TS_PTable_ST3_' + parseInt(parseInt(i) + 1) + '_40" value="' + dataSet[i]['TS_PTable_ST_40'] + '">'
                    )
                    TS_Ch_Inp_3_Shadow_Type( '.TS_PTable_ST3_' + number + '_06', number); 
                } else if (data[i]["TS_PTable_TType"] == "type4") {
                    jQuery('.TS_PTable_Container').append('' +
                        '<div class=" TS_PTable_Container_Col_' + number + ' Total_Soft_PTable_AMMain_Div2_Cols1" id="TS_PTable_Col_' + number + '">' +
                        '    <input type="text" style="display:none;" class="Total_Soft_PTable_Select" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_ID" id="TS_PTable_ST4_' + number + '_ID" value="' + number + '">' +
                        '    <input type="text" style="display:none;" class="Total_Soft_PTable_Select" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_00" id="TS_PTable_ST4_' + number + '_00" value="Theme_' + number + '">' +
                        '    <input type="text" style="display:none" name="Total_Soft_PTable_Cols_Id" class="Total_Soft_PTable_Cols_Id" value="' + number + '">' +
                        '    <input type="hidden" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_index" id="TS_PTable_ST4_' + number + '_index" value="' + dataSet[i]['index'] + '">' +

                        '    <input type="text" style="display:none" name="Total_Soft_PTable_Set_Title" class="Total_Soft_PTable_Set_Title" value="Theme_' + number + '">' +
                        '       <div class="TS_PTable_Parent">' +
                        '           <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Actions">' +
                        '              <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action1">' +
                        '                 <i class="totalsoft totalsoft-arrows" title="Reorder" onmouseup="TotalSoftPTable_ColumnDivdel()" onmousedown="TotalSoftPTable_ColumnDivSort(' + number + ',' + data[i]['index'] + ')"></i>' +
                        '              </div>' +
                        '              <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action2">' +
                        '                  <i class="totalsoft totalsoft-file-text" title="Make Duplicate" onClick="TotalSoftPTable_Dup_Col(' + number + ', ' + parseInt(parseInt(i) + 1) + ')"></i>' +
                        '              </div>' +
                        '              <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action3">' +
                        '                  <i class="totalsoft totalsoft-trash" title="Delete Column" onClick="TotalSoftPTable_Del_Col(' + number + ')"></i>' +
                        '              </div>' +
                        '              <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action4">' +
                        '                  <i class="totalsoft totalsoft-pencil" title="Edit Column" onClick="TotalSoftPTable_Col_Dragdown(); TotalSoftPTable_Edit_' + Theme + '(this,' + number + ')"></i>' +
                        '              </div>' +
                        '           </div>' +
                        '           <div class="TS_PTable_Shadow_' + number + '">' +
                        '               <div class="TS_PTable__' + number + '">' +
                        '                   <div class="TS_PTable_Div1_' + number + '">' +
                        '                       <div class="title_h3_Container TS_PTable_Header_' + number + '">' +
                        '                           <h3 class="h3_hover TS_PTable_Title_' + number + '" onInput="TS_Change_H3_Val(this)" contentEditable="true" onClick="jQuery(this).focus();">' + data[i]['TS_PTable_TText'] + '</h3>' +
                        '                           <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_TText_Hidden TS_PTable_TText" id="TS_PTable_New_TText_' + number + '" name="TS_PTable_TText_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_TText'] + '">' +
                        '                       </div>' +
                        '                       <span class="span_hover TS_PTable_Amount_' + number + '">' +
                        '                            <sup class="TS_PTable_PCur_' + number + '" contentEditable="true" oninput="TS_Change_Cur_Val(this)" onClick="jQuery(this).focus();">' + data[i]['TS_PTable_PCur'] + '</sup>' +
                        '                           <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_PCur_Hidden" id="TS_PTable_New_PCur_' + number + '" name="TS_PTable_PCur_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_PCur'] + '">' +
                        '                            <span class="TS_PTable_PVal_' + number + '" contentEditable="true" oninput="TS_Change_Val_Val(this)" onClick="jQuery(this).focus();">' + data[i]['TS_PTable_PVal'] + '</span>' +
                        '                           <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_PVal_Hidden" id="TS_PTable_New_PVal_' + number + '" name="TS_PTable_PVal_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_PVal'] + '">' +
                        '                            <sub class="TS_PTable_PPlan_' + number + '"  contentEditable="true" oninput="TS_Change_Plan_Val(this)" onClick="jQuery(this).focus();">' + data[i]['TS_PTable_PPlan'] + '</sub>' +
                        '                           <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_PPlan_Hidden" id="TS_PTable_New_PPlan_' + number + '" name="TS_PTable_PPlan_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_PPlan'] + '">' +
                        '                         </span>' +
                        '                   </div>' +
                        '                   <div class="TS_PTable_Content_' + number + '">' +
                        '<div class="relative feuture_cont_' + number + '">' +
                        '                       <ul class="TS_PTable_Features_' + number + ' TS_PTable_Features">')
                    var TS_PTable_FChek = '';
                     var Total_Soft_PTable_Select_Icon = jQuery('#Total_Soft_PTable_Select_Icon').html();
                    for (j = 0; j < data[i]['TS_PTable_FCount']; j++) {
                        if (TS_PTable_FCheck[j] != '') {
                            TS_PTable_FChek = 'TS_PTable_FCheck';
                        } else {
                            TS_PTable_FChek = '';
                        }
                        jQuery(".TS_PTable_Features_" + number).append('' +
                            '                       <li onMouseOver="TS_PTable_Features_Li(this)" onMouseOut="TS_PTable_Features_Li_Out(this)" class="TS_Li_' + number + '_' + parseInt(parseInt(j) + 1) + ' TS_Li_4_Border">' +
                            '                                   <div class="Hiddem_Li_Container">' +
                            '                                   <span class="hiddenChangeText"><i class="totalsoft totalsoft-times TS_PTable_FDI TS_PTable_FDI_' + number + '" title="Delete Feature" onClick="TotalSoftPTable_Del_FT(' + number + ', ' + parseInt(parseInt(j) + 1) + ')"></i>     </span>' +
                            '                                   <span class="TS_PTable_FChecked TS_PTable_FCheckedHide TS_PTable_FChecked_' + number + '">' +
                            '                                           <input type="checkbox" onClick="TS_PTable_TS_PTable_FChecked_label(this,' + parseInt(parseInt(i) + 1) + ')" class="' + TS_PTable_FChek + '" id="TS_PTable_FChecked_' + number + '_' + parseInt(parseInt(j) + 1) + '" name="TS_PTable_FChecked_' + parseInt(parseInt(i) + 1) + '_' + parseInt(parseInt(j) + 1) + '" value="' + parseInt(parseInt(j) + 1) + '">' +
                            '                                           <label class="totalsoft totalsoft-question-circle-o" for="TS_PTable_FChecked_' + number + '_' + parseInt(parseInt(j) + 1) + '"></label>' +
                            '                                   </span>' +
                            '                               </div>' +
                            '                               <div class="feut_container_' + number + ' feut_container">' +
                            '                                   <div class="feut_text_cont">' +
                            '                                       <span onmouseover="TS_Change_Feut_Val_Hover(this)" onmouseout="TS_Change_Feut_Val_Over(this)" class="TS_PTable_FText_' + number + '_' + parseInt(parseInt(j) + 1) + '" oninput="TS_Change_Feut_Val(this)" contentEditable="true" onClick="jQuery(this).focus();">' + TS_PTable_FText[j] + '</span>' +
                            '                                       <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_FText_' + number + ' TS_PTable_Feut_Hidden"  name="TS_PTable_FText_' + parseInt(parseInt(i) + 1) + '_' + parseInt(parseInt(j) + 1) + '" value="' + TS_PTable_FText[j] + '">' +
                            '                                   </div>' +
                            '                                   <div class="feut_icon_cont">' +
                            '                                       <i onClick="TS_Get_Icon_Feuture(this, ' + number + ', ' + parseInt(parseInt(j) + 1) + ')" class="totalsoft  TS_PTable_FIcon_' + number + ' ' + TS_PTable_FChek + ' totalsoft-' + (TS_PTable_FIcon[j]!='none'?TS_PTable_FIcon[j]:'plus-square-o ') + '"></i>' +
                            '                                       <input type="hidden"  class="Total_Soft_PTable_Select  TS_PTable_FIcon_' + number + '_' + parseInt(parseInt(j) + 1) + '" name="TS_PTable_FIcon_' + parseInt(parseInt(i) + 1) + '_' + parseInt(parseInt(j) + 1) + '" value="' + TS_PTable_FIcon[j] + '">' +
                            '                                   </div>' +
                            '                               </div>' +
                            '                         </li>')
                    }
                    jQuery(".feuture_cont_" + number).append('' +
                        '                  </ul>' +
                        '           <input type="hidden"  class="TS_PTable_FCount" id="TS_PTable_FCount_' + number + '" name="TS_PTable_FCount_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_FCount'] + '">' +
                        '              </div>' +
                        '              </div>' +
                        '       <table id="Total_Soft_PTable_Features_Col_' + number + '"></table>' +
                        '           <table class="TS_Table Total_Soft_PTable_AMMain_Div2_Cols1_FTable1" id="Total_Soft_PTable_Features_' + number + '">' +
                        '              <tr>' +
                        '                  <td colSpan="2">' +
                        '                      <div class="Total_Soft_PTable_Features_New" onClick="Total_Soft_PTable_Features_New(' + number + ');Total_Soft_PTable_Features_New_Col(' + number + ',' + parseInt(parseInt(i) + 1) + ',4)">' +
                        '                          <span class="Total_Soft_PTable_Features_New1">' +
                        '                             <i class="Total_Soft_PTable_Features_New_Icon totalsoft totalsoft-plus-circle" style="margin-right: 5px;"></i>Add New </span>' +
                        '                      </div>' +
                        '                  </td>' +
                        '              </tr>' +
                        '           </table>' +
                        '           <div class="TS_PTable_Div2_' + number + '  relative flex_center ">' +
                        '               <div onclick="TS_Get_Icon_Button(this, ' + number + ')" class="TS_PTable_Button_' + number + '">' +
                        '                    <span class="Button_cont Button_cont_' + number + '">' +
                        '                        <span class="Button_text_cont">' +
                        '                            <span onmouseover="TS_Change_Feut_Val_Hover(this)" onmouseout="TS_Change_Feut_Val_Over(this)" class="TS_PTable_BText_' + number + '" contentEditable="true">' + data[i]['TS_PTable_BText'] + '</span>' +
                        '                            <input type="hidden" class="Total_Soft_PTable_Select" id="TS_PTable_BText_' + number + '" name="TS_PTable_BText_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_BText'] + '">' +
                        '                        </span>' +
                        '                        <span class="Button_icon_cont">' +
                        '                            <i  class="totalsoft   TS_PTable_BIconA_' + number + ' totalsoft-' + ( data[i]['TS_PTable_BIcon']!='none'? data[i]['TS_PTable_BIcon']:'plus-square-o ') + '" ></i>' +
                        '                                   <select onchange= "TS_ChangeBut_Icon_Select(this,' + number + ')" class="Total_Soft_PTable_Select  TS_PTable_BIcon TS_PTable_BIcon_' + number + '" onchange="ChangeValueForHiddenBIcon(this)"  name="TS_PTable_BIcon_' + number + '" style="font-family: FontAwesome, Arial;">'+Total_Soft_PTable_Select_Icon+'</select>'+
          
                        '                            <input type="hidden" class="Total_Soft_PTable_Select" id="TS_PTable_BIcon_' + number + '" name="TS_PTable_BIcon_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_BIcon'] + '">' +
                        '                        </span>' +
                        '                    </span>' +
                        '                    <div class="TS_PTable_Button_Link_' + number + ' TS_PTable_Button_Link">' +
                        '                       <i class="totalsoft  totalsoft-link" ></i>' +
                        '                       <input type="text" class="Total_Soft_PTable_Select TS_PTable_BLink " id="TS_PTable_BLink_' + number + '" name="TS_PTable_BLink_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_BLink'] + '">' +
                        '                    </div>' +
                        '               </div>' +
                        '            </div>' +
                        '          </div>' +
                        '      </div>' +
                        '    </div>' +
                        '  </div>'
                    )
                jQuery('.TS_PTable_BIcon_' + number ).val( jQuery('#TS_PTable_BIcon_' + number  ).val());
                    jQuery(".TS_PTable_Container").css("gap", Total_Soft_PTable_M_03 + "px");
                    if (dataSet[i]["TS_PTable_ST_02"] == 'on') {
                        jQuery(".TS_PTable_Container_Col_" + dataSet[i]['id']).css({
                            "-webkit-transform": "scale(1, 1.05)",
                            "-moz-transform": "scale(1, 1.05)",
                            "transform": "scale(1, 1.05)",
                        })
                    }
                    for (var k = 1; k <= parseInt(data[i]['TS_PTable_FCount']); k++) {
                        jQuery("#TS_PTable_FIcon_" + number + "_" + k).html(Total_Soft_PTable_Select_Icon);
                        jQuery("#TS_PTable_FIcon_" + number + "_" + k).val(TS_PTable_FIcon[k - 1]);
                        if (jQuery('#TS_PTable_FChecked_' + number + '_' + k).val() == TS_PTable_FCheck[k - 1]) {
                            jQuery('#TS_PTable_FChecked_' + number + '_' + k).attr('checked', 'checked');
                        }
                    }
                    if (dataSet[i]['TS_PTable_ST_24'] == "after") {
                        jQuery(".feut_container_" + dataSet[i]['id'] + "").attr("style", "flex-direction:row;");
                    } else if (dataSet[i]['TS_PTable_ST_24'] == "before") {
                        jQuery(".feut_container_" + dataSet[i]['id'] + "").attr("style", "flex-direction:row-reverse;");
                    }

                    if (dataSet[i]['TS_PTable_ST_28'] == "after") {
                        jQuery(".Button_cont_" + dataSet[i]['id'] + "").attr("style", "flex-direction:row;");
                    } else if (dataSet[i]['TS_PTable_ST_28'] == "before") {
                        jQuery(".Button_cont_" + dataSet[i]['id'] + "").attr("style", "flex-direction:row-reverse;");
                    }

                    jQuery("#TS_PTable_Col_" + number).append('' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_01" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_01" value="' + dataSet[i]['TS_PTable_ST_01'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_02" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_02" value="' + dataSet[i]['TS_PTable_ST_02'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_03" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_03" value="' + dataSet[i]['TS_PTable_ST_03'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_04" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_04" value="' + dataSet[i]['TS_PTable_ST_04'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_05" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_05" value="' + dataSet[i]['TS_PTable_ST_05'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_06" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_06" value="' + dataSet[i]['TS_PTable_ST_06'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_07" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_07" value="' + dataSet[i]['TS_PTable_ST_07'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_08" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_08" value="' + dataSet[i]['TS_PTable_ST_08'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_09" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_09" value="' + dataSet[i]['TS_PTable_ST_09'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_10" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_10" value="' + dataSet[i]['TS_PTable_ST_10'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_11" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_11" value="' + dataSet[i]['TS_PTable_ST_11'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_12" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_12" value="' + dataSet[i]['TS_PTable_ST_12'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_13" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_13" value="' + dataSet[i]['TS_PTable_ST_13'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_14" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_14" value="' + dataSet[i]['TS_PTable_ST_14'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_15" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_15" value="' + dataSet[i]['TS_PTable_ST_15'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_16" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_16" value="' + dataSet[i]['TS_PTable_ST_16'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_17" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_17" value="' + dataSet[i]['TS_PTable_ST_17'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_18" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_18" value="' + dataSet[i]['TS_PTable_ST_18'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_19" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_19" value="' + dataSet[i]['TS_PTable_ST_19'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_20" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_20" value="' + dataSet[i]['TS_PTable_ST_20'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_21" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_21" value="' + dataSet[i]['TS_PTable_ST_21'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_21_1" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_21_1" value="' + dataSet[i]['TS_PTable_ST_21_1'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_22" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_22" value="' + dataSet[i]['TS_PTable_ST_22'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_23" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_23" value="' + dataSet[i]['TS_PTable_ST_23'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_24" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_24" value="' + dataSet[i]['TS_PTable_ST_24'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_25" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_25" value="' + dataSet[i]['TS_PTable_ST_25'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_26" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_26" value="' + dataSet[i]['TS_PTable_ST_26'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_27" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_27" value="' + dataSet[i]['TS_PTable_ST_27'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_28" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_28" value="' + dataSet[i]['TS_PTable_ST_28'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_29" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_29" value="' + dataSet[i]['TS_PTable_ST_29'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_30" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_30" value="' + dataSet[i]['TS_PTable_ST_30'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_31" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_31" value="' + dataSet[i]['TS_PTable_ST_31'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_32" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_32" value="' + dataSet[i]['TS_PTable_ST_32'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_33" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_33" value="' + dataSet[i]['TS_PTable_ST_33'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_34" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_34" value="' + dataSet[i]['TS_PTable_ST_34'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_35" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_35" value="' + dataSet[i]['TS_PTable_ST_35'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_36" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_36" value="' + dataSet[i]['TS_PTable_ST_36'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_37" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_37" value="' + dataSet[i]['TS_PTable_ST_37'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_38" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_38" value="' + dataSet[i]['TS_PTable_ST_38'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_39" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_39" value="' + dataSet[i]['TS_PTable_ST_39'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST4_' + number + '_40" name="TS_PTable_ST4_' + parseInt(parseInt(i) + 1) + '_40" value="' + dataSet[i]['TS_PTable_ST_40'] + '">'
                    )
                TS_Ch_Inp_4_Shadow_Type( '.TS_PTable_ST4_' + number + '_06', number); 
                } else if (data[i]["TS_PTable_TType"] == "type5") {
                    jQuery('.TS_PTable_Container').append('' +
                        '   <div class=" TS_PTable_Container_Col_' + number + ' Total_Soft_PTable_AMMain_Div2_Cols1" id="TS_PTable_Col_' + number + '"> ' +
                        '       <input type="text" style="display:none;" class="Total_Soft_PTable_Select" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_ID" id="TS_PTable_ST5_' + number + '_ID" value="' + number + '"> ' +
                        '       <input type="text" style="display:none;" class="Total_Soft_PTable_Select" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_00" id="TS_PTable_ST5_' + number + '_00" value="Theme_' + number + '"> ' +
                        '       <input type="text" style="display:none" name="Total_Soft_PTable_Cols_Id" class="Total_Soft_PTable_Cols_Id" value="' + number + '"> ' +
                        '    <input type="hidden" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_index" id="TS_PTable_ST5_' + number + '_index" value="' + dataSet[i]['index'] + '">' +
                        '       <input type="text" style="display:none" name="Total_Soft_PTable_Set_Title" class="Total_Soft_PTable_Set_Title" value="Theme_' + number + '"> ' +
                        '       <div class="TS_PTable_Parent"> ' +
                        '           <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Actions"> ' +
                        '               <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action1"> ' +
                        '                   <i class="totalsoft totalsoft-arrows" title="Reorder" ' +
                        '                     onmouseup="TotalSoftPTable_ColumnDivdel()" onmousedown="TotalSoftPTable_ColumnDivSort(' + number + ',' + data[i]['index'] + ')"></i> ' +
                        '               </div> ' +
                        '               <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action2"> ' +
                        '                   <i class="totalsoft totalsoft-file-text" title="Make Duplicate" ' +
                        '                      onClick="TotalSoftPTable_Dup_Col(' + number + ', ' + parseInt(parseInt(i) + 1) + ')"></i> ' +
                        '               </div> ' +
                        '               <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action3"> ' +
                        '                   <i class="totalsoft totalsoft-trash" title="Delete Column" ' +
                        '                      onClick="TotalSoftPTable_Del_Col(' + number + ')"></i> ' +
                        '               </div> ' +
                        '               <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action4"> ' +
                        '                   <i class="totalsoft totalsoft-pencil" title="Edit Column" ' +
                        '                      onClick="TotalSoftPTable_Col_Dragdown(); TotalSoftPTable_Edit_' + Theme + '(this,' + number + ')"></i> ' +
                        '               </div> ' +
                        '           </div> ' +
                        '           <div class="TS_PTable_Shadow_' + number + '"> ' +
                        '               <div class="TS_PTable__' + number + '"> ' +
                        '                   <div class="TS_PTable_Div1_' + number + '"> ' +
                        '                       <i  onClick="TS_Get_Icon_Title(this, ' + number + ')" class="totalsoft totalsoft-' + data[i]['TS_PTable_TIcon'] + '"></i> ' +
                        '                       <input type="hidden" class="Total_Soft_PTable_Select" id="TS_PTable_TIcon_' + number + '" name="TS_PTable_TIcon_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_TIcon'] + '">' +

                        '                       <div class="span_hover TS_PTable_Amount_' + number + '"> ' +
                        '                            <span class="TS_PTable_PCur_' + number + '" contentEditable="true" oninput="TS_Change_Cur_Val(this)" onClick="jQuery(this).focus();">' + data[i]['TS_PTable_PCur'] + '</span>' +
                        '                           <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_PCur_Hidden" id="TS_PTable_New_PCur_' + number + '" name="TS_PTable_PCur_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_PCur'] + '">' +
                        '                           <span class="TS_PTable_PVal_' + number + '" contentEditable="true" oninput="TS_Change_Val_Val(this)" onClick="jQuery(this).focus();"> ' + data[i]['TS_PTable_PVal'] + '</span>' +
                        '                           <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_PVal_Hidden" id="TS_PTable_New_PVal_' + number + '" name="TS_PTable_PVal_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_PVal'] + '">' +
                        '                           <div class="title_PPlan_Container">' +
                        '                                <span class="TS_PTable_PPlan_' + number + '"  contentEditable="true" oninput="TS_Change_Plan_Val(this)" onClick="jQuery(this).focus();">' + data[i]['TS_PTable_PPlan'] + '</span>' +
                        '                                <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_PPlan_Hidden" id="TS_PTable_New_PPlan_' + number + '" name="TS_PTable_PPlan_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_PPlan'] + '">' +
                        '                           </div>' +
                        '                       </div> ' +
                        '                   </div> ' +
                        '                   <div class="title_h3_Container TS_PTable_Header_' + number + '">' +
                        '                       <h3 class="h3_hover TS_PTable_Title_' + number + '" onInput="TS_Change_H3_Val(this)" contentEditable="true" onClick="jQuery(this).focus();">' + data[i]['TS_PTable_TText'] + '</h3>' +
                        '                       <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_TText_Hidden TS_PTable_TText" id="TS_PTable_New_TText_' + number + '" name="TS_PTable_TText_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_TText'] + '">' +
                        '                   </div>' +
                        '                   <div class="TS_PTable_Content_' + number + '"> ' +
                        '                       <div class="relative feuture_cont_' + number + '"> ' +
                        '                           <ul class="TS_PTable_Features_' + number + ' TS_PTable_Features"> ')
                    var TS_PTable_FChek = '';
                     var Total_Soft_PTable_Select_Icon = jQuery('#Total_Soft_PTable_Select_Icon').html();
                    for (j = 0; j < data[i]['TS_PTable_FCount']; j++) {
                        if (TS_PTable_FCheck[j] != '') {
                            TS_PTable_FChek = 'TS_PTable_FCheck';
                        } else {
                            TS_PTable_FChek = '';
                        }
                        jQuery(".TS_PTable_Features_" + number).append('' +
                            '                       <li onMouseOver="TS_PTable_Features_Li(this)" onMouseOut="TS_PTable_Features_Li_Out(this)" class="TS_Li_' + number + '_' + parseInt(parseInt(j) + 1) + '">' +
                            '                                   <div class="Hiddem_Li_Container">' +
                            '                                   <span class="hiddenChangeText"><i class="totalsoft totalsoft-times TS_PTable_FDI TS_PTable_FDI_' + number + '" title="Delete Feature" onClick="TotalSoftPTable_Del_FT(' + number + ', ' + parseInt(parseInt(j) + 1) + ')"></i>     </span>' +
                            '                                   <span class="TS_PTable_FChecked TS_PTable_FCheckedHide TS_PTable_FChecked_' + number + '">' +
                            '                                           <input type="checkbox" onClick="TS_PTable_TS_PTable_FChecked_label(this,' + parseInt(parseInt(i) + 1) + ')"  class="' + TS_PTable_FChek + '" id="TS_PTable_FChecked_' + number + '_' + parseInt(parseInt(j) + 1) + '" name="TS_PTable_FChecked_' + parseInt(parseInt(i) + 1) + '_' + parseInt(parseInt(j) + 1) + '" value="' + parseInt(parseInt(j) + 1) + '">' +
                            '                                           <label class="totalsoft totalsoft-question-circle-o" for="TS_PTable_FChecked_' + number + '_' + parseInt(parseInt(j) + 1) + '"></label>' +
                            '                                   </span>' +
                            '                               </div>' +
                            '                               <div class="feut_container_' + number + ' feut_container">' +
                            '                                   <div class="feut_text_cont">' +
                            '                                       <span onmouseover="TS_Change_Feut_Val_Hover(this)" onmouseout="TS_Change_Feut_Val_Over(this)" class="TS_PTable_FText_' + number + '_' + parseInt(parseInt(j) + 1) + '" oninput="TS_Change_Feut_Val(this)" contentEditable="true" onClick="jQuery(this).focus();">' + TS_PTable_FText[j] + '</span>' +
                            '                                       <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_FText_' + number + ' TS_PTable_Feut_Hidden"  name="TS_PTable_FText_' + parseInt(parseInt(i) + 1) + '_' + parseInt(parseInt(j) + 1) + '" value="' + TS_PTable_FText[j] + '">' +
                            '                                   </div>' +
                            '                                   <div class="feut_icon_cont">' +
                            '                                       <i onClick="TS_Get_Icon_Feuture(this, ' + number + ', ' + parseInt(parseInt(j) + 1) + ')" class="totalsoft  TS_PTable_FIcon_' + number + ' ' + TS_PTable_FChek + ' totalsoft-' + (TS_PTable_FIcon[j]!='none'?TS_PTable_FIcon[j]:'plus-square-o ') + '"></i>' +
                            '                                       <input type="hidden"  class="Total_Soft_PTable_Select  TS_PTable_FIcon_' + number + '_' + parseInt(parseInt(j) + 1) + '" name="TS_PTable_FIcon_' + parseInt(parseInt(i) + 1) + '_' + parseInt(parseInt(j) + 1) + '" value="' + TS_PTable_FIcon[j] + '">' +
                            '                                   </div>' +
                            '                               </div>' +
                            '                         </li>')
                    }
                    jQuery(".feuture_cont_" + number).append('' +
                        ' </ul>' +
                        '                           <input type="hidden"  class="TS_PTable_FCount" id="TS_PTable_FCount_' + number + '" name="TS_PTable_FCount_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_FCount'] + '">' +
                        '                       </div>' +
                        '                   </div>' +
                        '                   <table id="Total_Soft_PTable_Features_Col_' + number + '"></table>' +
                        '                   <table class="TS_Table Total_Soft_PTable_AMMain_Div2_Cols1_FTable1" id="Total_Soft_PTable_Features_' + number + '">' +
                        '                       <tr>' +
                        '                           <td colSpan="2">' +
                        '                               <div class="Total_Soft_PTable_Features_New" onClick="Total_Soft_PTable_Features_New(' + number + ');Total_Soft_PTable_Features_New_Col(' + number + ',' + parseInt(parseInt(i) + 1) + ',5)">' +
                        '                                   <span class="Total_Soft_PTable_Features_New1">' +
                        '                                   <i class="Total_Soft_PTable_Features_New_Icon totalsoft totalsoft-plus-circle" style="margin-right: 5px;"></i>Add New </span>' +
                        '                               </div>' +
                        '                           </td>' +
                        '                       </tr>' +
                        '                   </table>' +
                        '                   <div class="TS_PTable_Div2_' + number + ' relative flex_center">' +
                        '                       <div onclick="TS_Get_Icon_Button(this, ' + number + ')" class="TS_PTable_Button_' + number + '">' +
                        '                            <span class="Button_cont Button_cont_' + number + '">' +
                        '                                <span class="Button_text_cont">' +
                        '                                    <span onmouseover="TS_Change_Feut_Val_Hover(this)" onmouseout="TS_Change_Feut_Val_Over(this)" class="TS_PTable_BText_' + number + '" contentEditable="true">' + data[i]['TS_PTable_BText'] + '</span>' +
                        '                                    <input type="hidden" class="Total_Soft_PTable_Select" id="TS_PTable_BText_' + number + '" name="TS_PTable_BText_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_BText'] + '">' +
                        '                                </span>' +
                        '                                <span class="Button_icon_cont">' +
                        '                                    <i  class="totalsoft   TS_PTable_BIconA_' + number + ' totalsoft-' +( data[i]['TS_PTable_BIcon']!='none'? data[i]['TS_PTable_BIcon']:'plus-square-o ') + '" ></i>' +
                        '                                   <select onchange= "TS_ChangeBut_Icon_Select(this,' + number + ')" class="Total_Soft_PTable_Select  TS_PTable_BIcon TS_PTable_BIcon_' + number + '" onchange="ChangeValueForHiddenBIcon(this)"  name="TS_PTable_BIcon_' + number + '" style="font-family: FontAwesome, Arial;">'+Total_Soft_PTable_Select_Icon+'</select>'+
          
                        '                                    <input type="hidden" class="Total_Soft_PTable_Select" id="TS_PTable_BIcon_' + number + '" name="TS_PTable_BIcon_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_BIcon'] + '">' +
                        '                                </span>' +
                        '                            </span>' +
                        '                            <div class="TS_PTable_Button_Link_' + number + ' TS_PTable_Button_Link">' +
                        '                               <i class="totalsoft  totalsoft-link" ></i>' +
                        '                               <input type="text" class="Total_Soft_PTable_Select TS_PTable_BLink " id="TS_PTable_BLink_' + number + '" name="TS_PTable_BLink_' + parseInt(parseInt(i) + 1) + '" value="' + data[i]['TS_PTable_BLink'] + '">' +
                        '                            </div>' +
                        '                       </div>' +
                        '                   </div>' +
                        '               </div>' +
                        '           </div>' +
                        '           </div>' +
                        '   </div>'
                    )
                    jQuery('.TS_PTable_BIcon_' + number ).val( jQuery('#TS_PTable_BIcon_' + number  ).val());
                    jQuery(".TS_PTable_Container").css("gap", Total_Soft_PTable_M_03 + "px");
                    if (dataSet[i]["TS_PTable_ST_02"] == 'on') {
                        jQuery(".TS_PTable_Container_Col_" + dataSet[i]['id']).attr("style","-webkit-transform:  translate3d(0, 0, 0) scale(1, 1.05); -moz-transform: translate3d(0, 0, 0) scale(1, 1.05);transform:  translate3d(0, 0, 0) scale(1, 1.05);" ).addClass('hover_efect_on');
          
                     
                    } else {

                           jQuery(".TS_PTable_Container_Col_" + dataSet[i]['id']).attr("style",
                            "-webkit-transform:  translate3d(0, 0, 0) scale(1, 1); -moz-transform:  translate3d(0, 0, 0) scale(1, 1); transform:  translate3d(0, 0, 0) scale(1, 1);"
                        ).addClass('hover_efect')

                       
                    }
                    for (var k = 1; k <= parseInt(data[i]['TS_PTable_FCount']); k++) {
                        jQuery("#TS_PTable_FIcon_" + number + "_" + k).html(Total_Soft_PTable_Select_Icon);
                        jQuery("#TS_PTable_FIcon_" + number + "_" + k).val(TS_PTable_FIcon[k - 1]);
                        if (jQuery('#TS_PTable_FChecked_' + number + '_' + k).val() == TS_PTable_FCheck[k - 1]) {
                            jQuery('#TS_PTable_FChecked_' + number + '_' + k).attr('checked', 'checked');
                        }
                    }
                    if (dataSet[i]['TS_PTable_ST_26'] == "after") {
                        jQuery(".feut_container_" + dataSet[i]['id'] + "").attr("style", "flex-direction:row;");
                    } else if (dataSet[i]['TS_PTable_ST_26'] == "before") {
                        jQuery(".feut_container_" + dataSet[i]['id'] + "").attr("style", "flex-direction:row-reverse;");
                    }

                    if (dataSet[i]['TS_PTable_ST_30'] == "after") {
                        jQuery(".Button_cont_" + dataSet[i]['id'] + "").attr("style", "flex-direction:row;");
                    } else if (dataSet[i]['TS_PTable_ST_30'] == "before") {
                        jQuery(".Button_cont_" + dataSet[i]['id'] + "").attr("style", "flex-direction:row-reverse;");
                    }

                    jQuery("#TS_PTable_Col_" + number).append('' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_01" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_01" value="' + dataSet[i]['TS_PTable_ST_01'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_02" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_02" value="' + dataSet[i]['TS_PTable_ST_02'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_03" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_03" value="' + dataSet[i]['TS_PTable_ST_03'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_04" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_04" value="' + dataSet[i]['TS_PTable_ST_04'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_05" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_05" value="' + dataSet[i]['TS_PTable_ST_05'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_06" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_06" value="' + dataSet[i]['TS_PTable_ST_06'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_07" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_07" value="' + dataSet[i]['TS_PTable_ST_07'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_08" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_08" value="' + dataSet[i]['TS_PTable_ST_08'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_09" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_09" value="' + dataSet[i]['TS_PTable_ST_09'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_10" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_10" value="' + dataSet[i]['TS_PTable_ST_10'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_11" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_11" value="' + dataSet[i]['TS_PTable_ST_11'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_12" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_12" value="' + dataSet[i]['TS_PTable_ST_12'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_13" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_13" value="' + dataSet[i]['TS_PTable_ST_13'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_14" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_14" value="' + dataSet[i]['TS_PTable_ST_14'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_15" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_15" value="' + dataSet[i]['TS_PTable_ST_15'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_16" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_16" value="' + dataSet[i]['TS_PTable_ST_16'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_17" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_17" value="' + dataSet[i]['TS_PTable_ST_17'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_18" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_18" value="' + dataSet[i]['TS_PTable_ST_18'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_19" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_19" value="' + dataSet[i]['TS_PTable_ST_19'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_20" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_20" value="' + dataSet[i]['TS_PTable_ST_20'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_21" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_21" value="' + dataSet[i]['TS_PTable_ST_21'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_21_1" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_21_1" value="' + dataSet[i]['TS_PTable_ST_21_1'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_22" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_22" value="' + dataSet[i]['TS_PTable_ST_22'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_23" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_23" value="' + dataSet[i]['TS_PTable_ST_23'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_24" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_24" value="' + dataSet[i]['TS_PTable_ST_24'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_25" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_25" value="' + dataSet[i]['TS_PTable_ST_25'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_26" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_26" value="' + dataSet[i]['TS_PTable_ST_26'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_27" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_27" value="' + dataSet[i]['TS_PTable_ST_27'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_28" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_28" value="' + dataSet[i]['TS_PTable_ST_28'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_29" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_29" value="' + dataSet[i]['TS_PTable_ST_29'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_30" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_30" value="' + dataSet[i]['TS_PTable_ST_30'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_31" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_31" value="' + dataSet[i]['TS_PTable_ST_31'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_32" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_32" value="' + dataSet[i]['TS_PTable_ST_32'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_33" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_33" value="' + dataSet[i]['TS_PTable_ST_33'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_34" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_34" value="' + dataSet[i]['TS_PTable_ST_34'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_35" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_35" value="' + dataSet[i]['TS_PTable_ST_35'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_36" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_36" value="' + dataSet[i]['TS_PTable_ST_36'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_37" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_37" value="' + dataSet[i]['TS_PTable_ST_37'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_38" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_38" value="' + dataSet[i]['TS_PTable_ST_38'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_39" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_39" value="' + dataSet[i]['TS_PTable_ST_39'] + '">' +
                        '<input type="hidden" class="TS_PTable_ST5_' + number + '_40" name="TS_PTable_ST5_' + parseInt(parseInt(i) + 1) + '_40" value="' + dataSet[i]['TS_PTable_ST_40'] + '">'
                    )
                    TS_Ch_Inp_5_Shadow_Type( '.TS_PTable_ST5_' + number + '_06', number); 
                }
            }
            jQuery('.TS_Desctop_View').append('</div>');
            for (var i = 0; i < dataMan.length; i++) {
                jQuery(".Hidden_Top_Set_General").html(
                    '<div class="TS_PTable_TMMain_Set1" id="TS_PTable_Set_T1_' + Total_Soft_PTable_Set_Count + '">' +
                    '<div class="Total_Soft_PTable_TMMain_Div_Sets_Fields">' +
                    '<div class="Total_Soft_PT_AMSetDiv_Content">' +
                    '<div class="TS_PT_Option_Div TS_PT_Option_Div_1_' + Total_Soft_PTable_Set_Count + '" id="Total_Soft_PT_AMSetTable_1_' + Total_Soft_PTable_Set_Count + '_GO">' +
                    '<div class="TS_PT_Option_Div1">' +
                    '<div class="TS_PT_Option_Name">Width</div>' +
                    '<div class="TS_PT_Option_Field">' +
                    '<input type="range" class="TS_PTable_Range TS_PTable_Rangeper" oninput="TS_Ch_Cont_Width(this, Total_Soft_PTable_Set_Count)" name="Total_Soft_PTable_M_01" id="Total_Soft_PTable_M_01" min="30" max="100" value="' + dataMan[i]['Total_Soft_PTable_M_01'] + '">' +
                    '<output class="TS_PTable_Range_Out" name="" id="Total_Soft_PTable_M_01_Output" for="Total_Soft_PTable_M_01"></output>' +
                    '</div>' +
                    '</div>' +
                    '<div class="TS_PT_Option_Div1">' +
                    '<div class="TS_PT_Option_Name">Container Position</div>' +
                    '<div class="TS_PT_Option_Field">' +
                    '<select class="Total_Soft_PTable_Select" id="Total_Soft_PTable_M_02" name="Total_Soft_PTable_M_02" onchange="TS_Ch_Gen_Pos(this, Total_Soft_PTable_Set_Count)">' +
                    '<option value="left"> Left</option>' +
                    '<option value="right"> Right</option>' +
                    '<option value="center"> Center</option>' +
                    '</select>' +
                    '</div>' +
                    '</div>' +
                    '<div class="TS_PT_Option_Div1">' +
                    '<div class="TS_PT_Option_Name">Space Between</div>' +
                    '<div class="TS_PT_Option_Field">' +
                    '<input type="range" class="TS_PTable_Range TS_PTable_Rangepx" oninput="TS_Ch_Cont_Padding(this, Total_Soft_PTable_Set_Count)" name="Total_Soft_PTable_M_03" id="Total_Soft_PTable_M_03" min="0" max="100" value="' + dataMan[i]['Total_Soft_PTable_M_03'] + '">' +
                    '<output class="TS_PTable_Range_Out" name="" id="Total_Soft_PTable_M_03_Output" for="Total_Soft_PTable_M_03"></output>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>');
                TS_PTable_Out();
                jQuery('#Total_Soft_PTable_M_02').val(dataMan[i]['Total_Soft_PTable_M_02']);
                jQuery(".TS_PTable_Container").css("width", dataMan[i]['Total_Soft_PTable_M_01'] + "%");
                if (dataMan[i]['Total_Soft_PTable_M_02'] == 'left') {
                    jQuery(".TS_Desctop_View").css("justify-content", "flex-start");
                } else if (dataMan[i]['Total_Soft_PTable_M_02'] == 'right') {
                    jQuery(".TS_Desctop_View").css("justify-content", "flex-end");
                } else if (dataMan[i]['Total_Soft_PTable_M_02'] == 'center') {
                    jQuery(".TS_Desctop_View").css("justify-content", "center");
                }
                jQuery(".TS_PTable_Container").css("gap", dataMan[i]['Total_Soft_PTable_M_03'] + "px");


            }
            jQuery('.Total_Soft_PTable_AMD2').animate({'opacity': 0}, 500);
            jQuery('.Total_Soft_PTable_AMMTable').animate({'opacity': 0}, 500);
            jQuery('.Total_Soft_PTable_AMOTable').animate({'opacity': 0}, 500);
            if (Create_Action == 'Total_Soft_PTable_Edit1') {
                jQuery('.Total_Soft_PTable_Save').animate({'opacity': 0}, 500);
                jQuery('.Total_Soft_PTable_Update').animate({'opacity': 1}, 500);
            }
            jQuery('#Total_Soft_PTable_ID').html('[Total_Soft_Pricing_Table id="' + PTable_ID + '"]');
            jQuery('#Total_Soft_PTable_TID').html('&lt;?php echo do_shortcode(&#039;[Total_Soft_Pricing_Table id="' + PTable_ID + '"]&#039;);?&gt');

            setTimeout(function () {
                jQuery('.Total_Soft_PTable_AMD2').css('display', 'none');
                jQuery('.Total_Soft_PTable_AMMTable').css('display', 'none');
                jQuery('.Total_Soft_PTable_AMOTable').css('display', 'none');
                if (Create_Action == 'Total_Soft_PTable_Edit1') {
                    jQuery('.Total_Soft_PTable_Save').css('display', 'none');
                    jQuery('.Total_Soft_PTable_Update').css('display', 'block');
                }
                jQuery('.Total_Soft_PTable_AMD3').css('display', 'block');
                jQuery('.Total_Soft_PTable_AMMain_Div').css('display', 'block');
            }, 0)
            setTimeout(function () {
                jQuery('.Total_Soft_PTable_AMD3').animate({'opacity': 1}, 500);
                jQuery('.Total_Soft_PTable_AMMain_Div').animate({'opacity': 1}, 500);
                jQuery('.Total_Soft_PTable_Loading').css('display', 'none');
            }, 0)
            TS_PTable_Out();
               TotalSoftPTable_Edit_Col();
        }
    });


}


function Copy_Shortcode_PT(IDSHORT) {
    var aux = document.createElement("input");
    var code = document.getElementById(IDSHORT).innerHTML;
    code = code.replace("&lt;", "<");
    code = code.replace("&gt;", ">");
    code = code.replace("&#039;", "'");
    code = code.replace("&#039;", "'");
    aux.setAttribute("value", code);
    document.body.appendChild(aux);
    aux.select();
    document.execCommand("copy");
    document.body.removeChild(aux);
}

function Total_Soft_PTable_New_Col_Set() {
    var last_id = parseInt(parseInt(jQuery("#Total_Soft_PTable_New_Col_Last_Id").val()) + 1);
    jQuery("#Total_Soft_PTable_New_Col_Last_Id").val(last_id);
    TS_Col_Last_Id.push(last_id);
    jQuery("#Total_Soft_PTable_New_Col").val(1);
    var Total_Soft_PTable_TImage = jQuery("#Total_Soft_PTable_TImage").val();
    var Total_Soft_PTable_Add_Col = parseInt(parseInt(jQuery("#Total_Soft_PTable_Col_Id").val()) + 1);
    var Total_Soft_PTable_Col_Count = parseInt(parseInt(jQuery("#Total_Soft_PTable_Col_Count").val()) + 1);
    jQuery("#Total_Soft_PTable_Col_Id").val(Total_Soft_PTable_Add_Col);
    jQuery("#Total_Soft_PTable_Col_Count").val(Total_Soft_PTable_Col_Count);
    var Total_Soft_PTable_Select_Icon = jQuery('#Total_Soft_PTable_Select_Icon').html();

    var Update_Id = jQuery('#Total_SoftPTable_Update').val();
    jQuery.ajax({
        type: 'POST',
        url: object.ajaxurl,
        data: {
            action: 'Total_Soft_PTable_Select_Defoult_Theme', 
            foobarUpdate_Id: Update_Id, 
            foobarType: Total_Soft_PTable_Col_Type, 
        },
        beforeSend: function () {
        },
        success: function (response) {

            setTimeout(function () {
                var data = JSON.parse(response);

                New_Col_data = data;
                TotalSoftPTable_Dup_Col(0, 0, 0)


            }, 0)
        }
    });


    jQuery('.Total_Soft_PTable_AMMain_Div2_Cols1_None').animate({opacity: "1"}, 500);
}

TS_Toggle_Li_Opt = (this_Li) => {
    if (flag_edit=='false') {  flag_edit='true';}
    jQuery(this_Li).parents(".TS_Hidden_OPT_Body_Ul").children("li:not(:first-of-type)").each(function () {
        jQuery(this).children(".TS_Hidden_OPT_Body_Div").not(jQuery(this_Li).next()).slideUp();
        if ( jQuery(this).children(".TS_Toggle_Option").children("i").not(jQuery(this_Li).children("i")).hasClass('flip')) {
             jQuery(this).children(".TS_Toggle_Option").children("i").removeClass('flip');
        }
       
    })

    jQuery(this_Li).next().slideToggle();
    jQuery(this_Li).children("i").toggleClass('flip');
};

function getId(idString) {
    const match = idString.match(/TS_PTable_Col_(\d+)/)
    return match ? parseInt(match[1]) : -1;
}

function TotalSoftPTable_ColumnDivdel(){
    
    let items = document.querySelectorAll(".TS_PTable_Container .Total_Soft_PTable_AMMain_Div2_Cols1");
    items.forEach(function(item) {
    item.removeAttribute("draggable");
     if (item.getElementsByClassName("remov_element")[0]) {  item.getElementsByClassName("remov_element")[0].remove();}
});
}

function TotalSoftPTable_ColumnDivSort(This_Id, This_Index) {

    let dragSrcEl = null;
    let st_idx = null;
    let id_x=0;
    let key_id= null;
    let key_num=[];
    let st_tagIndex = [];
    let st_index = null;
function handleDragStart(e) {
    this.style.setProperty("opacity", "0.4", "important");
    //this.classList.add('over');
     let st_tag = document.getElementById(this.id);
     st_idx = st_tag.getElementsByTagName("input");
     dragSrcEl = this;
     e.dataTransfer.effectAllowed = 'move';
     st_index = this.getElementsByClassName("remov_element")[0].value;
}
function handleDragOver(e) {
    if (e.preventDefault) {
        e.preventDefault();
    }
    e.dataTransfer.dropEffect = 'move';
    return false;
}

function handleDragEnter(e) {
    if (this.id!=key_id) {
        for (var i =0; i < key_num.length; i++) {
           
            let tag = document.getElementById(key_num[i]);
            
            document.querySelectorAll('.TS_PTable_Container .over').forEach(n => n.classList.remove('over'));   
           
             
        }
        key_num=[];
    }
     key_id=this.id;
    key_num.push(this.id);
    this.classList.add('over');

  }


  function handleDrop(e) {
    if (e.stopPropagation) {
      e.stopPropagation(); 
    }
    
      if (dragSrcEl.nodeName != 'DIV' || dragSrcEl.id === null || this.id==dragSrcEl.id  ) {
                    e.stopPropagation();
                } else {
       let rem_id = this.getElementsByClassName("remov_element")[0].value;;
      let st_new_tag = document.getElementById(this.id);
      let st_new_idx = st_new_tag.getElementsByTagName("input");
      let st_new_num = st_new_idx[2].value;
      let st_num = st_idx[2].value;
      
   

      let dragEl = this;
     let number=0;



      if(st_num < st_new_num) {
      
        if (st_index>3 && st_index%4==0 && rem_id>2 && rem_id%3==0 && st_index-1== rem_id) {
            dragEl = this;
        }else{
            dragEl = this.nextSibling;
       }
        
      } else{
 
       if (st_index>2 && st_index%3==0 && rem_id>3 && rem_id%4==0 && st_index-1== rem_id) {   dragEl = dragEl.nextSibling; }
            
      }
  
      let list = document.getElementById("TS_PTable_Container");

     list.insertBefore(dragSrcEl, dragEl);
      let cols = document.querySelectorAll(".TS_PTable_Container .Total_Soft_PTable_AMMain_Div2_Cols1");
      if (id_x==0) {
         id_x++;
         cols.forEach(function (ev) {
            let item_index = ev.getElementsByTagName("input");
             item_index[2].value=  st_tagIndex[number];
             number++;
         });
       

         if (st_new_num==st_new_idx[2].value ) {
            dragEl = this;
            let number=0;
            list.insertBefore(dragSrcEl, dragEl);
             cols.forEach(function (ev) {
                let item_index = ev.getElementsByTagName("input");
                 item_index[2].value=  st_tagIndex[number];
                 number++;
             });
          

        }
      }

   

      
    
}
    
    return false;
  }

function handleDragEnd(e) {
 
 
    this.style.opacity = '1';
    items.forEach(function (item) {
        item.removeAttribute("draggable");
         item.classList.remove('over');
         if (item.getElementsByClassName("remov_element")[0]) {  item.getElementsByClassName("remov_element")[0].remove();}
         
        
     
    });
}
let item_x=0;
let items = document.querySelectorAll(".TS_PTable_Container .Total_Soft_PTable_AMMain_Div2_Cols1");
items.forEach(function(item) {
    item_x++;
  let new_element=document.createElement("INPUT");
  new_element.setAttribute("type", "hidden");
  new_element.value =item_x;
  new_element.classList.add('remov_element')
  item.append(new_element);
  item.draggable = true;
  let itemarr_index = item.getElementsByTagName("input");
   st_tagIndex.push(itemarr_index[2].value);
    item.addEventListener('dragstart', handleDragStart, false);
    item.addEventListener('dragenter', handleDragEnter, false);
    item.addEventListener('dragover', handleDragOver, false);
    item.addEventListener('drop', handleDrop, false);
    item.addEventListener('dragend', handleDragEnd, false);
});

}

function TotalSoftPTable_Del_Col(Col_index) {
    var TS_PTable_Col = Col_index;
    jQuery('.TS_PTable_Remove_Cols_Fixed').fadeIn();
    jQuery('.TS_PTable_Remove_Cols_Abs').fadeIn();
    jQuery('.TS_PTable_Remove_Cols_Rel_No').click(function () {
        jQuery('.TS_PTable_Remove_Cols_Fixed').fadeOut();
        jQuery('.TS_PTable_Remove_Cols_Abs').fadeOut();
      
        TS_PTable_Col = null;
    })
    jQuery('.TS_PTable_Remove_Cols_Rel_Yes').click(function () {
        if (TS_PTable_Col != null) {
            arr.push(Col_index);
            jQuery('.TS_PTable_Remove_Cols_Fixed').fadeOut();
            jQuery('.TS_PTable_Remove_Cols_Abs').fadeOut();
             if (jQuery('#TS_PTable_Col_' + Col_index).hasClass('TS_PTable_Container_Col_Copy')) {
                var Total_Soft_PTable_Add_Set = parseInt(parseInt(jQuery("#Total_Soft_PTable_Add_Set").val()) - 1);
                jQuery("#Total_Soft_PTable_Add_Set").val(Total_Soft_PTable_Add_Set);
            }
            jQuery('#TS_PTable_Col_' + Col_index).remove();
            var Total_Soft_PTable_Col_Id = parseInt(parseInt(jQuery('.Total_Soft_PTable_Col_Id').val()) - 1);
            var Total_Soft_PTable_Cols_Id = parseInt(parseInt(jQuery('.Total_Soft_PTable_Cols_Id').val()) - 1);
            jQuery('.Total_Soft_PTable_Col_Id').val(Total_Soft_PTable_Col_Id);
            jQuery('.Total_Soft_PTable_Cols_Id').val(Total_Soft_PTable_Cols_Id);
            jQuery('#Total_Soft_PTable_Col_Del').val(arr);
            var Total_Soft_PTable_Col_Sel_Count = parseInt(parseInt(jQuery("#Total_Soft_PTable_Col_Sel_Count").val()) - 1);
            jQuery("#Total_Soft_PTable_Col_Sel_Count").val(Total_Soft_PTable_Col_Sel_Count);
           
        }
          TotalSoftPTable_Close_Dragdown();
        flag_edit_col = 'false';
       flag_edit='false';
        TS_PTable_Col = null;
        
    })
}

TotalSoftPTable_Edit_Col = () => {
  
    jQuery(".TS_left_Side_Div").animate({width: '78%'});
    jQuery("#TS_Hidden_Opt").animate({'opacity':'1',width: '22%'});
    jQuery(".Ts_Opt_Close_Button").fadeIn();
    jQuery(".TS_Toggle_Option").fadeIn().css("display", "flex");
}

TotalSoftPTable_Col_Dragdown = () => {
    if (flag_edit_col == 'false') {
        flag_edit_col = 'true';
        jQuery(".TS_Hidden_OPT_Body_Ul li:not(:first-of-type)").css('display', 'block');
 
}
}

TotalSoftPTable_Close_Option = () => {
    flag_edit='false';
    jQuery(".TS_left_Side_Div").animate({width: '100%'});
    jQuery("#TS_Hidden_Opt").animate({width: '0%',opacity:0});
    jQuery(".Ts_Opt_Close_Button").fadeOut();
      jQuery(".TS_Hidden_OPT_Body_Ul li:not(:first-of-type)").fadeOut();
    jQuery(".TS_Toggle_Option").fadeOut().css("display", "flex");
}

TotalSoftPTable_Close_Dragdown = () => {
     flag_edit_col = 'false';
       flag_edit='false';
        jQuery(".TS_Hidden_OPT_Body_Ul li:not(:first-of-type)").css('display', 'none');
    

    
}

function Total_Soft_PTable_Features_New(Col_index) {
    var Total_Soft_PTable_Features = jQuery('#TS_PTable_FCount_' + Col_index).val();
    if (typeof Total_Soft_PTable_Features === "undefined") {
        return;
    }
    var Total_Soft_PTable_Features_N = parseInt(parseInt(Total_Soft_PTable_Features) + 1);
    var Total_Soft_PTable_Select_Icon = jQuery('#Total_Soft_PTable_Select_Icon').html();
    jQuery("#TS_PTable_FIcon_" + Col_index + "_" + Total_Soft_PTable_Features_N).html(Total_Soft_PTable_Select_Icon);
    jQuery("#Total_Soft_PTable_Col_Count").val(Col_index);
    jQuery('#Total_Soft_PTable_Col_Feut_Id_' + Col_index).val(Total_Soft_PTable_Features_N);
    jQuery('#TS_PTable_FCount_' + Col_index).val(Total_Soft_PTable_Features_N);
    jQuery("#Total_Soft_PTable_Col_Val_Id").val(Col_index);
    jQuery('.Total_Soft_PTable_Features_None').animate({opacity: "1"}, 500);
}

function Total_Soft_PTable_Features_New_Col(Col_index,idx,type) {
    var Total_Soft_PTable_Features_N = jQuery('#TS_PTable_FCount_' + Col_index).val();
    var Total_Soft_PTable_Select_Icon = jQuery('#Total_Soft_PTable_Select_Icon').html();
    jQuery("#TS_PTable_FIcon_" + Col_index + "_" + Total_Soft_PTable_Features_N).html(Total_Soft_PTable_Select_Icon);
    jQuery('#Total_Soft_PTable_Col_Feut_Id_' + Col_index).val(Total_Soft_PTable_Features_N);
    let FCheck_key =jQuery('.TS_PTable_Features_' + Col_index).children().last().find('.TS_PTable_FCheck').length;
    
    jQuery('.TS_PTable_Features_' + Col_index).append('' +
        '<li onmouseover="TS_PTable_Features_Li(this)" onmouseout="TS_PTable_Features_Li_Out(this)">' +
        '   <div class="Hiddem_Li_Container">' +
        '       <span class="hiddenChangeText">' +
        '           <i class="totalsoft totalsoft-times TS_PTable_FDI TS_PTable_FDI_' + Total_Soft_PTable_Features_N + '" title="Delete Feature" onclick="TotalSoftPTable_Del_FT(' + Col_index + ', ' + Total_Soft_PTable_Features_N + ')"></i>' +
        '       </span>' +
        '       <span class="TS_PTable_FChecked TS_PTable_FChecked_' + Total_Soft_PTable_Features_N + '">' +
        '           <input onClick="TS_PTable_TS_PTable_FChecked_label(this,' + idx + ')" type="checkbox" id="TS_PTable_FChecked_' + Col_index + '_' + Total_Soft_PTable_Features_N + '" name="TS_PTable_FChecked_' + Col_index + '_' + Total_Soft_PTable_Features_N + '" value="' + Total_Soft_PTable_Features_N + '">' +
        '           <label class="totalsoft totalsoft-question-circle-o" for="TS_PTable_FChecked_' + Col_index + '_' + Total_Soft_PTable_Features_N + '"></label>' +
        '       </span>' +
        '   </div>' +
        '   <table id="Total_Soft_PTable_Features_Col_' + Col_index + '_' + Total_Soft_PTable_Features_N+' " >' +
        '       <tr class="Total_Soft_PTable_Features_None">' +
        '           <td>' +
        '               <input onClick="TS_Get_text_Feuture(this, ' + Col_index + ', ' + Total_Soft_PTable_Features_N + ')" onChange="TS_PTable_Features_New_Li(this)" type="text" class="Total_Soft_PTable_Select TS_PTable_FText_' + Total_Soft_PTable_Features_N + '" id="TS_PTable_FText_' + Col_index + '_' + Total_Soft_PTable_Features_N + '" name="TS_PTable_FText_' + Col_index + '_' + Total_Soft_PTable_Features_N + '" value="">' +
        '               <span style="display:none;" onmouseover="TS_Change_Feut_Val_Hover(this)" onmouseout="TS_Change_Feut_Val_Over(this)" class="TS_PTable_FText_' + Col_index + '_' + Total_Soft_PTable_Features_N + ' Feut_Hover_new_' + Col_index + '_' + Total_Soft_PTable_Features_N + '" oninput="TS_Change_Feut_Val(this)" contenteditable="true" onclick="jQuery(this).focus();"></span>' +
        '           </td>' +
        '           <td>' +
        '               <i onClick="TS_Get_Icon_Feuture(this, ' + Col_index + ', ' + Total_Soft_PTable_Features_N + ')" class="totalsoft  TS_PTable_FIcon_' + Col_index + '  totalsoft- "></i>' +
        '               <select onchange="TS_ChangeFeut_Icon_Select(this)" class="Total_Soft_PTable_Select TS_PTable_FIcon_' + Total_Soft_PTable_Features_N + '" style="font-family: FontAwesome, Arial;" id="TS_PTable_FIcon_' + Col_index + '_' + Total_Soft_PTable_Features_N + '" name="TS_PTable_FIcon_' + Col_index + '_' + Total_Soft_PTable_Features_N + '">' + Total_Soft_PTable_Select_Icon + '</select>' +
        '               <input type="hidden"  class="Total_Soft_PTable_Select TS_PTable_FIcon_' + Col_index + '_' + Total_Soft_PTable_Features_N + '" name="TS_PTable_FIcon_' + Col_index + '_' + Total_Soft_PTable_Features_N + '" value="">' +
        '           </td>' +

        '       </tr>' +
        '   </table>' +
        '<li>'
    );
    if (FCheck_key==0) {
     setTimeout(function () {    
     let x=25;
        if (type == 2) {
            x=24;
        } else if (type == 3) {
            x=27;
        } else if (type == 4) {
            x=22;
        } else if (type == 5) {
            x=24;
        }

        jQuery('#TS_PTable_FChecked_' + Col_index + '_' + Total_Soft_PTable_Features_N ).attr('checked', 'checked').addClass('TS_PTable_FCheck');
       jQuery('.TS_PTable_Features_' + Col_index+' li').last().find( 'table i').css({ "color": jQuery("input[name*='TS_PTable_ST" + type  + "_" +idx + "_"+x+"']").val()});
    
     }, 0)
    }
    jQuery('.TS_PTable_Features_' + Col_index + ' li').each(function () {
        if (jQuery(this).is(':empty')) {
            jQuery(this).remove();
        }
    })

    function TS_Get_Icon_Feuture(This_Element, This_ID, This_Index) {
        jQuery(".hide_Select").remove();
        event.stopPropagation();
        jQuery(document).click(function (event) {
            var container = jQuery('.TS_PTable_FText_' + Total_Soft_PTable_Features_N);
            var container1 = jQuery(This_Element);
            if (!container1.is(event.target) && container1.has(event.target).length === 0 && !container.is(event.target) && container.has(event.target).length === 0) {
              
                container.hide();
            }
        });
    }

    function TS_ChangeFeut_Icon_Select(This_Element) {
        var splited_class = jQuery(This_Element).prev("i").attr("class").split("totalsoft-");
        splited_class.splice(splited_class.length - 1, 1, jQuery(This_Element).val()!='none'?jQuery(This_Element).val():'plus-square-o');
        var res = splited_class.join("totalsoft-");
        jQuery(This_Element).prev("i").attr("class", res);
        jQuery(This_Element).next().val(jQuery(This_Element).val());
        jQuery(This_Element).hide();
    }

    jQuery('.Total_Soft_PTable_Features_None').animate({opacity: "1"}, 500);
}

Total_Soft_PTable_New_Features_New = (Col_index) => {
    var Total_Soft_PTable_New_Features = jQuery('#TS_PTable_New_FCount_' + Col_index).val();
    var Total_Soft_PTable_New_Features_N = parseInt(parseInt(Total_Soft_PTable_New_Features) + 1);
    var Total_Soft_PTable_Select_Icon = jQuery('#Total_Soft_PTable_Select_Icon').html();
    jQuery("#TS_PTable_FIcon_" + Col_index + "_" + Total_Soft_PTable_New_Features).html(Total_Soft_PTable_Select_Icon);
    jQuery("#Total_Soft_PTable_Col_Count").val(Col_index);
    jQuery('#TS_PTable_New_FCount_' + Col_index).val(Total_Soft_PTable_New_Features_N);
    jQuery('.Total_Soft_PTable_Features_None').animate({opacity: "1"}, 500);
}

Total_Soft_PTable_New_Features_New_Col = (Col_index, Col_Count, Feut_index) => {
    var Total_Soft_PTable_New_Features_N = jQuery('#TS_PTable_New_FCount_' + Col_index).val();
    var Total_Soft_PTable_Select_Icon = jQuery('#Total_Soft_PTable_Select_Icon').html();
    jQuery("#TS_PTable_New_FIcon_" + Col_index + "_" + Total_Soft_PTable_New_Features_N).html(Total_Soft_PTable_Select_Icon);
    jQuery('.TS_PTable_New_Features_' + Col_index).append('' +
        '<li>' +
        '<table id="Total_Soft_PTable_Features_Col_' + Col_index + '" >' +
        '<tr class="Total_Soft_PTable_Features_None">' +
        '<td>' +
        '<select class="Total_Soft_PTable_Select TS_PTable_FIcon_' + Total_Soft_PTable_New_Features_N + '" style="font-family: FontAwesome, Arial;" id="TS_PTable_New_FIcon_' + Col_index + '_' + Total_Soft_PTable_New_Features_N + '" name="TS_PTable_New_FIcon_' + Col_index + '_' + Total_Soft_PTable_New_Features_N + '">' + Total_Soft_PTable_Select_Icon + '</select>' +
        '<span class="TS_PTable_FChecked TS_PTable_FChecked_' + Total_Soft_PTable_New_Features_N + '">' +
        '<input type="checkbox" id="TS_PTable_New_FChecked_' + Col_index + '_' + Total_Soft_PTable_New_Features_N + '" name="TS_PTable_New_FChecked_' + Col_index + '_' + Total_Soft_PTable_New_Features_N + '" value="' + Total_Soft_PTable_New_Features_N + '">' +
        '<label class="totalsoft totalsoft-question-circle-o" for="TS_PTable_New_FChecked_' + Col_index + '_' + Total_Soft_PTable_New_Features_N + '"></label>' +
        '</span>' +
        '</td>' +
        '<td>' +
        '<input type="text" class="Total_Soft_PTable_Select TS_PTable_FText_' + Total_Soft_PTable_New_Features_N + '" id="TS_PTable_New_Hid_FText_' + Col_index + '_' + Total_Soft_PTable_New_Features_N + '" name="TS_PTable_New_FText_' + Col_index + '_' + Total_Soft_PTable_New_Features_N + '" value="">' +
        '<i class="totalsoft totalsoft-times TS_PTable_FDI TS_PTable_FDI_' + Total_Soft_PTable_New_Features_N + '" title="Delete Feature" onclick="TotalSoftPTable_Del_FT(' + Col_index + ', ' + Total_Soft_PTable_New_Features_N + ')"></i>' +
        '</td>' +
        '</tr>' +
        '</table>' +
        '<li>'
    );
    jQuery('.TS_PTable_New_Features_' + Col_index + ' li').each(function () {
        if (jQuery(this).is(':empty')) {
            jQuery(this).remove();
        }
    })
    if (jQuery("#Total_Soft_PTable_Dup").val() == 1) {
        var Total_Soft_PTable_Cols_Count_Var = jQuery("#Total_Soft_PTable_Add_Set").val();

        var Feut_indexes = jQuery('#TS_PTable_FCount_' + Col_Count).val();
        var Feut_index = parseInt(parseInt(Feut_indexes) + 1);
        jQuery('#TS_PTable_FCount_' + Col_Count).val(Feut_index)
        Feut_index = jQuery('#TS_PTable_FCount_' + Col_Count).val();
        jQuery('#TS_PTable_Col_' + Col_index).append('' +
            '<input type="text" style="display: block" class="TS_PTable_New_Hid_FText_' + Col_Count + '_' + Feut_index + '" name="TS_PTable_New_FText_' + Total_Soft_PTable_Cols_Count_Var + '_' + Feut_index + '" value="">' +
            '<input type="text" style="display: block" class="TS_PTable_New_Hid_FCheck_' + Col_Count + '_' + Feut_index + '" name="TS_PTable_New_FChecked_' + Total_Soft_PTable_Cols_Count_Var + '_' + Feut_index + '" value="">' +
            '<input type="text" style="display: block" class="TS_PTable_New_Hid_FIcon_' + Col_Count + '_' + Feut_index + '" name="TS_PTable_New_FIcon_' + Total_Soft_PTable_Cols_Count_Var + '_' + Feut_index + '" value="">');
        jQuery(".TS_PTable_FIcon_" + Total_Soft_PTable_New_Features_N).attr("oninput", "TS_change_Value_Dup_Inp_icon(" + Total_Soft_PTable_New_Features_N + "," + Col_Count + "," + Feut_index + ")");
        jQuery("#TS_PTable_New_FChecked_" + Col_index + "_" + Total_Soft_PTable_New_Features_N).attr("oninput", "TS_change_Value_Dup_Inp_Check(" + Total_Soft_PTable_New_Features_N + "," + Col_Count + "," + Feut_index + "," + Col_index + ")");
        jQuery("#TS_PTable_New_Hid_FText_" + Col_index + "_" + Total_Soft_PTable_New_Features_N).attr("oninput", "TS_change_Value_Dup_Inp_Text(" + Total_Soft_PTable_New_Features_N + "," + Col_Count + "," + Feut_index + "," + Col_index + ")");
    }
    jQuery("#Total_Soft_PTable_Col_Val_Id").val(Col_index);
    jQuery('.Total_Soft_PTable_Features_None').animate({opacity: "1"}, 500);
}

function TS_change_Value_Dup_Inp_icon(This_var, Col_Count, Feut_index) {
    jQuery(".TS_PTable_New_Hid_FIcon_" + Col_Count + '_' + Feut_index).val(jQuery(".TS_PTable_FIcon_" + This_var).val());
}

function TS_change_Value_Dup_Inp_Check(This_var, Col_Count, Feut_index, Col_index) {
    jQuery(".TS_PTable_New_Hid_FCheck_" + Col_Count + '_' + Feut_index).val(jQuery("#TS_PTable_New_FChecked_" + Col_index + "_" + This_var).val());
}

function TS_change_Value_Dup_Inp_Text(This_var, Col_Count, Feut_index, Col_index) {
    jQuery(".TS_PTable_New_Hid_FText_" + Col_Count + '_' + Feut_index).val(jQuery("#TS_PTable_New_Hid_FText_" + Col_index + "_" + This_var).val());
}

function TotalSoftPTable_Del_FT(Col_index, Fea_Num) {
    var TS_PTable_Col = Col_index;
    jQuery('.TS_PTable_Remove_Cols_Fixed').fadeIn();
    jQuery('.TS_PTable_Remove_Cols_Abs').fadeIn();
    jQuery('.TS_PTable_Remove_Cols_Rel_No').click(function () {
        jQuery('.TS_PTable_Remove_Cols_Fixed').fadeOut();
        jQuery('.TS_PTable_Remove_Cols_Abs').fadeOut();
        TS_PTable_Col = null;
    })
    jQuery('.TS_PTable_Remove_Cols_Rel_Yes').click(function () {
        if (TS_PTable_Col != null) {
            jQuery('.TS_PTable_Remove_Cols_Fixed').fadeOut();
            jQuery('.TS_PTable_Remove_Cols_Abs').fadeOut();
            var TS_PTable_FCount = parseInt(parseInt(jQuery('#TS_PTable_FCount_' + Col_index).val()) - 1);
            if (jQuery("#Total_Soft_PTable_Dup").val() == 1) {
                var TS_PTable_New_FCount = parseInt(parseInt(jQuery('#TS_PTable_New_FCount_' + Col_index).val()) - 1);
                jQuery('#TS_PTable_New_FCount_' + Col_index).val(TS_PTable_New_FCount);
                jQuery('#Total_Soft_PTable_Col_Feut_Id_' + Col_index).val(TS_PTable_New_FCount);
                for (var i = 0; i < TS_PTable_New_FCount; i++) {
                    jQuery('.TS_PTable_New_Features_' + TS_PTable_Col + ' li:nth-child(' + parseInt(parseInt(i) + 1) + ')').find('select').removeClass('TS_PTable_FIcon_' + parseInt(parseInt(i) + 1));
                    jQuery('.TS_PTable_New_Features_' + TS_PTable_Col + ' li:nth-child(' + parseInt(parseInt(i) + 1) + ')').find('span').removeClass('TS_PTable_FChecked_' + parseInt(parseInt(i) + 1));
                    jQuery('.TS_PTable_New_Features_' + TS_PTable_Col + ' li:nth-child(' + parseInt(parseInt(i) + 1) + ')').find('input[type=text]').removeClass('TS_PTable_FText_' + parseInt(parseInt(i) + 1));
                    jQuery('.TS_PTable_New_Features_' + TS_PTable_Col + ' li:nth-child(' + parseInt(parseInt(i) + 1) + ')').find('i').removeClass('TS_PTable_FDI_' + parseInt(parseInt(i) + 1));
                }
                jQuery('.TS_PTable_New_Features_' + Col_index + ' li:nth-child(' + Fea_Num + ')').remove();

                for (var i = 1; i <= TS_PTable_New_FCount; i++) {
                    jQuery('.TS_PTable_New_Features_' + Col_index + ' li:nth-child(' + i + ')').find('select').attr('id', 'TS_PTable_FIcon_' + Col_index + '_' + i).attr('name', 'TS_PTable_FIcon_' + Col_index + '_' + i).addClass('TS_PTable_FIcon_' + i);
                    jQuery('.TS_PTable_New_Features_' + Col_index + ' li:nth-child(' + i + ')').find('span input[type=checkbox]').attr('id', 'TS_PTable_FChecked_' + Col_index + '_' + i).attr('name', 'TS_PTable_FChecked_' + Col_index + '_' + i).val(i);
                    jQuery('.TS_PTable_New_Features_' + Col_index + ' li:nth-child(' + i + ')').find('span label').attr('for', 'TS_PTable_FChecked_' + Col_index + '_' + i);
                    jQuery('.TS_PTable_New_Features_' + Col_index + ' li:nth-child(' + i + ')').find('span').addClass('TS_PTable_FChecked_' + i);
                    jQuery('.TS_PTable_New_Features_' + Col_index + ' li:nth-child(' + i + ')').find('input[type=text]').attr('id', 'TS_PTable_FText_' + Col_index + '_' + i).attr('name', 'TS_PTable_FText_' + Col_index + '_' + i).addClass('TS_PTable_FText_' + i);
                    jQuery('.TS_PTable_New_Features_' + Col_index + ' li:nth-child(' + i + ')').find('i').attr('onclick', 'TotalSoftPTable_Del_FT(' + Col_index + ',' + i + ')').addClass('TS_PTable_FDI_' + i);
                }
            } else {
                jQuery('#TS_PTable_FCount_' + Col_index).val(TS_PTable_FCount);
                jQuery('#Total_Soft_PTable_Col_Feut_Id_' + Col_index).val(TS_PTable_FCount);

                for (var i = 0; i < TS_PTable_FCount; i++) {
                    jQuery('.TS_PTable_Features_' + Col_index + ' li:nth-child(' + parseInt(parseInt(i) + 1) + ')').find('select').removeClass('TS_PTable_FIcon_' + parseInt(parseInt(i) + 1));
                    jQuery('.TS_PTable_Features_' + Col_index + ' li:nth-child(' + parseInt(parseInt(i) + 1) + ')').find('span').removeClass('TS_PTable_FChecked_' + parseInt(parseInt(i) + 1));
                    jQuery('.TS_PTable_Features_' + Col_index + ' li:nth-child(' + parseInt(parseInt(i) + 1) + ')').find('input[type=text]').removeClass('TS_PTable_FText_' + parseInt(parseInt(i) + 1));
                    jQuery('.TS_PTable_Features_' + Col_index + ' li:nth-child(' + parseInt(parseInt(i) + 1) + ')').find('i').removeClass('TS_PTable_FDI_' + parseInt(parseInt(i) + 1));
                }
                jQuery('.TS_PTable_Features_' + Col_index + ' li:nth-child(' + Fea_Num + ')').remove();

                for (var i = 1; i <= TS_PTable_FCount; i++) {
                    jQuery('.TS_PTable_Features_' + Col_index + ' li:nth-child(' + i + ')').find('select').attr('id', 'TS_PTable_FIcon_' + Col_index + '_' + i).attr('name', 'TS_PTable_FIcon_' + Col_index + '_' + i).addClass('TS_PTable_FIcon_' + i);
                    jQuery('.TS_PTable_Features_' + Col_index + ' li:nth-child(' + i + ')').find('span input[type=checkbox]').attr('id', 'TS_PTable_FChecked_' + Col_index + '_' + i).attr('name', 'TS_PTable_FChecked_' + Col_index + '_' + i).val(i);
                    jQuery('.TS_PTable_Features_' + Col_index + ' li:nth-child(' + i + ')').find('span label').attr('for', 'TS_PTable_FChecked_' + Col_index + '_' + i);
                    jQuery('.TS_PTable_Features_' + Col_index + ' li:nth-child(' + i + ')').find('span').addClass('TS_PTable_FChecked_' + i);
                    jQuery('.TS_PTable_Features_' + Col_index + ' li:nth-child(' + i + ')').find('input[type=text]').attr('id', 'TS_PTable_FText_' + Col_index + '_' + i).attr('name', 'TS_PTable_FText_' + Col_index + '_' + i).addClass('TS_PTable_FText_' + i);
                    jQuery('.TS_PTable_Features_' + Col_index + ' li:nth-child(' + i + ')').find('i').attr('onclick', 'TotalSoftPTable_Del_FT(' + Col_index + ',' + i + ')').addClass('TS_PTable_FDI_' + i);
                }
            }
        }
        TS_PTable_Col = null;
    })
}

function TotalSoftPTable_Clone(PTable_ID) {
    jQuery.ajax({
        type: 'POST',
        url: object.ajaxurl,
        data: {
            action: 'Total_Soft_PTable_Clone',
            foobar: PTable_ID, 
        },
        beforeSend: function () {
            jQuery('.Total_Soft_PTable_Loading').css('display', 'block');
        },
        success: function (response) {
            var data = JSON.parse(response);
             
             let num_i = jQuery('.Total_Soft_PTable_AMOTable').find('tr').length+1;
             var Total_Soft_PTable_T = data['Total_Soft_PTable_Them'].replace("type", "Theme ");
                jQuery(".Total_Soft_PTable_AMOTable").append(
                    '   <tr id="Total_Soft_PTable_AMOTable_tr_' + data["id"] + '">' +
                    '     <td>' + num_i + '</td>' +
                    '     <td>' + data["Total_Soft_PTable_Title"] + '</td>' +
                    '     <td>Pricing ' + Total_Soft_PTable_T + '</td>' +
                    '     <td>' + data["Total_Soft_PTable_Cols_Count"] + '</td>' +
                    '     <td><i class="totalsoft totalsoft-file-text" onclick="TotalSoftPTable_Clone(' + data["id"] + ')"></i></td>' +
                    '     <td><i class="totalsoft totalsoft-pencil"  onclick=" Total_Soft_PTable_AMD2_But_Edit(' + data["id"] + ',2)"></i></td>' +
                    '     <td>' +
                    '       <i class="totalsoft totalsoft-trash" onclick="TotalSoftPTable_Del(' + data["id"] + ')"></i> ' +
                    '       <span class="Total_Soft_PTable_Del_Span">' +
                    '           <i class="Total_Soft_PTable_Del_Span_Yes totalsoft totalsoft-check" onclick="TotalSoftPTable_Del_Yes(' + data["id"] + ')"></i>' +
                    '           <i class="Total_Soft_PTable_Del_Span_No totalsoft totalsoft-times" onclick="TotalSoftPTable_Del_No(' + data["id"] + ')"></i>' +
                    '       </span>' +
                    '     </td>' +
                    ' </tr>'
                );
             jQuery('.Total_Soft_PTable_Loading').css('display', 'none');
        }
    });
}

function TotalSoftPTable_Edit(PTable_ID) {
    jQuery('#Total_SoftPTable_Update').val(PTable_ID);

    jQuery.ajax({
        type: 'POST',
        url: object.ajaxurl,
        data: {
            action: 'Total_Soft_PTable_Edit',
        },
        beforeSend: function () {
            jQuery('.Total_Soft_PTable_Loading').css('display', 'block');
        },
        success: function (response) {
            jQuery('.Total_Soft_PTable_Loading').css('display', 'none');
            var data = JSON.parse(response);
            var z = [];
            for (var i = 0; i < data.length; i++) {
                if (data[i]['id'] == PTable_ID) {
                    z.push(data[i]);
                }
            }
            jQuery(".Hidden_Top_Set_General").html(
                '<div class="TS_PTable_TMMain_Set1" id="TS_PTable_Set_T1_' + Total_Soft_PTable_Set_Count + '">' +
                '<input type="text" style="display:none;" class="Total_Soft_PTable_Select" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_ID" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_ID" value="' + Total_Soft_PTable_Set_Count + '">' +
                '<input type="text" style="display:none;" class="Total_Soft_PTable_Select" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_00" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_00" value="Theme_' + Total_Soft_PTable_Set_Count + '">' +
                '<div class="Total_Soft_PTable_TMMain_Div_Sets_Fields">' +
                '<div class="Total_Soft_PT_AMSetDiv_Content">' +
                '<div class="TS_PT_Option_Div TS_PT_Option_Div_1_' + Total_Soft_PTable_Set_Count + '" id="Total_Soft_PT_AMSetTable_1_' + Total_Soft_PTable_Set_Count + '_GO">' +
                '<div class="TS_PT_Option_Div1">' +
                '<div class="TS_PT_Option_Name">Width</div>' +
                '<div class="TS_PT_Option_Field">' +
                '<input type="range" class="TS_PTable_Range TS_PTable_Rangeper" oninput="TS_Ch_Cont_Width(this, Total_Soft_PTable_Set_Count)" name="Total_Soft_PTable_M_01" id="Total_Soft_PTable_M_01" min="30" max="100" value="' + z[0]['Total_Soft_PTable_M_01'] + '">' +
                '<output class="TS_PTable_Range_Out" name="" id="Total_Soft_PTable_M_01_Output" for="Total_Soft_PTable_M_01"></output>' +
                '</div>' +
                '</div>' +
                '<div class="TS_PT_Option_Div1">' +
                '<div class="TS_PT_Option_Name">Container Position</div>' +
                '<div class="TS_PT_Option_Field">' +
                '<select class="Total_Soft_PTable_Select" id="Total_Soft_PTable_M_02" name="Total_Soft_PTable_M_02" onchange="TS_Ch_Gen_Pos(this, Total_Soft_PTable_Set_Count)">' +
                '<option value="left"> Left</option>' +
                '<option value="right"> Right</option>' +
                '<option value="center"> Center</option>' +
                '</select>' +
                '</div>' +
                '</div>' +
                '<div class="TS_PT_Option_Div1">' +
                '<div class="TS_PT_Option_Name">Space Between</div>' +
                '<div class="TS_PT_Option_Field">' +
                '<input type="range" class="TS_PTable_Range TS_PTable_Rangepx" oninput="TS_Ch_Cont_Padding(this, Total_Soft_PTable_Set_Count)" name="Total_Soft_PTable_M_03" id="Total_Soft_PTable_M_03" min="0" max="100" value="' + z[0]['Total_Soft_PTable_M_03'] + '">' +
                '<output class="TS_PTable_Range_Out" name="" id="Total_Soft_PTable_M_03_Output" for="Total_Soft_PTable_M_03"></output>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>');
            jQuery('#Total_Soft_PTable_Title').val(z[0]['Total_Soft_PTable_Title']);
            jQuery('#Total_Soft_PTable_Them').val(z[0]['Total_Soft_PTable_Them']);
            jQuery('#Total_Soft_PTable_M_02').val(z[0]['Total_Soft_PTable_M_02']);
            TS_PTable_Out();
        }
    });

    jQuery.ajax({
        type: 'POST',
        url: object.ajaxurl,
        data: {
            action: 'Total_Soft_PTable_Edit1',
            foobar: PTable_ID, 
        },
        beforeSend: function () {
        },
        success: function (response) {
            var data = JSON.parse(response);
            setTimeout(function () {
                for (var i = 0; i < data.length; i++) {
                    var number = parseInt(i) + 1;
                    jQuery('#Total_Soft_PTable_Col_Sel_Count').val(data.length);
                    if (data[i]['TS_PTable_TType'] == Total_Soft_PTable_Col_Type) {
                        jQuery("#Total_Soft_PTable_Col_Count").val(data.length);
                    }

                    jQuery('#TS_PTable_TSetting_' + number).val(data[i]['TS_PTable_TSetting']);
                    jQuery('#TS_PTable_PCur_' + number).val(data[i]['TS_PTable_PCur']);
                    jQuery('#TS_PTable_PVal_' + number).val(data[i]['TS_PTable_PVal']);
                    jQuery('#TS_PTable_PPlan_' + number).val(data[i]['TS_PTable_PPlan']);
                    jQuery('#TS_PTable_BLink_' + number).val(data[i]['TS_PTable_BLink']);
                    var TS_PTable_FIcon = data[i]['TS_PTable_FIcon'].split('TSPTFI');
                    var TS_PTable_FCheck = data[i]['TS_PTable_C_01'].split('TSPTFC');
                    for (var k = 1; k <= parseInt(data[i]['TS_PTable_FCount']); k++) {
                        Total_Soft_PTable_Features_New(number);
                        jQuery('#TS_PTable_FIcon_' + number + '_' + k).val(TS_PTable_FIcon[k - 1]);
                        if (jQuery('#TS_PTable_FChecked_' + number + '_' + k).val() == TS_PTable_FCheck[k - 1]) {
                            jQuery('#TS_PTable_FChecked_' + number + '_' + k).attr('checked', 'checked');
                        }
                    }
                }
            }, 0)

            jQuery('.Total_Soft_PTable_AMD2').animate({'opacity': 0}, 500);
            jQuery('.Total_Soft_PTable_AMMTable').animate({'opacity': 0}, 500);
            jQuery('.Total_Soft_PTable_AMOTable').animate({'opacity': 0}, 500);
            jQuery('.Total_Soft_PTable_Save').animate({'opacity': 0}, 500);
            jQuery('.Total_Soft_PTable_Update').animate({'opacity': 1}, 500);
            jQuery('#Total_Soft_PTable_ID').html('[Total_Soft_Pricing_Table id="' + PTable_ID + '"]');
            jQuery('#Total_Soft_PTable_TID').html('&lt;?php echo do_shortcode(&#039;[Total_Soft_Pricing_Table id="' + PTable_ID + '"]&#039;);?&gt');

            setTimeout(function () {
                jQuery('.Total_Soft_PTable_AMD2').css('display', 'none');
                jQuery('.Total_Soft_PTable_AMMTable').css('display', 'none');
                jQuery('.Total_Soft_PTable_AMOTable').css('display', 'none');
                jQuery('.Total_Soft_PTable_Save').css('display', 'none');
                jQuery('.Total_Soft_PTable_Update').css('display', 'block');
                jQuery('.Total_Soft_PTable_AMD3').css('display', 'block');
                jQuery('.Total_Soft_PTable_AMMain_Div').css('display', 'block');
            }, 0)
            setTimeout(function () {
                jQuery('.Total_Soft_PTable_AMD3').animate({'opacity': 1}, 500);
                jQuery('.Total_Soft_PTable_AMMain_Div').animate({'opacity': 1}, 500);
                jQuery('.Total_Soft_PTable_Loading').css('display', 'none');
            }, 0)
        }
    });
}

function TotalSoftPTable_Del(PTable_ID) {
    jQuery('#Total_Soft_PTable_AMOTable_tr_' + PTable_ID).find('.Total_Soft_PTable_Del_Span').addClass('Total_Soft_PTable_Del_Span1');
}

function TotalSoftPTable_Del_Yes(PTable_ID) {
    jQuery.ajax({
        type: 'POST',
        url: object.ajaxurl,
        data: {
            action: 'Total_Soft_PTable_Del',
            foobar: PTable_ID, 
        },
        beforeSend: function () {
            jQuery('.Total_Soft_PTable_Loading').css('display', 'block');
        },
        success: function (response) {
            jQuery(".Total_Soft_PTable_AMOTable").find('#Total_Soft_PTable_AMOTable_tr_'+PTable_ID).remove();
             jQuery('.Total_Soft_PTable_Loading').css('display', 'none');
         
        }
    });
}

function TotalSoftPTable_Del_No(PTable_ID) {
    jQuery('#Total_Soft_PTable_AMOTable_tr_' + PTable_ID).find('.Total_Soft_PTable_Del_Span').removeClass('Total_Soft_PTable_Del_Span1');
}

//Theme Menu


function TotalSoftPTable_Edit_Theme(This_Team, this_Id) {
    jQuery('.Total_Soft_PTable_AMMain_Div2_Cols1 .Total_Soft_PTable_AMMain_Div2_Cols1_Action4 i').removeClass('totalsoft-times scale-up-center').addClass('totalsoft-pencil')
if (flag_edit=='true' && flag_id==this_Id) {TotalSoftPTable_Close_Dragdown();}else{
jQuery('.TS_PTable_Container_Col_'+this_Id+' .Total_Soft_PTable_AMMain_Div2_Cols1_Action4 i').removeClass('totalsoft-pencil').addClass('totalsoft-times scale-up-center')
  if (flag_edit=='false') {  flag_edit='true'; }
jQuery('.cancel').removeAttr('onclick').css('background-color', '#36d8d5')
    flag_id=this_Id;
    var Total_Soft_PTable_Select_Icon = jQuery('#Total_Soft_PTable_Select_Icon').html();

    var This_Theam_Var = jQuery(This_Team).parents().prev(".Total_Soft_PTable_Set_Title").val();
    var TS_PTable_Fonts = jQuery('#TS_PTable_Fonts').html();
    var Total_Soft_PTable_Col_Count = jQuery('#Total_Soft_PTable_Col_Count').val();
  
    let col_type = 1;
    if (Total_Soft_PTable_Col_Type == "type2") {
        col_type = 2;
    } else if (Total_Soft_PTable_Col_Type == "type3") {
        col_type = 3;
    } else if (Total_Soft_PTable_Col_Type == "type4") {
        col_type = 4;
    } else if (Total_Soft_PTable_Col_Type == "type5") {
        col_type = 5;
    }
     let  Col_Count = jQuery(".TS_PTable_ST" + col_type + "_" + this_Id + "_01").attr('name').split('_');
      Col_Count =Col_Count[3];
      
     let Total_Soft_PTable_newCol = jQuery("#TS_PTable_Col_" + this_Id)[0];
     let PTable_Man_ID = jQuery('#Total_SoftPTable_Update').val();
            let new_set_col = {
            PTable_ID: PTable_Man_ID,
            TS_PTable_ST_00: "Theme_" + this_Id,
            TS_PTable_ST_01: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_01']").val(),
            TS_PTable_ST_02: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_02']").val(),
            TS_PTable_ST_03: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_03']").val(),
            TS_PTable_ST_04: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_04']").val(),
            TS_PTable_ST_05: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_05']").val(),
            TS_PTable_ST_06: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_06']").val(),
            TS_PTable_ST_07: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_07']").val(),
            TS_PTable_ST_08: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_08']").val(),
            TS_PTable_ST_09: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_09']").val(),
            TS_PTable_ST_10: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_10']").val(),
            TS_PTable_ST_11: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_11']").val(),
            TS_PTable_ST_12: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_12']").val(),
            TS_PTable_ST_13: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_13']").val(),
            TS_PTable_ST_14: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_14']").val(),
            TS_PTable_ST_15: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_15']").val(),
            TS_PTable_ST_16: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_16']").val(),
            TS_PTable_ST_17: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_17']").val(),
            TS_PTable_ST_18: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_18']").val(),
            TS_PTable_ST_19: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_19']").val(),
            TS_PTable_ST_20: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_20']").val(),
            TS_PTable_ST_21: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_21']").val(),
            TS_PTable_ST_21_1: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_21_1']").val(),
            TS_PTable_ST_22: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_22']").val(),
            TS_PTable_ST_23: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_23']").val(),
            TS_PTable_ST_24: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_24']").val(),
            TS_PTable_ST_25: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_25']").val(),
            TS_PTable_ST_26: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_26']").val(),
            TS_PTable_ST_27: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_27']").val(),
            TS_PTable_ST_28: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_28']").val(),
            TS_PTable_ST_29: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_29']").val(),
            TS_PTable_ST_30: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_30']").val(),
            TS_PTable_ST_31: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_31']").val(),
            TS_PTable_ST_32: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_32']").val(),
            TS_PTable_ST_33: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_33']").val(),
            TS_PTable_ST_34: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_34']").val(),
            TS_PTable_ST_35: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_35']").val(),
            TS_PTable_ST_36: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_36']").val(),
            TS_PTable_ST_37: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_37']").val(),
            TS_PTable_ST_38: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_38']").val(),
            TS_PTable_ST_39: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_39']").val(),
            TS_PTable_ST_40: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_40']").val(),
            TS_PTable_TType: Total_Soft_PTable_Col_Type,
            id: this_Id,
            index: jQuery(".TS_PTable_ST" + col_type + "_" + this_Id + "_index").val()
        }

     
    var data = [];
    jQuery.ajax({
        type: 'POST',
        url: object.ajaxurl,
        data: {
            action: New_Cop_Set_Edit, 
            foobar: Total_Soft_PTable_Col_Type, 
        },
        success: function (response) {
            if (jQuery('.TS_PTable_Container_Col_' + this_Id).hasClass("TS_PTable_Container_Col_Copy")||New_Cop_Set_Edit == "Total_Soft_PTable_Edit_New_Theme") {
                data = New_Cop_Set;
            } else {
                data = new_set_col;
            }
            var z = [];
            // for (var i = 0; i < data.length; i++) {
            //     if (data['TS_PTable_TType'] == Total_Soft_PTable_Col_Type && data['id'] == this_Id) {

            //         z.push(data);
            //     }
            // }
             z.push(data);
            if (Total_Soft_PTable_Col_Type == 'type1') {
                for (var i = 0; i < z.length; i++) {
                    Total_Soft_PTable_Set_Count = z[i]['id'];
                    jQuery("#Total_Soft_PTable_Col_Id").val(Total_Soft_PTable_Set_Count);
                    jQuery('#Total_Soft_PTable_Setting_Type').val(1);
                    Total_Soft_PTable_Col_Count += 1;
                    jQuery(".Total_Soft_PTable_Cols_Id").val(Total_Soft_PTable_Set_Count);
                    jQuery(".Hidden_Top_Set").html('' +
                        '<div class="TS_PTable_TMMain_Set1" id="TS_PTable_Set_T1_' + Total_Soft_PTable_Set_Count + '">' +
                        '<input type="text" style="display:none;" class="Total_Soft_PTable_Select" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_ID" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_ID" value="' + Total_Soft_PTable_Set_Count + '">' +
                        '<input type="text" style="display:none;" class="Total_Soft_PTable_Select" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_00" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_00" value="Theme_' + Total_Soft_PTable_Set_Count + '">' +
                        '<div class="Total_Soft_PTable_TMMain_Div_Sets_Fields">' +
                        '<div class="Total_Soft_PT_AMSetDiv_Content">' +
                        '<div class="TS_PT_Option_Div TS_PT_Option_Div_1_' + Total_Soft_PTable_Set_Count + '" id="Total_Soft_PT_AMSetTable_1_' + Total_Soft_PTable_Set_Count + '_GO">' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Width</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" class="TS_PTable_Range TS_PTable_Rangeper" oninput="TS_Ch_Col_Width(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_01" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_01" min="15" max="100" value="' + z[i]['TS_PTable_ST_01'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_01_Output" for="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_01"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Scale</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<div class="TS_PTable_Switch">' +
                        '<input class="TS_PTable_Switch_Toggle TS_PTable_Switch_Toggle-yes-no" onclick="TS_Ch_1_Col_Scale(this, Total_Soft_PTable_Set_Count)" type="checkbox" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_02" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_02">' +
                        '<label for="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_02" data-on="Yes" data-off="No"></label>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Background Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_Inp_1_Back_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_03" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_03" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_1_Back_Color" value="' + z[i]['TS_PTable_ST_03'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Border Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" oninput="TS_Ch_Inp_1_Border_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_04" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_04" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_1_Border_Color" value="' + z[i]['TS_PTable_ST_04'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Border Width</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_Inp_1_Border_Width(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_05" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_05" min="0" max="10" value="' + z[i]['TS_PTable_ST_05'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_05_Output" for="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_05"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Shadow Type</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<select class="Total_Soft_PTable_Select" onchange="TS_Ch_Inp_1_Shadow_Type(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_06" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_06">' +
                        '<option value="none">None</option>' +
                        '<option value="shadow01">Shadow 1</option>' +
                        '<option value="shadow02">Shadow 2</option>' +
                        '<option value="shadow03">Shadow 3</option>' +
                        '<option value="shadow04">Shadow 4</option>' +
                        '<option value="shadow05">Shadow 5</option>' +
                        '<option value="shadow06">Shadow 6</option>' +
                        '<option value="shadow07">Shadow 7</option>' +
                        '<option value="shadow08">Shadow 8</option>' +
                        '<option value="shadow09">Shadow 9</option>' +
                        '<option value="shadow10">Shadow 10</option>' +
                        '<option value="shadow11">Shadow 11</option>' +
                        '<option value="shadow12">Shadow 12</option>' +
                        '<option value="shadow13">Shadow 13</option>' +
                        '<option value="shadow14">Shadow 14</option>' +
                        '<option value="shadow15">Shadow 15</option>' +
                        '</select>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Shadow Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" oninput="TS_Ch_1_Inp_Shadow_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_07" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_07" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_1_Shadow_Color" value="' + z[i]['TS_PTable_ST_07'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Font Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" class="TS_PTable_Range TS_PTable_Rangepx" oninput="TS_Ch_1_Inp_Font_Size(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_08" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_08" min="8" max="72" value="' + z[i]['TS_PTable_ST_08'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_08_Output" for="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_08"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Font Family</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<select class="Total_Soft_PTable_Select" onchange="TS_Ch_1_Inp_Font_Family(this, Total_Soft_PTable_Set_Count)"  name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_09" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_09">' + TS_PTable_Fonts + '</select>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_1_Inp_Font_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_10" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_10" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_inp" value="' + z[i]['TS_PTable_ST_10'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_1_Inp_Icon_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_11" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_11" class="TS_PTable_Color TS_PTable_Color_Icon_' + Total_Soft_PTable_Set_Count + '_Inp_1_Icon " value="' + z[i]['TS_PTable_ST_11'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_1_Inp_Icon_Font_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_12" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_12" min="8" max="72" value="' + z[i]['TS_PTable_ST_12'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_12_Output" for="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_12"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Position</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<select class="Total_Soft_PTable_Select" onchange="TS_Ch_1_Inp_Font_Position(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_13" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_13" >' +
                        '<option value="after">After Text</option>' +
                        '<option value="before">Before Text</option>' +
                        '<option value="above">Above Text</option>' +
                        '<option value="under">Under Text</option>' +
                        '</select>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1 Total_Soft_Titles">Price Options</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Font Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_1_Inp_PV_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_14" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_14" min="8" max="48" value="' + z[i]['TS_PTable_ST_14'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_14_Output" for="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_14"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Font Family</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<select class="Total_Soft_PTable_Select" onchange="TS_Ch_1_Inp_PV_Font_Family(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_15" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_15">' + TS_PTable_Fonts + '</select>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_1_Inp_PV_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_16" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_16" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_1_Price" value="' + z[i]['TS_PTable_ST_16'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Plan Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_1_Inp_PL_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_17" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_17" min="8" max="48" value="' + z[i]['TS_PTable_ST_17'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_17_Output" for="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_17"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Plan Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_1_Inp_PL_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_18" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_18" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_1_Plan" value="' + z[i]['TS_PTable_ST_18'] + '">' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>');
                    jQuery('#Total_Soft_PTable_Setting_Type').val(2);
                    jQuery(".hidden_Top_Set_Desc").html('' +
                        '<div class="TS_PT_Option_Div TS_PT_Option_Div_1_' + Total_Soft_PTable_Set_Count + '" id="Total_Soft_PT_AMSetTable_1_' + Total_Soft_PTable_Set_Count + '_FO">' +
                        '<input type="text" style="display:none;" class="Total_Soft_PTable_Select" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_ID" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_ID" value="' + Total_Soft_PTable_Set_Count + '">' +
                        '<input type="text" style="display:none;" class="Total_Soft_PTable_Select" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_00" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_00" value="Theme_' + Total_Soft_PTable_Set_Count + '">' +
                        '<div class="TS_PT_Option_Div1 Total_Soft_Titles">Features Options</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Background Color 1</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_1_Inp_PVColor1(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_20" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_20" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_Color1" value="' + z[i]['TS_PTable_ST_20'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Background Color 2</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_1_Inp_PVColor2(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_19" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_19" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_Color2" value="' + z[i]['TS_PTable_ST_19'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Text 1 Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_InpText1(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_21" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_21" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_Text1" value="' + z[i]['TS_PTable_ST_21'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Text 2 Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_InpText2(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_21_1" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_21_1" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_Text2" value="' + z[i]['TS_PTable_ST_21_1'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Text Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_Inp_1_Feut_Text_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx  name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_22" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_22" min="8" max="48" value="' + z[i]['TS_PTable_ST_22'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_22_Output" for="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_22"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Text Font</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<select class="Total_Soft_PTable_Select" onchange="TS_Ch_Inp_Feut_1_Text_Family(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_23" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_23">' + TS_PTable_Fonts + '</select>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_Inp_1_Feut_Icon_Col(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)"  name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_24" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_24" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + ' TS_PTable_Color_Icon_' + Total_Soft_PTable_Set_Count + '_1_Feut" value="' + z[i]['TS_PTable_ST_24'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Selected Icon Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_Inp_1_PL_Icon(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_25" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_25" class="TS_PTable_Color TS_PTable_Color_Icon' + Total_Soft_PTable_Set_Count + '_Icon" value="' + z[i]['TS_PTable_ST_25'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_1_Inp_PL_Ic(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_26" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_26" min="8" max="48" value="' + z[i]['TS_PTable_ST_26'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_26_Output" for="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_26"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Position</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<select class="Total_Soft_PTable_Select" onchange="TS_Ch_1_Inp_Feut_icon_Position(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_27" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_27">' +
                        '<option value="after">After Text</option>' +
                        '<option value="before">Before Text</option>' +
                        '</select>' +
                        '</div>' +
                        '</div>' +
                        '</div>');
                    jQuery('#Total_Soft_PTable_Setting_Type').val(3);
                    jQuery(".hidden_Set_But").html('' +
                        '<input type="text" style="display:none;" class="Total_Soft_PTable_Select" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_ID" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_ID" value="' + Total_Soft_PTable_Set_Count + '">' +
                        '<input type="text" style="display:none;" class="Total_Soft_PTable_Select" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_00" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_00" value="Theme_' + Total_Soft_PTable_Set_Count + '">' +
                        '<div class="TS_PT_Option_Div TS_PT_Option_Div_1_' + Total_Soft_PTable_Set_Count + '" id="Total_Soft_PT_AMSetTable_1_' + Total_Soft_PTable_Set_Count + '_BO">' +
                        '<div class="TS_PT_Option_Div1 Total_Soft_Titles">Button Options</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Background Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_Inp_1_But_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_28" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_28" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + ' TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_Color3"  value="' + z[i]["TS_PTable_ST_28"] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_Inp_1_But_Font_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_29" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_29" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + ' TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_Color4 " value="' + z[i]["TS_PTable_ST_29"] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Font Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_Inp_1_But_Font_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_30" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_30" class="TS_PTable_ST1_30" min="8" max="48" value="' + z[i]["TS_PTable_ST_30"] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_30_Output" for="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_30"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Font Family</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<select class="Total_Soft_PTable_Select" onchange="TS_Ch_Inp_1_But_Font_Family(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_31" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_31">' + TS_PTable_Fonts + '</select>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_Inp_1_But_Icon_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_32" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_32" min="8" max="48" value="' + z[i]["TS_PTable_ST_32"] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_32_Output" for="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_32"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" oninput="TS_Ch_Inp_1_ButIcon_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_33" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_33" class="TS_PTable_Color  TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_Color5" value="' + z[i]["TS_PTable_ST_33"] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Position</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<select class="Total_Soft_PTable_Select" onchange="TS_Ch_Inp_1_But_Icon_Position(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_34" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_34">' +
                        '<option value="after">After Text</option>' +
                        '<option value="before">Before Text</option>' +
                        '</select>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>');
                    if (z[i]['TS_PTable_ST_00'] == This_Theam_Var) {
                        if (z[i]['TS_PTable_ST_02'] == 'on') {
                            z[i]['TS_PTable_ST_02'] = true;
                        } else {
                            z[i]['TS_PTable_ST_02'] = false;
                        }
                        jQuery('#TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_ID').val(z[i]['id']);
                        jQuery('#TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_00').val(z[i]['TS_PTable_ST_00']);
                        jQuery('#TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_01').val(z[i]['TS_PTable_ST_01']);
                        jQuery('#TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_02').attr('checked', z[i]['TS_PTable_ST_02']);
                         jQuery('#TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_06').val(z[i]['TS_PTable_ST_06']);
                        jQuery('#TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_09').val(z[i]['TS_PTable_ST_09']);
                        jQuery('#TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_13').val(z[i]['TS_PTable_ST_13']);
                        jQuery('#TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_15').val(z[i]['TS_PTable_ST_15']);
                        jQuery('#TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_23').val(z[i]['TS_PTable_ST_23']);
                        jQuery('#TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_27').val(z[i]['TS_PTable_ST_27']);
                        jQuery('#TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_31').val(z[i]['TS_PTable_ST_31']);
                        jQuery('#TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_34').val(z[i]['TS_PTable_ST_34']);
                    }
                }
            } else if (Total_Soft_PTable_Col_Type == 'type2') {
                for (var i = 0; i < z.length; i++) {
                   
                    Total_Soft_PTable_Set_Count = z[i]['id'];
                    jQuery("#Total_Soft_PTable_Col_Id").val(Total_Soft_PTable_Set_Count);
                    jQuery('#Total_Soft_PTable_Setting_Type').val(1);
                    Total_Soft_PTable_Col_Count += 1;
                    jQuery(".Total_Soft_PTable_Cols_Id").val(Total_Soft_PTable_Set_Count);
                    jQuery(".Hidden_Top_Set").html('' +
                        '<div class="TS_PTable_TMMain_Set1" id="TS_PTable_Set_T1_' + Total_Soft_PTable_Set_Count + '">' +
                        '<input type="hidden" class="Total_Soft_PTable_Select" name="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_ID" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_ID" value="' + Total_Soft_PTable_Set_Count + '">' +
                        '<input type="hidden" class="Total_Soft_PTable_Select" name="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_00" id="TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_00" value="Theme_' + Total_Soft_PTable_Set_Count + '">' +
                        '<div class="Total_Soft_PTable_TMMain_Div_Sets_Fields">' +
                        '<div class="Total_Soft_PT_AMSetDiv_Content">' +
                        '<div class="TS_PT_Option_Div TS_PT_Option_Div_1_' + Total_Soft_PTable_Set_Count + '" id="Total_Soft_PT_AMSetTable_1_' + Total_Soft_PTable_Set_Count + '_GO">' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Width</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" class="TS_PTable_Range TS_PTable_Rangeper" oninput="TS_Ch_2_Col_Width(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_01" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_01" min="15" max="100" value="' + z[i]['TS_PTable_ST_01'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_01_Output" for="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_01"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Scale</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<div class="TS_PTable_Switch">' +
                        '<input class="TS_PTable_Switch_Toggle TS_PTable_Switch_Toggle-yes-no" onclick="TS_Ch_2_Col_Scale(this, Total_Soft_PTable_Set_Count)" type="checkbox" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_02" name="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_02">' +
                        '<label for="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_02" data-on="Yes" data-off="No"></label>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Background Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_Inp_2_Back_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_03" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_03" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_2_Back_Color" value="' + z[i]['TS_PTable_ST_03'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Shadow Type</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<select class="Total_Soft_PTable_Select" onchange="TS_Ch_Inp_2_Shadow_Type(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_04" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_04">' +
                        '<option value="none">None</option>' +
                        '<option value="shadow01">Shadow 1</option>' +
                        '<option value="shadow02">Shadow 2</option>' +
                        '<option value="shadow03">Shadow 3</option>' +
                        '<option value="shadow04">Shadow 4</option>' +
                        '<option value="shadow05">Shadow 5</option>' +
                        '<option value="shadow06">Shadow 6</option>' +
                        '<option value="shadow07">Shadow 7</option>' +
                        '<option value="shadow08">Shadow 8</option>' +
                        '<option value="shadow09">Shadow 9</option>' +
                        '<option value="shadow10">Shadow 10</option>' +
                        '<option value="shadow11">Shadow 11</option>' +
                        '<option value="shadow12">Shadow 12</option>' +
                        '<option value="shadow13">Shadow 13</option>' +
                        '<option value="shadow14">Shadow 14</option>' +
                        '<option value="shadow15">Shadow 15</option>' +
                        '</select>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Shadow Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" oninput="TS_Ch_2_Inp_Shadow_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_05" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_05" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_2_Shadow_Color" value="' + z[i]['TS_PTable_ST_05'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1 Total_Soft_Titles">Title Options</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Font Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" class="TS_PTable_Range TS_PTable_Rangepx" oninput="TS_Ch_2_Inp_Font_Size(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_06" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_08" min="8" max="72" value="' + z[i]['TS_PTable_ST_06'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_06_Output" for="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_06"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Font Family</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<select class="Total_Soft_PTable_Select" onchange="TS_Ch_2_Inp_Font_Family(this, Total_Soft_PTable_Set_Count)"  name="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_07" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_07">' + TS_PTable_Fonts + '</select>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" oninput="TS_Ch_2_Inp_Font_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_08" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_08" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_2_Title" value="' + z[i]['TS_PTable_ST_08'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" oninput="TS_Ch_2_Inp_Icon_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_09" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_09" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_2_Icon" value="' + z[i]['TS_PTable_ST_11'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_2_Inp_Icon_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx" name="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_10" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_10" min="8" max="72" value="' + z[i]['TS_PTable_ST_10'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_10_Output" for="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_10"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1 Total_Soft_Titles">Price Options</div>' +
                        '<div class="TS_PT_Option_Div1"> ' +
                        '<div class="TS_PT_Option_Name">Background Color</div> ' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_Inp2ST(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_11" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_11" class="TS_PTable_Color TS_PTable_Color_Icon_' + Total_Soft_PTable_Set_Count + ' TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_2PT" value="' + z[i]['TS_PTable_ST_11'] + '"> ' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Font Family</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<select class="Total_Soft_PTable_Select" onchange="TS_Ch_Inp_2_PV_Font(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_12" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_12">' + TS_PTable_Fonts + '</select>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_Inp_2_PV_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_13" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_13" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + ' TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_Amount" value="' + z[i]['TS_PTable_ST_13'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Currency Font Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_Inp_2_PCur_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx" name="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_14" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_14" min="8" max="48" value="' + z[i]['TS_PTable_ST_14'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_14_Output" for="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_14"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Price Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_Inp_2_PPrice_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx" name="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_15" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_15" min="8" max="48" value="' + z[i]['TS_PTable_ST_15'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_15_Output" for="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_15"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Plan Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_Inp_2_PPlan_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx" name="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_16" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_16" min="8" max="48" value="' + z[i]['TS_PTable_ST_16'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_16_Output" for="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_16"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Hover Background Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" oninput="TS_Ch_Inp_2_PPlan_Hover_Background(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_17" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_17" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_2_hov_back" value="' + z[i]['TS_PTable_ST_17'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Hover Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_Inp_2_PPlan_Hover_Color(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_18" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_18" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_2_hov_Color" value="' + z[i]['TS_PTable_ST_18'] + '">' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>');
                    jQuery('#Total_Soft_PTable_Setting_Type').val(2);
                    jQuery(".hidden_Top_Set_Desc").html('' +
                        '<div class="TS_PT_Option_Div TS_PT_Option_Div_1_' + Total_Soft_PTable_Set_Count + '" id="Total_Soft_PT_AMSetTable_1_' + Total_Soft_PTable_Set_Count + '_FO">' +
                        '<input type="hidden" class="Total_Soft_PTable_Select" name="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_ID" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_ID" value="' + Total_Soft_PTable_Set_Count + '">' +
                        '<input type="hidden" class="Total_Soft_PTable_Select" name="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_00" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_00" value="Theme_' + Total_Soft_PTable_Set_Count + '">' +
                        '<div class="TS_PT_Option_Div1 Total_Soft_Titles">Features Options</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Background Color </div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" oninput="TS_Ch_Inp_2_Feut_Back(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_19" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_19" class="TS_PTable_Color  TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_2_Back" value="' + z[i]['TS_PTable_ST_19'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Text Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" oninput="TS_Ch_Inp_2_Feut_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_20" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_20" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_2_Text" value="' + z[i]['TS_PTable_ST_20'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Font Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_Inp_2_Feut_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx name="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_21" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_21" min="8" max="48" value="' + z[i]['TS_PTable_ST_21'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_21_Output" for="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_21"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Font Family</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<select class="Total_Soft_PTable_Select" onchange="TS_Ch_Inp_2_Feut_Family(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_22" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_22">' + TS_PTable_Fonts + '</select>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_Inp_Feut_2_Icon_Col(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)"  name="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_23" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_23" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_2_Feut" value="' + z[i]['TS_PTable_ST_23'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Selected Icon Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_Inp_Feut_2_Icon_Col_Sel(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_24" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_24" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_2_Feut_Sel" value="' + z[i]['TS_PTable_ST_24'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_Inp_Feut_2_Icon_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx" name="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_25" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_25" min="8" max="48" value="' + z[i]['TS_PTable_ST_25'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_25_Output" for="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_25"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Position</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<select class="Total_Soft_PTable_Select" onchange="TS_Ch_Inp_2_Feut_Icon_Position(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_26" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_26">' +
                        '<option value="after">After Text</option>' +
                        '<option value="before">Before Text</option>' +
                        '</select>' +
                        '</div>' +
                        '</div>' +
                        '</div>'
                    );
                    jQuery('#Total_Soft_PTable_Setting_Type').val(3);
                    jQuery(".hidden_Set_But").html('' +
                        '<input type="hidden" class="Total_Soft_PTable_Select" name="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_ID" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_ID" value="' + Total_Soft_PTable_Set_Count + '">' +
                        '<input type="hidden" class="Total_Soft_PTable_Select" name="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_00" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_00" value="Theme_' + Total_Soft_PTable_Set_Count + '">' +
                        '<div class="TS_PT_Option_Div TS_PT_Option_Div_1_' + Total_Soft_PTable_Set_Count + '" id="Total_Soft_PT_AMSetTable_1_' + Total_Soft_PTable_Set_Count + '_BO">' +
                        '<div class="TS_PT_Option_Div1 Total_Soft_Titles">Button Options</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Font Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_Inp_2_But_Font_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx" name="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_27" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_27" min="8" max="48" value="' + z[i]["TS_PTable_ST_27"] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_27_Output" for="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_27"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Font Family</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<select class="Total_Soft_PTable_Select" onchange="TS_Ch_Inp_2_But_Font_Family(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_28" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_28">' + TS_PTable_Fonts + '</select>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_Inp_2_But_Icon_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx" name="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_29" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_29" min="8" max="48" value="' + z[i]["TS_PTable_ST_29"] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_29_Output" for="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_29"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Position</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<select class="Total_Soft_PTable_Select" onchange="TS_Ch_Inp_2_But_Icon_Position(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_30" id="TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_30">' +
                        '<option value="after">After Text</option>' +
                        '<option value="before">Before Text</option>' +
                        '</select>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>');
                    if (z[i]['TS_PTable_ST_00'] == This_Theam_Var) {
                        if (z[i]['TS_PTable_ST_02'] == 'on') {
                            z[i]['TS_PTable_ST_02'] = true;
                        } else {
                            z[i]['TS_PTable_ST_02'] = false;
                        }
                        jQuery('#TS_PTable_ST1_' + Total_Soft_PTable_Set_Count + '_ID').val(z[i]['id']);
                        jQuery('#TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_00').val(z[i]['TS_PTable_ST_00']);
                        jQuery('#TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_01').val(z[i]['TS_PTable_ST_01']);
                        jQuery('#TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_02').attr('checked', z[i]['TS_PTable_ST_02']);
                        jQuery('#TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_03').val(z[i]['TS_PTable_ST_03']);
                        jQuery('#TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_04').val(z[i]['TS_PTable_ST_04']);
                        jQuery('#TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_07').val(z[i]['TS_PTable_ST_07']);
                        jQuery('#TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_12').val(z[i]['TS_PTable_ST_12']);
                        jQuery('#TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_22').val(z[i]['TS_PTable_ST_22']);
                        jQuery('#TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_26').val(z[i]['TS_PTable_ST_26']);
                        jQuery('#TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_28').val(z[i]['TS_PTable_ST_28']);
                        jQuery('#TS_PTable_ST2_' + Total_Soft_PTable_Set_Count + '_30').val(z[i]['TS_PTable_ST_30']);
                    }
                }
            } else if (Total_Soft_PTable_Col_Type == 'type3') {
                for (var i = 0; i < z.length; i++) {
                    Total_Soft_PTable_Set_Count = z[i]['id'];
                    jQuery("#Total_Soft_PTable_Col_Id").val(Total_Soft_PTable_Set_Count);
                    jQuery('#Total_Soft_PTable_Setting_Type').val(1);
                    Total_Soft_PTable_Col_Count += 1;
                    jQuery(".Total_Soft_PTable_Cols_Id").val(Total_Soft_PTable_Set_Count);
                    jQuery(".Hidden_Top_Set").html('' +
                        '<div class="TS_PTable_TMMain_Set1" id="TS_PTable_Set_T1_' + Total_Soft_PTable_Set_Count + '">' +
                        '<input type="hidden" class="Total_Soft_PTable_Select" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_ID" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_ID" value="' + Total_Soft_PTable_Set_Count + '">' +
                        '<input type="hidden" class="Total_Soft_PTable_Select" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_00" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_00" value="Theme_' + Total_Soft_PTable_Set_Count + '">' +
                        '<div class="Total_Soft_PTable_TMMain_Div_Sets_Fields">' +
                        '<div class="Total_Soft_PT_AMSetDiv_Content">' +
                        '<div class="TS_PT_Option_Div TS_PT_Option_Div_1_' + Total_Soft_PTable_Set_Count + '" id="Total_Soft_PT_AMSetTable_1_' + Total_Soft_PTable_Set_Count + '_GO">' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Width</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" class="TS_PTable_Range TS_PTable_Rangeper" oninput="TS_Ch_3_Col_Width(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_01" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_01" min="15" max="100" value="' + z[i]['TS_PTable_ST_01'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_01_Output" for="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_01"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Scale</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<div class="TS_PTable_Switch">' +
                        '<input class="TS_PTable_Switch_Toggle TS_PTable_Switch_Toggle-yes-no" onclick="TS_Ch_3_Col_Scale(this, Total_Soft_PTable_Set_Count)" type="checkbox" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_02" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_02">' +
                        '<label for="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_02" data-on="Yes" data-off="No"></label>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Background Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_Inp_3_Back_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_03" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_03" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_3_Back_Color" value="' + z[i]['TS_PTable_ST_03'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Border Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_Inp_3_Border_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_04" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_04" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_3_Border_Color" value="' + z[i]['TS_PTable_ST_04'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Border Width</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_Inp_3_Border_Width(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_05" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_05" min="0" max="10" value="' + z[i]['TS_PTable_ST_05'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_05_Output" for="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_05"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Shadow Type</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<select class="Total_Soft_PTable_Select" onchange="TS_Ch_Inp_3_Shadow_Type(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_06" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_06">' +
                        '<option value="none">None</option>' +
                        '<option value="shadow01">Shadow 1</option>' +
                        '<option value="shadow02">Shadow 2</option>' +
                        '<option value="shadow03">Shadow 3</option>' +
                        '<option value="shadow04">Shadow 4</option>' +
                        '<option value="shadow05">Shadow 5</option>' +
                        '<option value="shadow06">Shadow 6</option>' +
                        '<option value="shadow07">Shadow 7</option>' +
                        '<option value="shadow08">Shadow 8</option>' +
                        '<option value="shadow09">Shadow 9</option>' +
                        '<option value="shadow10">Shadow 10</option>' +
                        '<option value="shadow11">Shadow 11</option>' +
                        '<option value="shadow12">Shadow 12</option>' +
                        '<option value="shadow13">Shadow 13</option>' +
                        '<option value="shadow14">Shadow 14</option>' +
                        '<option value="shadow15">Shadow 15</option>' +
                        '</select>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Shadow Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" oninput="TS_Ch_3_Inp_Shadow_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_07" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_07" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_3_Shadow_Color" value="' + z[i]['TS_PTable_ST_07'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1 Total_Soft_Titles">Title Options</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Font Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" class="TS_PTable_Range TS_PTable_Rangepx" oninput="TS_Ch_3_Inp_Font_Size(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_08" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_08" min="8" max="72" value="' + z[i]['TS_PTable_ST_08'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_08_Output" for="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_08"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Font Family</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<select class="Total_Soft_PTable_Select" onchange="TS_Ch_3_Inp_Font_Family(this, Total_Soft_PTable_Set_Count)"  name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_09" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_09">' + TS_PTable_Fonts + '</select>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" oninput="TS_Ch_3_Inp_Font_Color(this, Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type)" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_10" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_10" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_3_Title_Color" value="' + z[i]['TS_PTable_ST_10'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1"> ' +
                        '<div class="TS_PT_Option_Name">Background Color</div> ' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_3_Title_Back(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_11" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_11" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + ' TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_BG_Title" value="' + z[i]['TS_PTable_ST_11'] + '"> ' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_3_Inp_Icon_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_12" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_12" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_3_Icon_Color" value="' + z[i]['TS_PTable_ST_12'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Background Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_3_Inp_Icon_Back(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_13" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_13" class="TS_PTable_Color TS_PTable_Color_Icon_' + Total_Soft_PTable_Set_Count + ' TS_PTable_Color_Icon_' + Total_Soft_PTable_Set_Count + '_I_BG" value="' + z[i]['TS_PTable_ST_13'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_3_Inp_Icon_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_14" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_14" min="8" max="72" value="' + z[i]['TS_PTable_ST_14'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_14_Output" for="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_14"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Hover Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" oninput="TS_Ch_Inp_3_Icon_Hover_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_15" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_15" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_3_Icon_Hov_Color" value="' + z[i]['TS_PTable_ST_15'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Hover Background</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" oninput="TS_Ch_Inp_3_Icon_Hover_Background(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_16" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_16" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_3_Back_Hov_Color" value="' + z[i]['TS_PTable_ST_16'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1 Total_Soft_Titles">Price Options</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Font Family</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<select class="Total_Soft_PTable_Select" onchange="TS_Ch_3_Inp_PV_Font_Family(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_17" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_17">' + TS_PTable_Fonts + '</select>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_3_Inp_PV_Font_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_18" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_18" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_3_Price_Color" value="' + z[i]['TS_PTable_ST_18'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Currency Font Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_3_Inp_PCur_Font_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_19" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_19" min="8" max="48" value="' + z[i]['TS_PTable_ST_19'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_19_Output" for="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_19"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Price Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_3_Inp_PPrice_Font_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_20" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_20" min="8" max="48" value="' + z[i]['TS_PTable_ST_20'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_20_Output" for="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_20"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Plan Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_3_Inp_PPan_Font_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_21" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_21" min="8" max="48" value="' + z[i]['TS_PTable_ST_21'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_21_Output" for="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_21"></output>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>');
                    jQuery('#Total_Soft_PTable_Setting_Type').val(2);
                    jQuery(".hidden_Top_Set_Desc").html('' +
                        '<div class="TS_PT_Option_Div TS_PT_Option_Div_1_' + Total_Soft_PTable_Set_Count + '" id="Total_Soft_PT_AMSetTable_1_' + Total_Soft_PTable_Set_Count + '_FO">' +
                        '<input type="hidden" class="Total_Soft_PTable_Select" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_ID" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_ID" value="' + Total_Soft_PTable_Set_Count + '">' +
                        '<input type="hidden" class="Total_Soft_PTable_Select" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_00" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_00" value="Theme_' + Total_Soft_PTable_Set_Count + '">' +
                        '<div class="TS_PT_Option_Div1 Total_Soft_Titles">Features Options</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Background Color </div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_Inp_3_Feut_Back(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_22" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_22" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_3_Back" value="' + z[i]['TS_PTable_ST_22'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Text Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_Inp_3_Feut_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_23" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_23" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_3_Text" value="' + z[i]['TS_PTable_ST_23'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Font Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_Inp_3_Feut_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx  name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_24" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_24" min="8" max="48" value="' + z[i]['TS_PTable_ST_24'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_24_Output" for="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_24"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Font Family</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<select class="Total_Soft_PTable_Select" onchange="TS_Ch_Inp_3_Feut_Family(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_25" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_25">' + TS_PTable_Fonts + '</select>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_Inp_Feut_3_Icon_Col(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)"  name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_26" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_26" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_3_Feut" value="' + z[i]['TS_PTable_ST_26'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Selected Icon Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_Inp_Feut_3_Icon_Col_Sel(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_27" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_27" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_3_Feut_Sel" value="' + z[i]['TS_PTable_ST_27'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_Inp_Feut_3_Icon_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_28" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_28" min="8" max="48" value="' + z[i]['TS_PTable_ST_28'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_28_Output" for="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_28"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Position</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<select class="Total_Soft_PTable_Select" onchange="TS_Ch_Inp_3_Feut_Icon_Position(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_29" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_29">' +
                        '<option value="after">After Text</option>' +
                        '<option value="before">Before Text</option>' +
                        '</select>' +
                        '</div>' +
                        '</div>' +
                        '</div>'
                    );
                    jQuery('#Total_Soft_PTable_Setting_Type').val(3);
                    jQuery(".hidden_Set_But").html('' +
                        '<input type="hidden" class="Total_Soft_PTable_Select" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_ID" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_ID" value="' + Total_Soft_PTable_Set_Count + '">' +
                        '<input type="hidden" class="Total_Soft_PTable_Select" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_00" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_00" value="Theme_' + Total_Soft_PTable_Set_Count + '">' +
                        '<div class="TS_PT_Option_Div TS_PT_Option_Div_1_' + Total_Soft_PTable_Set_Count + '" id="Total_Soft_PT_AMSetTable_1_' + Total_Soft_PTable_Set_Count + '_BO">' +
                        '<div class="TS_PT_Option_Div1 Total_Soft_Titles">Button Options</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Font Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_Inp_3_But_Font_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_30" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_30" min="8" max="48" value="' + z[i]["TS_PTable_ST_30"] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_30_Output" for="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_30"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Font Family</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<select class="Total_Soft_PTable_Select" onchange="TS_Ch_Inp_3_But_Font_Family(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_31" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_31">' + TS_PTable_Fonts + '</select>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_Inp_3_But_Icon_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_32" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_32" min="8" max="48" value="' + z[i]["TS_PTable_ST_32"] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_32_Output" for="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_32"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Position</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<select class="Total_Soft_PTable_Select" oninput="TS_Ch_Inp_3_But_Icon_Position(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_33" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_33">' +
                        '<option value="after">After Text</option>' +
                        '<option value="before">Before Text</option>' +
                        '</select>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Background Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" oninput="TS_Ch_Inp_3_BT_Back(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_34" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_34" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_3_BT" value="' + z[i]['TS_PTable_ST_34'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" oninput="TS_Ch_Inp_3_BT_Text(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_35" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_35" class="TS_PTable_Color  TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_3_BTExt" value="' + z[i]['TS_PTable_ST_35'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Hover Background Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" oninput="TS_Ch_Inp_3_BT_Back_Hover(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_36" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_36" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_3_Bt_Back_Hover" value="' + z[i]['TS_PTable_ST_36'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Hover Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" oninput="TS_Ch_Inp_3_BT_Color_Hover(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_37" id="TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_37" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + ' TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_3_Bt_Color_Hover" value="' + z[i]['TS_PTable_ST_37'] + '">' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>');
                    if (z[i]['TS_PTable_ST_00'] == This_Theam_Var) {
                        if (z[i]['TS_PTable_ST_02'] == 'on') {
                            z[i]['TS_PTable_ST_02'] = true;
                        } else {
                            z[i]['TS_PTable_ST_02'] = false;
                        }

                        jQuery('#TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_00').val(z[i]['TS_PTable_ST_00']);
                        jQuery('#TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_01').val(z[i]['TS_PTable_ST_01']);
                        jQuery('#TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_02').attr('checked', z[i]['TS_PTable_ST_02']);
                        jQuery('#TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_03').val(z[i]['TS_PTable_ST_03']);
                        jQuery('#TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_04').val(z[i]['TS_PTable_ST_04']);
                        jQuery('#TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_05').val(z[i]['TS_PTable_ST_05']);
                        jQuery('#TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_06').val(z[i]['TS_PTable_ST_06']);
                        jQuery('#TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_09').val(z[i]['TS_PTable_ST_09']);
                        jQuery('#TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_12').val(z[i]['TS_PTable_ST_12']);
                        jQuery('#TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_17').val(z[i]['TS_PTable_ST_17']);
                        jQuery('#TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_25').val(z[i]['TS_PTable_ST_25']);
                        jQuery('#TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_29').val(z[i]['TS_PTable_ST_29']);
                        jQuery('#TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_31').val(z[i]['TS_PTable_ST_31']);
                        jQuery('#TS_PTable_ST3_' + Total_Soft_PTable_Set_Count + '_33').val(z[i]['TS_PTable_ST_33']);
                    }
                }
            } else if (Total_Soft_PTable_Col_Type == 'type4') {
                for (var i = 0; i < z.length; i++) {
                    Total_Soft_PTable_Set_Count = z[i]['id'];
                    jQuery("#Total_Soft_PTable_Col_Id").val(Total_Soft_PTable_Set_Count);
                    jQuery('#Total_Soft_PTable_Setting_Type').val(1);
                    Total_Soft_PTable_Col_Count += 1;
                    jQuery(".Total_Soft_PTable_Cols_Id").val(Total_Soft_PTable_Set_Count);
                    jQuery(".Hidden_Top_Set").html('' +
                        '<div class="TS_PTable_TMMain_Set1" id="TS_PTable_Set_T1_' + Total_Soft_PTable_Set_Count + '">' +
                        '<input type="hidden" class="Total_Soft_PTable_Select" name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_ID" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_ID" value="' + Total_Soft_PTable_Set_Count + '">' +
                        '<input type="hidden" class="Total_Soft_PTable_Select" name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_00" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_00" value="Theme_' + Total_Soft_PTable_Set_Count + '">' +
                        '<div class="Total_Soft_PTable_TMMain_Div_Sets_Fields">' +
                        '<div class="Total_Soft_PT_AMSetDiv_Content">' +
                        '<div class="TS_PT_Option_Div TS_PT_Option_Div_1_' + Total_Soft_PTable_Set_Count + '" id="Total_Soft_PT_AMSetTable_1_' + Total_Soft_PTable_Set_Count + '_GO">' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Width</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" class="TS_PTable_Range TS_PTable_Rangeper" oninput="TS_Ch_4_Col_Width(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_01" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_01" min="15" max="100" value="' + z[i]['TS_PTable_ST_01'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_01_Output" for="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_01"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Scale</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<div class="TS_PTable_Switch">' +
                        '<input class="TS_PTable_Switch_Toggle TS_PTable_Switch_Toggle-yes-no" onclick="TS_Ch_4_Col_Scale(this, Total_Soft_PTable_Set_Count)" type="checkbox" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_02" name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_02">' +
                        '<label for="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_02" data-on="Yes" data-off="No"></label>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Background Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" oninput="TS_Ch_Inp_4_Back_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_03" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_03" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_4_Back_Color" value="' + z[i]['TS_PTable_ST_03'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Hover Background Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" oninput="TS_Ch_Inp_4_Back_Hover_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_04" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_04" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_4_Back_Hover_Color" value="' + z[i]['TS_PTable_ST_03'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Shadow Type</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<select class="Total_Soft_PTable_Select" onchange="TS_Ch_Inp_4_Shadow_Type(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_05" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_05">' +
                        '<option value="none">None</option>' +
                        '<option value="shadow01">Shadow 1</option>' +
                        '<option value="shadow02">Shadow 2</option>' +
                        '<option value="shadow03">Shadow 3</option>' +
                        '<option value="shadow04">Shadow 4</option>' +
                        '<option value="shadow05">Shadow 5</option>' +
                        '<option value="shadow06">Shadow 6</option>' +
                        '<option value="shadow07">Shadow 7</option>' +
                        '<option value="shadow08">Shadow 8</option>' +
                        '<option value="shadow09">Shadow 9</option>' +
                        '<option value="shadow10">Shadow 10</option>' +
                        '<option value="shadow11">Shadow 11</option>' +
                        '<option value="shadow12">Shadow 12</option>' +
                        '<option value="shadow13">Shadow 13</option>' +
                        '<option value="shadow14">Shadow 14</option>' +
                        '<option value="shadow15">Shadow 15</option>' +
                        '</select>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Shadow Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" oninput="TS_Ch_4_Inp_Shadow_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_06" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_06" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_4_Shadow_Color" value="' + z[i]['TS_PTable_ST_06'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1 Total_Soft_Titles">Title Options</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Font Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" class="TS_PTable_Range TS_PTable_Rangepx" oninput="TS_Ch_4_Inp_Font_Size(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_07" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_07" min="8" max="72" value="' + z[i]['TS_PTable_ST_07'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_07_Output" for="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_07"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Font Family</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<select class="Total_Soft_PTable_Select" onchange="TS_Ch_4_Inp_Font_Family(this, Total_Soft_PTable_Set_Count)"  name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_08" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_08">' + TS_PTable_Fonts + '</select>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1"> ' +
                        '<div class="TS_PT_Option_Name">Color</div> ' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_4_Inp_Font_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_09" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_09" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_4_Title_Color" value="' + z[i]['TS_PTable_ST_09'] + '"> ' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Hover Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_4_Inp_Font_Hover_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_10" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_10" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_4_Title_Hover_Color" value="' + z[i]['TS_PTable_ST_10'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1 Total_Soft_Titles">Price Options</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Font Family</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<select class="Total_Soft_PTable_Select" onchange="TS_Ch_4_Inp_PV_Font_Family(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_11" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_11">' + TS_PTable_Fonts + '</select>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_4_Inp_PV_Font_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_12" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_12" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + ' TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_4_Price_Color" value="' + z[i]['TS_PTable_ST_12'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Hover Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_4_Inp_PV_Font_Hover_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_13" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_13" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + ' TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_4_Price_Hover_Color" value="' + z[i]['TS_PTable_ST_13'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Currency Font Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_4_Inp_PCur_Font_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx" name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_14" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_14" min="8" max="48" value="' + z[i]['TS_PTable_ST_14'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_14_Output" for="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_14"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Price Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_4_Inp_PVal_Font_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx" name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_15" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_15" min="8" max="48" value="' + z[i]['TS_PTable_ST_15'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_15_Output" for="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_15"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Plan Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_4_Inp_PPlan_Font_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx" name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_16" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_16" min="8" max="48" value="' + z[i]['TS_PTable_ST_16'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_16_Output" for="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_16"></output>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>');
                    jQuery('#Total_Soft_PTable_Setting_Type').val(2);
                    jQuery(".hidden_Top_Set_Desc").html('' +
                        '<div class="TS_PT_Option_Div TS_PT_Option_Div_1_' + Total_Soft_PTable_Set_Count + '" id="Total_Soft_PT_AMSetTable_1_' + Total_Soft_PTable_Set_Count + '_FO">' +
                        '<input type="hidden" class="Total_Soft_PTable_Select" name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_ID" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_ID" value="' + Total_Soft_PTable_Set_Count + '">' +
                        '<input type="hidden" class="Total_Soft_PTable_Select" name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_00" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_00" value="Theme_' + Total_Soft_PTable_Set_Count + '">' +
                        '<div class="TS_PT_Option_Div1 Total_Soft_Titles">Features Options</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Background Color </div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_Inp_4_Feut_Back(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_17" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_17" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_4_Back" value="' + z[i]['TS_PTable_ST_17'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Text Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_Inp_4_Feut_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_18" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_18" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_4_Text" value="' + z[i]['TS_PTable_ST_18'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Font Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_Inp_4_Feut_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_19" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_19" min="8" max="48" value="' + z[i]['TS_PTable_ST_19'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_19_Output" for="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_19"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Font Family</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<select class="Total_Soft_PTable_Select" onchange="TS_Ch_Inp_4_Feut_Family(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_20" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_20">' + TS_PTable_Fonts + '</select>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_Inp_Feut_4_Icon_Col(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)"  name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_21" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_21" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + ' TS_PTable_Color_Icon_' + Total_Soft_PTable_Set_Count + '_4_Feut" value="' + z[i]['TS_PTable_ST_21'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Selected Icon Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_Inp_Feut_4_Icon_Col_Sel(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_22" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_22" class="TS_PTable_Color TS_PTable_Color_Icon' + Total_Soft_PTable_Set_Count + '_4_Feut_Sel" value="' + z[i]['TS_PTable_ST_22'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_Inp_Feut_4_Icon_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx" name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_23" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_23" min="8" max="48" value="' + z[i]['TS_PTable_ST_23'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_23_Output" for="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_23"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Position</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<select class="Total_Soft_PTable_Select" onchange="TS_Ch_Inp_4_Feut_Icon_Position(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_24" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_24">' +
                        '<option value="after">After Text</option>' +
                        '<option value="before">Before Text</option>' +
                        '</select>' +
                        '</div>' +
                        '</div>' +
                        '</div>'
                    );
                    jQuery('#Total_Soft_PTable_Setting_Type').val(3);
                    jQuery(".hidden_Set_But").html('' +
                        '<input type="hidden" class="Total_Soft_PTable_Select" name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_ID" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_ID" value="' + Total_Soft_PTable_Set_Count + '">' +
                        '<input type="hidden" class="Total_Soft_PTable_Select" name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_00" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_00" value="Theme_' + Total_Soft_PTable_Set_Count + '">' +
                        '<div class="TS_PT_Option_Div TS_PT_Option_Div_1_' + Total_Soft_PTable_Set_Count + '" id="Total_Soft_PT_AMSetTable_1_' + Total_Soft_PTable_Set_Count + '_BO">' +
                        '<div class="TS_PT_Option_Div1 Total_Soft_Titles">Button Options</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Font Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_Inp_4_But_Font_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx" name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_25" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_25" min="8" max="48" value="' + z[i]["TS_PTable_ST_25"] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_25_Output" for="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_25"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Font Family</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<select class="Total_Soft_PTable_Select" onchange="TS_Ch_Inp_4_But_Font_Family(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_26" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_26">' + TS_PTable_Fonts + '</select>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_Inp_4_But_Icon_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx" name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_27" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_27" min="8" max="48" value="' + z[i]["TS_PTable_ST_27"] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_27_Output" for="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_27"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Position</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<select class="Total_Soft_PTable_Select" oninput="TS_Ch_Inp_4_But_Icon_Position(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_28" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_28">' +
                        '<option value="after">After Text</option>' +
                        '<option value="before">Before Text</option>' +
                        '</select>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name"> Background Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" oninput="TS_Ch_Inp_4_BT_Back(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_29" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_29" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_4_BT" value="' + z[i]['TS_PTable_ST_29'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name"> Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_Inp_4_BT_Text(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_30" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_30" class="TS_PTable_Color T TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_4_BTExt" value="' + z[i]['TS_PTable_ST_30'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Hover Background Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" oninput="TS_Ch_Inp_4_BT_Back_Hover(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_31" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_31" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_4_Bt_Back_Hover" value="' + z[i]['TS_PTable_ST_31'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Hover Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" oninput="TS_Ch_Inp_4_BT_Color_Hover(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_31" id="TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_31" class="TS_PTable_Color  TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_4_Bt_Color_Hover" value="' + z[i]['TS_PTable_ST_31'] + '">' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>');
                    if (z[i]['TS_PTable_ST_00'] == This_Theam_Var) {
                        if (z[i]['TS_PTable_ST_02'] == 'on') {
                            z[i]['TS_PTable_ST_02'] = true;
                        } else {
                            z[i]['TS_PTable_ST_02'] = false;
                        }

                        jQuery('#TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_00').val(z[i]['TS_PTable_ST_00']);
                        jQuery('#TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_01').val(z[i]['TS_PTable_ST_01']);
                        jQuery('#TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_02').attr('checked', z[i]['TS_PTable_ST_02']);
                        jQuery('#TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_05').val(z[i]['TS_PTable_ST_05']);
                        jQuery('#TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_08').val(z[i]['TS_PTable_ST_08']);
                        jQuery('#TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_11').val(z[i]['TS_PTable_ST_11']);
                        jQuery('#TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_20').val(z[i]['TS_PTable_ST_20']);
                        jQuery('#TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_24').val(z[i]['TS_PTable_ST_24']);
                        jQuery('#TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_26').val(z[i]['TS_PTable_ST_26']);
                        jQuery('#TS_PTable_ST4_' + Total_Soft_PTable_Set_Count + '_28').val(z[i]['TS_PTable_ST_28']);
                    }
                }
            } else if (Total_Soft_PTable_Col_Type == 'type5') {
                for (var i = 0; i < z.length; i++) {
                    Total_Soft_PTable_Set_Count = z[i]['id'];
                    jQuery("#Total_Soft_PTable_Col_Id").val(Total_Soft_PTable_Set_Count);
                    jQuery('#Total_Soft_PTable_Setting_Type').val(1);
                    Total_Soft_PTable_Col_Count += 1;
                    jQuery(".Total_Soft_PTable_Cols_Id").val(Total_Soft_PTable_Set_Count);
                    jQuery(".Hidden_Top_Set").html('' +
                        '<div class="TS_PTable_TMMain_Set1" id="TS_PTable_Set_T1_' + Total_Soft_PTable_Set_Count + '">' +
                        '<input type="hidden" class="Total_Soft_PTable_Select" name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_ID" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_ID" value="' + Total_Soft_PTable_Set_Count + '">' +
                        '<input type="hidden" class="Total_Soft_PTable_Select" name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_00" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_00" value="Theme_' + Total_Soft_PTable_Set_Count + '">' +
                        '<div class="Total_Soft_PTable_TMMain_Div_Sets_Fields">' +
                        '<div class="Total_Soft_PT_AMSetDiv_Content">' +
                        '<div class="TS_PT_Option_Div TS_PT_Option_Div_1_' + Total_Soft_PTable_Set_Count + '" id="Total_Soft_PT_AMSetTable_1_' + Total_Soft_PTable_Set_Count + '_GO">' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Width</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" class="TS_PTable_Range TS_PTable_Rangeper" oninput="TS_Ch_5_Col_Width(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_01" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_01" min="15" max="100" value="' + z[i]['TS_PTable_ST_01'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_01_Output" for="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_01"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Scale</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<div class="TS_PTable_Switch">' +
                        '<input class="TS_PTable_Switch_Toggle TS_PTable_Switch_Toggle-yes-no" onclick="TS_Ch_5_Col_Scale(this, Total_Soft_PTable_Set_Count)" type="checkbox" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_02" name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_02">' +
                        '<label for="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_02" data-on="Yes" data-off="No"></label>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Background Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" oninput="TS_Ch_Inp_5_Back_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)"  name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_03" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_03" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_5_Back_Color" value="' + z[i]['TS_PTable_ST_03'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Shadow Type</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<select class="Total_Soft_PTable_Select" onchange="TS_Ch_Inp_5_Shadow_Type(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_04" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_04">' +
                        '<option value="none">None</option>' +
                        '<option value="shadow01">Shadow 1</option>' +
                        '<option value="shadow02">Shadow 2</option>' +
                        '<option value="shadow03">Shadow 3</option>' +
                        '<option value="shadow04">Shadow 4</option>' +
                        '<option value="shadow05">Shadow 5</option>' +
                        '<option value="shadow06">Shadow 6</option>' +
                        '<option value="shadow07">Shadow 7</option>' +
                        '<option value="shadow08">Shadow 8</option>' +
                        '<option value="shadow09">Shadow 9</option>' +
                        '<option value="shadow10">Shadow 10</option>' +
                        '<option value="shadow11">Shadow 11</option>' +
                        '<option value="shadow12">Shadow 12</option>' +
                        '<option value="shadow13">Shadow 13</option>' +
                        '<option value="shadow14">Shadow 14</option>' +
                        '<option value="shadow15">Shadow 15</option>' +
                        '</select>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Shadow Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" oninput="TS_Ch_5_Inp_Shadow_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_05" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_05" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_5_Shadow_Color" value="' + z[i]['TS_PTable_ST_05'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1 Total_Soft_Titles">Title Options</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Font Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_5_Inp_Font_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx" name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_06" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_06" min="8" max="72" value="' + z[i]['TS_PTable_ST_06'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_06_Output" for="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_06"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Font Family</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<select class="Total_Soft_PTable_Select" onchange="TS_Ch_5_Inp_Font_Family(this, Total_Soft_PTable_Set_Count)"  name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_07" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_07">' + TS_PTable_Fonts + '</select>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1"> ' +
                        '<div class="TS_PT_Option_Name">Color</div> ' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" oninput="TS_Ch_5_Inp_Font_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_08" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_08" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_5_Title_Color" value="' + z[i]['TS_PTable_ST_08'] + '"> ' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_5_Inp_Icon_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx" name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_09" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_09" min="8" max="72" value="' + z[i]['TS_PTable_ST_09'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_09_Output" for="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_09"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_5_Inp_Icon_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_10" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_10" class="TS_PTable_Color  TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_5_Icon_Color" value="' + z[i]['TS_PTable_ST_10'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Hover Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_5_Inp_Icon_Hover_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_11" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_11" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_5_Icon_Hover_Color" value="' + z[i]['TS_PTable_ST_11'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1 Total_Soft_Titles">Price Options</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Font Family</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<select class="Total_Soft_PTable_Select" onchange="TS_Ch_5_Inp_PV_Font_Family(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_12" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_12">' + TS_PTable_Fonts + '</select>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_5_Inp_PV_Font_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_13" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_13" class="TS_PTable_Color  TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_5_Price_Color" value="' + z[i]['TS_PTable_ST_13'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Hover Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_5_Inp_PV_Font_Hover_Color(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_14" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_14" class="TS_PTable_Color  TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_5_Price_Hover_Color" value="' + z[i]['TS_PTable_ST_14'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Currency Font Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_5_Inp_PCur_Font_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx" name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_15" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_15" min="8" max="48" value="' + z[i]['TS_PTable_ST_15'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_15_Output" for="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_15"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Price Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_5_Inp_PVal_Font_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx" name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_16" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_16" min="8" max="48" value="' + z[i]['TS_PTable_ST_16'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_16_Output" for="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_16"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Plan Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_5_Inp_PPlan_Font_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx" name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_17" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_17" min="8" max="48" value="' + z[i]['TS_PTable_ST_17'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_17_Output" for="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_17"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Hover Background Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" oninput="TS_Ch_Inp_5_PV_Back_Hover(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_18" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_18" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_5_PV_Hover_Back" value="' + z[i]['TS_PTable_ST_18'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Hover Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_Inp_5_PV_Color_Hover(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_19" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_18" class="TS_PTable_Color  TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_5_PV_Hover_Color" value="' + z[i]['TS_PTable_ST_19'] + '">' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>');
                    jQuery('#Total_Soft_PTable_Setting_Type').val(2);
                    jQuery(".hidden_Top_Set_Desc").html('' +
                        '<div class="TS_PT_Option_Div TS_PT_Option_Div_1_' + Total_Soft_PTable_Set_Count + '" id="Total_Soft_PT_AMSetTable_1_' + Total_Soft_PTable_Set_Count + '_FO">' +
                        '<input type="hidden" class="Total_Soft_PTable_Select" name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_ID" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_ID" value="' + Total_Soft_PTable_Set_Count + '">' +
                        '<input type="hidden" class="Total_Soft_PTable_Select" name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_00" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_00" value="Theme_' + Total_Soft_PTable_Set_Count + '">' +
                        '<div class="TS_PT_Option_Div1 Total_Soft_Titles">Features Options</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Text Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_5_Feut_Text(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_20" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_20" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_5_Feut_Text" value="' + z[i]['TS_PTable_ST_20'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Font Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_Inp_5_Feut_Text_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_21" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_21" min="8" max="48" value="' + z[i]['TS_PTable_ST_21'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_21_Output" for="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_21"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Font Family</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<select class="Total_Soft_PTable_Select" onchange="TS_Ch_Inp_5_Feut_Text_Font(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_22" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_22">' + TS_PTable_Fonts + '</select>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_Inp_5_Feut_Icon_Col(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)"  name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_23" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_23" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + ' TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_5_Feut" value="' + z[i]['TS_PTable_ST_23'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Selected Icon Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_Inp_5_Feut_Icon_Sel_Col(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_24" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_24" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_Icon_Sel" value="' + z[i]['TS_PTable_ST_24'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_Inp_5_FT_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx" name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_25" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_25" min="8" max="48" value="' + z[i]['TS_PTable_ST_25'] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_25_Output" for="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_25"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Position</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<select class="Total_Soft_PTable_Select" onchange="TS_Ch_Inp_5_Feut_Icon_Position(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_26" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_26">' +
                        '<option value="after">After Text</option>' +
                        '<option value="before">Before Text</option>' +
                        '</select>' +
                        '</div>' +
                        '</div>' +
                        '</div>'
                    );
                    jQuery('#Total_Soft_PTable_Setting_Type').val(3);
                    jQuery(".hidden_Set_But").html('' +
                        '<input type="hidden" class="Total_Soft_PTable_Select" name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_ID" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_ID" value="' + Total_Soft_PTable_Set_Count + '">' +
                        '<input type="hidden" class="Total_Soft_PTable_Select" name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_00" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_00" value="Theme_' + Total_Soft_PTable_Set_Count + '">' +
                        '<div class="TS_PT_Option_Div TS_PT_Option_Div_1_' + Total_Soft_PTable_Set_Count + '" id="Total_Soft_PT_AMSetTable_1_' + Total_Soft_PTable_Set_Count + '_BO">' +
                        '<div class="TS_PT_Option_Div1 Total_Soft_Titles">Button Options</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Font Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_Inp_5_But_Font_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx" name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_27" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_2t" min="8" max="48" value="' + z[i]["TS_PTable_ST_27"] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_27_Output" for="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_27"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Font Family</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<select class="Total_Soft_PTable_Select" onchange="TS_Ch_Inp_5_But_Font_Family(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_28" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_28">' + TS_PTable_Fonts + '</select>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Size</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="range" oninput="TS_Ch_Inp_5_But_Icon_Size(this, Total_Soft_PTable_Set_Count)" class="TS_PTable_Range TS_PTable_Rangepx" name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_29" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_29" min="8" max="48" value="' + z[i]["TS_PTable_ST_29"] + '">' +
                        '<output class="TS_PTable_Range_Out" name="" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_29_Output" for="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_29"></output>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name">Icon Position</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<select class="Total_Soft_PTable_Select" onchange="TS_Ch_Inp_5_But_Icon_Position(this, Total_Soft_PTable_Set_Count)" name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_30" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_30">' +
                        '<option value="after">After Text</option>' +
                        '<option value="before">Before Text</option>' +
                        '</select>' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name"> Background Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_Inp_5_BT_Back(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_31" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_31" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_5_But_Color" value="' + z[i]['TS_PTable_ST_31'] + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="TS_PT_Option_Div1">' +
                        '<div class="TS_PT_Option_Name"> Color</div>' +
                        '<div class="TS_PT_Option_Field">' +
                        '<input type="text" onchange="TS_Ch_Inp_5_BT_Text(this, Total_Soft_PTable_Set_Count,Total_Soft_PTable_Col_Type)" name="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_32" id="TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_32" class="TS_PTable_Color TS_PTable_Color_' + Total_Soft_PTable_Set_Count + ' TS_PTable_Color_' + Total_Soft_PTable_Set_Count + '_5_But_text_Color" value="' + z[i]['TS_PTable_ST_32'] + '">' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>');
                    if (z[i]['TS_PTable_ST_00'] == This_Theam_Var) {
                        if (z[i]['TS_PTable_ST_02'] == 'on') {
                            z[i]['TS_PTable_ST_02'] = true;
                        } else {
                            z[i]['TS_PTable_ST_02'] = false;
                        }

                        jQuery('#TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_00').val(z[i]['TS_PTable_ST_00']);
                        jQuery('#TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_01').val(z[i]['TS_PTable_ST_01']);
                        jQuery('#TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_02').attr('checked', z[i]['TS_PTable_ST_02']);
                        jQuery('#TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_04').val(z[i]['TS_PTable_ST_04']);
                        jQuery('#TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_07').val(z[i]['TS_PTable_ST_07']);
                        jQuery('#TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_12').val(z[i]['TS_PTable_ST_12']);
                        jQuery('#TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_22').val(z[i]['TS_PTable_ST_22']);
                        jQuery('#TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_26').val(z[i]['TS_PTable_ST_26']);
                        jQuery('#TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_28').val(z[i]['TS_PTable_ST_28']);
                        jQuery('#TS_PTable_ST5_' + Total_Soft_PTable_Set_Count + '_30').val(z[i]['TS_PTable_ST_30']);
                    }
                }
            }
            if (z.length > 1) {
                jQuery('.Total_Soft_PTable_TMMain_Div_But1').animate({opacity: "1"}, 500);
            }
            jQuery('.TS_PTable_Color').alphaColorPicker();
            jQuery('.wp-picker-holder').addClass('alpha-picker-holder');
            jQuery(".wp-color-picker").attr("style", "width:60px !important; border-color: #ece8e8; height: 24px; top:3px; min-height: 24px;");
            var myOptions = {
// you can declare a default color here,
// or in the data-default-color attribute on the input
                defaultColor: false,
// a callback to fire whenever the color changes to a valid color
                change: function () {
                    TS_Ch_Inp_1_Back_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_1_Back_Color"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_1_Border_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_1_Border_Color"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_1_Inp_Shadow_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_1_Shadow_Color"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_1_Inp_Font_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_inp"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_1_Inp_Icon_Color(jQuery(".TS_PTable_Color_Icon_" + Total_Soft_PTable_Set_Count + "_Inp_1_Icon"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_1_Inp_PV_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_1_Price"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_1_Inp_PL_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_1_Plan"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_1_Inp_PVColor1(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_Color1"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_1_Inp_PVColor2(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_Color2"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_InpText1(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_Text1"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_InpText2(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_Text2"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_1_Feut_Icon_Col(jQuery(".TS_PTable_Color_Icon_" + Total_Soft_PTable_Set_Count + "_1_Feut"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_1_PL_Icon(jQuery(".TS_PTable_Color_Icon" + Total_Soft_PTable_Set_Count + "_Icon"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_1_But_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_Color3"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_1_But_Font_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_Color4"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_1_ButIcon_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_Color5"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);

                    TS_Ch_Inp_2_Back_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_2_Back_Color"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_2_Inp_Shadow_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_2_Shadow_Color"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_2_Inp_Font_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_2_Title"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_2_Inp_Icon_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_2_Icon"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp2ST(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_2PT"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_2_PV_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_Amount"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_2_PPlan_Hover_Background(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_2_hov_back"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_2_PPlan_Hover_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_2_hov_Color"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_2_Feut_Back(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_2_Back"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_2_Feut_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_2_Text"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_Feut_2_Icon_Col(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_2_Feut"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_Feut_2_Icon_Col_Sel(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_2_Feut_Sel"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);

                    TS_Ch_Inp_3_Back_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_3_Back_Color"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_3_Border_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_3_Border_Color"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_3_Inp_Shadow_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_3_Shadow_Color"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_3_Inp_Font_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_3_Title_Color"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_3_Inp_PV_Font_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_3_Price_Color"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_3_Title_Back(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_BG_Title"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_3_Inp_Icon_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_3_Icon_Color"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_3_Inp_Icon_Back(jQuery(".TS_PTable_Color_Icon_" + Total_Soft_PTable_Set_Count + "_I_BG"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_3_Icon_Hover_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_3_Icon_Hov_Color"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_3_Icon_Hover_Background(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_3_Back_Hov_Color"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_3_Feut_Back(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_3_Back"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_3_Feut_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_3_Text"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_Feut_3_Icon_Col(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_3_Feut"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_Feut_3_Icon_Col_Sel(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_3_Feut_Sel"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_3_BT_Back(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_3_BT"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_3_BT_Text(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_3_BTExt"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_3_BT_Back_Hover(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_3_Bt_Back_Hover"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_3_BT_Color_Hover(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_3_Bt_Color_Hover"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);

                    TS_Ch_Inp_4_Back_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_4_Back_Color"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_4_Back_Hover_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_4_Back_Hover_Color"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_4_Inp_Shadow_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_4_Shadow_Color"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_4_Inp_Font_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_4_Title_Color"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_4_Inp_Font_Hover_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_4_Title_Hover_Color"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_4_Inp_PV_Font_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_4_Price_Color"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_4_Inp_PV_Font_Hover_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_4_Price_Hover_Color"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_4_Feut_Back(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_4_Back"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_4_Feut_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_4_Text"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_Feut_4_Icon_Col(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_4_Feut"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_Feut_4_Icon_Col_Sel(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_4_Feut_Sel"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_4_BT_Back(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_4_BT"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_4_BT_Text(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_4_BTExt"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_4_BT_Back_Hover(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_4_Bt_Back_Hover"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_4_BT_Color_Hover(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_4_Bt_Color_Hover"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);

                    TS_Ch_Inp_5_Back_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_5_Back_Color"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_5_Inp_Font_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_5_Title_Color"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_5_Inp_Icon_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_5_Icon_Color"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_5_Inp_Icon_Hover_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_5_Icon_Hover_Color"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_5_Inp_Shadow_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_5_Shadow_Color"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_5_Inp_PV_Font_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_5_Price_Color"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_5_Inp_PV_Font_Hover_Color(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_5_Price_Hover_Color"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_5_PV_Back_Hover(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_5_PV_Hover_Back"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_5_PV_Color_Hover(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_5_PV_Hover_Color"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_5_Feut_Text(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_5_Feut_Text"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_5_Feut_Icon_Col(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_5_Feut"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_5_Feut_Icon_Sel_Col(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_Icon_Sel"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_5_BT_Back(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_5_But_Color"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                    TS_Ch_Inp_5_BT_Text(jQuery(".TS_PTable_Color_" + Total_Soft_PTable_Set_Count + "_5_But_text_Color"), Total_Soft_PTable_Set_Count, Total_Soft_PTable_Col_Type);
                },

// a callback to fire when the input is emptied or an invalid color
                clear: function () {
                },
// hide the color picker controls on load
                hide: true,
// show a group of common colors beneath the square
// or, supply an array of colors to customize further
                palettes: true
            };
            jQuery('.TS_PTable_Color').wpColorPicker(myOptions);
            TS_PTable_Out();
            jQuery('.Total_Soft_PTable_TMMTable').animate({'opacity': 0}, 500);
            jQuery('.Total_Soft_PTable_TMOTable').animate({'opacity': 0}, 500);
            setTimeout(function () {
                jQuery('.Total_Soft_PTable_TMMTable').css('display', 'none');
                jQuery('.Total_Soft_PTable_TMOTable').css('display', 'none');
                jQuery('.Total_Soft_PTable_AMD3').css('display', 'block');
                jQuery('.Total_Soft_PTable_AMMain_Div').css('display', 'block');
                jQuery('.cancel').attr('onclick','TotalSoftPTable_Reload()').css('background-color', '#009491')
            }, 500)
            setTimeout(function () {
                jQuery('.Total_Soft_PTable_AMD3').animate({'opacity': 1}, 500);
                jQuery('.Total_Soft_PTable_AMMain_Div').animate({'opacity': 1}, 500);
                jQuery('.Total_Soft_PTable_Loading').css('display', 'none');
            }, 500)
        }
    });
}
}

function TotalSoftPTable_Dup_Col(Col_index, Col_Count, This_Col) {
    if (flag=='true') {
       
     
     return false;
    }
    flag='true';
     jQuery('.Total_Soft_PTable_AddColBut').css("background-color", "#ffc593");
     jQuery('.Total_Soft_PTable_AMMain_Div2_Cols1_Action2 i').css("color", "#ffc593");
    var col_type = 1;
    if (Total_Soft_PTable_Col_Type == "type2") {
        col_type = 2;
    } else if (Total_Soft_PTable_Col_Type == "type3") {
        col_type = 3;
    } else if (Total_Soft_PTable_Col_Type == "type4") {
        col_type = 4;
    } else if (Total_Soft_PTable_Col_Type == "type5") {
        col_type = 5;
    }

    var Total_Soft_PTable_Col_Sel_Count = parseInt(parseInt(jQuery('#Total_Soft_PTable_Col_Sel_Count').val()) + 1);
    jQuery('#Total_Soft_PTable_Col_Sel_Count').val(Total_Soft_PTable_Col_Sel_Count);
    var Total_Soft_PTable_Add_Set = parseInt(parseInt(jQuery('#Total_Soft_PTable_Add_Set').val()) + 1);
    let PTable_Man_ID = jQuery('#Total_SoftPTable_Update').val();
    jQuery('#Total_Soft_PTable_Add_Set').val(Total_Soft_PTable_Add_Set);
    var last_id = 0;
    var last_Index = 0;
   
    var Total_Soft_PTable_Add_Col = parseInt(parseInt(jQuery("#Total_Soft_PTable_Col_Id").val()) + 1);
    var Total_Soft_PTable_Col_Count = parseInt(parseInt(jQuery("#Total_Soft_PTable_Col_Count").val()) + 1);
    id_col_index++;
    jQuery("#Total_Soft_PTable_Col_Id").val(Total_Soft_PTable_Add_Col);
    jQuery("#Total_Soft_PTable_Col_Count").val(Total_Soft_PTable_Col_Count);
    var TS_PTable_FIcon = [];
    var TS_PTable_FCheck = [];
    var TS_PTable_FText = [];
    var TS_PTable_FIcon_Im = [];
    var TS_PTable_FCheck_Im = [];
    var TS_PTable_FText_Im = [];
    var data = [];
    var dataSet = [];
    var number = 0;


    jQuery.ajax({
        type: 'POST',
        url: object.ajaxurl,
        data: {
            action: 'Total_Soft_PTable_Sort_Index'
        },
        beforeSend: function () {
        },
        success: function (response) {
             var datas = JSON.parse(response);
             last_Index=parseInt(datas)+id_col_index;
           
             last_id = last_Index+1;
              jQuery("#Total_Soft_PTable_New_Col_Last_Id").val(last_id);
              number = last_id;
    if (!New_Col_data.length) {
        var Total_Soft_PTable_newCol = jQuery("#TS_PTable_Col_" + Col_index)[0];
        let new_set_col = {
            PTable_ID: PTable_Man_ID,
            TS_PTable_ST_00: "Theme_" + last_id,
            TS_PTable_ST_01: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_01']").val(),
            TS_PTable_ST_02: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_02']").val(),
            TS_PTable_ST_03: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_03']").val(),
            TS_PTable_ST_04: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_04']").val(),
            TS_PTable_ST_05: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_05']").val(),
            TS_PTable_ST_06: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_06']").val(),
            TS_PTable_ST_07: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_07']").val(),
            TS_PTable_ST_08: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_08']").val(),
            TS_PTable_ST_09: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_09']").val(),
            TS_PTable_ST_10: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_10']").val(),
            TS_PTable_ST_11: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_11']").val(),
            TS_PTable_ST_12: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_12']").val(),
            TS_PTable_ST_13: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_13']").val(),
            TS_PTable_ST_14: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_14']").val(),
            TS_PTable_ST_15: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_15']").val(),
            TS_PTable_ST_16: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_16']").val(),
            TS_PTable_ST_17: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_17']").val(),
            TS_PTable_ST_18: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_18']").val(),
            TS_PTable_ST_19: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_19']").val(),
            TS_PTable_ST_20: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_20']").val(),
            TS_PTable_ST_21: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_21']").val(),
            TS_PTable_ST_21_1: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_21_1']").val(),
            TS_PTable_ST_22: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_22']").val(),
            TS_PTable_ST_23: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_23']").val(),
            TS_PTable_ST_24: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_24']").val(),
            TS_PTable_ST_25: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_25']").val(),
            TS_PTable_ST_26: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_26']").val(),
            TS_PTable_ST_27: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_27']").val(),
            TS_PTable_ST_28: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_28']").val(),
            TS_PTable_ST_29: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_29']").val(),
            TS_PTable_ST_30: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_30']").val(),
            TS_PTable_ST_31: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_31']").val(),
            TS_PTable_ST_32: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_32']").val(),
            TS_PTable_ST_33: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_33']").val(),
            TS_PTable_ST_34: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_34']").val(),
            TS_PTable_ST_35: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_35']").val(),
            TS_PTable_ST_36: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_36']").val(),
            TS_PTable_ST_37: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_37']").val(),
            TS_PTable_ST_38: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_38']").val(),
            TS_PTable_ST_39: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_39']").val(),
            TS_PTable_ST_40: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_ST" + col_type + "_" + Col_Count + "_40']").val(),
            TS_PTable_TType: Total_Soft_PTable_Col_Type,
            id: last_id,
            index: last_Index
        }

         jQuery(Total_Soft_PTable_newCol).find(".TS_PTable_Features li").each(function(){

      if (jQuery(this).find('table').length) {
            TS_PTable_FIcon.push(jQuery(this).find('table td:nth-child(2) input').val());
         TS_PTable_FText.push(jQuery(this).find('table td:nth-child(1) input').val());
        }else{
         TS_PTable_FIcon.push(jQuery(this).find('.feut_icon_cont input').val());
         TS_PTable_FText.push(jQuery(this).find('.feut_text_cont input').val());
        }

      if (jQuery(this).find('.TS_PTable_FChecked input').hasClass('TS_PTable_FCheck')) {
                TS_PTable_FCheck.push(jQuery(this).find('.TS_PTable_FChecked input').val());
            } else {
                TS_PTable_FCheck.push("");
            }
     
    });

     
        TS_PTable_FIcon_Im = TS_PTable_FIcon.join('TSPTFI');
        TS_PTable_FCheck_Im = TS_PTable_FCheck.join('TSPTFC');
        TS_PTable_FText_Im = TS_PTable_FText.join('TSPTFT');

        let new_cop_col = {
            PTable_ID: PTable_Man_ID,
            TS_PTable_BIcon: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_BIcon_" + Col_Count + "']").val(),
            TS_PTable_BLink: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_BLink_" + Col_Count + "']").val(),
            TS_PTable_BText: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_BText_" + Col_Count + "']").val(),
            TS_PTable_C_01: TS_PTable_FCheck_Im,
            TS_PTable_FCount: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_FCount_" + Col_Count + "']").val(),
            TS_PTable_FIcon: TS_PTable_FIcon_Im,
            TS_PTable_FText: TS_PTable_FText_Im,
            TS_PTable_PCur: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_PCur_" + Col_Count + "']").val(),
            TS_PTable_PPlan: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_PPlan_" + Col_Count + "']").val(),
            TS_PTable_PVal: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_PVal_" + Col_Count + "']").val(),
            TS_PTable_TIcon: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_TIcon_" + Col_Count + "']").val(),
            TS_PTable_TSetting: last_id,
            TS_PTable_TText: jQuery(Total_Soft_PTable_newCol).find("input[name*='TS_PTable_TText_" + Col_Count + "']").val(),
            TS_PTable_TType: Total_Soft_PTable_Col_Type,
            id: last_id,
            index: last_Index
        }
        New_Cop_Set.push(new_set_col);
        data.push(new_cop_col);
        data.push(new_set_col);
    } else {
        for (i = 0; i < New_Col_data[0].length; i++) {
            if (New_Col_data[0][i]['TS_PTable_TType'] == Total_Soft_PTable_Col_Type) {
                data.push(New_Col_data[0][i]);

                TS_PTable_FText = data[0]['TS_PTable_FText'].split('TSPTFT');
                TS_PTable_FIcon = data[0]['TS_PTable_FIcon'].split('TSPTFI');
                TS_PTable_FCheck = data[0]['TS_PTable_C_01'].split('TSPTFC');
                New_Col_data[1][i]['id'] = last_id;
                data.push(New_Col_data[1][i]);
                New_Cop_Set.push(New_Col_data[1][i]);
            }
        }
    }
    Col_Count = Total_Soft_PTable_Col_Sel_Count;

    let Total_Soft_PTable_M_03 = jQuery('#Total_Soft_PTable_M_03').val();

    if (Total_Soft_PTable_Col_Type == "type1") {
        jQuery('.TS_PTable_Container').append('' +
            '    <style type="text/css">' +
            '            .TS_PTable_Container_Col_' + last_id + ' {' +
            '            position: relative;' +
            '            min-height: 1px;' +
            '            width: ' + data[1]["TS_PTable_ST_01"] + '%;' +
            '            margin-bottom: 40px;' +
            '        }' +
            '        @media not screen and (min-width: 820px) {' +
            '            .TS_PTable_Container_Col_' + last_id + ' {' +
            '                width: 70%;' +
            '                margin: 0 15% 40px 15%;' +
            '                padding: 0 10px;' +
            '            }' +
            '        }' +
            '    @media not screen and (min-width: 400px) {' +
            '        .TS_PTable_Container_Col_' + last_id + ' {' +
            '            width: 100%;' +
            '            margin: 0 0 40px 0;' +
            '            padding: 0 5px;' +
            '        }' +
            '    }' +
            '        .TS_PTable_Shadow_' + last_id + ' {' +
            '            position: relative;' +
            '            z-index: 0;' +
            '        }' +
            '        +if( data[1]["TS_PTable_ST_06"] == "none") {+' +
            '            .TS_PTable_Shadow_' + last_id + ' {' +
            '                box-shadow: none !important;' +
            '                -moz-box-shadow: none !important;' +
            '                -webkit-box-shadow: none !important;' +
            '            }' +
            '        +}' +
            '        +else if( data[1]["TS_PTable_ST_06"] == "shadow01") {+' +
            '            .TS_PTable_Shadow_' + data[0]['id'] + ' {' +
            '                box-shadow: 0 10px 6px -6px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '                -webkit-box-shadow: 0 10px 6px -6px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '                -moz-box-shadow: 0 10px 6px -6px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '            }' +
            '        + }' +
            '        +else if( data[1]["TS_PTable_ST_06"] == "shadow02") {+' +
            '           .TS_PTable_Shadow_' + last_id + ':before, .TS_PTable_Shadow_' + last_id + ':after {' +
            '               bottom: 15px;' +
            '               left: 10px;' +
            '               width: 50%;' +
            '               height: 20%;' +
            '               max-width: 300px;' +
            '               max-height: 100px;' +
            '               -webkit-box-shadow: 0 15px 10px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '               -moz-box-shadow: 0 15px 10px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '               box-shadow: 0 15px 10px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '               -webkit-transform: rotate(-3deg);' +
            '               -moz-transform: rotate(-3deg);' +
            '               -ms-transform: rotate(-3deg);' +
            '               -o-transform: rotate(-3deg);' +
            '               transform: rotate(-3deg);' +
            '               z-index: -1;' +
            '               position: absolute;' +
            '               content: "";' +
            '           }' +
            '           .TS_PTable_Shadow_' + last_id + ':after {' +
            '               transform: rotate(3deg);' +
            '               -moz-transform: rotate(3deg);' +
            '               -webkit-transform: rotate(3deg);' +
            '               right: 10px;' +
            '               left: auto;' +
            '           }' +
            '        }' +
            '        +else if( data[1]["TS_PTable_ST_06"] == "shadow03") {+' +
            '            .TS_PTable_Shadow_' + last_id + ':before {' +
            '                bottom: 15px;' +
            '                left: 10px;' +
            '                width: 50%;' +
            '                height: 20%;' +
            '                max-width: 300px;' +
            '                max-height: 100px;' +
            '                -webkit-box-shadow: 0 15px 10px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '                -moz-box-shadow: 0 15px 10px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '                box-shadow: 0 15px 10px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '                -webkit-transform: rotate(-3deg);' +
            '                -moz-transform: rotate(-3deg);' +
            '                -ms-transform: rotate(-3deg);' +
            '                -o-transform: rotate(-3deg);' +
            '                transform: rotate(-3deg);' +
            '                z-index: -1;' +
            '                position: absolute;' +
            '                content: "";' +
            '            }' +

            '        +}' +
            '        +else if( data[1]["TS_PTable_ST_06"] == "shadow04") {+' +
            '            .TS_PTable_Shadow_' + last_id + ':after {' +
            '                bottom: 15px;' +
            '                right: 10px;' +
            '                width: 50%;' +
            '                height: 20%;' +
            '                max-width: 300px;' +
            '                max-height: 100px;' +
            '                -webkit-box-shadow: 0 15px 10px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '                -moz-box-shadow: 0 15px 10px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '                box-shadow: 0 15px 10px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '                -webkit-transform: rotate(3deg);' +
            '                -moz-transform: rotate(3deg);' +
            '                -ms-transform: rotate(3deg);' +
            '                -o-transform: rotate(3deg);' +
            '                transform: rotate(3deg);' +
            '                z-index: -1;' +
            '                position: absolute;' +
            '                content: "";' +
            '            }' +

            '         +}' +
            '         +else if( data[1]["TS_PTable_ST_06"] == "shadow05") {+' +
            '             .TS_PTable_Shadow_' + last_id + ':before, .TS_PTable_Shadow_' + last_id + ':after {' +
            '                 top: 15px;' +
            '                 left: 10px;' +
            '                 width: 50%;' +
            '                 height: 20%;' +
            '                 max-width: 300px;' +
            '                 max-height: 100px;' +
            '                 z-index: -1;' +
            '                 position: absolute;' +
            '                 content: "";' +
            '                 background:  ' + data[1]["TS_PTable_ST_07"] + ';' +
            '                 box-shadow: 0 -15px 10px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '                 -webkit-box-shadow: 0 -15px 10px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '                 -moz-box-shadow: 0 -15px 10px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '                 transform: rotate(3deg);' +
            '                 -moz-transform: rotate(3deg);' +
            '                 -webkit-transform: rotate(3deg);' +
            '             }' +

            '             .TS_PTable_Shadow_' + last_id + ':after {' +
            '                 transform: rotate(-3deg);' +
            '                 -moz-transform: rotate(-3deg);' +
            '                 -webkit-transform: rotate(-3deg);' +
            '                 right: 10px;' +
            '                 left: auto;' +
            '             }' +

            '        +}' +
            '        +else if( data[1]["TS_PTable_ST_06"] == "shadow06") {+' +
            '            .TS_PTable_Shadow_' + last_id + ' {' +
            '                position: relative;' +
            '                box-shadow: 0 1px 4px ' + data[1]["TS_PTable_ST_07"] + ', 0 0 40px ' + data[1]["TS_PTable_ST_07"] + ' inset;' +
            '                -webkit-box-shadow: 0 1px 4px ' + data[1]["TS_PTable_ST_07"] + ', 0 0 40px  ' + data[1]["TS_PTable_ST_07"] + ' inset;' +
            '                -moz-box-shadow: 0 1px 4px ' + data[1]["TS_PTable_ST_07"] + ', 0 0 40px  ' + data[1]["TS_PTable_ST_07"] + 'inset;' +
            '            }' +

            '            .TS_PTable_Shadow_' + last_id + ':before, .TS_PTable_Shadow_' + last_id + ':after {' +
            '                content: "";' +
            '                position: absolute;' +
            '                z-index: -1;' +
            '                box-shadow: 0 0 20px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '                -webkit-box-shadow: 0 0 20px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '                -moz-box-shadow: 0 0 20px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '                top: 50%;' +
            '                bottom: 0;' +
            '                left: 10px;' +
            '                right: 10px;' +
            '                border-radius: 100px / 10px;' +
            '            }' +
            '        +}' +
            '        +else if( data[1]["TS_PTable_ST_06"] == "shadow07") {+' +
            '            .TS_PTable_Shadow_' + last_id + '{' +
            '                position: relative;' +
            '                box-shadow: 0 1px 4px ' + data[1]["TS_PTable_ST_07"] + ', 0 0 40px ' + data[1]["TS_PTable_ST_07"] + ' inset;' +
            '                -webkit-box-shadow: 0 1px 4px ' + data[1]["TS_PTable_ST_07"] + ', 0 0 40px ' + data[1]["TS_PTable_ST_07"] + ' inset;' +
            '                -moz-box-shadow: 0 1px 4px ' + data[1]["TS_PTable_ST_07"] + ', 0 0 40px ' + data[1]["TS_PTable_ST_07"] + ' inset;' +
            '            }' +
            '            .TS_PTable_Shadow_' + last_id + ':before, .TS_PTable_Shadow_' + last_id + ':after {' +
            '                content: "";' +
            '                position: absolute;' +
            '                z-index: -1;' +
            '                box-shadow: 0 0 20px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '                -webkit-box-shadow: 0 0 20px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '                -moz-box-shadow: 0 0 20px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '                top: 0;' +
            '                bottom: 0;' +
            '                left: 10px;' +
            '                right: 10px;' +
            '                border-radius: 100px / 10px;' +
            '            }' +
            '            .TS_PTable_Shadow_' + last_id + ':after {' +
            '                right: 10px;' +
            '                left: auto;' +
            '                transform: skew(8deg) rotate(3deg);' +
            '                -moz-transform: skew(8deg) rotate(3deg);' +
            '                -webkit-transform: skew(8deg) rotate(3deg);' +
            '            }' +
            '        +}' +
            '        +else if( data[1]["TS_PTable_ST_06"] == "shadow08") {+' +
            '            .TS_PTable_Shadow_' + last_id + '{' +
            '                position: relative;' +
            '                box-shadow: 0 1px 4px ' + data[1]["TS_PTable_ST_07"] + ', 0 0 40px ' + data[1]["TS_PTable_ST_07"] + ' inset;' +
            '                -webkit-box-shadow: 0 1px 4px ' + data[1]["TS_PTable_ST_07"] + ', 0 0 40px ' + data[1]["TS_PTable_ST_07"] + ' inset;' +
            '                -moz-box-shadow: 0 1px 4px ' + data[1]["TS_PTable_ST_07"] + ', 0 0 40px ' + data[1]["TS_PTable_ST_07"] + ' inset;' +
            '            }' +
            '            .TS_PTable_Shadow_' + last_id + ':before, .TS_PTable_Shadow_' + last_id + ':after {' +
            '                content: "";' +
            '                position: absolute;' +
            '                z-index: -1;' +
            '                box-shadow: 0 0 20px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '                -webkit-box-shadow: 0 0 20px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '                -moz-box-shadow: 0 0 20px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '                top: 10px;' +
            '                bottom: 10px;' +
            '                left: 0;' +
            '                right: 0;' +
            '                border-radius: 100px / 10px;' +
            '            }' +
            '            .TS_PTable_Shadow_' + last_id + ':after {' +
            '                right: 10px;' +
            '                left: auto;' +
            '                transform: skew(8deg) rotate(3deg);' +
            '                -moz-transform: skew(8deg) rotate(3deg);' +
            '                -webkit-transform: skew(8deg) rotate(3deg);' +
            '            }' +
            '        +}' +
            '    +else if( data[1]["TS_PTable_ST_06"] == "shadow09") {+' +
            '        .TS_PTable_Shadow_' + last_id + '{' +
            '            box-shadow: 0 0 10px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '            -webkit-box-shadow: 0 0 10px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '            -moz-box-shadow: 0 0 10px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '        }' +
            '    +}' +
            '    +else if( data[1]["TS_PTable_ST_06"] == "shadow10") {+' +
            '        .TS_PTable_Shadow_' + last_id + '{' +
            '            box-shadow: 4px -4px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '            -moz-box-shadow: 4px -4px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '            -webkit-box-shadow: 4px -4px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '        }' +
            '    +}' +
            '    +else if( data[1]["TS_PTable_ST_06"] == "shadow11") {+' +
            '        .TS_PTable_Shadow_' + last_id + '{' +
            '            box-shadow: 5px 5px 3px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '            -moz-box-shadow: 5px 5px 3px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '            -webkit-box-shadow: 5px 5px 3px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '        }' +
            '    +}' +
            '    +else if( data[1]["TS_PTable_ST_06"] == "shadow12") {+' +
            '        .TS_PTable_Shadow_' + last_id + '{' +
            '            box-shadow: 2px 2px white, 4px 4px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '            -moz-box-shadow: 2px 2px white, 4px 4px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '            -webkit-box-shadow: 2px 2px white, 4px 4px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '        }' +
            '    +}' +
            '    +else if( data[1]["TS_PTable_ST_06"] == "shadow13") {+' +
            '        .TS_PTable_Shadow_' + last_id + '{' +
            '            box-shadow: 8px 8px 18px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '            -moz-box-shadow: 8px 8px 18px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '            -webkit-box-shadow: 8px 8px 18px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '        }' +
            '    +}' +
            '    +else if( data[1]["TS_PTable_ST_06"] == "shadow14") {+' +
            '        .TS_PTable_Shadow_' + last_id + '{' +
            '            box-shadow: 0 8px 6px -6px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '            -moz-box-shadow: 0 8px 6px -6px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '            -webkit-box-shadow: 0 8px 6px -6px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '        }' +
            '    +}' +
            '    +else if( data[1]["TS_PTable_ST_06"] == "shadow15") {+' +
            '        .TS_PTable_Shadow_' + last_id + '{' +
            '            box-shadow: 0 0 18px 7px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '            -moz-box-shadow: 0 0 18px 7px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '            -webkit-box-shadow: 0 0 18px 7px ' + data[1]["TS_PTable_ST_07"] + ';' +
            '        }' +
            '    +}' +
            '    .TS_PTable__' + last_id + ' {' +
            '        padding: 30px 0 !important;' +
            '        border: ' + data[1]["TS_PTable_ST_05"] + 'px solid ' + data[1]["TS_PTable_ST_04"] + ';' +
            '        text-align: center;' +
            '        overflow-x: hidden;' +
            '        position: relative;' +
            '        background-color: ' + data[1]["TS_PTable_ST_03"] + ';' +
            '    }' +
            '    .TS_PTable__' + last_id + ':before {' +
            '        content: "";' +
            '        border-right: 70px solid ' + data[1]["TS_PTable_ST_28"] + ';' +
            '        border-top: 70px solid transparent;' +
            '        border-bottom: 70px solid transparent;' +
            '        position: absolute;' +
            '        top: 30px;' +
            '        right: -100px;' +
            '        transition: all 0.3s ease 0s;' +
            '    }' +
            '    .TS_PTable__' + last_id + ':hover:before {' +
            '        right: 0;' +
            '    }' +
            '    .TS_PTable_Title_' + last_id + ' {' +
            '        font-size: ' + data[1]["TS_PTable_ST_08"] + 'px;' +
            '        font-family: ' + data[1]["TS_PTable_ST_09"] + ';' +
            '        color: ' + data[1]["TS_PTable_ST_10"] + ';' +
            '        margin: 10px 0 !important;' +
            '        padding: 0 !important;' +
            '    }' +

            '    .TS_PTable_Title_IconTB_' + last_id + ' {' +
            '        display: block;' +
            '    }' +

            '    .TS_PTable_Title_IconLR_' + last_id + ' {' +
            '        margin: 0 10px !important;' +
            '    }' +

            '    .TS_PTable_Title_Icon_' + last_id + ' {' +
            '        color: ' + data[1]["TS_PTable_ST_11"] + ';' +
            '        font-size: ' + data[1]["TS_PTable_ST_12"] + 'px;' +
            '    }' +

            '    .TS_PTable_PValue_' + last_id + ' {' +
            '        font-size: ' + data[1]["TS_PTable_ST_14"] + 'px;' +
            '        font-family: ' + data[1]["TS_PTable_ST_15"] + ';' +
            '        color: ' + data[1]["TS_PTable_ST_16"] + ';' +

            '    }' +

            '    .TS_PTable_PPlan_' + last_id + ' {' +
            '        display: inline-block;' +
            '        font-size: ' + data[1]["TS_PTable_ST_17"] + 'px;' +
            '        color: ' + data[1]["TS_PTable_ST_18"] + ';' +
            '    }' +

            '    .TS_PTable_Features_' + last_id + ', .TS_PTable_Features_Add {' +
            '        padding: 0 !important;' +
            '        margin: 20px 0 !important;' +
            '        list-style: none;' +
            '    }' +

            '    .TS_PTable_Features_' + last_id + ' li:before {' +
            '        content: "" !important;' +
            '        display: none !important;' +
            '    }' +


            '    .TS_PTable_Features_' + last_id + ' li, .TS_PTable_Features_Add li {' +
            '        font-size: ' + data[1]["TS_PTable_ST_22"] + 'px;' +
            '        font-family: ' + data[1]["TS_PTable_ST_23"] + ';' +
            '        padding: 8px;' +
            '        display: flex;' +
            '        align-items: center;' +
            '        justify-content: center;' +
            '    }' +

            '    .TS_PTable_Features_' + last_id + ' li:nth-child(even), .TS_PTable_Features_Add li:nth-child(even) {' +
            '        color: ' + data[1]["TS_PTable_ST_21"] + ';' +
            '        background: ' + data[1]["TS_PTable_ST_19"] + ';' +
            '    }' +

            '    .TS_PTable_Features_' + last_id + ' li:nth-child(odd), .TS_PTable_Features_Add li:nth-child(odd) {' +
            '        color: ' + data[1]["TS_PTable_ST_21_1"] + ';' +
            '        background: ' + data[1]["TS_PTable_ST_20"] + ';' +
            '    }' +

            '    .TS_PTable_FIcon_' + last_id + ' {' +
            '        color: ' + data[1]["TS_PTable_ST_24"] + ';' +
            '        font-size: ' + data[1]["TS_PTable_ST_26"] + 'px;' +
            '        margin: 0 10px !important;' +
            '    }' +

            '    .TS_PTable_FIcon_' + last_id + '.TS_PTable_FCheck {' +
            '        color: ' + data[1]["TS_PTable_ST_25"] + ';' +
            '    }' +

            '    .TS_PTable_Button_' + last_id + ' {' +
            '        display: inline-block;' +
            '        padding: 7px 30px !important;' +
            '        background: ' + data[1]["TS_PTable_ST_28"] + ';' +
            '        color: ' + data[1]["TS_PTable_ST_29"] + ';' +
            '        font-size: ' + data[1]["TS_PTable_ST_30"] + 'px;' +
            '        font-family: ' + data[1]["TS_PTable_ST_31"] + ';' +
            '        text-decoration: none;' +
            '        outline: none;' +
            '        box-shadow: none;' +
            '        -webkit-box-shadow: none;' +
            '        -moz-box-shadow: none;' +
            '        border-bottom: none;' +
            '        transition: all 0.5s ease 0s;' +
            '        cursor: pointer !important;' +
            '            margin:0' +
            '    }' +

            '    .TS_PTable_Button_' + last_id + ':hover, .TS_PTable_Button_' + last_id + ':focus {' +
            '        text-decoration: none;' +
            '        outline: none;' +
            '        box-shadow: none;' +
            '        -webkit-box-shadow: none;' +
            '        -moz-box-shadow: none;' +
            '        border-bottom: none;' +
            '        background: ' + data[1]["TS_PTable_ST_28"] + ';' +
            '        color: ' + data[1]["TS_PTable_ST_29"] + ';' +
            '        font-size: ' + data[1]["TS_PTable_ST_30"] + 'px;' +
            '        font-family: ' + data[1]["TS_PTable_ST_31"] + ';' +
            '    }' +

            '    .TS_PTable_BIconA_' + last_id + ', .TS_PTable_BIconB_' + last_id + ' {' +
            '        font-size: ' + data[1]["TS_PTable_ST_32"] + 'px;' +
            '        color: ' + data[1]["TS_PTable_ST_33"] + ';' +
            '    }' +

            '    .TS_PTable_BIconB_' + last_id + ' {' +
            '        margin: 0 10px 0 0 !important;' +
            '    }' +

            '    .TS_PTable_BIconA_' + last_id + '{' +
            '        margin: 0 10px !important;' +
            '    }' +

            '    .TS_PTable__' + last_id + ':hover .TS_PTable_Button_' + last_id + ' {' +
            '        border-radius: 30px;' +
            '    }' +
            '</style>' +
            ' <div class="TS_PTable_Container_Col_Copy TS_PTable_Container_Col_' + last_id + ' Total_Soft_PTable_AMMain_Div2_Cols1" id="TS_PTable_Col_' + last_id + '">' +
            '    <input type="text" style="display:none;" class="Total_Soft_PTable_Select" name="TS_PTable_ST1_' + Col_Count + '_ID" id="TS_PTable_ST1_' + data[1]['id'] + '_ID" value="' + data[1]['id'] + '">' +
            '    <input type="text" style="display:none;" class="Total_Soft_PTable_Select" name="TS_PTable_ST1_' + Col_Count + '_00" id="TS_PTable_ST1_' + data[1]['id'] + '_00" value="Theme_' + data[1]['id'] + '">' +
            '    <input type="text" style="display:none;" class="Total_Soft_PTable_Select" name="TS_PTable_ST1_' + Col_Count + '_index" id="TS_PTable_ST1_' + data[1]['id'] + '_index" value="' + parseInt(parseInt(last_id) - 1) + '">' +
            '    <input type="text" style="display:none" name="Total_Soft_PTable_Cols_Id" class="Total_Soft_PTable_Cols_Id" value="' + last_id + '">' +
            '    <input type="text" style="display:none" name="Total_Soft_PTable_Set_Title" class="Total_Soft_PTable_Set_Title" value="Theme_' + last_id + '">' +
            '       <div class="TS_PTable_Parent">' +
            '           <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Actions">' +
            '              <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action1">' +
            '                 <i class="totalsoft totalsoft-arrows" title="Reorder" onmouseup="TotalSoftPTable_ColumnDivdel()" onmousedown="TotalSoftPTable_ColumnDivSort(' + number + ',' + data[1]['index'] + ')"></i>' +
            '              </div>' +
            '              <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action2">' +
            '                  <i class="totalsoft totalsoft-file-text" title="Make Duplicate" onClick="TotalSoftPTable_Dup_Col(' + last_id + ', ' + Col_Count + ')"></i>' +
            '              </div>' +
            '              <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action3">' +
            '                  <i class="totalsoft totalsoft-trash" title="Delete Column" onClick="TotalSoftPTable_Del_Col(' + last_id + ')"></i>' +
            '              </div>' +
            '              <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action4">' +
            '                  <i class="totalsoft totalsoft-pencil" title="Edit Column" onClick="TotalSoftPTable_Col_Dragdown(); TotalSoftPTable_Edit_Theme(this,' + last_id + ')"></i>' +
            '              </div>' +
            '           </div>' +
            '           <div class="TS_PTable_Shadow_' + last_id + '">' +
            '               <div class="TS_PTable__' + last_id + ' TS_PTable_Parent">' +
            '                   <div class="relative title_Position_' + last_id + '">' +
            '                       <span class=" TS_PTable_Title_IconTB_' + last_id + ' TS_PTable_Title_Icon_" >' +
            '                            <i onClick="TS_Get_Icon_Title(this, ' + last_id + ')" class="total_Soft_Icon_Change  TS_PTable_Title_Icon_' + last_id + ' totalsoft totalsoft-' + ((data[0]['TS_PTable_TIcon']=='none' )?'plus-square-o':data[0]['TS_PTable_TIcon']) + '"></i>' +
            '                           <input type="hidden" class="Total_Soft_PTable_Select" id="TS_PTable_TIcon_' + last_id + '" name="TS_PTable_TIcon_' + Col_Count + '" value="' + data[0]['TS_PTable_TIcon'] + '">' +
            '                        </span>' +
            '                       <div class="title_h3_Container">' +
            '                           <h3 class="h3_hover TS_PTable_Title_' + last_id + ' TS_PTable_Title_" onInput="TS_Change_H3_Val(this)" contentEditable="true" onClick="jQuery(this).focus();">' + data[0]['TS_PTable_TText'] + '</h3>' +
            '                           <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_TText_Hidden TS_PTable_TText" id="TS_PTable_New_TText_' + last_id + '" name="TS_PTable_TText_' + Col_Count + '" value="' + data[0]['TS_PTable_TText'] + '">' +
            '                       </div>' +
            '                   </div>' +
            '                   <div class=" span_hover TS_PTable_PValue_' + last_id + '">' +
            '                       <span class="TS_PTable_Cur_' + last_id + '" contentEditable="true" oninput="TS_Change_Cur_Val(this)" onClick="jQuery(this).focus();">' + data[0]['TS_PTable_PCur'] + '</span>' +
            '                       <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_PCur_Hidden" id="TS_PTable_New_PCur_' + last_id + '" name="TS_PTable_PCur_' + Col_Count + '" value="' + data[0]['TS_PTable_PCur'] + '">' +
            '                       <span class="TS_PTable_Val_' + last_id + '" contentEditable="true" oninput="TS_Change_Val_Val(this)" onClick="jQuery(this).focus();">' + data[0]['TS_PTable_PVal'] + '</span>' +
            '                       <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_PVal_Hidden" id="TS_PTable_New_PVal_' + last_id + '" name="TS_PTable_PVal_' + Col_Count + '" value="' + data[0]['TS_PTable_PVal'] + '">' +
            '                       <span class=" TS_PTable_PPlan_' + last_id + '" contentEditable="true" oninput="TS_Change_Plan_Val(this)" onClick="jQuery(this).focus();">' + data[0]['TS_PTable_PPlan'] + '</span>' +
            '                       <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_PPlan_Hidden" id="TS_PTable_New_PPlan_' + last_id + '" name="TS_PTable_PPlan_' + Col_Count + '" value="' + data[0]['TS_PTable_PPlan'] + '">' +
            '                   </div>' +
            '                    <div class="relative feuture_cont_' + last_id + '">' +
            '                       <ul class="TS_PTable_Features_' + last_id + ' TS_PTable_Features ">');
        var TS_PTable_FChek = '';
          var Total_Soft_PTable_Select_Icon = jQuery('#Total_Soft_PTable_Select_Icon').html();
        for (j = 0; j < data[0]['TS_PTable_FCount']; j++) {
            if (TS_PTable_FCheck[j] != '') {
                TS_PTable_FChek = 'TS_PTable_FCheck';
            } else {
                TS_PTable_FChek = '';
            }
            jQuery(".TS_PTable_Features_" + last_id).append('' +
                '                       <li onMouseOver="TS_PTable_Features_Li(this)" onMouseOut="TS_PTable_Features_Li_Out(this)" class="TS_Li_' + last_id + '_' + parseInt(parseInt(j) + 1) + '">' +
                '                                   <div class="Hiddem_Li_Container">' +
                '                                   <span class="hiddenChangeText"><i class="totalsoft totalsoft-times TS_PTable_FDI TS_PTable_FDI_' + last_id + '" title="Delete Feature" onClick="TotalSoftPTable_Del_FT(' + last_id + ', ' + parseInt(parseInt(j) + 1) + ')"></i>     </span>' +
                '                                   <span class="TS_PTable_FChecked TS_PTable_FCheckedHide TS_PTable_FChecked_' + last_id + '">' +
                '                                           <input type="checkbox" onClick="TS_PTable_TS_PTable_FChecked_label(this,' + Col_Count + ')" class="' + TS_PTable_FChek + '" id="TS_PTable_FChecked_' + last_id + '_' + parseInt(parseInt(j) + 1) + '" name="TS_PTable_FChecked_' + Col_Count + '_' + parseInt(parseInt(j) + 1) + '" value="' + parseInt(parseInt(j) + 1) + '">' +
                '                                           <label class="totalsoft totalsoft-question-circle-o" for="TS_PTable_FChecked_' + last_id + '_' + parseInt(parseInt(j) + 1) + '"></label>' +
                '                                   </span>' +
                '                               </div>' +
                '                               <div class="feut_container_' + last_id + ' feut_container">' +
                '                                   <div class="feut_text_cont">' +
                '                                       <span onmouseover="TS_Change_Feut_Val_Hover(this)" onmouseout="TS_Change_Feut_Val_Over(this)" class="TS_PTable_FText_' + last_id + '_' + Col_Count + '" oninput="TS_Change_Feut_Val(this)" contentEditable="true" onClick="jQuery(this).focus();">' + TS_PTable_FText[j] + '</span>' +
                '                                       <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_FText_' + last_id + ' TS_PTable_Feut_Hidden"  name="TS_PTable_FText_' + Col_Count + '_' + parseInt(parseInt(j) + 1) + '" value="' + TS_PTable_FText[j] + '">' +
                '                                   </div>' +
                '                                   <div class="feut_icon_cont">' +
                '                                       <i onClick="TS_Get_Icon_Feuture(this, ' + last_id + ', ' + parseInt(parseInt(j) + 1) + ')" class="totalsoft  TS_PTable_FIcon_' + last_id + ' ' + TS_PTable_FChek + ' totalsoft-' + (TS_PTable_FIcon[j]!='none'?TS_PTable_FIcon[j]:'plus-square-o ') + '"></i>' +
                '                                       <input type="hidden"  class="Total_Soft_PTable_Select  TS_PTable_FIcon_' + last_id + '_' + parseInt(parseInt(j) + 1) + '" name="TS_PTable_FIcon_' + Col_Count + '_' + parseInt(parseInt(j) + 1) + '" value="' + TS_PTable_FIcon[j] + '">' +
                '                                   </div>' +
                '                               </div>' +
                '                         </li>')
        }
        jQuery(".feuture_cont_" + last_id).append('' +
            '                      </ul>' +
            '                      <input type="text" style="display: none;" class="TS_PTable_FCount" id="TS_PTable_FCount_' + last_id + '" name="TS_PTable_FCount_' + Col_Count + '" value="' + data[0]['TS_PTable_FCount'] + '">' +
            '                  </div>' +
            '                  <table id="Total_Soft_PTable_Features_Col_' + last_id + '"></table>' +
            '                  <div class="Total_Soft_PTable_Features_New" onClick="Total_Soft_PTable_Features_New(' + last_id + ');Total_Soft_PTable_Features_New_Col(' + last_id + ',' + Col_Count + ',1)">' +
            '                      <span class="Total_Soft_PTable_Features_New1">' +
            '                         <i class="Total_Soft_PTable_Features_New_Icon totalsoft totalsoft-plus-circle" style="margin-right: 5px;"></i>Add New </span>' +
            '                  </div>' +
            '                  <div class="relative flex_center">' +
            '                      <div onclick="TS_Get_Icon_Button(this, ' + last_id + ')" class="TS_PTable_Button_' + last_id + '">' +
            '                           <span class="Button_cont Button_cont_' + last_id + '">' +
            '                               <span class="Button_text_cont">' +
            '                                   <span onmouseover="TS_Change_Feut_Val_Hover(this)" onmouseout="TS_Change_Feut_Val_Over(this)" class="TS_PTable_BText_' + last_id + '" contentEditable="true">' + data[0]['TS_PTable_BText'] + '</span>' +
            '                                   <input type="hidden" class="Total_Soft_PTable_Select" id="TS_PTable_BText_' + last_id + '" name="TS_PTable_BText_' + Col_Count + '" value="' + data[0]['TS_PTable_BText'] + '">' +
            '                               </span>' +
            '                               <span class="Button_icon_cont">' +
            '                                   <i  class="totalsoft   TS_PTable_BIconA_' + last_id + ' totalsoft-' + ( data[0]['TS_PTable_BIcon']!='none'? data[0]['TS_PTable_BIcon']:'plus-square-o ') + '" ></i>' +
            '                                   <select onchange= "TS_ChangeBut_Icon_Select(this,' + last_id + ')" class="Total_Soft_PTable_Select  TS_PTable_BIcon TS_PTable_BIcon_' + last_id + '" onchange="ChangeValueForHiddenBIcon(this)"  name="TS_PTable_BIcon_' + last_id + '" style="font-family: FontAwesome, Arial;">'+Total_Soft_PTable_Select_Icon+'</select>'+
            '                                   <input type="hidden" class="Total_Soft_PTable_Select" id="TS_PTable_BIcon_' + last_id + '" name="TS_PTable_BIcon_' + Col_Count + '" value="' + data[0]['TS_PTable_BIcon'] + '">' +
            '                               </span>' +
            '                           </span>' +
           '                     <div class=" TS_PTable_Button_Link_' + last_id + ' TS_PTable_Button_Link">' +
            '                        <i class="totalsoft  totalsoft-link" ></i>' +
            '                        <input type="text" class="Total_Soft_PTable_Select TS_PTable_BLink " id="TS_PTable_BLink_' + last_id + '" name="TS_PTable_BLink_' + Col_Count + '" value="' + data[0]['TS_PTable_BLink'] + '">' +
            '                     </div>' +  
             '                      </div>' +
            
            '                  </div>' +
            '              <div' +
            '          </div>' +
            '       </div>' +
            '    </div>' +
            '</div>'
        );
        //jQuery(".TS_PTable_Container").find(".TS_PTable_Container_Col_" + last_id).css("padding", "0 " + Total_Soft_PTable_M_03 + "px");
         
        jQuery("#TS_PTable_Col_" + last_id).append('' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_01" name="TS_PTable_ST1_' + Col_Count + '_01" value="' + data[1]['TS_PTable_ST_01'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_02" name="TS_PTable_ST1_' + Col_Count + '_02" value="' + data[1]['TS_PTable_ST_02'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_03" name="TS_PTable_ST1_' + Col_Count + '_03" value="' + data[1]['TS_PTable_ST_03'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_04" name="TS_PTable_ST1_' + Col_Count + '_04" value="' + data[1]['TS_PTable_ST_04'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_05" name="TS_PTable_ST1_' + Col_Count + '_05" value="' + data[1]['TS_PTable_ST_05'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_06" name="TS_PTable_ST1_' + Col_Count + '_06" value="' + data[1]['TS_PTable_ST_06'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_07" name="TS_PTable_ST1_' + Col_Count + '_07" value="' + data[1]['TS_PTable_ST_07'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_08" name="TS_PTable_ST1_' + Col_Count + '_08" value="' + data[1]['TS_PTable_ST_08'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_09" name="TS_PTable_ST1_' + Col_Count + '_09" value="' + data[1]['TS_PTable_ST_09'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_10" name="TS_PTable_ST1_' + Col_Count + '_10" value="' + data[1]['TS_PTable_ST_10'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_11" name="TS_PTable_ST1_' + Col_Count + '_11" value="' + data[1]['TS_PTable_ST_11'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_12" name="TS_PTable_ST1_' + Col_Count + '_12" value="' + data[1]['TS_PTable_ST_12'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_13" name="TS_PTable_ST1_' + Col_Count + '_13" value="' + data[1]['TS_PTable_ST_13'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_14" name="TS_PTable_ST1_' + Col_Count + '_14" value="' + data[1]['TS_PTable_ST_14'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_15" name="TS_PTable_ST1_' + Col_Count + '_15" value="' + data[1]['TS_PTable_ST_15'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_16" name="TS_PTable_ST1_' + Col_Count + '_16" value="' + data[1]['TS_PTable_ST_16'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_17" name="TS_PTable_ST1_' + Col_Count + '_17" value="' + data[1]['TS_PTable_ST_17'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_18" name="TS_PTable_ST1_' + Col_Count + '_18" value="' + data[1]['TS_PTable_ST_18'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_19" name="TS_PTable_ST1_' + Col_Count + '_19" value="' + data[1]['TS_PTable_ST_19'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_20" name="TS_PTable_ST1_' + Col_Count + '_20" value="' + data[1]['TS_PTable_ST_20'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_21" name="TS_PTable_ST1_' + Col_Count + '_21" value="' + data[1]['TS_PTable_ST_21'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_21_1" name="TS_PTable_ST1_' + Col_Count + '_21_1" value="' + data[1]['TS_PTable_ST_21_1'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_22" name="TS_PTable_ST1_' + Col_Count + '_22" value="' + data[1]['TS_PTable_ST_22'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_23" name="TS_PTable_ST1_' + Col_Count + '_23" value="' + data[1]['TS_PTable_ST_23'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_24" name="TS_PTable_ST1_' + Col_Count + '_24" value="' + data[1]['TS_PTable_ST_24'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_25" name="TS_PTable_ST1_' + Col_Count + '_25" value="' + data[1]['TS_PTable_ST_25'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_26" name="TS_PTable_ST1_' + Col_Count + '_26" value="' + data[1]['TS_PTable_ST_26'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_27" name="TS_PTable_ST1_' + Col_Count + '_27" value="' + data[1]['TS_PTable_ST_27'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_28" name="TS_PTable_ST1_' + Col_Count + '_28" value="' + data[1]['TS_PTable_ST_28'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_29" name="TS_PTable_ST1_' + Col_Count + '_29" value="' + data[1]['TS_PTable_ST_29'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_30" name="TS_PTable_ST1_' + Col_Count + '_30" value="' + data[1]['TS_PTable_ST_30'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_31" name="TS_PTable_ST1_' + Col_Count + '_31" value="' + data[1]['TS_PTable_ST_31'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_32" name="TS_PTable_ST1_' + Col_Count + '_32" value="' + data[1]['TS_PTable_ST_32'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_33" name="TS_PTable_ST1_' + Col_Count + '_33" value="' + data[1]['TS_PTable_ST_33'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_34" name="TS_PTable_ST1_' + Col_Count + '_34" value="' + data[1]['TS_PTable_ST_34'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_35" name="TS_PTable_ST1_' + Col_Count + '_35" value="' + data[1]['TS_PTable_ST_35'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_36" name="TS_PTable_ST1_' + Col_Count + '_36" value="' + data[1]['TS_PTable_ST_36'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_37" name="TS_PTable_ST1_' + Col_Count + '_37" value="' + data[1]['TS_PTable_ST_37'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_38" name="TS_PTable_ST1_' + Col_Count + '_38" value="' + data[1]['TS_PTable_ST_38'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_39" name="TS_PTable_ST1_' + Col_Count + '_39" value="' + data[1]['TS_PTable_ST_39'] + '">' +
            '<input type="hidden" class="TS_PTable_ST1_' + last_id + '_40" name="TS_PTable_ST1_' + Col_Count + '_40" value="' + data[1]['TS_PTable_ST_40'] + '">'
        );
          jQuery('.TS_PTable_BIcon_' + last_id ).val( jQuery('#TS_PTable_BIcon_' + last_id  ).val());
        if (data[1]["TS_PTable_ST_02"] == "on") {
            jQuery('.TS_PTable_Container_Col_' + last_id).css({
                "-webkit-transform": "scale(1, 1.05)",
                "-moz-transform": "scale(1, 1.05)",
                "transform": "scale(1, 1.05)"
            });
        }
        for (var k = 1; k <= parseInt(data[0]['TS_PTable_FCount']); k++) {
            jQuery("#TS_PTable_FIcon_" + last_id + "_" + k).val(TS_PTable_FIcon[k - 1]);
            if (jQuery('#TS_PTable_FChecked_' + last_id + '_' + k).val() == TS_PTable_FCheck[k - 1]) {
                jQuery('#TS_PTable_FChecked_' + last_id + '_' + k).attr('checked', 'checked');
            }
        }
    } else if (Total_Soft_PTable_Col_Type == "type2") {
        jQuery('.TS_PTable_Container').append('' +
            '    <style type="text/css">' +
            '       .TS_PTable_Container_Col_' + last_id + ' {' +
            '              position: relative;' +
            '              min-height: 1px;' +
            '              float: left;' +
            '              width: ' + data[1]["TS_PTable_ST_01"] + '%;' +
            '              margin-bottom: 30px !important;' +
            '       }' +

            '        @media not screen and (min-width: 820px) {' +
            '            .TS_PTable_Container {' +
            '                padding: 20px 5px;' +
            '            }' +

            '            .TS_PTable_Container_Col_' + last_id + ' {' +
            '                width: 70%;' +
            '                margin: 0 15% 40px 15%;' +
            '                padding: 0 10px;' +
            '            }' +
            '        }' +

            '        @media not screen and (min-width: 400px) {' +
            '            .TS_PTable_Container {' +
            '                padding: 20px 0;' +
            '            }' +

            '            .TS_PTable_Container_Col_' + last_id + ' {' +
            '                width: 100%;' +
            '                margin: 0 0 40px 0;' +
            '                padding: 0 5px;' +
            '            }' +
            '        }' +

            '        .TS_PTable_Shadow_' + last_id + ' {' +
            '            position: relative;' +
            '            z-index: 0;' +
            '        }' +

            '   .TS_PTable_Shadow_' + last_id + ':before, .TS_PTable_Shadow_' + last_id + ':after {' +
            '            bottom: 15px;' +
            '            left: 10px;' +
            '            width: 50%;' +
            '            height: 20%;' +
            '            max-width: 300px;' +
            '            max-height: 100px;' +
            '            -webkit-box-shadow: 0 15px 10px ' + data[1]["TS_PTable_ST_05"] + ';' +
            '            -moz-box-shadow: 0 15px 10px ' + data[1]["TS_PTable_ST_05"] + ';' +
            '            box-shadow: 0 15px 10px ' + data[1]["TS_PTable_ST_05"] + ';' +
            '            -webkit-transform: rotate(-3deg);' +
            '            -moz-transform: rotate(-3deg);' +
            '            -ms-transform: rotate(-3deg);' +
            '            -o-transform: rotate(-3deg);' +
            '            transform: rotate(-3deg);' +
            '            z-index: -1;' +
            '            position: absolute;' +
            '            content: "";' +
            '        }' +
            '    .TS_PTable_Shadow_' + last_id + ':after {' +
            '            transform: rotate(3deg);' +
            '            -moz-transform: rotate(3deg);' +
            '            -webkit-transform: rotate(3deg);' +
            '            right: 10px;' +
            '            left: auto;' +
            '        }' +

            '       .TS_PTable__' + last_id + ' {' +
            '           text-align: center;' +
            '           position: relative;' +
            '           background: ' + data[1]["TS_PTable_ST_19"] + ' ;' +
            '        }' +
            '        .TS_PTable_Div1_' + last_id + ' {' +
            '            background-color: ' + data[1]["TS_PTable_ST_03"] + ' ;' +
            '            padding: 30px 0 1px !important;' +
            '        }' +
            '        .TS_PTable_Title_' + last_id + ' {' +
            '            font-size:  ' + data[1]["TS_PTable_ST_06"] + 'px;' +
            '            font-family:  ' + data[1]["TS_PTable_ST_07"] + ';' +
            '            color:  ' + data[1]["TS_PTable_ST_08"] + ';' +
            '            margin: 10px 0 !important;' +
            '            padding: 0 !important;' +
            '        }' +
            '        .TS_PTable_Title_Icon_' + last_id + ' {' +
            '            display: block;' +
            '        }' +
            '        .TS_PTable_Title_Icon_' + last_id + ' {' +
            '            color: ' + data[1]["TS_PTable_ST_09"] + ';' +
            '            font-size: ' + data[1]["TS_PTable_ST_10"] + 'px;' +
            '        }' +
            '        .TS_PTable_PValue_' + last_id + ' {' +
            '            padding: 20px 0 14px !important;' +
            '            margin: 23px 0px 30px 0px !important;' +
            '            background: ' + data[1]["TS_PTable_ST_11"] + ';' +
            '            font-family: ' + data[1]["TS_PTable_ST_12"] + ';' +
            '            color: ' + data[1]["TS_PTable_ST_13"] + ';' +
            '            position: relative;' +
            '            transition: all 0.3s ease-in-out 0s;' +
            '            -moz-transition: all 0.3s ease-in-out 0s;' +
            '            -webkit-transition: all 0.3s ease-in-out 0s;' +
            '        }' +
            '        .TS_PTable__' + last_id + ':hover .TS_PTable_PValue_' + last_id + ' {' +
            '            background: ' + data[1]["TS_PTable_ST_17"] + '!important;' +
            '            color: ' + data[1]["TS_PTable_ST_18"] + ';' +
            '        }' +
            '        .TS_PTable_PValue_' + last_id + ':before, .TS_PTable_PValue_' + last_id + ':after {' +
            '            content: "";' +
            '            display: block;' +
            '            border-width: 13px 5px 11px;' +
            '            border-style: solid;' +
            '            border-color: transparent ' + data[1]["TS_PTable_ST_11"] + ' ' + data[1]["TS_PTable_ST_11"] + ' transparent;' +
            '            position: absolute;' +
            '            left: 0;' +
            '            transition: all 0.3s ease-in-out 0s;' +
            '            -moz-transition: all 0.3s ease-in-out 0s;' +
            '            -webkit-transition: all 0.3s ease-in-out 0s;' +
            '        }' +
            '        .TS_PTable_PValue_' + last_id + ':after {' +
            '            border-width: 11px 5px;' +
            '            border-color: transparent transparent ' + data[1]["TS_PTable_ST_11"] + ' ' + data[1]["TS_PTable_ST_11"] + ';' +
            '            left: auto;' +
            '            right: 0;' +
            '        }' +
            '        .TS_PTable__' + last_id + ':hover .TS_PTable_PValue_' + last_id + ':before {' +
            '            border-color: transparent ' + data[1]["TS_PTable_ST_17"] + ' ' + data[1]["TS_PTable_ST_17"] + ' transparent;' +
            '        }' +
            '        .TS_PTable__' + last_id + ':hover .TS_PTable_PValue_' + last_id + ':after {' +
            '            border-color: transparent transparent ' + data[1]["TS_PTable_ST_17"] + ' ' + data[1]["TS_PTable_ST_17"] + ';' +
            '        }' +
            '        .TS_PTable_Amount_' + last_id + ' {' +
            '            display: inline-block;' +
            '            font-size: ' + data[1]["TS_PTable_ST_15"] + 'px;' +
            '            position: relative;' +
            '        }' +
            '        .TS_PTable_PCur_' + last_id + ' {' +
            '            font-size: ' + data[1]["TS_PTable_ST_14"] + 'px;' +
            '            top: 0 !important;' +
            '            vertical-align: super !important;' +
            '            line-height: 1 !important;' +
            '        }' +
            '        .TS_PTable_PPlan_' + last_id + ' {' +
            '            font-size: ' + data[1]["TS_PTable_ST_16"] + 'px;' +
            '        }' +
            '        .TS_PTable_Features_' + last_id + ' {' +
            '            padding: 0 !important;' +
            '            margin: 0 !important;' +
            '            list-style: none;' +
            '            background: ' + data[1]["TS_PTable_ST_19"] + ';' +
            '        }' +
            '        .TS_PTable_Features_' + last_id + ' li:before {' +
            '            content: "" !important;' +
            '            display: none !important;' +
            '        }' +
            '        .TS_PTable_Features_' + last_id + ' li {' +
            '            background: ' + data[1]["TS_PTable_ST_19"] + ';' +
            '            color: ' + data[1]["TS_PTable_ST_20"] + ';' +
            '            font-size: ' + data[1]["TS_PTable_ST_21"] + 'px;' +
            '            font-family: ' + data[1]["TS_PTable_ST_22"] + ';' +
            '            line-height: 1;' +
            '            padding: 10px;' +
            '            display: flex;' +
            '            align-items: center;' +
            '            justify-content: center;' +
            '        }' +
            '        .TS_PTable_FIcon_' + last_id + ' {' +
            '            color: ' + data[1]["TS_PTable_ST_23"] + ';' +
            '            font-size: ' + data[1]["TS_PTable_ST_25"] + 'px;' +
            '            margin: 0 10px !important;' +
            '        }' +
            '        .TS_PTable_FIcon_' + last_id + '.TS_PTable_FCheck {' +
            '            color: ' + data[1]["TS_PTable_ST_24"] + ';' +
            '        }' +
            '        .TS_PTable_Div2_' + last_id + ' {' +
            '            background-color: ' + data[1]["TS_PTable_ST_03"] + ';' +
            '            padding: 20px 0 30px !important;' +
            '        }' +
            '        .TS_PTable_Button_' + last_id + ' {' +
            '            /*display: flex;*/' +
            '            justify-content: center;' +
            '            padding: 10px 0 !important;' +
            '            width: 100%;' +
            '            font-size: ' + data[1]["TS_PTable_ST_27"] + 'px ;' +
            '            font-family: ' + data[1]["TS_PTable_ST_28"] + ';' +
            '            background: ' + data[1]["TS_PTable_ST_11"] + ';' +
            '            color: ' + data[1]["TS_PTable_ST_13"] + ';' +
            '            border-top: 2px solid' + data[1]["TS_PTable_ST_13"] + ';' +
            '            border-bottom: 2px solid' + data[1]["TS_PTable_ST_13"] + ';' +
            '            transition: all 0.5s ease 0s;' +
            '            -moz-transition: all 0.5s ease 0s;' +
            '            -webkit-transition: all 0.5s ease 0s;' +
            '            text-decoration: none;' +
            '            outline: none;' +
            '            box-shadow: none;' +
            '            -webkit-box-shadow: none;' +
            '            -moz-box-shadow: none;' +
            '            cursor: pointer !important;' +
            '        }' +
            '        .TS_PTable__' + last_id + ':hover .TS_PTable_Button_' + last_id + ' {' +
            '            background: ' + data[1]["TS_PTable_ST_17"] + ';' +
            '            border-top: 2px solid ' + data[1]["TS_PTable_ST_18"] + ';' +
            '            border-bottom: 2px solid ' + data[1]["TS_PTable_ST_18"] + ';' +
            '            color:  ' + data[1]["TS_PTable_ST_18"] + ';' +
            '        }' +
            '        .TS_PTable_Button_' + last_id + ':hover, .TS_PTable_Button_' + last_id + ':focus {' +
            '            text-decoration: none;' +
            '            outline: none;' +
            '            box-shadow: none;' +
            '            -webkit-box-shadow: none;' +
            '            -moz-box-shadow: none;' +
            '        }' +
            '        .TS_PTable_BIconA_' + last_id + ', .TS_PTable_BIconB_' + last_id + ' {' +
            '            font-size: ' + data[1]["TS_PTable_ST_29"] + 'px;' +
            '        }' +
            '        .TS_PTable_BIconB_' + last_id + ' {' +
            '            margin: 0 10px 0 0 !important;' +
            '        }' +
            '        .TS_PTable_BIconA_' + last_id + ' {' +
            '            margin: 0 10px !important;' +
            '        }' +
            '</style>'
        )
        jQuery('.TS_PTable_Container').append('' +
            '<div class="TS_PTable_Container_Col_Copy TS_PTable_Container_Col_' + last_id + ' Total_Soft_PTable_AMMain_Div2_Cols1" id="TS_PTable_Col_' + last_id + '">' +
            '    <input type="text" style="display:none;" class="Total_Soft_PTable_Select" name="TS_PTable_ST2_' + Col_Count + '_ID" id="TS_PTable_ST2_' + last_id + '_ID" value="' + last_id + '">' +
            '    <input type="text" style="display:none;" class="Total_Soft_PTable_Select" name="TS_PTable_ST2_' + Col_Count + '_00" id="TS_PTable_ST2_' + last_id + '_00" value="Theme_' + last_id + '">' +
            '    <input type="text" style="display:none;" class="Total_Soft_PTable_Select" name="TS_PTable_ST2_' + Col_Count + '_index" id="TS_PTable_ST2_' + data[1]['id'] + '_index" value="' + parseInt(parseInt(last_id) - 1) + '">' +

            '    <input type="text" style="display:none" name="Total_Soft_PTable_Cols_Id" class="Total_Soft_PTable_Cols_Id" value="' + last_id + '">' +
            '    <input type="text" style="display:none" name="Total_Soft_PTable_Set_Title" class="Total_Soft_PTable_Set_Title" value="Theme_' + last_id + '">' +
            '       <div class="TS_PTable_Parent">' +
            '           <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Actions">' +
            '              <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action1">' +
            '                 <i class="totalsoft totalsoft-arrows" title="Reorder" onmouseup="TotalSoftPTable_ColumnDivdel()" onmousedown="TotalSoftPTable_ColumnDivSort(' + number + ',' + data[1]['index'] + ')"></i>' +
            '              </div>' +
            '              <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action2">' +
            '                  <i class="totalsoft totalsoft-file-text" title="Make Duplicate" onClick="TotalSoftPTable_Dup_Col(' + last_id + ', ' + Col_Count + ')"></i>' +
            '              </div>' +
            '              <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action3">' +
            '                  <i class="totalsoft totalsoft-trash" title="Delete Column" onClick="TotalSoftPTable_Del_Col(' + last_id + ')"></i>' +
            '              </div>' +
            '              <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action4">' +
            '                  <i class="totalsoft totalsoft-pencil" title="Edit Column" onClick="TotalSoftPTable_Col_Dragdown(); TotalSoftPTable_Edit_Theme(this,' + last_id + ')"></i>' +
            '              </div>' +
            '           </div>' +
            '    <div class="TS_PTable_Shadow_' + last_id + '">' +
            '        <div class="TS_PTable__' + last_id + ' TS_PTable_Parent">' +
            '            <div class="TS_PTable_Div1_' + last_id + '">' +
            '                <span class="TS_PTable_Title_Icon_' + last_id + '">' +
            '                    <i  onClick="TS_Get_Icon_Title(this, ' + last_id + ')" class="totalsoft totalsoft-' + ((data[0]['TS_PTable_TIcon']=='none' )?'plus-square-o':data[0]['TS_PTable_TIcon']) + '"></i>' +
            '                   <input type="hidden" class="Total_Soft_PTable_Select" id="TS_PTable_TIcon_' + last_id + '" name="TS_PTable_TIcon_' + Col_Count + '" value="' + data[0]['TS_PTable_TIcon'] + '">' +
            '                </span>' +
            '                <div class="title_h3_Container">' +
            '                   <h3 class="h3_hover TS_PTable_Title_' + last_id + '" onInput="TS_Change_H3_Val(this)" contentEditable="true" onClick="jQuery(this).focus();">' + data[0]['TS_PTable_TText'] + '</h3>' +
            '                   <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_TText_Hidden TS_PTable_TText" id="TS_PTable_New_TText_' + last_id + '" name="TS_PTable_TText_' + Col_Count + '" value="' + data[0]['TS_PTable_TText'] + '">' +
            '                </div>' +
            '                <div class="TS_PTable_PValue_' + last_id + '">' +
            '                    <span class="span_hover TS_PTable_Amount_' + last_id + '">' +
            '                        <sup class="TS_PTable_PCur_' + last_id + '" contentEditable="true" oninput="TS_Change_Cur_Val(this)" onClick="jQuery(this).focus();">' + data[0]['TS_PTable_PCur'] + '</sup>' +
            '                       <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_PCur_Hidden" id="TS_PTable_New_PCur_' + last_id + '" name="TS_PTable_PCur_' + Col_Count + '" value="' + data[0]['TS_PTable_PCur'] + '">' +
            '                        <span class="TS_PTable_PPrice_' + last_id + ' TS_PTable_PVal_' + last_id + '" contentEditable="true" oninput="TS_Change_Val_Val(this)" onClick="jQuery(this).focus();">' + data[0]['TS_PTable_PVal'] + '</span>' +
            '                       <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_PVal_Hidden" id="TS_PTable_New_PVal_' + last_id + '" name="TS_PTable_PVal_' + Col_Count + '" value="' + data[0]['TS_PTable_PVal'] + '">' +
            '                        <sub class="TS_PTable_PPlan_' + last_id + '" contentEditable="true" oninput="TS_Change_Plan_Val(this)" onClick="jQuery(this).focus();">' + data[0]['TS_PTable_PPlan'] + '</sub>' +
            '                       <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_PPlan_Hidden" id="TS_PTable_New_PPlan_' + last_id + '" name="TS_PTable_PPlan_' + Col_Count + '" value="' + data[0]['TS_PTable_PPlan'] + '">' +
            '                    </span>' +
            '                </div>' +

            '           <div class="relative feuture_cont_' + number + '">' +
            '               <ul class="TS_PTable_Features_' + number + ' TS_PTable_Features">');
        var TS_PTable_FChek = '';
        var Total_Soft_PTable_Select_Icon = jQuery('#Total_Soft_PTable_Select_Icon').html();
        for (j = 0; j < data[0]['TS_PTable_FCount']; j++) {
            if (TS_PTable_FCheck[j] != '') {
                TS_PTable_FChek = 'TS_PTable_FCheck';
            } else {
                TS_PTable_FChek = '';
            }
            jQuery(".TS_PTable_Features_" + last_id).append('' +
                '<li onMouseOver="TS_PTable_Features_Li(this)" onMouseOut="TS_PTable_Features_Li_Out(this)" class="TS_Li_' + last_id + '_' + parseInt(parseInt(j) + 1) + '">' +
                '            <div class="Hiddem_Li_Container">' +
                '            <span class="hiddenChangeText"><i class="totalsoft totalsoft-times TS_PTable_FDI TS_PTable_FDI_' + last_id + '" title="Delete Feature" onClick="TotalSoftPTable_Del_FT(' + last_id + ', ' + parseInt(parseInt(j) + 1) + ')"></i>     </span>' +
                '            <span class="TS_PTable_FChecked TS_PTable_FCheckedHide TS_PTable_FChecked_' + last_id + '">' +
                '                    <input onClick="TS_PTable_TS_PTable_FChecked_label(this,' + Col_Count+ ')" type="checkbox" class="' + TS_PTable_FChek + '" id="TS_PTable_FChecked_' + last_id + '_' + parseInt(parseInt(j) + 1) + '" name="TS_PTable_FChecked_' + Col_Count + '_' + parseInt(parseInt(j) + 1) + '" value="' + parseInt(parseInt(j) + 1) + '">' +
                '                    <label class="totalsoft totalsoft-question-circle-o" for="TS_PTable_FChecked_' + last_id + '_' + parseInt(parseInt(j) + 1) + '"></label>' +
                '            </span>' +
                '        </div>' +
                '        <div class="feut_container_' + last_id + ' feut_container">' +
                '            <div class="feut_text_cont">' +
                '                <span onmouseover="TS_Change_Feut_Val_Hover(this)" onmouseout="TS_Change_Feut_Val_Over(this)" class="TS_PTable_FText_' + last_id + '_' + parseInt(parseInt(j) + 1) + '" oninput="TS_Change_Feut_Val(this)" contentEditable="true" onClick="jQuery(this).focus();">' + TS_PTable_FText[j] + '</span>' +
                '                <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_FText_' + last_id + ' TS_PTable_Feut_Hidden"  name="TS_PTable_FText_' + Col_Count + '_' + parseInt(parseInt(j) + 1) + '" value="' + TS_PTable_FText[j] + '">' +
                '            </div>' +
                '            <div class="feut_icon_cont">' +
                '                <i onClick="TS_Get_Icon_Feuture(this, ' + last_id + ', ' + parseInt(parseInt(j) + 1) + ')" class="totalsoft  TS_PTable_FIcon_' + last_id + ' ' + TS_PTable_FChek + ' totalsoft-' + (TS_PTable_FIcon[j]!='none'?TS_PTable_FIcon[j]:'plus-square-o ') + '"></i>' +
                '                <input type="hidden"  class="Total_Soft_PTable_Select  TS_PTable_FIcon_' + last_id + '_' + parseInt(parseInt(j) + 1) + '" name="TS_PTable_FIcon_' + Col_Count + '_' + parseInt(parseInt(j) + 1) + '" value="' + TS_PTable_FIcon[j] + '">' +
                '            </div>' +
                '        </div>' +
                '</li>')
        }
        jQuery(".feuture_cont_" + last_id).append('' +
            '            </ul>' +
            '                      <input type="hidden"  class="TS_PTable_FCount" id="TS_PTable_FCount_' + last_id + '" name="TS_PTable_FCount_' + Col_Count + '" value="' + data[0]['TS_PTable_FCount'] + '">' +

            '        </div>' +
            '          <div class="Total_Soft_PTable_Features_New" onClick="Total_Soft_PTable_Features_New(' + last_id + ');Total_Soft_PTable_Features_New_Col(' + last_id + ',' + Col_Count + ',2)">' +
            '              <span class="Total_Soft_PTable_Features_New1">' +
            '                 <i class="Total_Soft_PTable_Features_New_Icon totalsoft totalsoft-plus-circle" style="margin-right: 5px;"></i>Add New </span>' +
            '          </div>' +
            '          <div class="TS_PTable_Div2_' + last_id + ' relative flex_center" >' +
            '             <div onclick="TS_Get_Icon_Button(this, ' + last_id + ')" class=" TS_PTable_Button_' + last_id + '">' +
            '                <span class="Button_cont Button_cont_' + last_id + '">' +
            '                    <span class="Button_text_cont">' +
            '                        <span onmouseover="TS_Change_Feut_Val_Hover(this)" onmouseout="TS_Change_Feut_Val_Over(this)" class="TS_PTable_BText_' + last_id + '" contentEditable="true">' + data[0]['TS_PTable_BText'] + '</span>' +
            '                        <input type="hidden" class="Total_Soft_PTable_Select" id="TS_PTable_BText_' + last_id + '" name="TS_PTable_BText_' + Col_Count + '" value="' + data[0]['TS_PTable_BText'] + '">' +
            '                    </span>' +
            '                    <span class="Button_icon_cont">' +
            '                        <i class="totalsoft   TS_PTable_BIconA_' + last_id + ' totalsoft-' + ( data[0]['TS_PTable_BIcon']!='none'? data[0]['TS_PTable_BIcon']:'plus-square-o ') + '" ></i>' +
            '                                   <select onchange= "TS_ChangeBut_Icon_Select(this,' + last_id + ')" class="Total_Soft_PTable_Select  TS_PTable_BIcon TS_PTable_BIcon_' + last_id + '" onchange="ChangeValueForHiddenBIcon(this)"  name="TS_PTable_BIcon_' + last_id + '" style="font-family: FontAwesome, Arial;">'+Total_Soft_PTable_Select_Icon+'</select>'+
            
            '                        <input type="hidden" class="Total_Soft_PTable_Select" id="TS_PTable_BIcon_' + last_id + '" name="TS_PTable_BIcon_' + Col_Count + '" value="' + data[0]['TS_PTable_BIcon'] + '">' +
            '                    </span>' +
            '                </span>' +
            '             <div class=" TS_PTable_Button_Link_' + last_id + ' TS_PTable_Button_Link" style="top:90px">' +
            '                <i class="totalsoft  totalsoft-link" ></i>' +
            '                <input type="text" class="Total_Soft_PTable_Select TS_PTable_BLink " id="TS_PTable_BLink_' + last_id + '" name="TS_PTable_BLink_' + Col_Count + '" value="' + data[0]['TS_PTable_BLink'] + '">' +
            '             </div>' +
            '             </div>' +
            
            '          </div>' +
            '        </div>' +
            '      </div>' +
            '    </div>' +
            '   </div>' +
            '</div>'
        )
        for (var k = 1; k <= parseInt(data[0]['TS_PTable_FCount']); k++) {
            jQuery("#TS_PTable_FIcon_" + last_id + "_" + k).val(TS_PTable_FIcon[k - 1]);
            if (jQuery('#TS_PTable_FChecked_' + last_id + '_' + k).val() == TS_PTable_FCheck[k - 1]) {
                jQuery('#TS_PTable_FChecked_' + last_id + '_' + k).attr('checked', 'checked');
            }
        }
        jQuery(".TS_PTable_Container").find(".TS_PTable_Container_Col_" + last_id).css("padding", "0 " + Total_Soft_PTable_M_03 + "px");
         jQuery('.TS_PTable_BIcon_' + last_id ).val( jQuery('#TS_PTable_BIcon_' + last_id  ).val());
        if (data[1]["TS_PTable_ST_02"] == "on") {
            jQuery('.TS_PTable_PValue_' + last_id).addClass("top-23");
        } else {
            jQuery('.TS_PTable_PValue_' + last_id).addClass("top-24");
        }
        if (data[1]["TS_PTable_ST_02"] == "on") {
            jQuery('.TS_PTable_PValue_' + last_id).addClass("top-21");
        } else {
            jQuery('.TS_PTable_PValue_' + last_id).addClass("top-22");
        }
        if (data[1]["TS_PTable_ST_02"] == 'on') {
            jQuery(".TS_PTable_Container_Col_" + data[1]['id']).css({
                "-webkit-transform": "scale(1, 1.05)",
                "-moz-transform": "scale(1, 1.05)",
                "transform": "scale(1, 1.05)",
            })
        }

        jQuery("#TS_PTable_Col_" + last_id).append('' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_01" name="TS_PTable_ST2_' + Col_Count + '_01" value="' + data[1]['TS_PTable_ST_01'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_02" name="TS_PTable_ST2_' + Col_Count + '_02" value="' + data[1]['TS_PTable_ST_02'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_03" name="TS_PTable_ST2_' + Col_Count + '_03" value="' + data[1]['TS_PTable_ST_03'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_04" name="TS_PTable_ST2_' + Col_Count + '_04" value="' + data[1]['TS_PTable_ST_04'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_05" name="TS_PTable_ST2_' + Col_Count + '_05" value="' + data[1]['TS_PTable_ST_05'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_06" name="TS_PTable_ST2_' + Col_Count + '_06" value="' + data[1]['TS_PTable_ST_06'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_07" name="TS_PTable_ST2_' + Col_Count + '_07" value="' + data[1]['TS_PTable_ST_07'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_08" name="TS_PTable_ST2_' + Col_Count + '_08" value="' + data[1]['TS_PTable_ST_08'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_09" name="TS_PTable_ST2_' + Col_Count + '_09" value="' + data[1]['TS_PTable_ST_09'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_10" name="TS_PTable_ST2_' + Col_Count + '_10" value="' + data[1]['TS_PTable_ST_10'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_11" name="TS_PTable_ST2_' + Col_Count + '_11" value="' + data[1]['TS_PTable_ST_11'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_12" name="TS_PTable_ST2_' + Col_Count + '_12" value="' + data[1]['TS_PTable_ST_12'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_13" name="TS_PTable_ST2_' + Col_Count + '_13" value="' + data[1]['TS_PTable_ST_13'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_14" name="TS_PTable_ST2_' + Col_Count + '_14" value="' + data[1]['TS_PTable_ST_14'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_15" name="TS_PTable_ST2_' + Col_Count + '_15" value="' + data[1]['TS_PTable_ST_15'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_16" name="TS_PTable_ST2_' + Col_Count + '_16" value="' + data[1]['TS_PTable_ST_16'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_17" name="TS_PTable_ST2_' + Col_Count + '_17" value="' + data[1]['TS_PTable_ST_17'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_18" name="TS_PTable_ST2_' + Col_Count + '_18" value="' + data[1]['TS_PTable_ST_18'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_19" name="TS_PTable_ST2_' + Col_Count + '_19" value="' + data[1]['TS_PTable_ST_19'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_20" name="TS_PTable_ST2_' + Col_Count + '_20" value="' + data[1]['TS_PTable_ST_20'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_21" name="TS_PTable_ST2_' + Col_Count + '_21" value="' + data[1]['TS_PTable_ST_21'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_21_1" name="TS_PTable_ST2_' + Col_Count + '_21_1" value="0">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_22" name="TS_PTable_ST2_' + Col_Count + '_22" value="' + data[1]['TS_PTable_ST_22'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_23" name="TS_PTable_ST2_' + Col_Count + '_23" value="' + data[1]['TS_PTable_ST_23'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_24" name="TS_PTable_ST2_' + Col_Count + '_24" value="' + data[1]['TS_PTable_ST_24'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_25" name="TS_PTable_ST2_' + Col_Count + '_25" value="' + data[1]['TS_PTable_ST_25'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_26" name="TS_PTable_ST2_' + Col_Count + '_26" value="' + data[1]['TS_PTable_ST_26'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_27" name="TS_PTable_ST2_' + Col_Count + '_27" value="' + data[1]['TS_PTable_ST_27'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_28" name="TS_PTable_ST2_' + Col_Count + '_28" value="' + data[1]['TS_PTable_ST_28'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_29" name="TS_PTable_ST2_' + Col_Count + '_29" value="' + data[1]['TS_PTable_ST_29'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_30" name="TS_PTable_ST2_' + Col_Count + '_30" value="' + data[1]['TS_PTable_ST_30'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_31" name="TS_PTable_ST2_' + Col_Count + '_31" value="' + data[1]['TS_PTable_ST_31'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_32" name="TS_PTable_ST2_' + Col_Count + '_32" value="' + data[1]['TS_PTable_ST_32'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_33" name="TS_PTable_ST2_' + Col_Count + '_33" value="' + data[1]['TS_PTable_ST_33'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_34" name="TS_PTable_ST2_' + Col_Count + '_34" value="' + data[1]['TS_PTable_ST_34'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_35" name="TS_PTable_ST2_' + Col_Count + '_35" value="' + data[1]['TS_PTable_ST_35'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_36" name="TS_PTable_ST2_' + Col_Count + '_36" value="' + data[1]['TS_PTable_ST_36'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_37" name="TS_PTable_ST2_' + Col_Count + '_37" value="' + data[1]['TS_PTable_ST_37'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_38" name="TS_PTable_ST2_' + Col_Count + '_38" value="' + data[1]['TS_PTable_ST_38'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_39" name="TS_PTable_ST2_' + Col_Count + '_39" value="' + data[1]['TS_PTable_ST_39'] + '">' +
            '<input type="hidden" class="TS_PTable_ST2_' + number + '_40" name="TS_PTable_ST2_' + Col_Count + '_40" value="' + data[1]['TS_PTable_ST_40'] + '">'
        )
    } else if (Total_Soft_PTable_Col_Type == "type3") {
        jQuery('.TS_PTable_Container').append('' +
            '<div class="TS_PTable_Container_Col_Copy TS_PTable_Container_Col_' + last_id + ' Total_Soft_PTable_AMMain_Div2_Cols1" id="TS_PTable_Col_' + last_id + '">' +
            '    <input type="text" style="display:none;" class="Total_Soft_PTable_Select" name="TS_PTable_ST3_' + Col_Count + '_ID" id="TS_PTable_ST3_' + last_id + '_ID" value="' + last_id + '">' +
            '    <input type="text" style="display:none;" class="Total_Soft_PTable_Select" name="TS_PTable_ST3_' + Col_Count + '_00" id="TS_PTable_ST3_' + last_id + '_00" value="Theme_' + last_id + '">' +
            '    <input type="text" style="display:none;" class="Total_Soft_PTable_Select" name="TS_PTable_ST3_' + Col_Count + '_index" id="TS_PTable_ST3_' + data[1]['id'] + '_index" value="' + parseInt(parseInt(last_id) - 1) + '">' +
            '    <input type="text" style="display:none" name="Total_Soft_PTable_Cols_Id" class="Total_Soft_PTable_Cols_Id" value="' + last_id + '">' +
            '    <input type="text" style="display:none" name="Total_Soft_PTable_Set_Title" class="Total_Soft_PTable_Set_Title" value="Theme_' + last_id + '">' +
            '       <div class="TS_PTable_Parent">' +
            '           <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Actions">' +
            '              <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action1">' +
            '                 <i class="totalsoft totalsoft-arrows" title="Reorder" onmouseup="TotalSoftPTable_ColumnDivdel()" onmousedown="TotalSoftPTable_ColumnDivSort(' + number + ',' + data[1]['index'] + ')"></i>' +
            '              </div>' +
            '              <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action2">' +
            '                  <i class="totalsoft totalsoft-file-text" title="Make Duplicate" onClick="TotalSoftPTable_Dup_Col(' + last_id + ', ' + Col_Count + ')"></i>' +
            '              </div>' +
            '              <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action3">' +
            '                  <i class="totalsoft totalsoft-trash" title="Delete Column" onClick="TotalSoftPTable_Del_Col(' + last_id + ')"></i>' +
            '              </div>' +
            '              <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action4">' +
            '                  <i class="totalsoft totalsoft-pencil" title="Edit Column" onClick="TotalSoftPTable_Col_Dragdown(); TotalSoftPTable_Edit_Theme(this,' + last_id + ')"></i>' +
            '              </div>' +
            '           </div>' +
            '      <div class="TS_PTable_Shadow_' + last_id + '">' +
            '          <div class="TS_PTable__' + last_id + '">' +
            '              <div class="TS_PTable_Div1_' + last_id + '">' +
            '                  <div class="TS_PTable_Title_Icon_' + last_id + '">' +
            '                      <i onClick="TS_Get_Icon_Title(this, ' + last_id + ')" class="totalsoft totalsoft-' + ((data[0]['TS_PTable_TIcon']=='none' )?'plus-square-o':data[0]['TS_PTable_TIcon']) + '"></i>' +
            '                       <input type="hidden" class="Total_Soft_PTable_Select" id="TS_PTable_TIcon_' + last_id + '" name="TS_PTable_TIcon_' + Col_Count + '" value="' + data[0]['TS_PTable_TIcon'] + '">' +
            '                  </div>' +
            '                  <div class="span_hover TS_PTable_PValue_' + last_id + '">' +
            '                      <sup><span class="TS_PTable_PCur_' + last_id + '" contentEditable="true" oninput="TS_Change_Cur_Val(this)" onClick="jQuery(this).focus();">' + data[0]['TS_PTable_PCur'] + '</span> <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_PCur_Hidden" id="TS_PTable_New_PCur_' + last_id + '" name="TS_PTable_PCur_' + Col_Count + '" value="' + data[0]['TS_PTable_PCur'] + '"></sup>' +
            '                      <span class="TS_PTable_PVal_' + last_id + '" contentEditable="true" oninput="TS_Change_Val_Val(this)" onClick="jQuery(this).focus();">' + data[0]['TS_PTable_PVal'] + '</span>' +
            '                       <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_PVal_Hidden" id="TS_PTable_New_PVal_' + last_id + '" name="TS_PTable_PVal_' + Col_Count + '" value="' + data[0]['TS_PTable_PVal'] + '">' +
            '                  </div>' +
            '                  <div class="span_hover title_PPlan_Container">' +
            '                       <span class="TS_PTable_PPlan_' + last_id + '"  contentEditable="true" oninput="TS_Change_Plan_Val(this)" onClick="jQuery(this).focus();">' + data[0]['TS_PTable_PPlan'] + '</span>' +
            '                       <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_PPlan_Hidden" id="TS_PTable_New_PPlan_' + last_id + '" name="TS_PTable_PPlan_' + Col_Count + '" value="' + data[0]['TS_PTable_PPlan'] + '">' +
            '                  </div>' +
            '                  <div class="TS_PTable_Header_' + last_id + '">' +
            '                      <h3 class="h3_hover TS_PTable_Title_' + last_id + '" onInput="TS_Change_H3_Val(this)" contentEditable="true" onClick="jQuery(this).focus();">' + data[0]['TS_PTable_TText'] + '</h3>' +
            '                      <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_TText_Hidden TS_PTable_TText" id="TS_PTable_New_TText_' + last_id + '" name="TS_PTable_TText_' + Col_Count + '" value="' + data[0]['TS_PTable_TText'] + '">' +
            '                  </div>' +
            '              </div>' +
            '              <div class="TS_PTable_Content_' + last_id + '">' +
            '<div class="relative feuture_cont_' + last_id + '">' +
            '                  <ul class="TS_PTable_Features_' + last_id + ' TS_PTable_Features">')
        var TS_PTable_FChek = '';
        var Total_Soft_PTable_Select_Icon = jQuery('#Total_Soft_PTable_Select_Icon').html();
        for (j = 0; j < data[0]['TS_PTable_FCount']; j++) {
            if (TS_PTable_FCheck[j] != '') {
                TS_PTable_FChek = 'TS_PTable_FCheck';
            } else {
                TS_PTable_FChek = '';
            }
            jQuery(".TS_PTable_Features_" + last_id).append('' +
                '                       <li onMouseOver="TS_PTable_Features_Li(this)" onMouseOut="TS_PTable_Features_Li_Out(this)" class="TS_Li_' + last_id + '_' + parseInt(parseInt(j) + 1) + '">' +
                '                                   <div class="Hiddem_Li_Container">' +
                '                                   <span class="hiddenChangeText"><i class="totalsoft totalsoft-times TS_PTable_FDI TS_PTable_FDI_' + last_id + '" title="Delete Feature" onClick="TotalSoftPTable_Del_FT(' + last_id + ', ' + parseInt(parseInt(j) + 1) + ')"></i>     </span>' +
                '                                   <span class="TS_PTable_FChecked TS_PTable_FCheckedHide TS_PTable_FChecked_' + last_id + '">' +
                '                                           <input onClick="TS_PTable_TS_PTable_FChecked_label(this,' + Col_Count + ')" type="checkbox" class="' + TS_PTable_FChek + '" id="TS_PTable_FChecked_' + last_id + '_' + parseInt(parseInt(j) + 1) + '" name="TS_PTable_FChecked_' + Col_Count + '_' + parseInt(parseInt(j) + 1) + '" value="' + parseInt(parseInt(j) + 1) + '">' +
                '                                           <label class="totalsoft totalsoft-question-circle-o" for="TS_PTable_FChecked_' + last_id + '_' + parseInt(parseInt(j) + 1) + '"></label>' +
                '                                   </span>' +
                '                               </div>' +
                '                               <div class="feut_container_' + last_id + ' feut_container">' +
                '                                   <div class="feut_text_cont">' +
                '                                       <span onmouseover="TS_Change_Feut_Val_Hover(this)" onmouseout="TS_Change_Feut_Val_Over(this)" class="TS_PTable_FText_' + last_id + '_' + parseInt(parseInt(j) + 1) + '" oninput="TS_Change_Feut_Val(this)" contentEditable="true" onClick="jQuery(this).focus();">' + TS_PTable_FText[j] + '</span>' +
                '                                       <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_FText_' + last_id + ' TS_PTable_Feut_Hidden"  name="TS_PTable_FText_' + Col_Count + '_' + parseInt(parseInt(j) + 1) + '" value="' + TS_PTable_FText[j] + '">' +
                '                                   </div>' +
                '                                   <div class="feut_icon_cont">' +
                '                                       <i onClick="TS_Get_Icon_Feuture(this, ' + last_id + ', ' + parseInt(parseInt(j) + 1) + ')" class="totalsoft  TS_PTable_FIcon_' + last_id + ' ' + TS_PTable_FChek + ' totalsoft-' + (TS_PTable_FIcon[j]!='none'?TS_PTable_FIcon[j]:'plus-square-o ') + '"></i>' +
                '                                       <input type="hidden"  class="Total_Soft_PTable_Select  TS_PTable_FIcon_' + last_id + '_' + parseInt(parseInt(j) + 1) + '" name="TS_PTable_FIcon_' + Col_Count + '_' + parseInt(parseInt(j) + 1) + '" value="' + TS_PTable_FIcon[j] + '">' +
                '                                   </div>' +
                '                               </div>' +
                '                         </li>')
        }
        jQuery(".feuture_cont_" + last_id).append('' +
            '                  </ul>' +
            '           <input type="hidden"  class="TS_PTable_FCount" id="TS_PTable_FCount_' + last_id + '" name="TS_PTable_FCount_' + Col_Count + '" value="' + data[0]['TS_PTable_FCount'] + '">' +
            '              </div>' +
            '              </div>' +
            '                      <div class="Total_Soft_PTable_Features_New" onClick="Total_Soft_PTable_Features_New(' + last_id + ');Total_Soft_PTable_Features_New_Col(' + last_id + ',' + Col_Count + ',3)">' +
            '                          <span class="Total_Soft_PTable_Features_New1">' +
            '                             <i class="Total_Soft_PTable_Features_New_Icon totalsoft totalsoft-plus-circle" style="margin-right: 5px;"></i>Add New </span>' +
            '                      </div>' +
            '              <div class="TS_PTable_Div2_' + last_id + '  relative flex_center ">' +
            '                  <div onclick="TS_Get_Icon_Button(this, ' + last_id + ')" class=" TS_PTable_Button_' + last_id + '">' +
            '                       <span class="Button_cont Button_cont_' + last_id + '">' +
            '                           <span class="Button_text_cont">' +
            '                               <span onmouseover="TS_Change_Feut_Val_Hover(this)" onmouseout="TS_Change_Feut_Val_Over(this)" class="TS_PTable_BText_' + last_id + '" contentEditable="true">' + data[0]['TS_PTable_BText'] + '</span>' +
            '                               <input type="hidden" class="Total_Soft_PTable_Select" id="TS_PTable_BText_' + last_id + '" name="TS_PTable_BText_' + Col_Count + '" value="' + data[0]['TS_PTable_BText'] + '">' +
            '                           </span>' +
            '                           <span class="Button_icon_cont">' +
            '                               <i class="totalsoft   TS_PTable_BIconA_' + last_id + ' totalsoft-' + ( data[0]['TS_PTable_BIcon']!='none'? data[0]['TS_PTable_BIcon']:'plus-square-o ') + '" ></i>' +
            '                                   <select onchange= "TS_ChangeBut_Icon_Select(this,' + last_id + ')" class="Total_Soft_PTable_Select  TS_PTable_BIcon TS_PTable_BIcon_' + last_id + '" onchange="ChangeValueForHiddenBIcon(this)"  name="TS_PTable_BIcon_' + last_id + '" style="font-family: FontAwesome, Arial;">'+Total_Soft_PTable_Select_Icon+'</select>'+
            
            '                               <input type="hidden" class="Total_Soft_PTable_Select" id="TS_PTable_BIcon_' + last_id + '" name="TS_PTable_BIcon_' + Col_Count + '" value="' + data[0]['TS_PTable_BIcon'] + '">' +
            '                           </span>' +
            '                       </span>' +
             '                  <div class=" TS_PTable_Button_Link_' + last_id + ' TS_PTable_Button_Link" style="top:90px">' +
            '                     <i class="totalsoft  totalsoft-link" ></i>' +
            '                     <input type="text" class="Total_Soft_PTable_Select TS_PTable_BLink " id="TS_PTable_BLink_' + last_id + '" name="TS_PTable_BLink_' + Col_Count + '" value="' + data[0]['TS_PTable_BLink'] + '">' +
            '                  </div>' +
            '                  </div>' +
          
            '              </div>' +
            '          </div>' +
            '      </div>' +
            '    </div>' +
            '  </div>'
        )
        for (var k = 1; k <= parseInt(data[0]['TS_PTable_FCount']); k++) {
            jQuery("#TS_PTable_FIcon_" + data[0]['id'] + "_" + k).val(TS_PTable_FIcon[k - 1]);
            if (jQuery('#TS_PTable_FChecked_' + data[0]['id'] + '_' + k).val() == TS_PTable_FCheck[k - 1]) {
                jQuery('#TS_PTable_FChecked_' + data[0]['id'] + '_' + k).attr('checked', 'checked');
            }
        }
         jQuery('.TS_PTable_BIcon_' + last_id ).val( jQuery('#TS_PTable_BIcon_' + last_id  ).val());
        jQuery(".TS_PTable_Container").find(".TS_PTable_Container_Col_" + last_id).css("padding", "0 " + Total_Soft_PTable_M_03 + "px");
        jQuery('.TS_PTable_Container').append('' +
            ' <style type="text/css">' +
            '    .TS_PTable_Container_Col_' + data[1]["id"] + ' {' +
            '   position: relative;' +
            '   min-height: 1px;' +
            '   float: left;' +
            '   width:' + data[1]["TS_PTable_ST_01"] + '%;' +
            '           margin-bottom: 30px !important;' +
            '        }' +
            '        @media not screen and (min-width: 820px) {' +
            '           .TS_PTable_Container {' +
            '               padding: 20px 5px;' +
            '           }' +

            '           .TS_PTable_Container_Col_' + data[1]["id"] + ' {' +
            '               width: 70%;' +
            '               margin: 0 15% 40px 15%;' +
            '               padding: 0 10px;' +
            '           }' +
            '       }' +

            '        @media not screen and (min-width: 400px) {' +
            '            .TS_PTable_Container {' +
            '                padding: 20px 0;' +
            '            }' +

            '            .TS_PTable_Container_Col_' + data[1]["id"] + ' {' +
            '                width: 100%;' +
            '                margin: 0 0 40px 0;' +
            '                padding: 0 5px;' +
            '            }' +
            '        }' +

            '        .TS_PTable_Shadow_' + data[1]["id"] + ' {' +
            '           position: relative;' +
            '           z-index: 0;' +
            '       }' +

            '        .TS_PTable_Shadow_' + data[1]["id"] + ' { ' +
            '               box-shadow: 8px 8px 18px ' + data[1]["TS_PTable_ST_07"] + '; ' +
            '               -moz-box-shadow: 8px 8px 18px ' + data[1]["TS_PTable_ST_07"] + '; ' +
            '               -webkit-box-shadow: 8px 8px 18px ' + data[1]["TS_PTable_ST_07"] + '; ' +
            '         } ' +
            '        .TS_PTable__' + data[1]["id"] + ' {' +
            '          text-align: center;' +
            '          position: relative;' +
            '          border: ' + data[1]["TS_PTable_ST_05"] + 'px solid ' + data[1]["TS_PTable_ST_04"] + ';' +
            '            margin-top: 30px;' +
            '        }' +

            '        .TS_PTable_Div1_' + data[1]["id"] + ' {' +
            '            background-color: ' + data[1]["TS_PTable_ST_03"] + ';' +
            '            padding: 50px 0 1px !important;' +
            '        }' +

            '        .TS_PTable_Div2_' + data[1]["id"] + ' {' +
            '            background-color: ' + data[1]["TS_PTable_ST_03"] + ';' +
            '            padding: 20px 0 25px !important;' +
            '        }' +

            '        .TS_PTable_Title_Icon_' + data[1]["id"] + ' {' +
            '            width: 80px;' +
            '            height: 80px;' +
            '            border-radius: 50%;' +
            '            background: ' + data[1]["TS_PTable_ST_12"] + ';' +
            '            border: ' + data[1]["TS_PTable_ST_05"] + 'px solid ' + data[1]["TS_PTable_ST_04"] + ';' +
            '            position: absolute;' +
            '            top: -40px;' +
            '            left: 50%;' +
            '            padding: 10px !important;' +
            '            transform: translateX(-50%);' +
            '            -moz-transform: translateX(-50%);' +
            '            -webkit-transform: translateX(-50%);' +
            '            transition: all 0.5s ease 0s;' +
            '            -moz-transition: all 0.5s ease 0s;' +
            '            -webkit-transition: all 0.5s ease 0s;' +
            '        }' +

            '        .TS_PTable__' + data[1]["id"] + ':hover .TS_PTable_Title_Icon_' + data[1]["id"] + ' {' +
            '            background: ' + data[1]["TS_PTable_ST_15"] + ' !important;' +
            '            transform: translateX(-50%) !important;' +
            '            -moz-transform: translateX(-50%) !important;' +
            '            -webkit-transform: translateX(-50%) !important;' +
            '        }' +

            '        .TS_PTable_Title_Icon_' + data[1]["id"] + ' i {' +
            '            width: 100%;' +
            '            height: 100%;' +
            '            line-height: 58px;' +
            '            border-radius: 50%;' +
            '            color: ' + data[1]["TS_PTable_ST_12"] + ';' +
            '            background: ' + data[1]["TS_PTable_ST_13"] + ';' +
            '            font-size: ' + data[1]["TS_PTable_ST_14"] + 'px;' +
            '            transition: all 0.5s ease 0s;' +
            '            -moz-transition: all 0.5s ease 0s;' +
            '            -webkit-transition: all 0.5s ease 0s;' +
            '        }' +

            '        .TS_PTable__' + data[1]["id"] + ':hover .TS_PTable_Title_Icon_' + data[1]["id"] + ' i {' +
            '            color: ' + data[1]["TS_PTable_ST_15"] + ';' +
            '            background: ' + data[1]["TS_PTable_ST_15"] + ' !important;' +
            '        }' +

            '        .TS_PTable_PValue_' + data[1]["id"] + ' {' +
            '            display: inline-block;' +
            '            font-family: ' + data[1]["TS_PTable_ST_17"] + ';' +
            '            color: ' + data[1]["TS_PTable_ST_18"] + ';' +
            '            font-size: ' + data[1]["TS_PTable_ST_19"] + 'px;' +
            '            position: relative;' +
            '        }' +

            '        .TS_PTable_PCur_' + data[1]["id"] + ' {' +
            '            font-size: ' + data[1]["TS_PTable_ST_19"] + 'px;' +
            '            top: 0 !important;' +
            '            vertical-align: super !important;' +
            '            line-height: 1 !important;' +
            '        }' +

            '        .TS_PTable_PPlan_' + data[1]["id"] + ' {' +
            '            display: block;' +
            '            font-family: ' + data[1]["TS_PTable_ST_17"] + ';' +
            '            color: ' + data[1]["TS_PTable_ST_18"] + ';' +
            '            font-size: ' + data[1]["TS_PTable_ST_21"] + 'px;' +
            '        }' +

            '        .TS_PTable_Header_' + data[1]["id"] + ' {' +
            '            position: relative;' +
            '            z-index: 1;' +
            '        }' +

            '        .TS_PTable_Header_' + data[1]["id"] + ':after {' +
            '            content: "" !important;' +
            '            width: 100% !important;' +
            '            height: 1px;' +
            '            background: ' + data[1]["TS_PTable_ST_04"] + ';' +
            '            position: absolute;' +
            '            top: 50%;' +
            '            left: 0;' +
            '            z-index: -1;' +
            '        }' +

            '        .TS_PTable_Title_' + data[1]["id"] + ' {' +
            '            width: fit-content;' +
            '            margin: 10px auto !important;' +
            '            padding: 10px 15px !important;' +
            '            font-size: ' + data[1]["TS_PTable_ST_08"] + 'px;' +
            '            font-family: ' + data[1]["TS_PTable_ST_09"] + ';' +
            '            color: ' + data[1]["TS_PTable_ST_10"] + ';' +
            '            background: ' + data[1]["TS_PTable_ST_11"] + ';' +
            '            position: relative;' +
            '            z-index: 1;' +
            '        }' +

            '        .TS_PTable_Features_' + data[1]["id"] + ' {' +
            '            list-style: none;' +
            '            padding: 0 !important;' +
            '            margin: 0 !important;' +
            '            background: ' + data[1]["TS_PTable_ST_22"] + ';' +
            '        }' +

            '        .TS_PTable_Features_' + data[1]["id"] + ' li:before {' +
            '            content: "" !important;' +
            '            display: none !important;' +
            '        }' +

            '        .TS_PTable_Features_' + data[1]["id"] + ' li {' +
            '            background: ' + data[1]["TS_PTable_ST_22"] + ';' +
            '            color: ' + data[1]["TS_PTable_ST_23"] + ';' +
            '            font-size: ' + data[1]["TS_PTable_ST_24"] + 'px;' +
            '            font-family: ' + data[1]["TS_PTable_ST_25"] + ';' +
            '            line-height: 1;' +
            '            padding: 10px;' +
            '            display: flex;' +
            '            align-items: center;' +
            '            justify-content: center;' +
            '        }' +

            '        .TS_PTable_FIcon_' + data[1]["id"] + ' {' +
            '            color: ' + data[1]["TS_PTable_ST_26"] + ';' +
            '            font-size: ' + data[1]["TS_PTable_ST_28"] + 'px;' +
            '            margin: 0 10px !important;' +
            '        }' +

            '        .TS_PTable_FIcon_' + data[1]["id"] + '.TS_PTable_FCheck {' +
            '            color: ' + data[1]["TS_PTable_ST_27"] + ';' +
            '        }' +

            '        .TS_PTable_Button_' + data[1]["id"] + ' {' +
            '            display: inline-block;' +
            '            font-size: ' + data[1]["TS_PTable_ST_30"] + 'px;' +
            '            font-family: ' + data[1]["TS_PTable_ST_31"] + ';' +
            '            color: ' + data[1]["TS_PTable_ST_35"] + ';' +
            '            background: ' + data[1]["TS_PTable_ST_34"] + ';' +
            '            border: 1px solid ' + data[1]["TS_PTable_ST_35"] + ';' +
            '            padding: 5px 20px !important;' +
            '            transition: all 0.5s ease 0s;' +
            '            -moz-transition: all 0.5s ease 0s;' +
            '            -webkit-transition: all 0.5s ease 0s;' +
            '            text-decoration: none;' +
            '            outline: none;' +
            '            box-shadow: none;' +
            '            -webkit-box-shadow: none;' +
            '            -moz-box-shadow: none;' +
            '            cursor: pointer !important;' +
            '        }' +

            '        .TS_PTable_Button_' + data[1]["id"] + ':hover {' +
            '            background: ' + data[1]["TS_PTable_ST_36"] + ';' +
            '            color: ' + data[1]["TS_PTable_ST_37"] + ';' +
            '            border: 1px solid ' + data[1]["TS_PTable_ST_37"] + ';' +
            '        }' +

            '        .TS_PTable_Button_' + data[1]["id"] + ':hover, .TS_PTable_Button_' + data[1]["id"] + ':focus {' +
            '            text-decoration: none;' +
            '            outline: none;' +
            '            box-shadow: none;' +
            '            -webkit-box-shadow: none;' +
            '            -moz-box-shadow: none;' +
            '        }' +

            '        .TS_PTable_BIconA_' + data[1]["id"] + ', .TS_PTable_BIconB_' + data[1]["id"] + ' {' +
            '            font-size: ' + data[1]["TS_PTable_ST_32"] + 'px;' +
            '        }' +

            '        .TS_PTable_BIconB_' + data[1]["id"] + ' {' +
            '            margin: 0 10px 0 0 !important;' +
            '        }' +

            '        .TS_PTable_BIconA_' + data[1]["id"] + ' {' +
            '            margin: 0 10px !important;' +
            '        }' +
            '</style>'
        )
        if (data[1]["TS_PTable_ST_02"] == 'on') {
            jQuery(".TS_PTable_Container_Col_" + data[1]['id']).css({
                "-webkit-transform": "scale(1, 1.1)",
                "-moz-transform": "scale(1, 1.1)",
                "transform": "scale(1, 1.1)",
            })
        }
        jQuery("#TS_PTable_Col_" + number).append('' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_01" name="TS_PTable_ST3_' + Col_Count + '_01" value="' + data[1]['TS_PTable_ST_01'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_02" name="TS_PTable_ST3_' + Col_Count + '_02" value="' + data[1]['TS_PTable_ST_02'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_03" name="TS_PTable_ST3_' + Col_Count + '_03" value="' + data[1]['TS_PTable_ST_03'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_04" name="TS_PTable_ST3_' + Col_Count + '_04" value="' + data[1]['TS_PTable_ST_04'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_05" name="TS_PTable_ST3_' + Col_Count + '_05" value="' + data[1]['TS_PTable_ST_05'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_06" name="TS_PTable_ST3_' + Col_Count + '_06" value="' + data[1]['TS_PTable_ST_06'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_07" name="TS_PTable_ST3_' + Col_Count + '_07" value="' + data[1]['TS_PTable_ST_07'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_08" name="TS_PTable_ST3_' + Col_Count + '_08" value="' + data[1]['TS_PTable_ST_08'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_09" name="TS_PTable_ST3_' + Col_Count + '_09" value="' + data[1]['TS_PTable_ST_09'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_10" name="TS_PTable_ST3_' + Col_Count + '_10" value="' + data[1]['TS_PTable_ST_10'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_11" name="TS_PTable_ST3_' + Col_Count + '_11" value="' + data[1]['TS_PTable_ST_11'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_12" name="TS_PTable_ST3_' + Col_Count + '_12" value="' + data[1]['TS_PTable_ST_12'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_13" name="TS_PTable_ST3_' + Col_Count + '_13" value="' + data[1]['TS_PTable_ST_13'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_14" name="TS_PTable_ST3_' + Col_Count + '_14" value="' + data[1]['TS_PTable_ST_14'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_15" name="TS_PTable_ST3_' + Col_Count + '_15" value="' + data[1]['TS_PTable_ST_15'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_16" name="TS_PTable_ST3_' + Col_Count + '_16" value="' + data[1]['TS_PTable_ST_16'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_17" name="TS_PTable_ST3_' + Col_Count + '_17" value="' + data[1]['TS_PTable_ST_17'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_18" name="TS_PTable_ST3_' + Col_Count + '_18" value="' + data[1]['TS_PTable_ST_18'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_19" name="TS_PTable_ST3_' + Col_Count + '_19" value="' + data[1]['TS_PTable_ST_19'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_20" name="TS_PTable_ST3_' + Col_Count + '_20" value="' + data[1]['TS_PTable_ST_20'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_21" name="TS_PTable_ST3_' + Col_Count + '_21" value="' + data[1]['TS_PTable_ST_21'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_21_1" name="TS_PTable_ST3_' + Col_Count + '_21_1" value="' + data[1]['TS_PTable_ST_21_1'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_22" name="TS_PTable_ST3_' + Col_Count + '_22" value="' + data[1]['TS_PTable_ST_22'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_23" name="TS_PTable_ST3_' + Col_Count + '_23" value="' + data[1]['TS_PTable_ST_23'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_24" name="TS_PTable_ST3_' + Col_Count + '_24" value="' + data[1]['TS_PTable_ST_24'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_25" name="TS_PTable_ST3_' + Col_Count + '_25" value="' + data[1]['TS_PTable_ST_25'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_26" name="TS_PTable_ST3_' + Col_Count + '_26" value="' + data[1]['TS_PTable_ST_26'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_27" name="TS_PTable_ST3_' + Col_Count + '_27" value="' + data[1]['TS_PTable_ST_27'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_28" name="TS_PTable_ST3_' + Col_Count + '_28" value="' + data[1]['TS_PTable_ST_28'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_29" name="TS_PTable_ST3_' + Col_Count + '_29" value="' + data[1]['TS_PTable_ST_29'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_30" name="TS_PTable_ST3_' + Col_Count + '_30" value="' + data[1]['TS_PTable_ST_30'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_31" name="TS_PTable_ST3_' + Col_Count + '_31" value="' + data[1]['TS_PTable_ST_31'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_32" name="TS_PTable_ST3_' + Col_Count + '_32" value="' + data[1]['TS_PTable_ST_32'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_33" name="TS_PTable_ST3_' + Col_Count + '_33" value="' + data[1]['TS_PTable_ST_33'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_34" name="TS_PTable_ST3_' + Col_Count + '_34" value="' + data[1]['TS_PTable_ST_34'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_35" name="TS_PTable_ST3_' + Col_Count + '_35" value="' + data[1]['TS_PTable_ST_35'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_36" name="TS_PTable_ST3_' + Col_Count + '_36" value="' + data[1]['TS_PTable_ST_36'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_37" name="TS_PTable_ST3_' + Col_Count + '_37" value="' + data[1]['TS_PTable_ST_37'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_38" name="TS_PTable_ST3_' + Col_Count + '_38" value="' + data[1]['TS_PTable_ST_38'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_39" name="TS_PTable_ST3_' + Col_Count + '_39" value="' + data[1]['TS_PTable_ST_39'] + '">' +
            '<input type="hidden" class="TS_PTable_ST3_' + number + '_40" name="TS_PTable_ST3_' + Col_Count + '_40" value="' + data[1]['TS_PTable_ST_40'] + '">'
        )
    } else if (Total_Soft_PTable_Col_Type == "type4") {
        jQuery('.TS_PTable_Container').append('' +
            '<div class="TS_PTable_Container_Col_Copy TS_PTable_Container_Col_' + last_id + ' Total_Soft_PTable_AMMain_Div2_Cols1" id="TS_PTable_Col_' + last_id + '">' +
            '    <input type="text" style="display:none;" class="Total_Soft_PTable_Select" name="TS_PTable_ST4_' + Col_Count + '_ID" id="TS_PTable_ST4_' + last_id + '_ID" value="' + last_id + '">' +
            '    <input type="text" style="display:none;" class="Total_Soft_PTable_Select" name="TS_PTable_ST4_' + Col_Count + '_00" id="TS_PTable_ST4_' + last_id + '_00" value="Theme_' + last_id + '">' +
            '    <input type="text" style="display:none;" class="Total_Soft_PTable_Select" name="TS_PTable_ST4_' + Col_Count + '_index" id="TS_PTable_ST4_' + data[1]['id'] + '_index" value="' + parseInt(parseInt(last_id) - 1) + '">' +

            '    <input type="text" style="display:none" name="Total_Soft_PTable_Cols_Id" class="Total_Soft_PTable_Cols_Id" value="' + last_id + '">' +
            '    <input type="text" style="display:none" name="Total_Soft_PTable_Set_Title" class="Total_Soft_PTable_Set_Title" value="Theme_' + last_id + '">' +
            '       <div class="TS_PTable_Parent">' +
            '           <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Actions">' +
            '              <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action1">' +
            '                 <i class="totalsoft totalsoft-arrows" title="Reorder" onmouseup="TotalSoftPTable_ColumnDivdel()" onmousedown="TotalSoftPTable_ColumnDivSort(' + number + ',' + data[1]['index'] + ')"></i>' +
            '              </div>' +
            '              <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action2">' +
            '                  <i class="totalsoft totalsoft-file-text" title="Make Duplicate" onClick="TotalSoftPTable_Dup_Col(' + last_id + ', ' + Col_Count + ')"></i>' +
            '              </div>' +
            '              <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action3">' +
            '                  <i class="totalsoft totalsoft-trash" title="Delete Column" onClick="TotalSoftPTable_Del_Col(' + last_id + ')"></i>' +
            '              </div>' +
            '              <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action4">' +
            '                  <i class="totalsoft totalsoft-pencil" title="Edit Column" onClick="TotalSoftPTable_Col_Dragdown(); TotalSoftPTable_Edit_Theme(this,' + last_id + ')"></i>' +
            '              </div>' +
            '           </div>' +
            '           <div class="TS_PTable_Shadow_' + last_id + '">' +
            '               <div class="TS_PTable__' + last_id + '">' +
            '                   <div class="TS_PTable_Div1_' + last_id + '">' +
            '                       <div class="title_h3_Container TS_PTable_Header_' + last_id + '">' +
            '                           <h3 class="h3_hover TS_PTable_Title_' + last_id + '" onInput="TS_Change_H3_Val(this)" contentEditable="true" onClick="jQuery(this).focus();">' + data[0]['TS_PTable_TText'] + '</h3>' +
            '                           <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_TText_Hidden TS_PTable_TText" id="TS_PTable_New_TText_' + last_id + '" name="TS_PTable_TText_' + Col_Count + '" value="' + data[0]['TS_PTable_TText'] + '">' +
            '                       </div>' +
            '                       <span class="span_hover TS_PTable_Amount_' + last_id + '">' +
            '                            <sup class="TS_PTable_PCur_' + last_id + '" contentEditable="true" oninput="TS_Change_Cur_Val(this)" onClick="jQuery(this).focus();">' + data[0]['TS_PTable_PCur'] + '</sup>' +
            '                           <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_PCur_Hidden" id="TS_PTable_New_PCur_' + last_id + '" name="TS_PTable_PCur_' + Col_Count + '" value="' + data[0]['TS_PTable_PCur'] + '">' +
            '                            <span class="TS_PTable_PVal_' + last_id + '" contentEditable="true" oninput="TS_Change_Val_Val(this)" onClick="jQuery(this).focus();">' + data[0]['TS_PTable_PVal'] + '</span>' +
            '                           <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_PVal_Hidden" id="TS_PTable_New_PVal_' + last_id + '" name="TS_PTable_PVal_' + Col_Count + '" value="' + data[0]['TS_PTable_PVal'] + '">' +
            '                            <sub class="TS_PTable_PPlan_' + last_id + '"  contentEditable="true" oninput="TS_Change_Plan_Val(this)" onClick="jQuery(this).focus();">' + data[0]['TS_PTable_PPlan'] + '</sub>' +
            '                           <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_PPlan_Hidden" id="TS_PTable_New_PPlan_' + last_id + '" name="TS_PTable_PPlan_' + Col_Count + '" value="' + data[0]['TS_PTable_PPlan'] + '">' +
            '                         </span>' +
            '                   </div>' +
            '                   <div class="TS_PTable_Content_' + last_id + '">' +
            '<div class="relative feuture_cont_' + last_id + '">' +
            '                       <ul class="TS_PTable_Features_' + last_id + ' TS_PTable_Features">')
        var TS_PTable_FChek = '';
        var Total_Soft_PTable_Select_Icon = jQuery('#Total_Soft_PTable_Select_Icon').html();
        for (j = 0; j < data[0]['TS_PTable_FCount']; j++) {
            if (TS_PTable_FCheck[j] != '') {
                TS_PTable_FChek = 'TS_PTable_FCheck';
            } else {
                TS_PTable_FChek = '';
            }
            jQuery(".TS_PTable_Features_" + last_id).append('' +
                '                       <li onMouseOver="TS_PTable_Features_Li(this)" onMouseOut="TS_PTable_Features_Li_Out(this)" class="TS_Li_' + last_id + '_' + parseInt(parseInt(j) + 1) + '">' +
                '                                   <div class="Hiddem_Li_Container">' +
                '                                   <span class="hiddenChangeText"><i class="totalsoft totalsoft-times TS_PTable_FDI TS_PTable_FDI_' + last_id + '" title="Delete Feature" onClick="TotalSoftPTable_Del_FT(' + last_id + ', ' + parseInt(parseInt(j) + 1) + ')"></i>     </span>' +
                '                                   <span class="TS_PTable_FChecked TS_PTable_FCheckedHide TS_PTable_FChecked_' + last_id + '">' +
                '                                           <input onClick="TS_PTable_TS_PTable_FChecked_label(this,' + Col_Count + ')" type="checkbox" class="' + TS_PTable_FChek + '" id="TS_PTable_FChecked_' + last_id + '_' + parseInt(parseInt(j) + 1) + '" name="TS_PTable_FChecked_' + Col_Count + '_' + parseInt(parseInt(j) + 1) + '" value="' + parseInt(parseInt(j) + 1) + '">' +
                '                                           <label class="totalsoft totalsoft-question-circle-o" for="TS_PTable_FChecked_' + last_id + '_' + parseInt(parseInt(j) + 1) + '"></label>' +
                '                                   </span>' +
                '                               </div>' +
                '                               <div class="feut_container_' + last_id + ' feut_container">' +
                '                                   <div class="feut_text_cont">' +
                '                                       <span onmouseover="TS_Change_Feut_Val_Hover(this)" onmouseout="TS_Change_Feut_Val_Over(this)" class="TS_PTable_FText_' + last_id + '_' + parseInt(parseInt(j) + 1) + '" oninput="TS_Change_Feut_Val(this)" contentEditable="true" onClick="jQuery(this).focus();">' + TS_PTable_FText[j] + '</span>' +
                '                                       <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_FText_' + last_id + ' TS_PTable_Feut_Hidden"  name="TS_PTable_FText_' + Col_Count + '_' + parseInt(parseInt(j) + 1) + '" value="' + TS_PTable_FText[j] + '">' +
                '                                   </div>' +
                '                                   <div class="feut_icon_cont">' +
                '                                       <i onClick="TS_Get_Icon_Feuture(this, ' + last_id + ', ' + parseInt(parseInt(j) + 1) + ')" class="totalsoft  TS_PTable_FIcon_' + last_id + ' ' + TS_PTable_FChek + ' totalsoft-' + (TS_PTable_FIcon[j]!='none'?TS_PTable_FIcon[j]:'plus-square-o ') + '"></i>' +
                '                                       <input type="hidden"  class="Total_Soft_PTable_Select  TS_PTable_FIcon_' + last_id + '_' + parseInt(parseInt(j) + 1) + '" name="TS_PTable_FIcon_' + Col_Count + '_' + parseInt(parseInt(j) + 1) + '" value="' + TS_PTable_FIcon[j] + '">' +
                '                                   </div>' +
                '                               </div>' +
                '                         </li>')
        }
        jQuery(".feuture_cont_" + last_id).append('' +
            '                  </ul>' +
            '           <input type="hidden"  class="TS_PTable_FCount" id="TS_PTable_FCount_' + last_id + '" name="TS_PTable_FCount_' + Col_Count + '" value="' + data[0]['TS_PTable_FCount'] + '">' +
            '              </div>' +
            '              </div>' +
            '       <table id="Total_Soft_PTable_Features_Col_' + last_id + '"></table>' +
            '           <table class="TS_Table Total_Soft_PTable_AMMain_Div2_Cols1_FTable1" id="Total_Soft_PTable_Features_' + last_id + '">' +
            '              <tr>' +
            '                  <td colSpan="2">' +
            '                      <div class="Total_Soft_PTable_Features_New" onClick="Total_Soft_PTable_Features_New(' + last_id + ');Total_Soft_PTable_Features_New_Col(' + last_id + ',' + Col_Count  + ',4)">' +
            '                          <span class="Total_Soft_PTable_Features_New1">' +
            '                             <i class="Total_Soft_PTable_Features_New_Icon totalsoft totalsoft-plus-circle" style="margin-right: 5px;"></i>Add New </span>' +
            '                      </div>' +
            '                  </td>' +
            '              </tr>' +
            '           </table>' +
            '              <div class="TS_PTable_Div2_' + last_id + '  relative flex_center ">' +
            '                  <div  onclick="TS_Get_Icon_Button(this, ' + last_id + ')" class=" TS_PTable_Button_' + last_id + '">' +
            '                       <span class="Button_cont Button_cont_' + last_id + '">' +
            '                           <span class="Button_text_cont">' +
            '                               <span onmouseover="TS_Change_Feut_Val_Hover(this)" onmouseout="TS_Change_Feut_Val_Over(this)" class="TS_PTable_BText_' + last_id + '" contentEditable="true">' + data[0]['TS_PTable_BText'] + '</span>' +
            '                               <input type="hidden" class="Total_Soft_PTable_Select" id="TS_PTable_BText_' + last_id + '" name="TS_PTable_BText_' + Col_Count + '" value="' + data[0]['TS_PTable_BText'] + '">' +
            '                           </span>' +
            '                           <span class="Button_icon_cont">' +
            '                               <i class="totalsoft   TS_PTable_BIconA_' + last_id + ' totalsoft-' + ( data[0]['TS_PTable_BIcon']!='none'? data[0]['TS_PTable_BIcon']:'plus-square-o ') + '" ></i>' +
            '                                   <select onchange= "TS_ChangeBut_Icon_Select(this,' + last_id + ')" class="Total_Soft_PTable_Select  TS_PTable_BIcon TS_PTable_BIcon_' + last_id + '" onchange="ChangeValueForHiddenBIcon(this)"  name="TS_PTable_BIcon_' + last_id + '" style="font-family: FontAwesome, Arial;">'+Total_Soft_PTable_Select_Icon+'</select>'+
            
            '                               <input type="hidden" class="Total_Soft_PTable_Select" id="TS_PTable_BIcon_' + last_id + '" name="TS_PTable_BIcon_' + Col_Count + '" value="' + data[0]['TS_PTable_BIcon'] + '">' +
            '                           </span>' +
            '                       </span>' +
             '                  <div class=" TS_PTable_Button_Link_' + last_id + ' TS_PTable_Button_Link" style="top:90px">' +
            '                       <i class="totalsoft  totalsoft-link" ></i>' +
            '                       <input type="text" class="Total_Soft_PTable_Select TS_PTable_BLink " id="TS_PTable_BLink_' + last_id + '" name="TS_PTable_BLink_' + Col_Count + '" value="' + data[0]['TS_PTable_BLink'] + '">' +
            '                  </div>' +
            '                  </div>' +
           
            '            </div>' +
            '          </div>' +
            '      </div>' +
            '    </div>' +
            '  </div>'
        )
        jQuery('.TS_PTable_Container').append('' +
            ' <style type="text/css">' +
            '         .TS_PTable_Container_Col_' + data[1]["id"] + ' {' +
            '             position: relative;' +
            '             min-height: 1px;' +
            '             float: left;' +
            '             width: ' + data[1]["TS_PTable_ST_01"] + '%;' +
            '             margin-bottom: 70px !important;' +
            '         }' +
            '         @media not screen and (min-width: 820px) {' +
            '             .TS_PTable_Container {' +
            '                 padding: 20px 5px;' +
            '             }' +
            '             .TS_PTable_Container_Col_' + data[1]["id"] + ' {' +
            '                 width: 70%;' +
            '                 margin: 0 15% 40px 15%;' +
            '                 padding: 0 10px;' +
            '             }' +
            '         }' +
            '         @media not screen and (min-width: 400px) {' +
            '             .TS_PTable_Container {' +
            '                 padding: 20px 0;' +
            '             }' +
            '             .TS_PTable_Container_Col_' + data[1]["id"] + ' {' +
            '                 width: 100%;' +
            '                 margin: 0 0 40px 0;' +
            '                 padding: 0 5px;' +
            '             }' +
            '         }' +
            '         .TS_PTable_Shadow_' + data[1]["id"] + ' {' +
            '             position: relative;' +
            '             z-index: 0;' +
            '         }' +
            '         .TS_PTable_Shadow_' + data[1]["id"] + ' {' +
            '             box-shadow: 8px 8px 18px ' + data[1]["TS_PTable_ST_06"] + ';' +
            '             -moz-box-shadow: 8px 8px 18px ' + data[1]["TS_PTable_ST_06"] + ';' +
            '             -webkit-box-shadow: 8px 8px 18px ' + data[1]["TS_PTable_ST_06"] + ';' +
            '         }' +
            '         .TS_PTable__' + data[1]["id"] + ' {' +
            '             text-align: center;' +
            '             position: relative;' +
            '         }' +
            '         .TS_PTable_Div1_' + data[1]["id"] + ' {' +
            '             background-color: ' + data[1]["TS_PTable_ST_03"] + ';' +
            '             padding: 30px 0 !important;' +
            '             transition: all 0.3s ease 0s;' +
            '             -moz-transition: all 0.3s ease 0s;' +
            '             -webkit-transition: all 0.3s ease 0s;' +
            '             position: relative;' +
            '         }' +
            '         .TS_PTable__' + data[1]["id"] + ':hover .TS_PTable_Div1_' + data[1]["id"] + ' {' +
            '             background-color: ' + data[1]["TS_PTable_ST_04"] + ';' +
            '         }' +
            '         .TS_PTable_Div1_' + data[1]["id"] + ':before, .TS_PTable_Div1_' + data[1]["id"] + ':after {' +
            '             content: "" !important;' +
            '             width: 16px !important;' +
            '             height: 16px !important;' +
            '             border-radius: 50%;' +
            '             border: 1px solid ' + data[1]["TS_PTable_ST_12"] + ';' +
            '             position: absolute;' +
            '             bottom: 12px;' +
            '         }' +
            '         .TS_PTable_Div1_' + data[1]["id"] + ':before {' +
            '             left: 40px;' +
            '         }' +
            '         .TS_PTable_Div1_' + data[1]["id"] + ':after {' +
            '             right: 40px;' +
            '         }' +
            '         .TS_PTable_Title_' + data[1]["id"] + ' {' +
            '             font-size: ' + data[1]["TS_PTable_ST_07"] + 'px;' +
            '             font-family: ' + data[1]["TS_PTable_ST_08"] + ';' +
            '             color: ' + data[1]["TS_PTable_ST_09"] + ';' +
            '             margin: 0 0 15px 0 !important;' +
            '             padding: 0 !important;' +
            '             letter-spacing: 2px !important;' +
            '         }' +
            '         .TS_PTable__' + data[1]["id"] + ':hover .TS_PTable_Title_' + data[1]["id"] + ' {' +
            '             color: ' + data[1]["TS_PTable_ST_10"] + ';' +
            '         }' +
            '         .TS_PTable_Amount_' + data[1]["id"] + ' {' +
            '             display: inline-block;' +
            '             font-family: ' + data[1]["TS_PTable_ST_11"] + ';' +
            '             color: ' + data[1]["TS_PTable_ST_12"] + ';' +
            '             font-size: ' + data[1]["TS_PTable_ST_15"] + 'px;' +
            '             position: relative;' +
            '             transition: all 0.3s ease 0s;' +
            '             -moz-transition: all 0.3s ease 0s;' +
            '             -webkit-transition: all 0.3s ease 0s;' +
            '             margin-bottom: 20px !important;' +
            '         }' +
            '         .TS_PTable_PCur_' + data[1]["id"] + ' {' +
            '             font-size: ' + data[1]["TS_PTable_ST_14"] + 'px;' +
            '             top: 0px !important;' +
            '             vertical-align: super !important;' +
            '             line-height: 1 !important;' +
            '         }' +
            '         .TS_PTable_PPlan_' + data[1]["id"] + ' {' +
            '             font-size: ' + data[1]["TS_PTable_ST_16"] + 'px;' +
            '             color: ' + data[1]["TS_PTable_ST_09"] + ';' +
            '             bottom: 0;' +
            '         }' +
            '         .TS_PTable__' + data[1]["id"] + ':hover .TS_PTable_Amount_' + data[1]["id"] + ' {' +
            '             color: ' + data[1]["TS_PTable_ST_13"] + ';' +
            '         }' +
            '         .TS_PTable__' + data[1]["id"] + ':hover .TS_PTable_PPlan_' + data[1]["id"] + ' {' +
            '             color: ' + data[1]["TS_PTable_ST_10"] + ';' +
            '         }' +
            '         .TS_PTable_Content_' + data[1]["id"] + ' {' +
            '             padding-top: 50px;' +
            '             background: ' + data[1]["TS_PTable_ST_17"] + ';' +
            '             position: relative;' +
            '         }' +
            '         .TS_PTable_Content_' + data[1]["id"] + ':before, .TS_PTable_Content_' + data[1]["id"] + ':after {' +
            '             content: "" !important;' +
            '             width: 16px !important;' +
            '             height: 16px !important;' +
            '             border-radius: 50%;' +
            '             border: 1px solid ' + data[1]["TS_PTable_ST_18"] + ';' +
            '             position: absolute;' +
            '             top: 12px;' +
            '         }' +
            '         .TS_PTable_Content_' + data[1]["id"] + ':before {' +
            '             left: 40px;' +
            '         }' +
            '         .TS_PTable_Content_' + data[1]["id"] + ':after {' +
            '             right: 40px;' +
            '         }' +
            '         .TS_PTable_Features_' + data[1]["id"] + ' {' +
            '             padding: 0 10px !important;' +
            '             margin: 0 !important;' +
            '             list-style: none;' +
            '         }' +
            '         .TS_PTable_Features_' + data[1]["id"] + ':before, .TS_PTable_Features_' + data[1]["id"] + ':after {' +
            '             content: "" !important;' +
            '             width: 8px !important;' +
            '             height: 46px !important;' +
            '             border-radius: 3px;' +
            '             background: ' + data[1]["TS_PTable_ST_09"] + ';' +
            '             position: absolute;' +
            '             top: -73px;' +
            '             z-index: 1;' +
            '             box-shadow: 0 0 5px #707070;' +
            '             transition: all 0.3s ease 0s;' +
            '         }' +
            '         .TS_PTable__' + data[1]["id"] + ':hover .TS_PTable_Features_' + data[1]["id"] + ':before, .TS_PTable__' + data[1]["id"] + ':hover .TS_PTable_Features_' + data[1]["id"] + ':after {' +
            '             background: ' + data[1]["TS_PTable_ST_10"] + ' !important;' +
            '         }' +
            '         .TS_PTable_Features_' + data[1]["id"] + ':before {' +
            '             left: 45px;' +
            '         }' +
            '         .TS_PTable_Features_' + data[1]["id"] + ':after {' +
            '             right: 45px;' +
            '         }' +
            '         .TS_PTable_Features_' + data[1]["id"] + ' li {' +
            '             background: ' + data[1]["TS_PTable_ST_17"] + ';' +
            '             color: ' + data[1]["TS_PTable_ST_18"] + ';' +
            '             font-size: ' + data[1]["TS_PTable_ST_19"] + 'px;' +
            '             font-family: ' + data[1]["TS_PTable_ST_20"] + ';' +
            '             border-bottom: 1px solid ' + data[1]["TS_PTable_ST_18"] + '!important;' +
            '             line-height: 1;' +
            '             padding: 10px;' +
            '               display: flex;' +
            '               align-items: center;' +
            '               justify-content: center;' +
            '         }' +
            '         .TS_PTable_Features_' + data[1]["id"] + ' li:last-child {' +
            '             border-bottom: none;' +
            '         }' +
            '         .TS_PTable_Features_' + data[1]["id"] + ' li:before {' +
            '             content: "" !important;' +
            '             display: none !important;' +
            '         }' +
            '         .TS_PTable_FIcon_' + data[1]["id"] + ' {' +
            '             color: ' + data[1]["TS_PTable_ST_21"] + ';' +
            '             font-size: ' + data[1]["TS_PTable_ST_23"] + 'px;' +
            '             margin: 0 10px !important;' +
            '         }' +
            '         .TS_PTable_FIcon_' + data[1]["id"] + '.TS_PTable_FCheck {' +
            '             color: ' + data[1]["TS_PTable_ST_22"] + ';' +
            '         }' +
            '         .TS_PTable_Button_' + data[1]["id"] + ' {' +
            '             display: inline-block;' +
            '             padding: 5px 20px !important;' +
            '             margin: 15px 0 !important;' +
            '             font-size: ' + data[1]["TS_PTable_ST_25"] + 'px;' +
            '             font-family: ' + data[1]["TS_PTable_ST_26"] + ';' +
            '             background: ' + data[1]["TS_PTable_ST_29"] + ';' +
            '             color: ' + data[1]["TS_PTable_ST_30"] + ';' +
            '             transition: all 0.3s ease 0s;' +
            '             -moz-transition: all 0.3s ease 0s;' +
            '             -webkit-transition: all 0.3s ease 0s;' +
            '             text-decoration: none;' +
            '             outline: none;' +
            '             box-shadow: none;' +
            '             -webkit-box-shadow: none;' +
            '             -moz-box-shadow: none;' +
            '             cursor: pointer !important;' +
            '         }' +
            '         .TS_PTable__' + data[1]["id"] + ':hover .TS_PTable_Button_' + data[1]["id"] + ' {' +
            '             background: ' + data[1]["TS_PTable_ST_31"] + ';' +
            '             color: ' + data[1]["TS_PTable_ST_32"] + ';' +
            '         }' +
            '         .TS_PTable_Button_' + data[1]["id"] + ':hover, .TS_PTable_Button_' + data[1]["id"] + ':focus {' +
            '             text-decoration: none;' +
            '             outline: none;' +
            '             box-shadow: none;' +
            '             -webkit-box-shadow: none;' +
            '             -moz-box-shadow: none;' +
            '         }' +
            '         .TS_PTable_BIconA_' + data[1]["id"] + ', .TS_PTable_BIconB_' + data[1]["id"] + ' {' +
            '             font-size: ' + data[1]["TS_PTable_ST_27"] + 'px;' +
            '         }' +
            '         .TS_PTable_BIconB_' + data[1]["id"] + ' {' +
            '             margin: 0 10px 0 0 !important;' +
            '         }' +
            '         .TS_PTable_BIconA_' + data[1]["id"] + ' {' +
            '             margin: 0 10px !important;' +
            '         }' +
            '     </style>'
        )
        if (data[1]["TS_PTable_ST_02"] == 'on') {
            jQuery(".TS_PTable_Container_Col_" + data[1]['id']).css({
                "-webkit-transform": "scale(1, 1.1)",
                "-moz-transform": "scale(1, 1.1)",
                "transform": "scale(1, 1.1)",
            })
        }
         jQuery('.TS_PTable_BIcon_' + last_id ).val( jQuery('#TS_PTable_BIcon_' + last_id  ).val());
        jQuery(".TS_PTable_Container").find(".TS_PTable_Container_Col_" + last_id).css("padding", "0 " + Total_Soft_PTable_M_03 + "px");
        for (var k = 1; k <= parseInt(data[0]['TS_PTable_FCount']); k++) {
            jQuery("#TS_PTable_FIcon_" + last_id + "_" + k).html(Total_Soft_PTable_Select_Icon);
            jQuery("#TS_PTable_FIcon_" + last_id + "_" + k).val(TS_PTable_FIcon[k - 1]);
            if (jQuery('#TS_PTable_FChecked_' + last_id + '_' + k).val() == TS_PTable_FCheck[k - 1]) {
                jQuery('#TS_PTable_FChecked_' + number + '_' + k).attr('checked', 'checked');
            }
        }
        jQuery("#TS_PTable_Col_" + number).append('' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_01" name="TS_PTable_ST4_' + Col_Count + '_01" value="' + data[1]['TS_PTable_ST_01'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_02" name="TS_PTable_ST4_' + Col_Count + '_02" value="' + data[1]['TS_PTable_ST_02'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_03" name="TS_PTable_ST4_' + Col_Count + '_03" value="' + data[1]['TS_PTable_ST_03'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_04" name="TS_PTable_ST4_' + Col_Count + '_04" value="' + data[1]['TS_PTable_ST_04'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_05" name="TS_PTable_ST4_' + Col_Count + '_05" value="' + data[1]['TS_PTable_ST_05'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_06" name="TS_PTable_ST4_' + Col_Count + '_06" value="' + data[1]['TS_PTable_ST_06'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_07" name="TS_PTable_ST4_' + Col_Count + '_07" value="' + data[1]['TS_PTable_ST_07'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_08" name="TS_PTable_ST4_' + Col_Count + '_08" value="' + data[1]['TS_PTable_ST_08'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_09" name="TS_PTable_ST4_' + Col_Count + '_09" value="' + data[1]['TS_PTable_ST_09'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_10" name="TS_PTable_ST4_' + Col_Count + '_10" value="' + data[1]['TS_PTable_ST_10'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_11" name="TS_PTable_ST4_' + Col_Count + '_11" value="' + data[1]['TS_PTable_ST_11'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_12" name="TS_PTable_ST4_' + Col_Count + '_12" value="' + data[1]['TS_PTable_ST_12'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_13" name="TS_PTable_ST4_' + Col_Count + '_13" value="' + data[1]['TS_PTable_ST_13'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_14" name="TS_PTable_ST4_' + Col_Count + '_14" value="' + data[1]['TS_PTable_ST_14'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_15" name="TS_PTable_ST4_' + Col_Count + '_15" value="' + data[1]['TS_PTable_ST_15'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_16" name="TS_PTable_ST4_' + Col_Count + '_16" value="' + data[1]['TS_PTable_ST_16'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_17" name="TS_PTable_ST4_' + Col_Count + '_17" value="' + data[1]['TS_PTable_ST_17'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_18" name="TS_PTable_ST4_' + Col_Count + '_18" value="' + data[1]['TS_PTable_ST_18'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_19" name="TS_PTable_ST4_' + Col_Count + '_19" value="' + data[1]['TS_PTable_ST_19'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_20" name="TS_PTable_ST4_' + Col_Count + '_20" value="' + data[1]['TS_PTable_ST_20'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_21" name="TS_PTable_ST4_' + Col_Count + '_21" value="' + data[1]['TS_PTable_ST_21'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_21_1" name="TS_PTable_ST4_' + Col_Count + '_21_1" value="' + data[1]['TS_PTable_ST_21_1'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_22" name="TS_PTable_ST4_' + Col_Count + '_22" value="' + data[1]['TS_PTable_ST_22'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_23" name="TS_PTable_ST4_' + Col_Count + '_23" value="' + data[1]['TS_PTable_ST_23'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_24" name="TS_PTable_ST4_' + Col_Count + '_24" value="' + data[1]['TS_PTable_ST_24'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_25" name="TS_PTable_ST4_' + Col_Count + '_25" value="' + data[1]['TS_PTable_ST_25'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_26" name="TS_PTable_ST4_' + Col_Count + '_26" value="' + data[1]['TS_PTable_ST_26'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_27" name="TS_PTable_ST4_' + Col_Count + '_27" value="' + data[1]['TS_PTable_ST_27'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_28" name="TS_PTable_ST4_' + Col_Count + '_28" value="' + data[1]['TS_PTable_ST_28'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_29" name="TS_PTable_ST4_' + Col_Count + '_29" value="' + data[1]['TS_PTable_ST_29'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_30" name="TS_PTable_ST4_' + Col_Count + '_30" value="' + data[1]['TS_PTable_ST_30'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_31" name="TS_PTable_ST4_' + Col_Count + '_31" value="' + data[1]['TS_PTable_ST_31'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_32" name="TS_PTable_ST4_' + Col_Count + '_32" value="' + data[1]['TS_PTable_ST_32'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_33" name="TS_PTable_ST4_' + Col_Count + '_33" value="' + data[1]['TS_PTable_ST_33'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_34" name="TS_PTable_ST4_' + Col_Count + '_34" value="' + data[1]['TS_PTable_ST_34'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_35" name="TS_PTable_ST4_' + Col_Count + '_35" value="' + data[1]['TS_PTable_ST_35'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_36" name="TS_PTable_ST4_' + Col_Count + '_36" value="' + data[1]['TS_PTable_ST_36'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_37" name="TS_PTable_ST4_' + Col_Count + '_37" value="' + data[1]['TS_PTable_ST_37'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_38" name="TS_PTable_ST4_' + Col_Count + '_38" value="' + data[1]['TS_PTable_ST_38'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_39" name="TS_PTable_ST4_' + Col_Count + '_39" value="' + data[1]['TS_PTable_ST_39'] + '">' +
            '<input type="hidden" class="TS_PTable_ST4_' + number + '_40" name="TS_PTable_ST4_' + Col_Count + '_40" value="' + data[1]['TS_PTable_ST_40'] + '">'
        )
    } else if (Total_Soft_PTable_Col_Type == "type5") {
        jQuery('.TS_PTable_Container').append('' +
            ' <style type="text/css">' +
            '     .TS_PTable_Container_Col_' + data[1]["id"] + ' {' +
            '               position: relative;' +
            '               min-height: 1px;' +
            '               float: left;' +
            '               width: ' + data[1]["TS_PTable_ST_01"] + '%;' +
            '               margin-bottom: 30px !important;' +
            '               transition: transform 0.5s ease 0s;' +
            '               -moz-transition: transform 0.5s ease 0s;' +
            '               -webkit-transition: transform 0.5s ease 0s;' +
            '         }' +
            '         .TS_PTable_Container_Col_' + data[1]["id"] + ':hover {' +
            '           z-index: 1;' +
            '         }' +
            '         @media not screen and (min-width: 820px) {' +
            '             .TS_PTable_Container {' +
            '                 padding: 20px 5px;' +
            '             }' +
            '             .TS_PTable_Container_Col_' + data[1]["id"] + ' {' +
            '                 width: 70%;' +
            '                 margin: 0 15% 40px 15%;' +
            '                 padding: 0 10px;' +
            '             }' +
            '         }' +
            '         @media not screen and (min-width: 400px) {' +
            '             .TS_PTable_Container {' +
            '                 padding: 20px 0;' +
            '             }' +
            '             .TS_PTable_Container_Col_' + data[1]["id"] + ' {' +
            '                 width: 100%;' +
            '                 margin: 0 0 40px 0;' +
            '                 padding: 0 5px;' +
            '             }' +
            '         }' +
            '         .TS_PTable_Shadow_' + data[1]["id"] + ' {' +
            '             position: relative;' +
            '             z-index: 0;' +
            '             border-radius: 10px;' +
            '         }' +
            '         .TS_PTable_Shadow_' + data[1]["id"] + ' {' +
            '             box-shadow: 0 0 18px 7px ' + data[1]["TS_PTable_ST_05"] + ';' +
            '             -moz-box-shadow: 0 0 18px 7px ' + data[1]["TS_PTable_ST_05"] + ';' +
            '             -webkit-box-shadow: 0 0 18px 7px ' + data[1]["TS_PTable_ST_05"] + ';' +
            '         }' +
            '         .TS_PTable__' + data[1]["id"] + ' {' +
            '             text-align: center;' +
            '             position: relative;' +
            '             background-color: ' + data[1]["TS_PTable_ST_03"] + ';' +
            '             padding-bottom: 40px !important;' +
            '            border-radius: 10px;' +
            '            transition: all 0.5s ease 0s;' +
            '            -moz-transition: all 0.5s ease 0s;' +
            '            -webkit-transition: all 0.5s ease 0s;' +
            '        }' +
            '        .TS_PTable_Div1_' + data[1]["id"] + ' {' +
            '             background-color: ' + data[1]["TS_PTable_ST_18"] + ';' +
            '             padding: 40px 0 !important;' +
            '            border-radius: 10px 10px 50% 50%;' +
            '            transition: all 0.5s ease 0s;' +
            '            -moz-transition: all 0.5s ease 0s;' +
            '            -webkit-transition: all 0.5s ease 0s;' +
            '            position: relative;' +
            '        }' +
            '        .TS_PTable__' + data[1]["id"] + ':hover .TS_PTable_Div1_' + data[1]["id"] + ' {' +
            '             background-color: ' + data[1]["TS_PTable_ST_19"] + ' !important;' +
            '         }' +
            '        .TS_PTable_Div1_' + data[1]["id"] + ' i {' +
            '             font-size: ' + data[1]["TS_PTable_ST_09"] + 'px;' +
            '             color: ' + data[1]["TS_PTable_ST_10"] + ';' +
            '             margin-bottom: 10px;' +
            '            transition: all 0.5s ease 0s;' +
            '            -moz-transition: all 0.5s ease 0s;' +
            '            -webkit-transition: all 0.5s ease 0s;' +
            '        }' +
            '        .TS_PTable__' + data[1]["id"] + ':hover .TS_PTable_Div1_' + data[1]["id"] + ' i {' +
            '             color: ' + data[1]["TS_PTable_ST_11"] + ';' +
            '         }' +
            '        .TS_PTable_Title_' + data[1]["id"] + ' {' +
            '             font-size: ' + data[1]["TS_PTable_ST_06"] + 'px;' +
            '             font-family: ' + data[1]["TS_PTable_ST_07"] + ';' +
            '             color: ' + data[1]["TS_PTable_ST_08"] + ';' +
            '             margin: 20px 0 !important;' +
            '            padding: 0 !important;' +
            '        }' +
            '        .TS_PTable_Amount_' + data[1]["id"] + ' {' +
            '             font-family: ' + data[1]["TS_PTable_ST_12"] + ';' +
            '             color: ' + data[1]["TS_PTable_ST_13"] + ';' +
            '             font-size: ' + data[1]["TS_PTable_ST_16"] + 'px;' +
            '             position: relative;' +
            '            transition: all 0.5s ease 0s;' +
            '            -moz-transition: all 0.5s ease 0s;' +
            '            -webkit-transition: all 0.5s ease 0s;' +
            '        }' +
            '        .TS_PTable_PCur_' + data[1]["id"] + ' {' +
            '             font-size: ' + data[1]["TS_PTable_ST_15"] + 'px;' +
            '         }' +
            '        .TS_PTable_PPlan_' + data[1]["id"] + ' {' +
            '             font-size: ' + data[1]["TS_PTable_ST_17"] + 'px;' +
            '             display: block;' +
            '        }' +
            '        .TS_PTable__' + data[1]["id"] + ':hover .TS_PTable_Amount_' + data[1]["id"] + ' {' +
            '             color: ' + data[1]["TS_PTable_ST_14"] + ';' +
            '         }' +
            '        .TS_PTable_Features_' + data[1]["id"] + ' {' +
            '             padding: 0 !important;' +
            '            margin: 0 0 30px 0 !important;' +
            '            list-style: none;' +
            '        }' +
            '        .TS_PTable_Features_' + data[1]["id"] + ' li {' +
            '             color: ' + data[1]["TS_PTable_ST_20"] + ';' +
            '             font-size: ' + data[1]["TS_PTable_ST_21"] + 'px;' +
            '             font-family: ' + data[1]["TS_PTable_ST_22"] + ';' +
            '             line-height: 1;' +
            '            padding: 10px;' +
            '            display: flex;' +
            '            align-items: center;' +
            '            justify-content: center;' +
            '        }' +
            '        .TS_PTable_Features_' + data[1]["id"] + ' li:before {' +
            '             content: "" !important;' +
            '            display: none !important;' +
            '        }' +
            '        .TS_PTable_FIcon_' + data[1]["id"] + ' {' +
            '             color: ' + data[1]["TS_PTable_ST_23"] + ';' +
            '             font-size: ' + data[1]["TS_PTable_ST_25"] + 'px;' +
            '             margin: 0 10px !important;' +
            '        }' +
            '        .TS_PTable_FIcon_' + data[1]["id"] + '.TS_PTable_FCheck {' +
            '             color: ' + data[1]["TS_PTable_ST_24"] + ';' +
            '         }' +
            '        .TS_PTable_Button_' + data[1]["id"] + ' {' +
            '             display: inline-block;' +
            '            padding: 10px 35px !important;' +
            '            font-size: ' + data[1]["TS_PTable_ST_27"] + 'px;' +
            '             font-family: ' + data[1]["TS_PTable_ST_28"] + ';' +
            '             background: ' + data[1]["TS_PTable_ST_31"] + ';' +
            '             color: ' + data[1]["TS_PTable_ST_32"] + ';' +
            '             border-radius: 20px;' +
            '            transition: all 0.3s ease 0s;' +
            '            -moz-transition: all 0.3s ease 0s;' +
            '            -webkit-transition: all 0.3s ease 0s;' +
            '            text-decoration: none;' +
            '            outline: none;' +
            '            box-shadow: none;' +
            '            -webkit-box-shadow: none;' +
            '            -moz-box-shadow: none;' +
            '            cursor: pointer !important;' +
            '        }' +
            '        .TS_PTable_Button_' + data[1]["id"] + ':hover {' +
            '             box-shadow: 0 0 10px ' + data[1]["TS_PTable_ST_31"] + ';' +
            '             -moz-box-shadow: 0 0 10px ' + data[1]["TS_PTable_ST_31"] + ';' +
            '             -webkit-box-shadow: 0 0 10px ' + data[1]["TS_PTable_ST_31"] + ';' +
            '             background: ' + data[1]["TS_PTable_ST_31"] + ';' +
            '             color: ' + data[1]["TS_PTable_ST_32"] + ';' +
            '         }' +
            '        .TS_PTable_Button_' + data[1]["id"] + ':hover {' +
            '             text-decoration: none;' +
            '            outline: none;' +
            '        }' +
            '        .TS_PTable_Button_' + data[1]["id"] + ':focus {' +
            '             text-decoration: none;' +
            '            outline: none;' +
            '            box-shadow: none;' +
            '            -webkit-box-shadow: none;' +
            '            -moz-box-shadow: none;' +
            '        }' +
            '        .TS_PTable_BIconA_' + data[1]["id"] + ', .TS_PTable_BIconB_' + data[1]["id"] + ' {' +
            '             font-size: ' + data[1]["TS_PTable_ST_29"] + 'px;' +
            '         }' +
            '        .TS_PTable_BIconB_' + data[1]["id"] + ' {' +
            '             margin: 0 10px 0 0 !important;' +
            '        }' +
            '        .TS_PTable_BIconA_' + data[1]["id"] + ' {' +
            '             margin: 0 10px !important;' +
            '        }' +
            '    </style>'
        )
        jQuery('.TS_PTable_Container').append('' +
            '   <div class=" TS_PTable_Container_Col_Copy TS_PTable_Container_Col_' + number + ' Total_Soft_PTable_AMMain_Div2_Cols1" id="TS_PTable_Col_' + number + '"> ' +
            '       <input type="text" style="display:none;" class="Total_Soft_PTable_Select" name="TS_PTable_ST5_' + Col_Count + '_ID" id="TS_PTable_ST5_' + number + '_ID" value="' + number + '"> ' +
            '       <input type="text" style="display:none;" class="Total_Soft_PTable_Select" name="TS_PTable_ST5_' + Col_Count + '_00" id="TS_PTable_ST5_' + number + '_00" value="Theme_' + number + '"> ' +
            '       <input type="text" style="display:none;" class="Total_Soft_PTable_Select" name="TS_PTable_ST5_' + Col_Count + '_index" id="TS_PTable_ST5_' + data[1]['id'] + '_index" value="' + parseInt(parseInt(last_id) - 1) + '">' +
            '       <input type="text" style="display:none" name="Total_Soft_PTable_Cols_Id" class="Total_Soft_PTable_Cols_Id" value="' + number + '"> ' +
            '       <input type="text" style="display:none" name="Total_Soft_PTable_Set_Title" class="Total_Soft_PTable_Set_Title" value="Theme_' + number + '"> ' +
            '       <div class="TS_PTable_Parent"> ' +
            '           <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Actions"> ' +
            '               <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action1"> ' +
            '                   <i class="totalsoft totalsoft-arrows" title="Reorder" ' +
            '                      onmouseup="TotalSoftPTable_ColumnDivdel()" onmousedown="TotalSoftPTable_ColumnDivSort(' + number + ',' + data[1]['index'] + ')"></i> ' +
            '               </div> ' +
            '               <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action2"> ' +
            '                   <i class="totalsoft totalsoft-file-text" title="Make Duplicate" ' +
            '                      onClick="TotalSoftPTable_Dup_Col(' + number + ', ' + Col_Count + ')"></i> ' +
            '               </div> ' +
            '               <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action3"> ' +
            '                   <i class="totalsoft totalsoft-trash" title="Delete Column" ' +
            '                      onClick="TotalSoftPTable_Del_Col(' + number + ')"></i> ' +
            '               </div> ' +
            '               <div class="Total_Soft_PTable_AMMain_Div2_Cols1_Action4"> ' +
            '                   <i class="totalsoft totalsoft-pencil" title="Edit Column" ' +
            '                      onClick="TotalSoftPTable_Col_Dragdown(); TotalSoftPTable_Edit_Theme(this,' + number + ')"></i> ' +
            '               </div> ' +
            '           </div> ' +
            '           <div class="TS_PTable_Shadow_' + number + '"> ' +
            '               <div class="TS_PTable__' + number + '"> ' +
            '                   <div class="TS_PTable_Div1_' + number + '"> ' +
            '                       <i  onClick="TS_Get_Icon_Title(this, ' + number + ')" class="totalsoft totalsoft-' + ((data[0]['TS_PTable_TIcon']=='none' )?'plus-square-o':data[0]['TS_PTable_TIcon']) + '"></i> ' +
            '                       <input type="hidden" class="Total_Soft_PTable_Select" id="TS_PTable_TIcon_' + number + '" name="TS_PTable_TIcon_' + Col_Count + '" value="' + data[0]['TS_PTable_TIcon'] + '">' +
            '                       <div class="span_hover TS_PTable_Amount_' + number + '"> ' +
            '                            <span class="TS_PTable_PCur_' + number + '" contentEditable="true" oninput="TS_Change_Cur_Val(this)" onClick="jQuery(this).focus();">' + data[0]['TS_PTable_PCur'] + '</span>' +
            '                           <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_PCur_Hidden" id="TS_PTable_New_PCur_' + number + '" name="TS_PTable_PCur_' + Col_Count + '" value="' + data[0]['TS_PTable_PCur'] + '">' +
            '                           <span class="TS_PTable_PVal_' + number + '" contentEditable="true" oninput="TS_Change_Val_Val(this)" onClick="jQuery(this).focus();"> ' + data[0]['TS_PTable_PVal'] + '</span>' +
            '                           <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_PVal_Hidden" id="TS_PTable_New_PVal_' + number + '" name="TS_PTable_PVal_' + Col_Count + '" value="' + data[0]['TS_PTable_PVal'] + '">' +
            '                           <div class="title_PPlan_Container">' +
            '                                <span class="TS_PTable_PPlan_' + number + '"  contentEditable="true" oninput="TS_Change_Plan_Val(this)" onClick="jQuery(this).focus();">' + data[0]['TS_PTable_PPlan'] + '</span>' +
            '                                <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_PPlan_Hidden" id="TS_PTable_New_PPlan_' + number + '" name="TS_PTable_PPlan_' + Col_Count + '" value="' + data[0]['TS_PTable_PPlan'] + '">' +
            '                           </div>' +
            '                       </div> ' +
            '                   </div> ' +
            '                   <div class="title_h3_Container TS_PTable_Header_' + number + '">' +
            '                       <h3 class="h3_hover TS_PTable_Title_' + number + '" onInput="TS_Change_H3_Val(this)" contentEditable="true" onClick="jQuery(this).focus();">' + data[0]['TS_PTable_TText'] + '</h3>' +
            '                       <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_TText_Hidden TS_PTable_TText" id="TS_PTable_New_TText_' + number + '" name="TS_PTable_TText_' + Col_Count + '" value="' + data[0]['TS_PTable_TText'] + '">' +
            '                   </div>' +
            '                   <div class="TS_PTable_Content_' + number + '"> ' +
            '                       <div class="relative feuture_cont_' + number + '"> ' +
            '                           <ul class="TS_PTable_Features_' + number + ' TS_PTable_Features"> ')
        var TS_PTable_FChek = '';
        var Total_Soft_PTable_Select_Icon = jQuery('#Total_Soft_PTable_Select_Icon').html();
        for (j = 0; j < data[0]['TS_PTable_FCount']; j++) {
            if (TS_PTable_FCheck[j] != '') {
                TS_PTable_FChek = 'TS_PTable_FCheck';
            } else {
                TS_PTable_FChek = '';
            }
            jQuery(".TS_PTable_Features_" + number).append('' +
                '                       <li onMouseOver="TS_PTable_Features_Li(this)" onMouseOut="TS_PTable_Features_Li_Out(this)" class="TS_Li_' + number + '_' + parseInt(parseInt(j) + 1) + '">' +
                '                                   <div class="Hiddem_Li_Container">' +
                '                                   <span class="hiddenChangeText"><i class="totalsoft totalsoft-times TS_PTable_FDI TS_PTable_FDI_' + number + '" title="Delete Feature" onClick="TotalSoftPTable_Del_FT(' + number + ', ' + parseInt(parseInt(j) + 1) + ')"></i>     </span>' +
                '                                   <span class="TS_PTable_FChecked TS_PTable_FCheckedHide TS_PTable_FChecked_' + number + '">' +
                '                                           <input onClick="TS_PTable_TS_PTable_FChecked_label(this,' + Col_Count + ')" type="checkbox" class="' + TS_PTable_FChek + '" id="TS_PTable_FChecked_' + number + '_' + parseInt(parseInt(j) + 1) + '" name="TS_PTable_FChecked_' + Col_Count + '_' + parseInt(parseInt(j) + 1) + '" value="' + parseInt(parseInt(j) + 1) + '">' +
                '                                           <label class="totalsoft totalsoft-question-circle-o" for="TS_PTable_FChecked_' + number + '_' + parseInt(parseInt(j) + 1) + '"></label>' +
                '                                   </span>' +
                '                               </div>' +
                '                               <div class="feut_container_' + number + ' feut_container">' +
                '                                   <div class="feut_text_cont">' +
                '                                       <span onmouseover="TS_Change_Feut_Val_Hover(this)" onmouseout="TS_Change_Feut_Val_Over(this)" class="TS_PTable_FText_' + number + '_' + parseInt(parseInt(j) + 1) + '" oninput="TS_Change_Feut_Val(this)" contentEditable="true" onClick="jQuery(this).focus();">' + TS_PTable_FText[j] + '</span>' +
                '                                       <input type="hidden" class="Total_Soft_PTable_Select TS_PTable_FText_' + number + ' TS_PTable_Feut_Hidden"  name="TS_PTable_FText_' + Col_Count + '_' + parseInt(parseInt(j) + 1) + '" value="' + TS_PTable_FText[j] + '">' +
                '                                   </div>' +
                '                                   <div class="feut_icon_cont">' +
                '                                       <i onClick="TS_Get_Icon_Feuture(this, ' + number + ', ' + parseInt(parseInt(j) + 1) + ')" class="totalsoft  TS_PTable_FIcon_' + number + ' ' + TS_PTable_FChek + ' totalsoft-' +(TS_PTable_FIcon[j]!='none'?TS_PTable_FIcon[j]:'plus-square-o ') + '"></i>' +
                '                                       <input type="hidden"  class="Total_Soft_PTable_Select  TS_PTable_FIcon_' + number + '_' + parseInt(parseInt(j) + 1) + '" name="TS_PTable_FIcon_' + Col_Count + '_' + parseInt(parseInt(j) + 1) + '" value="' + TS_PTable_FIcon[j] + '">' +
                '                                   </div>' +
                '                               </div>' +
                '                         </li>')
        }
        jQuery(".feuture_cont_" + number).append('' +
            ' </ul>' +
            '                           <input type="hidden"  class="TS_PTable_FCount" id="TS_PTable_FCount_' + number + '" name="TS_PTable_FCount_' + Col_Count + '" value="' + data[0]['TS_PTable_FCount'] + '">' +
            '                       </div>' +
            '                   </div>' +
            '                   <table id="Total_Soft_PTable_Features_Col_' + number + '"></table>' +
            '                   <table class="TS_Table Total_Soft_PTable_AMMain_Div2_Cols1_FTable1" id="Total_Soft_PTable_Features_' + number + '">' +
            '                       <tr>' +
            '                           <td colSpan="2">' +
            '                               <div class="Total_Soft_PTable_Features_New" onClick="Total_Soft_PTable_Features_New(' + number + ');Total_Soft_PTable_Features_New_Col(' + number + ',' +Col_Count + ',5)">' +
            '                                   <span class="Total_Soft_PTable_Features_New1">' +
            '                                   <i class="Total_Soft_PTable_Features_New_Icon totalsoft totalsoft-plus-circle" style="margin-right: 5px;"></i>Add New </span>' +
            '                               </div>' +
            '                           </td>' +
            '                       </tr>' +
            '                   </table>' +
            '                   <div class="TS_PTable_Div2_' + number + ' relative flex_center">' +
            '                       <div onclick="TS_Get_Icon_Button(this, ' + number + ')" class=" TS_PTable_Button_' + number + '">' +
            '                           <span class="Button_cont Button_cont_' + number + '">' +
            '                               <span class="Button_text_cont">' +
            '                                   <span onmouseover="TS_Change_Feut_Val_Hover(this)" onmouseout="TS_Change_Feut_Val_Over(this)" class="TS_PTable_BText_' + number + '" contentEditable="true">' + data[0]['TS_PTable_BText'] + '</span>' +
            '                                   <input type="hidden" class="Total_Soft_PTable_Select" id="TS_PTable_BText_' + number + '" name="TS_PTable_BText_' + Col_Count + '" value="' + data[0]['TS_PTable_BText'] + '">' +
            '                               </span>' +
            '                               <span class="Button_icon_cont">' +
            '                                   <i class="totalsoft   TS_PTable_BIconA_' + number + ' totalsoft-' + ( data[0]['TS_PTable_BIcon']!='none'? data[0]['TS_PTable_BIcon']:'plus-square-o ') + '" ></i>' +
           '                                   <select onchange= "TS_ChangeBut_Icon_Select(this,' + last_id + ')" class="Total_Soft_PTable_Select  TS_PTable_BIcon TS_PTable_BIcon_' + last_id + '" onchange="ChangeValueForHiddenBIcon(this)"  name="TS_PTable_BIcon_' + last_id + '" style="font-family: FontAwesome, Arial;">'+Total_Soft_PTable_Select_Icon+'</select>'+
            
            '                                   <input type="hidden" class="Total_Soft_PTable_Select" id="TS_PTable_BIcon_' + number + '" name="TS_PTable_BIcon_' + Col_Count + '" value="' + data[0]['TS_PTable_BIcon'] + '">' +
            '                               </span>' +
            '                           </span>' +
            '                           <div class=" TS_PTable_Button_Link_' + number + ' TS_PTable_Button_Link" style="top:90px">' +
            '                            <i class="totalsoft  totalsoft-link" ></i>' +
            '                            <input type="text" class="Total_Soft_PTable_Select TS_PTable_BLink " id="TS_PTable_BLink_' + number + '" name="TS_PTable_BLink_' + Col_Count + '" value="' + data[0]['TS_PTable_BLink'] + '">' +
            '                       </div>' +
            '                      </div>' +

            '                   </div>' +
            '               </div>' +
            '           </div>' +
            '           </div>' +
            '   </div>'
        )
        if (data[1]["TS_PTable_ST_02"] == 'on') {
            jQuery(".TS_PTable_Container_Col_" + data[1]['id']).attr('style',
                "-webkit-transform:  translate3d(0, 0, 0) scale(1, 1.1); -moz-transform:  translate3d(0, 0, 0) scale(1, 1.1);transform:  translate3d(0, 0, 0) scale(1, 1.1);"
            ).addClass('hover_efect_on');
        
        } else {
             jQuery(".TS_PTable_Container_Col_" + data[1]['id']).attr('style',
                "-webkit-transform:  translate3d(0, 0, 0) scale(1, 1); -moz-transform:  translate3d(0, 0, 0) scale(1, 1);transform:  translate3d(0, 0, 0) scale(1, 1);"
            ).addClass('hover_efect');
         
        }
        for (var k = 1; k <= parseInt(data[0]['TS_PTable_FCount']); k++) {
            jQuery("#TS_PTable_FIcon_" + number + "_" + k).html(Total_Soft_PTable_Select_Icon);
            jQuery("#TS_PTable_FIcon_" + number + "_" + k).val(TS_PTable_FIcon[k - 1]);
            if (jQuery('#TS_PTable_FChecked_' + number + '_' + k).val() == TS_PTable_FCheck[k - 1]) {
                jQuery('#TS_PTable_FChecked_' + number + '_' + k).attr('checked', 'checked');
            }
        }
         jQuery('.TS_PTable_BIcon_' + last_id ).val( jQuery('#TS_PTable_BIcon_' + last_id  ).val());
        jQuery("#TS_PTable_Col_" + number).append('' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_01" name="TS_PTable_ST5_' + Col_Count + '_01" value="' + data[1]['TS_PTable_ST_01'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_02" name="TS_PTable_ST5_' + Col_Count + '_02" value="' + data[1]['TS_PTable_ST_02'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_03" name="TS_PTable_ST5_' + Col_Count + '_03" value="' + data[1]['TS_PTable_ST_03'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_04" name="TS_PTable_ST5_' + Col_Count + '_04" value="' + data[1]['TS_PTable_ST_04'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_05" name="TS_PTable_ST5_' + Col_Count + '_05" value="' + data[1]['TS_PTable_ST_05'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_06" name="TS_PTable_ST5_' + Col_Count + '_06" value="' + data[1]['TS_PTable_ST_06'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_07" name="TS_PTable_ST5_' + Col_Count + '_07" value="' + data[1]['TS_PTable_ST_07'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_08" name="TS_PTable_ST5_' + Col_Count + '_08" value="' + data[1]['TS_PTable_ST_08'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_09" name="TS_PTable_ST5_' + Col_Count + '_09" value="' + data[1]['TS_PTable_ST_09'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_10" name="TS_PTable_ST5_' + Col_Count + '_10" value="' + data[1]['TS_PTable_ST_10'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_11" name="TS_PTable_ST5_' + Col_Count + '_11" value="' + data[1]['TS_PTable_ST_11'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_12" name="TS_PTable_ST5_' + Col_Count + '_12" value="' + data[1]['TS_PTable_ST_12'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_13" name="TS_PTable_ST5_' + Col_Count + '_13" value="' + data[1]['TS_PTable_ST_13'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_14" name="TS_PTable_ST5_' + Col_Count + '_14" value="' + data[1]['TS_PTable_ST_14'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_15" name="TS_PTable_ST5_' + Col_Count + '_15" value="' + data[1]['TS_PTable_ST_15'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_16" name="TS_PTable_ST5_' + Col_Count + '_16" value="' + data[1]['TS_PTable_ST_16'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_17" name="TS_PTable_ST5_' + Col_Count + '_17" value="' + data[1]['TS_PTable_ST_17'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_18" name="TS_PTable_ST5_' + Col_Count + '_18" value="' + data[1]['TS_PTable_ST_18'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_19" name="TS_PTable_ST5_' + Col_Count + '_19" value="' + data[1]['TS_PTable_ST_19'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_20" name="TS_PTable_ST5_' + Col_Count + '_20" value="' + data[1]['TS_PTable_ST_20'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_21" name="TS_PTable_ST5_' + Col_Count + '_21" value="' + data[1]['TS_PTable_ST_21'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_21_1" name="TS_PTable_ST5_' + Col_Count + '_21_1" value="' + data[1]['TS_PTable_ST_21_1'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_22" name="TS_PTable_ST5_' + Col_Count + '_22" value="' + data[1]['TS_PTable_ST_22'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_23" name="TS_PTable_ST5_' + Col_Count + '_23" value="' + data[1]['TS_PTable_ST_23'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_24" name="TS_PTable_ST5_' + Col_Count + '_24" value="' + data[1]['TS_PTable_ST_24'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_25" name="TS_PTable_ST5_' + Col_Count + '_25" value="' + data[1]['TS_PTable_ST_25'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_26" name="TS_PTable_ST5_' + Col_Count + '_26" value="' + data[1]['TS_PTable_ST_26'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_27" name="TS_PTable_ST5_' + Col_Count + '_27" value="' + data[1]['TS_PTable_ST_27'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_28" name="TS_PTable_ST5_' + Col_Count + '_28" value="' + data[1]['TS_PTable_ST_28'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_29" name="TS_PTable_ST5_' + Col_Count + '_29" value="' + data[1]['TS_PTable_ST_29'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_30" name="TS_PTable_ST5_' + Col_Count + '_30" value="' + data[1]['TS_PTable_ST_30'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_31" name="TS_PTable_ST5_' + Col_Count + '_31" value="' + data[1]['TS_PTable_ST_31'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_32" name="TS_PTable_ST5_' + Col_Count + '_32" value="' + data[1]['TS_PTable_ST_32'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_33" name="TS_PTable_ST5_' + Col_Count + '_33" value="' + data[1]['TS_PTable_ST_33'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_34" name="TS_PTable_ST5_' + Col_Count + '_34" value="' + data[1]['TS_PTable_ST_34'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_35" name="TS_PTable_ST5_' + Col_Count + '_35" value="' + data[1]['TS_PTable_ST_35'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_36" name="TS_PTable_ST5_' + Col_Count + '_36" value="' + data[1]['TS_PTable_ST_36'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_37" name="TS_PTable_ST5_' + Col_Count + '_37" value="' + data[1]['TS_PTable_ST_37'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_38" name="TS_PTable_ST5_' + Col_Count + '_38" value="' + data[1]['TS_PTable_ST_38'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_39" name="TS_PTable_ST5_' + Col_Count + '_39" value="' + data[1]['TS_PTable_ST_39'] + '">' +
            '<input type="hidden" class="TS_PTable_ST5_' + number + '_40" name="TS_PTable_ST5_' + Col_Count + '_40" value="' + data[1]['TS_PTable_ST_40'] + '">'
        )
    }
    New_Col_data = [];-

        jQuery('.Total_Soft_PTable_AddColBut').css("background-color", "#ff9941");
     jQuery('.Total_Soft_PTable_AMMain_Div2_Cols1_Action2 i').css("color", "#ff9941");
     
   flag='false';
      }
    });


}

function ChangeValueForHiddenTText(This_Val) {
    if (jQuery("#Total_Soft_PTable_Dup").val() == 1) {
        jQuery(This_Val).parents().find(".TS_PTable_New_TText").val(jQuery(This_Val).val());
    }
}

function ChangeValueForHiddenTIcon(This_Val) {
    if (jQuery("#Total_Soft_PTable_Dup").val() == 1) {
        jQuery(This_Val).parents().find('.TS_PTable_New_TIcon').val(jQuery(This_Val).val());
    }
}

function ChangeValueForHiddenPCur(This_Val) {
    if (jQuery("#Total_Soft_PTable_Dup").val() == 1) {
        jQuery(This_Val).parents().find('.TS_PTable_New_PCur').val(jQuery(This_Val).val());
    }
}

function ChangeValueForHiddenPVal(This_Val) {
    if (jQuery("#Total_Soft_PTable_Dup").val() == 1) {
        jQuery(This_Val).parents().find('.TS_PTable_New_PVal').val(jQuery(This_Val).val());
    }
}

function ChangeValueForHiddenPPlan(This_Val) {
    if (jQuery("#Total_Soft_PTable_Dup").val() == 1) {
        jQuery(This_Val).parents().find('.TS_PTable_New_PValue').val(jQuery(This_Val).val());
    }
}

function TS_Ch_Inp_BT_Text(This_Val) {
    if (jQuery("#Total_Soft_PTable_Dup").val() == 1) {
        jQuery(This_Val).parents().find('.TS_PTable_New_BText').val(jQuery(This_Val).val());
    }
}

function TS_Ch_Inp_BT_Link(This_Val) {
    if (jQuery("#Total_Soft_PTable_Dup").val() == 1) {
        jQuery(This_Val).parents().find('.TS_PTable_New_BLink').val(jQuery(This_Val).val());
    }
}

function ChangeValueForHiddenBIcon(This_Val) {
    if (jQuery("#Total_Soft_PTable_Dup").val() == 1) {
        jQuery(This_Val).parents().find('.TS_PTable_New_BIcon').val(jQuery(This_Val).val());
    }
}

// ----------------------------------------------------------Changing Settings of type 1 ------------------------------------------------------------------------------------
function TS_Ch_Col_Width(This_val, Total_Soft_PTable_Cols_Id) {
      var TS_ChangedWidthValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent(".TS_PTable_Container_Col_" + Total_Soft_PTable_Cols_Id).css("width", TS_ChangedWidthValue + "%");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST1_" + Total_Soft_PTable_Cols_Id + "_01").val(TS_ChangedWidthValue);
}

function TS_Ch_1_Col_Scale(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    
    if (jQuery('#TS_PTable_ST1_' + Total_Soft_PTable_Cols_Id + '_02').is(":checked")) {
        jQuery(TS_Same_Id).parent(".TS_PTable_Container_Col_" + Total_Soft_PTable_Cols_Id).addClass('toggle_transform');
        jQuery(TS_Same_Id).parent(".TS_PTable_Container_Col_" + Total_Soft_PTable_Cols_Id).removeClass('noScale');
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST1_" + Total_Soft_PTable_Cols_Id + "_02").val("on");
    } else {
        jQuery(TS_Same_Id).parent(".TS_PTable_Container_Col_" + Total_Soft_PTable_Cols_Id).removeClass('toggle_transform');
        jQuery(TS_Same_Id).parent(".TS_PTable_Container_Col_" + Total_Soft_PTable_Cols_Id).addClass('noScale');
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST1_" + Total_Soft_PTable_Cols_Id + "_02").val("off");
    }
}

function TS_Ch_Inp_1_Back_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type1") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find(".TS_PTable__" + Total_Soft_PTable_Cols_Id).css("background", TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST1_" + Total_Soft_PTable_Cols_Id + "_03").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_1_Border_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type1") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find(".TS_PTable__" + Total_Soft_PTable_Cols_Id).css("border-color", TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST1_" + Total_Soft_PTable_Cols_Id + "_04").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_1_Border_Width(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable__" + Total_Soft_PTable_Cols_Id).css("border-width", TS_ChangedValue + 'px');
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST1_" + Total_Soft_PTable_Cols_Id + "_05").val(TS_ChangedValue);
}

function TS_Ch_Inp_1_Shadow_Type(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
       const root = document.querySelector(":root");
              root.style.setProperty("--pseudo-backgroundcolor",  jQuery(TS_Same_Id).parents().find(".TS_PTable_ST1_" + Total_Soft_PTable_Cols_Id + "_07").val());
    
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST1_" + Total_Soft_PTable_Cols_Id + "_06").val(TS_ChangedValue);
    switch (TS_ChangedValue) {
        case "none":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).css({
                " box-shadow": "none",
                "-moz-box-shadow": "none",
                "-webkit-box-shadow": "none"
            })
            break;
        case "shadow01":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_1_01_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow02":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_1_02_" + Total_Soft_PTable_Cols_Id);

            break;
        case "shadow03":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_1_03_" + Total_Soft_PTable_Cols_Id);

            break;
        case "shadow04":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_1_04_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow05":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_1_05_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow06":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_1_06_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow07":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_1_07_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow08":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_1_08_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow09":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_1_09_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow10":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_1_10_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow11":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_1_11_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow12":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_1_12_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow13":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_1_13_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow14":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_1_14_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow15":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_1_15_" + Total_Soft_PTable_Cols_Id);
            break;
        default:
// code block
    }
}

function TS_Ch_1_Inp_Shadow_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type1") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        const root = document.querySelector(":root");
        root.style.setProperty("--pseudo-backgroundcolor"+ Total_Soft_PTable_Cols_Id,  TS_ChangedValue);
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST1_" + Total_Soft_PTable_Cols_Id + "_07").val(TS_ChangedValue);
    }
}

function TS_Ch_1_Inp_Font_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_Title_" + Total_Soft_PTable_Cols_Id).css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST1_" + Total_Soft_PTable_Cols_Id + "_08").val(TS_ChangedValue);
}

function TS_Ch_1_Inp_Font_Family(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_Title_" + Total_Soft_PTable_Cols_Id).css("font-family", TS_ChangedValue);
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST1_" + Total_Soft_PTable_Cols_Id + "_09").val(TS_ChangedValue);
}

function TS_Ch_1_Inp_Font_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type1") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parents().find(".TS_PTable_Title_" + Total_Soft_PTable_Cols_Id).css("color", TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST1_" + Total_Soft_PTable_Cols_Id + "_10").val(TS_ChangedValue);
    }
}

function TS_Ch_1_Inp_Icon_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type1") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find('.TS_PTable_Title_Icon_' + Total_Soft_PTable_Cols_Id).css("color", TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST1_" + Total_Soft_PTable_Cols_Id + "_11").val(TS_ChangedValue);
    }
}

function TS_Ch_1_Inp_Icon_Font_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find('.TS_PTable_Title_Icon_' + Total_Soft_PTable_Cols_Id).css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST1_" + Total_Soft_PTable_Cols_Id + "_12").val(TS_ChangedValue);
}

function TS_Ch_1_Inp_Font_Position(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST1_" + Total_Soft_PTable_Cols_Id + "_13").val(TS_ChangedValue);
    if (TS_ChangedValue == "after") {
        jQuery(TS_Same_Id).parents().find(".title_Position_" + Total_Soft_PTable_Cols_Id + "").attr("style", "display:flex; flex-direction:row-reverse; justify-content:center;");
    } else if (TS_ChangedValue == "before") {
        jQuery(TS_Same_Id).parents().find(".title_Position_" + Total_Soft_PTable_Cols_Id + "").attr("style", "display:flex; justify-content:center;");
    } else if (TS_ChangedValue == "above") {
        jQuery(TS_Same_Id).parents().find(".title_Position_" + Total_Soft_PTable_Cols_Id + "").attr("style", "display:flex; flex-direction:column;");
    } else if (TS_ChangedValue == "under") {
        jQuery(TS_Same_Id).parents().find(".title_Position_" + Total_Soft_PTable_Cols_Id + "").attr("style", "display:flex; flex-direction:column-reverse;");
    }
}

function TS_Ch_1_Inp_PV_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_PValue_" + Total_Soft_PTable_Cols_Id).css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST1_" + Total_Soft_PTable_Cols_Id + "_14").val(TS_ChangedValue);
}

function TS_Ch_1_Inp_PV_Font_Family(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_PValue_" + Total_Soft_PTable_Cols_Id).css("font-family", TS_ChangedValue);
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST1_" + Total_Soft_PTable_Cols_Id + "_15").val(TS_ChangedValue);
}

function TS_Ch_1_Inp_PV_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type1") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find(".TS_PTable_PValue_" + Total_Soft_PTable_Cols_Id).css("color", TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST1_" + Total_Soft_PTable_Cols_Id + "_16").val(TS_ChangedValue);
    }
}

function TS_Ch_1_Inp_PL_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_PPlan_" + Total_Soft_PTable_Cols_Id).css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST1_" + Total_Soft_PTable_Cols_Id + "_17").val(TS_ChangedValue);
}

function TS_Ch_1_Inp_PL_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type1") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find(".TS_PTable_PPlan_" + Total_Soft_PTable_Cols_Id).css("color", TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST1_" + Total_Soft_PTable_Cols_Id + "_18").val(TS_ChangedValue);
    }
}

function TS_Ch_1_Inp_PVColor1(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type1") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Features_" + Total_Soft_PTable_Cols_Id).children("li:even").css("background-color", TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST1_" + Total_Soft_PTable_Cols_Id + "_20").val(TS_ChangedValue);
    }
}

function TS_Ch_1_Inp_PVColor2(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type1") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Features_" + Total_Soft_PTable_Cols_Id).children("li:odd").css("background-color", TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST1_" + Total_Soft_PTable_Cols_Id + "_19").val(TS_ChangedValue);
    }
}

function TS_Ch_InpText1(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValueText = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id")
    jQuery(TS_Same_Id).parent().find(".TS_PTable_Features_" + Total_Soft_PTable_Cols_Id).children("li:odd").css("color", TS_ChangedValueText);
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST1_" + Total_Soft_PTable_Cols_Id + "_21").val(TS_ChangedValueText);
}

function TS_Ch_InpText2(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValueText = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id")
    jQuery(TS_Same_Id).parent().find(".TS_PTable_Features_" + Total_Soft_PTable_Cols_Id).children("li:even").css("color", TS_ChangedValueText);
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST1_" + Total_Soft_PTable_Cols_Id + "_21_1").val(TS_ChangedValueText);
}

function TS_Ch_Inp_1_Feut_Text_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValueText = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id")
    jQuery(TS_Same_Id).parent().find(".TS_PTable_Features_" + Total_Soft_PTable_Cols_Id).children("li").css("font-size", TS_ChangedValueText + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST1_" + Total_Soft_PTable_Cols_Id + "_22").val(TS_ChangedValueText);
}

function TS_Ch_Inp_Feut_1_Text_Family(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValueText = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id")
    jQuery(TS_Same_Id).parent().find(".TS_PTable_Features_" + Total_Soft_PTable_Cols_Id).children("li").css("font-family", TS_ChangedValueText);
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST1_" + Total_Soft_PTable_Cols_Id + "_23").val(TS_ChangedValueText);
}

function TS_Ch_Inp_1_Feut_Icon_Col(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type1") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Features_" + Total_Soft_PTable_Cols_Id).find(".TS_PTable_FIcon_" + Total_Soft_PTable_Cols_Id).not(".TS_PTable_FCheck").attr("style", "color:" + TS_ChangedValue + " !important");
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST1_" + Total_Soft_PTable_Cols_Id + "_24").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_1_PL_Icon(This_val, Total_Soft_PTable_Cols_Id, type) {

    if (type == "type1") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Features_" + Total_Soft_PTable_Cols_Id).find(".TS_PTable_FCheck").attr('style', "color:" + TS_ChangedValue + " !important");
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST1_" + Total_Soft_PTable_Cols_Id + "_25").val(TS_ChangedValue);
    }
}

function TS_Ch_1_Inp_PL_Ic(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_Features_" + Total_Soft_PTable_Cols_Id).find(".TS_PTable_FIcon_" + Total_Soft_PTable_Cols_Id).attr('style', "font-size:" + TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST1_" + Total_Soft_PTable_Cols_Id + "_26").val(TS_ChangedValue);
}

function TS_Ch_1_Inp_Feut_icon_Position(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    if (TS_ChangedValue == "after") {
        jQuery(TS_Same_Id).parents().find(".feut_container_" + Total_Soft_PTable_Cols_Id + "").attr("style", "flex-direction:row;");
    } else if (TS_ChangedValue == "before") {
        jQuery(TS_Same_Id).parents().find(".feut_container_" + Total_Soft_PTable_Cols_Id + "").attr("style", "flex-direction:row-reverse;");
    }
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST1_" + Total_Soft_PTable_Cols_Id + "_27").val(TS_ChangedValue);
}

function TS_Ch_Inp_1_But_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type1") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Button_" + Total_Soft_PTable_Cols_Id).css("background-color", TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST1_" + Total_Soft_PTable_Cols_Id + "_28").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_1_But_Font_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type1") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Button_" + Total_Soft_PTable_Cols_Id).not(jQuery(".TS_PTable_Button_" + Total_Soft_PTable_Cols_Id + "").children('i')).css("color", TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST1_" + Total_Soft_PTable_Cols_Id + "_29").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_1_But_Font_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_Button_" + Total_Soft_PTable_Cols_Id).css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST1_" + Total_Soft_PTable_Cols_Id + "_30").val(TS_ChangedValue);
}

function TS_Ch_Inp_1_But_Font_Family(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_Button_" + Total_Soft_PTable_Cols_Id).css("font-family", TS_ChangedValue);
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST1_" + Total_Soft_PTable_Cols_Id + "_31").val(TS_ChangedValue);
}

function TS_Ch_Inp_1_But_Icon_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_Button_" + Total_Soft_PTable_Cols_Id).children("i").css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST1_" + Total_Soft_PTable_Cols_Id + "_32").val(TS_ChangedValue);
}

function TS_Ch_Inp_1_ButIcon_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type1") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Button_" + Total_Soft_PTable_Cols_Id).children("i").attr("style", "color:" + TS_ChangedValue + " !important");
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST1_" + Total_Soft_PTable_Cols_Id + "_33").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_1_But_Icon_Position(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    if (TS_ChangedValue == "after") {
        jQuery(TS_Same_Id).parents().find(".Button_cont_" + Total_Soft_PTable_Cols_Id + "").attr("style", "flex-direction:row;");
    } else if (TS_ChangedValue == "before") {
        jQuery(TS_Same_Id).parents().find(".Button_cont_" + Total_Soft_PTable_Cols_Id + "").attr("style", "flex-direction:row-reverse;");
    }
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST1_" + Total_Soft_PTable_Cols_Id + "_34").val(TS_ChangedValue);
}

function TS_PTable_Features_Li(This_Val) {
    jQuery(This_Val).find(".Hiddem_Li_Container").toggleClass("Hiddem_Li_Container_none")
    jQuery(This_Val).css("border", "2px solid #71d7f7");


}

function TS_PTable_Features_Li_Out(This_Val) {
    jQuery(This_Val).find(".Hiddem_Li_Container").toggleClass("Hiddem_Li_Container_none")
    jQuery(This_Val).css("border", "none");
}

function TS_OpenHiddenDesc(This_Val, This_Arg) {
    jQuery(This_Val).next(".hiddenDescDiv").toggle();
    jQuery("#Total_Soft_PTable_Col_Val_Id").val(This_Arg);
    jQuery("#Total_Soft_PTable_Col_Id").val(This_Arg);
    jQuery(This_Val).next(".hiddenDescDiv").find(jQuery('.Total_Soft_PTable_AMMain_Div2_Cols1_None').animate({opacity: "1"}, 500));
}

function TS_PT_TM_But(num, type, col_id) {
    jQuery('.TS_PT_Option_Div_' + num + '_' + type).css('display', 'none');
    jQuery('.Total_Soft_PT_AMSetDiv_Button_' + num + '_' + type).removeClass('Total_Soft_PT_AMSetDiv_Button_C');
    jQuery('#TS_PT_TM_TBut_' + num + '_' + type + '_' + col_id).addClass('Total_Soft_PT_AMSetDiv_Button_C');
    jQuery('#Total_Soft_PT_AMSetTable_' + num + '_' + type + '_' + col_id).css('display', 'block');
}

// ----------------------------------------------------------Changing Settings of type 2 ------------------------------------------------------------------------------------
function TS_Ch_2_Col_Width(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedWidthValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
   
    jQuery(TS_Same_Id).parent(".TS_PTable_Container_Col_" + Total_Soft_PTable_Cols_Id).css("width", TS_ChangedWidthValue + "%");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_ST2_" + Total_Soft_PTable_Cols_Id + "_01").val(TS_ChangedWidthValue);
}

function TS_Ch_2_Col_Scale(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    
    if (jQuery('#TS_PTable_ST2_' + Total_Soft_PTable_Cols_Id + '_02').is(":checked")) {
        jQuery(TS_Same_Id).parent(".TS_PTable_Container_Col_" + Total_Soft_PTable_Cols_Id).addClass('toggle_transform');
        jQuery(TS_Same_Id).parent(".TS_PTable_Container_Col_" + Total_Soft_PTable_Cols_Id).removeClass('noScale');
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST2_" + Total_Soft_PTable_Cols_Id + "_02").val("on");
    } else {
        jQuery(TS_Same_Id).parent(".TS_PTable_Container_Col_" + Total_Soft_PTable_Cols_Id).removeClass('toggle_transform');
        jQuery(TS_Same_Id).parent(".TS_PTable_Container_Col_" + Total_Soft_PTable_Cols_Id).addClass('noScale');
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST2_" + Total_Soft_PTable_Cols_Id + "_02").val("off");
    }
}

function TS_Ch_Inp_2_Back_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type2") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Div1_" + Total_Soft_PTable_Cols_Id).css("background", TS_ChangedValue);
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Div2_" + Total_Soft_PTable_Cols_Id).css("background", TS_ChangedValue);
        jQuery(TS_Same_Id).parent().find(".TS_PTable_ST2_" + Total_Soft_PTable_Cols_Id + "_03").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_2_Shadow_Type(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    const root = document.querySelector(":root");
    root.style.setProperty("--pseudo-backgroundcolor"+ Total_Soft_PTable_Cols_Id,  jQuery(TS_Same_Id).parents().find(".TS_PTable_ST2_" + Total_Soft_PTable_Cols_Id + "_05").val());
    
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST2_" + Total_Soft_PTable_Cols_Id + "_04").val(TS_ChangedValue);
    switch (TS_ChangedValue) {
        case "none":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).css({
                " box-shadow": "none",
                "-moz-box-shadow": "none",
                "-webkit-box-shadow": "none"
            })
            break;
        case "shadow01":
         jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).css({
                " box-shadow": ' 0 10px 6px -6px ' + TS_ChangedValue,
                "-moz-box-shadow": ' 0 10px 6px -6px ' + TS_ChangedValue,
                "-webkit-box-shadow": ' 0 10px 6px -6px ' + TS_ChangedValue
            })
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_2_01_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow02":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_2_02_" + Total_Soft_PTable_Cols_Id);

            break;
        case "shadow03":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_2_03_" + Total_Soft_PTable_Cols_Id);

            break;
        case "shadow04":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_2_04_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow05":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_2_05_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow06":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_2_06_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow07":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_2_07_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow08":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_2_08_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow09":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_2_09_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow10":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_2_10_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow11":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_2_11_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow12":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_2_12_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow13":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_2_13_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow14":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_2_14_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow15":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_2_15_" + Total_Soft_PTable_Cols_Id);
            break;
        default:
// code block
    }
}

function TS_Ch_2_Inp_Shadow_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type2") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        const root = document.querySelector(":root");
        root.style.setProperty("--pseudo-backgroundcolor"+ Total_Soft_PTable_Cols_Id,  TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST2_" + Total_Soft_PTable_Cols_Id + "_05").val(TS_ChangedValue);
    }
}

function TS_Ch_2_Inp_Font_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_Title_" + Total_Soft_PTable_Cols_Id).css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST2_" + Total_Soft_PTable_Cols_Id + "_06").val(TS_ChangedValue);
}

function TS_Ch_2_Inp_Font_Family(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_Title_" + Total_Soft_PTable_Cols_Id).css("font-family", TS_ChangedValue);
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST2_" + Total_Soft_PTable_Cols_Id + "_07").val(TS_ChangedValue);
}

function TS_Ch_2_Inp_Font_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type2") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Title_" + Total_Soft_PTable_Cols_Id).css("color", TS_ChangedValue);
        jQuery(TS_Same_Id).parent().find(".TS_PTable_ST2_" + Total_Soft_PTable_Cols_Id + "_08").val(TS_ChangedValue);
    }
}

function TS_Ch_2_Inp_Icon_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type2") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Title_Icon_" + Total_Soft_PTable_Cols_Id).css("color", TS_ChangedValue);
        jQuery(TS_Same_Id).parent().find(".TS_PTable_ST2_" + Total_Soft_PTable_Cols_Id + "_09").val(TS_ChangedValue);
    }
}

function TS_Ch_2_Inp_Icon_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_Title_Icon_" + Total_Soft_PTable_Cols_Id).css("font-size", TS_ChangedValue + 'px');
    jQuery(TS_Same_Id).parent().find(".TS_PTable_ST2_" + Total_Soft_PTable_Cols_Id + "_10").val(TS_ChangedValue);
}

function TS_Ch_Inp2ST(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type2") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find(".TS_PTable_PValue_" + Total_Soft_PTable_Cols_Id).css("background", TS_ChangedValue);
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Button_" + Total_Soft_PTable_Cols_Id).css("background", TS_ChangedValue);
        jQuery(TS_Same_Id).parent().find(".TS_PTable_ST2_" + Total_Soft_PTable_Cols_Id + "_11").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_2_PV_Font(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_Amount_" + Total_Soft_PTable_Cols_Id).css("font-family", TS_ChangedValue);
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST2_" + Total_Soft_PTable_Cols_Id + "_12").val(TS_ChangedValue);
}

function TS_Ch_Inp_2_PV_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type2") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Amount_" + Total_Soft_PTable_Cols_Id).children().css("color", TS_ChangedValue);
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Button_" + Total_Soft_PTable_Cols_Id).css("color", TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST2_" + Total_Soft_PTable_Cols_Id + "_13").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_2_PCur_Size(This_val, Total_Soft_PTable_Cols_Id) {

    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_PCur_" + Total_Soft_PTable_Cols_Id).css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST2_" + Total_Soft_PTable_Cols_Id + "_14").val(TS_ChangedValue);
}

function TS_Ch_Inp_2_PPrice_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_PPrice_" + Total_Soft_PTable_Cols_Id).css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST2_" + Total_Soft_PTable_Cols_Id + "_15").val(TS_ChangedValue);
}

function TS_Ch_Inp_2_PPlan_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_PPlan_" + Total_Soft_PTable_Cols_Id).css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST2_" + Total_Soft_PTable_Cols_Id + "_16").val(TS_ChangedValue);
}

function TS_Ch_Inp_2_PPlan_Hover_Background(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type2") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST2_" + Total_Soft_PTable_Cols_Id + "_17").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_2_PPlan_Hover_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type2") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST2_" + Total_Soft_PTable_Cols_Id + "_18").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_2_Feut_Back(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type2") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Features_" + Total_Soft_PTable_Cols_Id).children("li").css("background-color", TS_ChangedValue);
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Features_" + Total_Soft_PTable_Cols_Id).css("background-color", TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST2_" + Total_Soft_PTable_Cols_Id + "_19").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_2_Feut_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type2") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id")
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Features_" + Total_Soft_PTable_Cols_Id).children("li").css("color", TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST2_" + Total_Soft_PTable_Cols_Id + "_20").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_2_Feut_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_Features_" + Total_Soft_PTable_Cols_Id).children("li").css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST2_" + Total_Soft_PTable_Cols_Id + "_21").val(TS_ChangedValue);
}

function TS_Ch_Inp_2_Feut_Family(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_Features_" + Total_Soft_PTable_Cols_Id).children("li").css("font-family", TS_ChangedValue);
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST2_" + Total_Soft_PTable_Cols_Id + "_22").val(TS_ChangedValue);
}

function TS_Ch_Inp_Feut_2_Icon_Col(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type2") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id")
        jQuery(TS_Same_Id).parent().find(".TS_PTable_FIcon_" + Total_Soft_PTable_Cols_Id).not(".TS_PTable_FCheck").attr("style", "color:" + TS_ChangedValue + " !important");
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST2_" + Total_Soft_PTable_Cols_Id + "_23").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_Feut_2_Icon_Col_Sel(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type2") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("TS_PTable_Container_Col_" + Total_Soft_PTable_Cols_Id)
        jQuery(TS_Same_Id).find(".TS_PTable_FCheck").attr("style", "color:" + TS_ChangedValue + " !important");
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST2_" + Total_Soft_PTable_Cols_Id + "_24").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_Feut_2_Icon_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_FIcon_" + Total_Soft_PTable_Cols_Id).css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST2_" + Total_Soft_PTable_Cols_Id + "_25").val(TS_ChangedValue);
}

function TS_Ch_Inp_2_Feut_Icon_Position(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    if (TS_ChangedValue == "after") {
        jQuery(TS_Same_Id).parents().find(".feut_container_" + Total_Soft_PTable_Cols_Id + "").attr("style", "flex-direction:row;");
    } else if (TS_ChangedValue == "before") {
        jQuery(TS_Same_Id).parents().find(".feut_container_" + Total_Soft_PTable_Cols_Id + "").attr("style", "flex-direction:row-reverse;");
    }
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST2_" + Total_Soft_PTable_Cols_Id + "_26").val(TS_ChangedValue);
}

function TS_Ch_Inp_2_But_Font_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_BText_" + Total_Soft_PTable_Cols_Id).css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST2_" + Total_Soft_PTable_Cols_Id + "_27").val(TS_ChangedValue);
}

function TS_Ch_Inp_2_But_Font_Family(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_BText_" + Total_Soft_PTable_Cols_Id).css("font-family", TS_ChangedValue);
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST2_" + Total_Soft_PTable_Cols_Id + "_28").val(TS_ChangedValue);
}

function TS_Ch_Inp_2_But_Icon_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_Button_" + Total_Soft_PTable_Cols_Id).find("i").css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST2_" + Total_Soft_PTable_Cols_Id + "_29").val(TS_ChangedValue);
}

function TS_Ch_Inp_2_But_Icon_Position(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    if (TS_ChangedValue == "after") {
        jQuery(TS_Same_Id).parents().find(".Button_cont_" + Total_Soft_PTable_Cols_Id + "").attr("style", "flex-direction:row;");
    } else if (TS_ChangedValue == "before") {
        jQuery(TS_Same_Id).parents().find(".Button_cont_" + Total_Soft_PTable_Cols_Id + "").attr("style", "flex-direction:row-reverse;");
    }
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST2_" + Total_Soft_PTable_Cols_Id + "_30").val(TS_ChangedValue);
}

// ----------------------------------------------------------Changing Settings of type 3 ------------------------------------------------------------------------------------

function TS_Ch_3_Col_Width(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedWidthValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    

    jQuery(TS_Same_Id).parent(".TS_PTable_Container_Col_" + Total_Soft_PTable_Cols_Id).css("width", TS_ChangedWidthValue + "%");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_01").val(TS_ChangedWidthValue);
}

function TS_Ch_3_Col_Scale(This_val, Total_Soft_PTable_Cols_Id) {
       var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    
 
    if (jQuery('#TS_PTable_ST3_' + Total_Soft_PTable_Cols_Id + '_02').is(":checked")) {
        jQuery(TS_Same_Id).parent(".TS_PTable_Container_Col_" + Total_Soft_PTable_Cols_Id).addClass('toggle_transform');
        jQuery(TS_Same_Id).parent(".TS_PTable_Container_Col_" + Total_Soft_PTable_Cols_Id).removeClass('noScale');
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_02").val("on");
    } else {
        jQuery(TS_Same_Id).parent(".TS_PTable_Container_Col_" + Total_Soft_PTable_Cols_Id).removeClass('toggle_transform');
        jQuery(TS_Same_Id).parent(".TS_PTable_Container_Col_" + Total_Soft_PTable_Cols_Id).addClass('noScale');
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_02").val("off");
    }
}

function TS_Ch_Inp_3_Back_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type3") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Div1_" + Total_Soft_PTable_Cols_Id).css("background", TS_ChangedValue);
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Div2_" + Total_Soft_PTable_Cols_Id).css("background", TS_ChangedValue);
        jQuery(TS_Same_Id).parent().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_03").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_3_Border_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type3") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find(".TS_PTable__" + Total_Soft_PTable_Cols_Id).css("border-color", TS_ChangedValue);
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Title_Icon_" + Total_Soft_PTable_Cols_Id).css("border-color", TS_ChangedValue);
        jQuery(TS_Same_Id).parent().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_04").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_3_Border_Width(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable__" + Total_Soft_PTable_Cols_Id).css("border-width", TS_ChangedValue + 'px');
    jQuery(TS_Same_Id).parent().find(".TS_PTable_Title_Icon_" + Total_Soft_PTable_Cols_Id).css("border-width", TS_ChangedValue + 'px');
    jQuery(TS_Same_Id).parent().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_05").val(TS_ChangedValue);
}

function TS_Ch_Inp_3_Shadow_Type(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    const root = document.querySelector(":root");
    root.style.setProperty("--pseudo-backgroundcolor"+ Total_Soft_PTable_Cols_Id,  jQuery(TS_Same_Id).parents().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_07").val());
    
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_06").val(TS_ChangedValue);
    switch (TS_ChangedValue) {
        case "none":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).css({
                " box-shadow": "none",
                "-moz-box-shadow": "none",
                "-webkit-box-shadow": "none"
            })
            break;
        case "shadow01":
       
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_3_01_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow02":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_3_02_" + Total_Soft_PTable_Cols_Id);

            break;
        case "shadow03":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_3_03_" + Total_Soft_PTable_Cols_Id);

            break;
        case "shadow04":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_3_04_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow05":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_3_05_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow06":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_3_06_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow07":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_3_07_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow08":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_3_08_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow09":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_3_09_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow10":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_3_10_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow11":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_3_11_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow12":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_3_12_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow13":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_3_13_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow14":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_3_14_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow15":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_3_15_" + Total_Soft_PTable_Cols_Id);
            break;
        default:
// code block
    }
}

function TS_Ch_3_Inp_Shadow_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type3") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        const root = document.querySelector(":root");
        root.style.setProperty("--pseudo-backgroundcolor"+ Total_Soft_PTable_Cols_Id,  TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_07").val(TS_ChangedValue);
    }
}

function TS_Ch_3_Inp_Font_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_Title_" + Total_Soft_PTable_Cols_Id).css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_08").val(TS_ChangedValue);
}

function TS_Ch_3_Inp_Font_Family(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_Title_" + Total_Soft_PTable_Cols_Id).css("font-family", TS_ChangedValue);
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_09").val(TS_ChangedValue);
}

function TS_Ch_3_Inp_Font_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type3") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Title_" + Total_Soft_PTable_Cols_Id).css("color", TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_10").val(TS_ChangedValue);
    }
}

function TS_Ch_3_Title_Back(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type3") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Title_" + Total_Soft_PTable_Cols_Id).css("background-color", TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_11").val(TS_ChangedValue);
    }
}

function TS_Ch_3_Inp_Icon_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type3") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find('.TS_PTable_Title_Icon_' + Total_Soft_PTable_Cols_Id).children("i").css("color", TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_12").val(TS_ChangedValue);
    }
}

function TS_Ch_3_Inp_Icon_Back(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type3") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find('.TS_PTable_Title_Icon_' + Total_Soft_PTable_Cols_Id).children("i").css("background-color", TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_13").val(TS_ChangedValue);
    }
}

function TS_Ch_3_Inp_Icon_Size(This_val, Total_Soft_PTable_Cols_Id, type) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find('.TS_PTable_Title_Icon_' + Total_Soft_PTable_Cols_Id).children("i").css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_14").val(TS_ChangedValue);
}

function TS_Ch_Inp_3_Icon_Hover_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type3") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");

        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_15").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_3_Icon_Hover_Background(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type3") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");

        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_16").val(TS_ChangedValue);
    }
}

function TS_Ch_3_Inp_PV_Font_Family(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_PValue_" + Total_Soft_PTable_Cols_Id).css("font-family", TS_ChangedValue);
    jQuery(TS_Same_Id).parent().find(".TS_PTable_PPlan_" + Total_Soft_PTable_Cols_Id).css("font-family", TS_ChangedValue);
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_17").val(TS_ChangedValue);
}

function TS_Ch_3_Inp_PV_Font_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type3") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find(".TS_PTable_PValue_" + Total_Soft_PTable_Cols_Id).css("color", TS_ChangedValue);
        jQuery(TS_Same_Id).parent().find(".TS_PTable_PPlan_" + Total_Soft_PTable_Cols_Id).css("color", TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_18").val(TS_ChangedValue);
    }
}

function TS_Ch_3_Inp_PCur_Font_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_PCur_" + Total_Soft_PTable_Cols_Id).css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_19").val(TS_ChangedValue);
}

function TS_Ch_3_Inp_PPrice_Font_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_PValue_" + Total_Soft_PTable_Cols_Id).css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_20").val(TS_ChangedValue);
}

function TS_Ch_3_Inp_PPan_Font_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_PPlan_" + Total_Soft_PTable_Cols_Id).css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_21").val(TS_ChangedValue);
}

function TS_Ch_Inp_3_Feut_Back(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type3") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Features_" + Total_Soft_PTable_Cols_Id).children("li").css("background-color", TS_ChangedValue);
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Features_" + Total_Soft_PTable_Cols_Id).css("background-color", TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_22").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_3_Feut_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type3") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id")
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Features_" + Total_Soft_PTable_Cols_Id).children("li").css("color", TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_23").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_3_Feut_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_Features_" + Total_Soft_PTable_Cols_Id).children("li").css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_24").val(TS_ChangedValue);
}

function TS_Ch_Inp_3_Feut_Family(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_Features_" + Total_Soft_PTable_Cols_Id).children("li").css("font-family", TS_ChangedValue);
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_25").val(TS_ChangedValue);
}

function TS_Ch_Inp_Feut_3_Icon_Col(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type3") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id")
        jQuery(TS_Same_Id).parent().find(".TS_PTable_FIcon_" + Total_Soft_PTable_Cols_Id).not(".TS_PTable_FCheck").attr("style", "color:" + TS_ChangedValue + " !important");
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_26").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_Feut_3_Icon_Col_Sel(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type3") {
        
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("TS_PTable_Container_Col_" + Total_Soft_PTable_Cols_Id);
        jQuery(TS_Same_Id).find(".TS_PTable_FCheck").attr("style", "color:" + TS_ChangedValue + " !important");
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_27").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_Feut_3_Icon_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_FIcon_" + Total_Soft_PTable_Cols_Id).css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_28").val(TS_ChangedValue);
}

function TS_Ch_Inp_3_Feut_Icon_Position(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    if (TS_ChangedValue == "after") {
        jQuery(TS_Same_Id).parents().find(".feut_container_" + Total_Soft_PTable_Cols_Id + "").attr("style", "flex-direction:row;");
    } else if (TS_ChangedValue == "before") {
        jQuery(TS_Same_Id).parents().find(".feut_container_" + Total_Soft_PTable_Cols_Id + "").attr("style", "flex-direction:row-reverse;");
    }
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_29").val(TS_ChangedValue);
}

function TS_Ch_Inp_3_But_Font_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_BText_" + Total_Soft_PTable_Cols_Id).css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_30").val(TS_ChangedValue);
}

function TS_Ch_Inp_3_But_Font_Family(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_BText_" + Total_Soft_PTable_Cols_Id).css("font-family", TS_ChangedValue);
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_31").val(TS_ChangedValue);
}

function TS_Ch_Inp_3_But_Icon_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_Button_" + Total_Soft_PTable_Cols_Id).find("i").css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_32").val(TS_ChangedValue);
}

function TS_Ch_Inp_3_But_Icon_Position(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    if (TS_ChangedValue == "after") {
        jQuery(TS_Same_Id).parents().find(".Button_cont_" + Total_Soft_PTable_Cols_Id + "").attr("style", "flex-direction:row;");
    } else if (TS_ChangedValue == "before") {
        jQuery(TS_Same_Id).parents().find(".Button_cont_" + Total_Soft_PTable_Cols_Id + "").attr("style", "flex-direction:row-reverse;");
    }
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_33").val(TS_ChangedValue);
}

function TS_Ch_Inp_3_BT_Back(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type3") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find('.TS_PTable_Button_' + Total_Soft_PTable_Cols_Id).css("background-color", TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_34").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_3_BT_Text(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type3") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find('.TS_PTable_Button_' + Total_Soft_PTable_Cols_Id).css("color", TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_35").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_3_BT_Back_Hover(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type3") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_36").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_3_BT_Color_Hover(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type3") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST3_" + Total_Soft_PTable_Cols_Id + "_37").val(TS_ChangedValue);
    }
}


// ----------------------------------------------------------Changing Settings of type 4 ------------------------------------------------------------------------------------

function TS_Ch_4_Col_Width(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedWidthValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent(".TS_PTable_Container_Col_" + Total_Soft_PTable_Cols_Id).css("width", TS_ChangedWidthValue + "%");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_ST4_" + Total_Soft_PTable_Cols_Id + "_01").val(TS_ChangedWidthValue);
}

function TS_Ch_4_Col_Scale(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    
    if (jQuery('#TS_PTable_ST4_' + Total_Soft_PTable_Cols_Id + '_02').is(":checked")) {
        jQuery(TS_Same_Id).parent(".TS_PTable_Container_Col_" + Total_Soft_PTable_Cols_Id).addClass('toggle_transform');
        jQuery(TS_Same_Id).parent(".TS_PTable_Container_Col_" + Total_Soft_PTable_Cols_Id).removeClass('noScale');
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST4_" + Total_Soft_PTable_Cols_Id + "_02").val("on");
    } else {
        jQuery(TS_Same_Id).parent(".TS_PTable_Container_Col_" + Total_Soft_PTable_Cols_Id).removeClass('toggle_transform');
        jQuery(TS_Same_Id).parent(".TS_PTable_Container_Col_" + Total_Soft_PTable_Cols_Id).addClass('noScale');
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST4_" + Total_Soft_PTable_Cols_Id + "_02").val("off");
    }
}

function TS_Ch_Inp_4_Back_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type4") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Div1_" + Total_Soft_PTable_Cols_Id).css("background", TS_ChangedValue);
        jQuery(TS_Same_Id).parent().find(".TS_PTable_ST4_" + Total_Soft_PTable_Cols_Id + "_03").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_4_Back_Hover_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type4") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find(".TS_PTable_ST4_" + Total_Soft_PTable_Cols_Id + "_04").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_4_Shadow_Type(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    const root = document.querySelector(":root");
    root.style.setProperty("--pseudo-backgroundcolor"+ Total_Soft_PTable_Cols_Id,  jQuery(TS_Same_Id).parents().find(".TS_PTable_ST4_" + Total_Soft_PTable_Cols_Id + "_06").val());
    
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST4_" + Total_Soft_PTable_Cols_Id + "_05").val(TS_ChangedValue);
    switch (TS_ChangedValue) {
        case "none":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).css({
                " box-shadow": "none",
                "-moz-box-shadow": "none",
                "-webkit-box-shadow": "none"
            })
            break;
        case "shadow01":
         jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).css({
                " box-shadow": ' 0 10px 6px -6px ' + TS_ChangedValue,
                "-moz-box-shadow": ' 0 10px 6px -6px ' + TS_ChangedValue,
                "-webkit-box-shadow": ' 0 10px 6px -6px ' + TS_ChangedValue
            })
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_4_01_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow02":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_4_02_" + Total_Soft_PTable_Cols_Id);

            break;
        case "shadow03":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_4_03_" + Total_Soft_PTable_Cols_Id);

            break;
        case "shadow04":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_4_04_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow05":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_4_05_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow06":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_4_06_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow07":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_4_07_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow08":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_4_08_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow09":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_4_09_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow10":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_4_10_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow11":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_4_11_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow12":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_4_12_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow13":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_4_13_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow14":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_4_14_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow15":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_4_15_" + Total_Soft_PTable_Cols_Id);
            break;
        default:
// code block
    }
}

function TS_Ch_4_Inp_Shadow_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type4") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        const root = document.querySelector(":root");
        root.style.setProperty("--pseudo-backgroundcolor"+ Total_Soft_PTable_Cols_Id,  TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST4_" + Total_Soft_PTable_Cols_Id + "_06").val(TS_ChangedValue);
    }
}

function TS_Ch_4_Inp_Font_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_Title_" + Total_Soft_PTable_Cols_Id).css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST4_" + Total_Soft_PTable_Cols_Id + "_07").val(TS_ChangedValue);
}

function TS_Ch_4_Inp_Font_Family(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_Title_" + Total_Soft_PTable_Cols_Id).css("font-family", TS_ChangedValue);
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST4_" + Total_Soft_PTable_Cols_Id + "_08").val(TS_ChangedValue);
}

function TS_Ch_4_Inp_Font_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type4") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Title_" + Total_Soft_PTable_Cols_Id).css("color", TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST4_" + Total_Soft_PTable_Cols_Id + "_09").val(TS_ChangedValue);
    }
}

function TS_Ch_4_Inp_Font_Hover_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type4") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST4_" + Total_Soft_PTable_Cols_Id + "_10").val(TS_ChangedValue);
    }
}

function TS_Ch_4_Inp_PV_Font_Family(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_Amount_" + Total_Soft_PTable_Cols_Id).css("font-family", TS_ChangedValue);
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST4_" + Total_Soft_PTable_Cols_Id + "_11").val(TS_ChangedValue);
}

function TS_Ch_4_Inp_PV_Font_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type4") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find(".TS_PTable_PCur_" + Total_Soft_PTable_Cols_Id).css("color", TS_ChangedValue);
        jQuery(TS_Same_Id).parent().find(".TS_PTable_PVal_" + Total_Soft_PTable_Cols_Id).css("color", TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST4_" + Total_Soft_PTable_Cols_Id + "_12").val(TS_ChangedValue);
    }
}

function TS_Ch_4_Inp_PV_Font_Hover_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type4") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST4_" + Total_Soft_PTable_Cols_Id + "_13").val(TS_ChangedValue);
    }
}

function TS_Ch_4_Inp_PCur_Font_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_PCur_" + Total_Soft_PTable_Cols_Id).css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST4_" + Total_Soft_PTable_Cols_Id + "_14").val(TS_ChangedValue);
}

function TS_Ch_4_Inp_PVal_Font_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_PVal_" + Total_Soft_PTable_Cols_Id).css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST4_" + Total_Soft_PTable_Cols_Id + "_15").val(TS_ChangedValue);
}

function TS_Ch_4_Inp_PPlan_Font_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_PPlan_" + Total_Soft_PTable_Cols_Id).css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST4_" + Total_Soft_PTable_Cols_Id + "_16").val(TS_ChangedValue);
}

function TS_Ch_Inp_4_Feut_Back(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type4") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Features_" + Total_Soft_PTable_Cols_Id).children("li").css("background-color", TS_ChangedValue);
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Features_" + Total_Soft_PTable_Cols_Id).css("background-color", TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST4_" + Total_Soft_PTable_Cols_Id + "_17").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_4_Feut_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type4") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id")
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Features_" + Total_Soft_PTable_Cols_Id).children("li").css("color", TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST4_" + Total_Soft_PTable_Cols_Id + "_18").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_4_Feut_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_Features_" + Total_Soft_PTable_Cols_Id).children("li").css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST4_" + Total_Soft_PTable_Cols_Id + "_19").val(TS_ChangedValue);
}

function TS_Ch_Inp_4_Feut_Family(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_Features_" + Total_Soft_PTable_Cols_Id).children("li").css("font-family", TS_ChangedValue);
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST4_" + Total_Soft_PTable_Cols_Id + "_20").val(TS_ChangedValue);
}

function TS_Ch_Inp_Feut_4_Icon_Col(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type4") {
        var TS_ChangedValue = jQuery('#TS_PTable_ST4_' + Total_Soft_PTable_Cols_Id + '_21').val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id")
        jQuery(TS_Same_Id).parent().find(".TS_PTable_FIcon_" + Total_Soft_PTable_Cols_Id).not(".TS_PTable_FCheck").attr("style", "color:" + TS_ChangedValue + " !important");
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST4_" + Total_Soft_PTable_Cols_Id + "_21").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_Feut_4_Icon_Col_Sel(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type4") {
        var TS_ChangedValue = jQuery('#TS_PTable_ST4_' + Total_Soft_PTable_Cols_Id + '_22').val();
        var TS_Same_Id =  document.getElementsByClassName("TS_PTable_Container_Col_" + Total_Soft_PTable_Cols_Id);
        jQuery(TS_Same_Id).find(".TS_PTable_FCheck").attr("style", "color:" + TS_ChangedValue + " !important");
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST4_" + Total_Soft_PTable_Cols_Id + "_22").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_Feut_4_Icon_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_FIcon_" + Total_Soft_PTable_Cols_Id).css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST4_" + Total_Soft_PTable_Cols_Id + "_23").val(TS_ChangedValue);
}

function TS_Ch_Inp_4_Feut_Icon_Position(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    if (TS_ChangedValue == "after") {
        jQuery(TS_Same_Id).parents().find(".feut_container_" + Total_Soft_PTable_Cols_Id + "").attr("style", "flex-direction:row;");
    } else if (TS_ChangedValue == "before") {
        jQuery(TS_Same_Id).parents().find(".feut_container_" + Total_Soft_PTable_Cols_Id + "").attr("style", "flex-direction:row-reverse;");
    }
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST4_" + Total_Soft_PTable_Cols_Id + "_24").val(TS_ChangedValue);
}

function TS_Ch_Inp_4_But_Font_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_BText_" + Total_Soft_PTable_Cols_Id).css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST4_" + Total_Soft_PTable_Cols_Id + "_25").val(TS_ChangedValue);
}

function TS_Ch_Inp_4_But_Font_Family(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_BText_" + Total_Soft_PTable_Cols_Id).css("font-family", TS_ChangedValue);
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST4_" + Total_Soft_PTable_Cols_Id + "_26").val(TS_ChangedValue);
}

function TS_Ch_Inp_4_But_Icon_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_Button_" + Total_Soft_PTable_Cols_Id).find("i").css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST4_" + Total_Soft_PTable_Cols_Id + "_27").val(TS_ChangedValue);
}

function TS_Ch_Inp_4_But_Icon_Position(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    if (TS_ChangedValue == "after") {
        jQuery(TS_Same_Id).parents().find(".Button_cont_" + Total_Soft_PTable_Cols_Id + "").attr("style", "flex-direction:row;");
    } else if (TS_ChangedValue == "before") {
        jQuery(TS_Same_Id).parents().find(".Button_cont_" + Total_Soft_PTable_Cols_Id + "").attr("style", "flex-direction:row-reverse;");
    }
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST4_" + Total_Soft_PTable_Cols_Id + "_28").val(TS_ChangedValue);
}


function TS_Ch_Inp_4_BT_Back(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type4") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find('.TS_PTable_Button_' + Total_Soft_PTable_Cols_Id).css("background-color", TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST4_" + Total_Soft_PTable_Cols_Id + "_29").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_4_BT_Text(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type4") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find('.TS_PTable_Button_' + Total_Soft_PTable_Cols_Id).css("color", TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST4_" + Total_Soft_PTable_Cols_Id + "_30").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_4_BT_Back_Hover(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type4") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST4_" + Total_Soft_PTable_Cols_Id + "_31").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_4_BT_Color_Hover(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type4") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST4_" + Total_Soft_PTable_Cols_Id + "_32").val(TS_ChangedValue);
    }
}

// ----------------------------------------------------------Changing Settings of type 5 ------------------------------------------------------------------------------------

function TS_Ch_5_Col_Width(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedWidthValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent(".TS_PTable_Container_Col_" + Total_Soft_PTable_Cols_Id).css("width", TS_ChangedWidthValue + "%");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_ST5_" + Total_Soft_PTable_Cols_Id + "_01").val(TS_ChangedWidthValue);
}

function TS_Ch_5_Col_Scale(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
     
    if (jQuery('#TS_PTable_ST5_' + Total_Soft_PTable_Cols_Id + '_02').is(":checked")) {
        jQuery(TS_Same_Id).parent(".TS_PTable_Container_Col_" + Total_Soft_PTable_Cols_Id).addClass('toggle_transform');
        jQuery(TS_Same_Id).parent(".TS_PTable_Container_Col_" + Total_Soft_PTable_Cols_Id).removeClass('noScale');
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST5_" + Total_Soft_PTable_Cols_Id + "_02").val("on");
    } else {
        jQuery(TS_Same_Id).parent(".TS_PTable_Container_Col_" + Total_Soft_PTable_Cols_Id).removeClass('toggle_transform');
        jQuery(TS_Same_Id).parent(".TS_PTable_Container_Col_" + Total_Soft_PTable_Cols_Id).addClass('noScale');
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST5_" + Total_Soft_PTable_Cols_Id + "_02").val("off");
    }
}

function TS_Ch_Inp_5_Back_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type5") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find(".TS_PTable__" + Total_Soft_PTable_Cols_Id).css("background", TS_ChangedValue);
        jQuery(TS_Same_Id).parent().find(".TS_PTable_ST5_" + Total_Soft_PTable_Cols_Id + "_03").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_5_Shadow_Type(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    const root = document.querySelector(":root");
    root.style.setProperty("--pseudo-backgroundcolor"+ Total_Soft_PTable_Cols_Id,  jQuery(TS_Same_Id).parents().find(".TS_PTable_ST5_" + Total_Soft_PTable_Cols_Id + "_05").val());
    
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST5_" + Total_Soft_PTable_Cols_Id + "_04").val(TS_ChangedValue);
    switch (TS_ChangedValue) {
        case "none":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).css({
                " box-shadow": "none",
                "-moz-box-shadow": "none",
                "-webkit-box-shadow": "none"
            })
            break;
        case "shadow01":
         jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).css({
                " box-shadow": ' 0 10px 6px -6px ' + TS_ChangedValue,
                "-moz-box-shadow": ' 0 10px 6px -6px ' + TS_ChangedValue,
                "-webkit-box-shadow": ' 0 10px 6px -6px ' + TS_ChangedValue
            })
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_5_01_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow02":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_5_02_" + Total_Soft_PTable_Cols_Id);

            break;
        case "shadow03":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_5_03_" + Total_Soft_PTable_Cols_Id);

            break;
        case "shadow04":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_5_04_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow05":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_5_05_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow06":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_5_06_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow07":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_5_07_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow08":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_5_08_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow09":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_5_09_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow10":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_5_10_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow11":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_5_11_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow12":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_5_12_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow13":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_5_13_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow14":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_5_14_" + Total_Soft_PTable_Cols_Id);
            break;
        case "shadow15":
            jQuery(".TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id).removeClass().addClass("TS_PTable_Shadow_" + Total_Soft_PTable_Cols_Id + " Box_Shadow_5_15_" + Total_Soft_PTable_Cols_Id);
            break;
        default:
    }
}

function TS_Ch_5_Inp_Shadow_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type5") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
          const root = document.querySelector(":root");
        root.style.setProperty("--pseudo-backgroundcolor"+ Total_Soft_PTable_Cols_Id,  TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST5_" + Total_Soft_PTable_Cols_Id + "_05").val(TS_ChangedValue);
    }
}

function TS_Ch_5_Inp_Font_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_Title_" + Total_Soft_PTable_Cols_Id).css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST5_" + Total_Soft_PTable_Cols_Id + "_06").val(TS_ChangedValue);
}

function TS_Ch_5_Inp_Font_Family(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_Title_" + Total_Soft_PTable_Cols_Id).css("font-family", TS_ChangedValue);
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST5_" + Total_Soft_PTable_Cols_Id + "_07").val(TS_ChangedValue);
}

function TS_Ch_5_Inp_Font_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type5") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Title_" + Total_Soft_PTable_Cols_Id).css("color", TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST5_" + Total_Soft_PTable_Cols_Id + "_08").val(TS_ChangedValue);
    }
}

function TS_Ch_5_Inp_Icon_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_Div1_" + Total_Soft_PTable_Cols_Id).find("i").css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST5_" + Total_Soft_PTable_Cols_Id + "_09").val(TS_ChangedValue);
}

function TS_Ch_5_Inp_Icon_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type5") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Div1_" + Total_Soft_PTable_Cols_Id).find("i").css("color", TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST5_" + Total_Soft_PTable_Cols_Id + "_10").val(TS_ChangedValue);
    }
}

function TS_Ch_5_Inp_Icon_Hover_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type5") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST5_" + Total_Soft_PTable_Cols_Id + "_11").val(TS_ChangedValue);
    }
}

function TS_Ch_5_Inp_PV_Font_Family(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_Amount_" + Total_Soft_PTable_Cols_Id).css("font-family", TS_ChangedValue);
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST5_" + Total_Soft_PTable_Cols_Id + "_12").val(TS_ChangedValue);
}

function TS_Ch_5_Inp_PV_Font_Color(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type5") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Amount_" + Total_Soft_PTable_Cols_Id).css("color", TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST5_" + Total_Soft_PTable_Cols_Id + "_13").val(TS_ChangedValue);
    }
}

function TS_Ch_5_Inp_PV_Font_Hover_Color(This_val, Total_Soft_PTable_Cols_Id, type) {

    if (type == "type5") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST5_" + Total_Soft_PTable_Cols_Id + "_14").val(TS_ChangedValue);
    }
}

function TS_Ch_5_Inp_PCur_Font_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_PCur_" + Total_Soft_PTable_Cols_Id).css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST5_" + Total_Soft_PTable_Cols_Id + "_15").val(TS_ChangedValue);
}

function TS_Ch_5_Inp_PVal_Font_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_PVal_" + Total_Soft_PTable_Cols_Id).css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST5_" + Total_Soft_PTable_Cols_Id + "_16").val(TS_ChangedValue);
}

function TS_Ch_5_Inp_PPlan_Font_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_PPlan_" + Total_Soft_PTable_Cols_Id).css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST5_" + Total_Soft_PTable_Cols_Id + "_17").val(TS_ChangedValue);
}

function TS_Ch_Inp_5_PV_Back_Hover(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type5") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST5_" + Total_Soft_PTable_Cols_Id + "_18").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_5_PV_Color_Hover(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type5") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST5_" + Total_Soft_PTable_Cols_Id + "_19").val(TS_ChangedValue);
    }
}

function TS_Ch_5_Feut_Text(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type5") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Features_" + Total_Soft_PTable_Cols_Id).children("li").css("color", TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST5_" + Total_Soft_PTable_Cols_Id + "_20").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_5_Feut_Text_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id")
    jQuery(TS_Same_Id).parent().find(".TS_PTable_Features_" + Total_Soft_PTable_Cols_Id).children("li").css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST5_" + Total_Soft_PTable_Cols_Id + "_21").val(TS_ChangedValue);
}

function TS_Ch_Inp_5_Feut_Text_Font(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id")
    jQuery(TS_Same_Id).parent().find(".TS_PTable_Features_" + Total_Soft_PTable_Cols_Id).children("li").css("font-family", TS_ChangedValue);
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST5_" + Total_Soft_PTable_Cols_Id + "_22").val(TS_ChangedValue);
}

function TS_Ch_Inp_5_Feut_Icon_Col(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type5") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Features_" + Total_Soft_PTable_Cols_Id).find(".TS_PTable_FIcon_" + Total_Soft_PTable_Cols_Id).not(".TS_PTable_FCheck").attr("style", "color:" + TS_ChangedValue + " !important");
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST5_" + Total_Soft_PTable_Cols_Id + "_23").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_5_Feut_Icon_Sel_Col(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type5") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Features_" + Total_Soft_PTable_Cols_Id).find(".TS_PTable_FCheck").css("color", TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST5_" + Total_Soft_PTable_Cols_Id + "_24").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_5_FT_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_Features_" + Total_Soft_PTable_Cols_Id).find(".TS_PTable_FIcon_" + Total_Soft_PTable_Cols_Id).css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST5_" + Total_Soft_PTable_Cols_Id + "_25").val(TS_ChangedValue);
}

function TS_Ch_Inp_5_Feut_Icon_Position(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    if (TS_ChangedValue == "after") {
        jQuery(TS_Same_Id).parents().find(".feut_container_" + Total_Soft_PTable_Cols_Id + "").attr("style", "flex-direction:row;");
    } else if (TS_ChangedValue == "before") {
        jQuery(TS_Same_Id).parents().find(".feut_container_" + Total_Soft_PTable_Cols_Id + "").attr("style", "flex-direction:row-reverse;");
    }
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST5_" + Total_Soft_PTable_Cols_Id + "_26").val(TS_ChangedValue);
}

function TS_Ch_Inp_5_But_Font_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_BText_" + Total_Soft_PTable_Cols_Id).css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST5_" + Total_Soft_PTable_Cols_Id + "_27").val(TS_ChangedValue);
}

function TS_Ch_Inp_5_But_Font_Family(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_BText_" + Total_Soft_PTable_Cols_Id).css("font-family", TS_ChangedValue);
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST5_" + Total_Soft_PTable_Cols_Id + "_28").val(TS_ChangedValue);
}

function TS_Ch_Inp_5_But_Icon_Size(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parent().find(".TS_PTable_Button_" + Total_Soft_PTable_Cols_Id).children("i").css("font-size", TS_ChangedValue + "px");
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST5_" + Total_Soft_PTable_Cols_Id + "_29").val(TS_ChangedValue);
}

function TS_Ch_Inp_5_But_Icon_Position(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    if (TS_ChangedValue == "after") {
        jQuery(TS_Same_Id).parents().find(".Button_cont_" + Total_Soft_PTable_Cols_Id + "").attr("style", "flex-direction:row;");
    } else if (TS_ChangedValue == "before") {
        jQuery(TS_Same_Id).parents().find(".Button_cont_" + Total_Soft_PTable_Cols_Id + "").attr("style", "flex-direction:row-reverse;");
    }
    jQuery(TS_Same_Id).parents().find(".TS_PTable_ST5_" + Total_Soft_PTable_Cols_Id + "_30").val(TS_ChangedValue);
}

function TS_Ch_Inp_5_BT_Back(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type5") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Button_" + Total_Soft_PTable_Cols_Id).css("background-color", TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST5_" + Total_Soft_PTable_Cols_Id + "_31").val(TS_ChangedValue);
    }
}

function TS_Ch_Inp_5_BT_Text(This_val, Total_Soft_PTable_Cols_Id, type) {
    if (type == "type5") {
        var TS_ChangedValue = jQuery(This_val).val();
        var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
        jQuery(TS_Same_Id).parent().find(".TS_PTable_Button_" + Total_Soft_PTable_Cols_Id).css("color", TS_ChangedValue);
        jQuery(TS_Same_Id).parents().find(".TS_PTable_ST5_" + Total_Soft_PTable_Cols_Id + "_32").val(TS_ChangedValue);
    }
}

// ----------------------------------------------------------Changing columns values with this functions------------------------------------------------------------------------------------
function TS_Ch_Gen_Pos(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedWidthValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    if (TS_ChangedWidthValue == 'left') {
        jQuery(TS_Same_Id).parents(".TS_Desctop_View").css("justify-content", "flex-start");
    } else if (TS_ChangedWidthValue == 'right') {
        jQuery(TS_Same_Id).parents(".TS_Desctop_View").css("justify-content", "flex-end");
    } else if (TS_ChangedWidthValue == 'center') {
        jQuery(TS_Same_Id).parents(".TS_Desctop_View").css("justify-content", "center");
    }
}

function TS_Ch_Cont_Width(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedWidthValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("Total_Soft_PTable_Cols_Id");
    jQuery(TS_Same_Id).parents(".TS_PTable_Container").css("width", TS_ChangedWidthValue + "%");


}

function TS_Ch_Cont_Padding(This_val, Total_Soft_PTable_Cols_Id) {
    var TS_ChangedWidthValue = jQuery(This_val).val();
    var TS_Same_Id = document.getElementsByClassName("TS_PTable_Container");
    jQuery(TS_Same_Id).css({"gap": TS_ChangedWidthValue + "px"});
}

TS_Change_H3_Val = (This_Element) => {
    jQuery(This_Element).next(".TS_PTable_TText_Hidden").val(jQuery(This_Element).html());
}

TS_Change_Cur_Val = (This_Element) => {
    jQuery(This_Element).next(".TS_PTable_PCur_Hidden").val(jQuery(This_Element).html());
}

TS_Change_Val_Val = (This_Element) => {
    jQuery(This_Element).next(".TS_PTable_PVal_Hidden").val(jQuery(This_Element).html());
}

TS_Change_Plan_Val = (This_Element) => {
    jQuery(This_Element).next(".TS_PTable_PPlan_Hidden").val(jQuery(This_Element).html());
}

TS_Change_Feut_Val = (This_Element) => {
    
    //if (jQuery(This_Element).html()=='') {jQuery(This_Element).html('text')}
    jQuery(This_Element).next(".TS_PTable_Feut_Hidden").val(jQuery(This_Element).html());
}
TS_Change_Feut_Val_Hover = (This_Element) => {
  //  jQuery(This_Element).addClass("Feut_Hover")
}

TS_Change_Feut_Val_Over = (This_Element) => {
    jQuery(This_Element).removeClass("Feut_Hover")
}

TS_Change_Button_Text_Val = (This_Element) => {
    jQuery(This_Element).next(".TS_PTable_BText_Hidden").val(jQuery(This_Element).html());
}

TS_Get_Icon_Title = (This_Element, This_ID) => {
    jQuery(".hide_Select").remove();
    var Total_Soft_PTable_Select_Icon = jQuery('#Total_Soft_PTable_Select_Icon').html();
    jQuery(This_Element).after('<select onchange= "TS_ChangeTitle_Icon_Select(this,' + This_ID + ')" class="Total_Soft_PTable_Select TS_PTable_TIcon hide_Select TS_PTable_TIcon_' + This_ID + '" onchange="ChangeValueForHiddenTIcon(this)"  name="TS_PTable_TIcon_' + This_ID + '" style="font-family: FontAwesome, Arial; display:block;left:15%; position: absolute">' + Total_Soft_PTable_Select_Icon + '</select>');
    jQuery(".TS_PTable_TIcon_" + This_ID).val(jQuery('#TS_PTable_TIcon_' +This_ID).val());
    event.stopPropagation();
    jQuery(document).click(function (event) {
        var container = jQuery(".hide_Select");
        var container1 = jQuery(This_Element);
        if (!container1.is(event.target) && container1.has(event.target).length === 0 && !container.is(event.target) && container.has(event.target).length === 0) {
            container.remove();
        }
    });
}

function TS_ChangeTitle_Icon_Select(This_Element, This_ID) {
    var splited_class = jQuery(This_Element).prev("i").attr("class").split("totalsoft-");
    splited_class.splice(splited_class.length - 1, 1, jQuery(This_Element).val()!='none'?jQuery(This_Element).val():'plus-square-o');
    var res = splited_class.join("totalsoft-");
    jQuery(This_Element).prev("i").attr("class", res);
    jQuery("#TS_PTable_TIcon_" + This_ID).val(jQuery(This_Element).val());
    jQuery(This_Element).hide();
}

function TS_Get_Icon_Feuture(This_Element, This_ID, This_Index) {
    jQuery(".hide_Select").remove();
    var Total_Soft_PTable_Select_Icon = jQuery('#Total_Soft_PTable_Select_Icon').html();
    jQuery(This_Element).after('<select onchange= "TS_ChangeFeut_Icon_Select(this)" class="Total_Soft_PTable_Select hide_Select  TS_PTable_FIcon_' + This_ID + '_' + This_Index + '" style="font-family: FontAwesome, Arial;">' + Total_Soft_PTable_Select_Icon + '</select>');
   jQuery('.TS_PTable_FIcon_' + This_ID + '_' + This_Index).parent().find('select').val(jQuery('.TS_PTable_FIcon_' + This_ID + '_' + This_Index).parent().find('input').val());
    event.stopPropagation();
    jQuery(document).click(function (event) {
        var container = jQuery(".hide_Select");
        var container1 = jQuery(This_Element);
        if (!container1.is(event.target) && container1.has(event.target).length === 0 && !container.is(event.target) && container.has(event.target).length === 0) {
            container.remove();
        }
    });
}

function TS_ChangeFeut_Icon_Select(This_Element) {
    var splited_class = jQuery(This_Element).prev("i").attr("class").split("totalsoft-");
    splited_class.splice(splited_class.length - 1, 1, jQuery(This_Element).val()!='none'?jQuery(This_Element).val():'plus-square-o');
    var res = splited_class.join("totalsoft-");
    jQuery(This_Element).prev("i").attr("class", res);
    jQuery(This_Element).next("input").val(jQuery(This_Element).val());
    jQuery(This_Element).hide();
}


TS_Get_Icon_Button = (This_Element, This_ID) => {
    jQuery(".hide_Select").remove();
     This_Element = jQuery(This_Element).find('i');
    var Total_Soft_PTable_Select_Icon = jQuery('#Total_Soft_PTable_Select_Icon').html();
    jQuery(".TS_PTable_Button_" + This_ID).find(".TS_PTable_Button_Link").slideDown();
     jQuery(".TS_PTable_BIcon_" + This_ID).slideDown();
    event.stopPropagation();
    jQuery(document).click(function (event) {
        var container1 = jQuery(".TS_PTable_Button_" + This_ID).find(".TS_PTable_Button_Link_" + This_ID);
        var container2 = jQuery(".TS_PTable_Button_" + This_ID).find(".TS_PTable_BIcon_" + This_ID);
        var container = jQuery(This_Element).next(".hide_Select");
        if (!container1.is(event.target) && container1.has(event.target).length === 0 && !container.is(event.target) && container.has(event.target).length === 0) {
            jQuery(This_Element).next(".hide_Select").remove();
            container1.slideUp();
            container2.slideUp();
        }
    });
}

TS_Get_Icon_Link = (This_Element, This_ID) => {
    jQuery(This_Element).find(".TS_PTable_Button_Link").slideDown()
    event.stopPropagation();
    jQuery(document).click(function (event)
    {
        var container = jQuery(".hide_Select");
        var container1 = jQuery(This_Element);
        if (!container1.is(event.target) && container1.has(event.target).length === 0 && !container.is(event.target) && container.has(event.target).length === 0)
        {
            container.slideUp();
        }
    });
}


function TS_ChangeBut_Icon_Select(This_Element, This_ID) {
    var splited_class = jQuery(This_Element).prev("i").attr("class").split("totalsoft-");
    splited_class.splice(splited_class.length - 1, 1, jQuery(This_Element).val()!='none'?jQuery(This_Element).val():'plus-square-o ');
    var res = splited_class.join("totalsoft-");
    jQuery(This_Element).prev("i").attr("class", res);
    jQuery("#TS_PTable_BIcon_" + This_ID).val(jQuery(This_Element).val());
    jQuery(This_Element).hide();
}

function TS_PTable_Features_New_Li(This_Val) {

    jQuery(This_Val).next("span").html(jQuery(This_Val).val())

}

function TS_Get_text_Feuture(This_Element, This_ID, This_Index) {
    jQuery(".hide_Select").remove();
    event.stopPropagation();
    jQuery(document).click(function (event) {
        var container1 = jQuery(This_Element);
        let val_id = jQuery(This_Element).attr('id');
        if (!container1.is(event.target) && container1.has(event.target).length === 0 && jQuery('.'+val_id).text() != '') {
            jQuery('.Feut_Hover_new_' + This_ID + '_' + This_Index).show();
            container1.hide();
        }
    });
}

function TS_PTable_Out() {
    jQuery('.TS_PTable_Range').each(function () {
        if (jQuery(this).hasClass('TS_PTable_Rangeper')) {
            jQuery('#' + jQuery(this).attr('id') + '_Output').html(jQuery(this).val() + '%');
        } else if (jQuery(this).hasClass('TS_PTable_Rangepx')) {
            jQuery(this).next().val(jQuery(this).val() + 'px')

        } else if (jQuery(this).hasClass('TS_PTable_Rangesec')) {
            jQuery('#' + jQuery(this).attr('id') + '_Output').html(jQuery(this).val() + 's');
        } else {
            jQuery('#' + jQuery(this).attr('id') + '_Output').html(jQuery(this).val());
        }
    })
}

function TotalSoftPTable_Reload() {
    if (jQuery('#Total_Soft_PTable_New_Col').val() == 1) {
    }
               let Create_Action1=Total_Soft_PTable_Edit1;
                jQuery('.Total_Soft_PTable_AMD2').animate({'opacity': 1}, 500);
            jQuery('.Total_Soft_PTable_AMMTable').animate({'opacity': 1}, 500);
            jQuery('.Total_Soft_PTable_AMOTable').animate({'opacity': 1}, 500);
            if (Create_Action1 == 'Total_Soft_PTable_Edit1') {
                jQuery('.Total_Soft_PTable_Save').animate({'opacity': 1}, 500);
                jQuery('.Total_Soft_PTable_Update').animate({'opacity': 0}, 500);
            }
            jQuery('#Total_Soft_PTable_ID').html('');
            jQuery('#Total_Soft_PTable_TID').html('');

                setTimeout(function () {
                jQuery('.Total_Soft_PTable_AMD2').show();
                jQuery('.Total_Soft_PTable_AMMTable').show();
                jQuery('.Total_Soft_PTable_AMOTable').show();
                if (Create_Action1 == 'Total_Soft_PTable_Edit1') {
                    jQuery('.Total_Soft_PTable_Save').css('display', 'none');

                    jQuery('.Total_Soft_PTable_Update').css('display', 'none');
                }
                jQuery('.Total_Soft_PTable_AMD3').css('display', 'none');
                jQuery('.Total_Soft_PTable_AMMain_Div').css('display', 'none');
                 jQuery("#Total_Soft_PTable_Add_Set").val(0);
                jQuery("#Total_Soft_PTable_Col_Del").val(0);
                jQuery('body').removeAttr('style');
                TotalSoftPTable_Close_Option();
                Total_Soft_PTable_Set_Count = 0;
                TS_Col_Last_Id = [];
                New_Col_data = [];
                arr = []
                New_Cop_Set = [];
                New_Cop_Set_Edit = "Total_Soft_PTable_Edit_Theme";
                Theme = 'Theme';
                id_col_index=0;
                flag='false';
                flag_edit='false';
                flag_edit_col='false';
                jQuery('.Total_Soft_PTable_AMMain_Div2_Cols1 .Total_Soft_PTable_AMMain_Div2_Cols1_Action4 i').removeClass('totalsoft-times scale-up-center').addClass('totalsoft-pencil')

            }, 0)

}

function TS_PTable_TS_PTable_FChecked_label(ev,id) {
     let PTable_ID_col_type  = 1;
     let y=24;
     let x=25;
    if (Total_Soft_PTable_Col_Type == "type2") {
        PTable_ID_col_type  = 2;
        y=23;
        x=24;
    } else if (Total_Soft_PTable_Col_Type == "type3") {
        PTable_ID_col_type  = 3;
        y=26;
        x=27;
    } else if (Total_Soft_PTable_Col_Type == "type4") {
        PTable_ID_col_type  = 4;
         y=21;
        x=22;
    } else if (Total_Soft_PTable_Col_Type == "type5") {
        PTable_ID_col_type  = 5;
         y=23;
        x=24;
    }
     let ev_id = ev;
    
   
    if (jQuery(ev_id).hasClass('TS_PTable_FCheck')) {
            jQuery(ev_id).removeClass('TS_PTable_FCheck');
             jQuery(ev_id).removeAttr('checked');
             
               
             if (jQuery(ev_id).parents( "li" ).find( ".Total_Soft_PTable_Features_None i" ).length) {
                jQuery(ev_id).parents( "li" ).find( ".Total_Soft_PTable_Features_None i" ).css({ "color": jQuery("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +id + "_"+y+"']").val()});
            
            }else{
                jQuery(ev_id).parents( "li" ).find( ".feut_icon_cont i" ).css({ "color": jQuery("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +id + "_"+y+"']").val()});
            
            }
              
              
        }else{
       
            jQuery(ev_id).addClass('TS_PTable_FCheck');
             jQuery(ev_id).attr('checked', 'checked');
             if (jQuery(ev_id).parents( "li" ).find( ".Total_Soft_PTable_Features_None i" ).length) {
                 jQuery(ev_id).parents( "li" ).find( ".Total_Soft_PTable_Features_None i" ).css({ "color": jQuery("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +id + "_"+x+"']").val()});
        
             }else{
            jQuery(ev_id).parents( "li" ).find( ".feut_icon_cont i" ).css({ "color": jQuery("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +id + "_"+x+"']").val()});
           
             }
            
        }

}

function TS_PTable_Ajax(btn_name) {
    let ajax_name= '';
    let PTable_ID_col_type  = 1;
    let new_set_col = [];
    let new_dat_col =[];
    let new_set_cop_col = [];
    let new_dat_cop_col =[];
    let new_man_col =[];
    let new_arr =[];
    let PTable_Manag_ID = jQuery('#Total_SoftPTable_Update').val();
    let Col_index=[];
    
    if (btn_name=='save'){PTable_Manag_ID= parseInt(parseInt( PTable_Manag_Num)+PTable_Manag_Count);}
   
    if (Total_Soft_PTable_Col_Type == "type2") {
        PTable_ID_col_type  = 2;
    } else if (Total_Soft_PTable_Col_Type == "type3") {
        PTable_ID_col_type  = 3;
    } else if (Total_Soft_PTable_Col_Type == "type4") {
        PTable_ID_col_type  = 4;
    } else if (Total_Soft_PTable_Col_Type == "type5") {
        PTable_ID_col_type  = 5;
    }

    new_man_col.push( 
        Total_Soft_PTable_Col_Type,
        jQuery('#Total_Soft_PTable_Col_Sel_Count').val(),
        jQuery("#Total_Soft_PTable_M_01").val(),
        jQuery("#Total_Soft_PTable_M_02").val(),
        jQuery("#Total_Soft_PTable_M_03").val(),
        jQuery("#Total_Soft_PTable_Add_Set").val(),
        jQuery("#Total_Soft_PTable_Col_Del").val(),
        PTable_Manag_ID,
        jQuery("#Total_Soft_PTable_Col_Count").val(),
        jQuery("#Total_Soft_PTable_Col_Val_Id").val(),
        jQuery("#Total_Soft_PTable_Theme_Type").val()

    );
let items = document.querySelectorAll(".TS_PTable_Container .Total_Soft_PTable_AMMain_Div2_Cols1");
items.forEach(function(item) {
    let TS_PTable_col_FIcon = [];
    let TS_PTable_col_FCheck = [];
    let TS_PTable_col_FText = [];
    let TS_PTable_col_FIcon_Im = [];
    let TS_PTable_col_FCheck_Im = [];
    let TS_PTable_col_FText_Im = [];
let itemarr_input = item.getElementsByTagName("input");
 Col_index = itemarr_input[0].name.split('_');
Col_index =Col_index[3];
if(btn_name=='update'&& jQuery(item).hasClass('TS_PTable_Container_Col_Copy'))
{
          
          new_set_cop_col.push({
            PTable_ID: PTable_Manag_ID,
            TS_PTable_ST_00: itemarr_input[1].value,
            TS_PTable_ST_01: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_01']").val(),
            TS_PTable_ST_02: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_02']").val(),
            TS_PTable_ST_03: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_03']").val(),
            TS_PTable_ST_04: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_04']").val(),
            TS_PTable_ST_05: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_05']").val(),
            TS_PTable_ST_06: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_06']").val(),
            TS_PTable_ST_07: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_07']").val(),
            TS_PTable_ST_08: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_08']").val(),
            TS_PTable_ST_09: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_09']").val(),
            TS_PTable_ST_10: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_10']").val(),
            TS_PTable_ST_11: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_11']").val(),
            TS_PTable_ST_12: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_12']").val(),
            TS_PTable_ST_13: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_13']").val(),
            TS_PTable_ST_14: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_14']").val(),
            TS_PTable_ST_15: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_15']").val(),
            TS_PTable_ST_16: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_16']").val(),
            TS_PTable_ST_17: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_17']").val(),
            TS_PTable_ST_18: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_18']").val(),
            TS_PTable_ST_19: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_19']").val(),
            TS_PTable_ST_20: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_20']").val(),
            TS_PTable_ST_21: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_21']").val(),
            TS_PTable_ST_21_1: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_21_1']").val(),
            TS_PTable_ST_22: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_22']").val(),
            TS_PTable_ST_23: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_23']").val(),
            TS_PTable_ST_24: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_24']").val(),
            TS_PTable_ST_25: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_25']").val(),
            TS_PTable_ST_26: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_26']").val(),
            TS_PTable_ST_27: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_27']").val(),
            TS_PTable_ST_28: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_28']").val(),
            TS_PTable_ST_29: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_29']").val(),
            TS_PTable_ST_30: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_30']").val(),
            TS_PTable_ST_31: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_31']").val(),
            TS_PTable_ST_32: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_32']").val(),
            TS_PTable_ST_33: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_33']").val(),
            TS_PTable_ST_34: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_34']").val(),
            TS_PTable_ST_35: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_35']").val(),
            TS_PTable_ST_36: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_36']").val(),
            TS_PTable_ST_37: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_37']").val(),
            TS_PTable_ST_38: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_38']").val(),
            TS_PTable_ST_39: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_39']").val(),
            TS_PTable_ST_40: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_40']").val(),
            TS_PTable_TType: Total_Soft_PTable_Col_Type,
            id: itemarr_input[0].value,
            index: itemarr_input[2].value
        });
 jQuery(item).find(".TS_PTable_Features li").each(function(){
    if (jQuery(this).find('table').length) {
        TS_PTable_col_FIcon.push(jQuery(this).find('table td:nth-child(2) input').val());
     TS_PTable_col_FText.push(jQuery(this).find('table td:nth-child(1) input').val());
    }else{
     TS_PTable_col_FIcon.push(jQuery(this).find('.feut_icon_cont input').val());
     TS_PTable_col_FText.push(jQuery(this).find('.feut_text_cont input').val());
    }
      if (jQuery(this).find('.TS_PTable_FChecked input').hasClass('TS_PTable_FCheck')) {
                TS_PTable_col_FCheck.push(jQuery(this).find('.TS_PTable_FChecked input').val());
            } else {
                TS_PTable_col_FCheck.push("");
            }
            
   
    });
     
        TS_PTable_col_FIcon_Im = TS_PTable_col_FIcon.join('TSPTFI');
        TS_PTable_col_FCheck_Im = TS_PTable_col_FCheck.join('TSPTFC');
        TS_PTable_col_FText_Im = TS_PTable_col_FText.join('TSPTFT');

        new_dat_cop_col.push( {
            PTable_ID: PTable_Manag_ID,
            TS_PTable_BIcon: jQuery(item).find("input[name*='TS_PTable_BIcon_" + Col_index + "']").val(),
            TS_PTable_BLink: jQuery(item).find("input[name*='TS_PTable_BLink_" + Col_index + "']").val(),
            TS_PTable_BText: jQuery(item).find("input[name*='TS_PTable_BText_" + Col_index + "']").val(),
            TS_PTable_C_01: TS_PTable_col_FCheck_Im,
            TS_PTable_FCount: jQuery(item).find("input[name*='TS_PTable_FCount_" + Col_index + "']").val(),
            TS_PTable_FIcon: TS_PTable_col_FIcon_Im,
            TS_PTable_FText: TS_PTable_col_FText_Im,
            TS_PTable_PCur: jQuery(item).find("input[name*='TS_PTable_PCur_" + Col_index + "']").val(),
            TS_PTable_PPlan: jQuery(item).find("input[name*='TS_PTable_PPlan_" + Col_index + "']").val(),
            TS_PTable_PVal: jQuery(item).find("input[name*='TS_PTable_PVal_" + Col_index + "']").val(),
            TS_PTable_TIcon: jQuery(item).find("input[name*='TS_PTable_TIcon_" + Col_index + "']").val(),
            TS_PTable_TSetting: itemarr_input[0].value,
            TS_PTable_TText: jQuery(item).find("input[name*='TS_PTable_TText_" + Col_index + "']").val(),
            TS_PTable_TType: Total_Soft_PTable_Col_Type,
            id: itemarr_input[0].value,
            index: itemarr_input[2].value
        });

        jQuery(item).removeClass('TS_PTable_Container_Col_Copy');
}
else{
      new_set_col.push({
            PTable_ID: PTable_Manag_ID,
            TS_PTable_ST_00: itemarr_input[1].value,
            TS_PTable_ST_01: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_01']").val(),
            TS_PTable_ST_02: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_02']").val(),
            TS_PTable_ST_03: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_03']").val(),
            TS_PTable_ST_04: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_04']").val(),
            TS_PTable_ST_05: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_05']").val(),
            TS_PTable_ST_06: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_06']").val(),
            TS_PTable_ST_07: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_07']").val(),
            TS_PTable_ST_08: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_08']").val(),
            TS_PTable_ST_09: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_09']").val(),
            TS_PTable_ST_10: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_10']").val(),
            TS_PTable_ST_11: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_11']").val(),
            TS_PTable_ST_12: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_12']").val(),
            TS_PTable_ST_13: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_13']").val(),
            TS_PTable_ST_14: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_14']").val(),
            TS_PTable_ST_15: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_15']").val(),
            TS_PTable_ST_16: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_16']").val(),
            TS_PTable_ST_17: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_17']").val(),
            TS_PTable_ST_18: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_18']").val(),
            TS_PTable_ST_19: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_19']").val(),
            TS_PTable_ST_20: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_20']").val(),
            TS_PTable_ST_21: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" +Col_index + "_21']").val(),
            TS_PTable_ST_21_1: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_21_1']").val(),
            TS_PTable_ST_22: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_22']").val(),
            TS_PTable_ST_23: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_23']").val(),
            TS_PTable_ST_24: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_24']").val(),
            TS_PTable_ST_25: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_25']").val(),
            TS_PTable_ST_26: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_26']").val(),
            TS_PTable_ST_27: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_27']").val(),
            TS_PTable_ST_28: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_28']").val(),
            TS_PTable_ST_29: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_29']").val(),
            TS_PTable_ST_30: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_30']").val(),
            TS_PTable_ST_31: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_31']").val(),
            TS_PTable_ST_32: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_32']").val(),
            TS_PTable_ST_33: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_33']").val(),
            TS_PTable_ST_34: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_34']").val(),
            TS_PTable_ST_35: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_35']").val(),
            TS_PTable_ST_36: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_36']").val(),
            TS_PTable_ST_37: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_37']").val(),
            TS_PTable_ST_38: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_38']").val(),
            TS_PTable_ST_39: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_39']").val(),
            TS_PTable_ST_40: jQuery(item).find("input[name*='TS_PTable_ST" + PTable_ID_col_type  + "_" + Col_index + "_40']").val(),
            TS_PTable_TType: Total_Soft_PTable_Col_Type,
            id: itemarr_input[0].value,
            index: itemarr_input[2].value
        });
             jQuery(item).find(".TS_PTable_Features li").each(function(){
    

          if (jQuery(this).find('table').length) {
             TS_PTable_col_FIcon.push(jQuery(this).find('table td:nth-child(2) input').val());
             TS_PTable_col_FText.push(jQuery(this).find('table td:nth-child(1) input').val());
            }else{
             TS_PTable_col_FIcon.push(jQuery(this).find('.feut_icon_cont input').val());
             TS_PTable_col_FText.push(jQuery(this).find('.feut_text_cont input').val());
            }
      if (jQuery(this).find('.TS_PTable_FChecked input').hasClass('TS_PTable_FCheck')) {
                TS_PTable_col_FCheck.push(jQuery(this).find('.TS_PTable_FChecked input').val());
            } else {
                TS_PTable_col_FCheck.push("");
            }
      
    });
        
        TS_PTable_col_FIcon_Im = TS_PTable_col_FIcon.join('TSPTFI');
        TS_PTable_col_FCheck_Im = TS_PTable_col_FCheck.join('TSPTFC');
        TS_PTable_col_FText_Im = TS_PTable_col_FText.join('TSPTFT');

        new_dat_col.push( {
            PTable_ID: PTable_Manag_ID,
            TS_PTable_BIcon: jQuery(item).find("input[name*='TS_PTable_BIcon_" + Col_index + "']").val(),
            TS_PTable_BLink: jQuery(item).find("input[name*='TS_PTable_BLink_" + Col_index + "']").val(),
            TS_PTable_BText: jQuery(item).find("input[name*='TS_PTable_BText_" + Col_index + "']").val(),
            TS_PTable_C_01: TS_PTable_col_FCheck_Im,
            TS_PTable_FCount: jQuery(item).find("input[name*='TS_PTable_FCount_" + Col_index + "']").val(),
            TS_PTable_FIcon: TS_PTable_col_FIcon_Im,
            TS_PTable_FText: TS_PTable_col_FText_Im,
            TS_PTable_PCur: jQuery(item).find("input[name*='TS_PTable_PCur_" + Col_index + "']").val(),
            TS_PTable_PPlan: jQuery(item).find("input[name*='TS_PTable_PPlan_" + Col_index + "']").val(),
            TS_PTable_PVal: jQuery(item).find("input[name*='TS_PTable_PVal_" + Col_index + "']").val(),
            TS_PTable_TIcon: jQuery(item).find("input[name*='TS_PTable_TIcon_" + Col_index + "']").val(),
            TS_PTable_TSetting: itemarr_input[0].value,
            TS_PTable_TText: jQuery(item).find("input[name*='TS_PTable_TText_" + Col_index + "']").val(),
            TS_PTable_TType: Total_Soft_PTable_Col_Type,
            id: itemarr_input[0].value,
            index: itemarr_input[2].value
        });
    }

    });
if (new_set_col.length==0) {
    new_set_col.push({TS_PTable_TType: "type0"} );
    new_dat_col.push({TS_PTable_TType: "type0",});
}

if(btn_name=='update'){
       jQuery('span.Total_Soft_PTable_Update').html(' <i class="totalsoft totalsoft-cog tsoft_pricing_spin"></i>Updating');
    ajax_name='Total_Soft_PTable_Update';
      new_arr={
        arg_1:new_set_col,
        arg_2:new_dat_col,
        arg_5:new_man_col,
        arg_3:new_dat_cop_col,
        arg_4:new_set_cop_col
        
    }
}
else{
    ajax_name='Total_Soft_PTable_Save';
 
    new_arr={
        arg_1:new_set_col,
        arg_2:new_dat_col,
        arg_3:new_man_col
    }
}

    jQuery.ajax({
        type: 'POST',
        url: object.ajaxurl,
        data: {
            action: ajax_name,
            foobarArr: new_arr, 
        },
        success: function (response) {
              var data = JSON.parse(response);
              jQuery("#Total_Soft_PTable_Add_Set").val(0);
              jQuery("#Total_Soft_PTable_Col_Del").val(0);
              PTable_Manag_Count++;
                arr=[];
                if (btn_name=='update') {
                    jQuery("#Total_Soft_PTable_AMOTable_tr_"+PTable_Manag_ID+" td:nth-child(4)").text(jQuery("#Total_Soft_PTable_Col_Sel_Count").val())

                    
                }
              if (btn_name=='save') {
                 jQuery('.Total_Soft_PTable_Save').css('display', 'none');
                 jQuery('.Total_Soft_PTable_Update').css('display', 'block');
                 New_Cop_Set_Edit = "Total_Soft_PTable_Edit_Theme";
                  jQuery('.Total_Soft_PTable_Update').animate({'opacity': 1}, 500);

                   let num_i_s = jQuery('.Total_Soft_PTable_AMOTable').find('tr').length+1;
             var Total_Soft_PTable_T_S = data['Total_Soft_PTable_Them'].replace("type", "Theme ");
                jQuery(".Total_Soft_PTable_AMOTable").append(
                    '   <tr id="Total_Soft_PTable_AMOTable_tr_' + data["id"] + '">' +
                    '     <td>' + num_i_s + '</td>' +
                    '     <td>' + data["Total_Soft_PTable_Title"] + '</td>' +
                    '     <td>Pricing ' + Total_Soft_PTable_T_S + '</td>' +
                    '     <td>' + data["Total_Soft_PTable_Cols_Count"] + '</td>' +
                    '     <td><i class="totalsoft totalsoft-file-text" onclick="TotalSoftPTable_Clone(' + data["id"] + ')"></i></td>' +
                    '     <td><i class="totalsoft totalsoft-pencil"  onclick=" Total_Soft_PTable_AMD2_But_Edit(' + data["id"] + ',2)"></i></td>' +
                    '     <td>' +
                    '       <i class="totalsoft totalsoft-trash" onclick="TotalSoftPTable_Del(' + data["id"] + ')"></i> ' +
                    '       <span class="Total_Soft_PTable_Del_Span">' +
                    '           <i class="Total_Soft_PTable_Del_Span_Yes totalsoft totalsoft-check" onclick="TotalSoftPTable_Del_Yes(' + data["id"] + ')"></i>' +
                    '           <i class="Total_Soft_PTable_Del_Span_No totalsoft totalsoft-times" onclick="TotalSoftPTable_Del_No(' + data["id"] + ')"></i>' +
                    '       </span>' +
                    '     </td>' +
                    ' </tr>'
                );
              }
             
             jQuery('span.Total_Soft_PTable_Update').html(' <i class="totalsoft totalsoft-check-circle  tsoft_pricing_setting"></i>Updated');
              setTimeout(function () { jQuery('span.Total_Soft_PTable_Update').html(' <i class="totalsoft totalsoft-cog tsoft_pricing_setting"></i>Update');},500)
            }
        });
}
