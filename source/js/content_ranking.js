$(document).ready(function (){
    //Start start windows zone
    UpdateStudentPoint(function() {
    	callRating();
    });
    //End start windows zone
});

//start function zone
function callRating(){
        $.ajax({
            url: "./source/php/model_ranking.php",
            type: "POST",
            data: "mode=ranking",
            success: function(result) {
                var res = result.split(":");
                if(res[0]== "Error"){
                    var data;
                    data = "<div class='alert alert-danger alert-dismissible'>";
                    data += "<button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>";
                    data += "<strong>"+res[0]+" : </strong>"+res[1]+"</div>";
                    $("#ranking-Content").html(data);
                }else{
                    $('#ranking-Content').html(result);
                }
            }
        });
}

function UpdateStudentPoint(callback)
{
	$.ajax({
            url: "./source/php/model_ranking.php",
            type: "POST",
            data: "mode=checkStudentPoint",
            success: function(result) {
                callback();
            }
        });
}
//end function zone