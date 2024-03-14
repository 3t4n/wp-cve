const _timeLoader = 2000;

document.addEventListener("DOMContentLoaded", function() {
    setTimeout(function() {
        document.getElementById("loader").style.display = "none";
        document.getElementById("content").style.display = "block";
    }, _timeLoader);
});

document.addEventListener("DOMContentLoaded", function() {
    var dotContainer = document.getElementById('loadingDots');
    var maxDots = 3;
    var loadingInterval;
    var keepRunning = true;

    function updateDots() {
        if (dotContainer.innerText.length < maxDots) {
            dotContainer.innerText += '.';
        } else {
            dotContainer.innerText = '';
        }
    }

    loadingInterval = setInterval(function() {
        if(keepRunning) {
            updateDots();
        } else {
            clearInterval(loadingInterval);
            dotContainer.innerText = '';
        }
    }, 150);

    setTimeout(function() {
        keepRunning = false;
    }, _timeLoader);
});