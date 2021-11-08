$(document).ready(function(){
			
			$(".cat").click(function(){
			var name = $(this).val();
			
				$("#data").load("/categories", {
					name: name
					});
			});
		
		});