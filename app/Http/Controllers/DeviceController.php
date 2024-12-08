<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\Service;
use Illuminate\Support\Facades\Log;
class DeviceController extends Controller
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
        // Fetch devices with related services that belong to the current hospital
        $devices = Device::whereHas('service.department', function ($query) {
            $query->where('hospital_id', $this->hospitalId);
        })
        ->with('service')
        ->get();
        // Fetch all services linked to the current hospital
        $services = Service::whereHas('department', fn($query) => 
            $query->where('hospital_id', $this->hospitalId)
        )
        ->get();
        // Return the view with devices and services
        return view('devices.index', compact('devices', 'services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = Service::whereHas('department', fn($query) => 
            $query->where('hospital_id', $this->hospitalId)
        )
        ->get();
        return view('devices.create', compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $requestData = $request->all();
        Device::create($requestData);
        return redirect('devices')->with('flash_message', 'creation materiel avec succés!');
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
        //
        $device = Device::find($id);
        if(!$device) {
            return response()->json(["error" => "materiel non trouvé !!"], 404);
        }
        $service  = Service::where('name', $request->input('service_name'))->first();
        if(!$service) {
            return response()->json(["error" => "Service non trouvé !!"], 404);
        }
        $transaction_ok = $device->update($request->only('name', 'description')  + ['service_id' => $service->id]);
        if($transaction_ok ) {
            return response()->json(["success" => "mise a jour materiel avec succés!!"], 200);
        }
        return response()->json(["error" => "An error occurred on the server."], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $device = Device::find($id);

        if(!$device) {
            return response()->json(["error" => "Materiel non trouvé !!"], 404);
        }
    
        if($device->delete()) {
            return response()->json(["success" => "Materiel supprimé avec succés!!"], 200);
        }
    
        return response()->json(["error" => "An error occurred on the server."], 500);
    }
}
