const tredAjaxUrl = tred_js_object.ajaxurl;
const tredWpnonce = tred_js_object._wpnonce;
const tredSliceNumberItems = tred_js_object.sliceNumberItems;
const tredSliceNumberDays = tred_js_object.sliceNumberDays;
const tredActions = tred_js_object.tredActions;
const tredFilteredActions = tred_js_object.tredFilteredActions;
const tredColors = tred_js_object.tredColors;
const tredLoadingImgUrl = tred_js_object.tredLoadingImgUrl;
const tredCourseJson = tred_js_object.tredCourseJson;
const tredGlobalJson = tred_js_object.tredGlobalJson;
const tredUserJson = tred_js_object.tredUserJson;
const tredGroupJson = tred_js_object.tredGroupJson;
//make a constant containing all jsons
const tredAllJsons = {
  course: tredCourseJson,
  global: tredGlobalJson,
  user: tredUserJson,
  group: tredGroupJson,
};
const tredAccessModes = tred_js_object.tredAccessModes;
const tredWidgetsToShow = tred_js_object.tredWidgetsToShow;
const tredWidgetsTranslation = tred_js_object.tredWidgetsTranslation;
const tredElementsTranslation = tred_js_object.tredElementsTranslation;
const tredTableTranslation = tred_js_object.tredTableTranslation;
const tredItemsLabels = tred_js_object.tredItemsLabels;
const tredSiteName = tredDecodeAmp(tred_js_object.tredSiteName);
const tredCsvLabels = tred_js_object.tredCsvLabels;
const tredProActivated = tred_js_object.tredProActivated;

if (typeof Chart !== "undefined" && Chart.version !== "3.4.1") {
  console.warn(
    "Another version of Chart.js detected. This might cause conflicts with the Easy Dash for LearnDash plugin."
  );
}

function TredGetTimeText(timeInSec) {
  //if timeInSec is negative number, return 'N/A'
  if (timeInSec < 0) {
    return "N/A";
  }
  const days = Math.floor(timeInSec / (3600 * 24));
  const hours = Math.floor((timeInSec % (3600 * 24)) / 3600);
  const minutes = Math.floor((timeInSec % 3600) / 60);
  const seconds = Math.floor(timeInSec % 60);
  let output = "";
  if (days) {
    output += `${days}d `;
  }
  if (hours) {
    output += `${hours}h `;
  }
  if (minutes) {
    output += `${minutes}m `;
  }
  if (seconds) {
    output += `${seconds}s`;
  }
  return output;
}

//MOUNT HTML ELEMENTS FUNCTIONS
function tredMountBox(box, translationObject = {}) {
  let title = translationObject.box.title.hasOwnProperty(box["title"])
    ? translationObject.box.title[box["title"]]
    : box["title"];
  let obs = translationObject.box.obs.hasOwnProperty(box["obs"])
    ? translationObject.box.obs[box["obs"]]
    : box["obs"];
  return `
    <div class="w-full md:w-1/2 xl:w-1/3 p-6 tred-widgets-parents" data-widget-number="${box["number"]}">
        <!--Metric Card-->
        <button type="button" data-widget-id="${box["id"]}" data-widget-number="${box["number"]}" class="tred-remove-widget" style="display:none;">
            <span>&times;</span>
        </button>
        <div class="bg-gradient-to-b from-${box["color"]}-200 to-${box["color"]}-100 border-b-4 border-${box["color"]}-600 rounded-lg shadow-xl p-5">
            <div class="flex flex-row items-center">
                <div class="flex-shrink pr-4">
                    <div class="rounded-full p-5 bg-${box["color"]}-600">
                        <i class="fa fa-${box["icon_class"]} fa-2x fa-inverse"></i>
                    </div>
                </div>
                <div class="flex-1 text-right md:text-center">
                    <h5 class="font-bold uppercase text-gray-600 tred-widget-title">
                        ${title}
                    </h5>
                    <h3 class="font-bold text-3xl">
                        <span id="${box["id"]}">
                            <img class="tred-loading-img inline border-none" alt="load"
                                src="${tredLoadingImgUrl}">
                        </span>
                    </h3>
                    <span class="font-thin text-xs tred-box-obs" id="${box["id"]}-obs">
                        ${obs}
                    </span>
                </div>
            </div>
        </div>
        <!--/Metric Card-->
    </div> 
    `;
}

function tredMountChart(chart, translationObject = {}) {
  let title = translationObject.chart.title.hasOwnProperty(chart["title"])
    ? translationObject.chart.title[chart["title"]]
    : chart["title"];
  let obs = translationObject.chart.obs.hasOwnProperty(chart["obs"])
    ? translationObject.chart.obs[chart["obs"]]
    : chart["obs"];
  return `
    <div class="w-full md:w-1/2 xl:w-1/2 p-6 tred-widgets-parents" data-widget-number="${chart["number"]}">
        <!--Graph Card-->
        <button type="button" data-widget-id="${chart["id"]}" data-widget-number="${chart["number"]}" class="tred-remove-widget" style="display:none;">
            <span>&times;</span>
        </button>
        <div class="bg-white border-transparent rounded-lg shadow-xl">
            <div
                class="bg-gradient-to-b from-gray-300 to-gray-100 uppercase text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
                <h5 class="font-bold uppercase text-gray-600 tred-widget-title">
                    ${title}
                </h5>
            </div>
            <div class="p-5">
                <div class="chartjs-size-monitor">
                    <div class="chartjs-size-monitor-expand">
                        <div class=""></div>
                    </div>
                    <div class="chartjs-size-monitor-shrink">
                        <div class=""></div>
                    </div>
                </div>
                <canvas id="${chart["id"]}" class="chartjs chartjs-render-monitor"
                    width="625" height="312" style="display: block; width: 625px; height: 312px;"></canvas>
                <div class="text-center">
                    <span class="font-thin pl-2" id="${chart["id"]}-obs"
                        style="font-size: 0.8em;">
                        ${obs}
                    </span>
                    <!-- <span class="font-thin pl-2" style="font-size: 0.6em;">
                    Atualização: 05/03/2021, às 10h16
                    </span> -->
                </div>
            </div>
        </div>
        <!--/Graph Card-->
    </div>
    `;
}

function tredMountTable(table, translationObject = {}) {
  let title = translationObject.table.title.hasOwnProperty(table["title"])
    ? translationObject.table.title[table["title"]]
    : table["title"];
  return `
    <div class="w-full md:w-full xl:w-full p-6 tred-widgets-parents" data-widget-number="${table["number"]}">
        <!--Table Card-->
        <button type="button" data-widget-id="${table["id"]}" data-widget-number="${table["number"]}" class="tred-remove-widget" style="display:none;">
            <span>&times;</span>
        </button>
        <div class="bg-white border-transparent rounded-lg shadow-xl">
            <div
                class="bg-gradient-to-b from-gray-300 to-gray-100 uppercase text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
                <h5 class="font-bold uppercase text-gray-600 tred-widget-title">
                    ${title}
                </h5>
            </div>
            <div class="p-5">
                <table class="w-full text-gray-700 table-auto border"
                    id="${table["id"]}">
  
                    <thead>
                        <tr class="text-sm">
                            <!-- ajax -->
                        </tr>
                    </thead>
  
                    <tbody>
                        <!-- ajax -->
                    </tbody>
  
                </table>
  
                <div class="clear"></div>
                <div class="dt-buttons tred-table-buttons"> 
                    <button class="dt-button tred-table-button" data-notify-html type="button"><span>Copy</span></button>
                    <button class="dt-button tred-table-button" type="button"><span>CSV</span></button>
                    <button class="dt-button tred-table-button" type="button"><span>Excel</span></button>
                    <button class="dt-button tred-table-button" type="button"><span>PDF</span></button>
                    <button class="dt-button tred-table-button" type="button"><span>Print</span></button>
                    <button class="dt-button tred-table-button" type="button"><span>Column Visibility</span></button>
                </div>
                
                <span class="py-2 tred-obs-table" id="obs-${table["id"]}">
                    <!-- ajax -->
                </span>
                <div style="clear: both"></div>
            </div>
        </div>
        <!--/table Card-->
    </div>    
    `;
}
//END MOUNT HTML ELEMENTS FUNCTIONS

//UTILS FUNCTIONS
function tredDecodeAmp(item) {
  return item.replace(/&amp;/g, "&");
}

function tredGetItemJson(item) {
  if (item === "sfwd-courses") {
    return JSON.parse(tredCourseJson);
  } else if (item === "users") {
    return JSON.parse(tredUserJson);
  } else if (item === "groups") {
    return JSON.parse(tredGroupJson);
  }
  // else if(item === 'sfwd-lessons') {
  //     return JSON.parse(tredLessonJson);
  // } else if(item === 'sfwd-quiz') {
  //     return JSON.parse(tredQuizJson);
  // } else if(item === 'sfwd-topic') {
  //     return JSON.parse(tredTopicJson);
  // } else if(item === 'sfwd-certificate') {
  //     return JSON.parse(tredCertificateJson);
  // }

  return false;
}

function getJsonItemsByKey(key, jsons = tredAllJsons) {
  const items = {};
  for (const [jsonKey, jsonValue] of Object.entries(jsons)) {
    const jsonArray =
      typeof jsonValue === "string" ? JSON.parse(jsonValue) : jsonValue;
    if (Array.isArray(jsonArray)) {
      const jsonItems = [];
      for (const item of jsonArray) {
        if (item.hasOwnProperty(key)) {
          jsonItems.push(item);
        }
      }
      if (jsonItems.length > 0) {
        items[jsonKey] = jsonItems;
      }
    }
  }
  return items;
}
//CHILD ROW (2.3.0)
const tredChildRowItems = getJsonItemsByKey("child_row");

//json widgets functions
const tredJsonFunctions = {
  tredCheckModeExistentWidget: function (widget_json, modes = tredAccessModes) {
    let words = widget_json["title"].split(" ");
    let mode = words[0];
    //check if mode is in modes array
    return modes.indexOf(mode.toLowerCase()) > -1;
  },
  tredGetJsonByType: function (type) {
    let output = "";
    if (type === "global") {
      return tredGlobalJson;
    } else if (type === "course") {
      return tredCourseJson;
    } else if (type === "user") {
      return tredUserJson;
    } else if (type === "group") {
      return tredGroupJson;
    } else if (type === "lesson") {
      return tredLessonJson;
    } else if (type === "quiz") {
      return tredQuizJson;
    } else if (type === "topic") {
      return tredTopicJson;
    } else if (type === "certificate") {
      return tredCertificateJson;
    }
    return output;
  },
  tredGetItemJsonById: function (json, id) {
    jsonParsed = JSON.parse(json);
    for (let i = 0; i < jsonParsed.length; i++) {
      if (jsonParsed[i].id === id) {
        return jsonParsed[i];
      }
    }
    return false;
  },
  tredGetItemJsonChildRow: function (json, id) {
    const item = this.tredGetItemJsonById(json, id);
    if (item && item.child_row) {
      return item.child_row;
    }
    return false;
  },
};
//END UTILS FUNCTIONS

//CHILD ROW - courses and users (2.3.0)
function format_user_course_child_row(d, tableId, tr, jsonItem) {
  //Good for user + course stats
  let seconds,
    timeText,
    timeTextLabel,
    stepsReportLabel,
    downloadLabel,
    downloadFile,
    userId,
    courseId,
    output;
  seconds = tr.querySelector("td[data-seconds]").getAttribute("data-seconds");
  if (jsonItem === "user") {
    // console.log(d);
    userId = document
      .querySelector("#tred-title-filtered h2")
      .getAttribute("data-user-id");
    courseId = tr.getAttribute("id"); //could be d.DT_RowId
  } else if (jsonItem === "course") {
    userId = tr.getAttribute("id");
    courseId = document
      .querySelector("#tred-title-filtered h2")
      .getAttribute("data-course-id");
  }
  if (!userId || !Number.isInteger(parseInt(userId))) {
    console.log("userId is not valid");
    console.log(userId);
    return false;
  }
  if (!courseId || !Number.isInteger(parseInt(courseId))) {
    console.log("courseId is not valid");
    console.log(courseId);
    return false;
  }
  if (!seconds || !Number.isInteger(parseInt(seconds))) {
    console.log("seconds is not valid");
    console.log(seconds);
    return false;
  }
  output =
    '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">' +
    "<tr class='tr-child'>" +
    "<td>{{tr1_td1_text}}:</td>" +
    "<td>{{tr1_td2_text}}</td>" +
    "</tr>" +
    "<tr>" +
    "<td>{{tr2_td1_text}}:</td>" +
    "<td>{{tr1_td2_text}}</td>" +
    "</tr class='tr-child'>" +
    "</table>";

  timeText = TredGetTimeText(parseInt(seconds));
  timeTextLabel = tredCsvLabels["Time Spent in Course"];
  stepsReportLabel = tredCsvLabels["Course Report"];
  downloadLabel = tredCsvLabels["Download CSV"];

  //create a link with current URL and a query "action=tred_pro_download_csv_data"
  let url = new URL(window.location.href);
  url.searchParams.set("action", "tred_pro_download_csv_data");
  url.searchParams.set("table", tableId);
  url.searchParams.set("user_id", userId);
  url.searchParams.set("course_id", courseId);
  //apply tredWpnonce to the url
  url.searchParams.set("_wpnonce", tredWpnonce);
  downloadFile =
    '<a href="' +
    url.href +
    '" target="_blank" id="' +
    tableId +
    "_" +
    courseId +
    '_csv_download">' +
    downloadLabel +
    "</a>";

  output = output.replace("{{tr1_td1_text}}", timeTextLabel);
  output = output.replace("{{tr1_td2_text}}", timeText);
  output = output.replace("{{tr2_td1_text}}", stepsReportLabel);
  output = output.replace("{{tr1_td2_text}}", downloadFile);
  return output;
}

//CHILD ROW - groups (2.4.0)
function format_group_user_course_child_row(d, tableId, tr, jsonItem) {
  //Good for group stats
  let downloadFile, groupId, targetId, userId, courseId, output, url;

  groupId = document
    .querySelector("#tred-title-filtered h2")
    .getAttribute("data-group-id");
  if (!groupId || !Number.isInteger(parseInt(groupId))) {
    console.log("courseId is not valid");
    console.log(courseId);
    return false;
  }
  targetId = tr.getAttribute("id");
  if (!targetId || !Number.isInteger(parseInt(targetId))) {
    console.log("targetId is not valid");
    console.log(targetId);
    return false;
  }
  output =
    '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">';

  if (tableId === "table-group-course-completions") {
    courseId = targetId;
    url = new URL(window.location.href);
    url.searchParams.set("action", "tred_pro_download_csv_data");
    url.searchParams.set("table", tableId);
    url.searchParams.set("group_id", groupId);
    url.searchParams.set("course_id", courseId);
    //apply tredWpnonce to the url
    url.searchParams.set("_wpnonce", tredWpnonce);
    downloadFile = `<a href="${url.href}" target="_blank" id="${tableId}_${courseId}_csv_download">${tredCsvLabels["Download CSV"]}</a>`;
    output += `<tr"><td>${tredCsvLabels["Students Report"]}:</td>`;
    output += `<td>${downloadFile}</td></tr>`;
    output += "</table>";
  } else if (tableId === "table-group-users") {
    userId = targetId;

    //get courses names and IDs from the table-group-course-completions table
    let coursesTable = document.querySelector(
      "#table-group-course-completions"
    );
    let coursesTableBody = coursesTable.querySelector("tbody");
    //select all tr from the table that have at least one class
    let coursesTableRows = coursesTableBody.querySelectorAll("tr.text-sm");
    // let coursesTableRows = coursesTableBody.querySelectorAll("tr[id])");
    let groupCourses = [];
    coursesTableRows.forEach((row) => {
      let course = {};
      course.id = row.getAttribute("id");
      //get course name from the second td
      course.name = row.querySelector("td:nth-child(3)").innerText;
      groupCourses.push(course);
    });

    //for each course, create a row with a link to download the CSV
    groupCourses.forEach((course) => {
      //create a link with current URL and a query "action=tred_pro_download_csv_data"
      url = new URL(window.location.href);
      url.searchParams.set("action", "tred_pro_download_csv_data");
      url.searchParams.set("table", tableId);
      url.searchParams.set("user_id", userId);
      url.searchParams.set("course_id", course.id);
      //apply tredWpnonce to the url
      url.searchParams.set("_wpnonce", tredWpnonce);
      downloadFile = `<a href="${url.href}" target="_blank" id="${tableId}_${course.id}_csv_download">${tredCsvLabels["Download CSV"]}</a>`;
      output += `<tr><td>${course.name} (${tredCsvLabels["Student Report"]}):</td><td>${downloadFile}</td></tr>`;
    });

    output += "</table>";
  }
  return output;
}

jQuery(document).ready(function ($) {
  //CHILD ROW (2.3.0)
  $.fn.tredToggleChildRow = function (jsonItem, tableId) {
    // Add event listener for opening and closing details
    $("#" + tableId).on("click", "td.dt-control", function () {
      const tr = $(this).closest("tr");
      const tr_js = tr[0]; //convert tr to vanilla js element (tr[0])
      const table = $("#" + tableId).DataTable();
      const row = table.row(tr);
      //courses and users filtered
      if (row.child.isShown()) {
        // This row is already open - close it
        row.child.hide();
        tr.removeClass("shown");
      } else {
        if (jsonItem === "course" || jsonItem === "user") {
          // Open this row
          row
            .child(
              format_user_course_child_row(row.data(), tableId, tr_js, jsonItem)
            )
            .show();
          tr.addClass("shown");
        } else if (jsonItem === "group") {
          // Open this row
          row
            .child(
              format_group_user_course_child_row(
                row.data(),
                tableId,
                tr_js,
                jsonItem
              )
            )
            .show();
          tr.addClass("shown");
        }

        //if not tredProActivated, run notify("Pro feature. Click to get it") on link click
        if (!tredProActivated) {
          const csvLink = $('[id$="_csv_download"]');

          csvLink.on("click", function (e) {
            e.preventDefault();
            linkClicked = $(this);
            linkTd = linkClicked.closest("td");
            linkTr = linkClicked.closest("tr");

            if ($.notify !== undefined) {
              $.notify.addStyle("tred", {
                html: "<div><span data-notify-html/></div>",
                classes: {
                  base: {
                    "white-space": "nowrap",
                    "background-color": "#D97706",
                    padding: "5px",
                    color: "white",
                    width: "auto",
                    "font-size": "24px",
                  },
                },
              });
            }
            tr.notify(
              "<a href='https://wptrat.com/easy-dash-for-learndash?from=csv_link'>Pro feature. Click to get it!</a>",
              {
                style: "tred",
                position: "bottom-center",
              }
            );
          });
        }
      }
    });
  };

  //SHORTCODE TAB - BUTTONS TOGGLING SECTIONS
  $(".tred-shortcode-section-buttons").on("click", function () {
    let btnClicked = $(this);
    let section = btnClicked.attr("data-section");

    //if the string 'filtered exists in section, show select element, else hide
    if (section.indexOf("filtered") > -1) {
      $("#tred-settings-shortcode-filtered-select").show();
    } else {
      $("#tred-settings-shortcode-filtered-select").hide();
    }

    $(".tred-shortcode-section-buttons").removeClass("button-primary");
    btnClicked.addClass("button-primary");
    $(".tred-shortcode-section").hide();
    $("#" + section).show();
    if ($("#" + section).is(":visible")) {
      $("#" + section)
        .siblings(".tred-shortcode-section")
        .hide();
    } else {
      $("#" + section)
        .siblings(".tred-shortcode-section")
        .show();
    }
  });
  //END - SHORTCODE TAB - BUTTONS TOGGLING SECTIONS

  //SHOW-HIDE WIDGETS FUNCTIONS

  //function to get all widgets and only hide the ones that are not in options (server)
  //maybe redundant (see $.fn.tredHideWidgetsBasedOnOptions). A todo here...
  $.fn.tredGetWidgetsToShow = function (panelType, panelItem, divTab) {
    $.ajax({
      url: tredAjaxUrl,
      type: "get",
      dataType: "json",
      data: {
        action: "tred_ld_get_widget_options",
        _wpnonce: tredWpnonce,
        panel_item: panelItem,
        panel_type: panelType,
      },
      success: function (response) {
        if (response.result !== "success") {
          console.log(response.action + " => " + response.result);
        } else {
          divTab.attr("data-widgets", response.widgets);
        } //end if/else success
      }, //end success callback
    }); //end ajax call
  };

  //function to get all widgets and only hide the ones that are not in 'data-widgets' (HTML attribute)
  //Used when filtering, cause AJAX brings new item stats without reloading the page (when user selects a new one)
  //and there's no need to get options from server each time;
  //it's only a matter of reading the html attribute with the widgets to show
  //(which is repopulated whenever user edits the panel and clicks on 'Save changes')
  $.fn.tredHideWidgetsBasedOnHtmlDataWidgetAttr = function (el) {
    let divTab = el.closest(".tred-easydash-tab");
    let widgetsToShow = divTab.attr("data-widgets");
    let widgetsParents = divTab.find(".tred-widgets-parents");
    if (!widgetsToShow) {
      return false;
    }
    widgetsArr = widgetsToShow.split(",");
    if (!widgetsArr) {
      return false;
    }
    //for each widgetsParents, hide the ones that are not in widgetsToShow
    let widgetParent, widgetId;
    widgetsParents.each(function (i, elem) {
      widgetParent = $(elem);
      widgetId = widgetParent.attr("data-widget-number");
      if ($.inArray(widgetId, widgetsArr) === -1) {
        widgetParent.hide();
      } else {
        widgetParent.show();
      }
    });
  };

  //function to get all widgets and only hide the ones that are not in tredWidgetsToShow (options)
  //Used on the first load of the page for global, cause data-widgets is not set yet
  $.fn.tredHideWidgetsBasedOnOptions = function (tredWidgetsToShow, mainDiv) {
    let contentAreaDiv = mainDiv.find(".tred-content-area");
    let type = contentAreaDiv.attr("data-panel-type");
    let item = contentAreaDiv.attr("data-panel-item");
    if (
      !tredWidgetsToShow ||
      !type ||
      !item ||
      tredWidgetsToShow.hasOwnProperty(type) === false ||
      tredWidgetsToShow[type].hasOwnProperty(item) === false
    ) {
      return false;
    }
    let widgetsToShow = tredWidgetsToShow[type][item];
    //get all widgets
    let widgets = mainDiv.find(".tred-widgets-parents");
    //hide all widgets
    widgets.hide();
    //show only the ones that are in widgetsToShow
    for (let i = 0; i < widgetsToShow.length; i++) {
      let widgetNumber = widgetsToShow[i];
      mainDiv
        .find('.tred-remove-widget[data-widget-number="' + widgetNumber + '"]')
        .parent()
        .show();
    }
    mainDiv.attr("data-widgets", widgetsToShow);
  };

  //This functions is used when the dash is rendered on the frontend, with a shortcode.
  //PHP code (on the pro version) is responsible for setting the data-hide, show, types etc attributes on the mainDiv,
  //used by the function
  $.fn.tredHideWidgetsBasedOnMainDivDataAtts = function (mainDiv) {
    let dataHide = mainDiv.attr("data-hide");
    let dataShow = mainDiv.attr("data-show");
    let dataTypes = mainDiv.attr("data-types");
    //let dataTableButtons = mainDiv.attr('data-table-buttons'); //table buttons are handled on the tred-pro.js file (tredRebuildTablePro function, on the pro version)

    if (dataHide && dataHide !== "") {
      //array of widgets to hide
      let widgetsToHide = dataHide.split(",");
      //get all widgets
      let widgets = mainDiv.find(".tred-widgets-parents");
      // for each widget, check if it is in the array of widgets to hide
      widgets.each(function () {
        let widget = $(this);
        let widgetNumber = widget.attr("data-widget-number");
        if (widgetsToHide.indexOf(widgetNumber) > -1) {
          widget.hide();
        }
      });
    }
    if (dataShow && dataShow !== "") {
      //array of widgets to Show
      let widgetsToShow = dataShow.split(",");
      //get all widgets
      let widgets = mainDiv.find(".tred-widgets-parents");
      // for each widget, check if it is in the array of widgets to Show; if not, hide it
      widgets.each(function () {
        let widget = $(this);
        let widgetNumber = widget.attr("data-widget-number");
        if (widgetsToShow.indexOf(widgetNumber) === -1) {
          widget.hide();
        }
      });
    }
    if (dataTypes && dataTypes !== "") {
      //array of widgets types
      let widgetsTypes = dataTypes.split(",");
      if (widgetsTypes.indexOf("box") === -1) {
        //hide top-boxes
        mainDiv.find(".tred-top-banners .tred-widgets-parents").hide();
      }
      if (widgetsTypes.indexOf("chart") === -1) {
        //hide top-boxes
        mainDiv.find(".tred-charts .tred-widgets-parents").hide();
      }
      if (widgetsTypes.indexOf("table") === -1) {
        //hide tables
        mainDiv.find(".tred-tables .tred-widgets-parents").hide();
      }
    }
  };

  //END SHOW-HIDE WIDGETS FUNCTIONS

  //POPULATE AND INJECT FUNCTIONS
  $.fn.tredPopulateWithWidgetsHtmlElements = function (
    itemJson,
    mainDivSelectorId,
    item = ""
  ) {
    let boxToAppend = "";
    let chartToAppend = "";
    let tableToAppend = "";
    let thisItem, functionName;
    let filtered_charts = [];
    let filtered_tables = [];
    let chartFilt, chartStatus;
    let type =
      mainDivSelectorId.indexOf("filter") !== -1 ? "filtered" : "global";
    let translationObject =
      type === "filtered"
        ? tredWidgetsTranslation[item]
        : tredWidgetsTranslation["global"];

    for (let i = 0; i < itemJson.length; i++) {
      thisItem = itemJson[i];
      //conditional show checking...
      if (
        thisItem.hasOwnProperty("conditional_show") &&
        thisItem["conditional_show"].hasOwnProperty("function_name")
      ) {
        functionName = thisItem["conditional_show"]["function_name"];
        if (tredJsonFunctions[functionName](thisItem) === false) {
          continue;
        }
      }
      if (thisItem.widget_type === "box") {
        boxToAppend += tredMountBox(thisItem, translationObject);
      } else if (thisItem.widget_type === "chart") {
        chartToAppend += tredMountChart(thisItem, translationObject);
        //push to filtered_charts
        filtered_charts.push(thisItem);
      } else if (thisItem.widget_type === "table") {
        tableToAppend += tredMountTable(thisItem, translationObject);
        //push to filtered_tables
        filtered_tables.push(thisItem);
      }
    }

    $("#" + mainDivSelectorId)
      .find(".tred-top-banners")
      .html(boxToAppend);
    $("#" + mainDivSelectorId)
      .find(".tred-charts")
      .html(chartToAppend);
    $("#" + mainDivSelectorId)
      .find(".tred-tables")
      .html(tableToAppend);
    if (item && type !== "global") {
      $("#" + mainDivSelectorId)
        .find(".tred-content-area")
        .attr("data-panel-item", item);
    }

    //js for each chart
    for (let i = 0; i < filtered_charts.length; i++) {
      chartFilt = filtered_charts[i];
      chartStatus = Chart.getChart(chartFilt["id"]);
      if (chartStatus != undefined) {
        chartStatus.destroy();
      }
      new Chart(document.getElementById(chartFilt["id"]), {
        type: chartFilt["type"],
        data: {
          labels: ["Label A", "Label B"],
          datasets: [
            {
              label: "Dataset Label",
              data: [20, 40],
              borderColor: "rgb(54, 162, 235)",
              backgroundColor: "rgba(255, 99, 132, 0.2)",
            },
          ],
        },
        options: {
          indexAxis: chartFilt["indexAxis"],
        },
      });
    } //end for
  };

  $.fn.tredInjectTopBoxes = function (objTopBoxes) {
    for (var [key, value] of Object.entries(objTopBoxes)) {
      $("#" + key).text(value);
    }
  };

  $.fn.tredInjectFillers = function (objFillers) {
    for (var [key, value] of Object.entries(objFillers)) {
      $("#" + key).text(value);
    }
  };

  $.fn.tredInjectTables = function (objTables) {
    let table;
    let thead_cols = "";
    let tbody_rows = "";
    let dt;
    let rowId = "";
    let tredJson, jsonItem, tableId, child_row;
    let timeKeys = ["time", "hours_spent", "days_spent", "minutes_spent"];
    for (let i = 0; i < objTables.length; i++) {
      tredJson = tredJsonFunctions.tredGetJsonByType(objTables[i]["type"]);
      tableId = objTables[i]["id"];
      if (tredJson) {
        jsonItem = tredJsonFunctions.tredGetItemJsonById(tredJson, tableId);
      }
      child_row = jsonItem && jsonItem.hasOwnProperty("child_row");
      thead_cols = "";
      tbody_rows = "";
      table = objTables[i];
      if (child_row) {
        thead_cols += "<th></th>";
      }
      //Columns
      for (const label of Object.values(table["keys_labels"])) {
        thead_cols +=
          '<th class="text-left text-blue-900 border">' + label + "</th>";
      } //end for columns

      //Rows
      for (const row of Object.values(table["data"])) {
        if (row.hasOwnProperty("id")) {
          rowId = row["id"];
        }
        tbody_rows += '<tr class="text-sm" id="' + rowId + '">';
        if (child_row) {
          tbody_rows += '<td class="dt-control"></td>';
        }
        for (const key of Object.keys(table["keys_labels"])) {
          //new 2.3.0
          tbody_rows += '<td class="border"';
          if (
            timeKeys.indexOf(key) !== -1 &&
            row.hasOwnProperty("seconds_spent")
          ) {
            tbody_rows +=
              ' data-tooltip="time" data-seconds="' +
              row["seconds_spent"] +
              '"';
          }
          tbody_rows += ">" + row[key] + "</td>";
        }
        tbody_rows += "</tr>";
      } //end for rows
      $("#" + table["id"] + " thead tr").html(thead_cols);
      $("#" + table["id"] + " tbody").html(tbody_rows);
      $("#obs-" + table["id"]).text(table["obs"]);

      //find .tred-main-content parent
      let mainDiv = $("#" + table["id"]).closest(".tred-main-content");
      //get datta attr buttons
      let dataTableButtons = mainDiv.attr("data-table-buttons");
      if (!dataTableButtons) {
        dataTableButtons = "";
      }
      tableOptions = {
        destroy: true, //https://datatables.net/manual/tech-notes/3
        ordering: true,
        initComplete: function (settings, json) {
          if (
            typeof tredPro !== "undefined" &&
            tredPro &&
            typeof tredRebuildTablePro === "function"
          ) {
            tredRebuildTablePro(this, tredTableTranslation);
          }
        },
        language: tredTableTranslation,
      };
      dt = $("#" + table["id"]).DataTable(tableOptions); //end DataTable

      //CHILD ROW (2.3.0)
      //child rows for tables that need it
      for (const [jsonItem, value] of Object.entries(tredChildRowItems)) {
        value.forEach(function (item) {
          if (item.id === table["id"]) {
            $.fn.tredToggleChildRow(jsonItem, item.id);
          }
        });
      }
      //END - CHILD ROW (2.3.0)
    } //end for loop
  };

  $.fn.tredInjectChartData = function (objChart, max = tredSliceNumberItems) {
    let chart, getLast;
    Chart.helpers.each(Chart.instances, function (instance) {
      for (let i = 0; i < objChart.length; i++) {
        chart = objChart[i];
        getLast = chart.hasOwnProperty("slice") && chart["slice"] === "last";

        //if chart['labels'] is null or undefined, set to empty array
        if (!chart["labels"]) {
          //hide chart with id = chart['id'] from page (afer all, there is no data to show)
          $("#" + chart["id"])
            .parents(".tred-widgets-parents")
            .hide();
          continue;
        }

        //slicing lables and data. if getLast, slice negative (get last {max} array items)
        chart["labels"] = getLast
          ? chart["labels"].slice(-Math.abs(max))
          : chart["labels"].slice(0, max);
        for (let z = 0; z < chart["datasets"].length; z++) {
          chart.datasets[z]["data"] = getLast
            ? chart["datasets"][z]["data"].slice(-Math.abs(max))
            : chart["datasets"][z]["data"].slice(0, max);
        } //end inner for

        if (instance.canvas.getAttribute("id") === chart["id"]) {
          instance.data.labels = chart["labels"].map(tredDecodeAmp);
          instance.data.datasets = chart["datasets"];
          if (instance.options.indexAxis == "y") {
            chart["datasets"][0]["borderColor"] = tredColors.blue;
            chart["datasets"][0]["backgroundColor"] = tredColors.blue_t;
          }
          instance.update();
          if (chart["obs"]) {
            $("span#" + chart["id"] + "-obs").text(chart["obs"]);
          } //end if
        } //end if
      } //end outter for
    }); //end Chart each
  };
  //END POPULATE AND INJECT FUNCTIONS

  //SHOW-HIDE TABS CONTENT
  $("#tred-easydash-tabs a.button").click(function () {
    var target = $(this).data("target-content");
    var targetText = $(this).text().trim();

    $(".tred-easydash-tab").hide();
    $(".tred-easydash-tab#" + target).show();
    document.title = "Easy Dash (" + targetText + ") - " + tredSiteName;

    $("#tred-easydash-tabs a.button").removeClass("active");
    $(this).addClass("active");
  });
  //END SHOW-HIDE TABS CONTENT

  //FILTERED TAB
  //cleaning content area
  $.fn.tredCleanFilteredContentArea = function () {
    $("div#tred-top-banners-filtered").empty();
    $("div#tred-charts-filtered").empty();
    $("div#tred-tables-filtered").empty();
    $("div#tred-title-filtered h2").empty();
    $("div#tred-title-filtered span").empty();
    $("#tred-filtered-content-area .tred-edit-panel-button").hide();
  };
  //END cleaning content area

  //getting and showing filtered ld item stats
  $.fn.tredItemFiltered = function (
    mainDiv,
    intentionId,
    intentionName,
    intentionType = "sfwd-courses"
  ) {
    let intentionDataIdName = "data-course-id";
    if (intentionType === "groups") {
      intentionDataIdName = "data-group-id";
    }
    let itemJson = tredGetItemJson(intentionType);
    if (!itemJson) {
      console.log("no itemJson!");
      return false;
    }
    let tredFilteredAction = "tred_ld_item_filtered_get_numbers";

    $.fn.tredCleanFilteredContentArea();

    $("#tred-title-filtered h2").text(
      tredItemsLabels[intentionType.trim()] + ": " + intentionName.trim()
    );
    $("#tred-title-filtered h2").attr(intentionDataIdName, intentionId);

    mainDiv.find(".tred-edit-panel-button").show();

    $.fn.tredPopulateWithWidgetsHtmlElements(
      itemJson,
      "tred-easydash-tab-filter",
      intentionType
    );

    $.ajax({
      url: tredAjaxUrl,
      type: "get",
      dataType: "json",
      data: {
        action: tredFilteredAction,
        _wpnonce: tredWpnonce,
        post_id: intentionId,
        post_type: intentionType,
        post_title: intentionName,
      },
      success: function (response) {
        if (response.result !== "success") {
          console.log(response.action + " => " + response.result);
        } else {
          //fillers
          if (response["data"].hasOwnProperty("fillers")) {
            $.fn.tredInjectFillers(response["data"]["fillers"]);
          }

          //top-boxes
          if (response["data"].hasOwnProperty("top_boxes")) {
            $.fn.tredInjectTopBoxes(response["data"]["top_boxes"]);
          }

          //Charts
          if (response["data"].hasOwnProperty("charts")) {
            $.fn.tredInjectChartData(response["data"]["charts"]);
          }

          //Tables
          if (response["data"].hasOwnProperty("tables")) {
            $.fn.tredInjectTables(response["data"]["tables"]);
          }

          let shortcodeUsed = mainDiv.attr("data-shortcode");
          if (
            shortcodeUsed !== undefined &&
            shortcodeUsed.includes("easydash")
          ) {
            $.fn.tredHideWidgetsBasedOnMainDivDataAtts(mainDiv);
          } else {
            $.fn.tredHideWidgetsBasedOnHtmlDataWidgetAttr(formFilter);
          }
        } //end if/else success
      }, //end success callback
    }); //end ajax call
  };

  //getting and showing filtered user stats
  $.fn.tredUserFiltered = function (
    mainDiv,
    intentionId,
    intentionName,
    intentionType = "users"
  ) {
    let itemJson = tredGetItemJson(intentionType);
    if (!itemJson) {
      console.log("no itemJson!");
      return false;
    }
    let tredFilteredAction = "tred_user_filtered_get_numbers";
    $.fn.tredCleanFilteredContentArea();

    $("#tred-title-filtered h2").text(
      tredItemsLabels[intentionType.trim()] + ": " + intentionName.trim()
    );
    $("#tred-title-filtered h2").attr("data-user-id", intentionId);

    mainDiv.find(".tred-edit-panel-button").show();

    $.fn.tredPopulateWithWidgetsHtmlElements(
      itemJson,
      "tred-easydash-tab-filter",
      intentionType
    );

    $.ajax({
      url: tredAjaxUrl,
      type: "get",
      dataType: "json",
      data: {
        action: tredFilteredAction,
        _wpnonce: tredWpnonce,
        user_id: intentionId,
      },
      success: function (response) {
        if (response.result !== "success") {
          console.log(response.action + " => " + response.result);
        } else {
          //fillers
          if (response["data"].hasOwnProperty("fillers")) {
            $.fn.tredInjectFillers(response["data"]["fillers"]);
          }

          //top-boxes
          if (response["data"].hasOwnProperty("top_boxes")) {
            $.fn.tredInjectTopBoxes(response["data"]["top_boxes"]);
          }

          //Charts
          if (response["data"].hasOwnProperty("charts")) {
            $.fn.tredInjectChartData(response["data"]["charts"]);
          }

          //Tables
          if (response["data"].hasOwnProperty("tables")) {
            $.fn.tredInjectTables(response["data"]["tables"]);
          }

          let shortcodeUsed = mainDiv.attr("data-shortcode");
          if (
            shortcodeUsed !== undefined &&
            shortcodeUsed.includes("easydash")
          ) {
            $.fn.tredHideWidgetsBasedOnMainDivDataAtts(mainDiv);
          } else {
            $.fn.tredHideWidgetsBasedOnHtmlDataWidgetAttr(formFilter);
          }
        } //end if/else success
      }, //end success callback
    }); //end ajax call
  };

  $.fn.tredIntentionFiltered = function (
    mainDiv,
    intentionId,
    intentionName,
    intentionType
  ) {
    if (intentionType !== "users") {
      $.fn.tredItemFiltered(mainDiv, intentionId, intentionName, intentionType);
    } else {
      $.fn.tredUserFiltered(mainDiv, intentionId, intentionName);
    }
    return;
  };

  //function to get items by kind
  $.fn.tredGetItemsByKind = function (selectEl, optVal) {
    //check if selectEl is a jQuery object; if not, convert it into it
    if (!(selectEl instanceof jQuery)) {
      selectEl = $(selectEl);
    }

    // $('#submit-filter').prop('disabled', true);
    let mainDiv = selectEl.closest(".tred-easydash-tab");
    let pick = $("select#tred_pick");
    let pickUsers = $("select#tred_pick_users");
    if (pick) {
      pick.empty();
    }
    if (pickUsers) {
      pickUsers.empty();
    }

    //Clean content area
    $.fn.tredCleanFilteredContentArea();

    //Get widgets numbers to show and save them at data-widgets attr on mainDiv (.tred-easydash-tab)
    $.fn.tredGetWidgetsToShow("filtered", optVal, mainDiv);

    if (optVal === "users") {
      pickUsers.parent().show();
      if (pick) {
        pick.parent().hide();
      }
      return false; //if users, go to the ajax select2 function
    }

    if (pickUsers) {
      pickUsers.parent().hide();
    }
    pick.parent().show();

    if (!optVal || optVal == "0" || optVal == "select") {
      pick.append(
        $("<option>", {
          value: "0",
          text: "select",
        })
      );
      return false;
    }
    ldTypes = [
      "sfwd-courses",
      "sfwd-lessons",
      "sfwd-quiz",
      "sfwd-topic",
      "sfwd-certificate",
      "groups",
      "users",
    ];
    if (ldTypes.includes(optVal) === false) {
      console.log(`Type ${optVal} not included in possible values array...`);
      return false;
    }

    pick.append(
      $("<option>", {
        value: "0",
        text: "wait...",
      })
    );

    //get posts to populate dropdown select
    $.ajax({
      url: tredAjaxUrl,
      type: "get",
      dataType: "json",
      data: {
        action: "tred_ld_posts_dropdown",
        _wpnonce: tredWpnonce,
        item_type: optVal,
      },
      success: function (response) {
        if (response === "no post") {
          console.log("no post!");
        } else {
          pick.empty();
          $.each(response, function (i, item) {
            pick.append(
              $("<option>", {
                value: item.ID,
                text: item.post_title,
              })
            );
          });
          //enabling submit-filter button
          let btnSubmit = $("button#submit-filter");
          btnSubmit.prop("disabled", false);
        } //end if/else success
      }, //end success callback
    }); //end ajax call
  };
  //END function get items by kind

  //getting items by kind
  $("select#tred_filter_item").on("change", function () {
    $("#submit-filter").prop("disabled", true);
    let selectedOpt = $(this);
    let optVal = selectedOpt.val();
    let mainDiv = selectedOpt.closest(".tred-easydash-tab");
    let filteredContentArea = mainDiv.find("#tred-filtered-content-area");
    filteredContentArea.attr("data-panel-item", optVal);
    $.fn.tredGetItemsByKind(selectedOpt, optVal);
  });
  //END getting items by kind

  //enabling submit-filter button when pick is selected
  //a.  for ld items
  $("select#tred_pick").on("change", function () {
    let btnSubmit = $("button#submit-filter");
    if ($(this).val()) {
      btnSubmit.prop("disabled", false);
    } else {
      btnSubmit.prop("disabled", true);
    }
  });

  //b. for users
  $("#tred_pick_users").on("select2:select", function () {
    let btnSubmit = $("button#submit-filter");
    if ($(this).val()) {
      btnSubmit.prop("disabled", false);
    } else {
      btnSubmit.prop("disabled", true);
    }
  });

  //getting stats for filtered intention
  $("#form-filter").submit(function (e) {
    e.preventDefault();
    $("button#submit-filter").prop("disabled", true);
    formFilter = $(this);
    let mainDiv = formFilter.closest(".tred-easydash-tab");
    let intentionType = $("select#tred_filter_item").val();
    if (!intentionType) {
      //not easydash_filtered (must be easydash_course, or easydash_user, etc)
      shortcodeTag = mainDiv.attr("data-shortcode");
      if (shortcodeTag === "easydash_course") {
        intentionType = "sfwd-courses";
      } else if (shortcodeTag === "easydash_user") {
        intentionType = "users";
      } else if (shortcodeTag === "easydash_group") {
        intentionType = "groups";
      } else {
        return;
      }
    }

    let usersSelect = intentionType === "users";
    let pickSelector = !usersSelect
      ? "select#tred_pick"
      : "select#tred_pick_users";
    let intentionId = $(pickSelector).val();
    let intentionName = $(pickSelector + " option:selected").text();
    $.fn.tredIntentionFiltered(
      mainDiv,
      intentionId,
      intentionName,
      intentionType
    );
  });
  //END getting stats for filtered item
  //END FILTERED TAB

  //SELECT2 INIT
  $("#tred_filter_item").select2();
  $("#tred_pick").select2();

  //select users
  $("#tred_pick_users").select2({
    ajax: {
      url: tredAjaxUrl,
      dataType: "json",
      delay: 250,
      data: function (params) {
        return {
          q: params.term, // search term
          action: "tred_users_dropdown",
          _wpnonce: tredWpnonce,
        };
      },
      processResults: function (response) {
        // console.log(response);
        let options = [];
        if (response) {
          $.each(response, function (index, user) {
            options.push({
              id: user["ID"],
              text: user["display_name"] + " ( " + user["user_email"] + " ) ",
            });
          });
        }
        return {
          results: options,
        };
      },
      cache: true,
    },
    minimumInputLength: 3,
  });
  //END SELECT2 INIT

  //NOTIFY
  if ($.notify !== undefined) {
    $.notify.addStyle("tred", {
      html: "<div><span data-notify-html/></div>",
      classes: {
        base: {
          "white-space": "nowrap",
          "background-color": "#D97706",
          padding: "5px",
          color: "white",
          width: "auto",
          "font-size": "24px",
        },
      },
    });
  }
  //copy, csv, print...buttons
  $(".tred-content-area").on("click", "button.tred-table-button", function () {
    $(this).notify(
      "<a href='https://wptrat.com/easy-dash-for-learndash?from=plugin_buttons'>Pro feature. Click to get it!</a>",
      {
        style: "tred",
      }
    );
  });
  //END NOTIFY

  //EDITING PANEL
  //clicking on edit button
  $(".tred-edit-panel-button").on("click", function () {
    let btnClicked = $(this);
    let mainDiv = btnClicked.closest(".tred-content-area");
    //toggle button-primary class
    $(this).toggleClass("button-primary");
    if ($(this).hasClass("button-primary")) {
      mainDiv.find(".tred-remove-widget").show();
      mainDiv
        .find(".tred-save-panel-button")
        .show()
        .html(tredElementsTranslation["save"]);
      mainDiv.find(".tred-restore-panel-button").show().prop("disabled", false);
      $(this).text(tredElementsTranslation["view"]);
    } else {
      mainDiv.find(".tred-remove-widget").hide();
      mainDiv.find(".tred-save-panel-button").hide();
      mainDiv.find(".tred-restore-panel-button").hide();
      $(this).text(tredElementsTranslation["edit"]);
    }
  });
  //END clicking on edit button

  //clicking on save button
  $(".tred-save-panel-button").on("click", function () {
    let btnClicked = $(this);
    //get the maind div
    let mainDiv = btnClicked.closest(".tred-content-area");
    let saveBtn = mainDiv.find(".tred-save-panel-button");
    //make an array with all widgets numbers, if they are visible on the page
    let visibleWidgets = [];
    mainDiv.find(".tred-remove-widget").each(function () {
      if ($(this).is(":visible")) {
        visibleWidgets.push($(this).attr("data-widget-number"));
      }
    });

    //get the data-panel-type of the main div
    let panelType = mainDiv.attr("data-panel-type"); //global or filtered
    //get the data-panel-item of the main div
    let panelItem = mainDiv.attr("data-panel-item"); //global or [sfwd-courses, users etc]

    //loading image on the save button
    btnClicked.html('<img src="' + tredLoadingImgUrl + '" />');

    //send AJAX request to save to database
    $.ajax({
      url: tredAjaxUrl,
      type: "post",
      dataType: "json",
      data: {
        action: "tred_ld_save_panel",
        _wpnonce: tredWpnonce,
        visible_widgets: visibleWidgets,
        panel_type: panelType,
        panel_item: panelItem,
      },
      success: function (response) {
        // console.log(response);
        if (response.result !== "success") {
          saveBtn.html(tredElementsTranslation["save"]);
          console.log(response.action + " => " + response.result);
        } else {
          btnClicked.html(tredElementsTranslation["saved"]);
          btnClicked
            .closest(".tred-easydash-tab")
            .attr("data-widgets", visibleWidgets);
          btnClicked.prop("disabled", true);
        }
      },
    }); //end ajax
  });
  //END clicking on save button

  //clicking on restore all button
  $(".tred-restore-panel-button").on("click", function () {
    let btnClicked = $(this);
    let mainDiv = btnClicked.closest(".tred-content-area");
    let widgets = mainDiv.find(".tred-widgets-parents");
    widgets.show();
    //enable saveBtn button
    let saveBtn = mainDiv.find(".tred-save-panel-button");
    saveBtn.html(tredElementsTranslation["save"]).prop("disabled", false);
    btnClicked.prop("disabled", true);
  });
  //END clicking on restore all button

  //clicking on remove widget button
  $(".tred-content-area").on("click", ".tred-remove-widget", function () {
    //Using event delegation, because it is a dynamically created elements
    let btnClicked = $(this);
    let btnParent = btnClicked.parent();
    let widgetNumber = btnClicked.attr("data-widget-number");
    btnParent.hide();
    //get first parent that has a class tred-content-area
    let mainDiv = btnClicked.closest(".tred-content-area");
    //enable saveBtn button
    let saveBtn = mainDiv.find(".tred-save-panel-button");
    saveBtn.html(tredElementsTranslation["save"]);
    saveBtn.prop("disabled", false);
    //enable restoreBtn button
    let restoreBtn = mainDiv.find(".tred-restore-panel-button");
    restoreBtn.prop("disabled", false);
  });
  //END clicking on remove widget button=
  //END - EDITING PANEL

  //PAGE LOADS FOR GLOBAL DASH
  if ($(".tred-main-content").length === 0) {
    return;
  }

  const tredShortcodeFiltered = $(
    '.tred-main-content[data-shortcode="easydash_filtered"]'
  ).length;
  const tredShortcodeCourse = $(
    '.tred-main-content[data-shortcode="easydash_course"]'
  ).length;
  const tredShortcodeUser = $(
    '.tred-main-content[data-shortcode="easydash_user"]'
  ).length;
  const tredShortcodeGroup = $(
    '.tred-main-content[data-shortcode="easydash_group"]'
  ).length;
  const tredShortcode = $(
    '.tred-main-content[data-shortcode="easydash"]'
  ).length;
  const tredShortcodeUsed = tredShortcodeFiltered || tredShortcode;

  if (
    tredShortcodeFiltered ||
    tredShortcodeCourse ||
    tredShortcodeUser ||
    tredShortcodeGroup
  ) {
    //check if item is already filtered and, if so, do $.fn.tredItemFiltered(mainDiv,intentionId,intentionType,intentionName,item);
    let mainDiv = $('.tred-main-content[data-shortcode*="easydash_"]');
    let intentionId = mainDiv.attr("data-intention-id");
    let intentionType = mainDiv.attr("data-intention-type");
    let intentionName = mainDiv.attr("data-intention-name");

    //check if intentionId, intentionType, intentionName and item are not undefined and not empty strings
    if (mainDiv && intentionId && intentionName && intentionType) {
      $.fn.tredIntentionFiltered(
        mainDiv,
        intentionId,
        intentionName,
        intentionType
      );
    }
    //don't do the rest of the code
    return;
  }

  //load the translated button texts
  $("button.tred-edit-panel-button").html(tredElementsTranslation["edit"]);
  $("button.tred-save-panel-button").html(tredElementsTranslation["save"]);
  $("button.tred-restore-panel-button").html(
    tredElementsTranslation["restore"]
  );

  //when page loads, populate it with globalJson html elements
  let globalJson = JSON.parse(tredGlobalJson);
  $.fn.tredPopulateWithWidgetsHtmlElements(
    globalJson,
    "tred-easydash-tab-global"
  );

  //when page loads, show edit button
  let mainDiv = $("#tred-easydash-tab-global");

  //AJAX FOR POPULATING DASH (FIRST TAB TO DISPLAY)
  //TODO: not execute on the frontend
  let tredAction;
  for (let i = 0; i < tredActions.length; i++) {
    tredAction = tredActions[i];

    // console.log('tredAction: ' + tredAction);

    $.ajax({
      url: tredAjaxUrl,
      type: "get",
      dataType: "json",
      data: {
        action: tredAction,
        _wpnonce: tredWpnonce,
      },
      success: function (response) {
        if (response.result !== "success") {
          console.log(response.action + " => " + response.result);
        } else {
          //do the magic for each chart, top-box or table...

          //top-boxes
          if (response["data"].hasOwnProperty("top_boxes")) {
            $.fn.tredInjectTopBoxes(response["data"]["top_boxes"]);
          }

          //Charts
          if (response["data"].hasOwnProperty("charts")) {
            $.fn.tredInjectChartData(response["data"]["charts"]);
          }

          //Tables
          if (response["data"].hasOwnProperty("tables")) {
            $.fn.tredInjectTables(response["data"]["tables"]);
          }
        } //end if/else success
      }, //end success callback
    }); //end ajax call
  } //end for loop
  //END AJAX FOR POPULATING DASH (FIRST TAB TO DISPLAY)

  //tasks for admin global dash
  if (!tredShortcodeUsed) {
    //show buttons
    mainDiv.find(".tred-edit-panel-button").show();
    //hide unwanted widgets
    $.fn.tredHideWidgetsBasedOnOptions(tredWidgetsToShow, mainDiv);
  } else {
    $.fn.tredHideWidgetsBasedOnMainDivDataAtts(mainDiv);
  }

  $('select[name="tred_last_x_days"]').on("change", function () {
    let select = $(this);
    let value = select.val();
    if (value > 90) {
      alert(value + " " + tredElementsTranslation["alert"]["more_than_x_days"]);
    }
    if (value < 0) {
      alert(tredElementsTranslation["alert"]["all_time"]);
    }
  });

  //on select change the #tred-settings-shortcode-filtered-select element, get the option selected
  $("#tred-settings-shortcode-filtered-select").on("change", function () {
    let select = $(this);
    let value = select.val();
    $(".tred-filtered-shortcode-boxes").hide();
    $("#tred-filtered-shortcode-boxes-" + value).show();
  });

  document.title = "Easy Dash (Dash) - " + tredSiteName;

  //END - PAGE LOADS
}); //end jquery
