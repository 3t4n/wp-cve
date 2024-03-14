function changeTab(evt, cityName,tabType,secondtabType) {
    var i, x, tablinks;
    x = document.getElementsByClassName("city");
    document.getElementById(tabType).className='tabs-item active';
    document.getElementById(secondtabType).className='tabs-item ';

    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";
    }
  
    document.getElementById(cityName).style.display = "block";
}


function activateSetupTab(tab) {
    jQuery('.menu .item').tab('change tab', tab);
    jQuery('html,body').scrollTop(0);
  }
  