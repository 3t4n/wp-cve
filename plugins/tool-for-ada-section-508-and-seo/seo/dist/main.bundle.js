webpackJsonp(["main"],{

/***/ "../../../../../src/$$_lazy_route_resource lazy recursive":
/***/ (function(module, exports) {

function webpackEmptyAsyncContext(req) {
	// Here Promise.resolve().then() is used instead of new Promise() to prevent
	// uncatched exception popping up in devtools
	return Promise.resolve().then(function() {
		throw new Error("Cannot find module '" + req + "'.");
	});
}
webpackEmptyAsyncContext.keys = function() { return []; };
webpackEmptyAsyncContext.resolve = webpackEmptyAsyncContext;
module.exports = webpackEmptyAsyncContext;
webpackEmptyAsyncContext.id = "../../../../../src/$$_lazy_route_resource lazy recursive";

/***/ }),

/***/ "../../../../../src/app/app-routing.module.ts":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return AppRoutingModule; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__("../../../core/esm5/core.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__angular_router__ = __webpack_require__("../../../router/esm5/router.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__media_media_component__ = __webpack_require__("../../../../../src/app/media/media.component.ts");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__post_post_component__ = __webpack_require__("../../../../../src/app/post/post.component.ts");
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};




var routes = [
    { path: '', component: __WEBPACK_IMPORTED_MODULE_2__media_media_component__["a" /* MediaComponent */] },
    { path: 'post', component: __WEBPACK_IMPORTED_MODULE_3__post_post_component__["a" /* PostComponent */] },
];
var AppRoutingModule = (function () {
    function AppRoutingModule() {
    }
    AppRoutingModule = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["J" /* NgModule */])({
            imports: [__WEBPACK_IMPORTED_MODULE_1__angular_router__["a" /* RouterModule */].forRoot(routes, { useHash: true })],
            exports: [__WEBPACK_IMPORTED_MODULE_1__angular_router__["a" /* RouterModule */]]
        })
    ], AppRoutingModule);
    return AppRoutingModule;
}());



/***/ }),

/***/ "../../../../../src/app/app.component.css":
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__("../../../../css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "", ""]);

// exports


/*** EXPORTS FROM exports-loader ***/
module.exports = module.exports.toString();

/***/ }),

/***/ "../../../../../src/app/app.component.html":
/***/ (function(module, exports) {

module.exports = "<div class=\"container py-3\">\n  <div class=\"row\">\n    <div class=\"col-12\" >\n        <button type=\"button\" class=\"btn btn-info mx-1\" routerLinkActive=\"active\" routerLink=\"/\" [routerLinkActiveOptions]=\"{ exact: true }\" placement=\"bottom\" ngbTooltip=\"Find all pictures in the media library that are missing atleast one of the catagories chosen.\">Media</button>\n        <button type=\"button\" class=\"btn btn-info mx-1\" routerLinkActive=\"active\" routerLink=\"/post\" [routerLinkActiveOptions]=\"{ exact: true }\" placement=\"bottom\" ngbTooltip=\"Find all pictures that are already posted on your website missing atleast one of the catagories chosen.\">Posts / Pages</button>\n    </div>\n  </div>\n</div>\n<router-outlet></router-outlet>\n"

/***/ }),

/***/ "../../../../../src/app/app.component.ts":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return AppComponent; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__("../../../core/esm5/core.js");
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};

var AppComponent = (function () {
    function AppComponent() {
    }
    AppComponent = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["n" /* Component */])({
            selector: 'app-root',
            template: __webpack_require__("../../../../../src/app/app.component.html"),
            styles: [__webpack_require__("../../../../../src/app/app.component.css")]
        }),
        __metadata("design:paramtypes", [])
    ], AppComponent);
    return AppComponent;
}());



/***/ }),

/***/ "../../../../../src/app/app.module.ts":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return AppModule; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_platform_browser__ = __webpack_require__("../../../platform-browser/esm5/platform-browser.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__angular_core__ = __webpack_require__("../../../core/esm5/core.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__app_routing_module__ = __webpack_require__("../../../../../src/app/app-routing.module.ts");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__angular_forms__ = __webpack_require__("../../../forms/esm5/forms.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__app_component__ = __webpack_require__("../../../../../src/app/app.component.ts");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__media_media_component__ = __webpack_require__("../../../../../src/app/media/media.component.ts");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_6__angular_http__ = __webpack_require__("../../../http/esm5/http.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_7_ngx_loading__ = __webpack_require__("../../../../ngx-loading/ngx-loading/ngx-loading.es5.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_8__media_service__ = __webpack_require__("../../../../../src/app/media.service.ts");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_9__post_service__ = __webpack_require__("../../../../../src/app/post.service.ts");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_10__post_post_component__ = __webpack_require__("../../../../../src/app/post/post.component.ts");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_11__ng_bootstrap_ng_bootstrap__ = __webpack_require__("../../../../@ng-bootstrap/ng-bootstrap/index.js");
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};












var AppModule = (function () {
    function AppModule() {
    }
    AppModule = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_1__angular_core__["J" /* NgModule */])({
            declarations: [
                __WEBPACK_IMPORTED_MODULE_4__app_component__["a" /* AppComponent */],
                __WEBPACK_IMPORTED_MODULE_5__media_media_component__["a" /* MediaComponent */],
                __WEBPACK_IMPORTED_MODULE_10__post_post_component__["a" /* PostComponent */]
            ],
            imports: [
                __WEBPACK_IMPORTED_MODULE_0__angular_platform_browser__["a" /* BrowserModule */],
                __WEBPACK_IMPORTED_MODULE_2__app_routing_module__["a" /* AppRoutingModule */],
                __WEBPACK_IMPORTED_MODULE_6__angular_http__["c" /* HttpModule */],
                __WEBPACK_IMPORTED_MODULE_3__angular_forms__["a" /* FormsModule */],
                __WEBPACK_IMPORTED_MODULE_7_ngx_loading__["a" /* LoadingModule */],
                __WEBPACK_IMPORTED_MODULE_11__ng_bootstrap_ng_bootstrap__["a" /* NgbModule */].forRoot()
            ],
            providers: [__WEBPACK_IMPORTED_MODULE_8__media_service__["a" /* MediaService */], __WEBPACK_IMPORTED_MODULE_9__post_service__["a" /* PostService */]],
            bootstrap: [__WEBPACK_IMPORTED_MODULE_4__app_component__["a" /* AppComponent */]]
        })
    ], AppModule);
    return AppModule;
}());



/***/ }),

/***/ "../../../../../src/app/media.service.ts":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return MediaService; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__("../../../core/esm5/core.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__angular_http__ = __webpack_require__("../../../http/esm5/http.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_rxjs_add_operator_map__ = __webpack_require__("../../../../rxjs/_esm5/add/operator/map.js");
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};




var MediaService = (function () {
    function MediaService(http) {
        this.http = http;
        this.baseurl = window.config.baseurl;
        this.nones = window.config.nones;
        this.get_media_url = this.baseurl + '/wp-json/dvin508-seo/v1/media/missing';
        this.update_media_url = this.baseurl + '/wp-json/dvin508-seo/v1/update_media';
        this.page_number = 1;
    }
    /* get media list of any type of media */
    MediaService.prototype.get_media_list = function () {
        return this.http.get(this.get_media_url + '/' + this.media_type + '/' + this.page_number, { headers: new __WEBPACK_IMPORTED_MODULE_1__angular_http__["a" /* Headers */]({ 'X-WP-Nonce': this.nones }) });
    };
    /* change type of media and reset page number to 1 so new type is initialized from 1*/
    MediaService.prototype.reset_media_type = function (media_type) {
        this.page_number = 1; // setting page number to 1 will make sure we are loading new type with page 1
        this.media_type = media_type;
    };
    MediaService.prototype.next_page = function () {
        if ((this.page_number + 1) <= this.max_pages) {
            this.page_number = this.page_number + 1;
            return true;
        }
        return false;
    };
    MediaService.prototype.previous_page = function () {
        if ((this.page_number) > 1) {
            this.page_number = this.page_number - 1;
            return true;
        }
        return false;
    };
    MediaService.prototype.update_media = function (media) {
        return this.http.post(this.update_media_url, JSON.stringify(media), { headers: new __WEBPACK_IMPORTED_MODULE_1__angular_http__["a" /* Headers */]({ 'Content-Type': 'application/json', 'X-WP-Nonce': this.nones }) });
    };
    MediaService = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["B" /* Injectable */])(),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1__angular_http__["b" /* Http */]])
    ], MediaService);
    return MediaService;
}());



/***/ }),

/***/ "../../../../../src/app/media/media.component.css":
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__("../../../../css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "td{\r\n    vertical-align: middle;\r\n}\r\n\r\nth{\r\n    text-align: center;\r\n}\r\n\r\n.hidden{\r\n    display:none;\r\n}\r\n\r\n.f-10{\r\n    font-size:8px;\r\n}", ""]);

// exports


/*** EXPORTS FROM exports-loader ***/
module.exports = module.exports.toString();

/***/ }),

/***/ "../../../../../src/app/media/media.component.html":
/***/ (function(module, exports) {

module.exports = "<ngx-loading [show]=\"loading\" [config]=\"{ backdropBorderRadius: '0px' }\"></ngx-loading>\n<div class=\"container\">\n  <div class=\"row\">\n    <div class=\"col-12 pt-2 pb-2\">\n      <div class=\"btn-group- btn-group-toggle-\" data-toggle=\"buttons\">\n        <label for=\"option1\" class=\"btn btn-info\" [ngClass]=\"{'active': media_type == 'all' }\" placement=\"bottom\" ngbTooltip=\"Choose this to see all pictures that are in the category that you have chosen.\">\n          <input type=\"radio\" [(ngModel)] = \"media_type\" (ngModelChange)=\"change_media_type($event)\" name=\"media_type\" id=\"option1\" autocomplete=\"off\" value=\"all\" > Show All Media\n        </label>\n        <label for=\"option2\" class=\"btn btn-info\" [ngClass]=\"{'active': media_type == 'caption' }\"  placement=\"bottom\" ngbTooltip=\"Choose this to see all pictures that are missing text in the pictures Caption.\n        \">\n          <input type=\"radio\" [(ngModel)] = \"media_type\" (ngModelChange)=\"change_media_type($event)\" name=\"media_type\" id=\"option2\" autocomplete=\"off\" value=\"caption\"> Missing Caption\n        </label>\n        <label for=\"option3\" class=\"btn btn-info\" [ngClass]=\"{'active': media_type == 'alt' }\" placement=\"bottom\" ngbTooltip=\"Choose this to see all pictures that are missing text in the pictures Alt Text.\n        \">\n          <input type=\"radio\" [(ngModel)] = \"media_type\" (ngModelChange)=\"change_media_type($event)\" name=\"media_type\" id=\"option3\" autocomplete=\"off\" value=\"alt\"   > Missing Alt\n        </label>\n        <label for=\"option4\" class=\"btn btn-info\" [ngClass]=\"{'active': media_type == 'content' }\" placement=\"bottom\" ngbTooltip=\"Choose this to see all pictures that are missing text in the pictures Description.\n        \">\n          <input type=\"radio\" [(ngModel)] = \"media_type\" (ngModelChange)=\"change_media_type($event)\" name=\"media_type\" id=\"option4\" autocomplete=\"off\" value=\"content\"   > Missing Description\n        </label>\n      </div>\n    </div>\n  </div>\n  <h2>Media Images</h2>\n  <div class=\"row\">\n    <div class=\"col-12\">\n<table class=\"bws-table\">\n  <thead > \n    <tr >\n      <th></th>\n      <th>Image</th>\n      <th>Caption</th>\n      <th>Alternate text</th>\n      <th>Description</th>\n      <th></th>\n    </tr>\n  </thead>\n  <tbody>\n    <tr *ngIf = \"media_list\">\n          <td colspan=\"2\"><strong>Add missing text to <br>all pictures in this category</strong></td>\n          <td><input type=\"text\" #to_all_caption placeholder=\"Caption\"></td>\n          <td><input type=\"text\" #to_all_alt placeholder=\"Alternate Text\"></td>\n          <td><input type=\"text\" #to_all_desc placeholder=\"Description\"></td>\n          <td><button placement=\"top\" ngbTooltip=\"Click this button to add your text to only the photos you have selected.\" class=\"btn btn-info btn-sm f-10\" (click)=\"add_to_selected(to_all_caption.value,to_all_alt.value, to_all_desc.value)\">Add to Selected</button><button class=\"btn btn-info btn-sm f-10 ml-1\" (click)=\"add_to_all(to_all_caption.value,to_all_alt.value, to_all_desc.value)\" placement=\"bottom\" ngbTooltip=\"Click this button to Add to all pictures missing the text in the category that you added the text to.\">Add to all</button></td>\n    </tr>\n    <tr *ngFor=\"let media of media_list\">\n      <td><input name=\"selected_media\" type=\"checkbox\" (change)=\"selected_media_list(media.id, $event)\" ></td>\n      <td [innerHTML]=\"media.image\"></td>\n      <td><input type=\"text\" [(ngModel)]=\"media.caption\" class=\"\"></td>\n      <td><input type=\"text\" [(ngModel)]=\"media.alt\" class=\"\"></td>\n      <td><textarea [(ngModel)]=\"media.description\" class=\"\"></textarea></td>\n      <td><button class=\"btn btn-info btn-sm\" (click)=\"update_media(media_list.indexOf(media), $event)\" placement=\"bottom\" ngbTooltip=\"Click this button when you are done to Update this picture with the text that you added.\n        \">Update</button></td>\n    </tr>\n    <tr *ngIf = \"!media_list\">\n      <td colspan=\"6\">\n        There is no media in this category\n      </td>\n    </tr>\n  </tbody>\n</table>\n    </div>\n  </div>\n  <div class=\"row my-2\">\n      <div class=\"col-12 text-right mb-1\">\n          <button class=\"btn btn-info btn-sm\" (click)=\"update_all($event)\"  placement=\"left\" ngbTooltip=\"Click this button after you have added the missing text to multiple pictures so you can Update all at one time.\" >Update All</button>\n        </div>\n    <div class=\"col-12 text-center\">\n        <nav aria-label=\"Page navigation example\" [ngClass]=\"{'hidden': media.max_pages == 1 }\">\n            <ul class=\"pagination\">\n              <li class=\"page-item\" [ngClass]=\"{'disabled': media.page_number == 1 }\"><a class=\"page-link\" (click)=\"previous_page($event)\" href=\"#\">Previous</a></li>\n              <li class=\"page-item\" *ngFor=\"let page of max_page_array; let i = index\" [ngClass]= \"{'active':(i+1) == media.page_number}\" ><a  class=\"page-link \" (click)=\"go_to_page((i+1),$event)\" href=\"#\">{{i+1}}</a></li>\n              <li class=\"page-item\" [ngClass]=\"{'disabled': media.page_number >= media.max_pages }\"><a class=\"page-link\" (click)=\"next_page($event)\" href=\"#\">Next</a></li>\n            </ul>\n        </nav>\n    </div>\n    \n  </div>\n</div>\n"

/***/ }),

/***/ "../../../../../src/app/media/media.component.ts":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return MediaComponent; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__("../../../core/esm5/core.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__media_service__ = __webpack_require__("../../../../../src/app/media.service.ts");
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};


var MediaComponent = (function () {
    function MediaComponent(media) {
        this.media = media;
        this.loading = false;
        this.media_selected = [];
        this.media_type = 'all';
        this.media.reset_media_type(this.media_type);
    }
    /* get any kind of media list */
    MediaComponent.prototype.get_media_list = function () {
        this.loading = true;
        this.media_list_subscriber();
    };
    /* Media list subscriber function */
    MediaComponent.prototype.media_list_subscriber = function () {
        var _this = this;
        this.media.get_media_list().subscribe(function (res) {
            var data_received = res.json();
            _this.media_list = data_received['data'];
            _this.media.max_pages = data_received['max_pages'];
            _this.max_page_array = Array(_this.media.max_pages).fill(undefined);
            _this.loading = false;
        }, function (error) {
            var data_received = error.json();
            alert(data_received.message);
            _this.loading = false;
        });
    };
    /* get media list bsed on media type selected */
    MediaComponent.prototype.change_media_type = function ($event) {
        this.loading = true;
        this.media.reset_media_type(this.media_type);
        this.media_list_subscriber();
    };
    /* Update Single Media */
    MediaComponent.prototype.update_media = function (index, $event) {
        var _this = this;
        this.loading = true;
        this.media.update_media([this.media_list[index]]).subscribe(function (res) {
            var result = res.json();
            _this.loading = false;
        });
    };
    /* Update all media in list */
    MediaComponent.prototype.update_all = function ($event) {
        var _this = this;
        this.loading = true;
        this.media.update_media(this.media_list).subscribe(function (res) {
            var result = res.json();
            _this.loading = false;
        });
    };
    /* Next page */
    MediaComponent.prototype.next_page = function ($event) {
        if (this.media.next_page()) {
            this.get_media_list();
        }
    };
    /* Previous page */
    MediaComponent.prototype.previous_page = function ($event) {
        if (this.media.previous_page()) {
            this.get_media_list();
        }
    };
    /* Go to perticular page */
    MediaComponent.prototype.go_to_page = function (i, $event) {
        if (i <= this.media.max_pages && i != this.media.page_number) {
            this.media.page_number = i;
            this.get_media_list();
        }
    };
    /* This function will add alt, caption , desc from main feald to all
    images that have missing alt ,desc, caption */
    MediaComponent.prototype.add_to_all = function (caption, alt, desc) {
        (this.media_list).forEach(function (media) {
            if (caption != "" && media.caption == "") {
                media.caption = caption;
            }
            if (alt != "" && media.alt == "") {
                media.alt = alt;
            }
            if (desc != "" && media.description == "") {
                media.description = desc;
            }
        });
    };
    /* */
    MediaComponent.prototype.add_to_selected = function (caption, alt, desc) {
        var _this = this;
        console.log(this.media_selected);
        (this.media_list).forEach(function (media) {
            if (_this.media_selected.indexOf(media.id) != -1) {
                if (caption != "" && media.caption == "") {
                    media.caption = caption;
                }
                if (alt != "" && media.alt == "") {
                    media.alt = alt;
                }
                if (desc != "" && media.description == "") {
                    media.description = desc;
                }
            }
        });
    };
    /* This function is called to add element in selected_media list this is used to
    fill detial only for selected media */
    MediaComponent.prototype.selected_media_list = function (id, event) {
        if (event.target.checked) {
            this.media_selected.push(id);
        }
        else {
            this.media_selected.splice(this.media_selected.indexOf(id), 1);
        }
        //console.log(this.media_selected);
    };
    MediaComponent.prototype.ngOnInit = function () {
        this.get_media_list();
    };
    MediaComponent = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["n" /* Component */])({
            selector: 'app-media',
            template: __webpack_require__("../../../../../src/app/media/media.component.html"),
            styles: [__webpack_require__("../../../../../src/app/media/media.component.css")]
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1__media_service__["a" /* MediaService */]])
    ], MediaComponent);
    return MediaComponent;
}());



/***/ }),

/***/ "../../../../../src/app/post.service.ts":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return PostService; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__("../../../core/esm5/core.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__angular_http__ = __webpack_require__("../../../http/esm5/http.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_rxjs_add_operator_map__ = __webpack_require__("../../../../rxjs/_esm5/add/operator/map.js");
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};




var PostService = (function () {
    function PostService(http) {
        this.http = http;
        this.baseurl = window.config.baseurl;
        this.nones = window.config.nones;
        this.post_type_list = window.config.post_type;
        this.get_post_url = this.baseurl + '/wp-json/dvin508-seo/v1/post_type';
        this.update_post_url = this.baseurl + '/wp-json/dvin508-seo/v1/update_post';
        this.page_number = 1;
    }
    /* get post list of any type of media */
    PostService.prototype.get_post_list = function () {
        return this.http.get(this.get_post_url + '/' + this.post_type + '/' + this.page_number, { headers: new __WEBPACK_IMPORTED_MODULE_1__angular_http__["a" /* Headers */]({ 'X-WP-Nonce': this.nones }) });
    };
    /* change type of post and reset page number to 1 so new type is initialized from 1*/
    PostService.prototype.reset_post_type = function (post_type) {
        this.page_number = 1; // setting page number to 1 will make sure we are loading new type with page 1
        this.post_type = post_type;
    };
    PostService.prototype.update_post = function (media) {
        return this.http.post(this.update_post_url, JSON.stringify(media), { headers: new __WEBPACK_IMPORTED_MODULE_1__angular_http__["a" /* Headers */]({ 'Content-Type': 'application/json', 'X-WP-Nonce': this.nones }) });
    };
    PostService.prototype.next_page = function () {
        if ((this.page_number + 1) <= this.max_pages) {
            this.page_number = this.page_number + 1;
            return true;
        }
        return false;
    };
    PostService.prototype.previous_page = function () {
        if ((this.page_number) > 1) {
            this.page_number = this.page_number - 1;
            return true;
        }
        return false;
    };
    PostService = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["B" /* Injectable */])(),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1__angular_http__["b" /* Http */]])
    ], PostService);
    return PostService;
}());



/***/ }),

/***/ "../../../../../src/app/post/post.component.css":
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__("../../../../css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "td{\r\n    vertical-align: middle;\r\n}\r\n\r\nth{\r\n    text-align: center;\r\n}\r\n\r\n.hidden{\r\n    display:none;\r\n}\r\n\r\n.width-30{\r\n    width:30%;\r\n}\r\n\r\n.bws-thumb{\r\n    width:100px;\r\n    height:100px;\r\n    -o-object-fit: cover;\r\n       object-fit: cover;\r\n    border:2px solid #000;\r\n}", ""]);

// exports


/*** EXPORTS FROM exports-loader ***/
module.exports = module.exports.toString();

/***/ }),

/***/ "../../../../../src/app/post/post.component.html":
/***/ (function(module, exports) {

module.exports = "<ngx-loading [show]=\"loading\" [config]=\"{ backdropBorderRadius: '0px' }\"></ngx-loading>\n<div class=\"container\">\n  <div class=\"row\">\n    <div class=\"col-12 my-2\">\n        <small>Plugin only shows pages and post that have a picture. If it does not have a pic then it will not show those post or page.</small>\n    </div>\n    <!--<div class=\"col-4 pt-2 pb-2\">\n      <select [(ngModel)] = \"post_type\" class=\"\" (ngModelChange)=\"change_post_type($event)\">\n          \n          <option *ngFor=\"let type of post.post_type_list\" value=\"{{type}}\">{{type}}</option>\n\n      </select>\n    </div>-->\n    <div class=\"col-12 pt-2 pb-2\">\n      <div class=\"btn-group- btn-group-toggle-\" data-toggle=\"buttons\">\n        <label for=\"option1\" class=\"btn btn-info\" [ngClass]=\"{'active': post_type == 'post' }\" placement=\"bottom\" ngbTooltip=\"Click this button to show all posts that have images on them and how many images are missing the ALT text.\n        \">\n          <input type=\"radio\" [(ngModel)] = \"post_type\" (ngModelChange)=\"change_post_type($event)\" name=\"media_type\" id=\"option1\" autocomplete=\"off\" value=\"post\" > Show All Posts\n        </label>\n        <label for=\"option2\" class=\"btn btn-info\" [ngClass]=\"{'active': post_type == 'page' }\" placement=\"bottom\" ngbTooltip=\"Click this button to show all pages that have images on them and how many images are missing the ALT text.\n        \">\n          <input type=\"radio\" [(ngModel)] = \"post_type\" (ngModelChange)=\"change_post_type($event)\" name=\"media_type\" id=\"option2\" autocomplete=\"off\" value=\"page\" > Show All Pages\n        </label>\n      </div>\n    </div>\n  </div>\n  <h2>Content Images</h2>\n  <div class=\"row mt-2\">\n    <div class=\"col-12\">\n        <table class=\"bws-table\">\n            <thead> \n              <tr >\n                <!--<th>#</th>-->\n                <th class=\"width-30\">Title</th>\n                <th>Image count</th>\n                <th>Missing Alternate text count</th>\n                <th>Action</th>\n              </tr>\n            </thead>\n        <tbody>\n        <ng-container *ngFor=\"let post of post_list; let i = index;\">\n          <tr>\n         <!-- <td>{{post.id}}</td>-->\n          <td [innerHTML]=\"post.title\"></td>\n          <td>{{post.images.length}}</td>\n          <td>{{missing_alt_count[post.id]}}</td>\n          <td><button class=\"btn btn-info btn-sm mr-2\" (click)=\"hideme[post.id] = !hideme[post.id]\" placement=\"bottom\" ngbTooltip=\"Click this button to edit any images that do not have a ALT text.\n            \">Edit</button><button class=\"btn btn-info btn-sm\" (click)=\"update_post(i, $event)\" placement=\"bottom\" ngbTooltip=\"Click this button to update all of the images that you have added the ALT text.\">Update</button></td>\n          </tr>\n          <ng-container >\n            <tr [hidden]=\"!hideme[post.id]\">\n              <td colspan=\"3\">\n                <div class=\"row align-items-center\">\n                  <div class=\"col-12\">\n                    <input type=\"text\" #alt class=\"form-control\" placeholder=\"Alternate Text\">\n                  </div>\n                </div>\n              </td>\n              <td>\n                <div class=\"row align-items-center\">\n                  <div class=\"col-12\">\n                    <button class=\"btn btn-info btn-sm\" (click)=\"add_to_all(alt.value,i)\"  placement=\"bottom\" ngbTooltip=\"Click this button to Add to all pictures missing the text in the category that you added the text to.\">Add To All</button>\n                  </div>\n                </div>\n              </td>\n            </tr>\n            <tr [hidden]=\"!hideme[post.id]\">\n              <td colspan=\"5\" class=\"padding0\">\n                <div class=\"row align-items-center no-gutters image-col-container\">\n                <ng-container  *ngFor=\"let image of post.images\">\n                  <div class=\"col-6 px-3 py-3 images-column\">\n                      <div class=\"row align-items-center\">\n                      <div class=\"col-4\"><img [src]=\"image.src\" class=\"bws-thumb\"></div>\n                      <div class=\"col-8\"><input type=\"text\" [(ngModel)]=\"image.alt\" class=\"form-control\" placeholder=\"Add Missing Alt Text\"></div>\n                      </div>\n                  </div>\n                </ng-container>\n                </div>\n              </td>\n            </tr>\n          </ng-container>\n        </ng-container> \n        <tr *ngIf = \"post_list?.length == 0\">\n          <td colspan=\"4\">\n            There is no post with image\n          </td>\n        </tr> \n      </tbody> \n    </table>         \n    </div>\n  </div>\n  <div class=\"row my-3\">\n      <div class=\"col-12 text-center\">\n          <nav aria-label=\"Page navigation example\" [ngClass]=\"{'hidden': post.max_pages == 1 }\">\n              <ul class=\"pagination\">\n                <li class=\"page-item\" [ngClass]=\"{'disabled': post.page_number == 1 }\"><a class=\"page-link\" (click)=\"previous_page($event)\" >Previous</a></li>\n                <li class=\"page-item\" *ngFor=\"let page of max_page_array; let i = index\" [ngClass]= \"{'active':(i+1) == post.page_number}\" ><a  class=\"page-link \" (click)=\"go_to_page((i+1),$event)\" >{{i+1}}</a></li>\n                <li class=\"page-item\" [ngClass]=\"{'disabled': post.page_number >= post.max_pages }\"><a class=\"page-link\" (click)=\"next_page($event)\" >Next</a></li>\n              </ul>\n          </nav>\n      </div>\n    </div>\n</div>\n"

/***/ }),

/***/ "../../../../../src/app/post/post.component.ts":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return PostComponent; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__("../../../core/esm5/core.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__post_service__ = __webpack_require__("../../../../../src/app/post.service.ts");
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};


var PostComponent = (function () {
    function PostComponent(post) {
        this.post = post;
        this.loading = false;
        this.hideme = [];
        this.missing_alt_count = [];
        this.post_type = 'post';
        this.post.reset_post_type(this.post_type);
    }
    /* get any kind of post list */
    PostComponent.prototype.get_post_list = function () {
        this.loading = true;
        this.post_list_subscriber();
    };
    /* Media list subscriber function */
    PostComponent.prototype.post_list_subscriber = function () {
        var _this = this;
        this.post.get_post_list().subscribe(function (res) {
            var data_received = res.json();
            _this.post_list = data_received['data'];
            console.log(_this.post_list.length);
            _this.post.max_pages = data_received['max_pages'];
            _this.max_page_array = Array(_this.post.max_pages).fill(undefined);
            _this.loading = false;
            _this.missing_alt_total(_this.post_list);
        }, function (error) {
            var data_received = error.json();
            alert(data_received.message);
            _this.loading = false;
        });
    };
    /* setting missing alt count */
    PostComponent.prototype.missing_alt_total = function (data) {
        for (var _i = 0, data_1 = data; _i < data_1.length; _i++) {
            var post = data_1[_i];
            var count = 0;
            for (var _a = 0, _b = post['images']; _a < _b.length; _a++) {
                var image = _b[_a];
                if (image.alt == "") {
                    count++;
                }
            }
            this.missing_alt_count[post.id] = count;
        }
    };
    /* get media list bsed on media type selected */
    PostComponent.prototype.change_post_type = function ($event) {
        this.loading = true;
        this.post.reset_post_type(this.post_type);
        this.post_list_subscriber();
    };
    /* Update Single Media */
    PostComponent.prototype.update_post = function (index, $event) {
        var _this = this;
        this.loading = true;
        this.post.update_post([this.post_list[index]]).subscribe(function (res) {
            var result = res.json();
            _this.missing_alt_total(_this.post_list);
            _this.loading = false;
        });
    };
    /* Next page */
    PostComponent.prototype.next_page = function ($event) {
        if (this.post.next_page()) {
            this.get_post_list();
        }
    };
    /* Previous page */
    PostComponent.prototype.previous_page = function ($event) {
        if (this.post.previous_page()) {
            this.get_post_list();
        }
    };
    /* Go to perticular page */
    PostComponent.prototype.go_to_page = function (i, $event) {
        if (i <= this.post.max_pages && i != this.post.page_number) {
            this.post.page_number = i;
            this.get_post_list();
        }
    };
    /* This function will add alt to all the image of this perticular post */
    PostComponent.prototype.add_to_all = function (alt, index) {
        (this.post_list[index].images).forEach(function (image) {
            if (alt != "" && image.alt == "") {
                image.alt = alt;
            }
        });
    };
    PostComponent.prototype.ngOnInit = function () {
        this.get_post_list();
    };
    PostComponent = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["n" /* Component */])({
            selector: 'app-post',
            template: __webpack_require__("../../../../../src/app/post/post.component.html"),
            styles: [__webpack_require__("../../../../../src/app/post/post.component.css")]
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1__post_service__["a" /* PostService */]])
    ], PostComponent);
    return PostComponent;
}());



/***/ }),

/***/ "../../../../../src/environments/environment.ts":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return environment; });
// The file contents for the current environment will overwrite these during build.
// The build system defaults to the dev environment which uses `environment.ts`, but if you do
// `ng build --env=prod` then `environment.prod.ts` will be used instead.
// The list of which env maps to which file can be found in `.angular-cli.json`.
var environment = {
    production: false,
};


/***/ }),

/***/ "../../../../../src/main.ts":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__("../../../core/esm5/core.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__angular_platform_browser_dynamic__ = __webpack_require__("../../../platform-browser-dynamic/esm5/platform-browser-dynamic.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__app_app_module__ = __webpack_require__("../../../../../src/app/app.module.ts");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__environments_environment__ = __webpack_require__("../../../../../src/environments/environment.ts");




if (__WEBPACK_IMPORTED_MODULE_3__environments_environment__["a" /* environment */].production) {
    Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["_15" /* enableProdMode */])();
}
Object(__WEBPACK_IMPORTED_MODULE_1__angular_platform_browser_dynamic__["a" /* platformBrowserDynamic */])().bootstrapModule(__WEBPACK_IMPORTED_MODULE_2__app_app_module__["a" /* AppModule */])
    .catch(function (err) { return console.log(err); });


/***/ }),

/***/ 0:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__("../../../../../src/main.ts");


/***/ })

},[0]);
//# sourceMappingURL=main.bundle.js.map