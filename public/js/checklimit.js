$(document).ready(function(){
	var amount=0;
	var categoryName='';
	
	


$('#amount').keyup(updateValue);
$('#category').change(updateCategory);

var intRegex = /^\d+$/;
var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
var isNumber = true;

function updateValue()
{
	amount = $("#amount").val();
	if(intRegex.test(amount) || floatRegex.test(amount))
	{
		isNumber=true;
		checkLimit();
		$('.amountsum').html('');
	}
	else if(amount=='')
	{
		$('.amountsum').html('');
		$('#log').html('');
		isNumber=false;
	}	
	else
	{		
		$('.amountsum').html('Wprowadzona kwota nie jest liczbą');
		isNumber=false;
		$('#log').html('');
	}
	
	
}

function updateCategory()
{
	categoryName = $( "option:selected" ).val();
	checkLimit();
}

function checkLimit()
{
	if ( categoryName !='' && amount !=0 && isNumber==true){
					
					
			$('#log').load("/limit", {
			categoryName: categoryName,
			amount: amount
			})
		
			};
}


	
		
});