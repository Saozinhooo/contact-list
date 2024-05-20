<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = Auth::id();

        // Fetch paginated results instead of all results
        $people = Person::where('user_id', $user_id)->paginate(3);

        return view('person.index', ['people' => $people]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $people = Person::all();
        return view('person.create', ['people' => $people]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request )
    {
        $user_id = Auth::id();
        $validated = $request->validate([
            'name' => 'required',
            'company' => 'required',
            'email' => 'nullable|email',
            'phone' => 'nullable',
        ]);

        $validated['user_id'] = $user_id;

        $person = Person::create($validated);

        return redirect()->route('person.index')->with('success', 'Contact added successfully!');
    }
    /**
     * Display the specified resource.
     */
    public function show(Person $person)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($person)
    {
        $people = Person::findOrFail($person);

        return view('person.edit', ['person' => $people]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, Request $request)
    {
        $person = Person::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required',
            'company' => 'required',
            'email' => 'nullable|email',
            'phone' => 'nullable',
        ]);

        
        $validated['name'] = strip_tags($validated['name']);
        $validated['company'] = strip_tags($validated['company']);
        $validated['email'] = strip_tags($validated['email']);
        $validated['phone'] = strip_tags($validated['phone']);

        $person->update($validated);
        return redirect(route('person.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Person $person)
    {
        $person->delete();
        
        $people = Person::all();

        return view('person.index', ['people' => $people]);
    }

    public function search(Request $request)
{
    $user_id = Auth::id();

    $searchTerm = $request->get('query');
    $results = Person::where('user_id', $user_id)
        ->where(function($query) use ($searchTerm) {
            $query->where('name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('company', 'LIKE', "%{$searchTerm}%")
                ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                ->orWhere('phone', 'LIKE', "%{$searchTerm}%");
        })
        ->paginate(3); // Paginate the search results

    if ($request->ajax()) {
        $output = '';
        if ($results->count() > 0) {
            foreach ($results as $result) {
                $output .= '
                    <tr>
                        <td>' . $result->name . '</td>
                        <td>' . $result->company . '</td>
                        <td>' . $result->email . '</td>
                        <td>' . $result->phone . '</td>
                        <td>
                            <a href="' . route('person.edit', $result->id) . '">Edit</a>
                            <a href="' . route('person.destroy', $result->id) . '" onclick="return confirm(\'Are you sure you want to delete?\')">Delete</a>
                        </td>
                    </tr>';
            }
        } else {
            $output .= '
                <tr>
                    <td colspan="3">No results found</td>
                </tr>';
        }
        return $output;
    }
}
}
