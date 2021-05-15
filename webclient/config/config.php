<?php
// Workaround for oauth redirect issue
$_SERVER['REDIRECT_SCRIPT_URL'] = 'https://' . getenv("OAUTH2_CLIENT_ID") . '/';

$config['domain'] = 'ethmail.cc';
$config['default_host'] = 'tls://ethmail.cc';
$config['smtp_server'] = 'tls://ethmail.cc';
$config['smtp_port'] = 587;

$config['oauth_provider'] = 'generic';
$config['oauth_provider_name'] = 'Cryptoverse Login';
$config['oauth_client_id'] = getenv("OAUTH2_CLIENT_ID");
$config['oauth_client_secret'] = getenv("OAUTH2_CLIENT_SECRET");
$config['oauth_auth_uri'] = "https://login.cryptoverse.cc/oauth2/auth";
$config['oauth_token_uri'] = "https://login.cryptoverse.cc/oauth2/token";
$config['oauth_identity_uri'] = 'https://login.cryptoverse.cc/userinfo';
$config['oauth_scope'] = "email profile openid";
// $config['oauth_auth_parameters'] = ['access_type' => 'offline', 'prompt' => 'consent'];
$config['oauth_login_redirect'] = true;

$config['imap_auth_type'] = 'XOAUTH2';
$config['imap_conn_options'] = array(
    'ssl' => array(
        'verify_peer' => true,
        'allow_self_signed' => false,
        'ciphers' => 'TLSv1+HIGH:!aNull:@STRENGTH',
        'peer_name' => 'ethmail.cc',
    ),
);
$config['smtp_conn_options'] = array(
    'ssl' => array(
        'verify_peer' => true,
        'allow_self_signed' => false,
        'ciphers' => 'TLSv1+HIGH:!aNull:@STRENGTH',
        'peer_name' => 'ethmail.cc',
    ),
);
$config['skin'] = 'ethmail';
$config['dont_override'] = array("skin");
/*
identities_level:
0 - multiple identities with possibility to edit all parameters
1 - multiple identities with possibility to edit all parameters except email address
2 - one identity with possibility to edit all parameters
3 - one identity with possibility to edit all parameters except email address
4 - one identity with possibility to edit only signature
 */
$config['identities_level'] = 4;

// Logging
$config['log_driver'] = 'stdout';

// Plugins
$config['plugins'] = array("ethmail_webclient");
