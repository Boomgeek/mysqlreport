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

    $('#assignment-Content').delegate( ".status-btn", "click", function() {
        var num = $(this).attr('id').split('_')[1];
        CheckedAssignment(num);
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

function callSaveAnswerChecked(aid, status, point, comment, no) {
    //alert("mode=saveAnswerChecked&aid=" + aid + "&status=" + status + "&point=" + point + "&comment=" + comment);
    $.ajax({
        url: "./source/php/model_assignment.php",
        type: "POST",
        data: "mode=saveAnswerChecked&aid=" + aid + "&status=" + status + "&point=" + point + "&comment=" + comment,
        success: function(result) {
            var res = result.split(":");
            if(res[0] == "Success"){
                updateBadge();                          //this function from dashboard.js
                callAssignment();
                NoticeChecked(no);
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

function CheckedAssignment(num)
{
    var aid = $('#aid_' + num).text();
    var point = $('#point_' + num).val();
    var comment = $('#comment_' + num).val();
    var status = 1;
    var no = $('#no_' + num).text();
    if(comment == "")
    {
        comment = "NULL";
    }
    callSaveAnswerChecked(aid, status, point, comment, no);
}

function NoticeChecked(no) {
    new jBox('Notice', {
        autoClose: 3000,
        attributes:{x:'right',y:'bottom'},
        stack:false,
        animation:{open:'tada',close:'zoom'},
        color:'green',
        title:"<i class='fa fa-check-square-o'></i> Checked",
        content: "Checked assignment "+no+" successful."
    });
}
//end fucntion zone
