document.addEventListener('DOMContentLoaded', function () {

    // Get the form element
    const form = document.querySelector('.settings-form');

    // Create Burger-Menu
    const burgerMenu = document.createElement('div');
    burgerMenu.classList.add('burger-menu');

    // Create Burger-Icon
    const burgerIcon = document.createElement('div');
    burgerIcon.classList.add('burger-icon');
    burgerIcon.id = 'burger-icon';
    burgerIcon.innerHTML = '<h2>&#9776;</h2>';

    // Create wrappers
    const horizontalWrapper = document.createElement('div');
    horizontalWrapper.classList.add('horizontal-wrapper');

    const verticalWrapper = document.createElement('div');
    verticalWrapper.classList.add('vertical-wrapper');

    // Iterate through the form elements
    let currentElement = form.firstElementChild;
    while (currentElement) {
        // Store the reference to the next element before moving it
        const nextElement = currentElement.nextElementSibling;

        // Move h2 elements to horizontal wrapper and tables to vertical wrapper
        if (currentElement.tagName === 'DIV' && currentElement.classList.contains('gdpr-tab-link')) {
            horizontalWrapper.appendChild(currentElement);
        } else if (currentElement.tagName === 'TABLE' && currentElement.classList.contains('form-table')) {
            verticalWrapper.appendChild(currentElement);
        } else if (currentElement.tagName === 'DIV' && currentElement.id === 'submit-container') {
            verticalWrapper.appendChild(currentElement);
        }

        // Move to the next element
        currentElement = nextElement;
    }

    // Append the wrappers to the form
    burgerMenu.appendChild(burgerIcon);
    burgerMenu.appendChild(horizontalWrapper);
    form.appendChild(burgerMenu);
    //form.appendChild(horizontalWrapper);
    form.appendChild(verticalWrapper);

    // Get all tab links
    var tabLinks = document.querySelectorAll('.gdpr-tab-link');

    // Attach click event listener to each tab
    tabLinks.forEach(function (tab) {
        tab.addEventListener('click', function () {
            // Hide all options
            hideAllOptions();
            tabLinks.forEach(function (otherTabLink) {
                otherTabLink.classList.remove('gdpr-tab-link-last-clicked');
            });
            // Get the target tab's data-tab-target attribute
            var target = this.getAttribute('data-tab-target');

            // Show options belonging to the selected section
            showOptionsInTab(target);
            this.classList.add('gdpr-tab-link-last-clicked');

            // Assuming you have a unique identifier for your input element
            var gdprSettingsSelection = document.getElementById('gdpr-settings-selection');

            // Check if the element exists before attempting to modify its value
            if (gdprSettingsSelection) {
                // Change the value of the input
                gdprSettingsSelection.value = this.getAttribute('data-tab-target');
            }
            if(window.innerWidth<600){
                burgerIcon.click();
            }
        });
    });

    
    var hilfe_dialoge = document.getElementsByClassName('hilfe_dialog');

    for (var hilfe_dialog of hilfe_dialoge) {
        dragElement(hilfe_dialog);
    }

    function dragElement (elmnt){
        var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
        if (document.getElementById(elmnt.id + "header")) {
        /* if present, the header is where you move the DIV from:*/
        document.getElementById(elmnt.id + "header").onmousedown = dragMouseDown;
        } else {
        /* otherwise, move the DIV from anywhere inside the DIV:*/
        elmnt.onmousedown = dragMouseDown;
        }
    
        function dragMouseDown(e) {
        e = e || window.event;
        e.preventDefault();
        // get the mouse cursor position at startup:
        pos3 = e.clientX;
        pos4 = e.clientY;
        document.onmouseup = closeDragElement;
        // call a function whenever the cursor moves:
        document.onmousemove = elementDrag;
        }
    
        function elementDrag(e) {
        e = e || window.event;
        e.preventDefault();
        // calculate the new cursor position:
        pos1 = pos3 - e.clientX;
        pos2 = pos4 - e.clientY;
        pos3 = e.clientX;
        pos4 = e.clientY;
        // set the element's new position:
        elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
        elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
        }
    
        function closeDragElement() {
        /* stop moving when mouse button is released:*/
        document.onmouseup = null;
        document.onmousemove = null;
        }      
    }

    // Function to hide all options
    function hideAllOptions() {
        var allOptions = document.querySelectorAll('.recaptchaOption');
        allOptions.forEach(function (option) {
            var closestTable = option.closest('table');
            if (closestTable) {
                closestTable.style.display = 'none';
            }
        });
    }

    // Function to show options in a specific tab
    function showOptionsInTab(tabId) {
        var optionsInTab = document.querySelectorAll('.' + tabId);
        optionsInTab.forEach(function (option) {
            var closestTable = option.closest('table');
            if (closestTable){
                closestTable.style.display = '';
            }
        });
    }

    tabLinks.forEach(function (tab) {
        if(tab.getAttribute('data-tab-target') === gdprSettingsSelection.value){
            tab.click();
        }
    });

    // Toggle the display of the vertical wrapper on burger icon click
    burgerIcon.addEventListener('click', function() {
        var elements = document.querySelectorAll('.gdpr-tab-link');
        elements.forEach(function (currentElement) {
            currentElement.style.display = (currentElement.style.display === 'flex') ? 'none' : 'flex';
        });
    });

    window.addEventListener('resize', function() {
        // Your code to handle window resize goes here
    
        // You can access the current window width and height like this:
        const currentWidth = window.innerWidth;
        var elements = document.querySelectorAll('.gdpr-tab-link');
        elements.forEach(function (currentElement) {
            currentElement.style.display = (currentWidth < 600) ? 'none' : 'flex';
        });
    
    });
    
});