const setup = () => {
    return {
        isNavOpen: false,

        billPlan: 'monthly',

        plans: [
            // Minecraft plans
            {
                name: 'Minecraft Dev',
                price: {
                    monthly: 29,
                    annually: 29 * 12 - 199,
                },
                features: ['Intel 3.40GHz', '1 vCore', '2 GO Ram', '20 GO SSD', '10 Gbps', '16 Joueurs'],
                included: ['TX Admin', 'Clé Argentum incluse', 'Protection DDoS L4 & L7'],
            },
            {
                name: 'Minecraft Basic',
                price: {
                    monthly: 59,
                    annually: 59 * 12 - 100,
                },
                features: ['Intel 3.40GHz', '2 vCore', '4 GO Ram', '40 GO SSD', '10 Gbps', '32 Joueurs'],
                included: ['TX Admin', 'Clé Argentum incluse', 'Protection DDoS L4 & L7'],
            },
            {
                name: 'Minecraft Pro',
                price: {
                    monthly: 139,
                    annually: 139 * 12 - 100,
                },
                features: ['Intel 3.40GHz', '4 vCore', '8 GO Ram', '80 GO SSD', '10 Gbps', '64 Joueurs'],
                included: ['TX Admin', 'Clé Argentum incluse', 'Protection DDoS L4 & L7'],
            },
            {
                name: 'Minecraft Elite',
                price: {
                    monthly: 299,
                    annually: 299 * 12 - 100,
                },
                features: ['Intel 3.40GHz', '8 vCore', '16 GO Ram', '160 GO SSD', '10 Gbps', '128 Joueurs'],
                included: ['TX Admin', 'Clé Argentum incluse', 'Protection DDoS L4 & L7'],
            },
            {
                name: 'Minecraft Ultimate',
                price: {
                    monthly: 399,
                    annually: 399 * 12 - 100,
                },
                features: ['Intel 3.40GHz', '12 vCore', '24 GO Ram', '240 GO SSD', '10 Gbps', '192 Joueurs'],
                included: ['TX Admin', 'Clé Argentum incluse', 'Protection DDoS L4 & L7'],
            },
            {
                name: 'Minecraft Extreme',
                price: {
                    monthly: 499,
                    annually: 499 * 12 - 100,
                },
                features: ['Intel 3.40GHz', '16 vCore', '32 GO Ram', '320 GO SSD', '10 Gbps', '256 Joueurs'],
                included: ['TX Admin', 'Clé Argentum incluse', 'Protection DDoS L4 & L7'],
            },

            // Minecraft Bedrock plans

            {
                name: 'Minecraft Bedrock Dev',
                price: {
                    monthly: 29,
                    annually: 29 * 12 - 199,
                },
                features: ['Intel 3.40GHz', '1 vCore', '2 GO Ram', '20 GO SSD', '10 Gbps', '16 Joueurs'],
                included: ['TX Admin', 'Clé Argentum incluse', 'Protection DDoS L4 & L7'],
            },
            {
                name: 'Minecraft Bedrock Basic',
                price: {
                    monthly: 59,
                    annually: 59 * 12 - 100,
                },
                features: ['Intel 3.40GHz', '2 vCore', '4 GO Ram', '40 GO SSD', '10 Gbps', '32 Joueurs'],
                included: ['TX Admin', 'Clé Argentum incluse', 'Protection DDoS L4 & L7'],
            },
            {
                name: 'Minecraft Bedrock Pro',
                price: {
                    monthly: 139,
                    annually: 139 * 12 - 100,
                },
                features: ['Intel 3.40GHz', '4 vCore', '8 GO Ram', '80 GO SSD', '10 Gbps', '64 Joueurs'],
                included: ['TX Admin', 'Clé Argentum incluse', 'Protection DDoS L4 & L7'],
            },
            {
                name: 'Minecraft Bedrock Elite',
                price: {
                    monthly: 299,
                    annually: 299 * 12 - 100,
                },
                features: ['Intel 3.40GHz', '8 vCore', '16 GO Ram', '160 GO SSD', '10 Gbps', '128 Joueurs'],
                included: ['TX Admin', 'Clé Argentum incluse', 'Protection DDoS L4 & L7'],
            },
            {
                name: 'Minecraft Bedrock Ultimate',
                price: {
                    monthly: 399,
                    annually: 399 * 12 - 100,
                },
                features: ['Intel 3.40GHz', '12 vCore', '24 GO Ram', '240 GO SSD', '10 Gbps', '192 Joueurs'],
                included: ['TX Admin', 'Clé Argentum incluse', 'Protection DDoS L4 & L7'],
            },
            {
                name: 'Minecraft Bedrock Extreme',
                price: {
                    monthly: 499,
                    annually: 499 * 12 - 100,
                },
                features: ['Intel 3.40GHz', '16 vCore', '32 GO Ram', '320 GO SSD', '10 Gbps', '256 Joueurs'],
                included: ['TX Admin', 'Clé Argentum incluse', 'Protection DDoS L4 & L7'],
            }
        ],
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