<style>
body{
    font-family: Times New Roman !important;
}
a.nav-tab.revoke-access{
    display: block !important;
}
.card{
    max-width: 100% !important;
    margin-top: 0px !important;

}
.mailer-section .mailer-title{
    padding: 20px 0 10px 0 !important;
}
input[type="radio"]:checked + .mailer-label::after{
    height: 60px !important;
    width: 60px !important;
}
.form-label{
    
    font-size: 16px !important;
}
.nav-tabs .nav-link{
    margin-bottom: -7px !important;
}
.form-control{
    padding: 3px 15px !important;
    line-height: 1.8 !important;
}
.nav-link{
    font-size: 14px;
    font-family: Open-Sans !important;
    font-weight: 600;
    color: #aaa8a8;
}
.nav-item .active{
    font-weight: 600;
    font-family: Open-Sans !important;
    color: #515151;
}
.nav-item:hover,.nav-link:hover,.nav-link:active,.nav-link:link{
    color: #515151;
}
.update-nag {
    padding: 10px 0px !important;
    margin: 0px !important;
}
.success{

    background: white;

    border-left: 3px solid #84f084;

    padding: 8px 20px;

    width: 32%;

    display: block;

}

.failed{

    background: white;

    border-left: 3px solid red;

    padding: 8px 20px;

    width: 32%;

    display: block;

}
.failed-log{

    background:white;

    border-left: 3px solid red;

    padding: 20px 20px;

    width: 70%;

    position: relative;

    left: 7px;

    font-size: 13px;

    line-height: 23px;

}
.wrap div.updated{

    padding: 8px;

    position: relative;

    top: 8px;

    left: 4px;

    margin-right: 20px !important;

}
.mailerwrap input{                                        

    -webkit-appearance:none !important;

    -moz-appearance:none !important;

    appearance:none !important;

    border: #F0F0F1 !important;

    border-radius: 0px !important;

    background: #F0F0F1 !important;

    color: #F0F0F1 !important;

    clear: none !important;

    cursor: auto !important;

    display: inline-block !important;

    line-height: 0 !important;

    height: inherit !important;

    margin: 0px !important;

    outline: 0 !important;

    padding: 0 !important;

    text-align: center !important;

    vertical-align: unset !important;

    width: 0px !important;

    min-width: 1rem !important;

    -webkit-appearance: none !important;

    box-shadow: none !important;

    transition: none !important;

    visibility: hidden;

    position: absolute;

}
.mailerwrap input:active +.mailerlabel{opacity: .9;}

.mailerwrap input:checked +.mailerlabel{

    -webkit-filter: none;

    -moz-filter: none;

            filter: none;

}

.mailerlabel{

    cursor:pointer;

    background-size:contain;

    background-repeat:no-repeat;

    display:inline-block;

    width:100px;height:70px;

    -webkit-transition: all 100ms ease-in;

    -moz-transition: all 100ms ease-in;

            transition: all 100ms ease-in;

    -webkit-filter: brightness(1.8) grayscale(1) opacity(.7);

    -moz-filter: brightness(1.8) grayscale(1) opacity(.7);

            filter: brightness(1.8) grayscale(1) opacity(.7);

}

.mailerlabel:hover{

    -webkit-filter: brightness(1.2) grayscale(.5) opacity(.9);

    -moz-filter: brightness(1.2) grayscale(.5) opacity(.9);

            filter: brightness(1.2) grayscale(.5) opacity(.9);

}

.labelcontainer{

    padding: 10px;background: white;width: auto;float: left; margin:10px;

}          



.mailer-title {

    color: rgba(24, 25, 27, 1) !important; 

    margin-bottom: 1rem !important;

    margin-top: 1rem !important;

}

input[type="radio"]:checked + .mailer-title {

    color: white !important; 

}

section {

    display: flex !important;

    flex-flow: row wrap !important;

}

section > div {

    flex: 1 !important;

    padding: 0.5rem !important;

}

input[type="radio"] {

    display: none !important;

}

input[type="radio"]:not(:disabled) ~ .mailer-label {

    cursor: pointer !important;

}

.mailer-label {

    height: 100% !important;

    display: block !important;

    background: white !important;

    border: 2px solid rgba(32, 223, 128, 1) !important;

    border-radius: 20px !important;

    padding: 1rem !important;

    margin-bottom: 1rem !important;

    text-align: center !important;

    box-shadow: 0px 3px 10px -2px rgba(161, 170, 166, 0.5) !important;

    position: relative !important;

}

input[type="radio"]:checked + .mailer-label {

    background: rgba(32, 223, 128, 1) !important;

    color: rgba(255, 255, 255, 1) !important;

    box-shadow: 0px 0px 20px rgba(0, 255, 128, 0.75);

}

input[type="radio"]:checked + .mailer-label::after {

    border: 2px solid rgba(29, 201, 115, 1);

    content: "\f00c";

    font-size: 0px;

    position: absolute;

    top: -25px;

    left: 50%;

    transform: translateX(-50%);

    height: 50px;

    width: 50px;

    line-height: 50px;

    text-align: center;

    border-radius: 50%;

    box-shadow: 0px 2px 5px -2px rgba(0, 0, 0, 0.25);

    background-size: contain !important;

}
@media only screen and (max-width: 700px) {

    section {

        flex-direction: column;

    }

}



/* The Radio container */

.radio-container {

display: block;

position: relative;

padding-left: 35px;

margin-bottom: 12px;

cursor: pointer;

font-size: 19px;

-webkit-user-select: none;

-moz-user-select: none;

-ms-user-select: none;

user-select: none;

}



/* Hide the browser's default radio button */

.radio-container input {

position: absolute;

opacity: 0;

cursor: pointer;

}



/* Create a custom radio button */

.checkmark {

position: absolute;

top: 0;

left: 0;

height: 27px;

width: 27px;

background-color: white;

border-radius: 50%;

border:1px solid #ccc;

}



/* On mouse-over, add a grey background color */

.radio-container:hover input ~ .checkmark {

background-color: #ccc;

}



/* When the radio button is checked, add a blue background */

.radio-container input:checked ~ .checkmark {

background-color: #2196F3;

}



/* Create the indicator (the dot/circle - hidden when not checked) */

.checkmark:after {

content: "";

position: absolute;

display: none;

}



/* Show the indicator (dot/circle) when checked */

.radio-container input:checked ~ .checkmark:after {

display: block;

}



/* Style the indicator (dot/circle) */

.radio-container .checkmark:after {

    top: 9px;

    left: 9px;

    width: 8px;

    height: 8px;

    border-radius: 50%;

    background: white;

}
.alert-success{
    height: 40px;
    line-height: 0.7;
    margin-top: 10px;
}
</style>