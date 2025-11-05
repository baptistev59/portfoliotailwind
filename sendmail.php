<?php
// sendmail.php — Version AlwaysData (simplifiée, fiable)

// CONFIG
$to = "contact@alkhabir-wa.com"; 
$subject = "Portfolio — Nouveau message";

// Bloquer les robots (honeypot)
if (!empty($_POST['company'])) {
    http_response_code(400);
    exit("Bad request.");
}

// Vérifier méthode
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit("Méthode non autorisée.");
}

// Sécurisation
$name    = htmlspecialchars(trim($_POST['name'] ?? ''));
$email   = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$message = htmlspecialchars(trim($_POST['message'] ?? ''));

if ($name === '' || $email === '' || $message === '') {
    exit("❌ Tous les champs sont obligatoires.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit("❌ Adresse email invalide.");
}

// Corps du mail
$body = "Vous avez reçu un message via le portfolio :\n\n"
      . "Nom : $name\n"
      . "Email : $email\n\n"
      . "Message :\n$message\n";

// Entêtes
$headers  = "From: $name <$email>\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Envoi
if (mail($to, $subject, $body, $headers)) {
    header("Location: index.html?sent=1");
    exit;
} else {
    echo "❌ Une erreur est survenue, l'email n'a pas été envoyé.";
}
