# Config

Library for matching rulesets based on file configs

## Install

Via composer
```shell
composer require vmassalov/config
```

## Quick usage
Create yaml config file, e.g.:
```yaml
- conditions:
    dayOfWeek:
      - monday
      - tuesday
      - wednesday
      - thursday
      - friday
    projectPriority:
      - normal
      - minor
  result:
    salaryRate: 1
    needOvertimeApprove: true

- conditions:
    dayOfWeek:
      - monday
      - tuesday
      - wednesday
      - thursday
      - friday
    projectPriority: critical
  result:
    salaryRate: 1
    needOvertimeApprove: false

- conditions:
    projectPriority: critical
  result:
    salaryRate: 2
    needOvertimeApprove: false
```
All result blocks should contain same keys. All condition block can contain a different keys with single or multiple options.

```php
$configClient = \VMassalov\Config\ClientFactory::build('filesystem://./path/to/configs');
$configClient->find(
    'config.yaml',
    [
        'dayOfWeek' => 'sunday',
        'projectPriority' => 'critical',
    ],
);
```
Client will return a result of first full match item based on passed criteria
## Configuration

### DSN

filesystem://

## Test
Run unit and functional test in docker
```shell
make build && make test
```
Run unit and functional test locally
```shell
php ./vendor/bin/phpunit
```

## Roadmap

* [ ] Add Psalm
* [ ] Add PHPStan
* [ ] Change license to MIT

## Related
https://github.com/Nyholm/dsn?tab=readme-ov-file#definition
