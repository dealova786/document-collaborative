<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use Illuminate\Http\Request;
use App\Events\DocumentUpdated;
use App\Events\UserTyping;
use App\Events\CursorMoved;
use App\Models\DocumentHistory;

class DokumenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dokumen = Dokumen::with('user')
            ->latest()
            ->get();

        return view('documents.index', compact('dokumen'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('documents.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Dokumen::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return redirect('/document');
    }

    /**
     * Display the specified resource.
     */
    public function show(dokumen $dokumen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $dokumen = Dokumen::findOrFail($id);

        $histories = DocumentHistory::where(
            'dokumen_id',
            $dokumen->id
        )->latest()->get();

        return view('documents.edit', compact(
            'dokumen',
            'histories'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {   
        $dokumen = Dokumen::findOrFail($id);

        DocumentHistory::create([
            'dokumen_id' => $dokumen->id,
            'user_id' => auth()->id(),
            'content' => $dokumen->content,
        ]);

        $dokumen->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        $dokumen->refresh();

        broadcast(new DocumentUpdated(
            $dokumen->id,
            $dokumen->title,
            $dokumen->content
        ))->toOthers();

        return response()->json([
            'success' => true
        ]);
    }

    public function typing($id)
    {
        broadcast(new UserTyping(
            $id,
            auth()->user()->name
        ))->toOthers();

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $dokumen = Dokumen::findOrFail($id);
        $dokumen->delete();
        return redirect('/document');
    }

    public function cursor($id, Request $request)
    {
        broadcast(new CursorMoved(
            $id,
            auth()->user()->name,
            $request->position
        ))->toOthers();

        return response()->json([
            'success' => true
        ]);
    }
}

