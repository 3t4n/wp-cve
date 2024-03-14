function onCheqResponse(encryptedMessage){
    //code which sends the message to customer's backend for description
    let request = new XMLHttpRequest();
    request.open('POST', ajax_obj.ajax_url, true);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.send('action='+ajax_obj.ajax_action+'&security='+ajax_obj.cc_nonce+'&cheq_hash='+encryptedMessage);
    request.onreadystatechange = function() {
        if (request.readyState === 4) {
            if (request.status === 200) {
                console.log(request.responseText);
                let res = JSON.parse(request.responseText).message;
                performAction(res.action);
            } else {
                console.log('Error: ' + request.status);
            }
        }
    }
}

function performAction(action) {
    if (action == "blockuser") {
        document.querySelector("html").innerHTML = "";
        document.location.href = addGetParameters([{"name": "clickcease","value": "block"}]);
    } else if (action == "clearhtml") {
        document.querySelector("html").innerHTML = "";
        document.location.href = addGetParameters([{"name": "clickcease","value": "clearhtml"}]);
    }
}

function addGetParameters(parameters, new_url=window.location.href) {
    parameters.forEach(function (parameter) {
        if (new_url.includes("?")) {
            new_url += "&"+parameter.name+"="+parameter.value;
        } else {
            new_url += "?"+parameter.name+"="+parameter.value;
        }
    })
    return new_url;
}

function findGetParameter(parameter) {
    let url = window.location.href;

    if (url.includes("?"+parameter) || !url.includes("?"+parameter)) {

    } else {
        return;
    }

    let parameter_index = url.indexOf(parameter+"=")+parameter.length+1;
    let next_parameter_index = url.indexOf("&", parameter_index);
    if ( next_parameter_index > 0) {
        return url.substring(parameter_index, next_parameter_index);
    } else {
        return url.substring(parameter_index);
    }
}

function editAllInternalLinks(value) {
  const domain = window.location.hostname;
  const links = document.getElementsByTagName('a');
    for (let i = 0; i < links.length; i++) {
      const link_url = links[i].getAttribute('href');
      if (link_url.includes(domain)) {
        links[i].setAttribute('href', link_url+'?clickcease='+value);
      }
    }
}