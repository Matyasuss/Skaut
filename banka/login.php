<?php
// Database connection (replace with your actual database credentials)

$servername = "";
$db_username = "";
$password = "";
$dbname = "";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Query the database to check if the user exists
  $query = "SELECT * FROM b_skaut WHERE name = '$username' AND pin = '$password'";
  $result = $conn->query($query);

  // Check if the user exists
  if ($result->num_rows > 0) {
    // Start a session and store the user's login status
    session_start();
    $_SESSION["logged_in"] = true;
    $_SESSION["username"] = $username;
    $_SESSION["user_ID"] = $ID;

    // Redirect to the index.html page
    header("Location: index.php");
    exit;
  } else {

  }
}

// Close the database connection
$conn->close();
?>

<!-- HTML code starts here -->
<html>
  <head>
    <title>Přihlášení</title>
    <style>
      body {
        font-family: Arial, sans-serif;
        background: black;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
      }
      .container {
        text-align: center;
      }
      .logo {
        margin-bottom: 20px;
      }
      .logo img {
        width: 150px;
      }
      .title {
        font-size: 24px;
        font-weight: bold;
        color: #555;
      }
      .subtitle {
        font-size: 20px;
        color: white;
        margin-bottom: 20px;
      }
      .login-box {
        background: #292929;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 300px;
        margin: 0 auto;
      }
      .login-box input[type="text"], .login-box input[type="password"] {
        width: calc(100% - 20px);
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
      }
      .login-box input[type="checkbox"] {
        margin-right: 10px;
      }
      .login-box label {
        font-size: 14px;
        color: #555;
      }
      .login-box a {
        font-size: 14px;
        color: #007bff;
        text-decoration: none;
      }
      .login-box a:hover {
        text-decoration: underline;
      }
      .login-box button {
        background: #4caf50;
        color: white;
        padding: 10px;
        border: none;
        border-radius: 4px;
        width: 100%;
        font-size: 16px;
        cursor: pointer;
      }
      .login-box button:hover {
        background: #45a049;
      }
      .disclaimer {
          color: white;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="logo">
        <img alt="Logo" src="SKAUT_logo_bile_stit.png"/>
      </div>
      <div class="subtitle">Skautská národní banka</div>
      <div class="login-box">
        <form action="login.php" method="post">
          <input placeholder="Uživatelské jméno" type="text" name="username"/>
          <input placeholder="PIN" type="password" name="password"/>
          <button>Přihlásit</button>
        </form >
        <div class="disclaimer">
            Stránka vytvořena pouze pro hru
        </div>
      </div>
    </div>
  </body>
</html>