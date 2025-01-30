<?php

namespace App\Repository;

use App\Repository\Interfaces\TaskRepositoryInterface;
use Illuminate\Support\Facades\DB;

class TaskRepository implements TaskRepositoryInterface
{
    public function getTasks(array $data)
    {

        try {

           $tasks = DB::table('tasks')
           ->when(isset($data['status']), function ($query) use ($data) {
            return $query->where('tasks.status', $data['status']);
            })
            ->when(isset($data['due_date']), function ($query) use ($data) {
                return $query->whereDate('tasks.due_date', $data['due_date']);
            })
            ->when(isset($data['user_id']), function ($query) use ($data) {
                return $query->where('tasks.user_id', $data['user_id']);
            })
            ->when(isset($data['title']), function ($query) use ($data) {
                return $query->where('tasks.title', 'like', '%' . $data['title'] . '%');
         })
           ->leftJoin('users', 'tasks.user_id', '=', 'users.id')
           ->select('tasks.*', 'users.name as user_name')
           ->orderBy('tasks.id', 'desc')
           ->paginate(10);

      
            return ['success' => true, 'data' => $tasks];
        } catch (\Throwable $th) {
      
            return [ 'success' => false, 'message' => $th->getMessage()];
        }
        
    }

    public function show($id)
    {
     
        try {

            $task = DB::table('tasks')
            ->where('tasks.id', $id)
            ->leftJoin('users', 'tasks.user_id', '=', 'users.id')
            ->select('tasks.*', 'users.name as user_name')
            ->first();

           return ['success' => true, 'data' => $task];

        } catch (\Throwable $th) {

            return [ 'success' => false, 'message' => $th->getMessage()];

        }
    }

    public function store(array $data)
    {
        try {

            $info = [
                'user_id' => $data['user_id'],
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'status' => $data['status'],
                'due_date' => $data['due_date'],
            ];
            $task = DB::table('tasks')->insert($info);


          

            return ['success' => true, 'message' => "Task Created Successfully" , 'data' => $task];
        } catch (\Throwable $th) {
     
            return [ 'success' => false, 'message' => $th->getMessage()];
        }
    }

    public function update(array $data, $id)
    {
        try {

            $info = [
                'user_id' => $data['user_id'],
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'status' => $data['status'],
                'due_date' => $data['due_date'],
            ];
            $task = DB::table('tasks')
            ->where('id', $id)
            ->update($info);

            return ['success' => true, 'message' => "Task Updated Successfully" , 'data' => $task];
            
        } catch (\Throwable $th) {

            return [ 'success' => false, 'message' => $th->getMessage()];
        }
    }

    public function destroy($id)
    {
        try {

            $task = DB::table('tasks')
            ->where('id', $id)
            ->delete();

            return ['success' => true, 'message' => "Task Deleted Successfully"];
        } catch (\Throwable $th) {

            return [ 'success' => false, 'message' => $th->getMessage()];
        }
    }
}