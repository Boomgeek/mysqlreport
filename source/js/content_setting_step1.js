$(document).ready(function() {
    //start Even listener
    $('#unitQuantity').change(function() {
        createUnitForm();
    });
    $('#nextSetting').click(function() {
        pushUnitForm();
    });
    //end Even listener
});


//start function zone
function callUnitInsert(unit, uname, max_in_experiments, max_post_experiments) {
    $.ajax({
        url: "./source/php/model_setting.php",
        type: "POST",
        data: "mode=unitSetting&unit=" + unit + "&uname=" + uname + "&max_in_experiments=" + max_in_experiments + "&max_post_experiments=" + max_post_experiments,
        success: function(result) {
            /*
            var res = result.split(":");
            if(res[0]== "checking"){
                callContent("assignment_checking.php");
            }
            */
        }
    });
}

function pushUnitForm() {
    var unitFormSize = $('#unitForm').children().size(); //check size of children array 
    var i;

    for (i = 1; i <= unitFormSize; i++) { //get information with DOM
        var unit = $('#unit_' + i).text();
        var uname = $('#uname_' + i).val();
        var max_in_experiments = $('#max_in_experiments_' + i).val();
        var max_post_experiments = $('#max_post_experiments_' + i).val();
        
        if(uname == ""){
            alert("Unit Name was empty");
            return;
        }else{
            callUnitInsert(unit, uname, max_in_experiments, max_post_experiments);
        }
    }
    
    callContent("assignment_checking.php");
}

function createUnitForm() {
    var unitQuantityValue = $('#unitQuantity').val();
    var unitFormSize = $('#unitForm').children().size(); //check size of children array 

    if (unitQuantityValue != 0) {
       if (unitQuantityValue > unitFormSize) {
            var i, data;
            for (i = unitFormSize; i < unitQuantityValue; i++) 
            {
                // unitForm Size+1 is next row number
                data = "<tr>";
                data += "<td id='unit_" + ($('#unitForm').children().size() + 1) + "'>" + ($('#unitForm').children().size() + 1) + "</td>";
                data += "<td><input type='text' class='form-control' id='uname_" + ($('#unitForm').children().size() + 1) + "' placeholder='Enter Unit Name'></td>";
                data += "<td><input type='number' class='form-control' id='max_in_experiments_" + ($('#unitForm').children().size() + 1) + "' value='0' min='0'></td>";
                data += "<td><input type='number' class='form-control' id='max_post_experiments_" + ($('#unitForm').children().size() + 1) + "' value='0' min='0'></td>";
                data += "</tr>";
                $('#unitForm').append(data);
            }
        } else if (unitQuantityValue < unitFormSize) {
            var i;
            var loop = unitFormSize - unitQuantityValue;
            for (i = 0; i < loop; i++) 
            {
                $('#unitForm').children()[$('#unitForm').children().size() - 1].remove(); //remove last array
            }
        }
    }
}
//end function zone
