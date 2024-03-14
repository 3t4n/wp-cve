// getting the Display integration
var wpgsiDisplayId = document.currentScript.getAttribute('wpgsiDisplayid');
// getting the table data
var wpgsiTableData = JSON.parse( atob(document.currentScript.getAttribute('wpgsiTabledata')));
// number of row per page 
var showNumberOfRows = document.currentScript.getAttribute('wpgsiShownumberofrows');
//  if div is present 
if(document.getElementById( "wpgsiFrontend" + wpgsiDisplayId )){
    var showApp = new Vue({
        el: "#wpgsiFrontend" + wpgsiDisplayId,
        data: {
            googleSheetDataForTableRender: "",          // just set of data for table view render purpose
            googleSheetData: "",                        // all the data of the google sheet except first row 
            googleSheetTitles: "",                      // First row of the google sheet 
            // 
            searchField:"",                             // this is a table search field
            currentPage: 1,                             // For pagination purpose
            showNumberOfRows: "",                       // How many row will show at a time in a table view 
            ascendingDescendingStatus: "ascending"      // sorting default value
        },
        watch: {
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
            movePrevious(){
                //  console.log( this.currentPage );
                //  console.log( showApp.currentPage );
                //  Decrement the current page 
                if( this.currentPage != 1 ){
                    this.currentPage  =  -- this.currentPage;
                }
                // Separating paginated values
                showApp.googleSheetDataForTableRender = showApp.googleSheetData.slice( (showApp.currentPage - 1) * showApp.showNumberOfRows, showApp.currentPage * showApp.showNumberOfRows );
            },
            moveNext(){
                console.log( this.currentPage );
                console.log( showApp.currentPage );
                // 
                let totalNumberOfPage =  Math.ceil( this.googleSheetData.length / this.showNumberOfRows ); 
                //  Increment current page
                if( this.currentPage != totalNumberOfPage ){
                    this.currentPage  = ++ this.currentPage;
                }
                // Separating paginated values
                showApp.googleSheetDataForTableRender = showApp.googleSheetData.slice( (showApp.currentPage - 1) * showApp.showNumberOfRows, showApp.currentPage * showApp.showNumberOfRows );
            },
            navText(){
                return "Showing <b> " + ( this.currentPage - 1 ) * this.showNumberOfRows  + " </b> to <b> " +  this.currentPage * this.showNumberOfRows  + " </b> of <b> " + this.googleSheetData.length + " </b> entries";
            }
        },
        mounted: function(){
            //  console.log(wpgsiDisplayId);
            //  console.log(wpgsiTableData);
            //  choking the validity and not empty
            if ( wpgsiTableData &&  Array.isArray(wpgsiTableData) ) {
                // removing first array of the associative array 
                this.googleSheetTitles = wpgsiTableData.shift();
                // this is a copy of googleSheetData || this will act as local database 
                this.googleSheetData = wpgsiTableData;
                // Setting showNumberOfRows variable 
                if(showNumberOfRows){
                    this.showNumberOfRows = showNumberOfRows;
                    // inserting data to the googleSheetData, This will parse the table 
                    this.googleSheetDataForTableRender = wpgsiTableData.slice(0, showNumberOfRows);
                } else {
                    this.showNumberOfRows = 25;
                    // inserting data to the googleSheetData, This will parse the table 
                    this.googleSheetDataForTableRender = wpgsiTableData.slice(0, 25);
                }
            } else {
                console.log("ERROR: wpgsi frontend json pershing error." );
            }
        }
    });
};