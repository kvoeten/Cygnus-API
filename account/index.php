<?php



if (isset($_POST['code']) {
	$client = new GuzzleHttp\Client();
	$response = $client->request(
		'GET',
		'https://discordapp.com/api/v6',
		[
			'Content-Type' => 'application/x-www-form-urlencoded',
			'body' => [
				'client_id' => 652970958742618112,
				'client_secret' => 'UzYKZRk0jobB3Y-lljyvJjdvF2LApyww',
				'grant_type' => 'authorization_code',
				'code' => $_POST['code'],
				'redirect_uri' => 'https://account.skycastle.com/',
				'scope': 'identify email'
			],
			
		]
	);

	$body = json_decode((string)$response->getBody());
	if (!body['access_token']) {
		return;
	}
	
	/*
	  "access_token": "6qrZcUqja7812RVdnEKjpzOL4CvHBFG",
	  "token_type": "Bearer",
	  "expires_in": 604800,
	  "refresh_token": "D43f5y0ahjqew82jZ4NViEr2YafMKhue",
	  "scope": "identify"
	*/
	
	$info =  $client->request(
		'GET',
		'https://discordapp.com/api/v6',
		[
			'Content-Type' => 'application/x-www-form-urlencoded',
			'Authorization' => 'Bearer '.body['access_token'],
			'body' => [
				'client_id' => 652970958742618112,
				'client_secret' => 'UzYKZRk0jobB3Y-lljyvJjdvF2LApyww',
				'grant_type' => 'authorization_code',
				'code' => $_POST['code'],
				'redirect_uri' => 'https://account.skycastle.com/',
				'scope': 'identify email'
			],
			
		]
	);
?>

<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.css'>
<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css'><link rel="stylesheet" href="./style.css">
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

</head>
<body>
<!-- partial:index.partial.html -->
<div class="signupSection">
  <div class="info">
    <image src="bg.png"style="height:100%;position="> 
  </div>
  <form action="#" method="POST" class="signupForm" name="signupform">
    <h2>Sign Up</h2>
    <ul class="noBullet">
      <li>
        <label for="username"></label>
        <input type="text" class="inputFields" id="name" name="name" placeholder="Username" value="" oninput="return userNameValidation(this.value)" required/>
      </li>
      <li>
        <label for="password"></label>
        <input type="password" class="inputFields" id="password" name="password" placeholder="Password" value="" oninput="return passwordValidation(this.value)" required/>
      </li>
      <li>
        <label for="password_confirmation"></label>
        <input type="password" class="inputFields" id="password" name="password_confirmation" placeholder="Confirm Password" value="" oninput="return passwordValidation(this.value)" required/>
      </li>
      <li>
        <label for="email"></label>
        <input type="email" class="inputFields" id="email" name="email" placeholder="Email" value="" required/>
      </li>
      <li>
        <label for="birthday"></label>
        <input type="date" class="inputFields" id="birthday" name="birthday" placeholder="Birthday" value="" required/>
      </li>
      <li>
        <label for="sex"></label>
        <select class="inputFields" id="sex" name="gender">
			<option value="0">Male</option>
			<option value="1">Female</option>
			<option value="2">Special</option>
		</select>
      </li>
      <li>
        <label for="captcha"></label>
        <div class="g-recaptcha" data-sitekey="6LfjRHsUAAAAAFK3VUZ_05ShAHVgS2cCRnQWnM8R"></div>
      </li>
      <li id="center-btn">
        <input type="submit" id="join-btn" name="join" alt="Join" value="Join">
      </li>
    </ul>
  </form>
</div>
<!-- partial -->
  <script  src="./script.js"></script>

</body>
</html>