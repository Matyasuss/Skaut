<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit;
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

// Fetch current user's budget
$query = "SELECT budget FROM b_skaut WHERE name = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the data
if ($row = $result->fetch_assoc()) {
    $budget = $row['budget'];
} else {
    $budget = 0; // Default value if no budget found
}

// Process transfer
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $transferAmount = $_POST['transfer-amount'];
    $receiverUsername = $_POST['receiver-username'];

    // Validate transfer amount and receiver
    $errors = [];

    // Check if transfer amount is positive
    if ($transferAmount <= 0) {
        $errors[] = "Transfer amount must be greater than 0.";
    }

    // Check if sender has enough budget
    if ($transferAmount > $budget) {
        $errors[] = "Insufficient funds for transfer.";
    }

    // Check if receiver exists
    $checkReceiverQuery = "SELECT * FROM b_skaut WHERE name = ?";
    $checkStmt = $conn->prepare($checkReceiverQuery);
    $checkStmt->bind_param("s", $receiverUsername);
    $checkStmt->execute();
    $receiverResult = $checkStmt->get_result();

    if ($receiverResult->num_rows == 0) {
        $errors[] = "Receiver username does not exist.";
    }

    // If no errors, perform transfer
    if (empty($errors)) {
        // Start a transaction
        $conn->begin_transaction();

        try {
            // Deduct amount from sender
            $deductQuery = "UPDATE b_skaut SET budget = budget - ? WHERE name = ?";
            $deductStmt = $conn->prepare($deductQuery);
            $deductStmt->bind_param("is", $transferAmount, $username);
            $deductStmt->execute();

            // Add amount to receiver
            $addQuery = "UPDATE b_skaut SET budget = budget + ? WHERE name = ?";
            $addStmt = $conn->prepare($addQuery);
            $addStmt->bind_param("is", $transferAmount, $receiverUsername);
            $addStmt->execute();

            // Commit transaction
            $conn->commit();

            // Redirect with success message
            $_SESSION['transfer_success'] = "Transfer of $transferAmount Vd to $receiverUsername successful!";
            header("Location: index.php");
            exit;
        } catch (Exception $e) {
            // Rollback transaction in case of error
            $conn->rollback();
            $errors[] = "Transfer failed: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
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
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 600px;
            height: 150px;
            font-size: 20px;
            text-align: center;
        }
        .account-info span {
            font-weight: bold;
            font-size: 27px;
        }
        .right-section {
            text-align: center;
        }
        .form {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 20px;
            width: 500px;
        }
        .form label {
            font-size: 16px;
            font-weight: bold;
            color: white;
        }
        .form input[type="number"],
        .form input[type="text"] {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid black;
            font-size: 16px;
        }
        .form input[type="submit"] {
            background-color: white;
            color: black;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
        .success-message {
            color: green;
            text-align: center;
            margin-bottom: 10px;
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
                <?php echo htmlspecialchars($username); ?>:
            </span>
            <br>
            <?php echo htmlspecialchars($budget); ?> Vd
        </div>
        <div class="right-section">
            <?php
            // Display success message if set
            if (isset($_SESSION['transfer_success'])) {
                echo "<div class='success-message'>" . htmlspecialchars($_SESSION['transfer_success']) . "</div>";
                unset($_SESSION['transfer_success']);
            }

            // Display errors if any
            if (!empty($errors)) {
                echo "<div class='error-message'>";
                foreach ($errors as $error) {
                    echo htmlspecialchars($error) . "<br>";
                }
                echo "</div>";
            }
            ?>
            <form class="form" method="POST">
                <label for="receiver-username">Příjemce</label>
                <input type="text" id="receiver-username" name="receiver-username" required>
                
                <label for="transfer-amount">Částka převodu</label>
                <input type="number" id="transfer-amount" name="transfer-amount" required>
                
                <input type="submit" value="Převést">
            </form>
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