<?php
ob_start();
session_start();
if($_SESSION['name']!='admin')
{
	header('location: login.php');
}
include("../config.php");
?>

<?php
if(isset($_POST['form1'])) {
	
	try {
	
		if(empty($_POST['old'])) {
			throw new Exception("Old Password field can not be empty");
		}
		
		if(empty($_POST['new1'])) {
			throw new Exception("New Password field can not be empty");
		}
		
		if(empty($_POST['new2'])) {
			throw new Exception("Confirm Password field can not be empty");
		}
		
		$statement = $db->prepare("SELECT * FROM tbl_login WHERE id=1");
		$statement->execute();
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);
		foreach($result as $row)
		{
			
			$old_password = md5($_POST['old']);
			if($old_password != $row['password'])
			{
				throw new Exception("Old Password is wrong.");
			}
					
		}
		
		if($_POST['new1'] != $_POST['new2'])
		{
			throw new Exception("New Password and Confirm Password does not match.");
		}
		
		
		$new_final_password = md5($_POST['new1']);
		
		$statement = $db->prepare("UPDATE tbl_login SET password=? WHERE id=1");
		$statement->execute(array($new_final_password));
		
		$success_message = "Password is changed successfully.";
		
	
	}
	
	catch(Exception $e) {
		$error_message = $e->getMessage();
	}
	
	
}
?>

<?php include("header.php"); ?>
<h2>Change Password</h2>
<?php
if(isset($error_message)) {echo "<div class='error'>".$error_message."</div>";}
if(isset($success_message)) {echo "<div class='success'>".$success_message."</div>";}
?>
<form action="" method="post">
<table class="tbl1">
<tr>
	<td>Old Password</td>
</tr>
<tr>
	<td><input class="short" type="password" name="old"></td>
</tr>
<tr>
	<td>New Password</td>
</tr>
<tr>
	<td><input class="short" type="password" name="new1"></td>
</tr>
<tr>
	<td>Confirm Password</td>
</tr>
<tr>
	<td><input class="short" type="password" name="new2"></td>
</tr>
<tr>
	<td><input type="submit" value="SAVE" name="form1"></td>
</tr>
</table>	
</form>

<?php include("footer.php"); ?>