<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit;
$stmt->close();
}

$username = $_SESSION["username"];

$servername = "";
$db_username = "";
$password = "";
$dbname = "";

// Connect to the database
$conn = new mysqli($servername, $db_username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare a single query to fetch both names
$query = "SELECT budget FROM b_skaut WHERE name = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the data
if ($row = $result->fetch_assoc()) {
    $budget = $row['budget'];
} 
else {
    
}

// Close the statement
$stmt->close();

?>

<html>
 <head>
  <style>
   body {
            background-color: black;
            color: white;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0 50px;
            flex-direction: column;
        }
        .top-section {
            display: flex;
            justify-content: space-between;
            width: 100%;
            padding: 0 50px;
            margin-top: 100px;
        }
        .account-info {
        background-color: white;
        color: black;
        padding: 20px;
        border-radius: 20px;
        display: flex; /* Change to flex */
        flex-direction: column; /* Stack items vertically */
        justify-content: center; /* Center vertically */
        align-items: center; /* Center horizontally */
        width: 600px;
        height: 150px;
        font-size: 20px;
        text-align: center; /* Center the text */
        }
        .account-info span {
            font-weight: bold;
            font-size: 27px;
        }
        .right-section {
            text-align: center;
        }
        .buttons {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 0px;
            height: 200px;
        }
        .buttons a button {
            background-color: white;
            color: black;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            height: 50px;
            font-weight: bold;
            width: 500px;

        }
        .bottom-section {
            text-align: center;
            margin-top: auto;
            padding-bottom: 20px;
        }
        .logo img {
            width: 200px;
            height: auto;
        }
        .bank-name {
            font-size: 35px;
            font-weight: bold;
        }
  </style>
 </head>
 <body>
  <div class="top-section">
   <div class="account-info">
    <span>
     <?php echo $username ?>:
    </span>
    <br>
     <?php echo $budget ?> Vd
   </div>
   <div class="right-section">
    <div class="buttons">
     <a href="deposit.php">
     <button>
      Vklad
     </button>
     </a>
     <a href="withdraw.php">
     <button>
      Výběr
     </button>
     </a>
     <a href="transfer.php">
     <button>
      Převod
     </button>
     </a>
     <a href="logout.php">
     <button>
      Odhlásit se
     </button>
     </a>
    </div>
   </div>
  </div>
  <div class="bottom-section">
   <div class="logo">
    <img alt="Logo with a flower and a lion's head in the center" height="100" src="SKAUT_logo_bile_stit.png" width="100"/>
   </div>
   <div class="bank-name">
    Skautská národní banka
   </div>
  </div>
 </body>
</html>