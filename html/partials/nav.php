<link rel="stylesheet" href="static/css/styles.css">
<?php
require_once(__DIR__ . "/../lib/helpers.php");
?>
<?php if (is_logged_in()): ?>
	<style="text-align: left;"><b>Sample Collection and Tracking System</b>
	<span style="float: right;"><b>
	<?php 
		$dt = new DateTime();
		$dt->setTimezone(new DateTimeZone('America/New_York'));
		echo $dt->format("l M d, Y  G:i");
	?>
	</b></span><br>
	<style="text-align: left;">Newark Academy COVID-19 Testing Program
	<span style="float: right;"><b>Collected today: </b> 
	
	<?php
		$db = getDB();
		$stmt = $db->prepare("SELECT `collected_ts` FROM `Samples` WHERE `collected_ts` is NOT null;");
		$stmt->execute();
		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		$collectedSamples = $stmt->fetchAll();
		
		$currentDate = strtotime(date("Y-m-d"));
		$counter = 0;
		foreach($collectedSamples as $row):
			foreach($row as $col=>$val):
				$date = strtotime($val);
				$datediff = $date - $currentDate;
				$difference = floor($datediff/(60*60*24));
				if ($difference == 0) {
					$counter++;
				}
			endforeach;
		endforeach;
		
		echo $counter;
	?>
	</span><br>
	<span style="font-size: 13px;"><p>Version 2.0-5-14-2021</p></span>
<?php endif; ?>
<nav>
<ul class="nav">
    <?php if (!is_logged_in()): ?>
    <?php endif; ?>
    <?php if (is_logged_in()): ?>
	<li><a style="text-decoration: none;" href="home.php"><img src="images/dashboard.png" alt="Dashboard" style="width:23px;height:23px;margin:auto 5px;"><br>Dashboard</a></li>
	<li><a style="text-decoration: none;" href="profile.php"><img src="images/profile.png" alt="Profile" style="width:23px;height:23px;margin:auto 5px;"><br>Profile</a></li>
    <?php if (has_role("operator") || has_role("admin")): ?>
	<li><a style="text-decoration: none;" href="search.php"><img src="images/search.png" alt="Search" style="width:23px;height:23px;margin:auto 5px;"><br>Search</a></li>
        <li><a style="text-decoration: none;" href="assign.php"><img src="images/assign.png" alt="Assign" style="width:23px;height:23px;margin:auto 5px;"><br>Assign</a></li>
	<li><a style="text-decoration: none;" href="collect.php"><img src="images/collect.png" alt="Collect" style="width:23px;height:23px;margin:auto 5px;"><br>Collect</a></li>
    <?php endif; ?>
    <?php if (has_role("admin")): ?>
	<li><a style="text-decoration: none;" href="admin.php"><img src="images/admin.png" alt="Admin" style="width:23px;height:23px;margin:auto 5px;"><br>Admin</a></li>
    <?php endif; ?>
        <li><a style="text-decoration: none;" href="logout.php"><img src="images/logout.png" alt="Log Out" style="width:23px;height:23px;margin:auto 5px;"><br>Log Out</a></li>
    <?php endif; ?>
</ul>
</nav>
