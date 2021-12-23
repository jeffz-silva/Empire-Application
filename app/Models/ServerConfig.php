<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServerConfig extends Model
{
    use HasFactory;

    protected $table = "Server_Config";
    protected $connection = "server";

}
