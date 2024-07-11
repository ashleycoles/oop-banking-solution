<?php

class BankAccount {
    protected float $balance;
    protected Customer $customer;
    public function __construct(float $balance, Customer $customer)
    {
        if ($balance <= 0) {
            throw new Exception('You cannot open an account with no balance');
        }
        $this->balance = $balance;
        $this->customer = $customer;
    }

    public function deposit(float $amount): void
    {
        $this->balance += $amount;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function applyInterest(): void
    {
        $this->balance += $this->calculateInterest();
    }

    public function getDetails(): string
    {
        return "
            <div>
                <h2>Account details</h2>
                {$this->customer->getDetails()}
                <p>Balance: £$this->balance</p>
            </div>
        ";
    }

    protected function calculateInterest(): float
    {
        return $this->balance * 0.05; // 5% interest on standard accounts
    }
}

class SavingsAccount extends BankAccount {
    protected function calculateInterest(): float
    {
        return $this->balance * 0.07;
    }
}

abstract class Customer {
    public string $name;
    public string $postcode;

    public function __construct(string $name, string $postcode)
    {
        $this->name = $name;
        $this->postcode = $postcode;
    }

    abstract public function getDetails(): string;
}

class PrivateCustomer extends Customer {
    public function getDetails(): string
    {
        return "
            <ul>
                <li>$this->name</li>
                <li>$this->postcode</li>
            </ul>
        ";
    }
}

class BusinessCustomer extends Customer {
    public int $vatNumber;

    public function __construct(string $name, string $postcode, int $vatNumber)
    {
        parent::__construct($name, $postcode);
        $this->vatNumber = $vatNumber;
    }

    public function getDetails(): string
    {
        return "
            <ul>
                <li>$this->name</li>
                <li>$this->postcode</li>
                <li>$this->vatNumber</li>
            </ul>
        ";
    }
}


$privateCustomer = new PrivateCustomer('Bob', 'ab123cd');
$businessCustomer = new BusinessCustomer('Bob ltd', 'ab123cd', 1248153);

$account = new BankAccount(100, $privateCustomer);

echo $account->getDetails();