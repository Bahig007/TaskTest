<?php 

namespace App\Repository\Interfaces;

use Illuminate\Http\Request;

interface TaskRepositoryInterface
{
    public function getTasks(array $data);
    public function show($id);
    public function store(array $data);    
    public function update(array $data, $id);
    public function destroy($id);
}