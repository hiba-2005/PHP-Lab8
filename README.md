# LAB 8 — Sécurité, Authentification et Finalisation

## 🎯 Objectif
Implémenter un système sécurisé avec :
- Authentification admin
- Protection CSRF
- Middleware de sécurité
- Validation et nettoyage des données
- Routes protégées

---

## 🏗 Architecture
````
`Lab8/
- public/
- src/
  - Core/
  - Controller/
  - Dao/
  - Security/
  - Container/
- views/
- logs/
- docs/
````
Architecture MVC multicouche :
Controller → Service/DAO → Base de données

---

<img width="1366" height="496" alt="image" src="https://github.com/user-attachments/assets/b73760c4-e45b-40df-b461-4fec86227a17" />
<img width="1366" height="544" alt="image" src="https://github.com/user-attachments/assets/d7b4acae-bf67-4c1b-8695-7565dd7a7740" />
<img width="1366" height="488" alt="image" src="https://github.com/user-attachments/assets/387a7670-0497-40e9-a34e-8bdc4fedb88b" />
<img width="1366" height="665" alt="image" src="https://github.com/user-attachments/assets/7922c7d2-ddbb-465d-a2d7-e1b20c31e44e" />

---

## 🚀 Lancer le projet

Dans le dossier du projet :

```bash
php -S localhost:8000 -t public
````
<img width="676" height="131" alt="image" src="https://github.com/user-attachments/assets/418bb511-b151-4ea8-8621-b15dfb33b3e9" />
