<?php
require_once('Database.php');

class UserProfile {
    protected int $id;
    protected string $role;
    protected string $description;
    protected int $isSuspend;

    // CRUD Operations //

    //  Create User Profile
    public function createUserProfile(string $role, string $description): bool {
    /*  Inserts New User Profile:
        $role: string
        $description: string

        Returns: Boolean
    */

        // New DB Conn
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL TryCatch Statement
        try {
            $stmt = $db_conn->prepare("INSERT INTO `UserProfile` (`role`, `description`, `isSuspend`) VALUES (:role, :description, 0)");
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':description', $description);
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

    //  Read UserProfile
    public function readUserProfile(int $id): ?UserProfile {
    /*  Select User Profile By ID
        $id: int

        Returns: Single UserProfile (nullable)
    */

        // New DB Conn
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL TryCatch Statement
        try {
            $stmt = $db_conn->prepare("SELECT * FROM `UserProfile` WHERE `id` = :id");
            $stmt->bindParam(':id', $id);
            $execResult = $stmt->execute();

            unset($db_handle); // Disconnect DB Conn

            // execute() Success?
            if ($execResult) {
                $userProfile = $stmt->fetchObject('UserProfile');
                return $userProfile;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            error_log("Database query failed: " . $e->getMessage());
            unset($db_handle);
            return null;
        }

    }

    public function readAllUserProfile(): ?array {
    /*  Select All User Profile

        Returns: Array of User Profiles (nullable)
    */

        // New DB Conn
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL TryCatch Statement
        try {
            $stmt = $db_conn->prepare("SELECT * FROM `UserProfile`");
            $execResult = $stmt->execute();
            unset($db_handle); // Disconnect DB Conn

            // execute() Success?
            if ($execResult) {
                // Execute was successful, now fetch the data
                $userProfile = $stmt->fetchAll(PDO::FETCH_CLASS, 'UserProfile');
                return $userProfile;
            } else {
                return null;
            }

        } catch (PDOException $e) {
            error_log("Database query failed: " . $e->getMessage());
            unset($db_handle);
            return null;
        }

    }

    public function updateUserProfile(int $id, string $role, string $description): bool {
    /*  Updates a User Profile:

        $id: int
        $role: string
        $description: string

        Returns: Boolean
    */

        // New DB Conn
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL TryCatch Statement
        try {
            $stmt = $db_conn->prepare("UPDATE `UserProfile` SET `role` = :role, `description` = :description WHERE `id` = $id");
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':description', $description);

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

    public function suspendUserProfile(int $id): bool {
    /*  Suspends a User Profile:
        $id: int
        Returns: Boolean
    */
        // New DB Conn
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL TryCatch Statement
        try {
            $stmt = $db_conn->prepare("UPDATE `UserProfile` SET `isSuspend` = 1 WHERE `id` = $id");

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

    // Search User Profile
    public function searchUserProfile(string $searchTerm): ?array {
    /*  Searches for User Profile(s):
        $searchTerm: string
        Returns: Array of UserProfile (nullable)
    */

        // New DB Conn
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL Statement
        try {
            $searchTerm = "%" . $searchTerm . "%";
            $stmt = $db_conn->prepare("SELECT * FROM `UserProfile` WHERE `role` LIKE :term OR `description` LIKE :term");
            $stmt->bindParam(':term', $searchTerm);
            $execResult = $stmt->execute();
            unset($db_handle); // Disconnect DB Conn

            // Search Success ?
            if ($execResult) {
                return $stmt->fetchAll(PDO::FETCH_CLASS, 'UserProfile');
            } else {
                return null;
            }
        } catch (PDOException $e) {
            error_log("Database search failed: " . $e->getMessage());
            return null;
        }
    }

    // Accessor  Methods
    public function getId(): int { return $this->id; }
    public function getRole(): string { return $this->role; }
    public function getDescription(): string { return $this->description; }
    public function getSuspendStatus(): int { return $this->isSuspend; }

}
?>