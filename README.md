# Cushon Interview Task

## Prerequisites

- PHP 8.3 or higher
- Composer
- NVM (Node Version Manager)
- Node.js v22.14.0 (specified in `.nvmrc`)
- PostgreSQL 15.1 (DBngin recommended for easy setup)
- Git

## Installations / Steps to run

### 1. Clone the repository

```bash
git clone git@github.com:lukepaoloni/cushon-interview-task.git
cd cushon-interview-task
```

### 2. Install backend dependencies

```bash
composer install
```

### 3. Configure environment

Copy the example environment file and modify it according to your local setup:

```bash
cp .env.example .env
```

If using DBngin for PostgreSQL, update your `.env` file with these settings:

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=cushon
DB_USERNAME=postgres
DB_PASSWORD=
```

Create a new database named `cushon` in DBngin.

### 4. Generate key and run database migrations and seed initial data

```bash
php artisan key:generate
php artisan migrate
php artisan db:seed
```

### 5. Install frontend dependencies

Use NVM to ensure you're using the correct Node.js version and then install dependencies:

```bash
nvm install
npm ci
```

### 6. Start the development server

Run the development server with npm and PHP server in one command:

```bash
composer run dev
```

The application will be available at http://127.0.0.1:8000

### 7. Running tests

```bash
php artisan test
```

Tests use Laravel's in-memory database by default. No additional database setup is required for running tests.

### API Endpoints

The API provides the following endpoints:

- `GET /api/retail/isa/funds` - Retrieve available funds
- `POST /api/retail/isa/investments` - Create a new investment
- `GET /api/retail/isa/investments` - Retrieve investment history

### Demo Credentials

For testing purposes, a demo user is automatically authenticated through the middleware.

### Problem Domain
Cushon is expanding its financial services to include ISA offerings for retail customers who don't have employer relationships with Cushon. This is separate from their existing employer-based ISA and pension offerings.

### Existing Offering
- ISAs and Pensions to Employees of Companies (Employers)

### New Offering
- ISA investments to retail (direct) customers who are not associated with an employer

### Technical Requirements
1. **Fund Selection:**
   - Customers must select a single fund from available options
   - System architecture should anticipate future multi-fund selection capability
2. **Investment Process:**
   - After fund selection, customers specify investment amount
   - System records both fund selection and investment amount
   - Complete investment details must be persistently stored for future queries
3. **System Architecture:**
   - Maintain separation between retail and employer-based systems where practical
   - Implement distinct routing for retail customer flows (e.g. "/retail" prefix)
   - Design data storage model for investment records with query capabilities

### User Flow
1. Customer navigates to the retail ISA fund selection page
2. Customer selects a single fund from available options
3. Customer enters investment amount in the displayed input field
4. Customer confirms by clicking "Deposit" button
5. System confirms successful transaction and stores the investment details
6. Customer can later view their investment history

### Assumptions
- Funds are specific to the ISA selected
- Funds could be offered to both retail and employer-based customers (i.e. not customer type specific)
- Authentication and authorization systems already exist and can be leveraged
- The user interface follows a single-page rather than separate pages for each step
- No additional validation beyond basic investment functionality is required for this phase (such as minimum/maximum investment amount, currency is in GBP, customer has sufficient funds in their account to complete the transition)
- After selecting the fund and providing an amount, the user will also need to click on deposit to record the selection and amount.
- The actual payment processing (transferring funds from the customer's bank account to Cushon) is handled by an existing payment service and is outside the scope of this implementation.

### BDD Scenarios
```gherkin
  Feature: Cushon ISA for retail customers
    As a retail customer
    I want to be able to select a fund within my Cushon ISA and specify an investment amount
    So that I can invest my money according to my preferences

    Scenario: Retrieve available funds
      GIVEN I am an authenticated retail customer
      WHEN I send a GET request to "/api/retail/isa/funds"
      THEN the response status code should be 200
      AND the response should contain a list of available funds including "Cushon Equities Fund"

    Scenario: Create a new investment
      GIVEN I have selected "Cushon Equities Fund" as a fund I'd like to invest in
      WHEN I send a POST request to "/api/retail/isa/investments" with:
      | fund_id    | [fund_id for "Cushon Equities Fund"] |
      | amount     | 25000                                |
      THEN the response status code should be 201
      AND the response should include the investment details
      AND the investment should be stored in the database

    Scenario: Retrieve investment history
      GIVEN I am an authenticated retail customer
      AND I have previously made an investment of "£25,000" in "Cushon Equities Fund"
      WHEN I send a GET request to "/api/retail/isa/investments"
      THEN the response status code should be 200
      AND the response should include my investment details
```

### Key Decisions

#### Postgres for the database
- Financial transactions require absolute data integrity. Postgres offers full ACID compliance, ensuring that each investment transaction is either completely processed or not processed at all - critical for financial data.
- Postgres also handles concurrent reads and writes better than MySQL, particularly in high-transaction environments like financial applications.

#### REST API Separation Strategy
- The separation of retail and employer APIs (with the /retail prefix) ensures these two business domains can evolve independently. For example, adding new retail-specific features won't impact employer endpoints
- The API can support both web and mobile clients because:
    - It uses standard JSON responses that work across platforms
    - Authentication is handled via tokens that work in any client environment
    - The same endpoints can serve both web and mobile interfaces without duplication

#### SQLite for Testing
- SQLite was chosen for the development cycle because it requires no separate database server setup, reducing developer onboarding time.
- In production, Postgres would be used for its stronger concurrency controls and better scaling capabilities.

#### Authentication Implementation:
The auto-authentication middleware provides several benefits:
- It simulates a logged-in user for all API requests without requiring login screens
- The middleware injects consistent user context, making testing more reliable
- It demonstrates authentication concepts without the complexity of implementing a full auth system
- The approach can be easily replaced with proper authentication in production

#### Customer Type as a Column vs. Separate Tables
-  Implemented customer types as a database column rather than separate tables to reduce schema complexity and maintenance overhead while improving query performance. This approach simplifies reporting, enables code reuse, and accommodates users transitioning between customer types without data migration.

#### Future-Ready Investment Architecture
- Designed a flexible data model that supports the current single-fund requirement while enabling future multi-fund selection
- Created a separate `fund_allocations` table instead of a direct foreign key in the investments table
- This approach allows adding multiple fund selections without schema changes or data migrations in the future
- Frontend components use array structures even for single selections to make future expansion seamless

#### Transaction Integrity
- Implemented database transactions to ensure atomicity of investment operations
- Used locking to prevent race conditions
- Added retry mechanism for handling potential deadlocks in high-concurrency scenarios

### Improvements

#### BDD Testing Enhancement
- Implement Behat/Codeception for BDD-style testing to better match the existing Gherkin scenarios in the README
- Current tests follow a more traditional PHPUnit approach but could benefit from a more explicit BDD structure

#### API Optimisation
- Reduce API calls when switching to investment history tab - currently a new fetch is triggered each time
- Implement caching or state persistence between tab switches

#### Money Handling
- Add a custom Money cast for Eloquent to eliminate repetitive conversion code ($amount->getAmountInPennies() when saving, $amount->getAmountInPounds() when retrieving)
- The current Money value object implementation is good but could be integrated better with the ORM

#### Internationalization
- Make frontend components translatable - currently all text is hardcoded
- Implement locale-aware formatting for currency values

#### Architectural Considerations
- Consider domain-driven design for future scaling
- The current MVC architecture works but may limit flexibility as complexity grows

#### Audit & Logging
- Add comprehensive logging of financial operations, especially when placing investments
- Implement an audit trail for tracking user activity

#### Controller Refactoring
- `InvestmentController::store` is handling too many responsibilities
- Refactor into a service class to improve separation of concerns and testability

#### Transaction Safety
- Add test coverage for investment concurrency issues
- Ensure that partial completion doesn't occur during transaction failures
- Implement fund locking to prevent concurrent updates to the same fund
