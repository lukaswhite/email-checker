# Email Checker

A library for checking an e-mail address to see if it's from a provider of free or disposable providers.

It's essentially a PHP wrapper around the data provided by the [freemail](https://github.com/willwhite/freemail) project, with a straightforward way to keep the data up-to-date.

## Usage

First, you need to grab the data:

```php
$sync = new \Lukaswhite\EmailChecker\Data\Sync('./some/local/path');
$sync->fetch();
```

Then you're good to go:

```php
$checker = new \Lukaswhite\EmailChecker\Checker();

$result = $checker->check('hello@mailinator.com');

$result->isDisposable(); // true
$result->isFree(); // false
$result->isBlacklisted(); // false
```

To update the data; for example, via a cron job:

```php
$sync = new \Lukaswhite\EmailChecker\Data\Sync('./some/local/path');
$sync->update();
```

## Under the Hood

The package works by fetching the [freemail](https://github.com/willwhite/freemail) package using Git into a local folder, then looking up an email's domain from that data.

Running the update method simply pulls in any changes from the remote Git repository. 