google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
		  
		 var data = new google.visualization.DataTable();
		data.addColumn('string','Kategoria');
		data.addColumn('number', 'Suma');
		
		
		var oTable = document.getElementById('myTable');

		var rowLength = oTable.rows.length;
		
		data.addRows(rowLength);
		var suma =0;
		    
		for (i = 1; i < rowLength -1; i++){


				var oCells = oTable.rows.item(i).cells;

				
				var cellLength = oCells.length;

				
				for(var j = 0; j < cellLength ; j++){
					
					
					var cell = oCells.item(j).innerHTML;
				
						
						if(typeof cell === "number")
						{
							
							data.setValue(i,j, cell);
							
						}
						else
						{						
							
							data.setValue(i,j, cell);
						}											
				}			 			   
		}		
	
        var options = {
        backgroundColor: 'transparent',
		fontSize: 18,
		is3D: true,
		chartArea:{top:'20',width:'75%',height:'100%'},
		legend: {position: 'right', aligment:'center'}

		
        };
		
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

       chart.draw(data, options);
      }
	  
	  
