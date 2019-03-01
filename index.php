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
           echo "<li class=\"list-group-item d-flex justify-content-between align-items-center\">";
           echo "<p>";
           echo $row['supplierid'];
           echo "</p>";
           echo "<p>";
           echo $row['amount'];
           echo "</p>";
           echo "<a href=#>Update</a>";
           echo "</li>";
           $flag=TRUE;
       }
       $conn->close();            

       return "<li class=\"list-group-item d-flex justify-content-between align-items-center\">Stub Output</li>";
    }
    
    ?>
    <link rel="stylesheet" href="bootstrap.css">
    <script>        
        function pushPayments(){
            //get all items from ul (2-end), and create a json object with it
            
            var data = [];
            var temp = { amount: 0, recipient:""};
            var content = document.getElementById("content");
            var i;
            for (i =1; i< content.children.length - 1; i++){
                temp.recipient = content.children[i].children[0].innerHTML;
                temp["amount"] = parseInt(content.children[i].children[1].innerHTML);
                data.push(temp);
            }
            console.log(data);
            
            //push to the end point using a post xmlhttprequest
            var xmlhttp = new XMLHttpRequest();
            var url = "https://api.paystack.co/transfer/bulk";
            var requestBody = {};
            requestBody.currency = "NGN";
            requestBody.source = "balance";
            requestBody.transfers = data;

            console.log(requestBody);
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var myArr = JSON.parse(this.responseText);
                    myFunction(myArr);
                }
            };
            
            xmlhttp.open("POST", url, true);
            xmlhttp.setRequestHeader("Authorization", "Bearer sk_test_4c4c90d3bd67ef9e750c6c60a3c9c1fbe2354525");
            
            console.log(JSON.stringify(requestBody));            
            console.log(xmlhttp);
            
            xmlhttp.send(JSON.stringify(requestBody));
            
            function myFunction(arr) {
                console.log(arr.data);
                var out = "";
                var i;
                //status and message
                console.log(arr.status);
                console.log(arr.message);
            }
            
            //clear all the items from the online database
            //TODO
            
            
            //refresh current page
            //TODO
            
        }
        
        function clearDB(){
            
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
    <div class="">
        <a type="button" class="btn btn-primary" href = "addSupplier.php">Add New Supplier</a>
        <a type="button" class="btn btn-primary" href = "addPayment.php">Add New Payment</a>
        <button type="button" class="btn btn-primary" onclick="pushPayments()">Push Payments</button>
    </div>
    <ul class="list-group table-active" id = "content">
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <p>Supplier ID</p>
            <p>Amount</p>
            <a> </a>
        </li>
        <?php getpayments(); ?>
    </ul>
</div>
</body>
</html>