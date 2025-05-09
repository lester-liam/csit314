<?php
require_once "entity/UserProfile.php";

class ViewUserProfileController {
    
    private $userProfile;

    public function __construct() {
        $this->userProfile = new UserProfile();
    }

    // Returns One User Profile
    public function readUserProfile($id) {
        return $this->userProfile->readUserProfile($id);
    }
    
}

?>