<?php

namespace App\Services;

use App\Models\Customer;
use App\Exceptions\CustomerNotFoundException;

class CustomerService
{
    public function searchCustomers($searchTerm)
    {
        return Customer::where('firstName', 'LIKE', "%{$searchTerm}%")
            ->orWhere('lastName', 'LIKE', "%{$searchTerm}%")
            ->orWhere('email', 'LIKE', "%{$searchTerm}%")
            ->orWhere('street', 'LIKE', "%{$searchTerm}%")
            ->get();
    }
    public function destroyCustomer($id)
{
    $customer = Customer::find($id);
    if (!$customer) {
        throw new CustomerNotFoundException('Customer not found bla');
    }
    $customer->delete();
}
}
