<html>
<head>
    <meta charset="utf-8">
    <title>Signin</title>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	


	<?php
	
	require_once 'functions.php';
	require_once 'login.php';
	
	if(isset($_POST["username"]))
	{
		//krijo lidhjen
		$conn = new mysqli($hn, $un, $pw, $db);
		if ($conn->connect_error) 
			die($conn->connect_error);
		
		//marrim variablat perkatese (vlerat ne fushen username dhe password)
		$username = sanitizeMySQL($conn,$_POST["username"]);
		$password = sanitizeMySQL($conn,$_POST["password"]);
		
		//echo $username.":".$password;
		//krijojme queryn ne db
		$query = "select * from members where 
		Username='$username' and Password='$password'";
		$result = $conn->query($query);
		if (!$result) 
			die($conn->error);
		
		//trajtojme rezultatet. shikojme nese eshte kthyer ndonje rekord
		if($result->num_rows>0)
		{
			//lexojme te dhenat e db ne variablen rreshti
			$result->data_seek(0);
			$rreshti=$result->fetch_array(MYSQLI_ASSOC);
			
			//ruajme ne session id-ne e userit dhe username-n
			session_start();
			$_SESSION["username"] = $username;
			$_SESSION["name"] = $rreshti["Name"].
				" ".$rreshti["Surname"];
			$_SESSION["id"] = $rreshti["ID"];
			
			//ridrejtojme faqen tek mainpage.php
			header('Location: '.'index.html');
			die();
		}
		else
		{
			echo "<p><div class=\"alert alert-warning\">
		<a href=\"#\" class=\"close\" data-dismiss=\"alert\">&times;</a>
		<strong>Warning!</strong> Kredencialet jane gabim!.
		</div></p>";
		}
	}
	
	?>
	
</head>
<body class="text-center">
    <form action="signin.php" method="post" class="form-signin">
      <img class="mb-4" src="images/signin.png" alt="" width="110" height="110">
      <h1 class="h3 mb-3 font-weight-normal">Kycu ne aplikacion</h1>
      <label for="inputEmail" class="sr-only">Username</label>
      <input type="username" name="username" id="inputUsername" class="form-control" 
		placeholder="Username" required autofocus>
      <label for="inputPassword" class="sr-only">Password</label>
      <input type="password" name="password" id="inputPassword" class="form-control" 
	  placeholder="Password" required>
      <div class="checkbox mb-3">
        <label>
          <input type="checkbox" value="remember-me"> Remember me
        </label>
      </div>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
      <p class="mt-5 mb-3 text-muted">&copy; 2017-2018</p>
    </form>
  </body>
</html>