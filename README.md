# Payment API (Prototype)

### Lumen Framework

Documentation for the framework can be found on the [Lumen website](https://lumen.laravel.com/docs).

## Installation and Configuration

## Endpoints

### Account
| HTTP Method  | Path                | Description                  |
|--------|----------------------------|------------------------------|
| GET    | /account                   | account.index                |
| POST   | /account                   | account.store                |
| GET    | /account/{uuid}            | account.show                 |
| DELETE | /account/{uuid}            | account.destroy              |

### Payment
| HTTP Method  | Path                | Description                  |
|--------|----------------------------|------------------------------|
| GET    | /payment                   | payment.index                |
| POST   | /payment                   | payment.store                |
| GET    | /payment/{uuid}            | payment.show                 |
| PUT    | /payment/{uuid}            | payment.update               |
| DELETE | /payment/{uuid}            | payment.destroy              |
