/* 
    Appends deactivation feedback form to body
    
*/
const WPHINDI_FORM_HTML = 
`<form id='wphindi-deactivate-form' class='modal' action='https://feedback-wphindi.zozuk.com/submit-feedback'>
<h1>Deactivate WPHindi</h1>
<hr>
<div id='wphindi-feedback'>
    <p style='font-size:1.2em'>
    अगर आप किसी प्रॉब्लम की वजह से प्लगइन अनइंस्टाल कर रहे हैं तो आप हमें अपना फ़ोन नंबर या ईमेल आईडी दें। हम आपसे संपर्क करेंगे व आपकी प्रॉब्लम ठीक करने में पूरी सहायता करेंगे।  आप हमसे नीचे दिए बटन से चैट भी कर सकते हैं।
    </p>
    <div id='wphindi-errors-area'>
        
    </div>
    <div id='wphindi-deactivation-form-area'>
        <div id='wphindi-chat-section'>
            <div>
                <a href='https://tawk.to/wphindi' target='_blank' class='button button-primary' id='wphindi-chat-button'>अभी चैट करें</a>
            </div>
            <span>Online </span>
            <a href='#' id='wphindi-skip-deactivate'>Skip</a>
        </div>
        <div>
            <div>
                <label for='wphindi-name'>Name</label>
                <br>
                <input type='text' name='name' id='wphindi-name' class='text'>
            </div>
            <div>
                <label for='wphindi-name'>Phone  / Email
                <br>
                Format : +91-XXX-XXX-XXX
                </label>
                <br>
                <input type='text' name='contact' id='wphindi-contact' class='text'>
                
            </div>
            <div>
                <label for='wphindi-site'>Site</label> 
                <br>
                <input type='url' name='siteURL' id='wphindi-site' class='text' readonly>
            </div>
            <div>
                <button type='submit' class='button action' id='wphindi-feedback-deactivate'>Submit & Deactivate</button>
            </div>
        </div>
</div>
</form>
`;

jQuery(WPHINDI_FORM_HTML).appendTo('body');