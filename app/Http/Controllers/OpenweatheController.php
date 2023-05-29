<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request, Response;
use Illuminate\Support\Facades\Http;
use App\Models\History;


class OpenweatheController extends Controller
{
    public function getOpenWeathe(Request $request)
    {
        $response = Http::get('https://api.openweathermap.org/data/2.5/weather?lat='.$request->latitud
        .'&lon='.$request->longitud.'&appid=2cbed0651b2621896fa48f638e661772');

        $dataResponse = $response->object();

        $insertHistory = new History();
        $insertHistory->city_search = $dataResponse->name;
        $insertHistory->length = $request->latitud;
        $insertHistory->latitude = $request->longitud;
        $insertHistory->save();

        return $response->json();
    }

    public function getDataHistory()
    {
       $queryHistories = History::select(
       'city_search',
       'length',
       'latitude',
       'created_at')->get();

       foreach($queryHistories as $query)
       {
        $dataResponse[] = array('ip'=>$query->ip_adress,
        'city'=>$query->city_search,
        'length'=>$query->length,
        'latitude'=>$query->latitude,
        'dateTime'=>date('Y-m-d H:i:s', strtotime($query->created_at)));
       }

       return response()->json($dataResponse);


    }

}
