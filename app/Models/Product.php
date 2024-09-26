<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'status',
        'user_id', // Certifique-se de incluir o user_id se você está atribuindo
        'image_path', // Inclua se você estiver armazenando o caminho da imagem
    ];
}
