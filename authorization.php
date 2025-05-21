<?php
session_start();

// Käsitellään uloskirjautuminen
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: adminpw.php");  // Vaihda tähän oma kirjautumissivusi
    exit;
}

// Tarkistetaan onko käyttäjä kirjautunut adminina
if (!isset($_SESSION['is_admin'])) {
    header("Location: adminpw.php");  // Jos ei ole, ohjataan kirjautumissivulle
    exit;
}
