<html>
    <form action="signup.php" method="get">
        <input type="email" name = "email" placeholder="name@email.com"><br/>
        <input type="username" name = "user" placeholder="username"><br/>
        <input type="password" name = "pass" placeholder="password"><br/>
        <button type="submit">sign up</button>
    </form>
    
    <form action="/framework/public/index.php/" method="post">
        <input type="submit" value="Home">
    </form>

</html>

<?php

    require_once "../app/config.php";
    require_once "../app/models/User.php";
    
    $email = $_GET['email'];
    $user = $_GET['user'];
    $password = $_GET['pass'];

    try{
        if ($email != '' and $user != '' and $password != ''){
            $admin = new User();
            $db = $admin->newDbCon();
            $result = $admin->find(array($email, $user, $password));
            
            if ($result !== false)
            {
                echo 'User already exists';
            }
            else
            {
                $result = false;
                $result = $admin->new(array($email, $user, $password));
                if (result !== false){
                    echo 'User created.';
                }
                else{
                    echo 'Error.';
                }
                echo 'Signup complete!';
                header('Location: http://localhost/framework/public/index.php/signupcomplete');
            }
        }
        else{
            echo 'No info inserted, or forgot to complete a field.';
        }
    } catch(Throwable $e){};
?>