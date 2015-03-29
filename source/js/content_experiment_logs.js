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
                    var data = result.split(",");
                    callBarChart(data,"experiment-bar-chart");
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