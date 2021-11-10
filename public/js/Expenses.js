$(document).ready(function(){
	
	$(".expenseButton").click(function(){
		
			var categoryName = $(this).val();
			
			$("#allExpenses").load("/expense/showExpenses",{
				categoryName: categoryName
			});
			
	});
	
});