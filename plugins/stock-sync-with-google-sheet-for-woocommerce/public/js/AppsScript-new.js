/**
 * Stock Sync with Google Sheet for WooCommerce
 * Google Apps Script
 * ? Future use
 */

/**
 * Stock Sync with Google Sheet for WooCommerce
 * Google Apps Script
 */

/**
 * Custom Prototypes
 */

String.prototype.toSlug = function () {
    return this.toLowerCase()
    .replace(/[^a-z0-9]/g, '_')
    .replace(/-+/, '').trim();
}

/**
 * To All
 */
String.prototype.toRange = function () {
    return StockSync.getRange(this);
}

String.prototype.toRanges = function () {
    const c = this.toRange();
    return c ? c + '2:' + c : false;
}

String.prototype.getValues = function () {
    return StockSync.values(this);
}

String.prototype.toIndex = function () {
    const c = this.toUpperCase();
    return c.charCodeAt(0) - 64;
}

Number.prototype.toColumn = function () {
    if (this < 1) { return this;
    }
    return String.fromCharCode(64 + this);
}

const StockSync = {
    /**
     * Static Properties
     */
    _config: {
        baseUrl: "{baseUrl}",
        accessToken: "{accessToken}",
        productTab: "{productTab}",
    },

    /**
     * 
     * static columns 
     */
    _columns: ['id', 'type', 'name', 'stock', 'regular_price', 'sale_price', 'sku', 'no_of_sales', 'attributes', 'short_description'],

    /**
     * debug Log
     */
    log() {
        let logs = Array.from(arguments);
        if (!logs) { return;
        }
        logs.forEach(
            (log) => {
                Logger.log(log);
            }
        );
    },

    /**
     * Init
     */
    init() {
        this.initStyles();
        // this.log("Stock Sync Init"); 
    },

    /**
     * Current Sheet
     */
    get sheet() {
        return SpreadsheetApp.getActive().getSheetByName(this._config.productTab);
    },

    /**
     * Get Values
     */
    values(range) {
        let values = this.sheet.getRange(range).getValues();
        if (!values) { return false;
        }
        return values;
    },

    /**
     * Get Columns
     */
    get columns() {
        let columns = this.values("A1:1");
        return columns && columns.length ? columns[0].filter((column) => column.length).map((str) => str.toSlug()) : [];
    },

    /**
     * Rows length
     */
    get maxRows() {
        return this.sheet.getLastRow();
    },

    /**
     *  Column length
     */
    get maxColumns() {
        return this.sheet.getLastColumn();
    },

    /**
     * Current Column
     */

    get currentColumn() {
        return this.sheet.getActiveCell().getColumn();
    },

    /**
      * Current Row
      */

    get currentRow() {
        return this.sheet.getActiveCell().getRow();
    },
    /**
     * Get Column Index
     */
    getRange(columnName = 'id') {
        columnName = columnName.toSlug();
        if (this.columns.indexOf(columnName) > -1) { return (this.columns.indexOf(columnName) + 1).toColumn();
        }
        return false;
    },

    /**
     * Header range
     */
    get headerRange() {
        return 'A1:' + this.maxColumns.toColumn() + '1'
    },

    /**
     * Reusable Color
     */
    get color() {
        return {
            primary: "#3498db",
            white: "#FFFFFF",
            black: "#000000",
            grey: "#34495e",
            dim: "#ededed",
            success: "#2ecc71",
            error: "#e74c3c",
            info: "#3498db",
            warning: "#f1c40f",
        };
    },

    /**
     * Init Styles
     */

    initStyles() {
        this.styleHeaders();
        this.styleStaticColumns();
    },

    /**
     * Style headers
     */
    styleHeaders() {
        const headers = this.sheet.getRange(this.headerRange);
        headers
        .setBackground(this.color.primary)
        .setFontColor(this.color.white)
        .setFontWeight("bold")
        .setVerticalAlignment("center")

        this.sheet.autoResizeColumns(1, this.maxColumns);
    },

    /**
     * styleStaticColumns
     */
    async styleStaticColumns() {

        const range = ["id", "type", "no_of_sales", "attributes", "category", "short_description"]
        .map(column => column.toRanges())
        .filter(column => column);

        this.sheet.getRangeList(range)
        .setBackground(this.color.dim)
        .setFontColor(this.color.grey)
        .setNote("Readonly");
    },


    /**
     * Request
     */
    async request() {
        return UrlFetchApp.fetch(
            this._config.baseUrl + "/wp-json/ssgsw/v1/action/?action=sync",
            {
                method: "GET",
                contentType: "application/json",
                muteHttpExceptions: true,
                headers: {
                    Authorization: "Bearer " + this._config.accessToken,
                },
            }
        );
    }


};

/**
* 
 * Init 
*/ 

const RunSSGSW = () => StockSync.init();


