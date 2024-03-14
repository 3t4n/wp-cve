//Javasript name: My Date Time Picker
//Date created: 16-Nov-2003 23:19
//Creator: TengYong Ng
//Website: http://www.rainforestnet.com
//Copyright (c) 2003 TengYong Ng
//FileName: DateTimePicker_css.js
//Version: 2.2.4
// Note: Permission given to use and modify this script in ANY kind of applications if
// header lines are left unchanged.
//Permission is granted to redistribute and modify this javascript under a FreeBSD License.
//New Css style version added by Yvan Lavoie (Québec, Canada) 29-Jan-2009
//Formatted for JSLint compatibility by Labsmedia.com (30-Dec-2010)
//
//Extensively Modified as below ... Malcolm Shergold 30-Dec-2013:
//	Uses CSS Styles for all visual objects
//	Time Spinners changed to Drop Down boxes with caller defined Increments
//	Global variables converted to properties
//
//Global variables+
var StageShowLib_CalObj;

// Calendar prototype
function StageShowLib_DTPicker(pDate, pCtrl, pSpanID)
{
    // Globals are defined as object properties
    this.dtToday = pDate;
	this.calHeight = 0; 			// calendar height
	this.StartYear = 1940;			// First Year in drop down year selection
	this.EndYear = 5;				// The last year of pickable date. if current year is 2011, the last year that still picker will be 2016 (2011+5)
	this.selDate = ""; 				// selected date. version 1.7
	
	this.DTP_PageHeaderHeight = 40;
	
	this.SelDateColor = "#8DD53C";	// Backgrond color of selected date in textbox.
	this.HoverColor = "#E0FF38";	// color when mouse move over.
	this.DateSeparator = "-";		// Date Separator, you can change it to "-" if you want.
    
    this.CalObjName = "StageShowLib_CalObj";
    
	if (typeof stageshowlib_MonthName != 'undefined')
	    this.MonthName = stageshowlib_MonthName;
	else
	    this.MonthName = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

	if (typeof stageshowlib_WeekDayName1 != 'undefined')
	    this.WeekDayName1 = stageshowlib_WeekDayName1;
	else
    	this.WeekDayName1 = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
	
	if (typeof stageshowlib_WeekDayName2 != 'undefined')
	    this.WeekDayName2 = stageshowlib_WeekDayName2;
	else
	    this.WeekDayName2 = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];

	if (typeof stageshowlib_textOK != 'undefined')
	    this.textOK = stageshowlib_textOK;
	else
	    this.textOK = "OK!";
	
	if (typeof stageshowlib_textCancel != 'undefined')
	    this.textCancel = stageshowlib_textCancel;
	else
	    this.textCancel = "Cancel!";
		
    this.CalPosOffsetX = -1; //X position offset relative to calendar icon, can be negative value
    this.CalPosOffsetY = 0; //Y position offset relative to calendar icon, can be negative value

    this.ShowLongMonth = true; //Show long month name in Calendar header. example: "January".
    this.ShowMonthYear = true; //Show Month and Year in Calendar header.
    this.PrecedeZero = true; //Preceding zero [true|false]
    this.MondayFirstDay = true; //true:Use Monday as first day; false:Sunday as first day. [true|false] //added in version 1.7

    this.TimeDropdownIncrements = 1;
    this.WeekChar = 3;

    //Properties
    this.Date = pDate.getDate(); 		//selected date
    this.Month = pDate.getMonth(); 		//selected month number
    this.Year = pDate.getFullYear(); 	//selected year in 4 digits
    this.Hours = pDate.getHours();

    if (pDate.getMinutes() < 10)
    {
        this.Minutes = "0" + pDate.getMinutes();
    }
    else
    {
        this.Minutes = pDate.getMinutes();
    }

    if (pDate.getSeconds() < 10)
    {
        this.Seconds = "0" + pDate.getSeconds();
    }
    else
    {
        this.Seconds = pDate.getSeconds();
    }
    
    this.Ctrl = pCtrl;
    this.Format = "ddMMyyyy";
    this.Separator = "-";
    this.ShowTime = false;
    this.ShowSeconds = false;
    this.EnableDateMode = "";
}

StageShowLib_DTPicker.prototype.GetMonthIndex = function (shortMonthName)
{
    for (var i = 0; i < 12; i += 1)
    {
        if (this.MonthName[i].substring(0, 3).toUpperCase() === shortMonthName.toUpperCase())
        {
            return i;
        }
    }
    return -1;
};

StageShowLib_DTPicker.prototype.ClickIncYear = function ()
{
	this.IncYear();
	this.RenderCssCal();
};

StageShowLib_DTPicker.prototype.IncYear = function ()
{
    if (this.Year <= this.dtToday.getFullYear() + this.EndYear)
    {
        this.Year += 1;
    }
};

StageShowLib_DTPicker.prototype.ClickDecYear = function ()
{
	this.DecYear();
	this.RenderCssCal();
};

StageShowLib_DTPicker.prototype.DecYear = function ()
{
    if (this.Year > this.StartYear)
    {
        this.Year -= 1;
    }
};

StageShowLib_DTPicker.prototype.ClickIncMonth = function ()
{
	this.IncMonth();
	this.RenderCssCal();
};

StageShowLib_DTPicker.prototype.IncMonth = function ()
{
    if (this.Year <= this.dtToday.getFullYear() + this.EndYear)
    {
        this.Month += 1;
        if (this.Month >= 12)
        {
            this.Month = 0;
            this.IncYear();
        }
    }
};

StageShowLib_DTPicker.prototype.ClickDecMonth = function ()
{
	this.DecMonth();
	this.RenderCssCal();
};

StageShowLib_DTPicker.prototype.DecMonth = function ()
{
    if (this.Year >= this.StartYear)
    {
        this.Month -= 1;
        if (this.Month < 0)
        {
            this.Month = 11;
            this.DecYear();
        }
    }
};

StageShowLib_DTPicker.prototype.SwitchMth = function (intMth)
{
    this.Month = parseInt(intMth, 10);
};

StageShowLib_DTPicker.prototype.SwitchYear = function (intYear)
{
    this.Year = parseInt(intYear, 10);
};

StageShowLib_DTPicker.prototype.SetHour = function (intHour)
{
    var MaxHour = 23;
        MinHour = 0,
        HourExp = new RegExp("^\\d\\d"),
        SingleDigit = new RegExp("^\\d{1}$");

    if ((HourExp.test(intHour) || SingleDigit.test(intHour)) && (parseInt(intHour, 10) > MaxHour))
    {
        intHour = MinHour;
    }
    else if ((HourExp.test(intHour) || SingleDigit.test(intHour)) && (parseInt(intHour, 10) < MinHour))
    {
        intHour = MaxHour;
    }

    intHour = parseInt(intHour, 10);
    if (SingleDigit.test(intHour))
    {
        intHour = "0" + intHour;
    }

    if (HourExp.test(intHour) && (parseInt(intHour, 10) <= MaxHour) && (parseInt(intHour, 10) >= MinHour))
    {
		this.Hours = parseInt(intHour, 10);
    }

};

StageShowLib_DTPicker.prototype.SetMinute = function (intMin)
{
    var MaxMin = 59,
        MinMin = 0,

        SingleDigit = new RegExp("\\d"),
        SingleDigit2 = new RegExp("^\\d{1}$"),
        MinExp = new RegExp("^\\d{2}$"),

        strMin = 0;

    if ((MinExp.test(intMin) || SingleDigit.test(intMin)) && (parseInt(intMin, 10) > MaxMin))
    {
        intMin = MinMin;
    }
    else if ((MinExp.test(intMin) || SingleDigit.test(intMin)) && (parseInt(intMin, 10) < MinMin))
    {
        intMin = MaxMin;
    }

    strMin = intMin + "";
    if (SingleDigit2.test(intMin))
    {
        strMin = "0" + strMin;
    }

    if ((MinExp.test(intMin) || SingleDigit.test(intMin)) && (parseInt(intMin, 10) <= 59) && (parseInt(intMin, 10) >= 0))
    {
        this.Minutes = strMin;
    }
};

StageShowLib_DTPicker.prototype.SetSecond = function (intSec)
{
    var MaxSec = 59,
        MinSec = 0,

        SingleDigit = new RegExp("\\d"),
        SingleDigit2 = new RegExp("^\\d{1}$"),
        SecExp = new RegExp("^\\d{2}$"),

        strSec = 0;

    if ((SecExp.test(intSec) || SingleDigit.test(intSec)) && (parseInt(intSec, 10) > MaxSec))
    {
        intSec = MinSec;
    }
    else if ((SecExp.test(intSec) || SingleDigit.test(intSec)) && (parseInt(intSec, 10) < MinSec))
    {
        intSec = MaxSec;
    }

    strSec = intSec + "";
    if (SingleDigit2.test(intSec))
    {
        strSec = "0" + strSec;
    }

    if ((SecExp.test(intSec) || SingleDigit.test(intSec)) && (parseInt(intSec, 10) <= 59) && (parseInt(intSec, 10) >= 0))
    {
        this.Seconds = strSec;
    }

};

StageShowLib_DTPicker.prototype.getShowHour = function ()
{
    var finalHour;

    if (this.Hours < 10)
    {
        finalHour = "0" + parseInt(this.Hours, 10);
    }
    else
    {
        finalHour = this.Hours;
    }

    return finalHour;
};

StageShowLib_DTPicker.prototype.GetMonthName = function (IsLong)
{
    var Month = this.MonthName[this.Month];
    if (IsLong)
    {
        return Month;
    }
    else
    {
        return Month.substr(0, 3);
    }
};

StageShowLib_DTPicker.prototype.GetMonDays = function ()
{ //Get number of days in a month

    var DaysInMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    if (this.IsLeapYear())
    {
        DaysInMonth[1] = 29;
    }

    return DaysInMonth[this.Month];
};

StageShowLib_DTPicker.prototype.IsLeapYear = function ()
{
    if ((this.Year % 4) === 0)
    {
        if ((this.Year % 100 === 0) && (this.Year % 400) !== 0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    else
    {
        return false;
    }
};

StageShowLib_DTPicker.prototype.FormatDate = function (pDate)
{
    var MonthDigit = this.Month + 1;
    if (this.PrecedeZero === true)
    {
        if ((pDate < 10) && String(pDate).length === 1) //length checking added in version 2.2
        {
            pDate = "0" + pDate;
        }
        if (MonthDigit < 10)
        {
            MonthDigit = "0" + MonthDigit;
        }
    }

    switch (this.Format.toUpperCase())
    {
    case "DDMMYYYY":
        return (pDate + this.DateSeparator + MonthDigit + this.DateSeparator + this.Year);
    case "DDMMMYYYY":
        return (pDate + this.DateSeparator + this.GetMonthName(false) + this.DateSeparator + this.Year);
    case "MMDDYYYY":
        return (MonthDigit + this.DateSeparator + pDate + this.DateSeparator + this.Year);
    case "MMMDDYYYY":
        return (this.GetMonthName(false) + this.DateSeparator + pDate + this.DateSeparator + this.Year);
    case "YYYYMMDD":
        return (this.Year + this.DateSeparator + MonthDigit + this.DateSeparator + pDate);
    case "YYMMDD":
        return (String(this.Year).substring(2, 4) + this.DateSeparator + MonthDigit + this.DateSeparator + pDate);
    case "YYMMMDD":
        return (String(this.Year).substring(2, 4) + this.DateSeparator + this.GetMonthName(false) + this.DateSeparator + pDate);
    case "YYYYMMMDD":
        return (this.Year + this.DateSeparator + this.GetMonthName(false) + this.DateSeparator + pDate);
    default:
        return (pDate + this.DateSeparator + (this.Month + 1) + this.DateSeparator + this.Year);
    }
};

StageShowLib_DTPicker.prototype.RenderCssCal = function (bNewCal)
{
    if (typeof bNewCal === "undefined" || bNewCal !== true)
    {
        bNewCal = false;
    }
    var vCalHeader,
        vCalData,
        vCalTime = "",
        vCalClosing = "",
        winCalData = "",
        CalDate,

        i,
        j,

        SelectStr,
        vDayCount = 0,
        vFirstDay,

        WeekDayName = [], //Added version 1.7
        strCell,

        showHour,
        ShowArrows = false,
        HourCellWidth = "35px", //cell width with seconds.

        SelectAm,
        SelectPm,

        headID,
        e,
        cssStr,
        style,
        cssText,
        span;

    this.calHeight = 0; // reset the window height on refresh

    // Set the default cursor for the calendar

    winCalData = "<span class='calSpanAll'>";
    vCalHeader = "<table class='calTableAll calBgClass'><tbody>";

    //Table for Month & Year Selector

    vCalHeader += "<tr><td><table class='calTableMonthYear'><tr>";

	vCalHeader += "<td><div class='calNavButton calButtonPrevYear' onmousedown='" + this.CalObjName + ".ClickDecYear();" + "' onmouseover='" + this.CalObjName + ".highlightControl(this, 0)' onmouseout='" + this.CalObjName + ".highlightControl(this, 1)'></div></td>\n"; //Year scroller (decrease 1 year)
    vCalHeader += "<td><div class='calNavButton calButtonPrevMonth' onmousedown='" + this.CalObjName + ".ClickDecMonth();" + "' onmouseover='" + this.CalObjName + ".highlightControl(this, 0)' onmouseout='" + this.CalObjName + ".highlightControl(this, 1)'></div></td>\n"; //Month scroller (decrease 1 month)
    vCalHeader += "<td width='70%' class='calR calMonthName'>" + this.GetMonthName(this.ShowLongMonth) + " " + this.Year + "</td>"; //Month and Year
    vCalHeader += "<td><div class='calNavButton calButtonNextMonth' onmousedown='" + this.CalObjName + ".ClickIncMonth();" + "' onmouseover='" + this.CalObjName + ".highlightControl(this, 0)' onmouseout='" + this.CalObjName + ".highlightControl(this, 1)'></div></td>\n"; //Month scroller (increase 1 month)
    vCalHeader += "<td><div class='calNavButton calButtonNextYear' onmousedown='" + this.CalObjName + ".ClickIncYear();" + "' onmouseover='" + this.CalObjName + ".highlightControl(this, 0)' onmouseout='" + this.CalObjName + ".highlightControl(this, 1)'></div></td>\n"; //Year scroller (increase 1 year)
    this.calHeight += 22;

    vCalHeader += "</tr></table></td></tr>";

    //******************End Month and Year selector in arrow******************************

    //Week day header

    vCalHeader += "<tr><td><table class='calTableDates'><tr>";
    if (this.MondayFirstDay === true)
    {
        WeekDayName = this.WeekDayName2;
    }
    else
    {
        WeekDayName = this.WeekDayName1;
    }
    for (i = 0; i < 7; i += 1)
    {
        vCalHeader += "<td class='calTD calWeekHeadClass' >" + WeekDayName[i].substr(0, this.WeekChar) + "</td>";
    }

    this.calHeight += 19;
    vCalHeader += "</tr>";
    //Calendar detail
    CalDate = new Date(this.Year, this.Month);
    CalDate.setDate(1);

    vFirstDay = CalDate.getDay();

    //Added version 1.7
    if (this.MondayFirstDay === true)
    {
        vFirstDay -= 1;
        if (vFirstDay === -1)
        {
            vFirstDay = 6;
        }
    }

    //Added version 1.7
    vCalData = "<tr>";
    this.calHeight += 19;
    for (i = 0; i < vFirstDay; i += 1)
    {
        vCalData = vCalData + this.GenCell();
        vDayCount = vDayCount + 1;
    }

    //Added version 1.7
    for (j = 1; j <= this.GetMonDays(); j += 1)
    {
        if ((vDayCount % 7 === 0) && (j > 1))
        {
            vCalData = vCalData + "<tr>";
        }

        vDayCount = vDayCount + 1;
        //added version 2.1.2
        dayClass = 'calDayClass';
        cellEnabled = true;
        if (this.EnableDateMode === "future" && ((j < this.dtToday.getDate()) && (this.Month === this.dtToday.getMonth()) && (this.Year === this.dtToday.getFullYear()) || (this.Month < this.dtToday.getMonth()) && (this.Year === this.dtToday.getFullYear()) || (this.Year < this.dtToday.getFullYear())))
        {
            dayClass += ' calDisableClass'; //Before today's date is not clickable
            cellEnabled = false;
        }
        else if (this.EnableDateMode === "past" && ((j >= this.dtToday.getDate()) && (this.Month === this.dtToday.getMonth()) && (this.Year === this.dtToday.getFullYear()) || (this.Month > this.dtToday.getMonth()) && (this.Year === this.dtToday.getFullYear()) || (this.Year > this.dtToday.getFullYear())))
        {
            dayClass += ' calDisableClass'; //After today's date is not clickable
            cellEnabled = false;
        }
        //if End Year + Current Year = this.Year. Disable.
        else if (this.Year > (this.dtToday.getFullYear() + this.EndYear))
        {
            dayClass += ' calDisableClass';
            cellEnabled = false;
        }

        if ((j === this.dtToday.getDate()) && (this.Month === this.dtToday.getMonth()) && (this.Year === this.dtToday.getFullYear()))
        {
            dayClass += ' calTodayClass'; //Highlight today's date
        }

        if ((j === this.selDate.getDate()) && (this.Month === this.selDate.getMonth()) && (this.Year === this.selDate.getFullYear()))
        {
            //modified version 1.7
            dayClass += ' calSelDateClass';
        }

        if (this.MondayFirstDay === true)
        {
            if (vDayCount % 7 === 0)
            {
                dayClass += ' calSundayClass';
            }
            else if ((vDayCount + 1) % 7 === 0)
            {
                dayClass += ' calSaturdayClass';
            }
            else
            {
                dayClass += ' calWeekDayClass';
            }
        }
        else
        {
            if (vDayCount % 7 === 0)
            {
                dayClass += ' calSaturdayClass';
            }
            else if ((vDayCount + 6) % 7 === 0)
            {
                dayClass += ' calSundayClass';
            }
            else
            {
                dayClass += ' calWeekDayClass';
            }
        }

        strCell = this.GenCell(j, null, dayClass, cellEnabled);
        vCalData = vCalData + strCell;

        if ((vDayCount % 7 === 0) && (j < this.GetMonDays()))
        {
            vCalData = vCalData + "</tr>";
            this.calHeight += 19;
        }
    }

    // finish the table proper

    if (vDayCount % 7 !== 0)
    {
        while (vDayCount % 7 !== 0)
        {
            vCalData = vCalData + this.GenCell();
            vDayCount = vDayCount + 1;
        }
    }

    vCalData = vCalData + "</table></td></tr>";

    //Time picker
    if (this.ShowTime === true)
    {
        showHour = this.getShowHour();

        if (this.ShowSeconds === false)
        {
            ShowArrows = true;
            HourCellWidth = "10px";
        }

        vCalTime = "<tr><td><table class='calTimeTable'><tbody><tr>";

        if (this.TimeDropdownIncrements > 0)
        {
            vCalTime += '<td>';
            vCalTime += this.CreateSelectCtrl('hour', 0, 23, 1, showHour, this.CalObjName + '.SetHour');
            vCalTime += ":";
            if (this.ShowSeconds)
            {
                vCalTime += this.CreateSelectCtrl('minute', 0, 59, 1, this.Minutes, this.CalObjName + '.SetMinute');
                vCalTime += ":";
                vCalTime += this.CreateSelectCtrl('second', 0, 59, this.TimeDropdownIncrements, this.Seconds, this.CalObjName + '.SetSecond');
            }
            else
            {
                vCalTime += this.CreateSelectCtrl('minute', 0, 59, this.TimeDropdownIncrements, this.Minutes, this.CalObjName + '.SetMinute');
            }
            vCalTime += '</td>';
            vCalTime += '</tr>';
        }

        vCalTime += "</td>\n<td align='right' valign='bottom' width='" + HourCellWidth + "px'></td></tr>";
        vCalTime += "<tr><td colspan='8'>";
        vCalTime += "<input class='button-secondary calButton' onClick='javascript: " + this.CalObjName + ".closewin(\"" + this.Ctrl + "\");' type=\"button\" value=\"" + this.textOK + "\">&nbsp;";
        vCalTime += "<input class='button-secondary calButton' onClick='javascript: " + this.CalObjName + ".winCal.style.visibility = \"hidden\"' type=\"button\" value=\"" + this.textCancel + "\"></td></tr>";
    }
    else //if not to show time.
    {
        vCalTime += "\n<tr>\n<td>";
        //close button
        vCalClosing += "<div class='calNavButton calClose' onclick='javascript: " + this.CalObjName + ".closewin(\"" + this.Ctrl + "\");' onmousedown='' onmouseover='' onmouseout=''></div></td>";
        vCalClosing += "</tr>";
    }
    vCalClosing += "</tbody></table></td></tr>";
    this.calHeight += 31;
    vCalClosing += "</tbody></table>\n</span>";

    //end time picker

	
    // determines if there is enough space to open the cal above the position where it is called
    if (this.Client_ypos > this.calHeight + this.DTP_PageHeaderHeight)
    {
        this.Client_ypos = this.Client_ypos - this.calHeight;
    }

    if (!this.winCal)
    {
        headID = document.getElementsByTagName("head")[0];
/*
        // add javascript function to the span cal
        e = document.createElement("script");
        e.type = "text/javascript";
        e.language = "javascript";
        e.text = funcCalback;
        headID.appendChild(e);
*/
        // create the outer frame that allows the cal. to be moved
        span = document.createElement("span");
        span.id = this.calSpanID;
        span.className = "calBgClass " + this.calSpanID;
        span.style.left = (this.Client_xpos + this.CalPosOffsetX) + 'px';
        span.style.top = (this.Client_ypos - this.CalPosOffsetY) + 'px';
        //span.style.width = CalWidth + 'px';
        span.style.zIndex = 100;
        document.body.appendChild(span);
        this.winCal = document.getElementById(this.calSpanID);
    }
    else
    {
        this.winCal.style.visibility = "visible";
        this.winCal.style.Height = this.calHeight;

        // set the position for a new calendar only
        if (bNewCal === true)
        {
            this.winCal.style.left = (this.Client_xpos + this.CalPosOffsetX) + 'px';
            this.winCal.style.top = (this.Client_ypos - this.CalPosOffsetY) + 'px';
        }
    }

    this.winCal.innerHTML = winCalData + vCalHeader + vCalData + vCalTime + vCalClosing;
    return true;
};

StageShowLib_DTPicker.prototype.GenCell = function (pValue, pHighLight, pClassId, pClickable)
{ //Generate table cell with value
    var PValue,
        PCellStr,
        PClickable,
        vTimeStr;

    if (!pValue)
    {
        PValue = "";
    }
    else
    {
        PValue = pValue;
    }

    if (pClassId === undefined)
    {
        pClassId = 'calBgClass';
    }

    if (pClickable !== undefined)
    {
        PClickable = pClickable;
    }
    else
    {
        PClickable = true;
    }

    if (this.ShowTime)
    {
        vTimeStr = ' ' + this.Hours + ':' + this.Minutes;
        if (this.ShowSeconds)
        {
            vTimeStr += ':' + this.Seconds;
        }
    }
    else
    {
        vTimeStr = "";
    }

    if (PValue !== "")
    {
        if (PClickable === true)
        {
            if (this.ShowTime === true)
            {
                PCellStr = "<td id='c" + PValue + "' class='calTD " + pClassId + "' onmouseover='" + this.CalObjName + ".highlightDate(this, 0);' onmouseout='" + this.CalObjName + ".highlightDate(this, 1);' onmousedown='" + this.CalObjName + ".selectDate(this," + PValue + ");'>" + PValue + "</td>";
            }
            else
            {
                PCellStr = "<td class='calTD " + pClassId + "' onmouseover='" + this.CalObjName + ".highlightDate(this, 0);' onmouseout='" + this.CalObjName + ".highlightDate(this, 1);' onClick=\"javascript: " + this.CalObjName + ".callback('" + this.Ctrl + "','" + this.FormatDate(PValue) + "');\">" + PValue + "</td>";
            }
        }
        else
        {
            PCellStr = "<td class='calTD " + pClassId + "'>" + PValue + "</td>";
        }
    }
    else
    {
        PCellStr = "<td class='calTD " + pClassId + "'>&nbsp;</td>";
    }

    return PCellStr;
}

StageShowLib_DTPicker.prototype.highlightControl = function (element, col)
{
    if (col === 0)
    {
        element.className += 'Over';
    }
    else
    {
        element.className = element.className.replace('Over', '');
    }
}

StageShowLib_DTPicker.prototype.highlightDate = function (element, col)
{
    if (col === 0)
    {
        element.className += ' calDayClassOver';
    }
    else
    {
        element.className = element.className.replace(' calDayClassOver', '');
    }
}


StageShowLib_DTPicker.prototype.selectDate = function (element, date)
{
    this.Date = date;
    this.selDate = new Date(this.Year, this.Month, this.Date);
    element.style.background = this.SelDateColor;
    this.RenderCssCal();
}

StageShowLib_DTPicker.prototype.CreateSelectCtrl = function (selectName, selectMin, selectMax, selectInc, selectCurrent, selectEvent)
{
    selectCurrent -= selectCurrent % selectInc;
    if (selectInc != 1)
    {
        if (selectName == 'minute') this.SetMinute(selectCurrent);
        if (selectName == 'second') this.SetSecond(selectCurrent);
    }

    selectCtrl = '<select class="calSelectTime" id="' + selectName + '" onchange="javascript:' + selectEvent + '(this.value)">';

    for (selectVal = selectMin; selectVal <= selectMax; selectVal += selectInc)
    {
        selectParam = '';
        if (selectVal >= selectCurrent)
        {
            selectParam = ' selected="" ';
            selectCurrent = selectMax + 1;
        }
        selectValText = (selectVal <= 9) ? '0' + selectVal : selectVal;
        selectCtrl += '<option value="' + selectVal + '" ' + selectParam + '>' + selectValText + '</option>';
    }
    selectCtrl += '</select>';
    return selectCtrl;
}

StageShowLib_DTPicker.prototype.closewin = function (id)
{
    if (this.ShowTime === true)
    {
        var MaxYear = this.dtToday.getFullYear() + this.EndYear;
        var beforeToday =
            (this.Date < this.dtToday.getDate()) &&
            (this.Month === this.dtToday.getMonth()) &&
            (this.Year === this.dtToday.getFullYear()) ||
            (this.Month < this.dtToday.getMonth()) &&
            (this.Year === this.dtToday.getFullYear()) ||
            (this.Year < this.dtToday.getFullYear());

        if ((this.Year <= MaxYear) && (this.Year >= this.StartYear) && (this.Month === this.selDate.getMonth()) && (this.Year === this.selDate.getFullYear()))
        {
            if (this.EnableDateMode === "future")
            {
                if (beforeToday === false)
                {
                    this.callback(id, this.FormatDate(this.Date));
                }
            }
            else
            {
                this.callback(id, this.FormatDate(this.Date));
            }
        }
    }

    var CalId = document.getElementById(id);
    CalId.focus();
    this.winCal.style.visibility = 'hidden';
}

StageShowLib_DTPicker.prototype.callback = function (id, datum)
{
    var CalId = document.getElementById(id);
    if (datum === 'undefined')
    {
        var d = new Date();
        datum = d.getDate() + '/' + (d.getMonth() + 1) + '/' + d.getFullYear();
    }
    window.calDatum = datum;
    CalId.value = datum;
    if (this.ShowTime)
    {
        CalId.value += ' ' + this.getShowHour() + ':' + this.Minutes;
        if (this.ShowSeconds) CalId.value += ':' + this.Seconds;
    }
    if (CalId.onchange != undefined) CalId.onchange();
    CalId.focus();
    this.winCal.style.visibility = 'hidden';
}

function StageShowLib_getElemPosnOffset(elem) 
{
    var top=0, left=0;
    while(elem) 
    {
        top = top + parseInt(elem.offsetTop);
        left = left + parseInt(elem.offsetLeft);       
        elem = elem.offsetParent;
    }
    return {top: top, left: left};
}

// end Calendar prototype


function StageShowLib_DateTimeSelect(pSender, pFormat, pShowTime, pShowSeconds, pEnableDateMode, pTimeDropdownIncrements, defaultTimeDate)
{
    // get current date and time
    var dtToday;
	if ((typeof defaultTimeDate !== 'undefined') && (defaultTimeDate != ''))
		dtToday = new Date(defaultTimeDate);
	else
		dtToday = new Date();
	
    if (!StageShowLib_CalObj)
    {
        StageShowLib_CalObj = new StageShowLib_DTPicker(dtToday); // Class Constructor Declaration: StageShowLib_DTPicker(pDate, pCtrl, pSpanID)
    }

	var pickerObj = StageShowLib_CalObj;
	pickerObj.dtToday = dtToday;
	pickerObj.Ctrl = pSender.id;
	pickerObj.calSpanID = "calBorder"; 		// span ID

    var senderElem = document.getElementById(pickerObj.Ctrl);
    var offsetPosn = StageShowLib_getElemPosnOffset(senderElem);
    
	pickerObj.Client_xpos = offsetPosn.left;
	pickerObj.Client_ypos = offsetPosn.top;
        
    pickerObj.TimeDropdownIncrements = pTimeDropdownIncrements;
    pickerObj.WeekChar = 3;

	pickerObj.ShowTime = ((pShowTime !== undefined) && pShowTime);
	pickerObj.ShowSeconds = (pickerObj.ShowTime && (pShowSeconds !== undefined) && pShowSeconds);
	
    if (pFormat !== undefined && pFormat !== "")
    {
        pickerObj.Format = pFormat.toUpperCase();
    }
    else
    {
        pickerObj.Format = "MMDDYYYY";
    }

    if (pEnableDateMode !== undefined && (pEnableDateMode === "future" || pEnableDateMode === "past"))
    {
        pickerObj.EnableDateMode = pEnableDateMode;
    }
    else
    {
		pickerObj.EnableDateMode = '';		
	}

    var exDateTime = senderElem.value; //Existing Date Time value in textbox.

    if (exDateTime)
    { //Parse existing Date String
        var Sp1 = exDateTime.indexOf(pickerObj.DateSeparator, 0), //Index of Date Separator 1
            Sp2 = exDateTime.indexOf(pickerObj.DateSeparator, parseInt(Sp1, 10) + 1), //Index of Date Separator 2
            tSp1, //Index of Time Separator 1
            tSp2, //Index of Time Separator 2
            strMonth,
            strDate,
            strYear,
            intMonth,
            YearPattern,
            strHour,
            strMinute,
            strSecond,
            winHeight,
            offset = parseInt(pickerObj.Format.toUpperCase().lastIndexOf("M"), 10) - parseInt(pickerObj.Format.toUpperCase().indexOf("M"), 10) - 1,
            strAMPM = "";
        //parse month

        if (pickerObj.Format.toUpperCase() === "DDMMYYYY" || pickerObj.Format.toUpperCase() === "DDMMMYYYY")
        {
            if (pickerObj.DateSeparator === "")
            {
                strMonth = exDateTime.substring(2, 4 + offset);
                strDate = exDateTime.substring(0, 2);
                strYear = exDateTime.substring(4 + offset, 8 + offset);
            }
            else
            {
                if (exDateTime.indexOf("D*") !== -1)
                { //DTG
                    strMonth = exDateTime.substring(8, 11);
                    strDate = exDateTime.substring(0, 2);
                    strYear = "20" + exDateTime.substring(11, 13); //Hack, nur für Jahreszahlen ab 2000
                }
                else
                {
                    strMonth = exDateTime.substring(Sp1 + 1, Sp2);
                    strDate = exDateTime.substring(0, Sp1);
                    strYear = exDateTime.substring(Sp2 + 1, Sp2 + 5);
                }
            }
        }
        else if (pickerObj.Format.toUpperCase() === "MMDDYYYY" || pickerObj.Format.toUpperCase() === "MMMDDYYYY")
        {
            if (pickerObj.DateSeparator === "")
            {
                strMonth = exDateTime.substring(0, 2 + offset);
                strDate = exDateTime.substring(2 + offset, 4 + offset);
                strYear = exDateTime.substring(4 + offset, 8 + offset);
            }
            else
            {
                strMonth = exDateTime.substring(0, Sp1);
                strDate = exDateTime.substring(Sp1 + 1, Sp2);
                strYear = exDateTime.substring(Sp2 + 1, Sp2 + 5);
            }
        }
        else if (pickerObj.Format.toUpperCase() === "YYYYMMDD" || pickerObj.Format.toUpperCase() === "YYYYMMMDD")
        {
            if (pickerObj.DateSeparator === "")
            {
                strMonth = exDateTime.substring(4, 6 + offset);
                strDate = exDateTime.substring(6 + offset, 8 + offset);
                strYear = exDateTime.substring(0, 4);
            }
            else
            {
                strMonth = exDateTime.substring(Sp1 + 1, Sp2);
                strDate = exDateTime.substring(Sp2 + 1, Sp2 + 3);
                strYear = exDateTime.substring(0, Sp1);
            }
        }
        else if (pickerObj.Format.toUpperCase() === "YYMMDD" || pickerObj.Format.toUpperCase() === "YYMMMDD")
        {
            if (pickerObj.DateSeparator === "")
            {
                strMonth = exDateTime.substring(2, 4 + offset);
                strDate = exDateTime.substring(4 + offset, 6 + offset);
                strYear = exDateTime.substring(0, 2);
            }
            else
            {
                strMonth = exDateTime.substring(Sp1 + 1, Sp2);
                strDate = exDateTime.substring(Sp2 + 1, Sp2 + 3);
                strYear = exDateTime.substring(0, Sp1);
            }
        }

        if (isNaN(strMonth))
        {
            intMonth = pickerObj.GetMonthIndex(strMonth);
        }
        else
        {
            intMonth = parseInt(strMonth, 10) - 1;
        }
        if ((parseInt(intMonth, 10) >= 0) && (parseInt(intMonth, 10) < 12))
        {
            pickerObj.Month = intMonth;
        }
        //end parse month

        //parse year
        YearPattern = /^\d{4}$/;
        if (YearPattern.test(strYear))
        {
            if ((parseInt(strYear, 10) >= pickerObj.StartYear) && (parseInt(strYear, 10) <= (dtToday.getFullYear() + pickerObj.EndYear)))
            {
                pickerObj.Year = parseInt(strYear, 10);
            }
        }
        //end parse year

        //parse Date
        if ((parseInt(strDate, 10) <= pickerObj.GetMonDays()) && (parseInt(strDate, 10) >= 1))
        {
            pickerObj.Date = strDate;
        }
        //end parse Date

        //parse time

        if (pickerObj.ShowTime === true)
        {
            tSp1 = exDateTime.indexOf(":", 0);
            tSp2 = exDateTime.indexOf(":", (parseInt(tSp1, 10) + 1));
            if (tSp1 > 0)
            {
                strHour = exDateTime.substring(tSp1, tSp1 - 2);
                pickerObj.SetHour(strHour);

                strMinute = exDateTime.substring(tSp1 + 1, tSp1 + 3);
                pickerObj.SetMinute(strMinute);

                strSecond = exDateTime.substring(tSp2 + 1, tSp2 + 3);
                pickerObj.SetSecond(strSecond);

            }
            else if (exDateTime.indexOf("D*") !== -1)
            { //DTG
                strHour = exDateTime.substring(2, 4);
                pickerObj.SetHour(strHour);
                strMinute = exDateTime.substring(4, 6);
                pickerObj.SetMinute(strMinute);

            }
        }

    }
    pickerObj.selDate = new Date(pickerObj.Year, pickerObj.Month, pickerObj.Date); //version 1.7
    pickerObj.RenderCssCal(true);
}

function StageShowLib_CalendarSelector(pSender, pMode)
{
	pEnableDateMode = 'future';
	StageShowLib_DateModeCalendarSelector(pSender, pMode, pEnableDateMode);
}

function StageShowLib_DateModeCalendarSelector(pSender, pMode, pEnableDateMode, defaultTimeDate)
{
	defaultTimeDate = typeof defaultTimeDate !== 'undefined' ? defaultTimeDate : '';
	
    if (stageshowlib_dtFormat !== undefined && stageshowlib_dtFormat !== "")
		pFormat = stageshowlib_dtFormat;
    else	
		pFormat = 'yyyyMMdd';

    	pShowTime = true; 
	pShowSeconds = false; 
	
	pTimeDropdownIncrements = 0;
	
	if (pMode == null) 
	{
		pMode = 'DateSeconds';
	}
	
	pMode = pMode.toLowerCase();	
	switch(pMode)
	{
		case 'date':
			pShowTime = false; 
			break;
		
		case 'dateseconds':
			pShowSeconds = true; 
			pTimeDropdownIncrements = 1;
			break;
		
		default:
		case 'datetime':
			pTimeDropdownIncrements = 5;
			break;
	}

	return StageShowLib_DateTimeSelect(pSender, pFormat, pShowTime, pShowSeconds, pEnableDateMode, pTimeDropdownIncrements, defaultTimeDate);
}

