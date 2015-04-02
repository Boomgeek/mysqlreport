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

    $('#saveUnit-btn').click(function() {
    	if($('.update-unit').size() > 0){
    		updateUnit(function() {
    			if($('.insert-unit').size() > 0){
		    		insertUnit();
		    	}
    		});
    	}
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
                    $("#status").html(data);
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

function saveUnit(mode,unit,uname,max_in_experiments,max_post_experiments)
{
		$.ajax({
            url: "./source/php/model_setting.php",
            type: "POST",
            data: "mode="+mode+"&unit="+unit+"&uname="+uname+"&max_in_experiments="+max_in_experiments+"&max_post_experiments="+max_post_experiments,
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
                    $("#status").html(data);
                }else if(res[0]== "checking"){
                    callContent("assignment_checking.php");
                }
            }
        });
}

function updateUnit(callback)
{
	var updateUnitSize = $('.update-unit').size();
	var i;
	for(i=1; i<=updateUnitSize; i++)
	{
		var unit = $('#update_unit_'+i).text();
		var uname = $('#update_uname_' + i).val();
        var max_in_experiments = $('#update_max_in_experiments_' + i).val();
        var max_post_experiments = $('#update_max_post_experiments_' + i).val();
        var mode = "updateUnit";
        if(uname == ""){
        	alert("Unit Name was empty");
        	return;
        }else{
        	saveUnit(mode,unit,uname,max_in_experiments,max_post_experiments);
        }
	}
	callback();
}

function insertUnit()
{
	var insertUnitSize = $('.insert-unit').size();
	var i;

	for(i=1; i<=insertUnitSize; i++)
	{
		var unit = $('#insert_unit_'+i).text();
		var uname = $('#insert_uname_' + i).val();
        var max_in_experiments = $('#insert_max_in_experiments_' + i).val();
        var max_post_experiments = $('#insert_max_post_experiments_' + i).val();
        var mode = "insertUnit";
        if(uname == ""){
        	alert("Unit Name was empty");
        	return;
        }else{
        	saveUnit(mode,unit,uname,max_in_experiments,max_post_experiments);
        }
	}
}

//end function zone