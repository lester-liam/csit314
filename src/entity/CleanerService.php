<?php
require_once('Database.php');

class CleanerService {

    protected int $id;
    protected int $serviceCategoryID;
    protected int $cleanerID;
    protected string $serviceName;
    protected float $price;
    protected int $numViews;
    protected int $numShortlists;
    protected string $createdAt;
    protected string $updatedAt;
    protected string $category;
    protected string $cleanerName;

    // CRUD Operations //

    //  Create CleanerService
    public function createCleanerService(
        int $serviceCategoryID,
        int $cleanerID,
        string $serviceName,
        float $price
    ): bool {

    /*  Inserts New CleanerService
        $serviceCategoryID: int
        $cleanerID: int
        $serviceName: string
        $price: float

        Returns: Boolean
    */
        // New DB Conn
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL TryCatch Statement
        try {

            $sql = "INSERT INTO `CleanerService`
                    (`serviceCategoryID`, `cleanerID`, `serviceName`, `price`)
                    VALUES (
                        :serviceCategoryID, :cleanerID, :serviceName, :price
                    )";

            $stmt = $db_conn->prepare($sql);
            $stmt->bindParam(':serviceCategoryID', $serviceCategoryID);
            $stmt->bindParam(':cleanerID', $cleanerID);
            $stmt->bindParam(':serviceName', $serviceName);
            $stmt->bindParam(':price', $price);

            $execResult = $stmt->execute();

            unset($db_handle); // Disconnect DB Conn

            // Insert Success ?
            if ($execResult) {
                return TRUE;
            } else {
                return FALSE;
            }

        } catch (PDOException $e) {
            error_log("Database insert failed: " . $e->getMessage());
            unset($db_handle);
            return FALSE;
        }
    }

    //  Read CleanerService
    public function viewCleanerService(int $id, $cleanerID): ?CleanerService
    {
    /*  Select CleanerService By ID+CleanerID
        $id: int
        $cleanerID: int

        Returns: Single CleanerService (nullable)
    */

        // New DB Conn
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL TryCatch Statement
        try {
            $stmt = $db_conn->prepare(" SELECT cs.*, sc.category
                                        FROM CleanerService cs
                                        INNER JOIN ServiceCategory sc
                                        ON cs.serviceCategoryID = sc.id
                                        WHERE cs.id = :id
                                        AND cs.cleanerID = :cleanerID
                                     ");


            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':cleanerID', $cleanerID);
            $execResult = $stmt->execute();

            unset($db_handle); // Disconnect DB Conn

            // execute() Success?
            if ($execResult) {
                $cleanerService = $stmt->fetchObject('CleanerService');
                return $cleanerService;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            error_log("Database query failed: " . $e->getMessage());
            unset($db_handle);
            return null;
        }

    }

    public function viewAllCleanerService(int $cleanerID): ?array {
    /*  Select All CleanerService
        $cleanerID: int
        Returns: Array of CleanerService (nullable)
    */

        // New DB Conn
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL TryCatch Statement
        try {
            $stmt = $db_conn->prepare(" SELECT cs.*, sc.category
                                        FROM CleanerService cs
                                        INNER JOIN ServiceCategory sc
                                        ON cs.serviceCategoryID = sc.id
                                        WHERE cs.cleanerID = :cleanerID
                                     ");
            $stmt->bindParam(':cleanerID', $cleanerID);
            $execResult = $stmt->execute();
            unset($db_handle); // Disconnect DB Conn

            // execute() Success?
            if ($execResult) {
                // Execute was successful, now fetch the data
                $cleanerService = $stmt->fetchAll(
                                            PDO::FETCH_CLASS, 'CleanerService'
                                         );
                return $cleanerService;
            } else {
                return null;
            }

        } catch (PDOException $e) {
            error_log("Database query failed: " . $e->getMessage());
            unset($db_handle);
            return null;
        }

    }

    public function updateCleanerService(
        int $id,
        int $cleanerID,
        string $serviceName,
        float $price
    ): bool {

    /*  Updates a Cleaner Service:

        $id: int
        $cleanerID: int
        $serviceName: string
        $price: float

        Returns: Boolean
    */

        // New DB Conn
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL TryCatch Statement
        try {

            $sql = "UPDATE `CleanerService`
                    SET `serviceName` = :serviceName, `price` = :price
                    WHERE `id` = $id AND `cleanerID` = $cleanerID";

            $stmt = $db_conn->prepare($sql);
            $stmt->bindParam(':serviceName', $serviceName);
            $stmt->bindParam(':price', $price);

            $execResult = $stmt->execute();

            unset($db_handle); // Disconnect DB Conn

            // Insert Success ?
            if ($execResult) {
                return TRUE;
            } else {
                return FALSE;
            }

        } catch (PDOException $e) {
            error_log("Database update failed: " . $e->getMessage());
            unset($db_handle);
            return FALSE;
        }
    }

    public function deleteCleanerService(int $id, int $cleanerID): bool
    {
    /*  Deletes a CleanerService:
        $id: int
        $cleanerID: int
        Returns: Boolean
    */
        // New DB Conn
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL TryCatch Statement
        try {
            $stmt = $db_conn->prepare(" DELETE FROM `CleanerService`
                                        WHERE `id` = $id
                                        AND `cleanerID` = $cleanerID");

            $execResult = $stmt->execute();

            unset($db_handle); // Disconnect DB Conn

            // Insert Success ?
            if ($execResult) {
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (PDOException $e) {
            error_log("Database delete failed: " . $e->getMessage());
            unset($db_handle);
            return FALSE;
        }
    }


    public function searchCleanerService(
        int $cleanerID,
        string $searchTerm
    ): ?array {

    /*  Searches for CleanerSerivce:
        $cleanerID: int
        $searchTerm: string

        Returns: Array of CleanerService (nullable)
    */

        // New DB Conn
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL Statement
        try {
            $searchTerm = "%" . $searchTerm . "%";

            $stmt = $db_conn->prepare(" SELECT cs.*, sc.category
                                        FROM CleanerService cs
                                        INNER JOIN ServiceCategory sc
                                        ON cs.serviceCategoryID = sc.id
                                        WHERE cs.cleanerID = :cleanerID
                                        AND (sc.category LIKE :term
                                        OR cs.serviceName LIKE :term)
                                      ");

            $stmt->bindParam(':cleanerID', $cleanerID);
            $stmt->bindParam(':term', $searchTerm);
            $execResult = $stmt->execute();
            unset($db_handle); // Disconnect DB Conn

            // Search Success ?
            if ($execResult) {
                return $stmt->fetchAll(PDO::FETCH_CLASS, 'CleanerService');
            } else {
                return null;
            }
        } catch (PDOException $e) {
            error_log("Database search failed: " . $e->getMessage());
            return null;
        }
    }

    public function getCleanerServiceViews (int $id): int {
    /*  Select CleanerService Views
        $id: int
        $cleanerID: int

        Returns: int numViews
    */

        // New DB Conn
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL TryCatch Statement
        try {
            $stmt = $db_conn->prepare(" SELECT `numViews`
                                        FROM `CleanerService`
                                        WHERE `id` = $id
                                     ");
            $execResult = $stmt->execute();

            unset($db_handle); // Disconnect DB Conn

            // execute() Success?
            if ($execResult) {

                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return (int) $result['numViews'];

            } else {
                return 0; // Default Value
            }
        } catch (PDOException $e) {
            error_log("Database query failed: " . $e->getMessage());
            unset($db_handle);
            return 0; // Default Value
        }
    }

    public function getCleanerServiceShortlists (int $id): int {
    /*  Select CleanerService Shortlists
        $id: int
        $cleanerID: int

        Returns: int numShortlists
    */

        // New DB Conn
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL TryCatch Statement
        try {
            $stmt = $db_conn->prepare(" SELECT `numShortlists`
                                        FROM `CleanerService`
                                        WHERE `id` = $id
                                     ");
            $execResult = $stmt->execute();

            unset($db_handle); // Disconnect DB Conn

            // execute() Success?
            if ($execResult) {

                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return (int) $result['numShortlists'];

            } else {
                return 0; // Default Value
            }
        } catch (PDOException $e) {
            error_log("Database query failed: " . $e->getMessage());
            unset($db_handle);
            return 0; // Default Value
        }
    }

    public function hoViewAllService(): ?array {
    /*  Select All CleanerService
        Returns: Array of CleanerService (nullable)
    */

        // New DB Conn
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL TryCatch Statement
        try {
            $stmt = $db_conn->prepare(" SELECT cs.*,
                                        sc.category AS category,
                                        ua.fullName AS cleanerName
                                        FROM `CleanerService` cs
                                        LEFT JOIN `UserAccount` ua ON cs.cleanerID = ua.id
                                        LEFT JOIN `ServiceCategory` sc ON cs.serviceCategoryID = sc.id
                                    ");
            $execResult = $stmt->execute();
            unset($db_handle); // Disconnect DB Conn

            // execute() Success?
            if ($execResult) {
                // Execute was successful, now fetch the data
                $cleanerService = $stmt->fetchAll(
                                            PDO::FETCH_CLASS, 'CleanerService'
                                         );
                return $cleanerService;
            } else {
                return null;
            }

        } catch (PDOException $e) {
            error_log("Database query failed: " . $e->getMessage());
            unset($db_handle);
            return null;
        }

    }

    public function hoViewService(int $id): ?CleanerService {
    /*  Select All CleanerService
        Returns: Array of CleanerService (nullable)
    */

        // New DB Conn
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL TryCatch Statement
        try {
            $stmt = $db_conn->prepare(" SELECT cs.*,
                                        sc.category AS category,
                                        ua.fullName AS cleanerName
                                        FROM `CleanerService` cs
                                        LEFT JOIN `UserAccount` ua ON cs.cleanerID = ua.id
                                        LEFT JOIN `ServiceCategory` sc ON cs.serviceCategoryID = sc.id
                                        WHERE cs.id = :id
                                     ");
            $stmt->bindParam(':id', $id);
            $execResult = $stmt->execute();

            // execute() Success?
            if ($execResult) {

                $cleanerService = $stmt->fetchObject('CleanerService');
                $cleanerService->incrementViewCount();

                return $cleanerService;

            } else {
                unset($db_handle); // Disconnect DB Conn
                return null;
            }

        } catch (PDOException $e) {
            error_log("Database query failed: " . $e->getMessage());
            unset($db_handle);
            return null;
        }

    }

    public function hoSearchService($searchTerm): ?array {
    /*  Select All CleanerService
        Returns: Array of CleanerService (nullable)
    */

        // New DB Conn
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL TryCatch Statement
        try {
            $searchTerm = "%" . $searchTerm . "%";
            $stmt = $db_conn->prepare(" SELECT cs.*,
                                        sc.category AS category,
                                        ua.fullName AS cleanerName
                                        FROM `CleanerService` cs
                                        LEFT JOIN `UserAccount` ua
                                            ON cs.cleanerID = ua.id
                                        LEFT JOIN `ServiceCategory` sc
                                            ON cs.serviceCategoryID = sc.id
                                        WHERE ua.fullName LIKE :searchTerm
                                        OR sc.category LIKE :searchTerm
                                        OR cs.serviceName LIKE :searchTerm
                                    ");
            $stmt->bindParam(":searchTerm", $searchTerm);
            $execResult = $stmt->execute();
            unset($db_handle); // Disconnect DB Conn

            // execute() Success?
            if ($execResult) {
                // Execute was successful, now fetch the data
                $cleanerService = $stmt->fetchAll(PDO::FETCH_CLASS, 'CleanerService');
                return $cleanerService;
            } else {
                return null;
            }

        } catch (PDOException $e) {
            error_log("Database query failed: " . $e->getMessage());
            unset($db_handle);
            return null;
        }

    }

    // Accessor Methods
    public function getId(): int { return $this->id; }
    public function getServiceCategoryID(): int { return $this->serviceCategoryID; }
    public function getCleanerID(): int { return $this->cleanerID; }
    public function getServiceName(): string { return $this->serviceName; }
    public function getPrice(): float { return $this->price; }
    public function getNumViews(): int { return $this->numViews; }
    public function getNumShortlists(): int { return $this->numShortlists; }
    public function getCreatedAt(): string { return $this->createdAt; }
    public function getUpdatedAt(): string { return $this->updatedAt; }
    public function getCategory(): string { return $this->category; }
    public function getCleanerName(): string { return $this->cleanerName; }

    // Mutator Methods
    private function incrementViewCount(): void
    {

        $sql = "UPDATE CleanerService SET numViews = numViews + 1 WHERE id = :id";

        // New DB Conn
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL TryCatch Statement
        try {

            $stmt = $db_conn->prepare($sql);
            $stmt->bindParam(":id", $this->id);
            $execResult = $stmt->execute();
            unset($db_handle); // Disconnect DB Conn

            // execute() Success?
            if (!$execResult) {
                error_log("Database update failed");
            }

        } catch (PDOException $e) {
            error_log("Database update failed: " . $e->getMessage());
            unset($db_handle);
        }
    }

    public function incrementShortlistsCount(int $serviceID): void
    {

        $sql = "UPDATE CleanerService SET numShortlists = numShortlists + 1 WHERE id = :id";

        // New DB Conn
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL TryCatch Statement
        try {

            $stmt = $db_conn->prepare($sql);
            $stmt->bindParam(":id", $serviceID);
            $execResult = $stmt->execute();
            unset($db_handle); // Disconnect DB Conn

            // execute() Success?
            if (!$execResult) {
                error_log("Database update failed");
            }

        } catch (PDOException $e) {
            error_log("Database update failed: " . $e->getMessage());
            unset($db_handle);
        }
    }
}
?>