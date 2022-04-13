<?php require_once(__DIR__ . "/partials/nav.php"); ?>


<br>
<div style="text-align: center;">
	<b>Walnuts Investment Platform</b><br>
        Login<br>
</div>
<br>
<div style="width: 60%; margin: auto;">
    <form method="POST">
	<!--<input style="width: 100%" type="text" id="firstName" name="firstName" placeholder="First Name" /><br><br><br>
        <input style="width: 100%" type="text" id="lastName" name="lastName" placeholder="Last Name" /><br><br><br>
        <input style="width: 100%" type="email" id="email" name="email" placeholder="Email address" /><br><br><br>-->
        <input style="width: 100%" type="text" id="user" name="username"  maxlength="60" placeholder="Username"/><br><br><br>
        <input style="width: 100%" type="password" id="p1" name="password" placeholder="Password" /><br><br><br>
       <!-- <input style="width: 100%" type="password" id="p2" name="confirm" placeholder="Confirm password" /><br><br><br>-->
	<div style="display: flex;">
        	<input style="float: right;" class="submitButton" type="submit" id="login" name="login" value="Login"/><br><br>
	</div>
    </form>
	<div style="display: flex;"> 
		<!--<a style="text-decoration: none;" href="register.php" class="submitButton" type="button">Go Back</a>--> 
	</div>
    <?php
	$username = "";
	$password = "";
	$hash = "";
	if(array_key_exists('login', $_POST))
	{
		 if (isset($_POST["username"])) 
		 {
        		$username = $_POST["username"];}
        	 if (isset($_POST["password"])) 
        	 {
       		 	$password = $_POST["password"];}
			$hash = password_hash($password, PASSWORD_BCRYPT);
			$result3 = exec("python3 login.py $username $hash");
			echo $result3;

		//REDIRECT TO HOME PAGE UPON SUCCESSFUL LOGIN
		if($result3 == "1") {
			print("Log in successful");
			$_SESSION["user"] = $username;
           		die(header("Location: home.php"));
		}
		else
		{
			print("Invalid username or password");
		}
	} 
    ?>
</div>


<?php require(__DIR__ . "/partials/flash.php");
