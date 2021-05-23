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



#### Create Payment

Request

```json
{
    "account_uuid": "b0cf1ad8-2c96-48c0-97a0-77529f1b16e1",
    "payment_card": {
        "number": "4242424242424242",
        "exp_month": "04",
        "exp_year": 2020,
        "cvc": 232
    },
    "amount": 1000
}
```

