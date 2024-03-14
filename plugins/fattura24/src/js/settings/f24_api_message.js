window.addEventListener('load', () => {
    console.log('messaggi :', f24_scripts_data['messages']);
    let apiInput = document.getElementById('fatt-24-API-key'); // non scomodiamo Php
    let apiMessage = f24_scripts_data['messages']['apiText'];
    let apiErrorMsg = f24_scripts_data['messages']['apiTestMsg'];
                                    
    apiInput.addEventListener('focusout', function(e){
        let inputVal = e.target.value;
        let inputLen = inputVal.length;
                
        if (inputLen !== 32 && inputLen !== 0) {
            document.getElementById("fatt-24-api-message").innerHTML = "<span style='color: red; font-size: 120%;'>" + apiErrorMsg[1]  + "</span></br>";
        } else if (inputLen === 0) {
            document.getElementById("fatt-24-api-message").innerHTML = "<span style='color: red; font-size: 120%;'>" + apiErrorMsg[0] + "</span></br>"; 
        } else {
            document.getElementById("fatt-24-api-message").innerHTML = apiMessage;
        }
    });
}); 