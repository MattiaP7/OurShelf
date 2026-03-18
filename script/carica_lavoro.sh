#!/bin/bash
# scripts/carica_lavoro.sh

# Recupera il nome del branch corrente
BRANCH=$(git rev-parse --abbrev-ref HEAD)

if [ "$BRANCH" == "main" ]; then
  echo "❌ ERRORE: Sei sul main! Non puoi caricare qui direttamente."
  echo "Spostati sul tuo branch (git checkout dev-...) e riprova."
  read -p "Premi Invio per uscire..."
  exit 1
fi

echo "🚀 Branch rilevato: $BRANCH"
git add .

echo "📝 Inserisci un messaggio per il commit (cosa hai fatto?):"
read messaggio

git commit -m "$messaggio"
git push origin $BRANCH

echo "-------------------------------------------------------"
echo "✅ Caricato con successo su GitHub!"
echo "🔗 Ora vai su GitHub e crea la PULL REQUEST verso il main."
echo "-------------------------------------------------------"
read -p "Premi Invio per chiudere..."