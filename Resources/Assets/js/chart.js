function getMonthName(number)
{
	const monthNames = ["January", "February", "March", "April", "May", "June",
  		"July", "August", "September", "October", "November", "December"
	];

	return monthNames[number];
}

function getYear(date)
{
	date = new Date(date);
	return date.getFullYear();
}



function renderChartbyYear(id, year) {				
	$.ajax({
		'type' : 'POST',
		'dataType' : 'json',
		'url' : 'http://localhost/MVC/manage/employee/stats/year',
		'data' : {"id" : id, "year" : year},
		success : function(data) {
			var month_template = [
					{ x : new Date('1 January ' + year), y : 0},
					{ x : new Date('1 February ' + year), y : 0},
					{ x : new Date('1 March ' + year), y : 0},
					{ x : new Date('1 April ' + year), y : 0},
					{ x : new Date('1 May ' + year), y : 0},
					{ x : new Date('1 June ' + year), y : 0},
					{ x : new Date('1 July ' + year), y : 0},
					{ x : new Date('1 August ' + year), y : 0},
					{ x : new Date('1 September ' + year), y : 0},
					{ x : new Date('1 October ' + year), y : 0},
					{ x : new Date('1 November ' + year), y : 0},
					{ x : new Date('1 December ' + year), y : 0},
			];

			for(var x = 0; x <= Object.keys(data).length - 1; x++) {
				month_template[new Date('1 ' + data[x]['month'] + year).getMonth()].x = new Date('1 ' + data[x]['month'] + year);
				month_template[new Date('1 ' + data[x]['month'] + year).getMonth()].y = parseInt(data[x]['y']);
			}				
				
			var revenueColumnChart = new CanvasJS.Chart("chartContainer-" + id, {
				responsive: true,
				animationEnabled: true,
				backgroundColor: "transparent",
				theme: "theme2",
				title : {
					text : year + " Statistics Report"
				},
				axisX: {
					labelFontFamily: "Raleway",
					labelFontSize: 14,
					valueFormatString: "MMM YYYY"
				},
				axisY: {
					labelFontFamily: "Raleway",
					labelFontSize: 14,
					title: "Washed Vehicle Timeline",
					prefix: "",
				},
				toolTip: {
					borderThickness: 0,
					cornerRadius: 0
				},
				data: [
					{
						type: "column",
						yValueFormatString: "###,###.##",
						dataPoints: month_template
					}
				]
			});
			revenueColumnChart.render();
		}
			
	})
}


function renderChartbyTimeline(id, start, end, mode = null, month = null, year = null)
{
	$.ajax({
		'type' : 'POST',
		'dataType' : 'json',
		'url' : 'http://localhost/MVC/manage/employee/stats/timeline',
		'data' : {"id" : id, "start" : start, "end" : end},
		success : function(data) {
			  var month_template = [];
			  var date = new Date(start);
			  var formatString = {'format' : ""};
			  var intervalOption = { 'interval' : "", 'type' : "" };

			  if(mode == 'week') {
			  	var title = month; //Month will be the header name for this
			  	formatString['format'] = 'DDD';
			  } 

			  if(mode == 'month') {
			    var title = month + " " + year + " Statistics Report";
			  	formatString['format'] = 'DD MMM';
			  }

			  if(mode=='day') {
			  	var title = start + " - " + end + " Statistics Report";
			  	formatString['format'] = 'DD MMM';
			  }

				for(var x = 0; x <= Object.keys(data).length - 1; x++) {
					month_template.push({ x : new Date(data[x]['date']), y : parseInt(data[x]['count'])});
				}

				//If the dates are less than or equal to 4, set the intervalType to day and interval to 1
				if(Object.keys(data).length <= 4) {
					intervalOption['interval'] = 1;
					intervalOption['type'] = 'day';
				}

				var chart = new CanvasJS.Chart("chartContainer-" + id, {

				animationEnabled: true,
				theme: "light2",
				title:{
					text: title,
				},
				axisX:{
					labelFontFamily : 'Raleway',
					labelFontSize : 14,
					valueFormatString: formatString['format'],
					crosshair: {
						enabled: true,
						snapToDataPoint: true
					},
					interval : intervalOption['interval'],
					intervalType : intervalOption['type'] 

				},
				axisY: {
					labelFontFamily : 'Raleway',
					labelFontSize : 14,
					title: "Washed Vehicle Timeline",
					crosshair: {
						enabled: true
					},
				},
				toolTip:{
					shared:true
				},  
				legend:{
					cursor:"pointer",
					verticalAlign: "bottom",
					horizontalAlign: "left",
					dockInsidePlotArea: true,
					itemclick: toogleDataSeries
				},
				data: [{
					type: "line",
					showInLegend: true,
					name: "Total Washed Vehicle",
					markerType: "circle",
					xValueFormatString: "MMM DD,YYYY",
					color: "#F08080",
					dataPoints: month_template
				},
				{
					
				}]
			});

			chart.render();
		}
	});

	function toogleDataSeries(e){
		if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
			e.dataSeries.visible = false;
		} else{
			e.dataSeries.visible = true;
		}
		chart.render();
	}
}

function renderSalesChartByTimeline(id, start, end)
{
	$.ajax({
		'type' : 'POST',
		'dataType' : 'json',
		'url' : 'http://localhost/MVC/manage/package/stats/timeline',
		'data' : {"id" : id, "start" : start, "end" : end},
		success : function(data) {
			var chart = new CanvasJS.Chart("chartContainer-" + id, {
			animationEnabled: true,
			theme: "light2",
			title:{
				text: "Sales Report"
			},
			axisX:{
				valueFormatString: "DD MMM",
				crosshair: {
					enabled: true,
					snapToDataPoint: true
				}
			},
			axisY: {
				title: "Number of Visits",
		      	prefix : 'P',
				crosshair: {
					enabled: true
				}
			},
			toolTip:{
				shared:true
			},  
			legend:{
				cursor:"pointer",
				verticalAlign: "bottom",
				horizontalAlign: "left",
				dockInsidePlotArea: true,
				itemclick: toogleDataSeries
			},
			data: [{
				type: "line",
				showInLegend: true,
				name: "Total Visit",
				markerType: "square",
				xValueFormatString: "DD MMM, YYYY",
				color: "#F08080",
				dataPoints: [
					{ x: new Date(2017, 0, 3), y: 650 },
					{ x: new Date(2017, 0, 4), y: 700 },
					{ x: new Date(2017, 0, 5), y: 710 },
					{ x: new Date(2017, 0, 6), y: 658 },
					{ x: new Date(2017, 0, 7), y: 734 },
					{ x: new Date(2017, 0, 8), y: 963 },
					{ x: new Date(2017, 0, 9), y: 847 },
					{ x: new Date(2017, 0, 10), y: 853 },
					{ x: new Date(2017, 0, 11), y: 869 },
					{ x: new Date(2017, 0, 12), y: 943 },
					{ x: new Date(2017, 0, 13), y: 970 },
					{ x: new Date(2017, 0, 14), y: 869 },
					{ x: new Date(2017, 0, 15), y: 890 },
					{ x: new Date(2017, 0, 16), y: 930 }
				]
			},
			{
				type: "line",
				showInLegend: true,
				name: "Unique Visit",
				lineDashType: "dash",
				dataPoints: [
					{ x: new Date(2017, 0, 3), y: 510 },
					{ x: new Date(2017, 0, 4), y: 560 },
					{ x: new Date(2017, 0, 5), y: 540 },
					{ x: new Date(2017, 0, 6), y: 558 },
					{ x: new Date(2017, 0, 7), y: 544 },
					{ x: new Date(2017, 0, 8), y: 693 },
					{ x: new Date(2017, 0, 9), y: 657 },
					{ x: new Date(2017, 0, 10), y: 663 },
					{ x: new Date(2017, 0, 11), y: 639 },
					{ x: new Date(2017, 0, 12), y: 673 },
					{ x: new Date(2017, 0, 13), y: 660 },
					{ x: new Date(2017, 0, 14), y: 562 },
					{ x: new Date(2017, 0, 15), y: 643 },
					{ x: new Date(2017, 0, 16), y: 570 }
				]
			},
			{
				type: "line",
				showInLegend: true,
				name: "Unique Visit",
				lineDashType: "dash",
				dataPoints: [
					{ x: new Date(2017, 0, 3), y: 510 },
					{ x: new Date(2017, 0, 4), y: 560 },
					{ x: new Date(2017, 0, 5), y: 540 },
					{ x: new Date(2017, 0, 6), y: 558 },
					{ x: new Date(2017, 0, 7), y: 544 },
					{ x: new Date(2017, 0, 8), y: 693 },
					{ x: new Date(2017, 0, 9), y: 657 },
					{ x: new Date(2017, 0, 10), y: 663 },
					{ x: new Date(2017, 0, 11), y: 639 },
					{ x: new Date(2017, 0, 12), y: 673 },
					{ x: new Date(2017, 0, 13), y: 660 },
					{ x: new Date(2017, 0, 14), y: 562 },
					{ x: new Date(2017, 0, 15), y: 643 },
					{ x: new Date(2017, 0, 16), y: 570 }
				]
			},
			{
				type: "line",
				showInLegend: true,
				name: "Unique Visit",
				lineDashType: "dash",
				dataPoints: [
					{ x: new Date(2017, 0, 3), y: 510 },
					{ x: new Date(2017, 0, 4), y: 560 },
					{ x: new Date(2017, 0, 5), y: 540 },
					{ x: new Date(2017, 0, 6), y: 558 },
					{ x: new Date(2017, 0, 7), y: 544 },
					{ x: new Date(2017, 0, 8), y: 693 },
					{ x: new Date(2017, 0, 9), y: 657 },
					{ x: new Date(2017, 0, 10), y: 663 },
					{ x: new Date(2017, 0, 11), y: 639 },
					{ x: new Date(2017, 0, 12), y: 673 },
					{ x: new Date(2017, 0, 13), y: 660 },
					{ x: new Date(2017, 0, 14), y: 562 },
					{ x: new Date(2017, 0, 15), y: 643 },
					{ x: new Date(2017, 0, 16), y: 570 }
				]
			}]

		});
			chart.render();

			function toogleDataSeries(e){
				if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
					e.dataSeries.visible = false;
				} else{
					e.dataSeries.visible = true;
				}
				chart.render();
			}
		}
	})
}

