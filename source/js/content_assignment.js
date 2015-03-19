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

    $('#saveAssignment').click(function() {
        pushAnswerCheckedForm();
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
            data: "mode=assignment&unit="+$('#unit-Filter').val()+"&type="+$('#type-Filter').val()+"&article="+$('#article-Filter').val(),
            success: function(result) {
                $('#assignment-Content').html(result);
                //checking for not have content
                if($('#assignment-Content').html() == ""){
                    var data;
                    data = "<div class='alert alert-danger alert-dismissible'>";
                    data += "<button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>";
                    data += "<strong>Assignment is empty :</strong> No students submits.</div>";
                    $("#assignment-Content").html(data);
                }
            }
        });
}

function callAnswerChecked(aid, status, point, comment) {
    //alert("mode=saveAnswerChecked&aid=" + aid + "&status=" + status + "&point=" + point + "&comment=" + comment);
    $.ajax({
        url: "./source/php/model_assignment.php",
        type: "POST",
        data: "mode=saveAnswerChecked&aid=" + aid + "&status=" + status + "&point=" + point + "&comment=" + comment,
        success: function(result) {
            callContent("checking.php"); //this function from dashboard.js
        }
    });
}

function pushAnswerCheckedForm() {
    var assignmentFormSize = $('#assignmentForm').children().size(); //check size of children array 
    var i;

    for (i = 1; i <= assignmentFormSize; i++) { //get information with DOM
        var aid = $('#aid_' + i).text();
        var status = $("input[name='status_"+i+"']:checked").val();
        var point = $('#point_' + i).val();
        var comment = $('#comment_' + i).val();
        if(comment == ""){
            comment = "NULL";
        }
        callAnswerChecked(aid, status, point, comment);
    }
}

//end fucntion zone
