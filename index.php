<!-- in the name of god -->
<!-- news project index orginal page -->
<!-- my name is pouya i am a coder and programmir php js react jquery -->
<?php
# session started
session_start();

# use class object
use Admin\Category;
use database\CreateDB;
use database\DataBase;


# config setting
define('BASE_PATH', __DIR__);
define('CURRENT_DOMAIN', currentDomain() . '/news-project/');
define('DISPLAY_ERROR', true);
define('DB_HOST', 'localhost');
define('DB_NAME', 'news_project');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');


# require_once pages
require_once 'database/DataBase.php';
require_once 'database/CreateDB.php';
require_once 'activities/Admin/Admin.php';
require_once 'activities/Admin/Category.php';
require_once 'activities/Admin/Panel.php';
require_once 'activities/Admin/Post.php';
//$db = new DataBase();
//$db = new CreateDB();
//$db->run();
//$db->insert('users', ['username', 'email', 'password', 'role'], ['pouyapuma', 'pouya@gmail.com', '123456', 'admin']);


# Routing system
function uri($reservedUrl, $class, $method, $requestMethod = 'GET')
{
    $currentUrl = explode('?', currentUrl())[0];
    $currentUrl = str_replace(CURRENT_DOMAIN, '', $currentUrl);
    $currentUrl = trim($currentUrl, '/ ');
    $currentUrlArray = explode('/', $currentUrl);
    $currentUrlArray = array_filter($currentUrlArray);

    $reservedUrl = trim($reservedUrl, '/ ');
    $reservedUrlArray = explode('/', $reservedUrl);
    $reservedUrlArray = array_filter($reservedUrlArray);

    if (sizeof($reservedUrlArray) !== sizeof($currentUrlArray) || methodField() !== $requestMethod) {
        return false;
    }

    $parameters = [];
    for ($key = 0; $key < sizeof($currentUrlArray); $key++) {
        if ($reservedUrlArray[$key][0] === "{" && $reservedUrlArray[$key][strlen($reservedUrlArray[$key]) - 1] === "}") {
            array_push($parameters, $currentUrlArray[$key]);
        } elseif ($currentUrlArray[$key] !== $reservedUrlArray[$key]) {
            return false;
        }
    }

    if (methodField() === 'POST') {
        $request = isset($_FILES) ? array_merge($_POST, $_FILES) : $_POST;
        $parameters = array_merge([$request], $parameters);
    }

    $object = new $class;
    call_user_func_array(array($object, $method), $parameters);
    exit();
}


# helpers
# protocol http and https website
function protocol()
{
    return stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
}

# domain website
function currentDomain()
{
    return protocol() . $_SERVER['HTTP_HOST'];
}

# assets folder url
function asset($src)
{
    $domain = trim(CURRENT_DOMAIN, '/ ');
    $src = $domain . '/' . trim($src, '/ ');
    return $src;
}

# url link
function url($url)
{
    $domain = trim(CURRENT_DOMAIN, '/ ');
    $url = $domain . '/' . trim($url, '/ ');
    return $url;
}

# redirecting the user
function redirect($path)
{
    header('Location: ' . trim(BASE_PATH, '/ ') . '/' . trim($path, '/ ') . '/');
    exit;
}

# debugging
function dd($var)
{
    echo "<pre>";
    var_dump($var);
    exit;
}

# CDN icons bs
function icon()
{
    return "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css'>";
}

# url address active page
function currentUrl()
{
    return currentDomain() . $_SERVER['REQUEST_URI'];
}

# show method POST and GET
function methodField()
{
    return $_SERVER['REQUEST_METHOD'];
}

# show Display error message
function displayError($displayError)
{
    if ($displayError) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    } else {
        ini_set('display_errors', 0);
        ini_set('display_startup_errors', 0);
        error_reporting(0);
    }
}

displayError(DISPLAY_ERROR);

# CDN sweet alert
function swal()
{
    return "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
}

# message errors
global $flashMessage;
if (isset($_SESSION['flash_message'])) {
    $flashMessage = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);
}
function flash($name, $value = null)
{
    if ($value === null) {
        global $flashMessage;
        $message = isset($flashMessage[$name]) ? $flashMessage[$name] : '';
        return $message;
    } else {
        $_SESSION['flash_message'][$name] = $value;
    }
}

// panel admin
uri('admin', 'Admin\Panel', 'index');

// category
uri('admin/category', 'Admin\Category', 'index');
uri('admin/category/create', 'Admin\Category', 'create');
uri('admin/category/store', 'Admin\Category', 'store', 'POST');
uri('admin/category/edit/{id}', 'Admin\Category', 'edit');
uri('admin/category/update/{id}', 'Admin\Category', 'update', 'POST');
uri('admin/category/delete/{id}', 'Admin\Category', 'delete');

// posts
uri('admin/posts', 'Admin\Post', 'index');
echo '<h1>404 - page not found</h1>';

?>