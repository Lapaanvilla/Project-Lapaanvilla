<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('SALT','5&JDDlwz%Rwh!t2Yg-Igae@QxPzFTSId');
define('ADMIN_URL','backoffice');
define('FCM_KEY','AAAAyBTXzpA:APA91bE2dFMoGR4g5ep1x5CgXW9jiMzccfBGiTtQQjjV9T8ITUQt4dZiotHWRtJAgP4FuRMJOzz9-iKPsxnpTxmCp8c5hlXbRX-3OhiSJlBT4nsB7hTZtYPXfoO2BUbJsKhNyE8zfeET');
/*Old key
AAAAqwpmla4:APA91bH6WXV2_zxG95ZarruHXbK6e76Fr9x-SRIQLmxOrzRxDyLrn4shpaTXkAbuBLl4EBwQMVmYtBg_TZWj9K_eKI7rU04tCZGvUpxseYLc0Y_IQMY7JcATHm7AhlwJdTlSeVGZiYOS
*/ 
define('IS_SMS_API_INTEGRATED',FALSE);
//sandbox url (paypal)
define('use_sandbox',true);
define('SANDBOX_PAYPAL_URL_V1',"https://api.sandbox.paypal.com/v1/");
define('SANDBOX_PAYPAL_URL_V2',"https://api.sandbox.paypal.com/v2/");
// live url (paypal)
define('LIVE_PAYPAL_URL_V1',"https://api.paypal.com/v1/");
define('LIVE_PAYPAL_URL_V2',"https://api.paypal.com/v2/");

//driver tip
define('driver_tiparr',[1,2,3,4]);
define('review_count',5);
define('order_count',6);
//image default quality
define('IMAGE_QUALITY',60);
//for system currency display
define('ACTIVATE_SYSTEM_DEFAULT_CURRENCY',1);
define('REGISTRATION_MINUTES', 50);
/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);
/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);
/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');
/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code
// Eatance Constants
/*Begin::Print Receipt Constants*/
define('PRINT_RECEIPT_EMAIL',"support@evincedev.com");
define('PRINT_RECEIPT_TELEPHONE',"(+91) 98765 43210");
define('PRINT_RECEIPT_WEBSITE',"www.lapaanvilla.com");
/*End::Print Receipt Constants*/
define('TIME_INTERVAL',"60 mins");

//Twilio SMS integration :: demo server credentials
define("TWILIO_SID", "ACdc748ef4f3f80f23ab955d77b3f120dc");
define("TWILIO_MSID", "");
define("TWILIO_AUTH_TOKEN", "50260ded302f92bca2f491560ae271ba");
define("TWILIO_PHN_NO", "+16476849711");

//Date Format
define("datetimepicker_format", "MM-DD-YYYY LT"); //(24 hours : HH:mm) (12 hours : LT) :: front web
define("datepicker_format_front", "MM-DD-YYYY"); //front web
define("timepicker_format", "LT"); //front web
define("daterangepicker_format", "MM-DD-YYYY"); //backoffice
define("datepicker_format", "mm-dd-yyyy"); //backoffice
define("date_timepicker_format", "mm-dd-yyyy HH:ii P"); //backoffice


define('PREPARATION_MINUTES', '15'); //minutes to be added in current time and sent in pick_time request
//doorDash keys & api url :: end
