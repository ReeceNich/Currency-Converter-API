<?php
define("CONFIG", array("base" => ($base = "GBP"),
                        "key" => ($key = ""),
                        "api_url" => "https://freecurrencyapi.net/api/v2/latest?apikey=" . $key . "&base_currency=" . $base,
                        "currencies" => "currencies.xml",
                        "source_currencies" => "source_currencies4217.xml"
                    ));
?>