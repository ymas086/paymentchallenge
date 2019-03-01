<html>
    <head>
        <?php
            //sudo service mysql start 
        
            define('__ROOT__', dirname(dirname(__FILE__))); 
            //require_once(__ROOT__.'/config.php');
            
            function saveAmount(){
                //push amount to the database and return to home page
               $dbhost = "db4free.net:3306";
               $dbuser = "yusufms";
               $dbpass = "pass@word1";
               $dbname = "yusufms";
               $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
               
               if ($conn->connect_error) {
                   die("Connection failed: " . $conn->connect_error);
               }


               $amount = intval($_POST["amount"]);
               $amount *= 100;
               print strval($amount);
               $sql = 'INSERT INTO Payments '.
                  '(supplierid, amount, dateadded) '.
                  'VALUES ( "'.
                  $_POST["recipient"].
                  '",'.
                  strval($amount).
                  ',now());';
                if ($conn->query($sql) === TRUE) {
                    echo "New record created successfully";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
                
                $conn->close();            
               header('Location: index.php');
               exit();
            }
        ?>
        <link rel="stylesheet" href="bootstrap.css">
        <script>
            window.onload = function(){
                //get list of recipients to populate dropdown list
                var xmlhttp = new XMLHttpRequest();
                var url = "https://api.paystack.co/transferrecipient";
        
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var myArr = JSON.parse(this.responseText);
                        myFunction(myArr);
                    }
                };
                xmlhttp.open("GET", url, true);
                xmlhttp.setRequestHeader("Authorization", "Bearer sk_test_4c4c90d3bd67ef9e750c6c60a3c9c1fbe2354525")
                xmlhttp.send();
                
                function myFunction(arr) {
                    console.log(arr.data);
                    var out = "";
                    var i;
                    for(i = 0; i < arr["data"].length; i++) {
                        out += "<option value="
                        out += "\""  + arr["data"][i]["recipient_code"] + "\">" + arr["data"][i]["name"];
                        out += "</option>";
                    }
                    console.log(out);
                    document.getElementById("recipient").innerHTML += out;
                }
            }
    
        </script>
        <title>Home - View Recipients</title>
    </head>
    
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
          <a class="navbar-brand" href="#">Navbar</a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
        
          <div class="collapse navbar-collapse" id="navbarColor01">
            <ul class="navbar-nav mr-auto">
              <li class="nav-item active">
                <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#"></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Pricing</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">About</a>
              </li>
            </ul>
          </div>
        </nav>
        <div class="container">
            <div class="row">
                <a type="button" class="btn btn-primary" href = "addSupplier.php">Add New Supplier</a> <br/>
                <a type="button" class="btn btn-primary" href = "#">Add New Payment</a> <br/>
            </div>
            <form method="post" action="addPayment.php">        
                <div class="form-group">
                    <label class="col-form-label" for="recipient">Recipient</label>
                        <select class="custom-select" id="recipient" name="recipient"></select>
                    <label class="col-form-label" for="amount">Amount</label>
                        <input type="number" class="form-control" placeholder="Enter amount" id="amount" name="amount">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <?php
                        if(isset($_POST["amount"]))
                        {
                            //send the information to the paystack URL
                            echo $_POST["amount"];
                            saveAmount();
                        }
                    ?>
                </div>
            </form>
        </div>
    </body>
</html>