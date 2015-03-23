$(document).ready(function() {

    //start load index content
    callContent("checking.php");
    updateBadge();
    //end load index content

    //start event listener zone
    $('#assignment-btn').click(function() {
        callContent("checking.php");
        updateBadge();
    });
    
    $('#progressive-btn').click(function() {
        callContent("progressive_teacher.php");
    });
    //end event listener zone

});
//start function zone
function callContent(source) {
    $.ajax({
        url: "./source/content/" + source,
        type: "POST",
        data: "",
        success: function(result) {
            $('#content').html(result);
        }
    });
}

function updateBadge(){
    //alert(1);
    $.ajax({
        url: "./source/php/assignment_badge.php",
        type: "POST",
        data: "mode=updateBadge",
        success: function(result) {
            $('#asssignment-badge').html(result);
        }
    });
}
//end function zone
