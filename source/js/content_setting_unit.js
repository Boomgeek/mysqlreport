$(document).ready(function (){
    //Start start windows zone
    callUnitSetting();
    //End start windows zone

    //Start event listener zone
    $('#UnitSetting-Content').delegate( "#addUnit-btn", "click", function() {
    	addUnit();
    });

    $('#UnitSetting-Content').delegate("#delete-btn", "click", function () {
    	deleteUnit();
    });   
    //End event listener zone
});

//start function zone
function callUnitSetting(){
        $.ajax({
            url: "./source/php/model_setting.php",
            type: "POST",
            data: "mode=callUnitSetting",
            success: function(result) {
                var res = result.split(":");
                if(res[0]== "Error"){
                    var data;
                    data = "<div class='alert alert-danger alert-dismissible'>";
                    data += "<button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>";
                    data += "<strong>"+res[0]+" : </strong>"+res[1]+"</div>";
                    $("#UnitSetting-Content").html(data);
                }else{
                    $('#UnitSetting-Content').html(result);
                }
            }
        });
}

function addUnit(){
	var data;
	data = "<tr class='insert-unit'>";
    data += "<td id='insert_unit_"+($('.insert-unit').size() + 1)+"'>"+($('#unitForm').children().size() + 1)+"</td>";
    data += "<td><input type='text' class='form-control' id='insert_uname_" + ($('.insert-unit').size() + 1) + "' placeholder='Enter Unit Name'></td>";
    data += "<td><input type='number' class='form-control' id='insert_max_in_experiments_" + ($('.insert-unit').size() + 1) + "' value='0' min='0'></td>";
    data += "<td><input type='number' class='form-control' id='insert_max_post_experiments_" + ($('.insert-unit').size() + 1) + "' value='0' min='0'></td>";
    data += "</tr>";
    $('#unitForm').append(data);
}

function deleteUnit(){
	var lastUnit = $('#unitForm').children().last().children().text();
	var result = confirm("Do you want delete Unit "+lastUnit+" ?");
	if(result){
		var className = $('#unitForm').children().last().attr('class');			//get class name from last row
		if(className == 'update-unit'){
			$('#unitForm').children().last().remove();
			$.ajax({
	            url: "./source/php/model_setting.php",
	            type: "POST",
	            data: "mode=deleteUnit&unit="+lastUnit,
	            success: function(result) {
	                var res = result.split(":");
	                if(res[0]== "Error"){
	                    var data;
	                    data = "<div class='alert alert-danger alert-dismissible'>";
	                    data += "<button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>";
	                    data += "<strong>"+res[0]+" : </strong>"+res[1]+"</div>";
	                    $("#status").html(data);
	                }else if(res[0]== "Success"){
	                	var data;
	                    data = "<div class='alert alert-success alert-dismissible'>";
	                    data += "<button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>";
	                    data += "<strong>"+res[0]+" : </strong>"+res[1]+"</div>";
	                    $('#status').html(data);
	                }
	            }
	        });
		}else if(className == 'insert-unit'){
			$('#unitForm').children().last().remove();
			var data;
	        data = "<div class='alert alert-success alert-dismissible'>";
	        data += "<button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>";
	        data += "<strong>"+res[0]+" : </strong>"+res[1]+"</div>";
	        $('#status').html(data);
		}
    }
}
//end function zone