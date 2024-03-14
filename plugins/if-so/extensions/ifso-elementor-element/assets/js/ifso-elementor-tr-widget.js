(function ($) {
    'use strict';
    $(document).ready(function () {elementor.hooks.addAction( 'panel/open_editor/widget/ifso_dynamic_content', constructIfsoAnalyticsUi );});
})(jQuery);

var ifso_trigger_selected_pid;

function constructIfsoAnalyticsUi(panel) {
    ifso_trigger_selected_pid = jQuery('.elementor-control:not(.elementor-hidden-control) .analytics-container').attr('pid');
    jQuery('select[data-setting="trigger"]').on('change', waitAndTryConstructAnalyticsUi);
    if (!!ifso_trigger_selected_pid)
        refreshAnalyticsDisplay();
}

function waitAndTryConstructAnalyticsUi(){
    window.setTimeout(constructIfsoAnalyticsUi, 1000); // 1 second
}

function getAnalyticsData(postid) {
    ajaxPost({action: 'ifso_analytics_req', an_action: 'getFields', postid: postid}, buildAnalyticsDisplay)
}

function buildAnalyticsDisplay(res) {
    document.querySelector('#analytics-loading-notice-' + ifso_trigger_selected_pid).className = '';
    var container = document.querySelector('#analytics-container-' + ifso_trigger_selected_pid);
    container.innerHTML = '';
    var data = JSON.parse(res);
    container.appendChild(createRow(['Version', 'Views', 'Conversions', 'Conv.rate']));
    for (var x = 0; x <= data.length - 1; x++) {
        var convRate = (Number(data[x]['views']) != 0) ? (Number(data[x]['conversion']) * 100 / Number(data[x]['views'])).toFixed(2) + '%' : '0.00%';
        //var convRate = ( Number(data[x]['views'])!=0) ? Math.round((Number(data[x]['conversion'])*100/Number(data[x]['views']) )) + '%' : '0%';
        var newrow = createRow([data[x]['version_name'], data[x]['views'], data[x]['conversion'], convRate]);
        newrow.setAttribute('myversion', x);
        if (data[x]['version_name'] == 'Default') newrow.setAttribute('myversion', 'default');
        if (x % 2 == 0) newrow.className += ' odd';
        newrow.addEventListener('click', function (e) {
            var version = e.currentTarget.getAttribute('myversion');
            resetVersionFields(version);
        });
        container.appendChild(newrow);
    }
    document.querySelector('#analytics-loading-notice-' + ifso_trigger_selected_pid).className = 'nodisplay';
    document.querySelectorAll('#refreshTriggerAnalytics>i').forEach(function (e) {
        e.classList.remove('spin');
    });
}

function resetVersionFields(version) {
    if (confirm('Are you sure you want to reset this version stats')) {
        ajaxPost({
            action: 'ifso_analytics_req',
            an_action: 'resetFields',
            postid: ifso_trigger_selected_pid,
            versionid: version
        }, function () {
            refreshAnalyticsDisplay(ifso_trigger_selected_pid);
        })
    }
}

function refreshAnalyticsDisplay() {
    document.querySelectorAll('#refreshTriggerAnalytics>i').forEach(function (e) {
        e.classList.add('spin');
    });
    getAnalyticsData(ifso_trigger_selected_pid);
}

function createRow(children) {
    var row = document.createElement('div');
    row.className = 'row';
    for (var i = 0; i <= children.length - 1; i++) {
        var el = document.createElement('span');
        el.innerHTML = (children[i] && children[i] != 'false') ? children[i] : 0;
        row.appendChild(el);
    }
    var reset_notice = document.createElement('div');
    reset_notice.className = 'reset-notice';
    reset_notice.innerHTML = 'Reset version stats';
    row.appendChild(reset_notice);
    return row;
}

function ajaxPost(data,callback,errCallback){
    if(data==undefined) data = {};
    if(callback==undefined) callback = function(){};	//Not using default parameters to prevent from breaking in IE
    if(errCallback==undefined) errCallback = function(){};
    if(typeof(nonce)!=='undefined' && nonce)
        data['_ifsononce'] = nonce;
    jQuery.post(ajaxurl,data,callback).fail(console.log);
}