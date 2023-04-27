<?php

include("classes/connect.php");
include("classes/signup.php");

$first_name = "";
$last_name = "";
$gender = "";
$email = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


	$signup = new Signup();
	$result = $signup->evaluate($_POST);

	if ($result != "") {

		echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
		echo "<br>The following errors occured:<br><br>";
		echo $result;
		echo "</div>";
	} else {

		header("Location:" . ROOT . "login");
		die;
	}


	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$gender = $_POST['gender'];
	$email = $_POST['email'];

}




?>

<html>

<head>

	<title>SkyLine | Signup</title>
</head>

<style>
	* {
		margin: 0;
		padding: 0;
		font-family: 'poppins', sans-serif;
	}

	a {
		text-decoration: none;
	}



	#text {

		height: 40px;
		width: 300px;
		border-radius: 4px;
		border: solid 1px white;
		padding: 4px;
		font-size: 14px;
		background: transparent;
	}


	@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

	* {
		margin: 0;
		padding: 0;
		font-family: 'poppins', sans-serif;
	}

	section {
		display: flex;
		justify-content: center;
		align-items: center;
		min-height: 100vh;
		width: 100%;

		background: url('background6.jpg')no-repeat;
		background-position: center;
		background-size: cover;
	}

	.form-box {
		position: relative;
		width: 400px;
		height: 650px;
		background: transparent;
		border: 2px solid rgba(255, 255, 255, 0.5);
		border-radius: 20px;
		backdrop-filter: blur(15px);
		display: flex;
		justify-content: center;
		align-items: center;

	}

	h2 {
		font-size: 2em;
		color: #fff;
		text-align: center;
	}

	.inputbox {
		position: relative;
		margin: 30px 0;
		width: 310px;
		border-bottom: 2px solid #fff;
	}

	.inputbox label {
		position: absolute;
		top: 50%;
		left: 5px;
		transform: translateY(-50%);
		color: #fff;
		font-size: 1em;
		pointer-events: none;
		transition: .5s;
	}

	input:focus~label,
	input:valid~label {
		top: -5px;
	}

	.inputbox input {
		width: 100%;
		height: 50px;
		background: transparent;
		border: none;
		outline: none;
		font-size: 1em;
		padding: 0 35px 0 5px;
		color: #fff;
	}

	.inputbox ion-icon {
		position: absolute;
		right: 8px;
		color: #fff;
		font-size: 1.2em;
		top: 20px;
	}

	.forget {
		margin: -15px 0 15px;
		font-size: .9em;
		color: #fff;
		display: flex;
		justify-content: space-between;
	}

	.forget label input {
		margin-right: 3px;

	}

	.forget label a {
		color: #fff;
		text-decoration: none;
	}

	.forget label a:hover {
		text-decoration: underline;
	}

	button {
		width: 100%;
		height: 40px;
		border-radius: 40px;
		background: #fff;
		border: none;
		outline: none;
		cursor: pointer;
		font-size: 1em;
		font-weight: 600;
	}

	.register {
		font-size: .9em;
		color: #fff;
		text-align: center;
		margin: 25px 0 10px;
	}

	.register p a {
		text-decoration: none;
		color: #fff;
		font-weight: 600;
	}

	.register p a:hover {
		text-decoration: underline;
	}
</style>

<body>

	<section>
		<div class="form-box">
			<div class="form-value">
				<form method="post">
					<h2>Sign up</h2>
					<div class="inputbox">
						<ion-icon name="person-outline"></ion-icon>
						<input value="<?php echo $first_name ?>" name="first_name" type="text" required>
						<label for="">firstname</label>
					</div>
					<div class="inputbox">
						<ion-icon name="person-outline"></ion-icon>
						<input value="<?php echo $last_name ?>" name="last_name" type="text" required>
						<label for="">lastname</label>
					</div>

					<span style="font-weight: normal;color:white">Gender</span><br>
					<select id="text" name="gender">

						<option>
							<?php echo $gender ?>
						</option>
						<option>Male</option>
						<option>Female</option>

					</select>
					<div class="inputbox">
						<ion-icon name="mail-outline"></ion-icon>
						<input value="<?php echo $email ?>" name="email" type="email" required>
						<label for="">Email</label>
					</div>
					<div class="inputbox">
						<ion-icon name="lock-closed-outline"></ion-icon>
						<input name="password" type="password" required>
						<label for="">Password</label>
					</div>
					<div class="inputbox">
						<ion-icon name="lock-closed-outline"></ion-icon>
						<input name="password2" type="password" required>
						<label for="">Password</label>
					</div>
					<button type="submit">Signup</button>
					<div class="register">
						<p><a href="<?= ROOT ?>login">Login</a></p>
					</div>
				</form>
			</div>
		</div>
	</section>
	<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
	<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>


</body>


</html>