# EngageAPI_PHP
 
Introduction: ...
### Installation

##### PHP compatibility
Current releases require PHP 5.5 or higher, and depends on Guzzle version 6.

You need to install Guzzle 6 through composer.

You can add Guzzle as a dependency using the composer.phar CLI:

<code>php composer.phar require guzzlehttp/guzzle:~6.0</code>

### Documentation
Our full documentation for this package is available at URL.

##### Quick start
First, signup here for a free account and grab your API key and secret.

###### Initiating a Feed object:

Instantiate a feed object, find your API key and SECRET key in the dashboard.

<code>require_once 'EngageAPI.php';

$client = new EngageAPI('YOUR_API_KEY', 'YOUR_SECRET_KEY');</code>

###### Get the latest feed contents for this particular project.

<code>$feed = $client->get_contents();</code>

This function returns the last 10 feeds.

The response will be the json decoded API response.

<code>{"result": [...], "status_code": 200, "page_no": 0}</code>

###### Get the feed contents by page, defaults to 0.

<code>$feed = $client->get_contents_by_page_number($page_number);</code>

The response will be the json decoded API response.

<code>{"result": [...], "status_code": 200, "page_no": $page_number}</code>

This function returns 10 feeds which has been created or updated recently, page by page.

###### Get the feed content by its guid.

<code>$feed = $client->get_content($guid);</code>

The response will be the json decoded API response.

<code>{"result": [...], "status_code": 200}</code>

Again, our full documentation with all options and methods, is available at URL.
