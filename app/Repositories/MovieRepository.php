<?php

namespace App\Repositories;

use App\Models\Movie;
use App\Interfaces\MovieRepositoryInterface;

class MovieRepository implements MovieRepositoryInterface
{
    public function getAll()
    {
        return Movie::all();
    }

    public function getById($id)
    {
        return Movie::findOrFail($id);
    }

    public function create(array $data)
    {
        return Movie::create($data);
    }

    public function update($id, array $data)
    {
        $movie = Movie::findOrFail($id);
        $movie->update($data);
        return $movie;
    }

    public function delete($id)
    {
        return Movie::destroy($id);
    }
}