
const setup = () => {
    return {
        isNavOpen: false,
        billPlan: 'monthly',
        billPlanmc: 'java', // Ajout de la variable pour suivre l'état Java/Bedrock

        // Plans séparés pour Java et Bedrock
        plans: {
            java: [
                {
                    name: 'Minecraft Java Dev',
                    price: {
                        monthly: 29,
                        annually: 29 * 12 - 199,
                    },
                    features: ['Intel 3.40GHz', '1 vCore', '2 GO Ram', '20 GO SSD', '10 Gbps', '16 Joueurs'],
                    included: ['TX Admin', 'Clé Argentum incluse', 'Protection DDoS L4 & L7'],
                },
                {
                    name: 'Minecraft Java Basic',
                    price: {
                        monthly: 59,
                        annually: 59 * 12 - 100,
                    },
                    features: ['Intel 3.40GHz', '2 vCore', '4 GO Ram', '40 GO SSD', '10 Gbps', '32 Joueurs'],
                    included: ['TX Admin', 'Clé Argentum incluse', 'Protection DDoS L4 & L7'],
                },
                // ... autres plans Java
                
                {
                    name: 'Minecraft Java Pro',
                    price: {
                        monthly: 139,
                        annually: 139 * 12 - 100,
                    },
                    features: ['Intel 3.40GHz', '4 vCore', '8 GO Ram', '80 GO SSD', '10 Gbps', '64 Joueurs'],
                    included: ['TX Admin', 'Clé Argentum incluse', 'Protection DDoS L4 & L7'],
                },
                {
                    name: 'Minecraft Java Elite',
                    price: {
                        monthly: 299,
                        annually: 299 * 12 - 100,
                    },
                    features: ['Intel 3.40GHz', '8 vCore', '16 GO Ram', '160 GO SSD', '10 Gbps', '128 Joueurs'],
                    included: ['TX Admin', 'Clé Argentum incluse', 'Protection DDoS L4 & L7'],
                },
                {
                    name: 'Minecraft Java Ultimate',
                    price: {
                        monthly: 399,
                        annually: 399 * 12 - 100,
                    },
                    features: ['Intel 3.40GHz', '12 vCore', '24 GO Ram', '240 GO SSD', '10 Gbps', '192 Joueurs'],
                    included: ['TX Admin', 'Clé Argentum incluse', 'Protection DDoS L4 & L7'],
                },
                {
                    name: 'Minecraft Java Extreme',
                    price: {
                        monthly: 499,
                        annually: 499 * 12 - 100,
                    },
                    features: ['Intel 3.40GHz', '16 vCore', '32 GO Ram', '320 GO SSD', '10 Gbps', '256 Joueurs'],
                    included: ['TX Admin', 'Clé Argentum incluse', 'Protection DDoS L4 & L7'],
                },
                {
                    name: 'Minecraft Java Titan',
                    price: {
                        monthly: 599,
                        annually: 599 * 12 - 100,
                    },
                    features: ['Intel 3.40GHz', '20 vCore', '48 GO Ram', '480 GO SSD', '10 Gbps', '512 Joueurs'],
                    included: ['TX Admin', 'Clé Argentum incluse', 'Protection DDoS L4 & L7'],
                },
                {
                    name: 'Minecraft Java God',
                    price: {
                        monthly: 699,
                        annually: 699 * 12 - 100,
                    },
                    features: ['Intel 3.40GHz', '24 vCore', '64 GO Ram', '640 GO SSD', '10 Gbps', '1024 Joueurs'],
                    included: ['TX Admin', 'Clé Argentum incluse', 'Protection DDoS L4 & L7'],
                }
            ],
            bedrock: [
                {
                    name: 'Minecraft Bedrock Dev',
                    price: {
                        monthly: 25, // Exemple de prix différent pour Bedrock
                        annually: 25 * 12 - 150,
                    },
                    features: ['Intel 3.40GHz', '1 vCore', '2 GO Ram', '20 GO SSD', '10 Gbps', '16 Joueurs'],
                    included: ['TX Admin', 'Clé Argentum incluse', 'Protection DDoS L4 & L7'],
                },
                {
                    name: 'Minecraft Bedrock Basic',
                    price: {
                        monthly: 49, // Exemple de prix différent pour Bedrock
                        annually: 49 * 12 - 100,
                    },
                    features: ['Intel 3.40GHz', '2 vCore', '4 GO Ram', '40 GO SSD', '10 Gbps', '32 Joueurs'],
                    included: ['TX Admin', 'Clé Argentum incluse', 'Protection DDoS L4 & L7'],
                },
                // ... autres plans Bedrock
                {
                    name: 'Minecraft Bedrock Pro',
                    price: {
                        monthly: 99, // Exemple de prix différent pour Bedrock
                        annually: 99 * 12 - 100,
                    },
                    features: ['Intel 3.40GHz', '4 vCore', '8 GO Ram', '80 GO SSD', '10 Gbps', '64 Joueurs'],
                    included: ['TX Admin', 'Clé Argentum incluse', 'Protection DDoS L4 & L7'],
                },
                {
                    name: 'Minecraft Bedrock Elite',
                    price: {
                        monthly: 199, // Exemple de prix différent pour Bedrock
                        annually: 199 * 12 - 100,
                    },
                    features: ['Intel 3.40GHz', '8 vCore', '16 GO Ram', '160 GO SSD', '10 Gbps', '128 Joueurs'],
                    included: ['TX Admin', 'Clé Argentum incluse', 'Protection DDoS L4 & L7'],
                },
                {
                    name: 'Minecraft Bedrock Ultimate',
                    price: {
                        monthly: 299, // Exemple de prix différent pour Bedrock
                        annually: 299 * 12 - 100,
                    },
                    features: ['Intel 3.40GHz', '12 vCore', '24 GO Ram', '240 GO SSD', '10 Gbps', '192 Joueurs'],
                    included: ['TX Admin', 'Clé Argentum incluse', 'Protection DDoS L4 & L7'],
                },
                {
                    name: 'Minecraft Bedrock Extreme',
                    price: {
                        monthly: 399, // Exemple de prix différent pour Bedrock
                        annually: 399 * 12 - 100,
                    },
                    features: ['Intel 3.40GHz', '16 vCore', '32 GO Ram', '320 GO SSD', '10 Gbps', '256 Joueurs'],
                    included: ['TX Admin', 'Clé Argentum incluse', 'Protection DDoS L4 & L7'],
                },
                {
                    name: 'Minecraft Bedrock Titan',
                    price: {
                        monthly: 499, // Exemple de prix différent pour Bedrock
                        annually: 499 * 12 - 100,
                    },
                    features: ['Intel 3.40GHz', '20 vCore', '48 GO Ram', '480 GO SSD', '10 Gbps', '512 Joueurs'],
                    included: ['TX Admin', 'Clé Argentum incluse', 'Protection DDoS L4 & L7'],
                },
                {
                    name: 'Minecraft Bedrock God',
                    price: {
                        monthly: 599, // Exemple de prix différent pour Bedrock
                        annually: 599 * 12 - 100,
                    },
                    features: ['Intel 3.40GHz', '24 vCore', '64 GO Ram', '640 GO SSD', '10 Gbps', '1024 Joueurs'],
                    included: ['TX Admin', 'Clé Argentum incluse', 'Protection DDoS L4 & L7'],
                }
                
                
            ]
        },

        // Méthode pour obtenir les plans actifs en fonction de billPlanmc
        activePlans() {
            return this.plans[this.billPlanmc];
        },

        getFeatureIcon(feature) {
            const f = feature.toLowerCase();
            if (f.includes('intel')) return 'cpu';
            if (f.includes('vcore')) return 'vCore';
            if (f.includes('ram')) return 'ram';
            if (f.includes('ssd')) return 'ssd';
            if (f.includes('gbps')) return 'gbps';
            if (f.includes('joueurs')) return 'players';
            return 'check';
        }
    }
}

