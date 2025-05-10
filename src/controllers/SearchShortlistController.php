<?php

require_once '/var/www/html/entity/Shortlist.php';

class SearchShortlistController {

    private $shortlist;

    public function __construct() {
        $this->shortlist = new Shortlist();
    }

    // Search User Account, Return Array[0 to Many] of User Profiles
    public function searchShortlist($homeownerID, $searchTerm) {
        return $this->shortlist->searchShortlist($homeownerID, $searchTerm);
    }

}

?>