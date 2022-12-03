<?php if (JAK_SHOW_IPS) { ?>
<script src="<?php echo BASE_URL;?>js/leaflet.js" type="text/javascript"></script>
<script src="<?php echo BASE_URL;?>js/leaflet.cluster.js" type="text/javascript"></script>
<script type="text/javascript">
var statmap = L.map('map_canvas', {zoomControl:true}).setView([10,4], 2);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
	attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
				'<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>'
}).addTo(statmap);

// Disable drag and zoom handlers.
statmap.dragging.disable();
statmap.touchZoom.disable();
statmap.doubleClickZoom.disable();
statmap.scrollWheelZoom.disable();

$(document).ready(function() {
	
	$.ajax({
		async: true,
	  url: '<?php echo BASE_URL;?>ajax/statmap.php',
	  type: "POST",
	  data: "ajax=1&depid=<?php echo $department;?>",
	  dataType: "json",
	  cache: false
	}).done(function(data) {

		if (data.status == 0) {
			$.notify({
		        icon: "fa fa-info-circle",
		        message: "<?php echo $jkl['i3'];?>"
		      }, {
		        type: 'info',
		        timer: 4000,
		        placement: {
		          from: 'top',
		          align: 'right'
		        }
		      });
		} else {
		
			var markers = L.markerClusterGroup();
			
			for (var i=0; i < data.markers.length; ++i) {
			   var marker = L.marker([data.markers[i].latitude, data.markers[i].longitude]).bindPopup(data.markers[i].name);
			   markers.addLayer(marker);
			}
			
			statmap.addLayer(markers);
			
			// Enable drag and zoom handlers.
			statmap.dragging.enable();
			statmap.touchZoom.enable();
			
			statmap.flyToBounds(markers);
		}
	});
});
  
</script>
<?php } ?>

<!-- First Stat -->
<script type="text/javascript">
var chart_operator;
var chart_feedback;
var chart;
var chart2;
$(document).ready(function() {

$('#reportrange').daterangepicker({
      ranges: {
         '<?php echo $jkl["stat_s15"];?>': [moment(), moment()],
         '<?php echo $jkl["stat_s16"];?>': [moment().subtract(2,'days'), moment()],
         '<?php echo $jkl["stat_s17"];?>': [moment().subtract(6,'days'), moment()],
         '<?php echo $jkl["stat_s18"];?>': [moment().subtract(29, 'days'), moment()],
         '<?php echo $jkl["stat_s22"];?>': [moment().startOf('month'), moment().endOf('month')],
         '<?php echo $jkl["stat_s23"];?>': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      },
      startDate: moment("<?php echo $_SESSION["stat_start_date"];?>", "YYYY-MM-DD"),
      endDate: moment("<?php echo $_SESSION["stat_end_date"];?>", "YYYY-MM-DD"),
      format: 'YYYY-MM-DD',
      locale: {
      	customRangeLabel: '<?php echo $jkl["stat_s24"];?>'
      }
    },
    function() {
        $('#reportrange span').html(<?php echo $_SESSION["stat_start_date"];?> + ' - ' + <?php echo $_SESSION["stat_end_date"];?>);
    }
);

$('#reportrange').on('apply.daterangepicker', function(ev, picker) {
  //do something, like clearing an input
  $('#start_date').val(picker.startDate.format('YYYY-MM-DD'));
  $('#end_date').val(picker.endDate.format('YYYY-MM-DD'));
  $("#jak_statform").submit();
});

$(document).on("change", "#jak_depid", function() {
	$("#jak_statform").submit();
});

chart_operator = new Highcharts.Chart({
            chart: {
                renderTo: 'chart_operator',
                type: 'column'
            },
            title: {
                text: '<?php echo addslashes($jkl["stat_s2"]);?>'
            },
            subtitle: {
            	text: '<?php echo addslashes(JAK_TITLE);?>'
            },
            xAxis: [{
            	categories: ['<?php echo $stat1op;?>'],
            }],
            yAxis: [{
            	title: {
            	    text: '<?php echo addslashes($jkl["stat_s6"]);?>',
            	    style: {
            	    	color: '#89A54E'
            	    }
            	},
            	opposite: true
            }, 
            { // Secondary yAxis
            	min: 0,
            	title: {
            	    text: '<?php echo addslashes($jkl["stat_s5"]);?>'
            	}
            }],
            legend: {
                layout: 'vertical',
                backgroundColor: '#FFFFFF',
                align: 'left',
                verticalAlign: 'top',
                x: 100,
                y: 70,
                floating: true,
                shadow: true
            },
            tooltip: {
            	formatter: function() {
                	return ''+ this.x +': ' + this.y + (this.series.name == '<?php echo addslashes($jkl["stat_s6"]);?>' ? ' <?php echo addslashes($jkl["stat_s13"]);?>' : ' <?php echo addslashes($jkl["stat_s5"]);?>');
                }
            },
            series: [{
                name: '<?php echo addslashes($jkl["stat_s5"]);?>',
                yAxis: 1,
                data: [<?php echo $stat1opt;?>]
    
            }, {
                name: '<?php echo addslashes($jkl["stat_s6"]);?>',
                data: [<?php echo $statsuptime;?>]
            }]
        });
	
	chart_feedback = new Highcharts.Chart({
			chart: {
				renderTo: 'chart_feedback',
				zoomType: 'xy'
			},
			title: {
				text: '<?php echo addslashes($jkl["stat_s8"]);?>'
			},
			subtitle: {
				text: '<?php echo addslashes(JAK_TITLE);?>'
			},
			xAxis: [{
				categories: ['<?php echo $stat1totalf;?>'],
				reversed: true
			}],
			yAxis: [{ // Primary yAxis
				min: 0,
				max: 5,
				alignTicks: false,
				tickInterval: 1,
				labels: {
					formatter: function() {
						return this.value +'/5';
					},
					style: {
						color: '#89A54E'
					}
				},
				title: {
					text: '<?php echo addslashes($jkl["stat_s9"]);?>',
					style: {
						color: '#89A54E'
					}
				}
			}, { // Secondary yAxis
				title: {
					text: '<?php echo addslashes($jkl["stat_s10"]);?>',
					style: {
						color: '#4572A7'
					}
				},
				labels: {
					formatter: function() {
						return this.value +' <?php echo addslashes($jkl["stat_s12"]);?>';
					},
					style: {
						color: '#4572A7'
					}
				},
				opposite: true
			}],
			tooltip: {
				formatter: function() {
					return ''+
						this.x +': '+ this.y +
						(this.series.name == '<?php echo addslashes($jkl["stat_s11"]);?>' ? '/5' : ' <?php echo addslashes($jkl["stat_s12"]);?>');
				}
			},
			legend: {
				floating: false
			},
			series: [{
				name: '<?php echo addslashes($jkl["g89"]);?>',
				color: '#4572A7',
				type: 'column',
				yAxis: 1,
				max: 5,
				data: [<?php echo $stat1total;?>]
	
			}, {
				name: '<?php echo addslashes($jkl["stat_s11"]);?>',
				color: '#89A54E',
				type: 'column',
				data: [<?php echo $stat1vote;?>]
			}]
		});
	
	chart = new Highcharts.Chart({
		chart: {
			renderTo: 'chart',
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false
		},
		title: {
			text: '<?php echo addslashes($jkl["stat_s1"]);?>'
		},
		tooltip: {
			formatter: function() {
				return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.percentage, 1) +' %';
			}
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: true,
					color: '#000000',
					connectorColor: '#000000',
					formatter: function() {
						return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.percentage, 1) +' %';
					}
				}
			}
		},
		series: [{
			type: 'pie',
			name: '<?php echo addslashes($jkl["stat_s1"]);?>',
			data: [<?php echo $stat1country;?>
			]
		}]
	});
	
	chart2 = new Highcharts.Chart({
		chart: {
			renderTo: 'chart2',
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false
		},
		title: {
			text: '<?php echo addslashes($jkl["stat_s4"]);?>'
		},
		tooltip: {
			formatter: function() {
				return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.percentage, 1) +' %';
			}
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: true,
					color: '#000000',
					connectorColor: '#000000',
					formatter: function() {
						return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.percentage, 1) +' %';
					}
				}
			}
		},
		series: [{
			type: 'pie',
			name: '<?php echo addslashes($jkl["stat_s4"]);?>',
			data: [<?php echo $stat1ref;?>
			]
		}]
	});
});
</script>

<script type="text/javascript" src="<?php echo BASE_URL;?>charts/highcharts.js"></script>
<script type="text/javascript" src="<?php echo BASE_URL;?>charts/exporting.js"></script>
<script type="text/javascript" src="<?php echo BASE_URL;?>charts/export-csv.js"></script>
<script type="text/javascript" src="<?php echo BASE_URL;?>js/calendar.js"></script>