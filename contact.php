<?php
// Formulaire de contact de La Cerise sur le Site
// Envoie le message à bonjour@lacerisesurlesite.fr puis revient sur la page avec un message de confirmation.

$destinataire = 'bonjour@lacerisesurlesite.fr';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit;
}

// Piège à robots : ce champ est invisible pour les humains
if (!empty($_POST['site_web'])) {
    header('Location: index.html?envoye=1#contact');
    exit;
}

function nettoie($v) {
    return trim(str_replace(["\r", "\n"], ' ', strip_tags($v ?? '')));
}

$prenom   = nettoie($_POST['prenom'] ?? '');
$email    = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$activite = nettoie($_POST['activite'] ?? '');
$message  = trim(strip_tags($_POST['message'] ?? ''));

if ($prenom === '' || !$email || $message === '') {
    header('Location: index.html#contact');
    exit;
}

$sujet = 'Nouveau projet : ' . $prenom . ($activite ? ' (' . $activite . ')' : '');
$corps = "Prénom : $prenom\nEmail : $email\nActivité : $activite\n\nProjet :\n$message\n";
$entetes = "From: La Cerise sur le Site <$destinataire>\r\n"
         . "Reply-To: $email\r\n"
         . "Content-Type: text/plain; charset=UTF-8\r\n";

mail($destinataire, '=?UTF-8?B?' . base64_encode($sujet) . '?=', $corps, $entetes);

header('Location: index.html?envoye=1#contact');
exit;
