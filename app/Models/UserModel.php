<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id_users';
    protected $allowedFields = ['username', 'fullname', 'email', 'saldo', 'level', 'status', 'uplink', 'password', 'user_ip', 'expiration_date', 'reset_link_token', 'exp_date', 'logged_in'];
    protected $useTimestamps = true;
    
    
    
    protected $table_m      = 'modname';
    protected $primaryKey_m = 'id';
    protected $allowedFields_m = ['modname'];
    
    
    

    public function getUser($userid = false, $where = 'default')
    {
        $userid = $userid ?: session()->userid;
        $where = ($where == 'default' ? 'id_users' : $where);
        $wfind = $this->where($where, $userid)
            ->get()
            ->getFirstRow();
        return $wfind ?: NULL;
    }

    public function getUserList($select = "*")
    {
        $this->select($select);
        return $this->get()
            ->getResultObject();
    }

    public function API_getUser()
    {
        $user = $this->getUser();

        $where = [];
        if ($user == null or $user->level != 1) {
            $where['uplink'] = $user->username;
        }

        $formatters = [
            'fullname' => function ($value) {
                return $value ? esc(word_limiter($value, 1, '')) : '';
            },
            'level' => function ($value) {
                return getLevel($value);
            },
        ];

        return datatables_json(
            'users',
            $where,
            ['username', 'fullname', 'saldo', 'uplink'],
            $formatters,
            'id_users as id, username, fullname, saldo, level, status, uplink',
            ['id' => 'id_users', 'level' => 'level', 'saldo' => 'saldo'],
            'id_users'
        );
    }

    public function clientIp()
    {
        $clientIp  = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : '';
        $forwardIp = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '';

        if (filter_var($clientIp, FILTER_VALIDATE_IP)) {
            return $clientIp;
        }

        if (filter_var($forwardIp, FILTER_VALIDATE_IP)) {
            return $forwardIp;
        }

        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
    }

    public function checkAuthFilter()
    {
        $time = new \CodeIgniter\I18n\Time;
        $session = session();
        $time_ex = $session->time_login;
        if ($time::now()->isBefore($time_ex)) {
            $bindIp = $session->bind_ip;
            if (!empty($bindIp) && !hash_equals($bindIp, (string) $this->clientIp())) {
                return $this->AuthSessionLogout(
                    'Security: session IP changed (' . esc($this->clientIp()) . '). Please login again.'
                );
            }

            $userCek = $this->getUser($session->userid);
            if ($userCek->level > 3) {
                $msg = 'Level account invalid!';
            } elseif ($userCek->status != 1) {
                $msg = 'Status account changed!';
            } else {
                return $userCek;
            }
        } else {
            $msg = 'Session account expired!';
        }
        return $this->AuthSessionLogout($msg);
    }

    public function AuthSessionLogout($msg = 'Session terminate')
    {
        $list = ['userid', 'unames', 'time_login', 'time_since', 'login_at', 'stay_log', 'bind_ip'];
        session()->remove($list);
        return redirect()->to('login')->with('msgDanger', $msg);
    }
}
