# Cushon Interview Task

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

  Scenario: Retail customer selects a fund
    GIVEN I am on the "/retail/isa/funds" page
    WHEN I select "Cushon Equities Fund"
    THEN I should see an investment amount input field

  Scenario: Retail customer completes an investment
    GIVEN I have selected "Cushon Equities Fund"
    WHEN I enter an investment amount of "£25,000"
    AND I click the "Deposit" button
    THEN I should see a confirmation message "Deposit sent"
    AND my investment details should be stored in the system

  Scenario: System allows querying of investment details
    GIVEN I have completed an investment of "£25,000" in "Cushon Equities Fund"
    WHEN I navigate to "/retail/isa/investments"
    THEN I should see my investment details
```

### Improvements
