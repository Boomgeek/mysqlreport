$(document).ready(function (){
    //Start start windows zone
    callDifficulty();
    //End start windows zone
});

//start function zone
function callDifficulty(){
        $.ajax({
            url: "./source/php/model_difficulty.php",
            type: "POST",
            data: "mode=difficulty",
            success: function(result) {
                var res = result.split(":");
                if(res[0]== "Error"){
                    var data;
                    data = "<div class='alert alert-danger alert-dismissible'>";
                    data += "<button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>";
                    data += "<strong>"+res[0]+" : </strong>"+res[1]+"</div>";
                    $("#Difficulty-Content").html(data);
                }else{
                    $('#Difficulty-Content').html(result);
                }
            }
        });
}