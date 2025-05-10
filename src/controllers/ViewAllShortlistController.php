<?php

require_once '/var/www/html/entity/Shortlist.php';

class ViewAllShortlistController {

    private $shortlist;

    public function __construct() {
        $this->shortlist = new Shortlist();
    }

    // Returns All User Profiles
    public function viewAllShortlist($homeownerID) {
        return $this->shortlist->viewAllShortlist($homeownerID);
    }
}

?>