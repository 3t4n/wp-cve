if(document.getElementById("wpgsi-show")){
    var showApp = new Vue({
        el: '#wpgsi-show',
        data: {
            spreadSheetWorkSheets: "",                  // list of worksheet and spreadsheet 
            googleSheetsDetails: "",                    
            spreadsheetID:"",                           // selected spreadsheet ID
            spreadsheetName:"",                         // selected spreadsheet Name
            worksheetID:"",                             // selected worksheet ID
            worksheetName:"",                           // selected worksheet name
            selectedSpreadSheetWorkSheet: "",           // base64 encoded data || this is a Dropdown select value 
            // 
            googleSheetDataForTableRender: "",          // just set of data for table view render purpose
            googleSheetData: "",                        // all the data of the google sheet except first row 
            googleSheetTitles: "",                      // First row of the google sheet 
            syncFrequency: "manually",                  // default value
            // 
            searchField:"",                             // this is a table search field
            currentPage: 1,                             // For pagination purpose
            showNumberOfRows: 25,                       // How many row will show at a time in a table view 
            ascendingDescendingStatus: "ascending",     // sorting default value
            disableColumns: [],                          // disableColumns
            cssClass:'cssClass',
        },
        watch: {
            selectedSpreadSheetWorkSheet(newData, oldData) {
                //  If new data is empty or null return 0
                if( newData.length == 0){
                    return 0;
                }
                // newData is a base64 encoded value 
                let base64decode = JSON.parse(atob(newData));
                // setting spreadsheet ID
                this.spreadsheetID = base64decode[0];
                // setting spreadsheet name
                this.spreadsheetName = this.googleSheetsDetails[ base64decode[0] ][0];
                // setting worksheet ID
                this.worksheetID = base64decode[1];
                // setting worksheet name
                this.worksheetName = this.googleSheetsDetails[ base64decode[0] ][1][base64decode[1]];
                // Emptying the googleSheetDataForTableRender
                showApp.googleSheetDataForTableRender = "";
                // Emptying the googleSheetData
                showApp.googleSheetData = "";
                // Empty check 
                if( newData.length ){
                    showApp.googleSheetDataForTableRender = "";
                    //  Creating data 
                    let requestData = {
                        action: "wpgsi_ajaxWorksheetData",
                        spreadsheetID: base64decode[0],
                        worksheetID  : base64decode[1],
                        worksheetName: this.worksheetName,
                        nonce: showData.nonce
                    };
                    // Initiating AJAX request to the server 
                    fetch(showData.wpgsiAJAXurl, {
                        method:"post",
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: new URLSearchParams(requestData).toString(),
                    }).then(function(response) {
                        return response.json();
                    }).then(function(response) {
                        //  Check and balance 
                        if(! response[0]){
                            console.log( response );
                        } else {
                            // removing first array of the associative array 
                            showApp.googleSheetTitles = response[1].shift();
                            // this is a copy of googleSheetData || this will act as local database 
                            showApp.googleSheetData = response[1];
                            // inserting data to the googleSheetData, This will parse the table 
                            showApp.googleSheetDataForTableRender = response[1].slice(0, showApp.showNumberOfRows);
                        }
                        // Deleting the disableColumns
                        this.disableColumns = ""; 
                    });
                }
            },
            searchField(newData, oldData){
                if(newData.length > 0){
                    let listOfContainingRow = [];
                    // Sorting the array to check the ting exist or not 
                    for (let listIndex = 0; listIndex <  showApp.googleSheetData.length; listIndex++) {
                        // looping the row
                        for (let rowIndex = 0; rowIndex < showApp.googleSheetData[listIndex].length; rowIndex++) {
                            // if search item length is grater than 0 && value consists the search item
                            if(showApp.googleSheetData[listIndex][rowIndex].length > 0  &&  showApp.googleSheetData[listIndex][rowIndex].toString().toLowerCase().includes(newData.toString().toLowerCase())){
                                // insert the item to the listOfContainingRow
                                listOfContainingRow.push(showApp.googleSheetData[listIndex]);
                            }
                        }
                    }
                    // setting the value 
                    showApp.googleSheetDataForTableRender = listOfContainingRow;
                } else {
                    // search item length is less than 0 | so 
                    showApp.googleSheetDataForTableRender = showApp.googleSheetData.slice( (showApp.currentPage - 1) * showApp.showNumberOfRows, showApp.currentPage * showApp.showNumberOfRows );
                }
            },
            showNumberOfRows(newData, oldData){
                showApp.googleSheetDataForTableRender = showApp.googleSheetData.slice(0, newData);
            }
        },
        methods: {
            sortingTableRowsByColumn(columnID){
                columnID = parseInt(columnID);
                // sorting by column
                showApp.googleSheetData.sort(function(a, b){
                    let valueA, valueB;   // defining the value 
                    valueA = a[columnID]; // Where 1 is your index, from your example
                    valueB = b[columnID];
                    // If ascending selected then showApp part will work
                    if( showApp.ascendingDescendingStatus === "ascending" ){
                        if (valueA < valueB) {
                            return 1;
                        } else if (valueA > valueB) {
                            return -1;
                        } else {
                            return 0;
                        }
                    } 
                    // If descending selected then showApp part will work
                    if( showApp.ascendingDescendingStatus === "descending" ){
                        if (valueA < valueB) {
                            return -1;
                        } else if (valueA > valueB) {
                            return 1;
                        } else {
                            return 0;
                        }
                    }
                });
                // Separating paginated values
                showApp.googleSheetDataForTableRender = showApp.googleSheetData.slice( (showApp.currentPage - 1) * showApp.showNumberOfRows, showApp.currentPage * showApp.showNumberOfRows );
                //  Setting ascending and descending status
                if( showApp.ascendingDescendingStatus == "ascending"){
                    showApp.ascendingDescendingStatus = "descending";
                } else {
                    showApp.ascendingDescendingStatus = "ascending";
                }
            },
            disableColumnToDisplay(columnID){
                // 
                if(showApp.disableColumns.includes(columnID)){
                    let updateDisableColumns = showApp.disableColumns.filter(x => x !== columnID);
                    showApp.disableColumns = updateDisableColumns;
                } else {
                    showApp.disableColumns.push( columnID );
                }
            },
            movePrevious(){
                //  Decrement the current page 
                if( showApp.currentPage != 1 ){
                    showApp.currentPage  =  -- showApp.currentPage;
                }
                // Separating paginated values
                showApp.googleSheetDataForTableRender = showApp.googleSheetData.slice( (showApp.currentPage - 1) * showApp.showNumberOfRows, showApp.currentPage * showApp.showNumberOfRows );
            },
            moveNext(){
                let totalNumberOfPage =  Math.ceil( showApp.googleSheetData.length / showApp.showNumberOfRows ); 
                //  Increment current page
                if( showApp.currentPage != totalNumberOfPage ){
                    showApp.currentPage  = ++ showApp.currentPage;
                }
                // Separating paginated values
                showApp.googleSheetDataForTableRender = showApp.googleSheetData.slice( (showApp.currentPage - 1) * showApp.showNumberOfRows, showApp.currentPage * showApp.showNumberOfRows );
            }
        },
        mounted: function(){
            // Getting the data and parsing that 
            let googleSheetsDetails = JSON.parse( showData.googleSheetsDetails );
            // Check and Balance
            if (googleSheetsDetails && typeof googleSheetsDetails === 'object' && !Array.isArray(googleSheetsDetails)) {
                // Inserting data to the main data holder 
                this.googleSheetsDetails = googleSheetsDetails;
                // spreadSheet and WorkSheets holder 
                let spreadSheetWorkSheets = {};
                // Looping data 
                for (let spreadsheetID in googleSheetsDetails) {
                    for (let worksheetID in  googleSheetsDetails[spreadsheetID][1]) {
                        let tmpHolder = [];
                        tmpHolder.push(spreadsheetID);
                        tmpHolder.push(worksheetID);
                        spreadSheetWorkSheets[ btoa(JSON.stringify(tmpHolder)) ] = googleSheetsDetails[spreadsheetID][0] + " ⯈ " + googleSheetsDetails[spreadsheetID][1][worksheetID];
                    }
                }
                // Inserting data to the main data holder 
                this.spreadSheetWorkSheets = spreadSheetWorkSheets;
            }

            // insert selectedSpreadSheetWorkSheet if this is a edit
            if(showData.selectedSpreadSheetWorkSheet){
                this.selectedSpreadSheetWorkSheet = showData.selectedSpreadSheetWorkSheet;
            }
            // insert showNumberOfRows if this is a edit
            if(showData.showNumberOfRows){
                this.showNumberOfRows = showData.showNumberOfRows;
            }
            // insert syncFrequency if this is a edit
            if(showData.syncFrequency){
                this.syncFrequency = showData.syncFrequency;   
            }
            // insert disableColumns if this is a edit
            if(showData.disableColumns){
                let disableColumns =  JSON.parse(JSON.stringify(showData.disableColumns));
                for (id in disableColumns) {
                    this.disableColumns.push(parseInt(disableColumns[id]));
                }
            }
        }
    });
}