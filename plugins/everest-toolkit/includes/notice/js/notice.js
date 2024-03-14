var offerHeadingClass = document.getElementById( "et-offer-heading" );
var offerPercentClass = document.getElementById( "et-offer-percent" );
var discountCodeClass = document.getElementById( "et-discount-code" );

var ourRequest = new XMLHttpRequest();

ourRequest.open( 'GET', noticeObj.siteURL + 'wp-json/wp/v2/notice?per_page=1&order=desc' );

ourRequest.onload = function() {

    if( ourRequest.status >= 200 && ourRequest.status < 400 ) {

        var data = JSON.parse( ourRequest.responseText );

        createHTML( data );

    } else {

        console.log( "We connected to server, but it returned an error." );
    }

};

ourRequest.onerror = function() {

    console.log( "Connecion error" );

};

ourRequest.send();

function createHTML( pagesData ) {

    var offerHeadingString = '';
    var offerPercentString = '';
    var discountCode = '';
    var discountCodeString = '';

    for( i = 0; i < pagesData.length; i++ ) {

        offerHeadingString+= pagesData[i].title.rendered;
        offerPercentString+= pagesData[i].discount_title;
        discountCode+= pagesData[i].discount_code;
        discountCodeString+= 'Use Discount Coupon Code : <code>' + discountCode + '</code> to grab this offer.';

        //ourHTMLString += pagesData[i].slug;

    }

    offerHeadingClass.innerText = offerHeadingString;
    offerPercentClass.innerText = offerPercentString;

    if( discountCode ) {

        discountCodeClass.innerHTML = discountCodeString;

    }

}
