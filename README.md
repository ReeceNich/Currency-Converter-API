# Currency Converter API Coursework

This API converts well-known currencies using live rates (up to 2 hours old) and returns results in json or xml.

## How to Use the Converter

To use the converter API, place this folder in your web server directory, navigate to this project's root folder in your web browser and type:

```
?from={}&to={}&amnt={}&format={}
```

### Parameters
- 'from' = Currency Code (ISO 4217)
- 'to' = Currency Code (ISO 4217)
- 'amnt' = Integer or decimal currency value
- 'format' (Optional) = must equal 'xml' or 'json' - defaults to 'xml'.

## Before Using the API

You **must** obtain an API key for [Free Currency API](https://freecurrencyapi.net) and place the API key into the `config.php` file, otherwise this API service may not work.

## How to Update Currencies

Currencies can be updated, deleted or added to the API service.
This can be done by visiting the `update/` directory.

Navigate to this project's root folder in your web browser and type:
```
update/?cur={}&action={}
```

### Parameters

- 'cur' = Currency Code (ISO 4217)
- 'action' = 'put', 'post' or 'del'.

#### Action - explained:

- 'put' means update an existing currency's rate.
- 'post' means add a new currency to the API service.
- 'del' means disable an active currency from the API service (can be added again using post).

### Updating Currencies Graphically

You can also visit `update/test.php` to update, delete or add currencies using a web interface.

## Resetting the API to default

Visiting `factoryReset.php` will reset the API service back to default (e.g. the 24 default currencies, removes any modifications made using the 'post' and 'put' actions, and gets all their latest rates).

---

Copyright © 2021-2022 Reece Nicholls