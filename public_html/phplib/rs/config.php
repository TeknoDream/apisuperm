<?php
define('CONSUMER_KEY', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa');
define('CONSUMER_SECRET', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa');
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
$linkedin_api["key"]="aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa";
$linkedin_api["secret"]="aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa";
$linkedin_api["credential"]="b1ef709f-aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa-4aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa5a4fcc63";
$linkedin_api["secret_user"]="ca82b9f1-daaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa7d8f5";
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
$instagram_api["CLIENT_ID"]="e738836543aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa627";
$instagram_api["CLIENT_SECRET"]="389d22aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaab5c";
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
$tumblr_api["OAuth_KEY"]="fq8hHb7aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaMfHeGh8a";
$tumblr_api["OAuth_CS_KEY"]="Lzp1U7ovx5aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaiwiga47g5G";
$tumblr_api["callback"]="http://".$_SERVER['HTTP_HOST']."/tumblr/callback";
$tumblr_api["req_url"] = 'http://www.tumblr.com/oauth/request_token';
$tumblr_api["authurl"] = 'http://www.tumblr.com/oauth/authorize';
$tumblr_api["acc_url"] = 'http://www.tumblr.com/oauth/access_token';
/*************/
/**flickr**/
/************/
$flickr_api=array();
$flickr_api["key"]="53278aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa0688cc7f4";
$flickr_api["secret"]="edea5aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa886";
$flickr_api["callback"]="http://".$_SERVER['HTTP_HOST']."/flickr/callback";
$flickr_api['requesttokenurl']="http://www.flickr.com/services/oauth/request_token";
$flickr_api['accesstokenurl']="http://www.flickr.com/services/oauth/access_token";
$flickr_api['authurl']="http://www.flickr.com/services/oauth/authorize";
$flickr_api["rest"] = 'http://api.flickr.com/services/rest/';


/*************/
/**Foursquare**/
/************/
$foursquare_api=array();
$foursquare_api["client_id"]="5JFWMG5FFUGYWO2aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaTPDO4HVVNNZYSWODU";
$foursquare_api["client_secret"]="CCMHUNNZaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaI2QVCXQ4";
$foursquare_api["callback"]="http://".$_SERVER['HTTP_HOST']."/foursquare/callback";
$foursquare_api["authenticate"] = 'https://foursquare.com/oauth2/authenticate';
$foursquare_api["access_token"] = 'https://foursquare.com/oauth2/access_token';
$foursquare_api["request"] = 'https://api.foursquare.com/v2/users/1/request';

?>
