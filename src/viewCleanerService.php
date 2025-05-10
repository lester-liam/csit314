<?php

// Start the session (if not already started)
session_start();

// Include the controller
require_once 'controllers/ViewAllCleanerServiceController.php';
require_once 'controllers/SearchCleanerServiceController.php';

// Ensure User is Logged In
if (!isset($_SESSION['id']) && !isset($_SESSION['username']) && !isset($_SESSION['userProfile'])) {
    header("Location: login.php");
    exit();
}

// Ensure User is Cleaner
if ($_SESSION['userProfile'] != "Cleaner") {
     header("Location: login.php");
     exit();
}

$cleanerID = (int) $_SESSION['id']; // Cleaner ID

// Retrieve all Cleaner Services with Controller
$controller = new ViewAllCleanerServiceController();

// Get all user accounts
$cleanerService = $controller->ViewAllCleanerService($cleanerID);

if (isset($_GET['q'])) {

  if ($_GET['q'] != '') {

    // Remove quotes (both single and double)
    $searchTermWithoutQuotes = str_replace(['"', "'"], '', $_GET['q']);

    // Decode URL-encoded characters, including %20 for spaces
    $searchTerm = urldecode($searchTermWithoutQuotes);

    // Remove 2 or more consecutive whitespaces
    $searchTerm = preg_replace('/\s{2,}/', ' ', $searchTerm);

    // Remove trailing whitespaces
    $searchTerm = ltrim($searchTerm);
    $searchTerm = rtrim($searchTerm);

    // Instantiate the search controller
    $controller = new SearchCleanerServiceController();

    // Search Cleaner Services
    $cleanerService = $controller->SearchCleanerService($cleanerID, $searchTerm);
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>My Services</title>

  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/modal.css">



</head>

<body>

  <!-- Navbar -->
  <div class="navbar">
    <div class="navbar-left">
      <img src='img/cleaning-logo.png' alt="Cleaning Logo" width='48px' height='48px'/>
      <a href="viewCleanerService.php" class="active">My Services</a>
      <a href="viewMatches.php">My Matches</a>
    </div>
    <div class="navbar-right">
      <span class="navbar-right-text">Logged in as,<br/>(<?php echo htmlspecialchars($_SESSION["userProfile"]); ?>) <?php echo htmlspecialchars($_SESSION["username"]); ?></span>
      <button class="logout-button" onclick="window.location.href='logout.php'">Logout</button>
    </div>
  </div>

  <!-- Headline -->
  <h1>My Services</h1>

  <div class="section-container">
    <div class="search">
      <div class="search-group">
        <div class="input-wrapper">
          <ion-icon name="search-outline"></ion-icon>
          <input type="text" id="search_term" type="text" placeholder="Search..." value=<?php if (isset($_GET['q'])) { echo $_GET['q']; } ?>>
        </div>
        <button onclick='searchBtnClicked()' class="search-button">Search</button>
      </div>
      <button onclick='window.location.href="selectCleanerServiceCategory.php"' class="create-button">
        <ion-icon name="add-outline"></ion-icon>
        Create
      </button>
    </div>

    <!-- User Table -->
    <table class="display-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Service Category</th>
          <th>Service Name</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($cleanerService as $cs): ?>
          <tr>
              <td><?php echo htmlspecialchars($cs->getId()); ?></td>
              <td><?php echo htmlspecialchars($cs->getCategory()); ?></td>
              <td><?php echo htmlspecialchars($cs->getServiceName()); ?></td>
              <td>
                <button id='viewCleanerBtn' class="view-button" onclick="getCleanerService(<?php echo $cs->getId(); ?>, <?php echo $cleanerID; ?>)"><ion-icon name="eye-outline"></ion-icon>View</button>
                <button class="view-button" onclick='window.location.href="updateCleanerService.php?id=<?php echo htmlspecialchars($cs->getId()); ?>"'><ion-icon name="create-outline"></ion-icon>Update</button>
              </td>
          </tr>
      <?php endforeach; ?>
      </tbody>
    </table>

    <!-- The Modal -->
    <div id="CleanerServiceModal" class="modal">

      <!-- Modal content -->
      <div class="modal-content">
        <div class="modal-header">
          <h2>Service Info</h2>
          <button class="close">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="id">ID:</label>
            <input type="text" id="id" disabled>
          </div>
          <div class="form-group">
            <label for="category">Service Category</label>
            <input type="text" id="category" disabled>
          </div>
          <div class="form-group">
            <label for="serviceName">Service Name</label>
            <input type="text" id="serviceName" disabled>
          </div>
          <div class="form-group">
            <label for="price">Price</label>
            <input type="number" id="price" disabled>
          </div>
          <div class="form-group">
            <label for="numViews">Number of Views</label>
            <input type="number" id="numViews" disabled>
          </div>
          <div class="form-group">
            <label for="numShortlists">Number of Shortlists</label>
            <input type="number" id="numShortlists" disabled>
          </div>
        </div>
      </div>
    </div>

  </div>

  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

  <script>

    function searchBtnClicked() {
      var searchTermInput = document.getElementById("search_term");
      var searchTerm = searchTermInput.value;

      // Alphanumeric & Single Whitespace Regex
      searchTerm = searchTerm.replace(/[^a-zA-Z0-9\s]+/g, '');
      searchTerm = searchTerm.replace(/\s+/g, ' ');

      window.location.href = 'viewCleanerService.php?q="' + searchTerm + '"';
    }

    const modal = document.getElementById("CleanerServiceModal");
    const closeBtn = document.getElementsByClassName("close")[0];

    // Close Modal onClick
    closeBtn.onclick = function() {
      modal.style.display = "none";
    }

    function getCleanerService(id, cleanerID) {

      // Modal
      const CleanerServiceModal = document.getElementById("CleanerServiceModal");

      // Use Fetch
      fetch(`./controllers/ViewCleanerServiceController.php?id=${id}&cleanerID=${cleanerID}&includeMetrics=1`)
            .then(response => response.json())
            .then(data => {

              // Use Reponse Data to Populate Fields
              document.getElementById('id').value = data.id;
              document.getElementById('category').value = data.category;
              document.getElementById('serviceName').value = data.serviceName;
              document.getElementById('price').value = data.price;
              document.getElementById('numViews').value = data.numViews;
              document.getElementById('numShortlists').value = data.numShortlists;

        CleanerServiceModal.style.display = "block";
      })
      .catch(error => {
        console.error("Error fetching service data:", error);
        alert(error.message)
      });

    }

  </script>

</body>
</html>