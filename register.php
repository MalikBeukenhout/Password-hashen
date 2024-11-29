<?php
$conn = new mysqli('localhost', 'root', '', 'login_system');
if ($conn->connect_error) {
    die("Databaseverbinding mislukt: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $password = isset($_POST['password']) ? trim($_POST['password']) : null;
    $confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : null;

    // Controleer op lege velden
    if (empty($email) || empty($password) || empty($confirm_password)) {
        echo "Alle velden zijn verplicht.";
        exit;
    }

    // Controleer of wachtwoorden overeenkomen
    if ($password !== $confirm_password) {
        echo "Wachtwoorden komen niet overeen.";
        exit;
    }

    // Controleer of e-mailadres geldig is
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Voer een geldig e-mailadres in.";
        exit;
    }

    // Controleer of e-mail al bestaat
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo "Dit e-mailadres is al geregistreerd.";
        exit;
    }
    $stmt->close();

    // Hash wachtwoord met try-catch
    try {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        if (!$hashed_password) {
            throw new Exception("Er is een fout opgetreden bij het hashen van het wachtwoord.");
        }
    } catch (Exception $e) {
        echo $e->getMessage();
        exit;
    }

    // Sla gebruiker op in de database
    $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $hashed_password);
    if ($stmt->execute()) {
        echo "Registratie voltooid! U kunt nu inloggen.";
    } else {
        echo "Er is een fout opgetreden bij het opslaan van uw gegevens.";
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreren</title>
</head>
<body>
    <form method="post">
        <label>E-mail: <input type="email" name="email" required></label><br>
        <label>Wachtwoord: <input type="password" name="password" required></label><br>
        <label>Bevestig Wachtwoord: <input type="password" name="confirm_password" required></label><br>
        <button type="submit">Registreer</button>
    </form>
</body>
</html>
