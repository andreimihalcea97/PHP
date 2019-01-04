<html>
    <style type="text/css">
        .circle_green {
        
        padding: 1px 2px;
        background: green;
        height: 2px;
        border-radius: 60%;
        margin-left: auto;
        margin-right: auto;
        width: 2px;
        display: inline-block;
        }

        .circle_red {
        
        padding: 1px 2px;
        background: red;
        height: 2px;
        border-radius: 50%;
        margin-left: auto;
        margin-right: auto;
        width: 2px;
        display: inline-block;
        }

        .button_holder{ text-align: center; }
    </style>

    <form action="login.php" method="get">
        <input type="email" name = "email" placeholder="name@email.com"><br/>
        <input type="password" name = "pass"><br/>
        <button type="submit">login</button>
        <button type="submit">sign in</button>
    </form>
    
    <form action="/framework/public/index.php/" method="post">
        <input type="submit" value="Home">
    </form>

</html>

<?php
require_once "../app/config.php";
require_once "../app/models/User.php";

try{
    $admin = new User();
    $db = $admin->newDbCon();
    try{
        $email = $_GET['email'];
        $password = $_GET['pass'];
        $sth = $db->prepare('SELECT EMail, Password FROM registeredusers WHERE EMail = :email AND Password = :password');
        $sth->execute(array(':email' => $email, ':password' => $password));
        $result = $sth->fetch();
        if ($result !== false)
        {
            echo 'Assigned';
        }
        else
        {
            echo 'Unassigned';
        }
    } catch(Throwable $e){}
    // check in db to see if user is registered
    // if registered -> set link to index where the router goes to users page
    // if not try and login
    echo('<div id="header" style="width:500px;text-align: left;">');
    echo('Connected to database ');
    echo('<div class="circle_green" id="centered" style="text-align: center; width: 10; height: 10"></div>');
    echo('</div>');
} catch (Throwable $e) {
    echo('<div id="header" style="width:500px;text-align: left;">');
    echo('Connected to database ');
    echo('<div class="circle_red" id="centered" style="text-align: center; width: 10; height: 10"></div>');
    echo('</div>');
}
?>