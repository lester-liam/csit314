<?php

require_once('Database.php');

class ServiceHistory
{
    protected int $id;
    protected string $category;
    protected int $cleanerID;
    protected int $homeownerID;
    protected string $serviceDate;
    protected string $name;

    public function searchMatches(
        int $cleanerID,
        string $searchTerm,
        string $category,
        int $dateOption
    ): ?array {
        /*
            Searches for matching service history records based on the provided criteria.

            $cleanerID: int - The ID of the cleaner.
            $searchTerm: string - The term to search for in the category.
            $category: string - The specific category to filter by (can be empty for all categories).
            $dateOption: int - Defines the date range for the search:
                0: Past 7 Days
                1: Past 30 Days
                2: All Time

            Returns: Array of matching ServiceHistory records (nullable).
        */

        // New DB Conn
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL TryCatch Statement
        try {

            // Add Wildcard Search Operator
            $searchTerm = "%" . $searchTerm . "%";
            $category = "%" . $category . "%";

            // SQL Statements by dateOptions
            switch ($dateOption) {
                case 0: // Past 7 Days
                    $sql = "SELECT sh.*, ua.fullName AS name
                            FROM `ServiceHistory` sh
                            LEFT JOIN `UserAccount` ua ON sh.homeownerID = ua.id
                            WHERE sh.`cleanerID` = :cleanerID
                            AND (ua.`fullName` LIKE :searchTerm AND sh.`category`LIKE :category)
                            AND sh.`serviceDate` >= CURDATE() - INTERVAL 7 DAY";
                    break;
                case 1: // Past 30 Days
                    $sql = "SELECT sh.*, ua.fullName AS name
                            FROM `ServiceHistory` sh
                            LEFT JOIN `UserAccount` ua ON sh.homeownerID = ua.id
                            WHERE sh.`cleanerID` = :cleanerID
                            AND (ua.`fullName` LIKE :searchTerm AND sh.`category`LIKE :category)
                            AND sh.`serviceDate` >= CURDATE() - INTERVAL 30 DAY";
                    break;
                case 2: // All Time
                    $sql = "SELECT sh.*, ua.fullName AS name
                            FROM `ServiceHistory` sh
                            LEFT JOIN `UserAccount` ua ON sh.homeownerID = ua.id
                            WHERE sh.`cleanerID` = :cleanerID
                            AND (ua.`fullName` LIKE :searchTerm AND sh.`category`LIKE :category)";
                    break;
            }

            $stmt = $db_conn->prepare($sql);
            $stmt->bindParam(':cleanerID', $cleanerID);
            $stmt->bindParam(':searchTerm', $searchTerm);
            $stmt->bindParam(':category', $category);
            $execResult = $stmt->execute();
            unset($db_handle); // Disconnect DB Conn

            // execute() Success?
            if ($execResult) {
                return $stmt->fetchAll(PDO::FETCH_CLASS, 'ServiceHistory');
            } else {
                return null;
            }

        } catch (PDOException $e) {
            error_log("Database search failed: " . $e->getMessage());
            unset($db_handle);
            return null;
        }
    }

    public function viewMatches(int $cleanerID): ?array
    {
        // New DB Conn
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL TryCatch Statement
        try {
            $sql = "SELECT sh.*, ua.fullName AS name
                    FROM `ServiceHistory` sh
                    LEFT JOIN `UserAccount` ua ON sh.homeownerID = ua.id
                    WHERE sh.`cleanerID` = :cleanerID";

            $stmt = $db_conn->prepare($sql);
            $stmt->bindParam(':cleanerID', $cleanerID);

            $execResult = $stmt->execute();
            unset($db_handle); // Disconnect DB Conn

            // execute() Success?
            if ($execResult) {
                return $stmt->fetchAll(PDO::FETCH_CLASS, 'ServiceHistory');
            } else {
                return null;
            }

        } catch (PDOException $e) {
            error_log("Database search failed: " . $e->getMessage());
            unset($db_handle);
            return null;
        }
    }

    public function getCategories(int $cleanerID): ?array
    {
        // New DB Conn
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL TryCatch Statement
        try {
            $sql =  "SELECT DISTINCT category
                    FROM ServiceHistory sh
                    WHERE cleanerID = :cleanerID";

            $stmt = $db_conn->prepare($sql);
            $stmt->bindParam(':cleanerID', $cleanerID);

            $execResult = $stmt->execute();
            unset($db_handle); // Disconnect DB Conn

            // execute() Success?
            if ($execResult) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return null;
            }

        } catch (PDOException $e) {
            error_log("Database search failed: " . $e->getMessage());
            unset($db_handle);
            return null;
        }
    }

    public function searchBookings(
        int $homeownerID,
        string $searchTerm,
        string $category,
        int $dateOption
    ): ?array {
        /*
            Searches for matching service history records based on the provided criteria.

            $cleanerID: int - The ID of the cleaner.
            $searchTerm: string - The term to search for in the category.
            $category: string - The specific category to filter by (can be empty for all categories).
            $dateOption: int - Defines the date range for the search:
                0: Past 7 Days
                1: Past 30 Days
                2: All Time

            Returns: Array of matching ServiceHistory records (nullable).
        */

        // New DB Conn
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL TryCatch Statement
        try {

            // Add Wildcard Search Operator
            $searchTerm = "%" . $searchTerm . "%";
            $category = "%" . $category . "%";

            // SQL Statements by dateOptions
            switch ($dateOption) {
                case 0: // Past 7 Days
                    $sql = "SELECT sh.*, ua.fullName AS name
                            FROM `ServiceHistory` sh
                            LEFT JOIN `UserAccount` ua ON sh.cleanerID = ua.id
                            WHERE sh.`homeownerID` = :homeownerID
                            AND (ua.`fullName` LIKE :searchTerm AND sh.`category`LIKE :category)
                            AND sh.`serviceDate` >= CURDATE() - INTERVAL 7 DAY";
                    break;
                case 1: // Past 30 Days
                    $sql = "SELECT sh.*, ua.fullName AS name
                            FROM `ServiceHistory` sh
                            LEFT JOIN `UserAccount` ua ON sh.cleanerID = ua.id
                            WHERE sh.`homeownerID` = :homeownerID
                            AND (ua.`fullName` LIKE :searchTerm AND sh.`category`LIKE :category)
                            AND sh.`serviceDate` >= CURDATE() - INTERVAL 30 DAY";
                    break;
                case 2: // All Time
                    $sql = "SELECT sh.*, ua.fullName AS name
                            FROM `ServiceHistory` sh
                            LEFT JOIN `UserAccount` ua ON sh.cleanerID = ua.id
                            WHERE sh.`homeownerID` = :homeownerID
                            AND (ua.`fullName` LIKE :searchTerm AND sh.`category`LIKE :category)";
                    break;
            }

            $stmt = $db_conn->prepare($sql);
            $stmt->bindParam(':homeownerID', $homeownerID);
            $stmt->bindParam(':searchTerm', $searchTerm);
            $stmt->bindParam(':category', $category);
            $execResult = $stmt->execute();
            unset($db_handle); // Disconnect DB Conn

            // execute() Success?
            if ($execResult) {
                return $stmt->fetchAll(PDO::FETCH_CLASS, 'ServiceHistory');
            } else {
                return null;
            }

        } catch (PDOException $e) {
            error_log("Database search failed: " . $e->getMessage());
            unset($db_handle);
            return null;
        }
    }

    public function viewBookings(int $homeownerID): ?array
    {
        // New DB Conn
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL TryCatch Statement
        try {
            $sql = "SELECT sh.*, ua.fullName AS name
                    FROM `ServiceHistory` sh
                    LEFT JOIN `UserAccount` ua ON sh.cleanerID = ua.id
                    WHERE sh.`homeownerID` = :homeownerID";

            $stmt = $db_conn->prepare($sql);
            $stmt->bindParam(':homeownerID', $homeownerID);

            $execResult = $stmt->execute();
            unset($db_handle); // Disconnect DB Conn

            // execute() Success?
            if ($execResult) {
                return $stmt->fetchAll(PDO::FETCH_CLASS, 'ServiceHistory');
            } else {
                return null;
            }

        } catch (PDOException $e) {
            error_log("Database search failed: " . $e->getMessage());
            unset($db_handle);
            return null;
        }
    }

    public function getHoCategories(int $homeownerID): ?array
    {
        // New DB Conn
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL TryCatch Statement
        try {
            $sql =  "SELECT DISTINCT category
                    FROM ServiceHistory sh
                    WHERE homeownerID = :homeownerID";

            $stmt = $db_conn->prepare($sql);
            $stmt->bindParam(':homeownerID', $homeownerID);

            $execResult = $stmt->execute();
            unset($db_handle); // Disconnect DB Conn

            // execute() Success?
            if ($execResult) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return null;
            }

        } catch (PDOException $e) {
            error_log("Database search failed: " . $e->getMessage());
            unset($db_handle);
            return null;
        }
    }

    // Accessor Methods
    public function getId(): int { return $this->id; }
    public function getCategory(): string { return $this->category; }
    public function getCleanerID(): int { return $this->cleanerID; }
    public function getHomeownerID(): int { return $this->homeownerID; }
    public function getServiceDate(): string { return $this->serviceDate; }
    public function getName(): string { return $this->name; }
}
?>