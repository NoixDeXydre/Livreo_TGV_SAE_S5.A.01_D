/*
 * Script de création de la base de données Livreo
 * Fait le 01 octobre 2026
 */
 
db = db.getSiblingDB("livreo");

// Réinitialisation de la base 
db.Clients.drop();
db.Commandes.drop();
db.Tournees.drop();
db.Vehicules.drop();
db.Depots.drop();
 
db.createCollection("Clients");
db.createCollection("Commandes");
db.createCollection("Tournees");
db.createCollection("Vehicules");
db.createCollection("Depots");