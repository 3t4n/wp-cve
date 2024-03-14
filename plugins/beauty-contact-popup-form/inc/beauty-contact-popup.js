/* Beauty Contact Popup form */

var http_req = false;
function TagPopupPOSTRequest(url, parameters) 
{
  http_req = false;
  if (window.XMLHttpRequest) 
  {
	 http_req = new XMLHttpRequest();
	 if (http_req.overrideMimeType) 
	 {
		http_req.overrideMimeType('text/html');
	 }
  } 
  else if (window.ActiveXObject) 
  {
	 try 
	 {
		http_req = new ActiveXObject("Msxml2.XMLHTTP");
	 } 
	 catch (e) 
	 {
		try 
		{
		   http_req = new ActiveXObject("Microsoft.XMLHTTP");
		} 
		catch (e) {}
	 }
  }
  if (!http_req) 
  {
	 alert('Cannot create XMLHTTP instance');
	 return false;
  }
  http_req.onreadystatechange = TagPopupContents;
  http_req.open('POST', url, true);
  http_req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  http_req.setRequestHeader("Content-length", parameters.length);
  http_req.setRequestHeader("Connection", "close");
  http_req.send(parameters);
}

function TagPopupContents() 
{
  //alert(http_req.readyState);
  //alert(http_req.responseText);
  if (http_req.readyState == 4) 
  {
	 if (http_req.status == 200) 
	 {
		result = http_req.responseText;
		result = result.trim();
		if(result == "invalid-email")
		{
			alert("Invalid email address.");
			document.getElementById('TagPopup_alertmessage').innerHTML = "Invalid email address.";   
		}
		else if(result == "empty-email")
		{
			alert("Please enter email address.");
			document.getElementById('TagPopup_alertmessage').innerHTML = "Please enter email address.";   
		}
		else if(result == "empty-email")
		{
			alert("Please enter email address.");
			document.getElementById('TagPopup_alertmessage').innerHTML = "Please enter email address.";   
		}
		else if(result == "there-was-problem")
		{
			alert("There was a problem with the request.");
			document.getElementById('TagPopup_alertmessage').innerHTML = "There was a problem with the request.";   
		}
		else if(result == "mail-sent-successfully")
		{
			alert("Mail sent successfully");
			document.getElementById('TagPopup_alertmessage').innerHTML = "Details submitted successfully";   
			document.getElementById("TagPopup_mail").value = "";
			document.getElementById("TagPopup_name").value = "";
			document.getElementById("TagPopup_message").value = "";
		}
		else
		{
			alert("There was a problem with the request.");
			document.getElementById('TagPopup_alertmessage').innerHTML = "There was a problem with the request.";   
		}
	 } 
	 else 
	 {
		alert('There was a problem with the request.');
	 }
  }
}

function TagPopup_Submit(obj, url) 
{
	_e=document.getElementById("TagPopup_mail");
	_n=document.getElementById("TagPopup_name");
	_m=document.getElementById("TagPopup_message");
	_c=document.getElementById("TagPopup_captcha");
	_s=document.getElementById("TagCorrectsum");
	if(_n.value=="")
	{
		alert("Please Enter Your Name.");
		_n.focus();
		return false;    
	}
	else if(_e.value=="")
	{
		alert("Please Enter Your Email.");
		_e.focus();
		return false;    
	}
	else if(_e.value!="" && (_e.value.indexOf("@",0)==-1 || _e.value.indexOf(".",0)==-1))
	{
		alert("Please Enter Valid Email.")
		_e.focus();
		_e.select();
		return false;
	} 
	else if(_m.value=="")
	{
		alert("Please Enter Your Message.");
		_m.focus();
		return false;    
	}
	else if(_c.value=="")
	{
		alert("Please Enter the Captcha.");
		_c.focus();
		return false; 
	}
		
	else if(_c.value!=_s.value)
	{
		alert("Invalid Captcha. Please Try Again.")
		_c.focus();
		return false;
	} 
	
	document.getElementById('TagPopup_alertmessage').innerHTML = "Sending..."; 
	var str = "TagPopup_name=" + encodeURI( document.getElementById("TagPopup_name").value ) + "&TagPopup_mail=" + encodeURI( document.getElementById("TagPopup_mail").value ) + "&TagPopup_message=" + encodeURI( document.getElementById("TagPopup_message").value ) + "&TagPopup_captcha=nocaptcha";
	TagPopupPOSTRequest(url+'/?tagpopup=send-mail', str);
}

