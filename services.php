<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laehost services</title>
    <link rel="stylesheet" href="./assets/css/style.css">
     <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
     <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#8a2be2',
                        secondary: '#6a11cb',
                        dark: {
                            DEFAULT: '#121212',
                            light: '#1e1e1e',
                            lighter: '#2d2d2d'
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-dark-light text-white">
    <header class="bg-dark shadow-md p-4">
         <div class="container mx-auto px-4 py-6">
            <nav class="flex justify-between items-center">
                <div class="flex items-center">
                    <i class="fas fa-server text-primary text-2xl mr-2"></i>
                    <h1 class="text-2xl font-bold text-start">Nos Services</h1>
                </div>
                <div class=" flex justify-end space-x-4 mt-2">
                    <a href="index.php" class="hover:text-primary transition-colors">Accueil</a>
                    <a href="login.php" class="hover:text-primary transition-colors">Espace Client</a>
                </div>
            </nav>
        </div>
    </header>
     <!-- Game Servers Section -->
    <section id="games" class="py-20 bg-gradient-to-br from-dark via-dark-light to-secondary">
        <div class="container mx-auto px-4 animate-on-scroll">
            <h2 data-i18n="game-servers" class="text-3xl md:text-4xl font-bold mb-16 text-center">
                Serveurs de Jeux <span class="text-primary">Optimisés</span>
            </h2>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- FiveM -->
                <div class="bg-dark-light rounded-lg overflow-hidden shadow-lg transition-transform hover:-translate-y-1 hover:shadow-xl">
                    <div class="h-48 bg-dark-lighter relative">
                        <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent animate-on-scroll">
                            <img src="./assets/img/fivem.jpg" alt="Garry's Mod" class="w-full h-full object-cover opacity-50">
                        </div>
                        <div class="absolute bottom-4 left-4 text-2xl font-bold">FiveM</div>
                    </div>
                    <div class="p-6">
                        <h3 data-i18n="serveurfivem" class="text-xl font-bold mb-2">Serveurs FiveM</h3>
                        <p data-i18n="serveurfivem-desc" class="text-gray-400 mb-6">Hébergez votre serveur GTA RP avec une performance optimale</p>
                        <ul class="space-y-2 mb-6">
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span data-i18n="fivem-installation">Installation automatique</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span data-i18n="fivem-ressources">Ressources optimisées</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span data-i18n="fivemFTP">Accès FTP</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span data-i18n="fivem-sql">Base de données MySQL</span>
                            </li>
                        </ul>
                    </div>
                    <div class="px-6 py-4 border-t border-dark-lighter">
                        <a data-i18n="fivemprice" href="./fivem.html" class="block w-full bg-primary hover:bg-purple-700 text-white text-center py-3 rounded-md font-semibold transition-colors">
                            À partir de 15€/mois
                        </a>
                    </div>
                </div>

                <!-- Minecraft -->
                <div class="bg-dark-light rounded-lg overflow-hidden shadow-lg transition-transform hover:-translate-y-1 hover:shadow-xl">
                    <div class="h-48 bg-dark-lighter relative">
                        <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent animate-on-scroll">
                            <img src="./assets/img/minecraft.jpg" alt="Garry's Mod" class="w-full h-full object-cover opacity-50">
                        </div>
                        <div class="absolute bottom-4 left-4 text-2xl font-bold">Minecraft</div>
                    </div>
                    <div class="p-6">
                        <h3 data-i18n="minecraft-servers" class="text-xl font-bold mb-2">Serveurs Minecraft</h3>
                        <p data-i18n="minecraft-description" class="text-gray-400 mb-6">Java ou Bedrock, serveurs optimisés pour tous vos besoins</p>
                        <ul class="space-y-2 mb-6">
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span data-i18n="minecraft-pterodactyl">Panneau de contrôle Pterodactyl</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span data-i18n="minecraft-installation">Installation de mods facile</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span data-i18n="minecraft-sauvegarde">Sauvegardes automatiques</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span data-i18n="minecraft-support">Support pour plugins</span>
                            </li>
                        </ul>
                    </div>
                    <div class="px-6 py-4 border-t border-dark-lighter">
                        <a data-i18n="minecraftprice" href="minecraft.html" class="block w-full bg-primary hover:bg-purple-700 text-white text-center py-3 rounded-md font-semibold transition-colors">
                            À partir de 8€/mois
                        </a>
                    </div>
                </div>

                <!-- GMod -->
                <div class="bg-dark-light rounded-lg overflow-hidden shadow-lg transition-transform hover:-translate-y-1 hover:shadow-xl">
                    <div class="h-48 bg-dark-lighter relative">
                        <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent animate-on-scroll">
                        <img src="./assets/img/gmod.jpg" alt="Garry's Mod" class="w-full h-full object-cover opacity-50">
                        </div>
                        <div class="absolute bottom-4 left-4 text-2xl font-bold">GMod</div>
                    </div>
                    <div class="p-6">
                        <h3 data-i18n="gmod-servers" class="text-xl font-bold mb-2">Serveurs Garry's Mod</h3>
                        <p data-i18n="gmod-description" class="text-gray-400 mb-6">Hébergez vos modes de jeu préférés avec une performance optimale</p>
                        <ul class="space-y-2 mb-6">
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span data-i18n="gmod-installation">Installation de workshop facile</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span data-i18n="gmod-support">Support pour addons</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span data-i18n="gmod-Darkrp">Configuration DarkRP</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span data-i18n="gmod-accès">Accès FastDL</span>
                            </li>
                        </ul>
                    </div>
                    <div class="px-6 py-4 border-t border-dark-lighter">
                        <a data-i18n="gmodprice" href="gmod.html" class="block w-full bg-primary hover:bg-purple-700 text-white text-center py-3 rounded-md font-semibold transition-colors">
                            À partir de 12€/mois
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- VPS Section -->
    <section id="vps" class="py-20 bg-dark-light">
        <div class="container mx-auto px-4 animate-on-scroll">
            <h2 data-i18n="vps-servers" class="text-3xl md:text-4xl font-bold mb-16 text-center">
                Serveurs <span class="text-primary">VPS</span>
            </h2>

            <div class="max-w-4xl mx-auto animate-on-scroll">
                <div class="mb-8 grid grid-cols-2 rounded-md overflow-hidden">
                    <button data-i18n="linux-servers" class="tab-btn active py-4 text-center bg-dark-lighter font-semibold transition-colors" data-tab="linux">Linux VPS</button>
                    <button data-i18n="windows-servers" class="tab-btn py-4 text-center bg-dark-lighter font-semibold transition-colors" data-tab="windows">Windows VPS</button>
                </div>

                <div class="tab-content">
                    <div class="tab-pane active" id="linux">
                        <div class="grid md:grid-cols-3 gap-6">
                            <div class="bg-dark rounded-lg overflow-hidden shadow-lg">
                                <div class="p-6">
                                    <h3 class="text-xl font-bold mb-2">VPS Starter</h3>
                                    <div class="text-3xl font-bold text-primary mb-6">
                                        5€<span data-i18n="mois" class="text-sm text-gray-400">/mois</span>
                                    </div>
                                    <ul class="space-y-2 mb-6">
                                        <li class="flex items-center">
                                            <img src="./assets/img/icons8-electronics-50.png" alt="CPU" class="w-5 h-5 mr-2">
                                            <span>2 vCPU</span>
                                        </li>
                                        <li class="flex items-center">
                                            <img src="./assets/img/icons8-ram-49.png" alt="RAM" class="w-5 h-5 mr-2">
                                            <span>2 GB RAM</span>
                                        </li>
                                        <li class="flex items-center">
                                            <img src="./assets/img/icons8-ssd-50.png" alt="SSD" class="w-5 h-5 mr-2">
                                            <span>20 GB SSD</span>
                                        </li>
                                        <li class="flex items-center">
                                            <img src="./assets/img/icons8-wifi-50.png" alt="Bande passante" class="w-5 h-5 mr-2">
                                            <span class="mr-1">1 TB</span>
                                            <span data-i18n="bandepassante">Bande passante</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="px-6 py-4 border-t border-dark-lighter">
                                    <a data-i18n="commande" href="new_subscription.php" class="block w-full bg-gray-700 hover:bg-gray-600 text-white text-center py-3 rounded-md font-semibold transition-colors">
                                        Commander
                                    </a>
                                </div>
                            </div>

                            <div class="bg-dark rounded-lg overflow-hidden shadow-lg border-2 border-primary relative">
                                <div data-i18n="popular" class="absolute  left-1/2 -translate-x-1/2 bg-primary text-white px-4 py-1 rounded-full text-sm font-bold">
                                    Populaire
                                </div>
                                <div class="p-6">
                                    <h3 class="text-xl font-bold mb-2">VPS Pro</h3>
                                    <div class="text-3xl font-bold text-primary mb-6">
                                        15€<span data-i18n="mois" class="text-sm text-gray-400">/mois</span>
                                    </div>
                                    <ul class="space-y-2 mb-6">
                                        <li class="flex items-center">
                                            <img src="./assets/img/icons8-electronics-50.png" alt="CPU" class="w-5 h-5 mr-2">
                                            <span>4 vCPU</span>
                                        </li>
                                        <li class="flex items-center">
                                            <img src="./assets/img/icons8-ram-49.png" alt="RAM" class="w-5 h-5 mr-2">
                                            <span>8 GB RAM</span>
                                        </li>
                                        <li class="flex items-center">
                                            <img src="./assets/img/icons8-ssd-50.png" alt="SSD" class="w-5 h-5 mr-2">
                                            <span>80 GB SSD</span>
                                        </li>
                                        <li class="flex items-center">
                                            <img src="./assets/img/icons8-wifi-50.png" alt="Bande passante" class="w-5 h-5 mr-2">
                                            <span class="mr-1">3 TB</span>
                                            <span data-i18n="bandepassante"> Bande passante</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="px-6 py-4 border-t border-dark-lighter">
                                    <a href="new_subscription.php" data-i18n="commande" class="block w-full bg-primary hover:bg-purple-700 text-white text-center py-3 rounded-md font-semibold transition-colors">
                                        Commander
                                    </a>
                                </div>
                            </div>

                            <div class="bg-dark rounded-lg overflow-hidden shadow-lg">
                                <div class="p-6">
                                    <h3 class="text-xl font-bold mb-2">VPS Elite</h3>
                                    <div class="text-3xl font-bold text-primary mb-6">
                                        30€<span data-i18n="mois" class="text-sm text-gray-400">/mois</span>
                                    </div>
                                    <ul class="space-y-2 mb-6">
                                        <li class="flex items-center">
                                            <img src="./assets/img/icons8-electronics-50.png" alt="CPU" class="w-5 h-5 mr-2">
                                            <span>8 vCPU</span>
                                        </li>
                                        <li class="flex items-center">
                                            <img src="./assets/img/icons8-ram-49.png" alt="RAM" class="w-5 h-5 mr-2">
                                            <span>16 GB RAM</span>
                                        </li>
                                        <li class="flex items-center">
                                            <img src="./assets/img/icons8-ssd-50.png" alt="SSD" class="w-5 h-5 mr-2">
                                            <span>160 GB SSD</span>
                                        </li>
                                        <li class="flex items-center">
                                            <img src="./assets/img/icons8-wifi-50.png" alt="Bande passante" class="w-5 h-5 mr-2">
                                            <span class="mr-1"><span class="mr-1">5 TB</span>
                                            <span data-i18n="bandepassante">Bande passante</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="px-6 py-4 border-t border-dark-lighter">
                                    <a data-i18n="commande" href="new_subscription.php" class="block w-full bg-gray-700 hover:bg-gray-600 text-white text-center py-3 rounded-md font-semibold transition-colors">
                                        Commander
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane hidden" id="windows">
                        <div class="grid md:grid-cols-3 gap-6">
                            <div class="bg-dark rounded-lg overflow-hidden shadow-lg">
                                <div class="p-6">
                                    <h3 class="text-xl font-bold mb-2">Windows Starter</h3>
                                    <div class="text-3xl font-bold text-primary mb-6">
                                        10€<span data-i18n="mois" class="text-sm text-gray-400">/mois</span>
                                    </div>
                                    <ul class="space-y-2 mb-6">
                                        <li class="flex items-center">
                                            <img src="./assets/img/icons8-electronics-50.png" alt="CPU" class="w-5 h-5 mr-2">
                                            <span>2 vCPU</span>
                                        </li>
                                        <li class="flex items-center">
                                            <img src="./assets/img/icons8-ram-49.png" alt="RAM" class="w-5 h-5 mr-2">
                                            <span>4 GB RAM</span>
                                        </li>
                                        <li class="flex items-center">
                                            <img src="./assets/img/icons8-ssd-50.png" alt="SSD" class="w-5 h-5 mr-2">
                                            <span>40 GB SSD</span>
                                        </li>
                                        <li class="flex items-center">
                                            <img src="./assets/img/icons8-wifi-50.png" alt="Bande passante" class="w-5 h-5 mr-2">
                                            <span class="mr-1">1 TB</span>
                                            <span data-i18n="bandepassante">Bande passante</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="px-6 py-4 border-t border-dark-lighter">
                                    <a data-i18n="commande" href="new_subscription.php" class="block w-full bg-gray-700 hover:bg-gray-600 text-white text-center py-3 rounded-md font-semibold transition-colors">
                                        Commander
                                    </a>
                                </div>
                            </div>

                            <div class="bg-dark rounded-lg overflow-hidden shadow-lg border-2 border-primary relative">
                                <div data-i18n="popular" class="absolute left-1/2 -translate-x-1/2 bg-primary text-white px-4 py-1 rounded-full text-sm font-bold">
                                    Populaire
                                </div>
                                <div class="p-6">
                                    <h3 class="text-xl font-bold mb-2">Windows Pro</h3>
                                    <div class="text-3xl font-bold text-primary mb-6">
                                        25€<span data-i18n="mois" class="text-sm text-gray-400">/mois</span>
                                    </div>
                                    <ul class="space-y-2 mb-6">
                                        <li class="flex items-center">
                                            <img src="./assets/img/icons8-electronics-50.png" alt="CPU" class="w-5 h-5 mr-2">
                                            <span>4 vCPU</span>
                                        </li>
                                        <li class="flex items-center">
                                            <img src="./assets/img/icons8-ram-49.png" alt="RAM" class="w-5 h-5 mr-2">
                                            <span>8 GB RAM</span>
                                        </li>
                                        <li class="flex items-center">
                                            <img src="./assets/img/icons8-ssd-50.png" alt="SSD" class="w-5 h-5 mr-2">
                                            <span>100 GB SSD</span>
                                        </li>
                                        <li class="flex items-center">
                                            <img src="./assets/img/icons8-wifi-50.png" alt="Bande passante" class="w-5 h-5 mr-2">
                                            <span class="mr-1">3 TB</span>
                                            <span data-i18n="bandepassante">Bande passante</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="px-6 py-4 border-t border-dark-lighter">
                                    <a data-i18n="commande" href="new_subscription.php" class="block w-full bg-primary hover:bg-purple-700 text-white text-center py-3 rounded-md font-semibold transition-colors">
                                        Commander
                                    </a>
                                </div>
                            </div>

                            <div class="bg-dark rounded-lg overflow-hidden shadow-lg">
                                <div class="p-6">
                                    <h3 class="text-xl font-bold mb-2">Windows Elite</h3>
                                    <div class="text-3xl font-bold text-primary mb-6">
                                        45€<span data-i18n="mois" class="text-sm text-gray-400">/mois</span>
                                    </div>
                                    <ul class="space-y-2 mb-6">
                                        <li class="flex items-center">
                                            <img src="./assets/img/icons8-electronics-50.png" alt="CPU" class="w-5 h-5 mr-2">
                                            <span>8 vCPU</span>
                                        </li>
                                        <li class="flex items-center">
                                            <img src="./assets/img/icons8-ram-49.png" alt="RAM" class="w-5 h-5 mr-2">
                                            <span>16 GB RAM</span>
                                        </li>
                                        <li class="flex items-center">
                                            <img src="./assets/img/icons8-ssd-50.png" alt="SSD" class="w-5 h-5 mr-2">
                                            <span>200 GB SSD</span>
                                        </li>
                                        <li class="flex items-center">
                                            <img src="./assets/img/icons8-wifi-50.png" alt="Bande passante" class="w-5 h-5 mr-2">
                                            <span class="mr-1">5 TB</span>
                                            <span data-i18n="bandepassante">Bande passante</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="px-6 py-4 border-t border-dark-lighter">
                                    <a data-i18n="commande" href="new_subscription.php" class="block w-full bg-gray-700 hover:bg-gray-600 text-white text-center py-3 rounded-md font-semibold transition-colors">
                                        Commander
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Dedicated Servers Section -->
    <section id="dedicated" class="py-20 bg-gradient-to-br from-dark to-secondary">
        <div class="container mx-auto px-4 animate-on-scroll">
            <h2 data-i18n="serveurs" class="text-3xl md:text-4xl font-bold mb-16 text-center">
                Serveurs Dédiés</span>
            </h2>

            <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <div class="bg-dark-light rounded-lg overflow-hidden shadow-lg">
                    <div class="p-6">
                        <h3 data-i18n="serveurdedies" class="text-xl font-bold mb-2">Serveur Dédié Game</h3>
                        <p data-i18n="serveurdedies-desc" class="text-gray-400 mb-4">Idéal pour les serveurs de jeux à forte charge</p>
                        <div class="text-3xl font-bold text-primary mb-6">
                            80€<span data-i18n="mois" class="text-sm text-gray-400">/mois</span>
                        </div>
                        <ul class="space-y-3 mb-6">
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span>AMD Ryzen 7 5800X</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span>32 GB DDR4 RAM</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span>2x 512 GB NVMe SSD</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span class="mr-1">1 Gbps</span>
                                <span data-i18n="bandepassanteill">Bande passante illimitée</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span data-i18n="vpsprotection">Protection DDoS avancée</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span data-i18n="lorw">Linux ou Windows</span>
                            </li>
                        </ul>
                    </div>
                    <div class="px-6 py-4 border-t border-dark-lighter">
                        <a data-i18n="Commande" href="new_subscription.php" class="block w-full bg-primary hover:bg-purple-700 text-white text-center py-3 rounded-md font-semibold transition-colors">
                            Commander
                        </a>
                    </div>
                </div>

                <div class="bg-dark-light rounded-lg overflow-hidden shadow-lg">
                    <div class="p-6">
                        <h3 data-i18n="serveurpro" class="text-xl font-bold mb-2">Serveur Dédié Pro</h3>
                        <p data-i18n="serveurpro-desc" class="text-gray-400 mb-4">Pour les besoins professionnels et les grandes communautés</p>
                        <div class="text-3xl font-bold text-primary mb-6">
                            150€<span data-i18n="mois" class="text-sm text-gray-400">/mois</span>
                        </div>
                        <ul class="space-y-3 mb-6">
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span>AMD Ryzen 9 5950X</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span>64 GB DDR4 RAM</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span>2x 1 TB NVMe SSD</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span class="mr-1">10 Gbps</span>
                                <span data-i18n="bandepassanteill">Bande passante illimitée</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span data-i18n="vpsprotection">Protection DDoS avancée</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span data-i18n="lorw">Linux ou Windows</span>
                            </li>
                        </ul>
                    </div>
                    <div class="px-6 py-4 border-t border-dark-lighter">
                        <a data-i18n="commande" href="new_subscription.php" class="block w-full bg-primary hover:bg-purple-700 text-white text-center py-3 rounded-md font-semibold transition-colors">
                            Commander
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer class="bg-dark py-12">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-6">
                        <i class="fas fa-server text-primary text-xl mr-2"></i>
                        <span class="text-xl font-bold">LaeHosting</span>
                    </div>
                    <p data-i18n="Hostdesc" class="text-gray-400 mb-6">
                        Hébergement de serveurs de jeux, VPS et serveurs dédiés avec une performance optimale et un support 24/7.
                    </p>
                    <p class="text-gray-500 text-sm">© <span id="currentYear"></span> LaeHosting. Tous droits réservés.</p>
                </div>

                <div>
                    <h4 class="text-lg font-bold mb-4">Services</h4>
                    <ul class="space-y-2">
                        <li><a href="#games" class="text-gray-400 hover:text-primary transition-colors">Serveurs de Jeux</a></li>
                        <li><a href="#vps" class="text-gray-400 hover:text-primary transition-colors">VPS</a></li>
                        <li><a href="#dedicated" class="text-gray-400 hover:text-primary transition-colors">Serveurs Dédiés</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-bold mb-4">Jeux</h4>
                    <ul class="space-y-2">
                        <li><a href="fivem.html" class="text-gray-400 hover:text-primary transition-colors">FiveM</a></li>
                        <li><a href="minecraft.html" class="text-gray-400 hover:text-primary transition-colors">Minecraft</a></li>
                        <li><a href="gmod.html" class="text-gray-400 hover:text-primary transition-colors">Garry's Mod</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-bold mb-4">Entreprise</h4>
                    <ul class="space-y-2">
                        <li><a href="about.php" class="text-gray-400 hover:text-primary transition-colors">À propos</a></li>
                        <li><a href="contact.php" class="text-gray-400 hover:text-primary transition-colors">Contact</a></li>
                        <li><a href="terms.php" class="text-gray-400 hover:text-primary transition-colors">Conditions d'utilisation</a></li>
                        <li><a href="privacy.php" class="text-gray-400 hover:text-primary transition-colors">Politique de confidentialité</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <script src="./assets/js/indexjs.js"></script>
    <script src="./assets/js/scroll.js"></script>
    
</body>
</html>