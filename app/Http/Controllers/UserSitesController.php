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
            'title' => strtolower($r->title),
            'user_id' => Auth::id(),
            'singlepage' => $r->singlepage,
            'page_string' => $r->page_string,
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
        $d->page_string = $r->page_string;
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
        $siteitem = UserSites::where('id',$id)->where('user_id',Auth::id())->first();
        $siteitem['scrape_data'] = json_decode($siteitem['scrape_data'],true);
        //dd($siteitem['scrape_data']);
        $crawler = Goutte::request('GET', $siteitem['url']);

        $resNode = $crawler->filter('.product_whole')->each(function ($node) use ($siteitem){
            $res = array();
            foreach($siteitem['scrape_data'] as $key => $item){
                $tempVal = '';
                $section = $node->filter($item['element']);
                if(count($section) > 0){
                if($item['attribute'] == 'text'){
                    if(count($section) > 1){
                        //$tempVal = '';
                        $tempVal = $this->scrapeGetPosition($section,$item);
                    }
                    else{
                        $tempVal = trim($section->text());
                    }
                }
                else{
                    if(count($section) > 1){
                        $tempVal = $this->scrapeGetPosition($section,$item);
                    }
                    else{
                        $tempVal = trim($section->attr($item['attribute']));
                    }
                }
                if($item['filters'] != null){
                    $item['filters'] = json_decode($item['filters'],true);
                    $tempVal = $this->filterItem($tempVal,$item['filters']);
                }
                $res[$item['title']] = $tempVal;
            }
            }
            return $res;
        });
        //exit;
        dd($resNode);
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
}
