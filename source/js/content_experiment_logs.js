$(document).ready(function (){
    //Start start windows zone
    callExperimentLogs();
    //End start windows zone
});

//start function zone
function callExperimentLogs(){
        $.ajax({
            url: "./source/php/model_experiment_logs.php",
            type: "POST",
            data: "mode=rating",
            success: function(result) {
                var res = result.split(":");
                if(res[0]== "Error"){
                    var data;
                    data = "<div class='alert alert-danger alert-dismissible'>";
                    data += "<button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>";
                    data += "<strong>"+res[0]+" : </strong>"+res[1]+"</div>";
                    $("#ExperimentLogs-Content").html(data);
                }else{
                    $('#ExperimentLogs-Content').html(result);
                }
            }
        });
}