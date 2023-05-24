<?php

namespace ArieTimmerman\Laravel\URLShortener\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use ArieTimmerman\Laravel\URLShortener\URL;
use ArieTimmerman\Laravel\URLShortener\URLShortener;

class URLController extends Controller
{
    
    public function getRules()
    {
        return [
        "url" => 'required|url',
        ];
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
                
        return URL::paginate(100);
        
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request            
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), $this->getRules());

        if ($validator->fails()) {
            abort(422, $validator->errors());
        }

        
        $url = new URL(array_intersect_key($request->all(), $this->getRules()));
        
        $url->save();
        
        return $url;
        
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int $id            
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return URL::where('id', $id)->firstOrFail();
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request            
     * @param  int                      $id            
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $this->validate($request, $this->getRules());
        
        $url = URL::where('id', $id)->firstOrFail();
        
        $url->fill(
            array_intersect_key($request->all(), $this->getRules())
        );
         
        $url->save();
        
        return $url;
        
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id            
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $url = URL::where('id', $id)->firstOrFail();
        $url->delete();

        return response(null, 204);
        
    }
}
