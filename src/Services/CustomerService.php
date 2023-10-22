<?php

namespace Dvhoangfh\Aepay\Services;

use Dvhoangfh\Aepay\Models\Customer;

class CustomerService extends BaseService
{
    private $model;
    
    public function __construct()
    {
        $this->model();
    }
    
    public function model()
    {
        $this->model = app(Customer::class);
    }
    
    public function firstOrCreate(array $attributes, array $values = [])
    {
        return Customer::firstOrCreate($attributes, $values);
    }
    
    public function getInfo($id)
    {
        $customer = Customer::whereId($id)->select(['id', 'name', 'email', 'status'])->first();
        if ($customer) {
            $customer['is_premium'] = $customer->is_premium;
            $customer = $customer->toArray();
            if (isset($customer['subscriptions'])) {
                unset($customer['subscriptions']);
            }
            return $customer;
        }
        return [];
    }
    
    public function getInfoProfile($customerId)
    {
        return $this->model->with(['receipts', 'receipts.subscription.package'])->find($customerId);
    }
}
