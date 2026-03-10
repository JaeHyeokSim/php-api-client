# PHP API Client

Reusable API client written in PHP.

## Features

- API request abstraction
- automatic retry
- timeout control
- parameter merging
- simple memory cache

## Example

```php
$client->get("/posts", ["userId"=>1]);
