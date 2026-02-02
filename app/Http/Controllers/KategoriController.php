<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\MasterItem;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        // Hitung total kategori
        $total_kategori = Kategori::count();

        // Kirim data ke view
        return view('master_items.kategori.index', [
            'total_kategori' => $total_kategori
        ]);

        // Atau menggunakan compact():
        // return view('master_items.kategori.index', compact('total_kategori'));
    }
    public function search(Request $request)
    {
        $nama = $request->nama;
        $kode = $request->kode;

        $data_search = Kategori::query();

        if (!empty($kode)) {
            $data_search = $data_search->where('kode', 'LIKE', '%' . $kode . '%');
        }
        if (!empty($nama)) {
            $data_search = $data_search->where('nama', 'LIKE', '%' . $nama . '%');
        }

        $data_search = $data_search->select('id', 'kode', 'nama')->orderBy('id')->get();

        return json_encode([
            'status' => 200,
            'data' => $data_search
        ]);
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            // Untuk kategori baru
            $kategori = new \stdClass();
            $kategori->id = 0;
            $kategori->kode = '';
            $kategori->nama = '';
        } else {
            // Untuk edit kategori
            $kategori = Kategori::find($id);
            if (!$kategori) {
                return redirect('kategori')->with('error', 'Kategori tidak ditemukan');
            }
        }

        $data = [
            'kategori' => $kategori,
            'method' => $method
        ];

        // Kembalikan view KATEGORI, bukan master_items
        return view('master_items.kategori.form', $data);
    }
    public function singleView($kode)
    {
        $kategori = Kategori::where('kode', $kode)->first();

        if (!$kategori) {
            if (request()->ajax()) {
                return response()->json(['error' => 'Kategori tidak ditemukan'], 404);
            }
            return redirect('kategori')->with('error', 'Kategori tidak ditemukan');
        }

        // Eager load masterItems untuk optimasi
        $kategori->load('masterItems');

        $data = [
            'data' => $kategori,
            'items' => $kategori->masterItems ?? collect()
        ];

        // Debug: cek jika data ada
        // dd($data);

        return view('master_items.kategori.single', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        $request->validate([
            'nama' => 'required',
            'master_items' => 'array'
        ]);

        if ($method == 'new') {
            $kategori = new Kategori;
            $kode = Kategori::count('id');
            $kode = $kode + 1;
            $kode = 'KAT-' . str_pad($kode, 5, '0', STR_PAD_LEFT);
        } else {
            $kategori = Kategori::find($id);
            $kode = $kategori->kode;
        }

        $kategori->nama = $request->nama;
        $kategori->kode = $kode;
        $kategori->save();

        // Sync relasi many-to-many
        if ($request->has('master_items')) {
            $kategori->masterItems()->sync($request->master_items);
        } else {
            $kategori->masterItems()->sync([]);
        }

        return redirect('kategori')->with('success', 'Data kategori berhasil disimpan');
    }

    public function delete($id)
    {
        $kategori = Kategori::find($id);
        $kategori->masterItems()->detach(); // Hapus relasi
        $kategori->delete();

        return redirect('kategori')->with('success', 'Data kategori berhasil dihapus');
    }

    // Tambahkan/hapus master item dari kategori via AJAX
    public function toggleItem(Request $request)
    {
        $kategori = Kategori::find($request->kategori_id);
        $item_id = $request->item_id;

        if ($kategori->masterItems()->where('master_item_id', $item_id)->exists()) {
            $kategori->masterItems()->detach($item_id);
            $status = 'removed';
        } else {
            $kategori->masterItems()->attach($item_id);
            $status = 'added';
        }

        return response()->json([
            'status' => 200,
            'action' => $status
        ]);
    }
}