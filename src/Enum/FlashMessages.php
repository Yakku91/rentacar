<?php

namespace App\Enum;

enum FlashMessages: string
{
    case SUCCESS_ORDER = "Order.success";
    case SUCCESS_REGISTER = "Register.success";
    case SUCCESS_LOGOUT = "Logout.success";
    case SUCCESS_EDIT = "Edit.success";
    case SUCCESS_CONTACT = "Contact.success";

    case ERROR_DELETING_ACCOUNT = "Error.deleting.account";
    case SUCCESS_DELETING_ACCOUNT = "Success.deleting.account";
    case SUCCESS_DELETING_RENTAL = "Success.deleting.rental";


    public function toString(): string
    {
        return $this->value;
    }
}
