$(document).ready(function() {
//Start start windows zone
	callDropdown("mode=unitStudent","#unit-Filter",function(){
        callDropdown("mode=typeStudent&unit="+$('#unit-Filter').val(),"#type-Filter",function() {
            callAssignment();
        });
    });
    //End start windows zone

    //start event listener zone
    $('#unit-Filter').change(function() {
        callDropdown("mode=typeStudent&unit="+$('#unit-Filter').val(),"#type-Filter",function() {
            callAssignment();
        });
    });

    $('#type-Filter').change(function() {
        callAssignment();
    });
});


//start function zone
function callDropdown(data,res,callback) {
        $.ajax({
            url: "./source/php/model_assignment.php",
            type: "POST",
            data: data,
            success: function(result) {
                $(res).html(result);
                callback();
            }
        });
}

function callAssignment(){
    //alert("mode=assignment&unit="+$('#unit-Filter').val()+"&type="+$('#type-Filter').val());
        $.ajax({
            url: "./source/php/model_assignment.php",
            type: "POST",
            data: "mode=assignmentStudent&unit="+$('#unit-Filter').val()+"&type="+$('#type-Filter').val(),
            success: function(result) {
                var res = result.split(":");
                if(res[0]== "Assignment is empty"){
                    var data;
                    data = "<div class='alert alert-danger alert-dismissible'>";
                    data += "<button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>";
                    data += "<strong>"+res[0]+" : </strong>"+res[1]+"</div>";
                    $("#assignment-Content").html(data);
                }else{
                    $('#assignment-Content').html(result);
                }
            }
        });
}