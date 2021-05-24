# Payment API (Prototype)

### Lumen Framework

Documentation for the framework can be found on the [Lumen website](https://lumen.laravel.com/docs).

## Installation and Configuration
### Docker
```
docker-compose up
```

## Endpoints

### Account

| HTTP Method  | Path                | Description                  |
|--------|----------------------------|------------------------------|
| GET    | /account                   | List accounts               |
| POST   | /account                   | Create account               |
| GET    | /account/{uuid}            | Show account along with their payment methods                |
| DELETE | /account/{uuid}            | Delete account          |

### Payment

| HTTP Method  | Path                | Description                  |
|--------|----------------------------|------------------------------|
| GET    | /payment                   | Get payment linked to an account|
| POST   | /payment                   | Create payment             |
| GET    | /payment/{uuid}            | Get a payment               |
| POST    | /payment/{uuid}/refund     | Refund a payment             |

#### Create Payment

##### Set up a payment using card details

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

##### Set up a payment using existing payment method

```json
{
  "account_uuid": "b0cf1ad8-2c96-48c0-97a0-77529f1b16e1",
  "payment_method_id": "5013d53c-8263-42c1-bc27-d7f5a6b82fa1",
  "amount": 1000
}
```

##### Response
```json
{
    "type": "payment",
    "currency": "GBP",
    "amount": 1000,
    "remark": null,
    "status": "success",
    "account_id": 2,
    "payment_method_id": 2,
    "uuid": "ee15d9d1-2415-497e-8b51-1bebaa3dac8c",
    "updated_at": "2021-05-23T22:19:50.000000Z",
    "created_at": "2021-05-23T22:19:50.000000Z",
    "id": 44,
    "charged": 1000,
    "payment_method": {
        "id": 2,
        "uuid": "88c6b990-e2ba-4a85-b90c-48c675465a58",
        "account_payment_provider_profile_id": 2,
        "external_id": null,
        "card_type": "visa",
        "last4": 1223,
        "created_at": "2021-05-23T19:20:31.000000Z",
        "updated_at": "2021-05-23T19:20:31.000000Z",
        "deleted_at": null
    },
    "children": []
}
```
