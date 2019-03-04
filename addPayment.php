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
                  '(supplierid, suppliername, amount, dateadded) '.
                  'VALUES ( "'.
                  $_POST["recipient"].
                  '","'.
                  $_POST["recipientname"].
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
        <title>Add Payment</title>
    </head>
    
    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
          <a class="navbar-brand" href="#">Paystack Challenge</a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" 
                aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
        
          <div class="collapse navbar-collapse" id="navbarColor01">
            <ul class="navbar-nav mr-auto">
              <li class="nav-item">
                <a class="nav-link" href="index.php">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="addSupplier.php">Add Supplier</a>
              </li>
              <li class="nav-item active">
                <a class="nav-link" href="addPayment.php">Add Payment <span class="sr-only">(current)</span></a>
              </li>
            </ul>
          </div>
        </nav>
        <div class="container">
            <form method="post" action="addPayment.php">        
                <div class="form-group">
                    <label class="col-form-label" for="recipient">Recipient</label>
                        <select class="custom-select" id="recipient" name="recipient" 
                            onchange="document.getElementById('recipientname').value = this.options[this.selectedIndex].text">
                            <?php
                            //populate the select list using a remote API call to the transfer recipients end point
                            
                                $url = "https://api.paystack.co/transferrecipient";
                                
                                $curl = curl_init($url);
                                curl_setopt($curl, CURLOPT_HEADER, false);
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($curl, CURLOPT_HTTPHEADER,
                                        array("Content-type: application/json", "Authorization: Bearer sk_test_4c4c90d3bd67ef9e750c6c60a3c9c1fbe2354525"));
                                //curl_setopt($curl, CURLOPT_POST, true);
                                
                                $json_response = curl_exec($curl);
                                                                        
                                curl_close($curl);
                                
                                $response = json_decode($json_response, true);

                                //echo $response["data"]["recipient_code"];
                                if($response["status"]== 1){
                                    foreach($response["data"] as $recipient){
                                        echo '<option value="';
                                        echo $recipient["recipient_code"].'">'.$recipient["name"];
                                        echo "</option>";
                                    }
                                    $firstname = $response["data"][0]["name"];
                                } else {
                                    echo $response["message"];
                                }
                            ?>
                        </select>
                        <?php 
                            echo "<input type = 'hidden' id='recipientname' name='recipientname'".
                            "value = '".$firstname."'/>";
                        ?>
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