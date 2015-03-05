$(document).ready(function(){	

//start load index content
	callContent("checking.php");
//end load index content



});
//start function zone
function callContent(source)
{
	$.ajax({
			url:"./source/content/"+source,
			type: "POST",
			data: "",
			success:function(result)
			{
				$('#content').html(result);
			}
    	});
}
//end function zone