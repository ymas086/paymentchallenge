<html>
<head>
    <?php
    
    //sudo service mysql start 

    define('__ROOT__', dirname(dirname(__FILE__))); 
    //require_once(__ROOT__.'/config.php'); 

    function getpayments(){
        //get scheduled payments from db and display on home page
       $dbhost = "db4free.net:3306";
       $dbuser = "yusufms";
       $dbpass = "pass@word1";
       $dbname = "yusufms";
       $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
       
       if ($conn->connect_error) {
           die("Connection failed: " . $conn->connect_error);
       }
       
       $sql = 'SELECT * FROM Payments';
       
       $result = mysqli_query($conn,$sql);  
       
       while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
           echo "<tr>";
           echo "<td>";
           echo $row['suppliername'];
           echo "</td>";
           echo "<td>";
           echo $row['amount'];
           echo "</td>";
           echo "<td>";
           echo "<a href=#>Update</a>";
           echo "</td>";
           echo "</tr>";
           $flag=TRUE;
       }
       $conn->close();
    }
    
    
    function pushPayments(){
        //query remote sql server to get saved payments
        $dbhost = "db4free.net:3306";
        $dbuser = "yusufms";
        $dbpass = "pass@word1";
        $dbname = "yusufms";
        $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
       
       if ($conn->connect_error) {
           die("Connection failed: " . $conn->connect_error);
           echo "db error";
       }
       
       $sql = 'SELECT * FROM Payments';
       
       $result = mysqli_query($conn,$sql);  
       
       $payments = array();
       
       while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
           $temp = array(
            'amount'    => $row['amount'],
            'recipient' => $row['supplierid']
           );
           array_push($payments, $temp);
           $flag=TRUE;
       }
       
        //push bulk transfers using paystack API
        $url = "https://api.paystack.co/transfer/bulk"; 
        
        $data = array(
          'source'    => 'balance',
          'currency'  => 'NGN',
          'transfers' => $payments
        );
                        
        $content = json_encode($data);
        
        $options = array(
          'http' => array(
            'method'  => 'POST',
            'content' => json_encode( $data ),
            'header'=>  "Authorization: Bearer sk_test_4c4c90d3bd67ef9e750c6c60a3c9c1fbe2354525"
            )
        );
        
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
                array("Content-type: application/json", "Authorization: Bearer sk_test_4c4c90d3bd67ef9e750c6c60a3c9c1fbe2354525"));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
        
        $json_response = curl_exec($curl);
        
        //$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        
        //echo $json_response;
                
        curl_close($curl);
        
        $response = json_decode($json_response, true);

        
        if($response["status"]== 1){
            //clear all the items from the online database
            //use same sql connection above
             $sql = 'DELETE FROM Payments';
             $result = mysqli_query($conn,$sql);
            
        } else {
            echo $response["message"];
        }
        $conn->close();
    }
    ?>
    <link rel="stylesheet" href="bootstrap.css">
<title>Home - View Recipients</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="#">Paystack Challenge</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    
      <div class="collapse navbar-collapse" id="navbarColor03">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="addSupplier.php">Add Supplier</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="addPayment.php">Add Payment</a>
          </li>
          <li class="nav-item">
            <a class="btn btn-primary" style="backgoundColor=#fffff"onclick="document.getElementById('form').submit();">Push Payments</a>
          </li>
        </ul>
      </div>
    </nav>
    <div class="container">
        <table class="table table-hover" id = "content">
            <form action = "index.php" method="post" id="form">
                <input value="1" name="push" type="hidden">
            </form>
            <thead>
                <th>Supplier</th>
                <th>Amount</th>
                <th></th>
            </thead>
            <tbody>
                <?php if(isset($_POST["push"])){
                    pushPayments();
                }
                ?>
                <?php getpayments(); ?>
            </tbody>
        </table>
    </div>
</body>
</html>