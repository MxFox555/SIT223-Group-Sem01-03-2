<?php
	//Honey pot
	if(!empty($_POST["username"])){
		die();
	}

	if(!isset($_SESSION))
	{
		session_start();
	}

	require_once "config.php";

	$username = $password = $email = $name_f = $name_l = $confirm_password = "";
	$user_id = -1;

	function GetRndKey($length)
	{
		$final = '';
		$charset = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charcount = strlen($charset);
		for($i = 0; $i < $length; $i++){
			$final .= substr($charset, rand(0, strlen($charset)), 1);
		}
		return $final;
	}

	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$username_err = $password_err = $email_err = $name_f_err = $name_l_err = $confirm_password_err = "";
		if(empty(trim($_POST["usernameactual"])))
		{
			$username_err = "Please enter username.";
		}
		else
		{
			$sql = "SELECT id FROM accounts WHERE BINARY id = ?";

			if($stmt = mysqli_prepare($link, $sql))
			{
				mysqli_stmt_bind_param($stmt, "s", $param_username);

				$param_username = trim($_POST["usernameactual"]);

				if(mysqli_stmt_execute($stmt))
				{
					mysqli_stmt_store_result($stmt);

					if(mysqli_stmt_num_rows($stmt) >= 1)
					{
						$username_err = "This username is already taken.";
					}
					else
					{
						$username = trim($_POST["usernameactual"]);
					}
				}
				else
				{
					echo "Something went wrong, try again";
				}
			}

			mysqli_stmt_close($stmt);
		}

		if(empty(trim($_POST["email"])))
		{
			$email_err = "Please enter an email";
		}
		else
		{
			$email = trim($_POST["email"]);
		}

		if(empty(trim($_POST["firstname"])))
		{
			$name_f_err = "Please enter your first name";
		}
		else
		{
			$name_f = trim($_POST["firstname"]);
		}

		if(empty(trim($_POST["lastname"])))
		{
			$name_l_err = "Please enter your last name";
		}
		else
		{
			$name_l = trim($_POST["lastname"]);
		}

		if(empty(trim($_POST["password"])))
		{
			$password_err = "Please enter a password.";
		}
		elseif(strlen(trim($_POST["password"])) < 6)
		{
			$password_err = "Password must have at least 6 characters";
		}
		else
		{
			$password = trim($_POST["password"]);
		}

		if(empty(trim($_POST["passwordverif"])))
		{
			$confirm_password_err = "Please confirm password.";
		}
		else
		{
			$confirm_password = trim($_POST["passwordverif"]);
			if(empty($password_err) && ($password != $confirm_password))
			{
				$confirm_password_err = "Password did not match.";
			}
		}

		function RandId($length){
			$charset = "abcedfghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUV1234567890";
			$finalchar = "";
			for ($i = 0; $i <= 19; $i++) {
				$subpart = rand(0, 61);
				$finalchar .= substr($charset, $subpart, 1);
			}
			return $finalchar;
		}

		function CheckIdAccount($link, $id){
			$sql = "SELECT id FROM accounts WHERE id = " . "'" . $id . "'";
			$result = mysqli_query($link, $sql);
			if (mysqli_num_rows($result) > 0)
			{
				return False;
			}else{
				return True;
			}
		}
		if(empty($username_err) && empty($password_err) && empty($confirm_password_err))
		{
			$idgen = trim($_POST["usernameactual"]);

			//Create the account
			$sql = "INSERT INTO accounts (id, name_f, name_s, email, activated, password, account_type) VALUES (?, ?, ?, ?, 1, ?, ?)";

			if($stmt = mysqli_prepare($link, $sql))
			{
				$param_test = "Test";
				mysqli_stmt_bind_param($stmt, "ssssss", $idgen, $param_fname, $param_lname, $param_email, $param_password, $param_test);
				$param_username = $username;
				$param_password = password_hash($password, PASSWORD_DEFAULT);
				$param_email = $email;
				$param_fname = $name_f;
				$param_lname = $name_l;

				if(!mysqli_stmt_execute($stmt))
				{
					echo "Something went wrong, try again.";
					echo $stmt->error;
				}
			}
			mysqli_stmt_close($stmt);
			header("location: Login.php");
		}
		mysqli_close($link);
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Timey</title>
		<link rel="stylesheet" type="text/css" href="CSS/Login.css">
		<script src="https://apis.google.com/js/platform.js" async defer></script>
		<meta name="google-signin-client_id" content="YOUR_CLIENT_ID.apps.googleusercontent.com">
	</head>
	<body>
		<div data-layer="60602350-9672-4c12-9b00-015815f32be8" class="rectangle14"></div>
		<div data-layer="88cee664-6fe4-45ac-a87c-869e6e889da1" class="rectangle15"></div>
		<div class="left-side">
			<div class="left-side-margin-1">
				<img class="logo" alt="Timey" src="Images/Logo.png">
				<form class="left-side-margin-1-1" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
					<input name="username" type="text" style="display: none"/>
					<div class="left-side-margin-1-1-centerer-2">
						<label>Faculty ID</label>
						<br/>
						<input name="usernameactual" type="Timeyusername" style="font-size: 30px; height: 40px; width: 335px;"/>
						<br/>
						<label>Email</label>
						<br/>
						<input name="email" type="Timeyemail" style="font-size: 30px; height: 40px; width: 335px;"/>
						<br/>
						<label>First Name</label>
						<br/>
						<input name="firstname" type="Timeyfirstname" style="font-size: 30px; height: 40px; width: 335px;"/>
						<br/>
						<label>Last Name</label>
						<br/>
						<input name="lastname" type="Timeylastname" style="font-size: 30px; height: 40px; width: 335px;"/>
						<br/>
						<label style="margin-top: 50px;">Password</label>
						<br/>
						<input name="password" type="password" style="font-size: 30px; height: 40px; width: 335px;"/>
						<label>Verify Password</label>
						<br/>
						<input name="passwordverif" type="password" style="font-size: 30px; height: 40px; width: 335px;"/>
						<br/>
						<a href="Login.php">Login</a> | <a href="#mydick">Forgot Password</a>
						<p style="background-color: #00ee00;"><!--<?php echo $username_err . " " . $password_err ?>--></p>
					</div>
					<button class="display-button" id="returned-button">Signup</button>
				</form>
			</div>
		</div>
		<div style="width:100%; height:100px;background-color:#00053a;">
		</div>

	</body>
</html>
