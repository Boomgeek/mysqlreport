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

    $('#article-Filter').change(function() {
        callExperimentDetails();
    });

    $('#sort-Filter').change(function() {
        callExperimentDetails();
    });

    $('#viewDetails-btn').click(function() {
        var logo = "<span class='fa fa-fw fa-flask' aria-hidden='true'></span>";
        var title = logo+" Experiment Details of Unit "+$("#unit-Filter").val()+" ["+$("#type-Filter").val()+"]";
        $('#experimentDetailTitle').html(title);
        callArticleDropdown(function (){
            callExperimentDetails();
        });
    });
    //End event listener zone
});

//start function zone
function callExperimentDetails(){
        $.ajax({
            url: "./source/php/model_experiment_logs.php",
            type: "POST",
            data: "mode=callExperimentDetails&unit="+$("#unit-Filter").val()+"&type="+$("#type-Filter").val()+"&article="+$("#article-Filter").val()+"&sort="+$('#sort-Filter').val(),
            success: function(result) {
                var res = result.split(":");
                if(res[0]== "Error"){
                    $('#panel-bar-chart').html(null);
                    var data;
                    data = "<div class='alert alert-danger alert-dismissible'>";
                    data += "<button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>";
                    data += "<strong>"+res[0]+" : </strong>"+res[1]+"</div>";
                    $("#experimentDetailStatus").html(data);
                }else{
                    $("#experimentDetailContent").html(result);
                }
            }
        });
}

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
        data.push({article: 'Article '+(i+1),times: data_array[i]});
    }
    // Bar Chart
    Morris.Bar({
        element: res,
        data: data,
        xkey: 'article',
        ykeys: ['times'],
        labels: ['Experiment times'],
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

function callArticleDropdown(callback)
{
    $.ajax({
        url: "./source/php/model_experiment_logs.php",
        type: "POST",
        data: "mode=callArticleDropdown&unit="+$("#unit-Filter").val()+"&type="+$("#type-Filter").val(),
        success: function(result) {
            $("#article-Filter").html(result);
            callback();
        }
    });
} 