<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class ClientController extends Controller
{


    // Permissions Method
    public function __construct()
    {
        // Define required permissions for each action in the Client module
        $this->setPermissions([
            'index'   => 'client_list',   // Permission to view client list
            'create'  => 'client_add',    // Permission to add a new client
            'edit'    => 'client_edit',   // Permission to edit existing client
            'destroy' => 'client_delete', // Permission to delete a client
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::all();
        return view('client.index', [
            'clients' => $clients,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('client.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:clients,email',
        ]);

        Client::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Log the action
        userLog('Client Create', 'Created a New Client - ' . $request->name);

        return Redirect::route('client.index')->with('success', 'Client Created Successfully');
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
        $client = Client::findOrFail($id);
        return view('client.edit', [
            'client' => $client,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'nullable|email|unique:clients,email,' . $id,
        ]);

        $client = Client::findOrFail($id);
        $client->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Log the action
        userLog('Client Update', 'Updated a Client - ' . $request->name);

        return Redirect::route('client.index')->with('success', 'Client Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $client = Client::findOrFail($id);

        // Log the action
        userLog('Client Delete', 'Destroyed a Client - ' . $client->name);

        try {
            // Delete client
            $client->delete();
        } catch (\Exception $e) {
            Log::error($e);
            return error($e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'Client Deleted Successfully'], 200);
    }
}
