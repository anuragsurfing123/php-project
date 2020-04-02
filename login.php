<?php include('functions.php');

define('MyConst1', TRUE);
define('MyConst2', TRUE);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Registration system PHP and MySQL</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php
include 'index3.php';
?>

	<div class="header">
		<h2>Login</h2>
	</div>
	<form method="post" action="login.php">
		

		<?php echo display_error(); ?>

		<div class="input-group">
			<label>Username</label>
			<input type="text" name="username" >
		</div>
		<div class="input-group">
			<label>Password</label>
			<input type="password" name="password">
		</div>
		<div class="input-group">
			<button type="submit" class="btn1" name="login_btn">Login</button>
		</div>
		<p>
			Not yet a member? <a href="register.php">Sign up</a>
		</p>
		
	</form>
	<br><br><br><br><br>
	<?php
include 'index5.php';
?>
</body>
</html