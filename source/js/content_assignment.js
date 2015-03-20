$(document).ready(function() {

    //Start start windows zone
	callDropdown("mode=unit&status="+$('#status-Filter').val(),"#unit-Filter",function(){
        callDropdown("mode=type&status="+$('#status-Filter').val()+"&unit="+$('#unit-Filter').val(),"#type-Filter",function() {
            callDropdown("mode=article&status="+$('#status-Filter').val()+"&unit="+$('#unit-Filter').val()+"&type="+$('#type-Filter').val(),"#article-Filter",function() {
                callAssignment();
            });
        });
    });
    //End start windows zone

    //start event listener zone
    $('#status-Filter').change(function() {
        callDropdown("mode=unit&status="+$('#status-Filter').val(),"#unit-Filter",function(){
            callDropdown("mode=type&status="+$('#status-Filter').val()+"&unit="+$('#unit-Filter').val(),"#type-Filter",function() {
                callDropdown("mode=article&status="+$('#status-Filter').val()+"&unit="+$('#unit-Filter').val()+"&type="+$('#type-Filter').val(),"#article-Filter",function() {
                    callAssignment();
                });
            });
        });
    });
    $('#unit-Filter').change(function() {
        callDropdown("mode=type&status="+$('#status-Filter').val()+"&unit="+$('#unit-Filter').val(),"#type-Filter",function() {
            callDropdown("mode=article&status="+$('#status-Filter').val()+"&unit="+$('#unit-Filter').val()+"&type="+$('#type-Filter').val(),"#article-Filter",function() {
                callAssignment();
            });
        });
    });

    $('#type-Filter').change(function() {
        callDropdown("mode=article&status="+$('#status-Filter').val()+"&unit="+$('#unit-Filter').val()+"&type="+$('#type-Filter').val(),"#article-Filter",function() {
            callAssignment();
        });
    });

    $('#article-Filter').change(function() {
        callAssignment();
    });

    $('#saveAssignment').click(function() {
        pushAnswerCheckedForm();
        updateBadge();          //this function from dashboard.js. this file is run on dashboard.php
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
    //alert("mode=assignment&unit="+$('#unit-Filter').val()+"&type="+$('#type-Filter').val()+"&article="+$('#article-Filter').val());
        $.ajax({
            url: "./source/php/model_assignment.php",
            type: "POST",
            data: "mode=assignment&status="+$('#status-Filter').val()+"&unit="+$('#unit-Filter').val()+"&type="+$('#type-Filter').val()+"&article="+$('#article-Filter').val(),
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

function callSaveAnswerChecked(aid, status, point, comment) {
    //alert("mode=saveAnswerChecked&aid=" + aid + "&status=" + status + "&point=" + point + "&comment=" + comment);
    $.ajax({
        url: "./source/php/model_assignment.php",
        type: "POST",
        data: "mode=saveAnswerChecked&aid=" + aid + "&status=" + status + "&point=" + point + "&comment=" + comment,
        success: function(result) {
            var res = result.split(":");
            if(res[0] == "Success"){
                callContent("checking.php"); //this function from dashboard.js
            }else{
                var data;
                data = "<div class='alert alert-danger alert-dismissible'>";
                data += "<button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>";
                data += "<strong>"+res[0]+" : </strong>"+res[1]+"</div>";
                $("#assignment-Content").html(data); 
            }
        }
    });
}

function pushAnswerCheckedForm() {
    var assignmentFormSize = $('#assignmentForm').children().size(); //check size of children array 
    var i;

    for (i = 1; i <= assignmentFormSize; i++) { //get information with DOM
        //check status is checked box
        if($("#status_" + i).prop('checked') ){
            var aid = $('#aid_' + i).text();
            var point = $('#point_' + i).val();
            var comment = $('#comment_' + i).val();
            var status = 1;
            if(comment == ""){
                comment = "NULL";
            }
            callSaveAnswerChecked(aid, status, point, comment);
        }
    }
}

//end fucntion zone
