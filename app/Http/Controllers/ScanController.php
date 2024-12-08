<?php

namespace App\Http\Controllers;
use App\Models\Service;
use App\Models\Device;
use App\Models\Scan;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
//use Illuminate\Support\Facades\Log;
class ScanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Define the hospitalId as a class property
    protected $hospitalId;
    public function __construct()
    {
         // Set the hospitalId to the authenticated user's ID
         $this->hospitalId = auth()->id();
    }
    public function index()
    {
        // return all categories to index pages 
        $categories = Category::all();
        $all_services = Service::all();
        $scan_with_pages = [];
        $query = Scan::with(['service','category']);
        //return Service::paginate();
        if (request()->has('ctx') && Str::of(request()->input('ctx'))->isNotEmpty()) {
            $service_ids = json_decode(request()->input('srv'));
            $category_ids = json_decode(request()->input('ctg'));
            $scan_ref = request()->input('ref');
            $scan_ref = Str::of($scan_ref)->trim();
            // Filter scans using the reject method
            $query = Scan::with(['service', 'category'])
            ->when($scan_ref->isNotEmpty(), function ($query) use ($scan_ref) {
                return $query->where('scan_reference', $scan_ref);
            })
            ->when(count($service_ids) !== 0, function ($query) use ($service_ids) {
                return $query->whereHas('service', function ($q) use ($service_ids) {
                    $q->whereIn('id', $service_ids);
                });
            })
            ->when(count($category_ids) !== 0, function ($query) use ($category_ids) {
                return $query->whereHas('category', function ($q) use ($category_ids) {
                    $q->whereIn('id', $category_ids);
                });
            });
        } 

        $scan_with_pages =  $query->paginate(Config::get('app.pages'));
        Log::info($scan_with_pages);
        if (request()->has('page') || request()->has('ctx')) {
            return $scan_with_pages;
        } else {
            return view('scans.index', compact('scan_with_pages', 'categories', 'all_services'));
        }
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        /*
        $devices = Device::whereHas('service.department', fn($query) => 
            $query->where('hospital_id', $this->hospitalId)
        )
        ->get();
        return view('scans.create', compact('devices'));
        */
        /*$devices = Device::whereHas('service.department', function ($query) {
            $query->where('hospital_id', $this->hospitalId);
        })
        ->with('service') // Eager load the `service` relationship
        ->get();*/
        //$services = Department::where('hospital_id', $this->hospitalId)
        //->get();
        $services = Service::whereHas('department', fn($query) => 
        $query->where('hospital_id', $this->hospitalId)
        )
        ->get();
        $categories = Category::all();
        return view('scans.create', compact('services', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $requestData = $request->all();
        $image_name = $request->file('image_name')->getClientOriginalName();
        $requestData['image_name'] = $image_name;
        $image_name_with_time = time().$image_name;
        $path = $request->file('image_name')->storeAs('images', $image_name_with_time, 'public');
        $requestData["path_name"] = $path;

        if ($request->has('produced_at')) {
            $producedAt = Carbon::createFromFormat('m/d/Y', $request->input('produced_at'))->format('Y-m-d');
        } else {
            $producedAt = null; // Handle missing input if necessary
        }
        $requestData["produced_at"] = $producedAt;
        Scan::create($requestData);
        return redirect('scans')->with('flash_message', 'Ajout image avec succés!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        /*$scan = Scan::with(['device', 'device.service'])->find($id);
        $devices = Device::whereHas('service.department', function ($query) {
            $query->where('hospital_id', $this->hospitalId);
        })
        ->with('service') // Eager load the `service` relationship
        ->get();
        return view('scans.edit', compact('devices', 'scan'));*/
        $scan = Scan::findOrFail($id);
        $services = Service::whereHas('department', function($query) {
            $query->where('hospital_id', $this->hospitalId);
        })->get();
        $categories = Category::all();
        //$services = Service::where('department.hospital_id', $this->hospitalId)->get();
        return view('scans.edit', compact('scan', 'services', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $requestData = $request->all();
        $scan = Scan::findOrFail($id);

        if ($request->has('produced_at')) {
            $producedAt = Carbon::createFromFormat('m/d/Y', $request->input('produced_at'))->format('Y-m-d');
        } else {
            $producedAt = null; // Handle missing input if necessary
        }
        $requestData["produced_at"] = $producedAt;
        if ($request->hasFile('image_name')){
            $image_name = $request->file('image_name')->getClientOriginalName();
            $requestData['image_name'] = $image_name;
            $image_name_with_time = time().$image_name;
            $path = $request->file('image_name')->storeAs('images', $image_name_with_time, 'public'); 
            $requestData["path_name"] = $path;
            // here i want to delete the old from file system  $scan->path_name
            Storage::disk('public')->delete($scan->path_name);
        }
        $scan->update($requestData);
        return redirect('scans')->with('flash_message', 'Image modifiée avec succès!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $scan = Scan::find($id);

        if(!$scan) {
            return response()->json(["error" => "image non trouvé !!"], 404);
        }
        $path_file = $scan->path_name;
        if($scan->delete()) {
            LOG::info("before delete");
            LOG::info($path_file);
            Storage::disk('public')->delete($path_file);
            LOG::info("after delete");
            return response()->json(["success" => "image  supprimé avec succés!!"], 200);
        }
    
        return response()->json(["error" => "An error occurred on the server."], 500);
    }
    // add liste des categories 
    public function categConfig(Request $request, string $context)
    {  
        //$categories =  explode(",", $request->input('name'));
        $categories = $request->input('newcateg');
        Log::info( $categories);
        if ($context == "add") {
            try {
                DB::transaction(function () use ($categories) {
                    // Check if any of the categories already exist
                    $existingCategories = Category::whereIn('name', $categories)->pluck('name')->toArray();
        
                    if (!empty($existingCategories)) {
                        // Cancel the operation if any exist
                        return response()->json(["error" => 'some categories already exist:'], 403);
                    }
        
                    // Create the categories
                    foreach ($categories as $category) {
                        Category::create(['name' => $category]);
                    }
                });
        
                return response()->json(['message' => 'Categories created successfully.'], 201);
        
            } catch (\Exception $e) {
                // Handle the error
                return response()->json(['error' => " server internal error "], 500);
            }
        }  else if ($conext = "del"){
            try {
                DB::transaction(function () use ($categories) {
                    // Check if any of the categories already exist
                    $existingCategories = Category::whereIn('name', $categories)->pluck('name')->toArray();
        
                    if (empty($existingCategories)) {
                        // Cancel the operation if any exist
                        return response()->json(["error" => 'some categories not found :'], 403);
                    }
        
                    // Create the categories
                    foreach ($categories as $category) {
                        $deleted = Category::where('name', $category)->delete();
                        if (!$deleted) {
                            return response()->json(["error" => 'ressource forbiden to delete :'], 403);
                        }
                    }
                });
                return response()->json(['message' => 'Categories deleted  successfully.'], 201);
            } catch (\Exception $e) {
                return response()->json(['error' => " server internal error "], 500);
            }
        }
        return response()->json(["success" => " test context not defined !!"], 200);
        
    }

}
