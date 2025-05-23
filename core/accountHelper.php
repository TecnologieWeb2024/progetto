<?php
require_once('core/formFieldsValidator.php');
class AccountHelper
{
    private $dbh;
    public function __construct($dbh)
    {
        $this->dbh = $dbh;
    }

    public function changePassword($new_password, $confirm_password)
    {
        if (!FormFieldsValidator::validatePassword($new_password)) {
            return ['success' => false, 'message' => 'La password non soddisfa i requisiti di sicurezza.'];
        }
        if ($new_password != $confirm_password) {
            return ['success' => false, 'message' => 'Le password non corrispondono.'];
        }

        if (isset($_SESSION['customer'])) {
            $updatePasswordResult = $this->dbh->updatePassword($_SESSION['customer']['user_id'], $new_password);
        } elseif (isset($_SESSION['seller'])) {
            $updatePasswordResult = $this->dbh->updatePassword($_SESSION['seller']['user_id'], $new_password);
        }
        // $updatePasswordResult = $this->dbh->updatePassword($_SESSION['customer']['user_id'], $new_password);
        return ['success' => $updatePasswordResult['success'], 'message' => $updatePasswordResult['message']];
    }
}
?>
