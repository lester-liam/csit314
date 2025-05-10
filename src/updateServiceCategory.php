<?php

session_start();

require_once 'controllers/ViewServiceCategoryController.php';

// Check if User is Logged In
if (
    !isset($_SESSION['id']) &&
    !isset($_SESSION['username']) &&
    !isset($_SESSION['userProfile'])
) {
    header("Location: login.php");
    exit();
}

// Check if UserProfile is Valid
if ($_SESSION['userProfile'] != "Platform Management") {
    header("Location: login.php");
    exit();
}

// Check if GET['id' Parameter Exists
if (isset($_GET['id'])) {
    // Get Service Category
    $controller = new ViewServiceCategoryController();
    $serviceCategory = $controller->readServiceCategory($_GET['id']);
} else {
    // Return to Home Page
    header("Location: viewServiceCategories.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Update Service Category</title>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <!-- Navbar -->
  <div class="navbar">
    <div class="navbar-left">
      <img src='img/cleaning-logo.png' alt="Cleaning Logo" width='48px' height='48px'/>
      <a href="viewServiceCategory.php" class="active">Services</a>
      <a href="#">Report</a>
    </div>
    <div class="navbar-right">
      <span class="navbar-right-text">Logged in as,<br/>
        (<?php echo htmlspecialchars($_SESSION["userProfile"]); ?>)
        <?php echo htmlspecialchars($_SESSION["username"]); ?>
      </span>
      <button class="logout-button" onclick="window.location.href='logout.php'">Logout</button>
    </div>
  </div>

  <!-- Update Form -->
  <div class="form-container">
    <h2>Update Service Category</h2>
    <br>
    <form action="controllers/UpdateServiceCategoryController.php" method="post">
      <?php if (isset($_GET['status']) && $_GET['status'] == 0) { ?>
        <div class="alert-danger" role="alert">
          <strong>Update Failed: Please Try Again</strong>
        </div>
      <?php } else if (isset($_GET['status']) && $_GET['status'] == 1) { ?>
        <div class="alert-success" role="alert">
          <strong>Service Category Updated Successfully</strong>
        </div>
      <?php } ?>
      <div class="form-group">
        <label for="id">ID:</label>
        <input type="text"
               id="id"
               name="id"
               value="<?php echo htmlspecialchars($serviceCategory->getId()); ?>" readonly>
      </div>
      <div class="form-group">
        <label for="category">Category:</label>
        <input type="text"
               id="category"
               name="category"
               value="<?php echo htmlspecialchars($serviceCategory->getCategory()); ?>" required>
        <span id='categoryValidation' class='text-danger'></span>
      </div>
      <div class="form-group">
        <label for="description">Description:</label>
        <input type="text"
               id="description"
               name="description"
               value="<?php echo htmlspecialchars($serviceCategory->getDescription()); ?>">
        <span id='descriptionValidation' class='text-danger'></span>
      </div>
      <div class="submit-row">
        <button type="button"
                class="back-button"
                onclick='window.location.href="viewServiceCategory.php"'>
          Back
        </button>
        <button type="submit" id="submit-button" class="submit-button">Update</button>
      </div>
    </form>
  </div>

  <!-- Javascript -->
  <script>
    // Form Validation
    const form = document.querySelector("form");
    document.getElementById("submit-button").addEventListener("click", function (event) {
      event.preventDefault();
      let isValid = true;

      // Category Validation (Trimmed Field must be non-empty)
      const category = document.getElementById("category").value.trim();
      if (!category) {
        document.getElementById("categoryValidation").innerText = "Category cannot be empty.";
        isValid = false;
      } else {
        document.getElementById("categoryValidation").innerText = "";
      }

      // Description Validation (Trim Field)
      const descriptionInput = document.getElementById("description");
      const description = document.getElementById("description").value.trim();
      if (!description) {
        descriptionInput.value = "";
      }

      // Prompt Confirmation & Submit Form
      if (isValid) {
        if (confirm("Confirm Create Service Category?")) {
          form.submit();
        }
      }
    });
  </script>
</body>
</html>