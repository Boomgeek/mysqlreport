$(document).ready(function() {
	//Start start windows zone
    callUnitDropdown(function() {
        callTypeDropdown(function() {
            callPracticeSetting();
        });
    });
    //End start windows zone

    //Start event listener zone
    $('#unit-Filter').change(function() {
        callTypeDropdown(function() {
            callPracticeSetting();
        });
    });
    $('#type-Filter').change(function() {
        callPracticeSetting();
    });
    //End event listener zone
});

//start function zone
function callPracticeSetting(){
        $.ajax({
            url: "./source/php/model_setting.php",
            type: "POST",
            data: "mode=callPracticeSetting&unit="+$("#unit-Filter").val()+"&type="+$("#type-Filter").val(),
            success: function(result) {
                var res = result.split(":");
                if(res[0]== "Error"){
                    var data;
                    data = "<div class='alert alert-danger alert-dismissible'>";
                    data += "<button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>";
                    data += "<strong>"+res[0]+" : </strong>"+res[1]+"</div>";
                    $("#status").html(data);
                }else{
                    $('#practiceSetting-Content').html(result);
                }
            }
        });
}

function callUnitDropdown(callback)
{
    $.ajax({
        url: "./source/php/model_experiment_logs.php",			//call dropdown from model_experiment_logs.php
        type: "POST",
        data: "mode=callUnitDropdown",
        success: function(result) {
            $("#unit-Filter").html(result);
            callback();
        }
    });
} 

function callTypeDropdown(callback) 
{
    $.ajax({
        url: "./source/php/model_experiment_logs.php",			//call dropdown from model_experiment_logs.php
        type: "POST",
        data: "mode=callTypeDropdown&unit="+$("#unit-Filter").val(),
        success: function(result) {
            $("#type-Filter").html(result);
            callback();
        }
    });
}
//end function zone