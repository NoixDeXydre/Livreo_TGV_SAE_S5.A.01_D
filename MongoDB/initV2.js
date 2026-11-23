/*
 * Script de création de la base de données Livreo
 * Fait le 01 octobre 2026
 */
 
db = db.getSiblingDB("livreo");

// Réinitialisation de la base 
db.Clients.drop();
db.Commandes.drop();
db.Depots.drop();
db.Pieces.drop();
db.Vehicules.drop();
db.Tournees.drop();
 
db.createCollection("Clients");
db.createCollection("Commandes");        
db.createCollection("Depots");
db.createCollection("Pieces");
db.createCollection("Vehicules")
db.createCollection("Tournees");

db.Clients.insertMany([
  {
    _id: "cli1",
    nom: "Marcenac",
    prenom: "Marcel",
    adresse: "1 Rue du Citron, 51100 Reims",
    localisation: { type: "Point", coordinates: [4.0347, 49.2628] }
  },
  {
    _id: "cli2",
    nom: "Dupont",
    prenom: "Claire",
    adresse: "8 Avenue de Champagne, 51200 Épernay",
    localisation: { type: "Point", coordinates: [3.9570, 49.0430] }
  },
  {
    _id: "cli3",
    nom: "Lefèvre",
    prenom: "Paul",
    adresse: "25 Rue Gambetta, 51000 Châlons-en-Champagne",
    localisation: { type: "Point", coordinates: [4.3630, 48.9560] }
  }
]);

db.Pieces.insertOne
(
  {
   
    nom: "Acier carré galvanisé", 
    poids: 12, 
    
    // Point modulaire
    quantite: 5, 
    a_faire: 2
  }
);

db.Commandes.insertMany([
  {
    _id: "cmd1",
    etat: "prête", // prête / livrée 
    client: {
      id_client: "cli1", nom: "Marcenac", prenom: "Marcel",
      adresse: "1 Rue du Citron, 51100 Reims",
      localisation: { type: "Point", coordinates: [4.0347, 49.2628] }
    },
    colis: [
      {
        id_colis: "col1",
        pieces: [
          { id_piece: "p1", nom: "Acier carré galvanisé", poids: 12, etat_piece: "fabriquée" },
          { id_piece: "p2", nom: "Poutre IPN 120", poids: 32, etat_piece: "fabriquée" }
        ]
      }
    ]
  },
  {
    _id: "cmd2",
    etat: "prête",
    client: {
      id_client: "cli2", nom: "Dupont", prenom: "Claire",
      adresse: "8 Avenue de Champagne, 51200 Épernay",
      localisation: { type: "Point", coordinates: [3.9570, 49.0430] }
    },
    colis: [
      {
        id_colis: "col2",
        pieces: [
          { id_piece: "p3", nom: "Platine soudée 400x400", poids: 25, etat_piece: "fabriquée" },
          { id_piece: "p4", nom: "Cornière 80x80",         poids: 15, etat_piece: "fabriquée" }
        ]
      }
    ]
  },
  {
    _id: "cmd3",
    date_prevue: ISODate("2026-10-01"),
    etat: "prête",
    client: {
      id_client: "cli3", nom: "Lefèvre", prenom: "Paul",
      adresse: "25 Rue Gambetta, 51000 Châlons-en-Champagne",
      localisation: { type: "Point", coordinates: [4.3630, 48.9560] }
    },
    colis: [
      {
        id_colis: "col3",
        pieces: [
          { id_piece: "p5", nom: "Tube rond 60mm", poids: 8, etat_piece: "fabriquée" }
        ]
      }
    ]
  },
  {
    // Commande encore en fabrication : sera livrée une prochaine semaine
    _id: "cmd4",
    etat: "en fabrication",
    client: {
      id_client: "cli1", nom: "Marcenac", prenom: "Marcel",
      adresse: "1 Rue du Citron, 51100 Reims",
      localisation: { type: "Point", coordinates: [4.0347, 49.2628] }
    },
    colis: [
      {
        id_colis: "col4",
        pieces: [
          { id_piece: "p6", nom: "Cadre acier 1200x800", poids: 45, etat_piece: "en cours de fabrication" },
          { id_piece: "p7", nom: "Renfort en T", poids: 15, etat_piece: "à faire" }
        ]
      }
    ]
  }
]);

db.Vehicules.insertOne({
 _id: "AA-000-AA",
 colis_max: 15,
 poids_max: 400 // poids en kg
});

db.Tournees.insertOne({
 
 _id: "tour1",
 etat: 'effectuée', // préparée/en cours/effectuée

 // Mise en place donnée en vue d'une future amélioration
 vehicule_util: "AA-000-AA",
 
 // Pas besoin de trier !!! Voir algo
 chargement: [
    { id_colis: "col1", id_commande: "cmd1", id_client: "cli1", poids: 44 },
    { id_colis: "col2", id_commande: "cmd2", id_client: "cli2", poids: 40 },
    { id_colis: "col3", id_commande: "cmd3", id_client: "cli3", poids: 8 }
  ],
  
  etapes: [
    {
      ordre: 0, 
      adresse: "12 Rue de l'Industrie, 51100 Reims",
      localisation: { type: "Point", coordinates: [4.0317, 49.2583] },
      colis: []
    },
    {
      ordre: 1,
      adresse: "25 Rue Gambetta, 51000 Châlons-en-Champagne",
      localisation: { type: "Point", coordinates: [4.3630, 48.9560] },
      colis: ["col3"],
      bon_livraison: { etat: "à valider", date_validation: null }
    },
    {
      ordre: 2,
      adresse: "8 Avenue de Champagne, 51200 Épernay",
      localisation: { type: "Point", coordinates: [3.9570, 49.0430] },
      colis: ["col2"],
      bon_livraison: { etat: "à valider", date_validation: null }
    },
    {
      ordre: 3, 
      adresse: "1 Rue du Citron, 51100 Reims",
      localisation: { type: "Point", coordinates: [4.0347, 49.2628] },
      colis: ["col1"],
      bon_livraison: { etat: "à valider", date_validation: null }
    },
    {
      ordre: 4,
      adresse: "12 Rue de l'Industrie, 51100 Reims",
      localisation: { type: "Point", coordinates: [4.0317, 49.2583] },
      colis: []
    }
  ]
});
