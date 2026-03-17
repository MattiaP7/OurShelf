# Guida al Contributo - OurShelf

Se ci attendiamo alle regole che ci stabiliremo andra' tutto liscio come l'olio... si spera.

## Flusso di Lavoro (Git)

- Non lavorare mai sul branch `main`, anche perche' non potrai'.
- Utilizza il tuo branch dedicato (es. dev-frontend, dev-backend, ...).
- Usa gli script nella cartella `/scripts` per fare il push del tuo lavoro.
- Una volta scritto tutto scrivi al Project Manager (MattiaP7) di verificare il lavoro e fare il merge nel `main` branch

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

## Organizzazione del Team

Ogni membro ha un ruolo specifico come indicato nel documento di progetto:

1. **Pirazzi Mattia** Project Manager / Analista (Coordinamento e Documentazione)
2. **nome** Database Designer (Schema ER e SQL)
3. **nome** Backend Developer (Logica PHP e CRUD)
4. **nome** Frontend Developer (Interfaccia e UX)

Per dubbi tecnici, rivolgersi al Team Leader.
Ricordo che e' sempre possibile lavorare insieme ad altre persone, scambiarsi di ruolo e altro, basta avvisare il Team Leader
