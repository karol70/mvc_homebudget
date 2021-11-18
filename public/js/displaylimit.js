function setLimits()
{

			var categoryName = '';
			var amount='';
			categoryName = $( "option:selected" ).val();
			
			$('select').change(updateCategoryLimit);
			getLimitAmount();
			getLimitActivated();
			
			function getLimitAmount()
			{				
				$.ajax({
				  type: "POST",
				  url: "/Expense/limitAmount",
				  data: { categoryName: categoryName},
				  success: function(data) {
					$('#limitamount').val(data);
					}
				});				
			}
			
			function getLimitActivated()
			{				
				$.ajax({
				  type: "POST",
				  url: "/Expense/limitActive",
				  data: { categoryName: categoryName},
				  success: function(data) {
						if(data=='10')
						{
							$('#setOrUnsetLimit').prop('checked',true);
							$('#limitamount').prop('disabled',false);
						}
						else
						{
							$('#setOrUnsetLimit').prop('checked',false);
							$('#limitamount').prop('disabled',true);
						}
					}
				});				
			}
			
			function updateCategoryLimit()
			{
				categoryName = $( "option:selected" ).val();
				
				getLimitAmount();
				getLimitActivated();
			}
			
		
};