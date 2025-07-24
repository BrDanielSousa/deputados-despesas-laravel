<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deputado extends Model
{
    protected $table = 'deputados';
    protected $keyType = 'int';
    protected $primaryKey = 'id';

    protected $fillable = [
        'api_id',
        'nome',
        'sigla_partido',
        'sigla_uf',
        'id_legislatura',
        'email',
        'uri',
        'uri_partido',
        'url_foto',
    ];


    public function despesas()
    {
        return $this->hasMany(Despesa::class, 'deputado_id', 'id');
    }
}
