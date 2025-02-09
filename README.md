# A Laravel package to review content and images using ChatGPT API

[![Latest Version on Packagist](https://img.shields.io/packagist/v/gamalkh299/gpt-content-reviewer.svg?style=flat-square)](https://packagist.org/packages/gamalkh299/gpt-content-reviewer)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/gamalkh299/gpt-content-reviewer/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/gamalkh299/gpt-content-reviewer/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/gamalkh299/gpt-content-reviewer/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/gamalkh299/gpt-content-reviewer/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/gamalkh299/gpt-content-reviewer.svg?style=flat-square)](https://packagist.org/packages/gamalkh299/gpt-content-reviewer)

This package allows you to review the content of a model using ChatGPT Moderation API.

## Installation

You can install the package via composer:

```bash
composer require gamalkh299/gpt-content-reviewer
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="gpt-content-reviewer"
php artisan migrate
```

This is the contents of the published config file:

```php
return [
    'key' => env('CHAT_GPT_API_KEY'),
    'queue' => env('GPT_QUEUE', 'gpt-content-reviewer'),
];
```


## Usage

- At fisrt, you need to get an API key from [ChatGPT](https://www.openai.com/).
- Then, you need to add the API key to your `.env` file.
```bash
CHAT_GPT_API_KEY=your-api-key
```
Then add the following trait to the model you want to review its content.
```php
use gamalkh\GptContentReviewer\Traits\Reviewable;
```

Don't forget to implement the **getReviewableColumns** abstract function to specify the columns you want to review and **handleReviewResult** abstract function to handle the review result. 
```php
public function getReviewableColumns(): array
{
   // return the columns you want to review
    return ['content'];
}

public function handleReviewResult($result)
{
    // handle the review result
}
```

before you use it you need to run the following command to create the review table.
```bash
php artisan queue:work --queue=gpt-content-reviewer
```

you can use the package to create a review for a model and it will be added to the queue to be reviewed.
```php

$model = Model::find(1);
$gptContentReviewer = new gamalkh\GptContentReviewer();
$gptContentReviewer->createReview($model);
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Gamal Khaled](https://github.com/gamalkh299)
## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
