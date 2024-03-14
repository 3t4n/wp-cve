/* Persian (Farsi) Translation for the jQuery UI date picker plugin. */
/* Javad Mowlanezhad -- jmowla@gmail.com */
/* Jalali calendar should supported soon! (Its implemented but I have to test it) */
if(typeof jQuery.datepicker!='undefined'){
( function( factory ) {
	if ( typeof define === "function" && define.amd ) {

		// AMD. Register as an anonymous module.
		define( [ "../widgets/datepicker" ], factory );
	} else {

		// Browser globals
		factory( jQuery.datepicker );
	}
}( function( datepicker ) {

datepicker.regional.fa = {
	calendar: JalaliDate,
	closeText: 'خروج',
	prevText: 'قبل',
	nextText: 'بعد',
	currentText: 'امروز',
	monthNames: ['فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر','دی','بهمن','اسفند'],
	monthNamesShort: ['فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر','دی','بهمن','اسفند'],
	dayNames: ['یکشنبه', 'دوشنبه', 'سه شنبه', 'چهارشنبه', 'پنجشنبه', 'جمعه', 'شنبه'],
	dayNamesShort: ['یک', 'دو', 'سه', 'چهار', 'پنج', 'جمعه', 'شنبه'],
	dayNamesMin: ['ی','د','س','چ','پ','ج','ش'],
	weekHeader: "هف",
	dateFormat: 'dd MM yy',
	firstDay: 6,
	isRTL: true,
	showMonthAfterYear: false,
	yearSuffix: "" };
datepicker.setDefaults( datepicker.regional.fa );

return datepicker.regional.fa;

} ) );
}



// JalaliDate: a Date-like object wrapper for jalali.js functions
// Mahdi Hasheminezhad. email: hasheminezhad at gmail dot com (http://hasheminezhad.com)
function JalaliDate(p0, p1, p2) {
    var georgianDate;
    var jalaliDate;
    var isJalali = true;

    if (!p0) {
        setFullDate();
    } else if (typeof (p0) == 'boolean') {
        isJalali = p0;
        setFullDate();
    } else if (typeof (p0 == 'number')) {
        var y = parseInt(p0, 10);
        var m = parseInt(p1, 10);
        var d = parseInt(p2, 10);
        y += div(m, 12);
        m = remainder(m, 12);
        var g = jalali_to_gregorian([y, m, d]);
        setFullDate(new Date(g[0], g[1], g[2]));
    } else if (p0 instanceof Array) {
        throw new "JalaliDate(Array) is not implemented yet!";
    } else {
        setFullDate(p0);
    }

    function setFullDate(date) {
        if (date instanceof JalaliDate) {
            date = date.getGeorgianDate();
        }
        georgianDate = new Date(date);
        if (!georgianDate || georgianDate == 'Invalid Date' || isNaN(georgianDate || !georgianDate.getDate())) {
            georgianDate = new Date();
        }
        jalaliDate = gregorian_to_jalali([
            georgianDate.getFullYear(),
            georgianDate.getMonth(),
            georgianDate.getDate()]);
        return this;
    }
    this.getGeorgianDate = function() { return georgianDate; }

    this.setFullDate = setFullDate;

    this.setDate = function(e) {
        jalaliDate[2] = e;
        var g = jalali_to_gregorian(jalaliDate);
        georgianDate = new Date(g[0], g[1], g[2]);
        jalaliDate = gregorian_to_jalali([g[0], g[1], g[2]]);
    };

    this.getFullYear = function() { return jalaliDate[0]; };
    this.getMonth = function() { return jalaliDate[1]; };
    this.getDate = function() { return jalaliDate[2]; };
    this.toString = function() { return jalaliDate.join(',').toString(); };
    this.getDay = function() { return georgianDate.getDay(); };
    this.getHours = function() { return georgianDate.getHours(); };
    this.getMinutes = function() { return georgianDate.getMinutes(); };
    this.getSeconds = function() { return georgianDate.getSeconds(); };
    this.getTime = function() { return georgianDate.getTime(); };
    this.getTimeZoneOffset = function() { return georgianDate.getTimeZoneOffset(); };
    this.getYear = function() { return JalaliDate[0] % 100; };

    this.setHours = function(e) { georgianDate.setHours(e) };
    this.setMinutes = function(e) { georgianDate.setMinutes(e) };
    this.setSeconds = function(e) { georgianDate.setSeconds(e) };
    this.setMilliseconds = function(e) { georgianDate.setMilliseconds(e) };
}


g_days_in_month = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
j_days_in_month = new Array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
 
function div(a,b) {
  return Math.floor(a/b);
}

function remainder(a,b) {
  return a - div(a,b)*b;
}

function gregorian_to_jalali(g /* array containing year, month, day*/ )
{
   var gy, gm, gd;
   var jy, jm, jd;
   var g_day_no, j_day_no;
   var j_np;
 
   var i;

   gy = g[0]-1600;
   //gm = g[1]-1;
   gm = g[1];
   gd = g[2]-1;

   g_day_no = 365*gy+div((gy+3),4)-div((gy+99),100)+div((gy+399),400);
   for (i=0;i<gm;++i)
      g_day_no += g_days_in_month[i];
   if (gm>1 && ((gy%4==0 && gy%100!=0) || (gy%400==0)))
      /* leap and after Feb */
      ++g_day_no;
   g_day_no += gd;
 
   j_day_no = g_day_no-79;
 
   j_np = div(j_day_no, 12053);
   j_day_no = remainder (j_day_no, 12053);
 
   jy = 979+33*j_np+4*div(j_day_no,1461);
   j_day_no = remainder (j_day_no, 1461);
 
   if (j_day_no >= 366) {
      jy += div((j_day_no-1),365);
      j_day_no = remainder ((j_day_no-1), 365);
   }
 
   for (i = 0; i < 11 && j_day_no >= j_days_in_month[i]; ++i) {
      j_day_no -= j_days_in_month[i];
   }
   //jm = i+1;
   jm = i;
   jd = j_day_no+1;

   return new Array(jy, jm, jd);
}

function jalali_to_gregorian(j /* array containing year, month, day*/ )
{
   var gy, gm, gd;
   var jy, jm, jd;
   var g_day_no, j_day_no;
   var leap;

   var i;

   jy = j[0]-979;
   //jm = j[1]-1;
   jm = j[1];
   jd = j[2] - 1;

   j_day_no = 365*jy + div(jy,33)*8 + div((remainder (jy, 33)+3),4);
   for (i=0; i < jm; ++i)
      j_day_no += j_days_in_month[i];

   j_day_no += jd;

   g_day_no = j_day_no+79;

   gy = 1600 + 400*div(g_day_no,146097); /* 146097 = 365*400 + 400/4 - 400/100 + 400/400 */
   g_day_no = remainder (g_day_no, 146097);

   leap = 1;
   if (g_day_no >= 36525) /* 36525 = 365*100 + 100/4 */
   {
      g_day_no--;
      gy += 100*div(g_day_no,36524); /* 36524 = 365*100 + 100/4 - 100/100 */
      g_day_no = remainder (g_day_no, 36524);
      
      if (g_day_no >= 365)
         g_day_no++;
      else
         leap = 0;
   }

   gy += 4*div(g_day_no,1461); /* 1461 = 365*4 + 4/4 */
   g_day_no = remainder (g_day_no, 1461);

   if (g_day_no >= 366) {
      leap = 0;

      g_day_no--;
      gy += div(g_day_no, 365);
      g_day_no = remainder (g_day_no, 365);
   }

   for (i = 0; g_day_no >= g_days_in_month[i] + (i == 1 && leap); i++)
      g_day_no -= g_days_in_month[i] + (i == 1 && leap);
   //gm = i+1;
  gm = i;
  gd = g_day_no + 1;

   return new Array(gy, gm, gd);
}

function jalali_today() {
  Today = new Date();
  j = gregorian_to_jalali(new Array(
                          Today.getFullYear(),
                          Today.getMonth()+1,
                          Today.getDate()
                          ));
  return j[2]+"/"+j[1]+"/"+j[0];
}