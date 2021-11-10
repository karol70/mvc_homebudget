$(document).ready(function(){
	
	$(".incomeButton").click(function(){
		
			var categoryName = $(this).val();
			
			$("#allIncomes").load("/income/showIncomes",{
				categoryName: categoryName
			});
			
	});
	
});