<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Module::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validazione dei dati in ingresso
        $validatedData = $request->validate([
            'module_tag' => 'required|string|unique:modules',
            'module_des' => 'required|string|max:255',
            'group_id' => 'required|integer|exists:groups,id',
        ]);

        // Creazione del record
        $module = Module::create($validatedData);
        
        // Restituzione della risposta
        return response()->json([
            'message' => 'Module created successfully',
            'module' => $module,
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function show(Module $module)
    {
        return response()->json($module, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function edit(Module $module)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Module $module)
    {
        // Validazione dei dati in ingresso
        $validatedData = $request->validate([
            'module_tag' => 'required|string|unique:modules,module_tag,' . $module->id,
            'module_des' => 'required|string|max:255',
            'group_id' => 'required|integer|exists:groups,id',
        ]);

        // Aggiornamento del record
        $module->update($validatedData);
        
        // Restituzione della risposta
        return response()->json([
            'message' => 'Module updated successfully',
            'module' => $module,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function destroy(Module $module)
    {
        $module->delete();
        
        return response()->json([
            'message' => 'Module deleted successfully',
        ], 200);
    }

    /**
     * Get the list of users can manage the module
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function users(Module $module)
    {
        return $module->users;
    }

    /**
     * Get the group of the module
     *
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function group(Module $module)
    {
        return $module->group;
    }   
}
