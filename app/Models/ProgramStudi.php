<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramStudi extends Model
{
    protected $guarded = ['id'];

    public function dosen()
    {
        return $this->hasMany(
            Dosen::class,
            'program_studi_siakad_id',
            'program_studi_siakad_id'
        );
    }
}
