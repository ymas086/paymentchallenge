<html>
<head>
    <?php
    
    //sudo service mysql start 

    define('__ROOT__', dirname(dirname(__FILE__))); 
    //require_once(__ROOT__.'/config.php'); 
    ?>
    <link rel="stylesheet" href="bootstrap.css">
<title>Hello World</title>
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
        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Features</a>
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
<?php
    $people = array("Alice", "Bob", "Catherine");
    //print_r($people);
    //echo($people[2]);
    echo "<p>Something just like this</p>";
    foreach($people as $person){
        echo $person . ' ';
    }
    
?>

<form action = "addSupplier.php" method = "post">
    Enter your name:
    <input name = "name" type="text">
    <input type="submit">
</form>
</body>
</html>