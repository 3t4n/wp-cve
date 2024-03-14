jQuery( document ).ready(function(){

    var a = document.querySelectorAll( 'a' );
    var button = document.querySelectorAll( 'button' );
    var input = document.querySelectorAll( 'input[type="submit"]' );

    // generates CSS classes to display the cursor.
    document.body.classList.add( 'cursor-customizer103', 'cursor-customizer104', 'cursor-customizer105', 'cursor-customizer106' );

    var btnCount = button.length;
    for( var i = 0; i < btnCount; i++ ) {
        button[ i ].classList.add( 'cursor-customizer103', 'cursor-customizer104', 'cursor-customizer105', 'cursor-customizer106' );
    }

    var inptCount = input.length;
    for( var j = 0; j < inptCount; j++ ) {
        input[ j ].classList.add( 'cursor-customizer103', 'cursor-customizer104', 'cursor-customizer105', 'cursor-customizer106' );
    }

    var linksCount = a.length;
    for( var k = 0; k < linksCount; k++ ) {
        a[ k ].classList.add( 'cursor-customizer103', 'cursor-customizer104', 'cursor-customizer105', 'cursor-customizer106' );
    }

});





