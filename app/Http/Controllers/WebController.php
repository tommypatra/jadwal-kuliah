<?php

namespace App\Http\Controllers;

use App\Libraries\Sevima;
use Illuminate\Http\Request;

class WebController extends Controller
{
    public function index(Request $request,Sevima $sevima, $api_keyword, $id=null, $id2=null)
    {
        $url = 'siakadcloud/v1/'.$api_keyword;

        if ($id)
            $url .= '/'.$id;
        if ($id2)
            $url .= '/'.$id2;

        $response = $sevima->get(
            $url,
            $request->query()
        );
        return response()->json(
            $response['data'],
            $response['status']
        );    
    }


    public function login(Request $request,Sevima $sevima)
    {
        $url = 'siakadcloud/v1/user/login';

        $akun=[
            [
                'email' => 'tommyirawan.patra@gmail.com',
                'password' => '12345678'
            ],
            [
                'email' => 'ismaun85.iainkdi@gmail.com',
                'password' => '85032885@Sia'
            ],
            [
                'email' => 'windawnd809@gmail.com',
                'password' => 'Winda016'
            ],
            [
                'email' => 'jokoaziswetomi@gmail.com',
                'password' => '2024101001'
            ],
            [
                'email' => 'andisakri.p75@gmail.com',
                'password' => 'tabeandi75'
            ],
            [
                'email' => 'Rianaldisupri@gmail.com',
                'password' => '24102008'
            ]
        ];

        $response = $sevima->post(
            $url,
            $akun[0] //$request->all(),
        );
    
        return response()->json(
            $response['data'],
            $response['status']
        );    
    }

}
