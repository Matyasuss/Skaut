<?php
session_start();

// Database connection
$servername = "";
$db_username = "";
$password = "";
$dbname = "";

// Handle AJAX request for budget update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $userId = $data['userId'];
    $amount = $data['amount'];
    $action = $data['action']; // 'pay' or 'add'

    // Connect to database
    $conn = new mysqli($servername, $db_username, $password, $dbname);
    
    if ($conn->connect_error) {
        die(json_encode(['success' => false, 'message' => 'Database connection failed']));
    }

    // Determine user ID (handle leading zero)
    $userIdToQuery = ltrim($userId, '0');

    // Check if user exists
    $checkQuery = "SELECT budget FROM b_skaut WHERE id = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("i", $userIdToQuery);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if ($action === 'pay') {
    // Check if user has enough budget for payment
    if ($user['budget'] >= $amount) {
        // Deduct amount from user's budget
        $updateQuery = "UPDATE b_skaut SET budget = budget - ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("di", $amount, $userIdToQuery);
        
        if ($updateStmt->execute()) {
            // Fetch updated budget
            $updatedQuery = "SELECT budget FROM b_skaut WHERE id = ?";
            $updatedStmt = $conn->prepare($updatedQuery);
            $updatedStmt->bind_param("i", $userIdToQuery);
            $updatedStmt->execute();
            $updatedResult = $updatedStmt->get_result();
            $updatedUser  = $updatedResult->fetch_assoc();

            echo json_encode([
                'success' => true, 
                'message' => "Payment of $amount processed successfully for UserID $userId",
                'newBalance' => $updatedUser ['budget']
            ]);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => "Failed to process payment"
            ]);
        }
    } else {
        echo json_encode([
            'success' => false, 
            'message' => "Insufficient funds. Current balance: " . $user['budget']
        ]);
    }
} elseif ($action === 'add') {
    // Add amount to user's budget
    $updateQuery = "UPDATE b_skaut SET budget = budget + ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("di", $amount, $userIdToQuery);
    
    if ($updateStmt->execute()) {
        // Fetch updated budget
        $updatedQuery = "SELECT budget FROM b_skaut WHERE id = ?";
        $updatedStmt = $conn->prepare($updatedQuery);
        $updatedStmt->bind_param("i", $userIdToQuery);
        $updatedStmt->execute();
        $updatedResult = $updatedStmt->get_result();
        $updatedUser  = $updatedResult->fetch_assoc();

        echo json_encode([
            'success' => true, 
            'message' => "Added $amount to budget for UserID $userId",
            'newBalance' => $updatedUser ['budget']
        ]);
    } else {
        echo json_encode([
            'success' => false, 
            'message' => "Failed to add funds"
        ]);
    }
}
    } else {
        echo json_encode([
            'success' => false, 
            'message' => "User  not found"
        ]);
    }

    $conn->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminál</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
            background-color: black;
            color: white;
        }
        #scan-button, #add-funds-button, #add-amount {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 18px;
            cursor: pointer;
            margin: 10px;
        }
        #amount-display, #balance-display {
            font-size: 24px;
            margin: 20px 0;
        }
        #message {
            margin-top: 20px;
            padding: 10px;
            font-weight: bold;
        }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>Platební terminál Skautské národní banky </h1>
    
    <div id="amount-display">Cena: 0 Vd</div>
    <div id="balance-display">Aktuální budget: ---</div>
    
    <div id="controls">
        <button id="add-amount">Nastavit množství</button>
        <button id="scan-button">Zaplatit</button>
        <button id="add-funds-button">Přidat</button>
    </div>

    <div id="message"></div>

<script>
    const addAmountBtn = document.getElementById('add-amount');
    const scanButton = document.getElementById('scan-button');
    const addFundsButton = document.getElementById('add-funds-button');
    const amountDisplay = document.getElementById('amount-display');
    const balanceDisplay = document.getElementById('balance-display');
    const messageDiv = document.getElementById('message');

    let currentAmount = 0;
    let currentUser_Id = null; // Corrected variable name
    let currentAction = null; // To store the action (add or pay)

    // Amount addition dialog
    addAmountBtn.addEventListener('click', () => {
        const input = prompt('Enter amount in Vd:', '');
        const amount = parseFloat(input);
        
        if (!isNaN(amount) && amount > 0) {
            currentAmount = amount;
            amountDisplay.textContent = `Amount: ${currentAmount} Vd`;
            messageDiv.textContent = `Amount set to ${currentAmount} Vd`;
            messageDiv.className = 'success';
        } else {
            messageDiv.textContent = 'Invalid amount entered';
            messageDiv.className = 'error';
        }
    });

    // Add Funds Button
    addFundsButton.addEventListener('click', () => {
        // Set currentAction to 'add'
        currentAction = 'add';
        initiateCardScanning();
    });

    // Scan Button
    scanButton.addEventListener('click', () => {
        // Set currentAction to 'pay'
        currentAction = 'pay';
        initiateCardScanning();
    });

    // Function to initiate NFC scanning
async function initiateCardScanning() {
    // Check if amount is set
    if (currentAmount <= 0) {
        messageDiv.textContent = 'Please set an amount first';
        messageDiv.className = 'error';
        return;
    }

    // NFC Scanning
    if ('NDEFReader' in window) {
        try {
            const ndef = new NDEFReader();
            await ndef.scan();

            messageDiv.textContent = 'Touch NFC card to proceed...';
            messageDiv.className = '';

            ndef.onreading = async (event) => {
                try {
                    const message = event.message;
                    const records = message.records;

                    for (const record of records) {
                        if (record.recordType === "text") {
                            const textDecoder = new TextDecoder(record.encoding);
                            const cardData = textDecoder.decode(record.data);
                            
                            // Validate that cardData is a numeric string
                            if (!/^\d+$/.test(cardData)) {
                                messageDiv.textContent = "Invalid card data";
                                messageDiv.className = 'error';
                                return;
                            }

                            // Use the cardData directly as the user ID
                            currentUserId = cardData; // Corrected variable name

                            // Send payment or add request based on action
                            const response = await fetch(window.location.href, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    userId: currentUserId, // Corrected variable name
                                    amount: currentAmount,
                                    action: currentAction // Use the action set before scanning
                                })
                            });

                            const result = await response.json();
                            
                            if (result.success) {
                                messageDiv.textContent = `${currentAction === 'add' ? 'Added' : 'Payment of'} ${currentAmount} Vd successful`;
                                messageDiv.className = 'success';
                                balanceDisplay.textContent = `Current Balance: ${result.newBalance} Vd`;
                                
                                // Reset amount
                                currentAmount = 0;
                                amountDisplay.textContent = 'Amount: 0 Vd';
                                
                                // Reload the page after a successful operation
                                setTimeout(() => {
                                    location.reload();
                                }, 2000); // Optional: add a delay of 2 seconds before reloading
                            } else {
                                messageDiv.textContent = result.message;
                                messageDiv.className = 'error';
                            }
                            return;
                        }
                    }
                } catch (readError) {
                    messageDiv.textContent = `Reading error: ${readError}`;
                    messageDiv.className = 'error';
                }
            };

            ndef.onreadingerror = (error) => {
                messageDiv.textContent = `NFC reading error: ${error}`;
                messageDiv.className = 'error';
            };

        } catch (scanError) {
            messageDiv.textContent = `Scan error: ${scanError}`;
            messageDiv.className = 'error';
            console.error(scanError);
        }
    } else {
        messageDiv.textContent = 'NFC not supported on this device';
        messageDiv.className = 'error';
    }
}

    // Browser compatibility check
    if (!/Chrome/i.test(navigator.userAgent)) {
        messageDiv.textContent = 'Please use Google Chrome';
        messageDiv.className = 'error';
    }
</script>
</body>
</html>
