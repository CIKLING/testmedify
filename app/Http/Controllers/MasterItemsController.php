<?php

namespace App\Http\Controllers;

use App\Models\MasterItem;
use Illuminate\Http\Request;

class MasterItemsController extends Controller
{
    public function index()
    {
        return view('master_items.index.index');
    }

    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;
        $hargamin = $request->hargamin;
        $hargamax = $request->hargamax;

        $data_search = MasterItem::query();

        if (!empty($kode))
            $data_search = $data_search->where('kode', $kode);
        if (!empty($nama))
            $data_search = $data_search->where('nama', 'LIKE', '%' . $nama . '%');

        // FIX: Perbaikan filter harga
        if (!empty($hargamin) && !empty($hargamax)) {
            $data_search = $data_search->whereBetween('harga_beli', [$hargamin, $hargamax]);
        } elseif (!empty($hargamin)) {
            $data_search = $data_search->where('harga_beli', '>=', $hargamin);
        } elseif (!empty($hargamax)) {
            $data_search = $data_search->where('harga_beli', '<=', $hargamax);
        }

        $data_search = $data_search->select('kode', 'nama', 'jenis', 'harga_beli', 'laba', 'supplier')->orderBy('id')->get();

        return json_encode([
            'status' => 200,
            'data' => $data_search
        ]);
    }

    public function formView($method, $id = 0)
    {
        // Buat objek untuk new item
        if ($method == 'new') {
            $item = new \stdClass();
            $item->id = 0;
            $item->nama = '';
            $item->harga_beli = '';
            $item->laba = '';
            $item->supplier = '';
            $item->jenis = '';
            $item->foto = null;
            $item->kode = '';
            $item->kategoris = collect(); // Tambahkan ini
        } else {
            $item = MasterItem::with('kategoris')->find($id); // Eager load kategoris
            if (!$item) {
                abort(404, 'Data tidak ditemukan');
            }
        }

        // Ambil semua kategori dari database
        $kategoris = \App\Models\Kategori::all();

        // Ambil kategori yang sudah dipilih (jika edit mode)
        $selected_kategoris = [];
        if ($method == 'edit' && isset($item->id) && $item->id > 0) {
            $selected_kategoris = $item->kategoris->pluck('id')->toArray();
        }

        $data = [
            'item' => $item,
            'method' => $method,
            'kategoris' => $kategoris,
            'selected_kategoris' => $selected_kategoris
        ];

        return view('master_items.form.index', $data);
    }
    public function singleView($kode)
    {
        $data['data'] = MasterItem::where('kode', $kode)->first();
        return view('master_items.single.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        // dd($request->all());
        $request->validate([
            'nama' => 'required',
            'harga_beli' => 'required|numeric',
            'laba' => 'required|numeric',
            'supplier' => 'required',
            'jenis' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($method == 'new') {
            $data_item = new MasterItem;
            $kode = MasterItem::count('id');
            $kode = $kode + 1;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);
        } else {
            $data_item = MasterItem::find($id);
            if (!$data_item) {
                return redirect()->back()->with('error', 'Data tidak ditemukan');
            }
            $kode = $data_item->kode;
        }

        if ($request->hasFile('foto')) {
            if ($method == 'edit' && $data_item->foto && file_exists(public_path('uploads/items/' . $data_item->foto))) {
                unlink(public_path('uploads/items/' . $data_item->foto));
            }

            $foto = $request->file('foto');
            $foto_name = $kode . '_' . time() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('uploads/items'), $foto_name);
            $data_item->foto = $foto_name;
        }

        $data_item->nama = $request->nama;
        $data_item->harga_beli = $request->harga_beli;
        $data_item->laba = $request->laba;
        $data_item->kode = $kode;
        $data_item->supplier = $request->supplier;
        $data_item->jenis = $request->jenis;
        $data_item->save();

        if ($request->has('kategoris')) {
            $data_item->kategoris()->sync($request->kategoris);
        } else {

            $data_item->kategoris()->detach();
        }


        return redirect('master-items')->with('success', 'Data berhasil disimpan');
    }
    public function delete($id)
    {
        $item = MasterItem::find($id);

        if ($item->foto && file_exists(public_path('uploads/items/' . $item->foto))) {
            unlink(public_path('uploads/items/' . $item->foto));
        }

        $item->delete();
        return redirect('master-items')->with('success', 'Data berhasil dihapus');
    }

    public function updateRandomData()
    {
        $data = MasterItem::get();
        foreach ($data as $item) {
            $kode = $item->id;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);

            $item->harga_beli = rand(100, 1000000);
            $item->laba = rand(10, 99);
            $item->kode = $kode;
            $item->supplier = $this->getRandomSupplier();
            $item->jenis = $this->getRandomJenis();
            $item->save();
        }
    }

    private function getRandomSupplier()
    {
        $array = ['Tokopaedi', 'Bukulapuk', 'TokoBagas', 'E Commurz', 'Blublu'];
        $random = rand(0, 4);
        return $array[$random];
    }

    private function getRandomJenis()
    {
        $array = ['Obat', 'Alkes', 'Matkes', 'Umum', 'ATK'];
        $random = rand(0, 4);
        return $array[$random];
    }
}
