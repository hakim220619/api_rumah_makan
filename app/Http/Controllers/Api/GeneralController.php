<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class GeneralController extends Controller
{
    function getRumahMakan()
    {
        $data = DB::select("SELECT w.*, (SELECT (sum(r.rate) / COUNT(r.id)) from rate r WHERE r.id_rumah_makan=w.id) as rate FROM rumah_makan w ORDER BY rate DESC");
        return response()->json([
            'success' => true,
            'message' => 'Data Showw',
            'data' => $data,
        ]);
    }
    function listRumahMakanByIdUser(Request $request)
    {
        $data = DB::select("SELECT w.*, (SELECT (sum(r.rate) / COUNT(r.id)) from rate r WHERE r.id_rumah_makan=w.id) as rate FROM rumah_makan w where w.id_user = '$request->id_user' ORDER BY rate DESC");
        return response()->json([
            'success' => true,
            'message' => 'Data Showw',
            'data' => $data,
        ]);
    }
    function detailRumahMakan(Request $request)
    {
        $data = DB::select("SELECT w.*, (SELECT (sum(r.rate) / COUNT(r.id)) from rate r WHERE r.id_rumah_makan=w.id) as rate FROM rumah_makan w WHERE w.id = '$request->id_rumah_makan'");
        return response()->json([
            'success' => true,
            'message' => 'Data Showw',
            'data' => $data,
        ]);
    }
    function listCommentById(Request $request)
    {
        $data = DB::select("select c.*, u.name from comment c, users u where c.id_user=u.id and c.id_rumah_makan = '$request->id_rumah_makan'");
        return response()->json([
            'success' => true,
            'message' => 'Data Showw',
            'data' => $data,
        ]);
    }
    function addCommentById(Request $request)
    {
        $data = [
            'id_rumah_makan' => $request->id_rumah_makan,
            'comment' => $request->comment,
            'id_user' => $request->id_user,
            'created_at' => now(),
        ];
        DB::table('comment')->insert($data);
        return response()->json([
            'success' => true,
            'message' => 'Insert Data',
            'data' => $data,
        ]);
    }
    function rate(Request $request)
    {
        $cekRate = DB::table('rate')->where('id_user', $request->id_user)->where('id_rumah_makan', $request->id_rumah_makan)->first();
        if ($cekRate == null) {
            $data = [
                'id_user' => $request->id_user,
                'id_rumah_makan' => $request->id_rumah_makan,
                'rate' => $request->rate,
                'created_at' => now(),
            ];
            DB::table('rate')->insert($data);
            return response()->json([
                'success' => true,
                'message' => 'Insert Data',
                'data' => $data,
            ]);
        } else {
            $data = [
                'id_user' => $request->id_user,
                'id_rumah_makan' => $request->id_rumah_makan,
                'rate' => $request->rate,
                'created_at' => now(),
            ];
            DB::table('rate')->where('id_user', $request->id_user)->where('id_rumah_makan', $request->id_rumah_makan)->update($data);
            return response()->json([
                'success' => true,
                'message' => 'Update Data',
                'data' => $data,
            ]);
        }
    }
    function addRumahMakan(Request $request)
    {

        // $file_path = public_path() . '/storage/images/wisata/' . $request->image;
        // File::delete($file_path);
        // $image = $request->file('image');
        // $filename = $image->getClientOriginalName();
        // $image->move(public_path('storage/images/wisata/'), $filename);
        $data = [
            'id_user' => request()->user()->id,
            'name' => $request->name,
            'keterangan' => $request->keterangan,
            'description' => $request->description,
            // 'image1' => 'https://apirumahmakan.sppapp.com/storage/images/wisata/' . $filename . '',
            'tag' => $request->tag,
            'wilayah' => $request->wilayah,
            'created_at' => now(),
        ];

        DB::table('rumah_makan')->insert($data);
        return response()->json([
            'success' => true,
            'message' => 'Add Data',
            'data' => $data,
        ]);
    }
    function updateRumahMakan(Request $request)
    {
        if ($request->hasFile('image1')) {
            $file_path = public_path() . '/storage/images/wisata/' . $request->image1;
            File::delete($file_path);
            $image = $request->file('image1');
            $filename1 = $image->getClientOriginalName();
            $image->move(public_path('storage/images/wisata/'), $filename1);

            $data = [
                'name' => $request->name,
                'keterangan' => $request->keterangan,
                'description' => $request->description,
                'image1' => 'https://apirumahmakan.sppapp.com/storage/images/wisata/' . $filename1 . '',
                'tag' => $request->tag,
                'wilayah' => $request->wilayah,
                'updated_at' => now(),
            ];
        } else {
            $data = [
                'name' => $request->name,
                'keterangan' => $request->keterangan,
                'description' => $request->description,
                'tag' => $request->tag,
                'wilayah' => $request->wilayah,
                'updated_at' => now(),
            ];
        }
        DB::table('rumah_makan')->where('id', $request->id)->update($data);
        return response()->json([
            'success' => true,
            'message' => 'Update Data',
            'data' => $data,
        ]);
    }
    function deleteRumahMakan(Request $request)
    {
        $getImage = DB::table('rumah_makan')->where('id', $request->id)->first();
        $file_path1 = public_path() . '/storage/images/wisata/' . $getImage->image1;
        File::delete($file_path1);

        DB::table('rumah_makan')->where('id', $request->id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Delete Data'
        ]);
    }
    function deleteComment(Request $request)
    {
        DB::table('comment')->where('id', $request->id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Delete Data'
        ]);
    }
}
