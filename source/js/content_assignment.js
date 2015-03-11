$(document).ready(function() {

    //Start start windows zone
	callDropdown("mode=unit","#unit-Filter",function(){
        callDropdown("mode=type&unit="+$('#unit-Filter').val(),"#type-Filter",function() {
            callDropdown("mode=article&unit="+$('#unit-Filter').val()+"&type="+$('#type-Filter').val(),"#article-Filter",function() {
                callAssignment();
            });
        });
    });
    //End start windows zone

    //start event listener zone
    $('#unit-Filter').change(function() {
        callDropdown("mode=type&unit="+$('#unit-Filter').val(),"#type-Filter",function() {
            callDropdown("mode=article&unit="+$('#unit-Filter').val()+"&type="+$('#type-Filter').val(),"#article-Filter",function() {
                callAssignment();
            });
        });
    });

    $('#type-Filter').change(function() {
        callDropdown("mode=article&unit="+$('#unit-Filter').val()+"&type="+$('#type-Filter').val(),"#article-Filter",function() {
            callAssignment();
        });
    });

    $('#article-Filter').change(function() {
        callAssignment();
    });
    //end event listener zone

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
        $.ajax({
            url: "./source/php/model_assignment.php",
            type: "POST",
            data: "mode=assignment&unit="+$('#unit-Filter').val()+"&type="+$('#type-Filter').val()+"&article="+$('#article-Filter').val(),
            success: function(result) {
                $('#assignment-Content').html(result);
            }
        });
}

//end fucntion zone
