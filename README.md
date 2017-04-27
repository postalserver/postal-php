# Postal for PHP

This library helps you send e-mails through [Postal](https://github.com/atech/postal) in PHP 5.4 and above.

## Installation

Install the library using [Composer](https://getcomposer.org/):

```
$ composer require postal/postal
```

## Usage

Sending an email is very simple. Just follow the example below. Before you can begin, you'll
need to login to our web interface and generate a new API credential.

```php
// Create a new Postal client using the server key you generate in the web interface
$client = new Postal\Client('https://postal.yourdomain.com', 'your-api-key');

// Create a new message
$message = new Postal\SendMessage($client);

// Add some recipients
$message->to('john@example.com');
$message->to('mary@example.com');
$message->cc('mike@example.com');
$message->bcc('secret@awesomeapp.com');

// Specify who the message should be from. This must be from a verified domain
// on your mail server.
$message->from('test@test.postal.io');

// Set the subject
$message->subject('Hi there!');

// Set the content for the e-mail
$message->plainBody('Hello world!');
$message->htmlBody('<p>Hello world!</p>');

// Add any custom headers
$message->header('X-PHP-Test', 'value');

// Attach any files
$message->attach('textmessage.txt', 'text/plain', 'Hello world!');

// Send the message and get the result
$result = $message->send();

// Loop through each of the recipients to get the message ID
foreach ($result->recipients() as $email => $message) {
    $email;            // The e-mail address of the recipient
    $message->id();    // Returns the message ID
    $message->token(); // Returns the message's token
}
```
