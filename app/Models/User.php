<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;


class User extends Authenticatable implements MustVerifyEmail
{
    // ! user dikasi hasfactory
    use Notifiable, SoftDeletes,HasFactory;

    protected $fillable = [
        'name',
        'full_name',
        'email',
        'password',
        'avatar',
        'company_id',
        'user_database',
        'user_status',
        'user_group_id',
        'user_type_id',
        'product_id',
        'salesman_id',
        'customer_id',
        'item_picture',
        'keep_status',
        'reseller_status',
        'change_price',
        'item_discount',
        'customer_status',
        'delivery_status',
        'receivable_status',
        'sales_order_status',
        'printer_address',
        'sync_status',
        'sync_date',
        'item_category_name',
        'item_stock',
        'branch_id',
        'branch_status',
        'resto_status',
        'kitchen_status',
        'division_id',
        'merchant_id',
        'warehouse_id',
        'user_level',
        'user_token',
        'log_stat',
        'avatar',
        'school_period_id',
        'school_period_semester',
        'teacher_id',
        'data_state',
        'created_id',
        'updated_id',
        'deleted_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getAvatarUrlAttribute()
    {
        return $this->avatar ? Storage::url($this->avatar) : 'path/default-avatar.jpeg'; // Berikan default jika tidak ada avatar
    }
}


