<?php

namespace App\Http\Controllers;

use App\Models\Posyandu;
use Illuminate\Http\Request;
//import
use App\Helpers\ApiFormatter;
use Exception;
use FFI\Exception as FFIExpection;
class PosyanduController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         //ambil data dari key search_nama bagian params nya postman
         $search = $request->search_nama;
         //ambil data dari key bagian params nya postman
         $limit = $request->limit;
         //cari data berdasarkan data yang di search
         $posyandus = Posyandu::where('nama', 'LIKE', '%'.$search. '%')->limit($limit)->get();
         //ambil semua data melalui model
         //$students = Student::all();
         if ($posyandus) {
             //kalau data berhasil diambil
             return ApiFormatter::createAPI(200, 'success', $posyandus);
         }else {
             //kalau data gagal diambil 
             return ApiFormatter::createAPI(400, 'failed');
         }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            //untuk mengvalidasi data
            $request->validate([
                'nama' => 'required|min:3',
                'JK' => 'required',
                'berat_badan' => 'required',
                'tanggal_berkunjung'=> 'required',
                'vaksin' => 'required',
                'nama_ibu' => 'required',
                'biaya' => 'required',
            ]);
            //ngirim data baru ke table students lewat model Student
            $posyandu = Posyandu::create([
                'nama' => $request->nama,
                'JK' => $request->JK,
                'berat_badan' => $request->berat_badan,
                'tanggal_berkunjung' => \Carbon\Carbon::parse($request->tanggal)->format('Y-m-d'),
                'vaksin' => $request->vaksin,
                'nama_ibu' => $request->nama_ibu,
                'biaya' => $request->biaya,
            ]);
            // cari data baru yang berhasil di simpen, cari berdasarkan id lewat data id dari $student yang di atas
            $hasilTambahData = Posyandu::where('id', $posyandu->id)->first();
            if ($hasilTambahData) {
                return ApiFormatter::createAPI(200, 'success', $posyandu);
            }else {
                return ApiFormatter::createAPI(400, 'failed');
            }
        }catch(Exception $error) { 
            //munculin deksripsi error yang bakal tampil di property data json
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        };
    }

    public function createToken()
    {
        return csrf_token();
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
         //coba baris kode didalam try
         try {
            // ambil data dari table students yang id nya sama kaya $id dari path route nya
            // where & find fungsi mencari, bedanya : where nyari berdasarkan column apa aja boleh, kalau find cuman bisa berdasarkan id nya
            $posyandu = Posyandu::find($id);
            if ($posyandu) {
                //kalau data berhasil diambil, tampilkan data dari $student nya dengan tanda status code 200
                return ApiFormatter::createAPI(200, 'success', $posyandu);
             }else {
                // kalau data gagal diambil/data gada, yang dikembaliin status code 400
                return ApiFormatter::createAPI(400, 'failed');
            }
        } catch (Exception $error) {
            // kalau pas try ada error, deskripsi nya ditampilin dengan status  code 400
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
    }
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Posyandu $posyandu)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            //untuk validasi inputan pada body postman
            $request->validate([
                'nama' => 'required|min:3',
                'JK' => 'required',
                'berat_badan' => 'required',
                'tanggal_berkunjung' => 'date',
                'vaksin' => 'required',
                'nama_ibu' => 'required',
                'biaya' => 'integer',
            ]);
            $student = Student::find($id);
            // update data yang telah diambil diatas
             $student->update([
                'nama' => $request->nama,
                'JK' => $request->JK,
                'berat_badan' => $request->berat_badan,
                'tanggal_berkunjung' =>  \Carbon\Carbon::parse($request->tanggal)->format('Y-m-d'),
                'vaksin'=> $request->vaksin,
                'nama_ibu' => $request->nama_ibu,
                'biaya' => $request->biaya,
            ]);
              // cari data yang berhasil diubah tadi, cari berdasarkan id dari $student yang ngambil data diawal
              $dataTerbaru = Posyandu::where('id', $posyandu->id)->first();
              if ($dataTerbaru) {
                  // jika update berhasil, tampilkan data dari $updatestudent diatas (data yang sudah berhasil diubah)
                  return ApiFormatter::createAPI(200, 'success', $dataTerbaru);
              } else {
                  return ApiFormatter::crateAPI(400, 'failed');
              }
          } catch (Exception $error) {
              // jika di baris kode try ada trouble, error dimunculkan dengan desc error nya dengan status code 400
              return ApiFormatter::createAPI(400, 'error', $error->getMessage());
          }
        }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            //ambil data yang mau dihapus
            $posyandu = Posyandu::find($id);
            // hapus data yang diambil diatas
            $cekBerhasil = $posyandu->delete();
            if ($cekBerhasil) {
                //kalau berhasil hapus, data yang dimunculi n teks konfirm dengan status code 200
                return ApiFormatter::createAPI(200, 'success', 'Data Terhapus');
            }else {
                return ApiFormatter::createAPI(400, 'failed');
            }
        } catch (Exception $error) {
            // kalau ada trouble di baris kode dalem try, error desc nya dimunculin
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }
    public function trash ()
    {
        try{
            //ambil data yg sudah dihpus smntra
            $posyandus= Posyandu::onlyTrashed()->get(); //hanya sampah, maggil data dri table student, data yg sudah dihapus
            if($posyandus){
                //kalau dta berhasil terambil, 
                return ApiFormatter::createAPI(200, 'success', $posyandus);
            }else {
                return ApiFormatter::createAPI(400, 'failed');
            }
        
        }catch(Exception $error){
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }
    public function restore($id)
    {
        try{
            //ambil data yg akan dihapus, diambil berdasarkan id dari route nya
            $posyandu=Posyandu::onlyTrashed()->where('id', $id);
            //kembalikan data
            $posyandu->restore();
            //ambil kembali data yg sudah di restore
            $dataKembali = Posyandu::where('id', $id)->first();
            if ($dataKembali) {
                return ApiFormatter::createAPI(200, 'success', $dataKembali);
            }else{
                return ApiFormatter::createAPI(400, 'failed');
            }
        }catch(Exception $error){
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }
    public function permanentDelete($id)
    {
        try {
            $posyandu = Posyandu::onlyTrashed()->where('id',$id);
            $proses =$posyandu->forceDelete();
            return ApiFormatter::createAPI(200, 'success', 'Berhasil hapus permanen!');
            

        }catch(Exception $error){
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }
}
