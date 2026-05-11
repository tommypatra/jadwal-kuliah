<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function programStudi()
    {
        return $this->belongsTo(
            ProgramStudi::class,
            'program_studi_siakad_id',
            'program_studi_siakad_id'
        );
    }
}
