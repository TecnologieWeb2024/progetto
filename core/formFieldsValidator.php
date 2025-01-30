<?php
class FormFieldsValidator
{
    /**
     * Validate an email address
     * @param string $email
     * @return bool True if the email is valid, false otherwise
     */
    public static function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validate a password
     * @param string $password
     * @return bool True if the password is valid, false otherwise
     */
    public static function validatePassword($password)
    {
        $pattern = '/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[\W_]).{8,}$/';
        return self::validate($pattern, $password);
    }

    /**
     * Validate a phone number
     * @param string $phone
     * @return bool True if the phone number is valid, false otherwise
     */
    public static function validatePhone($phone)
    {
        $pattern = '/^\+?[0-9]{10,15}$/';
        return self::validate($pattern, $phone);
    }

    /**
     * Validate a name
     * @param string $name
     * @return bool True if the name is valid, false otherwise
     */
    public static function validateName($name)
    {
        $pattern = '/^[a-zA-Z\s]{2,20}$/';
        return self::validate($pattern, $name);
    }

    /**
     * Validate an address
     * @param string $address
     * @return bool True if the address is valid, false otherwise
     */
    public static function validateAddress($address)
    {
        $pattern = '/^[a-zA-Z0-9\s,]{0,50}$/';
        return self::validate($pattern, $address);
    }

    /**
     * Validate a string against a pattern (a preg_match wrapper)
     * @param string $string
     * @param string $pattern
     * @return bool True if the string matches the pattern, false otherwise
     */
    public static function validate($string, $pattern)
    {
        return preg_match($pattern, $string);
    }
}
?>