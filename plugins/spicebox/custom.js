JotForm.init(function(){
	JotForm.clearFieldOnHide="disable";
    /*INIT-END*/
	});

   JotForm.prepareCalculationsOnTheFly([null,{"name":"quickFeedback","qid":"1","text":"QUICK FEEDBACK","type":"control_head"},{"name":"submitForm","qid":"2","text":"Submit Feedback","type":"control_button"},{"name":"feedbackType","qid":"3","text":"Feedback Type","type":"control_radio"}]);
   setTimeout(function() {
JotForm.paymentExtrasOnTheFly([null,{"name":"quickFeedback","qid":"1","text":"QUICK FEEDBACK","type":"control_head"},{"name":"submitForm","qid":"2","text":"Submit Feedback","type":"control_button"},{"name":"feedbackType","qid":"3","text":"Feedback Type","type":"control_radio"}]);}, 20); 