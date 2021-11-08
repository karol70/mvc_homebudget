$(document).ready(function(){
	var amount;
	var categoryName
	
	
/*	
	function showValues() {
    var str = $( "form" ).serialize();
    $( "#log" ).text( str );
  }
  $( "input[type='text']").on( "keydown", showValues );
  $( "select" ).on( "change", showValues );
  showValues();
*/	
	

	if(($('#amount').keyup) && $('option').click(function() {
		  		
			amount = $("#amount").val();
			
			if (($("#category ")[0].selectedIndex >= 0) && amount !=''){
					categoryName = $( "option:checked" ).val();
					
							$('#log').load("/limit", {
							categoryName: categoryName,
							amount: amount
							})
		
			});
	
	})
	);	
	
		
});