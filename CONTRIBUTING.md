# Guida al Contributo - OurShelf

Se ci attendiamo alle regole che ci stabiliremo andra' tutto liscio come l'olio... si spera.

## Flusso di Lavoro (Git)

Per garantire la stabilità del progetto, seguiamo il modello **Feature Branching**.

### 1. I Branch

- **`main`**: È la versione stabile. Solo il PM può scriverci.
- **`dev-db`**: Lavoro sulle tabelle e SQL.
- **`dev-backend`**: Lavoro su PHP, logica e query.
- **`dev-frontend`**: Lavoro su HTML, CSS e interfacce.

### 2. Il Ciclo di Lavoro (Quando fare Pull?)

Per evitare conflitti, ogni membro deve seguire questo ordine:

1. **INIZIO**: Prima di scrivere codice, scarica gli aggiornamenti degli altri:
   `git pull origin main`
2. **LAVORO**: Scrivi il tuo codice nel tuo branch dedicato.
3. **FINE**: Quando hai finito una funzione o un'interfaccia:
   - Fai un ultimo `git pull origin main` per verificare che non ci siano conflitti.
   - Carica il tuo lavoro: `git push origin [nome-tuo-branch]`.
4. **REVISIONE**: Avvisa il PM (**MattiaP7**) su WhatsApp/Teams, io controllero e fare il merge nel branch main.

Di seguito la guida generale per fare commit su git, se non lo hai installato vai al sito di [git](https://git-scm.com/) e installalo.

```bash
# 1. Spostati sul tuo branch (fallo solo la prima volta)
# git checkout -b [nome-branch], es:
git checkout -b dev-db

# 2. Ogni volta che inizi a lavorare scarica gli aggiornamenti dal main
git pull origin main

# 3. Dopo che hai aggiornato, creato dei file aggiungili al commit:
# git add path-file, es:
git add database/schema.sql

# crea il commit con una descrizione esausitiva, se raggiungi il limite di 50 caratteri fai un altro -m, es:
git commit -m "DB: Aggiornata tabella ourshelf_libri" -m "Descrizione estesa..."
# Invia le modifiche SOLO al tuo branch.
# git push origin [nome-branch], es:
git push origin dev-db
```

## Standard di Codifica

- **Naming Variabili**: `camelCase` (es: `$prezzoLibro`, `$utenteLoggato`).
- **Naming Tabelle DB**: `snake_case` (es: `ourshelf_libri_adottati`, `ourshelf_storico_operazioni`). Devono iniziare con il prefisso `ourshelf_` (a meno che Dessolis non crei uno spazio dedicato).
- **Commenti**: Ogni funzione deve avere la documentazione DocBlock con l'autore, generata da `PHP DocBlocker` tramite `/**` (usate l'autocompletamento), se non dovesse andare posizionatevi con il cursore sopra una funzione e fate `F1 > Insert PHP Docblock`.
- **Database**: Le query devono utilizzare i **Prepared Statements** per la sicurezza (PDO).

## Requisiti del Database

Il database deve contenere obbligatoriamente i seguenti campi per ogni libro:

- ISBN (13 caratteri)
- Titolo e Autori
- Editore e Volume (U, 1, 2, 3)
- Classe (1-5) e Materia
- Corso di studi

### Cartella /database

Questa cartella serve per avere una copia locale degli script sql che creiamo su phpmyadmin, cosi in caso il server della scuola scoppi abbiamo una copia delle query per la creazione delle tabelle, inserimento di dati (di prova), etc... Quando create uno script sql ricordatevi di andare sulla tabella e cliccare nella navbar in alto Export e esportate in formato .sql, il file lo caricate poi sulla cartella /database **operazione che deve fare il database designer**, riguardo lo schema ER consiglio caldamente di farlo tramite siti come drawio cosi abbiamo anche un file grafico dello schema ER.

## Organizzazione del Team e branch name

Ogni membro ha un ruolo specifico come indicato nel documento di progetto:

1. **Pirazzi Mattia** Project Manager / Analista (Coordinamento e Documentazione) - \*\*
2. **nome** Database Designer (Schema ER e SQL)
3. **nome** Backend Developer (Logica PHP e CRUD)
4. **nome** Frontend Developer (Interfaccia e UX)

> **Nota**: Per dubbi tecnici o conflitti di codice, rivolgersi subito al Project Manager. Se dovete cambiare ruolo o aiutarvi su un compito specifico, avvisate prima il Team Leader.
