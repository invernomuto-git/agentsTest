# Agents Test
## Scopo
L'obiettivo è quello di testare le possibilità di sviluppo usando gli agenti copilot su github e nello spcifico:
1. se sono in grado di sviluppare codice che deve interagire con una base di dati
   1. È in grado e gestisce molto bene la creazione di migrations
2. se sono in grado di sviluppare codice basandosi sull'uso di un framework es. Codeigniter
3. se sono in grado di sviluppare codice su un framework e su altre classi installate, tipo Grocery
4. se sono in grado di sviluppare codice su Wordpress
## Testi
1. Far realizzare all'agente un sistema crud elementare per gestire una rubrica telefonica collegandola ad un database mysql, facendogli creare anche delle migrations e dei seeds.
2. Far installare un framework come Codeigniter.
   1. In locale userei composer: è utilizzabile anche su github?
   2. viene creata la cartella vendor?
   3. Se non viene creata testare il caricamento sul repositori di un progetto che contiene già la cartella vendor con le dipendency
   4. Far creare usando Codeginiter e il suo sistema MVC e routing il sistema CRUD per la rubrica telefonica, facendogli creare migrations e seeds
3. Fare lo stesso test usando COdeigniter e Grocery, installandolo a seconda delle risposte avute al punto 2
4. A seconda degli step precedenti, fare un test con Wordpress

---

## Struttura del progetto

### v1/ — PHP semplice (Test case 1)
Implementazione CRUD della rubrica telefonica in PHP puro con PDO.
- `index.php` — lista contatti
- `create.php` / `edit.php` / `delete.php` — CRUD contatti
- `aziende.php` / `create_azienda.php` / `edit_azienda.php` / `delete_azienda.php` — CRUD aziende
- `db.php` / `config.php` — connessione al database (PDO, singleton)
- `style.css` — foglio di stile
- `rubrica.sql` — schema completo del database
- `migration_add_*.sql` — migrazioni incrementali

### rubrica-ci4/ — CodeIgniter 4 (Test case 2)
Stessa rubrica telefonica reimplementata con CodeIgniter 4 (MVC, routing, migrations, seeds).

#### Setup
```bash
cd rubrica-ci4
composer install
cp .env.example .env   # oppure modifica .env
# Configura database in .env:
# database.default.hostname = localhost
# database.default.database = rubrica
# database.default.username = root
# database.default.password = <password>
php spark migrate
php spark db:seed MainSeeder
php spark serve
```

#### Struttura MVC
- `app/Controllers/Contatti.php` — CRUD contatti
- `app/Controllers/Aziende.php` — CRUD aziende
- `app/Models/ContattoModel.php` — modello contatti (con helper per tag)
- `app/Models/AziendaModel.php` — modello aziende
- `app/Models/TagModel.php` — modello tag
- `app/Views/contatti/` — viste CRUD contatti
- `app/Views/aziende/` — viste CRUD aziende
- `app/Views/layouts/main.php` — layout base CI4
- `app/Database/Migrations/` — migrazioni CI4
- `app/Database/Seeds/` — seeds CI4
- `app/Config/Routes.php` — definizione URL routing
- `public/css/style.css` — foglio di stile

#### Rotte disponibili
| Metodo | URL | Azione |
|--------|-----|--------|
| GET | `/` | Lista contatti |
| GET | `/contatti` | Lista contatti |
| GET/POST | `/contatti/create` | Nuovo contatto |
| GET/POST | `/contatti/edit/{id}` | Modifica contatto |
| GET/POST | `/contatti/delete/{id}` | Elimina contatto |
| GET | `/aziende` | Lista aziende |
| GET/POST | `/aziende/create` | Nuova azienda |
| GET/POST | `/aziende/edit/{id}` | Modifica azienda |
| GET/POST | `/aziende/delete/{id}` | Elimina azienda |
