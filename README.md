# OurShelf - Book Swap Project

OurShelf è un'applicazione web sviluppata per facilitare lo scambio e la vendita di libri scolastici usati all'interno dell'istituto. Il sistema permette agli studenti di gestire inserzioni tramite scansione ISBN e organizzare incontri sicuri a scuola.

## Struttura del Progetto

Il progetto segue il paradigma MVC utilizzando PHP e MariaDB.

```
OurShelf/
├── .vscode/        # Configurazioni ottimizzate per l'editor
├── database/       # Cartella dedicata per gli script SQL in caso di errori
├── src/            # Codice sorgente dell'applicazione
│ ├── config/       # Connessione al Database e parametri di sistema
│ ├── utils/        # Cartella che contiene funzioni di helper usate in tutto il progetto
│ ├── controllers/  # Logica di controllo (Gestione richieste)
│ ├── models/       # Modelli dei dati (Query SQL)
│ └── views/        # Interfaccia utente (HTML/PHP)reali
├── CONTRIBUTING.md # Regole di codifica e standard del team
└── .gitignore      # File da escludere dal versionamento
```

## Requisiti Tecnici e Plugin VS Code

Per lavorare correttamente su OurShelf, installa i seguenti plugin in Visual Studio Code:

- **PHP Intelephense** Fondamentale per l'autocompletamento e la navigazione tra classi MVC.
- **PHP DocBlocker** Genera in modo automatico la documentazione delle funzioni, classi, etc
- **SFTP (di Natizyskunk)** Per sincronizzare il codice locale con il server info5/projects.
- **Bootstrap IntelliSense** Per autocompletamento delle classi bootstrap

Se avete bisogno di una lista di plugin di vscode da installare per il progetto io ho queste, cercate il nome scritto cosi nella ricerca dei plugin (`Ctrl + Shift + X`):

```
bmewburn.vscode-intelephense-client
natizyskunk.sftp
neilbrayfield.php-docblocker
usernamehw.errorlens
formulahendry.auto-close-tag
hossaini.bootstrap-intellisense
```

### Documentazione del Codice (DocBlock)

Per garantire la manutenibilità e facilitare il Testing, ogni collaboratore deve documentare il proprio lavoro:

- **Obbligatorietà**: Ogni classe (Model/Controller) e ogni funzione deve avere un blocco DocBlock `/** ... */`.
- **Tag Richiesti**:
  - `@author`: Il nome di chi ha scritto il codice.
  - `@param`: Descrizione di ogni variabile in ingresso.
  - `@return`: Cosa restituisce la funzione (es: bool, array, string).
  - **Descrizione**: La prima riga del commento deve spiegare chiaramente la funzionalità (es: "Effettua la ricerca nel database dei libri adottati").

Per aver un buon funzionamento di vscode fate questi passaggi:

1. Premi sulla tastiera `Ctrl + Shift + P`
2. Scrivi nella barra che compare: `Open User Settings (JSON)` e fate invio
3. Aggiungete infondo queste regole:

```jsonc
"php-docblocker.extra": [
  //  ovviamente metti il tuo nome cognome e email
  "@author Nome Cognome <email@isit100.fe.it>",
  "@date ${CURRENT_DATE}/${CURRENT_MONTH}/${CURRENT_YEAR}",
],
"php-docblocker.returnVoid": true,
"[php]": {
  "editor.defaultFormatter": "bmewburn.vscode-intelephense-client",
  "editor.formatOnSave": true,
  "editor.snippetSuggestions": "inline",
  "editor.quickSuggestions": {
    "other": true,
    "comments": true,
    "strings": true,
  },
  "intelephense.completion.triggerParameterHints": true,
},
"php.suggest.basic": false,
"php.validate.enable": true,
```

<!--
Se per qualche motivo non dovesse generare la documentazione sopra una funzione fate questi passaggi:

1. Premi sulla tastiera `Ctrl + Shift + P`
2. Scrivi nella barra che compare: `Snippets: configure snippets` e cercate php, fate invio
3. Aggiungete il seguente blocco:

```json
"DocBlock PHP": {
  "prefix": "docme",
  "body": [
      "/**",
      " * ${1:Descrizione della funzione}",
      " *",
      " * @param ${2:mixed} \\$${3:variabile} ${4:Descrizione parametro}",
      " * @return ${5:void}",
      " * @author nome cognome <email@isit100.fe.it>",
	  " * @date ${CURRENT_DATE}/${CURRENT_MONTH}/${CURRENT_YEAR}",
      " */"
  ],
  "description": "Genera il blocco commenti"
}
``` -->

Adesso basterà sopra una funzione scrivere `docme` e autocompletare, questa è una soluzione più spartana ma funzionante.

## Configurazione .vscode

Per un'esperienza ottimale, la cartella `.vscode` deve contenere:

1. `.vscode/sftp.json:` Configura il percorso remoto puntando esattamente a info5/projects/OurShelf/, configurato cosi:

```jsonc
{
  "name": "",
  "host": "",
  "protocol": "ftp",
  "port": 21,
  "username": "",
  "password": "",
  "remotePath": "/vostro cognome/OurShelf",
  "uploadOnSave": true,
  "useTempFile": false,
  "openSsh": false,
  // evitare di caricare se stesso
  "ignore": [
    ".vscode",
    ".git",
    ".DS_Store",
    "databases/*.sql",
    "README.md",
    "CONTRIBUTING.md"
  ]
}
```

Le informazioni vuote prendete dal `.vscode/sftp.json` di project in info5

2. `.vscode/launch.json` Il file per aprire il progetto nel browser, prendetelo dalla cartella dove mettete i progetti info5 (`info5/projects`)

## Link Utili

- Consulta il file [CONTRIBUTING.md](CONTRIBUTING.md) per gli standard di programmazione.
- Consulta il file [Book_swap_project.pdf](Book_swap_project.pdf) per informazioni aggiuntive sul progetto/
  Per ogni dubbio sulle configurazioni vscode e altro scrivete al Team Leader.

# Stato del progetto

**Configurazione Ambiente e Repository**

- [x] Creazione repository GitHub | 17/03/2026
- [x] Strutturazione cartelle progetto secondo paradigma MVC (app, public, src, views) | 17/03/2026
- [x] Configurazione file di connessione al database (PDO) | 17/03/2026

**Analisi e Progettazione Database**

- [x] Progettazione schema concettuale (Diagramma ER) | 28/03/2026
- [x] Progettazione schema logico relazionale | 28/03/2026
- [x] Creazione tabelle | 28/03/2026
- [x] Implementazione vincoli di integrità referenziale (Foreign Keys) | 28/03/2026
<!-- - [x] Creazione indici ottimizzati per la ricerca (Full-text su Titolo/ISBN/Autore)  | 31/03/2026 -->

**Sistema di Autenticazione e Profilo**

- [x] Sviluppo logica di registrazione nuovi utenti | 31/03/2026
- [x] Sviluppo sistema di login con gestione sessioni sicure | 31/03/2026
- [x] Implementazione sistema di cambia password | 31/03/2026
- [x] Creazione area riservata personale dello studente
- [x] Implementazione recupero password o modifica dati profilo

**Gestione Catalogo Libri Scolastici**

- [x] Script di importazione massiva libri da file JSON/CSV (Adozioni scolastiche) | 31/03/2026
<!-- - [x] Sviluppo sistema di ricerca libri per classe, indirizzo o ISBN | 31/03/2026
- [x] Visualizzazione dettagliata del libro (Titolo, Autore, Editore, Prezzo consigliato) | 31/03/2026 -->

**Mercatino e Annunci (Core Business)**

- [x] Sviluppo procedura inserimento annuncio (inserimento ISBN)
- [ ] Gestione upload immagini per lo stato d'usura del libro usato
- [x] Visualizzazione bacheca annunci pubblici filtrabile
<!-- **Comunicazione e Scambio**
  - [ ] Gestione delle notifiche per nuovi messaggi ricevuti
  - [ ] Funzionalità di segnalazione interesse per un libro -->

**Frontend e Interfaccia Utente**

- [x] Sviluppo layout responsive (Bootstrap)

**Testing e Integrazione**

- [ ] Test di sicurezza (protezione SQL Injection e XSS)
- [ ] Verifica flussi operativi (registrazione -> inserimento -> vendita)
- [ ] Bug fixing e ottimizzazione delle performance delle query
- [ ] Produzione documentazione finale del progetto
