<?php
if (!defined('ABSPATH'))
   exit;

?>
<div class="sbs-6310">
   <div class="sbs-6310-row sbs-6310-row-plugins">
      <h1 class="sbs-6310-sbsart-all-plugins">Plugins Reference Video</h1>
   </div>
</div>

<script>
   jQuery.getJSON('https://demo.tcsesoft.com/plugins/sbs.php', function(data) {
      let htmlCode = '';
      for(let i = 0; i < data.length; i++) {         
         htmlCode += `
         <div class="sbs-6310-help-section">         
            <div class="sbs-6310-sbsart-plugins-video">
            <i class="fas fa-film"></i><a href="${data[i].url}" target="_blank">${data[i].title}</a>
            </div>
         </div>`;
      }
      jQuery('.sbs-6310-sbsart-all-plugins').after(htmlCode);
   });
</script>
<style>
h1.sbs-6310-sbsart-all-plugins {  
    color: chocolate !important; 
    
}
.sbs-6310-help-section{
   width: 100%;
   display: inline;
   float: left;
   margin: 8px 30px;
   font-size: 14px;
}
.sbs-6310-sbsart-plugins-video{
   background-color: transparent;
}
.sbs-6310-sbsart-plugins-video i{
   float: left;
   padding-right: 5px;
   font-size: 21px;
   color: #009097;
}
.sbs-6310-sbsart-plugins-video a {
    text-decoration: none;
    float: left;
    margin: 0;
    padding: 0;
    color: #2c2e1d94;
    font-weight: 600;
 
}
.sbs-6310-sbsart-plugins-video a:hover {
    color: #027f85;
}

</style>