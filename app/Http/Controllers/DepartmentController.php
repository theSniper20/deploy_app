<?php

namespace App\Http\Controllers;
use App\Models\Department;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DepartmentController extends Controller
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
        $departments = Department::where('hospital_id', $this->hospitalId)
            ->with('services')
            ->get();
        return view('departments.index', compact('departments'));
    }
    /**
     *  json for refresh index departments
     */
    public function getJsonDepartments()
    {   
        // Fetch all departments with their associated services
        $departments = Department::where('hospital_id', $this->hospitalId)
            ->whith('services')
            ->get();
        // Return the departments as a JSON response
        return response()->json($departments);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('departments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $requestData = $request->all();
        $requestData['hospital_id'] = $this->hospitalId;
        Department::create($requestData);
        return redirect('departments')->with('flash_message', 'creation department avec succés!');
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
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {   
        $department = Department::find($id);
        if(!$department) {
            return response()->json(["error" => "Departement non trouvé !!"], 404);
        }
        $transaction_ok = $department->update($request->only('name', 'description'));
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
        $department = Department::find($id);

        if(!$department) {
            return response()->json(["error" => "Departement non trouvé !!"], 404);
        }
    
        if($department->delete()) {
            return response()->json(["success" => "departement supprimé avec succés!!"], 200);
        }
    
        return response()->json(["error" => "An error occurred on the server."], 500);
    }
}
