jQuery(document).ready(function(){
  
  var windowSizeArray = [ "width=500,height=500,scrollbars=yes" ];
  
  jQuery('.wpfainw').click(function (event){
    event.preventDefault();
    
    var url = jQuery(this).attr("href");
    var windowName = "wpfai_popUp";//
    var windowSize = windowSizeArray[0];

    window.open(url, windowName, windowSize);

    return false;

  });
});