<?php
session_start();

require_once '../entity/UserAccount.php';

class LoginController {

    private $userAccount;

    // Constructor Class
    public function __construct() {
        $this->userAccount = new UserAccount();
    }

    // Authentication Method
    // Login Method Calls UserAccount Method
    public function login($username, $password, $userProfile) {
        return $this->userAccount->login($username, $password, $userProfile);
    }

}

// `login.php` Script
// Executes when Login Form is Submitted (POST Request)
if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['userProfile'])) {

    // Instantiate New Login Controller & Authenticate User
    $controller = new LoginController();
    $userAccount = $controller->login($_POST['username'], $_POST['password'], $_POST['userProfile']);

    // Login Fail:
    //   - Null User: Display Invalid Credentials Error
    //   - Suspended User/User Profile: Display User Suspended Error
    // Login Success:
    //   - Display Corresponding User Profile Page
    if (is_null($userAccount)) {

        header("Location: ../login.php?error=Invalid Credentials.");
        exit();


    } elseif (($userAccount->getSuspendStatus()) == 1) {

        header("Location: ../login.php?error=User Suspended");
        exit();

    } else {

        // Update Session Variables
        $_SESSION['id'] = $userAccount->getId();
        $_SESSION['username'] = $userAccount->getUsername();
        $_SESSION['userProfile'] = $userAccount->getUserProfile();

        if ($_SESSION['userProfile'] == "User Admin") {
            header("Location: ../viewUserProfile.php");
            exit();
        } elseif ($_SESSION['userProfile'] == "Platform Management") {
            header("Location: ../viewServiceCategory.php");
            exit();
        } elseif ($_SESSION['userProfile'] == "Cleaner") {
            header("Location: ../viewCleanerService.php");
            exit();
        } else {
            header("Location: ../homeownerHome.php");
            exit();
        }

    }
}

?>