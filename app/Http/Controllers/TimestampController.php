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

        $amount_display = 50;
        $timestamps = Timestamp::orderBy('created_at', 'desc')->take($amount_display)->get();
        return view('index', ['timestamps' => $timestamps, 'url' => $url]);
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
