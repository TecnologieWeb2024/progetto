<?php
class FormFieldsValidator
{
    public static function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function validatePassword($password)
    {
        $pattern = '/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[\W_]).{8,}$/';
        return self::validate($pattern, $password);
    }

    public static function validatePhone($phone)
    {
        $pattern = '/^\+?[0-9]{10,15}$/';
        return self::validate($pattern, $phone);
    }

    public static function validateName($name)
    {
        $pattern = '/^[a-zA-Z\s]{2,20}$/';
        return self::validate($pattern, $name);
    }

    public static function validateAddress($address)
    {
        $pattern = '/^[a-zA-Z0-9\s,]{0,50}$/';
        return self::validate($pattern, $address);
    }

    public static function validate($string, $pattern)
    {
        return preg_match($pattern, $string);
    }
}
?>