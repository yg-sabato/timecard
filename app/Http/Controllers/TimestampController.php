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

        return view('index', ['timestamps' => $timestamps, 'hourlyWage' => $hourlyWage, 'totalTime' => $totalTime, 'inflag' => $inflag]);
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
}
