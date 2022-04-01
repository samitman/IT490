<link rel="stylesheet" href="static/css/styles.css">

<?php require_once(__DIR__ . "/lib/helpers.php");?>

<p style="text-align:center;"><b>Create New User</b></p>

<?php
if (!has_role("admin")) {
	flash("You must be logged in at administrator level to access these functions.");
	die(header("Location: index.php"));
}
?>

<?php
if (isset($_POST["createNew"])) {
    $email = null;
    $password = null;
    $confirm = null;
    $username = null;
    $firstName = null;
    $lastName = null;
    $role = null;

    if (isset($_POST["email"])) {
        $email = $_POST["email"];
    }
    if (isset($_POST["password"])) {
        $password = $_POST["password"];
    }
    if (isset($_POST["confirm"])) {
        $confirm = $_POST["confirm"];
    }
    if (isset($_POST["username"])) {
        $username = $_POST["username"];
    }
    if (isset($_POST["firstName"])) {
	$firstName = $_POST["firstName"];
    }
    if (isset($_POST["lastName"])) {
	$lastName = $_POST["lastName"];
    }
    if (isset($_POST["role"])) {
	$role = $_POST["role"];
    }

    switch ( $role )
    {
	case "operator" :
		$role = 2;
		break;
	case "admin" :
		$role = 1;
		break;
	default :
		$role = 3;
		break;
    }

    $isValid = true;
    //check if passwords match on the server side
    if ($password == $confirm) {
        //not necessary to show
        //echo "Passwords match <br>";
    }
    else {
        flash("I'm sorry, but your passwords don't match. Try again.");
        $isValid = false;
    }
    if (!isset($email) || !isset($password) || !isset($confirm)) {
        $isValid = false;
    }
    //TODO other validation as desired, remember this is the last line of defense
    if ($isValid) {
        $hash = password_hash($password, PASSWORD_BCRYPT);

        $db = getDB();
	$is_active = 1;
        if (isset($db)) {
            //here we'll use placeholders to let PDO map and sanitize our data
            $stmt = $db->prepare("INSERT INTO Users(email, username, password, firstName, lastName) VALUES(:email,:username, :password, :firstName, :lastName)");
            //here's the data map for the parameter to data
            $params = array(":email" => $email, ":username" => $username, ":password" => $hash, ":firstName" => $firstName, ":lastName" => $lastName);
            $r = $stmt->execute($params);
            $e = $stmt->errorInfo();

            $id = $db->lastInsertId();

	    $stmt2 = $db->prepare("INSERT INTO UserRoles(user_id, role_id, is_active) VALUES(:id, :role, :is_active)");
	    $params2 = array(":id" => $id, ":role" => $role, ":is_active" => $is_active );
            $r2 = $stmt2->execute($params2);
	    $e2 = $stmt2->errorInfo();

            if ($e[0] && $e2[0] == "00000") {
                flash("New user ".$firstName." ".$lastName." has been successfully created.");
                die(header("Location: new_user.php"));
            }
            else {
                if ($e[0] == "23000") {//code for duplicate entry
                    flash("Sorry, but this username or email already exists.");
                }
                else {
                    flash("An error occurred, please try again.");
                }
            }
        }
    }
    else {
        flash( "Please enter your details to register for this system.");
    }
}
//safety measure to prevent php warnings
if (!isset($email)) {
    $email = "";
}
if (!isset($username)) {
    $username = "";
}
?>

<div style="width: 60%; margin: auto;">
    <form method="POST">
	<input style="width: 100%" type="text" id="firstName" name="firstName" placeholder="First Name" required/><br><br><br>
        <input style="width: 100%" type="text" id="lastName" name="lastName" placeholder="Last Name" required/><br><br><br>
        <input style="width: 100%" type="email" id="email" name="email" placeholder="Email address" required/><br><br><br>
        <input style="width: 100%" type="text" id="user" name="username" required maxlength="60" placeholder="Desired username"/><br><br><br>
	<div style="display: inline;">
		<b>Choose Additional Role</b><br>
		<span style="font-size: 16px; font-style: italic;">If no role is selected, this user will be a student.</span><br><br>
		<span style="font-size: 16px;"><b>Operators</b> can search for users and can assign and collect samples.<b> Administrators</b> may perform all operator tasks, and can also create, edit, and delete users and samples. Additionally, only administrators may generate reports from this system.<b> Choose wisely!</b></span><br><br> 
		<label for="operator">Operator </label>
		<input type="radio" id="operator" name="role" value="operator"><br>
		<label for="admin">Administrator </label>
		<input type="radio" id="admin" name="role" value="admin"><br>
	</div><br>
        <input style="width: 100%" type="password" id="p1" name="password" placeholder="Password" required/><br><br><br>
        <input style="width: 100%" type="password" id="p2" name="confirm" placeholder="Confirm password" required/><br><br><br>
	<div style="display: flex;">
        	<input class="submitButton" style="float: right;" type="submit" name="createNew" value="Create User"/><br><br>
	</div>
    </form>
</div>

<?php require(__DIR__ . "/partials/flash.php");
