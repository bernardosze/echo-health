"use strict";
/**
 * General methods to create a hash and assigned it into the form.
 * File used in the authentication and user registration pages
 *
 * @author: Leonardo Otoni de Assis
 *
 * Dependency: sha1.min.js
 */
//It Generates an authentication hash based on the token provided
const generateSHA1Hash = token => {
  //generate the SHA1 Hash
  sha1(token);
  const hash = sha1.create();
  hash.update(token);
  return hash.hex();
};
