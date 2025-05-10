<?php

session_start();

require_once 'controllers/SearchMatchesController.php';
require_once 'controllers/ViewMatchesController.php';

// User is Logged In
if (
    !isset($_SESSION['id']) &&
    !isset($_SESSION['username']) &&
    !isset($_SESSION['userProfile'])
) {
    header("Location: login.php");
    exit();
}

// UserProfile is Valid
if ($_SESSION['userProfile'] != "Cleaner") {
    header("Location: login.php");
    exit();
}

// Initialize Cleaner ID
$cleanerID = (int) $_SESSION['id'];

// Retrieve GET Variables
if (
    isset($_GET['searchTerm']) &&
    isset($_GET['category']) &&
    isset($_GET['dateOption'])
) {
    $controller = new SearchMatchesController();

    // Remove Quotes & Decode URL (Eg. %20 for Whitespaces)
    $searchTermWithoutQuotes = str_replace(['"', "'"], '', $_GET['searchTerm']);
    $searchTerm = urldecode($searchTermWithoutQuotes);

    // Remove 2 or more consecutive whitespaces
    $searchTerm = preg_replace('/\s{2,}/', ' ', $searchTerm);

    // Remove trailing whitespaces
    $searchTerm = ltrim($searchTerm);
    $searchTerm = rtrim($searchTerm);

    $categoryWithoutQuotes = str_replace(['"', "'"], '', $_GET['category']);
    $category = urldecode($categoryWithoutQuotes);

    // Handle Empty Cases
    if (empty($category)) {
      $searchTerm = "";
    }

    if (empty($category)) {
      $category = "";
    }

    // Convert dateOption to integer
    $dateOption = (int) $_GET['dateOption'];

    $matches = $controller->searchMatches(
                                $cleanerID,
                                $searchTerm,
                                $category,
                                $dateOption
                            );
} else {
    // Invalid Search (Default to All Results)
    $controller = new ViewMatchesController();
    $matches = $controller->ViewMatches($cleanerID);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>My Services</title>
  <!-- Import CSS Stylesheet -->
  <link rel="stylesheet" href="css/modal.css">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <!-- Navbar -->
  <div class="navbar">
    <div class="navbar-left">
      <img src='img/cleaning-logo.png' alt="Cleaning Logo" width='48px' height='48px'/>
      <a href="viewCleanerService.php">My Services</a>
      <a href="viewMatches.php" class="active">My Matches</a>
    </div>
    <div class="navbar-right">
      <span class="navbar-right-text">Logged in as,<br/>
        (<?php echo htmlspecialchars($_SESSION["userProfile"]); ?>)
        <?php echo htmlspecialchars($_SESSION["username"]); ?>
      </span>
      <button class="logout-button" onclick="window.location.href='logout.php'">Logout</button>
    </div>
  </div>

  <!-- Headline -->
  <h1>My Services</h1>
  <div class="section-container">
    <div class="search">
      <div class="search-group">
        <div class="input-wrapper">
          <input type="hidden">
        </div>
      </div>
      <button onclick='window.location.href="viewMatches.php"' class="create-button">
        <ion-icon name="return-down-back-outline"></ion-icon>
        Back
      </button>
    </div>

    <!-- Table -->
    <table class="display-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Customer Name</th>
          <th>Service Category</th>
          <th>Completion Date</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($matches as $m): ?>
          <tr>
            <td><?php echo htmlspecialchars($m->getId()); ?></td>
            <td><?php echo htmlspecialchars($m->getName()); ?></td>
            <td><?php echo htmlspecialchars($m->getCategory()); ?></td>
            <td><?php echo htmlspecialchars($m->getServiceDate()); ?></td>
            <td>
              <button
                class="view-button"
                onclick="viewMatch(
                          '<?php echo $m->getCategory(); ?>',
                          '<?php echo $m->getName(); ?>',
                          '<?php echo $m->getServiceDate(); ?>')">
              <ion-icon name="eye-outline"></ion-icon>
              View
              </button>
            </td>
          </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Modal -->
  <div id="ViewMatchModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>History Info</h2>
        <button class="close">&times;</button>
      </div>
      <!-- Form Group Displaying Match History Record -->
      <div class="modal-body">
      <div class="form-group">
          <label for="viewCustName">Customer Name</label>
          <input type="text" id="viewCustName" disabled>
        </div>
        <div class="form-group">
          <label for="viewCategory">Service Category</label>
          <input type="text" id="viewCategory" disabled>
        </div>
        <div class="form-group">
          <label for="viewDate">Service Completion Date</label>
          <input type="text" id="viewDate" disabled>
        </div>
      </div>
    </div>
  </div>

  <!-- JavaScript -->
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <script>
    // Get Modal & Close Buttons Elemnts
    const modal = document.getElementById("ViewMatchModal");
    const modal2 = document.getElementById("SearchMatchesModal");
    const closeBtn = document.getElementsByClassName("close")[0];
    const closeBtn1 = document.getElementsByClassName("close")[1];

    // Close Modal onClick Event
    closeBtn.onclick = function() {
      modal.style.display = "none";
    }

    closeBtn1.onclick = function() {
      modal2.style.display = "none";
    }

    // Display ViewMatchModal onClick
    function viewMatch(category, custName, serviceDate) {
      // Set Modal Values
      document.getElementById('viewCustName').value = custName;
      document.getElementById('viewCategory').value = category;
      document.getElementById('viewDate').value = serviceDate;

      // Display Modal
      const viewModal = document.getElementById("ViewMatchModal");
      viewModal.style.display = "block";
    }
  </script>
</body>
</html>