<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Produk as ModelProduk;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\Produk as ImporProduk;

class Produk extends Component
{
    use WithFileUploads;
    public $pilihanMenu = 'lihat';
    public $nama;
    public $kode;
    public $harga;
    public $stok;
    public $produkTerpilih;
    public $fileExcel;

    public function imporExcel(){
        Excel::import(new ImporProduk, $this->fileExcel);
        $this->reset();
    }

    public function pilihHapus($id){

        $this->produkTerpilih = ModelProduk::findOrFail($id);
        $this->pilihanMenu = 'hapus';
    }

    public function pilihEdit($id){

        $this->produkTerpilih = ModelProduk::findOrFail($id);
        $this->nama = $this->produkTerpilih->nama;
        $this->kode = $this->produkTerpilih->kode;
        $this->harga = $this->produkTerpilih->harga;
        $this->stok = $this->produkTerpilih->stok;
        //$this->stok = $this->penggunaTerpilih->stok;
        $this->pilihanMenu = 'edit';
    }


    public function simpan(){
        $this->validate([
            'nama' => 'required',
            'kode' => ['required', 'unique:produks,kode'],
            'harga' => 'required',
            'stok' => 'required'
        ],[
            'nama.required' => 'Nama harus diisi',
            'kode.required' => 'Kode harus diisi',
            'kode.kode' => 'Format harus kode',
            'kode.unique' => 'kode telah digunakan',
            'harga.required' => 'harga harus diisi',
            'stok.required' => 'stok harus diisi'
        ]);

        $simpan = new ModelProduk();
        $simpan->nama = $this->nama;
        $simpan->kode = $this->kode;
        $simpan->stok = $this->stok;
        $simpan->harga = $this->harga;
        $simpan->save();

        $this->reset(['nama', 'kode', 'harga', 'stok']);
        $this->pilihanMenu = 'lihat';

    }

    public function simpanEdit(){
        $this->validate([
            'nama' => 'required',
            'kode' => ['required', 'unique:produks,kode' .$this->produkTerpilih->id],
            'harga' => 'required',
            'stok' => 'required'
        ],[
            'nama.required' => 'Nama harus diisi',
            'kode.required' => 'Kode harus diisi',
            'kode.kode' => 'Format harus angka',
            'kode.unique' => 'kode telah digunakan',
            'harga.required' => 'harga harus diisi',
            'stok.required' => 'stok harus diisi'
        ]);

        $simpan = $this->produkTerpilih;
        $simpan->nama = $this->nama;
        $simpan->kode = $this->kode;       
        $simpan->stok = $this->stok;
        $simpan->harga = $this->harga;
        $simpan->save();

        $this->reset();
        $this->pilihanMenu = 'lihat';

    }
    
    public function hapus(){
        $this->produkTerpilih->delete();
        $this->reset();
    }

    public function batal(){
        $this->reset();
    }

    public function pilihMenu($menu){

        $this->pilihanMenu = $menu;

    }

    public function render()
    {
        return view('livewire.produk')->with([
            'semuaProduk' => ModelProduk::all()
        ]);
    }
}
