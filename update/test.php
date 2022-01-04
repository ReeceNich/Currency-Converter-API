<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form for POST, PUT & DELETE</title>
</head>
<body>
    <h1>Form for POST, PUT & DELETE</h1>
    

    <input type="radio" name="action" id="PUT" value="PUT" onclick="actionClick()"/>
    <label for="PUT">PUT</label>

    <input type="radio" name="action" id="POST" value="POST" onclick="actionClick()"/>
    <label for="POST">POST</label>

    <input type="radio" name="action" id="DEL" value="DEL" onclick="actionClick()"/>
    <label for="DEL">DEL</label>

    <br/>
    <label for="cur">Choose a currency:</label>
    <select name="cur" id="cur">
        <option value="" selected disabled>Select currency code</option>
    </select>

    <br/>
    <input type="submit" value="Submit" onclick="submit()"/>


    <h2>Response XML</h2>
    <textarea id="output" rows=10 cols=50>

    </textarea>




    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script>
        function actionClick() {
            var action=$('input[type="radio"]:checked').val();
            console.log(action);
            var pass_data = {
                'action' : action
            };

            $.ajax({
                url : "actionGetCurrency.php?action=" + action,
                type : "GET",
                data: pass_data,
                success : function(data) {
                    // put returned data into dropdown menu
                    // alert(data);

                    var json = JSON.parse(data);
                    console.log(json);

                    // Select the dropdown menu 
                    const sb = document.querySelector('#cur');
                    
                    // REMOVE ALL EXISTING ELEMENTS FROM LIST AND REPOPULATE WITH LATEST CCODES.
                    $('#cur')
                        .find('option')
                        .remove()
                        .end()
                        .append('<option value="" selected disabled>Select currency code</option>')
                        .val('whatever')

                    for(let i = 0; i < json.length; i++) {
                        let newOption = new Option(json[i]["ccode"] + " - " + json[i]["cname"], json[i]["ccode"]);
                        sb.appendChild(newOption);
                    }

                }
            });

            
;
        }


        function submit() {
            var action=$('input[type="radio"]:checked').val();
            var curDropdown = document.getElementById("cur");
            var ccode = curDropdown.value;

            var pass_data = {
                'action' : action,
                'ccode' : ccode
            };

            console.log(action);
            console.log(ccode);

            $.ajax({
                url : "index.php?cur=" + ccode + "&action=" + action,
                type : "GET",
                dataType: "text",
                data: pass_data,
                success : function(data) {
                    // put returned data into dropdown menu
                    // alert(data);
                    // console.log("index.php?cur=" + ccode + "&action=" + action);
                    // Select the dropdown menu 
                    const out = document.querySelector('#output');

                    out.innerHTML = data;

                    actionClick();
                }
            });
        }





    </script>


</body>
</html>