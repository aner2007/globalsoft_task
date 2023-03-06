<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Services\CustomerService;
use App\Exceptions\CustomerNotFoundException;

class CustomerController extends Controller
{
    private $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function index(Request $request)
    {
        $searchTerm = $request->query("search");

        if ($searchTerm) {
            $customers = $this->customerService->searchCustomers($searchTerm);
        } else {
            $customers = Customer::all();
        }

        return response()->json($customers);
    }

    public function store(Request $request)
    {
        $customer = Customer::create($request->all());

        return response()->json($customer, 201);
    }

    public function show($id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return response()->json(["message" => "Customer not found"], 404);
        }
        return response()->json($customer);
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::find($id);
        $customer->update($request->all());

        return response()->json($customer);
    }

    public function delete($id)
    {
        try {
            $this->customerService->deleteCustomer($id);
            return response()->json(null, 204);
        } catch (CustomerNotFoundException $e) {
            return response()->json(["message" => $e->getMessage()], 404);
        }
    }
}
