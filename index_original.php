<html>
<head>
    <?php
    
    //sudo service mysql start 

    define('__ROOT__', dirname(dirname(__FILE__))); 
    //require_once(__ROOT__.'/config.php'); 
    function getpayments(){
        return "<li class=\"list-group-item d-flex justify-content-between align-items-center\">Stub Output</li>";
    }
    ?>
    <link rel="stylesheet" href="bootstrap.css">
    <script>
        window.onload = function(){
            var xmlhttp = new XMLHttpRequest();
            var url = "https://api.paystack.co/transferrecipient";
    
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var myArr = JSON.parse(this.responseText);
                    myFunction(myArr);
                }
            };
            xmlhttp.open("GET", url, true);
            xmlhttp.setRequestHeader("Authorization", "Bearer sk_test_7fcd75d85841c297b58cd43dd881750de433ad09")
            xmlhttp.send();
            
            function myFunction(arr) {
                console.log(arr.data);
                var out = "";
                var i;
                for(i = 0; i < arr["data"].length; i++) {
                    //out += '<a href="' + arr[i].url + '">' + 
                    //arr[i].display + '</a><br>';
                    //out += arr[i]["data"]["id"] + " - " + item["name"] + "<br/>";
                    out += "<li class=\"list-group-item d-flex justify-content-between align-items-center\">"
                    out += arr["data"][i]["id"] + " - "+ arr["data"][i]["name"];
                    out += "<a href=#> Update </a>"
                    out += "</li>";
                    
                }
                //document.getElementById("content").innerHTML += out;
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
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="text" placeholder="Search">
      <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
    </form>
  </div>
</nav>
<div class="container">
    <div class="row">
        <a type="button" class="btn btn-primary" href = "addSupplier.php">Add New Supplier</a> <br/>
        <a type="button" class="btn btn-primary" href = "#">Add New Payment</a> <br/>
    </div>
    <ul class="list-group" id = "content">
    <?php
        //TODO: fetch the data from the db in the cloud
        echo getPayments();
        $people = array("Alice", "Bob", "Catherine");
        //print_r($people);
        //echo($people[2]);
        //echo "<p>Something just like this</p>";
        ///echo "<h1>";
        foreach($people as $person){
            //echo $person . ' ';
        }
        //echo "</h1>";
    ?>
    </ul>
</div>
</body>
</html>