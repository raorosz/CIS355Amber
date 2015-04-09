<?php
/**
 * Created by IntelliJ IDEA.
 * User: Brad Whitfield
 * Date: 2/17/15
 * Time: 3:25 PM
 */
require_once('../mysqlconnector.php');
session_start();
$email = $password = $loginError = '';
if(isset($_POST['sub'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    //Check connection to database
    $db = DBConnector::get_db_connection();

    //Prepare the statement so we can bind data to it
    $sql = "SELECT id FROM persons WHERE email=? AND password_hash=?";
    if (!$statement = $db->prepare($sql)) {
        $loginError = "Prepare failed: ($db->errno) $db->error";
    }

    $hashedPassword = hash("sha512", $password);

    //Bind the parameters to the statement
    $statement->bind_param("ss", $email, $hashedPassword);

    //Run query
    if (!$statement->execute()) {
        $loginError = "Execution of prepared statement failed: ($statement->errno) $statement->error";
    }

    $statement->bind_result($id);
    $statement->fetch();

    $statement->close();
    $db->close();

    if ($id != 0) {
        $_SESSION['persons_id'] = $id;
        if (isset($_SESSION['CALLING_PAGE'])) {
            $redirect = $_SESSION['CALLING_PAGE'];
            unset($_SESSION['CALLING_PAGE']);
            header("LOCATION:$redirect");
        }
        else {
            header('LOCATION:/~bswhitf1/');
        }
        die();
    }
    else {
        $loginError = "Incorrect login!";
    }
}

//If they deliberately went to the login page, allow them to log in as someone else.
if (isset($_SESSION['persons_id'])) {
    unset($_SESSION['persons_id']);
}
echo "
<!DOCTYPE html>
<html xmlns=\"http://www.w3.org/1999/html\">
   <head>
     <title>Login to Teacherati</title>
     <meta http-equiv='content-type' content='text/html;charset=utf-8' />
     <link rel='stylesheet' type='text/css' href='https://bootswatch.com/cosmo/bootstrap.min.css'>
   </head>
<body>
    <div class='container'>
    </br>
    <div class='row'>
    <div class='col-md-3'>
    </div>
    <div class='col-md-6'>
        <form name='input' action='{$_SERVER['PHP_SELF']}' method='post'>
            <div class='form-group'>
                <label class='control-label' for='username'>User Name</label>
                <input class='form-control' type='text' value='$email' id='email' name='email' />
            </div>
            <div class='form-group'>
                <label class='control-label' for='password'>Password</label>
                <input class='form-control' type='password' value='$password' id='password' name='password' />
            </div>
            <div class='error'>$loginError</div>
            <input class='btn btn-default' type='submit' value='Login' name='sub' />
        </form>
    </div>
    <div class='col-md-3'>
    </div>
    </div>
    </div>
</body>
</html>";
?>