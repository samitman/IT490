<?php

if(isset($_POST["sampleDelete"])) {
	require(__DIR__ . "/../delete_sample.php");
}

if(is_logged_in()){
	$db = getDB();
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
	// Get First Name
	$stmt = $db->prepare("SELECT `firstName` FROM `Users` WHERE id=:id;");
	$stmt->execute([":id"=>$userID]);
	$result1 = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $firstName = $stmt->fetch();
	// Get Last Name
	$stmt = $db-> prepare("SELECT `lastName` FROM `Users` WHERE id=:id;");
	$stmt->execute([":id"=>$userID]);
	$result2 = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	$lastName = $stmt->fetch();
	// Get Cohort
	$stmt = $db-> prepare("SELECT `cohort` FROM `Users` WHERE id=:id;");
        $stmt->execute([":id"=>$userID]);
        $result3 = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $cohort = $stmt->fetch();
	// Get Advisor
	$stmt = $db-> prepare("SELECT `advisorName` FROM `Users` WHERE id=:id;");
        $stmt->execute([":id"=>$userID]);
        $result4 = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $advisor = $stmt->fetch();
	// Get photoURL
        $stmt = $db-> prepare("SELECT `photoURL` FROM `Users` WHERE id=:id;");
        $stmt->execute([":id"=>$userID]);
        $result5 = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $photoURL = $stmt->fetch(); 
	// Get Collected Dates
	$stmt = $db-> prepare("SELECT `collected_ts` FROM `Samples` WHERE `allocated_to`=:id and `collected_ts` is NOT null;");
	$stmt->execute([":id"=>$userID]);
	$result6 = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	$collectedDates = $stmt->fetchAll();
}
?>

<?php
        $stmt = $db->prepare("SELECT `allocated_to`, `barcodeID`, `allocated_ts`, `collected_ts` FROM `Samples` as e JOIN `Users` as i ON e.allocated_to=i.id WHERE i.id=:id;");
        $stmt->execute([":id"=>$userID]);
        $r = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $samples = $stmt->fetchAll();

?>

<?php
	$currentDate = strtotime(date("Y-m-d"));
	$collectedToday = 0; 

	foreach($collectedDates as $row): 
    		foreach($row as $col=>$val):
    			$date = strtotime($val);
			$datediff = $date - $currentDate;
			$difference = floor($datediff/(60*60*24));
			if ($difference == 0) {
				$collectedToday = 1;
			} else {
				$collectedToday = 0;
			}
    		endforeach;
	endforeach; 
?>

<div class="row">
	<div class="column1" style="height:350px; overflow:auto;">
		<table>
			<thead>
				<th><b><p style="text-align: left">User Information</p></b></th>
				<th><b><p style="text-align: right">Collected Today: </b>
				<?php
					if ($collectedToday == 1) {
						echo "Yes"; 
					} else { 
						echo "No"; 
					} 
				?>
				</th>
			</thead>
			<tr>
				<td style="width: 20%; height:115px; text-align:left; vertical-align:center;" colspan="1">
					<img src=<?php foreach ($photoURL as $value) { echo $value, "\n"; } ?> height="200%"><br></td>
				<td style="text-align: left;">
					<b><?php foreach ($firstName as $value) { echo $value, "\n"; } ?><?php foreach ($lastName as $value) { echo $value, "\n"; } ?></b><br>
					<b>Group: </b><?php foreach ($cohort as $value) { echo $value, "\n"; } ?><br>
					<?php $hasAdvisor = reset($advisor); ?>		
					<?php if (!$hasAdvisor == "") { ?><b>Advisor: </b><?php foreach ($advisor as $value) { echo $value, "\n"; } ?><br><?php } ?>
					<br><b>Username: </b><br><?php echo $username; ?><br>
					<b>Email address: </b><br><?php echo $email; ?><br>
				</td>
			</tr>
		</table>
	</div>
	<div class="column2" style="height:350px; overflow:auto;">
		<table>
                	<thead>
                        	<thead><th colspan="5"><b><p style="text-align: left">Assigned Samples</p></b></th></thead>
                        	<tr>
                                	<td><b>User ID</b></td>
                                	<td><b>Sample ID</b></td>
                                	<td><b>Allocated</b></td>
                                	<td><b>Collected</b></td>
                        	</tr>
                	</thead>
                	<?php foreach($samples as $row):?>
                        	<tr>
                                	<?php foreach($row as $col=>$val):?>
                                	<td><?php echo $val;?></td>
                                	<?php endforeach;?>
					<?php if(has_role("admin")):?>
						<td style="vertical-align: center; margin: auto;">
						<form method="POST">
							<input type="hidden" name="sampleDelete" value="<?php echo $row['barcodeID'];?>"/>
							<button style="border-radius: 10px; background-image: linear-gradient(#fdc956, #f9982b); type="submit"><img style="height: 20px;" src="/images/trash.png" alt="delete"/></button>
						</form>
						</td>
					<?php endif; ?>
                        	</tr>
                	<?php endforeach;?>
		</table>
	</div>
</div>
