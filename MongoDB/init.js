/*
 * Script de création de la base de données Livreo
 * Fait le 01 octobre 2026
 */
 
// TODO

// Base client
// => Commandes
// => Etat commande
// => Bon de livraison

// Base camion
// Base livraison
//

// Création de la base de données
use livreo

// TODO rajouter dépôts et véhicules

db.createCollection("Clients");
db.createCollection("Pieces");
db.createCollection("Vehicules");
db.createCollection("Depots");

// FIXME réfléchir poids pieces

db.Clients.insertOne
({
  id_client: "Test1", 
  nom: "Marcenac", 
  prenom: "Marcel", 
  adresse: "1 Rue du citron", 
  commandes: [
    {
      etat: "livrée", // livrée/non livrée
      colis: [
        {
          pieces: 
          [
            {
              id_piece: 'id1', 
              poids: 12, 
              etat_piece: "Fabriquée"
            }, 
            {
              id_piece: 'id2', 
              poids: 32, 
              etat_piece: "Fabriquée" // à faire/en cours de fabrication / fabriquée
            }
          ]
        }
      ]
    }
  ]
});

db.Pieces.insertOne
(
  {
    _id: "id1", 
    nom: "Acier carré galvanisé", 
    poids: 12, 
    a_fabriquer: 2
  }
);

db.Vehicules.insertOne
(
  {
    _id: 1, 
    volume: 500, 
    // localisation: "atelier1",
    boucle: 
    [
      
      // Point 1 et final = atelier
      // Autres points = clients  
      
      {
        // GeoJson1
      }, 
      {
        // GeoJson2
      }
    ], 
    chargement: // Par ordre de livraison, de la plus légère à la plus lourde
    [
      {
        id_commande: "cmd1", 
        quantite: 5
      }, 
      {
        id_commande: "cmd2", 
        quantite: 3
      }
    ]
  }
);

db.Depots.insertOne
(
  {
    _id: 1, 
    localisation: {}, // GEOJSON
    
  }
);