<?php
    
namespace App\Http\Controllers;
    
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
    
class PermissionController extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:permission-list|permission-create|permission-edit|permission-delete', ['only' => ['index','show']]);
         $this->middleware('permission:permission-create', ['only' => ['create','store']]);
         $this->middleware('permission:permission-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:permission-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        $getMenu = DB::table("permissions")->paginate(8);
        return view('menus.index',compact('getMenu'))
            ->with('i', (request()->input('page', 1) - 1) * 8);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        return view('menus.create');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        DB::
        table('permissions')
        ->insert([
            'name' =>$request->name,
            'guard_name' => 'web'
        ]); 
    
        return redirect()->route('menus.index')
                        ->with('success','Menu created successfully.');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $menu = DB::
        table('permissions')
        ->where('id', '=', $id)
        ->first();
        return view('menus.show',compact('menu'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $menu = DB::
        table('permissions')
        ->where('id', '=', $id)
        ->first();
        return view('menus.edit',compact('menu'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //  request()->validate([
        //     'name' => 'required',
        //     'detail' => 'required',
        // ]);
        DB::
        table('permissions')
        ->where('id', $id)
        ->update([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);

        return redirect()->route('menus.index')
                        ->with('success','Menu updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
    
        return redirect()->route('menus.index')
                        ->with('success','Menu deleted successfully');
    }
}