jQuery(document).ready(function($) {
	$(document).on('click', '.cal-change-button', function() {
		var month = $('#d4-event-calendar').attr('data-month');
		var year = $('#d4-event-calendar').attr('data-year');
		var category = $('#d4-event-calendar').attr('data-category');
		var exclude_category = $('#d4-event-calendar').attr('data-exclude_category');
		var change = $(this).attr('data-change');
		$.post(
		    '/wp-admin/admin-ajax.php', 
		    {
		        'action': 'cal_change',
		        'month':   month,
		        'year': year,
		        'category': category,
		        'change': change,
		    }, 
		    function(response){
		    	$('#d4-cal-inner').html(response);
		    }
		);
	});

(function () {
            if (window.addtocalendar)if(typeof window.addtocalendar.start == "function")return;
            if (window.ifaddtocalendar == undefined) { window.ifaddtocalendar = 1;
                var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
                s.type = 'text/javascript';s.charset = 'UTF-8';s.async = true;
                s.src = ('https:' == window.location.protocol ? 'https' : 'http')+'://addtocalendar.com/atc/1.5/atc.min.js';
                var h = d[g]('body')[0];h.appendChild(s); }})();
});