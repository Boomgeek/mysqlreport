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
    $('#savePractice-btn').click(function() {
        pushPracticeForm();
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

function updatePractice(pid,max_practice_point,question){
        //alert("mode=updatePractice&pid="+pid+"&max_practice_point="+max_practice_point+"&question="+question);
        $.ajax({
            url: "./source/php/model_setting.php",
            type: "POST",
            data: "mode=updatePractice&pid="+pid+"&max_practice_point="+max_practice_point+"&question="+question,
            success: function(result) {
                var res = result.split(":");
                if(res[0]== "Error"){
                    var data;
                    data = "<div class='alert alert-danger alert-dismissible'>";
                    data += "<button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>";
                    data += "<strong>"+res[0]+" : </strong>"+res[1]+"</div>";
                    $("#status").html(data);
                }else if(res[0]== "Success"){
                    var data;
                    data = "<div class='alert alert-success alert-dismissible'>";
                    data += "<button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>";
                    data += "<strong>"+res[0]+" : </strong>"+res[1]+"</div>";
                    $("#status").html(data);
                }
            }
        });
}

function pushPracticeForm() {
    var practiceFormSize = $('#practiceForm').children().size(); //check size of children array 
    var i;

    for (i = 1; i <= practiceFormSize; i++) { //get information with DOM
        var pid = $('#pid_' + i).text();
        var max_practice_point = $('#max_point_' + i).val();
        var question = $('#question_' + i).val();
        if(question != ""){
            updatePractice(pid,max_practice_point,question);
        }else{
            alert("Error : Question was empty");
            return;
        }
    }
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