<?php
$conn = new mysqli('localhost', 'root', '', 'login_system');
if ($conn->connect_error) {
    die("Databaseverbinding mislukt: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $password = isset($_POST['password']) ? trim($_POST['password']) : null;

    // Controleer op lege velden
    if (empty($email) || empty($password)) {
        echo "E-mail en wachtwoord zijn verplicht.";
        exit;
    }

    // Controleer of e-mailadres geldig is
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Voer een geldig e-mailadres in.";
        exit;
    }

    // Haal gehashte wachtwoord op
    $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    if ($stmt->fetch()) {
        // Verifieer wachtwoord
        if (password_verify($password, $hashed_password)) {
            echo "Welkom, $email!";
        } else {
            echo "Onjuiste inloggegevens.";
        }
    } else {
        echo "E-mailadres niet gevonden.";
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
    <title>Inloggen</title>
</head>
<body>
    <form method="post">
        <label>E-mail: <input type="email" name="email" required></label><br>
        <label>Wachtwoord: <input type="password" name="password" required></label><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
