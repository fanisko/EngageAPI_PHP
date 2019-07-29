# EngageAPI_PHP - v0.1
One stop content publishing API
Create & Curate content from anywhere into your platform with ease. Post news, infographics, curate content from social media sites and keep your feed up-to-date!

## What you can do
<ul><li>Retrieve all feeds</li></ul>

## Methods
<ul>
    <li>get_content($guid)</li>
    <li>get_contents()</li>
    <li>get_contents_by_page_number($page_number)</li>
</ul>

## Configuration

<table>
<tr align="left">
<th>Name</th>
<th>Type</th>
<th>Required</th>
<th>Description</th>
</tr>
<tr>
<td>api_key</td>
<td>String</td>
<td>Yes</td>
<td>This is your project api key.</td>
</tr>
<tr>
<td>secret_key</td>
<td>String</td>
<td>Yes</td>
<td>This is your project secret key.</td>
</tr>

</table>

## Get your API key and Secret key
Sign up at engage.fanisko.com/api (it's free!).

Create a new project to generate the API key and Secret key.


## Installation

#### PHP compatibility
Current releases require PHP 5.5 or higher, and depends on Guzzle version 6.

You need to install Guzzle 6 through composer.

You can add Guzzle as a dependency using the composer.phar CLI:

```php
php composer.phar require guzzlehttp/guzzle:~6.0
```
## Documentation
Our full documentation for this package is available at URL.

#### Quick start
First, signup here for a free account and grab your API key and secret.

###### Initiating a Feed object:

Instantiate a feed object, find your API key and SECRET key in the dashboard.

```php
require_once 'EngageAPI.php';

$client = new EngageAPI('YOUR_API_KEY', 'YOUR_SECRET_KEY');
```
<hr>

###### Get the latest feed contents for this particular project.

```php
$feed = $client->get_contents();
```

This function returns the last 10 feeds.

The response will be the json decoded API response.

```json
{
    "result": ["..."], 
    "status_code": 200, 
    "page_no": 0
}
```
<hr>

###### Get the feed contents by page, defaults to 0.

```php
$feed = $client->get_contents_by_page_number($page_number);
```

The response will be the json decoded API response.

```json
{
    "result": ["..."], 
    "status_code": 200, 
    "page_no": 0,
}
```
This function returns 10 feeds which has been created or updated recently, page by page.

<hr>

###### Get the feed content by its guid.

```php
$feed = $client->get_content($guid);
```

The response will be the json decoded API response.

```json
{
    "result": ["..."], 
    "status_code": 200
}
```

<hr>

Again, our full documentation with all options and methods, is available at URL.
