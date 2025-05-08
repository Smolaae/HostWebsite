const setup = () => {
    return {
        isNavOpen: false,
        billPlan: 'monthly',

        plans: [
            {
                name: 'GMod Starter',
                price: {
                    monthly: 19,
                    annually: 19 * 12 - 50,
                },
                features: ['Intel 3.40GHz', '1 vCore', '4 GO Ram', '30 GO SSD', '1 Gbps', '16 Joueurs'],
                included: ['ULX Admin', 'FTP Access', 'Protection DDoS Basic'],
            },
            {
                name: 'GMod Basic',
                price: {
                    monthly: 39,
                    annually: 39 * 12 - 100,
                },
                features: ['Intel 3.40GHz', '2 vCore', '8 GO Ram', '60 GO SSD', '2 Gbps', '32 Joueurs'],
                included: ['ULX Admin', 'FTP Access', 'MySQL Database', 'Protection DDoS Standard'],
            },
            {
                name: 'GMod Pro',
                price: {
                    monthly: 79,
                    annually: 79 * 12 - 150,
                },
                features: ['Intel 3.40GHz', '4 vCore', '16 GO Ram', '120 GO SSD', '5 Gbps', '64 Joueurs'],
                included: ['SuperAdmin', 'FTP Access', 'MySQL Database', 'Automatic Backups', 'Protection DDoS Premium'],
            },
            {
                name: 'GMod Elite',
                price: {
                    monthly: 129,
                    annually: 129 * 12 - 200,
                },
                features: ['Intel 3.40GHz', '6 vCore', '32 GO Ram', '240 GO SSD', '10 Gbps', '100 Joueurs'],
                included: ['SuperAdmin', 'FTP Access', 'MySQL Database', 'Automatic Backups', 'Priority Support', 'Protection DDoS Premium'],
            },
            {
                name: 'GMod Ultimate',
                price: {
                    monthly: 199,
                    annually: 199 * 12 - 300,
                },
                features: ['Intel 3.40GHz', '8 vCore', '64 GO Ram', '480 GO SSD', '10 Gbps', '128 Joueurs'],
                included: ['SuperAdmin', 'FTP Access', 'MySQL Database', 'Automatic Backups', 'Priority Support', 'Dedicated IP', 'Protection DDoS Enterprise'],
            },
            {
                name: 'GMod Custom',
                price: {
                    monthly: 'Sur devis',
                    annually: 'Sur devis',
                },
                features: ['Configuration sur mesure', 'Ressources dédiées', 'Support 24/7'],
                included: ['Tous les avantages Ultimate', '+ Solutions personnalisées'],
            }
        ],

        getFeatureIcon(feature) {
            const f = feature.toLowerCase();
            if (f.includes('intel')) return 'cpu';
            if (f.includes('vcore')) return 'vCore';
            if (f.includes('ram')) return 'ram';
            if (f.includes('ssd')) return 'ssd';
            if (f.includes('gbps')) return 'network';
            if (f.includes('joueurs')) return 'users';
            if (f.includes('devis')) return 'quote';
            return 'check';
        }
    }
}