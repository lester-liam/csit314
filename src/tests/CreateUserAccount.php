<?php

require_once "/var/www/html/entity/Database.php";

// Read JSON File Content, if fail exit script
$json_file = "Users_DummyJSON.json";
$file_contents = file_get_contents($json_file);
if ($file_contents === false) {
    unset($db_conn);
    die('Error: Could not read JSON file.');
}

// Decode JSON Data, if fail exit script
$data = json_decode($file_contents, true);
if ($data === null) {
    unset($db_conn);
    die('Error: JSON File cannot be decoded.');
}

// New DB Connnection
$db_handle = new Database();
$db_conn = $db_handle->getConnection();

// Prepare SQL Statement
$sql = "INSERT INTO UserAccount (username, password, fullName, email, phone, userProfile)
        VALUES (:username, :password, :fullName, :email, :phone, :userProfile)";
$stmt = $db_conn->prepare($sql);

$insertCount = 0; // Number of Inserts Counter
$scriptRanSuccess = true;
foreach ($data['users'] as $user) {
    try {
        // Merge First and Last Name
        $fullName = $user['firstName'] . " " . $user['lastName'];
        $password = md5($user['password']);
        $phone = strval(rand(80000000, 99999999)); // Random Phone Number

        // Insert Role Based on Count:
        //  <= 10           : User Admins
        //  >10 and <= 30   : Platform Management
        //  >30 and <= 60   : Cleaner
        //  >60             : Homeowner
        if ($insertCount <= 10) {
            $up = "User Admin";
        } elseif ($insertCount > 10 && $insertCount <= 30) {
            $up = "Platform Management";
        } elseif ($insertCount > 30 && $insertCount <= 60) {
            $up = "Cleaner";
        } else {
            $up = "Homeowner";
        }

        $stmt->bindParam(':username', $user['username']);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':fullName', $fullName);
        $stmt->bindParam(':email', $user['email']);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':userProfile', $up);

        // Execute Statement
        $execResult = $stmt->execute();

        // Check if Insert Successful
        if ($execResult) {
            echo "User {$user['firstName']} {$user['lastName']} inserted successfully.\n";
        } else {
            echo "Failed to insert User {$user['firstName']} {$user['lastName']}.\n";
        }

        $insertCount = $insertCount + 1; // Increment Counter

    } catch (PDOException $e) {
        error_log("Error inserting user {$user['firstName']} {$user['lastName']}: " . $e->getMessage());
        $scriptRanSuccess = false;
        continue;
    }
}

// Script Ran Success Message
unset($db_conn);
if ($scriptRanSuccess) {
    echo "\nScript ran successfully.\n";
} else {
    echo "\nScript ran successfully, but with errors, check console or logs.\n";
}

exit();