$(document).ready( function(){
	var main = $("#main"),
		side = $("#side"),
		title = $(document).attr("title"),
		cache = {};

	// clear the cache every 10 minutes
	setInterval( function(){ cache = {}; }, 36000000 );

	// set up initial state which may not be filter-free
	var qs = ( location.href.indexOf("?") != -1 ) ? location.href.substr( location.href.indexOf("?") + 1 ) : "";
	History.replaceState( {filter: qs}, title, "./?" + qs );

	History.Adapter.bind( window,'statechange',function(){
		var state = History.getState();
		// check cache
		if( cache[state.data.filter] ) {
			updateData( cache[state.data.filter] );
		}
		else {
			$.get("./", 'ajax=1&' + state.data.filter, function( data ) {
				cache[state.data.filter] = data;
				updateData( data );
			}, "html");
		}
	});
	
	function updateData( data ) {
		data = $(data);
		main.html(data.find("#main").html());
		side.html(data.find("#side").html());
		overviewRefresh();
	}
	
	function changeState( qs ) {
		History.pushState( {filter: qs}, title, "./?" + qs  );	// will trigger a statechange event
	}
	
	function overviewRefresh() {
		// js/no-js
		main.find(".hide-if-js").hide();
		side.find(".hide-if-js").hide();
		main.find(".hide-if-no-js").show();
		side.find(".hide-if-no-js").show();
		
		// toggle links
		main.find('a.toggle').click( function(e) {
			e.preventDefault();
			var a = $(this);
			a.toggleClass("unfolded");
			a.css("border-color", a.hasClass("unfolded") ? "#000 #FFF #FFF #FFF" : "#FFF #FFF #FFF #000" );
				
			var id = a.attr('id');
			if (id != '')
				$('tr.detail_'+id).toggle();
		});
		
		// filter links
		$("#main a.filter, #side table.calendar a").click(function(e) {
			e.preventDefault();
			changeState( $(this).attr("href").replace(/^\.\/\??/, "" ) );
		});
		
		main.find("a.filter").attr("title", i18n.filter_title);
		main.find("a.ext").attr("title", i18n.ext_link_title);
		
		// handle filters changing
		var filters = $('#filters :input');
		filters.change( function() {
			$("#filters .clear-filter").remove();	// remove old x's
			
			var qvs = [];
			filters.each(function() {
				var i = $(this), name = i.attr("name"), val = i.val();
				if ( !name )
					return;
				if ( val != "_" ) {
					qvs.push( name + '=' + encodeURIComponent( val ) );
					if( i.is("select") )
						i.parent().addClass("active-filter").prepend("<a class='clear-filter'>&#215;</a> ");
				}
			});
			
			changeState( qvs.join("&") );
		});
		
		// filters being removed
		$(".clear-filter").click( function(){
			$( "#" + $(this).data("filter") ).val("_").change();
		});
		
		// make the filter section pretty
		side.css("padding-bottom", "10px");
		var diff = main.innerHeight() - side.innerHeight();
		if( diff )
			side.css("padding-bottom", ( 10 + diff ) + "px");
			
		// ajax activity indicator
		var ajax = $('<div id="ajaxindicator"></div>').css( {position: "absolute", top: "20px", right: "20px"} );
		main.prepend(ajax);
		
		// show/hide ajax activity indicator
		$(document).ajaxStart(function() {
			var spinner = new Spinner({ lines: 10, length: 5, width: 2, radius: 4, color: '#000', speed: 1, trail: 60, shadow: false}).spin(ajax[0]);
		}).ajaxStop(function() { 
			$('#ajaxindicator').empty();
		});
		
		// charts
		var lineChartOptions = {
			series: {
				bars: { show: true, align: "center" }
			},
			legend: { show: false },
			grid: { hoverable: true },
			xaxis: { tickLength: 2, tickSize: 1, tickDecimals: 0 },
			yaxis: { tickDecimals: 0 }
		};
		
		var cdata = $("#chart-data");
		if( cdata.length ) {
			var data_visits = [], data_hits = [];
			
			$("tr", cdata).each( function(i,row){
				var r = $(row);
				var x = parseInt( $("th", r).text() );
				var bits = $("td", r);
				data_visits.push( [x, parseInt( $(bits[0]).text() )] );
				data_hits.push( [x, parseInt( $(bits[1]).text() )] );
			});
			
			var chart = $.plot( $("#chart"), [data_visits], lineChartOptions );
			
			$("#chartopt a").click( function(e){
				e.preventDefault();
				var type = $(this).data("show");
				chart = $.plot( $("#chart"), [ type == "h" ? data_hits : data_visits ], lineChartOptions );
				$("#chart-title").text( $("#chart-data").data(type + "title") );
				$("#chartopt a").removeClass("current");
				$(this).addClass("current");
			});
			
			var previousPoint = null;
			$("#chart").bind("plothover", function (event, pos, item) {
				if (item) {
					if (previousPoint != item.dataIndex) {
						previousPoint = item.dataIndex;
						
						$("#tooltip").remove();
						var y = item.datapoint[1];
						
						$('<div id="tooltip">' + y + '</div>').css( {
							position: 'absolute',
							display: 'none',
							"border-radius": "5px",
							"box-shadow": "#CCC 2px 2px 5px",
							"font-weight": "bold",
							top: item.pageY,
							left: item.pageX + 20,
							border: '1px solid #CCC',
							padding: '5px 10px',
							'background-color': '#FFF'
						}).appendTo("body").fadeIn(200);
					}
				}
				else {
					$("#tooltip").remove();
					previousPoint = null;            
				}
			});
		}
	}

	overviewRefresh();
});
