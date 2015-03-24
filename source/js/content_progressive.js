$(document).ready(function (){
    //Start start windows zone
    callProgressive();
    //End start windows zone
});

//start function zone
function callProgressive(){
        $.ajax({
            url: "./source/php/model_progressive.php",
            type: "POST",
            data: "mode=progressive",
            success: function(result) {
                var res = result.split(":");
                if(res[0]== "Error"){
                    var data;
                    data = "<div class='alert alert-danger alert-dismissible'>";
                    data += "<button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>";
                    data += "<strong>"+res[0]+" : </strong>"+res[1]+"</div>";
                    $("#progressive-Content").html(data);
                }else{
                    $('#progressive-Content').html(result);
                }
            }
        });
}
//end function zone