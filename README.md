# Postal for PHP

This library helps you send e-mails through [Postal](https://github.com/postalserver/postal) in PHP 7.4 and above.

## Installation

Install the library using [Composer](https://getcomposer.org/):

```
$ composer require postal/postal
```

## Usage

Sending an email is very simple. Just follow the example below. Before you can begin, you'll
need to login to our web interface and generate a new API credential.

```php
 
    // Initialize Postal Client 
    $client = new Postal\Client("POSTAL_DOMAIN","POSTAL_APL_KEY");
    // Create a new message
    $sender = new Postal\SendService($client);

    // Add some recipients
    $message = new Postal\Send\Message();
    $message->to('recipient1@domain.com');
    $message->to('recipient2@anotherdomain.com');


    // Specify who the message should be from. This must be from a verified domain on your mail server.
    $message->from('test-api@warp.cc');
 
    // Set the subject
    $message->subject('Hi Test!');

    // Set the content for the e-mail
    $message->plainBody('Hello world!');
    $message->htmlBody('<p>Hello world!</p>');

    // Add any custom headers
    $message->header('X-PHP-Test', 'value');

    // Attach any files
    $message->attach('textmessage.txt', 'text/plain', 'Hello world!');

    // Send the message and get the result
    $result = $sender->message($message);

    foreach ($result->recipients() as $email => $message) {
        echo $email;  // The e-mail address of the recipient
        echo $message->id;// The message ID
        echo $message->token; // The message's token
    }

```
