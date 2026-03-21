/**
 * Mostra a schermo il contenuto di un tag input password.
 * @param {*} PasswordId id del tag password
 */
function showPassword(PasswordId) {
  const password = document.getElementById(PasswordId);
  const type = password.type === "password" ? "text" : "password";

  password.type = type;
}
