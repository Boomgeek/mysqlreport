$(document).ready(function() {
	callTable();
});

//start function zone
function callTable() {
        $.ajax({
            url: "./source/php/model_assignment.php",
            type: "POST",
            data: "null",
            success: function(result) {
                $('#answerTable').html(result);
            }
        });
    }
//end fucntion zone
