# MIPS
MIPS e-commerce integration. See [https://www.mips.mu/orchestration](https://www.mips.mu/orchestration)

## Usage

```php
use nadimattari\mips\mips;

$mips = new mips([
    'id_merchant'     => 'respective-values-here',
    'id_entity'       => 'respective-values-here',
    'operator'        => 'respective-values-here',
    'operator_pass'   => 'respective-values-here',
    'salt'            => 'respective-values-here',
    'cipher_key'      => 'respective-values-here',
    'basic_auth_user' => 'respective-values-here',
    'basic_auth_pass' => 'respective-values-here',
]);

$response = $mips
    ->setPayload([
        'order' => [
            'id_order' => 'INV-00011',
            'currency' => 'MUR',
            'amount'   => $total,
        ],
        'iframe_behavior' => [
            'height"                 => 500,
            'width"                  => 960,
            'language"               => 'EN',
            'custom_redirection_url' => 'https://my-domain.tld/display-payment-done',
        ],
    ])
    ->loadPaymentZone()
;

// $response = [
//     'original_message' => 'string',
//     'answer' => [
//         "operation_status' => 'success',
//         "payment_zone_data'=> 'html-string',
//     ],
// ];
```

## Mips Documentation
The documentation can be found here: [https://docs.mips.mu/](https://docs.mips.mu/)

### 1. Load Payment Zone

```http request
POST https://api.mips.mu/api/load_payment_zone
```

This API call gives back a HTML code, generating the iframe. It allows the 
merchant to output an HTML payment zone within an iframe.

Important: The result of this API call is NOT a Token nor a payment result. The 
Token or the payment result will be sent to the IMN URL given by the merchant 
only when a tokenization or payment is successful.

The look & feel of the iFrame is automatically generated.

#### CURL Request

```shell
$ curl --request POST \
  --url https://api.mips.mu/api/load_payment_zone \
  --header 'Authorization: Basic dXNlcjpwYXNz' \
  --header 'Content-Type: application/json' \
  --header 'user-agent: ' \
  --data '{
  "authentify": {
    "id_merchant": "q7r79YV13XjisGDnGgRw7pVMGSagfRzx",
    "id_entity": "Dem1091uOLSIVQPnLYuVTtmkfGppLo0t",
    "id_operator": "w8kvu7ShJrbRnVy54CjGpakWGj6H5zJy",
    "operator_password": "G2JvCxTo2LJpZC3a9zNN9LlCdzjwf9X0"
  },
  "order": {
    "id_order": "INV5026",
    "currency": "MUR",
    "amount": 10.25
  },
  "iframe_behavior": {
    "height": 400,
    "width": 350,
    "custom_redirection_url": "www.example.com",
    "language": "EN"
  },
  "request_mode": "simple",
  "touchpoint": "native_app",
  "odrp": {
    "max_amount_total": 0,
    "max_amount_per_claim": 0,
    "max_frequency": 0,
    "max_date": "2019-08-24"
  },
  "membership": {
    "interval": 1,
    "start_date": "2019-08-24",
    "frequency": "day",
    "end_date": "2019-08-24",
    "day_to_process": 5,
    "membership_amount": 1240.05
  },
  "additional_params": [
    {
      "param_name": "string",
      "param_value": "string"
    }
  ]
}'
```
#### HTTP Request

```http request
POST /api/load_payment_zone HTTP/1.1
Content-Type: application/json
User-Agent: 
Authorization: Basic dXNlcjpwYXNz
Host: api.mips.mu

{
  "authentify": {
    "id_merchant": "q7r79YV13XjisGDnGgRw7pVMGSagfRzx",
    "id_entity": "Dem1091uOLSIVQPnLYuVTtmkfGppLo0t",
    "id_operator": "w8kvu7ShJrbRnVy54CjGpakWGj6H5zJy",
    "operator_password": "G2JvCxTo2LJpZC3a9zNN9LlCdzjwf9X0"
  },
  "order": {
    "id_order": "INV5026",
    "currency": "MUR",
    "amount": 10.25
  },
  "iframe_behavior": {
    "height": 400,
    "width": 350,
    "custom_redirection_url": "www.example.com",
    "language": "EN"
  },
  "request_mode": "simple",
  "touchpoint": "native_app",
  "odrp": {
    "max_amount_total": 0,
    "max_amount_per_claim": 0,
    "max_frequency": 0,
    "max_date": "2019-08-24"
  },
  "membership": {
    "interval": 1,
    "start_date": "2019-08-24",
    "frequency": "day",
    "end_date": "2019-08-24",
    "day_to_process": 5,
    "membership_amount": 1240.05
  },
  "additional_params": [
    {
      "param_name": "string",
      "param_value": "string"
    }
  ]
}
```

#### JSON Response

```json
{
  "original_message": "string",
  "answer": {
    "operation_status": "success",
    "payment_zone_data": "string"
  }
}
```

### 2. Decrypt IMN Callback data

#### IMN callback architecture

An URL defined upfront and hosted on Merchants side. This URL is triggered by
MiPS ONLY WHEN A SUCCESSFUL PAYMENT IS MADE Your IMN URL will be asked by MiPS
Team on account opening.

```http request
POST https://api.mips.mu/api/decrypt_imn_data
```

This API is to be called when the merchant receives an IMN Callback.

#### CURL Request

```shell
$ curl --request POST \
  --url https://api.mips.mu/api/decrypt_imn_data \
  --header 'Authorization: Basic dXNlcjpwYXNz' \
  --header 'Content-Type: application/json' \
  --header 'user-agent: ' \
  --data '{
  "authentify": {
    "id_merchant": "q7r79YV13XjisGDnGgRw7pVMGSagfRzx",
    "id_entity": "Dem1091uOLSIVQPnLYuVTtmkfGppLo0t",
    "id_operator": "w8kvu7ShJrbRnVy54CjGpakWGj6H5zJy",
    "operator_password": "G2JvCxTo2LJpZC3a9zNN9LlCdzjwf9X0"
  },
  "salt": "string",
  "cipher_key": "string",
  "received_crypted_data": "string"
}'
```
#### CURL Request

```http request
POST /api/decrypt_imn_data HTTP/1.1
Content-Type: application/json
User-Agent: 
Authorization: Basic dXNlcjpwYXNz
Host: api.mips.mu
Content-Length: 331

{
  "authentify": {
    "id_merchant": "q7r79YV13XjisGDnGgRw7pVMGSagfRzx",
    "id_entity": "Dem1091uOLSIVQPnLYuVTtmkfGppLo0t",
    "id_operator": "w8kvu7ShJrbRnVy54CjGpakWGj6H5zJy",
    "operator_password": "G2JvCxTo2LJpZC3a9zNN9LlCdzjwf9X0"
  },
  "salt": "string",
  "cipher_key": "string",
  "received_crypted_data": "string"
}
```

#### JSON Response

```json
{
  "amount": "string",
  "currency": "string",
  "status": "success",
  "checksum": "string",
  "id_order": "string",
  "transaction_id": "string",
  "type": "string",
  "reference": "string",
  "payment_method": "string",
  "additional_param": "string",
  "reason_fail": "string",
  "token": {
    "id_token": "stringstringstringstringstringstringstringstringstringstring",
    "token_for_id_order": "string",
    "token_exp_date": "string"
  },
  "card_details": {
    "masked_card_number": "123456xxxxxx1234",
    "expiry_date": "mm/yy"
  }
}
```
