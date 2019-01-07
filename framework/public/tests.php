<html>
    <form action="tests.php" method="get">
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
    
    // $email = $_GET['email'];
    // $user = $_GET['user'];
    // $password = $_GET['pass'];
    $id = 23;

    $admin = new User();
    $db = $admin->newDbCon();
    //$result = $admin ->get($id);
    $result = $admin ->delete($id);
    print_r($result);
    die();


    if ($email != '' and $user != '' and $password != ''){
        $admin = new User();
        $db = $admin->newDbCon();
        $result = $admin->find(array($email, $user, $password));
        
        //$sth = $db->prepare('SELECT EMail, Username, Password FROM registeredusers WHERE EMail = :email AND Username = :username AND Password = :password');
        // $sth->execute(array(':email' => $email, ':username' => $user, ':password' => $password));
        // $result = $sth->fetch();
        if ($result !== false)
        {
            echo 'User already exists!';
        }
        else
        {
            $result = $admin->new(array($email, $user, $password));
            if (result !== false){
                echo 'User created.';
            }
            else{
                echo 'Error.';
            }
            //$result = $admin->new(array($email, $user, $password));
            // $sth = $db->prepare('INSERT INTO registeredusers (EMail, Username, Password)
            //                     VALUES (:email, :username, :password)');
            // $sth->execute(array(':email' => $email, ':username' => $user, ':password' => $password));
            // echo 'Signup complete!';
            // //header('Location: http://localhost/framework/public/index.php/signupcomplete');
        }
    }
    else{
        echo 'No info inserted, or forgot to complete a field.';
    }
?>