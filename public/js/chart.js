$(document).ready(function(){
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

				
				var cellLength = oCells.length -1;

				
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
		pieHole: 0.4,	
		is3D: false,
		legend: {maxLines: 2}
		

		
        };
		if (document.getElementById('expensessum').innerText == "0.00")
		{
			$("#piechart").html('<p class="h4">Brak wydatków w zadanym okresie</p>');
		}
		else
		{
		
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

		chart.draw(data, options);
		
		}
}
})  
