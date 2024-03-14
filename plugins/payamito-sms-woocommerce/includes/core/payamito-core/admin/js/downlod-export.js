jQuery(window).load(function ($) {

    var element = document.createElement('a');
    let href = payamito_export.url + "/" + payamito_export.file_name;
    element.setAttribute('href', href);

    element.setAttribute('download', payamito_export.file_name);

    document.body.appendChild(element);

    element.click();


});