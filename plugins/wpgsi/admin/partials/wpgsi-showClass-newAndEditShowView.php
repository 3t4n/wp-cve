<div class="wrap" >
	<div id="icon-options-general" class="icon32"> </div>
	<h1> <?php esc_attr_e( "show Google sheet on Page & Page.", "wpgsi" ); ?> </h1>

    <div id='wpgsi-show'> 
        <!-- <pre> {{$data}} </pre> -->
        <!-- <pre> {{disableColumns}} </pre> -->
        <br>
        <!-- settings Starts starts -->
        <table class="widefat">
            <tbody>
                <tr class="" >
                    <td class="row-title">
                        <label for="tablecell"> <?php esc_attr_e('New Settings', 'wpgsi');?> </label>
                    </td>
                    <td id="gData">
                        <form name="googleSheetShowSettings" method="POST" name='settingsForm' action="<?php echo esc_url(admin_url('admin-post.php')); ?>" > 
                            <!-- Form Submission  Fields Starts -->
                            <input type="hidden" name="action"          value="save_google_show">
                            <input type="hidden" name="nonce"           value="<?php echo wp_create_nonce('wpgsi-googleShow-nonce'); ?>"> 
                            <input type="hidden" name="editID"          value="<?php echo $id ?>"> 

                            <input type="hidden" name="showNumberOfRows":value="showNumberOfRows"> 
                            <input type="hidden" name="spreadsheetID"   :value="spreadsheetID"> 
                            <input type="hidden" name="spreadsheetName" :value="spreadsheetName"> 
                            <input type="hidden" name="worksheetID"     :value="worksheetID"> 
                            <input type="hidden" name="worksheetName"   :value="worksheetName"> 
                            <!-- select the google sheet from dropdown  -->
                            <select name='googleSpreadsheetAndWorksheet' v-model="selectedSpreadSheetWorkSheet" id='googleSpreadsheetAndWorksheet'>
                                <option value=""><?php _e('Select your Google sheet ...', 'wpgsi');?></option>
                                <option :value="value" v-for="(name,value, index) in spreadSheetWorkSheets"> {{name}} </option>
                            </select>  &nbsp;
                            <!--  select dropdown sync  -->
                            <?php
                                echo"<select name='syncFrequency' v-model='syncFrequency'  id='syncFrequencyID'> ";
                                    foreach($this->syncFrequency as $key => $value) {
                                        echo"<option value='".$key."'>".$value."</option>";
                                    }
                                echo"</select> &nbsp;";
                            ?>
                            <!--   hidden select start  -->
                            <input type="hidden" name="disableColumns" :value="disableColumns" />
                            <!--   hidden select ends  -->
                            <!--   Save Settings button  -->
                            <button type="submit" class='button-secondary'>  
                                <span style='padding-top:3px;' class="dashicons dashicons-saved"></span> Save Settings 
                            </button>  &nbsp; 
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>   
        <!-- settings Starts ends -->
        
        <br class="clear"/>

        <!--  Display Table starts  v-show="googleSheetData.length" -->
        <p  v-show="googleSheetData.length"><strong>Google sheet Table view</strong></p>
       
        <table class="widefat" id='wpgsiDataTable'  v-show="googleSheetData.length">
            <!-- Search bar -->
            <thead id='wpgsiTableHeaderOne'>
                <tr id='wpgsiTableHeaderSearchBar'>
                    <td v-bind:colspan="googleSheetTitles.length" title='Select the number, it will be the default number of rows displayed in the table.'> 
                        <span>
                            <select id='showNumberOfRowsDropdown' v-model="showNumberOfRows">
                                <option value="5">    5    </option>
                                <option value="10">   10   </option>
                                <option value="25">   25   </option>
                                <option value="50">   50   </option>
                                <option value="75">   75   </option>
                                <option value="100">  100  </option>
                            </select>
                        </span>
                        <span style='float:right;'>
                            Search: <input type='text' v-model="searchField" id='searchFieldID'>  
                        </span>
                    </td>
                </tr>
            </thead>
            <!-- Real Header -->
            <thead id='wpgsiTableHeaderTwo'>
                <tr id='wpgsiTableTitleTr'>
                    <td title='Double-click to disable the display of this column. SORRY ! not is in the Free version.' v-for="(titleItem, columnIndex) in googleSheetTitles" @click="sortingTableRowsByColumn(columnIndex)" @dblclick="disableColumnToDisplay(columnIndex)" :class="[disableColumns.includes(columnIndex) ? cssClass : '']">
                        <strong> {{titleItem}} </strong>
                    </td>
                </tr>
            </thead>
            <!-- Table body -->
            <tbody id='wpgsiTableBody'>
                <tr v-for="(row, index) in googleSheetDataForTableRender" :class=" index % 2 ? '' : 'alternate' ">
                    <td v-for="(item, tdIndex) in row" v-html="item"></td>
                </tr>
            </tbody>
            <!-- next and Previous bar  -->
            <tfoot id='wpgsiTableFooterTwo'>
                <tr id='wpgsiTableFooterNavigationBar'>
                    <td v-bind:colspan="googleSheetTitles.length"> 
                        <span>  Showing <b> {{ (currentPage - 1) * showNumberOfRows }} </b> to <b> {{ currentPage * showNumberOfRows  }} </b> of <b> {{ googleSheetData.length }} </b> entries </span>
                        <span style='float:right;'> 
                            <a href='#' id='movePrevious' @click="movePrevious()"> <span class='dashicons dashicons-arrow-left-alt2'></span> Previous </a>
                            |
                            <a href='#' id='moveNext' @click="moveNext()"> Next <span class='dashicons dashicons-arrow-right-alt2'></span> </a>
                        </span>
                    </td>
                </tr>
            </tfoot>
            <!-- footer title bar -->
            <tfoot id='wpgsiTableFooterOne'>
                <tr id='wpgsiTableFooterTr'>
                    <td v-for="titleItem in googleSheetTitles"> <strong>  {{titleItem}}  </strong> </td>
                </tr>
            </tfoot>
        </table>
        <!--  Display Table Ends -->
    </div>
</div> 
 <!-- table style -->
<style>
    .cssClass{
        background-color: #3B3C36;
        color: white !important;
    }
</style>
