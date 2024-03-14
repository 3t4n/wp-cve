window.addEventListener('load', saveAndTestActions);

function saveAndTestActions() {
    let apiBtn = document.getElementById("fatt-24-API-verification");
        apiBtn.addEventListener('click', saveAndTest);
}

function saveAndTest() {
   
    let apiInput = document.getElementById('fatt-24-API-key');
    let apiKey = apiInput.value;
    let testkey_nonce = f24_scripts_data['testkey_nonce'];
    let elements = replaceElements();
    
    $.ajax({
        type: 'POST',
        url: f24_scripts_data['url'],
        data: {
            action: 'test_key',
            apiKey: apiKey,
            nonce: testkey_nonce
        },
        dataType: 'json'    
    }).done(function(r){
        restoreElements(elements);
        handleTest(apiKey);
    }).fail(function(err){
         console.log('error :', err, 'arguments :', arguments);
    })
  
}

/**
 * Sostituisco la scritta con l'icona update cui applico colore e stile personalizzati
 * e aggiungo una scritta - Davide Iandoli 01.02.2023
 */
function replaceElements() {
    let messages = f24_scripts_data['messages'];
    let oldElement = document.getElementById("fatt-24-api-message");
    let newEl = document.createElement('span');
    newEl.className = 'dashicons dashicons-update spin';
    newEl.style = 'color: orange; margin-right: 7px;';
    oldElement.parentNode.replaceChild(newEl, oldElement);
    let newSpan = document.createElement('span');
    newSpan.innerHTML = messages['updating'];
    newEl.parentNode.insertBefore(newSpan, newEl.nextSibling);
    let elements = {
        oldElement: oldElement,
        newEl: newEl,
        newSpan: newSpan
    }
    return elements;
}

/**
 * Ripristino gli elementi precedenti:
 * in questo modo riesco a visualizzare il risultato del test
 * nello stesso blocco - Davide Iandoli 01.02.2023 
 */
function restoreElements(elements) {
    let { oldElement, newEl, newSpan } = elements;
    newEl.parentNode.replaceChild(oldElement, newEl);
    newSpan.remove();
}

function handleTest(apiKey) {
    let apiTestMsg = f24_scripts_data['messages']['apiTestMsg'];
    let planExpiration = f24_scripts_data['messages']['planExpiration'];
    let xhr = new XMLHttpRequest();
    let source = f24_scripts_data['source'];
    let params = "apiKey=" + apiKey + "&source=" + source; // la passa
    let today = new Date();
    xhr.open("post","https://www.app.fattura24.com/api/v0.3/TestKey", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
    xhr.send(params);

    xhr.onload = function () {
        //console.log(this.responseText);
        let response = this.responseText.replace("&egrave;", ""); //devo togliere questa parte altrimenti da problemi con l'encoding xml
        console.log(response);
            
        let parser = new DOMParser(); // legge il risultato xml
        let xml = parser.parseFromString(response, "text/xml");
        let code = xml.getElementsByTagName('returnCode');
        //let description = xml.getElementsByTagName('description');
        let subscriptionType = '';
        if (apiKey !== '' && apiKey.length === 32) {
            let type = xml.getElementsByTagName('type'); // tipo di abbonamento
            let result = [...type];
            if (result.length > 0) {
                subscriptionType = result[0].innerHTML;
            }
        }

        let expiryMessage = '';			
        let expire = xml.getElementsByTagName('expire'); // data di scadenza
        if (expire.length > 0) {
            let year = expire[0].childNodes[0].nodeValue.slice(6, 10);
            // tolgo 1 perché in Js il primo mese è zero
            let month = expire[0].childNodes[0].nodeValue.slice(3, 5) - 1;
            let day = expire[0].childNodes[0].nodeValue.slice(0, 2);
            let expireDate = new Date(year, month, day);
            let diff = expireDate.getTime() - today.getTime();
            let diffInDays = Math.ceil(diff / (1000 * 3600 * 24));
                        
            let expired = diffInDays <= 0; // se la differenza è negativa l'abbonamento è già scaduto
            if (diffInDays < 30 && !expired) {
                let translatedMsg = getTransMsg(diffInDays, planExpiration);
                expiryMessage = "<br/><span style='color: red;'>" + translatedMsg + "</span> <a href='https://www.fattura24.com/' target='_blank'>" + planExpiration[2] + "</a>"; 
            } else if (expired) {
                expiryMessage = "<br/><span style='color: red;'>" + planExpiration[1] + expire[0].childNodes[0].nodeValue  + "</span> <a href='https://www.fattura24.com/' target='_blank'>" + planExpiration[2] + "</a>"; 
            }  
        }

        let totalCallInLast24Hour = xml.getElementsByTagName('totalCallInLast24Hour');
        if (code[0].childNodes[0].nodeValue == 1) {
        /**
        * Qui gestisco gli errori se l'albero xml non è completo
        * in particolare se manca la data di scadenza. Il testo risultante sostituisce quello predefinito
        * grazie alla proprietà innerHtml
        */
            try {  
                   document.getElementById("fatt-24-api-message").innerHTML = "<span style='color: green; font-size: 120%;'>" + apiTestMsg[6] + "</span><br/>"+ planExpiration[0] + expire[0].childNodes[0].nodeValue 
                    + expiryMessage +
                    "<br/>" + apiTestMsg[7] + totalCallInLast24Hour[0].childNodes[0].nodeValue; 
            }
            catch (err) {
                    if (expire.length === 0) {
                            document.getElementById("fatt-24-api-message").innerHTML = "<span style='color: red; font-size: 120%;'>" + apiTestMsg[2]  + "</span></br>";// solo gli account di test sono senza scadenza
                        } else {
                            document.getElementById("fatt-24-api-message").innerHTML = "<span style='color: red; font-size: 120%;'>" + apiTestMsg[3] + "</span></br>";
                        }    
            }
        } else if (!apiKey) {
            document.getElementById("fatt-24-api-message").innerHTML = "<span style='color: red; font-size: 120%;'>" + apiTestMsg[0] + "</span></br>"; 
                        
        } else {
                /**
                * Qui cerco di ottenere il tipo di abbonamento per includere un messaggio di errore specifico
                * se non lo trovo visualizzo il messaggio generico (Davide Iandoli 24.01.2020)
                */
                if (subscriptionType && subscriptionType < 5) { 
                    document.getElementById("fatt-24-api-message").innerHTML = "<span style='color: red; font-size: 120%;'>" + apiTestMsg[4] + "</span></br>";
                } else {
                    document.getElementById("fatt-24-api-message").innerHTML = "<span style='color: red; font-size: 120%;'>" + apiTestMsg[5] + "</span></br>";
                } 
        }
    }        
}

function getTransMsg(diffInDays, planExpiration) {
    diffInDays.toString();
    let result = planExpiration.filter(item => item.includes(diffInDays));
    return result.toString()
}