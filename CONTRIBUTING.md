# Guida al Contributo — OurShelf

Questa guida definisce le regole di lavoro del team. Seguirle riduce i conflitti e mantiene il codice leggibile da tutti.

---

## Indice

- [Team e ruoli](#team-e-ruoli)
- [Branch e flusso Git](#branch-e-flusso-git)
- [Comandi Git quotidiani](#comandi-git-quotidiani)
- [Risolvere i conflitti](#risolvere-i-conflitti)
- [Standard di codifica](#standard-di-codifica)
- [Documentazione DocBlock](#documentazione-docblock)
- [Cartella /database](#cartella-database)

---

## Team e ruoli

| #   | Nome                | Ruolo                               | Branch                |
| --- | ------------------- | ----------------------------------- | --------------------- |
| 1   | **Pirazzi Mattia**  | Project Manager / Backend Developer | `main`, `dev-backend` |
| 2   | **Landi**           | Database Designer                   | `dev-db`              |
| 3   | **Ionut**           | Database Designer                   | `dev-db`              |
| 4   | **Portacci Matteo** | Frontend Developer                  | `dev-frontend`        |

> Per dubbi tecnici o conflitti di codice contatta subito il PM (**MattiaP7**) su WhatsApp o Teams.

---

## Branch e flusso Git

Il progetto usa il modello **Feature Branching**. Ogni ruolo ha il proprio branch e solo il PM fa merge su `main`.

| Branch         | Scopo                                          | Chi ci lavora |
| -------------- | ---------------------------------------------- | ------------- |
| `main`         | Versione stabile — non modificare direttamente | Solo PM       |
| `dev-backend`  | Logica PHP, controller, model                  | Pirazzi       |
| `dev-db`       | Tabelle SQL, query, schema ER                  | Landi, Ionut  |
| `dev-frontend` | HTML, CSS, interfacce                          | Portacci      |

### Regola d'oro

```
pull → lavora → pull → push → avvisa il PM
```

Sempre fare un `git pull` prima di iniziare e un secondo `git pull` prima di fare `push`, per evitare conflitti.

---

## Comandi Git quotidiani

### Prima di iniziare a lavorare

```bash
# Scarica gli aggiornamenti dal main nel tuo branch
git pull origin main
```

### Durante il lavoro

```bash
# Aggiungi i file modificati
git add src/controllers/AnnunciController.php

# Oppure aggiungi tutto
git add .

# Crea un commit con messaggio descrittivo
git commit -m "feat: aggiunto endpoint search per annunci"

# Se il messaggio supera 50 caratteri aggiungi una riga di dettaglio
git commit -m "feat: aggiunto endpoint search" -m "Restituisce JSON filtrato per materia, ISBN, prezzo"
```

### Alla fine, prima di mandare

```bash
# Controlla che non ci siano novità dal main
git pull origin main

# Invia al tuo branch (non a main)
git push origin dev-backend
```

---

### Lavorare sullo stesso file con un altro membro

Se devi modificare un file su cui sta lavorando anche qualcun altro:

```bash
# 1. Scarica il lavoro dell'altro
git pull origin dev-backend

# 2. Fai le tue modifiche, poi aggiungi
git add .

# 3. Riscarica per sicurezza (potrebbe aver pushato nel frattempo)
git pull origin dev-backend

# 4. Invia
git push origin dev-backend
```

---

### Salvare lavoro non finito con lo Stash

Se devi scaricare aggiornamenti urgenti ma hai modifiche a metà che non vuoi ancora committare:

```bash
# 1. Congela le modifiche correnti
git stash

# 2. Scarica gli aggiornamenti
git pull origin main

# 3. Riprendi esattamente da dove eri rimasto
git stash pop
```

---

## Risolvere i conflitti

Quando Git segnala un conflitto (righe rosse nel terminale), VS Code mostra i file in conflitto con questa struttura:

```
<<<<<<< HEAD
// tua versione del codice
=======
// versione dal server (incoming)
>>>>>>> branch-name
```

**Come risolverlo:**

1. Apri il file in conflitto in VS Code
2. Clicca su **Accept Current Change** (tua versione) o **Accept Incoming Change** (versione del server), oppure **Accept Both** se entrambe le parti vanno tenute
3. Salva il file
4. Completa il merge:

```bash
git add .
git commit -m "fix: risolti conflitti di merge"
```

---

## Standard di codifica

### Naming convention

| Elemento      | Stile        | Esempio                                |
| ------------- | ------------ | -------------------------------------- |
| Variabili PHP | `camelCase`  | `$prezzoLibro`, `$idStudente`          |
| Funzioni PHP  | `camelCase`  | `getAnnunci()`, `insertUser()`         |
| Classi PHP    | `PascalCase` | `AnnunciController`, `LibriModels`     |
| Tabelle DB    | `PascalCase` | `Annunci`, `Classi_Libri`              |
| Colonne DB    | `snake_case` | `id_annuncio`, `data_pubblicazione`    |
| File view     | `snake_case` | `change_password.php`, `main_view.php` |

### Regole generali

- **Lingua italiana** per commenti, nomi di variabili, messaggi utente e documentazione
- **Prepared Statements PDO** obbligatori per tutte le query — mai concatenare variabili nelle query SQL
- **`safe_string()`** obbligatorio per ogni valore stampato in HTML — mai echo diretto di dati utente
- Ogni file PHP che non è un entry point deve iniziare con `defined("APP") or die("Accesso negato");`
- Nessuna logica di business nelle view — le view mostrano solo ciò che il controller passa loro
- Nessuna query SQL nei controller — le query stanno solo nei model

### Esempio query corretta

```php
// ✅ Corretto — prepared statement
$sql  = "SELECT * FROM Studenti WHERE email = ? LIMIT 1";
$stmt = $this->pdo->prepare($sql);
$stmt->execute([$email]);

// ❌ Sbagliato — concatenazione diretta
$sql = "SELECT * FROM Studenti WHERE email = '$email'";
```

---

## Documentazione DocBlock

Ogni classe e ogni metodo **devono** avere un blocco DocBlock. È obbligatorio, non opzionale.

### Tag richiesti

| Tag         | Obbligatorio       | Descrizione                                     |
| ----------- | ------------------ | ----------------------------------------------- |
| Descrizione | ✅                 | Prima riga: cosa fa la funzione                 |
| `@param`    | ✅ se ha parametri | Tipo e descrizione di ogni parametro            |
| `@return`   | ✅                 | Tipo restituito (`void`, `array`, `bool`, ecc.) |
| `@author`   | ✅                 | Nome e email di chi ha scritto il codice        |
| `@date`     | ✅                 | Data di creazione/ultima modifica               |

### Esempio

```php
/**
 * Recupera tutti gli annunci disponibili con i dati del libro e del venditore.
 * Supporta il filtraggio opzionale per materia, condizione e fascia di prezzo.
 *
 * @param string $materia    Filtra per materia esatta del libro (default '').
 * @param string $condizione Filtra per condizione del libro (default '').
 * @param float  $prezzoMin  Prezzo minimo, 0 = nessun limite (default 0).
 * @param float  $prezzoMax  Prezzo massimo, 0 = nessun limite (default 0).
 * @return array Array associativo degli annunci trovati.
 * @author Mario Rossi <rossi.0000@isit100.fe.it>
 * @date 21/04/2026
 */
public function getAnnunci(string $materia = '', string $condizione = '', float $prezzoMin = 0, float $prezzoMax = 0): array
```

### Come generare il DocBlock in automatico

Posiziona il cursore sopra la firma della funzione e digita `/**` + Invio: VS Code con **PHP DocBlocker** compila automaticamente i tag `@param` e `@return` leggendo la firma del metodo.

---

## Cartella /database

La cartella `database/` contiene backup degli script SQL creati su phpMyAdmin. Serve come copia di sicurezza in caso di problemi con il server della scuola.

**Chi si occupa di tenerla aggiornata:** il Database Designer (Landi/Ionut).

**Procedura di export:**

1. Apri phpMyAdmin e seleziona la tabella modificata
2. Clicca su **Esporta** nella navbar in alto
3. Scegli formato `.sql`
4. Salva il file nella cartella `database/`
5. Fai commit e push su `dev-db`

> Non caricare file `.sql` con dati sensibili (password, dati personali reali). Usa solo dati di prova.

---

<div align="center">
  OurShelf &bull; Team 2 &bull; A.S. 2025/2026
</div>
