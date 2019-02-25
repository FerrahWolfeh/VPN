/**
 * British English translation for bootstrap-datepicker
 * Xavier Dutreilh <xavier@dutreilh.com>
 */
$(function(){
	$('.input-group.date').datepicker({
   	 	format: 'yyyy/mm/dd',
    	language: 'en',
   		weekStart: 0,
    	startDate: false,
    	todayHighlight: true,
		autoclose: true
	});
});
 
;(function($){
	$.fn.datepicker.dates['en'] = {
		days: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
		daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
		daysMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
		months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
		monthsShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
		today: "Today",
		monthsTitle: "Months",
		clear: "Clear",
		weekStart: 1,
		format: "dd/mm/yyyy"
	};
}(jQuery));
