<?php

namespace App\Controllers;

use App\Models\KeysModel;

class Connect extends BaseController
{
    protected $model, $game, $uKey, $sDev;

    public function __construct()
    {
        include('conn.php');
//=================================================
        $sql1 ="select * from onoff where id=1";
        $result1 = mysqli_query($conn, $sql1);
        $userDetails1 = mysqli_fetch_assoc($result1);
//=================================================
        $this->model = new KeysModel();
//=================================================
        if($userDetails1['status'] == 'on')
        {
            $this->maintenance = true;
        } else {
            $this->maintenance = false;
        }
//=================================================
        $this->staticWords = "Vm8Lk7Uj2JmsjCPVPVjrLa7zgfx3uz9E";
    }

    public function index()
    {
        if ($this->request->getPost()) {
            return $this->index_post();
        } else {
            $nata = [
                "web_info" => [
                    "_client" => BASE_NAME_FULL,
                    "license" => "5G7B3F8J2H
",
                    "version" => "3.0.0",
                ],
                "web__dev" => [
                    "author" => "Zia Dev",
                    "Channel" => "https://t.me/christdmm",
                    "Group" => "https://t.me/christdmm"
                ],
            ];
            
            
            $this->response->setJSON($nata);
        }
    }

    public function index_post()
    {
        $isMT = $this->maintenance;
        $game = $this->request->getPost('game');
        $uKey = $this->request->getPost('user_key');
        $sDev = $this->request->getPost('serial');

        $form_rules = [
            'game' => 'required|alpha_dash',
           // 'user_key' => 'required|regex:/^[A-Za-z0-9_-]+$/',
            
            'user_key' => 'required|alpha_dash',
            'serial' => 'required|alpha_dash'
        ];

        if (!$this->validate($form_rules)) {
            $data = [
                'status' => false,
                'reason' => "Bad Parameter, Contact: @Christdmm",
            ];
            return $this->response->setJSON($data);
        }

        if ($isMT) {
            
            include('conn.php');
        
            $sql1 ="select * from onoff where id=1";
            $result1 = mysqli_query($conn, $sql1);
            $userDetails1 = mysqli_fetch_assoc($result1);
        
            
            $data = [
                'status' => true,
                'reason' => $userDetails1['myinput']
            ];
        } else {
            if (!$game or !$uKey or !$sDev) {
                $data = [
                    'status' => false,
                    'reason' => 'Invalid Parameter, Contact: @Christdmm'
                ];
            } else {
                $time = new \CodeIgniter\I18n\Time;
                $model = $this->model;
                $findKey = $model
                    ->getKeysGame(['user_key' => $uKey, 'game' => $game]);

                if ($findKey) {
                    if ($findKey->status != 1) {
                        $data = [
                            'status' => false,
                            'reason' => 'Suspended Key, Contact: @Christdmm'
                        ];
                    } else {
                        $id_keys = $findKey->id_keys;
                        $duration = $findKey->duration;
                        $expired = $findKey->expired_date;
                        $max_dev = $findKey->max_devices;
                        $devices = $findKey->devices;
    
                        function checkDevicesAdd($serial, $devices, $max_dev)
                        {
                            $lsDevice = explode(",", $devices);
                            $cDevices = isset($devices) ? count($lsDevice) : 0;
                            $serialOn = in_array($serial, $lsDevice);
    
                            if ($serialOn) {
                                return true;
                            } else {
                                if ($cDevices < $max_dev) {
                                    array_push($lsDevice, $serial);
                                    $setDevice = reduce_multiples(implode(",", $lsDevice), ",", true);
                                    return ['devices' => $setDevice];
                                } else {
                                    // ! false - devices max
                                    return false;
                                }
                            }
                        }
    
                        if (!$expired) {
                            $setExpired = $time::now()->addDays($duration);
                            $model->update($id_keys, ['expired_date' => $setExpired]);
                            $data['status'] = true;
                        } else {
                            if ($time::now()->isBefore($expired)) {
                                $data['status'] = true;
                            } else {
                                $data = [
                                    'status' => false,
                                    'reason' => 'Expired Key, Contact: @Christdmm'
                                ];
                            }
                        }
    
                        if ($data['status']) {
                            
                            include('conn.php');
        
                            $sql2 ="select * from modname where id=1";
                            $result2 = mysqli_query($conn, $sql2);
                            $userDetails2 = mysqli_fetch_assoc($result2);
                            
                            $sql3 ="select * from _ftext where id=1";
                            $result3 = mysqli_query($conn, $sql3);
                            $userDetails3 = mysqli_fetch_assoc($result3);
                            
                            $sql4 = "SELECT expired_date FROM keys_code WHERE user_key='$uKey'";
                            $result4 = mysqli_query($conn, $sql4);
                            $userDetails4 = mysqli_fetch_assoc($result4);
//=================================================
        $sql = "SELECT * FROM Feature WHERE id=1";
        $result = mysqli_query($conn, $sql);
        $ModFeatureStatus = mysqli_fetch_assoc($result);
//=================================================
        $rngcnt = $time->getTimestamp();
//=================================================
                            $devicesAdd = checkDevicesAdd($sDev, $devices, $max_dev);
                            if ($devicesAdd) {
                                if (is_array($devicesAdd)) {
                                    $model->update($id_keys, $devicesAdd);
                                }
                                // ? game-user_key-serial-word di line 15
                                $real = "$game-$uKey-$sDev-$this->staticWords";
                                $data = [
                                    'status' => true,
                                    'data' => [
                                        'real' => $real,
                                        'token' => md5($real),
                                        'modname' => $userDetails2['modname'],
                                        'mod_status' => $userDetails3['_status'],
                                        'credit' => $userDetails3['_ftext'],
                                        'ESP' => $ModFeatureStatus['ESP'] == 'true' ? true : false,
                                        'Item' => $ModFeatureStatus['Item'] == 'true' ? true : false,
                                        'AIM' => $ModFeatureStatus['AIM'] == 'true' ? true : false,
                                        'SilentAim' => $ModFeatureStatus['SilentAim'] == 'true' ? true : false,
                                        'BulletTrack' => $ModFeatureStatus['BulletTrack'] == 'true' ? true : false,
                                        'Floating' => $ModFeatureStatus['Floating'] == 'true' ? true : false,
                                        'Memory' => $ModFeatureStatus['Memory'] == 'true' ? true : false,
                                        'Setting' => $ModFeatureStatus['Setting'] == 'true' ? true : false,

                                        'EXP' => $userDetails4['expired_date'],
                                        'device'=> $max_dev,
                                        'MOD_NAME' => $userDetails2['modname'],
                                        'MOD_STATUS' => $userDetails3['_status'],
                                        'FLOTING_TEST' => $userDetails3['_ftext'],
                                        'BHATIA_EXP' => $expired,
                                        'BHATIA_SLOT'=> $max_dev,
                                        'rng' => $time->getTimestamp()
                                    ],
                                ];
                            } else {
                                $data = [
                                    'status' => false,
                                    'reason' => 'Max Device Reached, Contact: @Christdmm'
                                ];
                            }
                        }
                    }
                } else {
                    $data = [
                        'status' => false,
                        'reason' => 'Incorrect Key, Contact: @Christdmm'
                    ];
                }
            }
        }
        return $this->response->setJSON($data);
    }
}