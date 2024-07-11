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

    public function withdraw(float $amount): bool
    {
        if (!$this->customer->allowOverdrafts && $this->balance - $amount < 0) {
            return false;
        }
        $this->balance -= $amount;
        return true;
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
    public bool $allowOverdrafts;

    public function __construct(string $name, string $postcode, bool $allowOverdrafts)
    {
        $this->name = $name;
        $this->postcode = $postcode;
        $this->allowOverdrafts = $allowOverdrafts;
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

    public function __construct(string $name, string $postcode, bool $allowOverdrafts, int $vatNumber)
    {
        parent::__construct($name, $postcode, $allowOverdrafts);
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

class Transfer {
    protected BankAccount $sender;
    protected BankAccount $recipient;

    public function __construct(BankAccount $sender, BankAccount $recipient)
    {
        $this->sender = $sender;
        $this->recipient = $recipient;
    }

    public function send(float $amount): bool
    {
        if (!$this->sender->withdraw($amount)) {
            return false;
        }

        $this->recipient->deposit($amount);
        return true;
    }
}


$privateCustomer = new PrivateCustomer('Bob', 'ab123cd', false);
$businessCustomer = new BusinessCustomer('Bob ltd', 'ab123cd', true,1248153);

$account = new BankAccount(100, $privateCustomer);
$account2 = new SavingsAccount(1000, $businessCustomer);

$transfer = new Transfer($account, $account2);
$transfer->send(150);

echo $account->getBalance() . '<br />';
echo $account2->getBalance();

