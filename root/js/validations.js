function signupValidations(u, e, p1, p2, c, g) {
  var message = "";

  if (g === "") {
    message = "You must choose a gender";
  }

  if (c === "") {
    message = "You must choose a country";
  }

  if (p2 !== p1) {
    message = "Confirmation does not match the password";
  }

  if (p1 === "") {
    message = "You must fill the password field";
  }

  if (e === "") {
    message = "You must fill the email field";
  }

  if (u === "") {
    message = "You must fill the user name text field";
  }

  return message;
}