<?php
require_once "../entity/UserAccount.php";

class CreateUserAccountController {

    private $userAccount;

    public function __construct() {
        $this->userAccount = new UserAccount();
    }

    // Create User Account, Returns a Boolean Value (Success/Fail)
    public function createUserAccount($username, $password, $fullName, $email, $phone, $userProfile) {
        return $this->userAccount->createUserAccount($username, $password, $fullName, $email, $phone, $userProfile);
    }

}

// `createUserAccount.php` Script
// Executes when Create User Account Form is Submitted (POST Request)
if (
    isset($_POST['username']) &&
    isset($_POST['password']) &&
    isset($_POST['fullName']) &&
    isset($_POST['email']) &&
    isset($_POST['phone']) &&
    isset($_POST['userProfile'])
) {

    // Instantiate New Controller & Create Account
    $controller = new CreateUserAccountController();
    $status = $controller->createUserAccount($_POST['username'], $_POST['password'], $_POST['fullName'], $_POST['email'], $_POST['phone'], $_POST['userProfile']);

    // Display Success or Fail
    if ($status) {
        header("Location: ../createUserAccount.php?status=1");
        exit();
    } else {
        header("Location: ../createUserAccount.php?status=0");
        exit();
    }

}

?>