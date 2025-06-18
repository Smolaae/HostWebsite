<?php


$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = htmlspecialchars(trim($_POST["name"]));
    $email   = htmlspecialchars(trim($_POST["email"]));
    $message = htmlspecialchars(trim($_POST["message"]));

    if (empty($name) || empty($email) || empty($message)) {
        $error = "Veuillez remplir tous les champs.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Adresse email invalide.";
    } else {
        // Envoi par mail (à remplacer par ta propre logique si tu préfères stocker en BDD)
        $to      = "tonemail@example.com"; // remplace avec ton adresse
        $subject = "Nouveau message de contact";
        $body    = "Nom: $name\nEmail: $email\nMessage:\n$message";

        if (mail($to, $subject, $body)) {
            $success = "Message envoyé avec succès !";
        } else {
            $error = "Une erreur est survenue. Veuillez réessayer plus tard.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <!-- tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Contact</title>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {"50":"#eff6ff","100":"#dbeafe","200":"#bfdbfe","300":"#93c5fd","400":"#60a5fa","500":"#3b82f6","600":"#2563eb","700":"#1d4ed8","800":"#1e40af","900":"#1e3a8a","950":"#172554"}
                    }
                },
                fontFamily: {
                    'body': [
                        'Inter', 
                        'ui-sans-serif', 
                        'system-ui', 
                        '-apple-system', 
                        'system-ui', 
                        'Segoe UI', 
                        'Roboto', 
                        'Helvetica Neue', 
                        'Arial', 
                        'Noto Sans', 
                        'sans-serif', 
                        'Apple Color Emoji', 
                        'Segoe UI Emoji', 
                        'Segoe UI Symbol', 
                        'Noto Color Emoji'
                    ],
                    'sans': [
                        'Inter', 
                        'ui-sans-serif', 
                        'system-ui', 
                        '-apple-system', 
                        'system-ui', 
                        'Segoe UI', 
                        'Roboto', 
                        'Helvetica Neue', 
                        'Arial', 
                        'Noto Sans', 
                        'sans-serif', 
                        'Apple Color Emoji', 
                        'Segoe UI Emoji', 
                        'Segoe UI Symbol', 
                        'Noto Color Emoji'
                    ]
                }
            }
            @utility tab-* {
                 tab-size: --value(--tab-size-*);
            }
        }
    </script>
</head>
<body class ="bg-gray-700">
    <div class="grid max-w-screen-xl grid-cols-1 gap-8 px-8 py-16 mx-auto rounded-lg md:grid-cols-2 md:px-12 lg:px-16 xl:px-32">
	<div class="flex flex-col justify-between">
		<div class="space-y-2">
			<h2 class="text-4xl font-bold text-white leading-tight lg:text-5xl">Let's talk!</h2>
			<div class="text-white">Un problème ? Une question ? N'hésitez pas à nous contacter.</div>
		</div>
		<img src="assets/img/contact.svg" alt="" class="p-6 h-52 md:h-64">
	</div>
	<form novalidate="" class="space-y-6">
		<div>
			<label for="name" class="text-sm text-white">Prénom</label>
			<input id="name" type="text" placeholder="" class="w-full p-3 rounded dark:bg-gray-100">
		</div>
		<div> 
			<label for="email" class="text-sm text-white">Email</label>
			<input id="email" type="email" class="w-full p-3 rounded dark:bg-gray-100">
		</div>
		<div>
			<label for="message" class="text-sm text-white">Message</label>
			<textarea id="message" rows="3" class="w-full p-3 rounded dark:bg-gray-100"></textarea>
		</div>
		<button type="submit" class="w-full p-3 text-sm font-bold tracking-wide uppercase rounded dark:bg-violet-600 dark:text-gray-50">Send Message</button>
	</form>
</div>
    
</body>
</html>