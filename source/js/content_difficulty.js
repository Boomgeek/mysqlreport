$(document).ready(function (){
    //Start start windows zone
    updateDifficulty(function() {
        callDifficulty();
    });

    callUnitDropdown();
    //End start windows zone

    //start event listener zone
    $('#unit-Filter').change(function() {
        callDifficulty();
    });
    //end event listener zone
});

//start function zone
function callDifficulty(){
        $.ajax({
            url: "./source/php/model_difficulty.php",
            type: "POST",
            data: "mode=callDifficulty&unit="+$('#unit-Filter').val(),
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

function updateDifficulty(callback){
        $.ajax({
            url: "./source/php/model_difficulty.php",
            type: "POST",
            data: "mode=updateDifficulty",
            success: function(result) {
                callback();
            }
        });
}

function callUnitDropdown() {
        $.ajax({
            url: "./source/php/model_difficulty.php",
            type: "POST",
            data: "mode=callUnitDropdown",
            success: function(result) {
                $("#unit-Filter").html(result);
            }
        });
}

/*
    // Bar Chart
    Morris.Bar({
        element: 'morris-bar-chart',
        data: [{
            device: 'article 1',
            geekbench: 0.6
        }, {
            device: 'article 2',
            geekbench: 0.8
        }, {
            device: 'article 3',
            geekbench: 0
        }, {
            device: 'article 4',
            geekbench: 0.4
        }, {
            device: 'article 5',
            geekbench: 0.5
        }, {
            device: 'article 6',
            geekbench: 0.1
        }],
        xkey: 'device',
        ykeys: ['geekbench'],
        labels: ['Geekbench'],
        barRatio: 0.4,
        xLabelAngle: 0,
        hideHover: 'auto',
        resize: true
    }); */
