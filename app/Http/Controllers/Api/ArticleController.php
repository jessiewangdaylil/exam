<?php

namespace App\Http\Controllers\Api;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

//請完成下方所有方法的實作，並撰寫對應的路由，用 Postman 來進行測試
class ArticleController extends Controller
{
    /**
     * 回傳該表格的所有資料，以 sort 欄位從小到大排序
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $articles = Article::select('sort')->orderBy('sort', 'asc')->get();
        dd($articles);
        return $articles;
    }

    /**
     * 儲存前端傳入的資料，成功後回傳儲存的內容
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $article = Article::create($request->all());
       return $article;
        // return redirect('index');

    }

    /**
     * 回傳指定的資料
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $article = Article::find($id);
       dd($article);
       return $article;
    }

    /**
     * 更新指定的資料，成功後回傳更新後的內容
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //$article = $article->update($request->only(['subject','content','enabled_at','sort','enabled','cgy_id']));
        $article = Article::find($id);
        $article ->subject=$request['subject'];
        $article ->content=$request['content'];
        $article ->enabled_at=$request['enabled_at'];
        $article ->sort=$request['sort'];
        $article ->enabled=$request['enabled'];
        $article ->cgy_id=$request['cgy_id'];
        $article ->save();
        return $article;
        //  return redirect('/');
    }

    /**
     * 刪除指定的資料，成功後回傳 Success
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $num = Article::destroy($id);
        Article::findOrFail($id);
        return  $num.'Delete Successfully';
    }

    //查詢所有資料，只取 id , subject 以及 content 這三個欄位
    public function querySelect()
    {
         $articles = Article::select(['id', 'subject', 'content'])->get();
         dd($articles);
        return $articles;
         //  echo "<pre>";
        //  var_dump($articles);
        //  die();
    }

    //查詢 enabled_at 於 2022/12/13 00:00:00 之後，enabled 為 true 的資料，按照 created_at 從新到舊排序，回傳第一筆資料的 subject 欄位內容
    public function querySpecific()
    {

        $date = Carbon::createFromFormat('Y-m-d H:i:s', '2022-12-13 00:00:00');
        $article = Article::where('enabled_at', '>', $date)->where('enabled', true)->orderBy('created_at', 'desc')->first();//單數
         dd($article->subject);
         return $article;

    }

    //查詢 enabled_at 於 2022/12/10 00:00:00 之後，enabled 為 true 的資料，按照 created_at 從新到舊排序，回傳第2~4筆資料
    public function queryPagination()
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', '2022-12-10 00:00:00');
        $articles = Article::where('enabled_at', '>', $date)->where('enabled', true)->orderBy('created_at', 'desc')->take(4)->get()->skip(1);
         dd($articles);
         return $articles;
    }

    //查詢 enabled_at 介於 2022/12/10 00:00:00 和 2022/12/15 23:59:59 之間，sort 位於 $min 到 $max 之間的資料並回傳
    public function queryRange($min, $max)
    {
        $date_s = Carbon::createFromFormat('Y-m-d H:i:s', '2022-12-10 00:00:00');
        $date_e = Carbon::createFromFormat('Y-m-d H:i:s', '2022-12-15 23:59:59');
        $articles = Article::wherebetween('enabled_at', [$date_s,$date_e])->wherebetween('sort', [$min,$max])->get();
        dd($articles);
        return $articles;
    }

    //根據所傳入的分類id，取出該分類所有 enabled 為 true 的資料，依照 sort 從小到大排序，回傳符合的資料
    public function queryByCgy($cgy_id)
    {

      $articles =Article::where('cgy_id',$cgy_id)->where('enabled',true)->orderby('sort','asc')->get();
       dd($articles);
       return $articles;
    }

    //試著使用 pluck() 來取得 id 為 key ， subject 為 value 的陣列
    public function queryPluck()
    {
      $articles=Article::pluck('subject','id');
      dd($articles);
      return $articles;
    }

    //計算所有 enabled 為 true 的資料筆數後回傳，利用查詢方法 count()
    public function enabledCount()
    {
      $articles=Article::where('enabled',true)->get();
      dd(count($articles));
      return count($articles);
    }
}