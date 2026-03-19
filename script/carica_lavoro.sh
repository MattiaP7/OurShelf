#!/bin/bash
# scripts/carica_lavoro.sh

# 1. Recupera il nome del branch corrente
BRANCH=$(git rev-parse --abbrev-ref HEAD)

if [ "$BRANCH" == "main" ]; then
  echo "❌ ERRORE: Sei sul main! Non puoi caricare qui direttamente."
  read -p "Premi Invio per uscire..."
  exit 1
fi

echo "🚀 Branch rilevato: $BRANCH"
echo "-------------------------------------------------------"

# 2. Mostra lo stato dei file modificati
echo "📄 File modificati/nuovi trovati:"
git status -s

echo "-------------------------------------------------------"
echo "❓ Vuoi caricare TUTTI i file sopra elencati? (s/n) - SCONSIGLIATO"
read scelta

if [ "$scelta" == "s" ] || [ "$scelta" == "S" ]; then
    git add .
    echo "✅ Tutti i file aggiunti."
else
    echo "📥 Digita i nomi dei file da aggiungere (separati da spazio) e premi Invio:"
    echo "💡 (Esempio: src/User.php src/config/Database.php)"
    read files
    git add $files
    echo "✅ File selezionati aggiunti."
fi

# 3. Verifica se c'è effettivamente qualcosa in stage
if git diff --cached --quiet; then
    echo "⚠️ Nessun file aggiunto. Operazione annullata."
    read -p "Premi Invio per chiudere..."
    exit 0
fi

# 4. Commit e Push
echo "📝 Inserisci un messaggio per il commit (breve e chiaro):"
read messaggio

git commit -m "$messaggio"
git push origin $BRANCH

echo "-------------------------------------------------------"
echo "✅ Caricato con successo su GitHub!"
echo "🔗 Ora vai su GitHub e crea la PULL REQUEST verso il main."
echo "-------------------------------------------------------"
read -p "Premi Invio per chiudere..."