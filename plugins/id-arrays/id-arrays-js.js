// Copies checked checkboxes to clipboard and/or outputs to text input
function ida79GetSlctedChkbxs(chkboxName, arrayDelimiter, copyOption) {
    // Declare variables
    var checkbx = [];
    // Get elements in the html document by name
    var chkboxes = document.getElementsByName(chkboxName);
    // Get the length of the array to prepare loop iterations
    var nr_chkboxes = chkboxes.length;
    // Run the loop for ALL checkboxes with name = chkboxName
    for (var i = 0; i < nr_chkboxes; i++) {
        // Add elements in the array only if the checkboxes are checked
        if (chkboxes[i].type == 'checkbox' && chkboxes[i].checked == true) checkbx.push(chkboxes[i].value);
    }
    // Ensure the array is an array of strings
    checkbx.toString();
    console.log(checkbx);
    // Add the delimiter after each array element
    checkbxstr = checkbx.join(arrayDelimiter);
    // Run If statement depending on user option
    if (copyOption == 0) {
        // Output the array into the textarea
        document.getElementById("ida79Textbox").value = checkbxstr;
        // Select the textarea
        document.getElementById("ida79Textbox").select();
        // Copy its contents
        document.execCommand("copy");
    } else {
        // Create a dummy input to copy the string array inside it
        var dummy = document.createElement("input");
        // Add it to the document
        document.body.appendChild(dummy);
        // Set its ID
        dummy.setAttribute("id", "dummy_id");
        // Output the array into it
        document.getElementById("dummy_id").value = checkbxstr;
        // Select it
        dummy.select();
        // Copy its contents
        document.execCommand("copy");
        // Remove it as its not needed anymore
        document.body.removeChild(dummy);
    }
}