# Password-hashen

Werking van het programma
Registreren:

Een gebruiker voert een e-mailadres en wachtwoord in.
Het wachtwoord wordt gehasht met password_hash() en opgeslagen in de database samen met het e-mailadres.
Inloggen:

Een gebruiker voert zijn e-mailadres en wachtwoord in.
Het ingevoerde wachtwoord wordt vergeleken met de opgeslagen hash met password_verify().

Database instellen
Maak een nieuwe database aan:
CREATE DATABASE login_system;

Maak de benodigde tabel:
CREATE TABLE login_system.users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

Bestanden toevoegen
Voeg register.php en login.php toe aan de root van je webserver.
Zorg ervoor dat de PHP-bestanden verbinding maken met jouw database. Pas indien nodig de verbindingsinstellingen aan:
$conn = new mysqli('localhost', 'root', '', 'login_system');

Ga naar http://localhost/register.php en registreer een nieuwe gebruiker.
Ga naar http://localhost/login.php en probeer in te loggen met dezelfde gegevens.


Kun je het oorspronkelijke wachtwoord uit de hash achterhalen?
Nee want de originele invoer kan niet worden hersteld van de Hash.

Maken de functies ( password_hash() of bcrypt ) gebruik van een salt?
Password_hash en bcrypt gebruiken standaart de functies salt in PHP.
