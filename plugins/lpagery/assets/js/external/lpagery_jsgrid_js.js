/*
 * jsGrid v1.5.3 (http://js-grid.com)
 * (c) 2016 Artem Tabalin
 * Licensed under MIT (https://github.com/tabalinas/jsgrid/blob/master/LICENSE)
 */

! function(a, b, c) {
    function d(a, c) {
        var d = b(a);
        d.data(f, this), this._container = d, this.data = [], this.fields = [], this._editingRow = null, this._sortField = null, this._sortOrder = i, this._firstDisplayingPage = 1, this._init(c), this.render()
    }
    var e = "JSGrid",
        f = e,
        g = "JSGridItem",
        h = "JSGridEditRow",
        i = "asc",
        j = "desc",
        k = "{first}",
        l = "{pages}",
        m = "{prev}",
        n = "{next}",
        o = "{last}",
        p = "{pageIndex}",
        q = "{pageCount}",
        r = "{itemCount}",
        s = "javascript:void(0);",
        t = function(a, c) { return b.isFunction(a) ? a.apply(c, b.makeArray(arguments).slice(2)) : a },
        u = function(a) { var c = b.Deferred(); return a && a.then ? a.then(function() { c.resolve.apply(c, arguments) }, function() { c.reject.apply(c, arguments) }) : c.resolve(a), c.promise() },
        v = { loadData: b.noop, insertItem: b.noop, updateItem: b.noop, deleteItem: b.noop };
    d.prototype = {
        width: "auto",
        height: "auto",
        updateOnResize: !0,
        rowClass: b.noop,
        rowRenderer: null,
        rowClick: function(a) { this.editing && this.editItem(b(a.event.target).closest("tr")) },
        rowDoubleClick: b.noop,
        noDataContent: "Not found",
        noDataRowClass: "jsgrid-nodata-row",
        heading: !0,
        headerRowRenderer: null,
        headerRowClass: "jsgrid-header-row",
        headerCellClass: "jsgrid-header-cell",
        filtering: !1,
        filterRowRenderer: null,
        filterRowClass: "jsgrid-filter-row",
        inserting: !1,
        insertRowRenderer: null,
        insertRowClass: "jsgrid-insert-row",
        editing: !1,
        editRowRenderer: null,
        editRowClass: "jsgrid-edit-row",
        confirmDeleting: !0,
        deleteConfirm: "Are you sure?",
        selecting: !0,
        selectedRowClass: "jsgrid-selected-row",
        oddRowClass: "jsgrid-row",
        evenRowClass: "jsgrid-alt-row",
        cellClass: "jsgrid-cell",
        sorting: !1,
        sortableClass: "jsgrid-header-sortable",
        sortAscClass: "jsgrid-header-sort jsgrid-header-sort-asc",
        sortDescClass: "jsgrid-header-sort jsgrid-header-sort-desc",
        paging: !1,
        pagerContainer: null,
        pageIndex: 1,
        pageSize: 20,
        pageButtonCount: 15,
        pagerFormat: "Pages: {first} {prev} {pages} {next} {last} &nbsp;&nbsp; {pageIndex} of {pageCount}",
        pagePrevText: "Prev",
        pageNextText: "Next",
        pageFirstText: "First",
        pageLastText: "Last",
        pageNavigatorNextText: "...",
        pageNavigatorPrevText: "...",
        pagerContainerClass: "jsgrid-pager-container",
        pagerClass: "jsgrid-pager",
        pagerNavButtonClass: "jsgrid-pager-nav-button",
        pagerNavButtonInactiveClass: "jsgrid-pager-nav-inactive-button",
        pageClass: "jsgrid-pager-page",
        currentPageClass: "jsgrid-pager-current-page",
        customLoading: !1,
        pageLoading: !1,
        autoload: !1,
        controller: v,
        loadIndication: !0,
        loadIndicationDelay: 500,
        loadMessage: "Please, wait...",
        loadShading: !0,
        invalidMessage: "Invalid data entered!",
        invalidNotify: function(c) {
            var d = b.map(c.errors, function(a) { return a.message || null });
            a.alert([this.invalidMessage].concat(d).join("\n"))
        },
        onInit: b.noop,
        onRefreshing: b.noop,
        onRefreshed: b.noop,
        onPageChanged: b.noop,
        onItemDeleting: b.noop,
        onItemDeleted: b.noop,
        onItemInserting: b.noop,
        onItemInserted: b.noop,
        onItemEditing: b.noop,
        onItemUpdating: b.noop,
        onItemUpdated: b.noop,
        onItemInvalid: b.noop,
        onDataLoading: b.noop,
        onDataLoaded: b.noop,
        onOptionChanging: b.noop,
        onOptionChanged: b.noop,
        onError: b.noop,
        invalidClass: "jsgrid-invalid",
        containerClass: "jsgrid",
        tableClass: "jsgrid-table",
        gridHeaderClass: "jsgrid-grid-header",
        gridBodyClass: "jsgrid-grid-body",
        _init: function(a) { b.extend(this, a), this._initLoadStrategy(), this._initController(), this._initFields(), this._attachWindowLoadResize(), this._attachWindowResizeCallback(), this._callEventHandler(this.onInit) },
        loadStrategy: function() { return this.pageLoading ? new jsGrid.loadStrategies.PageLoadingStrategy(this) : new jsGrid.loadStrategies.DirectLoadingStrategy(this) },
        _initLoadStrategy: function() { this._loadStrategy = t(this.loadStrategy, this) },
        _initController: function() { this._controller = b.extend({}, v, t(this.controller, this)) },
        renderTemplate: function(a, b, d) { args = []; for (var e in d) args.push(d[e]); return args.unshift(a, b), a = t.apply(null, args), a === c || null === a ? "" : a },
        loadIndicator: function(a) { return new jsGrid.LoadIndicator(a) },
        validation: function(a) { return jsGrid.Validation && new jsGrid.Validation(a) },
        _initFields: function() {
            var a = this;
            a.fields = b.map(a.fields, function(c) {
                if (b.isPlainObject(c)) {
                    var d = c.type && jsGrid.fields[c.type] || jsGrid.Field;
                    c = new d(c)
                }
                return c._grid = a, c
            })
        },
        _attachWindowLoadResize: function() { b(a).on("load", b.proxy(this._refreshSize, this)) },
        _attachWindowResizeCallback: function() { this.updateOnResize && b(a).on("resize", b.proxy(this._refreshSize, this)) },
        _detachWindowResizeCallback: function() { b(a).off("resize", this._refreshSize) },
        option: function(a, b) { var c, d; return 1 === arguments.length ? this[a] : (c = { option: a, oldValue: this[a], newValue: b }, this._callEventHandler(this.onOptionChanging, c), this._handleOptionChange(c.option, c.newValue), d = { option: c.option, value: c.newValue }, void this._callEventHandler(this.onOptionChanged, d)) },
        fieldOption: function(a, b, c) { return a = this._normalizeField(a), 2 === arguments.length ? a[b] : (a[b] = c, void this._renderGrid()) },
        _handleOptionChange: function(a, b) {
            switch (this[a] = b, a) {
                case "width":
                case "height":
                    this._refreshSize();
                    break;
                case "rowClass":
                case "rowRenderer":
                case "rowClick":
                case "rowDoubleClick":
                case "noDataRowClass":
                case "noDataContent":
                case "selecting":
                case "selectedRowClass":
                case "oddRowClass":
                case "evenRowClass":
                    this._refreshContent();
                    break;
                case "pageButtonCount":
                case "pagerFormat":
                case "pagePrevText":
                case "pageNextText":
                case "pageFirstText":
                case "pageLastText":
                case "pageNavigatorNextText":
                case "pageNavigatorPrevText":
                case "pagerClass":
                case "pagerNavButtonClass":
                case "pageClass":
                case "currentPageClass":
                case "pagerRenderer":
                    this._refreshPager();
                    break;
                case "fields":
                    this._initFields(), this.render();
                    break;
                case "data":
                case "editing":
                case "heading":
                case "filtering":
                case "inserting":
                case "paging":
                    this.refresh();
                    break;
                case "loadStrategy":
                case "pageLoading":
                    this._initLoadStrategy(), this.search();
                    break;
                case "pageIndex":
                    this.openPage(b);
                    break;
                case "pageSize":
                    this.refresh(), this.search();
                    break;
                case "editRowRenderer":
                case "editRowClass":
                    this.cancelEdit();
                    break;
                case "updateOnResize":
                    this._detachWindowResizeCallback(), this._attachWindowResizeCallback();
                    break;
                case "invalidNotify":
                case "invalidMessage":
                    break;
                default:
                    this.render()
            }
        },
        destroy: function() { this._detachWindowResizeCallback(), this._clear(), this._container.removeData(f) },
        render: function() { return this._renderGrid(), this.autoload ? this.loadData() : b.Deferred().resolve().promise() },
        _renderGrid: function() { this._clear(), this._container.addClass(this.containerClass).css("position", "relative").append(this._createHeader()).append(this._createBody()), this._pagerContainer = this._createPagerContainer(), this._loadIndicator = this._createLoadIndicator(), this._validation = this._createValidation(), this.refresh() },
        _createLoadIndicator: function() { return t(this.loadIndicator, this, { message: this.loadMessage, shading: this.loadShading, container: this._container }) },
        _createValidation: function() { return t(this.validation, this) },
        _clear: function() { this.cancelEdit(), clearTimeout(this._loadingTimer), this._pagerContainer && this._pagerContainer.empty(), this._container.empty().css({ position: "", width: "", height: "" }) },
        _createHeader: function() {
            var a = this._headerRow = this._createHeaderRow(),
                c = this._filterRow = this._createFilterRow(),
                d = this._insertRow = this._createInsertRow(),
                e = this._headerGrid = b("<table>").addClass(this.tableClass).append(a).append(c).append(d),
                f = this._header = b("<div>").addClass(this.gridHeaderClass).addClass(this._scrollBarWidth() ? "jsgrid-header-scrollbar" : "").append(e);
            return f
        },
        _createBody: function() {
            var a = this._content = b("<tbody>"),
                c = this._bodyGrid = b("<table>").addClass(this.tableClass).append(a),
                d = this._body = b("<div>").addClass(this.gridBodyClass).append(c).on("scroll", b.proxy(function(a) { this._header.scrollLeft(a.target.scrollLeft) }, this));
            return d
        },
        _createPagerContainer: function() { var a = this.pagerContainer || b("<div>").appendTo(this._container); return b(a).addClass(this.pagerContainerClass) },
        _eachField: function(a) {
            var c = this;
            b.each(this.fields, function(b, d) { d.visible && a.call(c, d, b) })
        },
        _createHeaderRow: function() {
            if (b.isFunction(this.headerRowRenderer)) return b(this.renderTemplate(this.headerRowRenderer, this));
            var a = b("<tr>").addClass(this.headerRowClass);
            return this._eachField(function(c, d) {
                var e = this._prepareCell("<th>", c, "headercss", this.headerCellClass).append(this.renderTemplate(c.headerTemplate, c)).appendTo(a);
                this.sorting && c.sorting && e.addClass(this.sortableClass).on("click", b.proxy(function() { this.sort(d) }, this))
            }), a
        },
        _prepareCell: function(a, c, d, e) { return b(a).css("width", c.width).addClass(e || this.cellClass).addClass(d && c[d] || c.css).addClass(c.align ? "jsgrid-align-" + c.align : "") },
        _createFilterRow: function() { if (b.isFunction(this.filterRowRenderer)) return b(this.renderTemplate(this.filterRowRenderer, this)); var a = b("<tr>").addClass(this.filterRowClass); return this._eachField(function(b) { this._prepareCell("<td>", b, "filtercss").append(this.renderTemplate(b.filterTemplate, b)).appendTo(a) }), a },
        _createInsertRow: function() { if (b.isFunction(this.insertRowRenderer)) return b(this.renderTemplate(this.insertRowRenderer, this)); var a = b("<tr>").addClass(this.insertRowClass); return this._eachField(function(b) { this._prepareCell("<td>", b, "insertcss").append(this.renderTemplate(b.insertTemplate, b)).appendTo(a) }), a },
        _callEventHandler: function(a, c) { return a.call(this, b.extend(c, { grid: this })), c },
        reset: function() { return this._resetSorting(), this._resetPager(), this._loadStrategy.reset() },
        _resetPager: function() { this._firstDisplayingPage = 1, this._setPage(1) },
        _resetSorting: function() { this._sortField = null, this._sortOrder = i, this._clearSortingCss() },
        refresh: function() { this._callEventHandler(this.onRefreshing), this.cancelEdit(), this._refreshHeading(), this._refreshFiltering(), this._refreshInserting(), this._refreshContent(), this._refreshPager(), this._refreshSize(), this._callEventHandler(this.onRefreshed) },
        _refreshHeading: function() { this._headerRow.toggle(this.heading) },
        _refreshFiltering: function() { this._filterRow.toggle(this.filtering) },
        _refreshInserting: function() { this._insertRow.toggle(this.inserting) },
        _refreshContent: function() {
            var a = this._content;
            if (a.empty(), !this.data.length) return a.append(this._createNoDataRow()), this;
            for (var b = this._loadStrategy.firstDisplayIndex(), c = this._loadStrategy.lastDisplayIndex(), d = b; c > d; d++) {
                var e = this.data[d];
                a.append(this._createRow(e, d))
            }
        },
        _createNoDataRow: function() { var a = 0; return this._eachField(function() { a++ }), b("<tr>").addClass(this.noDataRowClass).append(b("<td>").addClass(this.cellClass).attr("colspan", a).append(this.renderTemplate(this.noDataContent, this))) },
        _createRow: function(a, c) { var d; return b.isFunction(this.rowRenderer) ? d = this.renderTemplate(this.rowRenderer, this, { item: a, itemIndex: c }) : (d = b("<tr>"), this._renderCells(d, a)), d.addClass(this._getRowClasses(a, c)).data(g, a).on("click", b.proxy(function(b) { this.rowClick({ item: a, itemIndex: c, event: b }) }, this)).on("dblclick", b.proxy(function(b) { this.rowDoubleClick({ item: a, itemIndex: c, event: b }) }, this)), this.selecting && this._attachRowHover(d), d },
        _getRowClasses: function(a, b) { var c = []; return c.push((b + 1) % 2 ? this.oddRowClass : this.evenRowClass), c.push(t(this.rowClass, this, a, b)), c.join(" ") },
        _attachRowHover: function(a) {
            var c = this.selectedRowClass;
            a.hover(function() { b(this).addClass(c) }, function() { b(this).removeClass(c) })
        },
        _renderCells: function(a, b) { return this._eachField(function(c) { a.append(this._createCell(b, c)) }), this },
        _createCell: function(a, c) {
            var d, e = this._getItemFieldValue(a, c),
                f = { value: e, item: a };
            return d = b.isFunction(c.cellRenderer) ? this.renderTemplate(c.cellRenderer, c, f) : b("<td>").append(this.renderTemplate(c.itemTemplate || e, c, f)), this._prepareCell(d, c)
        },
        _getItemFieldValue: function(a, b) { for (var c = b.name.split("."), d = a[c.shift()]; d && c.length;) d = d[c.shift()]; return d },
        _setItemFieldValue: function(a, b, c) {
            for (var d = b.name.split("."), e = a, f = d[0]; e && d.length;) a = e, f = d.shift(), e = a[f];
            if (!e)
                for (; d.length;) a = a[f] = {}, f = d.shift();
            a[f] = c
        },
        sort: function(a, c) { return b.isPlainObject(a) && (c = a.order, a = a.field), this._clearSortingCss(), this._setSortingParams(a, c), this._setSortingCss(), this._loadStrategy.sort() },
        _clearSortingCss: function() { this._headerRow.find("th").removeClass(this.sortAscClass).removeClass(this.sortDescClass) },
        _setSortingParams: function(a, b) { a = this._normalizeField(a), b = b || (this._sortField === a ? this._reversedSortOrder(this._sortOrder) : i), this._sortField = a, this._sortOrder = b },
        _normalizeField: function(a) { return b.isNumeric(a) ? this.fields[a] : "string" == typeof a ? b.grep(this.fields, function(b) { return b.name === a })[0] : a },
        _reversedSortOrder: function(a) { return a === i ? j : i },
        _setSortingCss: function() {
            var a = this._visibleFieldIndex(this._sortField);
            this._headerRow.find("th").eq(a).addClass(this._sortOrder === i ? this.sortAscClass : this.sortDescClass)
        },
        _visibleFieldIndex: function(a) { return b.inArray(a, b.grep(this.fields, function(a) { return a.visible })) },
        _sortData: function() {
            var a = this._sortFactor(),
                b = this._sortField;
            b && this.data.sort(function(c, d) { return a * b.sortingFunc(c[b.name], d[b.name]) })
        },
        _sortFactor: function() { return this._sortOrder === i ? 1 : -1 },
        _itemsCount: function() { return this._loadStrategy.itemsCount() },
        _pagesCount: function() {
            var a = this._itemsCount(),
                b = this.pageSize;
            return Math.floor(a / b) + (a % b ? 1 : 0)
        },
        _refreshPager: function() {
            var a = this._pagerContainer;
            a.empty(), this.paging && a.append(this._createPager());
            var b = this.paging && this._pagesCount() > 1;
            a.toggle(b)
        },
        _createPager: function() { var a; return a = b.isFunction(this.pagerRenderer) ? b(this.pagerRenderer({ pageIndex: this.pageIndex, pageCount: this._pagesCount() })) : b("<div>").append(this._createPagerByFormat()), a.addClass(this.pagerClass), a },
        _createPagerByFormat: function() {
            var a = this.pageIndex,
                c = this._pagesCount(),
                d = this._itemsCount(),
                e = this.pagerFormat.split(" ");
            return b.map(e, b.proxy(function(e) { var f = e; return e === l ? f = this._createPages() : e === k ? f = this._createPagerNavButton(this.pageFirstText, 1, a > 1) : e === m ? f = this._createPagerNavButton(this.pagePrevText, a - 1, a > 1) : e === n ? f = this._createPagerNavButton(this.pageNextText, a + 1, c > a) : e === o ? f = this._createPagerNavButton(this.pageLastText, c, c > a) : e === p ? f = a : e === q ? f = c : e === r && (f = d), b.isArray(f) ? f.concat([" "]) : [f, " "] }, this))
        },
        _createPages: function() {
            var a = this._pagesCount(),
                b = this.pageButtonCount,
                c = this._firstDisplayingPage,
                d = [];
            c > 1 && d.push(this._createPagerPageNavButton(this.pageNavigatorPrevText, this.showPrevPages));
            for (var e = 0, f = c; b > e && a >= f; e++, f++) d.push(f === this.pageIndex ? this._createPagerCurrentPage() : this._createPagerPage(f));
            return a > c + b - 1 && d.push(this._createPagerPageNavButton(this.pageNavigatorNextText, this.showNextPages)), d
        },
        _createPagerNavButton: function(a, c, d) { return this._createPagerButton(a, this.pagerNavButtonClass + (d ? "" : " " + this.pagerNavButtonInactiveClass), d ? function() { this.openPage(c) } : b.noop) },
        _createPagerPageNavButton: function(a, b) { return this._createPagerButton(a, this.pagerNavButtonClass, b) },
        _createPagerPage: function(a) { return this._createPagerButton(a, this.pageClass, function() { this.openPage(a) }) },
        _createPagerButton: function(a, c, d) { var e = b("<a>").attr("href", s).html(a).on("click", b.proxy(d, this)); return b("<span>").addClass(c).append(e) },
        _createPagerCurrentPage: function() { return b("<span>").addClass(this.pageClass).addClass(this.currentPageClass).text(this.pageIndex) },
        _refreshSize: function() { this._refreshHeight(), this._refreshWidth() },
        _refreshWidth: function() {
            var a = "auto" === this.width ? this._getAutoWidth() : this.width;
            this._container.width(a)
        },
        _getAutoWidth: function() {
            var a = this._headerGrid,
                b = this._header;
            a.width("auto");
            var c = a.outerWidth(),
                d = b.outerWidth() - b.innerWidth();
            return a.width(""), c + d
        },
        _scrollBarWidth: function() {
            var a;
            return function() {
                if (a === c) {
                    var d = b("<div style='width:50px;height:50px;overflow:hidden;position:absolute;top:-10000px;left:-10000px;'></div>"),
                        e = b("<div style='height:100px;'></div>");
                    d.append(e).appendTo("body");
                    var f = e.innerWidth();
                    d.css("overflow-y", "auto");
                    var g = e.innerWidth();
                    d.remove(), a = f - g
                }
                return a
            }
        }(),
        _refreshHeight: function() {
            var a, b = this._container,
                c = this._pagerContainer,
                d = this.height;
            b.height(d), "auto" !== d && (d = b.height(), a = this._header.outerHeight(!0), c.parents(b).length && (a += c.outerHeight(!0)), this._body.outerHeight(d - a))
        },
        showPrevPages: function() {
            var a = this._firstDisplayingPage,
                b = this.pageButtonCount;
            this._firstDisplayingPage = a > b ? a - b : 1, this._refreshPager()
        },
        showNextPages: function() {
            var a = this._firstDisplayingPage,
                b = this.pageButtonCount,
                c = this._pagesCount();
            this._firstDisplayingPage = a + 2 * b > c ? c - b + 1 : a + b, this._refreshPager()
        },
        openPage: function(a) { 1 > a || a > this._pagesCount() || (this._setPage(a), this._loadStrategy.openPage(a)) },
        _setPage: function(a) {
            var b = this._firstDisplayingPage,
                c = this.pageButtonCount;
            this.pageIndex = a, b > a && (this._firstDisplayingPage = a), a > b + c - 1 && (this._firstDisplayingPage = a - c + 1), this._callEventHandler(this.onPageChanged, { pageIndex: a })
        },
        _controllerCall: function(a, c, d, e) {
            if (d) return b.Deferred().reject().promise();
            this._showLoading();
            var f = this._controller;
            if (!f || !f[a]) throw Error("controller has no method '" + a + "'");
            return u(f[a](c)).done(b.proxy(e, this)).fail(b.proxy(this._errorHandler, this)).always(b.proxy(this._hideLoading, this))
        },
        _errorHandler: function() { this._callEventHandler(this.onError, { args: b.makeArray(arguments) }) },
        _showLoading: function() { this.loadIndication && (clearTimeout(this._loadingTimer), this._loadingTimer = setTimeout(b.proxy(function() { this._loadIndicator.show() }, this), this.loadIndicationDelay)) },
        _hideLoading: function() { this.loadIndication && (clearTimeout(this._loadingTimer), this._loadIndicator.hide()) },
        search: function(a) { return this._resetSorting(), this._resetPager(), this.loadData(a) },
        loadData: function(a) { a = a || (this.filtering ? this.getFilter() : {}), b.extend(a, this._loadStrategy.loadParams(), this._sortingParams()); var c = this._callEventHandler(this.onDataLoading, { filter: a }); return this._controllerCall("loadData", a, c.cancel, function(a) { a && (this._loadStrategy.finishLoad(a), this._callEventHandler(this.onDataLoaded, { data: a })) }) },
        getFilter: function() { var a = {}; return this._eachField(function(b) { b.filtering && this._setItemFieldValue(a, b, b.filterValue()) }), a },
        _sortingParams: function() { return this.sorting && this._sortField ? { sortField: this._sortField.name, sortOrder: this._sortOrder } : {} },
        getSorting: function() { var a = this._sortingParams(); return { field: a.sortField, order: a.sortOrder } },
        clearFilter: function() { var a = this._createFilterRow(); return this._filterRow.replaceWith(a), this._filterRow = a, this.search() },
        insertItem: function(a) { var c = a || this._getValidatedInsertItem(); if (!c) return b.Deferred().reject().promise(); var d = this._callEventHandler(this.onItemInserting, { item: c }); return this._controllerCall("insertItem", c, d.cancel, function(a) { a = a || c, this._loadStrategy.finishInsert(a), this._callEventHandler(this.onItemInserted, { item: a }) }) },
        _getValidatedInsertItem: function() { var a = this._getInsertItem(); return this._validateItem(a, this._insertRow) ? a : null },
        _getInsertItem: function() { var a = {}; return this._eachField(function(b) { b.inserting && this._setItemFieldValue(a, b, b.insertValue()) }), a },
        _validateItem: function(a, c) {
            var d = [],
                e = { item: a, itemIndex: this._rowIndex(c), row: c };
            if (this._eachField(function(f) {
                    if (f.validate && (c !== this._insertRow || f.inserting) && (c !== this._getEditRow() || f.editing)) {
                        var g = this._getItemFieldValue(a, f),
                            h = this._validation.validate(b.extend({ value: g, rules: f.validate }, e));
                        this._setCellValidity(c.children().eq(this._visibleFieldIndex(f)), h), h.length && d.push.apply(d, b.map(h, function(a) { return { field: f, message: a } }))
                    }
                }), !d.length) return !0;
            var f = b.extend({ errors: d }, e);
            return this._callEventHandler(this.onItemInvalid, f), this.invalidNotify(f), !1
        },
        _setCellValidity: function(a, b) { a.toggleClass(this.invalidClass, !!b.length).attr("title", b.join("\n")) },
        clearInsert: function() {
            var a = this._createInsertRow();
            this._insertRow.replaceWith(a), this._insertRow = a, this.refresh()
        },
        editItem: function(a) {
            var b = this.rowByItem(a);
            b.length && this._editRow(b)
        },
        rowByItem: function(a) { return a.jquery || a.nodeType ? b(a) : this._content.find("tr").filter(function() { return b.data(this, g) === a }) },
        _editRow: function(a) {
            if (this.editing) {
                var b = a.data(g),
                    c = this._callEventHandler(this.onItemEditing, { row: a, item: b, itemIndex: this._itemIndex(b) });
                if (!c.cancel) {
                    this._editingRow && this.cancelEdit();
                    var d = this._createEditRow(b);
                    this._editingRow = a, a.hide(), d.insertBefore(a), a.data(h, d)
                }
            }
        },
        _createEditRow: function(a) {
            if (b.isFunction(this.editRowRenderer)) return b(this.renderTemplate(this.editRowRenderer, this, { item: a, itemIndex: this._itemIndex(a) }));
            var c = b("<tr>").addClass(this.editRowClass);
            return this._eachField(function(b) {
                var d = this._getItemFieldValue(a, b);
                this._prepareCell("<td>", b, "editcss").append(this.renderTemplate(b.editTemplate || "", b, { value: d, item: a })).appendTo(c)
            }), c
        },
        updateItem: function(a, b) { 1 === arguments.length && (b = a); var c = a ? this.rowByItem(a) : this._editingRow; return (b = b || this._getValidatedEditedItem()) ? this._updateRow(c, b) : void 0 },
        _getValidatedEditedItem: function() { var a = this._getEditedItem(); return this._validateItem(a, this._getEditRow()) ? a : null },
        _updateRow: function(a, c) {
            var d = a.data(g),
                e = this._itemIndex(d),
                f = b.extend(!0, {}, d, c),
                h = this._callEventHandler(this.onItemUpdating, { row: a, item: f, itemIndex: e, previousItem: d });
            return this._controllerCall("updateItem", f, h.cancel, function(g) {
                var h = b.extend(!0, {}, d);
                f = g || b.extend(!0, d, c);
                var i = this._finishUpdate(a, f, e);
                this._callEventHandler(this.onItemUpdated, { row: i, item: f, itemIndex: e, previousItem: h })
            })
        },
        _rowIndex: function(a) { return this._content.children().index(b(a)) },
        _itemIndex: function(a) { return b.inArray(a, this.data) },
        _finishUpdate: function(a, b, c) { this.cancelEdit(), this.data[c] = b; var d = this._createRow(b, c); return a.replaceWith(d), d },
        _getEditedItem: function() { var a = {}; return this._eachField(function(b) { b.editing && this._setItemFieldValue(a, b, b.editValue()) }), a },
        cancelEdit: function() { this._editingRow && (this._getEditRow().remove(), this._editingRow.show(), this._editingRow = null) },
        _getEditRow: function() { return this._editingRow && this._editingRow.data(h) },
        deleteItem: function(b) { var c = this.rowByItem(b); if (c.length && (!this.confirmDeleting || a.confirm(t(this.deleteConfirm, this, c.data(g))))) return this._deleteRow(c) },
        _deleteRow: function(a) {
            var b = a.data(g),
                c = this._itemIndex(b),
                d = this._callEventHandler(this.onItemDeleting, { row: a, item: b, itemIndex: c });
            return this._controllerCall("deleteItem", b, d.cancel, function() { this._loadStrategy.finishDelete(b, c), this._callEventHandler(this.onItemDeleted, { row: a, item: b, itemIndex: c }) })
        }
    }, b.fn.jsGrid = function(a) {
        var e = b.makeArray(arguments),
            g = e.slice(1),
            h = this;
        return this.each(function() {
            var e, i = b(this),
                j = i.data(f);
            if (j)
                if ("string" == typeof a) { if (e = j[a].apply(j, g), e !== c && e !== j) return h = e, !1 } else j._detachWindowResizeCallback(), j._init(a), j.render();
            else new d(i, a)
        }), h
    };
    var w = {},
        x = function(a) {
            var c;
            b.isPlainObject(a) ? c = d.prototype : (c = w[a].prototype, a = arguments[1] || {}), b.extend(c, a)
        },
        y = {},
        z = function(a) {
            var c = b.isPlainObject(a) ? a : y[a];
            if (!c) throw Error("unknown locale " + a);
            A(jsGrid, c)
        },
        A = function(a, c) { b.each(c, function(c, d) { return b.isPlainObject(d) ? void A(a[c] || a[c[0].toUpperCase() + c.slice(1)], d) : void(a.hasOwnProperty(c) ? a[c] = d : a.prototype[c] = d) }) };
    a.jsGrid = { Grid: d, fields: w, setDefaults: x, locales: y, locale: z, version: "1.5.3" }
}(window, jQuery),
function(a, b) {
    function c(a) { this._init(a) }
    c.prototype = {
        container: "body",
        message: "Loading...",
        shading: !0,
        zIndex: 1e3,
        shaderClass: "jsgrid-load-shader",
        loadPanelClass: "jsgrid-load-panel",
        _init: function(a) { b.extend(!0, this, a), this._initContainer(), this._initShader(), this._initLoadPanel() },
        _initContainer: function() { this._container = b(this.container) },
        _initShader: function() { this.shading && (this._shader = b("<div>").addClass(this.shaderClass).hide().css({ position: "absolute", top: 0, right: 0, bottom: 0, left: 0, zIndex: this.zIndex }).appendTo(this._container)) },
        _initLoadPanel: function() { this._loadPanel = b("<div>").addClass(this.loadPanelClass).text(this.message).hide().css({ position: "absolute", top: "50%", left: "50%", zIndex: this.zIndex }).appendTo(this._container) },
        show: function() {
            var a = this._loadPanel.show(),
                b = a.outerWidth(),
                c = a.outerHeight();
            a.css({ marginTop: -c / 2, marginLeft: -b / 2 }), this._shader.show()
        },
        hide: function() { this._loadPanel.hide(), this._shader.hide() }
    }, a.LoadIndicator = c
}(jsGrid, jQuery),
function(a, b) {
    function c(a) { this._grid = a }

    function d(a) { this._grid = a, this._itemsCount = 0 }
    c.prototype = {
        firstDisplayIndex: function() { var a = this._grid; return a.option("paging") ? (a.option("pageIndex") - 1) * a.option("pageSize") : 0 },
        lastDisplayIndex: function() {
            var a = this._grid,
                b = a.option("data").length;
            return a.option("paging") ? Math.min(a.option("pageIndex") * a.option("pageSize"), b) : b
        },
        itemsCount: function() { return this._grid.option("data").length },
        openPage: function() { this._grid.refresh() },
        loadParams: function() { return {} },
        sort: function() { return this._grid._sortData(), this._grid.refresh(), b.Deferred().resolve().promise() },
        reset: function() { return this._grid.refresh(), b.Deferred().resolve().promise() },
        finishLoad: function(a) { this._grid.option("data", a) },
        finishInsert: function(a) {
            var b = this._grid;
            b.option("data").push(a), b.refresh()
        },
        finishDelete: function(a, b) {
            var c = this._grid;
            c.option("data").splice(b, 1), c.reset()
        }
    }, d.prototype = { firstDisplayIndex: function() { return 0 }, lastDisplayIndex: function() { return this._grid.option("data").length }, itemsCount: function() { return this._itemsCount }, openPage: function() { this._grid.loadData() }, loadParams: function() { var a = this._grid; return { pageIndex: a.option("pageIndex"), pageSize: a.option("pageSize") } }, reset: function() { return this._grid.loadData() }, sort: function() { return this._grid.loadData() }, finishLoad: function(a) { this._itemsCount = a.itemsCount, this._grid.option("data", a.data) }, finishInsert: function() { this._grid.search() }, finishDelete: function() { this._grid.search() } }, a.loadStrategies = { DirectLoadingStrategy: c, PageLoadingStrategy: d }
}(jsGrid, jQuery),
function(a) {
    var b = function(a) { return "undefined" != typeof a && null !== a },
        c = { string: function(a, c) { return b(a) || b(c) ? b(a) ? b(c) ? ("" + a).localeCompare("" + c) : 1 : -1 : 0 }, number: function(a, b) { return a - b }, date: function(a, b) { return a - b }, numberAsString: function(a, b) { return parseFloat(a) - parseFloat(b) } };
    a.sortStrategies = c
}(jsGrid, jQuery),
function(a, b, c) {
    function d(a) { this._init(a) }
    d.prototype = {
        _init: function(a) { b.extend(!0, this, a) },
        validate: function(a) {
            var c = [];
            return b.each(this._normalizeRules(a.rules), function(d, e) {
                if (!e.validator(a.value, a.item, e.param)) {
                    var f = b.isFunction(e.message) ? e.message(a.value, a.item) : e.message;
                    c.push(f)
                }
            }), c
        },
        _normalizeRules: function(a) { return b.isArray(a) || (a = [a]), b.map(a, b.proxy(function(a) { return this._normalizeRule(a) }, this)) },
        _normalizeRule: function(a) { if ("string" == typeof a && (a = { validator: a }), b.isFunction(a) && (a = { validator: a }), !b.isPlainObject(a)) throw Error("wrong validation config specified"); return a = b.extend({}, a), b.isFunction(a.validator) ? a : this._applyNamedValidator(a, a.validator) },
        _applyNamedValidator: function(a, c) { delete a.validator; var d = e[c]; if (!d) throw Error('unknown validator "' + c + '"'); return b.isFunction(d) && (d = { validator: d }), b.extend({}, d, a) }
    }, a.Validation = d;
    var e = { required: { message: "Field is required", validator: function(a) { return a !== c && null !== a && "" !== a } }, rangeLength: { message: "Field value length is out of the defined range", validator: function(a, b, c) { return a.length >= c[0] && a.length <= c[1] } }, minLength: { message: "Field value is too short", validator: function(a, b, c) { return a.length >= c } }, maxLength: { message: "Field value is too long", validator: function(a, b, c) { return a.length <= c } }, pattern: { message: "Field value is not matching the defined pattern", validator: function(a, b, c) { return "string" == typeof c && (c = new RegExp("^(?:" + c + ")$")), c.test(a) } }, range: { message: "Field value is out of the defined range", validator: function(a, b, c) { return a >= c[0] && a <= c[1] } }, min: { message: "Field value is too small", validator: function(a, b, c) { return a >= c } }, max: { message: "Field value is too large", validator: function(a, b, c) { return c >= a } } };
    a.validators = e
}(jsGrid, jQuery),
function(a, b, c) {
    function d(a) { b.extend(!0, this, a), this.sortingFunc = this._getSortingFunc() }
    d.prototype = { name: "", title: null, css: "", align: "", width: 100, visible: !0, filtering: !0, inserting: !0, editing: !0, sorting: !0, sorter: "string", headerTemplate: function() { return this.title === c || null === this.title ? this.name : this.title }, itemTemplate: function(a) { return a }, filterTemplate: function() { return "" }, insertTemplate: function() { return "" }, editTemplate: function(a, b) { return this._value = a, this.itemTemplate(a, b) }, filterValue: function() { return "" }, insertValue: function() { return "" }, editValue: function() { return this._value }, _getSortingFunc: function() { var c = this.sorter; if (b.isFunction(c)) return c; if ("string" == typeof c) return a.sortStrategies[c]; throw Error('wrong sorter for the field "' + this.name + '"!') } }, a.Field = d
}(jsGrid, jQuery),
function(a, b) {
    function c(a) { d.call(this, a) }
    var d = a.Field;
    c.prototype = new d({
        autosearch: !0,
        readOnly: !1,
        filterTemplate: function() {
            if (!this.filtering) return "";
            var a = this._grid,
                b = this.filterControl = this._createTextBox();
            return this.autosearch && b.on("keypress", function(b) { 13 === b.which && (a.search(), b.preventDefault()) }), b
        },
        insertTemplate: function() { return this.inserting ? this.insertControl = this._createTextBox() : "" },
        editTemplate: function(a) { if (!this.editing) return this.itemTemplate.apply(this, arguments); var b = this.editControl = this._createTextBox(); return b.val(a), b },
        filterValue: function() { return this.filterControl.val() },
        insertValue: function() { return this.insertControl.val() },
        editValue: function() { return this.editControl.val() },
        _createTextBox: function() { return b("<input>").attr("type", "text").prop("readonly", !!this.readOnly) }
    }), a.fields.text = a.TextField = c
}(jsGrid, jQuery),
function(a, b, c) {
    function d(a) { e.call(this, a) }
    var e = a.TextField;
    d.prototype = new e({ sorter: "number", align: "right", readOnly: !1, filterValue: function() { return this.filterControl.val() ? parseInt(this.filterControl.val() || 0, 10) : c }, insertValue: function() { return this.insertControl.val() ? parseInt(this.insertControl.val() || 0, 10) : c }, editValue: function() { return this.editControl.val() ? parseInt(this.editControl.val() || 0, 10) : c }, _createTextBox: function() { return b("<input>").attr("type", "number").prop("readonly", !!this.readOnly) } }), a.fields.number = a.NumberField = d
}(jsGrid, jQuery),
function(a, b) {
    function c(a) { d.call(this, a) }
    var d = a.TextField;
    c.prototype = new d({ insertTemplate: function() { return this.inserting ? this.insertControl = this._createTextArea() : "" }, editTemplate: function(a) { if (!this.editing) return this.itemTemplate.apply(this, arguments); var b = this.editControl = this._createTextArea(); return b.val(a), b }, _createTextArea: function() { return b("<textarea>").prop("readonly", !!this.readOnly) } }), a.fields.textarea = a.TextAreaField = c
}(jsGrid, jQuery),
function(a, b, c) {
    function d(a) {
        if (this.items = [], this.selectedIndex = -1, this.valueField = "", this.textField = "", a.valueField && a.items.length) {
            var b = a.items[0][a.valueField];
            this.valueType = typeof b === f ? f : g
        }
        this.sorter = this.valueType, e.call(this, a)
    }
    var e = a.NumberField,
        f = "number",
        g = "string";
    d.prototype = new e({
        align: "center",
        valueType: f,
        itemTemplate: function(a) {
            var d, e = this.items,
                f = this.valueField,
                g = this.textField;
            d = f ? b.grep(e, function(b) { return b[f] === a })[0] || {} : e[a];
            var h = g ? d[g] : d;
            return h === c || null === h ? "" : h
        },
        filterTemplate: function() {
            if (!this.filtering) return "";
            var a = this._grid,
                b = this.filterControl = this._createSelect();
            return this.autosearch && b.on("change", function() { a.search() }), b
        },
        insertTemplate: function() { return this.inserting ? this.insertControl = this._createSelect() : "" },
        editTemplate: function(a) { if (!this.editing) return this.itemTemplate.apply(this, arguments); var b = this.editControl = this._createSelect(); return a !== c && b.val(a), b },
        filterValue: function() { var a = this.filterControl.val(); return this.valueType === f ? parseInt(a || 0, 10) : a },
        insertValue: function() { var a = this.insertControl.val(); return this.valueType === f ? parseInt(a || 0, 10) : a },
        editValue: function() { var a = this.editControl.val(); return this.valueType === f ? parseInt(a || 0, 10) : a },
        _createSelect: function() {
            var a = b("<select>"),
                c = this.valueField,
                d = this.textField,
                e = this.selectedIndex;
            return b.each(this.items, function(f, g) {
                var h = c ? g[c] : f,
                    i = d ? g[d] : g,
                    j = b("<option>").attr("value", h).text(i).appendTo(a);
                j.prop("selected", e === f)
            }), a.prop("disabled", !!this.readOnly), a
        }
    }), a.fields.select = a.SelectField = d
}(jsGrid, jQuery),
function(a, b, c) {
    function d(a) { e.call(this, a) }
    var e = a.Field;
    d.prototype = new e({
        sorter: "number",
        align: "center",
        autosearch: !0,
        itemTemplate: function(a) { return this._createCheckbox().prop({ checked: a, disabled: !0 }) },
        filterTemplate: function() {
            if (!this.filtering) return "";
            var a = this._grid,
                c = this.filterControl = this._createCheckbox();
            return c.prop({ readOnly: !0, indeterminate: !0 }), c.on("click", function() {
                var a = b(this);
                a.prop("readOnly") ? a.prop({ checked: !1, readOnly: !1 }) : a.prop("checked") || a.prop({ readOnly: !0, indeterminate: !0 })
            }), this.autosearch && c.on("click", function() { a.search() }), c
        },
        insertTemplate: function() { return this.inserting ? this.insertControl = this._createCheckbox() : "" },
        editTemplate: function(a) { if (!this.editing) return this.itemTemplate.apply(this, arguments); var b = this.editControl = this._createCheckbox(); return b.prop("checked", a), b },
        filterValue: function() { return this.filterControl.get(0).indeterminate ? c : this.filterControl.is(":checked") },
        insertValue: function() { return this.insertControl.is(":checked") },
        editValue: function() { return this.editControl.is(":checked") },
        _createCheckbox: function() { return b("<input>").attr("type", "checkbox") }
    }), a.fields.checkbox = a.CheckboxField = d
}(jsGrid, jQuery),
function(a, b) {
    function c(a) { d.call(this, a), this._configInitialized = !1 }
    var d = a.Field;
    c.prototype = new d({
        css: "jsgrid-control-field",
        align: "center",
        width: 50,
        filtering: !1,
        inserting: !1,
        editing: !1,
        sorting: !1,
        buttonClass: "jsgrid-button",
        modeButtonClass: "jsgrid-mode-button",
        modeOnButtonClass: "jsgrid-mode-on-button",
        searchModeButtonClass: "jsgrid-search-mode-button",
        insertModeButtonClass: "jsgrid-insert-mode-button",
        editButtonClass: "jsgrid-edit-button",
        deleteButtonClass: "jsgrid-delete-button",
        searchButtonClass: "jsgrid-search-button",
        clearFilterButtonClass: "jsgrid-clear-filter-button",
        insertButtonClass: "jsgrid-insert-button",
        updateButtonClass: "jsgrid-update-button",
        cancelEditButtonClass: "jsgrid-cancel-edit-button",
        searchModeButtonTooltip: "Switch to searching",
        insertModeButtonTooltip: "Switch to inserting",
        editButtonTooltip: "Edit",
        deleteButtonTooltip: "Delete",
        searchButtonTooltip: "Search",
        clearFilterButtonTooltip: "Clear filter",
        insertButtonTooltip: "Insert",
        updateButtonTooltip: "Update",
        cancelEditButtonTooltip: "Cancel edit",
        editButton: !0,
        deleteButton: !0,
        clearFilterButton: !0,
        modeSwitchButton: !0,
        _initConfig: function() { this._hasFiltering = this._grid.filtering, this._hasInserting = this._grid.inserting, this._hasInserting && this.modeSwitchButton && (this._grid.inserting = !1), this._configInitialized = !0 },
        headerTemplate: function() {
            this._configInitialized || this._initConfig();
            var a = this._hasFiltering,
                b = this._hasInserting;
            return this.modeSwitchButton && (a || b) ? a && !b ? this._createFilterSwitchButton() : b && !a ? this._createInsertSwitchButton() : this._createModeSwitchButton() : ""
        },
        itemTemplate: function(a, c) { var d = b([]); return this.editButton && (d = d.add(this._createEditButton(c))), this.deleteButton && (d = d.add(this._createDeleteButton(c))), d },
        filterTemplate: function() { var a = this._createSearchButton(); return this.clearFilterButton ? a.add(this._createClearFilterButton()) : a },
        insertTemplate: function() { return this._createInsertButton() },
        editTemplate: function() { return this._createUpdateButton().add(this._createCancelEditButton()) },
        _createFilterSwitchButton: function() { return this._createOnOffSwitchButton("filtering", this.searchModeButtonClass, !0) },
        _createInsertSwitchButton: function() { return this._createOnOffSwitchButton("inserting", this.insertModeButtonClass, !1) },
        _createOnOffSwitchButton: function(a, c, d) {
            var e = d,
                f = b.proxy(function() { g.toggleClass(this.modeOnButtonClass, e) }, this),
                g = this._createGridButton(this.modeButtonClass + " " + c, "", function(b) { e = !e, b.option(a, e), f() });
            return f(), g
        },
        _createModeSwitchButton: function() {
            var a = !1,
                c = b.proxy(function() { d.attr("title", a ? this.searchModeButtonTooltip : this.insertModeButtonTooltip).toggleClass(this.insertModeButtonClass, !a).toggleClass(this.searchModeButtonClass, a) }, this),
                d = this._createGridButton(this.modeButtonClass, "", function(b) { a = !a, b.option("inserting", a), b.option("filtering", !a), c() });
            return c(), d
        },
        _createEditButton: function(a) { return this._createGridButton(this.editButtonClass, this.editButtonTooltip, function(b, c) { b.editItem(a), c.stopPropagation() }) },
        _createDeleteButton: function(a) { return this._createGridButton(this.deleteButtonClass, this.deleteButtonTooltip, function(b, c) { b.deleteItem(a), c.stopPropagation() }) },
        _createSearchButton: function() { return this._createGridButton(this.searchButtonClass, this.searchButtonTooltip, function(a) { a.search() }) },
        _createClearFilterButton: function() { return this._createGridButton(this.clearFilterButtonClass, this.clearFilterButtonTooltip, function(a) { a.clearFilter() }) },
        _createInsertButton: function() { return this._createGridButton(this.insertButtonClass, this.insertButtonTooltip, function(a) { a.insertItem().done(function() { a.clearInsert() }) }) },
        _createUpdateButton: function() { return this._createGridButton(this.updateButtonClass, this.updateButtonTooltip, function(a, b) { a.updateItem(), b.stopPropagation() }) },
        _createCancelEditButton: function() { return this._createGridButton(this.cancelEditButtonClass, this.cancelEditButtonTooltip, function(a, b) { a.cancelEdit(), b.stopPropagation() }) },
        _createGridButton: function(a, c, d) { var e = this._grid; return b("<input>").addClass(this.buttonClass).addClass(a).attr({ type: "button", title: c }).on("click", function(a) { d(e, a) }) },
        editValue: function() { return "" }
    }), a.fields.control = a.ControlField = c
}(jsGrid, jQuery);