<?php
/**
 * Common Server-Side form field validators
 *
 * @author Leonardo Otoni
 */
namespace classes\util\validators {

    trait FormValidators
    {
        /**
         * Validate a given email
         * @param email
         */
        public function isValidEmail(string $email)
        {
            $EMAIL_VALIDATION_REGEX = "/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/";
            return preg_match($EMAIL_VALIDATION_REGEX, $email);
        }

        /**
         * Validate if a postal code follow the format LNLNLN or LNL NLN- L=Letter, N=Number
         */
        public function isValidPostalCode(string $postalCode)
        {
            $POSTAL_CODE_VALIDATION_REGEX = "/^[A-Za-z]\d[A-Za-z][ ]?\d[A-Za-z]\d$/";
            return preg_match($POSTAL_CODE_VALIDATION_REGEX, $postalCode);
        }

        /**
         * Returns true if a given date is a future date otherwise, returns false.
         * If date could not be converted to a valid date, it will return false.
         * @param date - date string in Y-m-d format
         */
        public function isFutureDate(string $date)
        {
            try {
                return (date("Y-m-d") < date("Y-m-d", strtotime($date)));
            } catch (Exception $e) {
                return false;
            }
        }

    }

}
