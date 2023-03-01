<?php

    session_start();

    $login = $_SESSION['login'];
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "sek_pizza";
    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <title>Main Page</title>

    <style>
    
    * {
    margin: 0;
    font-family: 'Montserrat', sans-serif;
}

body {
    display: flex;
    width: 100vw;
    height: 100vh;
    align-items: center;
    flex-direction: row-reverse;
    justify-content: center;
    background: rgb(252,217,57);
    background: linear-gradient(90deg, rgba(252,217,57,1) 0%, rgba(91,204,86,1) 48%, rgba(231,47,47,1) 100%);
}

input {
    display: flex;
    flex-direction: row;
    margin: 10px;
    border-radius: 10px;
}

.forms {
    height: 50vh;
    width: 50vw;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    border-radius: 50px;
}

form {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
}

.PizzaForm {
    height: 50vh;
    width: 50vw;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    border-radius: 50px;
    text-align:center;
}

::placeholder {
    text-align: center;
}

br {
    display: block;
    margin-bottom: 10px;
    font-size:10px;
    line-height: 10px;
}

#honey {
    margin-bottom: 20px;
}

</style>
</head>
<body>

<div class="forms">

    <h1>User's data editing</h1><br>

        <form action="main_page.php" method="POST" id="form">

            <input type="text" name="loginChange" placeholder="Change Login"><br>
            <input type="text" name="passConfirm1" placeholder="Confirm with password"><br>
            <input type="submit" name="button1" value="Submit" required>

            <?php

                if(isset($_POST['button1']) && isset($_POST['loginChange']) && isset($_POST['passConfirm1'])) {

                    $sql = "SELECT pass FROM user WHERE login='" . $_SESSION['login'] . "';";

                    if(mysqli_fetch_array(mysqli_query($conn, $sql))[0] == Encipher(sha1($_POST['passConfirm1']))) {
            
                        $changedLogin = $_POST['loginChange'];

                        $sql = "UPDATE user SET login = '$changedLogin' WHERE login='" . $_SESSION['login'] . "';";

                        $_SESSION['login'] = $_POST['loginChange'];

                        mysqli_query($conn, $sql) or die(mysqli_error($conn));
                        
                    }

                }

            ?>

        </form>

        <form action="main_page.php" method="POST" id="form">

            <input type="text" name="passwordChange" placeholder="Change Password"><br>
            <input type="text" name="passConfirm2" placeholder="Confirm with password"><br>
            <input type="submit" name="button2" value="Submit" required>

            <?php

                if(isset($_POST['button2']) && isset($_POST['passwordChange']) && isset($_POST['passConfirm2'])) {

                    $sql = "SELECT pass FROM user WHERE login='" . $_SESSION['login'] . "';";

                    if(mysqli_fetch_array(mysqli_query($conn, $sql))[0] == Encipher(sha1($_POST['passConfirm2']))) {

                        $changedPass = Encipher(sha1($_POST['passwordChange']));

                        $sql = "UPDATE user SET pass = '$changedPass' WHERE login='" . $_SESSION['login'] . "';";

                        mysqli_query($conn, $sql) or die(mysqli_error($conn));
                    }

                }

            ?>

        </form>

        <form action="main_page.php" method="POST" id="form">

            <input type="text" name="emailChange" placeholder="Change E-mail"><br>
            <input type="text" name="passConfirm3" placeholder="Confirm with password"><br>
            <input type="submit" name="button3" value="Submit" required>

            <?php

                if(isset($_POST['button3']) && isset($_POST['emailChange']) && isset($_POST['passConfirm3'])) {

                    $sql = "SELECT pass FROM user WHERE login='" . $_SESSION['login'] . "';";

                    if(mysqli_fetch_array(mysqli_query($conn, $sql))[0] == Encipher(sha1($_POST['passConfirm3']))) {

                        $changedEmail = $_POST['emailChange'];

                        $sql = "UPDATE user SET email = '$changedEmail' WHERE login='" . $_SESSION['login'] . "';";

                        mysqli_query($conn, $sql) or die(mysqli_error($conn));
                    }

                }

            ?>

        </form>

</div>

    <div class="PizzaForm">

    <form method="POST" action="main_page.php">

        <br><h1>Choose pizza by number:</h1><br>
        <input type="number" name="selection" min="1" max="4">
        <input type="submit" value="Buy" name="buyButton">

    </form>

        <?php

            $conn = mysqli_connect($servername, $username, $password, $dbname);

            $sql = "SELECT * FROM pizza;";
            $result = $conn->query($sql);
    
            if ($result->num_rows > 0) {
    
                echo "<br><table><tr><th colspan=3><h1 id='honey'>Menu<h1></th></tr>";
                echo "<tr><th>on.</th><th>Name</th><th>Price</th></tr>";
    
            while ($row = $result->fetch_assoc()) {
    
                echo "<tr><td>".$row["id"]."."."</td><td>".$row["description"]."</td><td>".$row["price"]."$"."</td></tr>";
    
            };
    
            echo "</table>";
    
            };

            $sql = "SELECT * FROM order_history WHERE user_id = (SELECT id FROM user WHERE login='$login');";
            $result = $conn->query($sql);

            if(isset($_POST['buyButton']) && $_POST['selection'] != 0){

                $user_id = "(SELECT id FROM user WHERE login='$login')";
                $selection = $_POST['selection'];
                $sql = "INSERT INTO order_history (id, user_id, pizza_id, if_sent) VALUES (NULL, $user_id, $selection, 0);";

                unset($_POST['buyButton']); //???????????????????????
                mysqli_query($conn, $sql) or die(mysqli_error($conn));

            };

            if ($result->num_rows > 0) {

                $total_cost = 0;
                $i = 0;
                $array = array(29, 39, 34, 49);

                echo "<br><table><tr><th colspan=3>Your pizza's:</th></tr>";
                echo "<tr><th>ID</th><th>Name</th></tr>";

            while ($row = $result->fetch_assoc()) {
                
                $pizza_name = $row["pizza_id"];
                $i = $i + 1;
                $name = "(SELECT description FROM pizza WHERE id = $pizza_name)";
                $pizza_name = mysqli_fetch_array(mysqli_query($conn, $name))[0];
                $cost = (int) $pizza_name + 1;
                $total_cost = $total_cost + $array[$cost];

                echo "<tr><td>".$i."</td><td>".$pizza_name."</td></tr>";

            };

            echo "</table>";
            echo "The total price is: ".$total_cost;

        };
        ?>

    </div>

</body>
</html>