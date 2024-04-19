<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;

class MovieController extends Controller
{
    public function index()
{
    $movies = Movie::all();

    $formattedMovies = $movies->map(function ($movie) {
        return [
            'id' => $movie->id,
            'title' => $movie->title,
            'description' => $movie->description,
            'poster' => '/api/v1/posters/' . $movie->poster,
        ];
    });

    return response()->json(['message' => 'Movies retrieved successfully', 'movies' => $formattedMovies]);
}


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // $file = $request->file('poster');
        // $filename = time() . '.' . $file->getClientOriginalExtension();
        // $path = $file->storeAs('posters', $filename, 'public');

        $movie = new Movie();
        $movie->title = $request->title;
        $movie->description = $request->description;

        if ($request->hasFile('poster')) {
            $poster = $request->file('poster');
            $posterName = time() . '_' . $poster->getClientOriginalName(); // Use a unique name for the poster
            $posterPath = $poster->storeAs('public/posters', $posterName);
            $posterFinal = str_replace('public/', '', $posterPath);
            $movie->poster = asset('storage/' . $posterFinal);
        }

        else {
            return response()->json([
                'message' => 'The poster field is required.',
                'errors' => [
                    'poster' => ['The poster field is required.']
                ]
            ], 422);
        }

        $movie->save();

        return response()->json(['message' => 'Movie created successfully', 'data' => $movie], 201);
    }
}