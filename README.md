# Payment API (Prototype)

### Lumen Framework

Documentation for the framework can be found on the [Lumen website](https://lumen.laravel.com/docs).

## Installation and Configuration

The project can be setup in the same way in Laravel project. The below step provided is for setting up the project using Docker

### Docker

You should have docker installed on your computer already.

#### Run setup script

```shell
./setup.sh
```

Wait until everything up and running before moving on to the next step, database may take longer to configure at the first time

#### Setting up database

```shell
docker exec -it payment-api_app php artisan migrate:fresh --seed
```
###### Note

- Default URL: http://localhost:8000
- If experiencing error when running the solution in docker, it may down to directory permission settings. Check permission for `/logs` and `/docker/storage` folder in particular.
- The project only tested on the least copy of openSUSE Tumbleweed and should also work on other OS.

## Endpoints

### Swagger Documentation
http://localhost:8000/api/documentation

### Account

| HTTP Method | Path            | Description                                  |
| ----------- | --------------- | -------------------------------------------- |
| GET         | /account        | List accounts                                |
| POST        | /account        | Create account                               |
| GET         | /account/{uuid} | Get account along with their payment methods |
| DELETE      | /account/{uuid} | Delete account                               |

### Payment

| HTTP Method | Path                   | Description                                                  |
| ----------- | ---------------------- | ------------------------------------------------------------ |
| GET         | /payment               | Get payments, filter can be passed it through URL parameters. |
| POST        | /payment               | Create payment                                               |
| GET         | /payment/{uuid}        | Get a payment                                                |
| POST        | /payment/{uuid}/refund | Refund a payment                                             |

### Payment Method

| HTTP Method | Path                   | Description                         |
| ----------- | ---------------------- | ----------------------------------- |
| POST        | /payment_method        | Create payment method on an account |
| GET         | /payment_method/{uuid} | Get a payment method                |
| DELETE      | /payment_method/{uuid} | Remove a payment method             |

## Request examples

#### Get Account: [GET] /account/{uuid}

##### Response

```json
{
    "id": 1,
    "uuid": "be1799fe-edce-468e-9cf6-43a5d9e0c0a8",
    "default_payment_method_id": null,
    "created_at": "2021-05-24T20:38:24.000000Z",
    "updated_at": "2021-05-24T20:38:24.000000Z",
    "deleted_at": null,
    "payment_methods": [
        {
            "id": 1,
            "uuid": "d9d2e9fd-a606-44f5-812b-f1deb05a752b",
            "account_payment_provider_profile_id": 1,
            "external_id": "pm_3e02054631066b7c525ea4950eee3088cd2bf9e4",
            "card_type": "visa",
            "last4": 6613,
            "created_at": "2021-05-24T20:38:25.000000Z",
            "updated_at": "2021-05-24T20:38:25.000000Z",
            "deleted_at": null,
            "laravel_through_key": 1
        }
    ]
}
```

#### Create Payment: [POST] /payment

##### [Request] Set up a payment using card details

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

##### [Request] Set up a payment using existing payment method

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
        "created_at": "2021-05-23T19:20:31.000000Z",-
        "updated_at": "2021-05-23T19:20:31.000000Z",
        "deleted_at": null
    },
    "children": []
}
```

### Refund: [POST] /payment/{uuid}/refund

##### Request (Optional)

Specify refund amount, if not issuing full refund.

```json
{
    "amount": 1000
}
```

##### Response

```json
{
    "account_id": 1,
    "payment_method_id": 1,
    "parent_id": 1,
    "currency": "GBP",
    "amount": -45,
    "remark": "Refund: Duck and a sad tale!' said the Gryphon, before Alice could hear him sighing as if it please your Majesty!' the soldiers had to sing \"Twinkle, twinkle, little bat! How I wonder what they WILL do.",
    "type": "refund",
    "status": "success",
    "uuid": "64dd895c-1604-42b3-ac8e-bac8bc8dc0b6",
    "updated_at": "2021-05-24T21:30:47.000000Z",
    "created_at": "2021-05-24T21:30:47.000000Z",
    "id": 4,
    "parent": {
        "id": 1,
        "uuid": "2cabb62d-4aef-41bb-9192-c9d79fc14ed3",
        "account_id": 1,
        "payment_method_id": 1,
        "parent_id": null,
        "type": "P",
        "currency": "GBP",
        "amount": 1543,
        "remark": "Duck and a sad tale!' said the Gryphon, before Alice could hear him sighing as if it please your Majesty!' the soldiers had to sing \"Twinkle, twinkle, little bat! How I wonder what they WILL do.",
        "status": "S",
        "created_at": "2021-05-24T20:38:25.000000Z",
        "updated_at": "2021-05-24T20:38:25.000000Z"
    }
}
```

###### Note

- Amount used in the system are decimal amounts. In GBP, 1000 represent £10.00.
