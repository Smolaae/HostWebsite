const setup = () => {
    return {
        isNavOpen: false,

        billPlan: 'monthly',

        plans: [
        {
            name: 'FiveM Dev',
            price: {
            monthly: 29,
            annually: 29 * 12 - 199,
            },
            features: ['Intel 3.40GHz', '1 vCore', '2 GO Ram', '20 GO SSD', '10 Gbps', '16 Joueurs'],
            included: [ 'TX Admin', 'Clé Argentum incluse', 'Protection DDoS L4 & L7'],
        },
        {
            name: 'FiveM Basic',
            price: {
            monthly: 59,
            annually: 59 * 12 - 100,
            },
            features: ['Intel 3.40GHz', '2 vCore', '4 GO Ram', '40 GO SSD', '10 Gbps', '32 Joueurs'],
            included: [ 'TX Admin', 'Clé Argentum incluse', 'Protection DDoS L4 & L7'],
          },
        
        {
            name: 'FiveM Pro',
           
            price: {
            monthly: 139,
            annually: 139 * 12 - 100,
            },
            features: ['Intel 3.40GHz', '4 vCore', '8 GO Ram', '80 GO SSD', '10 Gbps', '64 Joueurs'],
            included: [ 'TX Admin', 'Clé Argentum incluse', 'Protection DDoS L4 & L7'],
        },
        {
            name: 'FiveM Elite',
            
            price: {
            monthly: 299,
            annually: 299 * 12 - 100,
            },
            features: ['Intel 3.40GHz', '8 vCore', '16 GO Ram', '160 GO SSD', '10 Gbps', '128 Joueurs'],
            included: [ 'TX Admin', 'Clé Argentum incluse', 'Protection DDoS L4 & L7'],
           },
        {
            name: 'FiveM sur mesure',
            
            price: {
            monthly: 499,
            annually: 499 * 12 - 100,
            },
            features: ['Intel 3.40GHz', '16 vCore', '32 GO Ram', '320 GO SSD', '10 Gbps', '256 Joueurs'],
            included: [ 'TX Admin', 'Clé Argentum incluse', 'Protection DDoS L4 & L7'],
            },
        ],
    }
}