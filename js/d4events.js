(function($) {

	function d4events_loadmore(element,testing) {
		
		var calwrapper = $(element).parents('.events-data-wrapper');

		$(element).html('Loading...');
		$(calwrapper).addClass('d4events-loadingmore');		
		
		var last_event = $(calwrapper).find('.event-single').last();
		var lastdate = $(last_event).attr('data-event_date');
		console.log(lastdate);
		var last_event_id = $(last_event).attr('data-event_id');

		var terms = $(calwrapper).attr('data-terms');
		var taxonomy = $(calwrapper).attr('data-taxonomy');
		var tax_field = $(calwrapper).attr('data-tax_field');
		var exclude_terms = $(calwrapper).attr('data-exclude_terms');
		var style = $(calwrapper).attr('data-style');
		var links = $(calwrapper).attr('data-links');
		var range = $(calwrapper).attr('data-range');
		var excluded_ids = [];

		//create an array of event ids for events that are non-repeating. these events are then omitted from any "loadmore" functionality as they only need to be displayed once.
		$(calwrapper).children('div[data-event_id]').each(function() {
			if(! $(this).attr('data-event_repeating')) {
				excluded_ids.push($(this).attr('data-event_id'));
			}
		});

		console.log(excluded_ids);

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
		        'range': range,
		        'links': links,
		        'excluded_ids': excluded_ids,
		    }, 
		    function(response){
		    	$(calwrapper).removeClass('d4events-loadingmore');
		    	$(element).html('Load More Events');
		    	if(!testing) {
		    		$(element).remove();
		    		$(calwrapper).append(response);
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
		var links = $('.d4-event-calendar').attr('data-links');
		var change = $(this).attr('data-change');

		console.log(month);		
		
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
		        'links' : links,
		    }, 
		    function(response){
		    	$('.events-data-wrapper').html(response);
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