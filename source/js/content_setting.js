$(document).ready(function() {

    //start Even listener
    $('#unitQuantity').change(function() {
        createForm();
    });
    $('#saveSetting').click(function() {
            pushForm();
        })
        //end Even listener


    //start function zone
    function callInsert(unit, uname, max_practice_while, max_practice_after) {
        $.ajax({
            url: "./source/php/model_setting.php",
            type: "POST",
            data: "unit=" + unit + "&uname=" + uname + "&max_practice_while=" + max_practice_while + "&max_practice_after=" + max_practice_after,
            success: function(result) {
                $('#content').html(result);
            }
        });
    }

    function pushForm() {
        var unitFormSize = $('#unitForm').children().size(); //check size of children array 
        var i;

        for (i = 1; i <= unitFormSize; i++) { //get information with DOM
            var unit = $('#unit' + i).text();
            var uname = $('#uname' + i).val();
            var max_practice_while = $('#while' + i).val();
            var max_practice_after = $('#after' + i).val();

            callInsert(unit, uname, max_practice_while, max_practice_after);
        }
    }

    function createForm() {
        var unitQuantityValue = $('#unitQuantity').val();
        var unitFormSize = $('#unitForm').children().size(); //check size of children array 

        if (unitQuantityValue != 0) {
            if (unitQuantityValue > unitFormSize) {
                var i, data;
                for (i = unitFormSize; i < unitQuantityValue; i++) {
                    // unitForm Size+1 is next row number
                    data = "<tr>";
                    data += "<td id='unit" + ($('#unitForm').children().size() + 1) + "'>" + ($('#unitForm').children().size() + 1) + "</td>";
                    data += "<td><input type='text' class='form-control' id='uname" + ($('#unitForm').children().size() + 1) + "' placeholder='Enter Unit Name'></td>";
                    data += "<td><input type='number' class='form-control' id='while" + ($('#unitForm').children().size() + 1) + "' value='0' min='0'></td>";
                    data += "<td><input type='number' class='form-control' id='after" + ($('#unitForm').children().size() + 1) + "' value='0' min='0'></td>";
                    data += "</tr>";
                    $('#unitForm').append(data);
                }
            } else if (unitQuantityValue < unitFormSize) {
                var i;
                var loop = unitFormSize - unitQuantityValue;
                for (i = 0; i < loop; i++) {
                    $('#unitForm').children()[$('#unitForm').children().size() - 1].remove(); //remove last array
                }
            }
        }
    }
        //end function zone
});
