<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ListsController extends Controller
{

    // Получение списка покупок
    public function ajax_get(Request $request){
        if ($request->ajax()){

            $response_err = response()->json([
                'message' => 'Не удалось совершить операцию получения данных на сервере.'
            ]);

            if (!Auth::check()) {
                return $response_err;
            }

            $user_id = Auth::user()->id;

            if ($request->list_id && $request->list_id >= 0 ){
                $list_id = $request->list_id;
            } else {
                $list_id = DB::table('lists')
                    ->where('user_id',$user_id)
                    ->value('id');
            }

            if (!$list_id) {
                $list_id = -1;
                $list = [];
                $budget = 0;
            } else {
                $budget = DB::table('lists')
                    ->where('user_id',$user_id)
                    ->value('budget');

                $list = DB::table('lists')
                    ->join('items','lists.id','=','items.list_id')
                    ->where('list_id', $list_id)
                    ->select('items.name as name','items.price as price','items.count as count', 'lists.budget as budget ')
                    ->get();

                if (!$list) {
                    return $response_err;
                }
            }
            return response()->json([
                'all_data' => $list,
                'list_id' => $list_id,
                'budget' => $budget
            ]);
        }
    }

    // Сохранение нового/измененного списка покупок
    public function ajax_save(Request $request){

        if ($request->ajax() && $request->all()){

            if (!Auth::check() || !$request->isMethod('post')) {
                return response()->json([
                    'message' => 'Не удалось совершить операцию сохранения данных на сервере.'
                ]);
            }

            $user_id = Auth::user()->id;

//            DB::table('items')->where('user_id',$user_id)->delete();

            if ($request->list_id && $request->list_id >= 0 ){
                $list_id = $request->list_id;
            } else {
                $list_id = DB::table('lists')
                    ->where('user_id',$user_id)
                    ->value('id');
            }

            if ($list_id) {
                DB::table('items')->where('list_id',$list_id)->delete();
                DB::table('lists')->where('id',$list_id)->delete();
            }

            $budget = $request->total_plan;

            DB::table('lists')->insert([
                ['user_id' => $user_id , 'budget' => $budget]
            ]);
            $list_last_id = DB::table('lists')->max('id');

            $items = $request->items;

            $data = array();
            foreach($items as $item){
                $data[] = [
                    'list_id' => $list_last_id,
                    'name' => $item['text'],
                    'price' => $item['price'],
                    'count' => $item['count']
                ];
            }
            DB::table('items')->insert($data);

            return response()->json([
                'message' => 'Список сохранен на сервере'
            ]);
        }
    }

    // Удаление списка покупок
    public function ajax_delete(Request $request){

        if ($request->ajax()){

            if (!Auth::check()) {
                return response()->json([
                    'message' => 'Не удалось совершить операцию сохранения данных на сервере.'
                ]);
            }

            $user_id = Auth::user()->id;

            if ($request->list_id && $request->list_id >= 0 ){
                $list_id = $request->list_id;
            } else {
                $list_id = DB::table('lists')
                    ->where('user_id',$user_id)
                    ->value('id');
            }

            if ($list_id) {
                DB::table('items')->where('list_id',$list_id)->delete();
                DB::table('lists')->where('id',$list_id)->delete();
                return response()->json([
                    'message' => 'Список удален с сервера'
                ]);
            }

            return response()->json([
                'message' => ''
            ]);
        }
    }
}

