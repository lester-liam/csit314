<?php

require_once('Database.php');
require_once('CleanerService.php');

class Shortlist
{
    protected int $homeownerID;
    protected int $serviceID;
    protected string $category;
    protected string $serviceName;
    protected string $cleanerName;
    protected float $price;

    public function newShortlist(int $homeownerID, int $serviceID): bool
    {
        // New DB Conn
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL TryCatch Statement
        try {

            $sql = "INSERT INTO Shortlist (homeownerID, serviceID)
                    VALUES (:homeownerID, :serviceID)";

            $stmt = $db_conn->prepare($sql);
            $stmt->bindParam(":homeownerID", $homeownerID);
            $stmt->bindParam(":serviceID", $serviceID);

            $execResult = $stmt->execute();
            unset($db_handle); // Disconnect DB Conn

            // execute() Success?
            if ($execResult) {
                // Increment CleanerService Shortlist Count
                $CleanerServiceObject = new CleanerService();
                $CleanerServiceObject->incrementShortlistsCount($serviceID);
                return true;
            } else {
                return false;
            }

        } catch (PDOException $e) {
            error_log("Database insert failed: " . $e->getMessage());
            unset($db_handle);
            return false;
        }
    }

    public function viewAllShortlist(int $homeownerID): ?array
    {

        // New DB Conn
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL TryCatch Statement
        try {

            $sql = "SELECT
                        s.homeownerID AS homeownerID,
                        cs.id AS serviceID,
                        sc.category AS category,
                        cs.serviceName AS serviceName,
                        ua.fullName AS cleanerName,
                        cs.price AS price
                    FROM Shortlist s
                    LEFT JOIN CleanerService cs ON s.serviceID = cs.id
                    LEFT JOIN ServiceCategory sc ON cs.serviceCategoryID = sc.id
                    LEFT JOIN UserAccount ua ON cs.cleanerID = ua.id
                    WHERE s.homeownerID = :homeownerID";

            $stmt = $db_conn->prepare($sql);
            $stmt->bindParam(":homeownerID", $homeownerID);

            $execResult = $stmt->execute();
            unset($db_handle); // Disconnect DB Conn

            // execute() Success?
            if ($execResult) {
                return $stmt->fetchAll(PDO::FETCH_CLASS, 'Shortlist');
            } else {
                return null;
            }

        } catch (PDOException $e) {
            error_log("Database search failed: " . $e->getMessage());
            unset($db_handle);
            return null;
        }
    }

    public function viewShortlist(int $homeownerID, int $serviceID): ?Shortlist
    {

        // New DB Conn
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL TryCatch Statement
        try {

            $sql = "SELECT
                        s.homeownerID AS homeownerID,
                        cs.id AS serviceID,
                        sc.category AS category,
                        ua.fullName AS cleanerName,
                        cs.price AS price,
                        cs.serviceName AS serviceName,
                        cs.cleanerID AS cleanerID
                    FROM Shortlist s
                    LEFT JOIN CleanerService cs ON s.serviceID = cs.id
                    LEFT JOIN ServiceCategory sc ON cs.serviceCategoryID = sc.id
                    LEFT JOIN UserAccount ua ON cs.cleanerID = ua.id
                    WHERE s.homeownerID = :homeownerID
                    AND s.serviceID = :serviceID";

            $stmt = $db_conn->prepare($sql);
            $stmt->bindParam(":homeownerID", $homeownerID);
            $stmt->bindParam(":serviceID", $serviceID);

            $execResult = $stmt->execute();
            unset($db_handle); // Disconnect DB Conn

            // execute() Success?
            if ($execResult) {
                return $stmt->fetchObject('Shortlist');
            } else {
                return null;
            }

        } catch (PDOException $e) {
            error_log("Database search failed: " . $e->getMessage());
            unset($db_handle);
            return null;
        }
    }

    public function searchShortlist(int $homeownerID, string $searchTerm): ?array
    {

        // New DB Conn
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL TryCatch Statement
        try {

            $searchTerm = "%" . $searchTerm . "%";

            $sql = "SELECT
                        s.homeownerID AS homeownerID,
                        cs.id AS serviceID,
                        sc.category AS category,
                        ua.fullName AS cleanerName,
                        cs.price AS price,
                        cs.serviceName AS serviceName,
                        cs.cleanerID AS cleanerID
                    FROM Shortlist s
                    LEFT JOIN CleanerService cs ON s.serviceID = cs.id
                    LEFT JOIN ServiceCategory sc ON cs.serviceCategoryID = sc.id
                    LEFT JOIN UserAccount ua ON cs.cleanerID = ua.id
                    WHERE s.homeownerID = :homeownerID
                    AND (ua.fullName LIKE :searchTerm
                    OR sc.category LIKE :searchTerm
                    OR cs.serviceName LIKE :searchTerm)";

            $stmt = $db_conn->prepare($sql);
            $stmt->bindParam(":homeownerID", $homeownerID);
            $stmt->bindParam(":searchTerm", $searchTerm);

            $execResult = $stmt->execute();
            unset($db_handle); // Disconnect DB Conn

            // execute() Success?
            if ($execResult) {
                return $stmt->fetchAll(PDO::FETCH_CLASS, 'Shortlist');
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
    public function getHomeownerID(): int { return $this->homeownerID; }
    public function getServiceID(): int { return $this->serviceID; }
    public function getCategory(): string { return $this->category; }
    public function getServiceName(): string { return $this->serviceName; }
    public function getCleanerName(): string { return $this->cleanerName; }
    public function getPrice(): float { return $this->price; }
}
?>