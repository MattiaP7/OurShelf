#!/bin/bash
# scripts/update.sh

echo "🔄 Passaggio al main per scaricare gli aggiornamenti..."
git checkout main
git pull origin main

# Chiede all'utente su quale branch vuole tornare
echo "❓ Su quale branch vuoi tornare? (es. dev-backend, dev-db...)"
read branch

git checkout $branch
echo "🔀 Unisco le novità del main nel tuo branch $branch..."
git merge main

echo "✅ Sei aggiornato e pronto a lavorare su $branch!"
read -p "Premi Invio per chiudere..."