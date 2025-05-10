<?php
require_once('Database.php');

class UserAccount {
    protected int $id;
    protected string $username;
    protected string $password;
    protected string $fullName;
    protected string $email;
    protected string $phone;
    protected string $userProfile;
    protected int $isSuspend;

    // CRUD Operations //

    public function createUserAccount(string $username, string $password, string $fullName, string $email, string $phone, string $userProfile): bool {
    /*  Inserts New User Account:
        $username: string
        $password: string
        $fullName: string
        $email: string
        $phone: string
        $userProfile: string

        Returns: Boolean
    */

        // New DB Conn
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // Hash Password with MD5
        $passwd = md5($password);

        // SQL TryCatch Statement
        try {

            $stmt = $db_conn->prepare("INSERT INTO `UserAccount` VALUES (null, :username, :password, :fullName, :email, :phone, :userProfile, 0)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $passwd);
            $stmt->bindParam(':fullName', $fullName);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':userProfile', $userProfile);
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

    public function readUserAccount(int $id): ?UserAccount {
    /*  Select User Account By ID
        $id: int

        Returns: Single UserAccount (nullable)
    */

        // New DB Conn
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL TryCatch Statement
        try {
            $stmt = $db_conn->prepare("SELECT * FROM `UserAccount` WHERE `id` = :id");
            $stmt->bindParam(':id', $id);
            $execResult = $stmt->execute();

            unset($db_handle); // Disconnect DB Conn

            // execute() Success?
            if ($execResult) {
                $userAccount = $stmt->fetchObject('UserAccount');
                return $userAccount;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            error_log("Database query failed: " . $e->getMessage());
            unset($db_handle);
            return null;
        }

    }

    public function readAllUserAccount(): ?array {
    /*  Select All User Account

        Returns: Array of UserAccounts (nullable)
    */

        // New DB Conn
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL TryCatch Statement
        try {
            $stmt = $db_conn->prepare("SELECT * FROM `UserAccount`");
            $execResult = $stmt->execute();
            unset($db_handle); // Disconnect DB Conn

            // execute() Success?
            if ($execResult) {
                // Execute was successful, now fetch the data
                $userAccounts = $stmt->fetchAll(PDO::FETCH_CLASS, 'UserAccount');
                return $userAccounts;
            } else {
                return null;
            }

        } catch (PDOException $e) {
            error_log("Database query failed: " . $e->getMessage());
            unset($db_handle);
            return null;
        }

    }

    public function updateUserAccount(int $id, string $username, ?string $password, string $fullName, string $email, string $phone, string $userProfile): bool {
    /*  Updates a User Account:
        If password is NULL, attribute will not be updated
        Otherwise, it will be hashed (MD5) and updated

        $id: int
        $username: string
        $password: string (nullable)
        $fullName: string
        $email: string
        $phone: string
        $userProfile: string

        Returns: Boolean
    */

        // New DB Conn
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL TryCatch Statement
        try {
            $sql = "UPDATE `UserAccount` SET `username` = :username, `fullName` = :fullName, `email` = :email, `phone` = :phone, `userProfile` = :userProfile WHERE `id` = :id";

            // Checks if Password is NULL
            if (!is_null($password)) {
                $sql = "UPDATE `UserAccount` SET `username` = :username, `password` = :password, `fullName` = :fullName, `email` = :email, `phone` = :phone, `userProfile` = :userProfile WHERE `id` = :id";
            }

            $stmt = $db_conn->prepare($sql);

            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':fullName', $fullName);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':userProfile', $userProfile);

            if (!is_null($password)) {
                $passwd = md5($password); # Hash Password
                $stmt->bindParam(':password', $passwd);
            }

            $execResult = $stmt->execute();

            unset($db_handle); // Disconnect DB Conn

            // Update Success ?
            if ($execResult) {
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (PDOException $e) {
            error_log("Database Update failed: " . $e->getMessage());
            unset($db_handle);
            return FALSE;
        }

    }

    public function suspendUserAccount(int $id): bool {
    /*  Suspends a User Account:
        $id: int
        Returns: Boolean
    */

        // New DB Conn
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL TryCatch Statement
        try {
            $stmt = $db_conn->prepare("UPDATE `UserAccount` SET `isSuspend` = 1 WHERE `id` = $id");

            $execResult = $stmt->execute();

            unset($db_handle); // Disconnect DB Conn

            // Update Success ?
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

    public function searchUserAccount($searchTerm): ?array {
    /*  Searches for User Account(s):
        $searchTerm: string
        Returns: Array of UserAccounts (nullable)
    */

        // New DB Conn
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL Statement
        try {
            $searchTerm = "%" . $searchTerm . "%";
            $stmt = $db_conn->prepare("SELECT * FROM `UserAccount` WHERE `username` LIKE :term OR `fullName` LIKE :term OR `email` LIKE :term OR `phone` LIKE :term OR `userProfile` LIKE :term");
            $stmt->bindParam(':term', $searchTerm);
            $execResult = $stmt->execute();
            unset($db_handle); // Disconnect DB Conn

            // Search Success ?
            if ($execResult) {
                return $stmt->fetchAll(PDO::FETCH_CLASS, 'UserAccount');
            } else {
                return null;
            }
        } catch (PDOException $e) {
            error_log("Database search failed: " . $e->getMessage());
            return null;
        }
    }


    public function login(string $username, string $password, string $userProfile): ?UserAccount {
    /*  Login (Authenticate) UserAccount
        Checks for UserAccount (UA) Exists, Authenticate Password,
        Then Checks if UserProfile or UA  is Suspended

        $username: string
        $password: string
        $userProfiel: string

        Return: UserAccount (nullable)
    */

        // New DB Conn
        $db_handle = new Database();
        $db_conn = $db_handle->getConnection();

        // SQL Statement (+ Checks user profile isSuspend status)
        // Returns NULL if Invalid Password / No Users Found
        try {
            $stmt = $db_conn->prepare("SELECT * FROM `UserAccount` WHERE `username` = :username AND `userProfile` = :userProfile");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':userProfile', $userProfile);

            $stmt->execute();

            // Ensure Only 1 Row
            if ($stmt->rowCount() == 1) {

                $userAccount = $stmt->fetchObject('UserAccount');

                // Verify Password
                if (md5($password) == $userAccount->getPassword()) {
                    $sql = "SELECT `isSuspend` FROM `UserProfile` WHERE `role` = '$userProfile'";
                    $stmt = $db_conn->query($sql);
                    $suspendStatus = $stmt->fetch(PDO::FETCH_ASSOC);

                    // Update User Object Suspend Status if User Profile is Suspended
                    if ($suspendStatus) {
                        if ($suspendStatus['isSuspend'] == 1) {
                            $userAccount->updateIsSuspended(1);
                        }
                    }

                    unset($db_handle);

                    return $userAccount;
                } else {
                    return null;
                }

            } else {
                unset($db_handle);
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
    public function getUsername(): string { return $this->username; }
    public function getPassword(): string { return $this->password; }
    public function getFullName(): string { return $this->fullName; }
    public function getEmail(): string { return $this->email; }
    public function getPhone(): string { return $this->phone; }
    public function getUserProfile(): string { return $this->userProfile; }
    public function getSuspendStatus(): int { return $this->isSuspend; }

    // Mutator Methods (only Suspend Status is Updated for Object)
    protected function updateIsSuspended(int $s): void {
        // Update if Valid, Else Use Default (assume User is Suspended)
        if ($s == 0 | $s == 1) {
            $this->isSuspend = $s;
        } else {
            error_log("Update UserAccount isSuspend Failed (Did not Update)");
        }
    }
}

?>