<?php

namespace UtenteDDDExample\Domain\Model\Utente\Password;

class NotHashedPasswordUtente extends PasswordUtente
{
    protected function isPasswordValid(string $password): bool
    {
        /**
         * Esempio di possibile validazione password
         *
         * 8 characters length
         * 2 letters in Upper Case
         * 1 Special Character (!@#$&*)
         * 2 numerals (0-9)
         * 3 letters in Lower Case
         *
         * ^                         Start anchor
         * (?=.*[A-Z].*[A-Z])        Ensure string has two uppercase letters.
         * (?=.*[!@#$&*])            Ensure string has one special case letter.
         * (?=.*[0-9].*[0-9])        Ensure string has two digits.
         * (?=.*[a-z].*[a-z].*[a-z]) Ensure string has three lowercase letters.
         * .{8}                      Ensure string is of length 8.
         * $                         End anchor.
         */

//        if (!preg_match('/^(?=.*[A-Z].*[A-Z])(?=.*[!@#$&*])(?=.*[0-9].*[0-9])(?=.*[a-z].*[a-z].*[a-z]).{8}$/', $password)) {
//
//            throw new \InvalidArgumentException(sprintf('Password non vadida [%s]', $password));
//        }

        return true;
    }
}
