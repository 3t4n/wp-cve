function deleteItEpollResultRecord(record_id=0,poll_id=0,option_id=0){
    if (confirm("Do you really want to delete this record?") == true) {
        if(record_id){
            var data = {
                'action': 'it_epoll_delete_voting_record',
                'result_id': record_id,
                'poll_id': poll_id,  
                'option_id': option_id       // We pass php values differently!
            };
            // We can also pass the url value separately from ajaxurl for front end AJAX implementations
            jQuery.post(ajaxurl, data, function(response) {
               if(response){
                var res = JSON.parse(response);
                if(res.sts == 200){

                    alert("Successfully Removed!");
                    window.location.reload();
                }else{
                    alert("Something went wrong!");
                }
               }
            });
        }
       
      }
}