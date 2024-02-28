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
        $amount_display = 100;
        $timestamps = Timestamp::orderBy('created_at', 'desc')->take($amount_display)->get();
        $hourlyWage = 1300; // 時給

        // stamp_typeが今月のものを抽出
        $timestamps_this_month = $timestamps->filter(function ($timestamp) {
            return $timestamp->created_at->format('Y-m') === now()->format('Y-m');
        });

        // 今月のレコードがない場合
        if(!$timestamps_this_month->first()){
            return view('index', ['timestamps' => $timestamps_this_month, 'hourlyWage' => 0, 'totalTime' => 0.0, 'inflag' => false]);
        }
        
        // 最新がinの場合は、給料の計算から除外 inflagを立てる
        $inflag = false;
        if ($timestamps_this_month->first()->stamp_type === 'in') {
            $timestamps_this_month->shift();
            $inflag = true;
        }

        // outとinの時間の合計を検索し、時間単位で取得
        $totalTime = 0.0;
        $one = 0.0;
        foreach($timestamps_this_month as $timestamp_this_month){
            if($timestamp_this_month->stamp_type === 'out'){
                $one = $timestamp_this_month->created_at;
            }else{
                // oneに記録したoutの時間との差を計算する
                $totalTime += $timestamp_this_month->created_at->floatDiffInRealHours($one);
            }
        }

        return view('index', ['timestamps' => $timestamps_this_month, 'hourlyWage' => $hourlyWage, 'totalTime' => $totalTime, 'inflag' => $inflag]);
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
    public function export($month)
    {   
        if($month === 'last-month'){
            // 前月のデータを取得
            $timestamps = Timestamp::where('created_at', '>=', now()->subMonth()->startOfMonth())
                ->where('created_at', '<=', now()->subMonth()->endOfMonth())
                ->get();
        }else if($month === 'this-month'){
            // 今月のデータを取得
            $timestamps = Timestamp::where('created_at', '>=', now()->startOfMonth())
                ->where('created_at', '<=', now()->endOfMonth())
                ->get();
        }else{
            return redirect()->route('home')->with('error', 'エラーが発生しました。');
        }

        // もしデータがないならエラーを返す
        if(!$timestamps->first()){
            return redirect()->route('home')->with('error', 'データがありません。');
        }
        
        // もしinから始まっているならエラーを返す
        if($timestamps->first()->stamp_type === 'out'){
            return redirect()->route('home')->with('error', 'データにエラーがあります。');
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
        $csvFileName = '';
        if($month === 'last-month') $csvFileName = now()->subMonth()->format('Y-m') . '.csv';
        if($month === 'this-month') $csvFileName = now()->format('Y-m') . '.csv';

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
