<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServerInfo extends Model
{
    use HasFactory;

    protected $table = "Server_List";
    protected $database = "server";
}
