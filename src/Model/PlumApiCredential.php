<?php

namespace Xoxoday\Plumapi\Model;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlumApiCredential extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['client_id','client_secret','refresh_token','access_token'];
}
