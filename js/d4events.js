(function($) {

	function d4events_loadmore(element,testing) {
		
		var calwrapper = $(element).parents('.d4-cal-wrapper');

		$(element).html('Loading...');
		$(calwrapper).addClass('d4events-loadingmore');

		if ($(calwrapper).hasClass('events-style_agenda')) {
			var lastevent = $(calwrapper).find('.agenda-day-row:last-of-type');
			var style = 'agenda';
		} else {
			var lastevent = $(calwrapper).find('.events_list-single:last-of-type');
			var style = 'list';
		}	
		
		var lastdate = $(lastevent).attr('data-event_date');
		var last_event_id = $(lastevent).attr('data-event_id');
		var terms = $('.d4-event-calendar').attr('data-terms');
		var taxonomy = $('.d4-event-calendar').attr('data-taxonomy');
		var tax_field = $('.d4-event-calendar').attr('data-tax_field');
		var exclude_terms = $('.d4-event-calendar').attr('data-exclude_terms');

		$.post(
		    ajax_object.ajaxurl, 
		    {
		        'action': 'loadmore',
		        'lastdate': lastdate,
		        'terms': terms,
		        'taxonomy': taxonomy,
		        'tax_field': tax_field,
		        'exclude_terms': exclude_terms,
		        'style': style,
		    }, 
		    function(response){
		    	$(calwrapper).removeClass('d4events-loadingmore');
		    	$(element).html('Load More Events');
		    	if(!testing) {
		    		$(calwrapper).find('.d4-cal-inner').append(response);
		    	}
		    	console.log(response);		    	
		    	if(response != '') {
		    		$(calwrapper).addClass('show-loadmore');
		    	} else {
		    		$(element).addClass('no-loadmore').html('No more events to load');
		    	}
		    }
		);
	}

	$(document).on('click', '.d4events-loadmore', function() {
		d4events_loadmore($(this),false);
	});

	$(document).ready(function() {
		$('.d4events-loadmore').each(function() {
			d4events_loadmore($(this),true);
		});
	});

})( jQuery );

jQuery(document).ready(function($) {
	$(document).on('click', '.cal-change-button', function() {
		var month = $('.d4-event-calendar').attr('data-month');
		var year = $('.d4-event-calendar').attr('data-year');
		var terms = $('.d4-event-calendar').attr('data-terms');
		var taxonomy = $('.d4-event-calendar').attr('data-taxonomy');
		var tax_field = $('.d4-event-calendar').attr('data-tax_field');
		var exclude_terms = $('.d4-event-calendar').attr('data-exclude_terms');
		var change = $(this).attr('data-change');
		$.post(
		    ajax_object.ajaxurl, 
		    {
		        'action': 'cal_change',
		        'month':   month,
		        'year': year,
		        'terms': terms,
		        'taxonomy': taxonomy,
		        'tax_field': tax_field,
		        'exclude_terms': exclude_terms,
		        'change': change,
		    }, 
		    function(response){
		    	$('.d4-cal-inner').html(response);
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