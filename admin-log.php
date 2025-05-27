  <?php
  session_start();

  // Jos ei admin, ohjataan takaisin kirjautumissivulle
  if (!isset($_SESSION['is_admin'])) {
    header("Location: adminpw.php");
    exit;
  }

  // Käsittele uloskirjautuminen
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy();
    header("Location: adminpw.php");
    exit;
  }

  include "admin-banner.php"


  ?>

  <?php
  if (isset($_SESSION['is_admin'])) {
  ?>
    <form method="POST">
      <button class="logout-button" type="submit" name="logout">Log out</button>
    </form>
  <?php
  }
  ?>