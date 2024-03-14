jQuery(function () {
    jQuery(".page-numbers").click(function (e) {
        jQuery("#hsb_HYPESocialBuffer").attr("action", jQuery(this).attr("href"));
        e.preventDefault();
        jQuery("#pageit").click();
    });// page number click end
});//jquery document.ready end

function hab_setExcludeList(exlist) {
    jQuery("#excludeList").html("\"" + exlist + "\"");
}


function hsb_managedelid(ctrl, id) {

    var delids = document.getElementById("delids").value;
    if (ctrl.checked) {
        delids = hsb_addId(delids, id);
    }
    else {
        delids = hsb_removeId(delids, id);
    }
    document.getElementById("delids").value = delids;
    hab_setExcludeList(delids);
}

function hsb_removeId(list, value) {
    list = list.split(",");
    if (list.indexOf(value) != -1)
        list.splice(list.indexOf(value), 1);
    return list.join(",");
}


function hsb_addId(list, value) {

    if (list === '')
        return [value];

    list = list.split(",");

    if (list.indexOf(value) == -1)
        list.push(value);

    return list.join(",");
}

function hsb_checkedAll() {
    var ischecked = document.hsb_HYPESocialBuffer.headchkbx.checked;
    var delids = "";
    for (var i = 0; i < document.hsb_HYPESocialBuffer.chkbx.length; i++) {
        document.hsb_HYPESocialBuffer.chkbx[i].checked = ischecked;
        if (ischecked)
            delids = delids + document.hsb_HYPESocialBuffer.chkbx[i].value + ",";
    }
    document.getElementById("delids").value = delids;
}

if (window.hsb_exposts != undefined) {
    hab_setExcludeList(window.hsb_exposts);
}
