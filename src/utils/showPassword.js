/**
 * Mostra/Nasconde la password e cambia l'icona di sicurezza
 * @param {string} inputId - ID dell'input field
 * @param {string} iconId - ID dell'elemento <i> che contiene l'icona
 */
function showPassword(inputId, iconId) {
  const input = document.getElementById(inputId);
  const icon = document.getElementById(iconId);

  if (input && input.type === "password") {
    input.type = "text";
    if (icon) {
      // Cambia l'icona da lucchetto a lucchetto aperto (o occhio)
      icon.classList.replace("bi-shield-lock", "bi-shield-lock-fill");
      icon.classList.replace("text-muted", "text-primary");
    }
  } else if (input) {
    input.type = "password";
    if (icon) {
      icon.classList.replace("bi-shield-lock-fill", "bi-shield-lock");
      icon.classList.replace("text-primary", "text-muted");
    }
  }
}