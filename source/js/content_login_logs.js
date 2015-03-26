$(document).ready(function (){
    //Start start windows zone
    callLoginLogs();
    //End start windows zone
});

//start function zone
function callLoginLogs(){
        $.ajax({
            url: "./source/php/model_login_logs.php",
            type: "POST",
            data: "mode=rating",
            success: function(result) {
                var res = result.split(":");
                if(res[0]== "Error"){
                    var data;
                    data = "<div class='alert alert-danger alert-dismissible'>";
                    data += "<button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>";
                    data += "<strong>"+res[0]+" : </strong>"+res[1]+"</div>";
                    $("#LoginLogs-Content").html(data);
                }else{
                    $('#LoginLogs-Content').html(result);
                }
            }
        });
}