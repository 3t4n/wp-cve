jQuery( document ).ready(function() {
    console.log( "ready!" );
     var wpcf7Elm = document.querySelector( '.wpcf7' );
     

        wpcf7Elm.addEventListener( 'wpcf7submit', function( event ) {
          var custome = event.detail.apiResponse.status;
          //console.log(custome);
          // alert( "Fire!" );
          //swal("Oops" ,  event.detail.apiResponse.message ,  "error");
          // console.log(event);
          //console.log(event.detail.apiResponse.status);

             if(custome == 'validation_failed'){
              swal("Oops" ,  event.detail.apiResponse.message ,  "error");
             }else{
              swal("Success" ,  event.detail.apiResponse.message ,  "success");
             }

        });
         
});

       