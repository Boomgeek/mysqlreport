$(document).ready(function (){
    //Start start windows zone
    callUnitDropdown(function() {
        callTypeDropdown(function() {
            callExperimentLogs();
        });
    });
    //End start windows zone

    //Start event listener zone
    $('#unit-Filter').change(function() {
        callTypeDropdown(function() {
            callExperimentLogs();
        });
    });
    $('#type-Filter').change(function() {
        callExperimentLogs();
    });
    //End event listener zone
});

//start function zone
function callExperimentLogs(){
        $.ajax({
            url: "./source/php/model_experiment_logs.php",
            type: "POST",
            data: "mode=callExperimentLogs&unit="+$("#unit-Filter").val()+"&type="+$("#type-Filter").val(),
            success: function(result) {
                var res = result.split(":");
                if(res[0]== "Error"){
                    $('#panel-bar-chart').html(null);
                    var data;
                    data = "<div class='alert alert-danger alert-dismissible'>";
                    data += "<button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>";
                    data += "<strong>"+res[0]+" : </strong>"+res[1]+"</div>";
                    $("#status").html(data);
                }else{
                    var data = result.split(",");
                    if(data != ''){
                        //alert(data);
                        $('#content-bar-chart').html(null);         //remove old content bar chart
                        callBarChart(data,"content-bar-chart");     //add new content bar chart
                    }else{
                        $('#panel-bar-chart').html(null);           //remove panel of bar chart
                        var data;
                        data = "<div class='alert alert-danger alert-dismissible'>";
                        data += "<button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>";
                        data += "<strong>Experiment Logs is empty : </strong>Experiment logs not exist</div>";
                        $("#status").html(data);
                    }
                }
            }
        });
}

function callBarChart(data_array,res)
{
    var data = [];
    for(i=0 ; i<data_array.length ; i++){
        data.push({article: 'Article '+(i+1),frequency: data_array[i]});
    }
    // Bar Chart
    Morris.Bar({
        element: res,
        data: data,
        xkey: 'article',
        ykeys: ['frequency'],
        labels: ['Experiment Frequency'],
        barRatio: 0.4,
        xLabelAngle: 0,
        hideHover: 'auto',
        resize: true,
    });
}

function callUnitDropdown(callback)
{
    $.ajax({
        url: "./source/php/model_experiment_logs.php",
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
        url: "./source/php/model_experiment_logs.php",
        type: "POST",
        data: "mode=callTypeDropdown&unit="+$("#unit-Filter").val(),
        success: function(result) {
            $("#type-Filter").html(result);
            callback();
        }
    });
}