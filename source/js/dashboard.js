$(document).ready(function() {

    //start load index content
    callContent("assignment_checking.php");
    updateBadge();
    //end load index content

    //start event listener zone
    $('.menu-control').click(function() {
        $('.menu-control').removeClass('active');
        $(this).addClass("active");
    });
    
    $('#assignment-btn').click(function() {
        callContent("assignment_checking.php");
        updateBadge();
    });
    
    $('#progressive-btn').click(function() {
        callContent("progressive.php");
    });
    
    $('#rating-btn').click(function() {
        callContent("rating.php");
    });

    $('#difficulty-btn').click(function() {
        callContent("difficulty.php");
    });

    $('#ExperimentLogs-btn').click(function() {
        callContent("experiment_logs.php");
    });

    $('#sign-out-btn').click(function() {
        window.location.replace("http://"+window.location.host+"/moodle/my/");
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
