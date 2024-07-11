<?php

class BankAccount {
    protected float $balance;

    public function __construct($balance)
    {
        if ($balance <= 0) {
            throw new Exception('You cannot open an account with no balance');
        }
        $this->balance = $balance;
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

    protected function calculateInterest(): float
    {
        return $this->balance * 0.05; // 5% interest on standard accounts
    }
}

$account = new BankAccount(100);
$account->deposit(10.5);
$account->applyInterest();
echo $account->getBalance();