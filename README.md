# BileMo API

API RESTful développée avec Symfony 7.3 et API Platform pour la société BileMo.  
Cette API permet à des clients partenaires d’accéder au catalogue de téléphones et de gérer leurs utilisateurs (customers).

---

## 🚀 Installation

### 1. Cloner le projet

```bash
git clone https://github.com/tcardo06/bilemo.git
cd bilemo
```

### 2. Installer les dépendances

```bash
composer install
```

### 3. Configuration de l’environnement

Créer un fichier `.env.local` à la racine du projet :

```bash
###> doctrine/doctrine-bundle ###
DATABASE_URL="mysql://root:<mot_de_passe>@127.0.0.1:3306/bilemo?serverVersion=8.4&charset=utf8mb4"
###< doctrine/doctrine-bundle ###

###> lexik/jwt-authentication-bundle ###
JWT_PASSPHRASE="bilemo"
###< lexik/jwt-authentication-bundle ###
```

> 💡 Remplacer `<mot_de_passe>` par le mot de passe MySQL.

---

## 🗄️ Base de données

### Création de la base et migration

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### Charger les données de démonstration

```bash
php bin/console doctrine:fixtures:load
```

Cela crée :
- un **client partenaire** : `partner@bilemo.test` (mot de passe : `ChangeMe123!`)
- plusieurs **produits** BileMo

---

## 🔐 Authentification (JWT)

L’API utilise **LexikJWTAuthenticationBundle**.  
Pour obtenir un token :

```bash
curl -X POST http://127.0.0.1:8000/auth   -H "Content-Type: application/json"   -d '{"email":"partner@bilemo.test","password":"ChangeMe123!"}'
```

Copier la valeur du champ `"token"` et l’utiliser dans les requêtes suivantes :

```
Authorization: Bearer <votre_token>
```

---

## 📚 Endpoints principaux

| Méthode | Endpoint | Description | Authentification |
|----------|-----------|--------------|------------------|
| `POST` | `/auth` | Obtenir un token JWT | ❌ |
| `GET` | `/api/products` | Liste des produits | ✅ |
| `GET` | `/api/products/{id}` | Détail d’un produit | ✅ |
| `GET` | `/api/customers` | Liste des utilisateurs liés au client connecté | ✅ |
| `GET` | `/api/customers/{id}` | Détail d’un utilisateur | ✅ |
| `POST` | `/api/customers` | Ajouter un utilisateur | ✅ |
| `DELETE` | `/api/customers/{id}` | Supprimer un utilisateur | ✅ |

> ✅ = JWT requis

---

## 🧪 Tests rapides avec Postman

1. Créer une requête **POST** → `/auth` pour obtenir un token.  
2. Copier le token dans les requêtes suivantes avec **Authorization → Bearer Token**.  
3. Tester les endpoints `/api/products` et `/api/customers`.

---

## 🧩 Documentation technique (Swagger / OpenAPI)

L’API dispose d’une documentation interactive via **Swagger UI** :

Swagger UI (GitHub Pages) : https://tcardo06.github.io/bilemo/

---

## 🧰 Outils principaux

- Symfony 7.3  
- API Platform 4  
- Doctrine ORM  
- LexikJWTAuthenticationBundle  
- NelmioCorsBundle  
- PHP 8.4  
- MySQL 8  
- Postman (tests)

---

## 🧑‍💻 Auteur

Projet réalisé par Thomas Cardoso dans le cadre de la formation [OpenClassrooms - Développeur d’application PHP / Symfony](https://openclassrooms.com/).
