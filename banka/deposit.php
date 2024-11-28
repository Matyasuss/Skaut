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

// Prepare a single query to fetch the budget
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

// Check if a deposit has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $depositValue = $_POST['hodnota-vkladu'];
    $workerPassword = $_POST['worker-password'];
    
    // Define the correct worker password
    $correctPassword = "6378591339456";

    // Check if the password is correct
    if (trim($workerPassword) == $correctPassword) {
        // Update the budget in the database
        $query = "UPDATE b_skaut SET budget = budget + ? WHERE name = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $depositValue, $username);
        
        if ($stmt->execute()) {
            echo "<script>alert('Deposit processed successfully!');</script>";
            echo "<script> window.location.href = 'index.php';</script>";
            $budget += $depositValue;
        } else {
            echo "<script>alert('Error updating budget: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Incorrect password. Please try again.');</script>";
    }
}
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
        .form input[type="button"] {
            background-color: white;
            color: black;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
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
        .password-modal {
            display: none;
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
        }
        .password-modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            color: black;
        }
    </style>
<script>
    function requestAssistance() {
        // Clear all password input fields
        document.getElementById('worker-password').value = '';
        document.getElementById('worker-password-hidden').value = '';
        document.getElementById('password-modal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('password-modal').style.display = 'none';
    }

    function submitForm() {
        // Get the password from the modal input
        const modalPassword = document.getElementById('worker-password').value;
        
        // Set the value of the hidden input explicitly
        document.getElementById('worker-password-hidden').value = modalPassword;
        
        // Submit the form
        document.forms[0].submit();
        closeModal();
    }
</script>
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
            <form class="form" method="POST">
                <label for="hodnota-vkladu">Hodnota vkladu</label>
                <input type="number" id="hodnota-vkladu" name="hodnota-vkladu" required>
                <br>
                <input type="button" value="Vložit" onclick="requestAssistance()">
<input type="hidden" name="worker-password" id="worker-password-hidden">
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

    <div id="password-modal" class="password-modal">
        <div class="password-modal-content">
            <h2>Vyžadována kontrola pracovníka banky</h2>
            <label for="worker-password">Kód pracovníka:</label>
            <input type="text" id="worker-password" required>
            <br>
            <input type="button" value="Confirm" onclick="submitForm()">
            <input type="button" value="Cancel" onclick="closeModal()">
        </div>
    </div>
</body>
</html>