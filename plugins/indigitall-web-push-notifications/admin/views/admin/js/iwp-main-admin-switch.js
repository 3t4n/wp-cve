document.addEventListener('DOMContentLoaded', function() {
    // toggleSwitch();

});

// function toggleSwitch() {
//     let switches = document.getElementsByClassName('iwp-admin-switch-container');
//     if (switches.length) {
//         Array.from(switches).forEach((element) => {
//             element.addEventListener("click", function () {
//                 if (!element.classList.contains('blocked')) {
//                     if (element.classList.contains('disabled')) {
//                         element.classList.remove('disabled');
//                         element.classList.add('enabled');
//                         element.querySelector('.iwp-admin-switch-value').value = '1';
//                     } else {
//                         element.classList.remove('enabled');
//                         element.classList.add('disabled');
//                         element.querySelector('.iwp-admin-switch-value').value = '0';
//                     }
//                 }
//             });
//         });
//     }
// }

function toggleSwitch(element, activateBlocked = false, disableClass, enableClass) {
    if (!element.classList.contains('blocked') || !activateBlocked) {
        if (element.classList.contains(disableClass)) {
            enableSwitch(element, disableClass, enableClass);
        } else {
            disableSwitch(element, disableClass, enableClass);
        }
    }
}

function enableSwitch(element, disableClass, enableClass) {
    element.classList.remove(disableClass);
    element.classList.add(enableClass);
    element.querySelector('.iwp-admin-switch-value').value = '1';
}

function disableSwitch(element, disableClass, enableClass) {
    element.classList.remove(enableClass);
    element.classList.add(disableClass);
    element.querySelector('.iwp-admin-switch-value').value = '0';
}