function zakah_check_empty(empty){
	if (empty.value == ""){
		empty.value = 0;
	}
	if (empty.value< 0) {
		alert("The value must be equal or greater than 0 !");
		empty.focus();
	}
	zakah_calculate();
}
	
function zakah_lostfocus(current_field){
	zakah_calculate();
	
}

function reset_zakah_print(){
		var div_id = document.getElementById("zakah_result");
		div_id.innerHTML  = "";
}
	
function zakah_blankfield(field_name){
	if (field_name.value == "0"){
	field_name.value = ""; }
} 
	
function zakah_calculate(){
	with(document.calculate_zakah){
		var zakah_format = 0;
		total_amount.value=eval(amount.value); 
		if(eval(total_amount.value)< 0){ 
			alert("The total cannot be less than Zero");
		}else{
			zakah_format = zakah_formated(Math.round(eval(total_amount.value)*0.025*100)/100);
			zakah.value=zakah_format;
			zakah_print(zakah_format);
		}
	}

}
	
function zakah_formated(source){
	 var temp1=new String(source);
	 if(temp1.indexOf(".")!=-1){
		 var position=temp1.indexOf(".");
		 if(temp1.charAt(position+3)!="" && temp1.charAt(position+3)>4){
			 a=temp1.substring(0,position+3);
			 a=eval(a)*100;
			 a=eval(a)+1;
			 a=eval(a)/100;
			 return a;
		 }else{
			 return temp1.substring(0,position+3);
		}
	 } else {
		 return temp1.valueOf();
	 }
}