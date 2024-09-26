<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Pelanggan extends Model
{
    use SoftDeletes;
    use HasFactory;

    // Menentukan kolom yang dapat diisi massal
    protected $fillable = ['NamaPelanggan', 'Alamat', 'NomorTelepon'];

    // Relasi untuk 'created_by'
   

    // Relasi untuk 'deleted_by'
   

    
}
