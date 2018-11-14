jQuery(document).ready(function() {
		
		jQuery('#calendar').fullCalendar({
			header: {
				left: 'prev,next',
				center: 'title',
				right: 'month,basicWeek,basicDay'
			},
			defaultDate: '2017-09-12',
			navLinks: true, // can click day/week names to navigate views
			editable: false,
			eventLimit: true, // allow "more" link when too many events
      allDay : false, // will make the time show,
      color: 'yellow',    // an option!
      textColor: 'black',  // an option!
      className:'vinnie',
			events: [
				{
					title: 'Meeting',
					start: '2017-09-12T10:30:00',
					end: '2017-09-12T12:30:00',
          id:123
				},
				{
					id: 21,
					title: 'TEstngg',
					start: '2017-09-13T20:30:00',
					end: '2017-09-13T22:30:00'
				},
				
			]
		});
		
	});
