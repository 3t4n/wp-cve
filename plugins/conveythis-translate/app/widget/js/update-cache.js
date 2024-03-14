setTimeout(function(){
    const req = new XMLHttpRequest();
    req.open("POST", window.location, true);
    req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    req.onreadystatechange = async function(result) {
        if (this.readyState === XMLHttpRequest.DONE) {
            if (req.responseText) {
                let result = JSON.parse(req.responseText);
                if (result.success !== true) {
                    console.log('Caching failed', result);
                }else {
                    console.log('Translation cache updated successfully');
                }
            }
            console.log(req.responseText);
        }
    }
    req.send('action=conveythis_update_cache');
}, 500);