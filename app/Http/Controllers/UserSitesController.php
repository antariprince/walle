<?php
namespace App\Http\Controllers;
use App\UserSites;
use Auth;
use App\User;
use Illuminate\Http\Request;
use Goutte;

class UserSitesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public $keys = ['title','element','attribute','positions','filters'];
    public $required = ['title','element','attribute'];
    
    public function index()
    {
        $sitelist = User::with('user_sites')->find(Auth::id());

        return view('admin.sites', compact('sitelist'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.sites.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $r = request();
        $this->validate($r,[
            'url' => 'required'
        ]);

        $algo = $this->buildScrapeAlgo($r);
        $site = UserSites::create([
            'url' => $r->url,
            'user_id' => Auth::id(),
            'collection' => $r->collection,
            'singlepage' => $r->singlepage,
            'pager' => $r->pager,
            'page_string' => $r->page_string,
            'limit' => $r->limit,
            'replace_with' => $r->replace_with,
            'scrape_data' => $algo
        ]);

        //Session::flash('success','Discussion successfully created.');

        return redirect()->route('admin.sites');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
        $siteitem = UserSites::where('id',$id)->where('user_id',Auth::id())->first();
        
        if($siteitem == null){
            return redirect()->route('admin.sites');
        }
        $siteitem['scrape_data'] = json_decode($siteitem['scrape_data'],true);
        return view('admin.sites.edit')->with('siteitem',$siteitem);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $d = UserSites::find($id);
        $r = request();
        $this->validate($r,[
            'url' => 'required'
        ]);
        $algo = $this->buildScrapeAlgo($r);
        $d->url = $r->url;
        $d->singlepage = $r->singlepage;
        $d->pager = $r->pager;
        $d->limit = $r->limit;
        $d->page_string = $r->page_string;
        $d->replace_with = $r->replace_with;
        $d->collection = $r->collection;
        $d->scrape_data = $algo;
        $d->save();
        //Session::flash('success','Discussion content updated.');
        return redirect()->route('admin.sites.edit',['id' => $d->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = UserSites::find($id);
        $post->delete();
        return redirect()->route('admin.sites');
    }

    public function buildScrapeAlgo($r){
        //dd($r);
        $stringAlgo = array();
        $count = count($r[$this->keys[0]]);
        for($i = 0; $i < $count; $i++){
            foreach($this->keys as $key){
                $stringAlgo[$i][$key] = strtolower($r[$key][$i]);
            }
        }
        $stringAlgo = $this->validateAlgo($stringAlgo,$this->required);
        return json_encode($stringAlgo);
    }

    public function validateAlgo($stringAlgo){
        foreach($stringAlgo as $key => $algo){
            foreach($this->required as $item){
                if($algo[$item] == null){
                    unset($stringAlgo[$key]);
                    break;
                }
            }
        }
        return array_values($stringAlgo);
    }

    public function preview($id){
        $data = $this->fetchData($id);
        return view('admin.sites.preview')->with('data',$data);
    }

     public function getJson($id){
        $data = $this->fetchData($id);
        echo(json_encode($data));
        exit;
    }

    public function downloadCsv($id){
        $data = $this->fetchData($id);
        $this->exportCsv($data); 
    }


    public function fetchData($id){
        $siteitem = UserSites::where('id',$id)->where('user_id',Auth::id())->first();
        $siteitem['scrape_data'] = json_decode($siteitem['scrape_data'],true);
        
        $exit = 0;
        $tempRes = array();
        $baseLink = $siteitem['url'];
        if($siteitem['singlepage'] == 'multi'){
        for($z = 1; $exit != 1; $z++){
        if($siteitem['limit'] > 0){
            if($z == $siteitem['limit']){
                $exit = 1;
            }
        }
        if($z == 1){
        $crawler = Goutte::request('GET', $baseLink);
            if(count($crawler) > 0){
                $tempRes = $this->nodeFilter($tempRes,$crawler,$siteitem);
            }
            else{
                $exit = 1;
            }
        }
        else{
            $link = $this->buildScrapePageLink($siteitem,$baseLink,$z);
            $crawler = Goutte::request('GET', $link);
            if(count($crawler) > 0){
                $tempRes = $this->nodeFilter($tempRes,$crawler,$siteitem);
            }
            else{
                $exit = 1;
            }
        }
        
        }
        }
        else{
            $crawler = Goutte::request('GET', $baseLink);
            $tempRes = $this->nodeFilter($tempRes,$crawler,$siteitem);
        }
        return $tempRes;
    }

    public function buildScrapePageLink($siteitem,$baseLink,$z){
        if($siteitem['pager'] == 'append'){
        $pageAppend = str_replace('***pagenum***',$z,$siteitem['page_string']);
        return $baseLink.$pageAppend;
        }
        else{
            $pageAppend = str_replace('***pagenum***',$z,$siteitem['replace_with']);
            $link = str_replace($siteitem['page_string'],$pageAppend,$baseLink);
            return $link;
        }
    }

    public function exportCsv($data){  

        $selected_array = array();
        foreach($data[0] as $key => $item){
            $selected_array[] = $key;
        }

        $Array_data = $data;

        $Filename ='csv_'.now().'.csv';
        header('Content-Type: text/csv; charset=utf-8');
        Header('Content-Type: application/force-download');
        header('Content-Disposition: attachment; filename='.$Filename.'');
        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');
        fputcsv($output, $selected_array);
        foreach ($Array_data as $row){
            fputcsv($output, $row);
        }
        fclose($output);

    }

    public function nodeFilter($tempRes,$crawler,$siteitem){
        $resNode = $crawler->filter($siteitem['collection'])->each(function ($node, $i) use ($siteitem){
            $res = array();
            foreach($siteitem['scrape_data'] as $key => $item){
                $tempVal = '';
                $add = true;
                $section = $node->filter($item['element']);
                if(count($section) > 0){
                if($item['attribute'] == 'text'){
                    if(count($section) > 1){
                        //$tempVal = '';
                        $tempVal = trim($this->scrapeGetPosition($section,$item));
                    }
                    else{
                        $tempVal = trim($section->text());
                    }
                }
                else{
                    if(count($section) > 1){
                        $tempVal = trim($this->scrapeGetPosition($section,$item));
                    }
                    else{
                        $tempVal = trim($section->attr($item['attribute']));
                    }
                }
                if($item['filters'] != null){
                    $item['filters'] = json_decode($item['filters'],true);
                    $tempVal = $this->filterItem($tempVal,$item['filters']);
                    if(!$tempVal){
                        $add = false;
                    }
                }
                $res[$item['title']] = $tempVal;
                if(!$add){
                    return null;
                }
            }
            }
            return $res;
        });

        
        $final = array_filter($resNode, function($var){return !is_null($var);} );
        return array_merge($tempRes, $final);
    }

    public function scrapeGetPosition($section,$item){
        $res = array();
        $res = $section->each(function ($node) use ($item){
            if($item['attribute'] == 'text'){
            return $node->text();
            }
            else{
                return $node->attr($item['attribute']);
            }
        });
        $resultString = '';
        $concatCount = 0;
        if(strpos($item['positions'], ',') !== false) {
            $positionsList = explode(',', $item['positions']);
            foreach($positionsList as $key => $positionItem){
                if(strpos($positionItem, '-') !== false) {
                    $rangeList = explode('-', $positionItem);
                    for($i = $rangeList[0]; $i <= $rangeList[1]; $i++){
                        if($concatCount == 0){
                        $resultString.=$res[$i];
                        }
                        else{
                            $resultString.='***'.$res[$i];
                        }
                    }
                }
                else{
                    if($concatCount == 0){
                    $resultString.=$res[$positionItem];
                    }
                    else{
                        $resultString.="***".$res[$positionItem];
                    }
                }
                $concatCount++;
            }
            }
        else{

            if(strpos($item['positions'], '-') !== false) {
            $positionsListX = explode('-', $item['positions']);
            for($x = $positionsListX[0]; $x <= $positionsListX[1]; $x++){
                    if($concatCount == 0){
                    $resultString.=$res[$x];
                    }
                    else{
                        $resultString.='***'.$res[$x];
                    }
                    $concatCount++;
                }  
            }
            else{
                if($item['positions']){
                $resultString = $res[$item['positions']];
                }
                else{
                $resultString = $res[0];
                }
                $concatCount++;
            }
        }
        return $resultString;
    }


    public function filterItem($item,$filters){

        foreach($filters as $key => $filter){
            switch ($key) {
                case 'explode':
                    $item = $this->scrapeExplode($item,$filter);
                    break;
                case 'position':
                    //code to be executed if n=label2;
                    break;
                case 'prepend':
                    $item = $this->scrapePrepend($item,$filter);
                    break;
                case 'append':
                    $item = $this->scrapeAppend($item,$filter);
                    break;
                case 'money':
                    $item = $this->scrapeMoney($item,$filter);
                    break;
                case 'spacing':
                    $item = $this->scrapeSpacing($item,$filter);
                    break;
                case 'replace':
                    $item = $this->scrapeReplace($item,$filter);
                    break;
                case 'range':
                    $res = $this->scrapeRange($item,$filter);
                    if(!$res){
                        $item = false;
                    }
                    break;
                case 'condition':
                    $res = $this->scrapeCondition($item,$filter);
                    if(!$res){
                        $item = false;
                    }
                    break;
                default:
                    break;
            }
        }
        return $item;
    }

    public function scrapeExplode($item, $filter){
        $temp = explode($filter['key'], $item);
        return trim($temp[$filter['position']]);
    }

    public function scrapePrepend($item, $filter){
        $temp = $filter.$item;
        return trim($temp);
    }

    public function scrapeAppend($item, $filter){
        $temp = $item.$filter;
        return trim($temp);
    }

    public function scrapeMoney($item, $filter){
        return trim(preg_replace("/[^0-9,.]/", "", $item));
    }

    public function scrapeSpacing($item, $filter){
        return trim(preg_replace('/\s+/', ' ', $item));
    }

    public function scrapeReplace($item, $filter){
        $temp = trim(str_replace($filter['key'], $filter['changeto'], $item));
        return $temp;
    }
    public function scrapeRange($item, $filter){
        $temp = $item >= $filter['from'] && $item <= $filter['to'];
        return $temp;
    }
    public function scrapeCondition($item, $filter){
        $temp = false;
        switch($filter['operator'])
        {
            case ">":
                $temp = $item > $filter['val'];
                break;
            case "<":
                $temp = $item < $filter['val'];
                break;
            case ">=":
                $temp = $item >= $filter['val'];
                break;
            case "<=":
                $temp = $item <= $filter['val'];
                break;
            case "==":
                $temp = $item == $filter['val'];
                break;
        }
        return $temp;
    }
}
