# curl-helper

### Default Parameters
```php
$config = [
  // Title for recognize
  title => '',
  // Targe url
  url => '',
  // Request method
  type => '',
  // Request argements
  data => [],
  // Extra curl options
  curlOpt => [],
  // Cookie content
  cookies => [],
];
```

### Excute Crawler
```php
// Set config
$config = new CrawlerConfig([
  'title' => '',
  'url' => '',
  'type' => '',
  'data' => [],
  'curlOpt' => [],
  'cookies' => [],
]);

// Execute crawler
$result = Crawler::run($config)
```

### Data
| id   | username | password   | email             |
| ---- | -------- | ---------- | ----------------- |
| 5241 | admin    | 123456     | admin@example.com |
| 6542 | user1    | user1_pass | user1@example.com |
| 6543 | user2    | user2_pass | user2@example.com |

---

## Example

### Get method

#### List all data
  - Example:
    ```php
    $config = new CrawlerConfig([
      'title' => 'List all data',
      'url' => 'https://example.com/tests/fakeWeb/index.php',
      'type' => 'get',
    ]);

    $result = Crawler::run($config);
    ```
  - Output:
    ```php
    $result = [
      'code' => 200,
      'message' => 'success',
      'data' => [
        '5241' => [
          'id' => 5241,
          'username' => 'admin',
          'password' => '123456',
          'email' => 'admin@example.com'
        ],
        '6542' => [
          'id' => 6542,
          'username' => 'user1',
          'password' => 'user1_pass',
          'email' => 'user1@example.com'
        ],
        '6543' => [
          'id' => 6543,
          'username' => 'user2',
          'password' => 'user2_pass',
          'email' => 'user2@example.com'
        ]
      ]
    ];
    ```

#### List someone data
  - example
    ```php
    $config = new CrawlerConfig([
      'title' => 'List someone member',
      'url' => 'https://example.com/tests/fakeWeb/index.php?id=6543',
      'type' => 'get',
    ]);

    $result = Crawler::run($config);
    ```
  - Output
    ```php
    $result = [
      'code' => 200,
      'message' => 'success',
      'data' => [
        'id' => 6543,
        'username' => 'user2',
        'password' => 'user2_pass',
        'email' => 'user2@example.com'
      ]
    ];
    ```

### Post method

#### login
  - example
    ```php
    $config = new CrawlerConfig([
      'title' => 'Login',
      'url' => 'https://example.com/tests/fakeWeb/index.php',
      'type' => 'post',
      'data' => [
        'username' => 'admin',
        'password' => '123456',
      ]
    ]);

    $result = Crawler::run($config);
    ```
  - Output:
    ```php
    $result = [
      'code' => 200,
      'message' => 'Login success',
    ];
    ```

### Put method

#### Edit data
  - example
    ```php
    $config = new CrawlerConfig([
      'title' => 'Edit data',
      'url' => 'https://example.com/tests/fakeWeb/index.php',
      'type' => 'put',
      'data' => [
        'id' => '1234',
        'email' => '123@gmail.com',
      ],
    ]);

    $result = Crawler::run($config);
    ```
  - Output
    ```php
    $result = [
      'code' => 200,
      'message' => 'success',
      'data' => [
        'id' => '1234',
        'email' => '123@gmail.com',
        'username' => 'admin',
        'password' => '123456'
      ]
    ];
    ```