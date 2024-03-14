// JavaScript Document

function toggleContainer() {
    var checkboxes = document.getElementsByClassName('collapsable');

    for(var i = 0; i < checkboxes.length; i++) {
        var checkboxName = checkboxes[i].getAttribute('name');
        var checkboxContainer = document.getElementById(checkboxName+'-container');

        if(checkboxes[i].checked){
            checkboxContainer.style.display = 'block';
        }else {
            checkboxContainer.style.display = 'none';
        }

    }
}

function copyToClipboard(id){
    var input = document.getElementById(id);

    input.select();
    document.execCommand("copy");
}

window.addEventListener('DOMContentLoaded', toggleContainer());