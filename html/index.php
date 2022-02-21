<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<link rel="stylesheet" href="static/css/styles.css">
<br>
<div>
<div style="text-align: center;">
	<b>Newark Academy Sample Collection and Tracking System</b><br>
	<p><img src="images/logo.png" alt="Newark Academy logo" width="419", height="402"></p>
	Please log in or register to continue.
</div>
<br>

<div style="width: 60%; margin:auto;">
	<form name="login" method="POST">
       	 	<input style="width: 100%" type="text" id="email" name="email" placeholder="Email address or username" required/><br><br><br>
        	<input style="width: 100%" type="password" id="p1" name="password" placeholder="Password" required/><br><br><br>
		<div style="display:flex;">
			<a style="text-decoration: none;" href="/register.php" class="submitButton" type="button">Register</a>
			<input class="submitButton" style="float: right;" type="submit" name="login" value="Log In"/>
		</div>
	</form>
</div>

<?php
if (isset($_POST["login"])) {
    $email = null;
    $password = null;
    if (isset($_POST["email"])) {
        $email = $_POST["email"];
    }
    if (isset($_POST["password"])) {
        $password = $_POST["password"];
    }
    $isValid = true;
    if (!isset($email) || !isset($password)) {
        $isValid = false;
        flash("Email/username or password is missing.");
    }
    if ($isValid) {
        $db = getDB();
        if (isset($db)) {
            $stmt = $db->prepare("SELECT id, email, username, password, firstName, lastName from Users WHERE username = :email OR email = :email LIMIT 1");

            $params = array(":email" => $email);
            $r = $stmt->execute($params);
            $e = $stmt->errorInfo();
            if ($e[0] != "00000") {
                flash("Something went wrong, please try logging in again.");
            }
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result && isset($result["password"])) {
                $password_hash_from_db = $result["password"];
                if (password_verify($password, $password_hash_from_db)) {
                    $stmt = $db->prepare("SELECT Roles.name FROM Roles JOIN UserRoles on Roles.id = UserRoles.role_id where UserRoles.user_id = :user_id and Roles.is_active = 1 and UserRoles.is_active = 1");
                    $stmt->execute([":user_id" => $result["id"]]);
                    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    unset($result["password"]);//remove password so we don't leak it beyond this page
                    //let's create a session for our user based on the other data we pulled from the table
                    $_SESSION["user"] = $result;//we can save the entire result array since we removed password
                    if ($roles) {
                        $_SESSION["user"]["roles"] = $roles;
                    }
                    else {
                        $_SESSION["user"]["roles"] = [];
                    }

	            $_SESSION["user"]["firstName"] = $result["firstName"];
		    $_SESSION["user"]["lastName"] = $result["lastName"];

                    //on successful login let's serve-side redirect the user to the home page
                    die(header("Location: home.php"));
                }
                else {
                    flash("You've enetered an invalid password.");
                }
            }
            else {
                flash("That user does not exist.");
            }
        }
    }
    else {
        flash("I'm sorry, something is wrong. Please try again.");
    }
}
?>
<?php require(__DIR__ . "/partials/flash.php");
