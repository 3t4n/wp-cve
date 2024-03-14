let $ = jQuery;

// click sul contenitore delle stelle
window.addEventListener('load', () => {
    //let $ = jQuery;
    let reviewContainer = document.getElementById('reviewContainer');
    let reviewBoxArray = ['reviewBox5', 'reviewBox4', 'reviewBox3', 'reviewBox2', 'reviewBox1' ];
    let rating;

    reviewBoxArray.forEach(box => {
        rating = document.getElementById(box);
        //console.log('rating :', rating);
        if (!rating) { return; }
        let ratingValue = box.substring(box.length - 1);
        rating.addEventListener('click', function(){
            //console.log('click eseguito');
            sendReview(ratingValue);
        });
    });

// click sul radio button specifico
    let radioReview = document.getElementById('radioReview');
    let ratingValue = 0;
        
    if (radioReview) {
        ratingValue = radioReview.value;
    }    
        
    if (ratingValue > 0) {          
        radioReview.addEventListener('click', function() {
            //console.log('click eseguito 2');
            sendReview(ratingValue);
        });
    } 
        
    function sendReview(ratingValue) {
        reviewContainer.style.display = 'none';
        let rating_nonce = f24_scripts_data['rating_nonce'];
        let today = new Date();
        let todayFormatted = formatDate(today);
        let xhrRating = new XMLHttpRequest();
        let action = "REV01";
        let source = "WOO";
        let stars = ratingValue;
        let apiKey = f24_scripts_data['apiKey'];
        let params = "action=" + action + "&src=" +  source + "&stars=" + stars
                            + "&apiKey=" + apiKey;
                                    
        xhrRating.open("post","https://www.app.fattura24.com/RatingReview.do", true);
        xhrRating.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
        xhrRating.send(params);
            
        /**
        * Qui invio il risultato al db delle opzioni,
        * il testo verrà è una stringa codificata in json
        */
        xhrRating.onload = function () {
        let optionData = {
            ratingValue: ratingValue,
            lastUpdate: todayFormatted
        };
                
        $.ajax({
                type: 'POST',
                url: f24_scripts_data['url'],
                data: {
                    action: 'hit_stars',
                    value: optionData,
                    nonce: rating_nonce
                },
                dataType: 'json'    
            }).done(function(r){
                if (r[0] == 1) {
                    alert(r[1]);
                }
             }).fail(function(err){
                 console.log('error :', err, 'arguments :', arguments);
             });    
        }
                
    }

    function formatDate(date)
    {
        let d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [day, month, year].join('-');
    }

});