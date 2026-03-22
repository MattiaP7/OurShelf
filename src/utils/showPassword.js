/**
 * Mostra a schermo il contenuto di un tag input password.
 * @param {*} PasswordId id del tag password
 * @author Mattia Pirazzi <PIRAZZI.8076@isit100.fe.it>
 * @date 21/03/2026
 */
function showPassword(PasswordId) {
  const password = document.getElementById(PasswordId);
  // se il tipo e' password lo trasformiamo in test e viceversa
  const type = password.type === "password" ? "text" : "password";
  password.type = type;
}
