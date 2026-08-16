<?php

namespace App\Services;

use App\Models\Movie;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Interfaces\MovieRepositoryInterface;

class MovieService
{
    protected $movieRepository;

    public function __construct(MovieRepositoryInterface $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }

    public function getHomepageMovies($search)
    {
        $query = Movie::latest();
        if ($search) {
            $query->where('judul', 'like', '%' . $search . '%')
                  ->orWhere('sinopsis', 'like', '%' . $search . '%');
        }
        return $query->paginate(6)->withQueryString();
    }

    public function getAllCategories()
    {
        return Category::all();
    }

    public function getAdminMovies()
    {
        return Movie::latest()->paginate(10);
    }

    public function getMovieById($id)
    {
        return $this->movieRepository->getById($id);
    }

    public function storeMovie($request)
    {
        $data = $request->except('foto_sampul');

        if ($request->hasFile('foto_sampul')) {
            $randomName = Str::uuid()->toString();
            $fileExtension = $request->file('foto_sampul')->getClientOriginalExtension();
            $fileName = $randomName . '.' . $fileExtension;
            $request->file('foto_sampul')->move(public_path('images'), $fileName);
            $data['foto_sampul'] = $fileName;
        }

        return $this->movieRepository->create($data);
    }

    public function updateMovie($request, $id)
    {
        $movie = $this->movieRepository->getById($id);
        $data = $request->except('foto_sampul');

        if ($request->hasFile('foto_sampul')) {
            $randomName = Str::uuid()->toString();
            $fileExtension = $request->file('foto_sampul')->getClientOriginalExtension();
            $fileName = $randomName . '.' . $fileExtension;
            $request->file('foto_sampul')->move(public_path('images'), $fileName);

            // Hapus foto lama
            if (File::exists(public_path('images/' . $movie->foto_sampul))) {
                File::delete(public_path('images/' . $movie->foto_sampul));
            }
            $data['foto_sampul'] = $fileName;
        }

        return $this->movieRepository->update($id, $data);
    }

    public function deleteMovie($id)
    {
        $movie = $this->movieRepository->getById($id);
        
        // Hapus foto sebelum menghapus data
        if (File::exists(public_path('images/' . $movie->foto_sampul))) {
            File::delete(public_path('images/' . $movie->foto_sampul));
        }
        
        return $this->movieRepository->delete($id);
    }
}