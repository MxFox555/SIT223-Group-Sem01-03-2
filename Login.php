<?php
//Honey pot
if(!empty($_POST["username"])){
	die();
}

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: Home.php");
    exit;
}


require_once "config.php";

$username = $password = $name_f = $name_l = "";
$username_err = $password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(empty(trim($_POST["usernameactual"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["usernameactual"]);
    }

    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

    if(empty($username_err) && empty($password_err)){
        $sql = "SELECT id, email, name_f, name_s, password FROM accounts WHERE BINARY email = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            $param_username = $username;

            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    mysqli_stmt_bind_result($stmt, $id, $email, $name_f, $name_l, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            session_start();
														$_SESSION["loggedin"] = true;
														$_SESSION["id"] = $id;
														$_SESSION["email"] = $email;
														$_SESSION["name_first"] = $name_f;
														$_SESSION["name_last"] = $name_l;
														header("location: calendar.html");

                        } else{
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    $username_err = "No account found with that username.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Timey</title>
		<link rel="stylesheet" type="text/css" href="CSS/Login.css">
	</head>
	<body>
		<div data-layer="60602350-9672-4c12-9b00-015815f32be8" class="rectangle14"></div>
		<div data-layer="88cee664-6fe4-45ac-a87c-869e6e889da1" class="rectangle15"></div>
		<div class="left-side">
			<div class="left-side-margin-1">
				<img class="logo" alt="Netwerkus" src="Images/Logo.png">
				<form class="left-side-margin-1-1" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
					<input name="username" type="text" style="display: none"/>
					<div class="left-side-margin-1-1-centerer">
						<label>Email</label>
						<br/>
						<input name="usernameactual" type="netwerkususername" style="font-size: 30px; height: 40px; width: 335px;"/>
						<br/>
						<label style="margin-top: 50px;">Password</label>
						<br/>
						<input name="password" type="password" style="font-size: 30px; height: 40px; width: 335px;"/>
						<a href="Signup.php">Signup</a> | <a href="#mydick">Forgot Password</a>
						<p style="background-color: #00ee00;"><?php echo $username_err . " " . $password_err ?></p>
					</div>
					<button class="display-button" id="returned-button">Login</button>
				</form>
			</div>
		</div>
		<div style="width:100%; height:100px;background-color:#00053a;">
		</div>
	</body>
</html>
