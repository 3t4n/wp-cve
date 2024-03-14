iFrameResize({
    log: false,
    checkOrigin: false,
    maxWidth: screen.width,
    sizeWidth: true,
    onResized: function (message) {
        var height = document.getElementById('hq-rental-iframe').clientHeight;
        var newheight = height * 1.1;
        document.getElementById("hq-rental-iframe").style.height = newheight + "px";
    }
}, '#hq-rental-iframe');
