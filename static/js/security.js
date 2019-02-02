"use strict";
/**
 * General methods to create a hash and assigned it into the form.
 * File used in the authentication and user registration pages
 *
 * Author: Leonardo Otoni de Assis
 *
 * Dependency: sha1.min.js
 */

//Operations perfomed on login
const formLogin = document.getElementById("form-login");
if (formLogin) {
  formLogin.addEventListener("submit", e => {
    const email = document.getElementById("email");
    const password = document.getElementById("password");
    document.getElementById("hash").value = generateSHA1Hash(
      email.value + password.value
    );
  });
}

//Operations performed on signup
const formSignup = document.getElementById("form-signup");
if (formSignup) {
  formSignup.addEventListener("submit", e => {
    const email = document.getElementById("email");
    const password = document.getElementById("password");
    document.getElementById("hash").value = generateSHA1Hash(
      email.value + password.value
    );
  });
}

//It Generates an authentication hash based on the token provided
const generateSHA1Hash = token => {
  //generate the SHA1 Hash
  sha1(token);
  const hash = sha1.create();
  hash.update(token);
  return hash.hex();
};
