<?php
set_time_limit(0);

// Include Composer libraries
require 'vendor/autoload.php';

$tokenLib = new DropboxClient\tokenLib;
$tokenLib::init();

$root = dirname((__FILE__));

$dropbox = new DropboxClient\DropboxClient(array(
    'app_key' => 'APP_KEY',
    'app_secret' => 'APP_SECRET',
    'app_full_access' => true,
));

$return_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . "?auth_redirect=1";

$bearer_token = $tokenLib::loadToken("bearer");

if ($bearer_token) {
    $dropbox->SetBearerToken($bearer_token);
} elseif (!empty($_GET['auth_redirect'])) {
    $bearer_token = $dropbox->GetBearerToken(null, $return_url);
    $tokenLib::storeToken($bearer_token, "bearer");
} elseif (!$dropbox->IsAuthorized()) {
    $auth_url = $dropbox->BuildAuthorizeUrl($return_url);
    header('Location: ' . $auth_url);
}

$token = $bearer_token['t'];
$dropbox = new Dropbox\Dropbox($token);
$files = $dropbox->files->list_folder('/example');
/*foreach ($denemeFiles['entries'] as $denemeFile) {
    $dropbox->files->download($denemeFile['path_lower'], "./download/".$denemeFile['name']);
    $dropbox->files->delete($denemeFile['path_lower']);
}*/
// Initialize Dropbox client

//$dropbox->files->upload('/target/file.txt', '/path-to-upload-from/uploadthisfile.txt', "overwrite");

// List all the files in a folder
echo '<pre>';
print_r($dropbox->files->list_folder('/example'));
echo '</pre>';


/***Documentation will be added/updated in the future***/
?>
