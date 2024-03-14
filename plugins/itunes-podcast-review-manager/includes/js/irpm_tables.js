

window.onload = Init;

	
function Init() {
	
	/* SET UP VARIABLES --PHP SHOULD HAVE IDS FOR SOME THINGS (TABLE HEADINGS, BUTTONS, ETC) */
	var iprm_main_table_body = document.getElementById("iprm_main_table_body");
	var sortTableBtn = document.getElementById("sortTableBtn");
	
	var rowsArray  = iprm_main_table_body.rows;
	var rowCount = rowsArray.length;
	
	/* LOAD SELECT BOXES: NOTE FUNCTIONS HAVE SIDE EFFECT OF FILLING PIEDATA AND BAR DATA FOR GOOGLE CHARTS */
	
	loadRatingsSelectBox(iprm_main_table_body);
	

} /* END INIT FUNCTION */

	

function loadRatingsSelectBox(reviewsTable){

	var iprm_review_h2 = document.getElementById("iprm_review_h2");
   /* CREATE ARRAY OF OPTIONS TO BE ADDED */

	var tableRowCount = reviewsTable.rows.length;
	var reviewCount = (tableRowCount - 1); // THERE IS 1 HEADING ROW
	var reviewScore = 0;
	var currentInnerHTML;
	
	/* POPULATE NUMBER OF VALUES FOR EACH OPTION */
	for (var i = 1; i < tableRowCount; i++) { /* USE 1 TO SKIP FIRST ROW (HEADER) */
	
		currentInnerHTML = parseInt(reviewsTable.rows[i].children[3].innerHTML, 10);
		reviewScore += currentInnerHTML;
		
			
    }
	
	
	/* FILL IN THE RATING HEADING WITH AVERAGE*/
	var num = reviewScore / reviewCount;
	iprm_review_h2.innerHTML = reviewCount + " Reviews (" + num.toFixed(2) + " average)";
	
}

   	