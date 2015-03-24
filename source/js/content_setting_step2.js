$(document).ready(function() {
	//start frist run on page
	createPracticeForm();
	//end frist run on page

	//start event listener
	$('#saveSetting').click(function() {
		pushPracticeForm();
	});
	//end event listener
});

//start function zone
function createPracticeForm(){
        $.ajax({
            url: "./source/php/model_setting.php",
            type: "POST",
            data: "mode=practiceSetting&function=loadForm",
            success: function(result) {
               $('#practiceForm').html(result);
            }
        });
}
function callPracticeUpdate(pid, max_point, question) {
    $.ajax({
        url: "./source/php/model_setting.php",
        type: "POST",
        data: "mode=practiceSetting&function=saveForm&pid=" + pid + "&max_point=" + max_point + "&question=" + question,
        success: function(result) {
        	callContent("assignment_checking.php"); //this function from dashboard.js
        }
    });
}

function pushPracticeForm() {
    var practiceFormSize = $('#practiceForm').children().size(); //check size of children array 
    var i;

    for (i = 1; i <= practiceFormSize; i++) { //get information with DOM
        var pid = $('#pid_' + i).text();
        var max_point = $('#max_point_' + i).val();
        var question = $('#question_' + i).val();
        if(question != ""){
        	callPracticeUpdate(pid, max_point, question);
        }else{
        	alert("Error : Question was empty");
        	return;
        }
    }
}
//end function zone