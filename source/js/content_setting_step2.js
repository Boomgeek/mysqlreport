$(document).ready(function() {
	//start frist run on page
	createPracticeForm();
	//end frist run on page

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
//end function zone