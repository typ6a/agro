<?php

namespace App\Http\Controllers\Trader;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Category;
use App\Ad;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AdFormRequest;
use App\Http\Requests\AdEditFormRequest;

class AdsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ads = Ad::all();
        return view('trader.ads.index', compact('ads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('trader.ads.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdFormRequest $request)
    {
        $user_id = Auth::user()->id;
        $ad= new Ad(array(
            'title' => $request->get('title'),
            'content' => $request->get('content'),
            'slug' => Str::slug($request->get('title'), '-'),
            'user_id' => $user_id
        ));

        $ad->save();
        $ad->categories()->sync($request->get('categories'));

        return redirect('/trader/ads/create')->with('status', 'Объявление создано!');
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
        $ad = Ad::whereId($id)->firstOrFail();
        $categories = Category::all();
        $selectedCategories = $ad->categories->pluck('id')->toArray();
        return view('trader.ads.edit', compact('ad', 'categories', 'selectedCategories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, AdEditFormRequest $request)
    {
        $ad = Ad::whereId($id)->firstOrFail();
        $ad->title = $request->get('title');
        $ad->content = $request->get('content');
        $ad->slug = Str::slug($request->get('title'), '-');

        $ad->save();
        $ad->categories()->sync($request->get('categories'));

        return redirect(action('Trader\AdsController@edit', $ad->id))->with('status', 'Объявление обновлено!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
