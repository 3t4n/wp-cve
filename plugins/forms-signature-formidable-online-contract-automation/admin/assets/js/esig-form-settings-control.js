function prefill() {

    if (document.getElementById('reminder_data').checked) {

        document.getElementById("reminder_section").style.visibility = "visible";

        // setting a validation message   
        let reminderEmail = document.getElementById("reminder_email");
        let firstReminderSend = document.getElementById("first_reminder_send");
        let expireReminder = document.getElementById("expire_reminder");

        reminderEmail.required = true;
        firstReminderSend.required = true;
        expireReminder.required = true;


    } else {
        document.getElementById("reminder_section").style.visibility = "hidden";
        let reminderEmail = document.getElementById("reminder_email");
        let firstReminderSend = document.getElementById("first_reminder_send");
        let expireReminder = document.getElementById("expire_reminder");

        reminderEmail.required = false;
        firstReminderSend.required = false;
        expireReminder.required = false;
    }

}


document.getElementById("frm_submit_side_top").onclick = function(){
    const mydivclass = document.querySelector('.frm_single_esig_settings');

   // if(mydivclass.classList.contains('open')) {
        if (document.getElementById('reminder_data').checked) {
            
            var firstReminder = document.getElementById('reminder_email');
            var secondReminder = document.getElementById('first_reminder_send');    
            var thirdReminder = document.getElementById('expire_reminder');    
            
            if(parseInt(secondReminder.value) <= parseInt(firstReminder.value)){                
                alert("Second reminder should be Greater than First reminder");
                secondReminder.value = '';
            }
            if( parseInt(thirdReminder.value) <= parseInt(secondReminder.value) ){
                alert("Last reminder should be Greater than Second reminder");
                thirdReminder.value = '';
            }

        
        }
    
  //  }


}