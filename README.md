# carbon-extended

## Introduction

- It can use some extended format on `Carbon::format` method

## Installation

Using the `composer` to install this package

```BASH
composer require lee/carbon-extended:^1.0
```

## Usage

- The `CarbonExtended` class extends `Carbon` and it can use `createFromFormat` to create the `Carbon` class instance and do format with extended foramts.

```php
use Lee\CarbonExtended;

$date = '2013-03-17';
$customizedFormat = 'QTR.';

$carbonExtended = CarbonExtended::createFromFormat('Y-m-d', $date);
$result = $carbonExtended->extendedFormat($customizedFormat); // 1
```

## Available extended date formats

- The customized format name is refer on SAS date format
- Here are available extended formats: (it will br more customized formats...)

| customized format name |                                                                                    references                                                                                    |
| ---------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| QTR.                   | [QTR. reference](https://documentation.sas.com/?docsetId=leforinforref&docsetTarget=n0mupey76zmd4sn15y96a03lrumz.htm&docsetVersion=1.0&locale=en)                                |
| JULDAY3.               | [JULDAY3. reference](https://documentation.sas.com/?docsetId=leforinforref&docsetTarget=p0rzo1ok9zk0x4n1oz50uf9a5jle.htm&docsetVersion=1.0&locale=en)                            |
| SAS_DATE_VALUE         | [SAS_DATE_VALUE reference](https://v8doc.sas.com/sashtml/lrcon/zenid-63.htm) |
| JULIAN5.               | [JULIAN5. reference](https://documentation.sas.com/?docsetId=leforinforref&docsetTarget=p0f7u4z8iuaoygn183i6q1yc49xb.htm&docsetVersion=3.1&locale=en)                            |
| JULIAN7.               | [JULIAN7. reference](https://documentation.sas.com/?docsetId=leforinforref&docsetTarget=p0f7u4z8iuaoygn183i6q1yc49xb.htm&docsetVersion=3.1&locale=en)                            |
| PDJULG4.               | [PDJULG4. reference](https://documentation.sas.com/?docsetId=leforinforref&docsetTarget=n1gtaebcye6hpan1g6lcq2bj6580.htm&docsetVersion=1.0&locale=en)                            |


## References

[SAS date documentation](https://documentation.sas.com/?docsetId=lrcon&docsetTarget=p1wj0wt2ebe2a0n1lv4lem9hdc0v.htm&docsetVersion=9.4&locale=en#n1franwnd7n7yrn1kasbprbtzroo)
