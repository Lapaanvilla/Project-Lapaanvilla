<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
|  Google API Configuration
| -------------------------------------------------------------------
|  client_id         string   Your Google API Client ID.
|  client_secret     string   Your Google API Client secret.
|  redirect_uri      string   URL to redirect back to after login.
|  application_name  string   Your Google application name.
|  api_key           string   Developer key.
|  scopes            string   Specify scopes
*/

$config['google']['client_id'] = '205618602160-spu7rbjrvdacudque7pro82649038u21.apps.googleusercontent.com';
$config['google']['client_secret']    = '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';
$config['google']['redirect_uri']     = base_url().'home/google/';
$config['google']['application_name'] = 'Login to La PaanVilla';
$config['google']['api_key']          = '';
$config['google']['scopes']           = array();