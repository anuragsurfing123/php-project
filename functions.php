


<?php 

function isAdmin()
{
	if (isset($_SESSION['user']) && $_SESSION['user']['user_type'] == 'admin' ) {
		return true;
	}else{
		return false;
	}
}


session_start();

// connect to database
$db = mysqli_connect('localhost', 'root', '', 'multi_login');

// variable declaration
$username = "";
$email    = "";
$errors   = array(); 

// call the register() function if register_btn is clicked
if (isset($_POST['register_btn'])) {
	register();
}

// REGISTER USER
function register(){
	// call these variables with the global keyword to make them available in function
	global $db, $errors, $username, $email;

	// receive all input values from the form. Call the e() function
    // defined below to escape form values
	$username    =  e($_POST['username']);
	$email       =  e($_POST['email']);
	$password_1  =  e($_POST['password_1']);
	$password_2  =  e($_POST['password_2']);

	// form validation: ensure that the form is correctly filled
	if (empty($username)) { 
		array_push($errors, "Username is required"); 
	}
	if (empty($email)) { 
		array_push($errors, "Email is required"); 
	}
	if (empty($password_1)) { 
		array_push($errors, "Password is required"); 
	}
	if ($password_1 != $password_2) {
		array_push($errors, "The two passwords do not match");
	}

	// register user if there are no errors in the form
	if (count($errors) == 0) {
		$password = md5($password_1);//encrypt the password before saving in the database

		if (isset($_POST['user_type'])) {
			$user_type = e($_POST['user_type']);
			$query = "INSERT INTO users (username, email, user_type, password) 
					  VALUES('$username', '$email', '$user_type', '$password')";
			mysqli_query($db, $query);
			$_SESSION['success']  = "New user successfully created!!";
			header('location: home1.php');
		}else{
			$query = "INSERT INTO users (username, email, user_type, password) 
					  VALUES('$username', '$email', 'user', '$password')";
			mysqli_query($db, $query);

			// get id of the created user
			$logged_in_user_id = mysqli_insert_id($db);

			$_SESSION['user'] = getUserById($logged_in_user_id); // put logged in user in session
			$_SESSION['success']  = "You are now logged in";
			header('location: index.php');				
		}
	}
}

// return user array from their id
function getUserById($id){
	global $db;
	$query = "SELECT * FROM users WHERE id=" . $id;
	$result = mysqli_query($db, $query);

	$user = mysqli_fetch_assoc($result);
	return $user;
}

// escape string
function e($val){
	global $db;
	return mysqli_real_escape_string($db, trim($val));
}

function display_error() {
	global $errors;

	if (count($errors) > 0){
		echo '<div class="error">';
			foreach ($errors as $error){
				echo $error .'<br>';
			}
		echo '</div>';
	}
}	


function isLoggedIn()
{
	if (isset($_SESSION['user'])) {
		return true;
	}else{
		return false;
	}
}

// log user out if logout button clicked
if (isset($_GET['logout'])) {
	session_destroy();
	unset($_SESSION['user']);
	header("location: login.php");
}

// call the login() function if register_btn is clicked
if (isset($_POST['login_btn'])) {
	login();
}

// LOGIN USER
function login(){
	global $db, $username, $errors;

	// grap form values
	$username = e($_POST['username']);
	$password = e($_POST['password']);

	// make sure form is filled properly
	if (empty($username)) {
		array_push($errors, "Username is required");
	}
	if (empty($password)) {
		array_push($errors, "Password is required");
	}

	// attempt login if no errors on form
	if (count($errors) == 0) {
		$password = md5($password);

		$query = "SELECT * FROM users WHERE username='$username' AND password='$password' LIMIT 1";
		$results = mysqli_query($db, $query);

		if (mysqli_num_rows($results) == 1) { // user found
			// check if user is admin or user
			$logged_in_user = mysqli_fetch_assoc($results);
			if ($logged_in_user['user_type'] == 'admin') {

				$_SESSION['user'] = $logged_in_user;
				$_SESSION['success']  = "You are now logged in";
				header('location: admin/home1.php');		  
			}else{
				$_SESSION['user'] = $logged_in_user;
				$_SESSION['success']  = "You are now logged in";

				header('location: index.php');
			}
		}else {
			array_push($errors, "Wrong username/password combination");
		}
	}
}





// call the changepass() function if change_pass is clicked
if (isset($_POST['change_pass'])) {
	changepass();
}

// Update password
function changepass(){
	global $db, $username, $errors;

	// grap form values
	
	$newpass = e($_POST['npwd']);
	$cnfpass = e($_POST['cpwd']);
	$user=e($_SESSION['user']['username']);
	
	
	

	// make sure form is filled properly
	
	if (empty($newpass)) {
		array_push($errors, "new Password is required");
	}
	if (empty($cnfpass)) {
		array_push($errors, "new Password is required");
	}
	if ($cnfpass != $newpass) {
		array_push($errors, "The two passwords do not match");
	}

	// attempt login if no errors on form
	
	$password = trim($newpass);
	$password1=md5($password);

	$query = "UPDATE users SET password='$password1' WHERE username='$user'";
	
	
	if (mysqli_query($db, $query) === TRUE) {
		echo '<script>alert("Password Updated successfully")</script>';
	} else {
		echo "Error: " . $sql . "<br>" . mysqli_error($db);
	}
}




// call the new_laundry() function if change_pass is clicked
if (isset($_POST['new_laundry'])) {
	newlaundry();
}

// Update new Laundry
function newlaundry(){
	global $db, $username, $errors;

	// grap form values
	
	$laundrydesc = e($_POST['laundry_desc']);
	$laundryprice = e($_POST['laundry_price']);
	
	
	
	

	// make sure form is filled properly
	
	if (empty($laundrydesc)) {
		array_push($errors, "new Password is required");
	}
	if (empty($laundryprice)) {
		array_push($errors, "new Password is required");
	}
	

	// attempt login if no errors on form
	

	$query = "INSERT INTO laundry_type (laun_type_desc, laun_type_price) 
	VALUES('$laundrydesc', '$laundryprice')";;
	
	if (mysqli_query($db, $query) === TRUE) {
		echo '<script>alert("New Record Created Successfully")</script>'; 
	} else {
		echo "Error: " . $sql . "<br>" . mysqli_error($db);
	}
}







// call the new_laundry() function if change_pass is clicked
if (isset($_POST['deletetype'])) {
	deletetype();
}

// Update new Laundry
function deletetype(){
	global $db, $username, $errors;

	
	// attempt login if no errors on form
	

	$query = "DELETE FROM laundry_type WHERE laun_type_id = 4";
	
	if (mysqli_query($db, $query) === TRUE) {
		echo '<script>alert("New Record Created Successfully")</script>'; 
	} else {
		echo "Error: " . $sql . "<br>" . mysqli_error($db);
	}
}
	












// call the register() function if register_btn is clicked
if (isset($_POST['new_customer'])) {
	newcustomer();
}

// REGISTER USER
function newcustomer(){
	// call these variables with the global keyword to make them available in function
	global $db, $errors, $username, $email;

	// receive all input values from the form. Call the e() function
    // defined below to escape form values
	$customer    =  e($_POST['customer']);
	$priority       =  e($_POST['priority']);
	$weight  =  e($_POST['weight']);
	$type_id  =  e($_POST['type_id']);

	// form validation: ensure that the form is correctly filled
	if (empty($customer)) { 
		array_push($errors, "customer name is required"); 
	}
	if (empty($priority)) { 
		array_push($errors, "priority is required"); 
	}
	if (empty($weight)) { 
		array_push($errors, "Weight is required"); 
	}
	if (empty($type_id)) { 
		array_push($errors, "type_id is required"); 
	}
	

	// register user if there are no errors in the form
	if (count($errors) == 0) {
		
		
			
			$query = "INSERT INTO laundry (customer_name,laun_priority, laun_weight, laun_type_id) 
					  VALUES('$customer', '$priority', '$weight', '$type_id')";
			if (mysqli_query($db, $query) === TRUE) {
				$message = "Data deleted successfully !"; 
			} else {
				echo "Error: " . $sql . "<br>" . mysqli_error($db);
			}
	}
}



//claim laundry
if(isset($_POST['claim_laundry'])){
	$checkbox = $_POST['check'];
	for($i=0;$i<count($checkbox);$i++){
	$del_id = $checkbox[$i]; 
	mysqli_query($db,"UPDATE laundry SET laun_claimed=laun_claimed+1 WHERE laun_id='".$del_id."'");
	$message = "Data incremented successfully !";
}
}


//delete laundry

if(isset($_POST['delete_customer'])){
	$checkbox = $_POST['check'];
	for($i=0;$i<count($checkbox);$i++){
	$del_id = $checkbox[$i]; 
	mysqli_query($db,"DELETE FROM laundry WHERE laun_id='".$del_id."'");
	$message = "Data deleted successfully !";
}
}

//delete type
if(isset($_POST['delete_type'])){
	$checkbox = $_POST['check'];
	for($i=0;$i<count($checkbox);$i++){
	$del_id = $checkbox[$i]; 
	mysqli_query($db,"DELETE FROM laundry_type WHERE laun_type_id='".$del_id."'");
	$message = "Data deleted successfully !";
}
}


//update type
// if(isset($_POST['edit_type'])){
// 	$checkbox = $_POST['check'];
// 	for($i=0;$i<count($checkbox);$i++){
// 	$del_id = $checkbox[$i]; 
// 	mysqli_query($db,"DELETE FROM laundry_type WHERE laun_type_id='".$del_id."'");
// 	$message = "Data deleted successfully !";
// }
// }