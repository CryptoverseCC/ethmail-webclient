<?php

/**
 * @copyright Copyright (c) 2019-2021, Cryptoverse OU. (https://cryptoverse.cc/)
 * @license   MIT   License            : <http://opensource.org/licenses/MIT>
 *
 * @package   OpenID Connect SSO Plugin for cryptoverse.cc
 * @category  Plugin for RoundCube WebMail
 * @version   0.1.0
 *
 * @author Grzegorz Kapkowski : <https://github.com/gkapkowski> <grzegorz.kapkowski@gmail.com>
 */

/* Workaround for oauth issue - double requests from mobile wallet */
$path = $_SERVER['PATH_INFO'];
$accept = $_SERVER['HTTP_ACCEPT'];

if ($path == '/login/oauth' and strlen($accept) == 0) {
    rcube::raise_error([
        'message' => "Invalid request (double request from mobile wallet)",
        'file' => __FILE__,
        'line' => __LINE__,
    ], true, false);
    exit();
}
/* END: Workaround for oauth issue */

class ethmail_webclient extends rcube_plugin
{
    public $task = ".*";
    private $rc;
    private $token;

    public function init()
    {
        $this->rc = rcmail::get_instance();

        $this->add_texts('localization/', true);

        $this->register_task('ethmail');
        $this->register_action('plans', [$this, 'ui_plans']);

        //$this->add_hook('startup', [$this, 'startup']);
        $this->add_hook('oauth_login', [$this, 'oauth_login']);
        $this->add_hook('login_after', [$this, 'login_after']);
    }

    public function startup($args)
    {
        rcube::raise_error([
            'message' => "Startup",
            'file' => __FILE__,
            'line' => __LINE__,
        ], true, false);

        return $args;
    }

    public function ui_plans()
    {
        $this->rc->output->send('ethmail_webclient.plans');
    }

    public function oauth_login($args)
    {
        $this->token = $args;
    }

    public function login_after()
    {
        $args = $this->token;

        // debug
        rcube::raise_error([
            'message' => json_encode($args),
            'file' => __FILE__,
            'line' => __LINE__,
        ], true, false);

        // try {
        $identity = $args["identity"];
        $emails = $identity["emails"];
        $supported_emails = $emails["all"];

        rcube::raise_error([
            'message' => print_r($this->rc->user, true),
            'file' => __FILE__,
            'line' => __LINE__,
        ], true, false);

        foreach ($this->rc->user->list_identities() as $identity) {
            $id_email = $identity['email'];

            rcube::raise_error([
                'message' => "ID: " . $id_email,
                'file' => __FILE__,
                'line' => __LINE__,
            ], true, false);

            if (in_array($id_email, $supported_emails)) {
                $supported_emails = array_diff($supported_emails, array($id_email));
            } else {
                $this->rc->user->delete_identity($identity['identity_id']);
            }
        }

        foreach ($supported_emails as $new_email) {
            $data = array(
                'name' => explode("@", $new_email)[0],
                'email' => $new_email,
            );

            $insert_id = $this->rc->user->insert_identity($data);

            if ($insert_id) {
                $this->rc->plugins->exec_hook('identity_create_after', ['id' => $insert_id, 'record' => $data]);
            } else {
                rcube::raise_error([
                    'message' => "Insert Failed for " . $new_email,
                    'file' => __FILE__,
                    'line' => __LINE__,
                ], true, false);
            }

        }
        // } catch (\Exception $e) {
        //     rcube::raise_error([
        //         'message' => $e->getMessage(),
        //         'file' => __FILE__,
        //         'line' => __LINE__,
        //     ], true, false);
        // }

        return [
            'task' => 'mail',
            'action' => 'inbox',
        ];
    }
}
