<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Group::all();
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
            'group_elix_id' => 'required|integer|unique:groups',
            'group_des' => 'required|string|max:255',
        ]);

        // Creazione del record
        $group = Group::create($validatedData);
        
        // Restituzione della risposta
        return response()->json([
            'message' => 'Group created successfully',
            'group' => $group,
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        return response()->json($group, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
         // Validazione dei dati in ingresso
        $validatedData = $request->validate([
            'group_elix_id' => 'required|integer|unique:groups,group_elix_id,' . $group->id,
            'group_des' => 'required|string|max:255',
        ]);

        // Aggiornamento del record
        $group->update($validatedData);

        // Restituzione della risposta
        return response()->json([
            'message' => 'Group updated successfully',
            'group' => $group,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        // Eliminazione del record
        $group->delete();

        // Restituzione della risposta
        return response()->json([
            'message' => 'Group deleted successfully',
        ], 200);
    }

    /**
     * Get the list of users in the group
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function users(Group $group)
    {
        return $group->users;
    }
}
