<?php
$email = "samads@me.com";
$password = "test";
$command = escapeshellcmd('../database/rpc_fe_logintest.py' .' '. $email .' ' .$password);
echo $command;
$output = shell_exec($command);
echo $output;
?>
