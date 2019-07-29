<?php
require_once 'vendor/autoload.php';

/**
 * Class EngageAPI
 */
class EngageAPI
{
    private $contentData;
    private $guzzleClient;
    private $app_id;
    private $api_key;
    private $secret_key;
    private $content_types;


    /**
     * @var string
     * This field can contain external database field id reference or leave empty to generate a new one.
     * @example "id"
     */
    public $guid = "";


    /**
     * @var string
     * The status of the content.
     * @example "publish", "draft"
     */
    public $status = "publish";


    /**
     * @var integer
     */
    public $status_code = 0;


    /**
     * @var string
     * Type of the content you post.
     * @example "news", "infographics", etc.
     */
    public $type = "";


    /**
     * @var integer
     * Type id of the content you post.
     * @see EngageAPI::get_type_ids()
     */
    public $type_id = 0;


    /**
     * @var boolean
     * Delete status of the content. Defaults to false.
     */
    public $trash = false;


    /**
     * @var string
     * This field is used for Curated contents like Facebook, Twitter, Youtube, etc to store the embed code
     * web view link.
     */
    public $link = "";


    /**
     * @var string
     * The title of the content. This is a required field.
     */
    public $title = "";


    /**
     * @var string
     * The description of the content.
     */
    public $desc = "";


    /**
     * @var string
     * This field is used for long contents like TEXT field in mysql.
     */
    public $content = "";


    /**
     * @var string
     * The image url of the content.
     */
    public $thumbnail = "";


    /**
     * @var array
     */
    public $media = array();


    /**
     * @var string
     * The user id of who create / update the content.
     */
    public $user = "";


    /**
     * @var stdClass
     * The meta field is used to store custom key-value pairs.
     */
    public $meta;


    /**
     * @var array
     */
    public $visible_to = array();


    /**
     * @var array
     */
    public $categories = array();


    /**
     * @var array
     */
    public $tags = array();

    /**
     * @var array
     * 'title', 'desc', 'content', 'guid', 'user', 'meta', 'tags',
     * 'link', 'thumbnail', 'status', 'status_code', 'type', 'type_id', 'media', 'visible_to', 'categories'
     */
    public $update_data = array();

    private $result_data;

    public function __construct($api_key, $secret_key)
    {
        $this->meta = new stdClass();
        $this->app_id = $api_key;
        $this->api_key = $api_key;
        $this->secret_key = $secret_key;
        $this->content_types = array(1 => 'Long News', 13 => 'Short News', 10 => 'Infographics', 12 => 'Twitter',
            8 => 'Instagram', 15 => 'Facebook', 3 => 'Youtube', 20 => 'Vimeo', 16 => 'Other Website - Curated Content');
        $this->guzzleClient = new GuzzleHttp\Client(array(
            // Base URI is used with relative requests
            'base_uri' => 'http://127.0.0.1:5000/',
            // You can set any number of default request options.
            'timeout'  => 30.0,
        ));
        $this->contentData = array(
            "guid" => "",
            "status" => "",
            "status_code" => "",
            "type" => "",
            "type_id" => 0,
            "trash" => false,
            "link" => "",
            "title" => "",
            "desc" => "",
            "content" => "",
            "thumbnail" => "",
            "media" => array(),
            "user" => "",
            "meta" => new stdClass(),
            "visible_to" => array(),
            "categories" => array(),
            "tags" => array()
        );
        if (!isset($_SESSION['access_token'])) {
            $_SESSION['access_token'] = '';
        }
        if (!isset($_SESSION['refresh_token'])) {
            $_SESSION['refresh_token'] = '';
        }
        $this->result_data = null;
    }

    private function api_auth() {
        $response = $this->guzzleClient->post('auth', array(
                'headers' => array('Content-Type'=> 'application/json'),
                'json' => array('api_key'=> $this->api_key, 'secret_key'=> $this->secret_key)
            )
        );
        return $response->getBody()->getContents();
    }

    private function api_refresh_auth() {
        $response = $this->guzzleClient->post('refresh-token', array(
                'headers' => array('Content-Type'=> 'application/json', 'Authorization'=> 'Bearer '.$_SESSION['refresh_token']),
            )
        );
        return $response->getBody()->getContents();
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function get_contents() {
        $method = 'GET';
        $this_method = __FUNCTION__;
        $endpoint = 'content';
        try {
            $method = strtolower($method);
            $response = $this->guzzleClient->$method($endpoint, array(
                'headers'=> array('Authorization'=> 'Bearer '.$_SESSION['access_token'])
            ));
            $response = $response->getBody()->getContents();
            $this->result_data = json_decode($response, true);
            return $this->result_data;
        }
        catch (Exception $exception) {
            $this->tryAuth($this_method, $exception);
            return $this->result_data;
        }
    }

    /**
     * @param int $page_number
     * @return mixed
     * @throws Exception
     */
    public function get_contents_by_page_number($page_number = 0) {
        $method = 'GET';
        $this_method = __FUNCTION__;
        $endpoint = 'content/page/'.$page_number;
        try {
            $method = strtolower($method);
            $response = $this->guzzleClient->$method($endpoint, array(
                'headers'=> array('Authorization'=> 'Bearer '.$_SESSION['access_token'])
            ));
            $response = $response->getBody()->getContents();
            $this->result_data = json_decode($response, true);
            return $this->result_data;
        }
        catch (Exception $exception) {
            $this->tryAuth($this_method, $exception, array($page_number));
            return $this->result_data;
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function get_content($id) {
        $this->guid = $id;
        $method = 'GET';
        $this_method = __FUNCTION__;
        $endpoint = 'content/'.$this->guid;
        try {
            $method = strtolower($method);
            $response = $this->guzzleClient->$method($endpoint, array(
                'headers'=> array('Authorization'=> 'Bearer '.$_SESSION['access_token'])
            ));
            $response = $response->getBody()->getContents();
            $this->result_data = json_decode($response, true);
            return $this->result_data;
        }
        catch (Exception $exception) {
            $this->tryAuth($this_method, $exception, array($this->guid));
            return $this->result_data;
        }
    }

    private function prepare_data() {
        $this->contentData = array(
            "guid" => $this->guid,
            "status" => $this->status,
            "status_code" => $this->status_code,
            "type" => $this->type,
            "type_id" => $this->type_id,
            "trash" => $this->trash,
            "link" => $this->link,
            "title" => $this->title,
            "desc" => $this->desc,
            "content" => $this->content,
            "thumbnail" => $this->thumbnail,
            "media" => $this->media,
            "user" => $this->user,
            "meta" => $this->meta,
            "visible_to" => $this->visible_to,
            "categories" => $this->categories,
            "tags" => $this->tags
        );
    }

    private function tryAuth($callback, $exception, $params=array()) {
        if ($exception instanceof GuzzleHttp\Exception\RequestException || $exception instanceof \GuzzleHttp\Exception\ConnectException) {
            if ($exception->getResponse()->getStatusCode() == 422 || $exception->getResponse()->getStatusCode() == 401) {
                try {
                    $response = $this->api_refresh_auth();
                    $tokens = json_decode($response, true);
                    $access_token = $tokens['access_token'];
                    $_SESSION['access_token'] = $access_token;
                    $this->$callback(implode(', ', $params));
                }
                catch (Exception $exception) {
                    $response = $this->api_auth();
                    $tokens = json_decode($response, true);
                    $_SESSION['access_token'] = $tokens['access_token'];
                    $_SESSION['refresh_token'] = $tokens['refresh_token'];
                    $this->$callback(implode(', ', $params));
                }
            }
            else {
                log_message('error', $exception->getTraceAsString());
                $message = $exception->getResponse()->getBody()->getContents();
                $message = json_decode($message);
                $message = $message->message;
                throw new Exception($message);
            }
        }
        else {
            if ($exception != '') {
                log_message('error', $exception->getTraceAsString());
                $message = $exception->getMessage();
                throw new Exception($message);
            }
        }
    }

    /**
     * @return array
     */
    public function get_type_ids() {
        return $this->content_types;
    }
}