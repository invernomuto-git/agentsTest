# Agents Test
## Scopo
L'obiettivo è quello di testare le possibilità di sviluppo usando gli agenti copilot su github e nello spcifico:
1. se sono in grado di sviluppare codice che deve interagire con una base di dati
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