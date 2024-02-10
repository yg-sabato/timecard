<?php

namespace App\Http\Controllers;

use App\Models\Timestamp;
use Illuminate\Http\Request;

class TimestampController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // クエリパラメータに指定された'url'を取得
        $url = request()->query('url');
        $url = $url ? $url : '';

        $amount_display = 100;
        $timestamps = Timestamp::orderBy('created_at', 'desc')->take($amount_display)->get();
        return view('index', ['timestamps' => $timestamps]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $timestamp = new Timestamp;
        $timestamp->stamp_type = $request->stamp_type;
        $timestamp->description = $request->stamp_type === 'in' ? $request->description : 'out';
        $timestamp->save();
        return redirect()->route('home');
    }

    /**
     * Display the specified resource.
     */
    public function show(Timestamp $timestamp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Timestamp $timestamp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Timestamp $timestamp)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Timestamp $timestamp)
    {
        //
    }

    // 前月分のデータをcsv書き出し
    public function export()
    {
        // 今日を起点にして、前月のデータを取得
        $timestamps = Timestamp::where('created_at', '>=', now()->subMonth()->startOfMonth())
            ->where('created_at', '<=', now()->subMonth()->endOfMonth())
            ->get();

        // もしデータがないならエラーを返す
        if(!$timestamps->first()){
            return redirect()->route('home')->with('error', '先月のデータがありません。');
        }
        
        // もしinから始まっているならエラーを返す
        if($timestamps->first()->stamp_type === 'out'){
            return redirect()->route('home')->with('error', '先月のデータにエラーがあります。');
        }

        // csvのヘッダーを作成
        $csvHeader = ['作業内容', '日付', '開始時間', '終了時間'];

        // csvのデータを作成
        $csvData = [];
        $in_time = null;
        $in_description = "";
        foreach($timestamps as $timestamp){
            if($timestamp->stamp_type === 'in'){
                if(!is_null($in_time)){
                    return redirect()->route('home')->with('error', '先月のデータにエラーがあります。');
                }
                $in_time = $timestamp->created_at;
                $in_description = $timestamp->description;
            }else{
                if(is_null($in_time)){
                    return redirect()->route('home')->with('error', '先月のデータにエラーがあります。');
                }
                $csvData[] = [
                    $in_description,
                    $timestamp->created_at->format('Y-m-d'),
                    $in_time->format('H:i'),
                    $timestamp->created_at->format('H:i')
                ];
                $in_time = null;
            }
        }

        // csvのファイル名を作成
        $csvFileName = now()->subMonth()->format('Y-m') . '.csv';

        // csvを作成してダウンロード
        return response()->streamDownload(function() use ($csvHeader, $csvData) {
            $stream = fopen('php://output', 'w');
            stream_filter_prepend($stream,'convert.iconv.utf-8/cp932//TRANSLIT');
            fputcsv($stream, $csvHeader);
            foreach ($csvData as $row) {
                fputcsv($stream, $row);
            }
            fclose($stream);
        }, $csvFileName);
    }
}
