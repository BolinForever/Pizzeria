<?php
    session_start();
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
    <title>Pizzeria - Registration page</title>
</head>
<body>

<div><form action="registration_page.php" method="POST" id="registration">

    <h1>Pizzeria</h1>
    <h1>Registration</h1><br>

    <input type="login" name="login" placeholder="Login" required>
    <input type="pass" name="pass" placeholder="Pass" required>
    <input type="email" name="email" placeholder="E-mail" required>
    <input type="submit" name="add" value="Submit" required>

</form><div>

<?php

if(isset($_POST['add'])) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "sek_pizza";
    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection
    if (!$conn) {
    die("Coś nie pykło: " . mysqli_connect_error());
    }
    $login = $_POST['login'];
    $pass = $_POST['pass']; 
    $hash = sha1($pass);
    $email = $_POST['email'];
    $pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^";  

if (!preg_match ($pattern, $email) ){  
    $ErrMsg = "Email is not valid.";  
            echo $ErrMsg;  
}

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

    $sql = "INSERT INTO user (login, pass, email) VALUES ('$login', '$hash', '$email')";
    if (mysqli_query($conn, $sql)) {
    echo "Dodane xD";
    header('location: login_page.php');
    } else {
    echo "Nie dziaua : " . $sql . "<br>" . mysqli_error($conn);
    }
    mysqli_close($conn);
}

?>
    
</body>
</html>