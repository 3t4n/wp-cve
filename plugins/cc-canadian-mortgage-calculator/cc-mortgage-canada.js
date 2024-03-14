jQuery(document).ready(function($) {
	// runtime events
	
	$(".ca-purchase-price").keydown(function(event) {
		if(!(isIntegerKey(event))) event.preventDefault();
		
	});	

	$(".ca-down-payment").keydown(function(event) {
		if(!(isIntegerKey(event))) event.preventDefault();
		
	});	

	$(".ca-mortgage-term").keydown(function(event) {
		if(!(isIntegerKey(event))) event.preventDefault();
		
	});	

	$(".ca-mortgage-rate").keydown(function(event) {
        if(!(isDecimalKey(event,this.value))) event.preventDefault();
		
	});	
	
	$(".ca-purchase-price").keyup(function( ) {
        calculate_canadian_mortgage(get_id(this.id,"purchase-price"));
	});

	$(".ca-down-payment").keyup(function( ) {
        calculate_canadian_mortgage(get_id(this.id,"down-payment"));
	});

	$(".ca-mortgage-term").keyup(function( ) {
        calculate_canadian_mortgage(get_id(this.id,"mortgage-term"));
	});

	$(".ca-mortgage-rate").keyup(function( ) {
        calculate_canadian_mortgage(get_id(this.id,"mortgage-rate"));
	});

    function get_id(long_id,fieldname)
    {
        return long_id.substr(0, long_id.lastIndexOf(fieldname) - 1);
    };

    function calculate_canadian_mortgage(id)
    {
	    var price = $('#' + id + '-' + 'purchase-price').val(),
            payment = $('#' + id + '-' + 'down-payment').val(),
		    term = $('#' + id + '-' + 'mortgage-term').val(),
		    rate = $('#' + id + '-' + 'mortgage-rate').val();
	
	    // if no data entered
	    if (isNaN(price) || price == "") return;
        if (isNaN(payment) || payment == "") return;
	    if (isNaN(term) || term == "") return;
	    if (isNaN(rate) || rate == "") return;
	
	
	    var semiAnnualRate = (rate/2)/100,
		    paymentsNumber = term * 12,
		    monthlyPayment = 0,
		    totalPayment = 0,
		    interestPaid = 0,
            currency = $('#' + id + '-' + 'currency').val();

	    monthlyInterestFactor = Math.pow(1 + semiAnnualRate, 1/6) - 1;

	    mortgage = price - payment;

        monthlyPayment = round2TwoDecimals((mortgage * monthlyInterestFactor) / (1 - Math.pow(1 + monthlyInterestFactor, -paymentsNumber)));		

        $('#' + id + '-' + 'mortgage').html(currency + formatNumber(mortgage).toString());
        $('#' + id + '-' + 'monthlyPayment').html(currency + formatNumber(monthlyPayment).toString());
   
    };

}); // <-- jQuery END

function isIntegerKey(evt)	  
      {
         var key = evt.which || evt.which || event.keyCode;
		 // allow backspace, tab, delete, arrows, numbers and keypad numbers ONLY
		 var isInteger = (key == 8 || 
                key == 9 ||
                key == 46 ||
                (key >= 35 && key <= 40) ||
                (key >= 48 && key <= 57) ||
                (key >= 96 && key <= 105));
		return isInteger;
				
      };
	  
function isDecimalKey(e, number)
      {
         var key = (e.which) ? e.which : e.keyCode;
		 // numbers (48-57 and 96-105), dot (110,190), comma (44,188), backspace(8), tab (9), navigation keys (35-40), DEL(46)
		 if ((key >= 48 && key <= 57) || (key >= 96 && key <= 105) || key == 110 || key == 190 || key == 8 || key == 9 || (35 <= key && key <= 40) || key == 46 )  
		 	{
			 		  if (key == 110 || key == 190)
					  {
					   	 // skip it if comma or decimal point already entered or it is empty field yet
						 if (number.indexOf(".") > -1 || number.indexOf(",") > -1 || number.length == 0) 
						 	return false; 
					  }
			          return true;
			}

         return false;
      };

function radioValue(element)	  
		 {
		    var returnValue = "";
            var radios = document.getElementsByName(element);
            
            for (var i = 0, length = radios.length; i < length; i++) {
                if (radios[i].checked) {
                    returnValue = radios[i].value;
                }
			}
			return returnValue;	
		 };	  	

function round2TwoDecimals(number)
		 {
 		    return Math.round(number*100)/100						 
		 };	
 
function formatNumber (num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}
