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
                
                $options = array(
                  'http' => array(
                    'method'  => 'POST',
                    'content' => json_encode( $data ),
                    'header'=>  "Authorization: Bearer sk_test_4c4c90d3bd67ef9e750c6c60a3c9c1fbe2354525"
                    )
                );
                
                $context  = stream_context_create( $options );
                $result = file_get_contents( $url, false, $context );
                $response = json_decode( $result );
                
                echo $response;
            }
             function saveSupplier2(){
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
                
                $options = array(
                  'http' => array(
                    'method'  => 'POST',
                    'content' => json_encode( $data ),
                    'header'=>  "Authorization: Bearer sk_test_4c4c90d3bd67ef9e750c6c60a3c9c1fbe2354525"
                    )
                );
                
                $context  = stream_context_create( $options );
                $result = file_get_contents( $url, false, $context );
                $response = json_decode( $result );
                
                echo $response;
                
                if($response["status"] == true){
                    header('Location: index.php');
                    exit();
                }
                
                                
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HTTPHEADER,
                        array("Content-type: application/json", "Authorization: Bearer sk_test_4c4c90d3bd67ef9e750c6c60a3c9c1fbe2354525"));
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
                
                $json_response = curl_exec($curl);
                
                $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                
                echo $json_response;
                
//                if ( $status != 201 ) {
//                    die("Error: call to URL $url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
//                }
                
                
                curl_close($curl);
                
                $response = json_decode($json_response, true);
                
                foreach($response as $a){
                    echo $a;
                }
                
                if($response->status == true){
                    header('Location: index.php');
                    exit();
                }
            }
        ?>
        <link rel="stylesheet" href="bootstrap.css">
        <script>
            window.onload = function(){
                //get list of recipients to populate dropdown list
                var xmlhttp = new XMLHttpRequest();
                var url = "https://api.paystack.co/bank";
        
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
                        out += "\""  + arr["data"][i]["code"] + "\">" + arr["data"][i]["name"];
                        out += "</option>";
                    }
                    console.log(out);
                    document.getElementById("bank").innerHTML += out;
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
            <form method="post" action="addSupplier.php">        
                <div class="form-group">
                    <label class="col-form-label" for="name">Supplier Name</label>
                        <input type = "text" class="form-control" id="name" name="name"></select>
                    <label class="col-form-label" for="bank">Bank</label>
                        <select class="custom-select" id="bank" name="bank"></select>
                    <label class="col-form-label" for="accountno">Supplier Account Number</label>
                        <input type="number" class="form-control" placeholder="Enter Account Number" id="accountno" name="accountno">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <?php
                        if(isset($_POST["accountno"]))
                        {
                            //send the information to the paystack URL
                            echo $_POST["accountno"];
                            saveSupplier2();
                        }
                    ?>
                </div>
            </form>
        </div>
    </body>
</html>