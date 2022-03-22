<?php
$email = "samads@me.com";
$password = "test";
$command = "../database/rpc_fe_logintest.py $email, $password";
shell_exec($command);
?>
