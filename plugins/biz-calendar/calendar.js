jQuery(document).ready(function ($) {
	if ($("#biz_calendar").size() == 0) {
		return;
	}
	bizCalendar.start();
});

var bizCalendar = {
	start: function () {
		var now = new Date();
		this.setting = {
			year: now.getFullYear(),
			month: now.getMonth() + 1,
			options: window.bizcalOptions
		};
		document.getElementById('biz_calendar').innerHTML = this.getCalendar();
	},

	downMonth: function () {
		if (this.setting.month <= 1) {
			this.setting.month = 12;
			this.setting.year = this.setting.year - 1;
		} else {
			this.setting.month = this.setting.month - 1;
		}
		document.getElementById('biz_calendar').innerHTML = this.getCalendar();
	},

	upMonth: function () {
		if (this.setting.month >= 12) {
			this.setting.month = 1;
			this.setting.year = this.setting.year + 1;
		} else {
			this.setting.month = this.setting.month + 1;
		}
		document.getElementById('biz_calendar').innerHTML = this.getCalendar();
	},

	goToday: function () {
		var now = new Date();
		if (this.setting.month == now.getMonth() + 1 && this.setting.year == now.getFullYear()) {
			return;
		}
		this.setting.year = now.getFullYear();
		this.setting.month = now.getMonth() + 1;
		document.getElementById('biz_calendar').innerHTML = this.getCalendar();
	},

	getCalendar: function () {
		var weekArray = new Array("日", "月", "火", "水", "木", "金", "土");
		var start_day = this.getStartDayOfMonth(this.setting.year, this.setting.month);
		var last_date = this.getEndDateOfMonth(this.setting.year, this.setting.month);
		var calLine = Math.ceil((start_day + last_date) / 7);
		var calArray = new Array(7 * calLine);

		// カレンダーの日付テーブル作成
		for (var i = 0; i < 7 * calLine; i++) {
			if (i >= last_date) {
				break;
			}
			calArray[i + start_day] = i + 1;
		}

		// カレンダーのタイトル
		var title = this.setting.year + "年 " + this.setting.month + "月";
		var html = "<table class='bizcal' ><tr>";
		html += "<td class='calmonth' colspan='4'>" + title + "</td>";
		html += this.getPrevMonthTag();
		html += "<td class='calbtn today-img' onclick='bizCalendar.goToday()' title='今月へ' ><img src='" + this.setting.options.plugindir + "image/today.png' ></td>";
		html += this.getNextMonthTag();
		html += "</tr>";

		// カレンダーの曜日行
		html += "<tr>";
		for (var i = 0; i < weekArray.length; i++) {
			html += "<th>";
			html += weekArray[i];
			html += "</th>";
		}
		html += "</tr>";

		// カレンダーの日付
		for (var i = 0; i < calLine; i++) {
			html += "<tr>";
			for (var j = 0; j < 7; j++) {
				var date = (calArray[j + (i * 7)] != undefined) ? calArray[j + (i * 7)] : "";
				html += "<td" + this.getDateClass(date, j) + ">";
				html += this.getDateTag(date, j);
				html += "</td>";
			}
			html += "</tr>";
		}
		html += "</table>";

		// 説明文
		html += this.getHolidayTitle();
		html += this.getEventdayTitle();
		return html;
	},

	getHolidayTitle: function () {
		if (this.setting.options.holiday_title != "") {
			return "<p><span class='boxholiday'></span>" + this.setting.options.holiday_title + "</p>";
		}
		return "";
	},

	getEventdayTitle: function () {
		if (this.setting.options.eventday_title == "") {
			return "";
		}
		var tag = "<p><span class='boxeventday'></span>"
		if (this.setting.options.eventday_url == "") {
			tag += this.setting.options.eventday_title + "</p>";
			return tag;
		}
		tag += "<a href='" + this.setting.options.eventday_url + "'>" + this.setting.options.eventday_title + "</a></p>";
		return tag;
	},

	getDateClass: function (date, day) {
		if (date == undefined || date == "") {
			return "";
		}
		var today = this.isToday(date);
		var attr = "";
		switch (this.getDateType(date, day)) {
			case "EVENTDAY":
				attr = today == false ? " class='eventday' " : " class='eventday today' ";
				return attr;
			case "HOLIDAY":
				attr = today == false ? " class='holiday' " : " class='holiday today' ";
				return attr;
			default:
				attr = today == false ? "" : " class='today' ";
				return attr;
		}
		return "";
	},

	isToday: function (date) {
		var now = new Date();
		if (now.getFullYear() == this.setting.year
			&& now.getMonth() + 1 == this.setting.month
			&& now.getDate() == date) {
			return true;
		}
		return false;
	},

	getDateTag: function (date, day) {
		if (date == undefined || date == "") {
			return "";
		}
		var url = this.setting.options.eventday_url;
		if (url == "") {
			return date;
		}
		if (this.getDateType(date, day) == "EVENTDAY") {
			return tag = "<a href='" + url + "'>" + date + "</a>";
		}
		return date;
	},

	getDateType: function (date, day) {
		var fulldate = this.toFormatDate(this.setting.year, this.setting.month, date);
		// イベント日
		if (this.setting.options.eventdays.indexOf(fulldate) != -1) {
			return "EVENTDAY";
		}
		// 臨時営業日
		if (this.setting.options.temp_weekdays.indexOf(fulldate) != -1) {
			return "WEEKDAY";
		}
		// 臨時休業日
		if (this.setting.options.temp_holidays.indexOf(fulldate) != -1) {
			return "HOLIDAY";
		}
		// 定休日
		var dayName = ["sun", "mon", "tue", "wed", "thu", "fri", "sat"];
		if (this.setting.options[dayName[day]] == "on") {
			return "HOLIDAY";
		}
		// 祝日
		if (this.isHoliday(fulldate)) {
			return "HOLIDAY";
		}
		return "WEEKDAY";
	},

	isHoliday: function (fulldate) {
		if (this.setting.options["holiday"] == undefined || this.setting.options["holiday"] == "off") {
			return false;
		}
		var holidays = this.setting.options["national_holiday"];
		if (holidays == undefined) {
			return false;
		}
		for (var i = 0; i < holidays.length; i++) {
			if (holidays[i] == fulldate) {
				return true;
			}
		}
		return false;
	},

	toFormatDate: function (y, m, d) {
		m = m < 10 ? "0" + m : m;
		d = d < 10 ? "0" + d : d;
		return y + "-" + m + "-" + d;
	},

	getEndDateOfMonth: function (year, month) {
		var date = new Date(year, month, 0);
		return date.getDate();
	},

	getStartDayOfMonth: function (year, month) {
		var date = new Date(year, month - 1, 1);
		return date.getDay();
	},

	getPrevMonthTag: function () {
		var limit = this.setting.options["month_limit"];
		var tag = "<td class='calbtn down-img' onclick='bizCalendar.downMonth()' title='前の月へ' ><img src='" 
			+ this.setting.options.plugindir + "image/down.png' ></td>";
		if (limit == undefined || limit == "制限なし") {
			return tag;
		}
		var now = new Date();
		var now_year = now.getFullYear();
		var now_month = now.getMonth() + 1;
		var can_move = true;

		if (limit == "年内") {
			if (this.setting.month == 1) {
				can_move = false;
			}
		} else if (limit == "年度内") {
			if (this.setting.month == 4) {
				can_move = false;
			}
		} else {
			var prev_limit = this.setting.options["prevmonthlimit"] == undefined ? 0 : this.setting.options["prevmonthlimit"];
			var prev_limit_year = now_year;
			var prev_limit_month = now_month - Number(prev_limit);
			if (prev_limit_month < 1) {
				prev_limit_year -= 1;
				prev_limit_month += 12;
			}
			if (this.setting.month == prev_limit_month && this.setting.year == prev_limit_year) {
				can_move = false;
			}
		}

		if (!can_move) {
			tag = "<td class='calbtn down-img' ><img src='" + this.setting.options.plugindir + "image/down-limit.png' ></td>";
		}
		return tag;
	},

	getNextMonthTag: function () {
		var limit = this.setting.options["month_limit"];
		var tag = "<td class='calbtn up-img' onclick='bizCalendar.upMonth()' title='次の月へ' ><img src='" + this.setting.options.plugindir + "image/up.png' ></td>";
		if (limit == undefined || limit == "制限なし") {
			return tag;
		}
		var now = new Date();
		var now_year = now.getFullYear();
		var now_month = now.getMonth() + 1;
		var can_move = true;

		if (limit == "年内") {
			if (this.setting.month == 12) {
				can_move = false;
			}
		} else if (limit == "年度内") {
			if (this.setting.month == 3) {
				can_move = false;
			}
		} else {
			var next_limit = this.setting.options["nextmonthlimit"] == undefined ? 0 : this.setting.options["nextmonthlimit"];
			var next_limit_year = now_year;
			var next_limit_month = now_month + Number(next_limit);
			if (next_limit_month > 12) {
				next_limit_year += 1;
				next_limit_month -= 12;
			}
			if (this.setting.month == next_limit_month && this.setting.year == next_limit_year) {
				can_move = false;
			}
		}
		if (!can_move) {
			tag = "<td class='calbtn up-img' ><img src='" + this.setting.options.plugindir + "image/up-limit.png' ></td>";
		}
		return tag;
	}
};
