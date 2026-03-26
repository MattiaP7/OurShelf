# Guida al Contributo - OurShelf

Se ci attendiamo alle regole che ci stabiliremo andra' tutto liscio come l'olio... si spera.

## Flusso di Lavoro (Git)

Per garantire la stabilità del progetto, seguiamo il modello **Feature Branching**.

### 1. I Branch

- **`main`**: È la versione stabile. Solo il PM può scriverci.
- **`dev-tm`** Lavoro generale del TM.
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
# oppure tutti i file con git add .

# crea il commit con una descrizione esausitiva, se raggiungi il limite di 50 caratteri fai un altro -m, es:
git commit -m "DB: Aggiornata tabella ourshelf_libri" -m "Descrizione estesa..."
# Invia le modifiche SOLO al tuo branch.
# git push origin [nome-branch], es:
git push origin dev-db
```

Se due persone vogliono lavorare sullo stesso file fate cosi:

```bash
# scarica il lavoro dell'altra persona
git pull origin [nome-branch]
# aggiungi i file
git add .
# sincronizza di nuovo (in caso avesse fatto un push)
git pull origin [nome-branch]

# invia
git push origin [nome-branch]
```

Se hai iniziato a lavorare ma devi scaricare aggiornamenti dal `main` che ti servono subito, usa lo **Stash** per non perdere le tue modifiche non salvate:

```bash
# 1. Congela le tue modifiche attuali
git stash

# 2. Scarica le novità dal main
git pull origin main

# 3. Riprendi il tuo lavoro da dove avevi lasciato
git stash pop

```

Se Git ti avvisa che ci sono dei conflitti (scritte rosse nel terminale):

1. Apri VS Code: I file in conflitto avranno dei segni <<<<<<< HEAD.
2. Scegli la versione: Clicca su "Accept Current Change" (il tuo) o "Accept Incoming Change" (quello dal server).
3. Concludi: Una volta risolti tutti i punti, salva il file e scrivi:

```bash
git add .
git commit -m "fix: risolti conflitti di merge"
```

## Standard di Codifica

- **Naming Variabili**: `camelCase` (es: `$prezzoLibro`, `$utenteLoggato`).
- **Naming Tabelle DB**: `snake_case` (es: `ourshelf_libri_adottati`, `ourshelf_storico_operazioni`). Devono iniziare con il prefisso `ourshelf_` (a meno che Dessolis non crei uno spazio dedicato).
- **Funzioni**: `snake_case`, es: `get_libri()`, per le funzioni specificare il tipo dei parametri e il tipo di ritorno.
- **Classi**: es `ControllerLibro`
- **Commenti**: Ogni funzione deve avere la documentazione DocBlock con l'autore, generata da `PHP DocBlocker` tramite `/**` (usate l'autocompletamento), se non dovesse andare posizionatevi con il cursore sopra una funzione e fate `F1 > Insert PHP Docblock`.
- **Database**: Le query devono utilizzare i **Prepared Statements** per la sicurezza (PDO).
- **Lingua Italiana per tutto.**

### Cartella /database

Questa cartella serve per avere una copia locale degli script sql che creiamo su phpmyadmin, cosi in caso il server della scuola scoppi abbiamo una copia delle query per la creazione delle tabelle, inserimento di dati (di prova), etc... Quando create uno script sql ricordatevi di andare sulla tabella e cliccare nella navbar in alto Export e esportate in formato .sql, il file lo caricate poi sulla cartella /database **operazione che deve fare il database designer**, riguardo lo schema ER consiglio caldamente di farlo tramite siti come drawio cosi abbiamo anche un file grafico dello schema ER.

## Organizzazione del Team e branch name

Ogni membro ha un ruolo specifico come indicato nel documento di progetto:

1. **Pirazzi Mattia** Project Manager / Analista (Coordinamento e Documentazione)
2. **Landi e Ionut** Database Designer (Schema ER e SQL)
3. **Pirazzi Mattia** Backend Developer (Logica PHP e CRUD)
4. **Portacci Matteo** Frontend Developer (Interfaccia e UX)

> **Nota**: Per dubbi tecnici o conflitti di codice, rivolgersi subito al Project Manager. Se dovete cambiare ruolo o aiutarvi su un compito specifico, avvisate prima il Team Leader.
