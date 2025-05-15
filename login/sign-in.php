<?php
    $con = new mysqli("localhost","root","ZAQ!2wsx","retorishub");
    if($con->connect_error){
        die("Failed to connect to database ".$con->connect_error);
    }
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Sign-in</title>
</head>
<body>
    <section>
        <div id="maincontent" role="main">
            <form action="" method="post">
                <div class="form-header"></div>
                <div class="main-items">
                    <input type="text" name="username" id="username" placeholder="Username" required autocomplete="none" 
                    value="<?php echo isset($_COOKIE['username']) ? htmlspecialchars($_COOKIE['username']) : ''; ?>">
                </div>
                <div class="main-items">
                    <input type="password" name="password" id="password" placeholder="Password" required autocomplete="none">
                </div>
                <div class="main-items">
                    <input type="email" name="email" id="email" placeholder="Email" required autocomplete="none" 
                    value="<?php echo isset($_COOKIE['email']) ? htmlspecialchars($_COOKIE['email']) : ''; ?>">
                </div>
                <div class=" jc-space-bwn customer-check">
                    <label for="customer"><span>I want to use website market</span><span class="space-3px"></span></label>
                    <input type="checkbox" value="Y" name="customer" id="customer">
                </div>
                <div class="diviter"></div>
                <div class="main-items sign-in-btn">
                    <input type="submit" value="Sign-in"> <!--Pop-up signing completed, go to login page - link-->
                </div>
            </form>
        </div>    
    </section>
    <?php
        if(isset($_POST['customer'])){
            $customer = $_POST['customer'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $email = $_POST['email'];
            $id = 1;
            
            //sprawdzenie czy login sie powtarza
            $stmt_check = $con->prepare("SELECT login FROM accounts WHERE login = ?");
            $stmt_check->bind_param("s", $username);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            if ($result_check->num_rows > 0) {
                // Login już istnieje, ustawiamy pliki cookie
                setcookie("username", $username, time() + 10, "/");
                setcookie("email", $email, time() + 10, "/");
                echo "<script>alert('The provided username already exists!'); window.location.href = 'sign-in.php';</script>";
                exit();
            }
            
            $stmt = $con->prepare("INSERT INTO accounts (login, password, email, day_of_creation,is_customer,customer_id) VALUES (?, ?, ?, NOW(),?,?)"); //preparted stentment
            
            if ($stmt === false) {
                die('Error preparing the query: ' . $con->error);
            }

            $stmt->bind_param("ssssi", $username, $password, $email, $customer, $id); //s - string, i - integer, d - double

            if ($stmt->execute()) {
                echo "Registration was successful. <a href='http://localhost/7R/login/index.html'>Sign-in</a>";
            } else {
                  die("Error during registration: " . $stmt->error);
                }
        }
        else if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email'])){
            $username = $_POST['username'];
            $password = $_POST['password'];
            $email = $_POST['email'];

            //sprawdzenie czy login sie powtarza
            $stmt_check = $con->prepare("SELECT login FROM accounts WHERE login = ?");
            $stmt_check->bind_param("s", $username);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            if ($result_check->num_rows > 0) {
                // Login już istnieje, ustawiamy pliki cookie
                setcookie("username", $username, time() + 10, "/");
                setcookie("email", $email, time() + 10, "/");
                echo "<script>alert('The provided username already exists!'); window.location.href = 'sign-in.php';</script>";
                exit();
            }
            
            $stmt = $con->prepare("INSERT INTO accounts (login, password, email, day_of_creation) VALUES (?, ?, ?, NOW())"); //preparted stentment
            
            if ($stmt === false) {
                die('Error preparing the query: ' . $con->error);
            }

            $stmt->bind_param("sss", $username, $password, $email);

            if ($stmt->execute()) {
                echo "Rejestracja powiodła się. <a href='http://localhost/7R/login/index.html'>Sign-in</a>";
            } else {
                    die("Error during registration: " . $stmt->error);
                }
        }
        
    ?>
</body>
</html>
<?php
    $con->close();        
?>