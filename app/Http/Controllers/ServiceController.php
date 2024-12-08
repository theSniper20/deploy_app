<?php

namespace App\Http\Controllers;
use App\Models\Service;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class ServiceController extends Controller
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
        $departments = Department::where('hospital_id', $this->hospitalId)->get();

        // Fetch services linked to the hospital via departments
        $services = Service::with(['scans.category', 'department'])
            ->whereHas('department', function ($query) {
                $query->where('hospital_id', $this->hospitalId); // Filter departments by hospital_id
            })
            ->get();

        // Attach all departments to each service
        $services->each(function ($service) use ($departments) {
            $service->all_departments = $departments;
        });

        //return compact('services');
        // Return the view with the services
        return view('services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        //$departments = Department::all();
        $departments = Department::where('hospital_id', $this->hospitalId)
        ->get();
        return view('services.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $requestData = $request->all();
        Service::create($requestData);
        return redirect('services')->with('flash_message', 'creation service  avec succés!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Retrieve the specific service with its department
        /*
        $service = Service::with('department')->findOrFail($id);
        return view ('services.index')->with('services', $services);*/
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $service = Service::find($id);
        if(!$service) {
            return response()->json(["error" => "Service non trouvé !!"], 404);
        }
        $department  = Department::where('name', $request->input('department_name'))->first();
        if(!$department) {
            return response()->json(["error" => "Departement non trouvé !!"], 404);
        }
        $transaction_ok = $service->update($request->only('name', 'description')  + ['department_id' => $department->id]);
        if($transaction_ok ) {
            return response()->json(["success" => "mise a jour departement avec succés!!"], 200);
        }
        return response()->json(["error" => "An error occurred on the server."], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $service = Service::find($id);

        if(!$service) {
            return response()->json(["error" => "Service non trouvé !!"], 404);
        }
    
        if($service->delete()) {
            return response()->json(["success" => "Service supprimé avec succés!!"], 200);
        }
    
        return response()->json(["error" => "An error occurred on the server."], 500);
    }
}
