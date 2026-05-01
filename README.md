# OurShelf — Mercatino dei Libri Scolastici

> Piattaforma web per lo scambio e la vendita di libri scolastici usati all'interno dell'istituto Bassi Burgatti.

---

## Indice

- [Descrizione](#descrizione)
- [Struttura del progetto](#struttura-del-progetto)
- [Requisiti](#requisiti)
- [Configurazione ambiente](#configurazione-ambiente)
- [Plugin VS Code consigliati](#plugin-vs-code-consigliati)
- [Stato del progetto](#stato-del-progetto)
- [Link utili](#link-utili)

---

## Descrizione

OurShelf permette agli studenti di:

- pubblicare annunci di vendita tramite scansione o inserimento manuale dell'ISBN
- consultare la bacheca degli annunci con filtri avanzati
- organizzare incontri di scambio in luoghi sicuri all'interno della scuola
- gestire il proprio profilo e lo storico di acquisti e vendite

Il progetto segue il paradigma **MVC** con **PHP** lato server e **MariaDB** come database.

---

## Struttura del progetto

```
OurShelf/
├── .vscode/               # Configurazioni editor (sftp, launch)
├── assets/                # CSS, immagini, font
├── database/              # Backup script SQL (non modificare manualmente)
├── docs/                  # Documentazione aggiuntiva e diagrammi ER
├── script/                # Script di importazione dati (JSON → DB)
├── uploads/               # Immagini caricate dagli utenti
│   └── annunci/           # Sottocartelle per annuncio ({id}/imgs.json)
└── src/                   # Sorgente principale dell'applicazione
    ├── config/            # Connessione al database (PDO) e costanti
    ├── controllers/       # Logica di controllo — gestisce le richieste HTTP
    ├── models/            # Accesso al database — tutte le query SQL
    ├── utils/             # Helper riutilizzabili (UploadHelper, ecc.)
    └── views/             # Interfaccia utente (HTML + PHP)
        ├── annunci/
        ├── dashboard/
        ├── layout/
        ├── login/
        └── users/
```

---

## Configurazione ambiente

### 1. Clona il repository

```bash
git clone https://github.com/MattiaP7/OurShelf.git
cd OurShelf
```

### 2. Configura la connessione al database

Copia `src/config/dbconfig.example.php` in `src/config/dbconfig.php` e compila i parametri:

```php
define("DB_HOST", 'HOST_DEL_TUO_DB');
define("DB_NAME", 'NOME_DEL_TUO_DB');
define("DB_USERNAME", 'USERNAME_DB');
define("DB_PASSWORD", 'PASSWORD_DB');
define("DB_CHARSET", 'utf8mb4');
```

### 3. Configura SFTP per VS Code

Crea `.vscode/sftp.json` con i dati del server della scuola:

```jsonc
{
  "name": "", // nome server sftp
  "host": "", // host del server sftp
  "protocol": "ftp",
  "port": 21,
  "username": "", // tuo username
  "password": "", // tua password
  "remotePath": "", // path del progetto nel server sftp
  "uploadOnSave": true,
  "useTempFile": false,
  "openSsh": false,
  "ignore": [
    ".vscode",
    ".git",
    ".DS_Store",
    "databases/*.sql",
    "README.md",
    "CONTRIBUTING.md",
  ],
}
```

> Per name, host, username e password consulta il file `sftp.json` di riferimento nella cartella `info5/projects`.

---

## Plugin VS Code consigliati

Installa i seguenti plugin cercandoli con `Ctrl + Shift + X`:

| ID Plugin                             | Descrizione                                 |
| ------------------------------------- | ------------------------------------------- |
| `bmewburn.vscode-intelephense-client` | Autocompletamento e navigazione PHP         |
| `natizyskunk.sftp`                    | Sincronizzazione con il server della scuola |
| `neilbrayfield.php-docblocker`        | Generazione automatica DocBlock             |
| `usernamehw.errorlens`                | Evidenzia errori inline nel codice          |
| `formulahendry.auto-close-tag`        | Chiusura automatica tag HTML                |
| `hossaini.bootstrap-intellisense`     | Autocompletamento classi Bootstrap          |

### Configurazione DocBlock automatica

Apri le impostazioni utente JSON (`Ctrl + Shift + P` → `Open User Settings (JSON)`) e aggiungi in fondo — sostituendo nome, cognome ed email con i tuoi:

```jsonc
"php-docblocker.extra": [
  "@author Nome Cognome <email@isit100.fe.it>",
  "@date ${CURRENT_DATE}/${CURRENT_MONTH}/${CURRENT_YEAR}"
],
"php-docblocker.returnVoid": true,
"[php]": {
  "editor.defaultFormatter": "bmewburn.vscode-intelephense-client",
  "editor.formatOnSave": true,
  "editor.snippetSuggestions": "inline",
  "editor.quickSuggestions": {
    "other": true,
    "comments": true,
    "strings": true
  }
},
"php.suggest.basic": false,
"php.validate.enable": true
```

Dopodichè, posizionati sopra qualsiasi funzione e digita `/**` + Invio: il DocBlock viene generato automaticamente.

---

## Stato del progetto

### Ambiente e repository

- [x] Creazione repository GitHub
- [x] Struttura cartelle MVC
- [x] Connessione al database (PDO)

### Database

- [x] Schema concettuale (ER)
- [x] Schema logico relazionale
- [x] Creazione tabelle e foreign key

### Autenticazione e profilo

- [x] Registrazione nuovi utenti
- [x] Login con gestione sessioni
- [x] Cambio password
- [x] Area riservata personale
- [x] Modifica dati profilo

### Catalogo libri

- [x] Importazione libri da JSON (adozioni scolastiche)

### Annunci — core

- [x] Inserimento annuncio con ricerca ISBN
- [x] Bacheca annunci con filtri dinamici (fetch)
- [x] Dettaglio annuncio con carosello immagini
- [x] Acquisto e conclusione vendita
- [x] Upload immagini annuncio - 01/05/2026

### Frontend

- [x] Layout responsive Bootstrap 5
- [x] Navbar e footer

### Testing

- [ ] Test sicurezza (SQL Injection, XSS)
- [ ] Verifica flussi operativi end-to-end
- [ ] Ottimizzazione query
- [ ] Documentazione finale

---

<div align="center">
  &copy; 2026 OurShelf &bull; Team 2: <a href="https://github.com/MattiaP7">Pirazzi Mattia</a> <a href="https://github.com/cyberCcode23">Portacci Matteo</a> <a href="https://github.com/alessandrolandi186">Alessandro Landi</a> <a href="https://github.com/IonutAnusca">Ionut Anusca</a> &bull; Istituto Bassi Burgatti, Ferrara
</div>
