$(document).ready(function(){
			//var open = false;
			$(".cat").click(function(){
			var name = $(this).val();
			
				$("#data").load("/categories", {
					name: name
					});
			});
		
		});