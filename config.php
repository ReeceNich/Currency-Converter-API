<?php
define("CONFIG", array("base" => ($base = "GBP"),
                        "key" => ($key = ""),
                        "api_url" => "https://freecurrencyapi.net/api/v2/latest?apikey=" . $key . "&base_currency=" . $base,
                        "currencies" => "currencies.xml",
                        "source_currencies" => "source_currencies4217.xml",
                        "defaultcurrencies" => array("AUD", "BRL", "CAD", "CHF", "CNY", "DKK", "EUR", "GBP", "HKD", "HUF", "INR", "JPY", "MXN", "MYR", "NOK", "NZD", "PHP", "RUB", "SEK", "SGD", "THB", "TRY", "USD", "ZAR")
                    ));
?>