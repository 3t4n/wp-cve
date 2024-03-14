function gAnnounceNoEnterKey(e)
{
    var pK = e ? e.which : window.event.keyCode;
    return pK != 13;
}
document.onkeypress = gAnnounceNoEnterKey;
if (document.layers) document.captureEvents(Event.KEYPRESS);