jQuery( document ).ready(function() {
  // console.log( "ready!" );
   var curr_date = new Date();
   var delivry_date = product_estdate.delivry_date;
   var est_delivry_date = curr_date.setDate(curr_date.getDate() + parseInt(delivry_date));
   // var months = curr_date.getMonth() + 1;

   
   var month = ["","January","February","March","April","May","June","July","August","September","October","November","December"];
   
  //  jQuery('.est_delvry_date').text(curr_date.getDate());
  //  jQuery('.est_delvry_month').text(month[curr_date.getMonth() + 1]);
  //  jQuery('.est_delvry_year').text(curr_date.getFullYear());
  //  var curdate = parseInt(curr_date.getDate());
  //  console.log(curdate);
  // jQuery.ajax({
  //   type: "POST",
  //   url: product_estdate.ajaxurl,
  //   data: { curdate: curdate, action: "esdppfw_save_val_ajax" },
  //   success:function(data){

  //   }
  // })
  //   //console.log(est_delivry_date);
});