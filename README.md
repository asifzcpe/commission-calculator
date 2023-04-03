# Commission Calculator

A commission calculator that handles financial operations in CSV format for clients, calculates commission fees based on defined rules, and rounds up fees to the decimal places of the currency used in the operation. For deposits, the fee is 0.03% of the deposit amount. For withdrawals, private clients can make up to 3 free withdrawals per week of up to 1000 EUR, after which a fee of 0.3% is applied. For business clients, the fee for all withdrawals is 0.5%. If the operation amount is not in Euros, it should be converted using the rates provided by the exchage rate api.

# Requirements

### PHP >= 8.0

# How to start

## 1. Clone the repository

```bash
git clone https://github.com/asifzcpe/commission-calculator.git
```

## 2. Go to the folder

```bash
cd commission-calculator
```

## 3. Now install the dependencies running the following command

```bash
composer install
```

## 4. Run the tests via the following command

```bash
composer test
```

## 5. Show the calculated commissions

### Run the following command to get the calculated commissions

```bash
php script.php input.csv
```
