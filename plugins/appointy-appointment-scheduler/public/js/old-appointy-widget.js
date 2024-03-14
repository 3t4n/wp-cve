
var appointy = '';
var scheduleoHeight = '';
var scheduleoWidth = '';
var ShowSchedulemeImg = '';
var ScheduleMeBgImg = '';
var ScheduleMeBg = '';
var ScheduleMeWidth = '';
var ScheduleMeHeight = '';
var ScheduleMePosition = '';
var AppointyLanguage = '';
var appointyHeight = '700';
var appointyWidth = '900';

function widget(useranme, width, height, language) {
    appointy = useranme;
    scheduleoHeight = height;
    scheduleoWidth = width;
    ShowSchedulemeImg = true;
    //if showSchedulemeImg is set to false then it will override the properties below. This can be used if you want to call overlay from your own custom link.
    ScheduleMeBgImg = 'http://static.appointy.com/Widget/Images/scheduleme.png';
    ScheduleMeBg = 'transparent';
    ScheduleMeWidth = '47';
    ScheduleMeHeight = '150';
    ScheduleMePosition = 'right';  // right, left
    // You can also call function ShowAppointyInOverlay() onclick of any tag.
    // e.g. <a href="javascript:void(0)" onclick="ShowAppointyInOverlay();">Schedule with us</a>
    AppointyLanguage = language;
	appointyHeight = parseInt(height)
	appointyWidth = parseInt(width)
    if(height && height.includes("%")) {
        appointyHeight = appointyHeight * screen.height/100;
    }
    if(width && width.includes("%")) {
        appointyWidth = appointyWidth * screen.width/100;
    }


}
