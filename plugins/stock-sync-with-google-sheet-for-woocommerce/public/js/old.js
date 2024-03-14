const baseURL = "{site_url}",
  accessToken = "{token}",
  sheetTab = "{sheet_tab}";

  function RunSSGSW() {
    if (!ssgsw_current_sheet()) return;
    ssgsw_add_menus();
    ssgsw_apply_styles();
  }
  
  function onOpen() {
   RunSSGSW();
  }
  
  function ssgsw_current_sheet() {
    return SpreadsheetApp.getActiveSheet().getSheetName() == sheetTab;
  }

  function onEdit(e) {
    if ( e == null || e == 'undefined' || e == '') return;
    if (!ssgsw_current_sheet() || ssgsw_current_row() == 1) return;
    const currentColumn = ssgsw_current_column();
    var ssgs_ui = SpreadsheetApp.getUi();
    if (["ID", "type", "sales", "category", "attributes"].includes(currentColumn)) {
      ssgs_ui.alert("This column is not editable");
      ssgsw_fetch_from_WordPress('manual');
      return;
    }
    ssgsw_toast2('Updating data... Please wait.','Loading!');
    let data = ssgsw_get_edited_data(e);
    let key_value = ssgsw_columns(currentColumn, true);
    if (typeof key_value === "undefined") {
      key_value = currentColumn;
    }
    let message = key_value + " updated successfully!";
    if (data[0]['ID'] == "") {
      message = "Product created successfully!";
    }
  
    ssgsw_apply_styles();
    ssgsw_sync_data(data, message,e);
  }
  function ssgsw_add_menus() {
    SpreadsheetApp.getUi()
      .createMenu("Stock Sync")
      .addItem("⟱ Fetch from WooCommerce", "ssgsw_fetch_from_WordPress")
      .addItem("⟰ Sync on WooCommerce", "ssgsw_sync_all")
      .addSeparator()
      .addItem(" Format Styles", "ssgsw_apply_styles")
      .addItem(" About Stock Sync", "ssgsw_about_us")
      .addToUi();
  }
  
  function ssgsw_apply_styles() {
    const Columns = ssgsw_column_char(ssgsw_max_column());
    const Headers = "A1:" + Columns + 1;
    const StaticColumns = ssgsw_column_index(["ID", "type", "sales", "attributes", "category"])
      .filter((index) => index >= 0)
      .map(ssgsw_column_char);
      Logger.log(StaticColumns);
    const StockColumn = ssgsw_column_index(["stock"]).map((index) =>
      ssgsw_column_char(index)
    )[0];
  
  
    const StaticColumnHeaders = StaticColumns.map((column) => column + 1);
    const StaticColumnValues = StaticColumns.map((column) => column + 2 + ":" + column);
  
    const StockColumnValues = StockColumn + 2 + ":" + StockColumn;
  
    const CenterableColumns = ssgsw_column_index([
      "stock",
      "regular_price",
      "sale_price",
      "sales",
    ])
      .filter((index) => index >= 0)
      .map((char) => ssgsw_column_char(char) + "1:" + ssgsw_column_char(char));
  
    const Color = {
      primary: "#686de0",
      white: "white",
      black: "black",
      grey: "#dedede",
      success: "green",
      error: "indianred",
      info: "purple",
      warning: "orange",
    };
  
    const CurrentSheet = SpreadsheetApp.getActive().getSheetByName(sheetTab);
  
    CurrentSheet.getRange("A1:Z1")
      .setFontWeight("normal")
      .setBackground(Color.white)
      .setFontColor(Color.black);

    CurrentSheet.getRange(Headers)
      .setFontWeight("bold")
      .setBackground(Color.primary)
      .setFontColor(Color.white);

    CurrentSheet.autoResizeColumns(1, ssgsw_max_column());

    CurrentSheet.getRangeList(StaticColumnHeaders).setBackground(Color.error);

    CurrentSheet.getRangeList(StaticColumnValues)
      .setBackground(Color.grey)
      .setFontColor(Color.black);

    CurrentSheet.getRangeList(CenterableColumns).setHorizontalAlignment("center");
  
  
    let rules = [];
  
    rules.push(SpreadsheetApp.newConditionalFormatRule()
      .whenTextEqualTo("In Stock")
      .setBackground("#f7fff9")
      .setFontColor("green")
      .setRanges([SpreadsheetApp.getActiveSheet().getRange(StockColumnValues)])
      .build());
  
    rules.push(SpreadsheetApp.newConditionalFormatRule()
      .whenTextEqualTo("Out of Stock")
      .setBackground("#fff8f7")
      .setFontColor(Color.error)
      .setRanges([SpreadsheetApp.getActiveSheet().getRange(StockColumnValues)])
      .build());
  
    rules.push(SpreadsheetApp.newConditionalFormatRule()
      .whenTextEqualTo("On Backorder")
      .setBackground("#fffdf7")
      .setFontColor("orange")
      .setRanges([SpreadsheetApp.getActiveSheet().getRange(StockColumnValues)])
      .build());
  
    rules.push(SpreadsheetApp.newConditionalFormatRule()
      .whenNumberGreaterThan(0)
      .setBackground("#f7fff9")
      .setFontColor(Color.success)
      .setRanges([SpreadsheetApp.getActiveSheet().getRange(StockColumnValues)])
      .build());
  
    rules.push(SpreadsheetApp.newConditionalFormatRule()
      .whenNumberLessThan(1)
      .setBackground("#fff8f7")
      .setFontColor(Color.error)
      .setRanges([SpreadsheetApp.getActiveSheet().getRange(StockColumnValues)])
      .build());
  
    if (ssgsw_column_index(["sales"]) >= 0) {
      const SaleColumn = ssgsw_column_index(["sales"]).map((index) =>
        ssgsw_column_char(index)
      )[0];
  
      const SaleColumnValues = SaleColumn + 2 + ":" + SaleColumn;
  
      rules.push(SpreadsheetApp.newConditionalFormatRule()
        .whenNumberLessThan(1)
        .setFontColor(Color.error)
        .setRanges([SpreadsheetApp.getActiveSheet().getRange(SaleColumnValues)])
        .build());
    }
  
  
    CurrentSheet.setConditionalFormatRules(rules);
  
    Logger.log("Stock Sync Initialized!");
  }
  
  function ssgsw_about_us() {
    let htmlOutput = HtmlService.createHtmlOutput(
      `<h3>Stock Sync with Google Sheet for WooCommerce</h3>
              <p>Sync your WooCommerce product stock with Google Sheets.</p>
              <p><a href="https://wordpress.org/plugins/stock-sync-with-google-sheet-for-woocommerce/" target="_blank">Download Free</a> version from WordPress.org</p>
              <p><a href="http://wppool.dev/stock-sync-for-woocommerce-with-google-sheet" target="_blank">Get Ultimate</a> version to enjoy all premium features and official updates.</p>
              `
    )
      .setWidth(550)
      .setHeight(200);
    SpreadsheetApp.getUi().showModalDialog(
      htmlOutput,
      "Stock Sync with Google Sheet for WooCommerce"
    );
  }
  
  function ssgsw_headers() {
    let header = SpreadsheetApp.getActive()
      .getSheetByName(sheetTab)
      .getRange("A1:Z1")
      .getValues();
    header = header[0].filter((column) => column.length);
    return header;
  }
  
  function ssgsw_columns($key = null, $reversed = false) {
    let columns = {
      ID: "ID",
      Type: "type",
      Name: "name",
      Stock: "stock",
      SKU: "sku",
      "Regular price": "regular_price",
      "Sale price": "sale_price",
      "Short description": "post_excerpt",
      Categories: "category",
      "No of Sales": "sales",
      Attributes: "attributes",
    };
  
    if ($key) {
      if (!$reversed) {
        return columns[$key];
      } else {
        let reverse = {};
        for (let key in columns) {
          reverse[columns[key]] = key;
        }
        return reverse[$key];
      }
    }
  
    return columns;
  }
  
  function ssgsw_available_columns() {
    let columns = ssgsw_columns();
    let headers = ssgsw_headers();
    let keys = {};
  
    headers.forEach((header) => {
      if (Object.keys(columns).includes(header)) {
        keys[header] = columns[header];
      } else {
        keys[header] = header;
      }
    });
  
    return keys;
  }
  
  function ssgsw_max_column() {
    let maxColumn = SpreadsheetApp.getActive()
      .getSheetByName(sheetTab)
      .getLastColumn();
    return maxColumn;
  }
  
  function ssgsw_column_char(index = 0) {
    const alphabet = "abcdefghijklmnopqrstuvwxyz".toUpperCase().split("");
    return alphabet[index - 1] || null;
  }
  
  function ssgsw_current_row() {
    let currentCell = SpreadsheetApp.getActive()
      .getSheetByName(sheetTab)
      .getCurrentCell()
      .getA1Notation();
    let row = currentCell.replace(/[^0-9]/g, "");
    return row;
  }
  
  function ssgsw_current_column() {
    let currentCell = SpreadsheetApp.getActive()
      .getSheetByName(sheetTab)
      .getCurrentCell()
      .getA1Notation();
      
    let rowNotation = currentCell.replace(/[0-9]/g, "");
    rowNotation = "abcdefghijklmnopqrstuvwxyz"
      .toUpperCase()
      .split("")
      .indexOf(rowNotation);
  
    let column = Object.values(ssgsw_available_columns())[rowNotation]; 
    return column;
  }
  
  function ssgsw_column_index(columns) {
    let indexes = [];
    let available_columns = ssgsw_available_columns();
  
    columns.forEach((column) => {
      let index = Object.values(available_columns).indexOf(column);
      if (index >= 0) index++;
      indexes.push(index);
    });
  
    return indexes;
  }
  
  function ssgsw_format(data) {
    const deletables = ["type", "sales", "category"];
    const keys = ssgsw_ordered_keys();

    data = data
      .map((row) => {
        return Object.assign.apply(
          {},
          keys.map((v, i) => ({ [v]: row[i] }))
        );
      })
      .map((row) => {
        deletables.forEach((key) => {
          if (key in row) delete row[key];
        });
  
        return row;
      });
    return data;
  }
  
  function ssgsw_get_all_data() {
    var values = SpreadsheetApp.getActive()
      .getSheetByName(sheetTab)
      .getDataRange()
      .getValues();
    values.shift();
    return ssgsw_format(values);
  }
  
function ssgsw_get_edited_data(e) {
  var sheet = e.source.getSheetByName(sheetTab);
  var rowStart = e.range.rowStart;
  var rowEnd = e.range.rowEnd;
  var data = sheet.getRange(rowStart, 1, rowEnd - rowStart + 1, sheet.getLastColumn()).getValues();

  return ssgsw_format2(data, rowStart);
}

function ssgsw_format2(data, rowStart) {
  const deletabless = ["type", "sales", "category"];
  const keyss = ssgsw_ordered_keys();

  return data.map((row, index) => {
    let formattedRow = {};
    formattedRow["index_number"] = rowStart + index;
    keyss.forEach((key, i) => {
      formattedRow[key] = row[i];
    });

    deletabless.forEach((key) => {
      if (key in formattedRow) delete formattedRow[key];
    });

    return formattedRow;
  });
}

  function ssgsw_ordered_keys() {
    let orderedKeys = [];
    ssgsw_headers().forEach((header) => {
      orderedKeys.push(ssgsw_available_columns()[header]);
    });
    return orderedKeys;
  }
  
  function ssgsw_sync_all() {
    let data = ssgsw_get_all_data();
    ssgsw_sync_data(data);
  }
  
  function ssgsw_toast(message = null, title = null) {
    SpreadsheetApp.getActiveSpreadsheet().toast(message, title);
  }
   function ssgsw_toast2(message = null, title = null) {
    SpreadsheetApp.getActiveSpreadsheet().toast(message, title,-1);
  }
  
  function ssgsw_sync_data(data, message = "Products synced successfully",event = '') {
    let products = data.filter((row) => {
      return row.name !== '';
    });
    var hasEmptyId = products.some(function(item) {
      return item.ID == '';
    });
    if (hasEmptyId) {
      if (message != 'Product created successfully!') {
        message = 'Product create and ' + message;
      }
    }

    if (!products.length) {
        ssgsw_toast('Product name cannot be empty! If you want to create a new product', "Warning!");
        return;
    }
  
  
    let response = UrlFetchApp.fetch(baseURL + "/wp-json/ssgsw/v1/update", {
      method: "POST",
      payload: JSON.stringify({ products, message }),
      contentType: "application/json",
      muteHttpExceptions: true,
      headers: {
        SSGSWKEY: "Bearer " + accessToken,
      },
      timeout: 300,
    });
  
    response = JSON.parse(response.getContentText());
  
    if (response.success) {
      if (response.message == "You couldn't create a new product because the Add new products from Google Sheet feature is not enabled in your settings") {
        ssgsw_toast(response.message, "Warning!");
      } else {
        ssgsw_toast(response.message, "Success!");
      }
    } else if (response.message) {
      ssgsw_toast(response.message, "Ops error!");
    }
  }
  
  function ssgsw_fetch_from_WordPress(message) {
    let response = UrlFetchApp.fetch(
      baseURL + "/wp-json/ssgsw/v1/action/?action=sync",
      {
        method: "GET",
        contentType: "application/json",
        muteHttpExceptions: true,
        headers: {
          SSGSWKEY: "Bearer " + accessToken,
        },
        timeout: 300,
      }
    );
  
    response = JSON.parse(response.getContentText());
    if (response.success) {
      if(!message) {
        ssgsw_toast("Products fetched from WordPress", "Success!");
      }
    } else if (response.message) {
      ssgsw_toast(response.message, "Ops!");
    }
  }
  