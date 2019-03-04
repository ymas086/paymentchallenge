<html>
    <head>
        <?php
            //sudo service mysql start 
        
            define('__ROOT__', dirname(dirname(__FILE__))); 
            //require_once(__ROOT__.'/config.php');
            
             function saveSupplier(){
                //push data to the suppliers API
                $url = "https://api.paystack.co/transferrecipient"; 
                
                $data = array(
                  'type'           => 'nuban',
                  'name'           => $_POST["name"],
                  'account_number' => $_POST["accountno"],
                  'bank_code'      => $_POST["bank"],
                  'currency'       => 'NGN'
                );
                                
                $content = json_encode($data);
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HTTPHEADER,
                        array("Content-type: application/json", "Authorization: Bearer sk_test_4c4c90d3bd67ef9e750c6c60a3c9c1fbe2354525"));
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
                
                $json_response = curl_exec($curl);
                
                $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                
                curl_close($curl);
                
                $response = json_decode($json_response, true);

                
                if($response["status"]== 1){
                    header('Location: index.php');
                    exit();
                } else {
                    echo $response["message"];
                }
            }
        ?>
        <link rel="stylesheet" href="bootstrap.css">
        <title>Add Supplier</title>
        <script>
            window.onload = function(){
                var validation = function(){
                    //get list of recipients to populate dropdown list
                    var xmlhttp = new XMLHttpRequest();
                    var accountno = document.getElementById("accountno").value;
                    var bankcode = document.getElementById("bank").value;
                    var url = "getAccountName.php?account_number=" + accountno + "&bank_code=" + bankcode;
            
                    xmlhttp.onreadystatechange = function() {
                        if (xmlhttp.readyState == XMLHttpRequest.DONE){
                            console.log(this.responseText);
                            document.getElementById("accountname").value = xmlhttp.responseText;
                        }
                    };
                    xmlhttp.open("GET", url, true);
                    xmlhttp.send();            
                }
                document.getElementById("accountno").onchange = validation;
//                document.getElementById("bank").oninput = validation;
                
            }
        </script>
    </head>
    
    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
          <a class="navbar-brand" href="#">Paystack Challenge</a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
        
          <div class="collapse navbar-collapse" id="navbarColor01">
            <ul class="navbar-nav mr-auto">
              <li class="nav-item">
                <a class="nav-link" href="index.php">Home</a>
              </li>
              <li class="nav-item active">
                <a class="nav-link" href="addSupplier.php">Add Supplier <span class="sr-only">(current)</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="addPayment.php">Add Payment</a>
              </li>
            </ul>
          </div>
        </nav>
        <div class="container">
            <form method="post" action="addSupplier.php">        
                <div class="form-group">
                    <label class="col-form-label" for="name">Supplier Name</label>
                        <input type = "text" class="form-control" id="name" name="name"></select>
                    <label class="col-form-label" for="bank">Bank</label>
                        <select class="custom-select" id="bank" name="bank">
                            <?php
                            //populate the Bank select list using a remote API call to the transfer recipients end point
                            
                                $url = "https://api.paystack.co/bank";
                                
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
                                    foreach($response["data"] as $bank){
                                        echo '<option value="';
                                        echo $bank["code"].'">'.$bank["name"];
                                        echo "</option>";
                                    }
                                } else {
                                    echo $response["message"];
                                }
                            ?>
                        </select>
                    <label class="col-form-label" for="accountno">Supplier Account Number</label>
                        <input type="number" class="form-control" placeholder="Enter Account Number" id="accountno" name="accountno">
                        
                    <label class="col-form-label" for="accountname">Account Name</label>
                        <input type="text" class="form-control" placeholder="" id="accountname" name="accountname" disabled>
                    <button type="submit" class="btn btn-primary">Save</button>
                    <?php
                        if(isset($_POST["accountno"]))
                        {
                            //send the information to the paystack URL
                            //if(validate()){
                                saveSupplier();
                            //}
                        }
                    ?>
                </div>
            </form>
        </div>
    </body>
</html>