<?php

session_start();

if(isset($_POST['submit'])) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "sek_pizza";
    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
    die("Coś nie pykło: " . mysqli_connect_error());
    }
    $login = $_POST['login'];
    $pass = $_POST['pass'];
    $hash = sha1($pass);

    function Cipher($ch)
  {
      if (!ctype_alpha($ch))
          return $ch;
      $offset = ord(ctype_upper($ch) ? 'A' : 'a');
      return chr(fmod(((ord($ch) + 3) - $offset), 26) + $offset);
  }
  function Encipher($input)
  {
      $output = "";
      $inputArr = str_split($input);
      foreach ($inputArr as $ch)
        $output .= Cipher($ch, 3);
    return $output;
  }

    $hash = Encipher($hash);
    $sql = "SELECT * FROM user WHERE login='$login' and pass='$hash';";
    $result = mysqli_query($conn, $sql);
    if ($result > 0) {
        $_SESSION['login']=$login;
        header('location: main_page.php');
    } else {
        echo "Error - something's wrong!";
        session_destroy();
    };
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="registrationpage_style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <title>Pizzeria - Log in form</title>
</head>
<body>

<form action="login_page.php" method="POST" id="registration">

    <h1>Pizzeria</h1>
    <h1>Log In Form</h1><br>

    <input type="text" name="login" placeholder="Login">
    <input type="text" name="hash" placeholder="Password">
    <input type="submit" name="submit" value="Submit">

</form>
    
</body>
</html>