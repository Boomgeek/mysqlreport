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
            url: "./source/php/model_rating.php",
            type: "POST",
            data: "mode=rating",
            success: function(result) {
                var res = result.split(":");
                if(res[0]== "Error"){
                    var data;
                    data = "<div class='alert alert-danger alert-dismissible'>";
                    data += "<button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>";
                    data += "<strong>"+res[0]+" : </strong>"+res[1]+"</div>";
                    $("#rating-Content").html(data);
                }else{
                    $('#rating-Content').html(result);
                }
            }
        });
}

function UpdateStudentPoint(callback)
{
	$.ajax({
            url: "./source/php/model_rating.php",
            type: "POST",
            data: "mode=checkStudentPoint",
            success: function(result) {
                callback();
                $('#rating-Content').html(result);
            }
        });
}
//end function zone