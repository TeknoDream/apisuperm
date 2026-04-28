<?php
define('CONSUMER_KEY', 'KkD143fFvKkSMC66W1qw');
define('CONSUMER_SECRET', 'n1oQ8ktH1GgJLyX9nVweCgAGuH1meutsZ0SHg7MmU');
define('OAUTH_CALLBACK', "http://".$_SERVER['HTTP_HOST']."/twitter/callback");


/*************/
/**Facebook**/
/************/
$facebook_url = "http://".$_SERVER['HTTP_HOST']."/facebook/callback";
$facebook_api = array();
$facebook_api['appId'] = $_PARAMETROS["FB_APPID"];
$facebook_api['secret'] = $_PARAMETROS["FB_APPSECRET"];

/*************/
/**linkedin**/
/************/
$linkedin_api=array();
$linkedin_api["key"]="1zxpe371ev6g";
$linkedin_api["secret"]="hqC5LmOfqzVCGPBK";
$linkedin_api["credential"]="b1ef709f-a3f0-4a6b-b823-b3525a4fcc63";
$linkedin_api["secret_user"]="ca82b9f1-daae-43c8-af8b-11bcc857d8f5";
$linkedin_api["callback"]="http://".$_SERVER['HTTP_HOST']."/linkedin/callback";

$linkedin_api["_URL_ACCESS"]= 'https://api.linkedin.com/uas/oauth/accessToken';
$linkedin_api["_URL_API"] = 'https://api.linkedin.com';
$linkedin_api["_URL_AUTH"]= 'https://www.linkedin.com/uas/oauth/authenticate?oauth_token=';
$linkedin_api["_URL_REQUEST"] = 'https://api.linkedin.com/uas/oauth/requestToken';
$linkedin_api["_URL_REVOKE"] = 'https://api.linkedin.com/uas/oauth/invalidateToken';

/*************/
/**Instagram**/
/************/
$instagram_api=array();
$instagram_api["CLIENT_ID"]="e738836543c64c8a96be5ca7bbc59627";
$instagram_api["CLIENT_SECRET"]="389d22fdb8d542d3a97dd2d631e0ab5c";
$instagram_api["callback"]="http://".$_SERVER['HTTP_HOST']."/instagram/callback";
$instagram_api["authurl"] = 'https://api.instagram.com/oauth/authorize';
$instagram_api["acc_url"] = 'https://api.instagram.com/oauth/access_token';

$instagram_api["api"][0]="https://api.instagram.com/v1/users/self/media/liked";
$instagram_api["api"][1]="https://api.instagram.com/v1/users/self/media/recent";
$instagram_api["api"][2]="https://api.instagram.com/v1/users/self/feed";
/*************/
/**Tumblr**/
/************/
$tumblr_api=array();
$tumblr_api["OAuth_KEY"]="fq8hHb7L4tV96gfMwTqmJBUZQTMO1zqt5eS26j97pIMfHeGh8a";
$tumblr_api["OAuth_CS_KEY"]="Lzp1U7ovx5qYPHNcEM2BcH8TAH1F615b51BcQkEgiwiga47g5G";
$tumblr_api["callback"]="http://".$_SERVER['HTTP_HOST']."/tumblr/callback";
$tumblr_api["req_url"] = 'http://www.tumblr.com/oauth/request_token';
$tumblr_api["authurl"] = 'http://www.tumblr.com/oauth/authorize';
$tumblr_api["acc_url"] = 'http://www.tumblr.com/oauth/access_token';
/*************/
/**flickr**/
/************/
$flickr_api=array();
$flickr_api["key"]="5327872571579c4b740e9880688cc7f4";
$flickr_api["secret"]="edea5b0a326a4886";
$flickr_api["callback"]="http://".$_SERVER['HTTP_HOST']."/flickr/callback";
$flickr_api['requesttokenurl']="http://www.flickr.com/services/oauth/request_token";
$flickr_api['accesstokenurl']="http://www.flickr.com/services/oauth/access_token";
$flickr_api['authurl']="http://www.flickr.com/services/oauth/authorize";
$flickr_api["rest"] = 'http://api.flickr.com/services/rest/';


/*************/
/**Foursquare**/
/************/
$foursquare_api=array();
$foursquare_api["client_id"]="5JFWMG5FFUGYWO22KARL2P5YQ3TGH0RTPDO4HVVNNZYSWODU";
$foursquare_api["client_secret"]="CCMHUNNZWHK1M5SRBZW4JCAVYJWNOPY1Z3FGOR4FI2QVCXQ4";
$foursquare_api["callback"]="http://".$_SERVER['HTTP_HOST']."/foursquare/callback";
$foursquare_api["authenticate"] = 'https://foursquare.com/oauth2/authenticate';
$foursquare_api["access_token"] = 'https://foursquare.com/oauth2/access_token';
$foursquare_api["request"] = 'https://api.foursquare.com/v2/users/1/request';

?>