<?php require_once(__DIR__ . "/partials/nav.php"); ?>


<br>
<div style="text-align: center;">
	<b>Walnuts Investment Platform</b><br>
        New User Registration<br>
</div>
<br>
<div style="width: 60%; margin: auto;">
    <form method="POST">
	<!--<input style="width: 100%" type="text" id="firstName" name="firstName" placeholder="First Name" /><br><br><br>
        <input style="width: 100%" type="text" id="lastName" name="lastName" placeholder="Last Name" /><br><br><br>
        <input style="width: 100%" type="email" id="email" name="email" placeholder="Email address" /><br><br><br>-->
        <input style="width: 100%" type="text" id="user" name="username"  maxlength="60" placeholder="Desired username"/><br><br><br>
        <input style="width: 100%" type="password" id="p1" name="password" placeholder="Password" /><br><br><br>
       <!-- <input style="width: 100%" type="password" id="p2" name="confirm" placeholder="Confirm password" /><br><br><br>-->
	<div style="display: flex;">
        	<input style="float: right;" class="submitButton" type="submit" id="register" name="register" value="Register"/><br><br>
	</div>
    </form>
	<div style="display: flex;"> 
		<a style="text-decoration: none;" href="login.php" class="submitButton" type="button">Go Back</a> 
	</div>
    <?php
	if(array_key_exists('register', $_POST))
	{
		 if (isset($_POST["username"])) 
		 {
        		$username = $_POST["username"];}
        	 if (isset($_POST["password"])) 
        	 {
       		 $password = $_POST["password"];}
		//$result1 = exec("/opt/lampp/htdocs/html/sendc.py 2>&1");
		//$result2 = exec("/opt/lampp/htdocs/html/rpc_client.py 2>&1");
		$result3 = exec("python3 rpc_client2.py $username $password");
		//echo $result1;
		//echo $result2;
		echo $result3;
		//echo $username;
		//echo "hello";
	} ?>
</div>


<?php require(__DIR__ . "/partials/flash.php");
