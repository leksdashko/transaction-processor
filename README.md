# Transaction Processor

This project calculates transaction fees based on BIN and exchange rate data.

## Time Spent

I spent around 7 hours to check and complete the task.

## Installation

1. Clone the repository:
    ```sh
    git clone https://github.com/leksdashko/transaction-processor.git
    ```

2. Navigate to the project directory:
    ```sh
    cd transaction-processor
    ```

3. Install dependencies:
    ```sh
    composer install
    ```

## Usage

1. Prepare an input file with transaction data (e.g., `input.txt`):
    ```json
    {"bin":"45717360","amount":"100.00","currency":"USD"}
    {"bin":"516793","amount":"50.00","currency":"EUR"}
    {"bin":"45417360","amount":"10000.00","currency":"JPY"}
    {"bin":"41417360","amount":"130.00","currency":"USD"}
    ```

2. Run the script:
    ```sh
    php app.php input.txt
    ```

## Testing

1. Run the tests using PHPUnit:
    ```sh
    vendor/bin/phpunit tests
    ```

### Step-by-Step Development Process

1. Initialized the project with Composer and created the necessary directory structure.
2. Defined interfaces for BIN and currency rate providers to ensure flexibility and extendibility.
3. Implemented the service classes to fetch BIN data and exchange rates.
4. Split the transaction processing logic into multiple classes following SOLID principles.
5. Set up the main script to utilize the services and core logic.
6. Wrote unit tests to ensure the functionality works as expected.

### Project Structure

- **Contracts**
  - `BinProviderInterface`: Interface for fetching BIN data.
  - `CurrencyRateProviderInterface`: Interface for fetching currency exchange rates.

- **Services**
  - `BinProvider`: Implementation of `BinProviderInterface` to fetch BIN data using `file_get_contents`.
  - `CurrencyRateProvider`: Implementation of `CurrencyRateProviderInterface` to fetch currency exchange rates using `file_get_contents`.

- **Transaction**
  - `EuChecker`: Checks if a given country code belongs to the EU.
  - `TransactionFeeCalculator`: Calculates the transaction fee based on BIN data, exchange rates, and EU membership.
  - `TransactionProcessor`: Processes the input file and calculates fees for each transaction.
