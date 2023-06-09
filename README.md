# Commission fee calculation - Test Task

## Requirements:
- [Docker](https://docs.docker.com/engine/install/ubuntu/)
- [Docker-compose](https://docs.docker.com/compose/install/)

## Installation:
- Run `docker-compose up -d` to build a container
- Run `docker exec -it php8.1-cli bash` to pass into container
  
**OR** run `docker ps`
- Find container ID and run `docker exec -it <containerID> bash` to pass into container
## Settings variables in config/services.yaml 

- commission_for_private_client: Commission fee from withdrawn amount for private client. (0.3)
- commission_for_business_client: Commission fee from withdrawn amount for business client. (0.5)
- private_client_free_amount: Private client free amount in default currency (1000)
- private_client_free_withdraws: Private client free withdraws (3)
- default_currency: Default currency ('EUR')
- deposit_percent: Deposit percent (0.03)
- currency_exchange_source: link for currency 
- currency_precision: list of currencies with specific precision (JPY: 0)

## Run command:
- Inside container run 
    `php bin/console app:calculate-commission txt/operations.csv`

## Run test:
- Inside container run `php bin/phpunit tests`



# About the task

Bank allows private and business clients to `deposit` and `withdraw` funds to and from accounts in multiple currencies. Clients may be charged a commission fee.

You have to create an application that handles operations provided in CSV format and calculates a commission fee based on defined rules.

## Commission fee calculation
- Commission fee is always calculated in the currency of the operation. 
- Commission fees are rounded up to currency's decimal places. For example, `0.023 EUR` should be rounded up to `0.03 EUR`.

### Deposit rule
All deposits are charged 0.03% of deposit amount.

### Withdraw rules
There are different calculation rules for `withdraw` of `private` and `business` clients.

**Private Clients**
- Commission fee - 0.3% from withdrawn amount.
- 1000.00 EUR for a week (from Monday to Sunday) is free of charge. Only for the first 3 withdraw operations per a week. 4th and the following operations are calculated by using the rule above (0.3%). If total free of charge amount is exceeded them commission is calculated only for the exceeded amount (i.e. up to 1000.00 EUR no commission fee is applied).

For the second rule you will need to convert operation amount if it's not in Euros.

**CSV file**
- CSV file is in **txt** folder

### DESCRIPTION
- The syfony skeleton was used to solve the test task.
- From the input information line Transaction Entity is formed, which includes Money Entity and User Entity. Money Entity contains information about processing money (amount and currency). User Entity has information about User (ID, type). Transaction Entity has transaction date. It gives oportunity to use money with currency separately from entire Transaction.
- For saving and work with transaction history I use Transaction Store and TransactionSoreRepository. Now Transaction Store keeps all history about private clients withdraw transactions.
- The strategy pattern is implemented for calculation fee.
- All necessary math functions described in MoneyService. All this functions work with MoneyEntity.

This approach implements a console application according to SOLID principles (Single Responsibility, Open-Closed, Interface Segrigation). It allows easily extend and maintain existing code.
All functional is divided for small classes, that allows to cover all application with unit tests.

### To improve this application:
- TransactionStoreRepository should have tool to delete not needed information (transactions older than 1 week from working date)
- ParserService should be used for extending functionality (add another parser type)
- In UserEntity we can use provided constants for setting ClientType.
- Provided application can be transformed for work with DataBase.
