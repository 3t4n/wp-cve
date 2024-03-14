const _timeLoader = 2000;

document.addEventListener("DOMContentLoaded", function() {
    setTimeout(function() {
        document.getElementById("content").style.display = "block";
    }, _timeLoader);
});